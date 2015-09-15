<?php
/*
 File: login.php

 UI login page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'conf/dns.php';

include_once 'external_libs/Smarty/Smarty.class.php';
$smarty = new Smarty();


$smarty->template_dir = 'templates';
$smarty->compile_dir = 'server_files/smarty/templates_c';
$smarty->cache_dir = 'server_files/smarty/cache';
$smarty->config_dir = 'server_files/smarty/configs';



error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}


$sql = sprintf("select * from `User Dimension` where `User Type`='Administrator'  ");
$result = mysql_query($sql);
if (!$row=mysql_fetch_array($result)) {
	header('Location: first_time.php');
	exit;
}


$sql = sprintf("select * from `Account Dimension`  ");
$result = mysql_query($sql);
if (!$row=mysql_fetch_array($result)) {
	header('Location: first_time.php');
	exit;
}else {
	$account_info=$row;

}


require_once 'common_functions.php';

mysql_set_charset('utf8');
require_once 'conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');


require_once 'common_detect_agent.php';
include_once 'conf/key.php';
include_once 'aes.php';
include_once 'set_locales.php';

//print date("Y-m-d H:i:s",date('U')+300);
$Sk="skstart|".(date('U')+3600)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
$St=AESEncryptCtr($Sk,SKEY, 256);





$smarty->assign('st',$St);



if (defined('LC_MESSAGES'))
	setlocale(LC_MESSAGES, $myconf['lang'].'_'.$myconf['country'].($myconf['encoding']!=''?'.'.$myconf['encoding']:''));
else
	setlocale(LC_ALL, $myconf['lang'].'_'.$myconf['country'].($myconf['encoding']!=''?'.'.$myconf['encoding']:''));

$current_lang=$myconf['lang'];
if (isset($_REQUEST['_lang']) and is_numeric($_REQUEST['_lang'])) {
	$sql="select `Language Key`,kbase.`Language Code` as code ,`Country 2 Alpha Code`  as country_code  from `Language Dimension` where `Language Key`=".$_REQUEST['_lang'];

	$result=mysql_query($sql);
	if ($sql_data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

		setlocale(LC_MESSAGES, $sql_data['code'].'_'.strtoupper($sql_data['country_code']).($myconf['encoding']!=''?'.'.$myconf['encoding']:''));
		$current_lang=$sql_data['code'];
		$lang_id=$sql_data['Language Key'];
	}
}

if (function_exists('bindtextdomain')) {
	bindtextdomain('inikoo', './locale');
	bind_textdomain_codeset('inikoo', $myconf['encoding']);
	textdomain('inikoo');
}


$smarty->assign('error',(isset($_REQUEST['e'])?true:false) );



$smarty->assign('lang_code',$current_lang);







$smarty->display("login.tpl");



?>
