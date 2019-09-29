<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 24 March 2016 at 13:57:44 GMT+8, Kuala Lumpur, Malaysia
 Created: 2013
 Copyright (c) 2016, Inikoo

 Version 3

*/

error_reporting(E_ALL ^ E_DEPRECATED);

require_once 'vendor/autoload.php';





class fake_session {
    function __construct() {
        $this->data = array();
    }

    function set($key, $value) {
        $this->data[$key] = $value;
    }

    function get($key) {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return false;
        }
    }
}

$session = new fake_session;


include 'utils/aes.php';
include 'utils/general_functions.php';
include 'utils/system_functions.php';
include 'utils/natural_language.php';
include 'export.fork.php';
include 'export_edit_template.fork.php';
include 'upload_edit.fork.php';
include 'housekeeping.fork.php';
include 'asset_sales.fork.php';
include 'time_series.fork.php';
include 'calculate_sales.fork.php';


//$count_number_used = 0;


$worker = new GearmanWorker();
$worker->addServer('127.0.0.1');
$worker->addFunction("au_export", "fork_export");
$worker->addFunction("au_export_edit_template", "fork_export_edit_template");
$worker->addFunction("au_upload_edit", "fork_upload_edit");
$worker->addFunction("au_housekeeping", "fork_housekeeping");
$worker->addFunction("au_asset_sales", "fork_asset_sales");
$worker->addFunction("au_time_series", "fork_time_series");
$worker->addFunction("au_calculate_sales", "fork_calculate_sales");


$db      = false;
$account = false;

while ($worker->work()) {
    if ($worker->returnCode() == GEARMAN_SUCCESS) {
        //$count_number_used++;
        $db = null;
        exec("kill -9 ".getmypid());
        die();
    }
}



function get_fork_metadata($job) {



    $editor = array(


        'Author Type'  => '',
        'Author Key'   => '',
        'User Key'     => 0,
        'Date'         => gmdate('Y-m-d H:i:s'),
        'Subject'      => 'System',
        'Subject Key'  => 0,
        'Author Name'  => 'Fork',
        'Author Alias' => 'Fork',
    );


    global $db, $account, $session;

    // $fork_encrypt_key = md5('huls0fjhslsshskslgjbtqcwijnbxhl2391');
    $fork_raw_data = $job->workload();
    // $fork_metadata    = json_decode(AESDecryptCtr(base64_decode($fork_raw_data), $fork_encrypt_key, 256), true);
    //print_r($fork_raw_data);
    $fork_metadata = json_decode($fork_raw_data, true);

    // print_r($fork_metadata);
    // exit;


    $inikoo_account_code = $fork_metadata['code'];
    if (!ctype_alnum($inikoo_account_code)) {

        // print_r(AESDecryptCtr(base64_decode($fork_raw_data), $fork_encrypt_key, 256));

        print "can't find account code ***  ->".$inikoo_account_code."<-  \n";
        print_r($fork_metadata);

        return false;
    }

    // tod remove after spain migration
    if ($inikoo_account_code == 'AWR') {
        return false;
    }


    require_once "keyring/dns.$inikoo_account_code.php";
    require_once "keyring/key.$inikoo_account_code.php";


    if(defined('SENTRY_DNS_FORK')){
        Sentry\init(['dsn' => SENTRY_DNS_FORK ]);
    }

    require_once "class.Account.php";

    $db = new PDO(
        "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


    /*

    if (function_exists('mysql_connect')) {

        $default_DB_link = mysql_connect($dns_host, $dns_user, $dns_pwd);
        if (!$default_DB_link) {
            print "Error can not connect with database server\n";

            return false;
        }
        $db_selected = mysql_select_db($dns_db, $default_DB_link);
        if (!$db_selected) {
            print "Error can not access the database\n";

            return false;
        }
        mysql_set_charset('utf8');
        mysql_query("SET time_zone='+0:00'");
    }

    */

    $account = new Account($db);

    if ($account->get('Timezone')) {
        date_default_timezone_set($account->get('Timezone'));
        define("TIMEZONE", $account->data['Account Timezone']);

    } else {
        date_default_timezone_set('UTC');
        define("TIMEZONE", 'UTC');

    }


    $warehouse_key = '';
    $sql           = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse State`="Active" limit 1');

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $warehouse_key = $row['Warehouse Key'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }
    $session->set('current_warehouse', $warehouse_key);



    return array(
        $account,
        $db,
        $fork_metadata['data'],
        $editor,
        $session
    );


}


function get_fork_data($job) {

    global $db, $account, $session;


    $editor = array(


        'Author Type'  => '',
        'Author Key'   => '',
        'User Key'     => 0,
        'Date'         => gmdate('Y-m-d H:i:s'),
        'Subject'      => 'System',
        'Subject Key'  => 0,
        'Author Name'  => 'Fork',
        'Author Alias' => 'Fork',
    );

    // $fork_encrypt_key = md5('huls0fjhslsshskslgjbtqcwijnbxhl2391');
    $fork_raw_data = $job->workload();
    //$fork_metadata    = json_decode(
    //   AESDecryptCtr(base64_decode($fork_raw_data), $fork_encrypt_key, 256), true
    //);

    $fork_metadata = json_decode($fork_raw_data, true);


    print_r($fork_metadata);

    $inikoo_account_code = $fork_metadata['code'];
    if (!ctype_alnum($inikoo_account_code)) {

        print_r($fork_metadata);

        print "can't find account code x->".$inikoo_account_code."<-  \n";

        return false;
    }


    if (!file_exists("keyring/dns.$inikoo_account_code.php")) {
        print "file keyring/dns.$inikoo_account_code.php missing\n";
        exit;
    }


    require_once "keyring/dns.$inikoo_account_code.php";
    require_once "keyring/key.$inikoo_account_code.php";

    require_once "class.Account.php";


    $fork_key = $fork_metadata['fork_key'];
    //    $token    = $fork_metadata['token'];

    $db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';"));
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    if (function_exists('mysql_connect')) {
        $default_DB_link = mysql_connect($dns_host, $dns_user, $dns_pwd);
        if (!$default_DB_link) {
            print "Error can not connect with database server\n";

            return false;
        }
        $db_selected = mysql_select_db($dns_db, $default_DB_link);
        if (!$db_selected) {
            print "Error can not access the database\n";

            return false;
        }
        mysql_set_charset('utf8');
        mysql_query("SET time_zone='+0:00'");
    }

    $account = new Account($db);

    if ($account->get('Timezone')) {
        date_default_timezone_set($account->get('Timezone'));
    } else {
        date_default_timezone_set('UTC');
    }


    $sql = sprintf(
        "SELECT `Fork Process Data` FROM `Fork Dimension` WHERE `Fork Key`=%d ", $fork_key
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {


            $warehouse_key = '';
            $sql           = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse State`="Active" limit 1');

            if ($result2 = $db->query($sql)) {
                if ($row2 = $result2->fetch()) {
                    $warehouse_key = $row2['Warehouse Key'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }
            $session->set('current_warehouse', $warehouse_key);


            $fork_data = json_decode($row['Fork Process Data'], true);


            $fork_data = array(
                'fork_key'            => $fork_key,
                'inikoo_account_code' => $inikoo_account_code,
                'fork_data'           => $fork_data,
                'db'                  => $db,
                'editor'              => $editor,
                'session'             => $session

            );


            return $fork_data;
        } else {
            print "fork data not found";

            return false;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}



