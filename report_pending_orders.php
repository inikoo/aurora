<?php
include_once 'common.php';
include_once 'common_date_functions.php';


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
	'js/localize_calendar.js',
	'js/calendar_interval.js',
	'js/reports_calendar.js',
		'report_pending_orders.js.php',

);

$title=_('Sales Components Report');


$smarty->assign('parent','reports');




if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store'])) {
	$store=new Store($_REQUEST['store']);
	if (!$store->id) {
		header('Location: report_pending_orders.php?no_store=1');
		exit;
	}

	$smarty->assign('store',$store);

	$template='report_pending_orders_store.tpl';

}
else {

	

	$smarty->assign('block_view',$_SESSION['state']['report_pending_orders']['block_view']);
	$tipo_filter=$_SESSION['state']['report_pending_orders']['stores']['f_field'];
	$smarty->assign('filter0',$tipo_filter);
	$smarty->assign('filter_value0',$_SESSION['state']['report_pending_orders']['stores']['f_value']);
	$filter_menu=array(
		'name'=>array('db_key'=>'name','menu_label'=>_('Store name'),'label'=>_('Name'))
	);
	$smarty->assign('filter_menu0',$filter_menu);
	$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

	$paginator_menu=array(10,25,50,100,500);
	$smarty->assign('paginator_menu0',$paginator_menu);

	$template='report_pending_orders.tpl';

}



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['report_pending_orders']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['report_pending_orders']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['report_pending_orders']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);
$_SESSION['state']['report_pending_orders']['period']=$period;
$_SESSION['state']['report_pending_orders']['from']=$from;
$_SESSION['state']['report_pending_orders']['to']=$to;
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');

$smarty->display($template);



?>
