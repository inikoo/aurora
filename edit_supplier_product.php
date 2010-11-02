<?php
include_once('common.php');
include_once('class.SupplierProduct.php');
include_once('class.Supplier.php');



$modify=$user->can_edit('suppliers');


$view_suppliers=$user->can_view('suppliers');


if(!$view_suppliers){
    header('Location: index.php');
    exit();
}

if(!$modify){
    header('Location: supplier_product.php');
    exit();
}

$supplier_key=(isset($_REQUEST['supplier_key'])?$_REQUEST['supplier_key']:$_SESSION['state']['supplier_product']['supplier_key']);
$supplier_product_code=(isset($_REQUEST['code'])?$_REQUEST['code']:$_SESSION['state']['supplier_product']['code']);

if(!$supplier_key or !$supplier_product_code){
 header('Location: suppliers.php?e');
    exit();
}


$supplier_product= new SupplierProduct('code',$supplier_product_code,$supplier_key);
if(!$supplier_product->id){
header('Location: supplier.php?id='.$supplier_key);
   exit;

}
$supplier=new Supplier($supplier_product->data['Supplier Key']);
$_SESSION['state']['supplier_product']['code']=$supplier_product->data['Supplier Product Code'];
$_SESSION['state']['supplier_product']['supplier_key']=$supplier_product->data['Supplier Key'];

$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'supplier_product.php','label'=>_('Exit Edit'));
$smarty->assign('general_options_list',$general_options_list);



$supplier_product->load_images_slidesshow();
$images=$supplier_product->images_slideshow;


$smarty->assign('images',$images);
$smarty->assign('num_images',count($images));


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 $yui_path.'container/assets/skins/sam/container.css',
		 $yui_path.'editor/assets/skins/sam/editor.css',
		  'text_editor.css',
		 'common.css',
		 'button.css',
		 'table.css',
		 'css/edit.css'
		 );




$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',	
		$yui_path.'datatable/datatable.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'editor/editor-min.js',
		'js/php.default.min.js',
		'common.js.php',
		'js/search.js',
		'table_common.js.php',
		'js/upload_image.js',
		'js/edit_common.js'
		);


$smarty->assign('parent','suppliers');
$smarty->assign('title',$supplier_product->get('Supplier Product Code'));




$js_files[]=sprintf('edit_supplier_product.js.php');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$units_types=getEnumValues("Supplier Product Dimension","Supplier Product Unit Type" );
//print_r($units_types);
$unit_type_options=array();
foreach($units_types as $units_type ){
  $unit_type_options[$units_type]=$units_type;
}

$smarty->assign('unit_type_options',$unit_type_options
                                );
$smarty->assign('unit_type',$product->data['Product Unit Type']);

$smarty->display('edit_supplier_product.tpl');
?>