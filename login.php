<?php
/*
 File: login.php

 UI login page

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Refurbished: 15 November 2015 at 02:57:40 GMT Sheffield UK

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
require_once 'vendor/autoload.php';
/** @var string $dns_host */
/** @var string $dns_port */
/** @var string $dns_db */
/** @var string $dns_user */
/** @var string $dns_pwd */
error_reporting(E_ALL ^ E_DEPRECATED);


define("_DEVEL", isset($_SERVER['devel']));


include_once 'keyring/dns.php';
require_once 'keyring/au_deploy_conf.php';
include_once 'keyring/key.php';
include_once 'utils/minify_html_output.php';


require_once 'utils/system_functions.php';
date_default_timezone_set('UTC');

include_once 'utils/i18n.php';
include_once 'class.Account.php';


$smarty               = new Smarty();
$smarty->caching_type = 'redis';
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');

$smarty->assign('_DEVEL', _DEVEL);


$smarty->registerFilter("output", "minify_html_output");


$smarty->assign('is_devel', ENVIRONMENT == 'DEVEL');

if (defined('SENTRY_DNS_AUJS')) {
    $smarty->assign('sentry_js', SENTRY_DNS_AUJS);

}


$db = new PDO("mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$account = new Account();


if ($account->id and $account->get('Account State') == 'Active') {

    set_locale($account->get('Account Locale').'.UTF-8');

    require_once 'utils/general_functions.php';
    require_once 'utils/network_functions.php';
    require_once "utils/aes.php";


    $Sk = "skstart|".(gmdate('U') + 30)."|".ip_from_cloudfare()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
    $St = AESEncryptCtr($Sk, SKEY, 256);
    $smarty->assign('st', $St);
    $smarty->assign('error', isset($_REQUEST['e']));
    $smarty->assign('url', ($_REQUEST['ref'] ?? ''));
    $smarty->assign('account_code', strtolower($account->get('Code')));


    if (file_exists("art/bg/".strtolower($account->get('Code')).".jpg")) {
        $bg_image = "/art/bg/".strtolower($account->get('Code')).".jpg";

    } else {
        $bg_image = "/art/bg/".strtolower($account->get('Account Country Code')).".jpg";

    }


    $smarty->assign('bg_image', $bg_image);

    $status_page = '';
    if (defined('STATUS_PAGE')) {
        $status_page = STATUS_PAGE;
    }
    $smarty->assign('status_page', $status_page);

    $status_page_widget = '';
    if (defined('STATUS_PAGE_WIDGET')) {
        $status_page_widget = STATUS_PAGE_WIDGET;
    }
    $smarty->assign('status_page_widget', $status_page_widget);


    $smarty->display("login.tpl");


} else {
    $smarty->assign('error', isset($_REQUEST['e']));
    $smarty->display("login.setup.tpl");
}


