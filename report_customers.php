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
	'css/edit.css',
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
	'js/reports_calendar.js',
	'js/export.js',
	'report_customers.js.php'

);



$root_title=_('Top Customers');
$report_name='report_customers';
$title=_('Top Customers');



$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);





$smarty->assign('report_url','report_customers.php');

if ($_SESSION['state']['report_customers']['store_keys']=='all')
	$store_keys=join(',',$user->stores);
else
	$store_keys=$_SESSION['state']['report_customers']['store_keys'];


$smarty->assign('criteria',$_SESSION['state']['report_customers']['criteria']);
$smarty->assign('top',$_SESSION['state']['report_customers']['top']);

$smarty->assign('title',$title);


$tipo_filter=$_SESSION['state']['customers']['customers']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['customers']['customers']['f_value']);

$filter_menu=array(
	'customer name'=>array('db_key'=>'customer name','menu_label'=>_('Customer Name'),'label'=>_('Name')),
	'postcode'=>array('db_key'=>'postcode','menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
	'country'=>array('db_key'=>'country','menu_label'=>_('Customer Country'),'label'=>_('Country')),

	'min'=>array('db_key'=>'min','menu_label'=>_('Mininum Number of Orders'),'label'=>_('Min No Orders')),
	'max'=>array('db_key'=>'min','menu_label'=>_('Maximum Number of Orders'),'label'=>_('Max No Orders')),
	'last_more'=>array('db_key'=>'last_more','menu_label'=>_('Last order more than (days)'),'label'=>_('Last Order >(Days)')),
	'last_less'=>array('db_key'=>'last_more','menu_label'=>_('Last order less than (days)'),'label'=>_('Last Order <(Days)')),
	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>_('Balance less than').' '.$corporate_currency  ,'label'=>_('Balance')." <($corporate_currency)"),
	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>_('Balance more than').' '.$corporate_currency  ,'label'=>_('Balance')." >($corporate_currency)"),
);


$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['report_customers']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['report_customers']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['report_customers']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);
$_SESSION['state']['report_customers']['period']=$period;
$_SESSION['state']['report_customers']['from']=$from;
$_SESSION['state']['report_customers']['to']=$to;
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');


$smarty->display('report_customers.tpl');

?>
