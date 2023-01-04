<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 16 Dec 2022 13:51:04 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */




if (empty($_REQUEST['id']) or !is_numeric($_REQUEST['id'])) {
    echo json_encode([
                         'status' => 'error',
                         'msg'    => 'invalid id'
                     ]);
    exit;
}


chdir('../');
/** @var string $dns_host */
/** @var string $dns_port */
/** @var string $dns_db */
/** @var string $dns_user */
/** @var string $dns_pwd */

require_once 'vendor/autoload.php';
require_once 'utils/sentry.php';
require_once 'keyring/dns.php';
require_once 'keyring/au_deploy_conf.php';
require_once 'keyring/key.php';
require_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once 'utils/object_functions.php';
require_once "class.Account.php";


$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);


if (!empty($_REQUEST['environment']) and $_REQUEST['environment']=='staging'  ) {

    $GLOBALS['skip_gearman'] =true;
    $dns_host=$dns_host_staging;
    $dns_db=$dns_db_staging;
}


$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$warehouse_key = '';

$sql = "SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse State`='Active' limit 1";

if ($result2 = $db->query($sql)) {
    if ($row2 = $result2->fetch()) {
        $warehouse_key = $row2['Warehouse Key'];
    }
}
$_SESSION['current_warehouse'] = $warehouse_key;


$account = new Account($db);
date_default_timezone_set($account->data['Account Timezone']);



require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';


$sql  = "select * from  pika_api_orders where id=? ";
$stmt = $db->prepare($sql);
$stmt->execute(
    [
        $_REQUEST['id']
    ]
);
if ($row = $stmt->fetch()) {

    if ($row['order_id']) {
        echo json_encode([
                             'status'    => 'ok',
                             'source_id' => $row['order_id'],
                             'msg'       => 'source_id found'
                         ]);
        exit;
    }


    $data = json_decode($row['data'], true);

    $customer = get_object('customer', $data['customer_key']);

    $editor = array(
        'Author Name'  => $customer->data['Customer Name'].' (via api)',
        'Author Alias' => $customer->data['Customer Name'].' (via api)',
        'Author Type'  => 'Customer',
        'Author Key'   => $customer->id,
        'User Key'     => 0,
        'Date'         => gmdate('Y-m-d H:i:s')
    );

    $customer->editor = $editor;

    $client = get_client($db, $customer, $data);

    $client->editor = $editor;

    $order = create_order($db, $client, $data);

    echo json_encode([
                         'status'          => 'ok',
                         'msg'             => 'order created',
                         'order_source_id' => $order->id
                     ]);
    //print_r($order);
} else {
    echo json_encode([
                         'status' => 'error',
                         'msg'    => 'id not found :('
                     ]);
}


function create_order($db, $client, $data)
{
    $order = $client->create_order();
    //$order = get_object('order', 2633056);

    $address = [
        'Address Recipient'            => $data['order']['contact_name'],
        'Address Organization'         => $data['order']['company_name'],
        'Address Line 1'               => $data['order']['delivery_address']['address_line_1'],
        'Address Line 2'               => $data['order']['delivery_address']['address_line_2'],
        'Address Sorting Code'         => $data['order']['delivery_address']['sorting_code'],
        'Address Postal Code'          => $data['order']['delivery_address']['postal_code'],
        'Address Dependent Locality'   => $data['order']['delivery_address']['dependant_locality'],
        'Address Locality'             => $data['order']['delivery_address']['locality'],
        'Address Administrative Area'  => $data['order']['delivery_address']['administrative_area'],
        'Address Country 2 Alpha Code' => $data['order']['delivery_address']['country_code']
    ];

    $order->skip_update_after_individual_transaction = true;


    $order->update_field_switcher('Order Delivery Address', json_encode($address), 'force');

    $order->fast_update([
                            'Order Original Data MIME Type'    => 'application/pika',
                            'Order Customer Purchase Order ID' => $data['order']['order_number'],
                        ]
    );

    foreach ($data['items'] as $item) {
        $data['item_historic_key']         = $item['source_id'];
        $data['qty']                       = $item['quantity'];
        $data['Current Dispatching State'] = 'In Process by Customer';
        $data['Current Payment State']     = 'Waiting Payment';
        $data['Metadata']                  = '';
        $order->update_item($data);
    }
    $order->update_totals();


    $order->update_state('InProcess');
    return $order;
}

function get_client($db, $customer, $data)
{

    if (!empty($data['client_key'])) {
        $client = get_object('customer client', $data['client_key']);
        if ($client->id and $customer->id = $client->get('Customer Client Customer Key')) {
            return $client;
        }
    }


    $sql  = "select `Customer Client Key` from `Customer Client Dimension` where `Customer Client Code`=? and `Customer Client Customer Key`=?  ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        [
            (string) $data['client']['code'],
            $customer->id

        ]
    );
    if ($row = $stmt->fetch()) {
        return get_object('customer client', $row['Customer Client Key']);
    }




    $client_data = [
        'Customer Client Code'                               => $data['client']['code'],
        'Customer Client Main Contact Name'                  => $data['order']['contact_name'],
        'Customer Client Company Name'                       => $data['order']['company_name'],
        'Customer Client Contact Address addressLine1'       => $data['order']['delivery_address']['address_line_1'].'caca',
        'Customer Client Contact Address addressLine2'       => $data['order']['delivery_address']['address_line_2'],
        'Customer Client Contact Address sortingCode'        => $data['order']['delivery_address']['sorting_code'],
        'Customer Client Contact Address postalCode'         => $data['order']['delivery_address']['postal_code'],
        'Customer Client Contact Address dependentLocality'  => $data['order']['delivery_address']['dependant_locality'],
        'Customer Client Contact Address locality'           => $data['order']['delivery_address']['locality'],
        'Customer Client Contact Address administrativeArea' => $data['order']['delivery_address']['administrative_area'],
        'Customer Client Contact Address country'            => $data['order']['delivery_address']['country_code'],


    ];


    $client = $customer->create_client($client_data, true);

    if (!empty($data['order']['email'])) {
        $client->update(
            [
                'Customer Client Main Plain Email' => $data['order']['email']
            ]
        );
    }

    if (!empty($data['order']['phone'])) {
        $client->update(
            [
                'Customer Client Main Plain Mobile' => $data['order']['phone']
            ]
        );
    }

    return $client;
}