<?php
/*
 File: location.php 

 UI location page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.Location.php');

$view_sales=$LU->checkRight(PROD_SALES_VIEW);
$view_stock=$LU->checkRight(PROD_STK_VIEW);
$view_orders=$LU->checkRight(ORDER_VIEW);

$create=$LU->checkRight(PROD_CREATE);
$modify=$LU->checkRight(PROD_MODIFY);
$modify_stock=$LU->checkRight(PROD_STK_MODIFY);
$smarty->assign('modify_stock',$modify_stock);
$view_suppliers=$LU->checkRight(SUP_VIEW);
$view_cust=$LU->checkRight(CUST_VIEW);
$smarty->assign('view_suppliers',$view_suppliers);
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
$smarty->assign('view_orders',$view_orders);
$smarty->assign('view_customers',$view_cust);

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',	
		$yui_path.'datatable/datatable.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js.php',
		'js/search.js',
		'js/table_common.js.php',
		);



if(!isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
  $location_id=1;
else
  $location_id=$_REQUEST['id'];
$_SESSION['state']['location']['id']=$location_id;


$location= new location($location_id);
$order=$_SESSION['state']['warehouse']['locations']['order'];

if($order=='code'){
  $order='`Location Code`';
 }
 elseif($order=='parts')
    $order='`Location Distinct Parts`';
 elseif($order=='max_volumen')
    $order='`Location Max Volume`';
  elseif($order=='max_weight')
    $order='`Location Max Weight`';
  elseif($order=='tipo')
    $order='`Location Mainly Used For`';
 elseif($order=='area')
    $order='`Location Area`';
$_order=str_replace('`','',$order);

$sql=sprintf("select `Location Key` as id,`Location Code` as code from `Location Dimension` where  %s<'%s'  order by %s desc  ",$order,$location->data[$_order],$order);

$result=mysql_query($sql);
if(!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
  $prev=array('id'=>0,'code'=>'');
$sql=sprintf("select `Location Key` as id,`Location Code` as code  from `Location Dimension` where  %s>'%s'   order by %s   ",$order,$location->data[$_order],$order);
//print "$sql";
$result=mysql_query($sql);
if(!$next=mysql_fetch_array($result, MYSQL_ASSOC))
  $next=array('id'=>0,'code'=>'');

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);


$location->load('product');

//print_r($locations);


$smarty->assign('parent','warehouse.php');
$smarty->assign('title',_('Location ').$location->data['Location Code']);

$smarty->assign('has_stock',$location->get('Location Has Stock'));

$smarty->assign('parts',$location->parts);
$smarty->assign('num_parts',count($location->parts));


$js_files[]='js/location.js.php';

$smarty->assign('location',$location);


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);





$smarty->display('location.tpl');
?>