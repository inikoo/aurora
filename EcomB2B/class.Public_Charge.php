<?php
/*
 /*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 September 2017 at 13:25:13 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


include_once('class.DBW_Table.php');

class Public_Charge extends DBW_Table {


    function __construct($a1) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Charge';
        $this->ignore_fields = array('Charge Key');

        $this->get_data('id', $a1);


    }

    function get_data($tipo, $tag) {


        $sql = sprintf(
            "SELECT * FROM `Charge Dimension` WHERE `Charge Key`=%d", $tag
        );


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Charge Key'];
        }


    }


    function get($key = '') {

        if (!$this->id) {
            return;
        }

        switch ($key) {
            case 'Amount':
                $store = get_object('Store', $this->data['Charge Store Key']);

                return money($this->data['Charge Total Acc '.$key], $store->get('Store Currency Code'));

                break;
            case 'Orders':
            case 'Customers':

                return number($this->data['Charge Total Acc '.$key]);

                break;

            case 'Number History Records':

                return number($this->data['Charge '.$key]);

                break;
            default:
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Charge '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }


                return false;
        }


    }


}