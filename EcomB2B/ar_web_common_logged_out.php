<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 April 2018 at 17:21:18 BST, Sheffield, UK
 Copyright (c) 2018, Inikoo

 Version 3

*/

include_once '../vendor/autoload.php';
require_once 'utils/sentry.php';
require_once 'keyring/dns.php';
require_once 'keyring/au_deploy_conf.php';
require_once 'keyring/key.php';
include_once 'utils/public_object_functions.php';

include_once 'utils/natural_language.php';
include_once 'utils/general_functions.php';
include_once 'utils/network_functions.php';
include_once __DIR__.'/utils/web_common.php';

$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);
session_start();
if (empty($_SESSION['website_key'])) {
    include_once('utils/find_website_key.include.php');
    $_SESSION['website_key']=get_website_key_from_domain($redis);
}

if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}



$logged_in =get_logged_in();

if (!isset($db) or is_null($db) ) {

    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
$website=get_object('Website',$_SESSION['website_key']);


if ($logged_in) {
    $response = array(
        'state' => 400,
        'resp'  => 'already logged in'
    );
    echo json_encode($response);
    exit;
}


$account=get_object('Account',1);
require_once 'utils/ar_web_common.php';


