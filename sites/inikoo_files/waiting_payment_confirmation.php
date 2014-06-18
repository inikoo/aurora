<?php
include_once 'class.DB_Table.php';



include_once 'class.Payment.php';
include_once 'class.Payment_Account.php';
include_once 'class.Payment_Service_Provider.php';
include_once 'common.php';

$page_key=$site->get_page_key_from_section('Payment Limbo');



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


$smarty->assign('title',$page->data['Page Title']);
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
	$yui_path.'assets/skins/sam/autocomplete.css'

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
	$yui_path.'calendar/calendar-min.js',
	$yui_path.'uploader/uploader-min.js',
	'external_libs/ampie/ampie/swfobject.js',
	'js/common.js',
	'js/edit_common.js',

	// 'js/page.js'
);


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



	if(isset($_REQUEST['id'])){
	
				$smarty->assign('redirect','thanks.php?id='.$_REQUEST['id']);

	
	}else{
	
					$smarty->assign('redirect','index.php');

	}



$pending_payments=count($order_in_process->get_payment_keys('Pending'));

if ($pending_payments==0) {

	if(isset($_REQUEST['id'])){
			header('Location: thanks.php?id='.$_REQUEST['id']);
	exit;
		
	}


	if(!$order_in_process->id){
		header('Location: index.php');
	exit;
		

}


	if (  count($order_in_process->get_payment_keys('Completed'))) {

		$order_in_process->checkout_submit_payment();
	}else {

		$order_in_process->checkout_cancel_payment();
	}
}




if ($order_in_process->data['Order Current Dispatch State']=='In Process by Customer') {

	header('Location: checkout.php');
	exit;

}elseif ($order_in_process->data['Order Current Dispatch State']=='Submitted by Customer') {

	header('Location: thanks.php?id='.$order_in_process->id);
	exit;

}elseif($order_in_process->data['Order Current Dispatch State']!='Waiting for Payment Confirmation') {
	header('Location: order.php?id='.$order_in_process->id+'&info='.$order_in_process->data['Order Current Dispatch State']);
	exit;

}

array_unshift($css_files,'css/table.css');
array_unshift($css_files,'css/edit.css');
array_unshift($css_files,'css/inikoo.css');

$smarty->assign('order',$order_in_process);
$smarty->assign('customer',$customer);










$smarty->assign('css_files',array_merge( $base_css_files,$css_files));
$smarty->assign('js_files',array_merge( $base_js_files,$js_files));

$smarty->display('page.tpl');

?>



