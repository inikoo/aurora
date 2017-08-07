<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 4 August 2017 at 16:02:46 CEST, Tranava, Slovakia

 Version 2.0
*/


include_once 'class.DBW_Table.php';


class Public_Payment extends DBW_Table {


    function Public_Payment($arg1 = false, $arg2 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Payment';
        $this->ignore_fields = array('Payment Key');

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        if (preg_match('/^(create|new)/i', $arg1)) {
            $this->create($arg2, 'create');

            return;
        }

        $this->get_data($arg1, $arg2);

        return;

    }


    function get_data($tipo, $tag) {

        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Payment Dimension` WHERE `Payment Key`=%d", $tag
            );
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Payment Key'];
        }


    }


    function create($raw_data) {


        $this->editor = $raw_data['editor'];


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = $value;
            }

        }


        $keys   = '';
        $values = '';


        foreach ($data as $key => $value) {

            $keys .= ",`".$key."`";
            if (

                in_array($key,array('Payment Completed Date','Payment Last Updated Date','Payment Cancelled Date','Payment Order Key','Payment Invoice Key','Payment Site Key','Payment Fees',
                    'Payment Balance','Payment Amount','Payment Refund','Payment Related Payment Key','Payment User Key'

                    ))

             ) {
                $values .= ','.prepare_mysql($value, true);

            } else {
                $values .= ','.prepare_mysql($value, false);
            }
        }

        $values = preg_replace('/^,/', '', $values);
        $keys   = preg_replace('/^,/', '', $keys);

        $sql = "insert into `Payment Dimension` ($keys) values ($values)";



        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->new = true;
            $this->get_data('id', $this->id);
        } else {
            print "Error can not create payment\n";
            exit;

        }
    }

    function get($key = '') {

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        switch ($key) {


            case 'Transaction Status':
                switch ($this->data['Payment Transaction Status']) {
                    case 'Pending':
                        return _('Pending');
                        break;
                    case 'Completed':
                        return _('Completed');
                        break;
                    case 'Cancelled':
                        return _('Cancelled');
                        break;
                    case 'Error':
                        return _('Error');
                        break;

                    default:
                        return $this->data['Payment Transaction Status'];

                }

                break;

                break;
            case 'Method':

                //'Credit Card','Cash','Paypal','Check','Bank Transfer','Cash on Delivery','Other','Unknown','Account'
                switch ($this->data['Payment Method']) {
                    case 'Credit Card':
                        return _('Credit Card');
                        break;
                    case 'Cash':
                        return _('Cash');
                        break;
                    case 'Paypal':
                        return _('Paypal');
                        break;
                    case 'Check':
                        return _('Check');
                        break;
                    case 'Bank Transfer':
                        return _('Bank Transfer');
                        break;
                    case 'Cash on Delivery':
                        return _('Cash on delivery');
                        break;
                    case 'Other':
                    case 'Unknown':
                        return _('Other');

                        break;
                    case 'Account':
                        return _('Account');

                        break;
                    default:
                        return $this->data['Payment Method'];

                }

                break;

            case('Amount'):
                return money(
                    $this->data['Payment '.$key], $this->data['Payment Currency Code']
                );
                break;
            case('Completed Date'):
            case('Cancelled Date'):
            case('Created Date'):
                return strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($this->data['Payment '.$key].' +0:00')
                );
                break;

        }
        $_key = ucfirst($key);
        if (isset($this->data[$_key])) {
            return $this->data[$_key];
        }

        return false;

    }


    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (is_string($value)) {
            $value = _trim($value);
        }


        switch ($field) {

            case 'Payment Order Key':

                $this->update_field($field,$value, $options);

                break;





                break;

            default:
                print "Public payment can't update >>>".$field."\n";

        }
    }


    function get_formatted_time_lapse($key) {
        include_once 'utils/date_functions.php';

        return gettext_relative_time(
            gmdate('U') - gmdate(
                'U', strtotime($this->data['Payment '.$key].' +0:00')
            )
        );
    }

    function get_formatted_info() {
        $info = '';
        $this->load_payment_account();
        $this->load_payment_service_provider();
        switch ($this->data['Payment Transaction Status']) {

            case 'Pending':
                $info = sprintf(
                    "%s %s %s %s, %s %s", _('A payment of'), money(
                    $this->data['Payment Transaction Amount'], $this->data['Payment Currency Code']
                ), _('using'), $this->payment_service_provider->data['Payment Service Provider Name'], _('payment service provider'), _('is in process')

                );

                break;
            case 'Completed':

                if ($this->data['Payment Method'] == 'Account') {
                    $info = sprintf(
                        "%s %s", money(
                        $this->data['Payment Transaction Amount'], $this->data['Payment Currency Code']
                    ), _('has been paid from the customer account')

                    );

                } else {
                    $info = sprintf(
                        "%s %s %s %s %s %s. %s: %s", _('A payment of'), money(
                        $this->data['Payment Transaction Amount'], $this->data['Payment Currency Code']
                    ), _('using'), $this->payment_service_provider->data['Payment Service Provider Name'], _('payment service provider'), _('has been completed sucessfully'), _('Reference'),
                        $this->data['Payment Transaction ID']

                    );
                }
                break;
            case 'Cancelled':
                $info = sprintf(
                    "%s %s %s %s %s %s", _('A payment of'), money(
                    $this->data['Payment Transaction Amount'], $this->data['Payment Currency Code']
                ), _('using'), $this->payment_service_provider->data['Payment Service Provider Name'], _('payment service provider'), _('has been cancelled')

                );

                break;
            case 'Error':
                $info = sprintf(
                    "%s %s %s %s %s %s", _('A payment of'), money(
                    $this->data['Payment Transaction Amount'], $this->data['Payment Currency Code']
                ), _('using'), $this->payment_service_provider->data['Payment Service Provider Name'], _('payment service provider'), _('has had an error')

                );

                break;

        }

        return $info;
    }

    function load_payment_account() {

        $this->payment_account = new Payment_Account(
            $this->data['Payment Account Key']
        );
    }

    function load_payment_service_provider() {

        $this->payment_service_provider = new Payment_Service_Provider(
            $this->data['Payment Service Provider Key']
        );
    }

    function get_formatted_short_info() {
        $info = '';
        $this->load_payment_account();
        $this->load_payment_service_provider();
        switch ($this->data['Payment Transaction Status']) {

            case 'Pending':
                $info = sprintf(
                    "%s, %s",

                    $this->payment_service_provider->data['Payment Service Provider Name'], _('payment in process')

                );

                break;
            case 'Completed':
                $info = sprintf(
                    "%s, %s, %s: ",

                    $this->payment_service_provider->data['Payment Service Provider Name'], _('payment completed successfully'), _('Reference'), $this->data['Payment Transaction ID']

                );

                break;
            case 'Cancelled':
                $info = sprintf(
                    "%s, %s",

                    $this->payment_service_provider->data['Payment Service Provider Name'], _('payment cancelled')

                );


                break;
            case 'Error':
                $info = sprintf(
                    "%s %s",

                    $this->payment_service_provider->data['Payment Service Provider Name'], _('payment has had an error')

                );

                break;

        }

        return $info;

    }


}
