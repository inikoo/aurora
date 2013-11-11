<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Kaktus

 Version 2.0
*/

include_once 'common.php';
include_once 'class.Store.php';
include_once 'class.Customer.php';

if (!$user->can_view('customers')) {
	header('Location: index.php');
	exit;
}

$modify=$user->can_edit('contacts');


if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {

	$customer_id=$_REQUEST['id'];
} else {
	header('Location: customers.php?error=no_id');
	exit();
}


$sql=sprintf("select * from `Customer Deleted Dimension` where `Customer Key`=%d",$customer_id);
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	foreach ($row as $key=>$value) {
		$customer_data[preg_replace('/\s/','',$key)]=$value;
	}

}else {
	header('Location: customers.php?error=customer_not_found_id='.customer_id);
	exit();
}



$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'editor/assets/skins/sam/editor.css',
	$yui_path.'assets/skins/sam/autocomplete.css',

	'css/text_editor.css',
	'css/common.css',
	'css/button.css',
	'css/container.css',
	'css/table.css',
	'css/customer.css'

);
$css_files[]='theme.css.php';
$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'editor/editor-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'external_libs/ampie/ampie/swfobject.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'js/edit_common.js',
	'customer_deleted.js.php'
);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('customer_data',$customer_data);


$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');


$message='';
$msg='';
$sql=sprintf("select * from `Customer Merge Bridge` where `Merged Customer Key`=%d",$customer_data['CustomerKey']);
$res2=mysql_query($sql);
if ($row2=mysql_fetch_assoc($res2)) {


	$_customer=new Customer($row2['Customer Key']);
	$msg.=','.sprintf("<a style='color:SteelBlue' href='customer.php?id=%d'>%s</a>",$_customer->id,$_customer->get_formated_id($myconf['customer_id_prefix']));
}
$msg=preg_replace('/^,/','',$msg);
if ($msg!='') {
	$message=_('Customer merged with').': '.$msg;

}



$smarty->assign('deleted_date',strftime("%c", strtotime($customer_data['CustomerDeletedDate']." +0:00")));

$smarty->assign('message',$message);

$store=new Store($customer_data['CustomerStoreKey']);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

$smarty->assign('parent','customers');
$smarty->assign('title',_('Deleted Customer'));
$smarty->display('customer_deleted.tpl');

?>
