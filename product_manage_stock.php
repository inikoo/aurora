<?php
include_once('common.php');
include_once('class.Product.php');



$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',	$yui_path.'datatable/datatable-debug.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/search.js',
		'common.js.php',
		'table_common.js.php',
		);



if(!isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
  $product_id=1;
else
  $product_id=$_REQUEST['id'];
$_SESSION['state']['product']['tag']=$product_id;
$_SESSION['state']['product']['mode']='pid';
$mode='pid';
$tag=$product_id;
$product= new product($mode,$tag);
$store= new store($product->data['Product Store Key']);


$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$view_orders=$user->can_view('orders');

$create=$user->can_create('products');
$modify=$user->can_edit('products');
$modify_stock=$user->can_edit('product stock');
$smarty->assign('modify_stock',$modify_stock);
$view_suppliers=$user->can_view('suppliers');
$view_cust=$user->can_view('customers');

$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('view_suppliers',$view_suppliers);
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
$smarty->assign('view_orders',$view_orders);
$smarty->assign('view_customers',$view_cust);



//$locations=($product->get('locations'));
//$smarty->assign('locations',$locations);
//print_r($locations);

$smarty->assign('parent','products');
$smarty->assign('title',$product->get('Product Code'));


$product_home="Products Home";
$smarty->assign('home',$product_home);



//$_SESSION['state']['product']['manage_stock_data']=json_encode($locations);


$js_files[]='product_manage_stock.js.php';

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->display('product_manage_stock.tpl');
?>