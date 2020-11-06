<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2018 at 14:17:14 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class Shipper extends DB_Table {


    function __construct($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Shipper';
        $this->ignore_fields = array('Shipper Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);

        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($key, $tag) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Shipper Dimension` WHERE `Shipper Key`=%d", $tag
            );
        } else {
            if ($key == 'code') {
                $sql = sprintf(
                    "SELECT  * FROM `Shipper Dimension` WHERE `Shipper Code`=%s ", prepare_mysql($tag)
                );
            } else {
                if ($key == 'name') {
                    $sql = sprintf(
                        "SELECT  *  FROM `Shipper Dimension` WHERE `Shipper Name`=%s ", prepare_mysql($tag)
                    );
                } else {
                    return;
                }
            }
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id   = $this->data['Shipper Key'];
            $this->code = $this->data['Shipper Code'];
        }


    }

    function find($raw_data, $options) {
        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }
            }
        }

        $this->found     = false;
        $this->found_key = false;

        $create = '';
        $update = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        if ($data['Shipper Code'] == '') {
            $this->error = true;
            $this->msg   = 'Shipper code empty';

            return;
        }

        if ($data['Shipper Name'] == '') {
            $data['Shipper Name'] = $data['Shipper Code'];
        }


        $sql = sprintf(
            "SELECT `Shipper Key` FROM `Shipper Dimension` WHERE `Shipper Code`=%s  ", prepare_mysql($data['Shipper Code'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Shipper Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Shipper Code';

                return;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($create and !$this->found) {
            $this->create($data);

            return;
        }


    }

    function create($data) {


        global $account;
        $this->new = false;
        $base_data = $this->base_data();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }

        $keys   = '(';
        $values = 'values(';
        foreach ($base_data as $key => $value) {
            $keys .= "`$key`,";


            $values .= prepare_mysql($value).",";

        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Shipper Dimension` %s %s", $keys, $values
        );

        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Shipper added");
            $this->get_data('id', $this->id);
            $this->new = true;

            $sql = sprintf(
                "INSERT INTO `Shipper Data` VALUES('Shipper Key')", $this->id
            );

            $this->db->exec($sql);


            $history_data = array(
                'History Abstract' => _('Shipping company inputted'),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


            $history_data = array(
                'History Abstract' => sprintf(
                    _('Shipping company (%s) created'), $this->get('Name')
                ),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $account->add_subject_history(
                $history_data, true, 'No', 'Changes', $account->get_object_name(), $account->id
            );


            return;
        } else {
            $this->msg = "Error can not create shipper";
            print $sql;
            exit;
        }
    }


    function get($key, $data = false) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {

            default:


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Shipper '.$key, $this->data)) {
                    return $this->data['Shipper '.$key];
                }


        }

        return '';
    }


    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if ($this->deleted) {
            return;
        }

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

            case 'Shipper Code':
                $label = _('code');
                break;
            case 'Shipper Name':
                $label = _('name');
                break;
            case 'Shipper Telephone':
                $label = _('telephone');
                break;
            case 'Shipper Tracking URL':
                $label = _('tracking link');
                break;

            default:


                $label = $field;

        }

        return $label;

    }

    function suspend() {

        $this->update(array('Shipper Status' => 'Suspended'));


        $sql  = 'select `Store Key` from `Store Dimension` ';
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(
            array()
        )) {
            while ($row = $stmt->fetch()) {
                $store         = get_object('Store', $row['Store Key']);
                $store->editor = $this->editor;

                if ($store->settings('data_entry_picking_aid_default_shipper') == $this->id) {


                    $store->update(
                        array(
                            'data entry picking aid default shipper' => 0
                        )
                    );
                }


            }
        } else {
            print_r($error_info = $stmt > errorInfo());
            exit();
        }


    }

    function activate() {

        $this->update(array('Shipper Status' => 'Active'));

    }

    function delete() {

        $this->update_shipper_usage();

        if ($this->data['Shipper Consignments'] > 0) {

            $this->error = true;
            $this->msg   = _("Can;t delete shipper if has been used before");

            return;

        }


    }

    function update_shipper_usage() {

        $consignments = 0;

        $last_consignment = '';
        $parcels          = 0;
        $disatched_weight = 0;

        $sql = 'select  count(*) as num from `Delivery Note Dimension` where   `Delivery Note Shipper Key`=?';


        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(
            array(
                $this->id
            )
        )) {
            if ($row = $stmt->fetch()) {
                $consignments = $row['num'];

            }
        } else {
            print_r($error_info = $stmt->errorInfo());
            exit();
        }


        $sql = 'select  sum(`Delivery Note Weight`) as weight,sum(`Delivery Note Number Parcels`) as parcels, max(`Delivery Note Date Dispatched`)  last_date from `Delivery Note Dimension` where `Delivery Note State`="Dispatched" and `Delivery Note Shipper Key`=?';


        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(
            array(
                $this->id
            )
        )) {
            if ($row = $stmt->fetch()) {
                $last_consignment = $row['last_date'];
                $parcels          = $row['parcels'];
                $disatched_weight = $row['weight'];

            }
        } else {
            print_r($error_info = $stmt->errorInfo());
            exit();
        }

        $this->fast_update(
            array(
                'Shipper Consignments'      => $consignments,
                'Shipper Number Parcels'    => $parcels,
                'Shipper Dispatched Weight' => $disatched_weight,
                'Shipper Last Consignment'  => $last_consignment
            )
        );


    }



    function get_services(){



        switch ($this->get('Shipper Code')){
            case 'APC':
                return [
                    '_AUTO_'=>'Automatic',
                    'ND16'=>'Next Day Service by 16:00 (ND16)',
                    'ND12'=>'Next Day Service by 12:00 (ND12)',
                    'ND10'=>'Next Day Service by 10:00 (ND10)',
                    'NC16'=>'Non Conveyable (NC16)',
                    'XS16'=>'Excess Parcel (XS16)',

                    'TDAY'=>'2-5 DAY Economy (TDAY)',
                    'ROAD'=>'BT Economy Service (ROAD)',
                    'LQ16'=>'Dangerous Goods (LQ16)',
                    'LW16'=>'Lightweight Parcel',
                    'CP16'=>'Courier pack (CP16)',
                    'MP16'=>'Mail pack (MP16)',


                ];
            default:
                return [];


        }


    }

}


