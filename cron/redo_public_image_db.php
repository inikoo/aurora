<?php /** @noinspection ALL */

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20-06-2019 16:33:26 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once __DIR__.'/common.php';
/** @var PDO $db */

$print_est=true;
require 'utils/new_fork.php';

$where = '';
$sql   = "select count(*) as num from `Image Dimension` $where ";
$total = 0;
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    }
}


$lap_time0 = date('U');
$contador  = 0;

$sql = "select  `Image Key`  from `Image Dimension` $where order by   `Image Key` desc     ";


if ($result2 = $db->query($sql)) {
    foreach ($result2 as $row2) {
        $image = get_object('image', $row2['Image Key']);
        $image->update_public_db();

        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'Pa '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
                )."h  ($contador/$total) \r";
        }
    }
}
