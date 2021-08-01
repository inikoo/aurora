<?php

/*
  File: class.Image.php

  This file contains the Image Class

  About:
  Author: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/

include_once 'class.DBW_Table.php';

class Public_Image extends DBW_Table
{




    public bool $fork = false;


    function __construct($a1, $a2 = false, $a3 = false, $_db = false)
    {
        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }

        $this->table_name    = 'Image';
        $this->ignore_fields = array('Image Key');



        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($tipo, $id)
    {
        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Image Dimension` WHERE `Image Key`=%d ",
                $id
            );

            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id = $this->data['Image Key'];
            }
        } elseif ($tipo == 'image_bridge_key') {
            $sql = sprintf(
                "SELECT * FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d ",
                $id
            );

            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id = $this->data['Image Subject Image Key'];
                if ($this->id) {
                    $sql = sprintf(
                        "SELECT * FROM `Image Dimension` WHERE `Image Key`=%d ",
                        $this->id
                    );

                    if ($row = $this->db->query($sql)->fetch()) {
                        foreach ($row as $key => $value) {
                            $this->data[$key] = $value;
                        }
                    } else {
                        $this->id = 0;
                    }
                }
            }
        }
    }


    function get_object_name(): string
    {
        return 'Image';
    }


    function get_subjects(): array
    {
        $subjects = array();
        $sql      = sprintf(
            'SELECT `Image Subject Object`,`Image Subject Is Principal`,`Image Subject Object Key` FROM `Image Subject Bridge` WHERE `Image Subject Image Key`=%d',
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $subjects[] = array(
                    'Subject Type' => $row['Image Subject Object'],
                    'Subject Key'  => $row['Image Subject Object Key'],
                    'Is Principal' => $row['Image Subject Is Principal']
                );
            }
        }


        return $subjects;
    }




    function get($key)
    {
        if (!$this->id) {
            return '';
        }

        switch ($key) {
            default:
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Image '.$key, $this->data)) {
                    return $this->data['Image '.$key];
                }
        }
        return '';
    }


}
