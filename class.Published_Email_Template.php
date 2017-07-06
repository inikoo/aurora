<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 July 2017 at 11:33:49 GMT+8, Cyberjaya. Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


include_once 'class.DB_Table.php';

class Published_Email_Template extends DB_Table {


    function Published_Email_Template($a1, $a2 = false, $a3 = false) {

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


    function create($data) {

        $data['Published Email Template From']         = gmdate('Y-m-d H:i:s');
        $data['Published Email Template Published By'] = $data['editor']['Author Key'];


        $this->new = false;
        $base_data = $this->base_data();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }

        $keys   = '(';
        $values = 'values(';
        foreach ($base_data as $key => $value) {
            $keys   .= "`$key`,";
            $values .= prepare_mysql($value).",";
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Published Email Template Dimension` %s %s", $keys, $values
        );


        // print $sql;


        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = "Published Email Template added";
            $this->get_data('id', $this->id);
            $this->new = true;

            return $this;
        } else {
            $this->msg = "Error can not create Published Email Template";

            print_r($this->db->errorInfo());
            // print $sql;
            exit;
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


    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if ($this->deleted) {
            return;
        }

        switch ($field) {
            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                }


        }
    }


    function get_field_label($field) {

        switch ($field) {


            default:


                $label = $field;

        }

        return $label;

    }


}


?>
