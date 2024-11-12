<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 June 2017 at 13:15:34 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

$url = $_REQUEST['url'];

$curl = curl_init();

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


curl_close($curl);
echo $response;
