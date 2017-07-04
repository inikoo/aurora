<?php

error_reporting(E_ALL ^ E_DEPRECATED);
define("_DEVEL", isset($_SERVER['devel']));


require_once 'keyring/dns.php';
require_once 'keyring/key.php';

include_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';

require_once 'utils/system_functions.php';
require_once 'utils/detect_agent.php';
require_once "utils/aes.php";
require_once "class.Account.php";
require_once "class.Auth.php";
require_once "class.User.php";

$mem = new Memcached();
$mem->addServer($memcache_ip, 11211);

$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

if(function_exists('mysql_connect')) {

    $default_DB_link = mysql_connect($dns_host, $dns_user, $dns_pwd);
    if (!$default_DB_link) {
        print "Error can not connect with database server\n";
    }
    $db_selected = mysql_select_db($dns_db, $default_DB_link);
    if (!$db_selected) {
        print "Error can not access the database\n";
        exit;
    }
    mysql_set_charset('utf8');
    mysql_query("SET time_zone='+0:00'");
}

$account = new Account($db);


if ($account->get('Account State') != 'Active') {


    $target = $_SERVER['PHP_SELF'];
    if (preg_match('/^\/ar_validation.php$/', $target)) {


    } else {

        header('Location: /login.php');
        exit;
    }
}


if ($account->get('Timezone')) {
    date_default_timezone_set($account->get('Timezone'));
} else {
    setTimezone('UTC');
}


require_once 'utils/modules.php';

session_start();



require 'external_libs/Smarty/Smarty.class.php';
$smarty               = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir  = 'server_files/smarty/templates_c';
$smarty->cache_dir    = 'server_files/smarty/cache';
$smarty->config_dir   = 'server_files/smarty/configs';
$smarty->assign('_DEVEL', _DEVEL);


$is_already_logged_in = (isset($_SESSION['logged_in']) and $_SESSION['logged_in'] ? true : false);

if (!$is_already_logged_in) {
    $target = $_SERVER['PHP_SELF'];
    if (!preg_match('/(js|js\.php)$/', $target)) {

        header('Location: /login.php?ref='.$_SERVER['REQUEST_URI']);
        exit;
    }
    exit;
}

if ($_SESSION['logged_in_page'] != 0) {


    $sql = sprintf(
        "UPDATE `User Log Dimension` SET `Logout Date`=NOW()  WHERE `Session ID`=%s", prepare_mysql(session_id())
    );
    $db->exec($sql);

    session_regenerate_id();
    session_destroy();
    unset($_SESSION);

    header('Location: /login.php');
    exit;

}
$user = new User($_SESSION['user_key']);

//$_client_locale='en_GB.UTF-8';


if (isset($user)) {
    $locale = $user->get('User Preferred Locale');
} else {
    $locale = $account->get('Locale').'.UTF-8';
}
$smarty->assign('locale', $locale);

set_locale($locale);


$user->read_groups();

$user->read_rights();
$user->read_stores();
$user->read_websites();
$user->read_warehouses();
if ($user->data['User Type'] == 'Supplier') {
    $user->read_suppliers();

}



$smarty->assign('user', $user);
$smarty->assign('account', $account);


$common = '';

$smarty->assign('page_name', basename($_SERVER["PHP_SELF"], ".php"));
$smarty->assign(
    'analytics_tracking', (file_exists('templates/analytics_tracking.tpl') ? true : false)
);


