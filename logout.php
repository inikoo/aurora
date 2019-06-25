<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:15 September 2015 14:14:14 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'common.php';
$sql = sprintf(
    "UPDATE `User Log Dimension` SET `Logout Date`=NOW()  WHERE `Session ID`=%s", prepare_mysql($session->get('getId'))
);
$db->exec($sql);

$redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'logged_in', false);



$session->migrate();
$session->invalidate();

unset($_SESSION);
header('Location: login.php');
exit;



