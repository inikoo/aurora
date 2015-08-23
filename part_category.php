<?php
/*
 File: customer.php

 UI customer page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/
include_once 'class.Category.php';
include_once 'class.Warehouse.php';

include_once 'common.php';
include_once 'common_date_functions.php';




if (!$user->can_view('warehouses')  ) {
	header('Location: index.php');
	exit;
}

if (isset($_REQUEST['id'])) {
	$category_key=$_REQUEST['id'];
} else {
	$category_key=0;
}

if (!$category_key) {
	header('Location: index.php?error_no_category_id');
	exit;
}


$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
//$modify=false;
$modify=$user->can_edit('stores');


$general_options_list=array();


$smarty->assign('view',$_SESSION['state']['part_categories']['view']);



$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/edit.css',
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
	'js/jquery.min.js',
'js/common.js',
	'js/export_common.js',
	'js/search.js',
	'js/table_common.js',
	'external_libs/ammap/ammap/swfobject.js',
	'js/parts_common.js',
	'js/edit_category_common.js',

	'js/localize_calendar.js',
	'js/calendar_interval.js',
	'js/reports_calendar.js',
	'edit_stock.js.php',
	'part_category.js.php',
);

$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');

$smarty->assign('subcategories_view',$_SESSION['state']['part_categories']['view']);

$smarty->assign('subcategories_period',$_SESSION['state']['part_categories']['period']);
$smarty->assign('subcategories_avg',$_SESSION['state']['part_categories']['avg']);

$smarty->assign('category_period',$_SESSION['state']['part_categories']['period']);






$category=new Category($category_key);
if (!$category->id) {

	header('Location: part_category_deleted.php?id='.$category_key);
	exit;

}





$category_key=  $category->id;
$warehouse=new Warehouse($category->data['Category Warehouse Key']);


$smarty->assign('category',$category);

if (isset($_REQUEST['block_view']) and in_array($_REQUEST['block_view'],array('subcategories','subjects','overview','history','sales'))) {
	$_SESSION['state']['part_categories']['block_view']=$_REQUEST['block_view'];
}



$state_type=($category->data['Category Branch Type']=='Head'?'head':'node');

$block_view=$_SESSION['state']['part_categories'][$state_type.'_block_view'];

$smarty->assign('state_type',$state_type);




$show_subcategories=true;
$show_subjects=true;
$show_subjects_data=true;

if ($category->data['Category Branch Type']!='Head') {
	$show_subjects=false;
	$show_subjects_data=false;

}

if ($category->data['Category Max Deep']<=$category->data['Category Deep']) {
	$show_subcategories=false;

}

if (!$show_subcategories and $block_view=='subcategories') {
	$block_view='overview';
}
if (!$show_subjects and $block_view=='subjects') {
	$block_view='overview';
}
if (!$show_subjects_data and $block_view=='sales') {
	$block_view='overview';
}

$smarty->assign('show_subcategories',$show_subcategories);
$smarty->assign('show_subjects',$show_subjects);
$smarty->assign('show_subjects_data',$show_subjects_data);
$smarty->assign('block_view',$block_view);


$tipo_filter=$_SESSION['state']['part_categories']['parts']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['part_categories']['parts']['f_value']);
$filter_menu=array(
	'reference'=>array('db_key'=>'reference','menu_label'=>_('Reference'),'label'=>_('Reference')),

	'used_in'=>array('db_key'=>'used_in','menu_label'=>_('Used in <i>x</i>'),'label'=>_('Used in')),
	'supplied_by'=>array('db_key'=>'supplied_by','menu_label'=>_('Supplied by <i>x</i>'),'label'=>_('Supplied by')),
	'description'=>array('db_key'=>'description','menu_label'=>_('Part Description like <i>x</i>'),'label'=>_('Description')),
	'sku'=>array('db_key'=>'sku','menu_label'=>_('Part SKU <i>x</i>'),'label'=>_('SKU')),

);
$smarty->assign('filter_menu0',$filter_menu);

$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('parts_view',$_SESSION['state']['part_categories']['parts']['view']);
$smarty->assign('parts_period',$_SESSION['state']['part_categories']['parts']['period']);
$smarty->assign('parts_avg',$_SESSION['state']['part_categories']['parts']['avg']);




$smarty->assign('elements_use',$_SESSION['state']['part_categories']['parts']['elements']['use']);
$smarty->assign('elements_state',$_SESSION['state']['part_categories']['parts']['elements']['state']);
$smarty->assign('elements_stock_state',$_SESSION['state']['part_categories']['parts']['elements']['stock_state']);
$smarty->assign('elements_part_elements_type',$_SESSION['state']['part_categories']['parts']['elements_type']);





$smarty->assign('warehouse_id',$warehouse->id);
$smarty->assign('warehouse',$warehouse);


$_SESSION['state']['part_categories']['category_key']=$category_key;




$order=$_SESSION['state']['part_categories']['subcategories']['order'];
if ($order=='code') {
	$order='`Category Code`';
	$order_label=_('Code');
} else {
	$order='`Category Label`';
	$order_label=_('Label');
}
$_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Category Key` as id , `Category Code` as name from `Category Dimension`  where  `Category Parent Key`=%d and `Category Root Key`=%d  and %s < %s  order by %s desc  limit 1",
	$category->data['Category Parent Key'],
	$category->data['Category Root Key'],
	$order,
	prepare_mysql($category->get($_order)),
	$order
);
//print $sql;
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$prev['link']='part_category.php?id='.$row['id'];
	$prev['title']=$row['name'];
	$smarty->assign('navigation_prev',$prev);
}
mysql_free_result($result);


$sql=sprintf(" select`Category Key` as id , `Category Code` as name from `Category Dimension`  where  `Category Parent Key`=%d  and `Category Root Key`=%d    and  %s>%s  order by %s   ",
	$category->data['Category Parent Key'],
	$category->data['Category Root Key'],
	$order,
	prepare_mysql($category->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$next['link']='part_category.php?id='.$row['id'];
	$next['title']=$row['name'];
	$smarty->assign('navigation_next',$next);
}
mysql_free_result($result);





$tipo_filter=$_SESSION['state']['part_categories']['subcategories']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['part_categories']['subcategories']['f_value']);

$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Category Code'),'label'=>_('Code')),
	'label'=>array('db_key'=>'code','menu_label'=>_('Category Label'),'label'=>_('Label')),

);


$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['store']['history']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['site']['history']['f_value']);
$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with  notes *<i>x</i>*'),'label'=>_('Notes')),
	'author'=>array('db_key'=>'author','menu_label'=>_('Done by <i>x</i>*'),'label'=>_('Notes')),
	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)')),
	'abstract'=>array('db_key'=>'abstract','menu_label'=>_('Records with abstract'),'label'=>_('Abstract'))

);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu2',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);


$tipo_filter=$_SESSION['state']['part_categories']['no_assigned_parts']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['part_categories']['no_assigned_parts']['f_value']);
$filter_menu=array(
	'sku'=>array('db_key'=>'sku','menu_label'=>_("SKU"),'label'=>_("SKU")),

	'used_in'=>array('db_key'=>'used_in','menu_label'=>_('Used in <i>x</i>'),'label'=>_('Used in')),
	'supplied_by'=>array('db_key'=>'supplied_by','menu_label'=>_('Supplied by <i>x</i>'),'label'=>_('Supplied by')),
	'description'=>array('db_key'=>'description','menu_label'=>_('Part Description like <i>x</i>'),'label'=>_('Description')),

);
$smarty->assign('filter_menu3',$filter_menu);

$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);



$smarty->assign('parent','parts');
$smarty->assign('title', _('Part Category').' '.$category->data['Category Code']);

$smarty->assign('subject','Part');
$smarty->assign('category_key',$category_key);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

include_once 'conf/period_tags.php';
unset($period_tags['hour']);
$smarty->assign('period_tags',$period_tags);

$plot_data=array('pie'=>array('forecast'=>3,'interval'=>''));
$smarty->assign('plot_tipo','store');
$smarty->assign('plot_data',$plot_data);

$elements_number=array('Changes'=>0,'Assign'=>0);
$sql=sprintf("select count(*) as num ,`Type` from  `Part Category History Bridge` where  `Category Key`=%d group by  `Type`",$category->id);
//print_r($sql);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=number($row['num']);
}
$smarty->assign('history_elements_number',$elements_number);
$smarty->assign('history_elements',$_SESSION['state']['part_categories']['history']['elements']);



$smarty->assign('sales_sub_block_tipo',$_SESSION['state']['part_categories']['sales_sub_block_tipo']);



if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['part_categories']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['part_categories']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['part_categories']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);

$_SESSION['state']['part_categories']['period']=$period;
$_SESSION['state']['part_categories']['from']=$from;
$_SESSION['state']['part_categories']['to']=$to;
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');


$smarty->assign('elements_part_category_use',$_SESSION['state']['part_categories']['subcategories']['elements']['use']);
$smarty->assign('elements_part_category_elements_type',$_SESSION['state']['part_categories']['subcategories']['elements_type']);


$modify_stock=$user->can_edit('product stock');
$smarty->assign('modify_stock',$modify_stock);

include 'parts_export_common.php';
$smarty->assign('filter_name4','');
$smarty->assign('filter_value4','');
$sales_history_timeline_group=$_SESSION['state']['part_categories']['sales_history']['timeline_group'];
$smarty->assign('sales_history_timeline_group',$sales_history_timeline_group);
switch ($sales_history_timeline_group) {
case 'day':
	$sales_history_timeline_group_label=_('Daily');
	break;
case 'week':
	$sales_history_timeline_group_label=_('Weekly (end of week)');
	break;
case 'month':
	$sales_history_timeline_group_label=_('Monthy (end of month)');
	break;
case 'year':
	$sales_history_timeline_group_label=_('Yearly');
	break;
default:
	$sales_history_timeline_group_label=$sales_history_timeline_group;
}
$smarty->assign('sales_history_timeline_group_label',$sales_history_timeline_group_label);

$timeline_group_sales_history_options=array(
	array('mode'=>'day','label'=>_('Daily')),
	array('mode'=>'week','label'=>_('Weekly (end of week)')),
	array('mode'=>'month','label'=>_('Monthy (end of month)')),
	array('mode'=>'year','label'=>_('Yearly'))

);
$smarty->assign('timeline_group_sales_history_options',$timeline_group_sales_history_options);

$smarty->display('part_category.tpl');
?>
