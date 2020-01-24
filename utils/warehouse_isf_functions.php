<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  23 January 2020  18:06::07  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2020, Inikoo

 Version 3.0
*/

/**
 * @param $db PDO
 * @param $part_sku
 * @param $date
 *
 * @return bool
 */
function get_if_part_hsa_no_sales_1y($db, $part_sku, $date) {


    $sql   = "select `Part SKU` from `Part Dimension`   WHERE  `Part SKU`=? AND  `Part Valid From`>?  ";
    $stmt2 = $db->prepare($sql);
    $stmt2->execute(
        array(
            $part_sku,
            date("Y-m-d H:i:s", strtotime($date.' 23:59:59 -1 year')),
        )
    );
    if ($row2 = $stmt2->fetch()) {
        $sql =
            "SELECT `Inventory Transaction Key` FROM `Inventory Transaction Fact` ITF   WHERE ITF.`Part SKU`=? and  `Inventory Transaction Type`='Sale' AND `Date`>=? AND `Date`<=?  limit 1 ";


        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $part_sku,
                date("Y-m-d H:i:s", strtotime($date.' 23:59:59 -1 year')),
                $date.' 23:59:59'
            )
        );
        if ($row = $stmt->fetch()) {
            $dormant = false;
        } else {
            $dormant = true;
        }


    } else {
        $dormant = false;

    }


    return $dormant;


}