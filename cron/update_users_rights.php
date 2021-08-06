<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:18-09-2019 14:59:51 MYT Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';


$sql  = 'select `User Key` from `User Dimension`';
$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {
    $user= get_object('User', $row['User Key']);
    $user->update_rights();


}

