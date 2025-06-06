<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 February 2019 at 14:29:51 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';

$print_est = false;


$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Stack orders in basket)',
    'Author Alias' => 'System (Stack orders in basket)',
    'v'            => 3


);



$sql = sprintf("SELECT count(*) AS num FROM `Stack Dimension`  where `Stack Operation`='update_order_in_basket'");
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
$lap_time1= date('U');

$contador  = 0;


$sql = sprintf(
    "SELECT `Stack Key`,`Stack Object Key` FROM `Stack Dimension`  where `Stack Operation`='update_order_in_basket' ORDER BY RAND()"
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $order =  get_object('Order',$row['Stack Object Key']);

        if($order->id and $order->get('Order State')=='InBasket'){


            $sql=sprintf('select `Stack Key` from `Stack Dimension` where `Stack Key`=%d ',$row['Stack Key']);

            if ($result2=$db->query($sql)) {
                if ($row2 = $result2->fetch()) {

                    $sql=sprintf('delete from `Stack Dimension`  where `Stack Key`=%d ',$row['Stack Key']);
                    $db->exec($sql);

                    $editor['Date'] = gmdate('Y-m-d H:i:s');
                    $order->editor = $editor;


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
                            print "$sql\n";
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






                }
            }

        }else{
            $sql=sprintf('delete from `Stack Dimension`  where `Stack Key`=%d ',$row['Stack Key']);
            $db->exec($sql);
        }

        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'Order '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
                )."h  ($contador/$total) \r";
        }

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}
if($total>0){
    printf("%s: %s/%s %.2f min Order in basket updated \n",gmdate('Y-m-d H:i:s'),$contador,$total,($lap_time1 - $lap_time0)/60);
}


?>
