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


$customer = get_object('Customer', $order->get('Order Customer Key'));
$store    = get_object('Store', $order->get('Order Store Key'));
$website  = get_object('Website', $store->get('Store Website Key'));

$payment_account_key = $website->get_payment_account__key('Paypal');
$payment_account     = get_object('Payment_Account', $payment_account_key);


$data = $_REQUEST;

$date = gmdate('Y-m-d H:i:s', strtotime($data['create_time']));

$payment_data = array(
    'Payment Store Key'          => $order->get('Order Store Key'),
    'Payment Website Key'        => $website->id,
    'Payment Customer Key'       => $customer->id,
    'Payment Transaction Amount' => $data['purchase_units'][0]['amount']['value'],
    'Payment Currency Code'      => $data['purchase_units'][0]['amount']['currency_code'],

    'Payment Completed Date'      => $date,
    'Payment Last Completed Date' => $date,
    'Payment Last Updated Date'   => $date,
    'Payment Transaction ID'      => $data['id'],
    'Payment Method'              => 'Paypal',
    'Payment Location'            => 'Basket',
    'Payment Metadata'            => json_encode($data),
    'Payment Transaction Status'      => 'Completed',
);

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

$payment_account->editor = $editor;
$payment = $payment_account->create_payment($payment_data);
$order->add_payment($payment);

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
        'order_key'=>$order->id
    ]
);