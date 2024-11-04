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





$sql = sprintf("SELECT * FROM `Deal Dimension`   where  `Deal Expiration Date` is not null  and `Deal Status` not in ('Finish') ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {



        $deal = get_object('Deal', $row['Deal Key']);

        $deal_campaign = get_object('DealCampaign', $deal->data['Deal Campaign Key']);


        if(!in_array($deal_campaign->get('Code'),['OR','VL','CU'] )){
            print_r($row);
            $deal_campaign->get('Code')."\n";
           // $deal->update_status_from_dates(false);
           // foreach ($deal->get_deal_components('objects', 'all') as $component) {
           //     $component->update_status_from_dates();
           // }
        }






    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


$sql = sprintf("SELECT *  FROM `Deal Component Dimension`  where  `Deal Component Expiration Date` is not null  and `Deal Component Status` not in ('Finish') ");

if ($result = $db->query($sql)) {
    foreach ($result as $row) {




        $dealComponent = get_object('DealComponent', $row['Deal Component Key']);

        $deal_campaign = get_object('DealCampaign', $dealComponent->data['Deal Component Campaign Key']);


        if(!in_array($deal_campaign->get('Code'),['OR','VL','CU'] )){
            print_r($row);
            $deal_campaign->get('Code')." <dc \n";

            //$dealComponent->update_status_from_dates();
        }

    }

}


