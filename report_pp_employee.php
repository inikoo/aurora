<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 24 January 2014 11:51:31 GMT, Sheffield UK	

 Copyright (c) 2014, Inikoo
 
 Version 2.0
*/


include_once 'common.php';
include_once('common_date_functions.php');
include_once('class.Staff.php');



if (!isset($_REQUEST['id']) ) {
	header('Location: report_pp.php');
	exit;
}


$employee=new Staff($_REQUEST['id']);
$smarty->assign('employee',$employee);




$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
	'css/common.css',
	'css/container.css',
	'css/calendar.css',

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
'report_pp_employee.js.php',

);


$report_name='report_pp';

if (isset($_REQUEST['view']) and ($_REQUEST['view']=='picked' or $_REQUEST['view']=='packed'  or $_REQUEST['view']=='overview'  ))
	$_SESSION['state'][$report_name]['employee_view']=$_REQUEST['view'];
$view=$_SESSION['state'][$report_name]['employee_view'];



$smarty->assign('block_view',$view);

$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);









$root_title=_('Picking & Packing Report');
$smarty->assign('title',$root_title);



if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['report_pp']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['report_pp']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['report_pp']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);
$_SESSION['state']['report_pp']['period']=$period;
$_SESSION['state']['report_pp']['from']=$from;
$_SESSION['state']['report_pp']['to']=$to;
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');

$smarty->display("report_pp_employee.tpl");



?>
