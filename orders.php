<?php
include_once 'common.php';
include_once 'class.Store.php';
if (!$user->can_view('orders'))
	exit();

if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
	$store_id=$_REQUEST['store'];

} else {
	$store_id=$_SESSION['state']['orders']['store'];

}

if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
	header('Location: index.php');
	exit;
}

$store=new Store($store_id);
$smarty->assign('store',$store);
$smarty->assign('store_id',$store->id);

$_SESSION['state']['orders']['store']=$store_id;


$q='';

$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'warehouse_orders.php','label'=>_('Warehouse Operations'));

$general_options_list[]=array('tipo'=>'url','url'=>'orders_lists.php?store='.$store->id,'label'=>_('Lists'));


//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');


$sql="select count(*) as numberof from `Order Dimension`";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC))
	$orders=$row['numberof'];
else
	exit('Internal Error');
mysql_free_result($result);

if (isset($_REQUEST['view']) and preg_match('/^orders|invoices|dn$/',$_REQUEST['view'])) {
	$_SESSION['state']['orders']['view']=$_REQUEST['view'];
}
if (isset($_REQUEST['invoice_type']) and preg_match('/^all|invoices|refunds|to_pay|paid$/',$_REQUEST['invoice_type'])) {
	$_SESSION['state']['orders']['invoices']['invoice_type']=$_REQUEST['invoice_type'];
}

if (isset($_REQUEST['dispatch']) and preg_match('/^all_orders|in_process|dispatched|unknown|cancelled|suspended$/',$_REQUEST['dispatch'])) {
	$_SESSION['state']['orders']['table']['dispatch']=$_REQUEST['dispatch'];
}

$block_view=$_SESSION['state']['orders']['view'];

$smarty->assign('block_view',$block_view);
$smarty->assign('dispatch',$_SESSION['state']['orders']['table']['dispatch']);
$smarty->assign('invoice_type',$_SESSION['state']['orders']['invoices']['invoice_type']);
$smarty->assign('dn_state_type',$_SESSION['state']['orders']['dn']['dn_state_type']);

$smarty->assign('dn_view',$_SESSION['state']['stores']['delivery_notes']['view']);

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
$_SESSION['state']['orders']['to']=$to;
$_SESSION['state']['orders']['from']=$from;

$smarty->assign('from',$from);
$smarty->assign('to',$to);

$smarty->assign('box_layout','yui-t0');


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'common.css',
	'css/container.css',
	'button.css',
	'table.css',
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
	'orders.js.php'
);




$smarty->assign('parent','orders');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$tipo_filter0=($q==''?$_SESSION['state']['orders']['table']['f_field']:'public_id');
$smarty->assign('filter0',$tipo_filter0);
$smarty->assign('filter_value0',($q==''?$_SESSION['state']['orders']['table']['f_value']:addslashes($q)));
$filter_menu0=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'Order Number'),
	'customer_name'=>array('db_key'=>'customer_name','menu_label'=>'Customer Name starting with <i>x</i>','label'=>'Customer'),
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
	'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'Invoice Number'),
	'customer_name'=>array('db_key'=>'customer_name','menu_label'=>'Customer Name starting with <i>x</i>','label'=>'Customer'),
	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Orders with a minimum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Min Value ('.$corporate_currency_symbol.')'),
	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Orders with a maximum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Max Value ('.$corporate_currency_symbol.')'),
	'country'=>array('db_key'=>'country','menu_label'=>'Orders from country code <i>xxx</i>','label'=>'Country Code')
);
$smarty->assign('filter_menu1',$filter_menu1);
$smarty->assign('filter_name1',$filter_menu1[$tipo_filter1]['label']);
$paginator_menu1=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu1);

$tipo_filter2=$_SESSION['state']['orders']['dn']['f_field'];
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2',($_SESSION['state']['orders']['dn']['f_value']));
$filter_menu2=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'DN Number'),
	'customer_name'=>array('db_key'=>'customer_name','menu_label'=>'Customer Name starting with <i>x</i>','label'=>'Customer'),
	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Orders with a minimum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Min Value ('.$corporate_currency_symbol.')'),
	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Orders with a maximum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Max Value ('.$corporate_currency_symbol.')'),
	'country'=>array('db_key'=>'country','menu_label'=>'Orders from country code <i>xxx</i>','label'=>'Country Code')
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



$smarty->display('orders.tpl');
?>
