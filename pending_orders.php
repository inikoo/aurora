<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 19 June 2014 10:53:50 GMT+1, Sheffield UK	

 Copyright (c) 2014, Inikoo
 
 Version 2.0
*/
include_once 'common.php';
include_once 'class.Store.php';


if (!$user->can_view('orders')) {
	header('Location: index.php');
	exit();
}


if (!($user->can_view('stores')  ) ) {

	header('Location: index.php');
	exit;
}




//====================
$smarty->assign('store_key','');

$currency=$corporate_currency;
$currency_symbol=$corporate_currency_symbol;


$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');



$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/container.css',
	'css/edit.css',
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
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'js/edit_common.js',
	
	'js/customers_common.js',
	'js/export_common.js',
		'js/common_order_not_dispatched.js',

	'pending_orders.js.php'

);




$smarty->assign('parent','orders');
$smarty->assign('title', _('Pending Orders'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$tipo_filter1=$_SESSION['state']['stores']['pending_orders']['f_field'];
$smarty->assign('filter1',$tipo_filter1);
$smarty->assign('filter_value1',($_SESSION['state']['stores']['pending_orders']['f_value']));
$filter_menu1=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Order Number starting with <i>x</i>'),'label'=>_('Order Number')),
);
$smarty->assign('filter_menu1',$filter_menu1);
$smarty->assign('filter_name1',$filter_menu1[$tipo_filter1]['label']);
$paginator_menu1=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu1);




if(isset($_REQUEST['show']) and  array_key_exists($_REQUEST['show'],$_SESSION['state']['stores']['pending_orders']['elements']) ){
foreach($_SESSION['state']['stores']['pending_orders']['elements'] as $key=>$value){
	$_SESSION['state']['stores']['pending_orders']['elements'][$key]=0;
}
$_SESSION['state']['stores']['pending_orders']['elements'][$_REQUEST['show']]=1;
}



$smarty->assign('elements',$_SESSION['state']['stores']['pending_orders']['elements']);

$smarty->assign('block_view','pending_orders');
$smarty->display('pending_orders.tpl');

?>
