<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:15 September 2015 14:14:14 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'common.php';
$sql = "UPDATE `User Log Dimension` SET `Logout Date`=NOW()  WHERE `Session ID`=?";
$db->prepare($sql)->execute(
    array(
        session_id()
    )
);


$redis->hSet('_IUObj'.$account->get('Code').':'.$user->id, 'logged_in', false);
session_destroy();
$_SESSION=[];

header('Location: login.php');
exit;



