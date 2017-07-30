<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 July 2017 at 23:04:19 GMT+8, Cyberjaya. Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


class Public_Email_Template {

    public $id = 0;

    function Public_Email_Template($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Email Template';
        $this->ignore_fields = array('Email Template Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'new') {
            $this->create($a2);

        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);

        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($tag, $key) {

        if ($tag == 'id') {

            $sql = sprintf(
                "SELECT * FROM `Email Template Dimension` WHERE `Email Template Key`=%d", $key
            );
        } else {
            return;
        }



        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Email Template Key'];
        }


    }


    function get($key, $data = false) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {


            default:


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Email Template '.$key, $this->data)) {
                    return $this->data['Email Template '.$key];
                }


        }

        return '';
    }


}


?>
