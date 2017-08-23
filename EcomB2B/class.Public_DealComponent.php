<?php
/*

 About:
 Created:  24 August 2017 at 00:55:19 GMT+8, Kuala Lumpur, Malaysia
 Author: Raul Perusquia <rulovico@gmail.com>


 Copyright (c) 2017, Inikoo

 Version 3.0
*/


class Public_DealComponent  {


    function Public_DealComponent($a1, $a2 = false) {


        global $db;
        $this->db = $db;

        $this->table_name    = 'Deal Component';
        $this->ignore_fields = array('Deal Component Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } else {
            if (($a1 == 'new' or $a1 == 'create') and is_array($a2)) {
                $this->find($a2, 'create');

            } elseif (preg_match('/find/i', $a1)) {
                $this->find($a2, $a1);
            } else {
                $this->get_data($a1, $a2);
            }
        }

    }

    function get_data($tipo, $tag) {


        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Deal Component Dimension` WHERE `Deal Component Key`=%d", $tag
            );
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id             = $this->data['Deal Component Key'];
        }


    }

    function get($key = '', $arg = false) {


        switch ($key) {



            case('Description'):
            case('Deal Description'):
                return $this->data['Deal Component Terms Description'].' &rArr; '.$this->data['Deal Component Allowance Description'];
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

    function get_xhtml_status() {
        switch ($this->data['Deal Component Status']) {
            case('Active'):
                return _("Active");
                break;
            case('Finish'):
                return _("Finished");
                break;
            case('Waiting'):
                return _("Waiting");
                break;
            case('Suspended'):
                return _("Suspended");
                break;


        }

    }

  
}

?>
