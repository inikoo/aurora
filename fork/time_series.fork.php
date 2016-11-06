<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 5 November 2016 at 18:04:29 GMT+8, Cyberjaya, Malaysia
 Created: 2016
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/object_functions.php';
include_once 'class.Timeserie.php';

function fork_time_series($job) {


    if (!$_data = get_fork_data($job)) {
        return;
    }


    $db                  = $_data['db'];
    $fork_data           = $_data['fork_data'];
    $fork_key            = $_data['fork_key'];
    //$inikoo_account_code = $_data['inikoo_account_code'];


    $object         = get_object($fork_data['parent'],$fork_data['parent_key']);
    $object->editor = $fork_data['editor'];



    $object->create_timeseries($fork_data['time_series_data'],$fork_key);



    return false;
}


?>
