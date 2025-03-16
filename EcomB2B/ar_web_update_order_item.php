<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 July 2017 at 09:38:41 CEST, Trnava, Slavakia
 Moved here:  22 November 2019  23:23::07  +0100, Mijas costa Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';
include_once 'utils/web_locale_functions.php';

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
    case 'update_order_item':
        $data = prepare_values(
            $_REQUEST, array(
                         'product_id'        => array('type' => 'key'),
                         'qty'               => array('type' => 'string'),
                         'webpage_key'       => array('type' => 'numeric'),
                         'page_section_type' => array('type' => 'string')
                     )
        );

        update_order_item($data, $website, $customer, $order, $editor, $db);
        break;
}

/**
 * @param $_data
 * @param $customer \Public_Customer
 * @param $website  \Public_Website
 * @param $order    \Public_Order
 * @param $editor
 * @param $db       \PDO
 */
function update_order_item($_data, $website, $customer, $order, $editor, $db) {
    include_once __DIR__.'/utils/update_order.item.php';


    $customer->editor = $editor;


    if (!$order->id) {

        $order = create_order($editor, $customer);

        $order->fast_update(array('Order Website Key' => $website->id));
        $_SESSION['order_key'] = $order->id;

    }

    $order->fast_update(array('Order Source Key' => 1));
    $order->model_updated('_new',$order->id);

    $response=process_update_order_item($db,$order,$_data['product_id'],$_data['qty'],$website,$_data['webpage_key'],$_data['page_section_type']);




    echo json_encode($response);

}

function create_order($editor, $customer) {


    $order_data = array(
        'editor' => $editor
    );


    $order = $customer->create_order($order_data);


    return $order;
}