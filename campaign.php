<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2013, Inikoo

 Version 2.0
*/

include_once 'common.php';
include_once 'class.Store.php';
include_once 'class.DealCampaign.php';

if (!$user->can_view('stores') or count($user->stores)==0 ) {

	header('Location: index.php');
	exit;
}



if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$campaign=new DealCampaign($_REQUEST['id']);
	$store_key=$campaign->data['Deal Campaign Store Key'];
	if (!$campaign->id) {
		header('Location: index.php?deal_not_found');
		exit;
	}

} else {
	header('Location: index.php?deal_no_arg');
	exit;

}

if (!($user->can_view('stores') and in_array($store_key,$user->stores)   ) ) {

	header('Location: index.php?error_deal_store');
	exit;
}

$modify=$user->can_edit('stores');
$smarty->assign('modify',$modify);

if ( isset($_REQUEST['referrer']))
		$referrer= $_REQUEST['referrer'];
	else {
		$referrer='marketing';
	}

$smarty->assign('referrer',$referrer);


$store=new Store($store_key);



$smarty->assign('campaign',$campaign);

$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

$smarty->assign('block_view',$_SESSION['state']['campaign']['view']);

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/button.css',
	'css/container.css',
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
	'js/edit_common.js',
	'js/search.js',
	'campaign.js.php',

);



$smarty->assign('parent','marketing');
$smarty->assign('title',_('Campaign').': '.$campaign->data['Deal Campaign Name']);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');


$tipo_filter=$_SESSION['state']['campaign']['orders']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['campaign']['orders']['f_value']);
$filter_menu=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Order Number'),'label'=>_('Number')),
	'customer_name'=>array('db_key'=>'customer_name','menu_label'=>_('Customer Name'),'label'=>_('Customer')),


);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$tipo_filter=$_SESSION['state']['campaign']['customers']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['campaign']['customers']['f_value']);
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Customer Name'),'label'=>_('Name')),
	'country'=>array('db_key'=>'country','menu_label'=>_('Customer Country'),'label'=>_('Country')),


);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);


$tipo_filter=$_SESSION['state']['campaign']['offers']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['campaign']['offers']['f_value']);
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Name'),'label'=>_('Name')),
	'code'=>array('db_key'=>'name','menu_label'=>_('Code'),'label'=>_('Code')),

);
$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);



$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('paginator_menu1',$paginator_menu);
$smarty->assign('paginator_menu2',$paginator_menu);



$smarty->display('campaign.tpl');
?>
