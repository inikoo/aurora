<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 August 2018 at 00:13:49 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

function get_payment_showcase($data, $smarty, $user, $db) {


    $payment = $data['_object'];

    if($payment->id==1){
        exit;
    }



    $smarty->assign('payment', $payment);



    return $smarty->fetch('showcase/payment.tpl');


}


?>
