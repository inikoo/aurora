<?php
/*
 File: store.php 

 UI store page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('common.php');
//include_once('stock_functions.php');
include_once('class.Store.php');




if(isset($_REQUEST['id']) and is_integer($_REQUEST['id']) and $_REQUEST['id']>0){
  $store_id=$_REQUEST['id'];
 }else
  $store_id=$_SESSION['state']['store']['id'];


if(!$user->can_view('stores',$store_id))
  exit();


$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product departments');
$modify=$user->can_edit('stores',$store_id);



$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);


if(isset($_REQUEST['edit']))
  $edit=$_REQUEST['edit'];
else
  $edit=$_SESSION['state']['store']['edit'];


if(!$modify)
  $edit=false;

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
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'common.js.php',
		'table_common.js.php',
		);

if($edit){

  $js_files[]='js/edit_common.js';
  $js_files[]='edit_store.js.php';
 }else{
   $js_files[]='js/search.js';
   $js_files[]='store.js.php';
 }


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if(isset($_REQUEST['id'])){
  $_SESSION['state']['store']['id']=$_REQUEST['id'];
 }

$store=new Store($_SESSION['state']['store']['id']);
  

$_SESSION['state']['assets']['page']='store';
if(isset($_REQUEST['view'])){
  $valid_views=array('sales','general','stoke');
  if (in_array($_REQUEST['view'], $valid_views)) 
    $_SESSION['state']['store']['view']=$_REQUEST['view'];

 }
$smarty->assign('view',$_SESSION['state']['store']['view']);
$smarty->assign('show_details',$_SESSION['state']['store']['details']);

$smarty->assign('show_percentages',$_SESSION['state']['store']['percentages']);
$smarty->assign('avg',$_SESSION['state']['store']['avg']);
$smarty->assign('period',$_SESSION['state']['store']['period']);

$plot_tipo=$_SESSION['state']['store']['plot'];
$plot_data=$_SESSION['state']['store']['plot_data'][$plot_tipo];
$plot_period=$plot_data['period'];
$plot_category=$plot_data['category'];




$plot_args='tipo=store&category='.$plot_category.'&period='.$plot_period.'&keys='.$store_id;
if($plot_tipo=='top_departments'){
  $number_children=3;
  $plot_args.=sprintf('&top_children=%d',$number_children);
}

if($plot_tipo=='pie'){
  $pie_forecast=$plot_data['forecast'];
  
  if($plot_data['date']=='today'){
    $plot_date=date('Y-m-d');
    $smarty->assign('plot_date',$plot_date);
    $smarty->assign('plot_formated_date',strftime("%b %Y",strtotime($plot_date)));

  }

  $plot_args=sprintf('tipo=children_share&item=store&category=%s&period=%s&keys=%d&date=%s&forecast=%s'
		     ,$plot_category
		     ,$plot_period
		     ,$store_id
		     ,$plot_date
		     ,$plot_data['forecast']);
}

$smarty->assign('plot_tipo',$plot_tipo);
$smarty->assign('plot_args',$plot_args);
$smarty->assign('plot_page',$plot_data['page']);
$smarty->assign('plot_period',$plot_period);
$smarty->assign('plot_category',$plot_period);
$smarty->assign('plot_data',$_SESSION['state']['store']['plot_data']);


if($plot_tipo=='pie'){
  if($plot_period=='m')
    $plot_formated_period='Month';
  elseif($plot_period=='y')
    $plot_formated_period='Year';
    elseif($plot_period=='q')
      $plot_formated_period='Quarter';
    elseif($plot_period=='w')
      $plot_formated_period='Week';
  }else{
    if($plot_period=='m')
      $plot_formated_period='Monthly';
    elseif($plot_period=='y')
      $plot_formated_period='Yearly';
    elseif($plot_period=='q')
      $plot_formated_period='Quarterly';
    elseif($plot_period=='w')
      $plot_formated_period='Weekly';
  }
  
if($plot_category=='profit')
  $plot_formated_category=_('Profits');
else
  $plot_formated_category=_('Net Sales');


$smarty->assign('plot_formated_category',$plot_formated_category);
$smarty->assign('plot_formated_period',$plot_formated_period);


/* $pie_data=$_SESSION['state']['store']['pie']; */
/* $smarty->assign('pie_period',$pie_data['period']); */
/* $smarty->assign('pie_forecast',$pie_data['forecast']); */

/* if($pie_data['period']=='month'){ */
/*   $smarty->assign('pie_period','Monthly'); */
/*   $smarty->assign('pie_period_label',_('Month')); */
/*   if($pie_data['date']=='today'){ */
/*     $smarty->assign('pie_date',date('Y-m-d')); */
/*     $smarty->assign('pie_formated_date',strftime("%b %y")); */
/*   } */
/* } */

$plot_period_menu=array(
		     array("period"=>'w','label'=>_('Weekly'))
		     ,array("period"=>'m','label'=>_('Montly'))
		     ,array("period"=>'q','label'=>_('Quarterly'))
		     ,array("period"=>'y','label'=>_('Yearly'))
		     );
$smarty->assign('plot_period_menu',$plot_period_menu);

$plot_category_menu=array(
		     array("category"=>'sales','label'=>_('Sales'))
		     ,array("category"=>'profit','label'=>_('Profit'))
		     );
$smarty->assign('plot_category_menu',$plot_category_menu);




//print $_SESSION['state']['store']['plot'];


//$sql="select id from product";
//$result=mysql_query($sql);

// include_once('class.product.php');
// while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
//   $product= new product($row['id']);
//   $product->set_stock();
// }




// //$smarty->assign('table_info',$store['numberof'].' '.ngettext('Department','Store',$store['numberof']));
// $sql="select count(*) as numberof from product_group";
// $result=mysql_query($sql);
// $families=mysql_fetch_array($result, MYSQL_ASSOC);
// $sql="select count(*) as numberof from product";
// $result=mysql_query($sql);
// $products=mysql_fetch_array($result, MYSQL_ASSOC);





// $smarty->assign('stock_value',money($store['stock_value']));
//$smarty->assign('total_sales',money($store['total_sales']));
$smarty->assign('store',$store);
// $smarty->assign('families',number($families['numberof']));
// $smarty->assign('products',number($products['numberof']));

$smarty->assign('parent','store.php');
$smarty->assign('title', $store->data['Store Name']);
//$smarty->assign('total_store',$store['numberof']);
//$smarty->assign('table_title',$table_title);

if($edit){

$stores=array();
$sql=sprintf("select * from `Store Dimension` CD order by `Store Key`");

$res=mysql_query($sql);
 $first=true;
while($row=mysql_fetch_array($res)){
    $stores[$row['Store Key']]=array('code'=>$row['Store Code'],'selected'=>0);
    if($first){
      $stores[$row['Store Key']]['selected']=1;
      $first=FALSE;
    }
}
mysql_free_result($res);





 $smarty->assign('stores',$stores);
$smarty->display('edit_store.tpl');
 }else{



  $q='';
  $tipo_filter=($q==''?$_SESSION['state']['store']['table']['f_field']:'code');
  $smarty->assign('filter',$tipo_filter);
  $smarty->assign('filter_value',($q==''?$_SESSION['state']['store']['table']['f_value']:addslashes($q)));
  $filter_menu=array(
		   'code'=>array('db_key'=>'code','menu_label'=>'Store starting with  <i>x</i>','label'=>'Code')
		     );
  $smarty->assign('filter_menu',$filter_menu);
  $smarty->assign('departments',$store->data['Store Departments']);
  $smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);
  $paginator_menu=array(10,25,50,100,500);
  $smarty->assign('paginator_menu',$paginator_menu);

  
 
  $smarty->display('store.tpl');
 }
?>