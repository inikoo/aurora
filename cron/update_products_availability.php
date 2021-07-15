<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 January 2018 at 19:45:26 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


require_once 'class.Product.php';
require_once 'class.Category.php';


$editor = array(
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (product availability)'
);

$where='';
$total=0;
$sql = "SELECT count(*) AS num FROM `Product Dimension` $where ";
/** @var PDO $db */
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    }
}
$print_est=true;
$lap_time0 = date('U');
$contador  = 0;



$sql = "SELECT `Product ID` FROM `Product Dimension` $where order by `Product ID`  desc ";
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $product         = new Product($row['Product ID']);
        $product->editor = $editor;
        $web_state_old   = $product->get_web_state();
        $product->update_availability();
        $web_state_new = $product->get_web_state();

        

        //print "$product->id\r";

        if ($web_state_old != $web_state_new) {
            print "\n".$product->get('Store Key').' '.$product->get('Code')." $web_state_old $web_state_new \n";
        }




        $sql = sprintf(
            "SELECT `Order Key` FROM `Order Transaction Fact` WHERE `Current Dispatching State` IN ('In Process','In Process by Customer') AND `Product ID`=%d ", $product->id
        );


        if ($result2 = $product->db->query($sql)) {
            foreach ($result2 as $row2) {

                $web_availability = ($product->get_web_state() == 'For Sale' ? 'Yes' : 'No');
                if ($web_availability == 'No') {
                    /**
                     * @var $order \Order
                     */
                    $order = get_object('Order', $row2['Order Key']);


                    $order->remove_out_of_stocks_from_basket($product->id);
                }
            }
        }


        $sql = sprintf(
            "SELECT `Order Key` FROM `Order Transaction Fact` WHERE `Current Dispatching State`='Out of Stock in Basket' AND `Product ID`=%d ", $product->id

        );

        if ($result2 = $product->db->query($sql)) {
            foreach ($result2 as $row2) {
                $web_availability = ($product->get_web_state() == 'For Sale' ? 'Yes' : 'No');
                if ($web_availability == 'Yes') {
                    /**
                     * @var Order $order
                     */
                    $order = get_object('Order', $row2['Order Key']);
                    $order->restore_back_to_stock_to_basket($product->id);
                }
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

}



