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
	'js/jquery.min.js',
'js/common.js',
	'js/table_common.js',
	'report_sales_main.js.php',
	'js/localize_calendar.js',
	'js/calendar_interval.js',
	'js/reports_calendar.js',
	'report_intrastat.js.php'

);

$title=_('Instrastad Report');



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
$smarty->assign('title',$title);


if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['report_intrastat']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['report_intrastat']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['report_intrastat']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);
$_SESSION['state']['report_intrastat']['period']=$period;
$_SESSION['state']['report_intrastat']['from']=$from;
$_SESSION['state']['report_intrastat']['to']=$to;
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');


$total=0;
$invoices=0;



$date_interval=prepare_mysql_dates($from.' 00:00:00' ,$to.' 23:59:59','`Invoice Date`');


	$where=sprintf("where `Current Dispatching State`='Dispatched' %s and `Destination Country 2 Alpha Code` in ('AT','BE','BG','CY','CZ','DK','EE','FI','FR','DE','GR','HU','IE','IT','LV','LT','LU','MT','NL','PL','PT','RO','SK','SI','ES') ",
		$date_interval['mysql']
	);



$sql="select  

	
	sum(`Invoice Currency Exchange Rate`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`+`Invoice Transaction Shipping Amount`+`Invoice Transaction Charges Amount`+`Invoice Transaction Insurance Amount`+`Invoice Transaction Net Adjust`+`Invoice Transaction Net Refund Items`+`Invoice Transaction Net Refund Shipping`+`Invoice Transaction Net Refund Charges`+`Invoice Transaction Net Refund Insurance`)) as value 
	,count(distinct `Invoice Key`) as invoices
	from
	`Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)
	$where  ";
	//print $sql;
	$result=mysql_query($sql);
	$data=array();

	if ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {
		$total=$row['value'];
		$invoices=$row['invoices'];
	}


$total=money($total,$corporate_currency);
$smarty->assign('total',$total);
$smarty->assign('invoices',$invoices);

$smarty->display('report_intrastat.tpl');



?>
