<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 15:03:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait Aiku
{


    public function model_updated($field, $key)
    {
    }


    public function process_aiku_fetch(string $model, ?int $key, ?string $field = null, ?array $valid_fields = null)
    {

        if(!$key){
            return;
        }

        if (is_null($valid_fields) or is_null($field) or in_array($field, $valid_fields)) {

            $date = gmdate('Y-m-d H:i:s');
            $sql = 'insert into `Stack Aiku Dimension` (`Stack Aiku Creation Date`,`Stack Aiku Last Update Date`,`Stack Aiku Operation`,`Stack Aiku Operation Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Aiku Last Update Date`=? ,`Stack Aiku Counter`=`Stack Aiku Counter`+1 ';

            $this->db->prepare($sql)->execute(
                [
                    $date,
                    $date,
                    $model,
                    $key, $date,

                ]
            );

//            include_once 'utils/new_fork.php';
//            new_housekeeping_fork(
//                'au_aiku',
//                array(
//                    'model'    => $model,
//                    'model_id' => $key,
//                    'field'    => $field
//                ),
//                DNS_ACCOUNT_CODE
//            );
        }
    }


}
