<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 26 May 2014 16:55:05 CEST, Malaga , Spain

 Version 2.0
*/


include_once 'class.DB_Table.php';
include_once 'trait.PaymentAiku.php';


class Payment extends DB_Table
{
    use PaymentAiku;

    function __construct($arg1 = false, $arg2 = false)
    {
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
    }


    function get_data($tipo, $tag)
    {
        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Payment Dimension` WHERE `Payment Key`=%d",
                $tag
            );
        } else {
            return;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Payment Key'];
        }
    }


    function create($raw_data)
    {
        $this->editor = $raw_data['editor'];


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = $value;
            }
        }


        //  print_r($data);

        $keys   = '';
        $values = '';


        foreach ($data as $key => $value) {
            $keys .= ",`".$key."`";
            if (

                in_array(
                    $key, array(
                            'Payment Completed Date',
                            'Payment Last Updated Date',
                            'Payment Cancelled Date',
                            'Payment Order Key',
                            'Payment Invoice Key',
                            'Payment Site Key',
                            'Payment Fees',
                            'Payment Balance',
                            'Payment Amount',
                            'Payment Refund',
                            'Payment Related Payment Key',
                            'Payment User Key'

                        )
                )

            ) {
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
            $this->id = $this->db->lastInsertId();
            if (!$this->id) {
                throw new Exception('Error inserting '.$this->table_name);
            }
            $this->new = true;
            $this->get_data('id', $this->id);

            $this->fork_index_elastic_search();
            $this->model_updated(null, 'new', $this->id);
        } else {
            print "Error can not create payment\n";
            print "$sql\n";
            exit;
        }
    }

    function get_orders($scope = 'keys')
    {
        $sql = sprintf(
            'SELECT `Payment Order Key` FROM `Payment Dimension` WHERE `Payment Key`=%d ',
            $this->id
        );

        $orders = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($scope == 'objects') {
                    $orders[$row['Payment Order Key']] = get_object('Order', $row['Payment Order Key']);
                } else {
                    $orders[$row['Payment Order Key']] = $row['Payment Order Key'];
                }
            }
        }
        return $orders;
    }

    function get_invoices($scope = 'keys')
    {
        $sql = sprintf(
            'SELECT `Payment Invoice Key` FROM `Payment Dimension` WHERE `Payment Key`=%d ',
            $this->id
        );

        $invoices = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($scope == 'objects') {
                    $orders[$row['Payment Invoice Key']] = get_object('Invoice', $row['Payment Invoice Key']);
                } else {
                    $orders[$row['Payment Invoice Key']] = $row['Payment Invoice Key'];
                }
            }
        }
        return $invoices;
    }


    function get($key = '')
    {
        if (!$this->id) {
            return;
        }


        switch ($key) {
            case('Max Payment to Refund'):
                return round(
                    $this->data['Payment Transaction Amount'] - $this->data['Payment Transaction Amount Refunded'],
                    2
                );

            case 'Transaction Status':
                switch ($this->data['Payment Transaction Status']) {
                    case 'Pending':
                        return _('Pending');
                        break;
                    case 'Completed':
                        return _('Completed');
                        break;
                    case 'Cancelled':
                        return '<span class="error">'._('Cancelled').'</span>';
                        break;
                    case 'Error':
                        return _('Error');
                        break;

                    default:
                        return $this->data['Payment Transaction Status'];
                }


            case 'Method':

                switch ($this->data['Payment Method']) {
                    case 'Credit Card':
                        return _('Credit Card');
                    case 'Cash':
                        return _('Cash');
                    case 'Paypal':
                        return _('Paypal');
                    case 'Check':
                        return _('Check');
                    case 'Bank Transfer':
                        return _('Bank Transfer');
                    case 'Cash on Delivery':
                        return _('Cash on delivery');
                    case 'Other':
                    case 'Unknown':
                        return _('Other');

                    case 'Account':
                        return _('Account');

                    default:
                        return $this->data['Payment Method'];
                }

            case 'Icon':
                switch ($this->data['Payment Method']) {
                    case 'Credit Card':
                        return 'fal fa-credit-card';

                    case 'Cash':
                        return 'fal fa-money-bill-wave';

                    case 'Paypal':
                        return 'fab fa-cc-paypal';

                    case 'Check':
                        return 'fal fa-money-check-edit-alt';

                    case 'Bank Transfer':
                        return 'fal fa-money-check-alt';

                    case 'Cash on Delivery':
                        return 'fal fa-hands-usd';

                    case 'Account':
                        return 'fal fa-piggy-bank';


                    default:
                        return 'fal fa-dollar-sign';
                }


            case('Transaction Amount'):
                return money($this->data['Payment '.$key], $this->data['Payment Currency Code']);
            case('Completed Date'):
            case('Cancelled Date'):
            case('Created Date'):
                return strftime(
                    "%a %e %b %Y %H:%M %Z",
                    strtotime($this->data['Payment '.$key].' +0:00')
                );
        }

        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        if (array_key_exists('Payment '.$key, $this->data)) {
            return $this->data['Payment '.$key];
        }

        return false;
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '')
    {
        if (is_string($value)) {
            $value = _trim($value);
        }


        switch ($field) {
            case 'Payment Transaction Amount':


                if ($value < ($this->data['Payment Transaction Amount Refunded'] + $this->data['Payment Transaction Amount Credited'])) {
                    $this->error = true;
                    $this->msg   = _("Payment amount can't be smaller than its refunds or credits");

                    return;
                }


                $this->update_field($field, $value, $options);

                $this->update_payment_parents();
                $this->fork_index_elastic_search();

                break;


            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if (isset($this->data[$field]) and $value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                }
        }
    }

    function update_payment_parents()
    {
        $order = get_object('Order', $this->data['Payment Order Key']);


        $order->update_totals();


        $invoice = get_object('Invoice', $this->data['Payment Invoice Key']);
        if ($invoice->id) {
            $invoice->update_payments_totals();
        }
        $account = get_object('Account', 1);
        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping',
            array(
                'type'        => 'payment_updated',
                'payment_key' => $this->id,
                'store_key'   => $order->get('Order Store Key'),
            ),
            $account->get('Account Code'),
            $this->db
        );
    }

    function delete()
    {
        if ($this->data['Payment Transaction Amount Refunded'] != 0 or $this->data['Payment Transaction Amount Credited'] != 0) {
            $this->error = true;
            $this->msg   = _("Payment can't be cancelled if it has refunds or credits");

            return;
        }

        $date = gmdate('Y-m-d H:i:s');
        $this->update(
            array(
                'Payment Transaction Status' => 'Cancelled',
                'Payment Cancelled Date'     => $date,
                'Payment Last Updated Date'  => $date,
            )

        );

        $parent_payment = get_object('Payment', $this->data['Payment Related Payment Key']);

        if ($this->data['Payment Type'] == 'Refund' or $this->data['Payment Type'] == 'Return') {
            if ($this->data['Payment Method'] == 'Account') {
                $parent_payment->update(array('Payment Transaction Amount Credited' => $parent_payment->get('Payment Transaction Amount Credited') + $this->data['Payment Transaction Amount']));
            } else {
                $parent_payment->update(array('Payment Transaction Amount Refunded' => $parent_payment->get('Payment Transaction Amount Refunded') + $this->data['Payment Transaction Amount']));
            }
        }

        if ($this->data['Payment Method'] == 'Account') {
            $customer = get_object('Customer', $this->data['Payment Customer Key']);

            $account = get_object('Account', 1);
            include_once 'utils/currency_functions.php';
            $exchange = currency_conversion(
                $this->db,
                $this->get('Payment Currency Code'),
                $account->get('Account Currency'),
                '- 1 minutes'
            );

            $sql = sprintf(
                'INSERT INTO `Credit Transaction Fact` 
                    (`Credit Transaction Date`,`Credit Transaction Amount`,`Credit Transaction Currency Code`,`Credit Transaction Currency Exchange Rate`,`Credit Transaction Customer Key`,`Credit Transaction Payment Key`,`Credit Transaction Type`) 
                    VALUES (%s,%.2f,%s,%f,%d,%d,%s) ',
                prepare_mysql($date),
                $this->data['Payment Transaction Amount'],
                prepare_mysql($this->get('Payment Currency Code')),
                $exchange,
                $this->get('Payment Customer Key'),
                $this->id,
                prepare_mysql('Cancel')


            );

            //print $sql;

            $this->db->exec($sql);


            $customer->update_account_balance();
            $customer->update_credit_account_running_balances();
        }


        $this->update_payment_parents();
        $this->fork_index_elastic_search('delete_elastic_index_object');
    }


    function get_formatted_time_lapse($key)
    {
        include_once 'utils/date_functions.php';

        return gettext_relative_time(
            gmdate('U') - gmdate(
                'U',
                strtotime($this->data['Payment '.$key].' +0:00')
            )
        );
    }

    function get_formatted_info()
    {
        $info = '';
        $this->load_payment_account();
        $this->load_payment_service_provider();
        switch ($this->data['Payment Transaction Status']) {
            case 'Pending':
                $info = sprintf(
                    "%s %s %s %s, %s %s",
                    _('A payment of'),
                    money(
                        $this->data['Payment Transaction Amount'],
                        $this->data['Payment Currency Code']
                    ),
                    _('using'),
                    $this->payment_service_provider->data['Payment Service Provider Name'],
                    _('payment service provider'),
                    _('is in process')

                );

                break;
            case 'Completed':

                if ($this->data['Payment Method'] == 'Account') {
                    $info = sprintf(
                        "%s %s",
                        money(
                            $this->data['Payment Transaction Amount'],
                            $this->data['Payment Currency Code']
                        ),
                        _('has been paid from the customer account')

                    );
                } else {
                    $info = sprintf(
                        "%s %s %s %s %s %s. %s: %s",
                        _('A payment of'),
                        money(
                            $this->data['Payment Transaction Amount'],
                            $this->data['Payment Currency Code']
                        ),
                        _('using'),
                        $this->payment_service_provider->data['Payment Service Provider Name'],
                        _('payment service provider'),
                        _('has been completed sucessfully'),
                        _('Reference'),
                        $this->data['Payment Transaction ID']

                    );
                }
                break;
            case 'Cancelled':
                $info = sprintf(
                    "%s %s %s %s %s %s",
                    _('A payment of'),
                    money(
                        $this->data['Payment Transaction Amount'],
                        $this->data['Payment Currency Code']
                    ),
                    _('using'),
                    $this->payment_service_provider->data['Payment Service Provider Name'],
                    _('payment service provider'),
                    _('has been cancelled')

                );

                break;
            case 'Error':
                $info = sprintf(
                    "%s %s %s %s %s %s",
                    _('A payment of'),
                    money(
                        $this->data['Payment Transaction Amount'],
                        $this->data['Payment Currency Code']
                    ),
                    _('using'),
                    $this->payment_service_provider->data['Payment Service Provider Name'],
                    _('payment service provider'),
                    _('has had an error')

                );

                break;
        }

        return $info;
    }

    function load_payment_account()
    {
        $this->payment_account = get_object('Payment_Account', $this->data['Payment Account Key']);
    }

    function load_payment_service_provider()
    {
        $this->payment_service_provider = new Payment_Service_Provider(
            $this->data['Payment Service Provider Key']
        );
    }

    function get_formatted_short_info()
    {
        $info = '';
        $this->load_payment_account();
        $this->load_payment_service_provider();
        switch ($this->data['Payment Transaction Status']) {
            case 'Pending':
                $info = sprintf(
                    "%s, %s",

                    $this->payment_service_provider->data['Payment Service Provider Name'],
                    _('payment in process')

                );

                break;
            case 'Completed':
                $info = sprintf(
                    "%s, %s, %s: ",

                    $this->payment_service_provider->data['Payment Service Provider Name'],
                    _('payment completed successfully'),
                    _('Reference'),
                    $this->data['Payment Transaction ID']

                );

                break;
            case 'Cancelled':
                $info = sprintf(
                    "%s, %s",

                    $this->payment_service_provider->data['Payment Service Provider Name'],
                    _('payment cancelled')

                );


                break;
            case 'Error':
                $info = sprintf(
                    "%s %s",

                    $this->payment_service_provider->data['Payment Service Provider Name'],
                    _('payment has had an error')

                );

                break;
        }

        return $info;
    }


}
