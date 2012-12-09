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
$smarty->assign('search_scope','parts');

$css_files=array(

	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'common.css',
	'css/container.css',
	'button.css',
	'table.css',
	'css/edit.css',
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
//	'js/edit_category_common.js',
	'edit_part_categories.js.php'
);
$smarty->assign('css_files',$css_files);

$create_subcategory=true;

$view='subcategory';
$_SESSION['state']['part_categories']['edit']=$view;


if (isset($_REQUEST['warehouse_id']) and is_numeric($_REQUEST['warehouse_id']) ) {
	$warehouse_id=$_REQUEST['warehouse_id'];

} else {
	if (count($user->warehouses)==0) {
		header('Location: index.php?error_no_warehouse_key');
		exit;
	}else {
		header('Location: edit_part_categories.php?warehouse_id='.$user->warehouses[0]);
		exit;
	}
}


$warehouse=new Warehouse($warehouse_id);

if (!$warehouse->id) {

	header('Location: index.php?error=warehouse_not_found');
	exit;

}



$smarty->assign('show_history',$_SESSION['state']['part_categories']['show_history']);


$smarty->assign('warehouse_id',$warehouse_id);
$smarty->assign('js_files',$js_files);
$smarty->assign('create_subcategory',$create_subcategory);



$smarty->assign('edit',$view);
$smarty->assign('warehouse',$warehouse);
$smarty->assign('subject','Part');

$smarty->assign('parent','parts');
$smarty->assign('title', _('Edit Part Categories'));






$tipo_filter=$_SESSION['state']['part_categories']['edit_categories']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['part_categories']['edit_categories']['f_value']);

$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Category Code'),'label'=>_('Code')),
		'label'=>array('db_key'=>'label','menu_label'=>_('Category Label'),'label'=>_('Label')),

);


$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$tipo_filter=$_SESSION['state']['part_categories']['history']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['part_categories']['history']['f_value']);
$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
	'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
	'uptu'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)')),

);

$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu1',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$elements_number=array('Root'=>0,'Node'=>0,'Head'=>0);
$sql=sprintf("select count(*) as num ,`Category Branch Type` from  `Category Dimension` where  `Category Subject`='Part' group by  `Category Branch Type`   ");
//print_r($sql);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Category Branch Type']]=number($row['num']);
}


$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['part_categories']['edit_categories']['elements']);


$elements_number=array('Change'=>0,'Assign'=>0);
$sql=sprintf("select count(*) as num ,`Type` from  `Part Category History Bridge` where  `Warehouse Key`=%d group by  `Type`",$warehouse->id);
//print_r($sql);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=number($row['num']);
}


$smarty->assign('history_elements_number',$elements_number);
$smarty->assign('history_elements',$_SESSION['state']['part_categories']['history']['elements']);



$smarty->display('edit_part_categories.tpl');
?>
