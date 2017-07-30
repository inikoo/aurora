<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 26 May 2014 17:00:48 CEST, Malaga , Spain

 Version 2.0
*/


class Payment_Account extends DB_Table {


    function Payment_Account($arg1 = false, $arg2 = false) {

        global $db;
        $this->db            = $db;
        $this->table_name    = 'Payment Account';
        $this->ignore_fields = array('Payment Account Key');

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
                "SELECT * FROM `Payment Account Dimension` WHERE `Payment Account Key`=%d", $tag
            );
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Payment Account Key'];
        }
    }


    function load_acc_data() {


        //todo
        return;

        $sql = sprintf("SELECT * FROM `Payment Account Data`  WHERE `Payment Account Key`=%d", $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        $sql = sprintf("SELECT * FROM `Payment Account DC Data`  WHERE `Payment Account Key`=%d", $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function find($raw_data, $options) {

        $create = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }

        $data = $this->base_data();


        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = $value;
            }

        }


        //  print_r($data);
        //  exit("s");


        $fields = array('Payment Account Code');

        $sql = sprintf(
            "SELECT * FROM `Payment Account Dimension` WHERE TRUE  "
        );
        foreach ($fields as $field) {
            $sql .= sprintf(
                ' and `%s`=%s', $field, prepare_mysql($data[$field], false)
            );
        }
        //print $sql;


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->get_data('id', $row['Payment Account Key']);
                $this->found     = true;
                $this->found_key = $row['Payment Account Key'];
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



        $keys   = '';
        $values = '';

$data['Payment Account From']=gmdate('Y-m-d H:i:s');

        unset($data['Payment Account Last Used Date']);


     //   print_r($data);


        foreach ($data as $key => $value) {


            $keys   .= ",`".$key."`";
            $values .= ','.prepare_mysql($value, false);


        }



        $values = preg_replace('/^,/', '', $values);
        $keys   = preg_replace('/^,/', '', $keys);



        $sql = "insert into `Payment Account Dimension` ($keys) values ($values)";



        if ($this->db->exec($sql)) {
            $this->id                  = $this->db->lastInsertId();
            $this->data['Address Key'] = $this->id;
            $this->new                 = true;
            $this->get_data('id', $this->id);
        } else {
            print "Error can not create payment account\n";
            exit;

        }
    }

    function get($key = '') {


        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        switch ($key) {
            case 'Transactions':
            case 'Number Stores':
            case 'Number Websites':
                return number($this->data['Payment Account '.$key]);

        }

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (array_key_exists('Payment Account '.$key, $this->data)) {
            return $this->data[$this->table_name.' '.$key];
        }

        return false;

    }










    function update_payments_data() {
        $transactions = 0;
        $payments     = 0;
        $refunds      = 0;
        $balance      = 0;
        $currencies   = '';

        $sql = sprintf(
            "SELECT count(*) AS num,group_concat(DISTINCT `Payment Currency Code`) AS currencies,sum(`Payment Amount`) AS payments,sum(`Payment Refund`) AS refunds,sum(`Payment Balance`) AS balance FROM `Payment Dimension` WHERE `Payment Account Key`=%d AND `Payment Type`='Payment'",
            $this->id
        );
        print $sql;
        if ($row = $this->db->query($sql)->fetch()) {
            print_r($row);
            $transactions = $row['num'];
            $currencies   = $row['currencies'];
            $payments     = $row['payments'];
            $refunds      = $row['refunds'];
            $balance      = $row['balance'];
        }

        $this->update(
            array(
                'Payment Account Transactions'    => $transactions,
                'Payment Account Currency'        => $currencies,
                'Payment Account Payments Amount' => $payments,
                'Payment Account Refunds Amount'  => $refunds,
                'Payment Account Balance Amount'  => $balance,


            ), 'no_history'
        );

    }

    function assign_to_store($data) {

        $store = get_object('Store', $data['Store Key']);
        //$data['Website Key']=$store->get('Store Website Key');


        $sql = sprintf(
            'INSERT INTO  `Payment Account Store Bridge`  (`Payment Account Store Payment Account Key`,`Payment Account Store Website Key`,`Payment Account Store Store Key`,`Payment Account Store Valid From`,`Payment Account Store Status`,`Payment Account Store Show In Cart`,`Payment Account Store Show Cart Order`) 
          VALUES (%d,%d,%d,%s,%s,%s,%d) ', $this->id, $store->id, $store->get('Store Website Key'), prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql($data['Status']), prepare_mysql($data['Show In Cart']),
            $data['Show Cart Order']
        );




        $this->db->exec($sql);


    }



    function get_field_label($field) {

        switch ($field) {

            case 'Payment Account Code':
                $label = _('code');
                break;
            case 'Payment Account Name':
                $label = _('name');
                break;
            case 'Payment Account Recipient Holder':
                $label = _('Account beneficiary');
                break;
            case 'Payment Account Recipient Address':
                $label = _('Bank address');
                break;
            case 'Payment Account Recipient Bank Account Number':
                $label = _('account number');
                break;
            case 'Payment Account Recipient Bank Code':
                $label = _('Bank code');
                break;
            case 'Payment Account Recipient Bank Name':
                $label = _('bank name');
                break;
            case 'Payment Account Recipient Bank Swift':
                $label = 'Bank SWIFT/BIC code';
                break;
            case 'Payment Account Recipient Bank IBAN':
                $label = _('Account IBAN');
                break;



            default:
                $label = $field;

        }

        return $label;

    }



}
