<?php
/*
 File: stores.php

 UI stores page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'common.php';
include_once 'assets_header_functions.php';
include_once 'class.Account.php';

//include_once('stock_functions.php');
if (!$user->can_view('stores'))
	exit();

$available_stores_list=$user->stores;
$available_stores=count($available_stores_list);
if ($available_stores==1) {
	header('Location: store.php?id='.$available_stores_list[0]);
	exit;
}

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('stores');
$modify=$user->can_edit('stores');



$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);





$corporation=new Account();
$smarty->assign('corporation',$corporation);

$number_of_stores=count($user->stores);



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
	$yui_path.'dragdrop/dragdrop-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/table_common.js',
	'js/dropdown.js',
	'js/edit_common.js',
	
	'js/assets_common.js',
	'js/search.js',
	'stores.js.php',
	'js/asset_elements.js'
);



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$block_view=$_SESSION['state']['stores']['block_view'];
$smarty->assign('block_view',$block_view);



$_SESSION['state']['assets']['page']='stores';
$smarty->assign('view',$_SESSION['state']['stores']['stores']['view']);


$smarty->assign('avg',$_SESSION['state']['stores']['stores']['avg']);
$smarty->assign('period',$_SESSION['state']['stores']['stores']['period']);


$smarty->assign('parent','products');
$smarty->assign('title', _('Stores'));


get_header_info($user,$smarty);



$stores=array();
$sql=sprintf("select count(distinct `Store Currency Code` ) as distint_currencies, sum(IF(`Store Currency Code`=%s,1,0)) as default_currency    from `Store Dimension` "
	,prepare_mysql($corporate_currency));

$res=mysql_query($sql);
if ($row=mysql_fetch_array($res)) {
	$distinct_currencies=$row['distint_currencies'];
	$default_currency=$row['default_currency'];
}

$mode_options=array(
	array('mode'=>'percentage','label'=>_('Percentages')),
	array('mode'=>'value','label'=>_('Sales Amount')),

);
$display_mode='value';
$display_mode_label=_('Sales in store currencies');
if ($_SESSION['state']['stores']['stores']['percentages']) {
	$display_mode='percentages';
	$display_mode_label=_('Percentages');
} elseif ($_SESSION['state']['stores']['stores']['show_default_currency']) {
	$display_mode='value_default_d2d';
	$display_mode_label=_("Sales in")." ".$corporate_currency." ("._('d2d').")";
}
if ($distinct_currencies>1) {
	$mode_options[]=array('mode'=>'value_default_d2d','label'=>_("Sales in")." ".$corporate_currency." ("._('d2d').")");
} else {
	$display_mode_label=_('Sales Amount');
}
$smarty->assign('display_stores_mode',$display_mode);
$smarty->assign('display_stores_mode_label',$display_mode_label);
$smarty->assign('stores_mode_options_menu',$mode_options);



$display_mode='value';
$display_mode_label=_('Sales in store currencies');
$mode_options=array(
	array('mode'=>'percentage','label'=>_('Percentages')),
	array('mode'=>'value','label'=>_('Values')),

);
if ($_SESSION['state']['stores']['departments']['percentages']) {
	$display_mode='percentages';
	$display_mode_label=_('Percentages');
} elseif ($_SESSION['state']['stores']['departments']['show_default_currency']) {
	$display_mode='value_default_d2d';
	$display_mode_label=_("Sales in")." ".$corporate_currency." ("._('d2d').")";
}
if ($distinct_currencies>1) {
	$mode_options[]=array('mode'=>'value_default_d2d','label'=>_("Sales in")." ".$corporate_currency." ("._('d2d').")");
} else {
	$display_mode_label=_('Sales Amount');
}
$smarty->assign('display_departments_mode',$display_mode);
$smarty->assign('display_departments_mode_label',$display_mode_label);
$smarty->assign('departments_mode_options_menu',$mode_options);


$display_mode='value';
$display_mode_label=_('Sales in store currencies');
$mode_options=array(
	array('mode'=>'percentage','label'=>_('Percentages')),
	array('mode'=>'value','label'=>_('Values')),

);
if ($_SESSION['state']['stores']['families']['percentages']) {
	$display_mode='percentages';
	$display_mode_label=_('Percentages');
} elseif ($_SESSION['state']['stores']['families']['show_default_currency']) {
	$display_mode='value_default_d2d';
	$display_mode_label=_("Sales in")." ".$corporate_currency." ("._('d2d').")";
}
if ($distinct_currencies>1) {
	$mode_options[]=array('mode'=>'value_default_d2d','label'=>_("Sales in")." ".$corporate_currency." ("._('d2d').")");
} else {
	$display_mode_label=_('Sales Amount');
}
$smarty->assign('display_families_mode',$display_mode);
$smarty->assign('display_families_mode_label',$display_mode_label);
$smarty->assign('families_mode_options_menu',$mode_options);

$display_mode='value';
$display_mode_label=_('Sales in store currencies');
$mode_options=array(
	array('mode'=>'percentage','label'=>_('Percentages')),
	array('mode'=>'value','label'=>_('Values')),

);
if ($_SESSION['state']['stores']['products']['percentages']) {
	$display_mode='percentages';
	$display_mode_label=_('Percentages');
} elseif ($_SESSION['state']['stores']['products']['show_default_currency']) {
	$display_mode='value_default_d2d';
	$display_mode_label=_("Sales in")." ".$corporate_currency." ("._('d2d').")";
}
if ($distinct_currencies>1) {
	$mode_options[]=array('mode'=>'value_default_d2d','label'=>_("Sales in")." ".$corporate_currency." ("._('d2d').")");
} else {
	$display_mode_label=_('Sales Amount');
}
$smarty->assign('display_products_mode',$display_mode);
$smarty->assign('display_products_mode_label',$display_mode_label);
$smarty->assign('products_mode_options_menu',$mode_options);



$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');



$tipo_filter=$_SESSION['state']['stores']['stores']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['stores']['stores']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Store code starting with  <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Store name containing <i>x</i>'),'label'=>_('Name'))
);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->assign('department_view',$_SESSION['state']['stores']['departments']['view']);
$smarty->assign('department_show_percentages',$_SESSION['state']['stores']['departments']['percentages']);
$smarty->assign('department_avg',$_SESSION['state']['stores']['departments']['avg']);
$smarty->assign('department_period',$_SESSION['state']['stores']['departments']['period']);

$tipo_filter=$_SESSION['state']['stores']['departments']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['stores']['departments']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Department code starting with  <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Department name containing <i>x</i>'),'label'=>_('Name'))

);
$smarty->assign('filter_menu1',$filter_menu);
//$smarty->assign('departments',$store->data['Store Departments']);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$smarty->assign('family_view',$_SESSION['state']['stores']['families']['view']);
$smarty->assign('family_show_percentages',$_SESSION['state']['stores']['families']['percentages']);
$smarty->assign('family_avg',$_SESSION['state']['stores']['families']['avg']);
$smarty->assign('family_period',$_SESSION['state']['stores']['families']['period']);

$tipo_filter=$_SESSION['state']['stores']['families']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['stores']['families']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Family code starting with  <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Family name containing <i>x</i>'),'label'=>_('Name'))
);
$smarty->assign('filter_menu2',$filter_menu);
//$smarty->assign('families',$store->data['Store Families']);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);

$smarty->assign('product_view',$_SESSION['state']['stores']['products']['view']);
$smarty->assign('product_show_percentages',$_SESSION['state']['stores']['products']['percentages']);
$smarty->assign('product_avg',$_SESSION['state']['stores']['products']['avg']);
$smarty->assign('product_period',$_SESSION['state']['stores']['products']['period']);

$tipo_filter=$_SESSION['state']['stores']['products']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['stores']['products']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Product code starting with <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Product name containing <i>x</i>'),'label'=>_('Name'))

);
$smarty->assign('filter_menu3',$filter_menu);
//$smarty->assign('products',$store->data['Store For Public Sale Products']);
$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);


$tipo_filter=($_SESSION['state']['stores']['sites']['f_field']);
$smarty->assign('filter4',$tipo_filter);
$smarty->assign('filter_value4',$_SESSION['state']['stores']['sites']['f_value']);
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>_('Page code starting with  <i>x</i>'),'label'=>_('Code')),
                 'name'=>array('db_key'=>'title','menu_label'=>_('Page name like  <i>x</i>'),'label'=>_('Name')),
             );
$smarty->assign('filter_menu4',$filter_menu);
$smarty->assign('filter_name4',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu4',$paginator_menu);
$smarty->assign('sites_table_type',$_SESSION['state']['stores']['sites']['table_type']);


/*
$csv_export_options=array(
	'description'=>array(
		'title'=>_('Description'),
		'rows'=>
		array(
			array(
				'description'=>array('label'=>_('Description'),'selected'=>$_SESSION['state']['stores']['stores']['csv_export']['description']),
				'stock'=>array('label'=>_('Stock'),'selected'=>$_SESSION['state']['stores']['stores']['csv_export']['stock']),
				'sales'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['stores']['csv_export']['sales']),


			)
		)
	)

);
$smarty->assign('export_csv_table_cols',7);
$smarty->assign('csv_export_options',$csv_export_options);
*/
//{include file='export_csv_menu_splinter.tpl' id=0  export_options=$csv_export_options }


$elements_number=array('InProcess'=>0,'Discontinued'=>0,'Normal'=>0,'Discontinuing'=>0,'NoSale'=>0);
$sql=sprintf("select count(*) as num ,`Product Family Record Type` from  `Product Family Dimension`  group by  `Product Family Record Type`   ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Product Family Record Type']]=$row['num'];
}
$smarty->assign('elements_family_number',$elements_number);
//print_r($_SESSION['state']['store']['families']);
$smarty->assign('elements_family',$_SESSION['state']['stores']['families']['elements']);
$smarty->assign('store_key',false);

$smarty->assign('elements_product_elements_type',$_SESSION['state']['stores']['products']['elements_type']);
$smarty->assign('elements_type',$_SESSION['state']['stores']['products']['elements']['type']);
$smarty->assign('elements_web',$_SESSION['state']['stores']['products']['elements']['web']);
$smarty->assign('elements_stock',$_SESSION['state']['stores']['products']['elements']['stock']);
$smarty->assign('elements_stock_aux',$_SESSION['state']['stores']['products']['elements_stock_aux']);



$smarty->display('stores.tpl');

?>
