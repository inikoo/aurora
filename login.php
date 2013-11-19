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
		 'css/common.css',
		 'css/button.css',
		 'css/login.css',
		
		 'public_theme.css.php',
		  'css/snow.css',
		 'http://fonts.googleapis.com/css?family=Spirax',
			 'http://fonts.googleapis.com/css?family=Mountains+of+Christmas'
	 

		 
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


$sql=sprintf("select `Inikoo Version`,`Account Code`,`Account Menu Label`,`Account Name`,`Inikoo Public URL`,`Account Country 2 Alpha Code`,`Account Country Code`,`Account Currency`,`Currency Symbol`,`Short Message` from  `Account Dimension` left join kbase.`Currency Dimension` CD on (CD.`Currency Code`=`Account Currency`) ");
//print $sql;

$res=mysql_query($sql);

if ($row=mysql_fetch_array($res)) {

	$smarty->assign('inikoo_version',$row['Inikoo Version']);
	$smarty->assign('top_navigation_message',$row['Short Message']);
	$smarty->assign('account_name',$row['Account Name']);

}


$default_message="At Christmas, all roads lead home.";

$messages=array(
'2013-11-19'=>"What I don't like about office Christmas parties is looking for a job the next day.",

'2013-11-20'=>'I hate Santa! He reminds me of the men who come for 10 minutes, do their thing and disappear for the remaining 364 days!',
'2013-11-21'=>"Dearest Santa I promise I will never bitch about anyone please get me....ah nah, forget it, I'll get it for myself!",
'2013-11-22'=>'Dearest God, this Christmas I planned on going green. So please get the point and send my lots of cash this Christmas. Thank you!',
'2013-11-23'=>"Put on a Santa suit and open a mall kiosk that sells reindeer jerky and Easter Bunny filets.",
'2013-11-24'=>'Christmas gifts are lovely, only when you are not the one paying for them though! ',
'2013-11-25'=>'Christmas shopping is awesome, only when it is for you.',
'2013-11-26'=>'Is it legal for a obese man to ride on reindeer’s? Is RSPCA listening?',
'2013-11-27'=>"Decorate your yard to look like a sleigh and eight tiny reindeer crashed and burned. Walk back and forth along the street muttering, \"Oh, the humanity\".",
'2013-11-28'=>"Hasn't Santa ever heard of Diet Coke and the treadmill?",
'2013-11-29'=>"You are all that I want this Christmas! No actually, I wouldn't mind a car, diamonds and some cash too!",
'2013-11-30'=>"Christmas isn't a season. It's a feeling.",
'2013-12-01'=>"The perfect Christmas tree? All Christmas trees are perfect!",
'2013-12-02'=>"At Christmas, all roads lead home.",
'2013-12-03'=>"Christmas is weird. What other time of the year do you sit in front of a dead tree and eat candy out of your socks?",
'2013-12-04'=>"This christmas i've decided to put the mistletoe in my back pocket, so all the people i don't like can kiss my ass.",
'2013-12-05'=>"Sell jars of water, advertising them as Frosty the Snowman urns.",
'2013-12-06'=>"Stand on a street corner selling dime bags of mistletoe.",
'2013-12-07'=>"Randomly replace one bulb in your neighbor's lights so they no longer work. Repeat this every day until Christmas.",
'2013-12-08'=>"What I don't like about office Christmas parties is looking for a job the next day."

);


$date=date('Y-m-d');

if(array_key_exists($date,$messages)){

$message=$messages[$date];
}else{

$message=$default_message;
}

	$smarty->assign('message',$message);


$smarty->display("login.tpl");

exit();

?>
