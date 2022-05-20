<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 27 Apr 2022 09:12:05 Central European Summer Time, Cala Mija, Spain
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

function api_post_call($url, $data, $api_key = false, $type = 'POST',$db=false)
{
    $ch = curl_init();

    $base_url = 'https://api.hokodo.co/v1/';
    if (ENVIRONMENT == 'DEVEL') {
        $base_url = 'https://api-sandbox.hokodo.co/v1/';
    }

    curl_setopt($ch, CURLOPT_URL, $base_url.$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($type == 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
    } else {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
    }


    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $headers   = array();
    $headers[] = 'Content-Type: application/json';
    if ($api_key) {
        $headers[] = "Authorization: Token $api_key";
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


    $res=curl_exec($ch);
    if($res === false){
        Sentry\captureMessage('Curl error: ' . curl_error($ch));
        curl_close($ch);
    }else{
        curl_close($ch);

        if($db) {
            $sql = "insert into hokodo_debug (`request`,`data`,`response`,`date`,`status`) values (?,?,?,?,?) ";
            $db->prepare($sql)->execute(
                [
                    $url,
                    json_encode($data),
                    $res,
                    gmdate('Y-m-d H:i:s'),
                    'ok'

                ]
            );
        }

        return json_decode($res, true);

    }




}