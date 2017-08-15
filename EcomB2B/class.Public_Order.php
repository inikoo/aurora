<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 December 2016 at 20:02:17 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'trait.OrderShippingOperations.php';
include_once 'trait.OrderChargesOperations.php';
include_once 'trait.OrderDiscountOperations.php';
include_once 'trait.OrderItems.php';
include_once 'trait.OrderPayments.php';
include_once 'trait.OrderCalculateTotals.php';
include_once 'trait.OrderBasketOperations.php';
include_once 'trait.OrderTax.php';


include_once 'class.DBW_Table.php';


class Public_Order extends DBW_Table {
    use OrderShippingOperations, OrderChargesOperations, OrderDiscountOperations, OrderItems, OrderPayments, OrderCalculateTotals,OrderBasketOperations,OrderTax;


    var $amount_off_allowance_data = false;


    function Public_Order($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db       = $db;
        $this->id       = false;
        $this->exchange = 1;


        $this->table_name = 'Order';


        if (preg_match('/new/i', $arg1)) {
            $this->create_order($arg2);

            return;
        }


        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        $this->get_data($arg1, $arg2, $arg3);


    }


    function get_data($key, $id, $aux_id = false) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Order Dimension` WHERE `Order Key`=%d", $id
            );
            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id            = $this->data['Order Key'];
                $this->currency_code = $this->data['Order Currency'];
            }
        } else {

            return;
        }


    }

    function set_display_currency($currency_code, $exchange) {
        $this->currency_code = $currency_code;
        $this->exchange      = $exchange;

    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {



            case('Order Tax Number'):
                $this->update_tax_number($value);
                break;
            case('Order Tax Number Valid'):
                $this->update_tax_number_valid($value);
                break;
            case 'Order Invoice Address':
                $this->update_address('Invoice', json_decode($value, true));


                $customer = get_object('Customer', $this->data['Order Customer Key']);
                $customer->update_field_switcher('Customer Invoice Address', $value, '', array('no_propagate_orders' => true));


                break;
            case 'Order Delivery Address':
                $this->update_address('Delivery', json_decode($value, true));
                break;

            case('Order State'):
                $this->update_state($value, $options, $metadata);
                break;

            default:
                $base_data = $this->base_data();


                if (array_key_exists($field, $base_data)) {
                    // print "xxx-> $field : $value -> ".$this->data[$field]." \n";

                    if ($value != $this->data[$field]) {

                        $this->update_field($field, $value, $options);
                    }
                }
        }

    }

    function update_state($value, $options = '', $metadata = array()) {
        $date = gmdate('Y-m-d H:i:s');


        $old_value         = $this->get('Order State');
        $operations        = array();
        $deliveries_xhtml  = '';
        $number_deliveries = 0;

        if ($old_value != $value) {

            switch ($value) {


                case 'InProcess':


                    if ($this->data['Order State'] != 'InBasket') {
                        $this->error = true;
                        $this->msg   = 'Order is not in basket: :(';

                        return;

                    }


                    $this->update_field('Order State', $value, 'no_history');
                    $this->update_field('Order Submitted by Customer Date', $date, 'no_history');
                    $this->update_field('Order Date', $date, 'no_history');
                    $this->update_field('Order Class', 'InProcess', 'no_history');


                    $history_data = array(
                        'History Abstract' => _('Order submited'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);

                    $operations = array('send_to_warehouse');


                    $sql = sprintf(
                        'update `Order Transaction Fact` set `Current Dispatching State`="Submitted by Customer" where `Order Key`=%d  and `Current Dispatching State` in ("In Process by Customer","In Process")  ',
                        $this->id
                    );

                    $this->db->exec($sql);


                    break;

                default:
                    $this->error = true;
                    $this->msg   = 'Unknown error';

                    return;
                    break;
            }


        }

        $this->update_metadata = array(
            'class_html'        => array(
                'Order_State'                  => $this->get('State'),
                'Order_Submitted_Date'         => '&nbsp;'.$this->get('Submitted by Customer Date'),
                'Order_Send_to_Warehouse_Date' => '&nbsp;'.$this->get('Send to Warehouse Date'),


            ),
            'operations'        => $operations,
            'state_index'       => $this->get('State Index'),
            'deliveries_xhtml'  => $deliveries_xhtml,
            'number_deliveries' => $number_deliveries
        );


    }

    function get($key, $arg1 = '') {


        switch ($key) {



            case 'Order Invoice Address':
            case 'Order Delivery Address':

                if ($key == 'Order Delivery Address') {
                    $type = 'Delivery';
                } else {
                    $type = 'Invoice';
                }

                $address_fields = array(

                    'Address Recipient'            => $this->get($type.' Address Recipient'),
                    'Address Organization'         => $this->get($type.' Address Organization'),
                    'Address Line 1'               => $this->get($type.' Address Line 1'),
                    'Address Line 2'               => $this->get($type.' Address Line 2'),
                    'Address Sorting Code'         => $this->get($type.' Address Sorting Code'),
                    'Address Postal Code'          => $this->get($type.' Address Postal Code'),
                    'Address Dependent Locality'   => $this->get($type.' Address Dependent Locality'),
                    'Address Locality'             => $this->get($type.' Address Locality'),
                    'Address Administrative Area'  => $this->get($type.' Address Administrative Area'),
                    'Address Country 2 Alpha Code' => $this->get($type.' Address Country 2 Alpha Code'),


                );

                return json_encode($address_fields);
                break;
            case 'Invoice Address':
            case 'Delivery Address':

                return $this->get('Order '.$key.' Formatted');
                break;

            case 'Basket To Pay Amount':
                return money($this->data['Order Total Amount'] - $this->data['Order Available Credit Amount'], $this->data['Order Currency']);
                break;

            case 'Order Basket To Pay Amount':
                return $this->data['Order Total Amount'] - $this->data['Order Available Credit Amount'];
                break;

            case 'Products':
                return number($this->data['Order Number Items']);
                break;

            case 'Total':
                return money($this->data['Order Total Amount'], $this->data['Order Currency']);
                break;

            default:


                if (preg_match(
                    '/^(Balance (Total|Net|Tax)|Invoiced Total Net Adjust|Invoiced Total Tax Adjust|Invoiced Refund Net|Invoiced Refund Tax|Total|Items|Invoiced Items|Invoiced Tax|Invoiced Net|Invoiced Charges|Payments|To Pay|Invoiced Shipping|Invoiced Insurance |(Shipping |Charges |Insurance )?Net).*(Amount)$/',
                    $key
                )) {
                    $amount = 'Order '.$key;

                    return money(
                        $this->exchange * $this->data[$amount], $this->currency_code
                    );
                }
                if (preg_match('/^Number (Items|Products)$/', $key)) {

                    $amount = 'Order '.$key;

                    return number($this->data[$amount]);
                }

                $_key = ucwords($key);
                if (array_key_exists($_key, $this->data)) {
                    return $this->data[$_key];
                }

                if (array_key_exists('Order '.$key, $this->data)) {
                    return $this->data['Order '.$key];
                }

        }

    }


}


?>
