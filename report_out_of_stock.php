<?php
include_once 'common.php';
include_once 'common_date_functions.php';



$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
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
	
	'js/localize_calendar.js',
	'js/calendar_interval.js',
	
	'js/reports_calendar.js',
	'js/export.js',
	'report_out_of_stock.js.php',

);

$root_title=_('Mark as Out of Stock Report');
$title=_('Out of Stock');


$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$report_name='report_part_out_of_stock';


$smarty->assign('report_url','report_customers.php');

if ($_SESSION['state']['report_part_out_of_stock']['store_keys']=='all')
	$store_keys=join(',',$user->stores);
else
	$store_keys=$_SESSION['state']['report_part_out_of_stock']['store_keys'];


if(isset($_REQUEST['block']) and in_array($_REQUEST['block'],array('transactions','parts','orders','customers'))){
	$block=$_REQUEST['block'];
}else{
	$block=$_SESSION['state']['report_part_out_of_stock']['block'];
}

$_SESSION['state']['report_part_out_of_stock']['block']=$block;
$smarty->assign('block',$block);

$tipo_filter=$_SESSION['state']['report_part_out_of_stock']['transactions']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['report_part_out_of_stock']['transactions']['f_value']);

$filter_menu=array(
	'product'=>array('db_key'=>'product','menu_label'=>_('Product Code'),'label'=>_('Product Code')),
	'sku'=>array('db_key'=>'sku','menu_label'=>_('Part SKU'),'label'=>_('SKU')),
		'picker'=>array('db_key'=>'picker','menu_label'=>_('Picker Alias'),'label'=>_('Picker')),
	'order'=>array('db_key'=>'order','menu_label'=>_('Order ID'),'label'=>_('Order'))

);

$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter=$_SESSION['state']['report_part_out_of_stock']['parts']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['report_part_out_of_stock']['parts']['f_value']);

$filter_menu=array(
	'sku'=>array('db_key'=>'sku','menu_label'=>_('Part SKU'),'label'=>_('SKU')),
	'reference'=>array('db_key'=>'reference','menu_label'=>_('Part Reference'),'label'=>_('Reference')),
//	'used_in'=>array('db_key'=>'used_in','menu_label'=>_('Used in'),'label'=>_('Used in')),  
);

$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['report_part_out_of_stock']['customers']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['report_part_out_of_stock']['customers']['f_value']);

$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Customer Name'),'label'=>_('Name')),
);

$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);



$tipo_filter=$_SESSION['state']['report_part_out_of_stock']['orders']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['report_part_out_of_stock']['orders']['f_value']);

$filter_menu3=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Order number'),'label'=>_('Order Number')),
	'customer_name'=>array('db_key'=>'customer_name','menu_label'=>_('Customer name'),'label'=>_('Customer')),
);
$smarty->assign('filter_menu3',$filter_menu3);
$smarty->assign('filter_name3',$filter_menu3[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);


$smarty->assign('title',$title);


if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['report_part_out_of_stock']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['report_part_out_of_stock']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['report_part_out_of_stock']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);
$_SESSION['state']['report_part_out_of_stock']['period']=$period;
$_SESSION['state']['report_part_out_of_stock']['from']=$from;
$_SESSION['state']['report_part_out_of_stock']['to']=$to;
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');


$smarty->display('report_out_of_stock.tpl');


?>
