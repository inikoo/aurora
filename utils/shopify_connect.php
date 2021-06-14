<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 1 Apr 2021 14:27 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */


function shopify_connect($store, $params, $path,$method='POST') {


    if ($store->get('Store Shopify API Key') == '' or !defined('SHOPIFY_URL')) {
        return array(
            'success' => false,
            'msg'     => 'Shopify integration not set up'
        );

    }


    $url = SHOPIFY_URL.'/api/'.$path;


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
                     "Authorization: Bearer ".$store->get('Store Shopify API Key')
                 ),
             )
    );



    $response = curl_exec($curl);

    curl_close($curl);

    //echo "Params:\n".print_r($params)." <<==\n";
    //echo "Response:".$response.' <<';

    if ($response) {

        $response=json_decode($response, true);
        if(!isset($response['success'])){
            $response['success']=false;
        }

        return $response;



    }else{
        return array(
            'success'  => false,
            'response' => 'Error try again later'
        );
    }




}

function shopify_create_portfolio_item($store,$customer_key,$customer_portfolio_key){
    $params = [
        'data' => '{}'
    ];
    $path='customer/'.$customer_key.'/portfolio_item/'.$customer_portfolio_key;
    shopify_connect($store, $params, $path);

}

function shopify_delete_portfolio_item($store,$customer_portfolio_key){

    $path='portfolio_item/'.$customer_portfolio_key;
    shopify_connect($store, [], $path,'DELETE');

}

function shopify_update_portfolio_item($store,$customer_portfolio_key,$params){

    $path='portfolio_item/'.$customer_portfolio_key;
    shopify_connect($store, $params, $path);

}


