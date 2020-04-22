<?php
/*

 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/
error_reporting(E_ALL);


require_once 'vendor/autoload.php';

include_once 'utils/object_functions.php';


include_once 'keyring/dns.php';
include_once 'keyring/key.php';

include_once 'class.Account.php';
$smarty = new Smarty();
$smarty->caching_type = 'redis';

$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');

date_default_timezone_set('UTC');


$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


include_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once 'utils/network_functions.php';
require_once "utils/aes.php";


setlocale(LC_MONETARY, 'en_GB.UTF-8');

$account = get_object('Account', '');
include_once 'class.Auth.php';
session_start();


$auth   = new Auth(IKEY, SKEY);
$handle = (array_key_exists('login__username', $_REQUEST)) ? $_REQUEST['login__username'] : false;
$sk     = (array_key_exists('token', $_REQUEST)) ? base64_decode($_REQUEST['token']) : false;


if (!$sk and array_key_exists('mk', $_REQUEST)) {
    $auth->authenticate_from_inikoo_masterkey($_REQUEST['mk']);
} elseif ($handle) {
    $auth->authenticate($handle, $sk, 'system', 0);
}


if ($auth->is_authenticated()) {

    $_user_key = $auth->get_user_key();
    $user      = get_object('User', $_user_key);

    $_SESSION['logged_in']      = true;
    $_SESSION['logged_in_page'] = 0;
    $_SESSION['user_key']       = $_user_key;
    $_SESSION['text_locale']    = $user->get('User Preferred Locale');


    include_once 'utils/timezones.php';
    switch ($user->settings('Timezone')) {
        case 'Account':
            date_default_timezone_set($account->get('Account Timezone'));
            break;
        case 'Local':
            if (!date_default_timezone_set($_REQUEST['timezone'])) {
                date_default_timezone_set($account->get('Account Timezone'));

            }
            break;
        default:
            break;

    }


    $_SESSION['timezone']             = date_default_timezone_get();
    $_SESSION['local_timezone']       = $_REQUEST['timezone'];
    $_SESSION['local_timezone_label'] = get_normalized_timezones_formatted_label($_REQUEST['timezone']);
    $_SESSION['state']                = array();


    $sql  = "SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse State`='Active' limit 1";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    if ($row = $stmt->fetch()) {
        $warehouse_key = $row['Warehouse Key'];
    } else {
        $warehouse_key = '';
    }


    $_SESSION['current_warehouse'] = $warehouse_key;

    $store_key = '';

    $sql  = "SELECT `Store Key`,count(*) as num FROM `Store Dimension` WHERE `Store Status`='Normal'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    if ($row = $stmt->fetch() and $row['num'] == 1) {
        $store_key = $row['Store Key'];
    }


    $_SESSION['current_store'] = $store_key;

    $production_key = '';

    $sql  = "SELECT `Supplier Production Supplier Key`,count(*) as num FROM `Supplier Production Dimension`";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    if ($row = $stmt->fetch() and $row['num'] == 1) {
        $production_key = $row['Supplier Production Supplier Key'];
    }

    $_SESSION['current_production'] = $production_key;


    if (isset($_REQUEST['url']) and $_REQUEST['url'] != '') {

        header("Location: ".urldecode($_REQUEST['url']));

    } else {

        header('Location: dashboard');
    }
    exit;


} else {


    $target = $_SERVER['PHP_SELF'];
    if (!preg_match('/(js|js\.php)$/', $target)) {
        if (isset($_REQUEST['url']) and $_REQUEST['url'] != '') {
            header('Location: login.php?e=1&ref='.$_REQUEST['url']);
        } else {
            header('Location: login.php?e=1');
        }


        exit;
    }
}



