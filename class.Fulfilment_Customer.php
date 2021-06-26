<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 26 june 2021 14:12 Kuala Lumpur Malaysia

 Copyright (c) 2021, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class Fulfilment_Customer extends DB_Table {


    function __construct($a1, $a2 = false) {

        global $db;
        $this->db         = $db;
        $this->error_code = '';

        $this->table_name    = 'Fulfilment Customer';
        $this->ignore_fields = array('Fulfilment Customer Key');

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

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        switch ($key) {



        }

        return false;
    }


}