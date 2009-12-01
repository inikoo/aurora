<?php
/*
 File: family.php 

 UI family page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.Family.php');
include_once('class.Store.php');
include_once('class.Department.php');
include_once('assets_header_functions.php');

if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
  $family_id=$_SESSION['state']['family']['id'];
 else
   $family_id=$_REQUEST['id'];
$_SESSION['state']['family']['id']=$family_id;
$family=new Family($family_id);



if(!( $user->can_view('stores') and in_array($family->data['Product Family Store Key'],$user->stores)))
  exit();


$store=new Store($family->data['Product Family Store Key']);
$department=new Department($family->get('Product Family Main Department Key'));

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product families');
$modify=$user->can_edit('stores');




if(isset($_REQUEST['edit']))
  $edit=$_REQUEST['edit'];
else
  $edit=$_SESSION['state']['family']['editing'];
if(!$modify)
  $edit=false;
$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);

get_header_info($user,$smarty);

$show_details=$_SESSION['state']['family']['details'];
$smarty->assign('table_type',$_SESSION['state']['family']['table_type']);
$general_options_list=array();

if($edit){
  $general_options_list[]=array('tipo'=>'url','url'=>'family.php?edit=0','label'=>_('Exit Edit'));

}else{
  if($modify)
    $general_options_list[]=array('tipo'=>'url','url'=>'family.php?edit=1','label'=>_('Edit Family'));
  $general_options_list[]=array('tipo'=>'js','state'=>$show_details,'id'=>'details','label'=>($show_details?_('Hide Details'):_('Show Details')));

}
  $smarty->assign('general_options_list',$general_options_list);
$show_only=$_SESSION['state']['family']['table']['show_only'];
$show_only_labels=array('forsale'=>_('For Sale Only'));

$_SESSION['state']['products']['table']['restrictions']=$show_only;
//print_r($_SESSION['state']['products']['table']);exit;

$smarty->assign('show_only',$show_only);
$smarty->assign('show_only_label',$show_only_labels[$show_only]);


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
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
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-debug.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/php.default.min.js',
		'common.js.php',
		'table_common.js.php',

		 'js/dropdown.js'
		);


if($edit){
  $smarty->assign('edit',$_SESSION['state']['family']['edit']);
  $css_files[]='css/edit.css';
  $js_files[]='js/edit_common.js';
  $js_files[]='country_select.js.php';
  $js_files[]='edit_family.js.php';
 }else{
 $js_files[]='js/search.js';
  $js_files[]='family.js.php';
  

 }

 // print_r(parse_money('€2.50'));
// exit;

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);






$_SESSION['state']['assets']['page']='department';
if(isset($_REQUEST['view'])){
  $valid_views=array('sales','general','stoke');
  if (in_array($_REQUEST['view'], $valid_views)) 
    $_SESSION['state']['product']['view']=$_REQUEST['view'];

 }

$department_order=$_SESSION['state']['department']['table']['order'];
$department_period=$_SESSION['state']['department']['period'];
$department_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));
  

$smarty->assign('department_period',$department_period);
$smarty->assign('department_period_title',$department_period_title[$department_period]);




if(isset($_REQUEST['department_id']) and $_REQUEST['department_id']>0){
  $department_id=$_REQUEST['department_id'];
  $order=$_SESSION['state']['department']['table']['order'];
  if($order=='per_tsall' or $order=='tsall')
    $order='total_sales';
  if($order=='per_tsm' or $order=='tms')
    $order='month_sales';
  if($order=='code')
    $order='Product Family Code';
  if($order=='name')
    $order='Product Family Name';
  if($order=='active')
    $order='Product Family For Sale Products';
  if($order=='outofstock')
    $order='Product Family Out Of Stock Products';
  if($order=='stockerror')
    $order='Product Family Unknown Stock Products';
  




$sql=sprintf("select  F.`Product Family Key` as id, `Product Family Code` as code  from `Product Family Dimension`   F left join `Product Family Department Bridge` FD on (FD.`Product Family Key`=F.`Product Family Key`) where  `%s`<'%s' and `Product Department Key`=%d  order by `%s` desc  ",$order,$family->get($order),$department_id,$order);


$res = mysql_query($sql);
if(!$prev=mysql_fetch_array($res, MYSQL_ASSOC))
  $prev=array('id'=>0,'code'=>'');

$sql=sprintf("select F.`Product Family Key` as id, `Product Family Code` as code   from `Product Family Dimension`   F left join `Product Family Department Bridge`  FD on (FD.`Product Family Key`=F.`Product Family Key`)  where  `%s`>'%s' and `Product Department Key`=%d order by `%s`   ",$order,$family->get($order),$department_id,$order);

$res = mysql_query($sql);

if(!$next=mysql_fetch_array($res, MYSQL_ASSOC))
  $next=array('id'=>0,'code'=>'');



 


$smarty->assign('prev',$prev);
$smarty->assign('next',$next);

 }



$plot_tipo=$_SESSION['state']['family']['plot'];
$plot_data=$_SESSION['state']['family']['plot_data'][$plot_tipo];
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

$plot_args='tipo=family&category='.$plot_category.'&period='.$plot_period.'&keys='.$family_id.'&currency='.$store->data['Store Currency Code'];

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

  $plot_args=sprintf('tipo=children_share&item=family&category=%s&period=%s&keys=%d&date=%s&forecast=%s'
		     ,$plot_category
		     ,$plot_period
		     ,$family_id
		     ,$plot_date
		     ,$plot_data['forecast']);
}

$smarty->assign('plot_tipo',$plot_tipo);
$smarty->assign('plot_args',$plot_args);
$smarty->assign('plot_page',$plot_data['page']);
$smarty->assign('plot_period',$plot_period);
$smarty->assign('plot_category',$plot_period);
$smarty->assign('plot_data',$_SESSION['state']['family']['plot_data']);


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







$smarty->assign('parent','departments.php');
$smarty->assign('title',$family->get('Product Family Code').' - '.$family->get('Product Family Name'));


$product_home="Products Home";
$smarty->assign('home',$product_home);
// $smarty->assign('department',$family->get('department'));
// $smarty->assign('department_id',$family->data['department_id']);
// $smarty->assign('products',$family->get('product_numbers'));
// $smarty->assign('data',$family->data);




 $smarty->assign('family',$family);
 $smarty->assign('store',$store);
 $smarty->assign('department',$department);


$smarty->assign('filter','code');
$smarty->assign('filter_name',_('Product code'));
$smarty->assign('filter_value',$_SESSION['tables']['products_list'][7]);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu',$paginator_menu);

$smarty->assign('view',$_SESSION['state']['products']['view']);
$smarty->assign('show_details',$_SESSION['state']['family']['details']);
$smarty->assign('period',$_SESSION['state']['products']['period']);
$smarty->assign('avg',$_SESSION['state']['products']['avg']);


$table_title=_('Product List');
$smarty->assign('table_title',$table_title);

$info_period_menu=array(
			array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
		     ,array("period"=>'month','label'=>_('last Month'),'title'=>_('last Month'))
		     ,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
		     ,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
		     ,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
		     );
$smarty->assign('info_period_menu',$info_period_menu);


//print show_currency_conversion('USD','GBP');


if($edit){
$smarty->assign('view',$_SESSION['state']['family']['edit_view']);
$units_tipo=array(
		  'Piece'=>array('fname'=>_('Piece'),'name'=>'Piece','selected'=>false),
		  'Grams'=>array('fname'=>_('Grams'),'name'=>'Grams','selected'=>false),
		  'Liters'=>array('fname'=>_('Liters'),'name'=>'Liters','selected'=>false),
		  'Meters'=>array('fname'=>_('Meters'),'name'=>'Meters','selected'=>false),
		  'Other'=>array('fname'=>_('Other'),'name'=>'Other','selected'=>false),
);
 $units_tipo['Piece']['selected']=true;

$smarty->assign('units_tipo',$units_tipo);
  $smarty->assign('title', _('Editing Family').': '.$family->get('Product Family Code'));
  $smarty->display('edit_family.tpl');
 }else{
  $smarty->assign('title',_('Family').': '.$family->get('Product Family Name'));
  $smarty->display('family.tpl');
 }

?>