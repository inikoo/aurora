<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 Feb 2025 19:22 Sanur Bali

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


function stand_alone_process_aiku_fetch($db, string $model, ?int $key, ?string $field = null, ?array $valid_fields = null, ?array $extra_fields = null)
{
    if (!$key) {
        return;
    }


    $_data = array(
        'model'    => $model,
        'model_id' => $key,
        'field'    => $field
    );

    if ($extra_fields) {
        $_data = array_merge($_data, $extra_fields);
    }

    if (is_null($valid_fields) or is_null($field) or in_array($field, $valid_fields)) {
        if (in_array($model, ['DeleteFavourite', 'Favourite'])) {
            include_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_aiku',
                $_data,
                DNS_ACCOUNT_CODE
            );
        }
        else {
            $date = gmdate('Y-m-d H:i:s');
            $sql  = 'insert into `Stack Aiku Dimension` (`Stack Aiku Creation Date`,`Stack Aiku Last Update Date`,`Stack Aiku Operation`,`Stack Aiku Operation Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Aiku Last Update Date`=? ,`Stack Aiku Counter`=`Stack Aiku Counter`+1 ';

            $db->prepare($sql)->execute(
                [
                    $date,
                    $date,
                    $model,
                    $key,
                    $date,

                ]
            );
        }
    }
}