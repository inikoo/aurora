<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 October 2015 at 10:19:15 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_order_showcase($data, $smarty, $user, $db) {





    if (!$data['_object']->id) {
        return "";
    }


    $order = $data['_object'];
    $store=get_object('store',$order->get('Store Key'));


  // $order->update_totals();

    $smarty->assign('order',$order);
    $smarty->assign('store', $store);
    $smarty->assign('customer', get_object('customer',$order->get('Customer Key')));



    $smarty->assign(
        'object_data', base64_encode(
            json_encode(
                array(
                    'object' => $data['object'],
                    'key'    => $data['key'],
                    'symbol'=>currency_symbol($order->get('Order Currency')),
                    'tax_rate'=>$order->get('Order Tax Rate'),
                    'available_to_refund'=>$order->get('Order Total Amount'),
                    'tab' => $data['tab'],
                    'order_type'=>'Order'
                )
            )
        )
    );



    if($data['section']=='refund.new'){
        $smarty->assign('zero_amount',money(0,$store->get('Store Currency Code')));
        return $smarty->fetch('showcase/refund.new.tpl');

    }elseif($data['section']=='replacement.new'){
        $smarty->assign('zero_amount',money(0,$store->get('Store Currency Code')));
        return $smarty->fetch('showcase/replacement.new.tpl');

    }else{
        return $smarty->fetch('showcase/order.tpl');

    }



}


?>
