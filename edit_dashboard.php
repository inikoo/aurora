<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/



include_once 'common.php';

include_once 'class.Product.php';
include_once 'class.Order.php';


$smarty->assign('store_keys',join(',',$user->stores));

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/index.css',
	'theme.css.php'
);


$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'js/table_common.js',
	'js/edit_common.js',
	'edit_dashboard.js.php',
);

if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])){
header('Location: edit_dashboards.php');
	exit;
}

$dashboard_key=$_REQUEST['id'];
	$sql=sprintf("select * from  `Dashboard Dimension` where `Dashboard Key`=%d", $dashboard_key);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

	}else {
	header('Location: edit_dashboards.php');
	exit;

	}

$smarty->assign('dashboard_key',$dashboard_key);

$smarty->assign('user_key',$user->id);
$smarty->assign('parent','home');
$smarty->assign('title', _('Dashboard Configuration'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);





$tipo_filter0=$_SESSION['state']['dashboards']['widgets']['f_field'];
$smarty->assign('filter0',$tipo_filter0);
$smarty->assign('filter_value0',$_SESSION['state']['dashboards']['widgets']['f_value']);
$filter_menu0=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Widget name starting with  <i>x</i>'),'label'=>_('Name')),
	'description'=>array('db_key'=>'description','menu_label'=>_('Widget description starting with  <i>x</i>'),'label'=>_('Description')),

);
$smarty->assign('filter_menu0',$filter_menu0);
$smarty->assign('filter_name0',$filter_menu0[$tipo_filter0]['label']);
$paginator_menu0=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu0);

$tipo_filter1=$_SESSION['state']['dashboard']['active_widgets']['f_field'];
$smarty->assign('filter1',$tipo_filter1);
$smarty->assign('filter_value1',$_SESSION['state']['dashboard']['active_widgets']['f_value']);
$filter_menu1=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Widget name starting with  <i>x</i>'),'label'=>_('Name')),
	'description'=>array('db_key'=>'description','menu_label'=>_('Widget description starting with  <i>x</i>'),'label'=>_('Description')),

);
$smarty->assign('filter_menu1',$filter_menu1);
$smarty->assign('filter_name1',$filter_menu1[$tipo_filter1]['label']);
$paginator_menu1=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu1);

$smarty->display('edit_dashboard.tpl');
?>
