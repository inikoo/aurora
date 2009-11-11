<?php
include_once('common.php');
include_once('assets_header_functions.php');



if(!$user->can_view('stores')  ){
  header('Location: index.php');
   exit;
}
$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$modify=false;

get_header_info($user,$smarty);
$general_options_list=array();
if($modify)
  $general_options_list[]=array('tipo'=>'url','url'=>'store.php?edit=1','label'=>_('Edit Store'));
//$general_options_list[]=array('tipo'=>'js','state'=>$show_details,'id'=>'details','label'=>($show_details?_('Hide Details'):_('Show Details')));
$smarty->assign('general_options_list',$general_options_list);



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
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'common.js.php',
		'table_common.js.php',
		'search.js',
		'categories.js.php',
		'js/dropdown.js'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$category=$_SESSION['state']['product_categories']['category'];

$sql="select `Category Name`,`Category Key` from `Category Dimension` where `Category Deep`=1 and `Category Subject`='Product' ";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  if(!$category)
    $category=$row['Category Key'];
  $main_category_list[]=array('name'=>$row['Category Name'],'id'=>$row['Category Key'],'selected'=>( $category==$row['Category Key']?1:0));
}
mysql_free_result($res);
$smarty->assign('main_category_list',$main_category_list);
$_SESSION['state']['product_categories']['category']=$category;


$smarty->display('categories.tpl');

?>