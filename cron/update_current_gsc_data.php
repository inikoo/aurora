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
require_once 'utils/date_functions.php';

$webmasters = initialize_webmasters();



foreach(array('1 Quarter','Last Month','Quarter To Day','Month To Day','Year To Day','1 Year','1 Month') as $interval){

    $interval_dates=calculate_interval_dates($db,$interval);



    $date_interval = array(
        'From' => preg_replace('/\s\d{2}\:\d{2}\:\d{2}$/','',$interval_dates[1]),
        'To'   =>preg_replace('/\s\d{2}\:\d{2}\:\d{2}$/','',$interval_dates[2]),
    );


    $sql  = 'select `Website Key`,`Website URL` from `Website Dimension` where `Website Status`="Active"';
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array()
    );
    while ($row = $stmt->fetch()) {

        $domain     = preg_replace('/^www\./', '', $row['Website URL']);



        get_gsc_website($db, $webmasters, $domain, $date_interval, $row['Website Key'],$interval);


        get_gsc_webpage($db, $webmasters, $domain, $date_interval, $row['Website Key'],$interval);

        get_gsc_website_queries($db, $webmasters, $domain, $date_interval, $row['Website Key'],$interval);

        get_gsc_webpage_queries($db, $webmasters, $domain, $date_interval, $row['Website Key'],$interval);

        // sleep(5);

    }



}




$sql       = 'SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=? AND `Date`<=? order by `Date` desc';
$stmt_date = $db->prepare($sql);
$stmt_date->execute(
    array(
        date('Y-m-d', strtotime('now -10 days')),
        date('Y-m-d')
    )
);
while ($row_date = $stmt_date->fetch()) {

    $date_interval = array(
        'From' => $row_date['Date'],
        'To'   => $row_date['Date'],
    );

   // print_r($date_interval);


    $sql  = 'select `Website Key`,`Website URL` from `Website Dimension` where `Website Status`="Active"';
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array()
    );
    while ($row = $stmt->fetch()) {

        $domain     = preg_replace('/^www\./', '', $row['Website URL']);



        get_gsc_website($db, $webmasters, $domain, $date_interval, $row['Website Key']);

        get_gsc_webpage($db, $webmasters, $domain, $date_interval, $row['Website Key']);
        get_gsc_website_queries($db, $webmasters, $domain, $date_interval, $row['Website Key']);
        get_gsc_webpage_queries($db, $webmasters, $domain, $date_interval, $row['Website Key']);

    }


}





$sql  = 'select `Website Key`,`Website URL` from `Website Dimension` where `Website Status`="Active"';
$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {
    $website=get_object('Website',$row['Website Key']
    );
    $website->update_gsc_data();

}