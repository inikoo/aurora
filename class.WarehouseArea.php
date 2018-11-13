<?php
/*

 About: 
 Author: Raul Perusquia <rulovico@gmail.com>

 Refurbished: 12 November 2018 at 15:04:45 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('class.DB_Table.php');


class WarehouseArea extends DB_Table {


    var $locations = false;

    var $warehouse = false;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Warehouse Area';
        $this->ignore_fields = array('Warehouse Area Key');

        if (preg_match('/^(new|create)$/i', $arg1) and is_array($arg2)) {
            $this->create($arg2);

            return;
        }

        if (preg_match('/find/i', $arg1)) {
            $this->find($arg2, $arg3);

            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        $this->get_data($arg1, $arg2);
    }


    function create($data) {

        $this->data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }

        if ($this->data['Warehouse Area Code'] == '') {
            $this->msg   = _('Field required');
            $this->new   = false;
            $this->error = true;

            return;
        }


        if ($this->data['Warehouse Area Name'] == '') {
            $this->data['Warehouse Area Name'] = $this->data['Warehouse Area Code'];
        }

        $keys   = '(';
        $values = 'values(';
        foreach ($this->data as $key => $value) {

            $keys  .= "`$key`,";
            $_mode = true;
            if ($key == 'Warehouse Area Description') {
                $_mode = false;
            }
            $values .= prepare_mysql($value, $_mode).",";
        }

        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);

        $sql = sprintf(
            "INSERT INTO `Warehouse Area Dimension` %s %s", $keys, $values
        );


        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();
            $this->new = true;
            $this->get_data('id', $this->id);



            $history_data = array(
                'History Abstract' => sprintf(_('Warehouse area %s (%s) created'), '<span class="italic">'.$this->get('Name').'</span>' ,'<span title="'._('Code').'" class="strong">'.$this->get('Code').'</span>'),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );



        } else {
            $this->error = true;
            $this->msg   = 'Error inserting warehouse area record';
        }

    }

    function get_data($key, $tag) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Warehouse Area Dimension` WHERE `Warehouse Area Key`=%d", $tag
            );
        } elseif ($key == 'code') {
            $sql = sprintf(
                "SELECT  *  FROM `Warehouse Area Dimension` WHERE `Warehouse Area Code`=%s ", prepare_mysql($tag)
            );
        } else {
            return false;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Warehouse Area Key'];

        }


    }

    function find($raw_data, $options) {

        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {

                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
        }


        $this->found = false;
        $create      = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $val) {
            $_key        = $key;
            $data[$_key] = $val;
        }

        $sql = sprintf(
            "SELECT `Warehouse Area Key` FROM `Warehouse Area Dimension` WHERE `Warehouse Area Warehouse Key`=%d AND `Warehouse Area Code`=%s", $data['Warehouse Area Warehouse Key'], prepare_mysql($data['Warehouse Area Code'])
        );

        if ($result=$this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Warehouse Area Key'];
        	}
        }else {
        	print_r($error_info=$this->db->errorInfo());
        	print "$sql\n";
        	exit;
        }


        if ($this->found) {
            $this->get_data('id', $this->found_key);
        }


        if ($create) {
            $this->create($data, $options);
        }
    }

    function get($key, $data = false) {


        if(!$this->id){
            return;
        }

        if (preg_match('/^warehouse (code|name)/i', $key)) {

            if (!$this->warehouse) {
                $warehouse = get_object('Warehouse', $this->data['Warehouse Key']);
            }

            return $warehouse->get($key);
        }

        switch ($key) {
            case('num_locations'):
            case('number_locations'):
                if (!$this->areas) {
                    $this->load('areas');
                }

                return count($this->areas);
                break;
            case('locations'):
                if (!$this->locations) {
                    $this->load('locations');
                }

                return $this->locations;
                break;
            case('area'):
                if (!$this->locations) {
                    $this->load('locations');
                }
                if (isset($this->locations[$data['id']])) {
                    return $this->locations[$data['id']];
                }
                break;
            default:


                if (array_key_exists('Warehouse Area '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }

                if (isset($this->data[$key])) {
                    return $this->data[$key];
                } else {
                    return '';
                }
        }

        return '';
    }

    function load($key = '') {
        switch ($key) {
            case('locations'):

                break;

        }


    }

    function add_location($data) {
        $this->updated                       = false;
        $data['Location Warehouse Key']      = $this->data['Warehouse Key'];
        $data['Location Warehouse Area Key'] = $this->id;

        include_once 'class.Location.php';

        $location               = new Location('find', $data, 'create');
        $this->new_location_msg = $location->msg;
        if ($location->new) {

            $this->updated      = true;
            $this->new_location = $location;

        } else {
            if ($location->found) {
                $this->new_location_msg = _(
                    'Location Code already in the warehouse'
                );
            }
        }
    }


    function update_children() {
        $sql              = sprintf(
            'SELECT count(*) AS number FROM `Location Dimension` WHERE `Location Warehouse Area Key`=%d', $this->id
        );
        $res              = mysql_query($sql);
        $number_locations = 0;
        if ($row = mysql_fetch_array($res)) {
            $number_locations = $row['number'];
        }
        $sql           = sprintf(
            'SELECT count(*) AS number FROM `Shelf Dimension` WHERE `Shelf Area Key`=%d', $this->id
        );
        $res           = mysql_query($sql);
        $number_shelfs = 0;
        if ($row = mysql_fetch_array($res)) {
            $number_shelfs = $row['number'];
        }
        $sql = sprintf(
            'UPDATE `Warehouse Area Dimension` SET `Warehouse Area Number Locations`=%d , `Warehouse Area Number Shelfs`=%d WHERE `Warehouse Area Key`=%d', $number_locations, $number_shelfs, $this->id
        );
        mysql_query($sql);
        $this->get_data('id', $this->id);
    }

    function delete() {
        $this->deleted     = false;
        $this->deleted_msg = '';

        if ($this->id == 1) {
            $this->deleted_msg = 'Error area unknown can not be deleted';

            return;
        }

        $move_all_locations = true;
        $sql                = sprintf(
            "SELECT `Location Key` FROM `Location Dimension` WHERE `Location Warehouse Area Key`=%d", $this->id
        );
        $result             = mysql_query($sql);
        while ($row = mysql_fetch_assoc($result)) {
            $location = new Location($row['Location Key']);
            $location->update(array('Location Warehouse Area Key' => '1'));
            if (!$location->updated) {
                $move_all_locations &= false;
            }
        }


        if ($move_all_locations) {
            $sql = sprintf(
                "DELETE FROM `Warehouse Area Dimension` WHERE `Warehouse Area Key`=%d", $this->id
            );
            mysql_query($sql);
        }


        if (mysql_affected_rows() > 0) {
            $this->deleted = true;
        } else {
            $this->deleted_msg = 'Error area can not be deleted';
        }

    }


    function get_field_label($field) {

        switch ($field) {

            case 'Warehouse Area Code':
                $label = _('code');
                break;
            case 'Warehouse Area Name':
                $label = _('name');
                break;


            default:


                $label = $field;

        }

        return $label;

    }
}

?>