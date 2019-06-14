<?php
/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 27 July 2017 at 09:10:40 CEST, Tranava, Slovakia

  Copyright (c) 2017, Inikoo

  Version 2.0
*/


require_once '../vendor/autoload.php';
require 'keyring/dns.php';
require_once 'utils/sentry.php';

$redis = new Redis();
if(  $redis->connect('127.0.0.1', 6379)){
    $redis_on=true;
}else{
    $redis_on=false;
}



session_start();


if (empty($_SESSION['website_key'])) {
    include('utils/find_website_key.include.php');
}



$is_reset=true;

include 'display_webpage.php';

?>