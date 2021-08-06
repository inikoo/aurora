<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2018 at 14:43:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'utils/natural_language.php';
require_once 'utils/order_functions.php';

$sql = sprintf("SELECT `Deal Key` FROM `Deal Dimension` where `Deal Key`=2 ");

$sql = sprintf("SELECT `Deal Key` FROM `Deal Dimension` ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal = get_object('Deal', $row['Deal Key']);

        $deal->update_number_components();
        $deal->update_usage();
        $deal->update_deal_term_allowances();


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}



$sql = sprintf("SELECT `Deal Component Key` FROM `Deal Component Dimension` where `Deal Component Deal Key`=%d ",10157);
$sql = sprintf("SELECT `Deal Component Key` FROM `Deal Component Dimension` ");

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal_component = get_object('DealComponent', $row['Deal Component Key']);

        $deal_component->update_usage();

        $deal_component->update_deal_component_term_allowances();




    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}





$sql = sprintf("SELECT `Deal Campaign Key` FROM `Deal Campaign Dimension`  ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal_campaign = get_object('DealCampaign', $row['Deal Campaign Key']);

        $deal_campaign->update_number_of_deals();
        $deal_campaign->update_usage();

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}




$sql = sprintf("SELECT `Deal Key` FROM `Deal Dimension`  left join `Store Dimension` on (`Deal Store Key`=`Store Key`) where  `Deal Expiration Date` is not null  and `Deal Status` not in ('Finished') ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal = get_object('Deal', $row['Deal Key']);

        $deal->update_usage();
        $deal->update_number_components();
        $deal->update_deal_term_allowances();


        $deal->update_status_from_dates(false);
        foreach ($deal->get_deal_components('objects', 'all') as $component) {
            $component->update_status_from_dates();
        }


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

