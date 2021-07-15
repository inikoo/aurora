<?php /** @noinspection DuplicatedCode */

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 October 2017 at 22:31:32 GMT+8, Plane Bali - Kuala Lumpur
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/object_functions.php';
/** @var PDO $db */

$where     = " where `Order State`!='Cancelled'";
$print_est = false;

$total = 0;
$sql   = sprintf("SELECT count(*) AS num FROM `Order Dimension` %s", $where);
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    }
}

$lap_time0 = date('U');
$contador  = 0;


$sql  = "SELECT `Order Key` FROM `Order Dimension` $where";
$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {
    $order = get_object('Order', $row['Order Key']);


    if ($order->get('State Index') > 0 and $order->get('State Index') < 80) {

        $sql   = "select  `Order Transaction Fact Key`,`Product ID`,(`Order Quantity`+`Order Bonus Quantity`) as qty from  `Order Transaction Fact` where `Order Key`=?";
        $stmt2 = $db->prepare($sql);
        $stmt2->execute(
            array(
                $order->id
            )
        );
        while ($row2 = $stmt2->fetch()) {


            $product=get_object('Product',$row2['Product ID']);
            $product->update_cost();
            $product_cost = (is_numeric($product->get('Product Cost')) ? $product->get('Product Cost') : 0);
            $cost         = round($row2['qty'] * $product_cost, 4);

            print $product->get('Code')." {$row2['qty']}   $product_cost   \n";


            $sql = "UPDATE `Order Transaction Fact` SET `Cost Supplier`=? WHERE `Order Transaction Fact Key`=?";
            $db->prepare($sql)->execute(
                [
                    $cost,
                    $row2['Order Transaction Fact Key']
                ]
            );

        }


    }else if($order->get('State Index') >=80){

        $sql   = "select  `Order Transaction Fact Key`  from  `Order Transaction Fact` where `Order Key`=?";
        $stmt2 = $db->prepare($sql);
        $stmt2->execute(
            array(
                $order->id
            )
        );
        while ($row2 = $stmt2->fetch()) {


            $cost = 0;
            $sql = 'SELECT sum(`Inventory Transaction Amount`) AS amount FROM `Inventory Transaction Fact` WHERE `Map To Order Transaction Fact Key`=?';

            $stmt3 = $db->prepare($sql);
            $stmt3->execute(
                array(
                    $row2['Order Transaction Fact Key']
                )
            );
            while ($row3 = $stmt3->fetch()) {
                if ($row3['amount'] == '') {
                    $row23['amount'] = 0;
                }

                $cost = -1 * $row3['amount'];
            }



            $sql = "UPDATE `Order Transaction Fact` SET `Cost Supplier`=? WHERE `Order Transaction Fact Key`=?";
            $db->prepare($sql)->execute(
                [
                    $cost,
                    $row2['Order Transaction Fact Key']
                ]
            );

        }

    }

    $order->update_totals();
    foreach ($order->get_invoices('objects') as $invoice) {
        $invoice->update_profit();
    }


    $contador++;
    $lap_time1 = date('U');

    if ($print_est) {
        print 'Pa '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
            )."h  ($contador/$total) \r";
    }
}


