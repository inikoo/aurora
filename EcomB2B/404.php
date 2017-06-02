<?php
/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 9 May 2017 at 22:06:41 GMT-5, CdMx Mexico  

  Copyright (c) 2017, Inikoo

  Version 2.0
*/



$not_found_current_page = $_REQUEST['original_url'];

if (preg_match('/\.(jpg|png|gif|xml|txt|ico|css|js)$/i', $not_found_current_page)) {
    header("HTTP/1.0 404 Not Found");
    exit();
}



include_once('common.php');
$webpage_key = $website->get_system_webpage('not_found.sys');

if (!$webpage_key) {
    header("HTTP/1.0 404 Not Found");
    exit;
}





//exit("::: Not found");

include_once('webpage.php');
?>