<?php
/*

 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/
error_reporting(E_ALL);


require_once 'vendor/autoload.php';

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcachedSessionHandler;


include_once 'keyring/dns.php';
include_once 'keyring/key.php';

include_once 'class.Account.php';
$smarty = new Smarty();

$memcached = new Memcached();
$memcached->addServer($memcache_ip, 11211);

$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');

date_default_timezone_set('UTC');

$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);



include_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once 'utils/network_functions.php';
require_once "utils/aes.php";


setlocale(LC_MONETARY, 'en_GB.UTF-8');


$account = new Account();
date_default_timezone_set($account->data['Account Timezone']);
define("TIMEZONE", $account->data['Account Timezone']);


include_once 'class.Auth.php';
include_once 'class.User.php';


$sessionStorage = new NativeSessionStorage(array(), new MemcachedSessionHandler($memcached));
$session        = new Session($sessionStorage);


//$session = new Session();
$session->start();

//session_start();


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

    $_SESSION['logged_in']      = true;
    $_SESSION['logged_in_page'] = 0;


    $session->set('logged_in', true);
    $session->set('logged_in_page', 0);
    $session->set('user_key', $auth->get_user_key());


    $_SESSION['user_key']    = $_user_key;
    $user                    = new User($_user_key);
    $_SESSION['text_locale'] = $user->get('User Preferred Locale');


    $session->set('state', array());

    $warehouse_key = '';

    $sql = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse State`="Active" limit 1');

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch() ) {
            $warehouse_key = $row['Warehouse Key'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }
    $session->set('current_warehouse', $warehouse_key);

    $store_key = '';

    $sql = sprintf('SELECT `Store Key`,count(*) as num FROM `Store Dimension` WHERE `Store State`="Normal" ');

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch() and $row['num'] == 1) {
            $store_key = $row['Store Key'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }
    $session->set('current_store', $store_key);


    $production_key = '';
    $sql            = sprintf('SELECT `Supplier Production Supplier Key`,count(*) as num FROM `Supplier Production Dimension`  ');

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch() and $row['num'] == 1) {
            $production_key = $row['Supplier Production Supplier Key'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }
    $session->set('current_production', $production_key);



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
        }else{
            header('Location: login.php?e=1');
        }


        exit;
    }
}


?>
