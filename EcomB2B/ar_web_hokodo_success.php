<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 27 Apr 2022 13:15:17 Central European Summer Time, Mijas costa, Spain
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

require_once 'keyring/dns.php';
require_once 'keyring/au_deploy_conf.php';

require '../vendor/autoload.php';
require_once 'utils/sentry.php';
require_once 'utils/object_functions.php';
require_once 'utils/general_functions.php';
require_once 'common_web_paying_functions.php';
require_once 'utils/placed_order_functions.php';
require_once 'utils/natural_language.php';


$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$account = get_object('Account', 1);

$smarty               = new Smarty();
$smarty->caching_type = 'redis';
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');


$order=get_object('Order',$_REQUEST['order_id']);
$store=get_object('Store',$order->get('Order Store Key'));


$website = get_object('Website', $store->get('Store Website Key'));

$customer=get_object('Customer',$order->get('Order Customer Key'));



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



$payment_account_key=$website->get_payment_account__key('hokodo');
$payment_account=get_object('Payment_Account',$payment_account_key);
//print_r($order);


$payment =get_object('Payment',$order->data['pending_hokodo_payment_id']);
$payment->fast_update(
    [
        'Payment Transaction Status' => 'Approving',
    ]
);




if($order->get('Order State')=='InBasket'){
    place_order($store, $order, $payment_account->id, $customer, $website, $editor, $smarty, $account, $db);

}






header('Location: thanks.sys?order_key='.$order->id.'&ts='.time());


