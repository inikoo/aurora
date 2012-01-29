<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

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
	'common.css',
	'css/container.css',
	'button.css',
	'table.css',
	'css/index.css',
	'theme.css.php'

);


$dashboard_data=array();
$sql=sprintf("select * from `Dashboard Dimension` where `User Key`=%d order by `Dashboard Key` ",$user->id);
//print $sql;
$res=mysql_query($sql);
$number_dashboards=0;



while ($row=mysql_fetch_assoc($res)) {
$sql=sprintf("select * from `Dashboard Widget Bridge` where `Dashboard Key`=%d", $row['Dashboard Key']);
$result=mysql_query($sql);

	$dashboard_data[$row['Dashboard Key']]=array('order'=>$row['Dashboard Order'], 'number_of_widgets'=> mysql_num_rows($result));
	$number_dashboards++;
}


//print_r($dashboard_data);
$smarty->assign('dashboard_data',$dashboard_data);
$smarty->assign('number_dashboards',$number_dashboards);
$smarty->assign('default_dashboard_key',$user->data['User Dashboard Key']);



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


	'edit_dashboards.js.php?user_id='.$user->id,
);

$smarty->assign('user_id',$user->id);
$smarty->assign('parent','home');
$smarty->assign('title', _('Dashboard Configuration'));

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$tipo_filter0=$_SESSION['state']['dashboards']['active_widgets']['f_field'];
$smarty->assign('filter0',$tipo_filter0);
$smarty->assign('filter_value0',$_SESSION['state']['dashboards']['active_widgets']['f_value']);
$filter_menu0=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Widget name starting with  <i>x</i>'),'label'=>_('Name')),
	'description'=>array('db_key'=>'description','menu_label'=>_('Widget description starting with  <i>x</i>'),'label'=>_('Description')),

);
$smarty->assign('filter_menu0',$filter_menu0);
$smarty->assign('filter_name0',$filter_menu0[$tipo_filter0]['label']);
$paginator_menu0=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu0);



$smarty->display('edit_dashboards.tpl');
?>
