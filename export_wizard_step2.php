<?php
/*
File: export_wizard_step2.php

Data for export process

About:
Autor: Raul Perusquia <rulovico@gmail.com>

Copyright (c) 2009, Kaktus

Version 2.0
*/
/*ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);*/
include_once('common.php');
include_once('class.Customer.php');

$css_files=array(
         $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
         $yui_path.'menu/assets/skins/sam/menu.css',
         $yui_path.'calendar/assets/skins/sam/calendar.css',
         $yui_path.'button/assets/skins/sam/button.css',
         $yui_path.'editor/assets/skins/sam/editor.css',
         $yui_path.'assets/skins/sam/autocomplete.css',
         'text_editor.css',
         'common.css',
         'button.css',
         'container.css',
         'table.css',
	 'css/export_wizard.css',
         'css/customer.css'
         );
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
        'common.js.php',
        'table_common.js.php',
        'js/search.js',
        'js/edit_common.js',
	'js/export_wizard.js',
        'customer.js.php'
        );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
if(!$user->can_view('customers')){
  exit();
}

## NOT BEING USED ##
/*if(!isset($_POST['SUBMIT'])){
	header('Location: index.php');
	exit;
}*/

## To check whether the form has proper parameters in query string ##
if(!isset($_REQUEST['subject_key'])){
	header('Location: customers_server.php');
	exit;
}
if(!isset($_REQUEST['subject'])){
	header('Location: customers_server.php');
	exit;
}
$map_type = $_REQUEST['subject'];

## FOR CUSTOMER - Individual ##
if($map_type == 'customer'){
	if(isset($_REQUEST['subject_key']) and is_numeric($_REQUEST['subject_key'])){
	  $_SESSION['state']['customer']['id']=$_REQUEST['subject_key'];
	  $customer_id=$_REQUEST['subject_key'];
	}else{
	  $customer_id=$_SESSION['state']['customer']['id'];
	}
	$customer=new customer($customer_id);
	$customer_id = $customer->data['Customer Key'];
	$smarty->assign('subject_key',$customer_id);
	$smarty->assign('return_path',"customer.php?p=cs&id=$customer_id");
	$list=$customer->data;
}
## FOR CUSTOMERS - of a Store ##
elseif($map_type == 'customers'){
	if(isset($_REQUEST['subject_key']) and is_numeric($_REQUEST['subject_key'])){
	    $store_id=$_REQUEST['subject_key'];
	}
	$qry = mysql_query("SELECT * FROM `Customer Dimension` WHERE `Customer Store Key` = '$store_id' LIMIT 1");
	$list=mysql_fetch_assoc($qry);
	//print_r($list);
	$smarty->assign('subject_key',$store_id);
	$smarty->assign('return_path',"customers.php?store=$store_id");
}
## FOR CUSTOMERS STATIC LIST ##
elseif($map_type == 'customers_static_list'){
	if(isset($_REQUEST['subject_key']) and is_numeric($_REQUEST['subject_key'])){
	    $static_list_id=$_REQUEST['subject_key'];
	}
	$qry = mysql_query("SELECT * FROM `Customer Dimension` WHERE `Customer Key` = (SELECT `Customer Key` FROM `Customer List Customer Bridge` WHERE `Customer List Key` = '$static_list_id' LIMIT 1 )");
	$list= mysql_fetch_assoc($qry);
	//print_r($list);
	$smarty->assign('subject_key', $static_list_id);
	$smarty->assign('return_path',"customers_lists.php");
}
## IF NO PROPER DEFINATION FOUND ##
else{
	header('Location: customers_server.php');
	exit;
}

## WORKING WITH DATA AND DISPLAYING IN TEMPLATE ##
if(isset($_POST['SUBMIT'])){
$included_data = $_POST['fld'];
//print_r($included_data);
$actual_data=$list;
//print_r($actual_data);
$exported_data = final_array($actual_data , $included_data);
//print_r($exported_data);
unset($_POST);
}
if(!isset($_SESSION['list'])){
	$_SESSION['list'] = $exported_data;
}
else{
	$exported_data = $_SESSION['list'];
}
$smarty->assign('subject',$map_type);
$smarty->assign('list', $exported_data);
$smarty->assign('count', count($exported_data)-1);
$smarty->display('export_wizard_step2.tpl');

### USER DEFINED METHODS ###
function final_array($assoc_arr, $num_arr){
	$final_arr = array();
	foreach($assoc_arr as $assoc_key => $assoc_val){
		if(in_array($assoc_key, $num_arr)){
			$final_arr[$assoc_key]=$assoc_val;
		}
	}
	return $final_arr;
}
?>
