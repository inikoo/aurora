<?php
/*
 File: login.php 

 UI login page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
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
		 $yui_path.'xreset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'css/common.css',
		 'css/login.css'
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
  $sql="select kbase.`Language Code` as code ,`Country 2 Alpha Code`  as country_code  from `Language Dimension` where `Language Key`=".$_REQUEST['_lang'];
  
  $result=mysql_query($sql);
  if($sql_data=mysql_fetch_array($result, MYSQL_ASSOC)   ){

    setlocale(LC_MESSAGES, $sql_data['code'].'_'.strtoupper($sql_data['country_code']).($myconf['encoding']!=''?'.'.$myconf['encoding']:''));
    $current_lang=$sql_data['code'];
  }
 }

if (function_exists('bindtextdomain')){
	bindtextdomain('inikoo', './locale');	
	bind_textdomain_codeset('inikoo', $myconf['encoding']);
	textdomain('inikoo');
}
$smarty->assign('theme', $myconf['theme']);
$smarty->assign('title', _('Authentication'));
$smarty->assign('welcome', _('Welcome'));
$smarty->assign('user', _('User'));
$smarty->assign('password', _('Password'));
$smarty->assign('log_in', _('Log in'));

$smarty->assign('type', "supplier");

/* other_langs=array(); */
/* $sql="select kbase.`Language Key` as id,`Language Original Name` as original_name, `Language Code` as code    from `Language Dimension`"; */

/*  $result=mysql_query($sql); */
/*        while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){ */

/*   if($row['code']==$current_lang){ */
/*     $smarty->assign('lang_id', $row['id']); */
/*     $smarty->assign('lang_code', $row['code']); */
/*   }else */
/*     $other_langs[$row['id']]=$row['original_name']; */
/* } */

/* mysql_free_result($result); */


/* $smarty->assign('other_langs', $other_langs); */
//Check for superuser
$sql = sprintf("select * from `User Dimension`");
$result = mysql_query($sql);


if(!$row=mysql_fetch_array($result)){
	$template="config.tpl ";
}
else
	$template="login_supplier.tpl";
$smarty->display($template);

exit();

?>
