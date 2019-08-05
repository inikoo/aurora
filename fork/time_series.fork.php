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

function fork_time_series($job) {


    if (!$_data = get_fork_data($job)) {
        return;
    }


    $db        = $_data['db'];
    $fork_data = $_data['fork_data'];
    $fork_key  = $_data['fork_key'];
    $session   = $_data['session'];

    //$inikoo_account_code = $_data['inikoo_account_code'];


    switch ($fork_data['type']) {
        case 'timeseries':
            include_once 'class.Timeserie.php';
            $object         = get_object($fork_data['parent'], $fork_data['parent_key']);
            $object->editor = $fork_data['editor'];
            $object->create_timeseries($fork_data['time_series_data'], $fork_key);

            break;
        case 'isf':
            ////No longer in use


            break;

    }

    return false;
}


?>
