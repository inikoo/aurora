<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 November 2018 at 16:41:20 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';
include_once 'class.PartLocation.php';

$print_est = false;


$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Stack ISF)',
    'Author Alias' => 'System (Stack ISF)',
    'v'            => 3


);

$intervals = array(
    'Total',
    'Year To Day',
    'Quarter To Day',
    'Month To Day',
    'Week To Day',
    'Today',
    '1 Year',
    '1 Month',
    '1 Week',
);

$sql = "SELECT count(*) AS num FROM `Stack Dimension`  where `Stack Operation`='update_isf'";

$stmt = $db->prepare($sql);
$stmt->execute();
if ($row = $stmt->fetch()) {
    $total = $row['num'];
} else {
    $total = 0;
}


$lap_time0 = date('U');
$lap_time1 = date('U');

$contador = 0;


$sql = sprintf(
    "SELECT `Stack BiKey Object Key One`,`Stack BiKey Object Key Two` FROM `Stack BiKey Dimension`  where `Stack BiKey Operation`='update_ISF' ORDER BY RAND()"
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $sql = sprintf('select `Stack BiKey Key` from `Stack BiKey Dimension` where `Stack BiKey Object Key One`=%d and `Stack BiKey Object Key Two`=%d ', $row['Stack BiKey Object Key One'], $row['Stack BiKey Object Key Two']);

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {

                $sql = sprintf('delete from `Stack BiKey Dimension`   where `Stack BiKey Object Key One`=%d and `Stack BiKey Object Key Two`=%d ', $row['Stack BiKey Object Key One'], $row['Stack BiKey Object Key Two']);
                $db->exec($sql);

                $editor['Date'] = gmdate('Y-m-d H:i:s');


                $date=gmdate('Y-m-d');

                $part=get_object('Part',$row['Stack BiKey Object Key One']);
                $part->editor = $editor;
                $part->update_part_inventory_snapshot_fact($date, $date);



                $part_location         = new PartLocation($row['Stack BiKey Object Key One'].'_'.$row['Stack BiKey Object Key Two']);
                $part_location->editor = $editor;




                if ($part_location->location->id) {
                    $date = gmdate('Y-m-d H:i:s');

                    $sql = sprintf(
                        'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (%s,%s,%s,%d) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=%s ,`Stack Counter`=`Stack Counter`+1 ', prepare_mysql($date), prepare_mysql($date), prepare_mysql('warehouse_ISF'), $part_location->location->get('Location Warehouse Key'), prepare_mysql($date)

                    );
                    $db->exec($sql);
                }


                if ($part_location->get('Quantity On Hand') < 0) {

                    $suppliers = $part_location->part->get_suppliers();
                    foreach ($suppliers as $supplier_key) {
                        $supplier_production         = get_object('Supplier_Production', $supplier_key);
                        $supplier_production->editor = $editor;
                        if ($supplier_production->id) {
                            $supplier_production->update_locations_with_errors();
                        }
                    }
                }


            }
        }


        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'Pa '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
                )."h  ($contador/$total) \r";
        }

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}
if ($total > 0) {
    printf("%s: IS  %s/%s %.2f min \n", gmdate('Y-m-d H:i:s'), $contador, $total, ($lap_time1 - $lap_time0) / 60);
}



