<?php
/*
 File: department.php 

 UI department page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');
include_once('class.Department.php');

if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']) )
  $department_id=$_SESSION['state']['department']['id'];
 else{
   $department_id=$_REQUEST['id'];
   $_SESSION['state']['department']['id']=$department_id;
  }
$department=new Department($department_id);

if(!( $user->can_view('stores') and in_array($department->data['Product Department Store Key'],$user->scopes)))
  exit();

$store=new Store($department->get('Product Department Store Key'));


$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product families');
$modify=$user->can_edit('stores',$store->id);



if(isset($_REQUEST['edit']))
  $edit=$_REQUEST['edit'];
else
  $edit=$_SESSION['state']['department']['editing'];

if(!$modify)
  $edit=false;


$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
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
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/php.default.min.js',
		'common.js.php',
		'table_common.js.php',
		

		);

if($edit){

$smarty->assign('edit',$_SESSION['state']['department']['edit']);
  $css_files[]='css/edit.css';
  
  $js_files[]='js/edit_common.js';
  $js_files[]='edit_department.js.php';
 }else{
     $js_files[]='js/search.js';
     $js_files[]='department.js.php';
}


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


if(isset($_REQUEST['view'])){
  $valid_views=array('sales','general','stoke');
  if (in_array($_REQUEST['view'], $valid_views)) 
    $_SESSION['state']['department']['view']=$_REQUEST['view'];

 }




$store_order=$_SESSION['state']['store']['table']['order'];
$store_period=$_SESSION['state']['store']['period'];
$store_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));
  

$smarty->assign('store_period',$store_period);
$smarty->assign('store_period_title',$store_period_title[$store_period]);



If($store_order=='profit'){
    if($store_period=='all')
      $store_order='`Product Department Total Profit`';
    elseif($store_period=='year')
      $store_order='`Product Department 1 Year Acc Profit`';
    elseif($store_period=='quarter')
      $store_order='`Product Department 1 Quarter Acc Profit`';
    elseif($store_period=='month')
      $store_order='`Product Department 1 Month Acc Profit`';
    elseif($store_period=='week')
      $store_order='`Product Department 1 Week Acc Profit`';
  }elseif($store_order=='sales'){
    if($store_period=='all')
      $store_order='`Product Department Total Invoiced Amount`';
    elseif($store_period=='year')
      $store_order='`Product Department 1 Year Acc Invoiced Amount`';
    elseif($store_period=='quarter')
      $store_order='`Product Department 1 Quarter Acc Invoiced Amount`';
    elseif($store_period=='month')
      $store_order='`Product Department 1 Month Acc Invoiced Amount`';
    elseif($store_period=='week')
      $store_order='`Product Department 1 Week Acc Invoiced Amount`';

  }
  elseif($store_order=='name')
    $store_order='`Product Department Name`';
  elseif($store_order=='families')
    $store_order='`Product Department Families`';
  elseif($store_order=='active')
    $store_order='`Product Department For Sale Products`';
  elseif($store_order=='outofstock')
    $store_order='`Product Department Out Of Stock Products`';
  elseif($store_order=='stockerror')
    $store_order='`Product Department Unknown Stock Products`';



$sql=sprintf("select `Product Department Key` as id,`Product Department Code` as code  from `Product Department Dimension` where `Product Department Store Key`=%d and   %s<%s order by %s desc  ",$department->data['Product Department Store Key'],$store_order,prepare_mysql($department->get(str_replace('`','',$store_order))),$store_order);
//print $sql;
$result=mysql_query($sql);
if(!$prev=mysql_fetch_array($result, MYSQL_ASSOC)   )
  $prev=array('id'=>0,'code'=>'');
mysql_free_result($result);  
  
$sql=sprintf("select `Product Department Key` as id,`Product Department Code` as code  from `Product Department Dimension`   where  `Product Department Store Key`=%d and   %s>%s order by %s   ",$department->data['Product Department Store Key'],$store_order,prepare_mysql($department->get(str_replace('`','',$store_order))),$store_order);

//print $sql;
$result=mysql_query($sql);
if(!$next=mysql_fetch_array($result, MYSQL_ASSOC)   )
  $next=array('id'=>0,'code'=>'');
mysql_free_result($result);  
$smarty->assign('prev',$prev);
$smarty->assign('next',$next);



$smarty->assign('parent','stores.php');

$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$department);
$smarty->assign('store',$store);

// $smarty->assign('department_id',$_REQUEST['id']);
// $smarty->assign('products',$families['products']);

//print_r($_SESSION['state']['department']);
$smarty->assign('filter',$_SESSION['state']['department']['table']['f_field']);
$smarty->assign('filter_value',$_SESSION['state']['department']['table']['f_value']);
$smarty->assign('filter_name',_('Family code'));

$smarty->assign('view',$_SESSION['state']['department']['view']);
$smarty->assign('show_details',$_SESSION['state']['department']['details']);
$smarty->assign('show_percentages',$_SESSION['state']['department']['percentages']);
$smarty->assign('avg',$_SESSION['state']['department']['avg']);
$smarty->assign('period',$_SESSION['state']['department']['period']);

$plot_tipo=$_SESSION['state']['department']['plot'];
$plot_data=$_SESSION['state']['department']['plot_data'][$plot_tipo];
$plot_period=$plot_data['period'];
$plot_category=$plot_data['category'];

$plot_args='tipo=department&category='.$plot_category.'&period='.$plot_period.'&keys='.$department_id;
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

  $plot_args=sprintf('tipo=children_share&item=department&category=%s&period=%s&keys=%d&date=%s&forecast=%s'
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
$smarty->assign('plot_data',$_SESSION['state']['department']['plot_data']);

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


$info_period_menu=array(
			array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
		     ,array("period"=>'month','label'=>_('last Month'),'title'=>_('last Month'))
		     ,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
		     ,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
		     ,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
		     );
$smarty->assign('info_period_menu',$info_period_menu);


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



//$table_title=_('Family List');
//$smarty->assign('table_title',$table_title);
//$smarty->assign('table_info',$families['families'].' '.ngettext('Families','Families',$families['families']).' '._('in').' '.$families['department']);
if($edit){

  $smarty->assign('title', _('Editing').': '.$department->get('Product Department Code'));
  $smarty->display('edit_department.tpl');
 }else{
  $smarty->assign('title',$department->get('Product Department Name'));
  $smarty->display('department.tpl');
 }


?>