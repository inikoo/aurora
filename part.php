<?php
/*
 File: part.php 

 UI part page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/

include_once('common.php');
//include_once('stock_functions.php');
include_once('class.Part.php');

$view_sales=false;
$view_stock=false;
$view_orders=false;
$create=false;
$modify=false;
$modify_stock=false;
$smarty->assign('modify_stock',$modify_stock);
$view_suppliers=false;
$view_cust=false;
$smarty->assign('view_suppliers',$view_suppliers);
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
$smarty->assign('view_orders',$view_orders);
$smarty->assign('view_customers',$view_cust);



$parts_period=$_SESSION['state']['parts']['period'];
$parts_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));

$smarty->assign('parts_period',$parts_period);
$smarty->assign('parts_period_title',$parts_period_title[$parts_period]);


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-debug.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'common.js.php',
		'table_common.js.php',
		);





// $_SESSION['views']['product_blocks'][5]=0;
// foreach($_SESSION['views']['product_blocks'] as $key=>$value){
//   $hide[$key]=($value==1?0:1);
// }
// //print_r($hide);

$smarty->assign('display',$_SESSION['state']['part']['display']);

// $smarty->assign('view_plot',$_SESSION['views']['part_plot']);

if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id'])){
  $part_id=$_REQUEST['id'];
  $_SESSION['state']['part']['id']=$part_id;
  $part= new part($part_id);
  $_SESSION['state']['part']['sku']=$part->data['Part SKU'];
 }else if(isset($_REQUEST['sku']) and is_numeric($_REQUEST['sku'])){
  $part= new part('sku',$_REQUEST['sku']);
  $part_id=$part->id;
  $_SESSION['state']['part']['id']=$part_id;
  $_SESSION['state']['part']['sku']=$part->data['Part SKU'];
 }else{
  $part_id=$_SESSION['state']['part']['id'];
  $_SESSION['state']['part']['id']=$part_id;
  $part= new part($part_id);
  $_SESSION['state']['part']['sku']=$part->data['Part SKU'];
  
 }



$smarty->assign('part',$part);
$smarty->assign('parent','products');
$smarty->assign('title',$part->get('SKU'));
$plot_tipo=$_SESSION['state']['part']['plot'];
$plot_data=$_SESSION['state']['part']['plot_data'];
$smarty->assign('plot_tipo',$plot_tipo);
$smarty->assign('plot_data',$plot_data);
$smarty->assign('key_filter_number',$regex['key_filter_number']);
$smarty->assign('key_filter_dimension',$regex['key_filter_dimension']);

//$js_files[]= 'js/search.js';
$js_files[]='part.js.php';

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);





$q='';
$tipo_filter=($q==''?$_SESSION['state']['part']['transactions']['f_field']:'note');
$smarty->assign('filter_show1',$_SESSION['state']['part']['transactions']['f_show']);
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',($q==''?$_SESSION['state']['part']['transactions']['f_value']:addslashes($q)));
$filter_menu=array(
		   'note'=>array('db_key'=>'note','menu_label'=>_('Note'),'label'=>_('Note')),
		   'location'=>array('db_key'=>'location','menu_label'=>_('Location'),'label'=>_('Location')),
		   );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);



$q='';
$tipo_filter=($q==''?$_SESSION['state']['part']['stock_history']['f_field']:'note');
$smarty->assign('filter_show0',$_SESSION['state']['part']['stock_history']['f_show']);
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',($q==''?$_SESSION['state']['part']['stock_history']['f_value']:addslashes($q)));
$filter_menu=array(
		   'location'=>array('db_key'=>'location','menu_label'=>_('Location'),'label'=>_('Location')),
		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->display('part.tpl');
?>