<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 26 May 2014 17:04:23 CEST, Malaga , Spain

 Version 2.0
*/


class Payment_Service_Provider extends DB_Table {


    function Payment_Service_Provider($arg1 = false, $arg2 = false) {
        global $db;

        $this->db            = $db;
        $this->table_name    = 'Payment Service Provider';
        $this->ignore_fields = array('Payment Service Provider Key');

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        if (preg_match('/^(create|new)/i', $arg1)) {
            $this->find($arg2, 'create');

            return;
        }
        if (preg_match('/find/i', $arg1)) {
            $this->find($arg2, $arg1);

            return;
        }
        $this->get_data($arg1, $arg2);

        return;

    }


    function get_data($tipo, $tag) {

        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Payment Service Provider Dimension` WHERE `Payment Service Provider Key`=%d", $tag
            );
        }
        elseif ($tipo == 'type') {
            $sql = sprintf(
                "SELECT * FROM `Payment Service Provider Dimension` WHERE `Payment Service Provider Type`=%s", prepare_mysql($tag)
            );
        }elseif ($tipo == 'code') {
            $sql = sprintf(
                "SELECT * FROM `Payment Service Provider Dimension` WHERE `Payment Service Provider Code`=%s", prepare_mysql($tag)
            );
        } elseif ($tipo == 'block') {
            $sql = sprintf(
                "SELECT * FROM `Payment Service Provider Dimension` WHERE `Payment Service Provider Block`=%s", prepare_mysql($tag)
            );
        } else {
            return;
        }
        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Payment Service Provider Key'];
        }


    }


    function find($raw_data, $options) {

        $create = '';
        $update = '';
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


        $fields = array('Payment Service Provider Code');

        $sql = sprintf(
            "SELECT * FROM `Payment Service Provider Dimension` WHERE TRUE  "
        );
        foreach ($fields as $field) {
            $sql .= sprintf(
                ' and `%s`=%s', $field, prepare_mysql($data[$field], false)
            );
        }
        //print $sql;


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->get_data('id', $row['Payment Service Provider Key']);
                $this->found     = true;
                $this->found_key = $row['Payment Service Provider Key'];

            } else {
                $this->found = false;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if (!$this->found and $create) {
            $this->create($data);

        }


    }

    function create($data) {

        $this->data = $data;

        $keys   = '';
        $values = '';

        foreach ($this->data as $key => $value) {
            if ($key == 'Payment Service Provider XHTML Address') {
                continue;
            }

            $keys   .= ",`".$key."`";
            $values .= ','.prepare_mysql($value, false);


        }


        $values = preg_replace('/^,/', '', $values);
        $keys   = preg_replace('/^,/', '', $keys);

        $sql = "insert into `Payment Service Provider Dimension` ($keys) values ($values)";
        if ($this->db->exec($sql)) {
            $this->id                                   = $this->db->lastInsertId();
            $this->data['Payment Service Provider Key'] = $this->id;
            $this->new                                  = true;
            $this->get_data('id', $this->id);
        } else {
            print "Error can not create payment service provider\n";
            exit;

        }
    }

    function get_type() {

        switch ($this->data['Payment Service Provider Type']) {
            case'EPS':
                $type = _('Electronic payment service');
                break;
            case'EBeP':
                $type = _('Electronic bank payments');
                break;
            case'Bank':
                $type = _('Bank');
                break;
            case'Cash':
                $type = _('Cash');
                break;
            case'Account':
                $type = _('Customer account');
                break;
            case'ConD':
                $type = _('Cash on delivery');
                break;
            default:
                $type = $this->data['Payment Service Provider Type'];
        }

        return $type;

    }

    function get_valid_payment_methods() {
        $valid_payment_method = array();
        $sql                  = sprintf(
            "SELECT `Payment Method` FROM `Payment Service Provider Payment Method Bridge` WHERE `Payment Service Provider Key`=%d ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $valid_payment_method[] = $row['Payment Method'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $valid_payment_method;
    }

    function create_payment_account($data) {

        include_once 'class.Payment_Account.php';

        $this->new_account = false;


        $data['editor']                       = $this->editor;
        $data['Payment Account Service Provider Key'] = $this->id;

        if(empty($data['Payment Account Block'])){
            $data['Payment Account Block']=$this->get('Payment Service Provider Block');
        }



        if($data['Payment Account Block']=='Paypal'){
            $data['Payment Account URL Link']='https://www.paypal.com/cgi-bin/webscr';
            $data['Payment Account Refund URL Link']='https://api-3t.paypal.com/nvp';
        }else if($data['Payment Account Block']=='Sofort'){
            $data['Payment Account URL Link']='https://www.sofort.com/payment/start';
        }





        $payment_account = new Payment_Account('new', $data);




        if ($payment_account->id) {
            $this->new_account_msg = $payment_account->msg;

            if ($payment_account->new) {
                $this->new_account = true;





                $this->update_accounts_data();


            } else {
                $this->error = true;
                $this->msg   = $payment_account->msg;

            }

            return $payment_account;
        } else {
            $this->error = true;
            $this->msg   = $account->msg;
        }


    }




    function get($key = '') {


        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        switch ($key) {
        }
        $_key = ucfirst($key);
        if (isset($this->data[$_key])) {
            return $this->data[$_key];
        }

        return false;

    }

    function update_accounts_data() {
        $number_accounts = 0;
        $transactions    = 0;
        $payments        = 0;
        $refunds         = 0;
        $balance         = 0;
        $currencies      = '';

        $sql = sprintf(
            "SELECT count(*) AS num, sum(`Payment Account Transactions`) AS transactions, sum(`Payment Account Payments Amount`) AS payments, sum(`Payment Account Refunds Amount`) AS refunds, sum(`Payment Account Balance Amount`) AS balance FROM `Payment Account Dimension` WHERE `Payment Service Provider Key`=%d ",
            $this->id
        );
        if ($row = $this->db->query($sql)->fetch()) {

            $number_accounts = $row['num'];
            $transactions    = $row['transactions'];
            $payments        = $row['payments'];
            $refunds         = $row['refunds'];
            $balance         = $row['balance'];
            //$currencies=$row['currencies'];
        }

        $this->update(
            array(
                'Payment Service Provider Accounts'        => $number_accounts,
      //          'Payment Service Provider Currency'        => $currencies,
                'Payment Service Provider Transactions'    => $transactions,
                'Payment Service Provider Payments Amount' => $payments,
                'Payment Service Provider Refunds Amount'  => $refunds,
                'Payment Service Provider Balance Amount'  => $balance,

            ), 'no_history'
        );

    }


}
