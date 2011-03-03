<?php
/*
File: export_wizard.php

UI customer page

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

## To check whether the form has proper parameters in query string ##
if(!$_REQUEST['subject']){
	header('Location: customers_server.php');
	exit;
}

if(!$_REQUEST['subject']){
	header('Location: customers_server.php');
	exit;
}
$map_type = $_REQUEST['subject'];

## NOT BEING USED ##
//$arr=array();
//$arr=explode('.',basename($_SERVER['HTTP_REFERER']));
//$map_for=$arr[0];

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
	$smarty->assign('customer_id',$customer_id);
	$smarty->assign('return_path',"customer.php?p=cs&id==$customer_id");
	$list=$customer->data;
}
## FOR CUSTOMERS - of a Store ##
elseif($map_type == 'customers'){
	if(isset($_REQUEST['subject_key']) and is_numeric($_REQUEST['subject_key'])){
	    $store_id=$_REQUEST['subject_key'];
	}
	$qry = mysql_query("SELECT * FROM `Customer Dimension` WHERE `Customer Store Key` = '$store_id' LIMIT 1");
	$list= mysql_fetch_assoc($qry);
	$smarty->assign('customer_id',$store_id);
	$smarty->assign('return_path',"customers.php?store=$store_id");
}
## IF NO PROPER DEFINATION FOUND ##
else{
	header('Location: customers_server.php');
	exit;
}

## WORKING WITH DATA AND DISPLAYING IN TEMPLATE ##

if(isset($_SESSION['list'])){
	unset($_SESSION['list']);
}
//print_r($list);
$smarty->assign('map_type',$map_type);
$smarty->assign('param',count($list)-1);
$smarty->assign('list',$list);
$smarty->display('export_wizard.tpl');
?>
