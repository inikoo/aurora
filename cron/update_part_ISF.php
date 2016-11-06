<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 August 2016 at 19:10:17 GMT+8, Kuala Lumpur, Malaysia
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


//$where=' `Part SKU`=50264';
$where = '  true';

$count = 0;
$sql   = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension` WHERE %s  ORDER BY `Part SKU` DESC', $where
);

// print "$sql\n";

if ($result2 = $db->query($sql)) {
    foreach ($result2 as $row2) {


        $part = new Part($row2['Part SKU']);

        $from = $part->get('Part Valid From');
        $to   = ($part->get('Part Status') == 'Not In Use' ? $part->get(
            'Part Valid To'
        ) : gmdate('Y-m-d H:i:s'));


        $sql = sprintf(
            "DELETE FROM `Inventory Spanshot Fact` WHERE `Part SKU`=%d  AND (`Date`<%s  OR `Date`>%s  )", $part->sku, prepare_mysql($from), prepare_mysql($to)
        );
        $db->exec($sql);


        //$from='2016-03-18';
        //$to='2016-03-18';
        $sql = sprintf(
            "SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s) ORDER BY `Date` DESC", prepare_mysql($from), prepare_mysql($to)
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                $sql = sprintf(
                    "SELECT `Location Key`  FROM `Inventory Transaction Fact` WHERE  `Inventory Transaction Type` LIKE 'Associate' AND  `Part SKU`=%d AND `Date`<=%s GROUP BY `Location Key`",
                    $row2['Part SKU'], prepare_mysql($row['Date'].' 23:59:59')
                );


                if ($result3 = $db->query($sql)) {
                    foreach ($result3 as $row3) {
                        print $row['Date'].' '.$row2['Part SKU'].'_'.$row3['Location Key']."\r";

                        $part_location = new PartLocation(
                            $row2['Part SKU'].'_'.$row3['Location Key']
                        );
                        $part_location->update_stock_history_date($row['Date']);


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


    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


$sql = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension`');
if ($result2 = $db->query($sql)) {
    foreach ($result2 as $row2) {
        $warehouse = new Warehouse($row2['Warehouse Key']);
        //$warehouse->update_inventory_snapshot($row['Date']);
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
