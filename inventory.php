<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/

include_once 'common.php';
include_once 'class.Warehouse.php';
include_once('common_date_functions.php');



if (isset($_REQUEST['warehouse_id']) and is_numeric($_REQUEST['warehouse_id']) ) {
	$warehouse_id=$_REQUEST['warehouse_id'];

}else {

	if (count($user->warehouses)==0) {
		header('Location: index.php?error_no_warehouse_key');
		exit;
	}else {
		header('Location: inventory.php?warehouse_id='.$user->warehouses[0]);
		exit;


	}


}


$warehouse=new warehouse($warehouse_id);
if (!($user->can_view('warehouses') and in_array($warehouse_id,$user->warehouses)   ) ) {
	header('Location: index.php');
	exit;
}
$modify=$user->can_edit('warehouses');

$smarty->assign('modify',$modify);
$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');

$smarty->assign('parts_view',$_SESSION['state']['warehouse']['parts']['view']);
$smarty->assign('parts_period',$_SESSION['state']['warehouse']['parts']['period']);
$smarty->assign('parts_avg',$_SESSION['state']['warehouse']['parts']['avg']);





if (isset($_REQUEST['block_view']) and in_array($_REQUEST['block_view'],array('history','parts','movements'))) {
	$block_view=$_REQUEST['block_view'];
	$_SESSION['state']['warehouse']['parts_view']=$block_view;

}else {
	$block_view=$_SESSION['state']['warehouse']['parts_view'];
}

$smarty->assign('block_view',$block_view);

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
	$yui_path.'datasource/datasource-debug.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'external_libs/amstock/amstock/swfobject.js',
	'js/table_common.js',
	'js/search.js',
	'js/export_common.js',
	'js/parts_common.js'

);


if ($block_view=='parts') {
	$js_files[]='inventory_light.js.php';
}else {

	$js_files[]='js/localize_calendar.js';
	$js_files[]='js/calendar_interval.js';
	$js_files[]='js/reports_calendar.js';
	$js_files[]='inventory.js.php';
}


$smarty->assign('parent','parts');
$smarty->assign('title', _('Inventory (Parts)'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$smarty->assign('show_stock_history_chart',$_SESSION['state']['warehouse']['stock_history']['show_chart']);
$smarty->assign('stock_history_chart_output',$_SESSION['state']['warehouse']['stock_history']['chart_output']);




$stock_history_timeline_group=$_SESSION['state']['warehouse']['stock_history']['timeline_group'];
$smarty->assign('stock_history_timeline_group',$stock_history_timeline_group);
switch ($stock_history_timeline_group) {
case 'day':
	$stock_history_timeline_group_label=_('Daily');
	break;
case 'week':
	$stock_history_timeline_group_label=_('Weekly (end of week)');
	break;
case 'month':
	$stock_history_timeline_group_label=_('Monthy (end of Month)');
	break;
default:
	$stock_history_timeline_group_label=$stock_history_timeline_group;
}
$smarty->assign('stock_history_timeline_group_label',$stock_history_timeline_group_label);

$timeline_group_stock_history_options=array(
	array('mode'=>'day','label'=>_('Daily')),
	array('mode'=>'week','label'=>_('Weekly (end of week)')),
	array('mode'=>'month','label'=>_('Monthy (end of Month)'))

);
$smarty->assign('timeline_group_stock_history_options',$timeline_group_stock_history_options);





$tipo_filter=$_SESSION['state']['warehouse']['transactions']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['warehouse']['transactions']['f_value']);
$filter_menu=array(
	'note'=>array('db_key'=>'note','menu_label'=>_('Node'),'label'=>_('Note')),
);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['warehouse']['stock_history']['f_field'];
$smarty->assign('filter_show0',$_SESSION['state']['warehouse']['stock_history']['f_show']);
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['warehouse']['stock_history']['f_value']);
$filter_menu=array(
	'location'=>array('db_key'=>'location','menu_label'=>_('Location'),'label'=>_('Location')),
);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$tipo_filter=$_SESSION['state']['warehouse']['parts']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['warehouse']['parts']['f_value']);
$filter_menu=array(
	'sku'=>array('db_key'=>'sku','menu_label'=>_('Part SKU'),'label'=>_('SKU')),
	'reference'=>array('db_key'=>'reference','menu_label'=>_('Part Reference'),'label'=>_('Reference')),
	'used_in'=>array('db_key'=>'used_in','menu_label'=>_('Used in'),'label'=>_('Used in')),

);
$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);



$smarty->assign('warehouse',$warehouse);
$smarty->assign('warehouse_id',$warehouse->id);



$smarty->assign('elements_use',$_SESSION['state']['warehouse']['parts']['elements']['use']);
$smarty->assign('elements_state',$_SESSION['state']['warehouse']['parts']['elements']['state']);
$smarty->assign('elements_stock_state',$_SESSION['state']['warehouse']['parts']['elements']['stock_state']);
$smarty->assign('elements_next_shipment',$_SESSION['state']['warehouse']['parts']['elements']['next_shipment']);

$smarty->assign('elements_part_elements_type',$_SESSION['state']['warehouse']['parts']['elements_type']);

$smarty->assign('stock_history_block',$_SESSION['state']['warehouse']['stock_history_block']);

include 'parts_export_common.php';


if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['warehouse']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['warehouse']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['warehouse']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);

$_SESSION['state']['warehouse']['period']=$period;
$_SESSION['state']['warehouse']['from']=$from;
$_SESSION['state']['warehouse']['to']=$to;
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');



$smarty->assign('transactions_type_elements',$_SESSION['state']['warehouse']['transactions']['elements']);

$smarty->display('inventory.tpl');
?>
