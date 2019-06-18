<?php
/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 9 May 2017 at 22:06:41 GMT-5, CdMx Mexico

  Copyright (c) 2017, Inikoo

  Version 2.0
*/

require_once '../vendor/autoload.php';
require 'keyring/dns.php';
require_once 'utils/sentry.php';


if (!empty($_REQUEST['original_url'])) {

    if (preg_match('/\.(jpg|png|gif|xml|txt|ico|css|js)$/i', $_REQUEST['original_url'])) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
}

include_once('common.php');
$webpage_key = $website->get_system_webpage_key('not_found.sys');

if (!$webpage_key) {
    header("HTTP/1.0 404 Not Found");
    exit;
}


include 'display_webpage.php';
