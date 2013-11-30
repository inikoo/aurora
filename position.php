<?php
include_once 'common.php';
include_once 'class.CompanyPosition.php';

if (!$user->can_view('staff')) {
	header('Location: index.php?no=1');
	exit;
}

if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$position_id=$_REQUEST['id'];
}else {
	exit("error no id");
}


$position=new CompanyPosition($position_id);


if (!$position->id) {
	//print_r();
	header('Location: index.php');
	exit;
}
$smarty->assign('position',$position);


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

	$smarty->assign('title', _('Position').' '.$position->data['Company Position Code']);

	$tipo_filter=$_SESSION['state']['position']['employees']['f_field'];

	$smarty->assign('filter0',$tipo_filter);
	$smarty->assign('filter_value0',$_SESSION['state']['position']['employees']['f_value']);

	$smarty->assign('block_view',$_SESSION['state']['position']['block']);

	$filter_menu=array(
		'name'=>array('db_key'=>'staff.alias','menu_label'=>'Staff name <i>*x*</i>','label'=>'Name'),
		'position_id'=>array('db_key'=>'position_id','menu_label'=>'Position Id','label'=>'Position Id'),
		'area_id'=>array('db_key'=>'area_id','menu_label'=>'Area Id','label'=>'Area Id'),
	);
	$smarty->assign('filter_menu0',$filter_menu);

	$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


	$paginator_menu=array(10,25,50,100,500);
	$smarty->assign('paginator_menu0',$paginator_menu);


	$smarty->display('position.tpl');

?>
