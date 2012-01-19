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
		'js/validate_telecom.js',
		'edit_address.js.php',
		'edit_contact_from_parent.js.php',
		'edit_contact_telecom.js.php',
		'edit_contact_name.js.php',
		'edit_contact_email.js.php','new_staff.js.php?scope=staff',
		//'new_contact.js.php?scope=staff'
		);

$smarty->assign('scope','staff');

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('box_layout','yui-t0');
$smarty->assign('parent','contacts');
$smarty->assign('title','New Staff');
$smarty->display('new_staff.tpl');


?>
