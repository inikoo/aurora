<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

include_once 'common.php';
include_once 'class.Store.php';
include_once 'class.Deal.php';
include_once 'class.DealCampaign.php';


if (!$user->can_view('stores') or count($user->stores)==0 ) {

	header('Location: index.php');
	exit;
}



if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$deal=new Deal($_REQUEST['id']);
	$store_id=$deal->data['Deal Store Key'];
	if (!$deal->id) {
		header('Location: index.php?deal_not_found');
		exit;
	}

} else {
	header('Location: index.php?deal_no_arg');
	exit;

}

if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {

	header('Location: index.php?error_deal_store');
	exit;
}

if ( isset($_REQUEST['referrer']))
	$referrer= $_REQUEST['referrer'];
else {
	$referrer='marketing';
}

$smarty->assign('referrer',$referrer);



$modify=$user->can_edit('stores');
$smarty->assign('modify',$modify);



$store=new Store($store_id);
$campaign=new DealCampaign($deal->data['Deal Campaign Key']);



$smarty->assign('deal',$deal);

$smarty->assign('store',$store);
$smarty->assign('campaign',$campaign);

$smarty->assign('store_key',$store->id);


if (!$deal->is_voucher() and $_SESSION['state']['deal']['view']=='vouchers') {
	$_SESSION['state']['deal']['view']='details';
}

$smarty->assign('block_view',$_SESSION['state']['deal']['view']);

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
	'css/edit.css',
	'theme.css.php',
	'css/deal.css'

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
	'js/php.default.min.js',
	'js/common.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/search.js',
	'js/deals_common.js',
	'js/deal.js',

);


if ($deal->get('Deal Terms Type')=='Order Interval') {




	$js_files[]=$yui_path.'editor/editor-min.js';
	$js_files[]=$yui_path.'slider/slider-min.js';
	$js_files[]=$yui_path.'colorpicker/colorpicker-min.js';

	$js_files[]='js/editor_image_uploader.js';
	$js_files[]='js/rgbcolor.js';
	$js_files[]='js/build_email.js';



	$color_schemes=array();
	$sql=sprintf("select * from `Email Template Color Scheme Dimension` where `Store Key`=%d  limit 100",$store->id);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$color_scheme=array();
		foreach ($row as $key=>$value) {
			$color_scheme[preg_replace('/ /','_',$key)]=$value;
		}
		$color_schemes[]=$color_scheme;
	}
	$smarty->assign('color_schemes',$color_schemes);



	$smarty->assign('filter10','name');
	$smarty->assign('filter_value10','');
	$filter_menu=array(
		'name'=>array('db_key'=>'name','menu_label'=>_('Name like <i>x</i>'),'label'=>_('Name')),
	);
	$smarty->assign('filter_menu10',$filter_menu);
	$smarty->assign('filter_name10',$filter_menu['name']['label']);
	$paginator_menu=array(10,25,50,100,500);
	$smarty->assign('paginator_menu10',$paginator_menu);


	$smarty->assign('filter11','name');
	$smarty->assign('filter_value11','');
	$filter_menu=array(
		'name'=>array('db_key'=>'name','menu_label'=>_('Name like <i>x</i>'),'label'=>_('Name')),
	);
	$smarty->assign('filter_menu11',$filter_menu);
	$smarty->assign('filter_name11',$filter_menu['name']['label']);
	$paginator_menu=array(10,25,50,100,500);
	$smarty->assign('paginator_menu11',$paginator_menu);


}



$smarty->assign('deal_component_status_elements',$_SESSION['state']['deal']['components']['elements']);

$smarty->assign('parent','marketing');
$smarty->assign('title',$deal->data['Deal Name']);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


;

//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');


$tipo_filter=$_SESSION['state']['deal']['orders']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['deal']['orders']['f_value']);
$filter_menu=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Order Number'),'label'=>_('Number')),
	'customer_name'=>array('db_key'=>'customer_name','menu_label'=>_('Customer Name'),'label'=>_('Customer')),


);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$tipo_filter=$_SESSION['state']['deal']['customers']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['deal']['customers']['f_value']);
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Customer Name'),'label'=>_('Name')),
	'country'=>array('db_key'=>'country','menu_label'=>_('Customer Country'),'label'=>_('Country')),


);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);


$tipo_filter=$_SESSION['state']['deal']['components']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['deal']['components']['f_value']);
$filter_menu=array(
	'allowances'=>array('db_key'=>'name','menu_label'=>_('Allowances'),'label'=>_('Allowances')),

);
$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);


$tipo_filter=$_SESSION['state']['deal']['orders_with_voucher']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['deal']['orders_with_voucher']['f_value']);
$filter_menu=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Order Number'),'label'=>_('Number')),
	'customer_name'=>array('db_key'=>'customer_name','menu_label'=>_('Customer Name'),'label'=>_('Customer')),


);
$smarty->assign('filter_menu3',$filter_menu);
$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);



$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('paginator_menu1',$paginator_menu);
$smarty->assign('paginator_menu2',$paginator_menu);
$smarty->assign('paginator_menu3',$paginator_menu);


$session_data=base64_encode(json_encode(array(
			'label'=>array(
				'Number'=>_('Number'),
				'Customer'=>_('Customer'),
				'Date'=>_('Date'),
				'Id'=>_('Id'),
				'Location'=>_('Location'),
				'Orders'=>_('Orders'),
				'Customers'=>_('Customers'),
'Allowance'=>_('Allowance'),
				'Name'=>_('Name'),
				'Terms'=>_('Terms'),
				'Description'=>_('Allowance Label'),
				'Interval'=>_('Interval'),
				'Offer'=>_('Offer'),
				'Total'=>_('Total'),
				'State'=>_('State'),
				'From'=>_('From'),
				'To'=>_('To'),

				'Page'=>_('Page'),
				'of'=>_('of')
			),
			'state'=>array(
				'deal'=>$_SESSION['state']['deal']
			)
		)));
$smarty->assign('session_data',$session_data);



$smarty->display('deal.tpl');
?>
