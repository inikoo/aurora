<?php
/*
 File: login.php

 UI login page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>
 Refurbished: 15 November 2015 at 02:57:40 GMT Sheffield UK

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

error_reporting(E_ALL);
date_default_timezone_set('UTC');


include_once 'utils/i18n.php';

include_once 'conf/dns.php';
include_once 'class.Account.php';

$db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user,$dns_pwd ,array(\PDO::MYSQL_ATTR_INIT_COMMAND =>"SET time_zone = '+0:00';"));
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$account=new Account($db);
set_locale($account->get('Account Locale').'.UTF-8');


include_once 'external_libs/Smarty/Smarty.class.php';
$smarty = new Smarty();


$smarty->template_dir = 'templates';
$smarty->compile_dir = 'server_files/smarty/templates_c';
$smarty->cache_dir = 'server_files/smarty/cache';
$smarty->config_dir = 'server_files/smarty/configs';


include_once 'conf/key.php';

require_once 'utils/general_functions.php';
require_once 'utils/detect_agent.php';
require_once "utils/aes.php";


$Sk="skstart|".(date('U')+3600)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
$St=AESEncryptCtr($Sk,SKEY, 256);
$smarty->assign('st',$St);

$smarty->assign('error',(isset($_REQUEST['e'])?true:false) );
$smarty->display("login.tpl");

?>
