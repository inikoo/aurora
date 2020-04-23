<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 30 December 2015 at 15:19:00 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2013, Inikoo

 Version 3.0
*/


function new_fork($type, $data, $account_code, $db) {


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

        $client  = new GearmanClient();

        include_once 'keyring/au_deploy_conf.php';
        $servers = explode(",", GEARMAN_SERVERS);
        shuffle($servers);
        $servers = implode(",", $servers);
        $client->addServers($servers);
        $msg = $client->doBackground($type, $fork_metadata);

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


function new_housekeeping_fork($type, $data, $account_code) {

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

        return $client->doBackground($type, $fork_metadata);
    } else {
        return 'Gearman Client class not found';
    }


}

