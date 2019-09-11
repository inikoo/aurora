<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11-09-2019 15:04:07 MYT, Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/google_api_functions.php';
require_once 'keyring/google_dns.php';
require_once 'class.Page.php';


$sql  = 'select `Website Key`,`Website URL` from `Website Dimension` where `Website Status`="Active"';
$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {

    $domain     = preg_replace('/^www\./', '', $row['Website URL']);
    $webmasters = initialize_webmasters();
    get_gsc_website_dates($db, $webmasters, $domain, $row['Website Key']);


    $sql = 'select `Website GSC Date` from `Website GSC Timeseries` where `Website GSC Website Key`=? and `Website GSC Type`="Day"   ';


    $stmt2 = $db->prepare($sql);
    $stmt2->execute(
        array($row['Website Key'])
    );
    while ($row2 = $stmt2->fetch()) {

        $date_interval = array(
            'From' => $row2['Website GSC Date'],
            'To'   => $row2['Website GSC Date'],
        );


        get_gsc_webpage($db, $webmasters, $domain, $date_interval, $row['Website Key']);
        get_gsc_website_queries($db, $webmasters, $domain, $date_interval, $row['Website Key']);
        get_gsc_webpage_queries($db, $webmasters, $domain, $date_interval, $row['Website Key']);

    }


}

