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
include_once('class.SupplierProduct.php');
include_once('class.Family.php');
include_once('class.Department.php');

if(!$user->can_view('contacts')){
  header('Location: index.php');
  exit();
}
if(!$user->can_edit('customers')){
  header('Location: customers.php');
  exit();

}

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
	'text_editor.css',
	'common.css',
	'button.css',
	'css/container.css',
	'table.css',
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
		'js/edit_common.js',
				'js/search.js',

		'js/validate_telecom.js',
		'edit_address.js.php',
		'edit_contact_from_parent.js.php',
		'edit_contact_telecom.js.php',
		'edit_contact_name.js.php',
		'edit_contact_email.js.php',
		'associate_product_part.js.php'
		//'new_contact.js.php?scope=staff'
		);

$family=new Family($_REQUEST['id']);
$store=new Store($family->data['Product Family Store Key']);
$department=new Department($family->data['Product Family Main Department Key']);

$smarty->assign('family',$family);
$smarty->assign('store',$store);
$smarty->assign('department',$department);

$smarty->assign('store_id',$store->id);


$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');



$tipo_filter2='code';
$filter_menu2=array(
                  'code'=>array('db_key'=>_('code'),'menu_label'=>_('Code'),'label'=>_('Code')),
                  'name'=>array('db_key'=>_('name'),'menu_label'=>_('Name'),'label'=>_('Name')),
              );
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2','');


$sp=new SupplierProduct($_REQUEST['id']);
$smarty->assign('sp',$sp);

$smarty->assign('scope','staff');

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('box_layout','yui-t0');
$smarty->assign('parent','products');
$smarty->assign('title','Associate New Product');
$smarty->display('associate_product_part.tpl');


?>
