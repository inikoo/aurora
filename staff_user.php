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


$modify=true;

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 
		 'button.css',
		 'container.css'
		 );

include_once('Theme.php');

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
         'staff_user.js.php'   		
		);


$smarty->assign('parent','users');
$id=$_REQUEST['id'];


$user_staff=new User($id);
// print($user_staff->data['User Type']);  //User Type is not selected


    $_SESSION['state']['user']['id']=$user_staff->id;

$block_view=$_SESSION['state']['user']['block_view'];
$smarty->assign('block_view',$block_view);

if ($modify) {
	$general_options_list[]=array('class'=>'edit','tipo'=>'url','url'=>'change_user_theme.php?id='.$user_staff->id,'label'=>_('Change Theme'));
    $general_options_list[]=array('class'=>'edit','tipo'=>'js','id'=>'change_password','label'=>_('Change Password'));
}


$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');




$title=_('Staff User');
$smarty->assign('user_class',$user_staff);
$smarty->assign('staff_user',$user);


$smarty->assign('title', $title);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
 $smarty->display('staff_user.tpl');     


?>
