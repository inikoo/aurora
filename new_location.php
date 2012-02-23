<?php
include_once 'common.php';
include_once 'class.Warehouse.php';
include_once 'class.WarehouseArea.php';


if (isset($_REQUEST['auto']))
	$auto=1;
else
	$auto=0;



$smarty->assign('box_layout','yui-t0');
$warehouse=new warehouse($_SESSION['state']['warehouse']['id']);

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



$smarty->assign('search_label',_('Locations'));
$smarty->assign('search_scope','locations');
$js_files=array(

	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/raphael.js',
	'new_location.js.php?auto='.$auto.'&warehouse_key='.$warehouse->id,
	'js/search.js'

);
$warehouse_area='';
$warehouse_area_id='';
if (isset($_REQUEST['warehouse_area_id'])) {
	$warehouse_area=new WarehouseArea($_REQUEST['warehouse_area_id']);
	//print_r($warehouse_area->data['Warehouse Area Name']);
	$warehouse_area=$warehouse_area->data['Warehouse Area Name'];
	$warehouse_area_id=$warehouse_area->id;
}
	$smarty->assign('warehouse_area_id',$warehouse_area_id);

$smarty->assign('warehouse_area_name',$warehouse_area);

if (isset($_REQUEST['window'])) {
	$smarty->assign('window',$_REQUEST['window']);
}

$smarty->assign('window',$_REQUEST['window']);
$smarty->assign('parent','warehouses');
$smarty->assign('title', _('New Location'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$tipo_filter2='code';
$filter_menu2=array(
	'code'=>array('db_key'=>_('code'),'menu_label'=>_('Code'),'label'=>_('Code')),
	'name'=>array('db_key'=>_('name'),'menu_label'=>_('Name'),'label'=>_('Name')),
);
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2','');


$used_for='Picking';
$used_for_list=array(
	'Picking'=>array('selected'=>true,'name'=>_('Picking'))
	,'Storing'=>array('selected'=>false,'name'=>_('Storing'))
	,'Displaying'=>array('selected'=>false,'name'=>_('Displaying'))
	,'Loading'=>array('selected'=>false,'name'=>_('Loading'))
);
$shape_type='Box';
$shape_type_list=array(
	'Box'=>array('selected'=>true,'name'=>_('Box'))
	,'Cylinder'=>array('selected'=>false,'name'=>_('Cylinder'))

);


$smarty->assign('warehouse',$warehouse);
$smarty->assign('warehouse_id',$warehouse->id);

$smarty->assign('used_for',$used_for);
$smarty->assign('shape_type',$shape_type);
$smarty->assign('used_for_list',$used_for_list);
$smarty->assign('shape_type_list',$shape_type_list);

$smarty->display('new_location.tpl');
?>
