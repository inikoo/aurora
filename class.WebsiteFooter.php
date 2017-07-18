<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 March 2017 at 13:49:24 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'class.DB_Table.php';
include_once 'trait.ImageSubject.php';


class WebsiteFooter extends DB_Table {

    use ImageSubject;

    function WebsiteFooter($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Website Footer';
        $this->ignore_fields = array('Website Footer Key');

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
            $sql = sprintf("SELECT * FROM `Website Footer Dimension` WHERE `Website Footer Key`=%d", $tag);
        } elseif ($key == 'website_code') {
            $sql = sprintf("SELECT  * FROM `Website Footer Dimension` WHERE `Website Footer Website Key`=%d AND `Website Footer Code`=%s ", $tag, prepare_mysql($tag2));
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Website Footer Key'];


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


        if ($data['Website Footer Code'] == '') {
            $this->error = true;
            $this->msg   = 'Website Footer code empty';

            return;
        }


        $sql = sprintf(
            "SELECT `Website Footer Key` FROM `Website Footer Dimension` WHERE `Website Footer Website Key`=%d AND  `Website Footer Code`=%s", $data['Website Footer Website Key'],
            prepare_mysql($data['Website Footer Code'])
        );

        // print "$sql\n";

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Website Footer Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Website Footer Code';
                $this->msg              = sprintf(_('Another footer has same code %s'), $data['Website Footer Code']);

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
            "INSERT INTO `Website Footer Dimension` %s %s", $keys, $values
        );
        //print "=======  $sql\"";
        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Website footer created");
            $this->get_data('id', $this->id);
            $this->new = true;


            return;
        } else {
            $this->msg = "Error can not create website Footer";
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

                if (array_key_exists('Website Footer '.$key, $this->data)) {
                    return $this->data['Website Footer '.$key];
                }


        }

        return '';
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if ($this->deleted) {
            return;
        }

        switch ($field) {

            case 'Website Footer Data':




                $this->update_field($field,json_encode( $value), $options);

                $updated=$this->updated;

                $this->update_field('Website Footer Last Updated',gmdate('Y-m-d H:i:s'), 'no_history');

                $this->updated=$updated;

                break;
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

            case 'Website Footer Code':
                $label = _('code');
                break;


            default:


                $label = $field;

        }

        return $label;

    }


    function reset(){
        require_once 'conf/footer_data.php';
        $this->update(
            array(
                'Website Footer Data' => get_default_footer_data(1)
            ), 'no_history'
        );


    }

}


?>
