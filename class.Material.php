<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 30 April 2014 15:10:05 CEST, Malaga Spain

 Version 2.0
*/
include_once 'class.DB_Table.php';

class Material extends DB_Table {

    function __construct($a1 = false, $a2 = false) {

        global $db;
        $this->db = $db;


        $this->table_name    = 'Material';
        $this->ignore_fields = array('Material Key');

        if ($a1 == 'create') {
            $this->create($a2);

        }
        if ($a1 == 'find create') {
            $this->find($a2, $a1);

        } else {
            if (is_numeric($a1) and !$a2) {
                $this->get_data('id', $a1);

            } else {
                $this->get_data($a1, $a2);
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
            $keys .= "`$key`,";

            if ($key == 'Material XHTML Description') {
                $values .= prepare_mysql($value, false).",";

            } else {
                $values .= prepare_mysql($value).",";

            }


        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);

        $sql = sprintf(
            "INSERT INTO `Material Dimension` %s %s", $keys, $values
        );


        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Material added");
            $this->get_data('id', $this->id);
            $this->new = true;


            return;
        } else {
            $this->msg = "Error can not create material\n";
        }
    }

    function get_data($tag, $key) {


        $sql = sprintf(
            "SELECT * FROM `Material Dimension` WHERE `Material Key`=%d ", $key
        );
        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Material Key'];
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

        $this->found     = false;
        $this->found_key = false;

        $create = '';
        $update = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }
        if (preg_match('/update/i', $options)) {
            $update = 'update';
        }

        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        //    print_r($raw_data);


        if ($data['Material Name'] == '') {
            return;
        }


        $sql = sprintf(
            "SELECT `Material Key` FROM `Material Dimension` WHERE `Material Name`=%s  ", prepare_mysql($data['Material Name'])
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Material Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($create and !$this->found) {
            $this->create($data);

            return;
        }
        if ($this->found) {
            $this->get_data('id', $this->found_key);
        }

        if ($update and $this->found) {

        }

    }

    function get($key, $data = false) {
        switch ($key) {

            case 'Parts Number':
                return number($this->data['Material '.$key]);
                break;
            case 'Type':
                switch ($this->data['Material Type']) {
                    case 'Material':
                        return _('Material');
                        break;
                    case 'Ingredient':
                        return _('Ingredient');
                        break;
                    default:
                        return $this->data['Material Type'];
                        break;
                }
                break;
            default:

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Material '.$key, $this->data)) {
                    return $this->data['Material '.$key];
                }


        }

        return '';
    }

    function update_stats() {

        $parts = 0;
        $sql   = sprintf(
            'SELECT count(DISTINCT `Part SKU`) parts FROM `Part Material Bridge` WHERE `Material Key`=%d ', $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $parts = $row['parts'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        $this->update(array('Material Parts Number' => $parts), 'no_history');

    }

    protected function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {

            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    $this->update_field($field, $value, $options);
                }
                break;
        }
    }


}


?>
