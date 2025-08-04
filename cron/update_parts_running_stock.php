<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 October 2017 at 22:31:32 GMT+8, Plane Bali - Kuala Lumpur
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'utils/object_functions.php';
/** @var PDO $db */

$where='';
$print_est = true;

$sql = sprintf("SELECT count(*) AS num FROM `Part Dimension` %s", $where);
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

$lap_time0 = date('U');
$contador  = 0;



$sql = sprintf('SELECT `Part SKU` FROM `Part Dimension`  ORDER BY `Part SKU`   ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $part = get_object('Part', $row['Part SKU']);


        $part->update_stock_run();

        foreach ($part->get_locations('part_location_object', '', true) as $part_location) {
            $part_location->update_stock();
        }

        $part->update_stock();



        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'Pa '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
                )."h  ($contador/$total) \r";
        }

    }

} 

