<?php
include_once 'common.php';
include_once 'class.Store.php';
include_once 'class.CompanyArea.php';
include_once 'class.Warehouse.php';



if (!  ($user->can_view('parts') or $user->data['User Type']=='Warehouse'   ) ) {
	header('Location: index.php?cannot_view');
	exit;
}



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



$smarty->assign('warehouse',$warehouse);
$smarty->assign('warehouse_id',$warehouse->id);



$smarty->assign('search_parent_key',$warehouse->id);
$smarty->assign('search_parent','warehouse');
$smarty->assign('search_label',_('Deliveries'));
$smarty->assign('search_scope','orders_warehouse');


$smarty->assign('view','warehouse_orders');
$smarty->assign('store_id',false);


$css_files=array(
$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/edit.css',
	'theme.css.php'
);

$js_files=array(

	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/search.js',
	'warehouse_orders.js.php',
	'js/edit_common.js',
	'js/common_assign_picker_packer.js',
//	'js/csv_common.js'
);



if($user->get('User Type')=='Warehouse')
$smarty->assign('parent','orders');

else
$smarty->assign('parent','parts');
$smarty->assign('title', _('Warehouse Orders'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$company_area=new CompanyArea('code','WAH');
$pickers=$company_area->get_current_staff_with_position_code('PICK');
$number_cols=5;
$row=0;
$pickers_data=array();
$contador=0;
foreach ($pickers as $picker) {
	if (fmod($contador,$number_cols)==0 and $contador>0)
		$row++;
	$tmp=array();
	foreach ($picker as $key=>$value) {
		$tmp[preg_replace('/\s/','',$key)]=$value;
	}
	$pickers_data[$row][]=$tmp;
	$contador++;
}

$smarty->assign('pickers',$pickers_data);
$smarty->assign('number_pickers',count($pickers_data));

$packers=$company_area->get_current_staff_with_position_code('PACK');
$number_cols=5;
$row=0;
$packers_data=array();
$contador=0;
foreach ($packers as $packer) {
	if (fmod($contador,$number_cols)==0 and $contador>0)
		$row++;
	$tmp=array();
	foreach ($packer as $key=>$value) {
		$tmp[preg_replace('/\s/','',$key)]=$value;
	}
	$packers_data[$row][]=$tmp;
	$contador++;
}

$smarty->assign('packers',$packers_data);
$smarty->assign('number_packers',count($packers_data));

$tipo_filter2='code';
$filter_menu2=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Code'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Name'),'label'=>_('Name')),
);
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2','');

$staff_list_active=array();
$sql=sprintf("select * from `Staff Dimension` where `Staff Currently Working`='Yes'");
$result=mysql_query($sql);
while ($row=mysql_fetch_assoc($result)) {
	$staff_list_active[$row['Staff Key']]=$row['Staff Alias'];
}
$smarty->assign('staff_list_active',$staff_list_active);


//print_r($pickers_data);

$tipo_filter2=$_SESSION['state']['orders']['warehouse_orders']['f_field'];
$smarty->assign('filter0',$tipo_filter2);
$smarty->assign('filter_value0',($_SESSION['state']['orders']['warehouse_orders']['f_value']));
$filter_menu2=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'Order Number'),
);
$smarty->assign('filter_menu0',$filter_menu2);
$smarty->assign('filter_name0',$filter_menu2[$tipo_filter2]['label']);
$paginator_menu0=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu0);


$elements_number=array('ReadytoPick'=>0,'ReadytoPack'=>0,'Done'=>0,'ReadytoShip'=>0,'PickingAndPacking'=>0,'ReadytoRestock'=>0);
$sql=sprintf("select count(*) as num from  `Delivery Note Dimension` where `Delivery Note State`  in ('Ready to be Picked') ");
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	$elements_number['ReadytoPick']=$row['num'];
}
$sql=sprintf("select count(*) as num from  `Delivery Note Dimension` where `Delivery Note State`  in ('Approved') ");
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	$elements_number['ReadytoShip']=$row['num'];
}

$sql=sprintf("select count(*) as num from  `Delivery Note Dimension` where `Delivery Note State`  in ('Packed Done') ");
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	$elements_number['Done']=$row['num'];
}

$sql=sprintf("select count(*) as num from  `Delivery Note Dimension` where `Delivery Note State`  in ('Picked') ");
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	$elements_number['ReadytoPack']=$row['num'];
}

$sql=sprintf("select count(*) as num from  `Delivery Note Dimension` where `Delivery Note State`  in ('Picking & Packing','Packer Assigned','Picker Assigned','Picking','Packing','Packed') ");
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	$elements_number['PickingAndPacking']=$row['num'];
}

$sql=sprintf("select count(*) as num from  `Delivery Note Dimension` where `Delivery Note State`  in ('Cancelled to Restock') ");
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	$elements_number['ReadytoRestock']=$row['num'];
}




$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['orders']['warehouse_orders']['elements']);



$smarty->display('warehouse_orders.tpl');
?>
