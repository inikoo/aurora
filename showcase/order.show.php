<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 October 2015 at 10:19:15 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_order_showcase($data, $smarty, $user, $db) {





    require_once 'utils/geography_functions.php';

    if (!$data['_object']->id) {
        return "";
    }


    $order = $data['_object'];
    $store = get_object('store', $order->get('Store Key'));


   // $order->update_totals();

    $smarty->assign('order', $order);
    $smarty->assign('store', $store);
    $smarty->assign('customer', get_object('customer', $order->get('Customer Key')));


    $smarty->assign(
        'object_data',
                         json_encode(
                             array(
                                 'object'              => $data['object'],
                                 'key'                 => $data['key'],
                                 'symbol'              => currency_symbol($order->get('Order Currency')),
                                 'tax_rate'            => $order->get('Order Tax Rate'),
                                 'available_to_refund' => $order->get('Order Total Amount'),
                                 'tab'                 => $data['tab'],
                                 'order_type'          => 'Order'
                             )
                         )

    );


    if (in_array(
        $order->get('Order Delivery Address Country 2 Alpha Code'), get_countries_EC_Fiscal_VAT_area($db)
    )) {
        $pdf_with_commodity = false;
    } else {
        $pdf_with_commodity = true;
    }
    $smarty->assign('pdf_with_commodity', $pdf_with_commodity);

    if($store->get('Store Locale')!='en_GB'){
        $pdf_show_locale_option = true;
    }else{
        $pdf_show_locale_option = false;

    }
    $smarty->assign('pdf_show_locale_option', $pdf_show_locale_option);

    $pdf_with_rrp=true;
    $smarty->assign('pdf_with_rrp', $pdf_with_rrp);


    if ($data['section'] == 'refund.new') {
        $smarty->assign('zero_amount', money(0, $store->get('Store Currency Code')));

        return $smarty->fetch('showcase/refund.new.tpl');

    } elseif ($data['section'] == 'replacement.new') {
        $smarty->assign('zero_amount', money(0, $store->get('Store Currency Code')));

        return $smarty->fetch('showcase/replacement.new.tpl');

    }elseif ($data['section'] == 'return.new') {
        $smarty->assign('zero_amount', money(0, $store->get('Store Currency Code')));

        return $smarty->fetch('showcase/return.new.tpl');

    } else {
        return $smarty->fetch('showcase/order.tpl');

    }


}


?>
