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

$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Migration from inikoo)',
    'Author Alias' => 'System (Migration from inikoo)',


);

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




                        $customer=get_object('Customer',$order->get('Order Customer Key'));
                        $customer->editor = $editor;
                        $customer->set_account_balance_adjust($payment->get('Payment Transaction Amount'), 'Carry on balance from old inikoo system');
                        $customer->update_account_balance();
                        $customer->update_credit_account_running_balances();



                    }

                    //print_r($payment);
                    print $order->get('Public ID').' '.$payment->get('Payment Transaction Status')." ".$payment->get('Payment Transaction Amount')."  \n";

                   // exit;

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