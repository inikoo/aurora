<?php
include_once 'common.php';
include_once 'class.Warehouse.php';
if (!$user->can_view('parts') ) {
	header('Location: index.php');
	exit;
}
//$modify=$user->can_edit('staff');
$general_options_list=array();

if (isset($_REQUEST['id']))
	$id=$_REQUEST['id'];
else {
	header('Location: index.php?error=no_id_in_part_list');
	exit;

}


$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$id);

$res=mysql_query($sql);
if (!$part_list_data=mysql_fetch_assoc($res)) {
	header('Location: index.php?error=id_in_part_list_not_found');
	exit;

}


$warehouse=new Warehouse($part_list_data['List Parent Key']);



$part_list_name=$part_list_data['List Name'];
$smarty->assign('part_list_name',$part_list_name);
$smarty->assign('part_list_id',$part_list_data['List Key']);



$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'theme.css.php'
);
$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	'js/common.js',
	'js/export_common.js',

	'js/table_common.js',
	'js/search.js',
	'js/edit_common.js',
	
	'js/parts_common.js',

	'parts_list.js.php'
);

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','parts');
//$smarty->assign('sub_parent','areas');
$smarty->assign('view',$_SESSION['state']['parts_list']['view']);

$smarty->assign('parts_view',$_SESSION['state']['parts_list']['parts']['view']);
$smarty->assign('parts_period',$_SESSION['state']['parts_list']['parts']['period']);
$smarty->assign('parts_avg',$_SESSION['state']['parts_list']['parts']['avg']);

$smarty->assign('title', _('Part List').": ".$part_list_data['List Name']);
$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');



$tipo_filter=$_SESSION['state']['parts_list']['parts']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['parts_list']['parts']['f_value']);
$filter_menu=array(
	'sku'=>array('db_key'=>'sku','menu_label'=>'Part SKU','label'=>'SKU'),
	'reference'=>array('db_key'=>'reference','menu_label'=>'Part Reference','label'=>'Reference'),

	'used_in'=>array('db_key'=>'used_in','menu_label'=>'Used in','label'=>'Used in'),

);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$smarty->assign('elements_use',$_SESSION['state']['parts_list']['parts']['elements']['use']);
$smarty->assign('elements_state',$_SESSION['state']['parts_list']['parts']['elements']['state']);
$smarty->assign('elements_stock_state',$_SESSION['state']['parts_list']['parts']['elements']['stock_state']);
$smarty->assign('elements_part_elements_type',$_SESSION['state']['parts_list']['parts']['elements_type']);


$smarty->assign('warehouse',$warehouse);
$smarty->assign('warehouse_id',$warehouse->id);

include 'parts_export_common.php';



$smarty->display('parts_list.tpl');
?>
