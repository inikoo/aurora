<?php
/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  Created: 27 June 2018 at 12:40:40 GMT+8, Kuala Lumpur, Malaysia

  Copyright (c) 2018, Inikoo

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



$is_unsubscribe=true;

include 'display_webpage.php';

?>