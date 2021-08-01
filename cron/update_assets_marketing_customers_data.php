<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 01-08-2019 13:39:05 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

$print_est = true;



$where = "where true";
//$where="where `Category Key`=434";

$sql = sprintf(
    "select count(distinct `Category Key`) as num from `Category Dimension` $where and  `Category Scope`='Product' and `Category Branch Type`='Head' "
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
    "select `Category Key` from `Category Dimension` $where and  `Category Scope`='Product' and `Category Branch Type`='Head' "
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $category = get_object('Category',$row['Category Key']);
        $category->update_product_category_targeted_marketing_customers();
        $category->update_product_category_spread_marketing_customers();

        $category->update_product_category_donut_marketing_customers();

        //exit;
        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'Pa '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
                )."h  ($contador/$total) \r";
        }


    }

}



$where = "where true";
//$where = "where `Product ID`=2474";

$sql = sprintf(
    "select count(distinct `Product ID`) as num from `Product Dimension` $where  "
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
    "select `Product ID` from `Product Dimension` $where ");

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        /** @var Product  $product */
        $product = get_object('Product',$row['Product ID']);
        $product->update_product_targeted_marketing_customers();

        $product->update_product_spread_marketing_customers();
        $product->update_product_donut_marketing_customers();


        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'Pa '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
                )."h  ($contador/$total) \r";
        }


    }

}


