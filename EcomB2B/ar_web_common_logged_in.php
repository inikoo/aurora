<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 April 2018 at 11:16:52 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'keyring/key.php';

session_start();


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$logged_in = !empty($_SESSION['logged_in']);


if (!$logged_in) {
    $response = array(
        'state' => 400,
        'resp'  => 'log out'
    );
    echo json_encode($response);
    exit;
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
    require_once 'keyring/dns.php';
    $db = new PDO(
        "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
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


$order_key = $customer->get_order_in_process_key();
$order = get_object('Order', $order_key);



require_once 'utils/ar_web_common.php';



?>