<?php
include_once 'common.php';
include_once 'class.Store.php';


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'common.css',
	'css/container.css',
	'button.css',
	'table.css',
	'css/edit.css',
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
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'js/edit_common.js',
	'js/csv_common.js',
	'orders.js.php',
	'js/calendar_interval.js',
	'reports_calendar.js.php'
);

if (!$user->can_view('orders')) {
	$smarty->assign('parent','orders');
	$smarty->assign('title', _('Orders'));
	$smarty->assign('scope', 'orders');
	$smarty->assign('css_files',$css_files);
	$smarty->assign('js_files',$js_files);
	$smarty->display('forbidden.tpl');
	exit;
}


if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
	$store_id=$_REQUEST['store'];

} else {
	header('Location: orders_server.php?msg=no_store');
	exit;

}

$store=new Store($store_id);
if (!$store->id) {
	header('Location: orders_server.php?msg=wrong_store');
	exit;
}


if (!($user->can_view('stores') and in_array($store->id,$user->stores)   ) ) {
	$smarty->assign('parent','orders');
	$smarty->assign('title', _('Orders').' ('.$store->data['Store Name'].')');
	$smarty->assign('scope', 'store');
	$smarty->assign('store', $store);
	$smarty->assign('css_files',$css_files);
	$smarty->assign('js_files',$js_files);
	$smarty->display('forbidden.tpl');
	exit;
}


$smarty->assign('store',$store);
$smarty->assign('store_id',$store->id);



$q='';


$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');




if (isset($_REQUEST['view']) and preg_match('/^orders|invoices|dn$/',$_REQUEST['view'])) {
	$_SESSION['state']['orders']['view']=$_REQUEST['view'];
}

$block_view=$_SESSION['state']['orders']['view'];

$smarty->assign('block_view',$block_view);

if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from='';
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
	$_SESSION['state']['orders']['to']=$to;
}else {
	$to='';
}
if (isset($_REQUEST['tipo'])) {
	$tipo=$_REQUEST['tipo'];
	$_SESSION['state']['orders']['period']=$tipo;
}else {
	$tipo=$_SESSION['state']['orders']['period'];
}

$smarty->assign('period_type',$tipo);


$report_name='orders';


include_once 'report_dates.php';

$_SESSION['state']['orders']['to']=$to;
$_SESSION['state']['orders']['from']=$from;

$smarty->assign('from',$from);
$smarty->assign('to',$to);

//print_r($_SESSION['state']['orders']);
$smarty->assign('period',$period);
$smarty->assign('tipo',$tipo);







$smarty->assign('parent','orders');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$tipo_filter0=($q==''?$_SESSION['state']['orders']['orders']['f_field']:'public_id');
$smarty->assign('filter0',$tipo_filter0);
$smarty->assign('filter_value0',($q==''?$_SESSION['state']['orders']['orders']['f_value']:addslashes($q)));
$filter_menu0=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'Order Number'),
	'customer_name'=>array('db_key'=>'customer_name','menu_label'=>'Customer name starting with <i>x</i>','label'=>'Customer'),
	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Orders with a minimum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Min Value ('.$corporate_currency_symbol.')'),
	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Orders with a maximum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Max Value ('.$corporate_currency_symbol.')'),
	'country'=>array('db_key'=>'country','menu_label'=>'Orders from country code <i>xxx</i>','label'=>'Country Code')
);
$smarty->assign('filter_menu0',$filter_menu0);
$smarty->assign('filter_name0',$filter_menu0[$tipo_filter0]['label']);
$paginator_menu0=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu0);

$tipo_filter1=$_SESSION['state']['orders']['invoices']['f_field'];
$smarty->assign('filter1',$tipo_filter1);
$smarty->assign('filter_value1',($_SESSION['state']['orders']['invoices']['f_value']));
$filter_menu1=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>'Invoice Number starting with <i>x</i>','label'=>'Invoice Number'),
	'customer_name'=>array('db_key'=>'customer_name','menu_label'=>'Customer Name starting with <i>x</i>','label'=>'Customer'),
	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Invoice with a minimum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Min Value ('.$corporate_currency_symbol.')'),
	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Invoice with a maximum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Max Value ('.$corporate_currency_symbol.')'),
	'country'=>array('db_key'=>'country','menu_label'=>'Invoice billed to country code <i>xxx</i>','label'=>'Country Code')
);
$smarty->assign('filter_menu1',$filter_menu1);
$smarty->assign('filter_name1',$filter_menu1[$tipo_filter1]['label']);
$paginator_menu1=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu1);

$tipo_filter2=$_SESSION['state']['orders']['dn']['f_field'];
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2',($_SESSION['state']['orders']['dn']['f_value']));
$filter_menu2=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>'Delivery Note ID starting with <i>x</i>','label'=>'DN Number'),
	'customer_name'=>array('db_key'=>'customer_name','menu_label'=>'Customer Name starting with <i>x</i>','label'=>'Customer'),
//	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Orders with a minimum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Min Value ('.$corporate_currency_symbol.')'),
//	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Orders with a maximum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Max Value ('.$corporate_currency_symbol.')'),
	'country'=>array('db_key'=>'country','menu_label'=>'Delivery Note to country code <i>xxx</i>','label'=>'Country Code')
);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$paginator_menu2=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu2);


if ($block_view=='invoices')
	$smarty->assign('title', _('Invoices').' ('.$store->data['Store Code'].')');

elseif ($block_view=='dn')
	$smarty->assign('title', _('Delivery Notes').' ('.$store->data['Store Code'].')');

else
	$smarty->assign('title', _('Orders').' ('.$store->data['Store Code'].')');



$total_invoices_and_refunds=$store->get('Total Invoices');
$total_invoices=$store->get('Invoices');
$total_refunds=$store->get('Refunds');
$total_to_pay=$store->get('All To Pay Invoices');
$total_paid=$store->get('All Paid Invoices');

$smarty->assign('total_invoices_and_refunds',$total_invoices_and_refunds);
$smarty->assign('total_invoices',$total_invoices);
$smarty->assign('total_refunds',$total_refunds);
$smarty->assign('total_paid',$total_paid);
$smarty->assign('total_to_pay',$total_to_pay);
$smarty->assign('quick_period',$quick_period);


$smarty->assign('elements_order_dispatch',$_SESSION['state']['orders']['orders']['elements']['dispatch']);
$smarty->assign('elements_order_type',$_SESSION['state']['orders']['orders']['elements']['type']);
$smarty->assign('elements_order_source',$_SESSION['state']['orders']['orders']['elements']['source']);
$smarty->assign('elements_order_payement',$_SESSION['state']['orders']['orders']['elements']['payment']);

$smarty->assign('elements_invoice_type',$_SESSION['state']['orders']['invoices']['elements']['type']);
$smarty->assign('elements_invoice_payment',$_SESSION['state']['orders']['invoices']['elements']['payment']);

$smarty->assign('elements_dn_type',$_SESSION['state']['orders']['dn']['elements']['type']);
$smarty->assign('elements_dn_dispatch',$_SESSION['state']['orders']['dn']['elements']['dispatch']);


$smarty->assign('elements_order_elements_type',$_SESSION['state']['orders']['orders']['elements_type']);
$smarty->assign('elements_dn_elements_type',$_SESSION['state']['orders']['dn']['elements_type']);
$smarty->assign('elements_invoice_elements_type',$_SESSION['state']['orders']['invoices']['elements_type']);



$smarty->display('orders.tpl');
?>
