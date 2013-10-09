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
	//  'report_sales.js.php',
	'report_sales_components.js.php',
	'js/calendar_interval.js',
	'reports_calendar.js.php'
);

$root_title=_('Sales Components Report');
$title=_('Sales Components Report');

include_once 'reports_list.php';

$smarty->assign('parent','reports');




if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store'])) {
	$store=new Store($_REQUEST['store']);
	if (!$store->id) {
		header('Location: report_sales_components.php?no_store=1');
		exit;
	}

	$smarty->assign('store',$store);

	$template='report_sales_components_store.tpl';

}
else {

	if (isset($_REQUEST['tipo'])) {
		$tipo=$_REQUEST['tipo'];
		$_SESSION['state']['report_sales_components']['tipo']=$tipo;
	} else
		$tipo=$_SESSION['state']['report_sales_components']['tipo'];

	$sql=sprintf("select count(*) as num_stores,GROUP_CONCAT(Distinct `Currency Symbol`) as store_currencies from  `Store Dimension` left join kbase.`Currency Dimension` CD on (CD.`Currency Code`=`Store Currency Code`) ");
	$res=mysql_query($sql);

	if ($row=mysql_fetch_array($res)) {
		$num_stores=$row['num_stores'];
		$store_currencies=$row['store_currencies'];
	} else {
		exit("no stores");
	}

	if ($_SESSION['state']['report_sales_components']['store_keys']=='all') {
		$store_keys=join(',',$user->stores);
		$formated_store_keys='all';
	} else {
		$store_keys=$_SESSION['state']['report_sales_components']['store_keys'];
		$formated_store_keys=$store_keys;
	}

	if ($store_keys=='all') {
		global $user;
		$store_keys=join(',',$user->stores);
	}

	$am_safe_store_keys=preg_replace('/,/','|',$store_keys);


	$mixed_currencies=false;


	$smarty->assign('store_currencies',$store_currencies);
	$smarty->assign('corporate_currency_symbol',$corporate_currency_symbol);

	$store_key=$store_keys;


	$smarty->assign('view',$_SESSION['state']['report_sales_components']['view']);

	//print_r($_SESSION['state']['report_sales_components']['currency']);

	$smarty->assign('currencies',$_SESSION['state']['report_sales_components']['currency']);
	$smarty->assign('am_safe_store_keys',$am_safe_store_keys);

	$smarty->assign('store_keys',$store_keys);
	$smarty->assign('formated_store_keys',$formated_store_keys);

	$smarty->assign('type',$_SESSION['state']['report_sales_components']['block_view']);
	$tipo_filter=$_SESSION['state']['report_sales_components']['stores']['f_field'];
	$smarty->assign('filter0',$tipo_filter);
	$smarty->assign('filter_value0',$_SESSION['state']['report_sales_components']['stores']['f_value']);
	$filter_menu=array(
		'code'=>array('db_key'=>'code','menu_label'=>_('Store code starting with  <i>x</i>'),'label'=>_('Code')),
		'name'=>array('db_key'=>'name','menu_label'=>_('Store name containing <i>x</i>'),'label'=>_('Name'))
	);
	$smarty->assign('filter_menu0',$filter_menu);
	$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

	$paginator_menu=array(10,25,50,100,500);
	$smarty->assign('paginator_menu0',$paginator_menu);

	$template='report_sales_components.tpl';

}

$report_name='report_sales';

include_once 'report_dates.php';
$smarty->assign('report_url','report_sales_main.php');

$_SESSION['state']['report_sales_components']['to']=$to;
$_SESSION['state']['report_sales_components']['from']=$from;
$_SESSION['state']['report_sales_components']['period']=$period;

$int=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Invoice Date`','date start end');

//print_r($int);
//exit;


$smarty->assign('mixed_currencies',$mixed_currencies);

$plot_tipo=$_SESSION['state']['report_sales_components']['plot'];
$smarty->assign('plot_tipo',$plot_tipo);


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
$smarty->assign('currency',$corporate_currency);

$smarty->assign('quick_period',$quick_period);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display($template);



?>
