<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 6 December 2015 at 14:46:31 GMT Sheffield UK

 Version 2.0
*/


class Overtime extends DB_Table {


    function Overtime($arg1 = false, $arg2 = false, $arg3 = false) {
        global $db;

        $this->db            = $db;
        $this->table_name    = 'Overtime';
        $this->ignore_fields = array('Overtime Key');

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        if (preg_match('/^find/i', $arg1)) {

            $this->find($arg2, $arg3);

            return;
        }

        if (preg_match('/^(create|new)/i', $arg1)) {
            $this->create($arg2);

            return;
        }

        $this->get_data($arg1, $arg2);

        return;

    }


    function get_data($tipo, $tag) {

        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Overtime Dimension` WHERE `Overtime Key`=%d", $tag
            );
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Overtime Key'];
        }


    }

    function create($data) {

        $this->duplicated = false;
        $this->new        = false;

        $this->editor = $data['editor'];
        unset($data['editor']);
        $this->data = $data;

        $keys   = '';
        $values = '';

        foreach ($this->data as $key => $value) {
            $keys .= ",`".$key."`";
            $values .= ','.prepare_mysql($value, false);
        }
        $values = preg_replace('/^,/', '', $values);
        $keys   = preg_replace('/^,/', '', $keys);

        $sql = "insert into `Overtime Dimension` ($keys) values ($values)";

        //print  $sql;
        if ($this->db->exec($sql)) {

            $this->id  = $this->db->lastInsertId();
            $this->new = true;

            $this->get_data('id', $this->id);
        } else {
            $this->error = true;


            $error_info = $this->db->errorInfo();
            if ($error_info[0] == 23000) {
                $this->duplicated = true;
                $this->msg        = _('Record already exists');
            } else {
                $this->msg = 'Can not create Overtime. '.$error_info[2];
            }

        }

    }

    function get($key = '') {


        switch ($key) {

            case 'Time Hours':
            case 'Accrued Time Hours':
                $hours = $this->data['Overtime '.preg_replace(
                        '/Hours/', 'Time', $key
                    )] / 3600;

                return sprintf(
                    "%s %s", number($hours, 3), ngettext("h", "hrs", $hours)
                );

                break;
            case 'Time':
            case 'Accrued Time':
                include_once 'utils/natural_language.php';

                return seconds_to_string(
                    $this->data['Overtime '.$key], 'minutes', true
                );


                break;
            case 'IsoDate':
                return $this->data['Overtime Date'] != '' ? date(
                    "Y-m-d", strtotime($this->data['Overtime Date'])
                ) : '';


                break;

            case 'Start Date':
            case 'End Date':

                return $this->data['Overtime '.$key] != '' ? strftime(
                    "%a %e %b %Y", strtotime($this->data['Overtime '.$key])
                ) : '';

                break;


            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                }
                $_key = ucfirst($key);
                if (isset($this->data[$_key])) {
                    return $this->data[$_key];
                }

                return false;

        }


    }

    function get_field_label($field) {

        switch ($field) {


            case 'Overtime Start Date':
                $label = _('Start');
                break;
            case 'Overtime End Date':
                $label = _('End');
                break;
            default:
                $label = $field;

        }

        return $label;

    }


}
