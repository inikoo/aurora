<?php

/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 28 Apr 2022 11:29:23 Central European Summer Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */
include_once 'ar_web_common_logged_in.php';
require_once 'utils/sentry.php';
require_once 'utils/object_functions.php';
require_once 'utils/general_functions.php';
require_once 'common_web_paying_functions.php';
require_once 'utils/placed_order_functions.php';
require_once 'utils/natural_language.php';


/** @var PDO $db */
/** @var Order $order */

if(empty($_REQUEST['order_id']) or empty($_REQUEST['hokodo_order_id'])){
    echo json_encode(
        [
            'status' => 'error',
        ]
    );
    exit;
}


if($order->id and $order->id==$_REQUEST['order_id']) {
    $sql = "update `Order Dimension` set `hokodo_order_id`=?  where `Order Key`=? ";

    $db->prepare($sql)->execute(
        [
            $_REQUEST['hokodo_order_id'],
            $order->id
        ]
    );

    $customer = get_object('Customer', $order->get('Order Customer Key'));
    $store    = get_object('Store', $order->get('Order Store Key'));
    $website  = get_object('Website', $store->get('Store Website Key'));

    $amount = $order->get('Order Total Amount');
    $date   = gmdate('Y-m-d H:i:s');

    $editor = array(
        'Author Name'  => $customer->get('Name'),
        'Author Alias' => $customer->get('Name'),
        'Author Type'  => 'Customer',
        'Author Key'   => $customer->id,
        'Subject'      => 'Customer',
        'Subject Key'  => $customer->id,
        'User Key'     => 0,
        'Date'         => gmdate('Y-m-d H:i:s')
    );

    $payment_data = array(
        'Payment Store Key'          => $order->get('Order Store Key'),
        'Payment Website Key'        => $website->id,
        'Payment Customer Key'       => $customer->id,
        'Payment Transaction Amount' => $amount,
        'Payment Currency Code'      => $order->get('Order Currency'),
        'Payment Created Date'       => $date,
        'Payment Last Updated Date'  => $date,
        'Payment Transaction Status' => 'Approving',
        'Payment Transaction ID'     => 'TBC',
        'Payment Method'             => 'Ecredit',
        'Payment Location'           => 'Basket',
    );


    $payment_account_key = $website->get_payment_account__key('hokodo');

    $payment_account         = get_object('Payment_Account', $payment_account_key);
    $payment_account->editor = $editor;


    $payment = $payment_account->create_payment($payment_data);
    $order->add_payment($payment);

    $sql = "update `Order Dimension` set `pending_hokodo_payment_id`=?  where `Order Key`=? ";

    $db->prepare($sql)->execute(
        [
            $payment->id,
            $order->id
        ]
    );


    $account = get_object('Account', 1);

    $smarty               = new Smarty();
    $smarty->caching_type = 'redis';
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');

    if ($order->get('Order State') == 'InBasket') {
        place_order($store, $order, $payment_account->id, $customer, $website, $editor, $smarty, $account, $db);
    }


    echo json_encode(
        [
            'status' => 'ok',
            'url'    => 'thanks.sys?order_key='.$order->id.'&ts='.time()
        ]
    );
    exit;
}

echo json_encode(
    [
        'status' => 'error',
    ]
);