<?php
/*
 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2012, Inikoo 
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Warehouse.php');
include_once('class.WarehouseArea.php');

if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $warehouse_area_key=$_REQUEST['id'];

}else{
  $warehouse_area_key=$_SESSION['state']['warehouse']['id'];
}
$warehouse_area=new WarehouseArea($warehouse_area_key);

$warehouse=new warehouse($warehouse_area->data['Warehouse Key']);

if(!($user->can_view('warehouses') and in_array($warehouse->id,$user->warehouses)   ) ){
  header('Location: index.php');
   exit;
}
$modify=$user->can_edit('warehouses');
if(!$modify ){
  header('Location: warehouse.php');
   exit;
}
$edit=true;


$smarty->assign('search_label',_('Locations'));
$smarty->assign('search_scope','locations');





$smarty->assign('edit',$_SESSION['state']['warehouse_area']['view']);


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
               'css/edit',
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
		'edit_warehouse_area.js.php?id='.$_REQUEST['id'],
		'js/search.js'
		);

 
$smarty->assign('parent','warehouses');
$smarty->assign('title', _('Warehouse'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);






$tipo_filter=$_SESSION['state']['locations']['table']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['locations']['table']['f_value']);

$filter_menu=array(
		   'code'=>array('db_key'=>_('code'),'menu_label'=>'Location Code','label'=>'Code'),
		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);




$smarty->assign('warehouse_area',$warehouse_area);

$smarty->assign('warehouse',$warehouse);
//print_r($warehouse->get('areas'));





$smarty->display('edit_warehouse_area.tpl');
?>
