<?php
/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 9 June 2017 at 12:08:30 GMT+7, Phuket, Thailand

  Copyright (c) 2017, Inikoo

  Version 2.0
*/


$redis = new Redis();
if(  $redis->connect('127.0.0.1', 6379)){
    $redis_on=true;
}else{
    $redis_on=false;
}

require_once 'keyring/dns.php';


session_start();


if (empty($_SESSION['website_key'])) {
    include('utils/find_website_key.include.php');
}



$is_homepage=true;

include 'display_webpage.php';


?>