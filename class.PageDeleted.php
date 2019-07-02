<?php

/*

 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/


class PageDeleted {

    var $deleted=true;

    function __construct($a1 = false, $a2 = false) {

        global $db;
        $this->db = $db;
        $this->table_name    = 'Page Store Deleted';



        $this->ignore_fields = array('Page Store Deleted Key');
        if ($a1) {

            if ($a2) {
                $this->get_data($a1, $a2);

            } else {

                $this->get_data('page_key', $a1);
            }
        }

    }


    function get_data($key, $tag) {


        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Page Store Deleted Dimension` WHERE `Page Store Deleted Key`=%d", $tag
            );
        } elseif ($key == 'page_key') {
            $sql = sprintf(
                "SELECT * FROM `Page Store Deleted Dimension` WHERE `Page Key`=%d", $tag
            );
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Page Store Deleted Key'];
        }


    }


    function create($data) {
        $this->new = false;
        $keys      = '(';
        $values    = 'values(';
        foreach ($data as $key => $value) {
            $keys .= "`$key`,";
            if (preg_match('/Page Title|Page Description/i', $key)) {
                $values .= "'".addslashes($value)."',";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Page Store Deleted Dimension` %s %s", $keys, $values
        );


        if ($this->db->exec($sql)) {
            $this->id  =$this->db->lastInsertId();
            $this->msg = "Page Deleted Created";
            $this->get_data('id', $this->id);
            $this->new = true;


            return;
        } else {
            $this->msg = "Error can not create deleted page";
        }
    }


    function get($key, $data = false) {
        switch ($key) {

            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                } else {
                    return '';
                }
        }

        return '';
    }


    function get_snapshot_date() {

        if ($this->data['Page Snapshot Last Update'] != '') {
            return strftime(
                "%a %e %b %Y %H:%M %Z", strtotime($this->data['Page Snapshot Last Update'].' UTC')
            );
        }
    }



}


