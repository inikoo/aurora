<?php
/*
 File: suppliers.php

 UI suppliers page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'common.php';


if (!($user->can_view('suppliers'))) {
	header('Location: index.php');
	exit;
}

if (!($user->can_edit('suppliers'))) {
	header('Location: suppliers.php');
	exit;
}


$smarty->assign('view',$_SESSION['state']['suppliers']['edit_suppliers']['view']);



$general_options_list=array();



$general_options_list[]=array('tipo'=>'url','url'=>'suppliers.php','label'=>_('Exit Edit'));
$general_options_list[]=array('tipo'=>'url','url'=>'new_suppler.php','label'=>_('Add Supplier'));

$smarty->assign('general_options_list',$general_options_list);

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
               'css/edit_address.css',
               'theme.css.php'
           );



$js_files=array(

	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'js/edit_common.js',
	'edit_suppliers.js.php',
	'js/validate_telecom.js',

'edit_address.js.php',
'edit_contact_from_parent.js.php',
'edit_contact_telecom.js.php',
'edit_contact_name.js.php',
'edit_contact_email.js.php'
);


$smarty->assign('search_label',_('Suppliers'));
$smarty->assign('search_scope','supplier_products');
$smarty->assign('supplier_id','');



$smarty->assign('edit',$_SESSION['state']['suppliers']['edit']);


$smarty->assign('scope','supplier');

$smarty->assign('parent','suppliers');
$smarty->assign('title', _('Edit Suppliers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$tipo_filter=$_SESSION['state']['suppliers']['edit_suppliers']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['suppliers']['edit_suppliers']['f_value']);


$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>'Suppliers with code starting with  <i>x</i>','label'=>'Code'),
	'name'=>array('db_key'=>'name','menu_label'=>'Suppliers which name starting with <i>x</i>','label'=>'Name'),
	'low'=>array('db_key'=>'low','menu_label'=>'Suppliers with more than <i>n</i> low stock products','label'=>'Low'),
	'outofstock'=>array('db_key'=>'outofstock','menu_label'=>'Suppliers with more than <i>n</i> products out of stock','label'=>'Out of Stock'),
);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

//$smarty->assign('table_info',$orders.'  '.ngettext('Order','Orders',$orders));
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->display('edit_suppliers.tpl');
?>
