<?php

error_reporting(E_ALL ^ E_DEPRECATED);
define("_DEVEL", isset($_SERVER['devel']));

require_once 'keyring/dns.php';

require_once 'vendor/autoload.php';
require_once 'utils/sentry.php';


use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcachedSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

require_once 'keyring/key.php';
include_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';
require_once 'utils/system_functions.php';
require_once 'utils/detect_agent.php';
require_once "utils/aes.php";
require_once "class.Account.php";
require_once "class.User.php";

$memcached = new Memcached();
$memcached->addServer($memcache_ip, 11211);


$redis = new Redis();
if ($redis->connect('127.0.0.1', 6379)) {
    $redis_on = true;
} else {
    $redis_on = false;
}

/**
 * @var PDO
 */
$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
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


if ($account->get('Timezone')) {
    date_default_timezone_set($account->get('Timezone'));
} else {
    setTimezone('UTC');
}


require_once 'utils/modules.php';


$sessionStorage = new NativeSessionStorage(array(), new MemcachedSessionHandler($memcached));
$session        = new Session($sessionStorage);


//$session = new Session();
$session->start();

//session_start();
$session->set('account', $account->get('Code'));


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

    $session->migrate();
    $session->invalidate();
    //session_regenerate_id();
    //session_destroy();
    unset($_SESSION);

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
    $user->read_websites();
    $user->read_warehouses();

    $redis->zadd('_IU'.$account->get('Code'), gmdate('U'), $user->id);


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
} else {
    $locale = $account->get('Locale').'.UTF-8';
}


$smarty->assign('user', $user);

$smarty->assign('locale', $locale);

set_locale($locale);


$smarty->assign('account', $account);


$common = '';

$smarty->assign('page_name', basename($_SERVER["PHP_SELF"], ".php"));


