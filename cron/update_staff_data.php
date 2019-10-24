<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Thu 24 Oct 2019 00:55:52 +0800 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/
require_once 'common.php';


$sql  = sprintf('SELECT `Staff Key` FROM `Staff Dimension`  ');
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {
    $staff = get_object('Staff', $row['Staff Key']);
    $staff->update_attachments_data();
}

