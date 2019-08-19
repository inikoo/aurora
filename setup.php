<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 14 March 2016 at 10:13:24 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'vendor/autoload.php';


error_reporting(E_ALL);
define("_DEVEL", isset($_SERVER['devel']));



use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcachedSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;


include_once 'keyring/dns.php';


require_once 'utils/general_functions.php';
require_once 'utils/password_functions.php';
require_once 'utils/system_functions.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';

include_once 'utils/i18n.php';
include_once 'class.User.php';
require_once 'class.Data_Sets.php';
include_once 'class.Category.php';



$memcached = new Memcached();
$memcached->addServer($memcache_ip, 11211);


$redis = new Redis();
if ($redis->connect('127.0.0.1', 6379)) {
    $redis_on = true;
} else {
    $redis_on = false;
}



date_default_timezone_set('UTC');


if (!isset($_REQUEST['key']) or $_REQUEST['key'] == '') {
    header('Location: login.php?e=1');
    exit;
}


include_once 'class.Account.php';

$smarty               = new Smarty();
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');

$smarty->assign('_DEVEL', _DEVEL);

$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


//session_start();





$account = new Account($db);


$sessionStorage = new NativeSessionStorage(array(), new MemcachedSessionHandler($memcached));
$session        = new Session($sessionStorage);
$session->start();
$session->set('account', $account->get('Code'));



if (!$account->id) {


    // TODO get account create data from setup.inikoo.com
    $account_data = array(
        'Account Code'              => 'IB',
        'Account Name'              => 'Spain',
        'Account System Public URL' => 'au.bali',
        'Account Setup Metadata'    => json_encode(
            array(
                'steps'     => array(
                    'root_user'     => array('setup' => false),
                    'setup_account'     => array('setup' => false),
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
            'Author Name'  => 'Aurora',
            'Author Alias' => 'Aurora',
            'Author Type'  => '',
            'Author Key'   => '',
            'User Key'     => 0,
            'Date'         => gmdate('Y-m-d H:i:s')
        )
    );


    // Create account

    $account_data['Account Creation Date'] = gmdate('Y-m-d H:i:s');
    $account_data['Account Key']           = 1;

    $base_data     = array();
    $ignore_fields = array();
    $sql           = sprintf('show columns from `Account Dimension`');
    foreach ($db->query($sql) as $row) {
        if (!in_array($row['Field'], $ignore_fields)) {
            $base_data[$row['Field']] = $row['Default'];
        }
    }


    foreach ($account_data as $key => $value) {
        if (array_key_exists($key, $base_data)) {
            $base_data[$key] = _trim($value);
        }
    }

    $keys   = '(';
    $values = 'values(';
    foreach ($base_data as $key => $value) {
        $keys .= "`$key`,";

        if ($key == 'Account Short Message' or $key == 'Account Menu Label') {
            $values .= prepare_mysql($value, false).",";
        } else {
            $values .= prepare_mysql($value).",";
        }
    }
    $keys   = preg_replace('/,$/', ')', $keys);
    $values = preg_replace('/,$/', ')', $values);

    $sql = sprintf("INSERT INTO `Account Dimension` %s %s", $keys, $values);


    if ($db->exec($sql)) {
        $account = new Account();




        $history_data = array(
            'History Abstract' => sprintf(_('%s account created'), $account->get('Account Code')),
            'History Details'  => '',
            'Action'           => 'created',
            'Subject'          => 'Administrator',
            'Subject Key'      => $account->id,
            'Author Name'      => 'Aurora'
        );



        $account->add_subject_history($history_data, true, 'No', 'Changes', $account->get_object_name(), $account->id);





    $sql = sprintf(
            "INSERT INTO `Account Data` ( `Account Key`) VALUES (%d)", $account->id

        );



        $db->exec($sql);

        $sql = sprintf(
            "INSERT INTO `Payment Service Provider Dimension` ( `Payment Service Provider Code`, `Payment Service Provider Name`, `Payment Service Provider Type`) VALUES ('Accounts', %s, 'Account');",
           prepare_mysql( _('Internal customers accounts'))

        );
        $db->exec($sql);

        $sql = sprintf(
            "INSERT INTO `Payment Service Provider Dimension` ( `Payment Service Provider Code`, `Payment Service Provider Name`, `Payment Service Provider Type`) VALUES ('Cash', %s, 'Cash');",
            prepare_mysql( _('Petty cash'))

        );
        $db->exec($sql);




    } else {
        $smarty->assign('request', 'account/setup/error/1');

        $smarty->display("setup.tpl");
        exit;
    }



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
            'Author Name'  => 'Aurora',
            'Author Alias' => 'Aurora',
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



    require_once 'conf/data_sets.php';

    $editor = array(
        'Author Name'  => $user->data['User Alias'],
        'Author Alias' => $user->data['User Alias'],
        'Author Type'  => $user->data['User Type'],
        'Author Key'   => $user->data['User Parent Key'],
        'User Key'     => $user->id,
        'Date'         => gmdate('Y-m-d H:i:s')
    );
    $account->editor=$editor;

    foreach ($data_sets as $data_set_data) {
        $data_set_data['editor'] = $editor;

        $data_set = $account->create_data_sets($data_set_data);
        $data_set->update_stats();
    }


    $parts_fmap_data = array(
        'Category Code'           => 'FMap',
        'Category Label'          => 'Family Map',
        'Category Scope'          => 'Part',
        'Category Subject'        => 'Part',


    );


    $fmap=$account->create_category($parts_fmap_data);

    $sr_cat_data = array(
        'Category Code'           => 'SR',
        'Category Label'          => 'SR',
        'Category Scope'          => 'Invoice',
        'Category Subject'        => 'Invoice',


    );


    $sr_cat=$account->create_category($sr_cat_data);

    $account->update(array(
        'Account State' => 'RootUser',
        'Account SR Category Key' => $sr_cat->id,
        'Account Part Family Category Key' => $fmap->id,
                     ), 'no_history');




} else {
    $user = new User('Administrator');
}


$_SESSION['logged_in']      = true;
$_SESSION['logged_in_page'] = 0;
$_SESSION['user_key']       = $user->id;
$_SESSION['text_locale']    = $user->get('User Preferred Locale');


$smarty->assign('locale',$user->get('User Preferred Locale'));



$session->set('current_store','');
//$session->set('current_website','');
$session->set('current_warehouse','');
$session->set('current_production','');


$request = 'account/setup/state';
foreach ($setup_data['steps'] as $step_code => $step_data) {
    if (!$step_data['setup']) {
        $request = 'account/setup/'.$step_code;
        break;
    }
}


$smarty->assign('request', $request);
$smarty->display("setup.tpl");


