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


$dns_replica=$dns_replicas[array_rand($dns_replicas, 1)];
$db_replica = new PDO(
    "mysql:host=".$dns_replica['host'].";dbname=".$dns_replica['db'].";charset=utf8mb4", $dns_replica['user'], $dns_replica['pwd']
);
$db_replica->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);




$where='where true';

$sql = sprintf(
    "select count(distinct `Category Key`) as num from `Category Dimension` $where and  `Category Scope`='Product' and `Category Subject`='Category'  and `Category Branch Type`='Head' "
);

if ($result = $db_replica->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
}

$lap_time0 = date('U');
$contador  = 0;

$sql = sprintf(
    "select `Category Key` from `Category Dimension` $where and  `Category Scope`='Product' and `Category Subject`='Category' and `Category Branch Type`='Head' "
);


if ($result = $db_replica->query($sql)) {
    foreach ($result as $row) {


        $category = get_object('Category',$row['Category Key']);
        $category->update_product_category_sales_correlations($db_replica);


        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'Pa '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
                )."h  ($contador/$total) \r";
        }


    }

}





