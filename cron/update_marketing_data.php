<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 September 2017 at 14:37:58 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'utils/object_functions.php';

$sql = sprintf(
    'SELECT `Deal Campaign Key`  FROM `Deal Campaign Dimension`  '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $campaign = get_object('Deal_Campaign', $row['Deal Campaign Key']);

        $campaign->update_number_of_deals();
        $campaign->update_usage();
        $campaign->update_history_records_data();


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


$sql = sprintf(
    'SELECT `Deal Key`  FROM `Deal Dimension`  '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $campaign = get_object('Deal', $row['Deal Key']);

        $campaign->update_number_components();
        $campaign->update_usage();
        $campaign->update_history_records_data();


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}



?>
