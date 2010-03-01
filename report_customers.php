<?php
include_once('common.php');
include_once('report_functions.php');






$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css',
		 'css/dropdown.css'
		 );
$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'common.js.php',
		'table_common.js.php',
		'calendar_common.js.php',

		'report_customers.js.php',
		'js/dropdown.js',
		'js/export.js'

		);



$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if(isset($_REQUEST['tipo'])){
$tipo=$_REQUEST['tipo'];
$_SESSION['state']['report']['tipo']=$tipo;
}else
$tipo=$_SESSION['state']['report']['tipo'];




$root_title=_('Customer Report');
$smarty->assign('report_url','report_customers.php');

if($_SESSION['state']['report']['customers']['store_keys']=='all')
  $store_keys=join(',',$user->stores);
else
  $store_keys=$_SESSION['state']['report']['customers']['store_keys'];


include_once('report_dates.php');
$_SESSION['state']['report']['customers']['from']=$from;
$_SESSION['state']['report']['customers']['to']=$to;



$export_output['type']=$_SESSION['state']['export'];
$export_output['label']=$export_data[$_SESSION['state']['export']]['label'];
//print_r($export_output);
$smarty->assign('export',$export_output);
$smarty->assign('export_menu',$export_data);


$smarty->assign('criteria',$_SESSION['state']['report']['customers']['criteria']);
$smarty->assign('top',$_SESSION['state']['report']['customers']['top']);



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
$smarty->assign('from',date('d-m-Y'));
$smarty->assign('to',date('d-m-Y'));

$smarty->display('report_customers.tpl');


?>

