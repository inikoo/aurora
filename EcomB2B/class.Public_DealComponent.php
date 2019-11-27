<?php
/*

 About:
 Created:  24 August 2017 at 00:55:19 GMT+8, Kuala Lumpur, Malaysia
 Author: Raul Perusquia <rulovico@gmail.com>


 Copyright (c) 2017, Inikoo

 Version 3.0
*/


class Public_DealComponent  {
    /**
     * @var \PDO
     */
    public $db;

    /**
     * @var array
     */
    public $data;
    /**
     * @var integer
     */
    public $id;

    function __construct($a1, $a2 = false,$_db = false) {


        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }


        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } else {
            $this->get_data($a1, $a2);
        }

    }

    function get_data($tipo, $tag) {


        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Deal Component Dimension` WHERE `Deal Component Key`=%d", $tag
            );
        }else{
            return false;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id             = $this->data['Deal Component Key'];
        }
        return true;


    }

    function get($key = '') {


        switch ($key) {



            case('Description'):
            case('Deal Description'):
                return $this->data['Deal Component Allowance Label'];
                break;
        }

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (isset($this->data['Deal Component '.$key])) {
            return $this->data['Deal Component '.$key];
        }


        return false;
    }



  
}

