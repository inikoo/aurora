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


//$db->exec('truncate `Inventory Warehouse Spanshot Fact`; truncate `Inventory Spanshot Fact`');


$warehouse=get_object('Warehouse',1);


$from = date("Y-m-d", strtotime($warehouse->get('Warehouse Valid From')));
$to   = date("Y-m-d", strtotime('now'));


//$from=date("Y-m-d");
//$to='2013-09-05';





$sql = sprintf(
    "SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=%s AND `Date`<=%s ORDER BY `Date` DESC", prepare_mysql($from), prepare_mysql($to)
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $where = ' `Part SKU`=46433';
        $where = '  true';

        $count = 0;
        $sql   = sprintf(
            'SELECT `Part SKU` FROM `Part Dimension` WHERE %s  ORDER BY `Part SKU` ', $where
        );

        // print "$sql\n";

        if ($result2 = $db->query($sql)) {
            foreach ($result2 as $row2) {

                //print "----".$row2['Part SKU']."\n";

                $sql = sprintf(
                    "SELECT `Location Key`  FROM `Inventory Transaction Fact` WHERE  `Inventory Transaction Type` LIKE 'Associate' AND  `Part SKU`=%d AND `Date`<=%s GROUP BY `Location Key`",
                    $row2['Part SKU'], prepare_mysql($row['Date'].' 23:59:59')
                );


                if ($result3 = $db->query($sql)) {
                    foreach ($result3 as $row3) {
                        print $row['Date'].' '.$row2['Part SKU'].'_'.$row3['Location Key']."\r";

                        $part_location = new PartLocation($row2['Part SKU'].'_'.$row3['Location Key']);
                        $part_location->update_stock_history_date($row['Date']);


                    }
                }


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


?>
