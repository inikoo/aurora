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

   // print "$url t:".AIKU_TOKEN."    \n";

    //return true;


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
            'Authorization: '.AIKU_TOKEN
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
    return true;
}


function getParameters($data): string
{
    $parameters = [
        'id' => $data['model_id'],
        'bg' => true
    ];

    if ($data['model'] == 'Order') {
        $parameters['with'] = 'transactions,payments';
    }
    if ($data['model'] == 'Invoice') {
        $parameters['with'] = 'transactions,payments';
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
            return strtolower($data['model']);
        case 'Staff':
            return 'order';
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
