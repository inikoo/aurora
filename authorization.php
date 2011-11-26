<?php
/*

 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

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


require_once 'common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once 'conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');


require_once 'common_detect_agent.php';
include_once('app_files/key.php');
include_once('aes.php');
include_once('set_locales.php');

include_once('app_files/key.php');

include_once('conf/timezone.php');
include_once('class.Auth.php');
require_once "class.Session.php";

include_once('class.User.php');
require_once 'conf/conf.php';

$max_session_time=$myconf['max_session_time'];
$max_session_time_in_milliseconds=1000*$max_session_time;
$session = new Session($max_session_time,1,100);

$auth=new Auth(IKEY,SKEY);
$handle = (array_key_exists('_login_', $_REQUEST)) ? $_REQUEST['_login_'] : false;
$sk = (array_key_exists('ep', $_REQUEST)) ? $_REQUEST['ep'] : false;


if (isset($_REQUEST['user_type']) and $_REQUEST['user_type']=='supplier')
    $user_type="supplier";
else
    $user_type="staff";


if (!$sk and array_key_exists('mk', $_REQUEST)    ) {
    $auth->authenticate_from_masterkey($_REQUEST['mk']);
}
elseif($handle) {

    $auth->authenticate($handle,$sk,$user_type,0);
}

if ($auth->is_authenticated()) {



    $_SESSION['logged_in']=true;
    $_SESSION['logged_in_page']=0;
    $_SESSION['user_key']=$auth->get_user_key();
    $user=new User($_SESSION['user_key']);
    $_SESSION['text_locale']=$user->data['User Preferred Locale'];
    header('Location: index.php');
    exit;


} else {


    $target = $_SERVER['PHP_SELF'];
    if (!preg_match('/(js|js\.php)$/',$target)){
         header('Location: login.php?log_as='.$user_type);
        exit;
        }
}


?>