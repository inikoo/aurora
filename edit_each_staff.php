<?php
include_once('common.php');
include_once('class.Staff.php');

if(!$user->can_view('staff')){
   header('Location: index.php?no=1');
   exit;
}

if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $company_staff_id=$_REQUEST['id'];
$_SESSION['state']['company_staff']['id']=$company_staff_id;
}else{
  $company_staff_id=$_SESSION['state']['company_staff']['id'];
}


$company_staff=new Staff($company_staff_id);
if(!$company_staff->id){
 //print_r();
 header('Location: index.php');
   exit;
}
$smarty->assign('company_staff',$company_staff);


$modify=$user->can_edit('staff');

$edit=false;
if(isset($_REQUEST['edit']) and $_REQUEST['edit'])
  $edit=true;


if(!$modify)
 $edit=false;


$general_options_list=array();

if($edit){
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_each_staff.php?edit=0','label'=>_('Exit Edit'));

}else{
if($modify){
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_each_staff?edit=1','label'=>_('Edit Staff'));
}
}
$smarty->assign('general_options_list',$general_options_list);
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 'common.css',
		 'css/container.css',
		 'table.css'
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
		);

$smarty->assign('parent','staff');
$smarty->assign('sub_parent','staff');

if($edit){
$smarty->assign('edit',$_SESSION['state']['edit_each_staff']['edit'] );
$css_files[]='css/edit.css';
$js_files[]='js/edit_common.js';
$js_files[]='edit_each_staff.js.php?staff_key='.$company_staff->data['Staff Key'];
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('title', _('Editing Staff'));
$smarty->assign('editing',true);
$smarty->display('edit_each_staff.tpl');
}
?>
