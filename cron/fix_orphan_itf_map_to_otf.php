<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-06-2019 14:10:43 BST  Sheffield, UK
 Copyright (c) 2019, Inikoo

 Version 3

*/


require_once __DIR__.'/cron_common.php';


/** @var PDO $db */


$sql = '   select `Inventory Transaction Key`,`Date`,ITF.`Delivery Note Key` ,`Part SKU` from  
    `Inventory Transaction Fact` ITF left join `Order Transaction Fact` on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`) 
   where ITF.`Delivery Note Key`>0  and `Order Transaction Fact Key` is null  ';

$stmt = $db->prepare($sql);
$stmt->execute(
    [

    ]
);
while ($row = $stmt->fetch()) {
    print_r($row);
    $sql = "update  `Inventory Transaction Fact`  set  `Map To Order Transaction Fact Key`=null where `Inventory Transaction Key`=? ";
    $db->prepare($sql)->execute(
        [
            $row['Inventory Transaction Key']
        ]
    );


    $dn = get_object('DeliveryNote', $row['Delivery Note Key']);

    if ($dn->id) {
        if (in_array($dn->get('Delivery Note Type'), ['Replacement & Shortages', 'Replacement', 'Shortages'])) {
            $order = get_object('Order', $dn->get('Delivery Note Order Key'));
            if ($order->id) {
                $sql = "select `Order Transaction Fact Key` from 
       `Order Transaction Fact` OTF left join  `Inventory Transaction Fact` ITF on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`) 
       where `Order Key`=? and OTF.`Delivery Note Key`!=? and `Part SKU`=? limit 1
       ";
                $stmt2 = $db->prepare($sql);
                $stmt2->execute(
                    [
                        $order->id,
                        $dn->id,
                        $row['Part SKU']

                    ]
                );
                while ($row2 = $stmt2->fetch()) {

                    $sql = "update  `Inventory Transaction Fact`  set  `Map To Order Transaction Fact Key`=? where `Inventory Transaction Key`=? ";
                    $db->prepare($sql)->execute(
                        [
                            $row2['Order Transaction Fact Key'],
                            $row['Inventory Transaction Key']
                        ]
                    );
                    print "updated ".$row['Date']." \n";

                }
            }
        }
    }
}


