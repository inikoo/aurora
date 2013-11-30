<?php
include_once('common.php');
include_once('class.Staff.php');

if(!$user->can_view('staff')){
   header('Location: index.php?no=1');
   exit;
}

if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $employee_key=$_REQUEST['id'];
}else{

 header('Location: index.php?no_employee_key');
   exit;
}


$employee=new Staff($employee_key);
if(!$employee->id){
 //print_r();
 header('Location: index.php');
   exit;
}
$smarty->assign('employee',$employee);




if(!$user->can_edit('staff')){

 header('Location: employee.php?id='.$employee->id);
   exit;
}



$smarty->assign('general_options_list',$general_options_list);
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 'css/common.css',
		 'css/container.css',
		 'css/table.css',
		 'css/edit.css'
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
		'edit_employee.js'
		);

$smarty->assign('parent','staff');
$smarty->assign('sub_parent','staff');


$smarty->assign('block',$_SESSION['state']['employee']['edi_block'] );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('title', _('Editing Employee'));

$smarty->display('edit_employee.tpl');

?>
