<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 September 2016 at 22:35:21 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'class.Category.php';

$print_est = true;


$where = "where true";
//$where="where `Category Key`=580";

$sql = sprintf(
    "select count(distinct `Category Key`) as num from `Category Dimension` $where and  `Category Scope`='Product' "
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

$sql = sprintf(
    "select `Category Key` from `Category Dimension` $where and  `Category Scope`='Product' "
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $category = get_object('Category', $row['Category Key']);


        $category->update_product_category_previous_years_data();
        $category->update_product_category_previous_quarters_data();

        $category->update_product_category_sales('Total');
        $category->update_product_category_sales('Week To Day');
        $category->update_product_category_sales('Month To Day');
        $category->update_product_category_sales('Quarter To Day');
        $category->update_product_category_sales('Year To Day');
        $category->update_product_category_sales('1 Year');
        $category->update_product_category_sales('1 Quarter');

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
