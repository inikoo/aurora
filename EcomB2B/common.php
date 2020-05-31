<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 8 May 2017 at 22:57:54 GMT-5, CDMX, Mexico

 Copyright (c) 2017, Inikoo

 Version 2.0
*/

error_reporting(E_ALL ^ E_DEPRECATED);

require_once '../vendor/autoload.php';

require 'keyring/dns.php';
require 'keyring/au_deploy_conf.php';

require_once 'utils/sentry.php';

include_once 'utils/natural_language.php';
include_once 'utils/general_functions.php';
include_once 'utils/public_object_functions.php';
include_once 'utils/network_functions.php';
include_once 'utils/aes.php';
include_once 'utils/web_common.php';


$smarty               = new Smarty();
$smarty->caching_type = 'redis';
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');

$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);

if (session_id() == '' || !isset($_SESSION)) {
    session_start();
}


if (empty($_SESSION['website_key'])) {
    include_once('utils/find_website_key.include.php');
    $_SESSION['website_key'] = get_website_key_from_domain($redis);
}


$order_key    = 0;
$customer_key = 0;

require_once 'keyring/key.php';

if (!isset($db) or is_null($db)) {
    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}

include_once 'class.Public_Account.php';
include_once 'class.Public_Website.php';
include_once 'class.Public_Webpage.php';

include_once 'class.Public_Store.php';
include_once 'class.Public_Website_User.php';
include_once 'class.Public_Customer.php';
include_once 'class.Public_Order.php';

$account = new Public_Account($db);


require_once 'external_libs/mobile_detect/Mobile_Detect.php';
$detect = new Mobile_Detect;

if ($detect->isTablet()) {
    $detected_device = 'tablet';

} elseif ($detect->isMobile()) {
    $detected_device = 'mobile';
} else {
    $detected_device = 'desktop';

}
//$detected_device = 'tablet';


if ($detected_device == 'mobile') {
    $template_suffix = '.mobile';
} elseif ($detected_device == 'tablet') {
    $template_suffix = '.tablet';
} else {
    $template_suffix = '';
}
// $template_suffix='';

$smarty->assign('detected_device', $detected_device);


$website = new Public_Website($_SESSION['website_key']);


$smarty->assign('client_tag_google_manager_id', $website->get('Website Google Tag Manager Code'));
$smarty->assign('zendesk_chat_code', $website->get('Website Zendesk Chat Code'));
$smarty->assign('firebase', get_ecom_firebase_data($website->get('Website Type')));

$website_key = $website->id;


$store_key = $website->get('Website Store Key');
$store     = new Public_Store($store_key);


if (!date_default_timezone_set($store->get('Store Timezone'))) {
    date_default_timezone_set('UTC');
}


$valid_locales = array(
    'de_DE',
    'fr_FR',
    'it_IT',
    'pl_PL'
);


if (!isset($_SESSION['website_locale'])) {
    $_SESSION['website_locale'] = $website->get('Website Locale');
    $website_locale             = $website->get('Website Locale');
}


if (isset($_REQUEST['lang']) and in_array($_REQUEST['lang'], $valid_locales)) {
    $website_locale             = $_REQUEST['lang'];
    $_SESSION['website_locale'] = $website_locale;
} elseif (isset($_REQUEST['lang']) and $_REQUEST['lang'] == 'website') {
    $website_locale             = $website->get('Website Locale');
    $_SESSION['website_locale'] = $website_locale;
} else {
    $website_locale = $_SESSION['website_locale'];
}


$language = substr($website_locale, 0, 2);
$smarty->assign('language', $language);


$locale = $website->get('Website Locale').'.UTF-8';


putenv('LC_MESSAGES='.$locale);

if (defined('LC_MESSAGES')) {
    setlocale(LC_MESSAGES, $locale);
} else {
    setlocale(LC_ALL, $locale);
}
bindtextdomain("inikoo", "./locale");
textdomain("inikoo");
bind_textdomain_codeset("inikoo", 'UTF-8');

setlocale(LC_TIME, $locale);


$theme = $website->get('Website Theme');


if ($website->get('Website Status') == 'InProcess') {
    $webpage_key = $website->get_system_webpage_key('launching.sys');
    $webpage     = new Public_Webpage($webpage_key);
    $content     = $webpage->get('Content Data');
    $smarty->assign('webpage', $webpage);
    $smarty->assign('content', $content);
    $smarty->display('homepage_to_launch.'.$theme.'.tpl', $webpage_key);
    exit;
}



$logged_in = get_logged_in();

if ($logged_in) {


    if (!isset($website_user)) {
        $website_user = get_object('User', $_SESSION['website_user_key']);

    }

    if (!isset($customer)) {
        $customer = get_object('Customer', $_SESSION['customer_key']);
    }


    $customer_key     = $customer->id;
    $website_user_key = $website_user->id;
    $smarty->assign('website_user', $website_user);
    $smarty->assign('customer', $customer);


    $order_key = $customer->get_order_in_process_key();

    if ($order_key) {
        $order = get_object('Order', $order_key);
        if ($order->id) {
            $smarty->assign('order', $order);
        }


    }


}


$smarty->assign('customer_key', $customer_key);
$smarty->assign('order_key', $order_key);


$smarty->assign('zero_money', money(0, $store->get('Store Currency Code')));
$smarty->assign('website', $website);
$smarty->assign('store', $store);
$smarty->assign('footer_data', $website->get('Footer Data'));
$smarty->assign('header_data', $website->get('Header Data'));
$smarty->assign('logged_in', $logged_in);




