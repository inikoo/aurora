<?php

define('DEBUG', 0);
if (DEBUG) {
	error_reporting(E_ALL);
}

require_once 'app_files/db/dns.php';
require_once 'app_files/key.php';

require_once 'common_functions.php';
require_once 'common_detect_agent.php';

require_once "class.Session.php";
require_once "aes.php";

require_once "class.Auth.php";
require_once "class.User.php";

$external_DB_link=false;

if (isset($connect_to_external) and isset($external_dns_user)) {
	$external_DB_link=mysql_connect($external_dns_host,$external_dns_user,$external_dns_pwd );

	if (!$external_DB_link) {
		print "Error can not connect with external database server\n";
	}
	$external_db_selected=mysql_select_db($external_dns_db, $external_DB_link);
	if (!$external_db_selected) {
		print "Error can not access the external database\n";
	}
	//print $external_DB_link;
}


$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$default_DB_link) {
	print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
	print "Error can not access the database\n";
	exit;
}


mysql_query("SET NAMES 'utf8'");
require_once 'conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;
mysql_query("SET time_zone='+0:00'");
require_once 'conf/conf.php';

$max_session_time=$myconf['max_session_time'];
$max_session_time_in_milliseconds=1000*$max_session_time;
$session = new Session($max_session_time);
//print date("%c",1349421365);

//print_r($session);
//print '//'.session_id( );
//print_r($_SESSION['state']);

require 'external_libs/Smarty/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir = 'server_files/smarty/templates_c';
$smarty->cache_dir = 'server_files/smarty/cache';
$smarty->config_dir = 'server_files/smarty/configs';
//$smarty->error_reporting = E_STRICT;

if (isset($_REQUEST['log_as']) and $_REQUEST['log_as']=='supplier')
	$log_as="supplier";
else
	$log_as="staff";

$logout = (array_key_exists('logout', $_REQUEST)) ? $_REQUEST['logout'] : false;
// print array_pop(explode('/', $_SERVER['PHP_SELF']));
if ($logout) {

	/*  ?><script type = "text/javascript">alert("You are about to be signed out due to Inactivity");</script><?php   */
	$sql=sprintf("update `User Log Dimension` set `Logout Date`=NOW()  where `Session ID`=%s", prepare_mysql(session_id()));
	mysql_query($sql);

	session_regenerate_id();
	session_destroy();
	unset($_SESSION);
	header('Location: login.php?log_as='.$log_as);
	exit;
}

$is_already_logged_in=(isset($_SESSION['logged_in']) and $_SESSION['logged_in']? true : false);

if (!$is_already_logged_in) {
	$target = $_SERVER['PHP_SELF'];
	if (!preg_match('/(js|js\.php)$/',$target)) {

		header('Location: login.php?log_as='.$log_as);
		exit;
	}
	exit;
}

if ($_SESSION['logged_in_page']!=0) {


	$sql=sprintf("update `User Log Dimension` set `Logout Date`=NOW()  where `Session ID`=%s", prepare_mysql(session_id()));
	mysql_query($sql);

	session_regenerate_id();
	session_destroy();
	unset($_SESSION);

	header('Location: login.php?log_as='.$log_as);
	exit;

}
$user=new User($_SESSION['user_key']);

$_client_locale='en_GB.UTF-8';
include_once 'set_locales.php';
require 'locale.php';

//print_r(localeconv());
$_SESSION['locale_info'] = localeconv();
if ($_SESSION['locale_info']['currency_symbol']=='EU')
	$_SESSION['locale_info']['currency_symbol']='Û';

$smarty->assign('lang_code',$_SESSION['text_locale_code']);
$smarty->assign('lang_country_code',strtolower($_SESSION['text_locale_country_code']));

$args="?";

foreach ($_GET as $key => $value) {
	if ($key!='_locale')$args.=$key.'='.$value.'&';
}
$lang_menu=array();


$sql=sprintf("select * from `Language Dimension`");
$res=mysql_query($sql);

while ($row=mysql_fetch_assoc($res) ) {
	$_locale=$row['Language Code'].'_'.$row['Country 2 Alpha Code'].'.UTF-8';
	$lang_menu[]=array($_SERVER['PHP_SELF'].$args.'_locale='.$_locale,strtolower($row['Country 2 Alpha Code']),$row['Language Original Name']);
}

$smarty->assign('lang_menu',$lang_menu);
$smarty->assign('page_layout','doc4');
$smarty->assign('timezone',date("e P"));

//print_r($_SESSION['state']['customers']['store']);

include_once 'set_state.php';
//print_r($_SESSION['state']['customers']['store']);




$smarty->assign('decimal_point',$_SESSION['locale_info']['decimal_point']);
$smarty->assign('thousands_sep',$_SESSION['locale_info']['thousands_sep']);
$smarty->assign('currency_symbol',$_SESSION['locale_info']['currency_symbol']);
$smarty->assign('max_session_time_in_milliseconds',$max_session_time_in_milliseconds);






//print_r($_SESSION['state']['department']);
//$_SESSION['state']['department']['id']=3;
$smarty->assign('user',$user);

$user->read_groups();
$user->read_rights();
$user->read_stores();
$user->read_websites();
$user->read_warehouses();
if ($user->data['User Type']=='Supplier') {
	$user->read_suppliers();

}




$sql=sprintf("select `Account Name`,`Inikoo Public URL`,`Account Country 2 Alpha Code`,`Account Country Code`,`Account Currency`,`Currency Symbol`,`Short Message` from  `Account Dimension` left join kbase.`Currency Dimension` CD on (CD.`Currency Code`=`Account Currency`) ");
//print $sql;

$res=mysql_query($sql);

if ($row=mysql_fetch_array($res)) {
	$corporate_currency=$row['Account Currency'];
	$corporate_currency_symbol=$row['Currency Symbol'];
	$corporate_country_code=$row['Account Country Code'];
	$corporate_country_2alpha_code=$row['Account Country 2 Alpha Code'];
	$inikoo_public_url=$row['Inikoo Public URL'];
	$smarty->assign('top_navigation_message',$row['Short Message']);
	$smarty->assign('account_name',$row['Account Name']);

}


$sql=sprintf("select * from  `Inikoo Dimension` where `Inikoo Key`=1 ");
//print $sql;

$res=mysql_query($sql);

if ($row=mysql_fetch_array($res)) {
	$inikoo_version=$row['Inikoo Version'];
	$inikoo_account_code=$row['Inikoo Account Code'];

	$smarty->assign('inikoo_version',$inikoo_version);
}


//print_r($row);
//exit;



$nav_menu=array();
if ($user->can_view('users'))
	$nav_menu[] = array(_('Users'), 'users.php','users');
elseif ($user->data['User Type']=='Staff')
	$nav_menu[] = array(_('Profile'), 'user.php','users');

if ($user->can_view('account'))
$nav_menu[] = array(_('Account'), 'account.php','account');




if ($user->can_view('staff'))
	$nav_menu[] = array(_('Staff'), 'hr.php','staff');

if ($user->can_view('reports')) {
	$nav_menu[] = array(_('Reports'), 'reports.php','reports');
}
if ($user->can_view('suppliers'))
	$nav_menu[] = array(_('Suppliers'), 'suppliers.php','suppliers');



if ($user->can_view('warehouses')) {


	if (count($user->warehouses)==1)
		$nav_menu[] = array(_('Inventory'), 'inventory.php?block_view=parts&warehouse_id='.$user->warehouses[0],'parts');
	else
		$nav_menu[] = array(_('Inventory'), 'warehouses.php','parts');

	if (count($user->warehouses)==1)
		$nav_menu[] = array(_('Locations'), 'warehouse.php?id='.$user->warehouses[0],'locations');
	else
		$nav_menu[] = array(_('Locations'), 'warehouses.php','locations');


}
if ($user->can_view('marketing')) {
	if (count($user->stores)==1) {
		$nav_menu[] = array(($_SESSION['text_locale_country_code']=='ES'?'Merca':('Marketing')), 'marketing.php?store='.$user->stores[0],'marketing');
	} else
		$nav_menu[] = array(($_SESSION['text_locale_country_code']=='ES'?'Merca':('Marketing')), 'marketing_server.php','marketing');

}


if ($user->can_view('stores')) {
	if (count($user->stores)==1) {
		$nav_menu[] = array(_('Products'), 'store.php?id='.$user->stores[0],'products');
	} elseif (count($user->stores)>1)
		$nav_menu[] = array(_('Products'), 'stores.php','products');
}


if ($user->can_view('sites')) {
	if (count($user->websites)==1) {
		$nav_menu[] = array(_('Website'), 'site.php?id='.$user->websites[0],'websites');
	} elseif (count($user->websites)>1)
		$nav_menu[] = array(_('Websites'), 'sites.php','websites');
}

if ($user->can_view('orders')) {

	if (count($user->stores)==1) {
		$nav_menu[] = array(_('Orders'), 'orders.php?store='.$user->stores[0],'orders');
	} elseif (count($user->stores)>1)
		$nav_menu[] = array(_('Orders'), 'orders_server.php','orders');

}

if ($user->can_view('customers')) {

	if (count($user->stores)==1) {
		$nav_menu[] = array(_('Customers'), 'customers.php?store='.$user->stores[0],'customers');
	} elseif (count($user->stores)>1)
		$nav_menu[] = array(_('Customers'), 'customers_server.php','customers');

}

$common='';


if ($user->data['User Type']=='Supplier') {


	//$nav_menu[] = array(_('Orders'), 'suppliers.php?orders'  ,'orders');
	$nav_menu[] = array(_('Products'), 'suppliers.php'  ,'suppliers');
	$nav_menu[] = array(_('Dashboard'), 'index.php','home');
}


if ($user->data['User Type']=='Warehouse') {

	 $nav_menu[] = array(_('Pending Orders'), 'warehouse_orders.php','orders');


}

//else
//   $nav_menu[] = array(_('Dashboard'), 'index.php','home');

$smarty->assign('nav_menu',$nav_menu);
$smarty->assign('theme',$myconf['theme']);
$smarty->assign('my_name',$myconf['name']);

/*
if(!$is_root_available){
   include_once 'config.php';
   exit;
}
*/



$smarty->assign('page_name',basename($_SERVER["PHP_SELF"], ".php").PHP_EOL);

$smarty->assign('analyticstracking',( file_exists('templates/analyticstracking.tpl')?true:false));

?>
