<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 April 2016 at 17:39:29 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
/** @var PDO $db */


$print_est=true;

$where = " where `Part SKU`=5291 ";
//	$where=" where `Part Reference` like 'jbb-%' ";
$where= ' where `Part SKU`=61065';

$where = "";

$sql = sprintf("SELECT count(*) AS num FROM `Part Dimension` %s", $where);
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
}

$lap_time0 = date('U');
$contador  = 0;
//$where='';

$sql= 'select `Part SKU` from `Part Dimension` '.$where;
//$sql = sprintf(
//    'SELECT `Part SKU` FROM `Part Dimension` ORDER BY `Part SKU` DESC  '
//);



if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        /**
         * @var $part \Part
         */
        $part = get_object('Part', $row['Part SKU']);

        print $part->get('Reference');

        //foreach($part->get_locations('part_location_object') as $part_location){
        //    $part_location->update_stock();
        // }
        $part->update_on_demand();

        $part->update_sales_from_invoices('1 Quarter',true,false);
        $part->update_stock();
        $part->update_stock_in_paid_orders();

        $part->update_available_forecast();


        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'Pa '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
                )."h  ($contador/$total) \r";
        }


    }

}



