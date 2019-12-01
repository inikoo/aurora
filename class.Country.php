<?php

/*
 File: Country.php

 This file contains the Country Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/


class Country {
    /**
     * @var \PDO
     */
    public $db;
    /**
     * @var  array
     */
    var $data = array();
    /**
     * @var  integer|bool
     */
    var $id = false;

    function __construct($arg1 = false, $arg2 = false, $_db = false) {


        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }


        if ($arg1 == 'id' and is_numeric($arg2)) {
            $this->get_data('id', $arg2);

            return;
        } elseif ($arg1 == 'code') {
            $this->get_data('code', $arg2);

            return;
        } elseif ($arg1 == 'find') {
            $this->get_data('find', $arg2);

            return;
        } elseif ($arg1 == '2alpha') {
            $this->get_data('2alpha', $arg2);

            return;
        } elseif ($arg1 == 'name') {
            $this->get_data('name', $arg2);

            return;
        } elseif (is_numeric($arg1) and !$arg2) {
            $this->get_data('id', $arg1);
        }


    }


    function get_data($key, $id) {


        if ($key == 'find') {

            if ($id == '') {
                $this->get_data('code', 'UNK');
            }

            if (is_numeric($key)) {


                $sql = sprintf(
                    "SELECT * FROM kbase.`Country Dimension` C WHERE `Country Key`=%d", $id
                );
                if ($this->data = $this->db->query($sql)->fetch()) {
                    $this->id = $this->data['Country Key'];

                    return;

                }
            }


            if (strlen($id) == 3) {
                $sql = sprintf(
                    "SELECT * FROM kbase.`Country Dimension` C WHERE `Country Code`=%s", prepare_mysql($id)
                );
                if ($this->data = $this->db->query($sql)->fetch()) {
                    $this->id = $this->data['Country Key'];

                    return;
                }
            } elseif (strlen($id) == 2) {
                $sql = sprintf(
                    "SELECT * FROM kbase.`Country Dimension` C WHERE `Country 2 Alpha Code`=%s", prepare_mysql($id)
                );
                if ($this->data = $this->db->query($sql)->fetch()) {
                    $this->id = $this->data['Country Key'];

                    return;
                }
            }


            $sql = sprintf(
                "SELECT *  FROM kbase.`Country Dimension`WHERE  `Country Name`=%s  ", prepare_mysql($id)
            );
            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id = $this->data['Country Key'];

                return;
            }

            $sql = sprintf(
                "SELECT `Country Alias Code`  FROM kbase.`Country Alias Dimension` WHERE `Country Alias`=%s  ", prepare_mysql($id)

            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $this->get_data('code', $row['Country Alias Code']);

                    return;
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }

            $this->get_data('code', 'UNK');


        } elseif ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM kbase.`Country Dimension` C WHERE `Country Key`=%d", $id
            );
            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id = $this->data['Country Key'];
            }

            return;
        } elseif ($key == '2alpha') {
            $sql = sprintf(
                "SELECT * FROM kbase.`Country Dimension` C WHERE `Country 2 Alpha Code`=%s", prepare_mysql($id)
            );
            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id = $this->data['Country Key'];
            }

            return;
        } elseif ($key == 'code') {
            $sql = sprintf(
                "SELECT * FROM kbase.`Country Dimension` C WHERE `Country Code`=%s", prepare_mysql($id)
            );
            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id = $this->data['Country Key'];
            }


            return;
        } elseif ($key == 'name') {
            $sql = sprintf(
                "SELECT * FROM kbase.`Country Dimension` C WHERE `Country Name`=%s", prepare_mysql($id)
            );
            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id = $this->data['Country Key'];
            }


        }


    }

    function get($key) {


        switch ($key) {
            case 'Flag':
                return '<img src="/art/flags/'.strtolower($this->data['Country 2 Alpha Code']).'.gif"/>';
                break;
            case 'Population':
                return number($this->data['Country Population']);
                break;
            case 'GNP':
                return money($this->data['Country GNP'], 'USD');
                break;
            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                }
        }

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }


        return false;

    }

    function get_alias() {
        $alias = array();
        $sql   = "select `Country Alias` from kbase.`Country Alias Dimension` where `Country Alias Code`=? and `Country Alias Type` in ('Misspelling','Short Name','Alias') ";
        $stmt  = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->data['Country Code']
            )
        );
        while ($row = $stmt->fetch()) {
            $alias[] = $row['Country Alias'];
        }

        return $alias;

    }


}



