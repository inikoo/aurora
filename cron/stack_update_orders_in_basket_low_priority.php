<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 February 2019 at 14:29:51 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';

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



$sql = sprintf("SELECT count(*) AS num FROM `Stack Dimension`  where `Stack Operation`='update_order_in_basket_low_priority'");
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
    "SELECT `Stack Key`,`Stack Object Key` FROM `Stack Dimension`  where `Stack Operation`='update_order_in_basket_low_priority' ORDER BY RAND()"
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



                    $order->update_totals();

                    $order->update_discounts_items();


                    $order->update_shipping();
                    $order->update_charges();
                    $order->update_discounts_no_items();
                    $order->update_deal_bridge();


                    $order->update_totals();






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
    printf("%s: %s/%s %.2f min Order in basket updated (Low priority) \n",gmdate('Y-m-d H:i:s'),$contador,$total,($lap_time1 - $lap_time0)/60);
}


?>
