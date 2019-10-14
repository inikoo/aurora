<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 15 April 2018 at 14:24:09 GMT+8, Cyberjaya, Malysia

 Copyright (c) 2017, Inikoo

 Version 2.0
*/

function get_device() {
    require_once 'external_libs/mobile_detect/Mobile_Detect.php';
    $detect = new Mobile_Detect;

    if ($detect->isTablet()) {
        $detected_device = 'tablet';
        $template_suffix = '.tablet';
    } elseif ($detect->isMobile()) {
        $detected_device = 'mobile';
        $template_suffix = '.mobile';
    } else {
        $detected_device = 'desktop';
        $template_suffix = '';

    }

return array($detected_device,$template_suffix);
}

function get_logged_in(){

    $logged_in = !empty($_SESSION['logged_in']);

    if (!$logged_in and !empty($_COOKIE['rmb'])) {



        include_once('class.WebAuth.php');

        $auth = new WebAuth();
        list($selector, $authenticator) = explode(':', $_COOKIE['rmb']);


        list($logged_in, $result, $customer_key, $website_user_key, $website_user_log_key) = $auth->authenticate_from_remember($selector, $authenticator, $_SESSION['website_key']);

        if ($logged_in) {

            $_SESSION['logged_in']            = true;
            $_SESSION['customer_key']         = $customer_key;
            $_SESSION['website_user_key']     = $website_user_key;
            $_SESSION['website_user_log_key'] = $website_user_log_key;
        }

    }

    if ($logged_in and (empty($_SESSION['customer_key']) or empty($_SESSION['website_user_key']) or empty($_SESSION['website_user_log_key']))) {


        session_regenerate_id();
        session_destroy();
        unset($_SESSION);
        setcookie(
            'rmb', 'x:x', time() - 864000, '/'
        );
        header('Location: /index.php');
        exit;
    }

    if ($logged_in and empty($_COOKIE['rmb'])) {




        require_once "external_libs/random/lib/random.php";
        $selector      = base64_encode(random_bytes(9));
        $authenticator = random_bytes(33);

        setcookie(
            'rmb', $selector.':'.base64_encode($authenticator), time() + 864000, '/'

        );

        include_once 'keyring/dns.php';

        if (!isset($db)) {

            require 'keyring/dns.php';

            $db = new PDO(
                "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
            );
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        $sql = sprintf(
            'INSERT INTO `Website Auth Token Dimension` (`Website Auth Token Website Key`,`Website Auth Token Selector`,`Website Auth Token Hash`,`Website Auth Token Website User Key`,`Website Auth Token Customer Key`,`Website Auth Token Website User Log Key`,`Website Auth Token Expire`) 
            VALUES (%d,%s,%s,%d,%d,%d,%s)', $_SESSION['website_key'], prepare_mysql($selector), prepare_mysql(hash('sha256', $authenticator)), $_SESSION['website_user_key'], $_SESSION['customer_key'], $_SESSION['website_user_log_key'],
            prepare_mysql(gmdate('Y-m-d H:i:s', time() + 864000))

        );

        $db->exec($sql);

    }


    return $logged_in;

}

function set_locate($website,&$smarty){


   if(!empty($_SESSION['website_locale'])) {
        $website_locale = $_SESSION['website_locale'];
    }else{
        $_SESSION['website_locale'] = $website->get('Website Locale');
        $website_locale             = $website->get('Website Locale');
    }


    $language= substr($website_locale, 0, 2);
    $smarty->assign('language', $language);

    $locale = $website_locale.'.UTF-8';




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

}
