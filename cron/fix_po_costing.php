<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 April 2018 at 14:36:10 BST, Sheffield, UK
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);


$sql = sprintf('select * from `Purchase Order Transaction Fact` where `Supplier Delivery Key`=72 ');
$sql = sprintf('select * from `Purchase Order Transaction Fact` where `Supplier Delivery Transaction Placed`="Yes" ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $supplier_delivery = get_object('SupplierDelivery', $row['Supplier Delivery Key']);
        $exchange          = $supplier_delivery->get('Supplier Delivery Currency Exchange');

        $qty    = $row['Supplier Delivery Quantity'];
        $amount = $row['Supplier Delivery Net Amount'];


        $paid = 0;


        if ($row['Metadata'] != '') {
            $metadata = json_decode($row['Metadata'], true);

            foreach ($metadata['placement_data'] as $placement_data) {

                $sql = sprintf('select * from `Inventory Transaction Fact` where `Inventory Transaction Key`=%d ', $placement_data['oif_key']);
                if ($result2 = $db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        $paid += $row2['Inventory Transaction Amount'];

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

            }
        }


        $sql = sprintf(
            'update `Purchase Order Transaction Fact` set  `Supplier Delivery Exchange Rate`=%f,`Supplier Delivery Paid Amount`=%.2f  where `Purchase Order Transaction Fact Key`=%d '
            , $exchange, $amount * $exchange, $row['Purchase Order Transaction Fact Key']


        );

        print "$sql\n";

        $db->exec($sql);


        //print 'X '.$amount * $exchange."\n";
        //print 'R '.$paid;
        // $extra=$paid-$amount;
        // print "$qty $amount ex: $exchange $extra $paid\n";


      //  exit;


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


?>
