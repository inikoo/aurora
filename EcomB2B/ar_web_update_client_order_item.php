<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  24 November 2019  19:30::59  +0100, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';
include_once __DIR__.'/utils/web_set_locale.php';

$website = get_object('Website', $_SESSION['website_key']);

if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'update_client_order_item':
        $data = prepare_values(
            $_REQUEST, array(
                         'product_id'        => array('type' => 'key'),
                         'client_key'        => array('type' => 'key'),
                         'qty'               => array('type' => 'string'),
                         'webpage_key'       => array('type' => 'numeric'),
                         'page_section_type' => array('type' => 'string')
                     )
        );

        update_client_order_item($data, $website, $customer->id, $editor, $db);
        break;
}

/**
 * @param $_data
 * @param $website
 * @param $customer_key
 * @param $editor
 * @param $db \PDO
 */
function update_client_order_item($_data, $website, $customer_key, $editor, $db) {

    include_once __DIR__.'/utils/update_order.item.php';

    $customer_client = get_object('Customer_Client', $_data['client_key']);
    if (!$customer_client->id) {
        $response = array(
            'state' => 400,
            'resp'  => 'Customer not found'
        );
        echo json_encode($response);
        exit;
    }
    if ($customer_client->get('Customer Client Customer Key') != $customer_key) {
        $response = array(
            'state' => 400,
            'resp'  => 'Customer not found'
        );
        echo json_encode($response);
        exit;
    }



    $customer_client->editor = $editor;
    $order_key = $customer_client->get_order_in_process_key();


    if (!$order_key) {

        $order_data = array(
            'editor' => $editor
        );

        $order = $customer_client->create_order($order_data);
        $order->fast_update(array('Order Website Key' => $website->id));

    }else{
        $order=get_object('Order',$order_key);
    }


    $response=process_update_order_item($db,$order,$_data['product_id'],$_data['qty'],$website,$_data['webpage_key'],$_data['page_section_type']);
    echo json_encode($response);

}
