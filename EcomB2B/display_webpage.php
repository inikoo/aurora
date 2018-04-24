<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 14 April 2018 at 17:14:20 GMT+8, Cyberjaya, Malaysia

 Copyright (c) 2017, Inikoo

 Version 2.0
*/

include_once 'utils/general_functions.php';

include_once 'utils/web_common.php';

list($detected_device, $template_suffix) = get_device();

if (!isset($db)) {
    require_once 'keyring/dns.php';
    $db = new PDO(
        "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}


$logged_in = get_logged_in();

require_once 'external_libs/Smarty/Smarty.class.php';
$smarty               = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir  = 'server_files/smarty/templates_c';
$smarty->cache_dir    = 'server_files/smarty/cache';
$smarty->config_dir   = 'server_files/smarty/configs';


$theme='theme_1';
$website_type='EcomB2B';


$cache_id=$_SESSION['website_key'].'|'.$webpage_key.'|'.($logged_in?'in':'out');

$template = $theme.'/webpage_blocks.'.$theme.'.'.$website_type.$template_suffix.'.tpl';


$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
$smarty->setCacheLifetime(-1);
$smarty->setCompileCheck(false);


if(!$smarty->isCached($template,$cache_id)) {

    include_once 'utils/natural_language.php';
    include_once 'utils/public_object_functions.php';


    $webpage = get_object('Webpage', $webpage_key);
    $website = get_object('Website', $webpage->get('Webpage Website Key'));
    $theme   = $website->get('Website Theme');

    $store = get_object('Store', $website->get('Website Store Key'));

    set_locate($website, $smarty);

    if ($website->get('Website Status') == 'InProcess') {
        $webpage_key = $website->get_system_webpage_key('launching.sys');
        $webpage     = get_object('Webpage', $webpage_key);
    }


    $account = get_object('Account', 1);
    $smarty->assign('analytics_id', $account->get('Account Analytics ID'));

    $smarty->assign('client_tag_google_manager_id', $website->get('Website Google Tag Manager Code'));
    $smarty->assign('zendesk_chat_code', $website->get('Website Zendesk Chat Code'));

    date_default_timezone_set($store->get('Store Timezone'));


    $smarty->assign('zero_money', money(0, $store->get('Store Currency Code')));
    $smarty->assign('website', $website);
    $smarty->assign('store', $store);
    $smarty->assign('footer_data', $website->get('Footer Data'));
    $smarty->assign('header_data', $website->get('Header Data'));
    $smarty->assign('logged_in', $logged_in);


    $smarty->assign('labels', $website->get('Localised Labels'));


    $smarty->assign('navigation', $webpage->get('Navigation Data'));
    $smarty->assign('discounts', $webpage->get('Discounts'));


    if ($webpage->get('Webpage State') == 'Offline') {


        if ($webpage->get('Webpage Redirection Code') != '') {
            $redirection_webpage = $website->get_webpage($webpage->get('Webpage Redirection Code'));
            if ($redirection_webpage->id and $redirection_webpage->get('Webpage State') == 'Online') {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: /".strtolower($redirection_webpage->get('Webpage Code')));
                exit();

            }
        } else {
            $webpage = $website->get_webpage('offline.sys');
        }
    }


    if ($webpage->get('Webpage Code') == 'register.sys') {
        require_once 'utils/get_addressing.php';
        list($address_format, $address_labels, $used_fields, $hidden_fields, $required_fields, $no_required_fields) = get_address_form_data($store->get('Store Home Country Code 2 Alpha'), $website->get('Website Locale'));

        require_once 'utils/get_countries.php';
        $countries = get_countries($website->get('Website Locale'));


        $smarty->assign('address_labels', $address_labels);
        $smarty->assign('used_address_fields', $used_fields);
        $smarty->assign('required_fields', $required_fields);
        $smarty->assign('no_required_fields', $no_required_fields);

        $smarty->assign('countries', $countries);
        $smarty->assign('selected_country', $store->get('Store Home Country Code 2 Alpha'));

    }


    if ($webpage->get('Webpage Scope') == 'Product') {


        $smarty->assign('product', get_object('Product', $webpage->get('Webpage Scope Key')));

    }





    if ($webpage->get('Webpage Code') == 'register.sys' or  $webpage->get('Webpage Code') == 'profile.sys' ) {
        $smarty->assign('poll_queries', $website->get_poll_queries($webpage));

    }

    $smarty->assign('webpage', $webpage);
    $smarty->assign('content', $webpage->get('Content Data'));

}

$smarty->display($template,$cache_id);


?>