<?php
include_once 'common.php';
include_once 'report_functions.php';
include_once 'class.Store.php';

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/calendar.css',
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
	'external_libs/amstock/amstock/swfobject.js',
	'js/common.js',
	'js/table_common.js',
	'report_sales_main.js.php',
	'js/calendar_interval.js',
	'reports_calendar.js.php',
	'report_intrastat.js.php'

);

//$root_title=_('Sales Report');
$title=_('Instrastad Report');

//include_once 'reports_list.php';

if(isset($_REQUEST['m'])){
$_SESSION['state']['report_intrastat']['m']=$_REQUEST['m'];
}
if(isset($_REQUEST['y'])){
$_SESSION['state']['report_intrastat']['y']=$_REQUEST['y'];
}

$y=$_SESSION['state']['report_intrastat']['y'];
$m=$_SESSION['state']['report_intrastat']['m'];

$period=strftime("%B %Y", strtotime("$y-$m-01"));
$smarty->assign('period',$period);

$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$tipo_filter=($_SESSION['state']['report_intrastat']['f_field']);
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['report_intrastat']['f_value']);
$filter_menu=array(
	'tariff_code'=>array('db_key'=>'tariff_code','menu_label'=>_('Tariff Code  <i>x</i>'),'label'=>_('Tariff Code')),

);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$quick_links=array(
array('label'=>strftime("%b %Y", strtotime('now -4 month')),'link'=>'report_intrastat.php?m='.date("m",strtotime('now -4 month')).'&y='.date("Y",strtotime('now -4 month'))),
array('label'=>strftime("%b %Y", strtotime('now -3 month')),'link'=>'report_intrastat.php?m='.date("m",strtotime('now -3 month')).'&y='.date("Y",strtotime('now -3 month'))),
array('label'=>strftime("%b %Y", strtotime('now -2 month')),'link'=>'report_intrastat.php?m='.date("m",strtotime('now -2 month')).'&y='.date("Y",strtotime('now -2 month'))),
array('label'=>strftime("%b %Y", strtotime('now -1 month')),'link'=>'report_intrastat.php?m='.date("m",strtotime('now -1 month')).'&y='.date("Y",strtotime('now -1 month'))),

array('label'=>strftime("%b %Y", strtotime('now')),'link'=>'report_intrastat.php?m='.date("m",strtotime('now')).'&y='.date("Y",strtotime('now')))

);
$smarty->assign('quick_links',$quick_links);



/*

if (isset($_REQUEST['tipo'])) {
	$tipo=$_REQUEST['tipo'];
	$_SESSION['state']['report_intrastad']['tipo']=$tipo;
} else
	$tipo=$_SESSION['state']['report_intrastad']['tipo'];


$report_name='report_intrastat';

//include_once 'report_dates.php';



$day_interval=get_time_interval(strtotime($from),(strtotime($to)))+1;
$smarty->assign('tipo',$tipo);
$smarty->assign('period',$period);

$smarty->assign('title',$title);
$smarty->assign('year',date('Y'));
$smarty->assign('month',date('m'));
$smarty->assign('month_name',date('M'));


$smarty->assign('week',date('W'));
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('currency',$myconf['currency_symbol']);

$smarty->assign('quick_period',$quick_period);

*/
$smarty->assign('title',$title);
$smarty->display('report_intrastat.tpl');



?>
