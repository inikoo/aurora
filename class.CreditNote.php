<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28-05-2019 16:28:15 BST, Sheffield, UK

 Copyright (c) 2019, Inikoo

 Version 3.0
*/


include_once 'class.DB_Table.php';


class CreditNote extends DB_Table {

    var $update_stock = true;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {


        global $db;
        $this->db = $db;

        $this->table_name    = 'Credit Note';
        $this->ignore_fields = array('Credit Note Key');

        if (!$arg1 and !$arg2) {
            $this->error = true;
            $this->msg   = 'No data provided';

            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        if ($arg1 == 'create') {
            $this->create($arg2, $arg3);

            return;
        }
        $this->get_data($arg1, $arg2);
    }


    function get_data($tipo, $tag) {
        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Credit Note Dimension` WHERE  `Credit Note Key`=%d", $tag
            );
        } elseif ($tipo == 'public_id') {
            $sql = sprintf(
                "SELECT * FROM `Credit Note Dimension` WHERE  `Credit Note ID`=%s", prepare_mysql($tag)
            );

        } else {

            return;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Credit Note Key'];

            if ($this->data['Credit Note Metadata'] == '') {
                $this->medatata = array();
            } else {
                $this->medatata = json_decode($this->data['Credit Note Metadata'], true);
            }

            if ($this->data['Credit Note Items'] == '') {
                $this->medatata = array();
            } else {
                $this->medatata = json_decode($this->data['Credit Note Items'], true);
            }


        }


    }

    protected function create($dn_data, $order) {


        $base_data = $this->base_data();

        $this->editor = $dn_data['editor'];
        unset($dn_data['editor']);

        foreach ($dn_data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }


        $sql = sprintf(
            "INSERT INTO `Credit Note Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($base_data)).'`', join(',', array_fill(0, count($base_data), '?'))
        );


        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($base_data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {

            $this->id = $this->db->lastInsertId();


            $sql = sprintf(
                'UPDATE `Credit Note Dimension`  SET  `Credit Note Metadata V2`=JSON_SET(`Credit Note Metadata V2`,"$.ver",2)
                             WHERE `Credit Note Key`=%d ', $this->id
            );


            $this->db->exec($sql);

            $this->get_data('id', $this->id);




            $history_data = array(
                'History Abstract' => _('Credit Note created'),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);


            $this->update_totals();


        } else {
            exit ("$sql \n Error can not create dn header");
        }

        return $this;


    }


    function get($key) {


        if (!$this->id) {
            return '';
        }

        switch ($key) {


            case('Date'):

                return strftime("%e %b %y", strtotime($this->data['Credit Note Date'].' +0:00'));



            case('Credit Note Net Amount'):
            case('Credit Note Tax Amount'):
                case('Credit Note Total Amount'):

                return money($this->data['Credit Note '.$key]);
                break;



        }


        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (array_key_exists('Credit Note '.$key, $this->data)) {
            return $this->data[$this->table_name.' '.$key];
        }


        return false;
    }


    function update_field_switcher($field, $value, $options = '', $metadata = '') {

        switch ($field) {



            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                }
        }
    }



    function get_field_label($field) {

        switch ($field) {




            default:
                $label = $field;

        }

        return $label;

    }


}



