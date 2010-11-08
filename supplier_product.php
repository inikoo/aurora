<?php
include_once('common.php');
//include_once('stock_functions.php');
include_once('class.SupplierProduct.php');
include_once('class.Supplier.php');

$view_suppliers=$user->can_view('suppliers');


if(!$view_suppliers){
    header('Location: index.php');
    exit();
}




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
		'common.js.php',
		'table_common.js.php',
		);

$smarty->assign('display',$_SESSION['state']['supplier_product']['display']);

$product_supplier_key=(isset($_REQUEST['pid'])?$_REQUEST['pid']:$_SESSION['state']['supplier_product']['pid']);
//$supplier_key=(isset($_REQUEST['supplier_key'])?$_REQUEST['supplier_key']:$_SESSION['state']['supplier_product']['supplier_key']);
//$supplier_product_code=(isset($_REQUEST['code'])?$_REQUEST['code']:$_SESSION['state']['supplier_product']['code']);
if(!$product_supplier_key){
 header('Location: suppliers.php?e');
    exit();
}
$supplier_product= new SupplierProduct('pid',$product_supplier_key);
if(!$supplier_product->id){
header('Location: suppliers.php');
   exit;

}
$supplier_key=$supplier_product->supplier_key;
$supplier_product_code=$supplier_product->code;
$supplier=new Supplier($supplier_product->data['Supplier Key']);

$_SESSION['state']['supplier_product']['code']=$supplier_product_code;
$_SESSION['state']['supplier_product']['supplier_key']=$supplier_key;
$_SESSION['state']['supplier_product']['pid']=$supplier_product->pid;
$_SESSION['state']['supplier_product']['id']=$supplier_product->id;



$modify=$user->can_edit('suppliers');
$general_options_list=array();
if($modify)
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_supplier_product.php','label'=>_('Edit Product'));
$smarty->assign('general_options_list',$general_options_list);


$smarty->assign('supplier_product',$supplier_product);
$smarty->assign('supplier',$supplier);

$smarty->assign('parent','suppliers');
$smarty->assign('title',$supplier_product->get('Supplier Product Code'));



$js_files[]= 'js/search.js';
$js_files[]='supplier_product.js.php';



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

//$parts=$product_suppliir->get_parts();



$smarty->display('supplier_product.tpl');




?>