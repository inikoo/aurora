<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 April 2018 at 14:37:59 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
$print_est = true;




$where = 'where `Product ID`=110789';
$where = 'where `Product Status`="Active"  and `Product Public`="Yes"  ';
//$where='where `Product Code` like "JBB-%"';
$sql = sprintf(
    "SELECT count(*) AS num FROM `Product Dimension` %s", $where
);
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




$sql = sprintf('SELECT `Product ID` FROM `Product Dimension`  %s order by RAND() ',$where);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $product = get_object('Product', $row['Product ID']);
        $product->update_sales_correlations('Random',10);


        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'P   '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
                )."h  ($contador/$total) \r";
        }

    }
}

