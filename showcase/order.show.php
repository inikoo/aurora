<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 October 2015 at 10:19:15 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_order_showcase($data, $smarty, $user, $db)
{
    require_once 'utils/geography_functions.php';

    if (!$data['_object']->id) {
        return "";
    }


    $order = $data['_object'];
    $store = get_object('store', $order->get('Order Store Key'));


   // $order->confirm_hokodo_order_fulfilment();
  //  exit;

   // $order->update_tax();

    //$order->update_totals();
    /*
            $order->update_totals();
            $order->update_discounts_items();
            $order->update_totals();
            $order->update_shipping(false, false);
            $order->update_charges(false, false);
            $order->update_discounts_no_items();
            $order->update_deal_bridge();

    */

    $smarty->assign('order', $order);
    $smarty->assign('store', $store);
    $smarty->assign('customer', get_object('customer', $order->get('Order Customer Key')));
    if ($order->get('Order Delivery Note Key')) {
        $smarty->assign('delivery_note', get_object('DeliveryNote', $order->get('Order Delivery Note Key')));
    }

    if ($store->get('Store Type') == 'Dropshipping') {
        $smarty->assign('customer_client', get_object('customer_client', $order->get('Order Customer Client Key')));
    }

    $smarty->assign(
        'object_data',
        json_encode(array(
                        'object'              => $data['object'],
                        'key'                 => $data['key'],
                        'symbol'              => currency_symbol($order->get('Order Currency')),
                        'tax_rate'            => $order->get('Order Tax Rate'),
                        'available_to_refund' => $order->get('Order Total Amount'),
                        'tab'                 => $data['tab'],
                        'order_type'          => 'Order'
                    ))

    );


    if (in_array(
        $order->get('Order Delivery Address Country 2 Alpha Code'),
        get_countries_EC_Fiscal_VAT_area($db)
    )) {
        $pdf_with_commodity = false;
    } else {
        $pdf_with_commodity = true;
    }
    $smarty->assign('pdf_with_commodity', $pdf_with_commodity);

    if ($store->settings('invoice_show_pro_mode') == 'Yes') {
        $pdf_pro_mode = true;
    } else {
        $pdf_pro_mode = false;
    }
    $smarty->assign('pdf_pro_mode', $pdf_pro_mode);

    if ($store->get('Store Locale') != 'en_GB') {
        $pdf_show_locale_option = true;
    } else {
        $pdf_show_locale_option = false;
    }
    $smarty->assign('pdf_show_locale_option', $pdf_show_locale_option);


    if ($store->settings('invoice_show_rrp') == 'Yes') {
        $pdf_with_rrp = true;
    } else {
        $pdf_with_rrp = false;
    }
    $smarty->assign('pdf_with_rrp', $pdf_with_rrp);

    if ($store->settings('invoice_show_parts') == 'Yes') {
        $pdf_with_parts = true;
    } else {
        $pdf_with_parts = false;
    }
    $smarty->assign('pdf_with_parts', $pdf_with_parts);

    if ($store->settings('invoice_show_barcode') == 'Yes') {
        $pdf_with_barcode = true;
    } else {
        $pdf_with_barcode = false;
    }
    $smarty->assign('pdf_with_barcode', $pdf_with_barcode);

    if ($store->settings('invoice_show_weight') == 'Yes') {
        $pdf_with_weight = true;
    } else {
        $pdf_with_weight = false;
    }
    $smarty->assign('pdf_with_weight', $pdf_with_weight);

    if ($store->settings('invoice_show_origin') == 'Yes') {
        $pdf_with_origin = true;
    } else {
        $pdf_with_origin = false;
    }
    $smarty->assign('pdf_with_origin', $pdf_with_origin);

    if ($store->settings('invoice_show_CPNP') == 'Yes') {
        $pdf_with_CPNP = true;
    } else {
        $pdf_with_CPNP = false;
    }
    $smarty->assign('pdf_with_CPNP', $pdf_with_CPNP);


    if ($data['section'] == 'refund.new') {
        $smarty->assign('zero_amount', money(0, $store->get('Store Currency Code')));

        return $smarty->fetch('showcase/refund.new.tpl');
    } elseif ($data['section'] == 'replacement.new') {
        $smarty->assign('zero_amount', money(0, $store->get('Store Currency Code')));

        return $smarty->fetch('showcase/replacement.new.tpl');
    } elseif ($data['section'] == 'return.new') {
        $smarty->assign('zero_amount', money(0, $store->get('Store Currency Code')));

        return $smarty->fetch('showcase/return.new.tpl');
    } else {
        return $smarty->fetch('showcase/order.tpl');
    }
}


