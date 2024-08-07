<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 14 March 2016 at 10:13:24 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo

 Version 3

*/
use Elasticsearch\ClientBuilder;

require_once 'vendor/autoload.php';


error_reporting(E_ALL);
define("_DEVEL", isset($_SERVER['devel']));





include_once 'keyring/dns.php';
require_once 'keyring/au_deploy_conf.php';


require_once 'utils/general_functions.php';
require_once 'utils/password_functions.php';
require_once 'utils/system_functions.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';
require_once 'utils/timezones.php';


include_once 'utils/i18n.php';
include_once 'class.User.php';
require_once 'class.Data_Sets.php';
include_once 'class.Category.php';



$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);

date_default_timezone_set('UTC');


if (!isset($_REQUEST['key']) or $_REQUEST['key'] == '') {
    header('Location: login.php?e=1');
    exit;
}


include_once 'class.Account.php';

$smarty = new Smarty();
$smarty->caching_type = 'redis';
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');

$smarty->assign('_DEVEL', _DEVEL);

$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$account = new Account($db);

session_start();
$_SESSION['account']= $account->get('Code');


if (!$account->id) {


    // TODO get account create data from setup.inikoo.com
    $account_data = array(
        'Account Code'              => 'INDO',
        'Account Name'              => 'Indonesia',
        'Account System Public URL' => 'bali.aurora.systems',
        'Account Country Code'      => 'IDN',
        'Account Currency'          => 'IDR',
        'Account Currency Symbol'   => 'Rp',
        'Account Timezone'          => 'Asia/Makassar',

        'Account Setup Metadata' => json_encode(
            array(

                'size'      => 'Big',
                'instances' => array(
                    'Com',
                    'Prod'
                )
            )
        ),
        'editor'                 => array(
            'Author Name'  => 'Aurora',
            'Author Alias' => 'Aurora',
            'Author Type'  => '',
            'Author Key'   => '',
            'User Key'     => 0,
            'Date'         => gmdate('Y-m-d H:i:s')
        )
    );


    // Create account

    $account_data['Account Valid From'] = gmdate('Y-m-d H:i:s');
    $account_data['Account Key']           = 1;

    $base_data     = array();
    $ignore_fields = array();
    $sql           = 'show columns from `Account Dimension`';
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

        if ( $key == 'Account Menu Label') {
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
            "INSERT INTO `Account Data` ( `Account Key`,`Account Properties`) VALUES (%d,'{}')", $account->id

        );


        $db->exec($sql);

        $sql = sprintf(
            "INSERT INTO `Payment Service Provider Dimension` ( `Payment Service Provider Code`, `Payment Service Provider Name`, `Payment Service Provider Type`) VALUES ('Accounts', %s, 'Account');", prepare_mysql(_('Internal customers accounts'))

        );
        $db->exec($sql);

        $sql = sprintf(
            "INSERT INTO `Payment Service Provider Dimension` ( `Payment Service Provider Code`, `Payment Service Provider Name`, `Payment Service Provider Type`) VALUES ('Cash', %s, 'Cash');", prepare_mysql(_('Petty cash'))

        );
        $db->exec($sql);

        $account->update_production_job_orders_stats();



        $account->fast_update_json_field(
            'Account Properties', 'part_label_unit', json_encode(
            array(

                'with_image'             => false,
                'with_weight'            => false,
                'with_origin'            => true,
                'with_ingredients'       => false,
                'with_custom_text'       => false,
                'with_account_signature' => true,
                'size'                   => 'EU30161',
                'set_up'                 => 'single',
                'custom_text'            => '',
                'with_borders'           => true


            )

        ), 'Account Data'

        );

        $account->fast_update_json_field(
            'Account Properties', 'part_label_sko', json_encode(
            array(

                'with_image'             => false,
                'with_weight'            => false,
                'with_origin'            => true,
                'with_ingredients'       => false,
                'with_custom_text'       => false,
                'with_account_signature' => true,
                'size'                   => 'EU30161',
                'set_up'                 => 'single',
                'custom_text'            => '',
                'with_borders'           => true


            )

        ), 'Account Data'

        );

        $account->fast_update_json_field(
            'Account Properties', 'part_label_carton', json_encode(
            array(

                'with_image'             => false,
                'with_weight'            => false,
                'with_false'             => false,
                'with_ingredients'       => true,
                'with_custom_text'       => false,
                'with_account_signature' => false,
                'size'                   => 'EU30036',
                'set_up'                 => 'single',
                'custom_text'            => '',
                'with_borders'           => true


            )

        ), 'Account Data'

        );



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


    $root_user_data = array(
        'User Handle'           => 'root',
        'User Password'         => hash('sha256', $_REQUEST['key']),
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

    $editor          = array(
        'Author Name'  => $user->data['User Alias'],
        'Author Alias' => $user->data['User Alias'],
        'Author Type'  => $user->data['User Type'],
        'Author Key'   => $user->data['User Parent Key'],
        'User Key'     => $user->id,
        'Date'         => gmdate('Y-m-d H:i:s')
    );
    $account->editor = $editor;

    foreach ($data_sets as $data_set_data) {
        $data_set_data['editor'] = $editor;

        $data_set = $account->create_data_sets($data_set_data);
        $data_set->update_stats();
    }

    
     create_elastic_tenant_instances($account->get('Account Code'));
    

    $parts_fmap_data = array(
        'Category Code'    => 'FMap',
        'Category Label'   => 'Family Map',
        'Category Scope'   => 'Part',
        'Category Subject' => 'Part',


    );


    $fmap = $account->create_category($parts_fmap_data);

    $sr_cat_data = array(
        'Category Code'    => 'SR',
        'Category Label'   => 'SR',
        'Category Scope'   => 'Invoice',
        'Category Subject' => 'Invoice',


    );


    $sr_cat = $account->create_category($sr_cat_data);

    $account->update(
        array(
            'Account State'                    => 'Active',
            'Account SR Category Key'          => $sr_cat->id,
            'Account Part Family Category Key' => $fmap->id,
        ), 'no_history'
    );



    $warehouse_data=array(
        'Warehouse Code'=>'W',
        'Warehouse Name'=>'W'
    );
    $account->create_warehouse($warehouse_data);

    $_SESSION['logged_in']      = true;
    $_SESSION['logged_in_page'] = 0;
    $_SESSION['user_key']    = $user->id;
    $_SESSION['text_locale'] = $user->get('User Preferred Locale');
    $_SESSION['state']    = array();
    $_SESSION['timezone']             = date_default_timezone_get();
    $_SESSION['local_timezone']       = $_REQUEST['timezone'];
    $_SESSION['local_timezone_label'] = get_normalized_timezones_formatted_label($_REQUEST['timezone']);

    header('Location: dashboard');


} else {
    exit();
}



function create_elastic_tenant_instances($account_code){


    $client = ClientBuilder::create()->setHosts(get_elasticsearch_hosts())
        ->setApiKey(ES_KEY1,ES_KEY2)
        ->setSSLVerification(ES_SSL)
        ->build();



    $params['body'] = array(
        'actions' => array(
            array(
                'add' => array(
                    'index'   => 'au_web_search_analytics',
                    'alias'   => 'au_web_search_analytics_'.strtolower($account_code),
                    "routing" => $account_code,
                    "filter"  => [
                        "term" => [
                            "tenant" => $account_code
                        ]
                    ]
                )
            )
        )
    );
    $client->indices()->updateAliases($params);

    $params['body'] = array(
        'actions' => array(
            array(
                'add' => array(
                    'index'   => 'au_web_search',
                    'alias'   => 'au_web_search_'.strtolower($account_code),
                    "routing" => $account_code,
                    "filter"  => [
                        "term" => [
                            "tenant" => $account_code
                        ]
                    ]
                )
            )
        )
    );
    $client->indices()->updateAliases($params);



    $params['body'] = array(
        'actions' => array(
            array(
                'add' => array(
                    'index'   => 'au_q_search_analytics',
                    'alias'   => 'au_q_search_analytics_'.strtolower($account_code),
                    "routing" => $account_code,
                    "filter"  => [
                        "term" => [
                            "tenant" => $account_code
                        ]
                    ]
                )
            )
        )
    );
    $client->indices()->updateAliases($params);

    $params['body'] = array(
        'actions' => array(
            array(
                'add' => array(
                    'index'   => 'au_search',
                    'alias'   => 'au_search_'.strtolower($account_code),
                    "routing" => $account_code,
                    "filter"  => [
                        "term" => [
                            "tenant" => $account_code
                        ]
                    ]
                )
            )
        )
    );
    $client->indices()->updateAliases($params);





    $params['body'] = array(
        'actions' => array(
            array(
                'add' => array(
                    'index'   => 'au_customers',
                    'alias'   => 'au_customers_'.strtolower($account_code),
                    "routing" => $account_code,
                    "filter"  => [
                        "term" => [
                            "tenant" => $account_code
                        ]
                    ]
                )
            )
        )
    );
    $client->indices()->updateAliases($params);

    $params['body'] = array(
        'actions' => array(
            array(
                'add' => array(
                    'index'   => 'au_part_isf',
                    'alias'   => 'au_part_isf_'.strtolower($account_code),
                    "filter"  => [
                        "term" => [
                            "tenant" => $account_code
                        ]
                    ]
                )
            )
        )
    );
    $client->indices()->updateAliases($params);


    $params['body'] = array(
        'actions' => array(
            array(
                'add' => array(
                    'index'   => 'au_part_location_isf',
                    'alias'   => 'au_part_location_isf_'.strtolower($account_code),
                    "filter"  => [
                        "term" => [
                            "tenant" => $account_code
                        ]
                    ]
                )
            )
        )
    );
    $client->indices()->updateAliases($params);

    $params['body'] = array(
        'actions' => array(
            array(
                'add' => array(
                    'index'   => 'au_warehouse_isf',
                    'alias'   => 'au_warehouse_isf_'.strtolower($account_code),
                    "filter"  => [
                        "term" => [
                            "tenant" => $account_code
                        ]
                    ]
                )
            )
        )
    );
    $client->indices()->updateAliases($params);
    
}