<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Mon 08 April  2019 13:00:33 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';


$print_est = true;



$where = " where `Webpage Website Key`=2 ";

$sql = sprintf("SELECT count(*) AS num FROM `Page Store Dimension` %s", $where);
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
    "SELECT `Page Key` FROM `Page Store Dimension`  %s  ", $where
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $webpage = get_object('Webpage',$row['Page Key']);



        $webpage->update_analytics('Total');
        exit;
        $webpage->update_analytics('Week To Day');
        $webpage->update_analytics('Month To Day');
        $webpage->update_analytics('Quarter To Day');
        $webpage->update_analytics('Year To Day');

        $webpage->update_analytics('1 Year');
        $webpage->update_analytics('1 Quarter');
        $webpage->update_analytics('Last Week');
        $webpage->update_analytics('Last Month');



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
