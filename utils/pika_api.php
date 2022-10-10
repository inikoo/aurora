<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 10 Oct 2022 11:53:16 Central European Summer Time, Mijas Costa, Spain
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

function pika_api($path, $id, $method = 'POST')
{
    $account = get_object('Account');
    $account->load_data();

    if ($account->get('pika_url')=='' or $account->get('pika_token')=='' or  !defined('PIKA_URL')) {
        return array(
            'success' => false,
            'msg'     => 'Pika integration not set up'
        );
    }


    $url = PIKA_URL.'/'.$account->get('pika_url').'/'.$path;

    $params = [
        'id' => $id

    ];


    $curl = curl_init();

    curl_setopt_array(
        $curl, array(
                 CURLOPT_URL            => $url."?".http_build_query($params),
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING       => "",
                 CURLOPT_MAXREDIRS      => 10,
                 CURLOPT_TIMEOUT        => 0,
                 CURLOPT_FOLLOWLOCATION => true,
                 CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST  => $method,
                 CURLOPT_HTTPHEADER     => array(
                     "Accept: application/json",
                     "Authorization: Bearer ".$account->get('pika_token')
                 ),
             )
    );


    $response = curl_exec($curl);

    curl_close($curl);

    //echo "Params:\n".print_r($params)." <<==\n";
    //echo "Response:".$response.' <<';


}

