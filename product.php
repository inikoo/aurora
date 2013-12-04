<?php
/*
 File: product.php

 UI product page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'common.php';
include_once('common_date_functions.php');

include_once 'class.Location.php';

include_once 'class.Product.php';
//include_once 'assets_header_functions.php';
$page='product';
$smarty->assign('page',$page);


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/images.css',
	
	'theme.css.php'
);
$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-debug.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/jquery-1.4.4.min.js',
	'js/barcode.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/table_common.js',
	'js/assets_common.js',
	
	'js/edit_common.js',
	'js/localize_calendar.js',
	'js/calendar_interval.js',
	'js/reports_calendar.js',
	'js/notes.js'

);

if (isset($_REQUEST['code'])) {
	$mode='code';
	$tag=$_REQUEST['code'];
}
elseif (isset($_REQUEST['pid'])) {
	$mode='pid';
	$tag=$_REQUEST['pid'];




}
elseif (isset($_REQUEST['key'])) {
	$mode='key';
	$tag=$_REQUEST['key'];
}
else {
	$tag=$_SESSION['state']['product']['tag'];
	$mode=$_SESSION['state']['product']['mode'];
}
$_SESSION['state']['product']['tag']=$tag;
$_SESSION['state']['product']['mode']=$mode;

$_SESSION['state']['product']['orders']['mode']=$mode;
$_SESSION['state']['product']['customers']['mode']=$mode;
$product= new product($mode,$tag);


if ($mode=='pid') {
	if (isset($_REQUEST['edit']) and $_REQUEST['edit']) {
		header('Location: edit_product.php?pid='.$tag);
		exit();
	}

	if ($product->data['Product First Sold Date']=='') {
		// dont display_plot

	}


	$web_status_error=false;
	$web_status_error_title='';
	if ($product->get('Product Web Configuration')=='Online For Sale') {
		if (!($product->get('Product Availability')>0)) {
			$web_status_error=true;
			$web_status_error_title=_('This product is out of stock');
		}
	} else {
		if ($product->get('Product Availability')>0) {
			$web_status_error=true;
			$web_status_error_title=_('This product is not for sale on the webpage');
		}
	}

	$smarty->assign('web_status_error',$web_status_error);
	$smarty->assign('web_status_error_title',$web_status_error_title);

	$product_home="Products Home";
	$smarty->assign('home',$product_home);
	$smarty->assign('department',$product->get('Product Main Department Name'));
	$smarty->assign('department_id',$product->get('Product Main Department Key'));
	$smarty->assign('family',$product->get('Product Family Code'));
	$smarty->assign('family_id',$product->get('Product Family Key'));
	$smarty->assign('sticky_note',$product->data['Product Sticky Note']);

	//$product->load_images_slidesshow();
	//$images=$product->images_slideshow;
	//$smarty->assign('div_img_width',190);
	//$smarty->assign('img_width',190);
	//$smarty->assign('images',$images);
	//$smarty->assign('num_images',count($images));

}
elseif ($mode=='code') {

	$number_stores=$user->get_number_stores();
	if ($number_stores==0) {
		header('Location: index.php');
		exit;
	}elseif ($number_stores==1) {
		$store=array_pop($user->stores);
		$smarty->assign('store',$store);
	}

	$sql=sprintf("select `Product ID`  from `Product Dimension` where `Product Code`=%s  and `Product Store Key` in (%s)   ;"
		,prepare_mysql($tag)
		,join(',',$user->stores)
	);

	$result=mysql_query($sql);
	//print $sql;

	if (mysql_num_rows($result)>1) {
		$_SESSION['state']['product']['server']['tag']=$tag;
		$js_files[]= 'js/search.js';
		// $js_files[]='product.js.php';
		$js_files[]='product_server.js.php';
		$smarty->assign('css_files',$css_files);
		$smarty->assign('js_files',$js_files);
		$smarty->assign('code',$tag);


		$smarty->assign('search_label',_('Products'));
		$smarty->assign('search_scope','products');





		$tipo_filter=$_SESSION['state']['product']['server']['f_field'];
		$smarty->assign('filter_name2',$tipo_filter);
		$smarty->assign('filter_value2',$_SESSION['state']['product']['server']['f_value']);
		$filter_menu=array(
			'id'=>array('db_key'=>'pid','menu_label'=>_('Product ID like<i>x</i>'),'label'=>_('Id')),
			'code'=>array('db_key'=>'code','menu_label'=>_('Product code starting with <i>x</i>'),'label'=>_('Code')),
			'name'=>array('db_key'=>'name','menu_label'=>_('Product name containing <i>x</i>'),'label'=>_('Name'))
		);

		$smarty->assign('filter_menu2',$filter_menu);
		$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);

		$paginator_menu=array(10,25,50,100,500);
		$smarty->assign('paginator_menu2',$paginator_menu);




		$smarty->display('product_server.tpl');
		mysql_free_result($result);
		exit;
	}
	elseif (mysql_num_rows($result)==0) {

		header('Location: index.php');
		exit;

	}
	else {



		$row=mysql_fetch_array($result, MYSQL_ASSOC);
		mysql_free_result($result);
		$tag=$row['Product ID'];
		$mode='pid';
		$_SESSION['state']['product']['tag']=$tag;
		$_SESSION['state']['product']['mode']=$mode;

	}

}






if ($user->data['User Type']=='Supplier') {
	$data=array_pop($product->get_part_list());
	header('Location: part.php?sku='.$data['Part SKU']);
	exit;
}


$store= new store($product->data['Product Store Key']);


$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');

	

if (isset($_REQUEST['block_view']) and in_array($_REQUEST['block_view'],array('details','sales','products','customers','orders','timeline','web','pictures')) ) {
	$_SESSION['state']['product']['block_view']=$_REQUEST['block_view'];
}
$block_view=$_SESSION['state']['product']['block_view'];
$smarty->assign('block_view',$block_view);

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$view_orders=$user->can_view('orders');

$create=$user->can_create('products');
$modify=$user->can_edit('products');
$modify_stock=$user->can_edit('product stock');
$smarty->assign('modify_stock',$modify_stock);
$view_suppliers=$user->can_view('suppliers');
$view_cust=$user->can_view('customers');

$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('view_suppliers',$view_suppliers);
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
$smarty->assign('view_orders',$user->can_view('orders'));
$smarty->assign('view_customers',$user->can_view('customers'));






//get_header_info($user,$smarty);




$family_order=$_SESSION['state']['family']['products']['order'];
$family_period=$_SESSION['state']['family']['products']['period'];

//$family_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));
$smarty->assign('products_period',$family_period);
//$smarty->assign('family_period_title',$family_period_title[$family_period]);


// $_SESSION['views']['product_blocks'][5]=0;
// foreach($_SESSION['views']['product_blocks'] as $key=>$value){
//   $hide[$key]=($value==1?0:1);
// }
// //print_r($hide);


$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);
$smarty->assign('store_id',$store->id);

$display=$_SESSION['state']['product']['display'];



$_SESSION['state']['product']['code_timeline']['code']=$product->data['Product Code'];

$product->load('part_location_list');
$smarty->assign('product',$product);
$smarty->assign('product_id',$product->data['Product Current Key']);
$smarty->assign('data',$product->data);

//get_header_info($user,$smarty);





$smarty->assign('parent','products');
$smarty->assign('title',$product->get('Product Code'));



$subject_id=$product->id;



//$smarty->assign('stock_table_options',array(_('Inv'),_('Pur'),_('Adj'),_('Sal'),_('P Sal')) );
//$smarty->assign('stock_table_options_tipo', $_SESSION['views']['stock_table_options'] );
$smarty->assign('table_title_orders',_('Orders'));
$smarty->assign('table_title_customers',_('Customers'));
$smarty->assign('table_title_stock',_('Stock History'));



$smarty->assign('key_filter_number',$regex['key_filter_number']);
$smarty->assign('key_filter_dimension',$regex['key_filter_dimension']);


$js_files[]= 'js/search.js';
$js_files[]= 'common_plot.js.php?page='.$page;

$js_files[]='product.js.php';


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$smarty->assign('web_status_menu',$_web_status);

$smarty->assign('display',$display);




$tipo_filter=$_SESSION['state']['product']['customers']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['product']['customers']['f_value']);
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Customer Name'),'label'=>_('Name')),
	'country'=>array('db_key'=>'country','menu_label'=>_('Customer Country'),'label'=>_('Country')),
);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);

$tipo_filter=$_SESSION['state']['product']['orders']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['product']['orders']['f_value']);
$filter_menu=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Order Number'),'label'=>_('Number')),
	'customer_name'=>array('db_key'=>'customer_name','menu_label'=>_('Customer Name'),'label'=>_('Customer')),


);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$tipo_filter=$_SESSION['state']['product']['code_timeline']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['product']['code_timeline']['f_value']);
$filter_menu=array(
	'description'=>array('db_key'=>'description','menu_label'=>_('Product Name'),'label'=>_('Name')),
);
$smarty->assign('filter_menu3',$filter_menu);
$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);





$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('paginator_menu1',$paginator_menu);

$number_parts=$product->get_number_of_parts();
$smarty->assign('number_parts',$number_parts);





$smarty->assign('plot_tipo','store');



$family_order=$_SESSION['state']['family']['products']['order'];



$smarty->assign('sales_sub_block_tipo',$_SESSION['state']['product']['sales_sub_block_tipo']);



$smarty->assign('filter_name4','');
$smarty->assign('filter_value4','');




$elements_number=array('Notes'=>0,'Changes'=>0,'Attachments'=>0);
$sql=sprintf("select count(*) as num , `Type` from  `Product History Bridge` where `Product ID`=%d group by `Type`",$product->pid);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=$row['num'];
}
$smarty->assign('elements_product_history_number',$elements_number);
$smarty->assign('elements_product_history',$_SESSION['state']['product']['history']['elements']);

$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with  notes *<i>x</i>*'),'label'=>_('Notes')),
	//   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Done by')),
	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)'))
);
$tipo_filter=$_SESSION['state']['product']['history']['f_field'];
$filter_value=$_SESSION['state']['product']['history']['f_value'];

$smarty->assign('filter_value2',$filter_value);
$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);


$tipo_filter=$_SESSION['state']['product']['pages']['f_field'];
$smarty->assign('filter5',$tipo_filter);
$smarty->assign('filter_value5',$_SESSION['state']['product']['pages']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>'Page code starting with  <i>x</i>','label'=>'Code'),
	'title'=>array('db_key'=>'code','menu_label'=>'Page title like  <i>x</i>','label'=>'Code'),

);
$smarty->assign('filter_menu5',$filter_menu);
$smarty->assign('filter_name5',$filter_menu[$tipo_filter]['label']);

$link='product.php';
include_once 'product_navigation_common.php';


if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['product']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['product']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['product']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);

$_SESSION['state']['product']['period']=$period;
$_SESSION['state']['product']['from']=$from;
$_SESSION['state']['product']['to']=$to;
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');


$sales_history_timeline_group=$_SESSION['state']['product']['sales_history']['timeline_group'];
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


$smarty->display('product.tpl');
?>
