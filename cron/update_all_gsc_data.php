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

$webmasters = initialize_webmasters();


$sql  = 'select `Website Key`,`Website URL` from `Website Dimension` where `Website Status`="Active"';
$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {

    $domain     = preg_replace('/^www\./', '', $row['Website URL']);
    get_gsc_website_dates($db, $webmasters, $domain, $row['Website Key']);


}


$sql       = 'SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=? AND `Date`<=? order by `Date`';
$stmt_date = $db->prepare($sql);
$stmt_date->execute(
    array(
        date('Y-m-d', strtotime('now -16 months')),
        date('Y-m-d')
    )
);
while ($row_date = $stmt_date->fetch()) {

    $date_interval = array(
        'From' => $row_date['Date'],
        'To'   => $row_date['Date'],
    );



    $sql  = 'select `Website Key`,`Website URL` from `Website Dimension` where `Website Status`="Active"';
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array()
    );
    while ($row = $stmt->fetch()) {

        $domain     = preg_replace('/^www\./', '', $row['Website URL']);




        get_gsc_webpage($db, $webmasters, $domain, $date_interval, $row['Website Key']);
        get_gsc_website_queries($db, $webmasters, $domain, $date_interval, $row['Website Key']);
        get_gsc_webpage_queries($db, $webmasters, $domain, $date_interval, $row['Website Key']);

    }


}



