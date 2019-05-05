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

        //  $fork_encrypt_key = md5('huls0fjhslsshskslgjbtqcwijnbxhl2391');

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

        // print $sql;

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

        $client->addServer('127.0.0.1');
        $msg = $client->doBackground($type, $fork_metadata);

        return array(
            $fork_key,
            $msg
        );
    } else {
        return array(
            0,
            'GearmanClient class not found'
        );
    }
}


function new_housekeeping_fork($type, $data, $account_code) {

    if (class_exists('GearmanClient')) {
        $client = new GearmanClient();
        $fork_metadata = json_encode(
            array(
                'code' => addslashes($account_code),
                'data' => $data
            )
        );
        $client->addServer('127.0.0.1');
        $msg = $client->doBackground($type, $fork_metadata);

        return $msg;
    } else {
        return '\'Gearman Client class not found\'';
    }


}

