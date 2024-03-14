<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 15 November 2014 11:35:49 GMT, Langley Mill Uk
 Copyright (c) 2014, Inikoo

 Version 2.0
*/


function fork_elastic_low_priority($job)
{
    global $account, $db;// remove the global $db and $account is removed

    if (!$_data = get_fork_metadata($job)) {
        return true;
    }

    list($account, $db, $data, $editor, $ES_hosts) = $_data;


    //return true;
    switch ($data['type']) {
        case 'forked_part_inventory_snapshot_fact':

            if(isset($data['part_sku'])){
                /** @var \Part $part */
                $part = get_object('Part', $data['part_sku']);
                $part->update_part_inventory_snapshot_fact($data['date'], $data['date']);
            }



            break;


        default:
            break;
    }


    return false;
}


