<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 October 2018 at 13:22:55 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'class.Part.php';
require_once 'class.Category.php';


$print_est = true;
$where     = "";

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


$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension`  ORDER BY `Part SKU` desc  '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = get_object('Part', $row['Part SKU']);


        $part->update_commercial_value();


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


?>
