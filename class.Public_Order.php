<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 December 2016 at 20:02:17 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


class Public_Order {

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db       = $db;
        $this->id       = false;
        $this->exchange = 1;


        $this->table_name = 'Order';

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

    function get($key, $arg1 = '') {


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

            case 'Order Items Discount Amount':
            case 'Order Charges Net Amount':
                return $this->data[$key];
                break;

            case 'Products':
                return number($this->data['Order Number Items']);
                break;
            case 'Total':
                return money($this->data['Order Total Amount'], $this->data['Order Currency']);
                break;

            default:
                $_key = ucwords($key);
                if (array_key_exists($_key, $this->data)) {
                    return $this->data[$_key];
                }

                if (array_key_exists('Order '.$key, $this->data)) {
                    return $this->data['Order '.$key];
                }

        }

    }


    function set_display_currency($currency_code, $exchange) {
        $this->currency_code = $currency_code;
        $this->exchange      = $exchange;

    }


    function get_items() {

        $sql = sprintf(
            'SELECT OTF.`Product ID`,OTF.`Product Key`,`Order Transaction Fact Key`,`Order Currency Code`,`Order Transaction Amount`,`Order Quantity`,`Product History Name`,`Product History Units Per Case`,PD.`Product Code`,`Product Name`,`Product Units Per Case` FROM `Order Transaction Fact` OTF LEFT JOIN `Product History Dimension` PHD ON (OTF.`Product Key`=PHD.`Product Key`) LEFT JOIN `Product Dimension` PD ON (PD.`Product ID`=PHD.`Product ID`)  WHERE `Order Key`=%d  ORDER BY `Product Code File As` ',
            $this->id
        );

        $items = array();




        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $edit_quantity = sprintf(
                    '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-plus fa-fw like_button button"  style="cursor:pointer" aria-hidden="true"></i></span>',
                    $row['Order Transaction Fact Key'], $row['Product ID'], $row['Product Key'], $row['Order Quantity'] + 0, $row['Order Quantity'] + 0
                );



                $items[] = array(
                    'code'        => $row['Product Code'],
                    'description' => $row['Product History Units Per Case'].'x '.$row['Product History Name'],
                    'qty'         => number($row['Order Quantity']),
                    'edit_qty'    => $edit_quantity,
                    'amount'      => '<span class="item_amount">'.money($row['Order Transaction Amount'], $row['Order Currency Code']).'</span>'

                );


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $items;

    }

}


?>
