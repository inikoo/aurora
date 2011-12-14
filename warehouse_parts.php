<?php
include_once('common.php');
include_once('class.Warehouse.php');
include_once('location_header_functions.php');



if(isset($_REQUEST['warehouse_id']) and is_numeric($_REQUEST['warehouse_id']) ){
  $warehouse_id=$_REQUEST['warehouse_id'];

}else{
  header('Location: index.php?error_no_warehouse_key');
   exit;
}


$warehouse=new warehouse($warehouse_id);
if(!($user->can_view('warehouses') and in_array($warehouse_id,$user->warehouses)   ) ){
  header('Location: index.php');
   exit;
}
$modify=$user->can_edit('warehouses');

$smarty->assign('modify',$modify);


$smarty->assign('view_parts',$user->can_view('parts'));
//get_header_info($user,$smarty);







$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');


//$smarty->assign('general_options_list',$general_options_list);





$smarty->assign('view',$_SESSION['state']['warehouse']['parts_view']);
$smarty->assign('parts_view',$_SESSION['state']['warehouse']['parts']['view']);
$smarty->assign('parts_period',$_SESSION['state']['warehouse']['parts']['period']);
$smarty->assign('parts_avg',$_SESSION['state']['warehouse']['parts']['avg']);

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'container.css',
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
		'warehouse_parts.js.php'
		);




$smarty->assign('parent','parts');
$smarty->assign('title', _('Inventory (Parts)'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


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


$elements_number=array('InUse'=>0,'NotInUse'=>0);
$sql=sprintf("select count(*) as num , `Part Status` from  `Part Dimension` P  left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`) where `Warehouse Key`=%d group by `Part Status`",$warehouse->id);

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $elements_number[preg_replace('/\s/','',$row['Part Status'])]=$row['num'];
}


$smarty->assign('elements_number',$elements_number);

$_elements=array();
foreach($_SESSION['state']['warehouse']['parts']['elements'] as $key=>$value){
    $_elements[preg_replace('/\s/','',$key)]=$value;
}

$smarty->assign('elements',$_elements);

$smarty->assign('warehouse',$warehouse);
$smarty->assign('warehouse_id',$warehouse->id);


$elements_number=array('Keeping'=>0,'LastStock'=>0,'Discontinued'=>0,'NotKeeping'=>0);
$sql=sprintf("select count(*) as num ,`Part Main State` from  `Part Dimension` P left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`)  where B.`Warehouse Key`=%d group by  `Part Main State`   ",
$warehouse->id);

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $elements_number[$row['Part Main State']]=$row['num'];
}


$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['warehouse']['parts']['elements']);


$smarty->display('warehouse_parts.tpl');
?>
