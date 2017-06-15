<?php

/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 9 May 2017 at 09:21:33 GMT-5, CdMx Mexico  

  Copyright (c) 2017, Inikoo

  Version 2.0
*/


class Public_Store {


    function Public_Store($a1, $a2 = false, $a3 = false, $_db = false) {

        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }
        $this->id            = false;
        $this->table_name    = 'Store';
        $this->ignore_fields = array('Store Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } else {
            $this->get_data($a1, $a2);
        }

    }


    function get_data($tipo, $tag) {


        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Store Dimension` WHERE `Store Key`=%d", $tag
            );
        } elseif ($tipo == 'code') {
            $sql = sprintf(
                "SELECT * FROM `Store Dimension` WHERE `Store Code`=%s", prepare_mysql($tag)
            );
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {

            $this->id   = $this->data['Store Key'];
            $this->code = $this->data['Store Code'];
        }


    }

    function get_categories($type = 'families', $output = 'data') {

        $categories = array();


        switch ($type) {
            case 'departments':
                $sql = sprintf(
                    'SELECT  `Webpage Code`,`Webpage Name` FROM  `Category Dimension` C   LEFT JOIN `Page Store Dimension` P ON (P.`Webpage Scope Key`=C.`Category Key` AND `Webpage Scope`="Category Categories" ) WHERE   C.`Category Parent Key`=%d ',

                    $this->get('Store Department Category Key')
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        switch ($output) {
                            case 'menu':
                                $categories[] = array(
                                    'url'   => $row['Webpage Code'],
                                    'label' => $row['Webpage Name'],
                                    'new'   => false

                                );
                                break;
                        }

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                return $categories;

                break;
        }


    }

    function get($key = '') {


        if (!$this->id) {
            return '';
        }


        switch ($key) {


            case 'Telephone':
            case 'Email':
            case 'Address':
                return $this->data['Store '.$key];
                break;


            case 'Store Currency Code':
            case 'Store Department Category Key':
            case 'Store Family Category Key':
            case 'Store Timezone':
            case 'Store Key':

            return $this->data[$key];
                break;

        }


    }


}


?>
