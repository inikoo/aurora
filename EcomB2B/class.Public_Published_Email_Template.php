<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:9 July 2017 at 02:13:27 GMT+8, Cyberjaya. Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/



class Public_Published_Email_Template {

    public $id = 0;

    function Public_Published_Email_Template($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Published Email Template';
        $this->ignore_fields = array('Published Email Template Key');

        if ((is_numeric($a1) or $a1 == '') and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'new') {
            $this->create($a2);

        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);

        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($key, $tag) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Published Email Template Dimension` WHERE `Published Email Template Key`=%d", $tag
            );
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Published Email Template Key'];
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

                if (array_key_exists('Published Email Template '.$key, $this->data)) {
                    return $this->data['Published Email Template '.$key];
                }


        }

        return '';
    }



}


?>
