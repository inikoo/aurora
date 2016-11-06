<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 6 February 2015 15:58:22 GMT+8, Ubud (Bali), Indonesia

 Copyright (c) 2015, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class Voucher extends DB_Table {


    function Voucher($a1, $a2 = false, $a3 = false) {

        $this->table_name    = 'Voucher';
        $this->ignore_fields = array('Voucher Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } else {
            if (($a1 == 'new' or $a1 == 'create') and is_array($a2)) {
                $this->find($a2, 'create');

            } elseif (preg_match('/find/i', $a1)) {
                $this->find($a2, $a1);
            } else {
                $this->get_data($a1, $a2, $a3);
            }
        }

    }

    function get_data($tipo, $tag, $tag2 = false) {

        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Voucher Dimension` WHERE `Voucher Key`=%d", $tag
            );
        } elseif ($tipo == 'code_store') {
            $sql = sprintf(
                "SELECT * FROM `Voucher Dimension` WHERE `Voucher Code`=%s AND `Voucher Store Key`=%d", prepare_mysql($tag), $tag2
            );
        }


        $result = mysql_query($sql);

        if ($this->data = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id = $this->data['Voucher Key'];
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
        $update          = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }
        if (preg_match('/update/i', $options)) {
            $update = 'update';
        }

        $data = $this->base_data();


        foreach ($raw_data as $key => $value) {

            if (array_key_exists($key, $data)) {
                $data[$key] = $value;
            }

        }


        $sql = sprintf(
            "SELECT `Voucher Key` FROM `Voucher Dimension` WHERE  `Voucher Code`=%s AND `Voucher Store Key`=%d ", prepare_mysql($data['Voucher Code']), $data['Voucher Store Key']
        );


        $result      = mysql_query($sql);
        $num_results = mysql_num_rows($result);
        if ($num_results == 1) {
            $row             = mysql_fetch_array($result, MYSQL_ASSOC);
            $this->found     = true;
            $this->found_key = $row['Voucher Key'];

        }
        if ($this->found) {
            $this->get_data('id', $this->found_key);
        }


        if ($create and !$this->found) {
            $this->create($data);

        }


    }


    function create($data) {

        $keys   = '';
        $values = '';
        foreach ($data as $key => $value) {
            $keys .= "`$key`,";
            if ($key == 'Voucher Description') {
                $values .= prepare_mysql($value, false).",";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }
        $keys   = preg_replace('/,$/', '', $keys);
        $values = preg_replace('/,$/', '', $values);


        // print_r($data);
        $sql = sprintf(
            "INSERT INTO `Voucher Dimension` (%s) VALUES (%s)", $keys, $values
        );

        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->get_data('id', $this->id);
            $this->new = true;

            $store = new Store('id', $this->data['Voucher Store Key']);
            $store->update_campaings_data();

        } else {
            print "Error can not create voucher  $sql\n";
            exit;

        }
    }


    function get($key = '') {

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        switch ($key) {

        }

        return false;
    }

    function get_formatted_status() {

        switch ($this->data['Voucher Status']) {
            case 'Waiting':
                return _('Waiting');
                break;
            case 'Used':
                return _('Used');
                break;
            case 'Active':
                return _('Active');
                break;
            case 'Expired':
                return _('Expired');
                break;

            default:
                return $this->data['Voucher Status'];
        }

    }

    function get_from_date() {
        if ($this->data['Voucher Valid From'] == '') {
            return '';
        } else {
            return gmdate(
                'd-m-Y', strtotime($this->data['Voucher Valid From'].' +0:00')
            );
        }
    }

    function get_to_date() {
        if ($this->data['Voucher Valid To'] == '') {
            return '';
        } else {
            return gmdate(
                'd-m-Y', strtotime($this->data['Voucher Valid To'].' +0:00')
            );
        }
    }


    function update_status_from_dates() {


        if ($this->data['Voucher Status'] == 'Waiting' and strtotime(
                $this->data['Voucher Valid From'].' +0:00'
            ) > strtotime('now +0:00')
        ) {

            $this->update_field_switcher(
                'Voucher Status', 'Active', 'no_history'
            );

        }


        if ($this->data['Voucher Valid To'] != '' and strtotime(
                $this->data['Voucher Valid To'].' +0:00'
            ) < strtotime('now +0:00')
        ) {

            $this->update_field_switcher(
                'Voucher Status', 'Finish', 'no_history'
            );

        }

        foreach ($this->get_deal_keys() as $deal_key) {
            $deal = new Deal($deal_key);
            $deal->update_status_from_dates();


        }

        foreach ($this->get_deal_component_keys() as $deal_component_key) {
            $deal_compoment = new DealComponent($deal_key);
            $deal_compoment->update_status_from_dates();
        }


    }


    function update_usage() {


        $sql       = sprintf(
            "SELECT count(*) AS orders,count( DISTINCT `Customer Key`) AS customers FROM `Voucher Order Bridge` B WHERE B.`Voucher Key`=%d ", $this->id

        );
        $res       = mysql_query($sql);
        $orders    = 0;
        $customers = 0;
        if ($row = mysql_fetch_assoc($res)) {
            $orders    = $row['orders'];
            $customers = $row['customers'];
        }
        /*
        $sql=sprintf("select count(*) as orders_done from `Voucher Order Bridge` B where B.`Voucher Key`=%d and `State`='Consolidated'",
            $this->id

        );

        $res=mysql_query($sql);
        $orders_done=0;

        if ($row=mysql_fetch_assoc($res)) {
            $orders_done=$row['orders_done'];

        }
*/
        $sql = sprintf(
            "UPDATE `Voucher Dimension` SET `Voucher Total Acc Used Orders`=%d, `Voucher Total Acc Used Customers`=%d WHERE `Voucher Key`=%d", //$orders_done,
            $orders, $customers, $this->id
        );
        mysql_query($sql);


        $store = new Store($this->data['Voucher Store Key']);
        $store->update_campaings_data();
        $store->update_deals_data();


    }

    function update_status_from_deal() {

        $deal = new Deal ($this->data['Voucher Deal Key']);
        if ($deal->data['Deal Status'] == 'Finish') {
            $this->update_field_switcher(
                'Voucher Status', 'Finish', 'no_history'
            );
        } else {
            $this->update_field_switcher(
                'Voucher Status', 'Active', 'no_history'
            );

        }

    }


    function delete_todo() {


        if ($this->get_number_deals() > 0 and $this->data['Voucher Status'] != 'Waiting') {
            $this->msg = 'can not delete';

            return;
        }


        $sql = sprintf(
            "DELETE FROM `Voucher Dimension` WHERE `Voucher Key`=%d", $this->id
        );
        mysql_query($sql);

        $sql = sprintf(
            "DELETE FROM `Deal Dimension` WHERE `Voucher Key`=%d", $this->id
        );
        mysql_query($sql);

        $sql = sprintf(
            "DELETE FROM `Deal Compoment Dimension` WHERE `Deal Compoment Campaign Key`=%d", $this->id
        );
        mysql_query($sql);


    }

}

?>
