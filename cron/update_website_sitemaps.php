<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:12-09-2019 17:58:09 MYT Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
include_once 'class.Webpage_Type.php';


$sql  = "select `Website Key`,`Website URL` from `Website Dimension` where `Website Status`='Active' ";
$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {
    $website = get_object('Website', $row['Website Key']);

    $website->update_sitemap();
}

