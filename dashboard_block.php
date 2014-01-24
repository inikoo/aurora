<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

include_once 'common.php';
include_once 'common_date_functions.php';

if (!isset($_REQUEST['tipo']))
	exit;
$tipo=$_REQUEST['tipo'];

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',

	// 'css/index.css',

	'css/dashboard.css',
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
	'external_libs/ampie/ampie/swfobject.js',
	'js/index.js',

);


// $_SESSION['state']['home']['splinters']['top_products']['type']='families';


switch ($tipo) {
case 'out_of_stock':
	$js_files[]='js/splinter_out_of_stock.js';
	$template='splinter_out_of_stock.tpl';





	if (isset($_REQUEST['period'])) {
		$period=$_REQUEST['period'];

	}else {
		$period=$_SESSION['state']['home']['splinters']['out_of_stock']['period'];
	}
	if (isset($_REQUEST['from'])) {
		$from=$_REQUEST['from'];
	}else {
		$from=$_SESSION['state']['home']['splinters']['out_of_stock']['from'];
	}

	if (isset($_REQUEST['to'])) {
		$to=$_REQUEST['to'];
	}else {
		$to=$_SESSION['state']['home']['splinters']['out_of_stock']['to'];
	}

	list($period_label,$from,$to)=get_period_data($period,$from,$to);
	$_SESSION['state']['home']['splinters']['out_of_stock']['period']=$period;
	$_SESSION['state']['home']['splinters']['out_of_stock']['from']=$from;
	$_SESSION['state']['home']['splinters']['out_of_stock']['to']=$to;
	$smarty->assign('from',$from);
	$smarty->assign('to',$to);
	$smarty->assign('period',$period);
	$smarty->assign('period_label',$period_label);


	switch ($period) {
	case 'ytd':
		$table_title=_('Out of stock').": "._('Year-to-Date');
		break;
	default:
		$table_title=_('Out of stock').' '.$period_label;
		break;
	}




	$smarty->assign('table_title',$table_title);



	break;
case 'top_customers':

	$js_files[]='js/splinter_top_customers.js';
	$template='splinter_top_customers.tpl';
	if (count($user->stores)==0) {
		return;
	}

	$store_keys=join(',',$user->stores);
	$store_title='';

	$sql=sprintf("select `Store Key`,`Store Code` from `Store Dimension` where `Store Key` in (%s) ",$store_keys);
	$res=mysql_query($sql);

	$smarty->assign('store_title',$store_title);
	$smarty->assign('store_keys',$store_keys);


	/*

	switch ($_SESSION['state']['home']['splinters']['top_customers']['period']) {
	case 'ytd':
		$table_title=_('Sales').": "._('Year-to-Date');
		break;
	default:
		$table_title=_('Sales').' '.$_SESSION['state']['home']['splinters']['top_customers']['period'];
		break;
	}
	$smarty->assign('table_title',$table_title);

*/



	$tipo_filter=$_SESSION['state']['home']['splinters']['top_customers']['f_field'];
	$smarty->assign('filter_name',$tipo_filter);
	$smarty->assign('filter_value',$_SESSION['state']['home']['splinters']['top_customers']['f_value']);
	$filter_menu=array(
		'name'=>array('db_key'=>'name','menu_label'=>_('Customer name like <i>x</i>'),'label'=>_('Name'))
	);

	$smarty->assign('filter_menu',$filter_menu);
	$smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);

	$paginator_menu=array(10,25,50,100,500);
	$smarty->assign('paginator_menu',$paginator_menu);

	//print_r($_SESSION['state']['home']['splinters']['top_customers']);
	$smarty->assign('top_customers_nr',$_SESSION['state']['home']['splinters']['top_customers']['nr']);
	//$smarty->assign('top_customers_type',$_SESSION['state']['home']['splinters']['top_customers']['type']);

	$smarty->assign('top_customers_period',$_SESSION['state']['home']['splinters']['top_customers']['period']);


	$smarty->assign('top_customers_index',1);

	break;
case 'top_products':

	$js_files[]='js/splinter_top_products.js';
	$template='splinter_top_products.tpl';
	if (count($user->stores)==0) {
		return;
	}

	$store_keys=join(',',$user->stores);
	$store_title='';

	$sql=sprintf("select `Store Key`,`Store Code` from `Store Dimension` where `Store Key` in (%s) ",$store_keys);
	$res=mysql_query($sql);

	$smarty->assign('store_keys',$store_keys);


	/*

	switch ($_SESSION['state']['home']['splinters']['top_products']['period']) {
	case 'ytd':
		$table_title=_('Sales').": "._('Year-to-Date');
		break;
	default:
		$table_title=_('Sales').' '.$_SESSION['state']['home']['splinters']['top_products']['period'];
		break;
	}
	$smarty->assign('table_title',$table_title);

*/



	$tipo_filter=$_SESSION['state']['home']['splinters']['top_products']['f_field'];
	$smarty->assign('filter_name',$tipo_filter);
	$smarty->assign('filter_value',$_SESSION['state']['home']['splinters']['top_products']['f_value']);
	$filter_menu=array(
		'code'=>array('db_key'=>'code','menu_label'=>_('Product code starting with <i>x</i>'),'label'=>_('Code')),
		'name'=>array('db_key'=>'name','menu_label'=>_('Product name containing <i>x</i>'),'label'=>_('Name'))
	);

	$smarty->assign('filter_menu',$filter_menu);
	$smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);

	$paginator_menu=array(10,25,50,100,500);
	$smarty->assign('paginator_menu',$paginator_menu);

	//print_r($_SESSION['state']['home']['splinters']['top_products']);
	$smarty->assign('top_products_nr',$_SESSION['state']['home']['splinters']['top_products']['nr']);
	$smarty->assign('top_products_type',$_SESSION['state']['home']['splinters']['top_products']['type']);

	$smarty->assign('top_products_period',$_SESSION['state']['home']['splinters']['top_products']['period']);


	$smarty->assign('top_products_index',1);



	break;

case 'sales_overview':
	$js_files[]='js/splinter_sales.js';
	$template='splinter_sales.tpl';

	switch ($_SESSION['state']['home']['splinters']['sales']['period']) {
	case 'ytd':
		$table_title=_('Sales').": "._('Year-to-Date');
		break;
	default:
		$table_title=_('Sales').' '.$_SESSION['state']['home']['splinters']['sales']['period'];
		break;
	}


	$smarty->assign('table_title',$table_title);



	break;
case 'pending_orders':

	include_once('class.Warehouse.php');
	$js_files[]='js/splinter_pending_orders.js';
	$template='splinter_pending_orders.tpl';

	if (count($user->stores)==0) {
		return;
	}

	$store_keys=join(',',$user->stores);
	$store_title='';

	$warehouse_key=1;
	$warehouse=new Warehouse($warehouse_key);

	$smarty->assign('warehouse',$warehouse);



	global $corporate_currency;

	$pending_orders_data=array();
	$total_pending_orders=0;
	$total_pending_orders_amount=0;
	
	$sql=sprintf('select count(*) as num ,`Order Store Key`,`Order Store Code`,sum(`Order Total Amount`*`Order Currency Exchange`) as amount from  `Order Dimension` where  `Order Store Key` in (%s)  and `Order Current Dispatch State` not in ("Dispatched","Unknown","Packing","Cancelled","Suspended","") group by `Order Store Key`',
	$store_keys);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$pending_orders_data[]=array(
		'store'=>'<a href="store_pending_orders.php?id='.$row['Order Store Key'].'" target="_parent">'.$row['Order Store Code'].'</a>',
		'number'=>'<a href="store_pending_orders.php?id='.$row['Order Store Key'].'" target="_parent">'.number($row['num']).'</a>',
		'amount'=>'<a href="store_pending_orders.php?id='.$row['Order Store Key'].'" target="_parent">'.money($row['amount'],$corporate_currency).'</a>',

		);
		
		$total_pending_orders+=$row['num'];
	$total_pending_orders_amount+=$row['amount'];
		
	
	}
$total_pending_orders_amount=money($total_pending_orders_amount,$corporate_currency);
	$smarty->assign('pending_orders_data',$pending_orders_data);
	$smarty->assign('total_pending_orders',$total_pending_orders);
	$smarty->assign('total_pending_orders_amount',$total_pending_orders_amount);

	break;


case 'dispatch_time':
	$js_files[]='js/splinter_dispatch_time.js';
	$template='splinter_dispatch_time.tpl';
	$parent='store';
	$parent_key=1;

	if ($parent=='store') {
		include_once 'class.Store.php';
		$scope= new Store($parent_key);
		$smarty->assign('scope',$scope);
	}elseif ($parent=='warehouse') {
		include_once 'class.Warehouse.php';
		$scope= new Warehouse($parent_key);
		$smarty->assign('scope',$scope);
	}else {
		exit;
	}
	break;
case 'average_order_value':
	$js_files[]='js/splinter_average_order_value.js';
	$template='splinter_average_order_value.tpl';
	$parent='store';
	$parent_key=1;


	include_once 'class.Store.php';
	$scope= new Store($parent_key);
	$smarty->assign('scope',$scope);
	$corporation_data=get_corporation_data();

	$currency=$corporation_data['Account Currency'];


	$from=date("Y-m-d H:i:s",strtotime("now -30 days"));
	$sql=sprintf("select count(*) as orders, avg(`Order Total Net Amount`*`Order Currency Exchange`) as net from `Order Dimension` where `Order Current Dispatch State`='Dispatched' and `Order Date`>%s ",prepare_mysql($from));
	$res=mysql_query($sql);


	$average_order_value=_('ND');
	$samples=0;

	if ($row=mysql_fetch_assoc($res)) {
		$average_order_value=money($row['net'],$currency);
		$samples=$row['orders'];

	}
	$smarty->assign('average_order_value',$average_order_value);
	$smarty->assign('store_title',$scope->data['Store Code']);



	break;
default:
	exit;
	break;
}
$smarty->assign('conf_data',$_SESSION['state']['home']['splinters']);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
if (isset($_REQUEST['block_key'])) {
	$smarty->assign('block_key',$_REQUEST['block_key']);
}
//print $template;
$smarty->display($template);
?>
