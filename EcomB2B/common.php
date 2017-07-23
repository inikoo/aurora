<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 8 May 2017 at 22:57:54 GMT-5, CDMX, Mexico

 Copyright (c) 2017, Inikoo

 Version 2.0
*/


error_reporting(E_ALL ^ E_DEPRECATED);

include_once 'utils/natural_language.php';
include_once 'utils/general_functions.php';
include_once 'utils/public_object_functions.php';

include_once 'utils/detect_agent.php';
include_once 'utils/aes.php';


require_once 'external_libs/Smarty/Smarty.class.php';
$smarty               = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir  = 'server_files/smarty/templates_c';
$smarty->cache_dir    = 'server_files/smarty/cache';
$smarty->config_dir   = 'server_files/smarty/configs';


//$smarty->caching = 1;
$smarty->clearAllCache();
//$smarty->clear_cache('index.tpl');


session_start();


if (!array_key_exists('website_key', $_SESSION) or !$_SESSION['website_key']) {

    include('utils/find_website_key.include.php');

}


$is_cached = false;

/*

if (    (!isset($_SESSION['logged_in']) or !$_SESSION['logged_in']) and isset($page_key)   and !isset($_REQUEST['p']) and !isset($_REQUEST['masterkey']) and !isset($_COOKIE['user_handle']) ) {


	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
	$smarty->setCacheLifetime(3600);
	$is_cached=$smarty->isCached('page.tpl',$page_key);


}else {
	$is_cached=false;
}
*/


if (!$is_cached) {

    require_once 'keyring/key.php';

    if (!isset($db)) {

        require_once 'keyring/dns.php';

        $db = new PDO(
            "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
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

    $website   = new Public_Website($_SESSION['website_key']);
    $store_key = $website->get('Website Store Key');
    $store     = new Public_Store($store_key);


    date_default_timezone_set($store->get('Store Timezone'));


    $valid_currencies = array(
        'GBP' => array(
            'name'        => 'Pound sterling',
            'native_name' => 'Pound sterling',
            'symbol'      => '£',
        ),
        'USD' => array(
            'name'        => 'US Dollar',
            'native_name' => 'US Dollar',
            'symbol'      => '£',
        ),
        'EUR' => array(
            'name'        => 'Euro',
            'native_name' => 'Euro',
            'symbol'      => '€',
        ),
        'DKK' => array(
            'name'        => 'Danish krone',
            'native_name' => 'Dansk krone',
            'symbol'      => 'kr.',
        ),
        'NOK' => array(
            'name'        => 'Norwegian krone',
            'native_name' => 'Norsk krone',
            'symbol'      => 'kr',
        ),
        'PLN' => array(
            'name'        => 'Polish złoty',
            'native_name' => 'Polski złoty',
            'symbol'      => 'zł',
        ),
        'SEK' => array(
            'name'        => 'Swedish krona',
            'native_name' => 'Svensk krona',
            'symbol'      => 'kr',
        ),
        'CHF' => array(
            'name'        => 'Swiss franc',
            'native_name' => 'Swiss franc',
            //'Schweizer Franken/Franc suisse/Franco svizzero',
            'symbol'      => 'CHF',
        ),

    );


    $valid_locales = array(
        'de_DE',
        'fr_FR',
        'it_IT',
        'pl_PL'
    );

    /*

    if (!isset($_SESSION['site_locale'])) {
        $_SESSION['site_locale'] = $website->get('Website Locale');
        $site_locale             = $website->get('Website Locale');;
    }


    if (isset($_REQUEST['lang']) and in_array($_REQUEST['lang'], $valid_locales)) {
        $site_locale             = $_REQUEST['lang'];
        $_SESSION['site_locale'] = $site_locale;
    } elseif (isset($_REQUEST['lang']) and $_REQUEST['lang'] == 'site') {
        $site_locale             = $website->get('Website Locale');;
        $_SESSION['site_locale'] = $site_locale;
    } else {
        $site_locale = $_SESSION['site_locale'];
    }



    if (!isset($_SESSION['set_currency']) or !array_key_exists($_SESSION['set_currency'], $valid_currencies)) {

        $set_currency                      = $store->get('Store Currency Code');
        $_SESSION['set_currency']          = $set_currency;
        $_SESSION['set_currency_exchange'] = 1;


    } else {

        if ($_SESSION['set_currency'] != $store->get('Store Currency Code')) {
            $set_currency_exchange = currency_conversion($store->get('Store Currency Code'), $_SESSION['set_currency']);
        } else {
            $set_currency_exchange = 1;
        }
        $_SESSION['set_currency_exchange'] = $set_currency_exchange;

    }

$language = substr($site_locale, 0, 2);
*/

    $locale = $website->get('Website Locale').'.UTF-8';

    setlocale(LC_TIME, $locale);
    setlocale(LC_MESSAGES, $locale);

    bindtextdomain("inikoosites", "./locale");
    textdomain("inikoosites");
    bind_textdomain_codeset("inikoosites", 'UTF-8');


    //$customer  = new Public_Customer(0);
    /*
    if (!isset($_SESSION['site_key'])) {
        unset($_SESSION['user_key']);
        unset($_SESSION['customer_key']);
        unset($_SESSION['user_log_key']);
        $_SESSION['logged_in'] = 0;
        $logged_in             = false;
        $St                    = get_sk();
    }
*/
    /*

    if ($logged_in) {
        if ($_SESSION['site_key'] != $website->id) {
            unset($_SESSION['user_key']);
            unset($_SESSION['customer_key']);
            unset($_SESSION['user_log_key']);
            $_SESSION['logged_in'] = 0;
            $_SESSION['_state']    = 'c';
            $logged_in             = false;
            $St                    = get_sk();
        } else {

            $user = new Public_User($_SESSION['user_key']);


            $customer = new Public_Customer($_SESSION['customer_key']);

            //print_r($customer);
        }

    } else {
        unset($_SESSION['user_key']);
        unset($_SESSION['customer_key']);
        unset($_SESSION['user_log_key']);
        $_SESSION['logged_in'] = 0;
        $_SESSION['_state']    = 'd';
        $logged_in             = false;
        $St                    = get_sk();
    }


*/
    /*
    if ($logged_in and ($customer->get('Customer Store Key') != $website->get('Website Store Key'))) {
        header('Location:  logout.php');
        exit;
    }

*/
    /*

    $order_in_process     = false;
    $order_in_process_key = $customer->get_order_in_process_key();
    $order_in_process     = new Public_Order ($order_in_process_key);
    $order_in_process->set_display_currency($_SESSION['set_currency'], $_SESSION['set_currency_exchange']);


    $smarty->assign('site_locale', $site_locale);
    $smarty->assign('language', $language);

*/


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


    $logged_in = !empty($_SESSION['logged_in']);


    /*
    if (!$logged_in) {

        if (isset($_REQUEST['p'])) {

            header('Location: reset.php?x=x&master_key='.$_REQUEST['p']);
            exit;
        }
        if (isset($_REQUEST['masterkey'])) {

            $dencrypted_secret_data = AESDecryptCtr(base64_decode($_REQUEST['masterkey']), $secret_key, 256);

            $auth           = new Auth(IKEY, SKEY);
            $auth->site_key = $website->id;
            $auth->log_page = 'customer';
            $auth->authenticate_from_masterkey($dencrypted_secret_data);

            if ($auth->is_authenticated()) {
                $authentication_type      = 'masterkey';
                $_SESSION['logged_in']    = true;
                $_SESSION['store_key']    = $store_key;
                $_SESSION['site_key']     = $website->id;
                $_SESSION['_state']       = 'a';
                $_SESSION['user_key']     = $auth->get_user_key();
                $_SESSION['customer_key'] = $auth->get_user_parent_key();
                $_SESSION['user_log_key'] = $auth->user_log_key;

                header('location: profile.php?view=change_password');
                exit;

            } else {

                $_SESSION['logged_in'] = 0;
                $_SESSION['_state']    = 'b';
                unset($_SESSION['user_key']);
                unset($_SESSION['customer_key']);
                unset($_SESSION['user_log_key']);
                $logged_in = false;
                $St        = get_sk();

                header('Location: reset.php?error='.$auth->pass['main_reason']);
                exit;


            }


        }
        elseif (isset($_COOKIE['user_handle'])) {

            //print_r($_COOKIE);


            $auth = new Auth(IKEY, SKEY);
            $auth->set_use_cookies();
            //$auth->use_cookies=true;
            $auth->authenticate(false, false, 'customer', $_COOKIE['page_key']);

            if ($auth->is_authenticated()) {
                $authentication_type      = 'cookie';
                $_SESSION['logged_in']    = true;
                $_SESSION['store_key']    = $store_key;
                $_SESSION['site_key']     = $website->id;
                $_SESSION['user_key']     = $auth->get_user_key();
                $_SESSION['customer_key'] = $auth->get_user_parent_key();
                $_SESSION['user_log_key'] = $auth->user_log_key;
                $sql                      = sprintf(
                    "UPDATE `User Log Dimension` SET `Remember Cookie`='Yes'  WHERE `User Log Key`=%d", $auth->user_log_key
                );
                mysql_query($sql);
            } else {
                unset($_SESSION['user_key']);
                unset($_SESSION['customer_key']);
                unset($_SESSION['user_log_key']);
                $_SESSION['logged_in'] = 0;
                $logged_in             = false;
                $St                    = get_sk();
            }
        }

    }
    */


    if ($logged_in) {

        if (empty($_SESSION['customer_key']) or empty($_SESSION['website_user_key']) or empty($_SESSION['website_user_log_key'])) {


            session_regenerate_id();
            session_destroy();
            unset($_SESSION);
            setcookie(
                'rmb', 'x:x', time() - 864000, '/'
            //,'',
            //true, // TLS-only
            //true  // http-only
            );
            header('Location: /index.php');
            exit;
        }

        include_once('class.Public_Customer.php');
        $customer = new Public_Customer($_SESSION['customer_key']);

        if ($customer->id and $customer->get('Customer Store Key') == $store->id) {

            $website_user = new Public_Website_User($_SESSION['website_user_key']);
            if ($website_user->id and $website_user->get('Website User Customer Key') == $customer->id) {


            } else {
                exit('caca2');
                session_regenerate_id();
                session_destroy();
                unset($_SESSION);
                setcookie(
                    'rmb', 'x:x', time() - 864000, '/'
                //,'',
                //true, // TLS-only
                //true  // http-only
                );
                header('Location: /index.php');
                exit;
            }


            if (empty($_COOKIE['rmb'])) {

                require_once "external_libs/random/lib/random.php";
                $selector      = base64_encode(random_bytes(9));
                $authenticator = random_bytes(33);

                setcookie(
                    'rmb', $selector.':'.base64_encode($authenticator), time() + 864000, '/'
                //,'',
                //true, // TLS-only
                //true  // http-only
                );


                $sql = sprintf(
                    'INSERT INTO `Website Auth Token Dimension` (`Website Auth Token Website Key`,`Website Auth Token Selector`,`Website Auth Token Hash`,`Website Auth Token Website User Key`,`Website Auth Token Customer Key`,`Website Auth Token Website User Log Key`,`Website Auth Token Expire`) 
            VALUES (%d,%s,%s,%d,%d,%d,%s)', $website->id, prepare_mysql($selector), prepare_mysql(hash('sha256', $authenticator)), $website_user->id, $customer->id, $_SESSION['website_user_log_key'],
                    prepare_mysql(date('Y-m-d H:i:s', time() + 864000))

                );

                $db->exec($sql);

            }


        } else {


            session_regenerate_id();
            session_destroy();
            unset($_SESSION);
            setcookie(
                'rmb', 'x:x', time() - 864000, '/'
            //,'',
            //true, // TLS-only
            //true  // http-only
            );
            header('Location: /index.php');
            exit;
        }

    } elseif (!empty($_COOKIE['rmb'])) {


        include_once('class.WebAuth.php');

        $auth = new WebAuth();
        list($selector, $authenticator) = explode(':', $_COOKIE['rmb']);


        list($logged_in, $result, $customer_key, $website_user_key, $website_user_log_key) = $auth->authenticate_from_remember($selector, $authenticator, $website->id);

        if ($logged_in) {

            $_SESSION['logged_in']            = true;
            $_SESSION['customer_key']         = $customer_key;
            $_SESSION['website_user_key']     = $website_user_key;
            $_SESSION['website_user_log_key'] = $website_user_log_key;
        }

    }

    if ($logged_in) {


        if (!isset($website_user)) {
            $website_user = get_object('User', $_SESSION['website_user_key']);

        }

        if (!isset($customer)) {
            $customer = get_object('Customer', $_SESSION['customer_key']);
        }


        $smarty->assign('website_user', $website_user);
        $smarty->assign('customer', $customer);


        $order_key = $customer->get_order_in_process_key();

        if ($order_key) {
            $order = get_object('Order', $order_key);
            if($order->id){
                $smarty->assign('order', $order);
            }


        }


    } else {
        $order_key = 0;
    }

    $smarty->assign('order_key', $order_key);
    $smarty->assign('website', $website);
    $smarty->assign('store', $store);
    $smarty->assign('footer_data', $website->get('Footer Data'));
    $smarty->assign('header_data', $website->get('Header Data'));
    $smarty->assign('logged_in', $logged_in);


}


?>
