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


    function __construct($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

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

        if ($this->data = $this->db->query($sql)->fetch()) {
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
        $this->error     = false;
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
            "SELECT `Voucher Key`,`Voucher Deal Key` FROM `Voucher Dimension` WHERE  `Voucher Code`=%s AND `Voucher Store Key`=%d ", prepare_mysql($data['Voucher Code']), $data['Voucher Store Key']
        );


        if ($result=$this->db->query($sql)) {
            if ($row = $result->fetch()) {

                if($row['Voucher Deal Key']==$data['Voucher Deal Key']){
                    $this->found     = true;
                    $this->found_key = $row['Voucher Key'];
                }else{
                    $this->error=true;
                }


        	}
        }else {
        	print_r($error_info=$this->db->errorInfo());
        	print "$sql\n";
        	exit;
        }



        if ($this->found) {
            $this->get_data('id', $this->found_key);
        }


        if ($create and !$this->found and !$this->error) {
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

       // print "$sql\n";


        if ($this->db->exec($sql)) {
            $this->id =$this->db->lastInsertId();
            $this->get_data('id', $this->id);
            $this->new = true;



        } else {
           $this->error=true;

        }
    }


    function get($key = '') {

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        switch ($key) {
            case 'Status':
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

                break;

            case 'Valid From':

                if ($this->data['Voucher Valid From'] == '') {
                    return '';
                } else {
                    return gmdate(
                        'd-m-Y', strtotime($this->data['Voucher Valid From'].' +0:00')
                    );
                }
                break;
            case 'Valid To':
                if ($this->data['Voucher Valid To'] == '') {
                    return '';
                } else {
                    return gmdate(
                        'd-m-Y', strtotime($this->data['Voucher Valid To'].' +0:00')
                    );
                }

                break;

        }

        return false;
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

        foreach ($this->get_deals('keys') as $deal_key) {
            $deal = new Deal($deal_key);
            $deal->update_status_from_dates();


        }




    }


    function update_usage() {

        $orders    = 0;
        $customers = 0;


        $sql       = sprintf(
            "SELECT count(*) AS orders,count( DISTINCT `Customer Key`) AS customers FROM `Voucher Order Bridge` B WHERE B.`Voucher Key`=%d ", $this->id

        );

        if ($result=$this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $orders    = $row['orders'];
                $customers = $row['customers'];
        	}
        }else {
        	print_r($error_info=$this->db->errorInfo());
        	print "$sql\n";
        	exit;
        }



        $sql = sprintf(
            "UPDATE `Voucher Dimension` SET `Voucher Total Acc Used Orders`=%d, `Voucher Total Acc Used Customers`=%d WHERE `Voucher Key`=%d", //$orders_done,
            $orders, $customers, $this->id
        );
       $this->db->exec($sql);

        /** @var $store \Store */
        $store = get_object('Store',$this->data['Voucher Store Key']);
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




}

?>
