<?php
/*
 File: new_contact.php 

 UI new contact page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Contact.php');


if(!$user->can_view('suppliers')){
  header('Location: index.php');
  exit();
}

if(!isset($_REQUEST['supplier_key']) or !$_REQUEST['supplier_key']){
header('Location: suppliers.php');
  exit();

}

$supplier_key=$_REQUEST['supplier_key'];
$supplier=new Supplier($supplier_key);

if(!$supplier->id){
header('Location: suppliers.php');
  exit();

}


if(!$user->can_edit('suppliers')){
  header('Location: supplier.php?id='.$supplier->id);
  exit();

}

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
	'css/text_editor.css',
	'css/common.css',
	'css/button.css',
	'css/container.css',
	'css/table.css',
	'css/edit.css',
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
		'js/common.js',
		'js/table_common.js',
		'js/search.php',
		'js/edit_common.js',
		'js/validate_telecom.js',
			'js/country_address_labels.js',
	'js/edit_address.js',
		'edit_contact_from_parent.js.php',
		'js/edit_contact_telecom.js',
		'edit_contact_name.js.php',
		'edit_contact_email.js.php',
		'new_supplier_product.js.php'
		//'new_contact.js.php?scope=staff'
		);




$smarty->assign('supplier',$supplier);

$smarty->assign('supplier_key',$supplier->id);
$smarty->assign('supplier_id',$supplier->id);
$smarty->assign('search_label',_('Suppliers'));
$smarty->assign('search_scope','supplier_products');


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','suppliers');
$smarty->assign('title',_('New Supplier Product'));
$smarty->display('new_supplier_product.tpl');


?>
