<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:4 April 2017 at 14:00:39 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';


$account->load_acc_data();
$account->update_dispatching_time_data('1m');
$account->update_sitting_time_in_warehouse();



$sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $store = get_object('Store', $row['Store Key']);

        $store->load_acc_data();



        $store->update_dispatching_time_data('1m');
        $store->update_sitting_time_in_warehouse();



    }
}