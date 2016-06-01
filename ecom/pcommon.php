<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2016 at 14:10:01 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

error_reporting(E_ALL ^ E_DEPRECATED);
define("_DEVEL",   isset($_SERVER['devel']));
define("_PREVIEW",   ((isset($_SERVER['preview']) and $_SERVER['preview'])?true:false) );


require_once 'keyring/dns.php';
require_once 'keyring/key.php';
include_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once 'utils/system_functions.php';
require_once 'utils/detect_agent.php';
require_once "utils/aes.php";

require_once "class.Account.php";
require_once "class.Auth.php";
require_once "class.User.php";
require_once "class.Website.php";



$mem = new Memcached();
$mem->addServer($memcache_ip, 11211);

$db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd , array(\PDO::MYSQL_ATTR_INIT_COMMAND =>"SET time_zone = '+0:00';"));
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$default_DB_link=mysql_connect($dns_host, $dns_user, $dns_pwd );
if (!$default_DB_link) {
	print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
	print "Error can not access the database\n";
	exit;
}
mysql_set_charset('utf8');
mysql_query("SET time_zone='+0:00'");

//session_save_path('server_files/tmp');
ini_set('session.gc_maxlifetime', 57600); // 16 hours
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
session_start();



$account=new Account($db);


if ($account->get('Account State')!='Active') {

	exit();
}

if (_PREVIEW) {

	if (isset($_REQUEST['website_key'])) {
		$_SESSION['ecom_website_key']=$_REQUEST['website_key'];
		$website_key=$_REQUEST['website_key'];
	}else {

		if (isset($_SESSION['ecom_website_key'])) {
			$website_key=$_SESSION['ecom_website_key'];
		}else {
		    print_r($_SESSION);
			exit('Error no website key');
		}
	}
}
$website=new Website($website_key);


if ($account->get('Timezone')) {
	date_default_timezone_set($account->get('Timezone'));
}else {
	setTimezone('UTC');
}


//require_once 'utils/modules.php';




require 'external_libs/Smarty/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir = 'server_files/smarty/templates_c';
$smarty->cache_dir = 'server_files/smarty/cache';
$smarty->config_dir = 'server_files/smarty/configs';
$smarty->assign('_DEVEL', _DEVEL);


$smarty->assign('account', $account);


$is_already_logged_in=(isset($_SESSION['logged_in']) and $_SESSION['logged_in']? true : false);


$smarty->assign('account', $account);
$smarty->assign('page_name', basename($_SERVER["PHP_SELF"], ".php"));
$smarty->assign('analyticstracking', ( file_exists('templates/analyticstracking.tpl')?true:false));


if ($is_already_logged_in) {

	$user=new User($_SESSION['user_key']);

	if (isset($user)) {
		$locale=$user->get('User Preferred Locale');
	}else {
		$locale=$account->get('Locale').'.UTF-8';
	}
	$smarty->assign('locale', $locale);

	set_locale($locale);



	$smarty->assign('user', $user);

}





?>
