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

$product_supplier_key=(isset($_REQUEST['pid'])?$_REQUEST['pid']:$_SESSION['state']['supplier_product']['pid']);
$supplier_key=(isset($_REQUEST['supplier_key'])?$_REQUEST['supplier_key']:$_SESSION['state']['supplier_product']['supplier_key']);
$supplier_product_code=(isset($_REQUEST['code'])?$_REQUEST['code']:$_SESSION['state']['supplier_product']['code']);
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

$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'supplier_product.php','label'=>_('Exit Edit'));
$smarty->assign('general_options_list',$general_options_list);


$smarty->assign('supplier_product',$supplier_product);

$supplier_product->load_images_slidesshow();
$images=$supplier_product->images_slideshow;


$smarty->assign('images',$images);
$smarty->assign('num_images',count($images));


if(isset($_REQUEST['edit_tab'])){
  $editing=$_REQUEST['edit_tab'];
$_SESSION['state']['supplier_product']['editing']=$edit;
}else{
  $editing=$_SESSION['state']['supplier_product']['editing'];
}
$smarty->assign('edit',$editing);




$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 $yui_path.'container/assets/skins/sam/container.css',
		 $yui_path.'editor/assets/skins/sam/editor.css',
		  'css/text_editor.css',
		 'css/common.css',
		 'css/button.css',
		 'css/table.css',
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
		'js/common.js',
		'js/search.js',
		'js/table_common.js',
		'js/upload_image.js',
		'js/edit_common.js'
		);


$smarty->assign('parent','suppliers');
$smarty->assign('title',$supplier_product->get('Supplier Product Code'));




$js_files[]=sprintf('edit_supplier_product.js.php');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$units_types=getEnumValues("Supplier Product Dimension","Supplier Product Unit Type" );

$unit_type_options=array();
foreach($units_types as $units_type ){
  $unit_type_options[$units_type]=$units_type;
}
$smarty->assign('unit_type_options',$unit_type_options);
$smarty->assign('unit_type',$supplier_product->data['Supplier Product Unit Type']);


$unit_packing_types=getEnumValues("Supplier Product Dimension","Supplier Product Unit Package Type" );

$unit_packing_type_options=array();
$i=0;$index=0;
foreach($unit_packing_types as $units_type ){
  $unit_packing_type_options[$units_type]=$units_type;
  if($units_type==$supplier_product->data['Supplier Product Unit Package Type']){
   $index=$i; 
  }
  $i++;
}

$smarty->assign('unit_packing_type_options',$unit_packing_type_options);
$smarty->assign('unit_packing_type',$supplier_product->data['Supplier Product Unit Package Type']);
$smarty->assign('unit_packing_index',$index);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->display('edit_supplier_product.tpl');
?>