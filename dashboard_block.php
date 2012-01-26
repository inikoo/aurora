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




switch ($tipo) {
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

if(count($user->stores)==0){
	return;
}

$store_keys=join(',',$user->stores);
$number_pending_orders=0;
$elements_number=array('InProcessbyCustomer'=>0,'InProcess'=>0,'SubmittedbyCustomer'=>0,'InWarehouse'=>0,'Packed'=>0);
$sql=sprintf("select count(*) as num,`Order Current Dispatch State` from  `Order Dimension` where  `Order Current Dispatch State` not in ('Dispatched','Unknown','Packing','Cancelled','Suspended','')  and `Order Store Key` in (%s)  group by `Order Current Dispatch State` ",$store_keys);
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
