<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 June 2017 at 13:15:34 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/
require_once '../vendor/autoload.php';
require 'keyring/dns.php';
require 'keyring/au_deploy_conf.php';

$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_READ_ONLY_PORT);

if(empty($_REQUEST['url_KHj321Tu'])){
    echo 'error A';
    exit;
}

$url = urldecode($_REQUEST['url_KHj321Tu']);

$curl = curl_init();

$url_cache_key='wowsbar_announcement_v2_'.$url;

//if ($redis->exists($url_cache_key)) {
//    $response = $redis->get($url_cache_key);
//    header('Content-type: application/json');
//    echo $response;
//    exit;
//}


curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'content-type: application/json',
        'Accept: application/json',
    ),
));

$response = curl_exec($curl);
$redis->set(
    $url_cache_key,
    $response,
    [
        'ex'=>10
    ]);


curl_close($curl);
header('Content-type: application/json');
echo $response;
