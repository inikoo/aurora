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
$redis->connect('127.0.0.1', 6379);
session_start();

if (empty($_SESSION['website_key'])) {
    include_once('utils/find_website_key.include.php');
    $_SESSION['website_key']=get_website_key_from_domain($redis);
}

$is_unsubscribe=true;

include 'display_webpage.php';