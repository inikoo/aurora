<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 April 2018 at 20:43:57 GMT+8, Mijas Costa, Spain
 Copyright (c) 2018, Inikoo

 Version 3

*/


use Aws\Ses\SesClient;

require_once 'utils/placed_order_functions.php';
require_once 'utils/aes.php';


require_once 'external_libs/Smarty/Smarty.class.php';


$smarty               = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir  = 'server_files/smarty/templates_c';
$smarty->cache_dir    = 'server_files/smarty/cache';
$smarty->config_dir   = 'server_files/smarty/configs';


if (!isset($_REQUEST['order_key'])) {
    exit;
}


require_once 'keyring/key.php';
include_once 'utils/public_object_functions.php';

include_once 'utils/natural_language.php';
include_once 'utils/general_functions.php';
include_once 'utils/detect_agent.php';


session_start();

include('utils/find_website_key.include.php');



if(isset($_REQUEST['cancel'])){
    $cancel=true;
}else{
    $cancel=false;
}

if (!isset($db)) {
    require 'keyring/dns.php';
    $db = new PDO(
        "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
$website = get_object('Website', $_SESSION['website_key']);


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
        $payment->update(array('Payment Transaction Status' => ( $cancel?'Cancelled':'Completed')));

    }

}

if($cancel){
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


send_order_confirmation_email($store, $website, $customer, $order, $smarty);

header('Location: thanks.sys?order_key='.$order->id);
exit;

?>