<?php
/*
 
 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 13 February 2018 at 19:51:28 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/

include_once 'class.DB_Table.php';


class Customer_Poll_Query_Option extends DB_Table {

    var $deleted = false;

    function Customer_Poll_Query_Option($a1, $a2 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Customer Poll Query Option';
        $this->ignore_fields = array('Customer Poll Query Option Key');


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
                "SELECT * FROM `Customer Poll Query Option Dimension` WHERE `Customer Poll Query Option Key`=%d", $tag
            );
        } elseif ($key == 'deleted') {
            $this->get_deleted_data($tag);

            return;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Customer Poll Query Option Key'];
        }


    }


    function get_deleted_data($tag) {

        $this->deleted = true;
        $sql           = sprintf(
            "SELECT * FROM `Customer Poll Query Option Deleted Dimension` WHERE `Customer Poll Query Option Deleted Key`=%d", $tag
        );

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id     = $this->data['Customer Poll Query Option Deleted Key'];
            $deleted_data = json_decode(
                gzuncompress($this->data['Customer Poll Query Option Deleted Metadata']), true
            );
            foreach ($deleted_data['data'] as $key => $value) {
                $this->data[$key] = $value;
            }
        }
    }


    function create($data) {


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
            "INSERT INTO `Customer Poll Query Option Dimension` %s %s", $keys, $values
        );


        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Option for customer poll created");
            $this->get_data('id', $this->id);
            $this->new = true;



            $history_data = array(
                'History Abstract' => _("Option for customer poll created"),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


            return;
        } else {
            $this->msg = "Error can not create poll query option";
            print $sql;
            exit;
        }
    }

    function get($key, $data = false) {

        if (!$this->id or $this->deleted) {
            return '';
        }


        switch ($key) {




            default:


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Customer Poll Query Option '.$key, $this->data)) {
                    return $this->data['Customer Poll Query Option '.$key];
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


            case 'Customer Poll Query Option Name':
                $label = _('answer code');
                break;
            case 'Customer Poll Query Option Label':
                $label = _('answer');
                break;


            default:

                $label = $field;

        }

        return $label;

    }

    function update_customers(){

        $number_customers = 0;
        $last_answered='';



        $sql = sprintf('SELECT count(DISTINCT `Customer Poll Customer Key`) AS number , max(`Customer Poll Date`)  last_answered FROM `Customer Poll Fact` WHERE `Customer Poll Query Key`=%d', $this->id);


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_customers = $row['number'];
                $last_answered=$row['last_answered'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $this->fast_update(
            array(
                'Customer Poll Query Option Customers' => $number_customers,
                'Customer Poll Query Option Last Answered'=>$last_answered

            )
        );




    }

}


?>
