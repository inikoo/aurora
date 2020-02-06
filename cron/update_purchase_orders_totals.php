<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 August 2018 at 11:27:38 GMT+8, Sanur, Bal, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';





$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$print_est = true;

print date('l jS \of F Y h:i:s A')."\n";


$sql = sprintf("SELECT `Purchase Order Key` FROM `Purchase Order Dimension`  ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $po = get_object('PurchaseOrder', $row['Purchase Order Key']);


        if ($po->get('State Index') >= 80) {

            $sql =
                "select max(`Supplier Delivery Received Date`) as delivery_date ,  count(*) as num from `Supplier Delivery Dimension` where `Supplier Delivery Received Date` is not null  and `Supplier Delivery Purchase Order Key`=? and `Supplier Delivery State` in ('Received','Checked','Placed','Costing','InvoiceChecked') ";




            $stmt = $po->db->prepare($sql);
            $stmt->execute(
                array(
                    $po->id
                )
            );
            if ($row = $stmt->fetch()) {


                if ($row['num'] > 0) {
                    $po->fast_update(
                        array(
                            'Purchase Order Received Date' => $row['delivery_date'],
                        )
                    );
                }

            }

        }

        if ($po->get('State Index') >= 90) {

            $sql =
                "select max(`Supplier Delivery Checked Date`) as checked_date ,  count(*) as num from `Supplier Delivery Dimension` where `Supplier Delivery Checked Date` is not null  and `Supplier Delivery Purchase Order Key`=? and `Supplier Delivery State` in ('Checked','Placed','Costing','InvoiceChecked') ";

            $stmt = $po->db->prepare($sql);
            $stmt->execute(
                array(
                    $po->id
                )
            );
            if ($row = $stmt->fetch()) {
                if ($row['num'] > 0) {
                    $po->fast_update(
                        array(
                            'Purchase Order Checked Date' => $row['checked_date'],
                        )
                    );
                }

            }

        }

        $po->update_purchase_order_date();
        
        

    }

}

