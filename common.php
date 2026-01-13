<?php

error_reporting(E_ALL ^ E_DEPRECATED);
define("_DEVEL", isset($_SERVER['devel']));
/** @var string $dns_host */
/** @var string $dns_port */
/** @var string $dns_db */
/** @var string $dns_user */
/** @var string $dns_pwd */

require_once 'keyring/dns.php';
require_once 'keyring/au_deploy_conf.php';
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
$redis->connect(REDIS_HOST, REDIS_PORT);


$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$account = new Account($db);

if ($account->get('Account State') != 'Active') {
    if (!preg_match('/^\/ar_validation.php$/', $_SERVER['PHP_SELF'])) {

        header('Location: /login.php');
        exit;
    }
}


require_once 'utils/modules.php';
ini_set("session.cookie_httponly", 1);
session_start();

$_SESSION['account'] = $account->get('Code');

if (empty($_SESSION['timezone'] ) or !date_default_timezone_set($_SESSION['timezone'])) {
    if ($account->get('Account Timezone') or !date_default_timezone_set($account->get('Account Timezone'))) {
        date_default_timezone_set('UTC');
    }
}
$_SESSION['timezone'] = date_default_timezone_get();



$smarty = new Smarty();
$smarty->caching_type = 'redis';
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


$is_already_logged_in = (isset($_SESSION['logged_in']) and $_SESSION['logged_in']);

if (!$is_already_logged_in) {
    if (!preg_match('/(js|js\.php)$/', $_SERVER['PHP_SELF'])) {
        header('Location: /login.php?ref='.$_SERVER['REQUEST_URI']);
        exit;
    }
    exit;
}

if ($_SESSION['logged_in_page'] != 0) {
    $sql =  "UPDATE `User Log Dimension` SET `Logout Date`=NOW()  WHERE `Session ID`=?";
    $this->db->prepare($sql)->execute([session_id()]);
    session_destroy();
    $_SESSION = [];
    header('Location: /login.php');
    exit;

}
$user = get_object('User', $_SESSION['user_key']);

if ($user->id) {

    if($user->get('User Active')=='No'  ){
        exit();
    }


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
        case 'Contractor':
        case 'Agent':
            break;
            case 'Supplier':
            $user->read_suppliers();
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
$smarty->assign('page_name', basename($_SERVER["PHP_SELF"], ".php"));


