<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Created: 28 July 2017 at 17:59:18 CEST, Tranava, Slovakia

 Copyright (c) 2017, Inikoo
 Version 2.0
*/


class Public_Payment_Account {


    function Public_Payment_Account($arg1 = false, $arg2 = false) {

        global $db;
        $this->db            = $db;
        $this->table_name    = 'Payment Account';
        $this->ignore_fields = array('Payment Account Key');

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

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


    function create_payment($data) {

        $data['Payment Account Key']          = $this->id;
        $data['Payment Account Code']         = $this->data['Payment Account Code'];
        $data['Payment Service Provider Key'] = $this->data['Payment Account Service Provider Key'];
        $data['editor']                       = $this->editor;


        $account=get_object('Account',1);
        if ($account->get('Currency Code') != $data['Payment Currency Code']) {
            include_once 'utils/currency_functions.php';
            $data['Payment Currency Exchange Rate'] = currency_conversion($this->db, $data['Payment Currency Code'], $account->get('Currency Code'));
        } else {
            $data['Payment Currency Exchange Rate'] = 1;
        }



        include_once 'class.Public_Payment.php';

        $payment = new Public_Payment('new', $data);
        if ($payment->error) {
            $this->error = true;
            $this->msg   = $payment->msg;

            return $payment;
        }



        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'        => 'payment_created',
            'subject_key' => $payment->id,
            'editor'      => $payment->editor
        ), $account->get('Account Code'), $this->db
        );


        return $payment;

    }

    function get($key = '', $arg = '') {




        switch ($key) {
            case 'Block Data':


                if ($this->data['Payment Account Block'] == 'BTreePaypal') {


                    $paypal_data = base64_url_encode(
                        AESEncryptCtr(
                            json_encode(
                                array(
                                    'braintree_account_key'    => $this->id,
                                    'Payment Account ID'       => $this->get('Payment Account ID'),
                                    'Payment Account Login'    => $this->get('Payment Account Login'),
                                    'Payment Account Password' => $this->get('Payment Account Password'),
                                    'order_key'                => $arg->id,
                                    'amount'                   => $arg->get('Order To Pay Amount'),
                                    'currency'                 => $arg->get('Order Currency'),
                                    'Random'                   => password_hash(time(), PASSWORD_BCRYPT)
                                )
                            ), md5('83edh3847203942,'.CKEY), 256
                        )
                    );


                    return $paypal_data;

                } elseif ($this->data['Payment Account Block'] = 'BTree') {


                    require_once 'external_libs/braintree-php-3.2.0/lib/Braintree.php';


                    Braintree_Configuration::environment('production');
                    Braintree_Configuration::merchantId($this->data['Payment Account ID']);
                    Braintree_Configuration::publicKey($this->data['Payment Account Login']);
                    Braintree_Configuration::privateKey($this->data['Payment Account Password']);


                    try {
                        return Braintree_ClientToken::generate();
                    } catch (Exception $e) {

                        return 'error';

                    }


                }

                break;

        }



        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (array_key_exists('Payment Account '.$key, $this->data)) {
            return $this->data[$this->table_name.' '.$key];
        }

        return false;

    }


    function in_website($website_key) {
        $is_in_website = false;
        $sql           = sprintf(
            "SELECT count(*) AS num FROM `Payment Account Store Bridge` WHERE `Website Key`=%d AND `Payment Account Key`=%d ", $website_key, $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $is_in_website = true;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $is_in_website;
    }


    function is_active_in_website($website_key) {
        $is_active_in_website = false;
        $sql                  = sprintf(
            "SELECT count(*) AS num FROM `Payment Account Store Bridge` WHERE `Website Key`=%d AND `Payment Account Key`=%d AND `Status`='Active'  ", $website_key, $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $is_active_in_website = true;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $is_active_in_website;
    }


    function get_settings() {
        return json_decode($this->data['Payment Account Settings'], true);

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
