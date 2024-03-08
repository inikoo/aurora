<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 30 December 2015 at 15:19:00 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2013, Inikoo

 Version 3.0
*/


function new_fork($type, $data, $account_code, $db, $priority = 'Normal'): array {


    if(!empty($GLOBALS['skip_gearman'])){
        return array(
            0,
            'Skipping'
        );
    }

    if (class_exists('GearmanClient')) {


        $token = substr(
            str_shuffle(
                md5(time()).rand().str_shuffle(
                    'qwertyuiopasdfghjjklmnbvcxzQWERTYUIOPKJHGFDSAZXCVBNM1234567890'
                )
            ), 0, 64
        );
        $sql   = sprintf(
            "INSERT INTO `Fork Dimension`  (`Fork Process Data`,`Fork Token`,`Fork Type`) VALUES (%s,%s,%s)  ", prepare_mysql(json_encode($data)), prepare_mysql($token), prepare_mysql($type)

        );


        $salt = md5(rand());
        $db->exec($sql);


        $fork_key = $db->lastInsertId();


        $fork_metadata = json_encode(
            array(
                'code'     => addslashes($account_code),
                'token'    => $token,
                'fork_key' => $fork_key,
                'salt'     => $salt

            )
        );

        $client = new GearmanClient();

        include_once 'keyring/au_deploy_conf.php';
        $servers = explode(",", GEARMAN_SERVERS);
        shuffle($servers);
        $servers = implode(",", $servers);
        $client->addServers($servers);


        switch ($priority) {
            case 'High':
                $msg = $client->doHighBackground($type, $fork_metadata);
                break;
            case 'Low':
                $msg = $client->doLowBackground($type, $fork_metadata);
                break;
            default:
                $msg = $client->doBackground($type, $fork_metadata);
                break;

        }


        return array(
            $fork_key,
            $msg
        );
    } else {
        return array(
            0,
            'Gearman Client class not found'
        );
    }
}

function new_housekeeping_fork($type, $data, $account_code, $priority = 'Normal'): string {

    if(!empty($GLOBALS['skip_gearman'])){
        return 'Skipping';
    }

    if (class_exists('GearmanClient')) {
        $client        = new GearmanClient();
        $fork_metadata = json_encode(
            array(
                'code' => addslashes($account_code),
                'data' => $data
            )
        );

        include_once 'keyring/au_deploy_conf.php';
        $servers = explode(",", GEARMAN_SERVERS);
        shuffle($servers);
        $servers = implode(",", $servers);
        $client->addServers($servers);

        switch ($priority) {
            case 'High':
                return $client->doHighBackground($type, $fork_metadata);
            case 'Low':
                return $client->doLowBackground($type, $fork_metadata);
            default:
                return $client->doBackground($type, $fork_metadata);


        }

    } else {
        return 'Gearman Client class not found';
    }


}

