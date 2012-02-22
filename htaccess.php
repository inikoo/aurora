<?php

/*
 File: customers.php

 UI customers page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/


require_once 'common.php';
include_once 'class.Page.php';
include_once 'class.Site.php';

if (!isset($_REQUEST['page_key'])) {
	exit;
}else
	$page_key=$_REQUEST['page_key'];
if (!isset($_REQUEST['redirection_key'])) {
	exit;
}else
	$redirection_key=$_REQUEST['redirection_key'];


$page=new Page($page_key);
if (!$page->id) {
	exit;
}
if ($page->data['Page Type']!='Store') {
	exit;
}

$redirection_data=$page->get_redirect_data($redirection_key);
if (!$redirection_data) {
	return;
}
$host=$redirection_data['Source Host'];
$path=$redirection_data['Source Path'];
$site=new Site($page->data['Page Site Key']);


$htaccess=$site->get_redirections_htaccess($host,$path);
//header("Content-Type: text/plain");
 // header('Content-Disposition: attachment; filename=.htaccess');

print $htaccess;



?>
