<?php
include_once('common.php');
if(!$user->can_view('staff')){
   header('Location: index.php');
   exit;
}

$modify=$user->can_edit('staff');

$general_options_list=array();


if($modify){
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_positions.php','label'=>_('Edit Positions'));
   $general_options_list[]=array('tipo'=>'url','url'=>'edit_positions.php?new=1','label'=>_('Add Position'));
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
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'common.js.php',
		'table_common.js.php',
		'positions.js.php'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','hr.php');
$smarty->assign('sub_parent','positions');

$smarty->assign('title', _('Staff'));

$tipo_filter=$_SESSION['state']['hr']['staff']['f_field'];

$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['state']['hr']['staff']['f_value']);

$smarty->assign('view',$_SESSION['state']['hr']['view']);

$filter_menu=array(
		   'name'=>array('db_key'=>'staff.alias','menu_label'=>'Staff name <i>*x*</i>','label'=>'Name'),
		   'position_id'=>array('db_key'=>'position_id','menu_label'=>'Position Id','label'=>'Position Id'),
		   'area_id'=>array('db_key'=>'area_id','menu_label'=>'Area Id','label'=>'Area Id'),
		   );
$smarty->assign('filter_menu',$filter_menu);

$smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu',$paginator_menu);

$smarty->display('positions.tpl');
?>