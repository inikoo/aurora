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
        $this->get_data($arg1, $arg2, $arg3);
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
            $this->id  = $this->db->lastInsertId();
            $this->new = true;
            $this->get_data('id', $this->id);


            $history_data = array(
                'History Abstract' => sprintf(_('Warehouse area %s (%s) created'), '<span class="italic">'.$this->get('Name').'</span>', '<span title="'._('Code').'" class="strong">'.$this->get('Code').'</span>'),
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

    function get_data($key, $tag, $tag2 = false) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Warehouse Area Dimension` WHERE `Warehouse Area Key`=%d", $tag
            );
        } elseif ($key == 'code') {
            $sql = sprintf(
                "SELECT  *  FROM `Warehouse Area Dimension` WHERE `Warehouse Area Code`=%s ", prepare_mysql($tag)
            );
        } elseif ($key == 'warehouse_code') {
            $sql = sprintf(
                "SELECT  *  FROM `Warehouse Area Dimension` WHERE `Warehouse Area Warehouse Key`=%d  and `Warehouse Area Code`=%s ", $tag, prepare_mysql($tag2)
            );
        } else {
            return false;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Warehouse Area Key'];

        }


    }

    function get($key, $data = false) {


        if (!$this->id) {
            return;
        }

        if (preg_match('/^warehouse (code|name)/i', $key)) {

            if (!$this->warehouse) {
                $warehouse = get_object('Warehouse', $this->data['Warehouse Key']);
            }

            return $warehouse->get($key);
        }

        switch ($key) {


            case('Location Codes'):

                $location_codes = $this->get_locations('codes');

                return join(', ', $location_codes);
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


    function get_locations($scope = 'keys') {

        $locations = array();
        switch ($scope) {
            case 'codes':
                $fields = '`Location Code`';
                break;
            default:
                $fields = '`Location Key`';
        }

        $sql = sprintf('select %s from `Location Dimension` where `Location Warehouse Area Key`=%d ', $fields, $this->id);


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                switch ($scope) {
                    case 'codes':
                        $locations[] = $row['Location Code'];
                        break;
                    case 'objects':
                        $locations[] = get_object('Location', $row['Location Key']);
                        break;
                    default:
                        $locations[] = $row['Location Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        return $locations;

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


        if ($raw_data['Warehouse Area Code'] == '') {
            $this->error_code = 'missing_field';
            $this->msg        = _('Missing code');

            // $this->duplicated_field = 'Warehouse Area Code';
            return;
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $val) {
            $_key        = $key;
            $data[$_key] = $val;
        }

        $sql = sprintf(
            "SELECT `Warehouse Area Key` FROM `Warehouse Area Dimension` WHERE `Warehouse Area Warehouse Key`=%d AND `Warehouse Area Code`=%s", $data['Warehouse Area Warehouse Key'], prepare_mysql($data['Warehouse Area Code'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found            = true;
                $this->error_code       = 'duplicated_field';
                $this->found_key        = $row['Warehouse Area Key'];
                $this->duplicated_field = 'Warehouse Area Code';
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
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

    function create_location($data) {

        include_once 'class.Location.php';


        $this->new_location = false;

        $data['editor'] = $this->editor;


        //print_r($data);

        if (!isset($data['Location Code']) or $data['Location Code'] == '') {
            $this->error      = true;
            $this->msg        = _("Location missing");
            $this->error_code = 'location_code_missing';
            $this->metadata   = '';

            return;
        }

        $sql = sprintf(
            'SELECT count(*) AS num FROM `Location Dimension` WHERE `Location Code`=%s AND `Location Warehouse Key`=%d  ', prepare_mysql($data['Location Code']), $this->get('Warehouse Area Warehouse Key')

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['num'] > 0) {
                    $this->error      = true;
                    $this->msg        = sprintf(_('Duplicated code (%s)'), $data['Location Code']);
                    $this->error_code = 'duplicate_location_code_reference';
                    $this->metadata   = $data['Location Code'];

                    return;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if (isset($data['Location Flag Color'])) {

            if ($data['Location Flag Color'] != '') {

                $sql = sprintf(
                    "SELECT `Warehouse Flag Key` FROM  `Warehouse Flag Dimension` WHERE `Warehouse Flag Color`=%s", prepare_mysql(ucfirst($data['Location Flag Color']))
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {

                        $data['Location Warehouse Flag Key'] = $row['Warehouse Flag Key'];

                    } else {
                        $this->error      = true;
                        $this->msg        = _('Flag invalid colour');
                        $this->error_code = 'location_flag_color_not_valid';

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }
            }

        }


        $data['Location Warehouse Key']      = $this->get('Warehouse Area Warehouse Key');
        $data['Location Warehouse Area Key'] = $this->id;


        $location = new Location('find', $data, 'create');


        if ($location->id) {
            $this->new_object_msg = $location->msg;

            if ($location->new) {
                $this->new_object   = true;
                $this->new_location = true;


                $warehouse = get_object('Warehouse', $this->get('Warehouse Area Warehouse Key'));

                if ($location->get('Location Warehouse Flag Key')) {
                    $warehouse->update_location_flag_number($location->get('Location Warehouse Flag Key'));
                }

                $warehouse->update_children();


                $this->update_warehouse_area_locations();

            } else {

                $this->error = true;
                if ($location->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(
                        array($location->duplicated_field)
                    );

                    if ($location->duplicated_field == 'Location Code') {
                        $this->msg = _("Duplicated location code");
                    } else {
                        $this->msg = 'Duplicated '.$location->duplicated_field;
                    }


                } else {
                    $this->msg = $location->msg;
                }
            }

            return $location;
        } else {

            $this->error = true;
            $this->msg   = $location->msg;

        }

    }

    function update_warehouse_area_locations() {
        $number_locations = 0;

        $sql = sprintf(
            'SELECT count(*) AS number FROM `Location Dimension` WHERE `Location Warehouse Area Key`=%d', $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_locations = $row['number'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->fast_update(
            array(
                'Warehouse Area Number Locations' => $number_locations
            )


        );


    }


}

?>