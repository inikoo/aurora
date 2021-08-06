<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 February 2018 at 14:21:19 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/
require_once __DIR__.'/cron_common.php';


$sql = sprintf('SELECT `Customer Poll Query Option Key` FROM `Customer Poll Query Option Dimension` ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $poll_option = get_object('Customer_Poll_Query_Option', $row['Customer Poll Query Option Key']);
        $poll_option->update_poll_query_option_customers();
    }
}


$sql = sprintf('SELECT `Customer Poll Query Key` FROM `Customer Poll Query Dimension` ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $poll_query = get_object('Customer_Poll_Query', $row['Customer Poll Query Key']);
        $poll_query->update_answers();
    }
}


