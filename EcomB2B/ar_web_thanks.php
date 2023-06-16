<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 April 2018 at 21:33:30 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';
require_once 'utils/currency_functions.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$account = get_object('Account', 1);


$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'get_thanks_html':
        $data = prepare_values(
            $_REQUEST, array(
                'device_prefix'    => array(
                    'type'     => 'string',
                    'optional' => true
                ),
                'order_key'        => array(
                    'type'     => 'string',
                    'optional' => true
                ),
                'timestamp'        => array(
                    'type'     => 'string',
                    'optional' => true
                ),
                'timestamp_server' => array(
                    'type'     => 'string',
                    'optional' => true
                )
            )
        );

        get_thanks_html($data, $customer, $db, $account);


        break;
}

/**
 * @param $data
 * @param $customer \Public_Customer
 * @param $db \PDO
 * @param $account \Public_Account
 *
 * @throws \SmartyException
 */
function get_thanks_html($data, $customer, $db, $account)
{
    $template_suffix = $data['device_prefix'];

    $smarty               = new Smarty();
    $smarty->caching_type = 'redis';
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');

    /**
     * @var $order \Public_Order
     */
    $order = get_object('Order', $data['order_key']);

    if (!$order->id or $order->get('Order Customer Key') != $customer->id) {
        $response = array(
            'state' => 200,
            'html'  => '',
        );
        echo json_encode($response);
        exit;
    }


    $website = get_object('Website', $_SESSION['website_key']);
    $theme   = $website->get('Website Theme');

    $store = get_object('Store', $website->get('Website Store Key'));

    $webpage = $website->get_webpage('thanks.sys');

    $content = $webpage->get('Content Data');


    $block_found = false;
    $block_key   = false;
    foreach ($content['blocks'] as $_block_key => $_block) {
        if ($_block['type'] == 'thanks') {
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
            'msg'   => 'no thanks in webpage'
        );
        echo json_encode($response);
        exit;
    }


    $smarty->assign('placed_order', $order);
    $smarty->assign('customer', $customer);
    $smarty->assign('website', $website);
    $smarty->assign('store', $store);

    $smarty->assign('key', $block_key);

    $smarty->assign('labels', $website->get('Localised Labels'));

    $smarty->assign('logged_in', true);
    $smarty->assign('order_key', $order->id);

    $placed_order = $order;


    require_once 'utils/placed_order_functions.php';


    $smarty->assign('placed_order', $placed_order);


    $placeholders = array(
        '[Greetings]'     => $customer->get_greetings(),
        '[Customer Name]' => $customer->get('Name'),
        '[Name]'          => $customer->get('Customer Main Contact Name'),
        '[Name,Company]'  => preg_replace(
            '/^, /',
            '',
            $customer->get('Customer Main Contact Name').($customer->get('Customer Company Name') == '' ? '' : ', '.$customer->get('Customer Company Name'))
        ),
        '[Signature]'     => $webpage->get('Signature'),
        '[Order Number]'  => $order->get('Public ID'),
        '[Order Amount]'  => $order->get('To Pay'),
        '[Pay Info]'      => get_pay_info($order, $website, $smarty),
        '[Order]'         => $smarty->fetch($theme.'/placed_order.'.$theme.'.EcomB2B'.($template_suffix != '' ? '.'.$template_suffix : '').'.tpl'),
        '#order_number'   => $order->get('Public ID')


    );


    $block['text'] = strtr($block['text'], $placeholders);


    if ($template_suffix1 = '') {
        $block['text'] = str_replace('<br/>', '', $block['text']);
        $block['text'] = str_replace('<br>', '', $block['text']);
        $block['text'] = str_replace('<p></p>', '', $block['text']);
    }
    $smarty->assign('data', $block);


    $analytics_items = array();
    foreach ($items = $order->get_items() as $item) {
        $analytics_items[] = $item['analytics_data'];
    }

    $exchange = currency_conversion(
        $db,
        $store->get('Store Currency Code'),
        $account->get('Account Currency Code'),
        '- 1440 minutes'
    );

    $analytics_data = json_encode(array(
        'id'          => $order->get('Public ID'),
        'affiliation' => $store->get('Name'),
        'revenue'     => $order->get('Order Total Amount'),
        'gbp_revenue' => ceil($order->get('Order Total Amount') * $exchange),
        'tax'         => $order->get('Order Total Tax Amount'),
        'shipping'    => $order->get('Order Shipping Net Amount'),

    ));


    $tag_manager_analytic_data = json_encode(array(
        'id'              => $order->get('Public ID'),
        'shop'            => $store->get('Name'),
        'currency'        => $order->get('Order Currency'),
        'total_items_net' => $order->get('Order Items Net Amount'),
        'shipping'        => $order->get('Order Shipping Net Amount'),
        'total_net'       => $order->get('Order Total Net Amount'),
        'tax'             => $order->get('Order Total Tax Amount'),
        'total'           => $order->get('Order Total Amount'),

    ));

    $smarty->assign('analytics_items', $analytics_items);
    $smarty->assign('analytics_data', $analytics_data);
    $smarty->assign('tag_manager_analytic_data', $tag_manager_analytic_data);


    if (empty($data['timestamp']) or !is_numeric($data['timestamp'])) {
        $timestamp = 0;

        if (!empty($data['timestamp_server']) and is_numeric($data['timestamp_server']) and (time() - $data['timestamp_server'] < 300 and time() - $data['timestamp_server'] >= 0)) {
            $smarty->assign('skip_timestamp_check', 'Yes');
        }
    } else {
        $timestamp = $data['timestamp'];
    }
    $smarty->assign('timestamp', $timestamp);


    $smarty->assign('adwords_tag_manager_data', $website->get('Website Google Adwords Tag Manager Data'));

    $conversion = [
        'value'          => $order->get('Order Total Net Amount'),
        'currency'       => $order->get('Order Currency'),
        'transaction_id' => $order->get('Public ID'),
    ];
    $smarty->assign('adwords_conversion_data', $conversion);


    $response = array(
        'state' => 200,
        'html'  => $smarty->fetch('theme_1/blk.thanks.theme_1.EcomB2B'.($template_suffix != '' ? '.'.$template_suffix : '').'.tpl'),
    );


    echo json_encode($response);
}

