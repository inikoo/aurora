<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 November 2016 at 14:39:43 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'class.DB_Table.php';

class Template extends DB_Table {

 
    function Template($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Template';
        $this->ignore_fields = array('Template Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);

        }else {
            $this->get_data($a1, $a2, $a3);
        }
    }


    function get_data($key, $tag, $tag2 = false) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Template Dimension` WHERE `Template Key`=%d", $tag
            );
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Template Key'];



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

                if (array_key_exists('Template '.$key, $this->data)) {
                    return $this->data['Template '.$key];
                }


        }

        return '';
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


        if ($data['Template Code'] == '') {
            $this->error = true;
            $this->msg   = 'Template code empty';

            return;
        }


        $sql = sprintf(
            "SELECT `Template Key` FROM `Template Dimension` WHERE `Template Website Key`=%d AND  `Template Code`=%s", $data['Template Website Key'], prepare_mysql($data['Template Code'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Template Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Template Code';

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
            "INSERT INTO `Template Dimension` %s %s", $keys, $values
        );
//print "=======  $sql\"";
        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Template created");
            $this->get_data('id', $this->id);
            $this->new = true;






            return;
        } else {
            $this->msg = "Error can not create webpage";
            print $sql;
            exit;
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

            case 'Template Code':
                $label = _('code');
                break;

            default:


                $label = $field;

        }

        return $label;

    }

    function get_content($smarty, $version_key = false) {

        include_once 'utils/object_functions.php';

        if (!$version_key) {
            $version_key = $this->version->id;
        }

        $content = '';

        $object = get_object(
            $this->get('Template Object'), $this->get('Template Object Key')
        );


        $sql = sprintf(
            'SELECT `Template Version Block Template`,`Template Version Block Settings` FROM  `Template Version Block Bridge` WHERE `Template Version Block Template Version Key`=%d  ORDER BY `Template Version Block Position` DESC',
            $version_key
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $smarty->assign(
                    'data', json_decode($row['Template Version Block Settings'], true)
                );

                if ($row['Template Version Block Template'] == 'product') {
                    $smarty->assign('product', $object);

                }

                $content .= $smarty->fetch(
                    'ecom/'.$row['Template Version Block Template'].'.tpl'
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
