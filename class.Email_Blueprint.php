<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 July 2017 at 11:40:08 GMT+8, Cyberjaya. Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


include_once 'class.DB_Table.php';

class Email_Blueprint extends DB_Table {


    function Email_Blueprint($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Email Blueprint';
        $this->ignore_fields = array('Email Blueprint Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'new') {
            $this->create($a2);

        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($key, $tag) {


        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Email Blueprint Dimension` WHERE `Email Blueprint Key`=%d", $tag
            );
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Email Blueprint Key'];
        }


    }


    function create($data) {



        $this->editor=$data['editor'];

        $data['Email Blueprint Created'] = gmdate('Y-m-d H:i:s');
        $data['Email Blueprint Created By'] = $this->editor['Author Key'];


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
            "INSERT INTO `Email Blueprint Dimension` %s %s", $keys, $values
        );


        // print $sql;


        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = "Email Blueprint added";
            $this->get_data('id', $this->id);
            $this->new = true;

            return $this;
        } else {
            $this->msg = "Error can not create Email Blueprint";

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

                if (array_key_exists('Email Blueprint '.$key, $this->data)) {
                    return $this->data['Email Blueprint '.$key];
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


            case 'Email Blueprint Name':
                $label = _('name');
                break;


            default:


                $label = $field;

        }

        return $label;

    }

    function delete(){

        $sql=sprintf('delete from `Email Blueprint Dimension` where `Email Blueprint Key`=%d ',$this->id);


        $this->db->exec($sql);

    }


}


?>
