<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 January 2017 at 14:44:16 GMT, Sheffield UK
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Category.php';

$print_est = true;

update_products_categories_products_data($db, $print_est);
//update_categories_sales($db, $print_est);

function update_products_categories_products_data($db, $print_est) {

    $where = "where true";
    //$where="where `Category Key`=15362";

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
            $category = new Category($row['Category Key']);
            $category->update_product_category_products_data();
            
    
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




}


?>
