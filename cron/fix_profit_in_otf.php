<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 March 2018 at 14:58:59 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';


$account = new Account();


$sql = sprintf(
    "SELECT `Delivery Note Key` FROM `Delivery Note Dimension` "
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $dn = get_object('Delivery Note', $row['Delivery Note Key']);
        $dn->update_totals();
    }
} else {
    print_r($error_info = $this->db->errorInfo());
    print "$sql\n";
    exit;
}

$sql = sprintf(
    "SELECT `Order Transaction Fact Key`,`Current Dispatching State`,`Order Quantity`,`Order Bonus Quantity`,`Product ID` FROM `Order Transaction Fact` "
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $cost = 0;
        if (in_array(
            $row['Current Dispatching State'], array(
                                                 'Dispatched',
                                                 'Packed',
                                                 'Packed Done'
                                             )
        )) {

            $sql = sprintf('SELECT sum(`Inventory Transaction Amount`) AS amount FROM `Inventory Transaction Fact` WHERE `Map To Order Transaction Fact Key`=%d ', $row['Order Transaction Fact Key']);
            if ($result2 = $db->query($sql)) {
                if ($row2 = $result2->fetch()) {
                    if ($row2['amount'] == '') {
                        $row2['amount'] = 0;
                    }

                    $cost = -1 * $row2['amount'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


        } elseif ($row['Current Dispatching State'] == 'Cancelled') {
            // get cost from product


            $cost = 0;


        } else {
            // get cost from product

            $qty     = $row['Order Quantity'] + $row['Order Bonus Quantity'];
            $product = get_object('Product', $row['Product ID']);
            $cost    = $product->get('Product Cost') * $qty;


        }
        $sql = sprintf('UPDATE `Order Transaction Fact` SET `Cost Supplier`=%f  WHERE  `Order Transaction Fact Key`=%d', $cost, $row['Order Transaction Fact Key']);
        //print "$sql\n";
        $db->exec($sql);

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$sql = sprintf(
    "SELECT `Order Key` FROM `Order Dimension` "
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $order = get_object('Order', $row['Order Key']);
        $order->update_totals();
    }
} else {
    print_r($error_info = $this->db->errorInfo());
    print "$sql\n";
    exit;
}


$sql = sprintf(
    "SELECT `Invoice Key` FROM `Invoice Dimension` "
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $invoice = get_object('Invoice', $row['Invoice Key']);

        $profit = 0;
        if ($invoice->get('Invoice Type') == 'Invoice') {
            $sql = sprintf(
                "SELECT sum(`Cost Supplier`) AS cost, sum(`Order Transaction Amount`) AS net  FROM `Order Transaction Fact` WHERE `Invoice Key`=%d AND `Order Transaction Type`='Order' ", $invoice->id
            );

            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {


                    $profit = $row['net'] - $row['cost'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }
        } else {

            $sql = sprintf(
                "SELECT sum(`Order Transaction Amount`) AS net  FROM `Order Transaction Fact` WHERE `Invoice Key`=%d AND `Order Transaction Type`='Refund' ", $invoice->id
            );

            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {


                    $profit = $row['net'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }

        }


        $invoice->fast_update(array('Invoice Total Profit' => $profit));

    }
} else {
    print_r($error_info = $this->db->errorInfo());
    print "$sql\n";
    exit;
}


?>
