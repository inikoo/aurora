<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 April 2018 at 11:16:52 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once '../vendor/autoload.php';
require 'keyring/dns.php';
require_once 'utils/sentry.php';
require 'keyring/key.php';
include_once __DIR__.'/utils/web_locale_functions.php';

session_start();


$logged_in = !empty($_SESSION['logged_in']);


if (!$logged_in) {

    if (empty($redirect_to_login)) {
        $response = array(
            'state' => 400,
            'resp'  => 'log out'
        );
        echo json_encode($response);
        exit;
    } else {
        header('Location: /login.sys?'.$redirect_to_login[0].'='.$redirect_to_login[1]);
        exit;
    }


}


if ($logged_in and (empty($_SESSION['customer_key']) or empty($_SESSION['website_user_key']) or empty($_SESSION['website_user_log_key']))) {
    $response = array(
        'state' => 400,
        'resp'  => 'wrong session'
    );
    echo json_encode($response);
    exit;
}


include_once 'utils/natural_language.php';
include_once 'utils/general_functions.php';
include_once 'utils/public_object_functions.php';


if (!isset($db)) {

    $db = new PDO(
        "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}


$customer = get_object('Customer', $_SESSION['customer_key']);

if (!$customer->id) {
    $response = array(
        'state' => 400,
        'resp'  => 'not customer'
    );
    echo json_encode($response);
    exit;
}


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

$website = get_object('Website', $_SESSION['website_key']);

if($website->get('Website Type')!='EcomDS'){
    $order_key = $customer->get_order_in_process_key();
    $order     = get_object('Order', $order_key);
    $order->editor=$editor;
}



if (!empty($_SESSION['website_locale'])) {
    $website_locale = $_SESSION['website_locale'];
} else {
    $_SESSION['website_locale'] = $website->get('Website Locale');
    $website_locale             = $website->get('Website Locale');
}
$locale = set_locate($website_locale);



require_once 'utils/ar_web_common.php';


