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


$sql = sprintf("SELECT count(*) as num FROM `Order Dimension`  left join `Store Dimension` on (`Store Key`=`Order Store Key`) where `Order State`='InBasket'and `Store Type`!='External' ");
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
}

$lap_time0 = date('U');
$contador  = 0;

$sql = "SELECT `Order Key` FROM `Order Dimension`  left join `Store Dimension` on (`Store Key`=`Order Store Key`) where `Order State`='InBasket'and `Store Type`!='External'    order by `Order Date` desc";
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $order = get_object('order', $row['Order Key']);

      //  print $order->id.' '.$order->get('Order Date')."\n";

        $order->update_tax();

        $order->fast_update(
            array(
                'Order Pinned Deal Components' => json_encode(array())
            )
        );


        $sql = sprintf(
            "UPDATE `Order Transaction Deal Bridge` SET `Order Transaction Deal Pinned`='No' WHERE `Order Key`=%d   ",


            $order->id
        );

        $old_used_deals = $order->get_used_deals();


        $order->update_totals();
        $order->update_discounts_items();
        $order->update_totals();
        $order->update_shipping();
        $order->update_charges();
        $order->update_discounts_no_items();
        $order->update_deal_bridge();


        $order->update_totals();


        $new_used_deals = $order->get_used_deals();


        $intersect      = array_intersect($old_used_deals[0], $new_used_deals[0]);
        $campaigns_diff = array_merge(array_diff($old_used_deals[0], $intersect), array_diff($new_used_deals[0], $intersect));

        $intersect = array_intersect($old_used_deals[1], $new_used_deals[1]);
        $deal_diff = array_merge(array_diff($old_used_deals[1], $intersect), array_diff($new_used_deals[1], $intersect));

        $intersect            = array_intersect($old_used_deals[2], $new_used_deals[2]);
        $deal_components_diff = array_merge(array_diff($old_used_deals[2], $intersect), array_diff($new_used_deals[2], $intersect));

        $date = gmdate('Y-m-d H:i:s');

        foreach ($campaigns_diff as $campaign_key) {

            if($campaign_key>0){
                $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
                //print "$sql\n";
                $db->prepare($sql)->execute(
                    [
                        $date,
                        $date,
                        'deal_campaign',
                        $campaign_key,
                        $date,

                    ]
                );
            }

        }

        foreach ($deal_diff as $deal_key) {
            if($deal_key>0) {
                $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
                $db->prepare($sql)->execute(
                    [
                        $date,
                        $date,
                        'deal',
                        $deal_key,
                        $date,

                    ]
                );
            }
        }

        foreach ($deal_components_diff as $deal_component_key) {
            if($deal_component_key>0) {
                $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
                $db->prepare($sql)->execute(
                    [
                        $date,
                        $date,
                        'deal_component',
                        $deal_component_key,
                        $date,

                    ]
                );
            }
        }


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