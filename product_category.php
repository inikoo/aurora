<?php
/*


 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once 'class.Category.php';
include_once 'class.Store.php';

include_once 'common.php';
include_once 'assets_header_functions.php';



if (!$user->can_view('stores')  ) {
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
$smarty->assign('view_products',$user->can_view('products'));
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
//$modify=false;
$modify=$user->can_edit('stores');

get_header_info($user,$smarty);
$general_options_list=array();


$smarty->assign('view',$_SESSION['state']['product_categories']['view']);



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
	'js/common.js',
	'js/export_common.js',
	'js/search.js',
	'js/table_common.js',
	'external_libs/ammap/ammap/swfobject.js',
	'js/assets_common.js',
	'js/asset_elements.js',
	'js/edit_category_common.js',
	'product_category.js.php',
	'js/localize_calendar.js',
	'js/calendar_interval.js',
	'js/reports_calendar.js',
	'edit_stock.js.php'

);

$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');
$smarty->assign('subcategories_view',$_SESSION['state']['product_categories']['view']);
$smarty->assign('subcategories_period',$_SESSION['state']['product_categories']['period']);
$smarty->assign('subcategories_avg',$_SESSION['state']['product_categories']['avg']);
$smarty->assign('category_period',$_SESSION['state']['product_categories']['period']);

$category=new Category($category_key);
if (!$category->id) {
	header('Location: product_category_deleted.php?id='.$category_key);
	exit;
}

$category_key=  $category->id;
$store=new Store($category->data['Category Store Key']);

$smarty->assign('category',$category);

if (isset($_REQUEST['block_view']) and in_array($_REQUEST['block_view'],array('subcategories','subjects','overview','history','sales'))) {
	$_SESSION['state']['product_categories']['block_view']=$_REQUEST['block_view'];
}



$state_type=($category->data['Category Branch Type']=='Head'?'head':'node');

$block_view=$_SESSION['state']['product_categories'][$state_type.'_block_view'];

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


$tipo_filter=$_SESSION['state']['product_categories']['products']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['product_categories']['products']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Product code starting with <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Product name containing <i>x</i>'),'label'=>_('Name'))

);
$smarty->assign('filter_menu0',$filter_menu);

$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->assign('product_view',$_SESSION['state']['product_categories']['products']['view']);
$smarty->assign('product_show_percentages',$_SESSION['state']['product_categories']['products']['percentages']);
$smarty->assign('product_avg',$_SESSION['state']['product_categories']['products']['avg']);
$smarty->assign('product_period',$_SESSION['state']['product_categories']['products']['period']);

$smarty->assign('elements_product_elements_type',$_SESSION['state']['product_categories']['products']['elements_type']);
$smarty->assign('elements_type',$_SESSION['state']['product_categories']['products']['elements']['type']);
$smarty->assign('elements_web',$_SESSION['state']['product_categories']['products']['elements']['web']);
$smarty->assign('elements_stock',$_SESSION['state']['product_categories']['products']['elements']['stock']);
$smarty->assign('elements_stock_aux',$_SESSION['state']['product_categories']['products']['elements_stock_aux']);


$table_type_options=array(
	'list'=>array('mode'=>'list','label'=>_('List')),
	'thumbnails'=>array('mode'=>'thumbnails','label'=>_('Thumbnails')),

);
$smarty->assign('products_table_type',$_SESSION['state']['product_categories']['products']['table_type']);
$smarty->assign('products_table_type_label',$table_type_options[$_SESSION['state']['product_categories']['products']['table_type']]['label']);
$smarty->assign('products_table_type_menu',$table_type_options);



$smarty->assign('store_id',$store->id);
$smarty->assign('store',$store);


$_SESSION['state']['product_categories']['category_key']=$category_key;




$order=$_SESSION['state']['product_categories']['subcategories']['order'];
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
	$prev['link']='product_category.php?id='.$row['id'];
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
	$next['link']='product_category.php?id='.$row['id'];
	$next['title']=$row['name'];
	$smarty->assign('navigation_next',$next);
}
mysql_free_result($result);





$tipo_filter=$_SESSION['state']['product_categories']['subcategories']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['product_categories']['subcategories']['f_value']);

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


$tipo_filter=$_SESSION['state']['product_categories']['no_assigned_products']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['product_categories']['no_assigned_products']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Product code starting with <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Product name containing <i>x</i>'),'label'=>_('Name'))

);
$smarty->assign('filter_menu3',$filter_menu);

$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);



$smarty->assign('parent','products');
$smarty->assign('title', _('Product Category').' '.$category->data['Category Code']);

$smarty->assign('subject','Product');
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
$sql=sprintf("select count(*) as num ,`Type` from  `Product Category History Bridge` where  `Category Key`=%d group by  `Type`",$category->id);
//print_r($sql);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=number($row['num']);
}
$smarty->assign('history_elements_number',$elements_number);
$smarty->assign('history_elements',$_SESSION['state']['product_categories']['history']['elements']);



$smarty->assign('sales_sub_block_tipo',$_SESSION['state']['product_categories']['sales_sub_block_tipo']);
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from='';
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to='';
}
if (isset($_REQUEST['tipo'])) {
	$tipo=$_REQUEST['tipo'];
	$_SESSION['state']['product']['period']=$tipo;
}else {
	$tipo=$_SESSION['state']['product_categories']['period'];
}

$smarty->assign('period_type',$tipo);
$report_name='product';
//print $tipo;

include_once 'report_dates.php';

$_SESSION['state']['product']['to']=$to;
$_SESSION['state']['product']['from']=$from;

$smarty->assign('from',$from);
$smarty->assign('to',$to);

//print_r($_SESSION['state']['orders']);
$smarty->assign('period',$period);
$smarty->assign('period_tag',$period);

$smarty->assign('quick_period',$quick_period);
$smarty->assign('tipo',$tipo);
$smarty->assign('report_url','product_category.php');

if ($from)$from=$from.' 00:00:00';
if ($to)$to=$to.' 23:59:59';
$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
$where_interval=$where_interval['mysql'];


$smarty->assign('elements_product_category_use',$_SESSION['state']['product_categories']['subcategories']['elements']['use']);
$smarty->assign('elements_product_category_elements_type',$_SESSION['state']['product_categories']['subcategories']['elements_type']);


$modify_stock=$user->can_edit('product stock');
$smarty->assign('modify_stock',$modify_stock);




include 'products_export_common.php';

$smarty->display('product_category.tpl');
?>
