<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-06-2019 14:10:43 BST  Sheffield, UK
 Copyright (c) 2019, Inikoo

 Version 3

*/


require_once __DIR__.'/cron_common.php';




$sql = sprintf('select  `Order Key`   from `Order Dimension` ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $order = get_object('Order', $row['Order Key']);


        update_checksum('Invoice',$order);

        update_checksum('Delivery',$order);



    }
}

$sql = sprintf('select  `Delivery Note Key`   from `Delivery Note Dimension`   ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $dn = get_object('Delivery Note', $row['Delivery Note Key']);

        update_checksum('Delivery',$order);



    }
}


$sql = sprintf('select  `Invoice Key`   from `Invoice Dimension`   ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $invoice = get_object('Invoice', $row['Invoice Key']);

        update_checksum('Invoice',$invoice);



    }
}



function update_checksum($type,$object){
    $new_checksum = md5(
        json_encode(
            array(
                'Address Recipient'            => $object->get($type.' Address Recipient'),
                'Address Organization'         => $object->get($type.' Address Organization'),
                'Address Line 1'               => $object->get($type.' Address Line 1'),
                'Address Line 2'               => $object->get($type.' Address Line 2'),
                'Address Sorting Code'         => $object->get($type.' Address Sorting Code'),
                'Address Postal Code'          => $object->get($type.' Address Postal Code'),
                'Address Dependent Locality'   => $object->get($type.' Address Dependent Locality'),
                'Address Locality'             => $object->get($type.' Address Locality'),
                'Address Administrative Area'  => $object->get($type.' Address Administrative Area'),
                'Address Country 2 Alpha Code' => $object->get($type.' Address Country 2 Alpha Code'),
            )
        )
    );


    $object->fast_update(
        array($object->get_object_name().' '.$type.' Address Checksum'=> $new_checksum)
    );
}



