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



$elements_number=array('Blue'=>0,'Green'=>0,'Orange'=>0,'Pink'=>0,'Purple'=>0,'Red'=>0,'Yellow'=>0);
$sql=sprintf("select count(*) as num,`Location Flag` from  `Location Dimension` where `Location Warehouse Key`=%d group by `Location Flag`",$warehouse_id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
$_key=preg_replace('/ /','',$row['Location Flag']);

   if(in_array($_key,array('Blue','Green','Orange','Pink','Purple','Red','Yellow')))
	$elements_number[$_key]=$row['num'];
}

//print_r($elements_number);
$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['warehouse']['locations']['elements']);






//$smarty->assign('general_options_list',$general_options_list);

if(isset($_REQUEST['view']) and in_array($_REQUEST['view'],array('areas','locations'))){
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
	'js/dropdown.js',
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



$smarty->assign('warehouse',$warehouse);
//print_r($warehouse->get('areas'));

$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->display('warehouse.tpl');
?>
