<?php

/*
 File: Ship_To.php

 This file contains the Ship To Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/


class Ship_To extends DB_Table {


    function __construct($arg1 = false, $arg2 = false) {

        global $db;
        $this->db = $db;


        $this->table_name    = 'Ship To';
        $this->ignore_fields = array('Ship To Key');

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        if (preg_match('/^(create|new)/i', $arg1)) {
            $this->find($arg2, 'create');

            return;
        }
        if (preg_match('/find/i', $arg1)) {
            $this->find($arg2, $arg1);

            return;
        }
        $this->get_data($arg1, $arg2);

        return;

    }


    function get_data($tipo, $tag) {

        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Ship To Dimension` WHERE `Ship To Key`=%d", $tag
            );
        } else {
            return;
        }



        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Ship To Key'];
        }



    }



    function get_xhtml_address() {
        return $this->display('xhtml');

    }



    function get($key = '') {




        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        switch ($key) {
        }
        $_key = ucfirst($key);
        if (isset($this->data[$_key])) {
            return $this->data[$_key];
        }
        print "Error $key not found in get from Ship TO\n";

        return false;

    }


}
