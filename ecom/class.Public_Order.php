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
        $this->db = $db;
        $this->id = false;
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
                $this->id          = $this->data['Order Key'];
                $this->currency_code=$this->data['Order Currency'];
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


        }

    }


    function set_display_currency($currency_code, $exchange) {
        $this->currency_code = $currency_code;
        $this->exchange      = $exchange;

    }

}


?>
