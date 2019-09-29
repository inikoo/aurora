<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 05-05-2019 09:49:14, CEST Tranava, Slovakia
 Copyright (c) 2019, Inikoo

 Version 3

*/

error_reporting(E_ALL ^ E_DEPRECATED);

require_once 'vendor/autoload.php';






include 'utils/aes.php';
include 'utils/general_functions.php';
include 'utils/system_functions.php';

include 'utils/natural_language.php';

include 'send_mailshot.fork.php';



$worker = new GearmanWorker();
$worker->addServer('127.0.0.1');

$worker->addFunction("au_send_mailshot", "fork_send_mailshot");


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


    global $db, $account;

    $fork_raw_data = $job->workload();
    $fork_metadata = json_decode($fork_raw_data, true);


    $inikoo_account_code = $fork_metadata['code'];
    if (!ctype_alnum($inikoo_account_code)) {
        print "can't find account code ***  ->".$inikoo_account_code."<-  \n";
        print_r($fork_metadata);
        return false;
    }


    require_once "keyring/dns.$inikoo_account_code.php";
    require_once "keyring/key.$inikoo_account_code.php";

    if(defined('SENTRY_DNS_FORK')){
        Sentry\init(['dsn' => SENTRY_DNS_FORK ]);
    }


    require_once "class.Account.php";

    $db = new PDO(
        "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);



    $account = new Account($db);

    if ($account->get('Timezone')) {
        date_default_timezone_set($account->get('Timezone'));
        define("TIMEZONE", $account->data['Account Timezone']);

    } else {
        date_default_timezone_set('UTC');
        define("TIMEZONE", 'UTC');

    }




    return array(
        $account,
        $db,
        $fork_metadata['data'],
        $editor
    );


}
