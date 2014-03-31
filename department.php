<?php
/*
 File: department.php

 UI department page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'common.php';
include_once('common_date_functions.php');

include_once 'class.Store.php';
include_once 'class.Department.php';


if (!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']) ) {
	header('Location: index.php?e=no_department_key');
	exit();
}else {
	$department_id=$_REQUEST['id'];

}
$department=new Department($department_id);
//$department->update_sales_averages();
if (!$department->id) {
	header('Location: stores.php?e=department_not_found');
	exit();
}


if (!( $user->can_view('stores') and in_array($department->data['Product Department Store Key'],$user->stores))) {
	header('Location: index.php');
	exit();
}
$store=new Store($department->get('Product Department Store Key'));


$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product families');
$modify=$user->can_edit('stores');


$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);



$smarty->assign('table_type',$_SESSION['state']['department']['table_type']);


//$smarty->assign('restrictions',$_SESSION['state']['department']['restrictions']);


$smarty->assign('store_key',$store->id);

$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');

	

if(isset($_REQUEST['block_view']) and in_array($_REQUEST['block_view'],array('details','sales','categories','families','products','deals','web')) ){

$_SESSION['state']['department']['block_view']=$_REQUEST['block_view'];
}

$block_view=$_SESSION['state']['department']['block_view'];
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
	'css/d3_calendar.css',
	'theme.css.php'
);
$js_files=array(

	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'history/history-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/assets_common.js',
	'js/search.js',
	
	'js/localize_calendar.js',
	'js/calendar_interval.js',
	'js/reports_calendar.js',
	'js/notes.js',
	'js/asset_elements.js',
		'js/d3.v3.min.js',
	'js/d3_calendar_asset_sales.js',
	'department.js.php'

);

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if (isset($_REQUEST['view'])) {
	$valid_views=array('sales','general','stoke');
	if (in_array($_REQUEST['view'], $valid_views))
		$_SESSION['state']['department']['view']=$_REQUEST['view'];

}

$smarty->assign('parent','products');

$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$department);
$smarty->assign('store',$store);





$smarty->assign('family_view',$_SESSION['state']['department']['families']['view']);
$smarty->assign('family_show_percentages',$_SESSION['state']['department']['families']['percentages']);
$smarty->assign('family_avg',$_SESSION['state']['department']['families']['avg']);
$smarty->assign('family_period',$_SESSION['state']['department']['families']['period']);


$smarty->assign('department_period',$_SESSION['state']['store']['departments']['period']);




$tipo_filter=$_SESSION['state']['department']['families']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['department']['families']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Family code starting with <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Family name containing <i>x</i>'),'label'=>_('Name'))
);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('families',$department->data['Product Department Families']);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->assign('product_view',$_SESSION['state']['department']['products']['view']);
$smarty->assign('product_show_percentages',$_SESSION['state']['department']['products']['percentages']);
$smarty->assign('product_avg',$_SESSION['state']['department']['products']['avg']);
$smarty->assign('product_period',$_SESSION['state']['department']['products']['period']);

$tipo_filter=$_SESSION['state']['department']['products']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['department']['products']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Product code starting with <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Product name containing <i>x</i>'),'label'=>_('Name'))

);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('products',$department->data['Product Department For Public Sale Products']);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);

$smarty->assign('paginator_menu1',$paginator_menu);





$smarty->assign('title',$department->get('Product Department Name'));






$mode_options=array(
	array('mode'=>'percentage','label'=>_('Percentages')),
	array('mode'=>'value','label'=>_('Sales Amount')),
);

if ($_SESSION['state']['department']['families']['percentages']) {
	$display_mode='percentages';
	$display_mode_label=_('Percentages');
}else {
	$display_mode='value';
	$display_mode_label=_('Sales Amount');
}
$smarty->assign('display_families_mode',$display_mode);
$smarty->assign('display_families_mode_label',$display_mode_label);
$smarty->assign('families_mode_options_menu',$mode_options);
$smarty->assign('families_table_type',$_SESSION['state']['department']['families']['table_type']);


if ($_SESSION['state']['department']['products']['percentages']) {
	$display_mode='percentages';
	$display_mode_label=_('Percentages');
}else {
	$display_mode='value';
	$display_mode_label=_('Sales Amount');
}
$smarty->assign('display_products_mode',$display_mode);
$smarty->assign('display_products_mode_label',$display_mode_label);
$smarty->assign('products_mode_options_menu',$mode_options);


$table_type_options=array(
	'list'=>array('mode'=>'list','label'=>_('List')),
	'thumbnails'=>array('mode'=>'thumbnails','label'=>_('Thumbnails')),

);
$smarty->assign('products_table_type',$_SESSION['state']['department']['products']['table_type']);
$smarty->assign('products_table_type_label',$table_type_options[$_SESSION['state']['department']['products']['table_type']]['label']);
$smarty->assign('products_table_type_menu',$table_type_options);

$smarty->assign('families_table_type',$_SESSION['state']['department']['families']['table_type']);
$smarty->assign('families_table_type_label',$table_type_options[$_SESSION['state']['department']['families']['table_type']]['label']);
$smarty->assign('families_table_type_menu',$table_type_options);


$tipo_filter=($_SESSION['state']['department']['pages']['f_field']);
$smarty->assign('filter4',$tipo_filter);
$smarty->assign('filter_value4',$_SESSION['state']['department']['pages']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Page code starting with  <i>x</i>'),'label'=>_('Code')),
	'title'=>array('db_key'=>'title','menu_label'=>_('Page title like  <i>x</i>'),'label'=>_('Title')),
);
$smarty->assign('filter_menu4',$filter_menu);
$smarty->assign('filter_name4',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu4',$paginator_menu);
$smarty->assign('sites_table_type',$_SESSION['state']['department']['pages']['table_type']);



$order=$_SESSION['state']['store']['departments']['order'];
$store_period=$_SESSION['state']['store']['departments']['period'];
$store_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));

$db_interval=get_interval_db_name($store_period);

if ($order=='families')
	$order='`Product Department Families`';
elseif ($order=='todo')
	$order='`Product Department In Process Products`';
elseif ($order=='profit') {

	$order="`Product Department $db_interval Acc Profit`";
}
elseif ($order=='sales') {
	$order="`Product Department $db_interval Acc Invoiced Amount`";



}
elseif ($order=='name')
	$order='`Product Department Name`';
elseif ($order=='code')
	$order='`Product Department Code`';
elseif ($order=='active')
	$order='`Product Department For Sale Products`';
elseif ($order=='outofstock')
	$order='`Product Department Out Of Stock Products`';
elseif ($order=='stock_error')
	$order='`Product Department Unknown Stock Products`';
elseif ($order=='surplus')
	$order='`Product Department Surplus Availability Products`';
elseif ($order=='optimal')
	$order='`Product Department Optimal Availability Products`';
elseif ($order=='low')
	$order='`Product Department Low Availability Products`';
elseif ($order=='critical')
	$order='`Product Department Critical Availability Products`';
else {
	$order='`Product Department Code`';
}


$_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Product Department Key` as id , `Product Department Code` as name from `Product Department Dimension`  where  `Product Department Store Key`=%d  and %s < %s  order by %s desc  limit 1",
	$department->data['Product Department Store Key'],
	$order,
	prepare_mysql($department->get($_order)),
	$order
);

$result=mysql_query($sql);

if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$prev['link']='department.php?id='.$row['id'];
	$prev['title']=$row['name'];
	$prev['to_end']=false;
	$smarty->assign('prev',$prev);
}else {
	$sql=sprintf("select `Product Department Key` as id , `Product Department Code` as name from `Product Department Dimension`  where  `Product Department Store Key`=%d  and %s > %s  order by %s desc  limit 1",
		$department->data['Product Department Store Key'],
		$order,
		prepare_mysql($department->get($_order)),
		$order
	);

	$result=mysql_query($sql);

	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$prev['link']='department.php?id='.$row['id'];
		$prev['title']=$row['name'];
		$prev['to_end']=true;
		$smarty->assign('prev',$prev);
	}
}


$sql=sprintf(" select `Product Department Key` as id , `Product Department Code` as name from `Product Department Dimension`  where  `Product Department Store Key`=%d   and  %s>%s  order by %s   ",
	$department->data['Product Department Store Key'],
	$order,
	prepare_mysql($department->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$next['link']='department.php?id='.$row['id'];
	$next['title']=$row['name'];
	$next['to_end']=false;
	$smarty->assign('next',$next);
}else {
	$sql=sprintf(" select `Product Department Key` as id , `Product Department Code` as name from `Product Department Dimension`  where  `Product Department Store Key`=%d   and  %s<%s  order by %s   ",
		$department->data['Product Department Store Key'],
		$order,
		prepare_mysql($department->get($_order)),
		$order
	);

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$next['link']='department.php?id='.$row['id'];
		$next['title']=$row['name'];
		$next['to_end']=true;
		$smarty->assign('next',$next);
	}

}

$plot_data=array('pie'=>array('forecast'=>3,'interval'=>''));
$smarty->assign('plot_tipo','store');
$smarty->assign('plot_data',$plot_data);

$smarty->assign('sales_sub_block_tipo',$_SESSION['state']['department']['sales_sub_block_tipo']);





//$smarty->assign('family_sales_elements',$_SESSION['state']['department']['family_sales']['elements']);
//$smarty->assign('product_sales_elements',$_SESSION['state']['department']['product_sales']['elements']);
$smarty->assign('family_elements',$_SESSION['state']['department']['families']['elements']);
$smarty->assign('product_elements',$_SESSION['state']['department']['products']['elements']);





$smarty->assign('filter_name2','');
$smarty->assign('filter_value2','');


$tipo_filter=$_SESSION['state']['department']['family_sales']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['department']['family_sales']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Family code starting with <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Family name containing <i>x</i>'),'label'=>_('Name'))
);
$smarty->assign('filter_menu3',$filter_menu);
$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);

$smarty->assign('filter_name5','');
$smarty->assign('filter_value5','');
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu5',$paginator_menu);

$smarty->assign('filter_name6','');
$smarty->assign('filter_value6','');
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu6',$paginator_menu);

$smarty->assign('sticky_note',$department->data['Product Department Sticky Note']);


$elements_number=array('Notes'=>0,'Changes'=>0,'Attachments'=>0);
$sql=sprintf("select count(*) as num , `Type` from  `Product Department History Bridge` where `Department Key`=%d group by `Type`",$department->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=$row['num'];
}
$smarty->assign('elements_department_history_number',$elements_number);
$smarty->assign('elements_department_history',$_SESSION['state']['department']['history']['elements']);

$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with  notes *<i>x</i>*'),'label'=>_('Notes')),
	//   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Done by')),
	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)'))
);
$tipo_filter=$_SESSION['state']['department']['history']['f_field'];
$filter_value=$_SESSION['state']['department']['history']['f_value'];

$smarty->assign('filter_value7',$filter_value);
$smarty->assign('filter_menu7',$filter_menu);
$smarty->assign('filter_name7',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu7',$paginator_menu);

$smarty->assign('elements_product_elements_type',$_SESSION['state']['department']['products']['elements_type']);
$smarty->assign('elements_type',$_SESSION['state']['department']['products']['elements']['type']);
$smarty->assign('elements_web',$_SESSION['state']['department']['products']['elements']['web']);
$smarty->assign('elements_stock',$_SESSION['state']['department']['products']['elements']['stock']);
$smarty->assign('elements_stock_aux',$_SESSION['state']['department']['products']['elements_stock_aux']);

if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['department']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['department']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['department']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);

$_SESSION['state']['department']['period']=$period;
$_SESSION['state']['department']['from']=$from;
$_SESSION['state']['department']['to']=$to;
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');

$sales_history_timeline_group=$_SESSION['state']['department']['sales_history']['timeline_group'];
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

$sales_max_sample_domain=1;
if ($department->data['Product Department Max Day Sales']>0) {
	$top_range=$department->data['Product Department Avg with Sale Day Sales']+(3*$department->data['Product Department STD with Sale Day Sales']);
	if ($department->data['Product Department Max Day Sales']<$top_range) {
		$sales_max_sample_domain=$department->data['Product Department Max Day Sales'];
	}else {
		$sales_max_sample_domain=$top_range;
	}
}


$smarty->assign('sales_max_sample_domain',$sales_max_sample_domain);

$smarty->display('department.tpl');



?>
