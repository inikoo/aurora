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







$where='where true';

$sql = sprintf(
    "select count(distinct `Category Key`) as num from `Category Dimension` $where and  `Category Scope`='Product' and `Category Subject`='Product'  and `Category Branch Type`='Head' "
);

if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
}

$lap_time0 = date('U');
$contador  = 0;

$sql = sprintf(
    "select `Category Key` from `Category Dimension` $where and  `Category Scope`='Product' and `Category Subject`='Product' and `Category Branch Type`='Head' order by rand() "
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $category = get_object('Category',$row['Category Key']);
        $category->update_product_category_sales_correlations();


        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'Pa '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
                )."h  ($contador/$total) \r";
        }


    }

}





