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

include_once('Theme.php');
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
'reports_calendar.js.php',
		'report_pp.js.php',
		'js/dropdown.js'
		);

$report_name='report_pp';

$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


if(isset($_REQUEST['tipo'])){
  $tipo=$_REQUEST['tipo'];
  $_SESSION['state'][$report_name]['tipo']=$tipo;
}else
  $tipo=$_SESSION['state'][$report_name]['tipo'];


$root_title=_('Pickers & Packers Report');



include_once('report_dates.php');



$smarty->assign('report_url','report_pp.php');

include_once('reports_list.php');

  
$_SESSION['state']['report_pp']['pickers']['from']=$from;
$_SESSION['state']['report_pp']['pickers']['to']=$to;
$_SESSION['state']['report_pp']['packers']['from']=$from;
$_SESSION['state']['report_pp']['packers']['to']=$to;

$int=prepare_mysql_dates($from,$to,'`Invoice Date`','date start end');


//$interval_data=sales_in_interval($from,$to);
//$day_interval=get_time_interval(strtotime($from),(strtotime($to)));


$smarty->assign('tipo',$tipo);
$smarty->assign('period',$period);
$smarty->assign('title',$root_title);

$smarty->assign('root_title',$root_title);
$smarty->assign('year',date('Y'));
$smarty->assign('month',date('m'));
$smarty->assign('month_name',date('M'));


$smarty->assign('week',date('W'));
$smarty->assign('from',date('Y-m-d'));
$smarty->assign('to',date('Y-m-d'));

$smarty->display('report_pp.tpl');


?>

