<?php
/*
 File: department.php 

 UI department page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2010, Inikoo 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');
include_once('class.Department.php');
include_once('assets_header_functions.php');

if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']) )
  $department_id=$_SESSION['state']['department']['id'];
 else{
   $department_id=$_REQUEST['id'];
   $_SESSION['state']['department']['id']=$department_id;
  }
$department=new Department($department_id);

if(!( $user->can_view('stores') and in_array($department->data['Product Department Store Key'],$user->stores))){
 header('Location: index.php');
  exit();
  }
  $modify=$user->can_edit('stores');
  if(!$modify){
  header('Location: department.php?id='.$department_id);
  exit();
 
 }
  
 
$store=new Store($department->get('Product Department Store Key'));


$create=$user->can_create('product families');




if(isset($_REQUEST['edit_tab'])){
  $edit=$_REQUEST['edit_tab'];
$_SESSION['state']['department']['editing']=$edit;
}else{
  $edit=$_SESSION['state']['department']['editing'];
}




$smarty->assign('create',$create);
$smarty->assign('modify',$modify);

get_header_info($user,$smarty);


$general_options_list=array();


  $general_options_list[]=array('tipo'=>'url','url'=>'department.php','label'=>_('Exit Edit'));

$smarty->assign('search_label',_('Products'));
    $smarty->assign('search_scope','products');
  
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
include_once('Theme.php');
$js_files=array(
		
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
			$yui_path.'uploader/uploader.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'history/history-min.js',
		$yui_path.'datatable/datatable-debug.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'js/php.default.min.js',
		'js/common.js',
		'js/search.js',
		'js/table_common.js',
		'js/edit_common.js',
                'js/csv_common.js',
		 'js/dropdown.js'

		);

$department->load_images_slidesshow();
$images=$department->images_slideshow;
$smarty->assign('images',$images);




  $css_files[]='css/edit.css';
  
  $js_files[]='js/edit_common.js';
  $js_files[]='js/upload_image.js';
  $js_files[]='edit_department.js.php';
  
  
  

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



 $store_order='`Product Department Code`';
    if ($store_order=='families')
        $store_order='`Product Department Families`';
    if ($store_order=='todo')
        $store_order='`Product Department In Process Products`';
    if ($store_order=='profit') {
        if ($store_period=='all')
            $store_order='`Product Department Total Profit`';
        elseif($store_period=='year')
        $store_order='`Product Department 1 Year Acc Profit`';
        elseif($store_period=='quarter')
        $store_order='`Product Department 1 Quarter Acc Profit`';
        elseif($store_period=='month')
        $store_order='`Product Department 1 Month Acc Profit`';
        elseif($store_period=='week')
        $store_order='`Product Department 1 Week Acc Profit`';
    }
    elseif($store_order=='sales') {
        if ($store_period=='all')
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
    elseif($store_order=='code')
    $store_order='`Product Department Code`';
    elseif($store_order=='active')
    $store_order='`Product Department For Sale Products`';
    elseif($store_order=='outofstock')
    $store_order='`Product Department Out Of Stock Products`';
    elseif($store_order=='stock_error')
    $store_order='`Product Department Unknown Stock Products`';
    elseif($store_order=='surplus')
    $store_order='`Product Department Surplus Availability Products`';
    elseif($store_order=='optimal')
    $store_order='`Product Department Optimal Availability Products`';
    elseif($store_order=='low')
    $store_order='`Product Department Low Availability Products`';
    elseif($store_order=='critical')
    $store_order='`Product Department Critical Availability Products`';

  


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



$smarty->assign('parent','products');

$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$department);
$smarty->assign('store',$store);

$smarty->assign('edit',$edit);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

  $smarty->assign('title', _('Editing').': '.$department->get('Product Department Code'));
  
  
  
  
  
  
  $number_of_sites=0;
$site_key=0;
$number_of_pages=0;
$page_key=0;

$sql=sprintf("select count(*) as num, `Site Key` from `Site Dimension` where `Site Store Key`=%d ",
$department->data['Product Department Store Key']);

$res=mysql_query($sql);
if($row=mysql_fetch_assoc($res)){
$number_of_sites=$row['num'];
if($number_of_sites==1)
$site_key=$row['Site Key'];

}

$sql=sprintf("select count(*) as num, `Page Key` from `Page Store Dimension` where `Page Store Section`='Department Catalogue' and `Page Parent Key`=%d ",
$department->id);
$res=mysql_query($sql);
if($row=mysql_fetch_assoc($res)){
$number_of_pages=$row['num'];
if($number_of_pages==1)
$page_key=$row['Page Key'];

}




if( isset(  $_REQUEST['page_key']) and is_numeric($_REQUEST['page_key'])){

$page_key=$_REQUEST['page_key'];

}

if($page_key){
$page=new Page($page_key);
//print_r($page);exit;
foreach($page->data as $key=>$value){
  $page_data[preg_replace('/\s/','',$key)]=$value;
  
}

$smarty->assign('page_data',$page_data);
}
$smarty->assign('number_of_sites',$number_of_sites);
$smarty->assign('site_key',$site_key);
$smarty->assign('number_of_pages',$number_of_pages);
$smarty->assign('page_key',$page_key);
  
  
  
  
  $smarty->display('edit_department.tpl');

?>
