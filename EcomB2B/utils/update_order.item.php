<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   24 November 2019  19:46::08  +0100, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/
/**
 * @param $db \PDO
 * @param $order \Public_Order
 * @param $product_pid
 * @param $quantity
 * @param $website \Public_Website
 * @param $webpage_key
 * @param $page_section_type
 *
 * @return array
 */

function process_update_order_item($db,$order, $product_pid, $quantity, $website,$webpage_key, $page_section_type) {


    if ($order->get('Order State') == 'InBasket') {
        $order->fast_update(
            array(
                'Order Last Updated by Customer' => gmdate('Y-m-d H:i:s')
            )
        );
    }


    if ($quantity == '') {
        $quantity = 0;
    }

    if (is_numeric($quantity) and $quantity >= 0) {
        $quantity = ceil($quantity);


        $dispatching_state = 'In Process';


        $payment_state = 'Waiting Payment';


        $product = get_object('Product', $product_pid);
        $data    = array(
            'date'                      => gmdate('Y-m-d H:i:s'),
            'item_historic_key'         => $product->get('Product Current Key'),
            'item_key'                  => $product->id,
            'Metadata'                  => '',
            'qty'                       => $quantity,
            'Current Dispatching State' => $dispatching_state,
            'Current Payment State'     => $payment_state
        );

        $discounted_products                             = $order->get_discounted_products();
        $order->skip_update_after_individual_transaction = false;
        //print_r($data);
        $transaction_data = $order->update_item($data);


        $discounts_data = array();


        $sql = sprintf(
            'SELECT `Order Transaction Amount`,OTF.`Product ID`,OTF.`Product Key`,`Order Transaction Total Discount Amount`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Amount`,`Order Currency Code`,OTF.`Order Transaction Fact Key`, `Deal Info` FROM `Order Transaction Fact` OTF LEFT JOIN  `Order Transaction Deal Bridge` B ON (OTF.`Order Transaction Fact Key`=B.`Order Transaction Fact Key`) WHERE OTF.`Order Key`=%s ',
            $order->id
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                $discounts_data[$row['Order Transaction Fact Key']] = array(
                    'deal_info' => $row['Deal Info'],
                    'item_net'  => money($row['Order Transaction Amount'], $row['Order Currency Code'])
                );


            }
        }


        $basket_history = array(
            'otf_key'           => $transaction_data['otf_key'],
            'webpage_key'       => $webpage_key,
            'product_id'        => $product->id,
            'quantity_delta'    => $transaction_data['delta_qty'],
            'quantity'          => $transaction_data['qty'],
            'net_amount_delta'  => $transaction_data['delta_net_amount'],
            'net_amount'        => $transaction_data['net_amount'],
            'page_section_type' => $page_section_type

        );


        $order->add_basket_history($basket_history);


        $new_discounted_products = $order->get_discounted_products();
        foreach ($new_discounted_products as $key => $value) {
            $discounted_products[$key] = $value;
        }


        $hide         = array();
        $show         = array();
        $add_class    = array();
        $remove_class = array();

        $labels = $website->get('Localised Labels');

        if ($order->get('Shipping Net Amount') == 'TBC') {
            $shipping_amount = sprintf('<i class="fa error fa-exclamation-circle" aria-hidden="true"></i> <small>%s</small>', (!empty($labels['_we_will_contact_you']) ? $labels['_we_will_contact_you'] : _('We will contact you')));
        } else {
            $shipping_amount = $order->get('Shipping Net Amount');
        }

        if ($order->get('Order Charges Net Amount') == 0) {

            $add_class['order_charges_container'] = 'very_discreet';

            $hide[] = 'order_charges_info';
        } else {
            $remove_class['order_charges_container'] = 'very_discreet';

            $show[] = 'order_charges_info';
        }


        if ($order->get('Order Items Discount Amount') == 0) {

            $hide[] = 'order_items_gross_container';
            $hide[] = 'order_items_discount_container';
        } else {
            $show[] = 'order_items_gross_container';
            $show[] = 'order_items_discount_container';
        }


        if ($order->get('Order Deal Amount Off') == 0) {
            $hide[] = 'Deal_Amount_Off_tr';
        } else {
            $show[] = 'Deal_Amount_Off_tr';
        }

        if ($order->get('Order Number Items') == 0) {
            $hide[] = 'order_basket_footer';
        } else {
            $show[] = 'order_basket_footer';
        }


        $class_html = array(
            'Deal_Amount_Off'         => $order->get('Deal Amount Off'),
            'order_estimated_weight'       => $order->get('Estimated Weight'),
            'order_items_gross'       => $order->get('Items Gross Amount'),
            'order_items_discount'    => $order->get('Basket Items Discount Amount'),
            'order_items_net'         => $order->get('Items Net Amount'),
            'order_net'               => $order->get('Total Net Amount'),
            'order_tax'               => $order->get('Total Tax Amount'),
            'order_charges'           => $order->get('Charges Net Amount'),
            'order_credits'           => $order->get('Net Credited Amount'),
            'available_credit_amount' => $order->get('Available Credit Amount'),
            'order_shipping'          => $shipping_amount,
            'order_total'             => $order->get('Total Amount'),
            'to_pay_amount'           => $order->get('Basket To Pay Amount'),
            'ordered_products_number' => $order->get('Products'),
            'order_amount'            => ((!empty($website->settings['Info Bar Basket Amount Type']) and $website->settings['Info Bar Basket Amount Type'] == 'items_net') ? $order->get('Items Net Amount') : $order->get('Total'))
        );


        $response = array(
            'state'               => 200,
            'quantity'            => $transaction_data['qty'],
            'otf_key'             => $transaction_data['otf_key'],
            'product_pid'         => $product_pid,
            'description'         => $product->data['Product Units Per Case'].'x '.$product->data['Product Name'],
            'discount_percentage' => $transaction_data['discount_percentage'],
            'key'                 => $order->id,
            'to_charge'           => $transaction_data['to_charge'],

            'metadata' => array(
                'class_html'   => $class_html,
                'hide'         => $hide,
                'show'         => $show,
                'add_class'    => $add_class,
                'remove_class' => $remove_class,
                'new_otfs'     => $order->new_otfs,
                'deleted_otfs' => $order->deleted_otfs,

            ),


            'tmp' => localeconv(),

            'discounts_data' => $discounts_data,
            'discounts'      => ($order->data['Order Items Discount Amount'] != 0 ? true : false),
            'charges'        => ($order->data['Order Charges Net Amount'] != 0 ? true : false),
            'order_empty'    => ($order->get('Products') == 0 ? true : false),
            'analytics'      => array(
                'action' => ($transaction_data['delta_qty'] > 0 ? 'add' : ($transaction_data['delta_qty'] < 0 ? 'remove' : '')),
                'event'  => ($transaction_data['delta_qty'] > 0 ? 'Add to cart' : ($transaction_data['delta_qty'] < 0 ? 'Remove from cart' : '')),

                'product_data' => array(
                    'id'       => $product->get('Code'),
                    'name'     => $product->get('Name'),
                    'category' => $product->get('Family Code'),
                    'price'    => $product->get('Product Price'),
                    'quantity' => abs($transaction_data['delta_qty']),
                )


            )


        );
    } else {
        $response = array('state' => 200);
    }

    return $response;

}