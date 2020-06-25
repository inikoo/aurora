<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6:25 pm Thursday, 25 June 2020 (MYT MYT Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/
require_once 'common.php';


$sql  = sprintf('SELECT `Staff Key` FROM `Staff Dimension` ');
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {
    $staff = get_object('Staff', $row['Staff Key']);
    $sql = "insert into `Staff Operative Data` (`Staff Operative Key`) values (?)";
    $db->prepare($sql)->execute([$staff->id]);

    $operative = get_object('Operative', $staff->id);
    $operative->update_operative_status();
    $operative->update_operative_stats();


}


