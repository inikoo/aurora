<?php
/*


 About:
 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 20 August 2015 13:07:27 GMT+8, Singapure
 Copyright (c) 2015, Inikoo

 Version 2.0
*/



require_once 'conf/dns.php';
require_once 'conf/key.php';

require_once 'common_functions.php';
require_once 'common_detect_agent.php';

require_once "class.User.php";

require_once "class.Account.php";

$default_DB_link=@mysql_connect($dns_host,$dns_user,$dns_pwd );
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
require_once 'conf/conf.php';

$inikoo_account=new Account();
date_default_timezone_set($inikoo_account->data['Account Timezone']) ;
define("TIMEZONE",$inikoo_account->data['Account Timezone']);



session_save_path('server_files/tmp');
ini_set('session.gc_maxlifetime', 57600); // 16 hours
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
session_start();

require 'external_libs/Smarty/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir = 'server_files/smarty/templates_c';
$smarty->cache_dir = 'server_files/smarty/cache';
$smarty->config_dir = 'server_files/smarty/configs';




if(!isset($_REQUEST['u'])){
    exit();
}

$app_view_url=$_REQUEST['u'];

$account_label=($inikoo_account->data['Account Menu Label']==''?_('Company'):$inikoo_account->data['Account Menu Label']);
$smarty->assign('account_label',$account_label);



$user=new User($_SESSION['user_key']);
$smarty->assign('inikoo_account',$inikoo_account);

$smarty->assign('user',$user);

$user->read_groups();
$user->read_rights();
$user->read_stores();
$user->read_websites();
$user->read_warehouses();
if ($user->data['User Type']=='Supplier') {
	$user->read_suppliers();

}



$nav_menu=array();

$nav_menu[] = array(_('Dashboard'), 'index.php','index');

if ($user->can_view('customers')) {

	if (count($user->stores)==1) {
		$nav_menu[] = array(_('Customers'), 'customers.php?store='.$user->stores[0],'customers');
	} elseif (count($user->stores)>1)

		if ($user->data['User Hooked Store Key']) {
			$nav_menu[] = array(_('Customers'), 'customers.php?store='.$user->data['User Hooked Store Key'],'customers');
		}
	else {
		$nav_menu[] = array(_('Customers'), 'customers_server.php','customers');
	}



}

if ($user->can_view('orders')) {

	if (count($user->stores)==1) {
		$nav_menu[] = array(_('Orders'), 'orders.php?store='.$user->stores[0],'orders');
	} elseif (count($user->stores)>1) {

		if ($user->data['User Hooked Store Key']) {
			$nav_menu[] = array(_('Orders'), 'orders.php?store='.$user->data['User Hooked Store Key'],'orders');
		}
		else {
			$nav_menu[] = array(_('Orders'), 'orders_server.php','orders');
		}
	}

}

if ($user->can_view('sites')) {
	if (count($user->websites)==1) {
		$nav_menu[] = array(_('Website'), 'site.php?id='.$user->websites[0],'websites');
	} elseif (count($user->websites)>1) {


		if ($user->data['User Hooked Site Key']) {
			$nav_menu[] = array(_('Website'), 'site.php?id='.$user->data['User Hooked Site Key'],'websites');
		}
		else {
			$nav_menu[] = array(_('Websites'), 'sites.php','websites');
		}
	}
}

if ($user->can_view('stores')) {
	if (count($user->stores)==1) {
		$nav_menu[] = array(_('Products'), 'store.php?id='.$user->stores[0],'products');
	} elseif (count($user->stores)>1) {

		if ($user->data['User Hooked Store Key']) {
			$nav_menu[] = array(_('Products'), 'store.php?id='.$user->data['User Hooked Store Key'],'products');
		}
		else {
			$nav_menu[] = array(_('Products'), 'stores.php','products');

		}
	}

}

if ($user->can_view('marketing')) {
	if (count($user->stores)==1) {
		$nav_menu[] = array(($_SESSION['text_locale_country_code']=='ES'?'Merca':('Marketing')), 'marketing.php?store='.$user->stores[0],'marketing');
	} elseif (count($user->stores)>1) {

		if ($user->data['User Hooked Store Key']) {
			$nav_menu[] = array(($_SESSION['text_locale_country_code']=='ES'?'Merca':('Marketing')), 'marketing.php?store='.$user->data['User Hooked Store Key'],'marketing');
		}
		else {
			$nav_menu[] = array(($_SESSION['text_locale_country_code']=='ES'?'Merca':('Marketing')), 'marketing_server.php','marketing');

		}
	}


}

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
if ($user->can_view('reports')) {
	$nav_menu[] = array(_('Reports'), 'reports.php','reports');
}


if ($user->can_view('suppliers')){
	$nav_menu[] = array(_('Suppliers'), 'suppliers.php','suppliers');
}


if ($user->can_view('staff'))
	$nav_menu[] = array($account_label, 'hr.php','staff');

if ($user->can_view('account'))
	$nav_menu[] = array(_('Account'), 'account.php','account');


if ($user->can_view('users'))
	$nav_menu[] = array(_('Users'), 'users.php','users');
elseif ($user->data['User Type']=='Staff')
	$nav_menu[] = array(_('Profile'), 'user.php','users');

























if ($user->data['User Type']=='Supplier') {


	//$nav_menu[] = array(_('Orders'), 'suppliers.php?orders'  ,'orders');
	$nav_menu[] = array(_('Products'), 'suppliers.php'  ,'suppliers');
	$nav_menu[] = array(_('Dashboard'), 'index.php','home');
}


if ($user->data['User Type']=='Warehouse') {

	$nav_menu[] = array(_('Pending Orders'), 'warehouse_orders.php?id='.$user->data['User Parent Key'],'orders');


}


$smarty->assign('nav_menu',$nav_menu);



$smarty->assign('app_view_url',$app_view_url);

$smarty->display('app.tpl');


?>