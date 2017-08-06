<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 26 May 2014 16:55:05 CEST, Malaga , Spain

 Version 2.0
*/


include_once 'class.DB_Table.php';


class Payment extends DB_Table {


    function Payment($arg1 = false, $arg2 = false) {

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
            if ($key == 'Payment Completed Date' or $key == 'Payment Last Updated Date' or $key == 'Payment Cancelled Date' or $key == 'Payment Order Key' or $key == 'Payment Invoice Key' or $key
                == 'Payment Site Key') {
                $values .= ','.prepare_mysql($value, true);

            } else {
                $values .= ','.prepare_mysql($value, false);
            }
        }

        $values = preg_replace('/^,/', '', $values);
        $keys   = preg_replace('/^,/', '', $keys);

        $sql = "insert into `Payment Dimension` ($keys) values ($values)";

        //   print "$sql\n";

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


        if (!$this->id) {
            return;
        }


        switch ($key) {

            case('Max Payment to Refund'):
                return round(
                    $this->data['Payment Transaction Amount'] - $this->data['Payment Transaction Amount Refunded'], 2);
                break;
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

            case('Transaction Amount'):
                return money($this->data['Payment '.$key], $this->data['Payment Currency Code']);
                break;
            case('Completed Date'):
            case('Cancelled Date'):
            case('Created Date'):
                return strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($this->data['Payment '.$key].' +0:00')
                );
                break;

        }

        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        if (array_key_exists('Payment '.$key, $this->data)) {
            return $this->data['Payment '.$key];
        }

        return false;

    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (is_string($value)) {
            $value = _trim($value);
        }


        switch ($field) {

            case 'Payment Transaction Amount':


                if( $value< ($this->data['Payment Transaction Amount Refunded']+$this->data['Payment Transaction Amount Credited'])){

                    $this->error=true;
                    $this->msg=_("Payment amount can't be smaller than its refunds or credits");
        return;
                }





                $this->update_field($field, $value, $options);

                $this->update_parents();


                break;


            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                }
        }
    }

    function update_parents() {
        $order = get_object('Order', $this->data['Payment Order Key']);
        $order->update_totals();


        $account=get_object('Account',1);
        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'        => 'payment_updated',
            'payment_key' => $this->id,
        ), $account->get('Account Code'), $this->db
        );

    }

    function delete() {

        if($this->data['Payment Transaction Amount Refunded']!=0 or $this->data['Payment Transaction Amount Credited']!=0 ){

            $this->error=true;
            $this->msg=_("Payment can't be cancelled if it has refunds or credits");
            return;

        }


        $this->update(
            array(
                'Payment Transaction Status' => 'Cancelled',
                'Payment Cancelled Date'     => gmdate('Y-m-d H:i:s'),
                'Payment Last Updated Date'  => gmdate('Y-m-d H:i:s'),
            )

        );

        $this->update_parents();


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
