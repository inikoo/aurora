<?php
include_once('common.php');
//include_once('stock_functions.php');
include_once('class.SupplierProduct.php');




//smarty->assign('view_suppliers',$view_suppliers);
//$smarty->assign('view_sales',$view_sales);
//$smarty->assign('view_stock',$view_stock);
//$smarty->assign('create',$create);
//$smarty->assign('modify',$modify);
//$smarty->assign('view_orders',$view_orders);
//$smarty->assign('view_customers',$view_cust);

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
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		);





// $_SESSION['views']['product_blocks'][5]=0;
// foreach($_SESSION['views']['product_blocks'] as $key=>$value){
//   $hide[$key]=($value==1?0:1);
// }
// //print_r($hide);

$smarty->assign('display',$_SESSION['state']['supplier_product']['display']);

// $smarty->assign('view_plot',$_SESSION['views']['product_plot']);

if(!isset($_REQUEST['code']) 
or !isset($_REQUEST['supplier_key'])  
or !is_numeric($_REQUEST['supplier_key'])
){
//header('Location: suppliers.php?e');
   exit('x');
}

$supplier_product= new SupplierProduct('code',$_REQUEST['code'],$_REQUEST['supplier_key']);
if(!$supplier_product->id){
header('Location: supplier.php?id='.$_REQUEST['supplier_key']);
   exit;

}


$_SESSION['state']['supplier_product']['code']=$supplier_product->data['Supplier Product Code'];
$_SESSION['state']['supplier_product']['code']=$supplier_product->data['Supplier Key'];




$smarty->assign('supplier_product',$supplier_product);

$smarty->assign('parent','suppliers');
$smarty->assign('title',$supplier_product->get('Supplier Product Code'));



$js_files[]= 'js/search.js';
$js_files[]='js/supplier_product.js.php';



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$smarty->display('supplier_product.tpl');
?>