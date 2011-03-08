<?php
/*
 File: import_csv.php

 UI Import CSV page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
/*ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);*/
include_once('common.php');

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css',
		 'css/dropdown.css',
		 'css/import_data.css'
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
		$yui_path.'uploader/uploader-debug.js',
		'js/php.default.min.js',
		'common.js.php',
		'table_common.js.php',
		'js/dropdown.js',
        	);

if(!isset($_REQUEST['subject'])){
exit("to do a page where the user can choose the correct options");
}
if(!isset($_REQUEST['subject_key'])){
exit("to do a page where the user can choose the correct options");
}
$scope=$_REQUEST['subject'];
 
switch($scope){
case('customers_store'):
$scope_args=$_SESSION['state']['customers']['store'];
break;

case('supplier_products'):
$scope_args=$_SESSION['state']['supplier']['id'];
break;

default:
$scope_args='';
}
	
if(isset($_REQUEST['error']))
{
	$showerror = $_REQUEST['error'];
}	
else
{
	$showerror = '';
}

$smarty->assign('subject',$scope);
$smarty->assign('subject_key',$scope_args);
$smarty->assign('js_files',$js_files);
$smarty->assign('css_files',$css_files);
$smarty->assign('showerror',$showerror);
$smarty->display('import_csv.tpl');
?>
