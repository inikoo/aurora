<?php

/*

 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/

/**
 * Class PageDeleted
 */
class PageDeleted {

    /**
     * @var $db PDO
     */
    public $db;
    /**
     * @var int
     */
    public $id = 0;
    /**
     * @var array
     */
    public $data = array();
    /**
     * @var bool
     */
    public $new = false;
    /**
     * @var string
     */
    public $msg = '';
    var $deleted = true;
    /**
     * @var string
     */
    protected $table_name;
    /**
     * @var array
     */
    protected $ignore_fields = array();

    function __construct($a1 = false, $a2 = false) {

        global $db;
        $this->db         = $db;
        $this->table_name = 'Page Store Deleted';


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


        $sql = sprintf(
            "INSERT INTO `Page Store Deleted Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($data)).'`', join(',', array_fill(0, count($data), '?'))
        );


        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($data as $key => $value) {

            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {

            $this->id  = $this->db->lastInsertId();
            $this->msg = "Page Deleted Created";
            $this->get_data('id', $this->id);
            $this->new = true;


            return;
        } else {
            $this->msg = "Error can not create deleted page";
        }
    }


    function get($key) {
        switch ($key) {

            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                } else {
                    return '';
                }
        }


    }


}


