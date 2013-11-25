<?php
/*
 File: store.php

 UI store page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'common.php';

include_once 'class.Store.php';
include_once 'assets_header_functions.php';
include_once('common_date_functions.php');

$smarty->assign('page','store');
if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$store_id=$_REQUEST['id'];

} else {
	$store_id=$_SESSION['state']['store']['id'];
}

if (isset($_REQUEST['edit'])) {
	header('Location: edit_store.php?id='.$store_id);

	exit("E2");
}


if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
	header('Location: index.php');
	exit;
}



$store=new Store($store_id);
$store->update_number_sites();
$_SESSION['state']['store']['id']=$store->id;
$smarty->assign('store_key',$store->id);

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product departments');

$modify=$user->can_edit('stores');
$smarty->assign('modify',$modify);

$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);


$stores_order=$_SESSION['state']['stores']['stores']['order'];
$stores_period=$_SESSION['state']['stores']['stores']['period'];



if(isset($_REQUEST['view']) and in_array($_REQUEST['view'],array('details','sales','categories','departments','families','products','sites','deals','pages'))){
$_SESSION['state']['store']['block_view']=$_REQUEST['view'];
$block_view=$_SESSION['state']['store']['block_view'];
	
}else{
$block_view=$_SESSION['state']['store']['block_view'];
}
$smarty->assign('block_view',$block_view);


get_header_info($user,$smarty);


$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');

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
	$yui_path.'dragdrop/dragdrop-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/assets_common.js',
	'js/deals_common.js',
	'js/search.js',

	'js/localize_calendar.js',
	'js/calendar_interval.js',
	'js/reports_calendar.js',
	'js/notes.js',
	'js/asset_elements.js',
		'store.js.php',
);




$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('department_view',$_SESSION['state']['store']['departments']['view']);
$smarty->assign('department_show_percentages',$_SESSION['state']['store']['departments']['percentages']);
$smarty->assign('department_avg',$_SESSION['state']['store']['departments']['avg']);
$smarty->assign('department_period',$_SESSION['state']['store']['departments']['period']);



$tipo_filter=$_SESSION['state']['store']['departments']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['store']['departments']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Department code starting with  <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Department name containing <i>x</i>'),'label'=>_('Name'))

);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('departments',$store->data['Store Departments']);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->assign('family_view',$_SESSION['state']['store']['families']['view']);
$smarty->assign('family_show_percentages',$_SESSION['state']['store']['families']['percentages']);
$smarty->assign('family_avg',$_SESSION['state']['store']['families']['avg']);
$smarty->assign('family_period',$_SESSION['state']['store']['families']['period']);

$q='';
$tipo_filter=($q==''?$_SESSION['state']['store']['families']['f_field']:'code');
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',($q==''?$_SESSION['state']['store']['families']['f_value']:addslashes($q)));
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Family code starting with  <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Family name containing <i>x</i>'),'label'=>_('Name'))

);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('families',$store->data['Store Families']);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$smarty->assign('product_view',$_SESSION['state']['store']['products']['view']);
$smarty->assign('product_show_percentages',$_SESSION['state']['store']['products']['percentages']);
$smarty->assign('product_avg',$_SESSION['state']['store']['products']['avg']);
$smarty->assign('product_period',$_SESSION['state']['store']['products']['period']);
$q='';
$tipo_filter=($q==''?$_SESSION['state']['store']['products']['f_field']:'code');
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',($q==''?$_SESSION['state']['store']['products']['f_value']:addslashes($q)));
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Product code starting with <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Product name containing <i>x</i>'),'label'=>_('Name'))
);


$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('products',$store->data['Store For Public Sale Products']);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);


$info_period_menu=array(
	array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
	,array("period"=>'month','label'=>_('Last Month'),'title'=>_('Last Month'))
	,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
	,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
	,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
);
$smarty->assign('info_period_menu',$info_period_menu);


$subject_id=$store_id;


$smarty->assign('store',$store);

$smarty->assign('parent','products');
$smarty->assign('title', _('Store').': ('.$store->data['Store Code'].')');






$smarty->assign('elements_family',$_SESSION['state']['store']['families']['elements']);

$smarty->assign('elements_product_elements_type',$_SESSION['state']['store']['products']['elements_type']);
$smarty->assign('elements_type',$_SESSION['state']['store']['products']['elements']['type']);
$smarty->assign('elements_web',$_SESSION['state']['store']['products']['elements']['web']);
$smarty->assign('elements_stock',$_SESSION['state']['store']['products']['elements']['stock']);
$smarty->assign('elements_stock_aux',$_SESSION['state']['store']['products']['elements_stock_aux']);




$number_sites=$store->get_number_sites();
$smarty->assign('number_sites',$number_sites);




$table_type_options=array(
	'list'=>array('mode'=>'list','label'=>_('List')),
	'thumbnails'=>array('mode'=>'thumbnails','label'=>_('Thumbnails')),

);
$smarty->assign('products_table_type',$_SESSION['state']['store']['products']['table_type']);
$smarty->assign('products_table_type_label',$table_type_options[$_SESSION['state']['store']['products']['table_type']]['label']);
$smarty->assign('products_table_type_menu',$table_type_options);

$smarty->assign('families_table_type',$_SESSION['state']['store']['families']['table_type']);
$smarty->assign('families_table_type_label',$table_type_options[$_SESSION['state']['store']['families']['table_type']]['label']);
$smarty->assign('families_table_type_menu',$table_type_options);

$smarty->assign('departments_table_type',$_SESSION['state']['store']['departments']['table_type']);
$smarty->assign('departments_table_type_label',$table_type_options[$_SESSION['state']['store']['departments']['table_type']]['label']);
$smarty->assign('departments_table_type_menu',$table_type_options);

$smarty->assign('pages_table_type',$_SESSION['state']['store']['pages']['table_type']);
$smarty->assign('pages_table_type_label',$table_type_options[$_SESSION['state']['store']['pages']['table_type']]['label']);
$smarty->assign('pages_table_type_menu',$table_type_options);


$mode_options=array(
	array('mode'=>'percentage','label'=>_('Percentages')),
	array('mode'=>'value','label'=>_('Sales Amount')),

);

if ($_SESSION['state']['store']['departments']['percentages']) {
	$display_mode='percentages';
	$display_mode_label=_('Percentages');
}else {
	$display_mode='value';
	$display_mode_label=_('Sales Amount');
}
$smarty->assign('display_departments_mode',$display_mode);
$smarty->assign('display_departments_mode_label',$display_mode_label);
$smarty->assign('departments_mode_options_menu',$mode_options);

if ($_SESSION['state']['store']['families']['percentages']) {
	$display_mode='percentages';
	$display_mode_label=_('Percentages');
}else {
	$display_mode='value';
	$display_mode_label=_('Sales Amount');
}
$smarty->assign('display_families_mode',$display_mode);
$smarty->assign('display_families_mode_label',$display_mode_label);
$smarty->assign('families_mode_options_menu',$mode_options);


if ($_SESSION['state']['store']['products']['percentages']) {
	$display_mode='percentages';
	$display_mode_label=_('Percentages');
}else {
	$display_mode='value';
	$display_mode_label=_('Sales Amount');
}
$smarty->assign('display_products_mode',$display_mode);
$smarty->assign('display_products_mode_label',$display_mode_label);
$smarty->assign('products_mode_options_menu',$mode_options);


$tipo_filter=($_SESSION['state']['store']['pages']['f_field']);
$smarty->assign('filter4',$tipo_filter);
$smarty->assign('filter_value4',$_SESSION['state']['store']['pages']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Page code starting with  <i>x</i>'),'label'=>_('Code')),
	'title'=>array('db_key'=>'title','menu_label'=>_('Page title like  <i>x</i>'),'label'=>_('Title')),
);
$smarty->assign('filter_menu4',$filter_menu);
$smarty->assign('filter_name4',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu4',$paginator_menu);


$plot_data=array('pie'=>array('forecast'=>3,'interval'=>''));
$smarty->assign('plot_tipo','store');
$smarty->assign('plot_data',$plot_data);


$smarty->assign('sales_sub_block_tipo',$_SESSION['state']['store']['sales_sub_block_tipo']);





$elements_number=array('Notes'=>0,'Changes'=>0,'Attachments'=>0);
$sql=sprintf("select count(*) as num , `Type` from  `Store History Bridge` where `Store Key`=%d group by `Type`",$store->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=$row['num'];
}
$smarty->assign('elements_store_history_number',$elements_number);
$smarty->assign('elements_store_history',$_SESSION['state']['store']['history']['elements']);

$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with  notes *<i>x</i>*'),'label'=>_('Notes')),
	//   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Done by')),
	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)'))
);
$tipo_filter=$_SESSION['state']['store']['history']['f_field'];
$filter_value=$_SESSION['state']['store']['history']['f_value'];

$smarty->assign('filter_value5',$filter_value);
$smarty->assign('filter_menu5',$filter_menu);
$smarty->assign('filter_name5',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu5',$paginator_menu);


$smarty->assign('filter_value7','');
$smarty->assign('filter_name7','');
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu7',$paginator_menu);


$smarty->assign('sticky_note',$store->data['Store Sticky Note']);


$smarty->assign('filter_value6','');
$smarty->assign('filter_name6','');
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu6',$paginator_menu);


$smarty->assign('filter_value8','');
$smarty->assign('filter_name8','');
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu8',$paginator_menu);


$smarty->assign('filter_value9','');
$smarty->assign('filter_name9','');
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu9',$paginator_menu);

//$smarty->assign('product_sales_elements',$_SESSION['state']['store']['product_sales']['elements']);

$tipo_filter=$_SESSION['state']['store']['offers']['f_field'];
$smarty->assign('filter10',$tipo_filter);
$smarty->assign('filter_value10',$_SESSION['state']['store']['offers']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'name','menu_label'=>_('Offers with name like *<i>x</i>*'),'label'=>_('Name')),
                  'code'=>array('db_key'=>'code','menu_label'=>_('Offers with code like x</i>*'),'label'=>_('Code')),
            );
$smarty->assign('filter_menu10',$filter_menu);
             
$smarty->assign('filter_name10',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu10',$paginator_menu);


$tipo_filter=$_SESSION['state']['store']['campaigns']['f_field'];
$smarty->assign('filter11',$tipo_filter);
$smarty->assign('filter_value11',$_SESSION['state']['store']['campaigns']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'name','menu_label'=>_('Campaign with name like *<i>x</i>*'),'label'=>_('Name')),
                  'code'=>array('db_key'=>'code','menu_label'=>_('Campaign with code like x</i>*'),'label'=>_('Code')),
            );
$smarty->assign('filter_menu11',$filter_menu);
             
$smarty->assign('filter_name11',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu11',$paginator_menu);

$deals_block_view=$_SESSION['state']['store']['deals_block_view'];
$smarty->assign('deals_block_view',$deals_block_view);

$smarty->assign('offer_elements',$_SESSION['state']['store']['offers']['elements']);


if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['store']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['store']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['store']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);

$_SESSION['state']['store']['period']=$period;
$_SESSION['state']['store']['from']=$from;
$_SESSION['state']['store']['to']=$to;
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');

$sales_history_timeline_group=$_SESSION['state']['store']['sales_history']['timeline_group'];
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



$smarty->display('store.tpl');

?>
