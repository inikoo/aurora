<?php
/*
 /*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2017 at 15:02:39 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


include_once('class.DB_Table.php');

class Shipping_Zone extends DB_Table {


    function __construct($a1, $a2 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Shipping Zone';
        $this->ignore_fields = array('Shipping Zone Key');

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


        $sql = sprintf(
            "SELECT * FROM `Shipping Zone Dimension` WHERE `Shipping Zone Key`=%d", $tag
        );


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Shipping Zone Key'];
        }


    }

    function find($raw_data, $options) {

        if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {

                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
        }

        $this->candidate = array();
        $this->found     = false;
        $this->found_key = 0;
        $create          = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {

            if (array_key_exists($key, $data)) {
                $data[$key] = $value;
            }

        }


        $sql = sprintf(
            "SELECT `Shipping Zone Key` FROM `Shipping Zone Dimension` WHERE  `Shipping Zone Store Key`=%d and `Shipping Zone Code`=%s   ",
            $data['Shipping Zone Store Key'],
            prepare_mysql($data['Shipping Zone Code'])

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found            = true;
                $this->found_key        = $row['Shipping Zone Key'];
                $this->duplicated_field = 'Shipping Zone Code';
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        if (!$this->found) {
            $sql = sprintf(
                "SELECT `Shipping Zone Key` FROM `Shipping Zone Dimension` WHERE  `Shipping Zone Store Key`=%d and `Shipping Zone Name`=%s   ",
                $data['Shipping Zone Store Key'],
                prepare_mysql($data['Shipping Zone Name'])

            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $this->found            = true;
                    $this->found_key        = $row['Shipping Zone Key'];
                    $this->duplicated_field = 'Shipping Zone Name';
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

        }


        if ($this->found) {
            $this->get_data('id', $this->found_key);
        }

        if ($create and !$this->found) {
            $this->create($data);

        }


    }


    function create($data) {


        $data['Shipping Zone Creation Date'] = gmdate('Y-m-d H:i:s');

        $keys   = '(';
        $values = 'values(';
        foreach ($data as $key => $value) {
            $keys   .= "`$key`,";
            $values .= prepare_mysql($value).",";
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Shipping Zone Dimension` %s %s", $keys, $values
        );



        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();
            $this->new=true;
            $this->get_data('id', $this->id);

            $history_data = array(
                'History Abstract' => sprintf(_('%s shipping zone created'), $this->get('Name')),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


        } else {
            print "Error can not create shipping zone  $sql\n";
            exit;

        }
    }


    function get($key = '') {


        if (!$this->id) {
            return;
        }

        switch ($key) {
            case 'Amount':
                $store = get_object('Store', $this->data['Shipping Zone Store Key']);

                return money($this->data['Shipping Zone Total Acc '.$key], $store->get('Store Currency Code'));

                break;
            case 'Orders':
            case 'Customers':

                return number($this->data['Shipping Zone Total Acc '.$key]);

                break;

            case 'Number History Records':

                return number($this->data['Shipping Zone '.$key]);

                break;
            default:
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Shipping Zone '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }


                return false;
        }


    }


    function get_field_label($field) {


        switch ($field) {
            case 'Shipping Zone Code':
                $label = _('code');
                break;
            case 'Shipping Zone Name':
                $label = _('name');
                break;
            case 'Shipping Zone Description':
                $label = _('description');
                break;


            default:


                $label = $field;

        }

        return $label;

    }


    function update_charge_usage() {

        $orders    = 0;
        $customers = 0;
        $amount    = 0;

        $sql = sprintf(
            "SELECT sum(`Transaction Net Amount`) as amount,count( DISTINCT O.`Order Key`) AS orders,count( DISTINCT `Order Customer Key`) AS customers FROM `Order No Product Transaction Fact` B LEFT  JOIN `Order Dimension` O ON (O.`Order Key`=B.`Order Key`) WHERE `Transaction Type Key`=%d AND `Transaction Type`='Shipping Zones' AND `Order State` not in ('InBasket','Cancelled') ",
            $this->id

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $orders    = $row['orders'];
                $customers = $row['customers'];
                $amount    = $row['amount'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->fast_update(
            array(
                'Shipping Zone Total Acc Orders'    => $orders,
                'Shipping Zone Total Acc Customers' => $customers,
                'Shipping Zone Total Acc Amount'    => $amount,
            )

        );


    }


}