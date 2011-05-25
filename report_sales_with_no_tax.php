<?php
include_once('common.php');
include_once('report_functions.php');
include_once('class.Store.php');

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
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/php.default.min.js',
		'js/common.js',
		'js/table_common.js',
		'js/calendar_interval.js',
		'report_sales_with_no_tax.js.php',
        'reports_calendar.js.php',
		'js/dropdown.js'
		);












$root_title=_('Sales Report');

include_once('reports_list.php');



$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$report_name='report_sales_with_no_tax';

if(isset($_REQUEST['tipo'])){
  $tipo=$_REQUEST['tipo'];
  $_SESSION['state'][$report_name]['tipo']=$tipo;
}else
  $tipo=$_SESSION['state'][$report_name]['tipo'];

if(isset($_REQUEST['currency_type'])){
  $currency_type=$_REQUEST['currency_type'];
  $_SESSION['state'][$report_name]['currency_type']=$currency_type;
}else
  $currency_type=$_SESSION['state'][$report_name]['currency_type'];

$store_keys=join(',',$user->stores);

if($tipo=='quick_all')
  $tipo='all_invoices';

include_once('report_dates.php');
$_SESSION['state'][$report_name]['stores']=$store_keys;
$_SESSION['state'][$report_name]['invoices']['from']=$from;
$_SESSION['state'][$report_name]['invoices']['to']=$to;
$_SESSION['state'][$report_name]['customers']['from']=$from;
$_SESSION['state'][$report_name]['customers']['to']=$to;
$_SESSION['state'][$report_name]['overview']['from']=$from;
$_SESSION['state'][$report_name]['overview']['to']=$to;
$smarty->assign('tipo',$tipo);
$smarty->assign('currency_type',$currency_type);

$smarty->assign('period',$period);
$smarty->assign('from',$from);
$smarty->assign('to',$to);

$tipo_filter=$_SESSION['state'][$report_name]['invoices']['f_field'];
$smarty->assign('filter_show0',$_SESSION['state'][$report_name]['invoices']['f_show']);
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state'][$report_name]['invoices']['f_value']);
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

$tipo_filter=$_SESSION['state'][$report_name]['customers']['f_field'];
$smarty->assign('filter_show1',$_SESSION['state'][$report_name]['customers']['f_show']);
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state'][$report_name]['customers']['f_value']);
$filter_menu=array(
		   'customer'=>array('db_key'=>'customer','menu_label'=>_('Customer'),'label'=>_('Customer')),
		   'tax_number'=>array('db_key'=>'tax_number','menu_label'=>_('Tax Number'),'label'=>_('Tax Number')),
		   
		   );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$smarty->assign('view','overview');
$smarty->assign('title',$title);
$smarty->assign('tipo',$tipo);
 $smarty->assign('quick_period',$quick_period);

$smarty->display('report_sales_with_no_tax.tpl');
?>

