<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/

include_once 'common.php';
include_once 'class.Warehouse.php';




if (isset($_REQUEST['warehouse_id']) and is_numeric($_REQUEST['warehouse_id']) ) {
	$warehouse_id=$_REQUEST['warehouse_id'];

}else {
	header('Location: index.php?msg=error_no_warehouse_key');
	exit;
}


$warehouse=new warehouse($warehouse_id);
if (!$warehouse->id) {
	header('Location: index.php?msg=error_warehouse_not_found');
	exit;
}


if (!($user->can_view('warehouses') and in_array($warehouse_id,$user->warehouses)   ) ) {
	header('Location: index.php');
	exit;
}

if (isset($_REQUEST['date'])  ) {
	$date=strtotime($_REQUEST['date']);

}else {
	header('Location: inventory.php?warehouse_id='.$warehouse->id.'&block_view=history&msg=wrong_date');
	exit;
}

$date_mysql_format=date("Y-m-d",$date);


$modify=$user->can_edit('warehouses');

$smarty->assign('modify',$modify);


$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');
$smarty->assign('formated_date',strftime("%a %x",$date));
$smarty->assign('date',date("Y-m-d",$date));




$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'common.css',
	'css/container.css',
	'button.css',
	'table.css',
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
	'external_libs/amstock/amstock/swfobject.js',
	'js/table_common.js',
	'js/search.js',
	'js/parts_common.js',
	'stock_history_parts.js.php'

);


if (isset($_REQUEST['block_view']) and in_array($_REQUEST['block_view'],array('overview','parts','movements'))) {
	$block_view=$_REQUEST['block_view'];
	$_SESSION['state']['stock_history']['block_view']=$block_view;

}else {
	$block_view=$_SESSION['state']['stock_history']['block_view'];
}

$smarty->assign('block_view',$block_view);




$smarty->assign('parent','parts');
$smarty->assign('title', _('Inventory (Parts)'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$prev=array(
	'title'=>_('Historic Inventory').' '.strftime("%a %x",strtotime("$date_mysql_format -1 day")),
	'link'=>sprintf('stock_history_parts.php?warehouse_id=%d&date=%s',$warehouse->id,date("Y-m-d",strtotime("$date_mysql_format -1 day")))
);
$smarty->assign('prev',$prev);

if (date('U',$date)<date('U') ) {
	$next=array(
		'title'=>_('Historic Inventory').' '.strftime("%a %x",strtotime("$date_mysql_format +1 day")),
		'link'=>sprintf('stock_history_parts.php?warehouse_id=%d&date=%s',$warehouse->id,date("Y-m-d",strtotime("$date_mysql_format +1 day")))
	);
	$smarty->assign('next',$next);
}





$tipo_filter=$_SESSION['state']['stock_history']['transactions']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['stock_history']['transactions']['f_value']);
$filter_menu=array(
	'note'=>array('db_key'=>'note','menu_label'=>_('Node'),'label'=>_('Note')),
);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);




$tipo_filter=$_SESSION['state']['stock_history']['parts']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['stock_history']['parts']['f_value']);
$filter_menu=array(
	'sku'=>array('db_key'=>_('code'),'menu_label'=>'Part SKU','label'=>'SKU'),
	'used_in'=>array('db_key'=>_('used_in'),'menu_label'=>'Used in','label'=>'Used in'),

);
$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);



$smarty->assign('warehouse',$warehouse);
$smarty->assign('warehouse_id',$warehouse->id);
$smarty->assign('transaction_type',$_SESSION['state']['stock_history']['transactions']['view']);




$smarty->display('stock_history_parts.tpl');
?>
