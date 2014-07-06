<?php
if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {

	if (isset($_REQUEST['password'])) {
		print '<!DOCTYPE html><html><body onload="parent.submit_login()">x</body></html>';
		exit;
	}
}

include_once 'common.php';
//print_r($_REQUEST);
if ($_SESSION['logged_in']) {
	$page_key=$site->get_profile_page_key();
}else {
	$page_key=$site->get_login_page_key();
}
//$page_key=$site->get_login_page_key();


$block='login';
if (isset($_REQUEST['forgot_password'])) {
	$block='forgot_password';
}
$smarty->assign('block',$block);








if (!isset($skip_common))include_once 'common.php';

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

if ($page->data['Page State']=='Offline') {



	$site_url=$site->data['Site URL'];
	$url=$_SERVER['REQUEST_URI'];
	$url=preg_replace('/^\//', '', $url);
	$url=preg_replace('/\?.*$/', '', $url);

	$original_url=$url;
	header("Location: http://".$site_url."/404.php?&url=$url&original_url=$original_url");

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




$smarty->assign('type_content','file');

$css_files[]='css/'.$page->data['Page Store Content Template Filename'].$template_suffix.'.css';


//if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
//	$smarty->assign('template_string','login.chrome.tpl');
//	$js_files[]='js/login.chrome.js';
//}else {
	$smarty->assign('template_string','login.tpl');
	$js_files[]='js/login.js';
//}










if ($logged_in) {
	header('location: index.php');
	exit;
}


$Sk="skstart|".(date('U')+300000)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
$St=AESEncryptCtr($Sk,SKEY, 256);
$smarty->assign('St',$St);

if (isset($_REQUEST['logged_out'])) {
	$smarty->assign('logged_out',1);

}

if (isset($_REQUEST['from']) and is_numeric($_REQUEST['from'])) {
	$referral=$_REQUEST['from'];
} else {
	$referral='';
}
$smarty->assign('referral',$referral);
$js_files[]='js/detect_timezone.js';
$js_files[]='js/aes.js';
$js_files[]='js/sha256.js';
$css_files[]='css/inikoo.css';







$smarty->assign('css_files',array_merge( $base_css_files,$css_files));
$smarty->assign('js_files',array_merge( $base_js_files,$js_files));

$smarty->display('page.tpl');

?>
