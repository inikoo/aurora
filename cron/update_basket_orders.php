<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 September 2017 at 02:49:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/order_functions.php';


require_once 'class.Store.php';
require_once 'class.Category.php';



  $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` where `Order State`='InBasket' ");
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
        
        $dn_key=false;
        
            $order = get_object('order', $row['Order Key']);

            $order->update_totals();

            $order->update_discounts_items();


            $order->update_shipping($dn_key, false);
            $order->update_charges($dn_key, false);

            $order->update_deal_bridge();


            $order->update_totals();



        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


?>