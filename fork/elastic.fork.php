<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 15 November 2014 11:35:49 GMT, Langley Mill Uk
 Copyright (c) 2014, Inikoo

 Version 2.0
*/


function fork_elastic($job)
{
    global $account, $db;// remove the global $db and $account is removed

    if (!$_data = get_fork_metadata_for_es($job)) {
        return true;
    }

    list($account, $db, $data, $editor, $ES_hosts) = $_data;


    switch ($data['type']) {

        case 'update_inventory_snapshot':

            $warehouse=get_object('Warehouse', $data['warehouse_key']);
            $warehouse->update_inventory_snapshot($data['date']);

            break;

        case 'create_elastic_index_object':
            print  $data['object']." ".$data['object_key']."  \n";


            $object = get_object($data['object'], $data['object_key']);
            if ($object->id) {
                try {
                    $object->index_elastic_search($ES_hosts, false, $data['indices']);
                } catch (Exception $e) {
                    echo 'Caught exception indexing: ', $e->getMessage(), "\n";
                }
            }

            break;
        case 'delete_elastic_index_object':
            print  $data['object']." ".$data['object_key']."  (delete)\n";
            $object = get_object($data['object'], $data['object_key']);
            if ($object->id) {
                try {
                    $object->delete_index_elastic_search($ES_hosts);
                } catch (Exception $e) {
                    echo 'Caught exception deleting : ', $e->getMessage(), "\n";
                }
            }
            break;

        default:
            break;
    }


    return false;
}


