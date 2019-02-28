<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 September 2017 at 02:49:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/order_functions.php';

$print_est = true;


$sql = sprintf("SELECT count(*) as num FROM `Order Dimension`  left join `Store Dimension` on (`Store Key`=`Order Store Key`) where `Order State`='InBasket'and `Store Version`=2 ");
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

$sql = sprintf("SELECT `Order Key` FROM `Order Dimension`  left join `Store Dimension` on (`Store Key`=`Order Store Key`) where `Order State`='InBasket'and `Store Version`=2 ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $order = get_object('order', $row['Order Key']);


        $order->fast_update(
            array(
                'Order Pinned Deal Components' => json_encode(array())
            )
        );


        $sql = sprintf(
            "UPDATE `Order Transaction Deal Bridge` SET `Order Transaction Deal Pinned`='No' WHERE `Order Key`=%d   ",


            $order->id
        );



        $order->update_totals();

        $order->update_discounts_items();


        $order->update_shipping();
        $order->update_charges();
        $order->update_discounts_no_items();
        $order->update_deal_bridge();


        $order->update_totals();


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