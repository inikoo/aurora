<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2018 at 14:43:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/order_functions.php';


$sql = sprintf("SELECT `Deal Campaign Key` FROM `Deal Campaign Dimension`  ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal_campaign = get_object('DealCampaign', $row['Deal Campaign Key']);

        $deal_campaign->update_number_of_deals();


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}




$sql = sprintf("SELECT `Deal Key` FROM `Deal Dimension`  left join `Store Dimension` on (`Deal Store Key`=`Store Key`) where `Store Version`=2 and `Deal Expiration Date` is not null  and `Deal Status` not in ('Finished') ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal = get_object('Deal', $row['Deal Key']);

        $deal->update_usage();
        $deal->update_number_components();
        $deal->update_term_allowances();


        $deal->update_status_from_dates(false);
        foreach ($deal->get_deal_components('objects', 'all') as $component) {
            $component->update_status_from_dates();
        }


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}



?>