<?php
/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 9 May 2017 at 22:06:41 GMT-5, CdMx Mexico

  Copyright (c) 2017, Inikoo

  Version 2.0
*/
if (!empty($_REQUEST['original_url'])) {
    if (preg_match('/\.(jpg|png|gif|xml|txt|ico|css|js|woff2)$/i', $_REQUEST['original_url'])) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
}
$is_404=true;

require __DIR__.'direct_process.php';