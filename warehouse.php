<?php
include_once 'common.php';
include_once 'class.Warehouse.php';
include_once 'location_header_functions.php';



if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$warehouse_id=$_REQUEST['id'];

}else {
	header('Location: index.php?error=no_warehouse_id');
	exit;
}

$warehouse=new warehouse($warehouse_id);
$warehouse->update_location_flags_numbers();

if (!($user->can_view('warehouses') and in_array($warehouse_id,$user->warehouses)   ) ) {
	header('Location: index.php');
	exit;
}
$modify=$user->can_edit('warehouses');
$smarty->assign('modify',$modify);


$smarty->assign('view_parts',$user->can_view('parts'));
get_header_info($user,$smarty);





$smarty->assign('search_label',_('Locations'));
$smarty->assign('search_scope','locations');


$sql=sprintf("select * from  `Warehouse Flag Dimension` where `Warehouse Key`=%d and `Warehouse Flag Active`='Yes' ",$warehouse->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {

	$elements_data[$row['Warehouse Flag Key']]=
		array(
		'number'=>number($row['Warehouse Flag Number Locations']),
		'label'=>$row['Warehouse Flag Label'],
		'color'=>$row['Warehouse Flag Color'],
		'img'=>'flag_'.strtolower($row['Warehouse Flag Color']).'.png',

	);
}

$smarty->assign('elements_data',$elements_data);
$smarty->assign('elements',$_SESSION['state']['warehouse']['locations']['elements']);

$replenishments_number=0;
$sql=sprintf('select count(*) as total from `Part Location Dimension` PL left join `Location Dimension` L on (PL.`Location Key`=L.`Location Key`) left join `Part Dimension` P on (PL.`Part SKU`=P.`Part SKU`) where `Can Pick`="Yes" and `Minimum Quantity` IS NOT NULL and `Minimum Quantity`>=`Quantity On Hand` and P.`Part Current On Hand Stock`>=`Minimum Quantity` and `Part Location Warehouse Key`=%d',$warehouse->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$replenishments_number=number($row['total']);
}


$smarty->assign('replenishments_number',$replenishments_number);


$part_location_number=0;
$sql=sprintf('select count(*) as total from `Part Location Dimension` PL where `Part Location Warehouse Key`=%d',$warehouse->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$part_location_number=number($row['total']);
}


$smarty->assign('part_location_number',$part_location_number);


if (isset($_REQUEST['view']) and in_array($_REQUEST['view'],array('areas','locations'))) {
	$_SESSION['state']['warehouse']['view']=$_REQUEST['view'];
}

$smarty->assign('view',$_SESSION['state']['warehouse']['view']);

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
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
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/search.js',
	'js/export_common.js',
	'warehouse.js.php'
);




$smarty->assign('parent','locations');
$smarty->assign('title', _('Warehouse'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('table_title',_('Location List'));


if ($_SESSION['state']['warehouse']['locations']['order']=='warehouse') {
	$_SESSION['state']['warehouse']['locations']['order']='code';
}

$tipo_filter=$_SESSION['state']['warehouse']['locations']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['warehouse']['locations']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>'Location Code','label'=>'Code'),
);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter=$_SESSION['state']['warehouse']['warehouse_areas']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['warehouse']['warehouse_areas']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>'Area Code','label'=>'Code'),
);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$tipo_filter=$_SESSION['state']['warehouse']['replenishments']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['warehouse']['replenishments']['f_value']);
$filter_menu=array(
	'location'=>array('db_key'=>'location','menu_label'=>'Location Code','label'=>'Location Code'),
	//'reference'=>array('db_key'=>'reference','menu_label'=>'Part Reference','label'=>'Part reference'),
	'sku'=>array('db_key'=>'sku','menu_label'=>'Part SKU','label'=>'SKU'),
	
);
$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);


$tipo_filter=$_SESSION['state']['warehouse']['part_locations']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['warehouse']['part_locations']['f_value']);
$filter_menu=array(
	'location'=>array('db_key'=>'location','menu_label'=>'Location Code','label'=>'Location Code'),
	'reference'=>array('db_key'=>'reference','menu_label'=>'Part Reference','label'=>'Part reference'),
	'sku'=>array('db_key'=>'sku','menu_label'=>'Part SKU','label'=>'SKU'),
	
);
$smarty->assign('filter_menu3',$filter_menu);
$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);

$flag_list=array();
$sql=sprintf("select * from  `Warehouse Flag Dimension` where `Warehouse Key`=%d",
	$warehouse->id);

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$flag_list[strtolower($row['Warehouse Flag Color'])]=array(
		'name'=>$row['Warehouse Flag Label'],
		'color'=>$row['Warehouse Flag Color'],
		'key'=>$row['Warehouse Flag Key'],
		'icon'=>"flag_".strtolower($row['Warehouse Flag Color']).".png"
	);

}

$table_key=11;

$user_maps=array();
$user_map_selected_key=0;
$sql=sprintf("select * from `Table User Export Fields` where `Table Key`=%d",$table_key,$user->id);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
	if($row['Map State']=='Selected')
	$user_map_selected_key=$row['Table User Export Fields Key'];
	$user_maps[$row['Table User Export Fields Key']]=array('key'=>$row['Table User Export Fields Key'],'name'=>$row['Map Name'],'selected'=>($row['Map State']=='Selected'?1:0),'fields'=>preg_split('/,/',$row['Fields']));
}

$export_fields=array();
$sql=sprintf("select `Table Export Fields` from `Table Dimension` where `Table Key`=%d",$table_key);
$res=mysql_query($sql);
if($row=mysql_fetch_assoc($res)){
	$default_fields=preg_split('/,/',$row['Table Export Fields']);
	foreach($default_fields as $default_field){
		list($field,$checked)=preg_split('/\|/',$default_field);
		switch($field){

		case '`Location Code`':
			$field_label=_('Code');
			break;
		case '`Location Mainly Used For':
			$field_label=_('Mainly used for');
			break;
		case '`Location Distinct Parts`':
			$field_label=_('Parts');
			break;	

		default:
			$field_label=$field;
		}
		
		if($user_map_selected_key){
			if(in_array($field,$user_maps[$user_map_selected_key]['fields']))
			$checked=1;
			else
			$checked=0;
		}
		$export_fields[]=array('label'=>$field_label,'name'=>$field,'checked'=>$checked);
	}
}

$smarty->assign('number_export_locations_fields',count($export_fields));
$smarty->assign('export_locations_fields',$export_fields);
$smarty->assign('export_locations_map','Default');
$smarty->assign('export_locations_map_is_default',true);

$table_key=12;

$user_maps=array();
$user_map_selected_key=0;
$sql=sprintf("select * from `Table User Export Fields` where `Table Key`=%d",$table_key,$user->id);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
	if($row['Map State']=='Selected')
	$user_map_selected_key=$row['Table User Export Fields Key'];
	$user_maps[$row['Table User Export Fields Key']]=array('key'=>$row['Table User Export Fields Key'],'name'=>$row['Map Name'],'selected'=>($row['Map State']=='Selected'?1:0),'fields'=>preg_split('/,/',$row['Fields']));
}

$export_fields=array();
$sql=sprintf("select `Table Export Fields` from `Table Dimension` where `Table Key`=%d",$table_key);
$res=mysql_query($sql);
if($row=mysql_fetch_assoc($res)){
	$default_fields=preg_split('/,/',$row['Table Export Fields']);
	foreach($default_fields as $default_field){
		list($field,$checked)=preg_split('/\|/',$default_field);
		switch($field){

		case '`Location Code`':
			$field_label=_('Location');
			break;
		case 'PL.`Part SKU`':
			$field_label=_('SKU');
			break;
		case '`Part Reference`':
			$field_label=_('Part Reference');
			break;	
		case '`Can Pick`':
			$field_label=_('Can Pick');
			break;
		case '`Quantity On Hand`':
			$field_label=_('Stock');
			break;
		case '`Stock Value`':
			$field_label=_('Stock Value');
			break;		
		case '`Last Updated`':
			$field_label=_('Last Updated');
			break;					
		default:
			$field_label=$field;
		}
		
		if($user_map_selected_key){
			if(in_array($field,$user_maps[$user_map_selected_key]['fields']))
			$checked=1;
			else
			$checked=0;
		}
		$export_fields[]=array('label'=>$field_label,'name'=>$field,'checked'=>$checked);
	}
}

$smarty->assign('number_export_part_locations_fields',count($export_fields));
$smarty->assign('export_part_locations_fields',$export_fields);
$smarty->assign('export_part_locations_map','Default');
$smarty->assign('export_part_locations_map_is_default',true);

$smarty->assign('warehouse',$warehouse);
$smarty->assign('flag_list',$flag_list);
$smarty->display('warehouse.tpl');

?>
