<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 March 2019 at 20:57:50 GMT+8, Kuala Lumpur, malaysia
 Copyright (c) 2019, Inikoo

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

$account = new Account();


$sql = sprintf('Select `Purchase Order Key` from `Purchase Order Dimension`  ');

$stmt = $db->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {

        $po = get_object('PurchaseOrder', $row['Purchase Order Key']);

        if ($po->get('State Index') > 30) {

            $sql = 'Select `Purchase Order Transaction Fact Key` from `Purchase Order Transaction Fact` where `Purchase Order Transaction State`="Submitted" and `Purchase Order Key`=?  ';

            $stmt2 = $db->prepare($sql);
            if ($stmt2->execute(
                array(
                    $po->id
                )
            )) {
                while ($row2 = $stmt2->fetch()) {
                    print $po->get('Public ID')."\n";


                    $sql = sprintf('update   `Purchase Order Transaction Fact` set `Purchase Order Transaction State`="Cancelled"  where  `Purchase Order Transaction Fact Key`=%d ',$row2['Purchase Order Transaction Fact Key']);
                    $db->exec($sql);

                }
            }




        }

/*

        $sql = 'Select `Purchase Order Transaction Fact Key` from `Purchase Order Transaction Fact` where `Supplier Delivery Transaction Placed`="Yes"  and `Purchase Order Transaction State`!="Placed"  and `Purchase Order Key`=?  ';

        $stmt2 = $db->prepare($sql);
        if ($stmt2->execute(
            array(
                $po->id
            )
        )) {
            while ($row2 = $stmt2->fetch()) {
                print $po->get('Public ID').' '.$po->get('State Index')."\n";


               // $sql = sprintf('update   `Purchase Order Transaction Fact` set `Purchase Order Transaction State`="Cancelled"  where  `Purchase Order Transaction Fact Key`=%d ',$row2['Purchase Order Transaction Fact Key']);
               // $db->exec($sql);

            }
        }
*/



    }
} else {
    print_r($error_info = $db->errorInfo());
    exit();
}


?>
