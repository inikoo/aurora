<?php
include_once 'common.php';
include_once 'report_functions.php';

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
	'report_customers.js.php',
	'js/localize_calendar.js',
	'js/reports_calendar.js',
	'js/export.js'

);



$root_title=_('Top Customers');
$report_name='report_customers';
$title=_('Top Customers');

include_once 'reports_list.php';


$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




if (isset($_REQUEST['tipo'])) {
	$tipo=$_REQUEST['tipo'];
	$_SESSION['state'][$report_name]['tipo']=$tipo;
}else
	$tipo=$_SESSION['state'][$report_name]['tipo'];




$smarty->assign('report_url','report_customers.php');

if ($_SESSION['state']['report_customers']['store_keys']=='all')
	$store_keys=join(',',$user->stores);
else
	$store_keys=$_SESSION['state']['report_customers']['store_keys'];


include_once 'report_dates.php';
$_SESSION['state']['report_customers']['from']=$from;
$_SESSION['state']['report_customers']['to']=$to;



//$export_output['type']=$_SESSION['state']['export'];
//$export_output['label']=$export_data[$_SESSION['state']['export']]['label'];
//print_r($export_output);
//$smarty->assign('export',$export_output);
//$smarty->assign('export_menu',$export_data);


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
$smarty->assign('quick_period',$quick_period);



$tipo_filter=$_SESSION['state']['customers']['customers']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['customers']['customers']['f_value']);

$filter_menu=array(
	'customer name'=>array('db_key'=>_('customer name'),'menu_label'=>_('Customer Name'),'label'=>_('Name')),
	'postcode'=>array('db_key'=>_('postcode'),'menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
	'country'=>array('db_key'=>_('country'),'menu_label'=>_('Customer Country'),'label'=>_('Country')),

	'min'=>array('db_key'=>_('min'),'menu_label'=>_('Mininum Number of Orders'),'label'=>_('Min No Orders')),
	'max'=>array('db_key'=>_('min'),'menu_label'=>_('Maximum Number of Orders'),'label'=>_('Max No Orders')),
	'last_more'=>array('db_key'=>_('last_more'),'menu_label'=>_('Last order more than (days)'),'label'=>_('Last Order >(Days)')),
	'last_less'=>array('db_key'=>_('last_more'),'menu_label'=>_('Last order less than (days)'),'label'=>_('Last Order <(Days)')),
	'maxvalue'=>array('db_key'=>_('maxvalue'),'menu_label'=>_('Balance less than').' '.$corporate_currency  ,'label'=>_('Balance')." <($corporate_currency)"),
	'minvalue'=>array('db_key'=>_('minvalue'),'menu_label'=>_('Balance more than').' '.$corporate_currency  ,'label'=>_('Balance')." >($corporate_currency)"),
);


$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->display('report_customers.tpl');

?>
