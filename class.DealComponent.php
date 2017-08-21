<?php
/*

 About:
Refurbished: 8 August 2017 at 15:16:40 CEST, Tranava , Slovakia
 Author: Raul Perusquia <rulovico@gmail.com>


 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';
include_once 'class.Deal.php';

class DealComponent extends DB_Table {


    function DealComponent($a1, $a2 = false) {


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
            $this->calculate_deal = create_function('$transaction_data,$customer_id,$date', $this->get('Deal Component'));
            $this->id             = $this->data['Deal Component Key'];
        }


    }

    function get($key = '', $arg = false) {


        switch ($key) {

            case('Icon'):

                if ($arg == '' or !is_object($arg)) {
                    $store = get_object('Store', $this->data['Deal Component Store Key']);
                } else {
                    $store = $arg;
                }

                if ($this->data['Deal Component Campaign Key'] == $store->get('Store Order Recursion Campaign Key')) {
                    return '<i class="fa fa-tag gold-text "  aria-hidden="true"></i>';
                } elseif ($this->data['Deal Component Campaign Key'] == $store->get('Store Bulk Discounts Campaign Key')) {
                    return '<i class="fa fa-tag  " style="color:#31B96E" aria-hidden="true"></i>';
                }else{
                    return '<i class="fa fa-tag  "  aria-hidden="true"></i>';
                }




                break;

            case('Description'):
            case('Deal Description'):
                return $this->data['Deal Component Terms Description'].' &rArr; '.$this->data['Deal Component Allowance Description'];
                break;
        }

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }


        return false;
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
            "SELECT `Deal Component Key` FROM `Deal Component Dimension` WHERE `Deal Component Deal Key`=%d AND  `Deal Component Trigger`=%s AND `Deal Component Trigger Key`=%d AND `Deal Component Terms Type`=%s AND `Deal Component Allowance Type`=%s AND `Deal Component Allowance Target`=%s AND `Deal Component Allowance Target Key`=%d ",
            $data['Deal Component Deal Key'], prepare_mysql($data['Deal Component Trigger']), $data['Deal Component Trigger Key'], prepare_mysql($data['Deal Component Terms Type']),
            prepare_mysql($data['Deal Component Allowance Type']), prepare_mysql($data['Deal Component Allowance Target']), $data['Deal Component Allowance Target Key']

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Deal Component Key'];
                $this->get_data('id', $row['Deal Component Key']);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($create and !$this->found) {
            $this->create($data);

        }


    }

    function create($data) {


        if ($data['Deal Component Trigger Key'] == '') {
            $data['Deal Component Trigger Key'] = 0;
        }
        if ($data['Deal Component Allowance Target Key'] == '') {
            $data['Deal Component Allowance Target Key'] = 0;
        }


        $keys   = '(';
        $values = 'values(';
        foreach ($data as $key => $value) {
            $keys .= "`$key`,";
            if ($key == 'Deal Component Replace' or $key == 'Deal Component Allowance Target XHTML Label' or $key == 'Deal Component Allowance XHTML Description' or $key
                == 'Deal Component Allowance Plain Description') {
                $values .= prepare_mysql($value, false).",";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Deal Component Dimension` %s %s", $keys, $values
        );
        // print "$sql\n";


        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();
            $this->get_data('id', $this->id);
            $this->update_allowance_description();
            $this->new = true;
        } else {
            print "Error can not create deal component\n $sql\n";
            exit;

        }
    }

    function update_allowance_description() {

        $allowance       = $this->data['Deal Component Allowance Description'];
        $allowance_plain = $this->data['Deal Component Allowance Description'];
        if ($this->data['Deal Component Allowance Target XHTML Label'] != ''

            and !($this->data['Deal Component Allowance Type'] == 'Get Free' and in_array(
                    $this->data['Deal Component Allowance Target'], array(
                                                                      'Product',
                                                                      'Family'
                                                                  )
                ))

            and !in_array(
                $this->data['Deal Component Terms Type'], array(
                                                            'Department Quantity Ordered',
                                                            'Department For Every Quantity Ordered',
                                                            'Department For Every Quantity Any Product Ordered',
                                                            'Family Quantity Ordered',
                                                            'Family For Every Quantity Ordered',
                                                            'Family For Every Quantity Any Product Ordered',
                                                            'Product Quantity Ordered',
                                                            'Product For Every Quantity Ordered'

                                                        )
            )) {
            $allowance       .= ' ('.$this->data['Deal Component Allowance Target XHTML Label'].')';
            $allowance_plain .= ' '.$this->data['Deal Component Allowance Target XHTML Label'];

        }

        if ($this->data['Deal Component Allowance Target XHTML Label'] != '' and $this->data['Deal Component Trigger'] == 'Customer') {
            $allowance       .= ' ('.$this->data['Deal Component Allowance Target XHTML Label'].')';
            $allowance_plain .= ' '.$this->data['Deal Component Allowance Target XHTML Label'];
        }


        $this->update(
            array(
                'Deal Component Allowance XHTML Description' => $allowance,
                'Deal Component Allowance Plain Description' => strip_tags($allowance_plain)

            ), 'no_history'
        );

        $this->data['Deal Component Allowance XHTML Description'] = $allowance;


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

    function update_field_switcher($field, $value, $options = '', $metadata = '') {

        switch ($field) {
            case 'Deal Component Expiration Date':
                $this->update_expiration_date($value, $options);
                break;
            case('term'):
                $this->update_term($value);
                break;
            case('allowance'):
                $this->update_allowance($value);
                break;
            default:
                $base_data = $this->base_data();

                if (array_key_exists($field, $base_data)) {
                    $this->update_field($field, $value, $options);
                }
        }
    }

    function update_expiration_date($value, $options) {

        if ($this->data['Deal Component Status'] == 'Finish') {
            $this->error = true;
            $this->msg   = 'Deal component already finished';
        } else {
            $this->update_field(
                'Deal Component Expiration Date', $value, $options
            );
            $this->updated = true;


        }

        $sql = sprintf(
            'SELECT `Deal Component Key` FROM `Deal Component Dimension` WHERE `Deal Component Status`!="Finish" AND `Deal Component Mirror Key`=%d', $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $deal_component = new DealComponent($row['Deal Component Key']);
                $deal_component->update(
                    array('Deal Component Expiration Date' => $value)
                );
                $deal_component->update_status_from_dates();
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update_status_from_dates();


    }

    function update_status_from_dates($force = false) {

        if ($this->data['Deal Component Expiration Date'] != '' and strtotime(
                $this->data['Deal Component Expiration Date'].' +0:00'
            ) <= strtotime('now +0:00')) {
            $this->update_field_switcher(
                'Deal Component Status', 'Finish', 'no_history'
            );

            return;
        }


        if (!$force and $this->data['Deal Component Status'] == 'Suspended') {
            return;
        }

        if (strtotime($this->data['Deal Component Begin Date'].' +0:00') >= strtotime('now +0:00')) {
            $this->update_field_switcher(
                'Deal Component Status', 'Waiting', 'no_history'
            );
        }


        if (strtotime($this->data['Deal Component Begin Date'].' +0:00') <= strtotime('now +0:00')) {
            $this->update_field_switcher(
                'Deal Component Status', 'Active', 'no_history'
            );
        }


    }

    function update_status($value) {


        if ($value == 'Suspended') {


            $this->update_field('Deal Component Status', $value);


        } else {
            $this->update_status_from_dates($force = true);
        }

        $sql = sprintf(
            "SELECT `Deal Component Key` FROM `Deal Component Dimension` WHERE `Deal Component Mirror Key`=%d", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $mirror_component         = new DealComponent(
                    $row['Deal Component Key']
                );
                $mirror_component->editor = $this->editor;


                if ($value == 'Suspended') {
                    $sql = sprintf(
                        "UPDATE `Deal Component Dimension` SET `Deal Component Status`=%s WHERE `Deal Component Key`=%d", prepare_mysql($value), $mirror_component->id
                    );
                    $this->db->exec($sql);
                    $mirror_component->data['Deal Component Status'] = $value;


                } else {
                    $mirror_component->update_status_from_dates($force = true);
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    function update_target_bridge() {

        if ($this->data['Deal Component Status'] == 'Finish') {
            $sql = sprintf(
                "DELETE FROM `Deal Target Bridge` WHERE `Deal Component Key`=%d ", $this->id
            );
            $this->db->exec($sql);
        } else {


            $sql = sprintf(
                "INSERT INTO `Deal Target Bridge` VALUES (%d,%s,%s,%d) ", $this->data['Deal Component Deal Key'], $this->id, prepare_mysql($this->data['Deal Component Allowance Target']),
                $this->data['Deal Component Allowance Target Key']

            );
            $this->db->exec($sql);

            if ($this->data['Deal Component Allowance Target'] == 'Category') {


                $sql = sprintf(
                    "SELECT `Subject Key` FROM `Category Bridge` WHERE `Category Key`=%d ", $this->data['Deal Component Allowance Target Key']
                );

                if ($result2 = $this->db->query($sql)) {
                    foreach ($result2 as $row2) {
                        $sql = sprintf(
                            "INSERT INTO `Deal Target Bridge` VALUES (%d,%d,%s,%d) ", $this->data['Deal Component Deal Key'], $this->id, prepare_mysql('Product'), $row2['Subject Key']

                        );
                        $this->db->exec($sql);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


            }


        }
    }


    function update_usage() {


        $sql = sprintf(
            "SELECT count( DISTINCT O.`Order Key`) AS orders,count( DISTINCT `Order Customer Key`) AS customers FROM `Order Deal Bridge` B LEFT  JOIN `Order Dimension` O ON (O.`Order Key`=B.`Order Key`) WHERE B.`Deal Component Key`=%d AND `Applied`='Yes' AND `Order State`!='Cancelled' ",
            $this->id

        );

        $orders    = 0;
        $customers = 0;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $orders    = $row['orders'];
                $customers = $row['customers'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update(
            array(
                'Deal Component Total Acc Applied Orders'    => $orders,
                'Deal Component Total Acc Applied Customers' => $customers,

            ), 'no_history'
        );


        $sql       = sprintf(
            "SELECT count( DISTINCT O.`Order Key`) AS orders,count( DISTINCT `Order Customer Key`) AS customers FROM `Order Deal Bridge` B LEFT  JOIN `Order Dimension` O ON (O.`Order Key`=B.`Order Key`) WHERE B.`Deal Component Key`=%d AND `Used`='Yes' AND `Order State`!='Cancelled' ",
            $this->id

        );
        $orders    = 0;
        $customers = 0;


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $orders    = $row['orders'];
                $customers = $row['customers'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update(
            array(
                'Deal Component Total Acc Used Orders'    => $orders,
                'Deal Component Total Acc Used Customers' => $customers,

            ), 'no_history'
        );


    }

}

?>
