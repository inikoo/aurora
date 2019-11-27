<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:12-09-2019 17:58:09 MYT Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';
include_once 'class.Webpage_Type.php';


$sql  = "select `Website Key`,`Website URL` from `Website Dimension` where `Website Status`='Active' ";
$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {
    $website = get_object('Website', $row['Website Key']);
    $website->update_gsc_data();


}


$sql  = "select `Website Key`,`Website URL` from `Website Dimension` where `Website Status`='Active' ";
$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {
    $website = get_object('Website', $row['Website Key']);
    $website->update_users_data();
    $website->update_website_webpages_data();
    $website->update_sitemap();

    include_once 'conf/webpage_types.php';

    foreach(get_webpage_types() as $webpage_type_data){
        $webpage_type = new Webpage_Type('website_code', $website->id, $webpage_type_data['code']);
        $webpage_type->update_number_webpages();
    }

}