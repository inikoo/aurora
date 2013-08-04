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

$smarty->assign('search_label',_('Locations'));
$smarty->assign('search_scope','locations');

$smarty->assign('edit',$_SESSION['state']['warehouse']['edit']);
$smarty->assign('shelf_type_view',$_SESSION['state']['shelf_types']['view']);

$units_tipo=array(
		  'Pallet'=>array('fname'=>_('Pallet Rack'))
		  ,'Shelf'=>array('fname'=>_('Shelf'))
		  ,'Drawer'=>array('fname'=>_('Drawer'))
		  ,'Other'=>array('fname'=>_('Other'),'selected'=>true)
		  );
$smarty->assign('shelf_default_type','Other');

$smarty->assign('shelf_types',$units_tipo);


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
		'js/edit_common.js',
		'edit_warehouse_shelf.js.php',
		'edit_warehouse.js.php?id='.$warehouse->id,
		'js/search.js'
		);

 
$smarty->assign('parent','locations');
$smarty->assign('title', _('Editing Warehouse'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('table_title',_('Location List'));





$tipo_filter=$_SESSION['state']['warehouse']['edit_locations']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['warehouse']['edit_locations']['f_value']);

$filter_menu=array(
		   'code'=>array('db_key'=>_('code'),'menu_label'=>'Location Code','label'=>'Code'),
		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter=$_SESSION['state']['warehouse_areas']['table']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['warehouse_areas']['table']['f_value']);
$filter_menu=array(
		   'code'=>array('db_key'=>_('code'),'menu_label'=>'Area Code','label'=>'Code'),
		   );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);





$smarty->assign('warehouse',$warehouse);
$smarty->assign('warehouse_id',$warehouse->id);

//print_r($warehouse->get('areas'));

$smarty->assign('paginator_menu0',$paginator_menu);

$flags=array();
$sql=sprintf("select `Warehouse Flag Number Locations` as locations ,`Warehouse Flag Key` as id ,`Warehouse Flag Color` as color, `Warehouse Flag Label`as  label ,`Warehouse Flag Active` as display from `Warehouse Flag Dimension` where `Warehouse Key`=%d ",$warehouse->id);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
$row['icon']='flag_'.strtolower($row['color']).'.png';
$row['locations']=number($row['locations']);
$row['default']=($warehouse->data['Warehouse Default Flag Color']==$row['color']?1:0);
	$flags[]=$row;
}

$smarty->assign('flags',$flags);

$smarty->display('edit_warehouse.tpl');
?>
