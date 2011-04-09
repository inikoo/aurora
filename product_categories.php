<?php
include_once('class.Category.php');

include_once('common.php');
include_once('assets_header_functions.php');



if(!$user->can_view('stores')  ){
  header('Location: index.php');
   exit;
}
$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
//$modify=false;
$modify=$user->can_edit('stores');

get_header_info($user,$smarty);
$general_options_list=array();


$smarty->assign('view',$_SESSION['state']['product_categories']['view']);




$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css',
		 'css/dropdown.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js',
		'js/table_common.js',
		'search.js',
		'js/edit_category_common.js',
		'product_categories.js.php',
		'js/dropdown.js',
		
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


if(isset($_REQUEST['id'])){
$category_key=$_REQUEST['id'];
}else{
$category_key=$_SESSION['state']['product_categories']['category_key'];
}

if(!$category_key){

if (isset($_REQUEST['store_id']) and is_numeric($_REQUEST['store_id']) ) {
    $store_id=$_REQUEST['store_id'];

} else {
    $store_id=$_SESSION['state']['store']['id'];
}


if($modify){

     $general_options_list[]=array('tipo'=>'js','id'=>'new_category','label'=>_('Add Main Category'));
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_product_category.php?store_id='.$store_id.'&id=0','label'=>_('Edit Categories'));

}
$tpl_file='product_categories_base.tpl';

}else{



$category=new Category($category_key);
if(!$category->id){
header('Location: product_categories.php?id=0&error=cat_not_found');
   exit;

}

 $category_key=  $category->id;         

if($modify){
   $general_options_list[]=array('tipo'=>'js','id'=>'new_category','label'=>_('Add Subcategory'));
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_product_category.php?&id='.$category->id,'label'=>_('Edit Category'));

}

$store_id=$category->data['Category Store Key'];

$smarty->assign('category',$category);


$tpl_file='product_category.tpl';


}


$_SESSION['state']['product_categories']['category_key']=$category_key;


$store=new Store($store_id);

if(!$store->id){

exit("Error wrong store");
}

$_SESSION['state']['store']['id']=$store->id;
$smarty->assign('store',$store);
$smarty->assign('subject','Product');


$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('category_key',$category_key);

$smarty->display($tpl_file);
?>
