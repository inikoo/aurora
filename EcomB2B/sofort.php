<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 April 2018 at 20:43:57 GMT+8, Mijas Costa, Spain
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once '../vendor/autoload.php';
require_once 'utils/sentry.php';
require_once 'utils/placed_order_functions.php';
require_once 'utils/aes.php';

$smarty = new Smarty();
$smarty->caching_type = 'redis';
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');

if (!isset($_REQUEST['order_key'])) {
    exit;
}


require_once 'keyring/key.php';
require_once 'keyring/dns.php';
require_once 'keyring/au_deploy_conf.php';

include_once 'utils/public_object_functions.php';

include_once 'utils/natural_language.php';
include_once 'utils/general_functions.php';
include_once 'utils/network_functions.php';


$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);
session_start();
if (empty($_SESSION['website_key'])) {
    include_once('utils/find_website_key.include.php');
    $_SESSION['website_key']=get_website_key_from_domain($redis);
}

if (isset($_REQUEST['cancel'])) {
    $cancel = true;
} else {
    $cancel = false;
}

if (!isset($db) or is_null($db) ) {
    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
$website = get_object('Website', $_SESSION['website_key']);
$account = get_object('Account', 1);


$order = get_object('Order', $_REQUEST['order_key']);

$customer = get_object('Customer', $order->get('Order Customer Key'));
$store    = get_object('Store', $order->get('Order Store Key'));

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


$customer->editor = $editor;
$order->editor    = $editor;
$store->editor    = $editor;

foreach ($order->get_payments('objects') as $payment) {
    if ($payment->get('Payment Metadata') == $_REQUEST['conf'] and $payment->get('Payment Transaction ID') == $_REQUEST['tx']) {
        $payment->editor = $editor;
        $payment->update(array('Payment Transaction Status' => ($cancel ? 'Cancelled' : 'Completed')));

    }

}

if ($cancel) {
    header('Location: checkout.sys');
    exit;

}

$order->get_data('id', $order->id);
$customer = get_object('Customer', $order->get('Order Customer Key'));

$customer->editor = $editor;


$order->update(
    array(
        'Order State' => 'InProcess'
    ), 'no_history'
);


$email_template_type      = get_object('Email_Template_Type', 'Order Confirmation|'.$website->get('Website Store Key'), 'code_store');
$email_template           = get_object('email_template', $email_template_type->get('Email Campaign Type Email Template Key'));
$published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


$send_data = array(
    'Email_Template_Type' => $email_template_type,
    'Email_Template'      => $email_template,
    'Order'               => $order,
    'Order Info'          => get_pay_info($order, $website, $smarty),
    'Pay Info'            => get_order_info($order)

);


$published_email_template->send($customer, $send_data);


setcookie('au_pu_'.$order->id, $order->id, time() + 300, "/");
header('Location: thanks.sys?order_key='.$order->id.'&ts='.time());
exit;

