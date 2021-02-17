<?php
/*

  About:
  Author: Raul Perusquia <rulovico@gmail.com>
  Created: 2:16 pm Wednesday, 17 February 2021 (MYT) Kuala Lumpur, Malaysia

  Copyright (c) 2021,Inikoo

  Version 2.0
*/
include_once 'class.DB_Table.php';



class External_Invoicer extends DB_Table {

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {


        global $db;
        $this->db = $db;

        $this->table_name    = 'External Invoicer';
        $this->ignore_fields = array('External Invoicer Key');

        if (!$arg1 and !$arg2) {
            $this->error = true;
            $this->msg   = 'No data provided';

            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        if ($arg1 == 'create') {
            $this->create($arg2, $arg3);

            return;
        }

        $this->get_data($arg1, $arg2);
    }


    function get_data($tipo, $tag) {
        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `External Invoicer Dimension` WHERE  `External Invoicer Key`=%d", $tag
            );
        }  else {

            // print
            return;
        }
        //   print $sql;


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id         = $this->data['External Invoicer Key'];
            $this->metadata = json_decode($this->data['External Invoicer Metadata'], true);


        }


    }


    function get($key) {


        if (!$this->id) {
            return '';
        }




        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (array_key_exists('External Invoicer '.$key, $this->data)) {
            return $this->data[$this->table_name.' '.$key];
        }


        return false;
    }

    function update_totals() {




    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


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




    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }





}


