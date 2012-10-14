<?php
include_once 'class.Category.php';
include_once 'class.Warehouse.php';

include_once 'common.php';
include_once 'assets_header_functions.php';



if (!$user->can_view('warehouses')  ) {
	header('Location: index.php');
	exit;
}


$modify=$user->can_edit('warehouses');
if (!$modify) {
	header('Location: part_categories.php');
}



get_header_info($user,$smarty);
$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');
$view=$_SESSION['state']['part_categories']['edit'];
$css_files=array(

	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'common.css',
	'css/container.css',
	'button.css',
	'table.css',
	'theme.css.php'

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
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'js/edit_common.js',
	'js/edit_category_common.js'
);
$smarty->assign('css_files',$css_files);



if (isset($_REQUEST['id'])) {
	$category_key=$_REQUEST['id'];


} else {
	$category_key=$_SESSION['state']['part_categories']['category_key'];
}
$_SESSION['state']['part_categories']['category_key']=$category_key;


if (!$category_key) {
	$category_key=0;
	
	
	
	$view='subcategory';
	$_SESSION['state']['part_categories']['edit']=$view;


	if (isset($_REQUEST['warehouse_id']) and is_numeric($_REQUEST['warehouse_id']) ) {
		$warehouse_id=$_REQUEST['warehouse_id'];

	} else {
		$warehouse_id=$_SESSION['state']['store']['id'];
	}


	$smarty->assign('category_key',false);

	//$general_options_list[]=array('tipo'=>'url','url'=>'part_categories.php?warehouse_id='.$warehouse_id.'&id=0','label'=>_('Exit Edit'));
	//$general_options_list[]=array('tipo'=>'js','id'=>'new_category','label'=>_('Add Category'));



}
else {



	$category=new Category($category_key);
	if (!$category->id) {
		header('Location: part_categories.php?id=0&error=cat_not_found');
		exit;

	}
	$category_key=$category->id;


	if ($modify) {
		$general_options_list[]=array('tipo'=>'url','url'=>'part_categories.php?id='.$category->id,'label'=>_('Exit Edit'));
		$general_options_list[]=array('tipo'=>'js','id'=>'new_category','label'=>_('Add Subcategory'));

	}



	$smarty->assign('category',$category);
	$smarty->assign('category_key',$category->id);

	// $tpl_file='part_category.tpl';
	$warehouse_id=$category->data['Category Warehouse Key'];


}

$warehouse=new Warehouse($warehouse_id);

if (!$warehouse->id) {

	//print_r($category);

	header('Location: index.php?error=warehouse_not_found');
	exit;

}


$smarty->assign('warehouse_id',$warehouse_id);

//$_SESSION['state']['categories']['subject']='Part';

//$_SESSION['state']['categories']['parent_key']=$category_key;
//$_SESSION['state']['categories']['subject_key']=false;
//$_SESSION['state']['categories']['store_key']=$store->id;


$js_files[]='edit_part_category.js.php?key='.$category_key;
$smarty->assign('js_files',$js_files);
$smarty->assign('category_key',$category_key);

$smarty->assign('edit',$view);
$smarty->assign('warehouse',$warehouse);
$smarty->assign('subject','Part');

$smarty->assign('parent','parts');
$smarty->assign('title', _('Edit Part Categories'));






$tipo_filter=$_SESSION['state']['part_categories']['subcategories']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['part_categories']['subcategories']['f_value']);

$filter_menu=array(
	'name'=>array('db_key'=>_('name'),'menu_label'=>_('Category Name'),'label'=>_('Name')),
);


$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);




$smarty->display('edit_part_category.tpl');
?>
