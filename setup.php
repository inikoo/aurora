<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 14 March 2016 at 10:13:24 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo

 Version 3

*/

error_reporting(E_ALL);
define("_DEVEL", isset($_SERVER['devel']));


include_once 'keyring/dns.php';


require_once 'utils/general_functions.php';
require_once 'utils/password_functions.php';
require_once 'utils/system_functions.php';
include_once 'utils/i18n.php';
include_once 'class.User.php';


date_default_timezone_set('UTC');


if (!isset($_REQUEST['key']) or $_REQUEST['key'] == '') {
    header('Location: login.php?e=1');
    exit;
}


include_once 'class.Account.php';

include_once 'external_libs/Smarty/Smarty.class.php';
$smarty               = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir  = 'server_files/smarty/templates_c';
$smarty->cache_dir    = 'server_files/smarty/cache';
$smarty->config_dir   = 'server_files/smarty/configs';
$smarty->assign('_DEVEL', _DEVEL);

$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


//session_save_path('server_files/tmp');
ini_set('session.gc_maxlifetime', 57600); // 16 hours
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
session_start();


$account = new Account($db);


if (!$account->id) {


    // TODO get account create data from setup.inikoo.com
    $accout_data = array(
        'Account Code'              => 'AU',
        'Account Name'              => 'Test',
        'Account System Public URL' => 'au.bali',
        'Account Setup Metadata'    => json_encode(
            array(
                'steps'     => array(
                    'root_user'     => array('setup' => false),
                    'add_employees' => array('setup' => false),
                    'add_warehouse' => array('setup' => false),
                    'add_store'     => array('setup' => false)
                ),
                'size'      => 'Big',
                'instances' => array(
                    'Com',
                    'Prod'
                )
            )
        ),
        'editor'                    => array(
            'Author Name'  => '',
            'Author Alias' => '',
            'Author Type'  => '',
            'Author Key'   => '',
            'User Key'     => 0,
            'Date'         => gmdate('Y-m-d H:i:s')
        )
    );


    // Create account

    $accout_data['Account Creation Date'] = gmdate('Y-m-d H:i:s');
    $accout_data['Account Key']           = 1;

    $base_data     = array();
    $ignore_fields = array();
    $sql           = sprintf('show columns from `Account Dimension`');
    foreach ($db->query($sql) as $row) {
        if (!in_array($row['Field'], $ignore_fields)) {
            $base_data[$row['Field']] = $row['Default'];
        }
    }


    foreach ($accout_data as $key => $value) {
        if (array_key_exists($key, $base_data)) {
            $base_data[$key] = _trim($value);
        }
    }

    $keys   = '(';
    $values = 'values(';
    foreach ($base_data as $key => $value) {
        $keys .= "`$key`,";

        if ($key == 'Account Short Message') {
            $values .= prepare_mysql($value, false).",";
        } else {
            $values .= prepare_mysql($value).",";
        }
    }
    $keys   = preg_replace('/,$/', ')', $keys);
    $values = preg_replace('/,$/', ')', $values);

    $sql = sprintf("INSERT INTO `Account Dimension` %s %s", $keys, $values);


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {


            $sql = sprintf(
                "INSERT INTO `Payment Service Provider Dimension` ( `Payment Service Provider Code`, `Payment Service Provider Name`, `Payment Service Provider Type`) VALUES ('Accounts', %s, 'Account');",
                _('Internal customers accounts')

            );

            $db->exec($sql);
        }
    } else {
        $smarty->assign('request', 'account/setup/error/1');

        $smarty->display("setup.tpl");
        exit;
    }


    $account = new Account();


    if ($account->id != 1) {

        $smarty->assign('request', 'account/setup/error/2');

        $smarty->display("setup.tpl");
        exit;
    }


}


// Create root user

$setup_data = $account->get('Setup Metadata');
$smarty->assign('setup_data', $setup_data);


if (!$setup_data['steps']['root_user']['setup']) {

    $root_user_data = array(
        'User Handle'           => 'root',
        'User Password'         => hash('sha256', generatePassword(10, 3)),
        'User PIN'              => hash('sha256', generatePassword(10, 3)),
        'User Active'           => 'Yes',
        'User Alias'            => 'Root',
        'User Type'             => 'Administrator',
        'User Preferred Locale' => 'en_GB.UTF-8',
        'User Created'          => gmdate('Y-m-d H:i:s'),
        'editor'                => array(
            'Author Name'  => '',
            'Author Alias' => '',
            'Author Type'  => '',
            'Author Key'   => '',
            'User Key'     => 0,
            'Date'         => gmdate('Y-m-d H:i:s')
        )
    );


    $user = new User('find', $root_user_data, 'create');

    if (!$user->id) {

        $smarty->assign('request', 'account/setup/error/3');

        $smarty->display("setup.tpl");
        exit;
    }

    $user->add_group(array(1), false);

    $account->update(array('Account State' => 'RootUser'), 'no_history');
} else {
    $user = new User('Administrator');
}

$_SESSION['logged_in']      = true;
$_SESSION['logged_in_page'] = 0;
$_SESSION['user_key']       = $user->id;
$_SESSION['text_locale']    = $user->get('User Preferred Locale');


$_SESSION['current_store']     = '';
$_SESSION['current_website']   = '';
$_SESSION['current_warehouse'] = '';

$request = 'account/setup/state';
foreach ($setup_data['steps'] as $step_code => $step_data) {
    if (!$step_data['setup']) {
        $request = 'account/setup/'.$step_code;
        break;
    }
}

$smarty->assign('request', $request);
$smarty->display("setup.tpl");


?>
