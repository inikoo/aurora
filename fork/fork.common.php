<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 24 March 2016 at 13:57:44 GMT+8, Kuala Lumpur, Malaysia
 Moved:  27 November 2019  09:30::53  +0100, Mijas Costa, Spain
 Created: 2013
 Copyright (c) 2016, Inikoo

 Version 3

*/

/**
 * @param $job
 *
 * @return array|bool
 */
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


    $fork_raw_data = $job->workload();
    $fork_metadata = json_decode($fork_raw_data, true);


    $inikoo_account_code = $fork_metadata['code'];
    if (!ctype_alnum($inikoo_account_code)) {
        print "can't find account code ***  ->".$inikoo_account_code."<-  \n";
        print_r($fork_metadata);

        return false;
    }

    require_once __DIR__."/keyring/dns.$inikoo_account_code.php";
    require_once __DIR__."/keyring/key.$inikoo_account_code.php";


    if (defined('SENTRY_DNS_FORK')) {
        Sentry\init(['dsn' => SENTRY_DNS_FORK]);
    }


    /**
     * @var string $dns_host
     * @var string $dns_db
     * @var string $dns_user
     * @var string $dns_pwd
     * @var string $dns_port
     */

    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


    $account = new Account($db);

    if (!date_default_timezone_set($account->get('Account Timezone'))) {
        date_default_timezone_set('UTC');
    }

    $warehouse_key = '';

    $sql  = sprintf("SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse State`='Active' limit 1");
    $stmt = $db->prepare($sql);
    $stmt->execute();
    if ($row = $stmt->fetch()) {
        $warehouse_key = $row['Warehouse Key'];
    }
    $_SESSION['current_warehouse']=$warehouse_key;

    $elasticsearch_hosts=get_elasticsearch_hosts();

    return array(
        $account,
        $db,
        $fork_metadata['data'],
        $editor,
        $elasticsearch_hosts
    );


}


function get_fork_data($job) {



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

    $fork_raw_data = $job->workload();
    $fork_metadata = json_decode($fork_raw_data, true);

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


    $fork_key = $fork_metadata['fork_key'];


    /**
     * @var string $dns_host
     * @var string $dns_db
     * @var string $dns_user
     * @var string $dns_pwd
     * @var string $dns_port
     */

    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


    $account = new Account($db);

    if (!date_default_timezone_set($account->get('Account Timezone'))) {
        date_default_timezone_set('UTC');
    }


    $sql = "SELECT `Fork Process Data` FROM `Fork Dimension` WHERE `Fork Key`=? ";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $fork_key
        )
    );
    if ($row = $stmt->fetch()) {
        $warehouse_key = '';

        $sql  = "SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse State`='Active' limit 1";
        $stmt2 = $db->prepare($sql);
        $stmt2->execute();
        if ($row2 = $stmt2->fetch()) {
            $warehouse_key = $row2['Warehouse Key'];
        }

        $_SESSION['current_warehouse']=$warehouse_key;


        $fork_data = json_decode($row['Fork Process Data'], true);


        $fork_data = array(
            'fork_key'            => $fork_key,
            'inikoo_account_code' => $inikoo_account_code,
            'fork_data'           => $fork_data,
            'db'                  => $db,
            'editor'              => $editor
        );


        return $fork_data;
    } else {
        return false;
    }
}


function get_fork_metadata_v2($job) {


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


    $fork_raw_data = $job->workload();
    $fork_metadata = json_decode($fork_raw_data, true);


    $inikoo_account_code = $fork_metadata['code'];
    if (!ctype_alnum($inikoo_account_code)) {
        print "can't find account code ***  ->".$inikoo_account_code."<-  \n";
        print_r($fork_metadata);

        return false;
    }

    include __DIR__."/keyring/dns.$inikoo_account_code.php";
    include __DIR__."/keyring/key.$inikoo_account_code.php";


    if (defined('SENTRY_DNS_FORK')) {
        Sentry\init(['dsn' => SENTRY_DNS_FORK]);
    }


    /**
     * @var string $dns_host
     * @var string $dns_db
     * @var string $dns_user
     * @var string $dns_pwd
     * @var string $dns_port
     */

    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


    $account = new Account($db);

    if (!date_default_timezone_set($account->get('Account Timezone'))) {
        date_default_timezone_set('UTC');
    }



    return array(
        $account,
        $db,
        $fork_metadata['data'],
        $editor,
    );


}

function get_fork_metadata_for_es($job) {


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


    $fork_raw_data = $job->workload();
    $fork_metadata = json_decode($fork_raw_data, true);


    $inikoo_account_code = $fork_metadata['code'];
    if (!ctype_alnum($inikoo_account_code)) {
        print "can't find account code ***  ->".$inikoo_account_code."<-  \n";
        print_r($fork_metadata);

        return false;
    }

    include __DIR__."/keyring/dns.$inikoo_account_code.php";
    include __DIR__."/keyring/key.$inikoo_account_code.php";


    if (defined('SENTRY_DNS_FORK')) {
        Sentry\init(['dsn' => SENTRY_DNS_FORK]);
    }


    /**
     * @var string $dns_host
     * @var string $dns_db
     * @var string $dns_user
     * @var string $dns_pwd
     * @var string $dns_port
     */

    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


    $account = new Account($db);

    if (!date_default_timezone_set($account->get('Account Timezone'))) {
        date_default_timezone_set('UTC');
    }

    $warehouse_key = '';

    $sql  = sprintf("SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse State`='Active' limit 1");
    $stmt = $db->prepare($sql);
    $stmt->execute();
    if ($row = $stmt->fetch()) {
        $warehouse_key = $row['Warehouse Key'];
    }
    $_SESSION['current_warehouse']=$warehouse_key;

    $elasticsearch_hosts=get_elasticsearch_hosts();

    return array(
        $account,
        $db,
        $fork_metadata['data'],
        $editor,
        $elasticsearch_hosts
    );


}

