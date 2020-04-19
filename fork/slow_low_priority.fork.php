<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created:  23-09-2019 22:07:31 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 2.0
*/

include_once 'utils/object_functions.php';
include_once 'utils/new_fork.php';

function fork_take_webpage_screenshot($job) {
    global $account, $db;// remove the global $db and $account is removed

    if (!$_data = get_fork_metadata($job)) {
        return true;
    }

    list($account, $db, $data, $editor,$ES_hosts) = $_data;


    $webpage       = get_object('Webpage', $data['webpage_key']);
    $webpage->fork = true;

    try {
        $webpage->update_screenshots('Desktop');
    } catch (Exception $e) {

        echo $e->getMessage();
        print "error $webpage->get('URL')\n";
    }


}

function fork_redo_time_series($job) {

    include_once 'class.Timeserie.php';

    global $account, $db;// remove the global $db and $account is removed


    if (!$_data = get_fork_metadata($job)) {
        return true;
    }

    list($account, $db, $data, $editor,$ES_hosts) = $_data;

    require_once 'conf/timeseries.php';
    $timeseries = get_time_series_config();

    $object_name = $data['object'];


    switch ($object_name) {
        case 'Supplier':
            $supplier = get_object('Supplier', $data['key']);
            if ($supplier->get('Supplier Type') == 'Archived') {
                $date1 = $supplier->get('Supplier Valid From');
                $date2 = $supplier->get('Supplier Valid To');
            } else {
                $date1 = $supplier->get('Supplier Valid From');
                $date2 = gmdate('Y-m-d H:i:s');
            }

            $timeseries_data = $timeseries['Supplier'];
            foreach ($timeseries_data as $time_series_data) {
                $time_series_data['Timeseries Parent']     = $object_name;
                $time_series_data['Timeseries Parent Key'] = $supplier->id;

                $editor['Date']             = gmdate('Y-m-d H:i:s');
                $time_series_data['editor'] = $editor;
                $object_timeseries          = new Timeseries('find', $time_series_data, 'create');
                $sql                        = "delete from `Timeseries Record Dimension` where `Timeseries Record Timeseries Key`=?  ";
                $db->prepare($sql)->execute([$object_timeseries->id]);
                $supplier->update_timeseries_record($object_timeseries, $date1, $date2);

            }

            break;
        case 'PartCategory':

            $category = get_object('Category', $data['key']);
            if ($category->get('Part Category Status') == 'NotInUse') {
                $date1 = $category->get('Part Category Valid From');
                $date2 = $category->get('Part Category Valid To');
            } else {
                $date1 = $category->get('Part Category Valid From');
                $date2 = gmdate('Y-m-d H:i:s');
            }

            $timeseries_data = $timeseries[$category->get('Category Scope').'Category'];
            foreach ($timeseries_data as $time_series_data) {
                $time_series_data['Timeseries Parent']     = 'Category';
                $time_series_data['Timeseries Parent Key'] = $category->id;

                $editor['Date']             = gmdate('Y-m-d H:i:s');
                $time_series_data['editor'] = $editor;
                $object_timeseries          = new Timeseries('find', $time_series_data, 'create');
                $sql                        = "delete from `Timeseries Record Dimension` where `Timeseries Record Timeseries Key`=?  ";
                $db->prepare($sql)->execute([$object_timeseries->id]);
                $category->update_part_timeseries_record($object_timeseries, $date1, $date2);

            }


            break;
    }


}

