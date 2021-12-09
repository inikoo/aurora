<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  31 March 2020  00:02::02  +0800 , Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
*/



class Public_Top_Up extends DBW_Table {


    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;
        $this->id = false;


        $this->table_name = 'Top Up';

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        if ($arg1 == 'new') {
            $this->create($arg2, $arg3);

            return;
        }

        $this->get_data($arg1, $arg2);


    }


    function get_data($key, $id) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Top Up Dimension` WHERE `Top Up Key`=%d", $id
            );

        } else {

            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id       = $this->data['Top Up Key'];
            $this->metadata = json_decode($this->data['Top Up Metadata'], true);

        }


    }

    function create($raw_data) {



        $this->data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }
        $this->editor = $raw_data['editor'];

        $this->data['Top Up Date'] = gmdate('Y-m-d H:i:s');
        $this->data['Top Up Metadata']      = '{}';


        $sql = sprintf(
            "INSERT INTO `Top Up Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($this->data)).'`', join(',', array_fill(0, count($this->data), '?'))
        );

        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($this->data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }

        if ($stmt->execute()) {
            $this->id = $this->db->lastInsertId();
            $this->get_data('id', $this->id);
            $this->new = true;


        } else {

            $this->error = true;
            $this->msg   = 'Unknown error';
        }




    }

    function get($key, $arg1 = false) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {

            case 'Top Up Store Key':
                return $this->data['Top Up Store key'];

            case('Date'):
                if ($this->data['Top Up '.$key] == '') {
                    return '';
                }

                return '<span title="'.strftime(
                        "%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Top Up '.$key]." +00:00")
                    ).'">'.strftime(
                        "%a %e %b %Y", strtotime($this->data['Top Up '.$key]." +00:00")
                    ).'</span>';



            default:

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Top Up '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }

                return '';

        }

    }

    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }





}

