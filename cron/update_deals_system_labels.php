<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Thu 11 April  2019 14:43:58 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'utils/natural_language.php';

$sql = sprintf("SELECT `Deal Key` FROM `Deal Dimension`  ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal = get_object('Deal', $row['Deal Key']);
        $deal->update_deal_term_allowances();

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}



$sql = sprintf("SELECT `Deal Component Key` FROM `Deal Component Dimension` ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal_component = get_object('DealComponent', $row['Deal Component Key']);
        $deal_component->update_deal_component_term_allowances();

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}
