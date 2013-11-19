<?php
/*
 File: part.php

 UI part page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/
include_once 'common.php';

include_once 'class.Customer.php';
include_once 'class.Store.php';
include_once 'class.Page.php';
include_once 'class.Site.php';
include_once 'class.DummyCustomer.php';



if (!isset($_REQUEST['id'])  or  !is_numeric($_REQUEST['id']) ) {
	header('Location: index.php?no_id');
	exit;
}


$page_key=$_REQUEST['id'];
$page=new Page($page_key);

$site=new Site($page->data['Page Site Key']);
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


if (isset($_REQUEST['header']) and !$_REQUEST['header']) {
	$show_header=false;
} else {
	$show_header=true;
}
$smarty->assign('show_header',$show_header);


if (isset($_REQUEST['referral'])   ) {
	$smarty->assign('referral',urldecode($_REQUEST['referral']));
}

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
	'css/button.css',
);


$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	'js/page_preview.js'
);




$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('title',_('Preview').' '.$page->data['Page Title']);
$smarty->assign('store',$store);
$smarty->assign('page',$page);
$smarty->assign('site',$site);





$order=$_SESSION['state']['site']['pages']['order'];
if ($order=='code') {
	$order='`Page Code`';
	$order_label=_('Code');
} else if ($order=='url') {
		$order='`Page URL`';
		$order_label=_('URL');
	} else if ($order=='title') {
		$order='`Page Store Title`';
		$order_label=_('Title');
	} else {
	$order='`Page Code`';
	$order_label=_('Code');
}

$_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Page Key` as id , `Page Store Title` as name from `Page Store Dimension`   where  `Page Site Key`=%d  and %s < %s  order by %s desc  limit 1",
	$site->id,
	$order,
	prepare_mysql($page->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$prev['link']='page_preview.php?id='.$row['id'];
	$prev['title']=$row['name'];
	$smarty->assign('prev',$prev);

}
mysql_free_result($result);
$sql=sprintf(" select `Page Key` as id , `Page Store Title` as name from `Page Store Dimension`    where  `Page Site Key`=%d  and  %s>%s  order by %s   ",
	$site->id,
	$order,
	prepare_mysql($page->get($_order)),
	$order
);

$result=mysql_query($sql);

if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$next['link']='page_preview.php?id='.$row['id'];
	$next['title']=$row['name'];
	$smarty->assign('next',$next);
}
mysql_free_result($result);


$smarty->assign('parent_url','site.php?id='.$site->id);
$parent_title=$site->data['Site Name'].' '._('Pages').' ('.$order_label.')';
$smarty->assign('parent_title',$parent_title);

$smarty->display('page_preview.tpl');
?>
