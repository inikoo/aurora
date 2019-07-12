<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 December 2017 at 11:01:42 GMT, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

require_once 'class.Part.php';
require_once 'class.Location.php';
require_once 'class.PartLocation.php';
require_once 'class.Warehouse.php';
require_once 'class.Product.php';

require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';


$db->exec('truncate `Inventory Warehouse Spanshot Fact`; truncate `Inventory Spanshot Fact`');


$warehouse = get_object('Warehouse', 1);


$from = date("Y-m-d", strtotime($warehouse->get('Warehouse Valid From')));
$to   = date("Y-m-d", strtotime('now'));






$sql = sprintf(
    "SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=%s AND `Date`<=%s ORDER BY `Date` DESC", prepare_mysql($from), prepare_mysql($to)
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $where = ' `Part SKU`=257';
        $where = '  true';

        $sql   = sprintf(
            'SELECT `Part SKU` FROM `Part Dimension` WHERE %s  ORDER BY `Part SKU` desc ', $where
        );

        // print "$sql\n";

        if ($result2 = $db->query($sql)) {
            foreach ($result2 as $row2) {


                $part = get_object('Part', $row2['Part SKU']);

                $part->update_part_inventory_snapshot_fact($row['Date'],$row['Date']);


                print $row['Date'].' '.$part->id.' '.$part->get('Reference')."                          \r";

            }
        }


        $sql = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension`');
        if ($result2 = $db->query($sql)) {
            foreach ($result2 as $row2) {
                $warehouse = new Warehouse($row2['Warehouse Key']);
                $warehouse->update_inventory_snapshot($row['Date']);
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}



