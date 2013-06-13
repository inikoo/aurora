<?php
include_once 'common.php';
include_once 'class.Warehouse.php';
include_once 'location_header_functions.php';



if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$warehouse_id=$_REQUEST['id'];

}else {
	$warehouse_id=$_SESSION['state']['warehouse']['id'];
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

/*
$elements_number=array('Blue'=>0,'Green'=>0,'Orange'=>0,'Pink'=>0,'Purple'=>0,'Red'=>0,'Yellow'=>0);
$sql=sprintf("select count(*) as num,`Warehouse Flag` from  `Location Dimension` where `Location Warehouse Key`=%d group by `Warehouse Flag`",$warehouse_id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
$_key=preg_replace('/ /','',$row['Warehouse Flag']);

   if(in_array($_key,array('Blue','Green','Orange','Pink','Purple','Red','Yellow')))
	$elements_number[$_key]=number($row['num']);
}
*/
//print_r($elements_number);
$smarty->assign('elements_data',$elements_data);
$smarty->assign('elements',$_SESSION['state']['warehouse']['locations']['elements']);

$replenishments_number=0;
$sql=sprintf('select count(*) as total from `Part Location Dimension` PL left join `Location Dimension` L on (PL.`Location Key`=L.`Location Key`) left join `Part Dimension` P on (PL.`Part SKU`=P.`Part SKU`) where `Can Pick`="Yes" and `Minimum Quantity` IS NOT NULL and `Minimum Quantity`>=`Quantity On Hand` and P.`Part Current On Hand Stock`>=`Minimum Quantity` and `Part Location Warehouse Key`=%d',$warehouse->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$replenishments_number=number($row['total']);
}


$smarty->assign('replenishments_number',$replenishments_number);

if (isset($_REQUEST['view']) and in_array($_REQUEST['view'],array('areas','locations'))) {
	$_SESSION['state']['warehouse']['view']=$_REQUEST['view'];
}

$smarty->assign('view',$_SESSION['state']['warehouse']['view']);

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

$smarty->assign('filter_value2','');
$smarty->assign('filter_name2','');
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);

$smarty->assign('warehouse',$warehouse);
//print_r($warehouse->get('areas'));

$smarty->assign('paginator_menu0',$paginator_menu);


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

$smarty->assign('flag_list',$flag_list);
$smarty->display('warehouse.tpl');

?>
