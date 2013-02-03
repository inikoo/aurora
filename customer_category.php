<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/
include_once 'class.Category.php';
include_once 'class.Store.php';
include_once 'common.php';



if (!$user->can_view('customers')  ) {
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
$category=new Category($category_key);
if (!$category->id) {
	header('Location: customer_category_deleted.php?id='.$category_key);
	exit;
}


if($category->data['Category Subject']!='Customer'){
header('Location: index.php?error_no_wrong_category_id');
	exit;
}

$modify=$user->can_edit('stores');


$smarty->assign('view',$_SESSION['state']['customer_categories']['view']);

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
	'js/search.js',
	'js/table_common.js',
	'external_libs/ammap/ammap/swfobject.js',
	'js/customers_common.js',

	'customer_category.js.php'

);





$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');

$smarty->assign('subcategories_view',$_SESSION['state']['customer_categories']['view']);
$smarty->assign('subcategories_period',$_SESSION['state']['customer_categories']['period']);
$smarty->assign('subcategories_avg',$_SESSION['state']['customer_categories']['avg']);
$smarty->assign('category_period',$_SESSION['state']['customer_categories']['period']);




$category_key=  $category->id;
$store=new Store($category->data['Category Store Key']);

$currency=$store->data['Store Currency Code'];
$currency_symbol=currency_symbol($currency);

$smarty->assign('category',$category);

if (isset($_REQUEST['block_view']) and in_array($_REQUEST['block_view'],array('subcategories','subjects','overview','history'))) {
	$_SESSION['state']['customer_categories']['block_view']=$_REQUEST['block_view'];
}


$state_type=($category->data['Category Branch Type']=='Head'?'head':'node');
$block_view=$_SESSION['state']['customer_categories'][$state_type.'_block_view'];
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


$tipo_filter=$_SESSION['state']['customer_categories']['customers']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['customer_categories']['customers']['f_value']);
$filter_menu=array(
	'customer name'=>array('db_key'=>'customer name','menu_label'=>_('Customer Name'),'label'=>_('Name')),
	'postcode'=>array('db_key'=>'postcode','menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
	'country'=>array('db_key'=>'country','menu_label'=>_('Customer Country'),'label'=>_('Country')),
	'min'=>array('db_key'=>'min','menu_label'=>_('Mininum Number of Orders'),'label'=>_('Min No Orders')),
	'max'=>array('db_key'=>'min','menu_label'=>_('Maximum Number of Orders'),'label'=>_('Max No Orders')),
	'last_more'=>array('db_key'=>'last_more','menu_label'=>_('Last order more than (days)'),'label'=>_('Last Order >(Days)')),
	'last_less'=>array('db_key'=>'last_more','menu_label'=>_('Last order less than (days)'),'label'=>_('Last Order <(Days)')),
	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>_('Balance less than').' '.$currency_symbol  ,'label'=>_('Balance')." <($currency_symbol)"),
	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>_('Balance more than').' '.$currency_symbol  ,'label'=>_('Balance')." >($currency_symbol)"),

);
$smarty->assign('filter_menu0',$filter_menu);

$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$customers_view=$_SESSION['state']['customer_categories']['customers']['view'];

if($customers_view=='other_value' and $category->data['Is Category Field Other']=='No'){
	$customers_view='general';
}


$smarty->assign('customers_view',$customers_view);



$smarty->assign('customers_period',$_SESSION['state']['customer_categories']['customers']['period']);
$smarty->assign('customers_avg',$_SESSION['state']['customer_categories']['customers']['avg']);





$smarty->assign('store_key',$store->id);

$smarty->assign('store_id',$store->id);
$smarty->assign('store',$store);



$order=$_SESSION['state']['customer_categories']['subcategories']['order'];
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
	$prev['link']='customer_category.php?id='.$row['id'];
	$prev['title']=$row['name'];
	$smarty->assign('prev',$prev);
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
	$next['link']='customer_category.php?id='.$row['id'];
	$next['title']=$row['name'];
	$smarty->assign('next',$next);
}
mysql_free_result($result);




$tipo_filter=$_SESSION['state']['customer_categories']['subcategories']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['customer_categories']['subcategories']['f_value']);

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


$tipo_filter=$_SESSION['state']['customer_categories']['no_assigned_customers']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['customer_categories']['no_assigned_customers']['f_value']);
$filter_menu=array(
	'customer name'=>array('db_key'=>'customer name','menu_label'=>_('Customer Name'),'label'=>_('Name')),
	'postcode'=>array('db_key'=>'postcode','menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
	'country'=>array('db_key'=>'country','menu_label'=>_('Customer Country'),'label'=>_('Country')),
	'min'=>array('db_key'=>'min','menu_label'=>_('Mininum Number of Orders'),'label'=>_('Min No Orders')),
	'max'=>array('db_key'=>'min','menu_label'=>_('Maximum Number of Orders'),'label'=>_('Max No Orders')),
	'last_more'=>array('db_key'=>'last_more','menu_label'=>_('Last order more than (days)'),'label'=>_('Last Order >(Days)')),
	'last_less'=>array('db_key'=>'last_more','menu_label'=>_('Last order less than (days)'),'label'=>_('Last Order <(Days)')),
	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>_('Balance less than').' '.$currency_symbol  ,'label'=>_('Balance')." <($currency_symbol)"),
	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>_('Balance more than').' '.$currency_symbol  ,'label'=>_('Balance')." >($currency_symbol)"),

);
$smarty->assign('filter_menu3',$filter_menu);

$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);



$smarty->assign('parent','customers');
$smarty->assign('title', _('Customer Category').' '.$category->data['Category Code']);

$smarty->assign('subject','Customer');
$smarty->assign('category_key',$category_key);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

include_once 'conf/period_tags.php';
unset($period_tags['hour']);
$smarty->assign('period_tags',$period_tags);

$plot_data=array('pie'=>array('forecast'=>3,'interval'=>''));
$smarty->assign('plot_tipo','store');
$smarty->assign('plot_data',$plot_data);

$elements_number=array('Changes'=>0,'Assign'=>0
);
$sql=sprintf("select count(*) as num ,`Type` from  `Customer Category History Bridge` where  `Category Key`=%d group by  `Type`",$category->id);
//print_r($sql);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=number($row['num']);
}
$smarty->assign('history_elements_number',$elements_number);
$smarty->assign('history_elements',$_SESSION['state']['customer_categories']['history']['elements']);


$smarty->assign('orders_type',$_SESSION['state']['customer_categories']['customers']['orders_type']);

$smarty->assign('elements_activity',$_SESSION['state']['customer_categories']['customers']['elements']['activity']);
$smarty->assign('elements_level_type',$_SESSION['state']['customer_categories']['customers']['elements']['level_type']);
$smarty->assign('elements_customers_elements_type',$_SESSION['state']['customer_categories']['customers']['elements_type']);


$smarty->display('customer_category.tpl');
?>
