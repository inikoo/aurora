<?php
include_once('common.php');
include_once('class.Node.php');
include_once('class.Category.php');
/*
if(!$user->can_view('staff')){
   header('Location: index.php?no=1');
   exit;
}
*/

if(isset($_REQUEST['id'])){
$category_key=$_REQUEST['id'];
}else{
$category_key=$_SESSION['state']['product_categories']['category_key'];
}

$nodes=new Nodes('`Category Dimension`');
$category=new Category('category_key',$category_key);
if($cat_data=$nodes->fetch($category_key)){
}else{
$category_key=0;
$main_title=_('Categories');
$subcategories_title=_('Category List');
}
$main_title=_('Categories');
$subcategories_title=_('Category List');

$_SESSION['state']['product_categories']['category_key']=$category_key;
$smarty->assign('main_title',$main_title);
$smarty->assign('subcategories_title',$subcategories_title);




if(!$category= new Category('category_key',$category_key))
  exit('Error category not found');



$modify=$user->can_edit('staff');

$edit=false;
if(isset($_REQUEST['edit']) and $_REQUEST['edit'])
  $edit=true;


if(!$modify)
 $edit=false;
$general_options_list=array();

if($edit){
  $general_options_list[]=array('tipo'=>'url','url'=>'categories.php','label'=>_('Exit Edit'));

}else{
if($modify){
 // $general_options_list[]=array('tipo'=>'url','url'=>'categories.php','label'=>_('Exit Edit'));
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_category?edit=1','label'=>_('Edit Staff'));
}
}
$smarty->assign('general_options_list',$general_options_list);
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 'common.css',
		 'container.css',
		 'table.css'
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
		'common.js.php',
		'table_common.js.php',
		);

$sql=sprintf("select * from `Category Dimension` where `Category Parent Key`=%d",$category_key);
$res = mysql_query($sql);
while ($row = mysql_fetch_assoc($res)){
   $subcategory_name[] = $row['Category Name'];
   $subcategory_key[] = $row['Category Key'];
} 
$smarty->assign('subcategory_name', $subcategory_name); 
$smarty->assign('subcategory_key', $subcategory_key); 



$smarty->assign('category',$category);
$smarty->assign('parent','category');
$smarty->assign('sub_parent','category');
//print("*************");print($edit);
if($edit){
$smarty->assign('edit',$_SESSION['state']['product_categories']['edit'] );
$css_files[]='css/edit.css';
$js_files[]='js/edit_common.js';
$js_files[]='edit_category.js.php?category_key='.$category_key;
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('title', _('Editing Category'));
$smarty->assign('editing',true);
$smarty->display('edit_category.tpl');
}
?>
