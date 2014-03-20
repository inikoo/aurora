<?php
/*
 File: customers.php

 UI customers page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'common.php';

if (!$user->can_view('customers')) {
	exit();
}


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
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
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	'js/php.default.min.js',
	'js/common.js',

	'js/table_common.js',
	'js/search.js',
	'js/edit_common.js',
	'js/customers_server.js'
);






//$smarty->assign('details',$_SESSION['state']['customers']['details']);
$smarty->assign('advanced_search',$_SESSION['state']['customers']['advanced_search']);



$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers').' ('._('All Stores').')');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');



$tipo_filter=$_SESSION['state']['stores']['customers']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['stores']['customers']['f_value']);

$filter_menu=array(
	'code'=>array('menu_label'=>_('Store Code'),'db_key'=>'Store Code','label'=>'Code'),
	'name'=>array('menu_label'=>_('Store Name'),'db_key'=>'Store Name','label'=>'Name'),

);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('type',$_SESSION['state']['stores']['customers']['type']);
$smarty->assign('store_key',false);

		

$field_labels=array(
"code"=>_("Code"),
"name"=>_("Store Name"),
"contacts"=>_("Total Contacts"),
"new_contacts"=>_("New Contacts"),
"active_contacts"=>_("Active Contacts"),
"losing_contacts"=>_("Losing Contacts"),
"lost_contacts"=>_("Lost Contacts"),
"contacts_with_orders"=>_("Total Customers"),
"new_contacts_with_orders"=>_("New Customers"),
"active_contacts_with_orders"=>_("Active Customers"),
"losing_contacts_with_orders"=>_("Losing Customers"),
"lost_contacts_with_orders"=>_("Lost Customers"),

);

$smarty->assign('field_labels',base64_encode(json_encode($field_labels)));


$state_data=array(
'stores'=>$_SESSION['state']['stores']
);
$smarty->assign('state_data',base64_encode(json_encode($state_data)));

$smarty->display('customers_server.tpl');

?>
