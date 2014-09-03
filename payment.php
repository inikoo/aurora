<?php
/*
 File: store.php

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 28 August 2014 16:33:51 BST, Nottingham, UK
 Copyright (c) 2014, Inikoo

 Version 2.0
*/
include_once 'common.php';
include_once 'class.Store.php';
include_once 'class.Payment.php';
include_once 'common_date_functions.php';

$smarty->assign('page','payment');
if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$payment_key=$_REQUEST['id'];

} else {
	exit("no id");
}



//if (!($user->can_view('stores') and in_array($payment_key,$user->stores)   ) ) {
// header('Location: index.php');
// exit;
//}

$payment=new Payment($payment_key);
//$payment->update_number_sites();
//$payment->update_sales_averages();

$store=new Store($payment->get('Payment Store Key'));
$smarty->assign('store_key',$store->id);
$smarty->assign('store',$store);


$modify=$user->can_edit('customers');
$smarty->assign('modify',$modify);


if (isset($_REQUEST['view']) and in_array($_REQUEST['view'],array('details','changelog'))) {
	$_SESSION['state']['payment']['block_view']=$_REQUEST['view'];
	$block_view=$_SESSION['state']['payment']['block_view'];

}else {
	$block_view=$_SESSION['state']['payment']['block_view'];
}
$smarty->assign('block_view',$block_view);



$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'theme.css.php'
);

$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'dragdrop/dragdrop-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'payments.js.php',
);

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$tipo_filter=$_SESSION['state']['payment']['changelog']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['payment']['changelog']['f_value']);

$smarty->assign('filter_menu0',array());
$smarty->assign('filter_name0','');
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->assign('payment',$payment);

$smarty->assign('parent','orders');
$smarty->assign('title', _('Payment').': ('.$payment->data['Payment Key'].')');



$smarty->display('payment.tpl');

?>
