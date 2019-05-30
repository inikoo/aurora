<?php
/*
 /*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 September 2017 at 13:25:13 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


include_once('class.DB_Table.php');

class Charge extends DB_Table {


    function __construct($a1, $a2 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Charge';
        $this->ignore_fields = array('Charge Key');

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
            "SELECT * FROM `Charge Dimension` WHERE `Charge Key`=%d", $tag
        );


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Charge Key'];
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
        $fields = array();
        foreach ($data as $key => $value) {
            if (!($key == 'Charge Begin Date' or $key == 'Charge Expiration Date' or $key == 'Charge Terms Metadata' or $key == 'Charge Metadata')) {
                $fields[] = $key;
            }
        }

        $sql = "SELECT `Charge Key` FROM `Charge Dimension` WHERE  TRUE ";
        //print_r($fields);
        foreach ($fields as $field) {
            $sql .= sprintf(
                ' and `%s`=%s', $field, prepare_mysql($data[$field], false)
            );
        }


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Charge Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($this->found) {
            $this->get_data('id', $this->found_key);
        }

        if ($create and !$this->found) {
            $this->create($data);

        }


    }


    function create($data) {


        if ($data['Charge Trigger Key'] == '') {
            $data['Charge Trigger Key'] = 0;
        }

        $data['Charge Metadata']       = Charge::parse_charge_metadata(
            $data['Charge Type'], $data['Charge Description']
        );
        $data['Charge Terms Metadata'] = Deal::parse_term_metadata(
            $data['Charge Terms Type'], $data['Charge Terms Description']
        );


        //print_r($data);

        $keys   = '(';
        $values = 'values(';
        foreach ($data as $key => $value) {
            $keys   .= "`$key`,";
            $values .= prepare_mysql($value).",";
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Charge Dimension` %s %s", $keys, $values
        );


        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();
            $this->get_data('id', $this->id);

            $history_data = array(
                'History Abstract' => sprintf(_('%s charge created'), $this->get('Name')),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


        } else {
            print "Error can not create charge  $sql\n";
            exit;

        }
    }

    public static function parse_charge_metadata($charge_type, $charge_description) {
        $conditions = preg_split('/\s+AND\s+/', $charge_type);
        $metadata   = '';

        foreach ($conditions as $condition) {
            $metadata .= ','.Charge::parse_individual_charge_metadata(
                    $condition, $charge_description
                );
        }
        $metadata = preg_replace('/^\,/', '', $metadata);

        // print "** $charge_type,$charge_description ->$metadata  \n";
        return $metadata;
    }

    public static function parse_individual_charge_metadata($charge_type, $charge_description) {
        // print "$charge_type,$charge_description\n";
        switch ($charge_type) {
            case('Percentage'):
                if (preg_match(
                    '/\d+((\.|\,)\d+)?\%/i', $charge_description, $match
                )) {
                    $number = preg_replace('/\,/', '.', $match[0]);
                    $number = preg_replace('/\%/', '', $number);

                    return 0.01 * (float)$number;
                }
                if (preg_match(
                    '/^(|.*\s+)free(\s+.*|)$/i', $charge_description, $match
                )) {
                    return 1;
                }
                break;
            case('Amount'):
                $charge_description = translate_written_number(
                    $charge_description
                );
                if (preg_match('/\d+(\.\d+)?/i', $charge_description, $match)) {
                    //            print "** $charge_description \n";


                    //preg_match('/\d+\.?[\d]{0,2}/i',$data['Charge Terms Description'],$match);
                    //$total_order=_trim(preg_replace('/[^\d^\.]/','',$match[0]));
                    //preg_match('/\d+\.?[\d]{0,2}/i',$data['Charge Description'],$match);
                    //$amount=_trim(preg_replace('/[^\d^\.]/','',$match[0]));

                    return _trim(preg_replace('/[^\d^.]/', '', $match[0]));
                }
                break;
        }
    }


    function get($key = '') {

        if (!$this->id) {
            return;
        }

        switch ($key) {

            case 'Metadata':
                $store = get_object('Store', $this->data['Charge Store Key']);

                return money($this->data['Charge Metadata'], $store->get('Store Currency Code'));

                break;

            case 'Amount':
                $store = get_object('Store', $this->data['Charge Store Key']);

                return money($this->data['Charge Total Acc '.$key], $store->get('Store Currency Code'));

                break;
            case 'Orders':
            case 'Customers':

                return number($this->data['Charge Total Acc '.$key]);

                break;

            case 'Number History Records':

                return number($this->data['Charge '.$key]);

                break;
            default:
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Charge '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }


                return false;
        }


    }


    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field){
            case 'Charge Metadata':
            case 'Charge Active':

                $old_value=$this->get($field);

                $this->update_field($field, $value, $options);


                if( $this->get('Charge Trigger')=='Order' and $old_value!=$this->get($field)){


                    $account = get_object('Account', $this->db);

                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping', array(
                        'type'        => 'update_basket_orders',
                        'store_key' => $this->get('Store Key'),
                        'editor'      => $this->editor
                    ), $account->get('Account Code'), $this->db
                    );


                }



                break;

        }

        $base_data = $this->base_data();


        if (array_key_exists($field, $base_data)) {

            if ($value != $this->data[$field]) {
                $this->update_field($field, $value, $options);

            }

        }
    }

    function get_field_label($field) {


        switch ($field) {

            case 'Charge Name':
                $label = _('code');
                break;
            case 'Charge Description':
                $label = _('name');
                break;
            case 'Charge Public Description':
                $label = _('description');
                break;
            case 'Charge Metadata':
                $label = _('amount');
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
            "SELECT sum(`Transaction Net Amount`) as amount,count( DISTINCT O.`Order Key`) AS orders,count( DISTINCT `Order Customer Key`) AS customers FROM `Order No Product Transaction Fact` B LEFT  JOIN `Order Dimension` O ON (O.`Order Key`=B.`Order Key`) WHERE `Transaction Type Key`=%d AND `Transaction Type` in ('Charges','Premium') AND `Order State` not in ('InBasket','Cancelled') ",
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
                'Charge Total Acc Orders'    => $orders,
                'Charge Total Acc Customers' => $customers,
                'Charge Total Acc Amount'    => $amount,
            )

        );


    }


}