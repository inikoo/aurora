<?php
include_once('common.php');
include_once('report_functions.php');






$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
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
		'report_customers.js.php',
		'reports_calendar.js.php',
		'js/dropdown.js',
		'js/export.js'

		);
include_once('reports_list.php');
$report_name=_('Top Customers');


$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$report_name='report_first_order';


if(isset($_REQUEST['tipo'])){
  $tipo=$_REQUEST['tipo'];
  $_SESSION['state'][$report_name]['tipo']=$tipo;
}else
  $tipo=$_SESSION['state'][$report_name]['tipo'];
  



$root_title=_('Customer Report');
$smarty->assign('report_url','report_customers.php');

if($_SESSION['state']['report_customers']['store_keys']=='all')
  $store_keys=join(',',$user->stores);
else
  $store_keys=$_SESSION['state']['report_customers']['store_keys'];


include_once('report_dates.php');
$_SESSION['state']['report_customers']['from']=$from;
$_SESSION['state']['report_customers']['to']=$to;



$export_output['type']=$_SESSION['state']['export'];
$export_output['label']=$export_data[$_SESSION['state']['export']]['label'];
//print_r($export_output);
$smarty->assign('export',$export_output);
$smarty->assign('export_menu',$export_data);


$smarty->assign('criteria',$_SESSION['state']['report_customers']['criteria']);
$smarty->assign('top',$_SESSION['state']['report_customers']['top']);



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
$smarty->display('report_customers.tpl');


?>

