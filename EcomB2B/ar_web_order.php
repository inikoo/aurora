<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 August 2018 at 23:33:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];


//print_r($_REQUEST);

switch ($tipo) {


    case 'get_order_html':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key' => array(
                             'type' => 'kry',
                         ),

                         'device_prefix' => array(
                             'type' => 'string',

                         )
                     )
        );

        get_order_html($data, $customer, $db);


        break;


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function get_order_html($data, $customer, $db) {


    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');

    $order = get_object('Order', $data['order_key']);

    if (!$order->id) {
        $response = array(
            'state' => 200,
            'html'  => _('Order not found')
        );
        echo json_encode($response);
    }

    if ($customer->id != $order->get('Order Customer Key')) {
        $response = array(
            'state' => 200,
            'html'  => _('Wrong order id')
        );
        echo json_encode($response);
    }

    $website = get_object('Website', $_SESSION['website_key']);
    $store   = get_object('Store', $website->get('Website Store Key'));


    $smarty->assign('order', $order);
    $smarty->assign('customer', $customer);
    $smarty->assign('website', $website);
    $smarty->assign('store', $store);

    $smarty->assign('labels', $website->get('Localised Labels'));
    $smarty->assign('logged_in', true);

    $smarty->assign('items_data', $order->get_items());



    $response = array(
        'state' => 200,
        'html'  => $smarty->fetch('theme_1/blk.order.theme_1.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),
    );


    echo json_encode($response);

}


