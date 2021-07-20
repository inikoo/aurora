<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 January 2016 at 16:18:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

require_once 'class.Timeserie.php';
require_once 'class.Store.php';
require_once 'class.Invoice.php';
require_once 'class.Category.php';
require_once 'class.Supplier.php';
require_once 'class.Warehouse.php';

require_once 'utils/date_functions.php';
require_once 'conf/timeseries.php';

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$timeseries = get_time_series_config();

//families();
//part_families();
//suppliers();
account();
stores();

//warehouses();

function warehouses() {

    global $db, $editor, $timeseries;

    $sql = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension`  ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $warehouse = new Warehouse($row['Warehouse Key']);


            $timeseries_data = $timeseries['Warehouse'];

            foreach ($timeseries_data as $time_series_data) {

                $editor['Date']             = gmdate('Y-m-d H:i:s');
                $time_series_data['editor'] = $editor;
                $warehouse->create_timeseries($time_series_data);

            }
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit($sql);
    }


}

function suppliers() {

    global $db, $editor, $timeseries;

    $sql = sprintf('SELECT `Supplier Key` FROM `Supplier Dimension`  ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $supplier = new Supplier($row['Supplier Key']);


            $timeseries_data = $timeseries['Supplier'];

            foreach ($timeseries_data as $time_series_data) {

                $editor['Date']             = gmdate('Y-m-d H:i:s');
                $time_series_data['editor'] = $editor;
                $supplier->create_timeseries($time_series_data);

            }
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit($sql);
    }


}


function families() {

    global $db, $editor, $timeseries;

    $sql = sprintf(
        'SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Product" AND `Category Key`=14797  '
    );
    $sql = sprintf(
        'SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Product" '
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $category = new Category($row['Category Key']);

            if (!array_key_exists(
                $category->get('Category Scope').'Category', $timeseries
            )) {
                continue;
            }

            $timeseries_data = $timeseries[$category->get('Category Scope').'Category'];
            print "creating ".$category->get('Code')." category \n";

            foreach ($timeseries_data as $time_series_data) {

                $editor['Date']             = gmdate('Y-m-d H:i:s');
                $time_series_data['editor'] = $editor;
                $category->create_timeseries($time_series_data);

            }
        }

    }
}


function stores() {

    global $db, $editor, $timeseries;
    $sql = 'SELECT `Store Key` FROM `Store Dimension`  ';

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $store = new Store($row['Store Key']);

            $timeseries_data = $timeseries['Store'];

            foreach ($timeseries_data as $time_series_data) {

                $editor['Date']             = gmdate('Y-m-d H:i:s');
                $time_series_data['editor'] = $editor;


                print "Store ".$store->get('Code').' '.$time_series_data['Timeseries Frequency']."\n";


                $store->create_timeseries($time_series_data);

            }
        }

    }

}

function account() {

    global  $editor, $timeseries;
    $account = get_object('Account', 1);

    $timeseries_data = $timeseries['Account'];

    foreach ($timeseries_data as $time_series_data) {

        $editor['Date']             = gmdate('Y-m-d H:i:s');
        $time_series_data['editor'] = $editor;


        print "Account ".$time_series_data['Timeseries Frequency']."\n";


        $account->create_timeseries($time_series_data);

    }


}


function part_families() {

    global $db, $editor, $timeseries;

    $sql = sprintf(
        'SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Part" AND `Category Key`=27832  '
    );
    $sql = sprintf('select `Category Key` from `Category Dimension` where `Category Scope`="Part" order by  `Category Key` desc');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $category = new Category($row['Category Key']);


            if (!array_key_exists(
                $category->get('Category Scope').'Category', $timeseries
            )) {
                continue;
            }

            $timeseries_data = $timeseries[$category->get('Category Scope').'Category'];
            print "creating ".$category->id." ".$category->get('Code')." category \n";
            foreach ($timeseries_data as $time_series_data) {

                $editor['Date']             = gmdate('Y-m-d H:i:s');
                $time_series_data['editor'] = $editor;


                $category->create_timeseries($time_series_data);


            }
        }

    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
    }

}

