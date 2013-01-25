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
		
		if (count($user->warehouses)==0) {
		header('Location: index.php?error_no_warehouse_key');
		exit;
	}else {
		header('Location: warehouse_parts.php?warehouse_id='.$user->warehouses[0]);
		exit;


	}
	
	
}


$warehouse=new warehouse($warehouse_id);
if (!($user->can_view('warehouses') and in_array($warehouse_id,$user->warehouses)   ) ) {
	header('Location: index.php');
	exit;
}
$modify=$user->can_edit('warehouses');

$smarty->assign('modify',$modify);


$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');



$smarty->assign('parts_view',$_SESSION['state']['warehouse']['parts']['view']);
$smarty->assign('parts_period',$_SESSION['state']['warehouse']['parts']['period']);
$smarty->assign('parts_avg',$_SESSION['state']['warehouse']['parts']['avg']);





if(isset($_REQUEST['block_view']) and in_array($_REQUEST['block_view'],array('history','parts','movements'))){
	$block_view=$_REQUEST['block_view'];
	$_SESSION['state']['warehouse']['parts_view']=$block_view;

}else{
$block_view=$_SESSION['state']['warehouse']['parts_view'];
}

$smarty->assign('block_view',$block_view);







$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'common.css',
	'css/container.css',
	'button.css',
	'table.css',
	'theme.css.php'
);
$js_files=array(

	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-debug.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'external_libs/amstock/amstock/swfobject.js',
	'js/table_common.js',
	'js/search.js',
	'js/parts_common.js'
	
);


if($block_view=='parts'){
$js_files[]='warehouse_parts_light.js.php';
}else{
$js_files[]='warehouse_parts.js.php';


}




$smarty->assign('parent','parts');
$smarty->assign('title', _('Inventory (Parts)'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$smarty->assign('show_stock_history_chart',$_SESSION['state']['warehouse']['stock_history']['show_chart']);
$smarty->assign('stock_history_chart_output',$_SESSION['state']['warehouse']['stock_history']['chart_output']);
$smarty->assign('stock_history_type',$_SESSION['state']['warehouse']['stock_history']['type']);

$smarty->assign('to',$_SESSION['state']['warehouse']['stock_history']['to']);
$smarty->assign('from',$_SESSION['state']['warehouse']['stock_history']['from']);
$smarty->assign('to_transactions',$_SESSION['state']['warehouse']['transactions']['to']);
$smarty->assign('from_transactions',$_SESSION['state']['warehouse']['transactions']['from']);





$tipo_filter=$_SESSION['state']['warehouse']['transactions']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['warehouse']['transactions']['f_value']);
$filter_menu=array(
	'note'=>array('db_key'=>'note','menu_label'=>_('Node'),'label'=>_('Note')),
);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['warehouse']['stock_history']['f_field'];
$smarty->assign('filter_show0',$_SESSION['state']['warehouse']['stock_history']['f_show']);
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['warehouse']['stock_history']['f_value']);
$filter_menu=array(
	'location'=>array('db_key'=>'location','menu_label'=>_('Location'),'label'=>_('Location')),
);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$tipo_filter=$_SESSION['state']['warehouse']['parts']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['warehouse']['parts']['f_value']);
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
$smarty->assign('transaction_type',$_SESSION['state']['warehouse']['transactions']['view']);
$smarty->assign('to',$_SESSION['state']['warehouse']['stock_history']['to']);
$smarty->assign('from',$_SESSION['state']['warehouse']['stock_history']['from']);
$smarty->assign('to_transactions',$_SESSION['state']['warehouse']['transactions']['to']);
$smarty->assign('from_transactions',$_SESSION['state']['warehouse']['transactions']['from']);


$elements_number_use=array('InUse'=>0,'NotInUse'=>0);
$sql=sprintf("select count(*) as num ,`Part Status` from  `Part Dimension` P left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`)  where B.`Warehouse Key`=%d group by  `Part Status`   ",
	$warehouse->id);
//print_r($sql);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number_use[preg_replace('/\s/','',$row['Part Status'])]=number($row['num']);
}
$smarty->assign('elements_number_use',$elements_number_use);
$smarty->assign('elements_use',$_SESSION['state']['warehouse']['parts']['elements']['use']);





$elements_number_state=array('Keeping'=>0,'LastStock'=>0,'Discontinued'=>0,'NotKeeping'=>0);
$sql=sprintf("select count(*) as num ,`Part Main State` from  `Part Dimension` P left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`)  where B.`Warehouse Key`=%d group by  `Part Main State`   ",
	$warehouse->id);
//print_r($sql);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number_state[$row['Part Main State']]=$row['num'];
}
$smarty->assign('elements_number_state',$elements_number_use);
$smarty->assign('elements_state',$_SESSION['state']['warehouse']['parts']['elements']['state']);



$elements_number_stock_state=array('Excess'=>0,'Normal'=>0,'Low'=>0,'VeryLow'=>0,'OutofStock'=>0,'Error'=>0);
$sql=sprintf("select count(*) as num ,`Part Stock State` from  `Part Dimension` P left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`)  where B.`Warehouse Key`=%d group by  `Part Stock State`   ",
	$warehouse->id);
//print_r($sql);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number_stock_state[$row['Part Stock State']]=number($row['num']);
}


$smarty->assign('elements_number_stock_state',$elements_number_stock_state);
$smarty->assign('elements_stock_state',$_SESSION['state']['warehouse']['parts']['elements']['stock_state']);



$smarty->assign('elements_part_elements_type',$_SESSION['state']['warehouse']['parts']['elements_type']);



$smarty->display('warehouse_parts.tpl');
?>
