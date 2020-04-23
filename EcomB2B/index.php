<?php
/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 9 June 2017 at 12:08:30 GMT+7, Phuket, Thailand

  Copyright (c) 2017, Inikoo

  Version 2.0
*/

require_once '../vendor/autoload.php';
require 'keyring/dns.php';
require_once 'utils/sentry.php';


$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);
session_start();
if (empty($_SESSION['website_key'])) {
    include_once('utils/find_website_key.include.php');
    $_SESSION['website_key']=get_website_key_from_domain($redis);
}

$is_homepage=true;
include 'display_webpage.php';