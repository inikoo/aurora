<?php
/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 27 July 2017 at 09:10:40 CEST, Tranava, Slovakia

  Copyright (c) 2017, Inikoo

  Version 2.0
*/


include_once('common.php');
$webpage_key = $website->get_system_webpage_key('reset_pwd.sys');

if (!$webpage_key) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

include_once('webpage.php');
?>