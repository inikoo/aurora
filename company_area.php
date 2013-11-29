<?php
include_once 'common.php';
include_once 'class.CompanyArea.php';

if (!$user->can_view('staff')) {
	header('Location: index.php?no=1');
	exit;
}

if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$company_area_id=$_REQUEST['id'];
}else {
	exit("error no id");
}


$company_area=new CompanyArea($company_area_id);


if (!$company_area->id) {
	//print_r();
	header('Location: index.php');
	exit;
}
$smarty->assign('company_area',$company_area);


$modify=$user->can_edit('staff');
$smarty->assign('modify',$modify);

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
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
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	'js/common.js',
	'js/table_common.js',
	'company_area.js.php'
);

$smarty->assign('parent','staff');
$smarty->assign('sub_parent','areas');



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('title', _('Company Area'));

$tipo_filter=$_SESSION['state']['company_area']['employees']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['company_area']['employees']['f_value']);
$filter_menu=array(
	'name'=>array('db_key'=>'staff.alias','menu_label'=>'Employee name <i>*x*</i>','label'=>'Name'),
	'position_id'=>array('db_key'=>'position_id','menu_label'=>'Position Id','label'=>'Position Id'),
	'area_id'=>array('db_key'=>'area_id','menu_label'=>'Area Id','label'=>'Area Id'),
);
$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);



$tipo_filter=$_SESSION['state']['company_area']['positions']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['company_area']['positions']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'staff.alias','menu_label'=>'Staff name <i>*x*</i>','label'=>'Name'),
                 'position_id'=>array('db_key'=>'position_id','menu_label'=>'Position Id','label'=>'Position Id'),
                 'area_id'=>array('db_key'=>'area_id','menu_label'=>'Area Id','label'=>'Area Id'),
             );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['company_area']['departments']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['company_area']['departments']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'staff.alias','menu_label'=>'Staff name <i>*x*</i>','label'=>'Name'),
                 'position_id'=>array('db_key'=>'position_id','menu_label'=>'Position Id','label'=>'Position Id'),
                 'area_id'=>array('db_key'=>'area_id','menu_label'=>'Area Id','label'=>'Area Id'),
             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->assign('block_view',$_SESSION['state']['company_area']['block']);




$smarty->display('company_area.tpl');

?>
