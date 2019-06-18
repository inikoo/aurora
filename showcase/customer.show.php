<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2015 16:54:39 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_customer_showcase($data, $smarty) {




    $customer = $data['_object'];
    if (!$customer->id) {
        return "";
    }

        $customer->update_account_balance();
        $customer->update_credit_account_running_balances();


    //$customer->update_orders();
    //$customer->update_last_dispatched_order_key();
    //$customer->update_invoices();
    //$customer->update_payments();
    //$customer->update_activity();


    $smarty->assign('customer', $customer);
    $smarty->assign('store', $data['store']);

    return $smarty->fetch('showcase/customer.tpl');


}


?>