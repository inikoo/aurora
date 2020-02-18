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

class Public_Image extends DBW_Table {

    var $id = false;
    var $im = "";

    var $msg = '';
    var $new = false;
    var $deleted = false;
    var $found_key = 0;
    public $fork = false;


    public $editor = array(
        'Author Name'  => false,
        'Author Alias' => false,
        'Author Key'   => 0,
        'User Key'     => 0,
        'Date'         => false
    );


    function __construct($a1, $a2 = false, $a3 = false, $_db = false) {

        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }

        $this->table_name    = 'Image';
        $this->ignore_fields = array('Image Key');


        $this->tmp_path       = 'server_files/tmp/';
        $this->found          = false;
        $this->error          = false;
        $this->thumbnail_size = array(
            25,
            20
        );
        $this->small_size     = array(
            320,
            280
        );
        $this->large_size     = array(
            800,
            600
        );
        if (is_numeric($a1) and !$a2) {

            $this->get_data('id', $a1);
        } else {
            if (($a1 == 'new' or $a1 == 'create') and is_string($a2)) {
                $this->find($a2, 'create');
            } elseif ($a1 == 'find') {
                $this->find($a2, $a3);

            } else {
                $this->get_data($a1, $a2);
            }
        }
    }


    function get_data($tipo = 'id', $id) {
        if ($tipo == 'id') {


            $sql = sprintf(
                "SELECT * FROM `Image Dimension` WHERE `Image Key`=%d ", $id
            );

            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id = $this->data['Image Key'];

            }
        } elseif ($tipo == 'image_bridge_key') {
            $sql = sprintf(
                "SELECT * FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d ", $id
            );

            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id = $this->data['Image Subject Image Key'];
                if ($this->id) {
                    $sql = sprintf(
                        "SELECT * FROM `Image Dimension` WHERE `Image Key`=%d ", $this->id
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





    function get_object_name() {

        return 'Image';
    }




    function get_subjects() {
        $subjects = array();
        $sql      = sprintf(
            'SELECT `Image Subject Object`,`Image Subject Is Principal`,`Image Subject Object Key` FROM `Image Subject Bridge` WHERE `Image Subject Image Key`=%d', $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $subjects[] = array(
                    'Subject Type' => $row['Image Subject Object'],
                    'Subject Key'  => $row['Image Subject Object Key'],
                    'Is Principal' => $row['Image Subject Is Principal']
                );

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql";
            exit;
        }


        return $subjects;
    }


    function get_field_label($field) {

        switch ($field) {

            case 'Image Caption':
                $label = _('caption');
                break;

            case 'Image Public':
                if ($this->get('Subject') == 'Staff') {
                    $label = _('Employee can see file');
                } elseif ($this->get('Subject') == 'Product') {
                    $label = _('Customers can see');
                } else {
                    $label = _('Public');
                }
                break;
            case 'Image File':
                $label = _('File');
                break;
            case 'Image File Original Name':
                $label = _('File name');
                break;
            case 'Image File Size':
                $label = _('File size');
                break;
            case 'Image Preview':
                $label = _('Preview');
                break;


            default:
                $label = $field;
                break;
        }

        return $label;
    }

    function get($key) {


        if (!$this->id) {
            return;
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


    }


    

}
