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
            if ($tipo == 'block') {
                $sql = sprintf(
                    "SELECT * FROM `Payment Account Dimension` WHERE `Payment Account Block`=%s", prepare_mysql($tag)
                );
            } else {
                return;
            }
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Payment Account Key'];
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

        $data['Payment Account From'] = gmdate('Y-m-d H:i:s');

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

    function get($key = '') {

        //'Credit Card','Cash','Paypal','Check','Bank Transfer','Cash on Delivery','Other','Unknown','Account'

        switch ($key) {
            case 'Default Payment Method':

                switch ($this->data['Payment Account Block']) {
                    case 'BTree':
                        return 'Credit Card';
                        break;
                    case 'BTreePaypal':
                        return 'Paypal';
                        break;
                    case 'Paypal':
                        return 'Paypal';
                        break;
                    case 'Bank':
                        return 'Bank Transfer';
                        break;
                    case 'Sofort':
                        return 'Bank Transfer';
                        break;
                    case 'Cash':
                        return 'Cash';
                        break;
                    case 'Other':
                        return 'Other';
                        break;
                    case 'ConD':
                        return 'Cash on Delivery';
                        break;
                    case 'Accounts':
                        return 'Account';
                        break;
                    default:
                        return '';
                        break;

                }

                break;

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
        $refunded     = 0;
        $credited     = 0;
        $balance      = 0;

        $sql = sprintf(
            "SELECT count(*) AS num,group_concat(DISTINCT `Payment Currency Code`) AS currencies, sum(if(`Payment Transaction Amount`>0,`Payment Transaction Amount`,0)) AS payments,
  sum(`Payment Transaction Amount`) AS balance,sum(`Payment Transaction Amount Refunded`) AS refunded,sum(`Payment Transaction Amount Credited`) AS credited FROM `Payment Dimension` WHERE `Payment Account Key`=%d AND `Payment Transaction Status`='Completed'",
            $this->id
        );
        // print $sql;
        if ($row = $this->db->query($sql)->fetch()) {

            $transactions = $row['num'];
            // $currencies   = $row['currencies'];
            $payments = $row['payments'];
            $refunded = $row['refunded'];
            $balance  = $row['balance'];
        }

        $this->update(
            array(
                'Payment Account Transactions'    => $transactions,
                'Payment Account Payments Amount' => $payments,
                'Payment Account Refunded Amount' => $refunded,
                'Payment Account Credited Amount' => $credited,

                'Payment Account Balance Amount' => $balance,


            ), 'no_history'
        );

    }

    function assign_to_store($data) {

        $store = get_object('Store', $data['Store Key']);
        //$data['Website Key']=$store->get('Store Website Key');


        $sql = sprintf(
            'INSERT INTO  `Payment Account Store Bridge`  (`Payment Account Store Payment Account Key`,`Payment Account Store Website Key`,`Payment Account Store Store Key`,`Payment Account Store Valid From`,`Payment Account Store Status`,`Payment Account Store Show In Cart`,`Payment Account Store Show Cart Order`) 
          VALUES (%d,%d,%d,%s,%s,%s,%d) ', $this->id, $store->id, $store->get('Store Website Key'), prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql($data['Status']),
            prepare_mysql($data['Show In Cart']), $data['Show Cart Order']
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

    function create_payment($data) {
        $account                              = get_object('Account', 1);
        $data['Payment Account Key']          = $this->id;
        $data['Payment Account Code']         = $this->data['Payment Account Code'];
        $data['Payment Service Provider Key'] = $this->data['Payment Account Service Provider Key'];
        $data['editor']                       = $this->editor;


        if ($account->get('Currency Code') != $data['Payment Currency Code']) {
            include_once 'utils/currency_functions.php';
            $data['Payment Currency Exchange Rate'] = currency_conversion($this->db, $data['Payment Currency Code'], $account->get('Currency Code'));
        } else {
            $data['Payment Currency Exchange Rate'] = 1;
        }


        include_once 'class.Payment.php';

        $payment = new Payment('new', $data);
        if ($payment->error) {
            $this->error = true;
            $this->msg   = $payment->msg;

            return $payment;
        }


        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'        => 'payment_created',
            'payment_key' => $payment->id
        ), $account->get('Account Code'), $this->db
        );


        return $payment;

    }


}
