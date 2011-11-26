<?php
include_once('common.php');
include_once('report_functions.php');






$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 
		 'button.css',
		 'container.css',
		 
		 'css/calendar.css'
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
		$yui_path.'calendar/calendar-min.js',
		'js/common.js',
		'js/table_common.js',
		'report_out_of_stock.js.php',
		'reports_calendar.js.php',
		'js/dropdown.js',
		'js/export.js'

		);
include_once('reports_list.php');
$report_name=_('Out of Stock');


$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$report_name='report_part_out_of_stock';


if(isset($_REQUEST['tipo'])){
  $tipo=$_REQUEST['tipo'];
  $_SESSION['state'][$report_name]['tipo']=$tipo;
}else
  $tipo=$_SESSION['state'][$report_name]['tipo'];
  



$root_title=_('Mark as Out of Stock Report');
$smarty->assign('report_url','report_customers.php');

if($_SESSION['state']['report_part_out_of_stock']['store_keys']=='all')
  $store_keys=join(',',$user->stores);
else
  $store_keys=$_SESSION['state']['report_part_out_of_stock']['store_keys'];


include_once('report_dates.php');


$_SESSION['state']['report_part_out_of_stock']['from']=$from;
$_SESSION['state']['report_part_out_of_stock']['to']=$to;

$smarty->assign('view',$_SESSION['state']['report_part_out_of_stock']['view']);


$export_output['type']=$_SESSION['state']['export'];
$export_output['label']=$export_data[$_SESSION['state']['export']]['label'];
//print_r($export_output);
$smarty->assign('export',$export_output);
$smarty->assign('export_menu',$export_data);




//$int=prepare_mysql_dates($from,$to,'`Invoice Date`','date start end');


//$interval_data=sales_in_interval($from,$to);
//$day_interval=get_time_interval(strtotime($from),(strtotime($to)));


$smarty->assign('tipo',$tipo);
$smarty->assign('period',$period);

$smarty->assign('title',$title);
$smarty->assign('year',date('Y'));
$smarty->assign('month',date('m'));
$smarty->assign('month_name',date('M'));


$smarty->assign('week',date('W'));

$smarty->assign('from',$from);
$smarty->assign('to',$to);

$tipo_filter=$_SESSION['state']['report_part_out_of_stock']['transactions']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['report_part_out_of_stock']['transactions']['f_value']);

$filter_menu=array(
		   'used_in'=>array('db_key'=>_('customer name'),'menu_label'=>_('Customer Name'),'label'=>_('Name')),
		   'sku'=>array('db_key'=>_('postcode'),'menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),

				   );
		   
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter=$_SESSION['state']['report_part_out_of_stock']['parts']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['report_part_out_of_stock']['parts']['f_value']);

$filter_menu=array(
		   'used_in'=>array('db_key'=>_('customer name'),'menu_label'=>_('Customer Name'),'label'=>_('Name')),
		   'sku'=>array('db_key'=>_('postcode'),'menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),

				   );
		   
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);



$smarty->display('report_out_of_stock.tpl');


?>

