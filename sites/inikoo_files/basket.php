<?php

include_once 'common.php';
include_once 'class.Payment.php';
include_once 'class.Payment_Account.php';
include_once 'class.Payment_Service_Provider.php';

$page_key=$site->get_page_key_from_section('Basket');






if (!isset($page_key) and isset($_REQUEST['id'])) {
	$page_key=$_REQUEST['id'];
}

if (!isset($page_key)) {
	header('Location: index.php?no_page_key');
	exit;
}

$page=new Page($page_key);



if (!$page->id) {
	header('Location: index.php?no_page');
	exit;
}


if ($page->data['Page Site Key']!=$site->id) {
	header('Location: index.php?site_page_not_match');
	//    exit("No site/page not match");
	exit;
}


if (  $order_in_process->id and $order_in_process->data['Order Current Dispatch State']=='Waiting for Payment Confirmation') {
	header('Location: waiting_payment_confirmation.php');
	exit;

}


$template_suffix='';
update_page_key_visit_log($page->id,$user_click_key);

if ($logged_in) {
	$page->customer=$customer;
	$page->order=$order_in_process;
}

$smarty->assign('logged',$logged_in);
$page->site=$site;
$page->user=$user;
$page->logged=$logged_in;
$page->currency=$store->data['Store Currency Code'];
$page->currency_symbol=currency_symbol($store->data['Store Currency Code']);
$page->customer=$customer;


$smarty->assign('title',_('Basket'));
$smarty->assign('store',$store);
$smarty->assign('page',$page);
$smarty->assign('site',$site);

$css_files=array();
$js_files=array();
// CSS JS FILES FOR ALL PAGES
$base_css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'editor/assets/skins/sam/editor.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/container.css',
	'css/inikoo.css',
	'css/edit.css',
	'css/table.css'
);



$base_js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'editor/editor-min.js',
	$yui_path.'menu/menu-min.js',

	'js/common.js',
	'js/edit_common.js',
	'js/country_address_labels.js',
	'js/edit_address.js',
	'js/edit_currency.js',
	'js/edit_delivery_address_common.js',
	'js/edit_billing_address_common.js',
	'js/table_common.js',
	'js/edit_common.js',


);



//$customer->update_orders();
//print_r($customer->data);
//exit;


// Dont put YUI stuff in normal assets pages (except if is inikoo -check out-)
if (  !$site->data['Site Checkout Method']=='Inikoo' and !in_array($page->data['Page Store Section'],array('Registration','Client Section','Checkout','Login','Welcome','Reset','Basket'))) {
	$base_js_files=array();
}

if ($logged_in and $site->data['Site Checkout Method']=='Inikoo') {
	$base_css_files[]='css/order_fields.css';
}


$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Header External File Bridge` where `Page Header Key`=%d",$page->data['Page Header Key']);
$res=mysql_query($sql);
//print $sql;
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$base_css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$base_js_files[]='public_external_file.php?id='.$row['external_file_key'];

}

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Footer External File Bridge` where `Page Footer Key`=%d",$page->data['Page Footer Key']);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$base_css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$base_js_files[]='public_external_file.php?id='.$row['external_file_key'];

}

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Site External File Bridge` where `Site Key`=%d",$site->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$base_css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$base_js_files[]='public_external_file.php?id='.$row['external_file_key'];

}



$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Store External File Bridge` where `Page Key`=%d",$page->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$base_css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$base_js_files[]='public_external_file.php?id='.$row['external_file_key'];
}



if ($page->data['Page Store Content Display Type']=='Source') {

	$smarty->assign('type_content','string');
	$smarty->assign('template_string',$page->data['Page Store Source']);
}
else {

	$smarty->assign('type_content','file');

	$css_files[]='css/'.$page->data['Page Store Content Template Filename'].$template_suffix.'.css';

	if ($page->data['Page Code']=='login') {

		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
			$smarty->assign('template_string','login.chrome.tpl');
			$js_files[]='js/login.chrome.js';
		}else {
			$smarty->assign('template_string','login.tpl');
			$js_files[]='js/login.js';
		}
	}else {

		$smarty->assign('template_string',$page->data['Page Store Content Template Filename'].$template_suffix.'.tpl');
		$js_files[]='js/'.$page->data['Page Store Content Template Filename'].$template_suffix.'.js';
	}
}

//$order_in_process->update_charges();
//$order_in_process->update_discounts();
//$order_in_process->update_no_normal_totals();
//			$order_in_process->update_totals_from_order_transactions();

if (!$logged_in) {
	header('location: login.php');
	exit;
}

$css_files[]='css/order.css';

if ( !$page->order->id) {


	if (  isset($_REQUEST['cancelled'])) {
		$cancelled=true;
	}else {
		$cancelled=false;
	}
	$smarty->assign('cancelled',$cancelled);

	$smarty->assign('template_string','empty_basket.tpl');
	$js_files[]='js/empty_basket.js';

	foreach (array_keys($js_files, "js/basket.js", true) as $key) {
		unset($js_files[$key]);

	}

}else {


	$smarty->assign('referral','');
	$smarty->assign('products_display_type','ordered');










	$smarty->assign('filter0','code');
	$smarty->assign('filter_value0','');
	$filter_menu=array(
		'code'=>array('db_key'=>'code','menu_label'=>'Code starting with  <i>x</i>','label'=>'Code'),
		'family'=>array('db_key'=>'family','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code'),
		'name'=>array('db_key'=>'name','menu_label'=>'Name starting with  <i>x</i>','label'=>'Code')

	);
	$smarty->assign('filter_menu0',$filter_menu);
	$smarty->assign('filter_name0',$filter_menu['code']['label']);


	$paginator_menu=array(10,25,50,100);
	$smarty->assign('paginator_menu0',$paginator_menu);

$order_in_process->apply_payment_from_customer_account();

	$smarty->assign('order',$order_in_process);
	$smarty->assign('customer',$customer);





	$charges_deal_info=$order_in_process->get_no_product_deal_info('Charges');
	if ($charges_deal_info!='') {
		$charges_deal_info='<span style="color:red" title="'.$charges_deal_info.'">*</span> ';
	}
	$smarty->assign('charges_deal_info',$charges_deal_info);
	
	
$insurances=$order_in_process->get_insurances();

$smarty->assign('insurances',$insurances);

$greetings='';
if ($customer->data['Customer Orders']==0) {

	$greetings=_('Hello & welcome').' '.$customer->data['Customer Main Contact Name'];
}elseif ($customer->data['Customer Orders']==1) {
	$greetings=_('Hi').' '.$customer->get_name_for_grettings().' '._('great to see you back!');
}else {


	if ((date('U')-date('U',strtotime($customer->data['Customer Last Order Date'].' +0:00')))>2592000 ) {
		$greetings=_('Hi').' '.$customer->get_name_for_grettings().' '._('for a special customer');

	}else {
		$greetings=_('Welcome back').' <b>'.$customer->get_name_for_grettings().'</b> '._('long time no see');

	}

}

/*
First Vist Hello & Welcome {Mr Big}
Second Visit Hi {Mr Big} great to see you back!
Gold Reward Customer Hello {Mr Big} a special welcome for a Gold Reward Customer!
Lapsed Gold Reward Welcome back {Mr Big}! Long time no see :)
*/

$smarty->assign('greetings',$greetings);

$smarty->assign('distinct_set_currency',($_SESSION['set_currency']!=$order_in_process->data['Order Currency']?0:1));

$smarty->assign('total_in_store_currency',money($order_in_process->data['Order Balance Total Amount'],$order_in_process->data['Order Currency']));




	
}





$last_basket_page_key=$order_in_process->get_last_basket_page();
if (!$last_basket_page_key) {
	$last_basket_page_key=$site->get_page_key_from_section('Front Page Store');
}
$smarty->assign('last_basket_page_key',$last_basket_page_key);






$smarty->assign('css_files',array_merge( $base_css_files,$css_files));
$smarty->assign('js_files',array_merge( $base_js_files,$js_files));

$smarty->display('page.tpl');

?>
