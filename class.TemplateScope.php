<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 November 2016 at 20:22:09 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'class.DB_Table.php';

class TemplateScope extends DB_Table {


    function TemplateScope($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Template Scope';
        $this->ignore_fields = array('Template Scope Key');

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
                "SELECT * FROM `Template Scope Dimension` WHERE `Template Scope Key`=%d", $tag
            );
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Template Scope Key'];


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


        if ($data['Template Scope Code'] == '') {
            $this->error = true;
            $this->msg   = 'Template Scope code empty';

            return;
        }


        $sql = sprintf(
            "SELECT `Template Scope Key` FROM `Template Scope Dimension` WHERE `Template Scope Website Key`=%d AND  `Template Scope Code`=%s", $data['Template Scope Website Key'],
            prepare_mysql($data['Template Scope Code'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Template Scope Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Template Scope Code';

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
            "INSERT INTO `Template Scope Dimension` %s %s", $keys, $values
        );
        //print "=======  $sql\"";

        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Template Scope created");
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

            case 'Template Scope Code':
                $label = _('code');
                break;

            default:


                $label = $field;

        }

        return $label;

    }

    function create_template($data) {

        include_once('class.Template.php');


        $template_data = array(
            'Template Website Key' => $this->get('Template Scope Website Key'),
            'Template Scope Key'   => $this->id,
            'Template Code'        => $data['Template Code'],
            'Template Scope'       => $this->get('Template Scope Code'),
            'Template Base'        => $data['Template Base'],
            'Template Device'      => $data['Template Device'],
            'editor'               => $this->editor

        );


        $template = new Template('find', $template_data, 'create');
        $this->update_template_numbers();
        return $template;

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

                if (array_key_exists('Template Scope '.$key, $this->data)) {
                    return $this->data['Template Scope '.$key];
                }


        }

        return '';
    }


    function update_template_numbers() {
        $templates = 0;
        $sql       = sprintf(
            'SELECT count(*) AS num FROM `Template Dimension` WHERE `Template Scope Key`=%d ', $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $templates = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->update(
            array('Template Scope Number Templates'=>$templates),
            'no_history'

        );

    }

}


?>
