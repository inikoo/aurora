<?php
include_once 'class.Category.php';
include_once 'class.Store.php';

include_once 'common.php';
include_once 'assets_header_functions.php';



if (!$user->can_view('stores')  ) {
	header('Location: index.php');
	exit;
}




$modify=$user->can_edit('stores');
if (!$modify) {
	header('Location: customer_categories.php');
}

get_header_info($user,$smarty);
$general_options_list=array();

$view=$_SESSION['state']['categories']['edit'];
$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'assets/skins/sam/autocomplete.css',

	'common.css',
	'css/container.css',
	'button.css',
	'table.css',
	'css/edit.css'

);
$css_files[]='theme.css.php';
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
	'js/edit_common.js',
	'js/search.js',
	'js/edit_category_common.js'
);
$smarty->assign('css_files',$css_files);

$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');


if (isset($_REQUEST['id'])) {
	$category_key=$_REQUEST['id'];


} else {
	$category_key=$_SESSION['state']['customer_categories']['category_key'];
}
$_SESSION['state']['customer_categories']['category_key']=$category_key;



$category_key=0;
$view='subcategory';
$_SESSION['state']['categories']['edit']=$view;


if (isset($_REQUEST['store_id']) and is_numeric($_REQUEST['store_id']) ) {
	$store_id=$_REQUEST['store_id'];

} else {
	header('Location: index.php?error=store_not_found');
	exit;
}






$store=new Store($store_id);

if (!$store->id) {
	header('Location: index.php?error=store_not_found');
	exit;

}

$_SESSION['state']['categories']['subject']='Customer';

$_SESSION['state']['categories']['parent_key']=$category_key;
$_SESSION['state']['categories']['subject_key']=false;
$_SESSION['state']['categories']['store_key']=$store->id;


$js_files[]='edit_customer_categories.js.php';
$smarty->assign('js_files',$js_files);

$smarty->assign('edit',$view);
$_SESSION['state']['store']['id']=$store->id;
$smarty->assign('store',$store);
$smarty->assign('subject','Customer');
$smarty->assign('title',_('Edit Category'));
$smarty->assign('parent','customers');
$smarty->assign('store_id',$store->id);
$smarty->assign('category_key',0);


$tipo_filter=$_SESSION['state']['categories']['history']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['categories']['history']['f_value']);
$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
	'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
	'uptu'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)')),
	'abstract'=>array('db_key'=>'abstract','menu_label'=>'Records with abstract','label'=>_('Abstract'))

);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu1',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$smarty->display('edit_customer_categories.tpl');
?>
