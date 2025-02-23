<?php


error_reporting(E_ALL ^ E_DEPRECATED);

require_once 'vendor/autoload.php';
require_once "class.Account.php";
require_once 'fork.common.php';

include_once 'utils/object_functions.php';

include 'utils/aes.php';
include 'utils/general_functions.php';
include 'utils/system_functions.php';
include 'utils/natural_language.php';
include 'slow_low_priority.fork.php';

include 'asset_sales.fork.php';

include_once 'utils/object_functions.php';
include_once 'utils/new_fork.php';


include_once 'keyring/au_deploy_conf.php';
$servers = explode(",", GEARMAN_SERVERS_FOR_WORKERS);
shuffle($servers);
$servers = implode(",", $servers);

$worker = new GearmanWorker();
$worker->addServers($servers);

$worker->addFunction("au_aiku", "fork_aiku_fetch");


$db      = false;
$account = false;

while ($worker->work()) {
    if ($worker->returnCode() == GEARMAN_SUCCESS) {
        $db = null;
        exec("kill -9 ".getmypid());
        die();
    }
}


function fork_aiku_fetch($job): bool
{
    if (!$_data = get_fork_metadata($job)) {
        return true;
    }

    if (!defined('AIKU_API_URL') || !defined('AIKU_TOKEN')) {
        return true;
    }


    $account                = $_data[0];
    $aiku_organisation_slug = getAikuOrganisation($account->get('Account Code'));

    $fetchData = $_data[2];


    $path = getPath($fetchData);
    if (is_null($path)) {
        print "Invalid model ".$fetchData['model']."  \n";

        return true;
    }
    $url = AIKU_API_URL.$aiku_organisation_slug.'/'.$path.'?'.getParameters($fetchData);


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_HTTPHEADER     => array(
            'Accept: application/json',
            'Authorization: Bearer '.AIKU_TOKEN
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $res = json_decode($response, true);

    if ($res and isset($res['class']) and !in_array($res['class'], [
            'FetchAuroraEmailTrackingEvents',
            'FetchAuroraOrders'
        ])) {
        print_r($res);
    }

    return true;
}


function getParameters($data): string
{
    $parameters = [
        'id' => $data['model_id'],
        'bg' => false
    ];

    if ($data['model'] == 'Order') {
        $parameters['with'] = 'transactions,payments';
    }
    if ($data['model'] == 'Invoice') {
        $parameters['with'] = 'transactions,payments';
    }
    if ($data['model'] == 'DispatchedEmailWithFull') {
        $parameters['with'] = 'full';
    }

    if ($data['model'] == 'SupplierDelivery') {
        $parameters['with'] = 'transactions';
    }

    if ($data['model'] == 'DeliveryNote') {
        $parameters['with'] = 'transactions';
    }

    if ($data['model'] == 'PurchaseOrder') {
        $parameters['with'] = 'transactions';
    }

    if ($data['model'] == 'EmailTrackingEvent') {
        $parameters['delay'] = 5;
    }

    if ($data['model'] == 'SupplierPart') {
        $parameters['delay'] = 120;
        $parameters['bg']    = true;
    }

    if ($data['model'] == 'DeleteFavourite') {
        $parameters['unfavourited_at']    = $data['unfavourited_at'];
    }


    return http_build_query($parameters);
}

function getPath($data): ?string
{
    switch ($data['model']) {
        case 'CustomerClient':
            return 'customer-client';
        case 'Order':
        case 'Invoice':
        case 'Customer':
        case 'Stock':
        case 'Product':
        case 'Payment':
        case 'Supplier':
        case 'Timesheet':
        case 'Favourite':
            return strtolower($data['model']);
        case 'DeliveryNote':
            return 'delivery-note';
        case 'DeleteFavourite':
            return 'delete-favourite';
        case 'Staff':
            return 'employee';
        case 'DispatchedEmailWithFull':
        case 'DispatchedEmail':
            return 'dispatched-email';
        case 'EmailTrackingEvent':
            return 'email-tracking-event';
        case 'OrgStockMovement':
            return 'org-stock-movement';
        case 'SupplierDelivery':
            return 'stock-delivery';
        case 'PurchaseOrder':
            return 'purchase-order';
        case 'SupplierPart':
            return 'supplier-product';
        default:
            return null;
    }
}


function getAikuOrganisation($account_code): string
{
    switch ($account_code) {
        case 'AWEU':
            return 'sk';
        default:
            return strtolower($account_code);
    }
}
