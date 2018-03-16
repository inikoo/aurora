<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 March 2018 at 15:41:56 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';


$account = new Account();




$sql = sprintf(
    "SELECT `Order Key`,`Order Delivery Note Key` FROM `Order Dimension` where `Order State`='Cancelled' "
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $order = get_object('Order', $row['Order Key']);

        $dn = get_object('DeliveryNote', $row['Order Delivery Note Key']);


        if($dn->id){
          //  print_r($order);

            print($dn->get('Delivery Note ID')."\n");
           // exit;
        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}




?>
