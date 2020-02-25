<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  12:12 pm Monday, 24 February 2020 (MYT) , Kuala Lumpur, Malaysia
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

    case 'get_client_order_html':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key'    => array(
                             'type' => 'key',
                         ),
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        get_client_order_html($data, $website, $customer, $editor);


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

    default:
        $response = array(
            'state' => 407,
            'resp'  => 'Non acceptable tipo'
        );
        echo json_encode($response);
        exit;


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
 * @param $website \Public_Website
 * @param $customer \Public_Customer
 * @param $editor

 *
 * @throws \SmartyException
 */
function get_client_order_html($data, $website, $customer, $editor) {

    $order = get_object('Order', $data['order_key']);
    if (!$order->id) {
        $response = array(
            'state' => 400,
            'resp'  => 'Order not found'
        );
        echo json_encode($response);
        exit;
    }
    if ($order->get('Order Customer Key') != $customer->id) {
        $response = array(
            'state' => 400,
            'resp'  => 'Wrong order ID'
        );
        echo json_encode($response);
        exit;
    }


    $order->editor = $editor;


    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');


    $theme = $website->get('Website Theme');
    $store = get_object('Store', $website->get('Website Store Key'));
    $customer_client = get_object('Customer_Client', $order->get('Order Customer Client Key'));

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
    $smarty->assign('key', $block_key);
    $smarty->assign('data', $block);

    $smarty->assign('order', $order);
    $smarty->assign('customer', $customer);

    $smarty->assign('customer_client', $customer_client);
    $smarty->assign('website', $website);
    $smarty->assign('store', $store);


    $smarty->assign('labels', $website->get('Localised Labels'));

    $response = array(
        'state'      => 200,
        'empty'      => false,
        'html'       => $smarty->fetch($theme.'/blk.client_order.'.$theme.'.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),
        'client_nav' => [
            'label' => '<a href="/client.sys?id='.$customer_client->id.'">'.($customer_client->get('Customer Client Code')==''?'<span class="italic">'.sprintf('%05d',$customer_client->id).'</span>':$customer_client->get('Customer Client Code')).'</a>',
            'title' => htmlspecialchars($customer_client->get('Customer Client Name'))

        ],
        'order_nav'  => [
            'label' => $order->get('Public ID'),
            'title' => htmlspecialchars($order->get('Public ID'))

        ]
    );


    echo json_encode($response);

}

