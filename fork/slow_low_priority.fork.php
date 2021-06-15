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

    list($account, $db, $data, $editor, $ES_hosts) = $_data;


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

    list($account, $db, $data, $editor, $ES_hosts) = $_data;

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

function fork_update_part_products_availability($job) {

    global $account, $db;// remove the global $db and $account is removed


    if (!$_data = get_fork_metadata($job)) {
        return true;
    }

    list($account, $db, $data, $editor, $ES_hosts) = $_data;

    /**
     * @var $part \Part
     */

    $part = get_object('Part', $data['part_sku']);
    print $part->get('Reference')."\n";

    if (isset($data['editor'])) {
        $data['editor']['Date'] = gmdate('Y-m-d H:i:s');
        $part->editor           = $data['editor'];
    } else {
        $part->editor = $editor;
    }


    $part->update_available_forecast();
    $part->update_stock_status();

    foreach ($part->get_products('objects') as $product) {
        if (isset($data['editor'])) {
            $data['editor']['Date'] = gmdate('Y-m-d H:i:s');
            $product->editor        = $data['editor'];
        } else {
            $product->editor = $editor;
        }

        $product->fork = true;

        $product->update_availability(false);
    }


}


function fork_long_operations($job) {

    global $account, $db;// remove the global $db and $account is removed

    if (!$_data = get_fork_metadata($job)) {
        return true;
    }

    list($account, $db, $data, $editor, $ES_hosts) = $_data;


    print $data['type']."\n";

    switch ($data['type']) {

        case 'update_parts_stock_run':


            foreach ($data['parts_data'] as $part_sku => $from_date) {


                $part         = get_object('Part', $part_sku);
                $part->editor = $data['editor'];

                if ($account->get('Account Add Stock Value Type') == 'Last Price') {
                    // update if is last placement


                    $sql = sprintf(
                        'select `ITF POTF Costing Done POTF Key`,(`Inventory Transaction Amount`/`Inventory Transaction Quantity`) as value_per_sko 
from    `ITF POTF Costing Done Bridge` B  left join     `Inventory Transaction Fact` ITF   on  (B.`ITF POTF Costing Done ITF Key`=`Inventory Transaction Key`)  
    left join `Purchase Order Transaction Fact` POTF on  (`Purchase Order Transaction Fact Key`=`ITF POTF Costing Done POTF Key`) 
where  `Inventory Transaction Amount`>0 and `Inventory Transaction Quantity`>0   and  `Inventory Transaction Section`="In"    and ITF.`Part SKU`=%d    order by `Date` desc  limit 1 ', $part->id
                    );


                    //  print "$sql\n";

                    if ($result = $db->query($sql)) {
                        foreach ($result as $row) {


                            print_r($row);


                            $part->update_field_switcher('Part Cost in Warehouse', $row['value_per_sko'], 'no_history');
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                }


            }


            foreach ($data['parts_data'] as $part_sku => $from_date) {


                $part         = get_object('Part', $part_sku);
                $part->editor = $data['editor'];


                $part->update_stock_run();
                $part->update_part_inventory_snapshot_fact($from_date);


            }

            $sql = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension`');
            if ($result2 = $db->query($sql)) {
                foreach ($result2 as $row2) {
                    $warehouse = get_object('Warehouse', $row2['Warehouse Key']);
                    $warehouse->update_inventory_snapshot($data['all_parts_min_date'], gmdate('Y-m-d'));
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            break;
        case 'part_stock_run':
            /**
             * @var $part \Part
             */
            $part = get_object('Part', $data['part_sku']);
            $part->update_stock_run();
            break;

        case 'redo_day_ISF':
            $date = $data['date'];


            $sql = sprintf(
                'SELECT `Part SKU` FROM `Part Dimension`  ORDER BY `Part SKU` desc '
            );

            // print "$sql\n";

            if ($result2 = $db->query($sql)) {
                foreach ($result2 as $row2) {
                    $part = get_object('Part', $row2['Part SKU']);
                    $part->update_part_inventory_snapshot_fact($date, $date);

                }
            }


            $sql = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension`');
            if ($result2 = $db->query($sql)) {
                foreach ($result2 as $row2) {
                    $warehouse = get_object('Warehouse', $row2['Warehouse Key']);
                    $warehouse->update_inventory_snapshot($date);
                }
            }
            break;

        case 'update_parts_cost':


            $sql  = "SELECT `Part SKU` FROM `Part Dimension`  where `Part Status`!=?";
            $stmt = $db->prepare($sql);
            $stmt->execute(['Not In Use']);
            while ($row = $stmt->fetch()) {
                $part = get_object('Part', $row['Part SKU']);
                $part->update_cost();
            }


            break;
        case 'update_deals_status_from_dates':

            $sql = sprintf("SELECT `Deal Key` FROM `Deal Dimension`  left join `Store Dimension` on (`Deal Store Key`=`Store Key`) where `Deal Expiration Date` is not null  and `Deal Status` not in ('Finished')");
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {


                    $deal = get_object('Deal', $row['Deal Key']);


                    $deal->update_status_from_dates(false);
                    foreach ($deal->get_deal_components('objects', 'all') as $component) {
                        $component->update_status_from_dates();
                    }


                }

            }


            break;


        case 'create_yesterday_timeseries':

            require_once 'class.Timeserie.php';


            require_once 'conf/timeseries.php';

            $timeseries = get_time_series_config();


            $sql = sprintf('SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Part" ORDER BY  `Category Key` DESC');

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $category = get_object('Category', $row['Category Key']);
                    if ($category->get('Part Category Status') != 'NotInUse' or date('Y-m-d') == date('Y-m-d', strtotime($category->get('Part Category Valid To').' +0:00'))) {
                        if (!array_key_exists($category->get('Category Scope').'Category', $timeseries)) {
                            continue;
                        }

                        $timeseries_data = $timeseries[$category->get('Category Scope').'Category'];
                        //print_r($timeseries_data);
                        foreach ($timeseries_data as $timeserie_data) {

                            $editor['Date']                          = gmdate('Y-m-d H:i:s');
                            $timeserie_data['editor']                = $editor;
                            $timeserie_data['Timeseries Parent']     = 'Category';
                            $timeserie_data['Timeseries Parent Key'] = $category->id;
                            $timeseries                              = new Timeseries(
                                'find', $timeserie_data, 'create'
                            );
                            $category->update_part_timeseries_record($timeseries, gmdate('Y-m-d', strtotime('now -1 day')), gmdate('Y-m-d', strtotime('now -1 day')));
                        }
                    }
                }

            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


            $sql = sprintf('SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Product" ORDER BY  `Category Key` DESC');

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $category = get_object('Category', $row['Category Key']);
                    $category->update_product_category_new_products();
                    if ($category->get('Product Category Status') != 'Discontinued' or date('Y-m-d') == date('Y-m-d', strtotime($category->get('Product Category Valid To').' +0:00'))) {
                        if (!array_key_exists($category->get('Category Scope').'Category', $timeseries)) {
                            continue;
                        }

                        $timeseries_data = $timeseries[$category->get('Category Scope').'Category'];
                        //print_r($timeseries_data);
                        foreach ($timeseries_data as $timeserie_data) {

                            $editor['Date']                          = gmdate('Y-m-d H:i:s');
                            $timeserie_data['editor']                = $editor;
                            $timeserie_data['Timeseries Parent']     = 'Category';
                            $timeserie_data['Timeseries Parent Key'] = $category->id;
                            $timeseries                              = new Timeseries('find', $timeserie_data, 'create');
                            $category->update_product_timeseries_record($timeseries, gmdate('Y-m-d', strtotime('now -1 day')), gmdate('Y-m-d', strtotime('now -1 day')));
                        }
                    }
                }

            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


            break;


    }


}