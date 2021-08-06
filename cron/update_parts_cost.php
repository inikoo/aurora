<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 November 2018 at 19:26:43 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';

include_once 'utils/new_fork.php';

new_housekeeping_fork(
    'au_housekeeping', array(
    'type'                    => 'update_parts_cost'
), $account->get('Account Code')
);
print "forking\n";

exit;

$where     = ' where `Part Status`!="Not In Use"';
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





exit;

$lap_time0 = date('U');
$contador  = 0;

$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension`    where `Part Status`!="Not In Use" '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = get_object('Part', $row['Part SKU']);




        //$part->update_cost();


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
