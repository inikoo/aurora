<?php
include_once('common.php');
include_once('class.Warehouse.php');
if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $warehouse_id=$_REQUEST['id'];

}else{
  $warehouse_id=$_SESSION['state']['warehouse']['id'];
}
$warehouse=new warehouse($warehouse_id);
if(!($user->can_view('warehouses') and in_array($warehouse_id,$user->warehouses)   ) ){
  header('Location: index.php');
   exit;
}
$modify=$user->can_edit('warehouses');
if(!$modify ){
  header('Location: warehouse.php');
   exit;
}
$edit=true;
$warehouse=new warehouse($warehouse_id);


$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'warehouse.php','label'=>_('Exit Edit'));
$general_options_list[]=array('tipo'=>'url','url'=>'new_warehouse_area.php?warehouse_id='.$warehouse_id,'label'=>_('Add Area'));
$general_options_list[]=array('tipo'=>'url','url'=>'new_location.php?warehouse_id='.$warehouse_id,'label'=>_('Add Location'));


$smarty->assign('general_options_list',$general_options_list);

$smarty->assign('edit',$_SESSION['state']['warehouse']['edit']);



$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css',
		 'css/edit.css'
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
		'js/edit_common.js',
		
		'edit_warehouse.js.php'
		);


 
 
$smarty->assign('parent','warehouse.php');
$smarty->assign('title', _('Warehouse'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('table_title',_('Location List'));





$tipo_filter=$_SESSION['state']['warehouse']['locations']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['state']['warehouse']['locations']['f_value']);

$filter_menu=array(
		   'code'=>array('db_key'=>_('code'),'menu_label'=>'Location Code','label'=>'Code'),
		   );
$smarty->assign('filter_menu',$filter_menu);
$smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu',$paginator_menu);



$smarty->assign('warehouse',$warehouse);
//print_r($warehouse->get('areas'));

$smarty->assign('paginator_menu',$paginator_menu);


$smarty->display('edit_warehouse.tpl');
?>