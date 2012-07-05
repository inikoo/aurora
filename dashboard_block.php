<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

include_once 'common.php';

if (!isset($_REQUEST['tipo']))
	exit;
$tipo=$_REQUEST['tipo'];

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'common.css',
	'css/container.css',
	'button.css',
	'table.css',

	// 'css/index.css',
	'theme.css.php',
	'css/dashboard.css'
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

	while ($row=mysql_fetch_assoc($res)) {
		$store_title.=sprintf(" ,<a target='_parent' style='color: inherit;' href='customers_pending_orders.php?store=%d'>%s</a>",$row['Store Key'],$row['Store Code']);
	}
	$store_title=preg_replace('/^\s*\,/','',$store_title);
	$smarty->assign('store_title',$store_title);
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
	$js_files[]='js/splinter_pending_orders.js';
	$template='splinter_pending_orders.tpl';

	if (count($user->stores)==0) {
		return;
	}

	$store_keys=join(',',$user->stores);
	$store_title='';

	$sql=sprintf("select `Store Key`,`Store Code` from `Store Dimension` where `Store Key` in (%s) ",$store_keys);
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {
		$store_title.=sprintf(" ,<a target='_parent' style='color: inherit;' href='customers_pending_orders.php?store=%d'>%s</a>",$row['Store Key'],$row['Store Code']);
	}
	$store_title=preg_replace('/^\s*\,/','',$store_title);
	$smarty->assign('store_title',$store_title);


	$number_pending_orders=0;
	$elements_number=array('InProcessbyCustomer'=>0,'InProcess'=>0,'SubmittedbyCustomer'=>0,'InWarehouse'=>0,'Packed'=>0);
	$sql=sprintf("select count(*) as num,`Order Current Dispatch State` from  `Order Dimension` where  `Order Current Dispatch State` not in ('Dispatched','Unknown','Packing','Cancelled','Suspended','')  and `Order Store Key` in (%s)  group by `Order Current Dispatch State` ",
		$store_keys);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_number[preg_replace('/\s/','',$row['Order Current Dispatch State'])]=$row['num'];
		$number_pending_orders+=$row['num'];
	}

	$sql=sprintf("select count(*) as num  from  `Order Dimension` where  `Order Store Key` in (%s)  and `Order Current Dispatch State` in ('Ready to Pick','Picking & Packing','Ready to Ship') ",$store_keys);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_number['InWarehouse']=$row['num'];
	}

	$smarty->assign('elements_number',$elements_number);

	$smarty->assign('number_pending_orders',$number_pending_orders);

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

$currency=$corporation_data['HQ Currency'];


$from=date("Y-m-d H:i:s",strtotime("now -30 days"));
$sql=sprintf("select count(*) as orders, avg(`Order Total Net Amount`*`Order Currency Exchange`) as net from `Order Dimension` where `Order Current Dispatch State`='Dispatched' and `Order Date`>%s ",prepare_mysql($from));
$res=mysql_query($sql);


$average_order_value=_('ND');
$samples=0;

if($row=mysql_fetch_assoc($res)){
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
$smarty->display($template);
?>
