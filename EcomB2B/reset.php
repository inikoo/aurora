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
$redis->connect(REDIS_HOST, REDIS_PORT);

session_start();

if (empty($_SESSION['website_key'])) {
    include_once('utils/find_website_key.include.php');
    $_SESSION['website_key']=get_website_key_from_domain($redis);
}

$is_reset=true;

include 'display_webpage.php';