<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/

include_once 'common.php';
include_once 'class.Warehouse.php';




if (isset($_REQUEST['warehouse_id']) and is_numeric($_REQUEST['warehouse_id']) ) {
	$warehouse_id=$_REQUEST['warehouse_id'];

}else {
	header('Location: index.php?msg=error_no_warehouse_key');
	exit;
}


$warehouse=new warehouse($warehouse_id);
if (!$warehouse->id) {
	header('Location: index.php?msg=error_warehouse_not_found');
	exit;
}


if (!($user->can_view('warehouses') and in_array($warehouse_id,$user->warehouses)   ) ) {
	header('Location: index.php');
	exit;
}

if (isset($_REQUEST['date'])  ) {
	$date=strtotime($_REQUEST['date']);

}else {
	header('Location: inventory.php?warehouse_id='.$warehouse->id.'&block_view=history&msg=wrong_date');
	exit;
}

$date_mysql_format=date("Y-m-d",$date);


$modify=$user->can_edit('warehouses');

$smarty->assign('modify',$modify);


$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');
$smarty->assign('formated_date',strftime("%a %x",$date));
$smarty->assign('date',date("Y-m-d",$date));



$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/edit.css',
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
	'external_libs/amstock/amstock/swfobject.js',
	'js/table_common.js',
	'js/search.js',
	'js/parts_common.js',
	'js/export_common.js',
	'stock_history_parts.js.php'

);


if (isset($_REQUEST['block_view']) and in_array($_REQUEST['block_view'],array('overview','parts','movements'))) {
	$block_view=$_REQUEST['block_view'];
	$_SESSION['state']['stock_history']['block_view']=$block_view;

}else {
	$block_view=$_SESSION['state']['stock_history']['block_view'];
}

$smarty->assign('block_view',$block_view);




$smarty->assign('parent','parts');
$smarty->assign('title', _('Inventory (Parts)'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$prev=array(
	'title'=>_('Historic Inventory').' '.strftime("%a %x",strtotime("$date_mysql_format -1 day")),
	'link'=>sprintf('stock_history_parts.php?warehouse_id=%d&date=%s',$warehouse->id,date("Y-m-d",strtotime("$date_mysql_format -1 day")))
);
$smarty->assign('prev',$prev);

if (date('U',$date)<date('U') ) {
	$next=array(
		'title'=>_('Historic Inventory').' '.strftime("%a %x",strtotime("$date_mysql_format +1 day")),
		'link'=>sprintf('stock_history_parts.php?warehouse_id=%d&date=%s',$warehouse->id,date("Y-m-d",strtotime("$date_mysql_format +1 day")))
	);
	$smarty->assign('next',$next);
}





$tipo_filter=$_SESSION['state']['stock_history']['transactions']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['stock_history']['transactions']['f_value']);
$filter_menu=array(
	'note'=>array('db_key'=>'note','menu_label'=>_('Node'),'label'=>_('Note')),
);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);




$tipo_filter=$_SESSION['state']['stock_history']['parts']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['stock_history']['parts']['f_value']);
$filter_menu=array(
	'sku'=>array('db_key'=>_('code'),'menu_label'=>'Part SKU','label'=>'SKU'),
	'used_in'=>array('db_key'=>_('used_in'),'menu_label'=>'Used in','label'=>'Used in'),

);
$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);



$smarty->assign('warehouse',$warehouse);
$smarty->assign('warehouse_id',$warehouse->id);
$smarty->assign('transaction_type',$_SESSION['state']['stock_history']['transactions']['view']);




$parts=0;
$locations=0;
$cost_value=money(0);
$cost_value_ed=money(0);
$commercial_value=money(0);

$sql=sprintf("select * from `Inventory Warehouse Spanshot Fact` where `Warehouse Key`=%d and `Date`=%s",
$warehouse->id,
prepare_mysql($date_mysql_format)
);
$res=mysql_query($sql);
if($row=mysql_fetch_assoc($res)){
$parts=number($row['Parts']);
$locations=number($row['Locations']);
$cost_value=money($row['Value At Cost']);
$cost_value_ed=money($row['Value At Day Cost']);
$commercial_value=money($row['Value Commercial']);

}

$smarty->assign('parts',$parts);
$smarty->assign('locations',$locations);
$smarty->assign('cost_value',$cost_value);
$smarty->assign('cost_value_ed',$cost_value_ed);
$smarty->assign('commercial_value',$commercial_value);



$table_key=10;

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

		case '`Date`':
			$field_label=_('Date');
			break;
		case '`Part SKU`':
			$field_label=_('SKU');
			break;
		case '`Locations`':
			$field_label=_('Locations');
			break;	
		case '`Stock`':
			$field_label=_('Stock');
			break;	
		case '`Customer Address`':
			$field_label=_('Contact Address');
			break;	
		case 'Customer Address Elements':
			$field_label=_('Contact Address').' ('._('Elements').')';
			break;	
		case '`Customer Billing Address`':
			$field_label=_('Billing Address');
			break;	
		case 'Customer Billing Address Elements':
			$field_label=_('Billing Address').' ('._('Elements').')';
			break;	
		case '`Customer Delivery Address`':
			$field_label=_('Delivery Address');
			break;	
		case 'Customer Delivery Address Elements':
			$field_label=_('Delivery Address').' ('._('Elements').')';
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

$smarty->assign('number_export_part_stock_historic_fields',count($export_fields));
$smarty->assign('export_part_stock_historic_fields',$export_fields);

$smarty->assign('export_part_stock_historic_map','Default');
$smarty->assign('export_part_stock_historic_map_is_default',true);



$smarty->display('stock_history_parts.tpl');
?>
