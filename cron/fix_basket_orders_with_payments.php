<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 April 2019 at 09:58:27 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/order_functions.php';



$sql = sprintf("SELECT `Order Key` FROM `Order Dimension`  left join `Store Dimension` on (`Store Key`=`Order Store Key`) where `Order State`='InBasket'and `Store Version`=2   order by `Order Date`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $order    = get_object('order', $row['Order Key']);
        $payments = $order->get_payments('objects','Completed_or_Pending');

        if(count($payments)>0){

            foreach($payments as $payment){

                if($payment->payment_account->get('Payment Account Block')=='Accounts'){


                    if($payment->get('Payment Transaction Status')=='Pending'){
                        $sql=sprintf('delete from `Payment Dimension` where `Payment Key`=%d',$payment->id);
                        $db->exec($sql);
                    }

                    print $order->get('Public ID').' '.$payment->get('Payment Transaction Status')."\n";

                }else{
                    //print $payment->payment_account->get('Payment Account Block')."\n";
                }



            }




        }


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>