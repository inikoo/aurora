<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 30 December 2015 at 15:19:00 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2013, Inikoo

 Version 3.0
*/


function new_fork($type, $data, $account_code, $db) {



    $fork_encrypt_key = md5('huls0fjhslsshskslgjbtqcwijnbxhl2391');

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




    $fork_metadata = base64_encode(
        AESEncryptCtr(
            json_encode(
                array(
                    'code'     => addslashes($account_code),
                    'token'    => $token,
                    'fork_key' => $fork_key,
                    'salt'     => $salt

                )
            ), $fork_encrypt_key, 256
        )
    );
    $client        = new GearmanClient();

    $client->addServer('127.0.0.1');
    $msg = $client->doBackground($type, $fork_metadata);

    return array(
        $fork_key,
        $msg
    );

}


function new_housekeeping_fork($type, $data, $account_code) {

    include_once  'utils/aes.php';

    $fork_encrypt_key = md5('huls0fjhslsshskslgjbtqcwijnbxhl2391');

    $token = substr(
        str_shuffle(
            md5(time()).rand().str_shuffle(
                'qwertyuiopasdfghjjklmnbvcxzQWERTYUIOPKJHGFDSAZXCVBNM1234567890'
            )
        ), 0, 64
    );
    $salt  = md5(rand());

    $fork_metadata = base64_encode(
        AESEncryptCtr(
            json_encode(
                array(
                    'code'  => addslashes($account_code),
                    'data'  => $data,
                    'token' => $token,
                    'salt'  => $salt
                )
            ), $fork_encrypt_key, 256
        )
    );
    $client        = new GearmanClient();

    $client->addServer('127.0.0.1');
    $msg = $client->doBackground($type, $fork_metadata);

    return $msg;

}


?>
