<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 June 2016 at 15:39:57 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'class.DB_Table.php';

class WebpageVersion extends DB_Table {


    function WebpageVersion($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Webpage Version';
        $this->ignore_fields = array('Webpage Version Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);

        } else {
            $this->get_data($a1, $a2, $a3);
        }
    }


    function get_data($key, $tag, $tag2 = false) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Webpage Version Dimension` WHERE `Webpage Version Key`=%d", $tag
            );
        } else {
            if ($key == 'webpage_code') {
                $sql = sprintf(
                    "SELECT  * FROM `Webpage Version Dimension` WHERE `Webpage Version Webpage Key`=%d AND `Webpage Version Code`=%s ", $tag, prepare_mysql($tag2)
                );
            } else {
                return;
            }
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id       = $this->data['Webpage Version Key'];
            $this->metadata = json_decode(
                $this->data['Webpage Version Metadata'], true
            );

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


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        if ($data['Webpage Version Code'] == '') {
            $this->error = true;
            $this->msg   = 'Webpage Version code empty';

            return;
        }


        $sql = sprintf(
            "SELECT `Webpage Version Key` FROM `Webpage Version Dimension` WHERE `Webpage Version Webpage Key`=%d AND  `Webpage Version Code`=%s", $data['Webpage Version Webpage Key'],
            prepare_mysql($data['Webpage Version Code'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Webpage Version Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Webpage Version Code';

                return;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($create and !$this->found) {
            $this->create($data);

            return;
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
            //   if (preg_match('/^()$/i', $key))
            //    $values.=prepare_mysql($value, false).",";
            //   else
            $values .= prepare_mysql($value).",";
        }


        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Webpage Version Dimension` %s %s", $keys, $values
        );

        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Webpage Version created");
            $this->get_data('id', $this->id);
            $this->new = true;


            return;
        } else {
            $this->msg = "Error can not create webpage";
            print $sql;
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

                if (array_key_exists('Webpage Version '.$key, $this->data)) {
                    return $this->data['Webpage Version '.$key];
                }


        }

        return '';
    }

    function get_metadata($key) {

        if (!$this->id or !$this->metadata) {
            return '';
        }

        if (array_key_exists($key, $this->metadata)) {
            return $this->metadata[$key];
        } else {
            return false;
        }

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

            case 'Webpage Version Code':
                $label = _('code');
                break;


            default:


                $label = $field;

        }

        return $label;

    }


    function append_block($data, $position = 0) {


        $sql = sprintf(
            'INSERT INTO `Webpage Version Block Bridge` (`Webpage Version Block Webpage Version Key`,`Webpage Version Block Position`,`Webpage Version Block Template`,`Webpage Version Block Settings`) VALUES (%d,%d,%s,%s) ',
            $this->id, (10 * $position) + 5, prepare_mysql($data['Webpage Version Block Template']), prepare_mysql(json_encode($data['Webpage Version Block Settings']))
        );

        $this->db->exec($sql);


        $sql = "SET @ordering_inc = 10;SET @new_ordering = 0;";
        $sql .= sprintf(
            "UPDATE  `Webpage Version Block Bridge` SET `Webpage Version Block Position` = (@new_ordering := @new_ordering + @ordering_inc) WHERE `Webpage Version Block Webpage Version Key`=%d ORDER BY `Webpage Version Block Position` DESC",
            $this->id
        );
        $this->db->exec($sql);
    }


    function get_content($smarty, $webpage) {


        $content = '';

        $sql = sprintf(
            'SELECT `Webpage Version Block Template`,`Webpage Version Block Settings` FROM  `Webpage Version Block Bridge` WHERE `Webpage Version Block Webpage Version Key`=%d  ORDER BY `Webpage Version Block Position` DESC',
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $smarty->assign(
                    'data', json_decode($row['Webpage Version Block Settings'], true)
                );

                if ($row['Webpage Version Block Template'] == 'product') {

                }

                $content .= $smarty->fetch(
                    'ecom/'.$row['Webpage Version Block Template'].'.tpl'
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $content;
    }


}


?>
