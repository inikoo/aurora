<?php
include_once('common.php');
include_once('class.CompanyArea.php');

if(!$user->can_view('staff')){
   header('Location: index.php?no=1');
   exit;
}

if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $company_area_id=$_REQUEST['id'];
}else{
 exit("error no id ");
}


$company_area=new CompanyArea($company_area_id);


if(!$company_area->id){
 //print_r();
 header('Location: index.php');
   exit;
}
$smarty->assign('company_area',$company_area);


$modify=$user->can_edit('staff');

$edit=false;
if(isset($_REQUEST['edit']) and $_REQUEST['edit'])
  $edit=true;


if(!$modify)
 $edit=false;


$general_options_list=array();

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	

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
		'js/common.js',
		'js/table_common.js',
		'js/edit_common.js',
		'edit_company_area.js.php'
		);

$smarty->assign('parent','staff');
$smarty->assign('sub_parent','areas');



$smarty->assign('show_history',$_SESSION['state']['company_area']['show_history'] );


$smarty->assign('edit',$_SESSION['state']['company_area']['edit_block'] );




$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('title', _('Editing Company Area'));

$tipo_filter=$_SESSION['state']['company_area']['history']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['company_area']['history']['f_value']);
$filter_menu=array(
		'notes'=>array('db_key'=>'abstract','menu_label'=>_('Records with abstract *<i>x</i>*'),'label'=>_('Abstract')),
	'author'=>array('db_key'=>'author','menu_label'=>_('Done by <i>x</i>*'),'label'=>_('Notes')),
//	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
//	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)')),

);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu1',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$smarty->display('edit_company_area.tpl');



?>
