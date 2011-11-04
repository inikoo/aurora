<?php
include_once('common.php');
include_once('class.CompanyPosition.php');

if(!$user->can_view('staff')){
   header('Location: index.php?no=1');
   exit;
}

if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $company_position_id=$_REQUEST['id'];
$_SESSION['state']['company_position']['id']=$company_position_id;
}else{
  $company_position_id=$_SESSION['state']['company_position']['id'];
}


$company_position=new CompanyPosition($company_position_id);
if(!$company_position->id){
 //print_r();
 header('Location: index.php');
   exit;
}
$smarty->assign('company_position',$company_position);


$modify=$user->can_edit('staff');

$edit=false;
if(isset($_REQUEST['edit']) and $_REQUEST['edit'])
  $edit=true;


if(!$modify)
 $edit=false;


$general_options_list=array();

if($edit){
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_position.php?edit=0','label'=>_('Exit Edit'));

}else{
if($modify){
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_position?edit=1','label'=>_('Edit Position'));
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
$smarty->assign('sub_parent','position');

if($edit){
$smarty->assign('edit',$_SESSION['state']['edit_each_position']['edit'] );
$css_files[]='css/edit.css';
$js_files[]='js/edit_common.js';
$js_files[]='edit_position.js.php?position_key='.$company_position->data['Company Position Key'];
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('title', _('Editing Position'));
$smarty->assign('editing',true);
$smarty->display('edit_position.tpl');
}
?>
