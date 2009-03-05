<?
include_once('common.php');
//include_once('stock_functions.php');
include_once('classes/Product.php');

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
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css'
		 );
$js_files=array(
		$yui_path.'yahoo-dom-event/yahoo-dom-event.js',
		$yui_path.'connection/connection-min.js',
		$yui_path.'json/json-min.js',
		$yui_path.'element/element-beta-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-debug.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		);





// $_SESSION['views']['product_blocks'][5]=0;
// foreach($_SESSION['views']['product_blocks'] as $key=>$value){
//   $hide[$key]=($value==1?0:1);
// }
// //print_r($hide);

$smarty->assign('display',$_SESSION['state']['part']['display']);

// $smarty->assign('view_plot',$_SESSION['views']['part_plot']);

if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id'])){
  $part_id=$_REQUEST['id'];
  $_SESSION['state']['part']['id']=$part_id;
  $part= new part($part_id);
  $_SESSION['state']['part']['sku']=$part->data['Part SKU'];
 }else if(isset($_REQUEST['sku']) and is_numeric($_REQUEST['sku'])){
  $part= new part('sku',$_REQUEST['sku']);
  $part_id=$part->id;
  $_SESSION['state']['part']['id']=$part_id;
  $_SESSION['state']['part']['sku']=$part->data['Part SKU'];
 }else{
  $part_id=$_SESSION['state']['part']['id'];
  $_SESSION['state']['part']['id']=$part_id;
  $part= new part($part_id);
  $_SESSION['state']['part']['sku']=$part->data['Part SKU'];
  
 }



$smarty->assign('part',$part);
$smarty->assign('parent','departments.php');
$smarty->assign('title',$part->get('Part SKU'));
$plot_tipo=$_SESSION['state']['part']['plot'];
$plot_data=$_SESSION['state']['part']['plot_data'];
$smarty->assign('plot_tipo',$plot_tipo);
$smarty->assign('plot_data',$plot_data);
$smarty->assign('key_filter_number',$regex['key_filter_number']);
$smarty->assign('key_filter_dimension',$regex['key_filter_dimension']);

//$js_files[]= 'js/search.js';
$js_files[]='js/part.js.php';

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('part.tpl');
?>