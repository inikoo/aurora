<?
/*
 File: departments.php 

 UI department page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0

 Created: 20-04-2009 17:38

*/
include_once('common.php');
include_once('stock_functions.php');

$view_sales=$LU->checkRight(PROD_SALES_VIEW);
$view_stock=$LU->checkRight(PROD_STK_VIEW);
$create=$LU->checkRight(PROD_CREATE);
$modify=$LU->checkRight(PROD_MODIFY);



$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);


if(isset($_REQUEST['edit']))
  $edit=$_REQUEST['edit'];
else
  $edit=$_SESSION['state']['departments']['edit'];



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
		'js/common.js.php',
		'js/table_common.js.php',
		);

if($edit){
  $js_files[]='js/edit_common.js';
  $js_files[]='js/edit_departments.js.php';
 } else{
   $js_files[]='js/search.js';
   $js_files[]='js/departments.js.php';
 }


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$_SESSION['state']['assets']['page']='departments';
//if(isset($_REQUEST['view'])){
//  $valid_views=array('sales','general','stoke');
//  if (in_array($_REQUEST['view'], $valid_views)) 
//    $_SESSION['state']['departments']['view']=$_REQUEST['view'];
//
// }
$smarty->assign('view',$_SESSION['state']['departments']['view']);
$smarty->assign('show_details',$_SESSION['state']['departments']['details']);
$smarty->assign('show_percentages',$_SESSION['state']['departments']['percentages']);
$smarty->assign('avg',$_SESSION['state']['departments']['avg']);
$smarty->assign('period',$_SESSION['state']['departments']['period']);


//$sql="select id from product";
//$result =& $db->query($sql);

// include_once('classes/product.php');
// while($row=$result->fetchRow()){
//   $product= new product($row['id']);
//   $product->set_stock();
// }




$smarty->assign('parent','stores.php');
$smarty->assign('title', _('Departments'));
//$smarty->assign('total_stores',$stores['numberof']);
//$smarty->assign('table_title',$table_title);



$departments=array();
$sql=sprintf("select count(*) as num from `Product Department Dimension` ");

$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){
  $departments=$row['num'];
 }
 
$smarty->assign('departments',$departments);

$q='';
$tipo_filter=($q==''?$_SESSION['state']['departments']['table']['f_field']:'code');
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',($q==''?$_SESSION['state']['departments']['table']['f_value']:addslashes($q)));
$filter_menu=array(
		   'code'=>array('db_key'=>'code','menu_label'=>'Store starting with  <i>x</i>','label'=>'Code'),
		   'description'=>array('db_key'=>'description','menu_label'=>'Store Description with <i>x</i>','label'=>'Description'),
		   );
$smarty->assign('filter_menu',$filter_menu);

$smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu',$paginator_menu);


if($edit){
$smarty->display('edit_departments.tpl');
 }else
$smarty->display('departments.tpl');

?>