<?php
/*
 File: user.php

 UI index page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once 'common.php';


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
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
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
		'sha256.js.php',
	'js/change_password.js',
	'js/edit_common.js',
'admin_user.js.php'

);


$smarty->assign('search_label',_('Users'));
$smarty->assign('search_scope','users');


$smarty->assign('parent','users');

$root=new User('Warehouse');


$smarty->assign('user_class',$root);

$title=_('Administrative User');
$tpl='admin_user.tpl';

$block_view='login_history';
$smarty->assign('block_view',$block_view);


$smarty->assign('modify',true);



$tipo_filter=$_SESSION['state']['staff_user']['login_history']['f_field'];
$filter_value=$_SESSION['state']['staff_user']['login_history']['f_value'];

$filter_menu=array(
	'ip'=>array('db_key'=>'ip','menu_label'=>'Records IP address like *<i>x</i>*','label'=>_('IP Address')),

);

$smarty->assign('filter_value0',$filter_value);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->assign('title', $title);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display($tpl);


?>
