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


    case 'update_special_instructions':
        $data = prepare_values(
            $_REQUEST, array(
                         'value'     => array('type' => 'string'),
                         'order_key' => array(
                             'type' => 'key',
                         ),

                     )
        );
        update_special_instructions($data, $customer->id, $editor);
        break;
    case 'web_toggle_charge':
        $data = prepare_values(
            $_REQUEST, array(

                         'charge_key' => array('type' => 'key'),
                         'order_key'  => array('type' => 'key'),
                         'operation'  => array('type' => 'string'),

                     )
        );
        web_toggle_charge($data, $customer->id, $website, $editor);
        break;
    case 'get_charges_info':
        $data = prepare_values(
            $_REQUEST, array(

                         'order_key' => array('type' => 'key'),

                     )
        );
        get_charges_info($data, $customer->id);
        break;


    default:
        $response = array(
            'state' => 407,
            'resp'  => 'Non acceptable tipo'
        );
        echo json_encode($response);
        exit;


}

function get_charges_info($data, $customer_key) {

    /** @var Order $order */
    $order = get_object('Order', $data['order_key']);
    if (!$order->id) {
        $response = array(
            'state' => 400,
            'resp'  => 'Order not found'
        );
        echo json_encode($response);
        exit;
    }
    if ($order->get('Order Customer Key') != $customer_key) {
        $response = array(
            'state' => 400,
            'resp'  => 'Customer not found'
        );
        echo json_encode($response);
        exit;
    }


    $response = array(
        'state' => 200,
        'title' => _('Charges'),
        'text'  => $order->get_charges_public_info()
    );
    echo json_encode($response);

}

/**
 * @param $data
 * @param $customer_key
 * @param $editor
 *
 */
function update_special_instructions($data, $customer_key, $editor) {

    $order = get_object('Order', $data['order_key']);
    if (!$order->id) {
        $response = array(
            'state' => 400,
            'resp'  => 'Order not found'
        );
        echo json_encode($response);
        exit;
    }
    if ($order->get('Order Customer Key') != $customer_key) {
        $response = array(
            'state' => 400,
            'resp'  => 'Customer not found'
        );
        echo json_encode($response);
        exit;
    }

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


    $smarty               = new Smarty();
    $smarty->caching_type = 'redis';
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
    $smarty->assign('client_key', $customer_client->id);


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


    $smarty               = new Smarty();
    $smarty->caching_type = 'redis';
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');


    $theme = $website->get('Website Theme');
    $store = get_object('Store', $website->get('Website Store Key'));

    $order_key = $customer_client->get_order_in_process_key();


    $order = get_object('Order', $order_key);


    if ($order->id) {
        $order->fast_update(
            array(
                'Order Available Credit Amount' => $customer_client->get('Customer Account Balance')
            )
        );
    } else {
        $order = $customer_client->create_order();
        $order->model_updated('_new',$order->id);
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
        'state'      => 200,
        'empty'      => false,
        'html'       => $smarty->fetch($theme.'/blk.client_basket.'.$theme.'.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),
        'client_nav' => [
            'label' => '<a href="/client.sys?id='.$customer_client->id.'">'.($customer_client->get('Customer Client Code') == '' ? '<span class="italic">'.sprintf('%05d', $customer_client->id).'</span>' : $customer_client->get('Customer Client Code')).'</a>',
            'title' => htmlspecialchars($customer_client->get('Customer Client Name'))

        ],
        'order_nav'  => [
            'label' => $order->get('Public ID'),
            'title' => htmlspecialchars($order->get('Public ID'))

        ]
    );


    echo json_encode($response);

}


function web_toggle_charge($data, $customer_key, $website, $editor) {


    $order = get_object('Order', $data['order_key']);
    if (!$order->id) {
        $response = array(
            'state' => 400,
            'resp'  => 'Order not found'
        );
        echo json_encode($response);
        exit;
    }
    if ($order->get('Order Customer Key') != $customer_key) {
        $response = array(
            'state' => 400,
            'resp'  => 'Customer not found'
        );
        echo json_encode($response);
        exit;
    }

    $order->editor = $editor;

    $charge = get_object('Charge', $data['charge_key']);
    if (!$charge->id) {
        $response = array(
            'state' => 400,
            'msg'   => 'Charge not found',
        );

        echo json_encode($response);
        exit;
    }

    if ($charge->get('Store Key') != $order->get('Store Key')) {
        $response = array(
            'state' => 400,
            'msg'   => 'Charge not in same store as order',
        );

        echo json_encode($response);
        exit;
    }


    if ($data['operation'] == 'add_charge') {

        $transaction_data = $order->add_charge($charge);


    } else {
        $transaction_data = $order->remove_charge($charge);


    }


    if ($order->get('Order State') == 'InBasket') {
        $order->fast_update(
            array(

                'Order Last Updated by Customer' => gmdate('Y-m-d H:i:s')
            )
        );
    }


    $new_discounted_products = $order->get_discounted_products();
    foreach ($new_discounted_products as $key => $value) {
        $discounted_products[$key] = $value;
    }


    $hide         = array();
    $show         = array();
    $add_class    = array();
    $remove_class = array();

    $labels = $website->get('Localised Labels');

    if ($order->get('Shipping Net Amount') == 'TBC') {
        $shipping_amount = sprintf('<i class="fa error fa-exclamation-circle" title="" aria-hidden="true"></i> <small>%s</small>', (!empty($labels['_we_will_contact_you']) ? $labels['_we_will_contact_you'] : _('We will contact you')));
    } else {
        $shipping_amount = $order->get('Shipping Net Amount');
    }

    if ($order->get('Order Charges Net Amount') == 0) {

        $add_class['order_charges_container'] = 'very_discreet';

        $hide[] = 'order_charges_info';
    } else {
        $remove_class['order_charges_container'] = 'very_discreet';

        $show[] = 'order_charges_info';
    }


    if ($order->get('Order Items Discount Amount') == 0) {

        $hide[] = 'order_items_gross_container';
        $hide[] = 'order_items_discount_container';
    } else {
        $show[] = 'order_items_gross_container';
        $show[] = 'order_items_discount_container';
    }


    if ($order->get('Order Deal Amount Off') == 0) {
        $hide[] = 'Deal_Amount_Off_tr';
    } else {
        $show[] = 'Deal_Amount_Off_tr';
    }


    $class_html = array(
        'Deal_Amount_Off'         => $order->get('Deal Amount Off'),
        'order_items_gross'       => $order->get('Items Gross Amount'),
        'order_items_discount'    => $order->get('Basket Items Discount Amount'),
        'order_items_net'         => $order->get('Items Net Amount'),
        'order_net'               => $order->get('Total Net Amount'),
        'order_tax'               => $order->get('Total Tax Amount'),
        'order_charges'           => $order->get('Charges Net Amount'),
        'order_credits'           => $order->get('Net Credited Amount'),
        'available_credit_amount' => $order->get('Available Credit Amount'),
        'order_shipping'          => $shipping_amount,
        'order_total'             => $order->get('Total Amount'),
        'to_pay_amount'           => $order->get('Basket To Pay Amount'),
        'ordered_products_number' => $order->get('Products'),
        'order_amount'            => ((!empty($website->settings['Info Bar Basket Amount Type']) and $website->settings['Info Bar Basket Amount Type'] == 'items_net') ? $order->get('Items Net Amount') : $order->get('Total'))
    );


    $response = array(
        'state' => 200,


        'metadata' => array(
            'class_html'   => $class_html,
            'hide'         => $hide,
            'show'         => $show,
            'add_class'    => $add_class,
            'remove_class' => $remove_class,
            'new_otfs'     => $order->new_otfs,
            'deleted_otfs' => $order->deleted_otfs,

        ),


        'discounts' => ($order->data['Order Items Discount Amount'] != 0 ? true : false),
        'charges'   => ($order->data['Order Charges Net Amount'] != 0 ? true : false),

        'order_empty' => ($order->get('Products') == 0 ? true : false),

        'operation'        => $data['operation'],
        'transaction_data' => $transaction_data

    );

    echo json_encode($response);


}
