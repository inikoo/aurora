<?php


require_once 'common.php';




include_once 'class.Customer.php';
include_once 'class.Store.php';
include_once 'class.Page.php';
include_once 'class.Site.php';
include_once 'class.DummyCustomer.php';


if (!isset($_REQUEST['id'])  or  !is_numeric($_REQUEST['id']) ) {
	header('Location: index.php');
	exit;
}


$page_key=$_REQUEST['id'];
$page=new Page($page_key);

$site=new Site($page->data['Page Site Key']);

putenv('LC_ALL='.$site->data['Site Locale']);
setlocale(LC_ALL,$site->data['Site Locale']);

// Specify location of translation tables
bindtextdomain("inikoo_sites", "./locale_sites");

// Choose domain
textdomain("inikoo_sites");


$store=new Store($page->data['Page Store Key']);

$_logged=1;
if (isset($_REQUEST['logged'])   ) {
	$_logged=$_REQUEST['logged'];
}

if ($_logged) {
	$logged=1;
} else {
	$logged=0;
}
$smarty->assign('logged',$logged);


$customer=new Dummy_Customer();
$page->customer=$customer;
$page->site=$site;
$page->user=$user;
$page->logged=$logged;
$page->currency=$store->data['Store Currency Code'];




if (isset($_REQUEST['update_heights'])  and  $_REQUEST['update_heights']) {
	$smarty->assign('update_heights',1);
} else {
	$smarty->assign('update_heights',0);
}


if (isset($_REQUEST['take_snapshot']) and $_REQUEST['take_snapshot']  ) {
	$smarty->assign('take_snapshot',1);
} else {
	$smarty->assign('take_snapshot',0);
}


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	'button.css',
);

$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	'js/page_store.js'
);




$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Header External File Bridge` where `Page Header Key`=%d",$page->data['Page Header Key']);
$res=mysql_query($sql);
//print $sql;
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$js_files[]='public_external_file.php?id='.$row['external_file_key'];

}

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Footer External File Bridge` where `Page Footer Key`=%d",$page->data['Page Footer Key']);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$js_files[]='public_external_file.php?id='.$row['external_file_key'];

}

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Site External File Bridge` where `Site Key`=%d",$site->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$js_files[]='public_external_file.php?id='.$row['external_file_key'];

}


$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Store External File Bridge` where `Page Key`=%d",$page->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$js_files[]='public_external_file.php?id='.$row['external_file_key'];

}


if ($page->data['Page Store Content Display Type']=='Source') {
	$smarty->assign('type_content','string');
	$smarty->assign('template_string',$page->data['Page Store Source']);

} else {
	$smarty->assign('type_content','file');
	$smarty->assign('template_string',$page->data['Page Store Content Template Filename'].'.tpl');
	$css_files[]='css/'.$page->data['Page Store Content Template Filename'].'.css';
	$js_files[]='js/'.$page->data['Page Store Content Template Filename'].'.js';
}



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('title',_('Preview').' '.$page->data['Page Title']);
$smarty->assign('store',$store);
$smarty->assign('page',$page);
$smarty->assign('site',$site);




$smarty->display('page_store.tpl');
?>
