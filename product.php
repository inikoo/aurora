<?php
/*
 File: product.php 

 UI product page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.Product.php');

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$view_orders=$user->can_view('orders');

$create=$user->can_create('products');
$modify=$user->can_edit('products');
$modify_stock=$user->can_edit('product stock');
$smarty->assign('modify_stock',$modify_stock);
$view_suppliers=$user->can_view('suppliers');
$view_cust=$user->can_view('customers');


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

$smarty->assign('display',$_SESSION['state']['product']['display']);

// $smarty->assign('view_plot',$_SESSION['views']['product_plot']);

if(isset($_REQUEST['code'])){
  $mode='code';
  $tag=$_REQUEST['code'];
 }elseif(isset($_REQUEST['pid'])){
  $mode='pid';
  $tag=$_REQUEST['pid'];
 }elseif(isset($_REQUEST['key'])){
  $mode='key';
  $tag=$_REQUEST['key'];
  }else{
  $tag=$_SESSION['state']['product']['tag'];
  $mode=$_SESSION['state']['product']['mode'];
 }
$_SESSION['state']['product']['tag']=$tag;
$_SESSION['state']['product']['mode']=$mode;
$_SESSION['state']['product']['orders']['mode']=$mode;
$_SESSION['state']['product']['customers']['mode']=$mode;



if($mode=='code'){
  $sql=sprintf("select `Product ID`  from `Product Dimension` where `Product Code`=%s and `Product Most Recent`='Yes' group by `Product ID`;"
        ,prepare_mysql($tag));

  $result=mysql_query($sql);
  
  if(mysql_num_rows($result)>1){
    $_SESSION['state']['product']['server']['tag']=$tag;
    $js_files[]= 'js/search.js';
    $js_files[]='product.js.php'; 
    $js_files[]='product_server.js.php'; 
    $smarty->assign('css_files',$css_files);
    $smarty->assign('js_files',$js_files);
    $smarty->assign('code',$tag);
    $smarty->display('product_server.tpl');
     mysql_free_result($result);
    exit;
  }
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $tag=$row['Product ID'];
     $mode='id';
     $_SESSION['state']['product']['tag']=$tag;
     $_SESSION['state']['product']['mode']=$mode;
  }
  mysql_free_result($result);
} 
    
   

$product= new product($mode,$tag);



$product->load('part_location_list');
$smarty->assign('product',$product);
$smarty->assign('product_id',$product->get('product current key'));
$smarty->assign('data',$product->data);
$web_status_error=false;
$web_status_error_title='';
 if($product->get('Product Web State')=='Online For Sale'){
   if(!($product->get('Product Availability')>0)){
     $web_status_error=true;
     $web_status_error_title=_('This product is out of stock');
   }
  }else{
   if($product->get('Product Availability')>0){
       $web_status_error=true;
       $web_status_error_title=_('This product is not for sale on the webpage');
   }
 }

$smarty->assign('web_status_error',$web_status_error);
$smarty->assign('web_status_error_title',$web_status_error_title);




$smarty->assign('parent','departments.php');
$smarty->assign('title',$product->get('Product Code'));


$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$product->get('Product Main Department Name'));
$smarty->assign('department_id',$product->get('Product Main Department Key'));
$smarty->assign('family',$product->get('Product Family Code'));
$smarty->assign('family_id',$product->get('Product Family Key'));

$product->load('images_slideshow');
$images=$product->images_slideshow;

$smarty->assign('div_img_width',190);

$smarty->assign('img_width',190);



$smarty->assign('images',$images);


$smarty->assign('num_images',count($images));

$plot_tipo=$_SESSION['state']['product']['plot'];

if(preg_match('/week/',$plot_tipo))
  $plot_interval='week';
if(preg_match('/month/',$plot_tipo))
  $plot_interval='month';
if(preg_match('/quarter/',$plot_tipo))
  $plot_interval='quarter';
if(preg_match('/year/',$plot_tipo))
  $plot_interval='year';

$plot_data=$_SESSION['state']['product']['plot_data'][$plot_interval];

//print print_r($_SESSION['state']['product']);
$smarty->assign('plot_tipo',$plot_tipo);
$smarty->assign('plot_data',$plot_data);




//$smarty->assign('stock_table_options',array(_('Inv'),_('Pur'),_('Adj'),_('Sal'),_('P Sal')) );
//$smarty->assign('stock_table_options_tipo', $_SESSION['views']['stock_table_options'] );
$smarty->assign('table_title_orders',_('Orders'));
$smarty->assign('table_title_customers',_('Customers'));
$smarty->assign('table_title_stock',_('Stock History'));



$smarty->assign('key_filter_number',$regex['key_filter_number']);
$smarty->assign('key_filter_dimension',$regex['key_filter_dimension']);


$js_files[]= 'js/search.js';
$js_files[]='product.js.php';


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('web_status_menu',$_web_status);


$smarty->display('product.tpl');
?>