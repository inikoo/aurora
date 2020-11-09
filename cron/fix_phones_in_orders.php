<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-06-2019 11:55:40 BST Sheffield, UK
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';


$sql = sprintf("SELECT `Order Key` FROM `Order Dimension` where  `Order State` not in ('Dispatched','Cancelled')  ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $order=get_object('Order',$row['Order Key']);



        if($order->get('Order Customer Client Key')){
            $client=get_object('CustomerClient',$order->get('Order Customer Client Key'));
            $telephone=$client->get_telephone();
        }else{
            $customer=get_object('Customer',$order->get('Order Customer Key'));

            $telephone=$customer->get_telephone();
        }




        if($telephone){
            $order->fast_update(['Order Telephone'=>$telephone]);
        }

    }

}
//
$sql = sprintf("SELECT `Delivery Note Key` FROM `Delivery Note Dimension` where `Delivery Note State` not in ('Dispatched','Cancelled')  ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $dn = get_object('Delivery Note', $row['Delivery Note Key']);
        $order=get_object('Order',$dn->get('Delivery Note Order Key'));
        if($order->id){
            $dn->fast_update(['Delivery Note Telephone'=>$order->get('Order Telephone')]);

        }

    }

}

