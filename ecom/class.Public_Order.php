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
            }
        } else {

            return;
        }


    }

    function get($key, $arg1 = '') {

        switch ($key) {
           
            default:


        }

    }



}


?>
