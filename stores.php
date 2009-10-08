<?php
/*
 File: stores.php 

 UI stores page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('common.php');
//include_once('stock_functions.php');
if(!$user->can_view('stores'))
  exit();

$avileable_stores_list=$user->can_view_list('stores');
$avileable_stores=count($avileable_stores_list);
if($avileable_stores==1){
  header('Location: store.php?id='.$avileable_stores_list[0]);
  
}

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('stores');
$modify=$user->can_edit('stores');



$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);


if(isset($_REQUEST['edit']))
  $edit=$_REQUEST['edit'];
else
  $edit=$_SESSION['state']['stores']['editing'];



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
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'common.js.php',
		'table_common.js.php',
		);

if($edit){
  $js_files[]='js/edit_common.js';
  $js_files[]='edit_stores.js.php';
 } else{
   $js_files[]='js/search.js';
   $js_files[]='stores.js.php';
 }


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$_SESSION['state']['assets']['page']='stores';
//if(isset($_REQUEST['view'])){
//  $valid_views=array('sales','general','stoke');
//  if (in_array($_REQUEST['view'], $valid_views)) 
//    $_SESSION['state']['stores']['view']=$_REQUEST['view'];
//
// }
$smarty->assign('view',$_SESSION['state']['stores']['view']);
$smarty->assign('show_details',$_SESSION['state']['stores']['details']);
$smarty->assign('show_percentages',$_SESSION['state']['stores']['percentages']);
$smarty->assign('avg',$_SESSION['state']['stores']['avg']);
$smarty->assign('period',$_SESSION['state']['stores']['period']);


//$sql="select id from product";
//$result=mysql_query($sql);

// include_once('class.product.php');
// while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
//   $product= new product($row['id']);
//   $product->set_stock();
// }




$smarty->assign('parent','stores.php');
$smarty->assign('title', _('Stores'));
//$smarty->assign('total_stores',$stores['numberof']);
//$smarty->assign('table_title',$table_title);



$stores=array();
$sql=sprintf("select count(*) as num from `Store Dimension` CD order by `Store Key`");

$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){
  $stores=$row['num'];
 }
 mysql_free_result($res);
$smarty->assign('stores',$stores);

$q='';
$tipo_filter=($q==''?$_SESSION['state']['stores']['table']['f_field']:'code');
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',($q==''?$_SESSION['state']['stores']['table']['f_value']:addslashes($q)));
$filter_menu=array(
		   'code'=>array('db_key'=>'code','menu_label'=>'Store starting with  <i>x</i>','label'=>'Code'),
		   'description'=>array('db_key'=>'description','menu_label'=>'Store Description with <i>x</i>','label'=>'Description'),
		   );
$smarty->assign('filter_menu',$filter_menu);

$smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu',$paginator_menu);


if($edit){
$smarty->display('edit_stores.tpl');
 }else
$smarty->display('stores.tpl');

?>