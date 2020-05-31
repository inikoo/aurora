<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 14 April 2018 at 17:14:20 GMT+8, Cyberjaya, Malaysia

 Copyright (c) 2017, Inikoo

 Version 2.0
*/
use ReallySimpleJWT\Token;


include_once __DIR__.'/utils/general_functions.php';
include_once __DIR__.'/utils/web_common.php';
include_once __DIR__.'/utils/web_locale_functions.php';

list($detected_device, $template_suffix) = get_device();



date_default_timezone_set('UTC');

$logged_in = get_logged_in();

$smarty               = new Smarty();
$smarty->caching_type = 'redis';
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');


if (defined('SENTRY_DNS_ECOM_JS')) {
    $smarty->assign('sentry_js', SENTRY_DNS_ECOM_JS);
}


$theme        = 'theme_1';
$website_type = 'EcomB2B';


if (!isset($db) or is_null($db)) {
    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}

if (isset($is_homepage)) {
    include_once __DIR__.'/utils/public_object_functions.php';

    $website = get_object('Website', $_SESSION['website_key']);

    if ($logged_in) {

        $webpage_key = $website->get_system_webpage_key('home.sys');


    } else {
        $webpage_key = $website->get_system_webpage_key('home_logout.sys');
    }

} elseif (isset($is_reset)) {

    include_once __DIR__.'/utils/public_object_functions.php';
    include_once __DIR__.'/utils/network_functions.php';

    $website = get_object('Website', $_SESSION['website_key']);

    $webpage_key = $website->get_system_webpage_key('reset_pwd.sys');

    $form_error = false;
    if (!$logged_in) {
        include_once('class.WebAuth.php');
        $auth = new WebAuth($db);

        list($logged_in, $result, $customer_key, $website_user_key, $website_user_log_key) = $auth->authenticate_from_reset_password(
            (isset($_REQUEST['s']) ? $_REQUEST['s'] : ''), (isset($_REQUEST['a']) ? $_REQUEST['a'] : ''), $website->id
        );

        if ($logged_in) {

            $_SESSION['logged_in']            = true;
            $_SESSION['customer_key']         = $customer_key;
            $_SESSION['website_user_key']     = $website_user_key;
            $_SESSION['UTK']=['C'=>$customer_key,'WU'=>$website_user_key,'WUL'=>$website_user_log_key];
            $token = Token::customPayload($_SESSION['UTK'], JWT_KEY);
            setcookie('UTK', $token, time() + 157680000);
            setcookie('AUK', strtolower(DNS_ACCOUNT_CODE).'.'.$_SESSION['customer_key'], time() + 157680000);

        }


        if (!$logged_in) {
            $form_error = $result;
        }

    }


    $smarty->assign('form_error', $form_error);


}
elseif (isset($is_unsubscribe)) {
    include_once __DIR__.'/utils/public_object_functions.php';
    include_once __DIR__.'/utils/network_functions.php';

    $website     = get_object('Website', $_SESSION['website_key']);
    $webpage_key = $website->get_system_webpage_key('unsubscribe.sys');
    include_once('class.WebAuth.php');
    $auth = new WebAuth($db);

    list($unsubscribe_subject_type, $unsubscribe_subject_key) = $auth->get_customer_from_unsubscribe_link((isset($_REQUEST['s']) ? $_REQUEST['s'] : ''), (isset($_REQUEST['a']) ? $_REQUEST['a'] : ''));

    if ($unsubscribe_subject_type == 'Customer') {
        if ($unsubscribe_subject_key != '') {
            $unsubscribe_customer = get_object('Customer', $unsubscribe_subject_key);
            if (!$unsubscribe_customer->id) {
                $unsubscribe_subject_key = '';
            } else {
                $smarty->assign('unsubscribe_customer', $unsubscribe_customer);

            }
        }
        $smarty->assign('selector', (isset($_REQUEST['s']) ? $_REQUEST['s'] : ''));
        $smarty->assign('authenticator', (isset($_REQUEST['a']) ? $_REQUEST['a'] : ''));
        $smarty->assign('unsubscribe_customer_key', $unsubscribe_subject_key);
        $smarty->assign('is_unsubscribe', true);
    } elseif ($unsubscribe_subject_type == 'Prospect') {

        if ($unsubscribe_subject_key != '') {

            $sql  = "select `Prospect Key` from `Prospect Dimension` where `Prospect Key`=?";
            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    $unsubscribe_subject_key
                )
            );
            if ($row = $stmt->fetch()) {
                include_once __DIR__.'/utils/new_fork.php';


                $email_tracking = get_object('Email_Tracking', (isset($_REQUEST['s']) ? $_REQUEST['s'] : ''));
                $email_tracking->fast_update(
                    array(
                        'Email Tracking Unsubscribed' => 'Yes'
                    )
                );

                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'                    => 'update_sent_emails_data',
                    'email_template_key'      => $email_tracking->get('Email Tracking Email Template Key'),
                    'email_template_type_key' => $email_tracking->get('Email Tracking Email Template Type Key'),
                    'email_mailshot_key'      => $email_tracking->get('Email Tracking Email Mailshot Key'),

                ), DNS_ACCOUNT_CODE
                );


                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'         => 'unsubscribe_prospect',
                    'prospect_key' => $row['Prospect Key'],
                    'date'         => gmdate('Y-m-d H:i:s')

                ), DNS_ACCOUNT_CODE
                );
            }


        }

        $smarty->assign('is_prospect', true);

        $smarty->assign('is_unsubscribe', true);

    }

}

elseif (isset($is_404)) {
    $webpage_key = $website->get_system_webpage_key('not_found.sys');
}

$is_devel = (ENVIRONMENT == 'DEVEL' ? true : false);


if (isset($_REQUEST['snapshot'])) {
    require __DIR__.'/keyring/key.php';

    if ($_REQUEST['snapshot'] == md5(VKEY.'||'.date('Ymd'))) {

        $is_devel = 1;
    }

    if (isset($_REQUEST['logged_in']) and $_REQUEST['logged_in']) {

        $logged_in                        = 1;
        $_SESSION['logged_in']            = true;
        $_SESSION['customer_key']         = 0;
        $_SESSION['website_user_key']     = 0;
    }


}


if ($logged_in) {

    include_once __DIR__.'/utils/new_fork.php';
    try {
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'         => 'website_user_visit',
            'server_data'  => $_SERVER,
            'session_data' => $_SESSION,
            'webpage_key'  => $webpage_key,
            'device'       => $detected_device,
            'datetime'     => gmdate('Y-m-d H:i:s')
        ), DNS_ACCOUNT_CODE
        );
    } catch (Exception $e) {
        Sentry\captureException($e);
    }
}


$cache_id = ($logged_in ? 'in' : 'out').'_'.$webpage_key.'|'.$_SESSION['website_key'].'|'.DNS_ACCOUNT_CODE;

$template = $theme.'/webpage_blocks.'.$theme.'.'.$website_type.$template_suffix.'.tpl';
$smarty->assign('is_devel', $is_devel);
$smarty->assign('cache_id', $cache_id);


if (!(isset($is_unsubscribe) or isset($is_reset))) {

    $smarty->setCaching(Smarty::CACHING_LIFETIME_SAVED);
    $smarty->setCacheLifetime(86400);
    $smarty->setCompileCheck(true);

}


if ($logged_in) {
    $smarty->assign('analytics_user_key', strtolower(DNS_ACCOUNT_CODE).'.'.$_SESSION['customer_key'], true);

    $smarty->assign('ws_key', md5(DNS_ACCOUNT_CODE.'-'.$_SESSION['website_key'].'-'.$_SESSION['customer_key'].'-'.crc32($_SESSION['customer_key'].'v1')), true);

}

$smarty->assign('account_code', DNS_ACCOUNT_CODE);


if (!$smarty->isCached($template, $cache_id) or isset($is_unsubscribe) or isset($is_reset)) {


    include_once __DIR__.'/utils/public_object_functions.php';
    include_once __DIR__.'/utils/natural_language.php';

    $webpage = get_object('Webpage', $webpage_key);
    $website = get_object('Website', $_SESSION['website_key']);
    if ($webpage->get('Webpage Code') == 'basket.sys' and $website->get('Website Type') == 'EcomDS') {
        exit;
    }

    if (!$webpage->id or ($webpage->get('Webpage Code') == 'reset_pwd.sys' and !isset($is_reset))) {


        $url = preg_replace('/^\//', '', $_SERVER['REQUEST_URI']);
        $url = preg_replace('/\?.*$/', '', $url);
        $url = substr($url, 0, 256);


        header("Location: https://".$_SERVER['SERVER_NAME']."/404.php?url=$url");
        exit;

    }

    $website = get_object('Website', $webpage->get('Webpage Website Key'));

    $website_settings = $website->settings;


    $theme = $website->get('Website Theme');

    $store = get_object('Store', $website->get('Website Store Key'));


    if (!empty($_SESSION['website_locale'])) {
        $website_locale = $_SESSION['website_locale'];
    } else {
        $_SESSION['website_locale'] = $website->get('Website Locale');
        $website_locale             = $website->get('Website Locale');
    }
    $language = set_locate($website_locale);

    $smarty->assign('language', $language);


    if ($website->get('Website Status') == 'InProcess') {
        $webpage_key = $website->get_system_webpage_key('launching.sys');
        $webpage     = get_object('Webpage', $webpage_key);
    }


    $smarty->assign('client_tag_google_manager_id', $website->get('Website Google Tag Manager Code'));
    $smarty->assign('zendesk_chat_code', $website->get('Website Zendesk Chat Code'));
    $smarty->assign('tawk_chat_code', $website->get('Website Tawk Chat Code'));
    $smarty->assign('sumo_code', $website->get('Website Sumo Code'));
    if (function_exists('get_ecom_firebase_data')) {
        $smarty->assign('firebase', get_ecom_firebase_data($website->get('Website Type')));
    }

    $one_signal = $website->get('Website One Signal Code');


    if ($one_signal != '') {
        $one_signal_data = preg_split('/,/', $one_signal);

        $smarty->assign('one_signal_id', $one_signal_data[0]);
        $smarty->assign('one_signal_key', $one_signal_data[1]);


    }

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
        require_once __DIR__.'/utils/get_addressing.php';
        //print_r( $website->settings);

        list($address_format, $address_labels, $used_fields, $hidden_fields, $required_fields, $no_required_fields) = get_address_form_data($store->get('Store Home Country Code 2 Alpha'), $website->get('Website Locale'));
        //print_r( $website->settings);

        require_once __DIR__.'/utils/get_countries.php';
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


        $smarty->assign('settings', $website->settings);

        $smarty->assign('countries', $countries);
        $smarty->assign('selected_country', $store->get('Store Home Country Code 2 Alpha'));


    } elseif ($webpage->get('Webpage Code') == 'clients.sys' or $webpage->get('Webpage Code') == 'client_order_new.sys') {


        require_once __DIR__.'/utils/get_addressing.php';

        list($address_format, $address_labels, $used_fields, $hidden_fields, $required_fields, $no_required_fields) = get_address_form_data($store->get('Store Home Country Code 2 Alpha'), $website->get('Website Locale'));

        require_once __DIR__.'/utils/get_countries.php';
        $countries = get_countries($website->get('Website Locale'));


        $smarty->assign('address_labels', $address_labels);
        $smarty->assign('used_address_fields', $used_fields);
        $smarty->assign('required_fields', $required_fields);
        $smarty->assign('no_required_fields', $no_required_fields);


        $smarty->assign('countries', $countries);
        $smarty->assign('selected_country', $store->get('Store Home Country Code 2 Alpha'));

    } elseif ($webpage->get('Webpage Code') == 'login.sys') {

        //  if (!empty($_GET['invoice_pdf'])) {
        //      $smarty->assign('redirect_after_login', '/invoice.pdf.php?id='.$_GET['invoice_pdf']);

        //  } elseif (!empty($_GET['order'])) {
        //     $smarty->assign('redirect_after_login', '/profile.sys?order='.$_GET['order']);

        // }

    }


    if ($webpage->get('Webpage Scope') == 'Product') {

        /**
         * @var $product \Public_Product
         */
        $product = get_object('Product', $webpage->get('Webpage Scope Key'));
        $smarty->assign('product', $product);

    }


    if ($webpage->get('Webpage Code') == 'register.sys' or $webpage->get('Webpage Code') == 'profile.sys') {
        $smarty->assign('poll_queries', $website->get_poll_queries($webpage));

    }


    $smarty->assign('webpage', $webpage);
    $smarty->assign('content', sanitize($webpage->get('Content Data')));


    $smarty->assign('settings', $website_settings);


}


$smarty->display($template, $cache_id);


