<?php
/*
 File: family.php

 UI family page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/


include_once 'common.php';
include_once 'common_date_functions.php';

include_once 'class.Family.php';
include_once 'class.Store.php';
include_once 'class.Department.php';



if (!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
	header('Location: index.php?error_no_family_key');
else
	$family_id=$_REQUEST['id'];

$family=new Family($family_id);
//$family->update_sales_averages();
if (!$family->id) {
	header('Location: stores.php?e=family_not_found');
	exit();
}


if (!( $user->can_view('stores') and in_array($family->data['Product Family Store Key'],$user->stores))) {
	header('Location: index.php');
	exit();
}

$store=new Store($family->data['Product Family Store Key']);
$department=new Department($family->get('Product Family Main Department Key'));

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product families');
$modify=$user->can_edit('stores');


$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);




$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');


if (isset($_REQUEST['block_view']) and in_array($_REQUEST['block_view'],array('details','sales','products','deals','web')) ) {
	$_SESSION['state']['family']['block_view']=$_REQUEST['block_view'];
}

$block_view=$_SESSION['state']['family']['block_view'];
$smarty->assign('block_view',$block_view);

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',

	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/edit.css',
	'css/calendar.css',
		'css/d3_calendar.css',

	'theme.css.php'
);


$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'uploader/uploader.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
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
	'family.js.php'

);

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


if (isset($_REQUEST['view'])) {
	$valid_views=array('sales','general','stoke');
	if (in_array($_REQUEST['view'], $valid_views))
		$_SESSION['state']['families']['products']['view']=$_REQUEST['view'];

}

$department_order=$_SESSION['state']['department']['products']['order'];
$department_period=$_SESSION['state']['department']['products']['period'];
$department_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));


//$smarty->assign('department_period',$department_period);
//$smarty->assign('department_period_title',$department_period_title[$department_period]);



$smarty->assign('parent','products');
$smarty->assign('title',$family->get('Product Family Code').' - '.$family->get('Product Family Name'));


$product_home="Products Home";
$smarty->assign('home',$product_home);




$smarty->assign('family',$family);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

$smarty->assign('department',$department);



$smarty->assign('product_view',$_SESSION['state']['family']['products']['view']);
$smarty->assign('product_period',$_SESSION['state']['family']['products']['period']);
$smarty->assign('product_avg',$_SESSION['state']['family']['products']['avg']);

$tipo_filter=$_SESSION['state']['family']['products']['f_field'];
$smarty->assign('filter_name0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['family']['products']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Product code starting with <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Product name containing <i>x</i>'),'label'=>_('Name'))
);

$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);


$tipo_filter1=$_SESSION['state']['family']['product_sales']['f_field'];
$smarty->assign('filter_name1',$tipo_filter1);
$smarty->assign('filter_value1',$_SESSION['state']['family']['product_sales']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Product code starting with <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Product name containing <i>x</i>'),'label'=>_('Name'))
);

$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter1]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['family']['pages']['f_field'];
$smarty->assign('filter4',$tipo_filter);
$smarty->assign('filter_value4',$_SESSION['state']['family']['pages']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>'Page code starting with  <i>x</i>','label'=>'Code'),
	'title'=>array('db_key'=>'code','menu_label'=>'Page title like  <i>x</i>','label'=>'Code'),

);
$smarty->assign('filter_menu4',$filter_menu);
$smarty->assign('filter_name4',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);


$table_title=_('List');
$smarty->assign('table_title',$table_title);

$info_period_menu=array(
	array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
	,array("period"=>'month','label'=>_('last Month'),'title'=>_('last Month'))
	,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
	,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
	,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
);
$smarty->assign('info_period_menu',$info_period_menu);



$smarty->assign('title',_('Family').': '.$family->get('Product Family Name'));



$mode_options=array(
	array('mode'=>'percentage','label'=>_('Percentages')),
	array('mode'=>'value','label'=>_('Sales Amount')),
);
if ($_SESSION['state']['family']['products']['percentages']) {
	$display_mode='percentages';
	$display_mode_label=_('Percentages');
}else {
	$display_mode='value';
	$display_mode_label=_('Sales Amount');
}
$smarty->assign('display_products_mode',$display_mode);
$smarty->assign('display_products_mode_label',$display_mode_label);
$smarty->assign('products_mode_options_menu',$mode_options);
$smarty->assign('products_table_type',$_SESSION['state']['family']['products']['table_type']);


$table_type_options=array(
	'list'=>array('mode'=>'list','label'=>_('List')),
	'thumbnails'=>array('mode'=>'thumbnails','label'=>_('Thumbnails')),
);
$smarty->assign('products_table_type',$_SESSION['state']['family']['products']['table_type']);
$smarty->assign('products_table_type_label',$table_type_options[$_SESSION['state']['family']['products']['table_type']]['label']);
$smarty->assign('products_table_type_menu',$table_type_options);

$order=$_SESSION['state']['department']['families']['order'];
if ($order=='code') {
	$order='`Product Family Code`';
	$order_label=_('Code');
} else {
	$order='`Product Family Code`';
	$order_label=_('Code');
}
$_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Product Family Key` as id , `Product Family Code` as name from `Product Family Dimension`  where  `Product Family Main Department Key`=%d  and %s < %s  order by %s desc  limit 1",
	$family->data['Product Family Main Department Key'],
	$order,
	prepare_mysql($family->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$prev['link']='family.php?id='.$row['id'];
	$prev['title']=$row['name'];
	$prev['to_end']=false;

	$smarty->assign('family_prev',$prev);
}else {
	$sql=sprintf("select `Product Family Key` as id , `Product Family Code` as name from `Product Family Dimension`  where  `Product Family Main Department Key`=%d  and %s > %s  order by %s desc  limit 1",
		$family->data['Product Family Main Department Key'],
		$order,
		prepare_mysql($family->get($_order)),
		$order
	);

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$prev['link']='family.php?id='.$row['id'];
		$prev['title']=$row['name'];
		$prev['to_end']=true;
		$smarty->assign('family_prev',$prev);
	}

}


$sql=sprintf("select`Product Family Key` as id , `Product Family Code` as name from `Product Family Dimension`  where  `Product Family Main Department Key`=%d   and  %s>%s  order by %s   ",
	$family->data['Product Family Main Department Key'],
	$order,
	prepare_mysql($family->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$next['link']='family.php?id='.$row['id'];
	$next['title']=$row['name'];
	$next['to_end']=false;
	$smarty->assign('family_next',$next);
}else {
	$sql=sprintf("select`Product Family Key` as id , `Product Family Code` as name from `Product Family Dimension`  where  `Product Family Main Department Key`=%d   and  %s<%s  order by %s   ",
		$family->data['Product Family Main Department Key'],
		$order,
		prepare_mysql($family->get($_order)),
		$order
	);

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$next['link']='family.php?id='.$row['id'];
		$next['title']=$row['name'];
		$next['to_end']=true;
		$smarty->assign('family_next',$next);
	}

}




$family_order=$_SESSION['state']['family']['products']['order'];


$smarty->assign('sales_sub_block_tipo',$_SESSION['state']['family']['sales_sub_block_tipo']);


//$smarty->assign('product_sales_elements',$_SESSION['state']['family']['product_sales']['elements']);
$smarty->assign('elements',$_SESSION['state']['family']['products']['elements']);



$smarty->assign('filter_name2','');
$smarty->assign('filter_value2','');
$smarty->assign('sticky_note',$family->data['Product Family Sticky Note']);


$elements_number=array('Notes'=>0,'Changes'=>0,'Attachments'=>0);
$sql=sprintf("select count(*) as num , `Type` from  `Product Family History Bridge` where `Family Key`=%d group by `Type`",$family->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=$row['num'];
}
$smarty->assign('elements_family_history_number',$elements_number);
$smarty->assign('elements_family_history',$_SESSION['state']['family']['history']['elements']);

$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with  notes *<i>x</i>*'),'label'=>_('Notes')),
	//   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Done by')),
	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)'))
);
$tipo_filter=$_SESSION['state']['family']['history']['f_field'];
$filter_value=$_SESSION['state']['family']['history']['f_value'];

$smarty->assign('filter_value5',$filter_value);
$smarty->assign('filter_menu5',$filter_menu);
$smarty->assign('filter_name5',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu5',$paginator_menu);


$tipo_filter=$_SESSION['state']['family']['offers']['f_field'];
$smarty->assign('filter10',$tipo_filter);
$smarty->assign('filter_value10',$_SESSION['state']['family']['offers']['f_value']);
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Offers with name like *<i>x</i>*'),'label'=>_('Name')),
	'code'=>array('db_key'=>'code','menu_label'=>_('Offers with code like x</i>*'),'label'=>_('Code')),
);
$smarty->assign('filter_menu10',$filter_menu);

$smarty->assign('filter_name10',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu10',$paginator_menu);



$smarty->assign('elements_product_elements_type',$_SESSION['state']['family']['products']['elements_type']);
$smarty->assign('elements_type',$_SESSION['state']['family']['products']['elements']['type']);
$smarty->assign('elements_web',$_SESSION['state']['family']['products']['elements']['web']);
$smarty->assign('elements_stock',$_SESSION['state']['family']['products']['elements']['stock']);
$smarty->assign('elements_stock_aux',$_SESSION['state']['family']['products']['elements_stock_aux']);


if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['family']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['family']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['family']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);

$_SESSION['state']['family']['period']=$period;
$_SESSION['state']['family']['from']=$from;
$_SESSION['state']['family']['to']=$to;
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');

$sales_history_timeline_group=$_SESSION['state']['family']['sales_history']['timeline_group'];
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

if ($family->data['Product Family Max Day Sales']>0) {
	$top_range=$family->data['Product Family Avg with Sale Day Sales']+(3*$family->data['Product Family STD with Sale Day Sales']);
	if ($family->data['Product Family Max Day Sales']<$top_range) {
		$sales_max_sample_domain=$family->data['Product Family Max Day Sales'];
	}else {
		$sales_max_sample_domain=$top_range;
	}
}

$smarty->assign('sales_max_sample_domain',$sales_max_sample_domain);

$smarty->display('family.tpl');


?>
