<?php
include_once('common.php');
include_once('class.CompanyDepartment.php');

if(!$user->can_view('staff')){
   header('Location: index.php?no=1');
   exit;
}

if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $company_department_id=$_REQUEST['id'];
$_SESSION['state']['company_department']['id']=$company_department_id;
}else{
  $company_department_id=$_SESSION['state']['company_department']['id'];
}
//print($company_department_id);

$company_department=new CompanyDepartment($company_department_id);
if(!$company_department->id){
 //print_r();
 header('Location: index.php');
   exit;
}
$smarty->assign('company_department',$company_department);


$modify=$user->can_edit('staff');

$edit=false;
if(isset($_REQUEST['edit']) and $_REQUEST['edit'])
  $edit=true;


if(!$modify)
 $edit=false;


$general_options_list=array();

if($edit){
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_company_department.php?edit=0','label'=>_('Exit Edit'));

}else{
if($modify){
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_company_department?edit=1','label'=>_('Edit Staff'));
}
}
$smarty->assign('general_options_list',$general_options_list);
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 
		 'container.css'
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
		);

$smarty->assign('parent','staff');
$smarty->assign('sub_parent','department');

if($edit){

$smarty->assign('edit',$_SESSION['state']['edit_each_department']['edit'] );
$css_files[]='css/edit.css';
$js_files[]='js/edit_common.js';
$js_files[]='edit_company_department.js.php?department_key='.$company_department->data['Company Department Key'];

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('title', _('Editing Department'));
$smarty->assign('editing',true);
$smarty->display('edit_company_department.tpl');
}
?>
