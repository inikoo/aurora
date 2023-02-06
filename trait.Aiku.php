<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 15:03:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait Aiku
{


    function model_updated($table, $field, $key)
    {
    }

    function submit_pika_fetch_model($model, $model_id)
    {
        $account = get_object('Account', 1);
        $account->load_acc_data();

        switch ($model) {
            case 'Customer':
                $path = 'customer';
                break;
            case 'Part':
                $path = 'stock';
                break;
            default:
                $path = null;
        }
        if (!$path) {
            return;
        }

        $curl = curl_init();


        if (!defined('PIKA_URL')) {
            return;
        }


        $url = PIKA_URL;
        if (defined('AU_ENV') and AU_ENV == 'staging') {
            if (!defined('PIKA_STAGING_URL')) {
                return;
            }

            $url = PIKA_STAGING_URL;
        }
        $url .= '/api/aurora/'.$path.'?id='.$model_id;


        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_HTTPHEADER     => array(
                'Accept: application/json',
                'Authorization: Bearer '.$account->get('pika_token')
            ),
        ));

        $response = curl_exec($curl);


        curl_close($curl);
        //echo $response;

    }


}
