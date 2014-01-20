<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 January 2014 16:03:20 GMT, Sheffield UK	

 Copyright (c) 2014, Inikoo
 
 Version 2.0
*/
include_once 'common.php';
include_once 'class.Store.php';


if (!$user->can_view('customers')) {
	header('Location: index.php');
	exit();
}

if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$store_key=$_REQUEST['id'];

} else {
	exit("no store id");

}

if (!($user->can_view('stores') and in_array($store_key,$user->stores)   ) ) {

	header('Location: index.php');
	exit;
}

$store=new Store($store_key);
if (!$store->id) {
	header('Location: index.php?error=store_not_found');
	exit();
}

//print_r($_SESSION['state']['customers']);

$currency=$store->data['Store Currency Code'];
$currency_symbol=currency_symbol($currency);
$smarty->assign('store',$store);

$smarty->assign('store_key',$store->id);
$modify=$user->can_edit('customers');


$smarty->assign('modify',$modify);


$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');



$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/container.css',
	'css/edit.css',
	'css/button.css',
	'css/table.css',
	'theme.css.php'

);

$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'js/edit_common.js',
	
	'js/customers_common.js',
	'js/export_common.js',
	'store_pending_orders.js.php'

);




$smarty->assign('parent','orders');
$smarty->assign('title', _('Customers').' ('.$store->data['Store Code'].')');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$tipo_filter1=$_SESSION['state']['customers']['pending_orders']['f_field'];
$smarty->assign('filter1',$tipo_filter1);
$smarty->assign('filter_value1',($_SESSION['state']['customers']['pending_orders']['f_value']));
$filter_menu1=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Order Number starting with <i>x</i>'),'label'=>_('Order Number')),
);
$smarty->assign('filter_menu1',$filter_menu1);
$smarty->assign('filter_name1',$filter_menu1[$tipo_filter1]['label']);
$paginator_menu1=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu1);




$smarty->assign('elements',$_SESSION['state']['customers']['pending_orders']['elements']);

$smarty->assign('block_view','pending_orders');
$smarty->display('store_pending_orders.tpl');

?>
