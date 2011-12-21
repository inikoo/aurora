<?php
include_once('common.php');
include_once('class.CompanyArea.php');

if(!$user->can_view('staff')){
   header('Location: index.php?no=1');
   exit;
}

if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $company_area_id=$_REQUEST['id'];
$_SESSION['state']['company_area']['id']=$company_area_id;
}else{
  $company_area_id=$_SESSION['state']['company_area']['id'];
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

if($edit){
  $general_options_list[]=array('tipo'=>'url','url'=>'company_area.php?edit=0','label'=>_('Exit Edit'));

}else{
if($modify){
  $general_options_list[]=array('tipo'=>'url','url'=>'company_area.php?edit=1','label'=>_('Edit Area'));
}
}


$smarty->assign('general_options_list',$general_options_list);





$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 
		 'css/container.css',
		 
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
$smarty->assign('sub_parent','areas');

if(!$edit){

$js_files[]='company_area.js.php';
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('title', _('Company Area'));

$tipo_filter=$_SESSION['state']['hr']['staff']['f_field'];

$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['state']['hr']['staff']['f_value']);

$smarty->assign('view',$_SESSION['state']['hr']['view']);

$filter_menu=array(
		   'name'=>array('db_key'=>'staff.alias','menu_label'=>'Staff name <i>*x*</i>','label'=>'Name'),
		   'position_id'=>array('db_key'=>'position_id','menu_label'=>'Position Id','label'=>'Position Id'),
		   'area_id'=>array('db_key'=>'area_id','menu_label'=>'Area Id','label'=>'Area Id'),
		   );
$smarty->assign('filter_menu0',$filter_menu);

$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->display('company_area.tpl');
}else{
$smarty->assign('edit',$_SESSION['state']['company_area']['edit'] );

$css_files[]='css/edit.css';

$js_files[]='js/edit_common.js';
$js_files[]='edit_company_area.js.php?company_key='.$company_area->data['Company Key'];
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('title', _('Editing Company Area'));
$smarty->assign('editing',true);

$smarty->display('edit_company_area.tpl');


}
?>
