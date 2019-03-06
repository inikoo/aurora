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
    require 'keyring/dns.php';
    $db = new PDO(
        "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}

date_default_timezone_set('UTC');
$logged_in = get_logged_in();

$smarty = new Smarty();
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');

$theme        = 'theme_1';
$website_type = 'EcomB2B';

if (isset($is_homepage)) {
    include_once 'utils/public_object_functions.php';

    $website = get_object('Website', $_SESSION['website_key']);

    if ($logged_in) {

        $webpage_key = $website->get_system_webpage_key('home.sys');


    } else {
        $webpage_key = $website->get_system_webpage_key('home_logout.sys');
    }

} elseif (isset($is_reset)) {
    include_once 'utils/public_object_functions.php';
    include_once 'utils/detect_agent.php';

    $website = get_object('Website', $_SESSION['website_key']);

    $webpage_key = $website->get_system_webpage_key('reset_pwd.sys');

    $form_error = false;
    if (!$logged_in) {
        include_once('class.WebAuth.php');
        $auth = new WebAuth();

        list($logged_in, $result, $customer_key, $website_user_key, $website_user_log_key) = $auth->authenticate_from_reset_password(
            (isset($_REQUEST['s']) ? $_REQUEST['s'] : ''), (isset($_REQUEST['a']) ? $_REQUEST['a'] : ''), $website->id
        );

        if ($logged_in) {

            $_SESSION['logged_in']            = true;
            $_SESSION['customer_key']         = $customer_key;
            $_SESSION['website_user_key']     = $website_user_key;
            $_SESSION['website_user_log_key'] = $website_user_log_key;
        }


        if (!$logged_in) {
            $form_error = $result;
        }

    } else {
        //todo remove the reset password
    }


    $smarty->assign('form_error', $form_error);


} elseif (isset($is_unsubscribe)) {
    include_once 'utils/public_object_functions.php';
    include_once 'utils/detect_agent.php';

    $website = get_object('Website', $_SESSION['website_key']);

    $webpage_key = $website->get_system_webpage_key('unsubscribe.sys');


    include_once('class.WebAuth.php');
    $auth = new WebAuth();

    $unsubscribe_customer_key = $auth->get_customer_from_unsubscribe_link((isset($_REQUEST['s']) ? $_REQUEST['s'] : ''), (isset($_REQUEST['a']) ? $_REQUEST['a'] : ''));


    if ($unsubscribe_customer_key != '') {
        $unsubscribe_customer = get_object('Customer', $unsubscribe_customer_key);
        if (!$unsubscribe_customer->id) {
            $unsubscribe_customer_key = '';
        } else {
            $smarty->assign('unsubscribe_customer', $unsubscribe_customer);

        }
    }
    $smarty->assign('selector', (isset($_REQUEST['s']) ? $_REQUEST['s'] : ''));
    $smarty->assign('authenticator', (isset($_REQUEST['a']) ? $_REQUEST['a'] : ''));

    $smarty->assign('unsubscribe_customer_key', $unsubscribe_customer_key);


}

//https://www.awgifts.eu/reset.php?s=ZBTN9OVoYabB&a=OZz-bvClmCKb0h8-QIYgz_UsR5sxz8PCR_rcs2_gFQZO

$cache_id = $_SESSION['website_key'].'|'.$webpage_key.'|'.($logged_in ? 'in' : 'out');

$template = $theme.'/webpage_blocks.'.$theme.'.'.$website_type.$template_suffix.'.tpl';

if (!(isset($is_unsubscribe) or isset($is_reset))) {


    $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
    $smarty->setCacheLifetime(-1);
    $smarty->setCompileCheck(true);


}


if (!$smarty->isCached($template, $cache_id) or isset($is_unsubscribe) or isset($is_reset)) {

    include_once 'utils/public_object_functions.php';

    include_once 'utils/natural_language.php';


    $webpage = get_object('Webpage', $webpage_key);
    $website = get_object('Website', $webpage->get('Webpage Website Key'));

    $website_settings = $website->settings;


    $theme = $website->get('Website Theme');

    $store = get_object('Store', $website->get('Website Store Key'));

    set_locate($website, $smarty);

    if ($website->get('Website Status') == 'InProcess') {
        $webpage_key = $website->get_system_webpage_key('launching.sys');
        $webpage     = get_object('Webpage', $webpage_key);
    }


    $account = get_object('Account', 1);
    $smarty->assign('account_code', $account->get('Account Code'));
    $smarty->assign('client_tag_google_manager_id', $website->get('Website Google Tag Manager Code'));
    $smarty->assign('zendesk_chat_code', $website->get('Website Zendesk Chat Code'));
    $smarty->assign('tawk_chat_code', $website->get('Website Tawk Chat Code'));


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
        //print_r( $website->settings);

        list($address_format, $address_labels, $used_fields, $hidden_fields, $required_fields, $no_required_fields) = get_address_form_data($store->get('Store Home Country Code 2 Alpha'), $website->get('Website Locale'));
        //print_r( $website->settings);

        require_once 'utils/get_countries.php';
        $countries = get_countries($website->get('Website Locale'));


        foreach ($website->get_poll_queries($webpage) as $poll_query) {
            if ($poll_query['Customer Poll Query Registration Required'] == 'Yes') {
                $required_fields[] = 'poll_'.$poll_query['Customer Poll Query Key'];
            }

        }


        $smarty->assign('address_labels', $address_labels);
        $smarty->assign('used_address_fields', $used_fields);
        $smarty->assign('required_fields', $required_fields);
        $smarty->assign('no_required_fields', $no_required_fields);


        $smarty->assign('countries', $countries);
        $smarty->assign('selected_country', $store->get('Store Home Country Code 2 Alpha'));


    }


    if ($webpage->get('Webpage Code') == 'login.sys') {

        if (!empty($_GET['invoice_pdf'])) {
            $smarty->assign('redirect_after_login', '/invoice.pdf.php?id='.$_GET['invoice_pdf']);

        } elseif (!empty($_GET['order'])) {
            $smarty->assign('redirect_after_login', '/profile.sys?order='.$_GET['order']);

        }

    }


    if ($webpage->get('Webpage Scope') == 'Product') {


        $smarty->assign('product', get_object('Product', $webpage->get('Webpage Scope Key')));

    }


    if ($webpage->get('Webpage Code') == 'register.sys' or $webpage->get('Webpage Code') == 'profile.sys') {
        $smarty->assign('poll_queries', $website->get_poll_queries($webpage));

    }


    $smarty->assign('webpage', $webpage);
    $smarty->assign('content', $webpage->get('Content Data'));
    $smarty->assign('settings', $website_settings);


    //print $template;

} else {
}


$smarty->display($template, $cache_id);


?>