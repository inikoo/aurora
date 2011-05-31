<?php
/*
 File: location.php 

 UI location page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.Location.php');



if(!isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
  $location_id=1;
else
  $location_id=$_REQUEST['id'];
$_SESSION['state']['location']['id']=$location_id;


$location= new location($location_id);



$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$view_orders=$user->can_view('orders');


$create=$user->can_create('products');
$modify=$user->can_edit('products');
$modify_stock=$user->can_edit('product stock');



$smarty->assign('modify_stock',$modify_stock);

$view_suppliers=$user->can_view('suppliers');
$view_cust=$user->can_view('customers');

$smarty->assign('view',$_SESSION['state']['location']['view']);


$general_options_list=array();
if($modify)
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_location.php?id='.$location_id,'label'=>_('Edit Location'));

$smarty->assign('general_options_list',$general_options_list);




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
		 $yui_path.'container/assets/skins/sam/container.css',
		 'common.css',
		 'button.css',
		 'table.css',
		 'css/dropdown.css'
		 );
include_once('Theme.php');
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',	
		$yui_path.'datatable/datatable.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js',
		'js/search.js',
		'js/table_common.js',
				'js/dropdown.js',
				'edit_stock.js.php'

		);



$order=$_SESSION['state']['locations']['table']['order'];

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
mysql_free_result($result);
$sql=sprintf("select `Location Key` as id,`Location Code` as code  from `Location Dimension` where  %s>'%s'   order by %s   ",$order,$location->data[$_order],$order);
//print "$sql";
$result=mysql_query($sql);
if(!$next=mysql_fetch_array($result, MYSQL_ASSOC))
  $next=array('id'=>0,'code'=>'');
mysql_free_result($result);

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);


$location->load('product');

//print_r($locations);


$smarty->assign('parent','warehouses');
$smarty->assign('title',_('Location ').$location->data['Location Code']);

$smarty->assign('has_stock',$location->get('Location Has Stock'));

$smarty->assign('parts',$location->parts);
$smarty->assign('num_parts',count($location->parts));

$js_files[]='js/edit_common.js';

$js_files[]='location.js.php';

$smarty->assign('location',$location);


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);





$smarty->display('location.tpl');
?>
