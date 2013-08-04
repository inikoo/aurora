<?php

/*
 File: user.php 

 UI index page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.User.php');

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'css/common.css',
		 'css/button.css',
		 'css/container.css',
		 'css/table.css'
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
		'supplier_user.js.php'
		);

$block_view=$_SESSION['state']['staff_user']['block_view'];
$smarty->assign('block_view',$block_view);
//$smarty->assign('user_key',$_REQUEST['id']);
$general_options_list[]=array('class'=>'edit','tipo'=>'js','id'=>'forgot_password','label'=>_('Forgot Password'));
$smarty->assign('search_scope','users');
$smarty->assign('search_label',_('Search'));
$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('parent','users');
$id=$_REQUEST['id'];


$user_customer=new User($id);
//print($user_customer->data['User Type']);  //User Type is not selected

$title=_('Supplier User');

$smarty->assign('user_class',$user_customer);
//$smarty->assign('user_store',$_REQUEST['store']);

$smarty->assign('title', $title);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
 $smarty->display('supplier_user.tpl');     


?>
