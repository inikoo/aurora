<?php
/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created:  31 May 2020  12:11::31  +0800, Kuala Lumpur, Malaysia

  Copyright (c) 2017, Inikoo

  Version 2.0
*/


require_once '../vendor/autoload.php';
require __DIR__.'/keyring/dns.php';
require __DIR__.'/keyring/au_deploy_conf.php';
require_once __DIR__.'/utils/sentry.php';


$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);

include_once(__DIR__.'/utils/find_website_key.include.php');
$website_key = get_website_key_from_domain($redis);


include 'display_webpage.php';