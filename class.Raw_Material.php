<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  15:17:55 MYT Friday, 3 July 2020 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3.1

*/


include_once 'class.DB_Table.php';

class Raw_Material extends DB_Table {


    function __construct($a1 = false, $a2 = false) {

        global $db;
        $this->db = $db;


        $this->table_name = 'Raw Material';

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


        $sql = sprintf(
            "INSERT INTO `Raw Material Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($base_data)).'`', join(',', array_fill(0, count($base_data), '?'))
        );


        $stmt = $this->db->prepare($sql);


        $i = 1;
        foreach ($base_data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Raw material added");
            $this->get_data('id', $this->id);
            $this->update_raw_material_stock();

            $this->new = true;


            return $this;
        } else {
            print_r($stmt->errorInfo());
            $this->msg = "Error can not create raw material\n";


        }
    }

    function get_data($tag, $key) {


        $sql = sprintf(
            "SELECT * FROM `Raw Material Dimension` WHERE `Raw Material Key`=%d ", $key
        );
        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Raw Material Key'];
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

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        //    print_r($raw_data);


        if ($data['Raw Material Code'] == '') {
            return;
        }


        $sql  = "SELECT `Raw Material Key` FROM `Raw Material Dimension` WHERE `Raw Material Code`=?  ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $data['Raw Material Code']
            )
        );
        if ($row = $stmt->fetch()) {
            $this->found     = true;
            $this->found_key = $row['Raw Material Key'];
        }


        if ($create and !$this->found) {
            $this->create($data);

            return;
        }
        if ($this->found) {
            $this->get_data('id', $this->found_key);
        }


    }

    function get($key) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {


            default:

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Raw Material '.$key, $this->data)) {
                    return $this->data['Raw Material '.$key];
                }


        }

        return '';
    }

    function update_raw_material_stock($parent_object = null) {
        if ($this->data['Raw Material Type'] == 'Part') {
            if (is_object($parent_object)) {
                $part = $parent_object;
            } else {
                $part=get_object('Part',$this->data['Raw Material Type Key']);
            }


            $stock = $part->get('Part Current On Hand Stock') * $part->get('Part Units Per Package');


            $this->fast_update(
                [
                    'Raw Material Stock'        => ($stock > 0 ? $stock : 0),
                    'Raw Material Stock Status' => $part->get('Part Stock Status')
                ]
            );
        }


    }

}

