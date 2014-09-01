<?php
/*
 File: store.php

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 28 August 2014 16:33:51 BST, Nottingham, UK
 Copyright (c) 2014, Inikoo

 Version 2.0
*/
include_once 'common.php';

include_once 'class.Payment.php';

include_once 'common_date_functions.php';

$smarty->assign('page','payment');
if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$payment_key=$_REQUEST['id'];

} else {
	exit("no id");
}



//if (!($user->can_view('stores') and in_array($payment_key,$user->stores)   ) ) {
// header('Location: index.php');
// exit;
//}

$payment=new Payment($payment_key);
//$payment->update_number_sites();
//$payment->update_sales_averages();




$modify=$user->can_edit('customers');
$smarty->assign('modify',$modify);


if (isset($_REQUEST['view']) and in_array($_REQUEST['view'],array('details','changelog'))) {
	$_SESSION['state']['payment']['block_view']=$_REQUEST['view'];
	$block_view=$_SESSION['state']['payment']['block_view'];

}else {
	$block_view=$_SESSION['state']['payment']['block_view'];
}
$smarty->assign('block_view',$block_view);



$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');


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
	'js/search.js',
	'payments.js.php',
);

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$tipo_filter=$_SESSION['state']['payment']['changelog']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['payment']['changelog']['f_value']);

$smarty->assign('filter_menu0',array());
$smarty->assign('filter_name0','');
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->assign('payment',$payment);

$smarty->assign('parent','orders');
$smarty->assign('title', _('Store').': ('.$payment->data['Store Code'].')');


$smarty->assign('elements_family',$_SESSION['state']['store']['families']['elements']);
$smarty->assign('elements_product_elements_type',$_SESSION['state']['store']['products']['elements_type']);
$smarty->assign('elements_type',$_SESSION['state']['store']['products']['elements']['type']);
$smarty->assign('elements_web',$_SESSION['state']['store']['products']['elements']['web']);
$smarty->assign('elements_stock',$_SESSION['state']['store']['products']['elements']['stock']);
$smarty->assign('elements_stock_aux',$_SESSION['state']['store']['products']['elements_stock_aux']);

$number_sites=$payment->get_number_sites();
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


$tipo_filter=($_SESSION['state']['store']['sites']['f_field']);
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['store']['sites']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Code starting with  <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Name like  <i>x</i>'),'label'=>_('Name')),
);
$smarty->assign('filter_menu3',$filter_menu);
$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);


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
$sql=sprintf("select count(*) as num , `Type` from  `Store History Bridge` where `Store Key`=%d group by `Type`",$payment->id);
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


$smarty->assign('sticky_note',$payment->data['Store Sticky Note']);


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

$tipo_filter=$_SESSION['state']['store']['payments']['f_field'];
$smarty->assign('filter12',$tipo_filter);
$smarty->assign('filter_value12',$_SESSION['state']['store']['payments']['f_value']);
$filter_menu=array(
	'id'=>array('db_key'=>'id','menu_label'=>_('Payment ID like <i>x</i>*'),'label'=>_('Id')),
);
$smarty->assign('filter_menu12',$filter_menu);

$smarty->assign('filter_name12',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu12',$paginator_menu);




$deals_block_view=$_SESSION['state']['store']['deals_block_view'];
$smarty->assign('deals_block_view',$deals_block_view);
$websites_block_view=$_SESSION['state']['store']['websites_block_view'];
$smarty->assign('websites_block_view',$websites_block_view);




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

$smarty->assign('sites_view',$_SESSION['state']['store']['sites']['view']);
$smarty->assign('page_period',$_SESSION['state']['site']['pages']['period']);






$elements_number=array('System'=>0, 'Info'=>0, 'Department'=>0, 'Family'=>0, 'Product'=>0, 'FamilyCategory'=>0, 'ProductCategory'=>0 );
$sql=sprintf("select count(*) as num,`Page Store Section Type` from  `Page Store Dimension` where `Page Store Key`=%d group by `Page Store Section Type`",$payment->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Page Store Section Type']]=number($row['num']);
}

$smarty->assign('elements_page_section_number',$elements_number);
$smarty->assign('elements_page_section',$_SESSION['state']['store']['pages']['elements']['section']);

$smarty->assign('page_elements_type',$_SESSION['state']['store']['pages']['elements_type']);





include_once 'products_export_common.php';
include_once 'families_export_common.php';

$sales_max_sample_domain=1;

if ($payment->data['Store Max Day Sales']>0) {
	$top_range=$payment->data['Store Avg with Sale Day Sales']+(3*$payment->data['Store STD with Sale Day Sales']);
	if ($payment->data['Store Max Day Sales']<$top_range) {
		$sales_max_sample_domain=$payment->data['Store Max Day Sales'];
	}else {
		$sales_max_sample_domain=$top_range;
	}

}


$smarty->assign('sales_max_sample_domain',$sales_max_sample_domain);

$smarty->display('store.tpl');

?>
