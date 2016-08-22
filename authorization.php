<?php
/*

 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/



include_once 'keyring/dns.php';
include_once 'keyring/key.php';

include_once 'external_libs/Smarty/Smarty.class.php';
include_once 'class.Account.php';
$smarty = new Smarty();


$smarty->template_dir = 'templates';
$smarty->compile_dir = 'server_files/smarty/templates_c';
$smarty->cache_dir = 'server_files/smarty/cache';
$smarty->config_dir = 'server_files/smarty/configs';



error_reporting(E_ALL);

date_default_timezone_set('UTC');

$db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd , array(\PDO::MYSQL_ATTR_INIT_COMMAND =>"SET time_zone = '+0:00';"));
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$con=@mysql_connect($dns_host, $dns_user, $dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
$db2=@mysql_select_db($dns_db, $con);
if (!$db2) {
	print "Error can not access the database\n";
	exit;
}

include_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once 'utils/detect_agent.php';
require_once "utils/aes.php";


mysql_set_charset('utf8');
setlocale(LC_MONETARY, 'en_GB.UTF-8');



$account=new Account();
date_default_timezone_set($account->data['Account Timezone']) ;
define("TIMEZONE", $account->data['Account Timezone']);


include_once 'class.Auth.php';
require_once "class.Session.php";
include_once 'class.User.php';

//session_save_path('server_files/tmp');
ini_set('session.gc_maxlifetime', 57600); // 16 hours
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
session_start();



$auth=new Auth(IKEY, SKEY);
$handle = (array_key_exists('login__username', $_REQUEST)) ? $_REQUEST['login__username'] : false;
$sk = (array_key_exists('ep', $_REQUEST)) ? base64_decode($_REQUEST['ep']) : false;







if (!$sk and array_key_exists('mk', $_REQUEST)    ) {
	$auth->authenticate_from_inikoo_masterkey($_REQUEST['mk']);
}
elseif ($handle) {
	$auth->authenticate($handle, $sk,'system', 0);
}


if ($auth->is_authenticated()) {



	$_SESSION['logged_in']=true;
	$_SESSION['logged_in_page']=0;
	$_SESSION['user_key']=$auth->get_user_key();
	$user=new User($_SESSION['user_key']);
	$_SESSION['text_locale']=$user->data['User Preferred Locale'];



	$_SESSION['current_store']='';
	$_SESSION['current_website']='';
	$_SESSION['current_warehouse']='';



	$_SESSION['state']=array();



	if (isset($_REQUEST['url']) and $_REQUEST['url']!='' ) {

		header("Location: ".urldecode($_REQUEST['url']));

	}else {

		header('Location: dashboard');
	}
	exit;


} else {



	$target = $_SERVER['PHP_SELF'];
	if (!preg_match('/(js|js\.php)$/', $target)) {
		header('Location: login.php?e=1');
		exit;
	}
}


?>
