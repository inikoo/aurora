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
	'js/php.default.min.js',
	'js/common.js',
	'js/table_common.js',
	'js/localize_calendar.js',
	'js/calendar_interval.js',
	'js/reports_calendar.js',
	'report_sales_with_no_tax.js.php'

);



$title='';
$root_title=_('No Tax Report');




$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$report_name='report_sales_with_no_tax';


if (isset($_REQUEST['currency_type'])) {
	$currency_type=$_REQUEST['currency_type'];
	$_SESSION['state']['report_sales_with_no_tax']['currency_type']=$currency_type;
}else
	$currency_type=$_SESSION['state']['report_sales_with_no_tax']['currency_type'];




$smarty->assign('currency_type',$currency_type);



$tipo_filter=$_SESSION['state']['report_sales_with_no_tax']['invoices']['f_field'];
$smarty->assign('filter_show0',$_SESSION['state']['report_sales_with_no_tax']['invoices']['f_show']);
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['report_sales_with_no_tax']['invoices']['f_value']);
$filter_menu=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Invoice Number'),'label'=>_('Inv No')),
	'customer'=>array('db_key'=>'customer','menu_label'=>_('Customer'),'label'=>_('Customer')),
	'tax_number'=>array('db_key'=>'tax_number','menu_label'=>_('Tax Number'),'label'=>_('Tax No.')),
	'send_to'=>array('db_key'=>'send_to','menu_label'=>_('Send to'),'label'=>_('Send to')),

);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter=$_SESSION['state']['report_sales_with_no_tax']['customers']['f_field'];
$smarty->assign('filter_show1',$_SESSION['state']['report_sales_with_no_tax']['customers']['f_show']);
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['report_sales_with_no_tax']['customers']['f_value']);
$filter_menu=array(
	'customer'=>array('db_key'=>'customer','menu_label'=>_('Customer'),'label'=>_('Customer')),
	'tax_number'=>array('db_key'=>'tax_number','menu_label'=>_('Tax Number'),'label'=>_('Tax Number')),

);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$smarty->assign('title',$root_title);

$smarty->assign('corporate_country_code',$corporate_country_2alpha_code);
$_SESSION['state']['report_sales_with_no_tax']['country']=$corporate_country_2alpha_code;
if (count($_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['tax_category'])==0) {
	$sql=sprintf("select `Tax Category Key`,`Tax Category Code`,`Tax Category Name` from `Tax Category Dimension`");
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['tax_category'][$row['Tax Category Code']]=1;
	}
}

if (isset($_REQUEST['tax_category']) and array_key_exists($_REQUEST['tax_category'],$_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['tax_category'])  ) {
	foreach ($_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['tax_category'] as $_key=>$value) {
		if ($_REQUEST['tax_category']==$_key) {
			$_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['tax_category'][$_key]=1;

		}else {
			$_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['tax_category'][$_key]=0;
		}
	}
}


if (isset($_REQUEST['view']) and in_array($_REQUEST['view'],array('overview','customers','invoices'))  ) {
	$_SESSION['state']['report_sales_with_no_tax']['view']=$_REQUEST['view'];
}

$smarty->assign('view',$_SESSION['state']['report_sales_with_no_tax']['view']);

if (isset($_REQUEST['regions']) and array_key_exists($_REQUEST['regions'],$_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['regions'])  ) {
	foreach ($_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['regions'] as $_key=>$value) {
		if ($_REQUEST['regions']==$_key) {
			$_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['regions'][$_key]=1;
		}else {
			$_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['regions'][$_key]=0;
		}
	}
}
$smarty->assign('regions_selected',$_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['regions']);
$smarty->assign('encoded_regions_selected',base64_encode(json_encode($_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['regions'])));
$smarty->assign('encoded_tax_category_selected',base64_encode(json_encode($_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['tax_category'])));


if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['report_sales_with_no_tax']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['report_sales_with_no_tax']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['report_sales_with_no_tax']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);
$_SESSION['state']['report_sales_with_no_tax']['period']=$period;
$_SESSION['state']['report_sales_with_no_tax']['from']=$from;
$_SESSION['state']['report_sales_with_no_tax']['to']=$to;
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');


if ($from)$from=$from.' 00:00:00';
if ($to)$to=$to.' 23:59:59';

$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
$where_interval=$where_interval['mysql'];

$tax_categories=array();
$sql=sprintf("select `Invoice Tax Code`,`Tax Category Key`,`Tax Category Name`,`Tax Category Code` from `Invoice Dimension` left join   `Tax Category Dimension`  on (`Tax Category Code`=`Invoice Tax Code`) where true $where_interval group by `Invoice Tax Code`",
	prepare_mysql($from),
	prepare_mysql($to)
);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['Tax Category Code']=='UNK')
		$description='';
	else
		$description=': '.$row['Tax Category Name'];
	$tax_categories[$row['Tax Category Key']]=array('code'=>$row['Tax Category Code'],'name'=>$description,'selected'=>$_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['tax_category'][$row['Tax Category Code']]  );
}
$smarty->assign('tax_categories',$tax_categories);



$smarty->assign('filter_menu2',array());
$smarty->assign('filter_name2','');
$smarty->assign('filter_value2','');


$smarty->display('report_sales_with_no_tax.tpl');
?>
