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
include_once('class.Store.php');
include_once('assets_header_functions.php');


if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $store_id=$_REQUEST['id'];

}else{
  $store_id=$_SESSION['state']['store']['id'];
}


if(!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ){
  header('Location: index.php');
   exit;
}


$store=new Store($store_id);
$_SESSION['state']['store']['id']=$store->id;

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product departments');



$modify=$user->can_edit('stores');


$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);


$stores_order=$_SESSION['state']['stores']['table']['order'];
$stores_period=$_SESSION['state']['stores']['period'];
$stores_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));

$smarty->assign('stores_period',$stores_period);
$smarty->assign('stores_period_title',$stores_period_title[$stores_period]);

$show_details=$_SESSION['state']['store']['details'];
$smarty->assign('show_details',$show_details);
get_header_info($user,$smarty);

if(isset($_REQUEST['edit']))
  $edit=$_REQUEST['edit'];
else
  $edit=$_SESSION['state']['store']['editing'];


if(!$modify)
  $edit=false;

if(!$edit){
$general_options_list=array();

if($modify)
  $general_options_list[]=array('tipo'=>'url','url'=>'store.php?edit=1','label'=>_('Edit Store'));
$general_options_list[]=array('tipo'=>'js','state'=>$show_details,'id'=>'details','label'=>($show_details?_('Hide Details'):_('Show Details')));

}else{
  $general_options_list[]=array('tipo'=>'url','url'=>'store.php?edit=0','label'=>_('Exit Edit'));
}
$smarty->assign('general_options_list',$general_options_list);






$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css',
		 'css/dropdown.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/php.default.min.js',
		'common.js.php',
		'table_common.js.php',
		'js/dropdown.js'
		);

if($edit){

  $smarty->assign('edit',$_SESSION['state']['store']['edit']);
  $css_files[]='css/edit.css';
 
  $js_files[]='js/edit_common.js';
  $js_files[]='country_select.js.php';
  $js_files[]='edit_store.js.php';
 }else{
   $js_files[]='js/search.js';
   $js_files[]='store.js.php';
 }


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$_SESSION['state']['assets']['page']='store';
if(isset($_REQUEST['view'])){
  $valid_views=array('sales','general','stoke');
  if (in_array($_REQUEST['view'], $valid_views)) 
    $_SESSION['state']['store']['view']=$_REQUEST['view'];

 }
$smarty->assign('view',$_SESSION['state']['store']['view']);


$smarty->assign('show_percentages',$_SESSION['state']['store']['percentages']);
$smarty->assign('avg',$_SESSION['state']['store']['avg']);
$smarty->assign('period',$_SESSION['state']['store']['period']);

$plot_tipo=$_SESSION['state']['store']['plot'];
$plot_data=$_SESSION['state']['store']['plot_data'][$plot_tipo];
$plot_period=$plot_data['period'];
$plot_category=$plot_data['category'];


$info_period_menu=array(
			array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
		     ,array("period"=>'month','label'=>_('Last Month'),'title'=>_('Last Month'))
		     ,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
		     ,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
		     ,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
		     );
$smarty->assign('info_period_menu',$info_period_menu);

$plot_args='tipo=store&category='.$plot_category.'&period='.$plot_period.'&keys='.$store_id.'&currency='.$store->data['Store Currency Code'];

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
  $plot_formated_category=_('Net Item Sales');


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
		     array("category"=>'sales','label'=>_('Net Item Sales'))
		     ,array("category"=>'profit','label'=>_('Profit'))
		     );
$smarty->assign('plot_category_menu',$plot_category_menu);



$smarty->assign('store',$store);

$smarty->assign('parent','products');
$smarty->assign('title', $store->data['Store Name']);

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