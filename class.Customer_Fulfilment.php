<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 26 june 2021 14:12 Kuala Lumpur Malaysia

 Copyright (c) 2021, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class Customer_Fulfilment extends DB_Table {


    function __construct($a1, $a2 = false) {

        global $db;
        $this->db         = $db;
        $this->error_code = '';

        $this->table_name    = 'Customer Fulfilment';
        $this->ignore_fields = array('Customer Fulfilment Customer Key');

        $this->get_data($a1, $a2);

    }

    function get_data($tipo, $tag) {

        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Customer Fulfilment Dimension` WHERE `Customer Fulfilment Customer Key`=%d", $tag
            );

            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id = $this->data['Customer Fulfilment Customer Key'];
            }
        }
    }

    function get($key = '') {

        if (!$this->id) {
            return;
        }


        switch ($key) {


        }

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (array_key_exists('Customer Fulfilment '.$key, $this->data)) {
            return $this->data[$this->table_name.' '.$key];
        }

        return false;
    }

    function get_field_label($field) {

        switch ($field) {
            case 'Customer Fulfilment Allow Part Procurement':
                $label = _('Full product procurement service');
                break;
            case 'Customer Fulfilment Allow Pallet Storing':
                $label = _('Asset storing');
                break;
            default:
                $label = $field;
        }

        return $label;
    }
}