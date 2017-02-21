<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 February 2017 at 11:47:49 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
*/


class Webpage_Type extends DB_Table {


    function Webpage_Type($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;
        $this->id = false;


        $this->table_name    = 'Webpage Type';
        $this->ignore_fields = array('Webpage Type');


        if (!$a2 and !$a3 and is_numeric($a1)) {
            $this->get_data('id', $a1);

        } else {
            $this->get_data($a1, $a2, $a3);

        }


    }


    function get_data($key, $tag, $tag2 = false) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Webpage Type Dimension` WHERE `Webpage Type Key`=%d", $tag
            );
        } else {
            if ($key == 'website_code') {
                $sql = sprintf(
                    "SELECT  * FROM `Webpage Type Dimension` WHERE `Webpage Type Website Key`=%d AND `Webpage Type Code`=%s ", $tag, prepare_mysql($tag2)
                );
            } else {
                return;
            }
        }
        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id   = $this->data['Webpage Type Key'];
            $this->code = $this->data['Webpage Type Code'];
        }


    }


    function get($key, $data = false) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {
            case 'Label':

                switch ($this->data['Webpage Type Code']) {
                    case 'Ops':
                        $label = _('System');
                        break;
                    case 'Info':
                        $label = _('Info');
                        break;
                    case 'Prods':
                        $label = _('Products');
                        break;
                    case 'Prod':
                        $label = _('Product');
                        break;
                    case 'Cats':
                        $label = _('Categories');
                        break;
                    default:
                        $label = $this->data['Webpage Type Code'];
                        break;
                }

                return $label;
                break;

                return $this->data[$key];
            default:
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Webpage Type '.$key, $this->data)) {
                    return $this->data['Webpage Type '.$key];
                }


        }

        return '';
    }


    function update_number_webpages() {

        $online_webpages  = 0;
        $offline_webpages = 0;
        $deletes_webpages = 0;


        $sql = sprintf('select `Webpage State`, count(*) as num from `Page Store Dimension` where `Webpage Type Key`=%d  group by `Webpage State` ', $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['Webpage State'] == 'Online') {
                    $online_webpages = $row['num'];

                } else {
                    $offline_webpages = $row['num'];

                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->update(
            array(
                'Webpage Type Online Webpages'  => $online_webpages,
                'Webpage Type Offline Webpages' => $offline_webpages,
                'Webpage Type Deleted Webpages' => $deletes_webpages,


            ), 'no_history'
        );


    }

}


?>
