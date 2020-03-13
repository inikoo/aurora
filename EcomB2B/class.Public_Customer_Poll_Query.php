<?php
/*
 
 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 21 February 2018 at 19:21:06 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/

include_once 'class.DBW_Table.php';

class Public_Customer_Poll_Query extends DBW_Table {


    function __construct($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Customer Poll Query';
        $this->ignore_fields = array('Customer Poll Query Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($key, $tag) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Customer Poll Query Dimension` WHERE `Customer Poll Query Key`=%d", $tag
            );
        } else {
            $this->get_deleted_data($tag);

            return;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Customer Poll Query Key'];
        }


    }

    function add_customer($customer, $value) {



        if ($customer->get('Customer Store Key') != $this->get('Store Key')) {

            $this->error = true;
            $this->msg   = 'customer not in poll store';

            return;
        }

        if ($this->get('Customer Poll Query Type') == 'Open') {


            $sql = sprintf('DELETE FROM `Customer Poll Fact` WHERE `Customer Poll Customer Key`=%d AND `Customer Poll Query Key`=%d ', $customer->id, $this->id);
            $this->db->exec($sql);

            if ($value != '') {
                $sql = sprintf(
                    'INSERT INTO `Customer Poll Fact` (`Customer Poll Customer Key`,`Customer Poll Query Key`,`Customer Poll Reply`,`Customer Poll Date`) VALUES (%d,%d,%s,%s)', $customer->id, $this->id, prepare_mysql($value), prepare_mysql(gmdate('Y-m-d H:i:s'))
                );

                $this->db->exec($sql);
            }


            require_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'     => 'update_poll_data',
                'poll_key' => $this->id,
                'editor'   => $this->editor
            ), DNS_ACCOUNT_CODE, $this->db
            );


        } else {



            $sql=sprintf('select `Customer Poll Query Option Key`,`Customer Poll Query Option Query Key` from `Customer Poll Query Option Dimension` where `Customer Poll Query Option Key`=%d  ',$value);


            if ($result2=$this->db->query($sql)) {
                if ($row2 = $result2->fetch()) {


                    if ($row2['Customer Poll Query Option Query Key'] != $this->id) {

                        $this->error = true;
                        $this->msg   = 'option not in poll';

                        return;
                    }


                    $sql = sprintf(
                        'SELECT `Customer Poll Key` FROM `Customer Poll Fact` WHERE `Customer Poll Customer Key`=%d AND `Customer Poll Query Key`=%d AND `Customer Poll Query Option Key`=%d ', $customer->id, $this->id, $value
                    );
                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $sql = sprintf('DELETE FROM `Customer Poll Fact` WHERE `Customer Poll Customer Key`=%d AND `Customer Poll Query Key`=%d  AND `Customer Poll Key`!=%d ', $customer->id, $this->id, $row['Customer Poll Key']);
                            $this->db->exec($sql);

                        } else {
                            $sql = sprintf('DELETE FROM `Customer Poll Fact` WHERE `Customer Poll Customer Key`=%d AND `Customer Poll Query Key`=%d ', $customer->id, $this->id);
                            $this->db->exec($sql);

                            $sql = sprintf(
                                'INSERT INTO `Customer Poll Fact` (`Customer Poll Customer Key`,`Customer Poll Query Key`,`Customer Poll Query Option Key`,`Customer Poll Date`) VALUES (%d,%d,%d,%s)', $customer->id, $this->id, $value, prepare_mysql(gmdate('Y-m-d H:i:s'))
                            );
                            $this->db->exec($sql);


                        }




                        require_once 'utils/new_fork.php';
                        new_housekeeping_fork(
                            'au_housekeeping', array(
                            'type'            => 'update_poll_option_data',
                            'poll_option_key' => $row2['Customer Poll Query Option Key'],
                            'editor'          => $this->editor
                        ), DNS_ACCOUNT_CODE, $this->db
                        );

                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


            	}
            }else {
            	print_r($error_info=$this->db->errorInfo());
            	print "$sql\n";
            	exit;
            }




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

                if (array_key_exists('Customer Poll Query '.$key, $this->data)) {
                    return $this->data['Customer Poll Query '.$key];
                }


        }

        return '';
    }


}


?>
