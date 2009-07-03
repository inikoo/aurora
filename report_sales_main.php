<?
include_once('common.php');
include_once('report_functions.php');
include_once('classes/Store.php');

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css'
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
		'js/common.js.php',
		'js/table_common.js.php',
		'js/calendar_common.js.php',

		'js/report_sales.js.php'
		);



$smarty->assign('parent','reports.php');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

include_once('report_dates.php');








$_SESSION['state']['report']['sales']['to']=$to;
$_SESSION['state']['report']['sales']['from']=$from;
$_SESSION['state']['report']['sales']['period']=$period;

  
/* $valid_rates=array( */
/* 		   array('date'=>'01-01-2000','rate'=>17.5), */
/* 		   array('date'=>'01-12-2008','rate'=>15) */
/* 		   ); */

$store_key=3;


$store=new Store($store_key);

$currency=$store->data['Store Currency Code']; 
$home_name=$store->data['Store Home Country Name'];
$home_short_name=$store->data['Store Home Country Short Name'];


$interval_data=sales_in_interval($from,$to,$store_key);
$day_interval=get_time_interval(strtotime($from),(strtotime($to)))+1;
$smarty->assign('tipo',$tipo);
$smarty->assign('period',$period);

$smarty->assign('title',$title);
$smarty->assign('year',date('Y'));
$smarty->assign('month',date('m'));
$smarty->assign('month_name',date('M'));


$smarty->assign('week',date('W'));
$smarty->assign('from',date('d-m-Y'));
$smarty->assign('to',date('d-m-Y'));
$smarty->assign('currency',$myconf['currency_symbol']);

 $smarty->display('report_sales_main.tpl');

?>

