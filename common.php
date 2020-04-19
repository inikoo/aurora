<?php

error_reporting(E_ALL ^ E_DEPRECATED);
define("_DEVEL", isset($_SERVER['devel']));

require_once 'keyring/dns.php';

require_once 'vendor/autoload.php';


require_once 'utils/sentry.php';


require_once 'keyring/key.php';
include_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once 'utils/cached_objects.php';

require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';
require_once 'utils/system_functions.php';
require_once 'utils/network_functions.php';
require_once "utils/aes.php";
require_once "class.Account.php";
require_once "class.User.php";


$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

/**
 * @var PDO
 */
$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$account = new Account($db);


if ($account->get('Account State') != 'Active') {


    $target = $_SERVER['PHP_SELF'];
    if (preg_match('/^\/ar_validation.php$/', $target)) {


    } else {

        header('Location: /login.php');
        exit;
    }
}


require_once 'utils/modules.php';
session_start();


$_SESSION['account'] = $account->get('Code');

if ($_SESSION['timezone'] == '' or !date_default_timezone_set($_SESSION['timezone'])) {
    if ($account->get('Account Timezone') or !date_default_timezone_set($account->get('Account Timezone'))) {
        date_default_timezone_set('UTC');
    }
}
$_SESSION['timezone'] = date_default_timezone_get();


/**
 * @var Smarty
 */
$smarty = new Smarty();
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');
$smarty->assign('_DEVEL', _DEVEL);

if (!empty($release)) {
    $smarty->assign('release', trim($release));
}
if (defined('SENTRY_DNS_AUJS')) {
    $smarty->assign('sentry_js', SENTRY_DNS_AUJS);

}


if (isset($auth_data)) {

    foreach ($auth_data['auth_token'] as $_key => $_value) {
        $_SESSION[$_key] = $_value;
    }
}


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


    session_destroy();
    $_SESSION = [];
    header('Location: /login.php');
    exit;

}
$user = get_object('User', $_SESSION['user_key']);

//$_client_locale='en_GB.UTF-8';


if ($user->id) {
    $locale = $user->get('User Preferred Locale');

    $user->read_groups();

    $user->read_rights();
    $user->read_stores();
    $user->read_warehouses();

    $redis->zadd('_IU'.$account->get('Code'), gmdate('U'), $user->id);
    $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'logged_in', true);


    $redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'alias', $user->get('Alias'));


    switch ($user->get('User Type')) {
        case 'Staff':
            break;
        case 'Contractor':
            break;
        case 'Supplier':
            $user->read_suppliers();
            break;
        case 'Agent':
            break;
    }

    $modules = get_modules($user);

    if ($user->settings('current_store') and in_array($user->settings('current_store'), $user->stores)) {
        $_SESSION['current_store'] = $user->settings('current_store');
    }


} else {
    $locale = $account->get('Locale').'.UTF-8';
}


$smarty->assign('user', $user);

$smarty->assign('locale', $locale);

set_locale($locale);


$smarty->assign('account', $account);


$common = '';

$smarty->assign('page_name', basename($_SERVER["PHP_SELF"], ".php"));


