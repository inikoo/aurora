<?php
/*
 File: login.php 

 UI login page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('app_files/db/dns.php');

include_once('external_libs/Smarty/Smarty.class.php');
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
if(!$row=mysql_fetch_array($result)){
  header('Location: first_time.php');
        exit;
}


require_once 'common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once 'conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');


require_once 'common_detect_agent.php';
include_once('app_files/key.php');
include_once('aes.php');
include_once('set_locales.php');

//print date("Y-m-d H:i:s",date('U')+300);
$Sk="skstart|".(date('U')+3600)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
$St=AESEncryptCtr($Sk,SKEY, 256);

//print AESDecryptCtr($St,SKEY,256);
//print($St);
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'button.css',
		// 'login.css',
		 'css/theme_1.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',
		'sha256.js.php',
		'js/aes.js',
		'login.js.php',
//		'config.js.php?store_key=1'
		);




$smarty->assign('st',$St);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


if (defined('LC_MESSAGES'))
	setlocale(LC_MESSAGES, $myconf['lang'].'_'.$myconf['country'].($myconf['encoding']!=''?'.'.$myconf['encoding']:''));
else
	setlocale(LC_ALL, $myconf['lang'].'_'.$myconf['country'].($myconf['encoding']!=''?'.'.$myconf['encoding']:''));
	
$current_lang=$myconf['lang'];
if(isset($_REQUEST['_lang']) and is_numeric($_REQUEST['_lang'])){
  $sql="select `Language Key`,kbase.`Language Code` as code ,`Country 2 Alpha Code`  as country_code  from `Language Dimension` where `Language Key`=".$_REQUEST['_lang'];
  
  $result=mysql_query($sql);
  if($sql_data=mysql_fetch_array($result, MYSQL_ASSOC)   ){

    setlocale(LC_MESSAGES, $sql_data['code'].'_'.strtoupper($sql_data['country_code']).($myconf['encoding']!=''?'.'.$myconf['encoding']:''));
    $current_lang=$sql_data['code'];
  $lang_id=$sql_data['Language Key'];
  }
 }

if (function_exists('bindtextdomain')){
	bindtextdomain('inikoo', './locale');	
	bind_textdomain_codeset('inikoo', $myconf['encoding']);
	textdomain('inikoo');
}


if (isset($_REQUEST['log_as']) and $_REQUEST['log_as']=='supplier')
    $log_as="supplier";
else
    $log_as="staff";

$smarty->assign('login_type',$log_as);
$smarty->assign('lang_id',0);
$smarty->assign('lang_code',$current_lang);


$smarty->display("login.tpl");

exit();

?>
