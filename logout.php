<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:15 September 2015 14:14:14 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'common.php';

$sql=sprintf("update `User Log Dimension` set `Logout Date`=NOW()  where `Session ID`=%s", prepare_mysql(session_id()));
mysql_query($sql);

session_regenerate_id();
session_destroy();
unset($_SESSION);
header('Location: login.php');
exit;


?>
