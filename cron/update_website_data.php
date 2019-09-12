<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:12-09-2019 17:58:09 MYT Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';


$sql  = 'select `Website Key`,`Website URL` from `Website Dimension` where `Website Status`="Active"';
$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {
    $website = get_object('Website', $row['Website Key']);
    $website->update_gsc_data();


}


$sql  = 'select `Website Key`,`Website URL` from `Website Dimension`';
$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {
    $website = get_object('Website', $row['Website Key']);
    $website->update_users_data();
    $website->update_website_webpages_data();

}