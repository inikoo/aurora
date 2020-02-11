<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  24 November 2019  16:45::36  +0100, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';
include_once __DIR__.'/utils/web_locale_functions.php';


$account        = get_object('Account', 1);
$website        = get_object('Website', $_SESSION['website_key']);
$current_locale = set_locate($website->get('Website Locale'));

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

    case 'get_client_basket_html':
        $data = prepare_values(
            $_REQUEST, array(
                         'client_key'    => array(
                             'type' => 'key',
                         ),
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        get_client_basket_html($data, $website, $customer->id, $editor);


        break;

    case 'get_client_order_items_html':
        $data = prepare_values(
            $_REQUEST, array(
                         'client_key'    => array(
                             'type' => 'key',
                         ),
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        get_client_order_items_html($data, $customer->id);


        break;


    case 'special_instructions':
        $data = prepare_values(
            $_REQUEST, array(
                         'value' => array('type' => 'string'),

                     )
        );
        update_special_instructions($data, $order, $editor);
        break;


}


function update_special_instructions($data, $order, $editor) {


    $order->editor = $editor;

    $order->fast_update(
        array('Order Customer Message' => $data['value'])
    );


    if ($order->get('Order State') == 'InBasket') {
        $order->fast_update(
            array(

                'Order Last Updated by Customer' => gmdate('Y-m-d H:i:s')
            )
        );
    }


    $response = array(
        'state' => 200,


    );
    echo json_encode($response);

}

function get_client_order_items_html($data, $customer_key) {

    $customer_client = get_object('Customer_Client', $data['client_key']);
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


    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');




    $website = get_object('Website', $_SESSION['website_key']);

    $theme = $website->get('Website Theme');

    $order = get_object('Order', $customer_client->get_order_in_process_key());


    $smarty->assign('edit', true);
    $smarty->assign('hide_title', true);
    $smarty->assign('items_data', $order->get_items());
    $smarty->assign('interactive_charges_data', $order->get_interactive_charges_data());

    // print_r( $order->get_interactive_deal_component_data());

    $smarty->assign('interactive_deal_component_data', $order->get_interactive_deal_component_data());


    $smarty->assign('order', $order);


    $response = array(
        'state' => 200,
        'empty' => false,
        'html'  => $smarty->fetch($theme.'/_order_items.'.$theme.'.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl')
    );

    echo json_encode($response);


}

/**
 * @param $data
 * @param $customer_key
 *
 * @throws \SmartyException
 */
function get_client_basket_html($data, $website, $customer_key, $editor) {

    $customer_client = get_object('Customer_Client', $data['client_key']);
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


    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');



    $theme = $website->get('Website Theme');
    $store = get_object('Store', $website->get('Website Store Key'));

    $order_key = $customer_client->get_order_in_process_key();



    $order = get_object('Order', $order_key);

    if($order->id){
        $order->fast_update(
            array(
                'Order Available Credit Amount' => $customer_client->get('Customer Account Balance')
            )
        );
    }else{


        $order=$customer_client->create_order();


        //$order->currency_code=$store->get('Store Currency Code');




    }






    $webpage = $website->get_webpage('client_basket.sys');

    $content = $webpage->get('Content Data');


    $block_found = false;
    $block_key   = false;

    foreach ($content['blocks'] as $_block_key => $_block) {
        if ($_block['type'] == 'client_basket') {
            $block       = $_block;
            $block_key   = $_block_key;
            $block_found = true;
            break;
        }
    }

    if (!$block_found) {
        $response = array(
            'state' => 200,
            'html'  => '',
            'msg'   => 'no basket in webpage'
        );
        echo json_encode($response);
        exit;
    }
    $smarty->assign('order', $order);
    $smarty->assign('customer_client', $customer_client);
    $smarty->assign('website', $website);
    $smarty->assign('store', $store);

    $smarty->assign('key', $block_key);
    $smarty->assign('data', $block);
    $smarty->assign('labels', $website->get('Localised Labels'));


    $response = array(
        'state' => 200,
        'empty' => false,
        'html'  => $smarty->fetch($theme.'/blk.client_basket.'.$theme.'.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),
        'client_nav' => [
            'label' => $customer_client->get('Customer Client Code'),
            'title' => htmlspecialchars($customer_client->get('Customer Client Name'))

        ],
        'order_nav' => [
            'label' => $order->get('Public ID'),
            'title' => htmlspecialchars($order->get('Public ID'))

        ]
        );


    echo json_encode($response);

}

