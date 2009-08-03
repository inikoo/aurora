<?php
/*
 File: login.php 

 UI login page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('app_files/key.php');
include_once('aes.php');

//print date("d-m-Y H:i:s",date('U')+300);
$Sk="skstart|".(date('U')+300)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
$St=AESEncryptCtr($Sk,SKEY, 256);

//print AESDecryptCtr($St,SKEY,256);
//exit;
$css_files=array(
		 $yui_path.'xreset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'login.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',
		'js/sha256.js.php',
		'js/aes.js',
		'js/login.js.php'
		);




$smarty->assign('st',$St);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


setlocale(LC_MESSAGES, $myconf['lang'].'_'.$myconf['country'].($myconf['encoding']!=''?'.'.$myconf['encoding']:''));
$current_lang=$myconf['lang'];
if(isset($_REQUEST['_lang']) and is_numeric($_REQUEST['_lang'])){
  $sql="select `Language Code` as code ,`Country 2 Alpha Code`  as country_code  from `Language Dimension` where `Language Key`=".$_REQUEST['_lang'];
  
  $result=mysql_query($sql);
  if($sql_data=mysql_fetch_array($result, MYSQL_ASSOC)   ){

    setlocale(LC_MESSAGES, $sql_data['code'].'_'.strtoupper($sql_data['country_code']).($myconf['encoding']!=''?'.'.$myconf['encoding']:''));
    $current_lang=$sql_data['code'];
  }
 }


bindtextdomain('kaktus', './locale');
bind_textdomain_codeset('kaktus', $myconf['encoding']);
textdomain('kaktus');

$smarty->assign('theme', $myconf['theme']);
$smarty->assign('title', _('Authentication'));
$smarty->assign('welcome', _('Welcome'));
$smarty->assign('user', _('User'));
$smarty->assign('password', _('Password'));
$smarty->assign('log_in', _('Log in'));


$other_langs=array();
$sql="select `Language Key` as id,`Language Original Name` as original_name, `Language Code` as code    from `Language Dimension`";



 $result=mysql_query($sql);
       while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){

  if($row['code']==$current_lang){
    $smarty->assign('lang_id', $row['id']);
    $smarty->assign('lang_code', $row['code']);
  }else
    $other_langs[$row['id']]=$row['original_name'];
}




$smarty->assign('other_langs', $other_langs);

$smarty->display('login.tpl');

exit();

?>
