<?php
/*
  File: Customer.php

  This file contains the Customer Class

  About:
  Author: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0


*/
include_once 'class.Subject.php';
include_once 'class.Order.php';
//include_once 'class.Address.php';
include_once 'class.Attachment.php';

class Customer extends Subject {
    var $contact_data = false;
    var $ship_to = array();
    var $billing_to = array();
    var $fuzzy = false;
    var $tax_number_read = false;
    var $warning_messages = array();
    var $warning = false;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;

        $this->label         = _('Customer');
        $this->table_name    = 'Customer';
        $this->ignore_fields = array(
            'Customer Key',
            'Customer Has More Orders Than',
            'Customer Has More  Invoices Than',
            'Customer Has Better Balance Than',
            'Customer Is More Profiteable Than',
            'Customer Order More Frecuently Than',
            'Customer Older Than',
            'Customer Orders Position',
            'Customer Invoices Position',
            'Customer Balance Position',
            'Customer Profit Position',
            'Customer Order Interval',
            'Customer Order Interval STD',
            'Customer Orders Top Percentage',
            'Customer Invoices Top Percentage',
            'Customer Balance Top Percentage',
            'Customer Profits Top Percentage',
            'Customer First Order Date',
            'Customer Last Order Date'
        );


        $this->status_names = array(0 => 'new');

        if (is_numeric($arg1) and !$arg2) {
            $this->get_data('id', $arg1);

            return;
        }


        if ($arg1 == 'new') {
            $this->find($arg2, $arg3, 'create');

            return;
        }


        $this->get_data($arg1, $arg2, $arg3);


    }

    function get_data($tag, $id, $id2 = false) {
        if ($tag == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Customer Dimension` WHERE `Customer Key`=%s", prepare_mysql($id)
            );
        } elseif ($tag == 'email') {
            $sql = sprintf(
                "SELECT * FROM `Customer Dimension` WHERE `Customer Main Plain Email`=%s", prepare_mysql($id)
            );
        } elseif ($tag == 'old_id') {
            $sql = sprintf(
                "SELECT * FROM `Customer Dimension` WHERE `Customer Old ID`=%s AND `Customer Store Key`=%d", prepare_mysql($id), $id2

            );
        } elseif ($tag == 'all') {
            $this->find($id);

            return true;
        } else {
            return false;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Customer Key'];
        }


    }

    function find($raw_data, $address_raw_data, $options = '') {


        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {

                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
        }

        $create = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        if (!isset($raw_data['Customer Store Key']) or !preg_match('/^\d+$/i', $raw_data['Customer Store Key'])) {
            $this->error = true;
            $this->msg   = 'missing store key';

        }


        $sql = sprintf(
            'SELECT `Customer Key` FROM `Customer Dimension` WHERE `Customer Store Key`=%d AND `Customer Main Plain Email`=%s ', $raw_data['Customer Store Key'], prepare_mysql($raw_data['Customer Main Plain Email'])
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->error = true;
                $this->found = true;
                $this->msg   = _('Another customer with same email has been found');

                return;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($create) {

            $this->create($raw_data, $address_raw_data);
        }


    }

    function create($raw_data, $address_raw_data, $args = '') {

        /*
                $this->data = $this->base_data();
                foreach ($raw_data as $key => $value) {
                    if (array_key_exists($key, $this->data)) {
                        $this->data[$key] = _trim($value);
                    }
                }

        */
        $this->editor = $raw_data['editor'];
        unset($raw_data['editor']);
        //if ($this->data['Customer First Contacted Date'] == '') {
        //    $this->data['Customer First Contacted Date'] = gmdate('Y-m-d H:i:s');
        // }

        $raw_data['Customer First Contacted Date'] = gmdate('Y-m-d H:i:s');
        $raw_data['Customer Sticky Note']          = '';


        /*
        //todo remove this in the DB after migration
        unset($this->data['Customer Main Country Key']);
        unset($this->data['Customer Main Delivery Address Country Key']);
        unset($this->data['Customer Main Billing Address Country Key']);
        unset($this->data['Customer Last Ship To Key']);
        unset($this->data['Customer Active Ship To Records']);
        unset($this->data['Customer Total Ship To Records']);
        unset($this->data['Customer Last Billing To Key']);
        unset($this->data['Customer Active Billing To Records']);
        unset($this->data['Customer Total Billing To Records']);
        unset($this->data['Customer Company Key']);
*/


        $keys   = '';
        $values = '';
        foreach ($raw_data as $key => $value) {
            $keys .= ",`".$key."`";
            if (in_array(
                $key, array(
                        'Customer First Contacted Date',
                        'Customer Lost Date',
                        'Customer Last Invoiced Dispatched Date',
                        'Customer First Invoiced Order Date',
                        'Customer Last Invoiced Order Date',
                        'Customer Tax Number Validation Date',
                        'Customer Last Order Date',
                        'Customer First Order Date'
                    )
            )) {
                $values .= ','.prepare_mysql($value, true);
            } else {
                $values .= ','.prepare_mysql($value, false);
            }
        }
        $values = preg_replace('/^,/', '', $values);
        $keys   = preg_replace('/^,/', '', $keys);

        $sql = "insert into `Customer Dimension` ($keys) values ($values)";


        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();
            $this->get_data('id', $this->id);


            if ($this->data['Customer Company Name'] != '') {
                $customer_name = $this->data['Customer Company Name'];
            } else {
                $customer_name = $this->data['Customer Main Contact Name'];
            }
            $this->update_field('Customer Name', $customer_name, 'no_history');


            $this->update_address('Contact', $address_raw_data, 'no_history');
            $this->update_address('Invoice', $address_raw_data, 'no_history');
            $this->update_address('Delivery', $address_raw_data, 'no_history');


            $this->update(
                array(
                    'Customer Main Plain Mobile'    => $this->get('Customer Main Plain Mobile'),
                    'Customer Main Plain Telephone' => $this->get('Customer Main Plain Telephone'),
                    'Customer Main Plain FAX'       => $this->get('Customer Main Plain FAX'),
                ), 'no_history'

            );


            $history_data = array(
                'History Abstract' => sprintf(_('%s customer record created'), $this->get('Name')),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $this->new = true;


        } else {
            $this->error = true;
            $this->msg   = 'Error inserting customer record';
        }


        //$this->update_full_search();
        // $this->update_location_type();

    }

    function get($key, $arg1 = false) {


        if (!$this->id) {
            return false;
        }

        list($got, $result) = $this->get_subject_common($key, $arg1);
        if ($got) {
            return $result;
        }

        switch ($key) {

            case 'Delivery Address Link':

                if ($this->data['Customer Delivery Address Link'] == 'Billing') {
                    return _('Same as invoice address');
                } elseif ($this->data['Customer Delivery Address Link'] == 'None') {
                    return _('Unrelated to invoice address');
                } else {
                    return _('Unrelated to contact address');
                }

                break;
            case 'Fiscal Name':
            case 'Invoice Name':

                if ($this->data['Customer Invoice Address Organization'] != '') {
                    return $this->data['Customer Invoice Address Organization'];
                }
                if ($this->data['Customer Invoice Address Recipient'] != '') {
                    return $this->data['Customer Invoice Address Recipient'];
                }

                return $this->data['Customer Name'];

                break;
            case 'Tax Number':
                if ($this->data['Customer Tax Number'] != '') {
                    if ($this->data['Customer Tax Number Valid'] == 'Yes') {
                        return sprintf(
                            '<span class="ok">%s</span>', $this->data['Customer Tax Number']
                        );
                    } elseif ($this->data['Customer Tax Number Valid'] == 'Unknown') {
                        return sprintf(
                            '<span class="disabled">%s</span>', $this->data['Customer Tax Number']
                        );
                    } else {
                        return sprintf(
                            '<span class="error">%s</span>', $this->data['Customer Tax Number']
                        );
                    }
                }

                break;


            case('Tax Number Valid'):
                if ($this->data['Customer Tax Number'] != '') {

                    if ($this->data['Customer Tax Number Validation Date'] != '') {
                        $_tmp = gmdate("U") - gmdate(
                                "U", strtotime(
                                       $this->data['Customer Tax Number Validation Date'].' +0:00'
                                   )
                            );
                        if ($_tmp < 3600) {
                            $date = strftime(
                                "%e %b %Y %H:%M:%S %Z", strtotime(
                                                          $this->data['Customer Tax Number Validation Date'].' +0:00'
                                                      )
                            );

                        } elseif ($_tmp < 86400) {
                            $date = strftime(
                                "%e %b %Y %H:%M %Z", strtotime(
                                                       $this->data['Customer Tax Number Validation Date'].' +0:00'
                                                   )
                            );

                        } else {
                            $date = strftime(
                                "%e %b %Y", strtotime(
                                              $this->data['Customer Tax Number Validation Date'].' +0:00'
                                          )
                            );
                        }
                    } else {
                        $date = '';
                    }

                    $msg = $this->data['Customer Tax Number Validation Message'];

                    if ($this->data['Customer Tax Number Validation Source'] == 'Online') {
                        $source = '<i title=\''._('Validated online').'\' class=\'fa fa-globe\'></i>';


                    } elseif ($this->data['Customer Tax Number Validation Source'] == 'Manual') {
                        $source = '<i title=\''._('Set up manually').'\' class=\'far fa-hand-rock\'></i>';
                    } else {
                        $source = '';
                    }

                    $validation_data = trim($date.' '.$source.' '.$msg);
                    if ($validation_data != '') {
                        $validation_data = ' <span class=\'discreet\'>('.$validation_data.')</span>';
                    }

                    switch ($this->data['Customer Tax Number Valid']) {
                        case 'Unknown':
                            return _('Not validated').$validation_data;
                            break;
                        case 'Yes':
                            return _('Validated').$validation_data;
                            break;
                        case 'No':
                            return _('Not valid').$validation_data;
                        default:
                            return $this->data['Customer Tax Number Valid'].$validation_data;

                            break;
                    }
                }
                break;
            case('Tax Number Details Match'):
                switch ($this->data['Customer '.$key]) {
                    case 'Unknown':
                        return _('Unknown');
                        break;
                    case 'Yes':
                        return _('Yes');
                        break;
                    case 'No':
                        return _('No');
                    default:
                        return $this->data['Customer '.$key];

                        break;
                }

                break;
            case('Lost Date'):
            case('Last Order Date'):
            case('First Order Date'):
            case('First Contacted Date'):
            case('Last Order Date'):
            case('Tax Number Validation Date'):
                if ($this->data['Customer '.$key] == '') {
                    return '';
                }

                return '<span title="'.strftime(
                        "%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Customer '.$key]." +00:00")
                    ).'">'.strftime(
                        "%a %e %b %Y", strtotime($this->data['Customer '.$key]." +00:00")
                    ).'</span>';
                break;
            case('Orders'):
                return number($this->data['Customer Orders']);
                break;
            case('Notes'):
                $sql   = sprintf(
                    "SELECT count(*) AS total FROM  `Customer History Bridge`     WHERE `Customer Key`=%d AND `Type`='Notes'  ", $this->id
                );
                $notes = 0;

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $notes = $row['total'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                return number($notes);
                break;
            case('Send Newsletter'):
            case('Send Email Marketing'):
            case('Send Postal Marketing'):

                return $this->data['Customer '.$key] == 'Yes'
                    ? _('Yes')
                    : _(
                        'No'
                    );

                break;
            case("ID"):
            case("Formatted ID"):
                return $this->get_formatted_id();
            case("Sticky Note"):
                return nl2br($this->data['Customer Sticky Note']);
                break;
            case('Account Balance'):
            case 'Invoiced Net Amount':
                if (is_object($arg1)) {
                    $store = $arg1;
                } else {
                    $store = get_object('Store', $this->data['Customer Store Key']);
                }

                return money(
                    $this->data['Customer '.$key], $store->get('Store Currency Code')
                );
                break;
            case('Total Net Per Order'):
                if ($this->data['Customer Orders Invoiced'] > 0) {

                    if (is_object($arg1)) {
                        $store = $arg1;
                    } else {
                        $store = get_object('Store', $this->data['Customer Store Key']);
                    }


                    return money($this->data['Customer Invoiced Net Amount'] / $this->data['Customer Orders Invoiced'], $store->data['Store Currency Code']);
                } else {
                    return _('ND');
                }
                break;


            case('Order Interval'):
                $order_interval = $this->get('Customer Order Interval') / 24 / 3600;

                if ($order_interval > 10) {
                    $order_interval = round($order_interval / 7);
                    if ($order_interval == 1) {
                        $order_interval = _('week');
                    } else {
                        $order_interval = $order_interval.' '._('weeks');
                    }

                } else {
                    if ($order_interval == '') {
                        $order_interval = '';
                    } else {
                        $order_interval = round($order_interval).' '._('days');
                    }
                }

                return $order_interval;
                break;

            case('Tax Rate'):
                return $this->get_tax_rate();
                break;
            case('Tax Code'):
                return $this->data['Customer Tax Category Code'];
                break;
            case 'Web Login Password':
                return '<i class="fa fa-asterisk" aria-hidden="true"></i><i class="fa fa-asterisk" aria-hidden="true"></i><i class="fa fa-asterisk" aria-hidden="true"></i><i class="fa fa-asterisk" aria-hidden="true"></i><i class="fa fa-asterisk" aria-hidden="true"></i>
';
                break;
            case('Sales Representative'):
                if ($this->data['Customer Sales Representative Key'] != '') {

                    $sales_representative = get_object('Sales_Representative', $this->data['Customer Sales Representative Key']);
                    if ($sales_representative->id) {
                        return $sales_representative->staff->get('Name');
                    }
                } else {
                    return '<span class="very_discreet italic">'._('No account manager').'</span>';
                }
                break;

            case 'Level Type Icon':
                switch ($this->get('Customer Level Type')) {
                    case 'Partner':

                        return '<i class="fa fa-dove blue margin_right_5" title="'._('Partner').'" ></i>';

                        break;
                    case 'VIP':

                        return '<i class="fa fa-badge-check success margin_right_5" title="'._('VIP customer').'" ></i>';

                        break;
                    default:
                        return '';
                }
                break;
            default:


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Customer '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }


                if (preg_match(
                    '/^Customer Other Delivery Address (\d+)/i', $key, $matches
                )) {

                    $address_fields = $this->get_other_delivery_address_fields(
                        $matches[1]
                    );


                    return json_encode($address_fields);

                }

                if (preg_match(
                    '/^Other Delivery Address (\d+)/i', $key, $matches
                )) {


                    $customer_delivery_key = $matches[1];
                    $sql                   = sprintf(
                        "SELECT `Customer Other Delivery Address Formatted` FROM `Customer Other Delivery Address Dimension` WHERE `Customer Other Delivery Address Key`=%d ", $customer_delivery_key
                    );
                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            return $row['Customer Other Delivery Address Formatted'];
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }

                }

                if (preg_match('/^Poll Query (\d+)/i', $key, $matches)) {

                    $poll_key   = $matches[1];
                    $poll_query = get_object('Customer_Poll_Query', $poll_key);

                    list($answer_code, $answer_label, $answer_key) = $poll_query->get_answer($this->id);

                    return $answer_label;


                }

                if (preg_match('/^Customer Poll Query (\d+)/i', $key, $matches)) {

                    $poll_key   = $matches[1];
                    $poll_query = get_object('Customer_Poll_Query', $poll_key);

                    list($answer_code, $answer_label, $answer_key) = $poll_query->get_answer($this->id);

                    return $answer_code;


                }


        }


        return '';

    }

    function get_tax_rate() {
        $rate = 0;
        $sql  = sprintf(
            "SELECT `Tax Category Rate` FROM kbase.`Tax Category Dimension` WHERE `Tax Category Code`=%s", prepare_mysql($this->data['Customer Tax Category Code'])
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $rate = $row['Tax Category Rate'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $rate;
    }

    function get_other_delivery_address_fields($other_delivery_address_key) {

        $sql = sprintf(
            "SELECT * FROM `Customer Other Delivery Address Dimension` WHERE `Customer Other Delivery Address Key`=%d ", $other_delivery_address_key
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $address_fields = array(

                    'Address Recipient'            => $row['Customer Other Delivery Address Recipient'],
                    'Address Organization'         => $row['Customer Other Delivery Address Organization'],
                    'Address Line 1'               => $row['Customer Other Delivery Address Line 1'],
                    'Address Line 2'               => $row['Customer Other Delivery Address Line 2'],
                    'Address Sorting Code'         => $row['Customer Other Delivery Address Sorting Code'],
                    'Address Postal Code'          => $row['Customer Other Delivery Address Postal Code'],
                    'Address Dependent Locality'   => $row['Customer Other Delivery Address Dependent Locality'],
                    'Address Locality'             => $row['Customer Other Delivery Address Locality'],
                    'Address Administrative Area'  => $row['Customer Other Delivery Address Administrative Area'],
                    'Address Country 2 Alpha Code' => $row['Customer Other Delivery Address Country 2 Alpha Code'],


                );

                return $address_fields;


            } else {

                return false;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function update_full_search() {

        $store = new Store($this->data['Customer Store Key']);


        $address_plain      = strip_tags($this->get('Contact Address'));
        $first_full_search  = $this->data['Customer Name'].' '.$this->data['Customer Name'].' '.$address_plain.' '.$this->data['Customer Main Contact Name'].' '.$this->data['Customer Main Plain Email'];
        $second_full_search = '';


        $description = '';

        if ($this->data['Customer Company Name'] != '') {
            $name = '<b>'.$this->data['Customer Name'].'</b> (Id:'.$this->get_formatted_id().')<br/>'.$this->data['Customer Main Contact Name'];
        } else {
            $name = '<b>'.$this->data['Customer Name'].'</b> (Id:'.$this->get_formatted_id().')';

        }
        $name .= '<br/>'._('Orders').':<b>'.number(
                $this->data['Customer Orders']
            ).'</b>';


        $_address = $this->data['Customer Main Plain Email'];

        if ($this->data['Customer Preferred Contact Number Formatted Number'] != '') {
            $_address .= '<br/>T: '.$this->data['Customer Preferred Contact Number Formatted Number'];
        }
        $_address .= '<br/>'.$this->data['Customer Main Location'];
        if ($this->data['Customer Main Postal Code']) {
            $_address .= ', '.$this->data['Customer Main Postal Code'];
        }
        $_address = preg_replace('/^\<br\/\>/', '', $_address);

        $description = '<table ><tr style="border:none;"><td class="col1">'.$name.'</td><td class="col2">'.$_address.'</td></tr></table>';

        //$sql=sprintf("select `Search Full Text Key` from `Search Full Text Dimension` where `Store Key`=%d,`Subject`='Customer',`Subject Key`=%d",
        //
        //,$this->data['Customer Store Key']
        // ,$this->id
        //);


        $sql = sprintf(
            "INSERT INTO `Search Full Text Dimension`  (`Store Key`,`Subject`,`Subject Key`,`First Search Full Text`,`Second Search Full Text`,`Search Result Name`,`Search Result Description`,`Search Result Image`)
                     VALUES  (%s,%s,%d,%s,%s,%s,%s,%s) ON DUPLICATE KEY
                     UPDATE `First Search Full Text`=%s ,`Second Search Full Text`=%s ,`Search Result Name`=%s,`Search Result Description`=%s,`Search Result Image`=%s", $this->data['Customer Store Key'], prepare_mysql('Customer'), $this->id,
            prepare_mysql($first_full_search), prepare_mysql($second_full_search), prepare_mysql($this->data['Customer Name']), prepare_mysql($description), "''", prepare_mysql($first_full_search), prepare_mysql($second_full_search),
            prepare_mysql($this->data['Customer Name']), prepare_mysql($description), "''"
        );
        //print $sql;
        $this->db->exec($sql);
    }

    function update_location_type() {

        $store = new Store($this->data['Customer Store Key']);

        if ($this->data['Customer Contact Address Country 2 Alpha Code'] == $store->data['Store Home Country Code 2 Alpha'] or $this->data['Customer Contact Address Country 2 Alpha Code'] == 'XX') {
            $location_type = 'Domestic';
        } else {
            $location_type = 'Export';
        }

        $this->update(array('Customer Location Type' => $location_type));


    }

    function number_of_user_logins() {
        list($is_user, $row) = $this->is_user_customer($this->id);
        if ($is_user) {
            $sql = sprintf(
                "SELECT count(*) AS num FROM `User Log Dimension` WHERE `User Key`=%d", $row['User Key']
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    return $row['num'];
                } else {
                    return 0;
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


        } else {
            return 0;
        }
    }

    function is_user_customer($data) {
        $sql = sprintf(
            "SELECT * FROM `User Dimension` WHERE `User Parent Key`=%d AND `User Type`='Customer' ", $data
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                return array(
                    true,
                    $row
                );
            } else {
                return array(
                    false,
                    false
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }


    function create_order() {

        global $account;


        $order_data = array(

            'Order Original Data MIME Type' => 'application/aurora',
            'Order Type'                    => 'Order',
            'editor'                        => $this->editor,


        );


        $order_data['Order Class'] = 'InWebsite';


        $order_data['Order Customer Key']          = $this->id;
        $order_data['Order Customer Name']         = $this->data['Customer Name'];
        $order_data['Order Customer Contact Name'] = $this->data['Customer Main Contact Name'];
        $order_data['Order Registration Number']   = $this->data['Customer Registration Number'];

        $order_data['Order Tax Number']                    = $this->data['Customer Tax Number'];
        $order_data['Order Tax Number Valid']              = $this->data['Customer Tax Number Valid'];
        $order_data['Order Tax Number Validation Date']    = $this->data['Customer Tax Number Validation Date'];
        $order_data['Order Tax Number Validation Source']  = $this->data['Customer Tax Number Validation Source'];
        $order_data['Order Tax Number Details Match']      = $this->data['Customer Tax Number Details Match'];
        $order_data['Order Tax Number Registered Name']    = $this->data['Customer Tax Number Registered Name'];
        $order_data['Order Tax Number Registered Address'] = $this->data['Customer Tax Number Registered Address'];
        $order_data['Order Available Credit Amount']       = $this->data['Customer Account Balance'];
        $order_data['Order Sales Representative Key']       = $this->data['Customer Sales Representative Key'];


        $order_data['Order Customer Fiscal Name'] = $this->get('Fiscal Name');
        $order_data['Order Email']                = $this->data['Customer Main Plain Email'];
        $order_data['Order Telephone']            = $this->data['Customer Preferred Contact Number Formatted Number'];


        $order_data['Order Invoice Address Recipient']            = $this->data['Customer Invoice Address Recipient'];
        $order_data['Order Invoice Address Organization']         = $this->data['Customer Invoice Address Organization'];
        $order_data['Order Invoice Address Line 1']               = $this->data['Customer Invoice Address Line 1'];
        $order_data['Order Invoice Address Line 2']               = $this->data['Customer Invoice Address Line 2'];
        $order_data['Order Invoice Address Sorting Code']         = $this->data['Customer Invoice Address Sorting Code'];
        $order_data['Order Invoice Address Postal Code']          = $this->data['Customer Invoice Address Postal Code'];
        $order_data['Order Invoice Address Dependent Locality']   = $this->data['Customer Invoice Address Dependent Locality'];
        $order_data['Order Invoice Address Locality']             = $this->data['Customer Invoice Address Locality'];
        $order_data['Order Invoice Address Administrative Area']  = $this->data['Customer Invoice Address Administrative Area'];
        $order_data['Order Invoice Address Country 2 Alpha Code'] = $this->data['Customer Invoice Address Country 2 Alpha Code'];
        $order_data['Order Invoice Address Checksum']             = $this->data['Customer Invoice Address Recipient'];
        $order_data['Order Invoice Address Formatted']            = $this->data['Customer Invoice Address Formatted'];
        $order_data['Order Invoice Address Postal Label']         = $this->data['Customer Invoice Address Postal Label'];


        $order_data['Order Delivery Address Recipient']            = $this->data['Customer Delivery Address Recipient'];
        $order_data['Order Delivery Address Organization']         = $this->data['Customer Delivery Address Organization'];
        $order_data['Order Delivery Address Line 1']               = $this->data['Customer Delivery Address Line 1'];
        $order_data['Order Delivery Address Line 2']               = $this->data['Customer Delivery Address Line 2'];
        $order_data['Order Delivery Address Sorting Code']         = $this->data['Customer Delivery Address Sorting Code'];
        $order_data['Order Delivery Address Postal Code']          = $this->data['Customer Delivery Address Postal Code'];
        $order_data['Order Delivery Address Dependent Locality']   = $this->data['Customer Delivery Address Dependent Locality'];
        $order_data['Order Delivery Address Locality']             = $this->data['Customer Delivery Address Locality'];
        $order_data['Order Delivery Address Administrative Area']  = $this->data['Customer Delivery Address Administrative Area'];
        $order_data['Order Delivery Address Country 2 Alpha Code'] = $this->data['Customer Delivery Address Country 2 Alpha Code'];
        $order_data['Order Delivery Address Checksum']             = $this->data['Customer Delivery Address Recipient'];
        $order_data['Order Delivery Address Formatted']            = $this->data['Customer Delivery Address Formatted'];
        $order_data['Order Delivery Address Postal Label']         = $this->data['Customer Delivery Address Postal Label'];


        $order_data['Order Customer Order Number'] = $this->get_number_of_orders() + 1;

        $store = get_object('Store', $this->get('Customer Store Key'));

        $order_data['Order Store Key']                = $store->id;
        $order_data['Order Currency']                 = $store->get('Store Currency Code');
        $order_data['Order Show in Warehouse Orders'] = $store->get('Store Show in Warehouse Orders');
        $order_data['public_id_format']               = $store->get('Store Order Public ID Format');


        $order = new Order('new', $order_data);


        if ($order->error) {
            $this->error = true;
            $this->msg   = $order->msg;

            return $order;
        }


        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'        => 'order_created',
            'subject_key' => $order->id,
            'editor'      => $order->editor
        ), $account->get('Account Code'), $this->db
        );

        return $order;

    }

    function get_number_of_orders() {
        $sql    = sprintf(
            "SELECT count(*) AS number FROM `Order Dimension` WHERE `Order Customer Key`=%d ", $this->id
        );
        $number = 0;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number = $row['number'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $number;


    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (is_string($value)) {
            $value = _trim($value);
        }


        if ($this->update_subject_field_switcher($field, $value, $options, $metadata)) {
            return;
        }


        switch ($field) {


            case 'Customer Web Login Password':


                $website_user = get_object('Website_User', $this->get('Customer Website User Key'));


                if ($website_user->id) {
                    $website_user->editor = $this->editor;

                    $website_user->update(array('Website User Password' => hash('sha256', $value)), 'no_history');

                    $website_user->update(array('Website User Password Hash' => password_hash(hash('sha256', $value), PASSWORD_DEFAULT, array('cost' => 12))), 'no_history');
                }

                // todo: remove after migration finished


                $sql = sprintf('UPDATE `User Dimension` SET `User Password`=%s WHERE `User Type`="Customer" AND `User Parent Key`=%d  ', prepare_mysql(hash('sha256', $value)), $this->id);
                $this->db->exec($sql);


                break;
            case 'Customer Contact Address':


                $this->update_address('Contact', json_decode($value, true), $options);
                /*

                                if(  empty($metadata['no_propagate_addresses'])  ) {


                                    if ($this->data['Customer Billing Address Link'] == 'Contact') {

                                        $this->update_field_switcher('Customer Invoice Address', $value, $options, array('no_propagate_addresses'=>true));

                                        if ($this->data['Customer Delivery Address Link'] == 'Billing') {
                                            $this->update_field_switcher('Customer Delivery Address', $value, $options, array('no_propagate_addresses'=>true));

                                        }


                                    }
                                    if ($this->data['Customer Delivery Address Link'] == 'Contact') {

                                        $this->update_field_switcher('Customer Delivery Address', $value, $options, array('no_propagate_addresses'=>true));
                                    }

                                }


                                $this->update_metadata = array(

                                    'class_html'  => array(
                                        'Contact_Address'      => $this->get('Contact Address')


                                    )
                                );
                */
                break;


            case 'Customer Invoice Address':

                $this->update_address('Invoice', json_decode($value, true), $options);


                //print_r(json_decode($value, true));
                /*
                                if(  empty($metadata['no_propagate_addresses'])  ) {


                                    if ($this->data['Customer Billing Address Link'] == 'Contact') {

                                        $this->update_field_switcher('Customer Contact Address', $value, $options, array('no_propagate_addresses'=>true));

                                        if ($this->data['Customer Delivery Address Link'] == 'Contact') {
                                            $this->update_field_switcher('Customer Delivery Address', $value, $options, array('no_propagate_addresses'=>true));

                                        }


                                    }
                                    if ($this->data['Customer Delivery Address Link'] == 'Billing') {



                                        $this->update_field_switcher('Customer Delivery Address', $value, $options, array('no_propagate_addresses'=>true));
                                    }

                                }

                                //'InBasket','InProcess','InWarehouse','PackedDone','Approved','Dispatched','Cancelled'

                                if(  empty($metadata['no_propagate_orders'])  ) {
                                    $sql = sprintf('SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` in ("Basket")   AND `Order Customer Key`=%d ', $this->id);
                                    if ($result = $this->db->query($sql)) {
                                        foreach ($result as $row) {
                                            $order = get_object('Order', $row['Order Key']);


                                            $order->update(array('Order Invoice Address' => $value), $options, array('no_propagate_customer' => true));
                                        }
                                    } else {
                                        print_r($error_info = $this->db->errorInfo());
                                        print "$sql\n";
                                        exit;
                                    }
                                }
                */


                $this->update_metadata = array(

                    'class_html' => array(
                        'Contact_Address' => $this->get('Contact Address')


                    )
                );


                break;
            case 'Customer Delivery Address':


                $this->update_address('Delivery', json_decode($value, true), $options);


                break;
            case 'new delivery address':
                $this->add_other_delivery_address(json_decode($value, true));

                break;


            case('Customer Tax Number'):
                $this->update_tax_number($value);


                $sql = sprintf('SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` IN (\'InBasket\',\'InProcess\',\'InWarehouse\',\'PackedDone\')  AND `Order Customer Key`=%d ', $this->id);
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $order = get_object('Order', $row['Order Key']);
                        $order->update_tax_number($value);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                break;
            case('Customer Tax Number Valid'):
                $this->update_tax_number_valid($value);


                $sql = sprintf('SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` IN (\'InBasket\',\'InProcess\',\'InWarehouse\',\'PackedDone\')  AND `Order Customer Key`=%d ', $this->id);
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $order = get_object('Order', $row['Order Key']);
                        $order->update_tax_number_valid($value);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                break;

            case('Customer Delivery Address Link'):
                $this->update_field($field, $value, $options);


                $this->other_fields_updated = array(
                    'Customer_Delivery_Address' => array(
                        'field'           => 'Customer_Delivery_Address',
                        'id'              => 'Customer_Delivery_Address',
                        'edit'            => 'address',
                        'render'          => ($this->get('Customer Delivery Address Link') != 'None' ? false : true),
                        'value'           => htmlspecialchars($this->get('Customer Delivery Address')),
                        'formatted_value' => $this->get('Delivery Address'),
                        'label'           => ucfirst($this->get_field_label('Customer Delivery Address')),

                    ),


                );


                break;

            case('Customer Sticky Note'):
                $this->update_field_switcher('Sticky Note', $value);
                break;
            case('Sticky Note'):
                $this->update_field('Customer '.$field, $value, 'no_null');
                $this->new_value = html_entity_decode($this->new_value);
                break;
            case('Note'):
                $this->add_note($value);
                break;
            case('Attach'):
                $this->add_attach($value);
                break;

            case 'Customer Level Type Partner':

                $old_value = $this->data['Customer Level Type'];
                if ($value == 'Yes') {
                    $value = 'Partner';
                } else {
                    $value = 'Normal';

                }
                $this->fast_update(
                    array(
                        'Customer Level Type' => $value
                    )
                );

                if ($value == 'Normal') {
                    $this->fast_update(
                        array(
                            'Customer Sales Representative Key' => ''
                        )
                    );
                }


                $this->update_level_type($old_value);


                $this->update_metadata = array(

                    'class_html' => array(
                        'Customer_Level_Type_Icon' => $this->get('Level Type Icon'),
                        'Sales_Representative'     => $this->get('Sales Representative')


                    )
                );

                if ($this->get('Customer Sales Representative Key')) {
                    $this->update_metadata['show'] = array('Customer_Sales_Representative_tr');
                } else {
                    $this->update_metadata['hide'] = array('Customer_Sales_Representative_tr');

                }


                $options_sales_representative = array();
                $sql                          = sprintf(
                    'SELECT `Staff Name`,S.`Staff Key`,`Staff Alias` FROM `Staff Dimension` S LEFT JOIN `User Dimension` U ON (S.`Staff Key`=U.`User Parent Key`) LEFT JOIN `User Group User Bridge` B ON (U.`User Key`=B.`User Key`) WHERE  `User Type` in  ("Staff","Contractor")  and `User Group Key`=2     and `Staff Currently Working`="Yes"  group by S.`Staff Key` order by `Staff Name`  '
                );

                foreach ($this->db->query($sql) as $row) {
                    $options_sales_representative[$row['Staff Key']] = array(
                        'label'    => $row['Staff Alias'],
                        'label2'   => $row['Staff Name'].' ('.sprintf('%03d', $row['Staff Key']).')',
                        'selected' => false
                    );
                }

                $_options_sales_representative = '';
                foreach ($options_sales_representative as $_key => $_option) {
                    $_options_sales_representative .= sprintf(
                        '  <li id="Customer_Sales_Representative_option_%d" label="%s"
                                                    value="%d" is_selected="%s"
                                                    onclick="select_option_multiple_choices(\'Customer_Sales_Representative\',\'%d\',\'%s\' )">
                                                    <i class="far fa-fw checkbox %s"></i> %s
                                                    <i class="fa fa-circle fw current_mark %s"></i>
                                                </li>', $_key, $_option['label'], $_key, $_option['selected'], $_key, $_option['label'], ($_option['selected'] ? 'fa-check-square' : 'fa-square'), $_option['label'], ($_option['selected'] ? 'current' : '')

                    );
                }

                $this->other_fields_updated = array(
                    'Part_Unit_Price' => array(
                        'field'           => 'Customer_Sales_Representative',
                        'render'          => ($this->get('Customer Level Type') == 'Partner' ? false : true),
                        'value'           => $this->get('Customer Sales Representative'),
                        'formatted_value' => $this->get('Sales Representative'),
                        'options'         => $_options_sales_representative

                    )
                );


                break;

            case 'Customer Sales Representative':

                if ($value == 0) {
                    $this->fast_update(array('Customer Sales Representative Key' => ''));

                } else {
                    include_once('class.Sales_Representative.php');
                    $sales_representative = new Sales_Representative(
                        'find', array(
                                  'Sales Representative Staff Key' => $value,
                                  'editor'                         => $this->editor
                              )
                    );


                    $this->fast_update(
                        array(
                            'Customer Sales Representative Key' => $sales_representative->id
                        )
                    );


                }


                $this->update_level_type();

                $this->update_metadata = array(

                    'class_html' => array(
                        'Customer_Level_Type_Icon' => $this->get('Level Type Icon'),
                        'Sales_Representative'     => $this->get('Sales Representative')


                    )
                );

                if ($this->get('Customer Sales Representative Key')) {
                    $this->update_metadata['show'] = array('Customer_Sales_Representative_tr');
                } else {
                    $this->update_metadata['hide'] = array('Customer_Sales_Representative_tr');

                }


                $options_sales_representative = array();
                $sql                          = sprintf(
                    'SELECT `Staff Name`,S.`Staff Key`,`Staff Alias` FROM `Staff Dimension` S LEFT JOIN `User Dimension` U ON (S.`Staff Key`=U.`User Parent Key`) LEFT JOIN `User Group User Bridge` B ON (U.`User Key`=B.`User Key`) WHERE  `User Type` in  ("Staff","Contractor")  and `User Group Key`=2     and `Staff Currently Working`="Yes"  group by S.`Staff Key` order by `Staff Name`  '
                );

                if ($this->get('Customer Sales Representative Key')) {
                    $options_sales_representative[0] = array(
                        'label'    => _('Remove account manager'),
                        'label2'   => _('Remove account manager'),
                        'selected' => false
                    );
                }


                foreach ($this->db->query($sql) as $row) {
                    $options_sales_representative[$row['Staff Key']] = array(
                        'label'    => $row['Staff Alias'],
                        'label2'   => $row['Staff Name'].' ('.sprintf('%03d', $row['Staff Key']).')',
                        'selected' => false
                    );
                }

                $_options_sales_representative = '';
                foreach ($options_sales_representative as $_key => $_option) {
                    $_options_sales_representative .= sprintf(
                        '  <li id="Customer_Sales_Representative_option_%d" label="%s"
                                                    value="%d" is_selected="%s"
                                                    onclick="select_option_multiple_choices(\'Customer_Sales_Representative\',\'%d\',\'%s\' )">
                                                    <i class="far fa-fw checkbox %s"></i> %s
                                                    <i class="fa fa-circle fw current_mark %s"></i>
                                                </li>', $_key, $_option['label'], $_key, $_option['selected'], $_key, $_option['label'], ($_option['selected'] ? 'fa-check-square' : 'fa-square'), $_option['label'], ($_option['selected'] ? 'current' : '')

                    );
                }


                $this->other_fields_updated = array(
                    'Part_Unit_Price' => array(
                        'field'           => 'Customer_Sales_Representative',
                        'render'          => ($this->get('Customer Level Type') == 'Partner' ? false : true),
                        'value'           => $this->get('Customer Sales Representative'),
                        'formatted_value' => $this->get('Sales Representative'),
                        'options'         => $_options_sales_representative,

                    )
                );


                break;


            default:


                if (preg_match('/^custom_field_/i', $field)) {
                    //$field=preg_replace('/^custom_field_/','',$field);
                    $this->update_field($field, $value, $options);


                    return;
                }


                if (preg_match('/^Customer Other Delivery Address (\d+)/i', $field, $matches)) {

                    $customer_delivery_address_key = $matches[1];
                    $this->update_other_delivery_address(
                        $customer_delivery_address_key, $field, json_decode($value, true), $options
                    );

                    return;
                }

                if (preg_match('/^Customer Poll Query (\d+)/i', $field, $matches)) {

                    $poll_key = $matches[1];
                    $this->update_poll_answer($poll_key, $value, $options);

                    return;
                }


                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                }
        }
    }

    function add_other_delivery_address($fields, $options = '') {


        include_once 'utils/get_addressing.php';

        $checksum = md5(json_encode($fields));
        if ($checksum == $this->get('Customer Delivery Address Checksum')) {
            $this->error = true;
            $this->msg   = _('Duplicated address');

            return;
        }

        $sql = sprintf(
            'SELECT `Customer Other Delivery Address Checksum` FROM `Customer Other Delivery Address Dimension` WHERE `Customer Other Delivery Address Customer Key`=%d', $this->id
        );
        if ($result = $this->db->query($sql)) {


            foreach ($result as $row) {
                if ($checksum == $row['Customer Other Delivery Address Checksum']) {
                    $this->error = true;
                    $this->msg   = _('Duplicated address');

                    return;
                }


            }


        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $store = new Store($this->get('Store Key'));


        list(
            $address, $formatter, $postal_label_formatter
            ) = get_address_formatter(
            $store->get('Store Home Country Code 2 Alpha'), $store->get('Store Locale')
        );


        $address = $address->withFamilyName($fields['Address Recipient'])->withOrganization($fields['Address Organization'])->withAddressLine1($fields['Address Line 1'])->withAddressLine2(
            $fields['Address Line 2']
        )->withSortingCode($fields['Address Sorting Code'])->withPostalCode($fields['Address Postal Code'])->withDependentLocality($fields['Address Dependent Locality'])->withLocality(
            $fields['Address Locality']
        )->withAdministrativeArea($fields['Address Administrative Area'])->withCountryCode($fields['Address Country 2 Alpha Code']);

        $xhtml_address = $formatter->format($address);
        $xhtml_address = preg_replace('/<br>\s/', "\n", $xhtml_address);
        $xhtml_address = preg_replace(
            '/class="recipient"/', 'class="recipient fn"', $xhtml_address
        );
        $xhtml_address = preg_replace(
            '/class="organization"/', 'class="organization org"', $xhtml_address
        );
        $xhtml_address = preg_replace(
            '/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address
        );
        $xhtml_address = preg_replace(
            '/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address
        );
        $xhtml_address = preg_replace(
            '/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address
        );
        $xhtml_address = preg_replace(
            '/class="country"/', 'class="country country-name"', $xhtml_address
        );


        $sql = sprintf(
            'INSERT INTO `Customer Other Delivery Address Dimension` (
        `Customer Other Delivery Address Store Key`,
        `Customer Other Delivery Address Customer Key`,
        `Customer Other Delivery Address Recipient`,
        `Customer Other Delivery Address Organization`,
        `Customer Other Delivery Address Line 1`,
        `Customer Other Delivery Address Line 2`,
        `Customer Other Delivery Address Sorting Code`,
        `Customer Other Delivery Address Postal Code`,
        `Customer Other Delivery Address Dependent Locality`,
        `Customer Other Delivery Address Locality`,
        `Customer Other Delivery Address Administrative Area`,
        `Customer Other Delivery Address Country 2 Alpha Code`,
         `Customer Other Delivery Address Checksum`,
        `Customer Other Delivery Address Formatted`,
        `Customer Other Delivery Address Postal Label`

        ) VALUES (%d,%d,
        %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s
        ) ', $this->get('Store Key'), $this->id, prepare_mysql($fields['Address Recipient'], false), prepare_mysql($fields['Address Organization'], false), prepare_mysql($fields['Address Line 1'], false), prepare_mysql($fields['Address Line 2'], false),
            prepare_mysql($fields['Address Sorting Code'], false), prepare_mysql($fields['Address Postal Code'], false), prepare_mysql($fields['Address Dependent Locality'], false), prepare_mysql($fields['Address Locality'], false),
            prepare_mysql($fields['Address Administrative Area'], false), prepare_mysql($fields['Address Country 2 Alpha Code'], false), prepare_mysql($checksum), prepare_mysql($xhtml_address), prepare_mysql($postal_label_formatter->format($address))
        );

        $prep = $this->db->prepare($sql);


        try {
            $prep->execute();

            $inserted_key = $this->db->lastInsertId();

            //print $sql;
            if ($inserted_key) {

                $this->field_created = true;


                $this->add_changelog_record(
                    _("delivery address"), '', $this->get("Other Delivery Address $inserted_key"), '', $this->table_name, $this->id, 'added'
                );


                $this->new_fields_info = array(
                    array(
                        'clone_from'      => 'Customer_Other_Delivery_Address',
                        'field'           => 'Customer_Other_Delivery_Address_'.$inserted_key,
                        'render'          => true,
                        'edit'            => 'address',
                        'value'           => $this->get(
                            'Customer Other Delivery Address '.$inserted_key
                        ),
                        'formatted_value' => $this->get(
                            'Other Delivery Address '.$inserted_key
                        ),
                        'label'           => '',


                    )
                );

            } else {
                $this->error = true;

                $this->msg = _('Duplicated address').' (1)';
            }

        } catch (PDOException $e) {
            $this->error = true;

            if ($e->errorInfo[0] == '23000' && $e->errorInfo[1] == '1062') {
                $this->msg = _('Duplicated address').' (2)';
            } else {

                $this->msg = $e->getMessage();
            }

        }


    }

    function update_tax_number($value) {

        include_once 'utils/validate_tax_number.php';

        $this->update_field('Customer Tax Number', $value);


        if ($this->updated) {

            $tax_validation_data = validate_tax_number($this->data['Customer Tax Number'], $this->data['Customer Invoice Address Country 2 Alpha Code']);

            $this->update(
                array(
                    'Customer Tax Number Valid'              => $tax_validation_data['Tax Number Valid'],
                    'Customer Tax Number Details Match'      => $tax_validation_data['Tax Number Details Match'],
                    'Customer Tax Number Validation Date'    => $tax_validation_data['Tax Number Validation Date'],
                    'Customer Tax Number Validation Source'  => 'Online',
                    'Customer Tax Number Validation Message' => 'B: '.$tax_validation_data['Tax Number Validation Message'],
                ), 'no_history'
            );


            $this->new_value = $value;


        }

        $this->other_fields_updated = array(
            'Customer_Tax_Number_Valid' => array(
                'field'           => 'Customer_Tax_Number_Valid',
                'render'          => ($this->get('Customer Tax Number') == '' ? false : true),
                'value'           => $this->get('Customer Tax Number Valid'),
                'formatted_value' => $this->get('Tax Number Valid'),


            )
        );


    }

    function update_tax_number_valid($value) {

        include_once 'utils/validate_tax_number.php';


        if ($value == 'Auto') {


            $tax_validation_data = validate_tax_number(
                $this->data['Customer Tax Number'], $this->data['Customer Invoice Address Country 2 Alpha Code']
            );

            $this->update(
                array(
                    'Customer Tax Number Valid' => $tax_validation_data['Tax Number Valid'],

                    'Customer Tax Number Details Match'      => $tax_validation_data['Tax Number Details Match'],
                    'Customer Tax Number Validation Date'    => ($tax_validation_data['Tax Number Validation Date'] == '' ? gmdate('Y-m-d H:i:s') : $tax_validation_data['Tax Number Validation Date']),
                    'Customer Tax Number Validation Source'  => 'Online',
                    'Customer Tax Number Validation Message' => $tax_validation_data['Tax Number Validation Message'],
                ), 'no_history'
            );

        } else {


            $this->update(
                array(
                    'Customer Tax Number Details Match'      => 'Unknown',
                    'Customer Tax Number Validation Date'    => $this->editor['Date'],
                    'Customer Tax Number Validation Source'  => 'Staff',
                    'Customer Tax Number Validation Message' => $this->editor['Author Name'],
                ), 'no_history'
            );
            $this->update_field('Customer Tax Number Valid', $value);
        }


        // print_r($this->data);

        $this->other_fields_updated = array(
            'Customer_Tax_Number' => array(
                'field'           => 'Customer_Tax_Number',
                'render'          => true,
                'value'           => $this->get('Customer Tax Number'),
                'formatted_value' => $this->get('Tax Number'),


            )
        );


    }

    function get_field_label($field) {


        switch ($field) {

            case 'Customer Delivery Address Link':
                $label = _('delivery address link');
                break;
            case 'Customer Billing Address Link':
                $label = _('invoice address link');
                break;
            case 'Customer Registration Number':
                $label = _('registration number');
                break;
            case 'Customer Tax Number':
                $label = _('tax number');
                break;
            case 'Customer Tax Number Valid':
                $label = _('tax number validity');
                break;
            case 'Customer Company Name':
                $label = _('company name');
                break;
            case 'Customer Main Contact Name':
                $label = _('contact name');
                break;
            case 'Customer Main Plain Email':
                $label = _('email');
                break;
            case 'Customer Main Email':
                $label = _('main email');
                break;
            case 'Customer Other Email':
                $label = _('other email');
                break;
            case 'Customer Main Plain Telephone':
            case 'Customer Main XHTML Telephone':
                $label = _('telephone');
                break;
            case 'Customer Main Plain Mobile':
            case 'Customer Main XHTML Mobile':
                $label = _('mobile');
                break;
            case 'Customer Main Plain FAX':
            case 'Customer Main XHTML Fax':
                $label = _('fax');
                break;
            case 'Customer Other Telephone':
                $label = _('other telephone');
                break;
            case 'Customer Preferred Contact Number':
                $label = _('main contact number');
                break;
            case 'Customer Fiscal Name':
                $label = _('fiscal name');
                break;

            case 'Customer Contact Address':
                $label = _('contact address');
                break;

            case 'Customer Invoice Address':
                $label = _('invoice address');
                break;
            case 'Customer Delivery Address':
                $label = _('delivery address');
                break;
            case 'Customer Other Delivery Address':
                $label = _('other delivery address');
                break;
            case 'Customer Send Email Marketing':
                $label = _('subscription to email marketing');
                break;
            case 'Customer Send Postal Marketing':
                $label = _('subscription postal marketing');
                break;
            case 'Customer Send Newsletter':
                $label = _('subscription to newsletter');
                break;
            case 'Customer Website':
                $label = _('website');
                break;
            default:
                $label = $field;

        }

        return $label;

    }

    function update_level_type($old_value = '') {

        if (!$old_value) {
            $old_value = $this->data['Customer Level Type'];
        }


        if ($this->data['Customer Level Type'] != 'Partner') {


            if ($this->data['Customer Sales Representative Key'] != '') {
                $value = 'VIP';
            } else {
                $value = 'Normal';
            }

            $this->fast_update(
                array(
                    'Customer Level Type' => $value
                )
            );
            $this->data['Customer Level Type'] = $value;


        }

        if ($old_value != $this->data['Customer Level Type']) {

            switch ($this->data['Customer Level Type']) {
                case 'Partner':
                    $history_data = array(
                        'History Abstract' => sprintf(_('Customer set up as partner')),
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );
                    break;
                case 'VIP':

                    $sales_representative = get_object('Sales_Representative', $this->data['Customer Sales Representative Key']);

                    $history_data = array(
                        'History Abstract' => sprintf(_('Customer set up as VIP with %s as account manager'), $sales_representative->staff->get('Name')),
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );
                    break;
                case 'Normal':

                    if ($old_value == 'Partner') {
                        $history_data = array(
                            'History Abstract' => sprintf(_('Customer is not longer set up as partner')),
                            'History Details'  => '',
                            'Action'           => 'edited'
                        );
                    } elseif ($old_value == 'Partner') {
                        $history_data = array(
                            'History Abstract' => sprintf(_('Customer is not longer a VIP')),
                            'History Details'  => '',
                            'Action'           => 'edited'
                        );
                    } else {
                        $history_data = array(
                            'History Abstract' => sprintf(_('Set up as normal customer')),
                            'History Details'  => '',
                            'Action'           => 'edited'
                        );
                    }


            }


            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


        }


    }

    function update_other_delivery_address($customer_delivery_address_key, $field, $fields, $options = '') {


        $sql = sprintf(
            "SELECT * FROM `Customer Other Delivery Address Dimension` WHERE `Customer Other Delivery Address Key`=%d ", $customer_delivery_address_key
        );
        if ($result = $this->db->query($sql)) {
            if ($address_data = $result->fetch()) {


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        //print_r($address_data);


        $address_fields           = array();
        $updated_fields_number    = 0;
        $updated_recipient_fields = false;
        $updated_address_fields   = false;
        foreach ($fields as $field => $value) {


            $sql = sprintf(
                'UPDATE `Customer Other Delivery Address Dimension` SET `%s`=%s WHERE `Customer Other Delivery Address Key`=%d ', addslashes('Customer Other Delivery '.$field), prepare_mysql($value, true), $customer_delivery_address_key
            );

            $update_op = $this->db->prepare($sql);
            $update_op->execute();
            $affected = $update_op->rowCount();


            // print "$sql\n";

            if ($affected > 0) {


                $updated_fields_number++;
                if ($field == 'Address Recipient' or $field == 'Address Organization') {
                    $updated_recipient_fields = true;
                } else {
                    $updated_address_fields = true;
                }
            }
        }


        if ($updated_fields_number > 0) {
            $this->updated = true;
        }


        if ($this->updated or true) {

            include_once 'utils/get_addressing.php';


            $address_fields = $this->get_other_delivery_address_fields(
                $customer_delivery_address_key
            );


            $new_checksum = md5(json_encode($address_fields));

            $sql = sprintf(
                'UPDATE `Customer Other Delivery Address Dimension` SET `Customer Other Delivery Address Checksum`=%s WHERE `Customer Other Delivery Address Key`=%d ', prepare_mysql($new_checksum, true), $customer_delivery_address_key
            );
            $this->db->exec($sql);

            $store = new Store($this->get('Store Key'));


            list(
                $address, $formatter, $postal_label_formatter
                ) = get_address_formatter(
                $store->get('Store Home Country Code 2 Alpha'), $store->get('Store Locale')
            );


            $address = $address->withFamilyName($address_fields['Address Recipient'])->withOrganization($address_fields['Address Organization'])->withAddressLine1($address_fields['Address Line 1'])->withAddressLine2($address_fields['Address Line 2'])->withSortingCode(
                $address_fields['Address Sorting Code']
            )->withPostalCode($address_fields['Address Postal Code'])->withDependentLocality(
                $address_fields['Address Dependent Locality']
            )->withLocality($address_fields['Address Locality'])->withAdministrativeArea(
                $address_fields['Address Administrative Area']
            )->withCountryCode(
                $address_fields['Address Country 2 Alpha Code']
            );

            $xhtml_address = $formatter->format($address);
            $xhtml_address = preg_replace('/<br>\s/', "\n", $xhtml_address);
            $xhtml_address = preg_replace(
                '/class="recipient"/', 'class="recipient fn"', $xhtml_address
            );
            $xhtml_address = preg_replace(
                '/class="organization"/', 'class="organization org"', $xhtml_address
            );
            $xhtml_address = preg_replace(
                '/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address
            );
            $xhtml_address = preg_replace(
                '/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address
            );
            $xhtml_address = preg_replace(
                '/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address
            );
            $xhtml_address = preg_replace(
                '/class="country"/', 'class="country country-name"', $xhtml_address
            );


            $sql = sprintf(
                'UPDATE `Customer Other Delivery Address Dimension` SET `Customer Other Delivery Address Formatted`=%s WHERE `Customer Other Delivery Address Key`=%d ', prepare_mysql($xhtml_address, true), $customer_delivery_address_key
            );
            $this->db->exec($sql);

            $sql = sprintf(
                'UPDATE `Customer Other Delivery Address Dimension` SET `Customer Other Delivery Address Postal Label`=%s WHERE `Customer Other Delivery Address Key`=%d ', prepare_mysql($postal_label_formatter->format($address), true), $customer_delivery_address_key
            );
            $this->db->exec($sql);


        }


    }

    function update_poll_answer($poll_key, $value, $options) {

        $poll = get_object('Customer_Poll_Query', $poll_key);
        $poll->add_customer($this, $value, $options);


    }

    function update_custom_fields($id, $value) {
        $this->update(array($id => $value));
    }

    function get_last_order() {
        $order_key = 0;
        $sql       = sprintf(
            "SELECT `Order Key` FROM `Order Dimension` WHERE `Order Customer Key`=%d ORDER BY `Order Date` DESC  ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $order_key = $row['Order Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $order_key;
    }

    function add_customer_history($history_data, $force_save = true, $deleteable = 'No', $type = 'Changes') {

        return $this->add_subject_history(
            $history_data, $force_save, $deleteable, $type
        );
    }

    function get_other_delivery_addresses_data() {
        $sql = sprintf(
            "SELECT `Customer Other Delivery Address Key`,`Customer Other Delivery Address Formatted`,`Customer Other Delivery Address Label` FROM `Customer Other Delivery Address Dimension` WHERE `Customer Other Delivery Address Customer Key`=%d ORDER BY `Customer Other Delivery Address Key`",
            $this->id
        );

        $delivery_address_keys = array();

        if ($result = $this->db->query($sql)) {

            foreach ($result as $row) {
                $delivery_address_keys[$row['Customer Other Delivery Address Key']] = array(
                    'value'           => $this->get('Customer Other Delivery Address '.$row['Customer Other Delivery Address Key']),
                    'formatted_value' => $row['Customer Other Delivery Address Formatted'],
                    'label'           => $row['Customer Other Delivery Address Label'],
                );
            }

        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $delivery_address_keys;


    }

    function is_tax_number_valid() {
        if ($this->data['Customer Tax Number'] == '') {
            return false;
        } else {
            return true;
        }

    }

    function get_order_in_process_key() {


        $order_key = false;
        $sql       = sprintf(
            "SELECT `Order Key` FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order State`='InBasket' ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $order_key = $row['Order Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $order_key;
    }

    function get_credits_formatted() {

        $credits = $this->get_credits();

        $store = new Store($this->data['Customer Store Key']);


        return money($credits, $store->data['Store Currency Code']);
    }

    function get_credits() {

        $sql = sprintf(
            "SELECT sum(`Credit Saved`) AS value FROM `Order Post Transaction Dimension` WHERE `Customer Key`=%d AND `State`='Saved' ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $credits = $row['value'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $credits;
    }

    function add_history_new_order($order, $text_locale = 'en_GB') {

        date_default_timezone_set(TIMEZONE);
        $tz_date = strftime(
            "%e %b %Y %H:%M %Z", strtotime($order->data ['Order Date']." +00:00")
        );

        date_default_timezone_set('GMT');


        switch ($text_locale) {
            default :
                $note = sprintf(
                    '%s <a href="order.php?id=%d">%s</a> (In Process)', _('Order Processed'), $order->data ['Order Key'], $order->data ['Order Public ID']
                );

                if ($order->data['Order Original Data MIME Type'] = 'application/inikoo') {

                    if ($this->editor['Author Alias'] != '' and $this->editor['Author Key']) {
                        $details = sprintf(
                            '<a href="staff.php?id=%d&took_order">%s</a> took an order for %s (<a href="customer.php?id=%d">%s</a>) on %s', $this->editor['Author Key'], $this->editor['Author Alias'], $this->get('Customer Name'), $this->id, $this->get('Formatted ID'),
                            strftime(
                                "%e %b %Y %H:%M", strtotime($order->data ['Order Date'])
                            )
                        );
                    } else {
                        $details = sprintf(
                            'Someone took an order for %s (<a href="customer.php?id=%d">%s</a>) on %s', $this->get('Customer Name'), $this->id, $this->get('Formatted ID'), $tz_date
                        );

                    }
                } else {
                    $details = sprintf(
                        '%s (<a href="customer.php?id=%d">%s</a>) place an order on %s', $this->get('Customer Name'), $this->id, $this->get('Formatted ID'), $tz_date
                    );
                }


        }
        $history_data = array(
            'Date'              => $order->data ['Order Date'],
            'Subject'           => 'Customer',
            'Subject Key'       => $this->id,
            'Direct Object'     => 'Order',
            'Direct Object Key' => $order->data ['Order Key'],
            'History Details'   => $details,
            'History Abstract'  => $note,
            'Metadata'          => 'Process'
        );

        $history_key = $order->add_history($history_data);
        $sql         = sprintf(
            "INSERT INTO `Customer History Bridge` VALUES (%d,%d,'No','No','Orders')", $this->id, $history_key
        );

        $this->db->exec($sql);


    }

    function add_history_order_cancelled($history_key) {


        $sql = sprintf(
            "INSERT INTO `Customer History Bridge` VALUES (%d,%d,'No','No','Orders')", $this->id, $history_key
        );
        $this->db->exec($sql);


    }

    function add_history_order_suspended($order) {


        date_default_timezone_set(TIMEZONE);
        $tz_date         = strftime(
            "%e %b %Y %H:%M %Z", strtotime($order->data ['Order Suspended Date']." +00:00")
        );
        $tz_date_created = strftime(
            "%e %b %Y %H:%M %Z", strtotime($order->data ['Order Date']." +00:00")
        );

        date_default_timezone_set('GMT');

        if (!isset($_SESSION ['lang'])) {
            $lang = 0;
        } else {
            $lang = $_SESSION ['lang'];
        }

        switch ($lang) {
            default :
                $note = sprintf(
                    'Order <a href="order.php?id=%d">%s</a> (Suspended)', $order->data ['Order Key'], $order->data ['Order Public ID']
                );
                if ($this->editor['Author Alias'] != '' and $this->editor['Author Key']) {
                    $details = sprintf(
                        '<a href="staff.php?id=%d&took_order">%s</a> suspended %s (<a href="customer.php?id=%d">%s</a>) order <a href="order.php?id=%d">%s</a>  on %s', $this->editor['Author Key'], $this->editor['Author Alias'], $this->get('Customer Name'), $this->id,
                        $this->get('Formatted ID'), $order->data ['Order Key'], $order->data ['Order Public ID'], $tz_date
                    );
                } else {
                    $details = sprintf(
                        '%s (<a href="customer.php?id=%d">%s</a>)  order <a href="order.php?id=%d">%s</a>  has been suspended on %s',

                        $this->get('Customer Name'), $this->id, $this->get('Formatted ID'), $order->data ['Order Key'], $order->data ['Order Public ID'], $tz_date
                    );

                }
                if ($order->data ['Order Suspend Note'] != '') {
                    $details .= '<div> Note: '.$order->data ['Order Suspend Note'].'</div>';
                }


        }
        $history_data = array(
            'Date'              => $order->data ['Order Suspended Date'],
            'Subject'           => 'Customer',
            'Subject Key'       => $this->id,
            'Direct Object'     => 'Order',
            'Direct Object Key' => $order->data ['Order Key'],
            'History Details'   => $details,
            'History Abstract'  => $note,
            'Metadata'          => 'Suspended'

        );

        $sql = sprintf(
            "UPDATE `History Dimension` SET `Deep`=2 WHERE `Subject`='Customer' AND `Subject Key`=%d  AND `Direct Object`='Order' AND `Direct Object Key`=%d ", $this->id, $order->id
        );
        $this->db->exec($sql);
        $history_key = $order->add_history($history_data);
        $sql         = sprintf(
            "INSERT INTO `Customer History Bridge` VALUES (%d,%d,'No','No','Orders')", $this->id, $history_key
        );
        $this->db->exec($sql);


        switch ($lang) {
            default :
                $note_created = sprintf(
                    '%s <a href="order.php?id=%d">%s</a> (Created)', _('Order'), $order->data ['Order Key'], $order->data ['Order Public ID']
                );

        }
        $sql = sprintf(
            "UPDATE `History Dimension` SET `History Abstract`=%s WHERE `Subject`='Customer' AND `Subject Key`=%d  AND `Direct Object`='Order' AND `Direct Object Key`=%d AND `Metadata`='Process'", prepare_mysql($note_created), $this->id, $order->id
        );
        $this->db->exec($sql);

    }

    function add_history_post_order_in_warehouse($dn) {


        date_default_timezone_set(TIMEZONE);
        $tz_date         = strftime(
            "%e %b %Y %H:%M %Z", strtotime($dn->data ['Delivery Note Date Created']." +00:00")
        );
        $tz_date_created = strftime(
            "%e %b %Y %H:%M %Z", strtotime($dn->data ['Delivery Note Date Created']." +00:00")
        );

        date_default_timezone_set('GMT');

        if (!isset($_SESSION ['lang'])) {
            $lang = 0;
        } else {
            $lang = $_SESSION ['lang'];
        }

        switch ($lang) {
            default :
                $state   = $dn->data['Delivery Note State'];
                $note    = sprintf(
                    '%s <a href="dn.php?id=%d">%s</a> (%s)', $dn->data['Delivery Note Type'], $dn->data ['Delivery Note Key'], $dn->data ['Delivery Note ID'], $state
                );
                $details = $dn->data['Delivery Note Title'];

                if ($this->editor['Author Alias'] != '' and $this->editor['Author Key']) {
                    $details .= '';
                } else {
                    $details .= '';

                }


        }
        $history_data = array(
            'Date'              => $dn->data ['Delivery Note Date Created'],
            'Subject'           => 'Customer',
            'Subject Key'       => $this->id,
            'Direct Object'     => 'After Sale',
            'Direct Object Key' => $dn->data ['Delivery Note Key'],
            'History Details'   => $details,
            'History Abstract'  => $note,
            'Metadata'          => 'Post Order'

        );

        //   print_r($history_data);

        $history_key = $dn->add_history($history_data);
        $sql         = sprintf(
            "INSERT INTO `Customer History Bridge` VALUES (%d,%d,'No','No','Orders')", $this->id, $history_key
        );
        $this->db->exec($sql);


    }

    function add_history_order_refunded($refund) {
        date_default_timezone_set(TIMEZONE);
        $tz_date = strftime(
            "%e %b %Y %H:%M %Z", strtotime($refund->data ['Invoice Date']." +00:00")
        );
        //    $tz_date_created=strftime ( "%e %b %Y %H:%M %Z", strtotime ( $order->data ['Order Date']." +00:00" ) );

        date_default_timezone_set('GMT');

        if (!isset($_SESSION ['lang'])) {
            $lang = 0;
        } else {
            $lang = $_SESSION ['lang'];
        }

        switch ($lang) {
            default :


                $note    = $refund->data['Invoice XHTML Orders'].' '._(
                        'refunded for'
                    ).' '.money(
                        -1 * $refund->data['Invoice Total Amount'], $refund->data['Invoice Currency']
                    );
                $details = _('Date refunded').": $tz_date";


        }


        $history_data = array(
            'History Abstract'  => $note,
            'History Details'   => $details,
            'Action'            => 'created',
            'Direct Object'     => 'Invoice',
            'Direct Object Key' => $refund->id,
            'Preposition'       => 'on',
            // 'Indirect Object'=>'User'
            //'Indirect Object Key'=>0,
            'Date'              => $refund->data ['Invoice Date']


        );


        //print_r($history_data);

        $history_key = $this->add_subject_history(
            $history_data, $force_save = true, $deleteable = 'No', $type = 'Orders'
        );


    }

    function update_history_order_in_warehouse($order) {


        //  date_default_timezone_set(TIMEZONE) ;
        //  $tz_date=strftime( "%e %b %Y %H:%M %Z", strtotime( $order->data ['Order Cancelled Date']." +00:00" ) );
        //  $tz_date_created=strftime( "%e %b %Y %H:%M %Z", strtotime( $order->data ['Order Date']." +00:00" ) );

        //  date_default_timezone_set('GMT') ;

        if (!isset($_SESSION ['lang'])) {
            $lang = 0;
        } else {
            $lang = $_SESSION ['lang'];
        }

        switch ($lang) {
            default :
                $note = sprintf(
                    'Order <a href="order.php?id=%d">%s</a> (%s) %s %s', $order->data ['Order Key'], $order->data ['Order Public ID'], $order->data['Order Current XHTML Payment State'], $order->get('Weight'), money(
                                                                           $order->data['Order Invoiced Balance Total Amount'], $order->data['Order Currency']
                                                                       )

                );


        }

        $sql = sprintf(
            "UPDATE `History Dimension` SET  `History Abstract`=%s WHERE `Subject`='Customer' AND `Subject Key`=%d  AND `Direct Object`='Order' AND `Direct Object Key`=%d AND `Metadata`='Process'",

            prepare_mysql($note), $this->id, $order->id
        );
        $this->db->exec($sql);


    }

    function add_history_new_post_order($order, $type) {

        date_default_timezone_set(TIMEZONE);
        $tz_date = strftime(
            "%e %b %Y %H:%M %Z", strtotime($order->data ['Order Date']." +00:00")
        );

        date_default_timezone_set('GMT');


        switch ($_SESSION ['lang']) {
            default :
                $note = sprintf(
                    '%s <a href="order.php?id=%d">%s</a> (In Process)', _('Order'), $order->data ['Order Key'], $order->data ['Order Public ID']
                );
                if ($order->data['Order Original Data MIME Type'] = 'application/inikoo') {

                    if ($this->editor['Author Alias'] != '' and $this->editor['Author Key']) {
                        $details = sprintf(
                            '<a href="staff.php?id=%d&took_order">%s</a> took an order for %s (<a href="customer.php?id=%d">%s</a>) on %s', $this->editor['Author Key'], $this->editor['Author Alias'], $this->get('Customer Name'), $this->id, $this->get('Formatted ID'),
                            strftime(
                                "%e %b %Y %H:%M", strtotime($order->data ['Order Date'])
                            )
                        );
                    } else {
                        $details = sprintf(
                            'Someone took an order for %s (<a href="customer.php?id=%d">%s</a>) on %s', $this->get('Customer Name'), $this->id, $this->get('Formatted ID'), $tz_date
                        );

                    }
                } else {
                    $details = sprintf(
                        '%s (<a href="customer.php?id=%d">%s</a>) place an order on %s', $this->get('Customer Name'), $this->id, $this->get('Formatted ID'), $tz_date
                    );
                }
                if ($order->data['Order Original Data MIME Type'] = 'application/vnd.ms-excel') {
                    if ($order->data['Order Original Data Filename'] != '') {

                        $details .= '<div >'._('Original Source').":<img src='art/icons/page_excel.png'> ".$order->data['Order Original Data MIME Type']."</div>";

                        $details .= '<div>'._('Original Source Filename').": ".$order->data['Order Original Data Filename']."</div>";


                    }
                }

        }
        $history_data = array(
            'Date'              => $order->data ['Order Date'],
            'Subject'           => 'Customer',
            'Subject Key'       => $this->id,
            'Direct Object'     => 'Order',
            'Direct Object Key' => $order->data ['Order Key'],
            'History Details'   => $details,
            'History Abstract'  => $note,
            'Metadata'          => 'Process'
        );
        $history_key  = $order->add_history($history_data);
        $sql          = sprintf(
            "INSERT INTO `Customer History Bridge` VALUES (%d,%d,'No','No','Orders')", $this->id, $history_key
        );
        $this->db->exec($sql);

    }

    public function update_orders() {


        $customer_orders            = 0;
        $orders_cancelled           = 0;
        $orders_invoiced            = 0;
        $orders_invoiced_first_date = '';
        $orders_invoiced_last_date  = '';
        $order_interval             = '';
        $order_interval_std         = '';

        $customer_with_orders = 'No';
        $first_order          = '';
        $last_order           = '';

        $payments            = 0;
        $invoiced_amount     = 0;
        $invoiced_net_amount = 0;


        $sql = sprintf(
            "SELECT count(*) AS num ,
	    min(`Invoice Date`) AS first_order_date ,
		max(`Invoice Date`) AS last_order_date
        FROM `Invoice Dimension` WHERE `Invoice Type`='Invoice'  AND `Invoice Customer Key`=%d  ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $orders_invoiced            = $row['num'];
                $orders_invoiced_first_date = $row['first_order_date'];
                $orders_invoiced_last_date  = $row['last_order_date'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "SELECT sum(`Invoice Total Amount`) AS total ,sum(`Invoice Total Net Amount`) AS net FROM `Invoice Dimension` WHERE   `Invoice Customer Key`=%d  ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $invoiced_amount     = $row['total'];
                $invoiced_net_amount = $row['net'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "SELECT sum(`Payment Transaction Amount`) AS payments FROM `Payment Dimension` WHERE   `Payment Customer Key`=%d  AND `Payment Transaction Status`='Completed' ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $payments = $row['payments'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($orders_invoiced > 1) {
            $sql        = "SELECT `Invoice Date` AS date FROM `Invoice Dimension` WHERE `Invoice Type`='Invoice'  AND `Invoice Customer Key`=".$this->id." ORDER BY `Invoice Date`";
            $last_order = false;
            $last_date  = false;
            $intervals  = array();


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $this_date = gmdate('U', strtotime($row['date']));
                    if ($last_order) {
                        $intervals[] = ($this_date - $last_date);
                    }

                    $last_date  = $this_date;
                    $last_order = true;
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $order_interval     = average($intervals);
            $order_interval_std = deviation($intervals);

        }


        $sql = sprintf(
            "SELECT
		min(`Order Date`) AS first_order_date ,
		max(`Order Date`) AS last_order_date,
		count(*) AS orders
		FROM `Order Dimension` WHERE `Order Customer Key`=%d  AND `Order State` NOT IN ('Cancelled','InBasket') ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $customer_orders = $row['orders'];


                if ($customer_orders > 0) {
                    $first_order          = $row['first_order_date'];
                    $last_order           = $row['last_order_date'];
                    $customer_with_orders = 'Yes';
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $update_data = array(
            'Customer Orders'                    => $customer_orders,
            'Customer Orders Cancelled'          => $orders_cancelled,
            'Customer Orders Invoiced'           => $orders_invoiced,
            'Customer First Invoiced Order Date' => $orders_invoiced_first_date,
            'Customer Last Invoiced Order Date'  => $orders_invoiced_last_date,
            'Customer First Order Date'          => $first_order,
            'Customer Last Order Date'           => $last_order,
            'Customer Order Interval'            => $order_interval,
            'Customer Order Interval STD'        => $order_interval_std,
            'Customer With Orders'               => $customer_with_orders,
            'Customer Payments Amount'           => $payments,
            'Customer Sales Amount'              => $invoiced_amount,
            'Customer Total Sales Amount'        => $invoiced_net_amount,

        );


        $this->fast_update($update_data);


    }

    public function update_invoices() {


        $orders_invoiced            = 0;
        $orders_invoiced_first_date = '';
        $orders_invoiced_last_date  = '';
        $order_interval             = '';
        $order_interval_std         = '';


        $invoiced_amount     = 0;
        $invoiced_net_amount = 0;


        $sql = sprintf(
            "SELECT count(*) AS num ,
		min(`Invoice Date`) AS first_order_date ,
		max(`Invoice Date`) AS last_order_date

		FROM `Invoice Dimension` WHERE `Invoice Type`='Invoice'  AND `Invoice Customer Key`=%d  ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $orders_invoiced            = $row['num'];
                $orders_invoiced_first_date = $row['first_order_date'];
                $orders_invoiced_last_date  = $row['last_order_date'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "SELECT sum(`Invoice Total Amount`) AS total ,sum(`Invoice Total Net Amount`) AS net FROM `Invoice Dimension` WHERE   `Invoice Customer Key`=%d  ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $invoiced_amount     = $row['total'];
                $invoiced_net_amount = $row['net'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($orders_invoiced > 1) {
            $sql        = "SELECT `Invoice Date` AS date FROM `Invoice Dimension` WHERE `Invoice Type`='Invoice'  AND `Invoice Customer Key`=".$this->id." ORDER BY `Invoice Date`";
            $last_order = false;
            $last_date  = false;
            $intervals  = array();


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $this_date = gmdate('U', strtotime($row['date']));
                    if ($last_order) {
                        $intervals[] = ($this_date - $last_date);
                    }

                    $last_date  = $this_date;
                    $last_order = true;
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $order_interval     = average($intervals);
            $order_interval_std = deviation($intervals);

        }


        $update_data = array(
            'Customer Orders Invoiced'           => $orders_invoiced,
            'Customer First Invoiced Order Date' => $orders_invoiced_first_date,
            'Customer Last Invoiced Order Date'  => $orders_invoiced_last_date,
            'Customer Order Interval'            => $order_interval,
            'Customer Order Interval STD'        => $order_interval_std,
            'Customer Invoiced Amount'           => $invoiced_amount,
            'Customer Invoiced Net Amount'       => $invoiced_net_amount,
        );


        $this->fast_update($update_data);


    }

    public function update_payments() {


        $payments = 0;


        $sql = sprintf(
            "SELECT sum(`Payment Transaction Amount`) AS payments FROM `Payment Dimension` WHERE   `Payment Customer Key`=%d  AND `Payment Transaction Status`='Completed' ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $payments = $row['payments'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $update_data = array(
            'Customer Payments Amount' => $payments,


        );


        $this->fast_update($update_data);


    }

    public function update_activity() {


        if ($this->data['Customer Type by Activity'] == 'ToApprove' or $this->data['Customer Type by Activity'] == 'Rejected') {
            return;
        }

        $this->data['Customer Lost Date'] = '';
        $this->data['Actual Customer']    = 'Yes';

        $orders = $this->data['Customer Orders'];

        $store = get_object('store', $this->data['Customer Store Key']);

        if ($orders == 0) {
            $this->data['Customer Type by Activity'] = 'Active';
            $this->data['Customer Active']           = 'Yes';
            if (strtotime('now') - strtotime(
                    $this->data['Customer First Contacted Date']
                ) > $store->data['Store Losing Customer Interval']) {
                $this->data['Customer Type by Activity'] = 'Losing';
            }
            if (strtotime('now') - strtotime(
                    $this->data['Customer First Contacted Date']
                ) > $store->data['Store Lost Customer Interval']) {
                $this->data['Customer Type by Activity'] = 'Lost';
                $this->data['Customer Active']           = 'No';
            }

            //print "\n\n".$this->data['Customer First Contacted Date']." +".$this->data['Customer First Contacted Date']." seconds\n";
            $this->data['Customer Lost Date'] = gmdate(
                'Y-m-d H:i:s', strtotime(
                                 $this->data['Customer First Contacted Date']." +".$store->data['Store Lost Customer Interval']." seconds"
                             )
            );
        } else {


            $losing_interval = $store->data['Store Losing Customer Interval'];
            $lost_interval   = $store->data['Store Lost Customer Interval'];

            if ($orders > 20) {
                $sigma_factor = 3.2906;//99.9% value assuming normal distribution

                $losing_interval = $this->data['Customer Order Interval'] + $sigma_factor * $this->data['Customer Order Interval STD'];
                $lost_interval   = $losing_interval * 4.0 / 3.0;
            }

            $lost_interval   = ceil($lost_interval);
            $losing_interval = ceil($losing_interval);

            $this->data['Customer Type by Activity'] = 'Active';
            $this->data['Customer Active']           = 'Yes';
            if (strtotime('now') - strtotime(
                    $this->data['Customer Last Order Date']
                ) > $losing_interval) {
                $this->data['Customer Type by Activity'] = 'Losing';
            }
            if (strtotime('now') - strtotime(
                    $this->data['Customer Last Order Date']
                ) > $lost_interval) {
                $this->data['Customer Type by Activity'] = 'Lost';
                $this->data['Customer Active']           = 'No';
            }
            //print "\n xxx ".$this->data['Customer Last Order Date']." +$losing_interval seconds"."    \n";
            $this->data['Customer Lost Date'] = gmdate(
                'Y-m-d H:i:s', strtotime(
                                 $this->data['Customer Last Order Date']." +$lost_interval seconds"
                             )
            );

        }

        $sql = sprintf(
            "UPDATE `Customer Dimension` SET `Customer Active`=%s,`Customer Type by Activity`=%s , `Customer Lost Date`=%s WHERE `Customer Key`=%d", prepare_mysql($this->data['Customer Active']), prepare_mysql($this->data['Customer Type by Activity']),
            prepare_mysql($this->data['Customer Lost Date']), $this->id
        );

        $this->db->exec($sql);

        $store = get_object('store', $this->data['Customer Store Key']);
        $store->update_customers_data();


    }


    function delete($note = '') {

        global $account;


        $this->deleted = false;


        $sql = "SELECT `Order Key`  FROM `Order Dimension` WHERE `Order State` in  ('InBasket','InProcess') and  `Order Customer Key`=".$this->id;

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $order = get_object('Order', $row['Order Key']);
                $order->cancel(_('Cancelled because customer was deleted'));
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $has_orders = false;
        $sql        = "SELECT count(*) AS total  FROM `Order Dimension` WHERE `Order State`!='Cancelled' and  `Order Customer Key`=".$this->id;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['total'] > 0) {
                    $has_orders = true;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($has_orders) {
            $this->msg   = _("Customer can't be deleted because has orders");
            $this->error = true;

            return;
        }


        $history_data = array(
            'History Abstract' => _('Customer Deleted'),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_history($history_data, $force_save = true);


        $sql = sprintf(
            "DELETE FROM `Customer Dimension` WHERE `Customer Key`=%d", $this->id
        );
        $this->db->exec($sql);
        $sql = sprintf(
            "DELETE FROM `Customer Correlation` WHERE `Customer A Key`=%d OR `Customer B Key`=%s", $this->id, $this->id
        );
        $this->db->exec($sql);
        $sql = sprintf(
            "DELETE FROM `Customer History Bridge` WHERE `Customer Key`=%d", $this->id
        );
        $this->db->exec($sql);
        $sql = sprintf(
            "DELETE FROM `List Customer Bridge` WHERE `Customer Key`=%d", $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM `Customer Send Post` WHERE `Customer Key`=%d", $this->id
        );
        $this->db->exec($sql);
        $sql = sprintf(
            "DELETE FROM `Search Full Text Dimension` WHERE `Subject`='Customer' AND `Subject Key`=%d", $this->id
        );
        $this->db->exec($sql);
        $sql = sprintf(
            "DELETE FROM `Category Bridge` WHERE `Subject`='Customer' AND `Subject Key`=%d", $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM `Customer Send Post` WHERE  `Customer Key`=%d", $this->id
        );
        $this->db->exec($sql);


        $website_user = get_object('Website_User', $this->get('Customer Website User Key'));
        $website_user->delete();


        // Delete if the email has not been send yet
        //Email Campaign Mailing List

        $sql = sprintf(
            "INSERT INTO `Customer Deleted Dimension` (`Customer Key`,`Customer Store Key`,`Customer Deleted Name`,`Customer Deleted Contact Name`,`Customer Deleted Email`,`Customer Deleted Metadata`,`Customer Deleted Date`,`Customer Deleted Note`) VALUE (%d,%d,%s,%s,%s,%s,%s,%s) ",
            $this->id, $this->data['Customer Store Key'], prepare_mysql($this->data['Customer Name']), prepare_mysql($this->data['Customer Main Contact Name']), prepare_mysql($this->data['Customer Main Plain Email']),
            prepare_mysql(gzcompress(json_encode($this->data), 9)), prepare_mysql($this->editor['Date']), prepare_mysql($note, false)
        );


        $this->db->exec($sql);


        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'      => 'customer_deleted',
            'store_key' => $this->data['Customer Store Key'],
            'editor'    => $this->editor
        ), $account->get('Account Code'), $this->db
        );


        $this->deleted = true;
    }

    function update_subscription($customer_id, $type) {
        if (!isset($customer_id) || !isset($type)) {
            return;
        }


    }

    function get_order_key() {
        $sql = sprintf(
            "SELECT `Order Key` FROM `Order Dimension` WHERE `Order Customer Key`=%d ORDER BY `Order Key` DESC", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                return $row['Order Key'];
            } else {
                return -1;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function update_web_data() {

        $failed_logins = 0;
        $logins        = 0;
        $requests      = 0;

        $sql = sprintf(
            "SELECT sum(`User Login Count`) AS logins, sum(`User Failed Login Count`) AS failed_logins, sum(`User Requests Count`) AS requests  FROM `User Dimension` WHERE `User Type`='Customer' AND `User Parent Key`=%d", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $failed_logins = $row['failed_logins'];
                $logins        = $row['logins'];
                $requests      = $row['requests'];
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "UPDATE `Customer Dimension` SET `Customer Number Web Logins`=%d , `Customer Number Web Failed Logins`=%d, `Customer Number Web Requests`=%d WHERE `Customer Key`=%d", $logins, $failed_logins, $requests, $this->id
        );
        //print "$sql\n";
        $this->db->exec($sql);

    }

    function get_category_data() {

        $category_data = array();

        $sql = sprintf(
            "SELECT `Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE  `Category Branch Type`='Head'  AND B.`Subject Key`=%d AND B.`Subject`='Customer'",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $sql = sprintf(
                    "SELECT `Category Label`,`Category Code` FROM `Category Dimension` WHERE `Category Key`=%d", $row['Category Root Key']
                );


                if ($result2 = $this->db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        $root_label = $row2['Category Label'];
                        $root_code  = $row2['Category Code'];
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }
                if ($row['Is Category Field Other'] == 'Yes' and $row['Other Note'] != '') {
                    $value = $row['Other Note'];
                } else {
                    $value = $row['Category Label'];
                }
                $category_data[] = array(
                    'root_label' => $root_label,
                    'root_code'  => $root_code,
                    'label'      => $row['Category Label'],
                    'label'      => $row['Category Code'],
                    'value'      => $value
                );

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        return $category_data;
    }

    function update_rankings() {
        $total_customers_with_less_invoices = 0;
        $total_customers_with_less_balance  = 0;
        $total_customers_with_less_orders   = 0;
        $total_customers_with_less_profit   = 0;

        $total_customers = 0;
        $sql             = sprintf(
            "SELECT count(*) AS customers FROM `Customer Dimension` WHERE `Customer Store Key`=%d", $this->data['Customer Store Key']
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $total_customers = $row['customers'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "SELECT count(*) AS customers FROM `Customer Dimension` USE INDEX (`Customer Orders Invoiced`)  WHERE `Customer Store Key`=%d AND `Customer Orders Invoiced`<%d", $this->data['Customer Store Key'], $this->data['Customer Orders Invoiced']

        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $total_customers_with_less_invoices = $row['customers'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "SELECT count(*) AS customers FROM `Customer Dimension` USE INDEX (`Customer Orders`) WHERE `Customer Store Key`=%d AND `Customer Orders`<%d", $this->data['Customer Store Key'],

            $this->data['Customer Orders']
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $total_customers_with_less_orders = $row['customers'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "SELECT count(*) AS customers FROM `Customer Dimension` USE INDEX (`Customer Invoiced Net Amount`) WHERE `Customer Store Key`=%d AND `Customer Invoiced Net Amount`<%f", $this->data['Customer Store Key'],

            $this->data['Customer Invoiced Net Amount']
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $total_customers_with_less_balance = $row['customers'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "SELECT count(*) AS customers FROM `Customer Dimension` USE INDEX (`Customer Profit`) WHERE `Customer Store Key`=%d AND `Customer Profit`<%f", $this->data['Customer Store Key'], $this->data['Customer Profit']
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $total_customers_with_less_profit = $row['customers'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->data['Customer Invoices Top Percentage'] = ($total_customers == 0 ? 0 : $total_customers_with_less_invoices / $total_customers);
        $this->data['Customer Orders Top Percentage']   = ($total_customers == 0 ? 0 : $total_customers_with_less_orders / $total_customers);
        $this->data['Customer Balance Top Percentage']  = ($total_customers == 0 ? 0 : $total_customers_with_less_balance / $total_customers);
        $this->data['Customer Profits Top Percentage']  = ($total_customers == 0 ? 0 : $total_customers_with_less_profit / $total_customers);

        $sql = sprintf(
            "UPDATE `Customer Dimension` SET `Customer Invoices Top Percentage`=%f ,`Customer Orders Top Percentage`=%f ,`Customer Balance Top Percentage`=%f ,`Customer Profits Top Percentage`=%f  WHERE `Customer Key`=%d",
            $this->data['Customer Invoices Top Percentage'], $this->data['Customer Orders Top Percentage'], $this->data['Customer Balance Top Percentage'], $this->data['Customer Profits Top Percentage'],

            $this->id
        );

        $this->db->exec($sql);

    }

    function get_formatted_pending_payment_amount_from_account_balance() {
        return money(
            $this->get_pending_payment_amount_from_account_balance(), $this->data['Customer Currency Code']
        );
    }

    function get_pending_payment_amount_from_account_balance() {
        $pending_amount = 0;
        $sql            = sprintf(
            "SELECT `Amount` FROM `Order Payment Bridge` B LEFT JOIN `Order Dimension` O ON (O.`Order Key`=B.`Order Key`) LEFT JOIN `Payment Dimension` PD ON (PD.`Payment Key`=B.`Payment Key`)  WHERE `Is Account Payment`='Yes' AND`Order Customer Key`=%d  AND `Payment Transaction Status`='Pending' ",
            $this->id

        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $pending_amount = $row['Amount'];
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        return $pending_amount;
    }

    function get_number_saved_credit_cards($delivery_address_checksum, $invoice_address_checksum) {

        $number_saved_credit_cards = 0;
        $sql                       = sprintf(
            "SELECT count(*) AS number FROM `Customer Credit Card Dimension` WHERE `Customer Credit Card Customer Key`=%d AND `Customer Credit Card Invoice Address Checksum`=%s AND `Customer Credit Card Delivery Address Checksum`=%s AND   `Customer Credit Card Valid Until`>NOW()  ",
            $this->id, prepare_mysql($invoice_address_checksum), prepare_mysql($delivery_address_checksum)
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_saved_credit_cards = $row['number'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $number_saved_credit_cards;
    }

    function get_saved_credit_cards($delivery_address_checksum, $invoice_address_checksum) {

        $key = md5($this->id.','.$delivery_address_checksum.','.$invoice_address_checksum.','.CKEY);

        $card_data = array();
        $sql       = sprintf(
            "SELECT * FROM `Customer Credit Card Dimension` WHERE `Customer Credit Card Customer Key`=%d AND `Customer Credit Card Invoice Address Checksum`=%s AND `Customer Credit Card Delivery Address Checksum`=%s AND   `Customer Credit Card Valid Until`>NOW()  ",
            $this->id, prepare_mysql($invoice_address_checksum), prepare_mysql($delivery_address_checksum)


        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $_card_data       = json_decode(AESDecryptCtr($row['Customer Credit Card Metadata'], $key, 256), true);
                $_card_data['id'] = $row['Customer Credit Card Key'];

                $card_data[] = $_card_data;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $card_data;

    }

    function delete_credit_card($card_key) {


        $tokens = array();
        $sql    = sprintf(
            "SELECT `Customer Credit Card CCUI` FROM `Customer Credit Card Dimension` WHERE `Customer Credit Card Customer Key`=%d  AND `Customer Credit Card Key`=%d ", $this->id,

            $card_key
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sql = sprintf(
                    'SELECT `Customer Credit Card Key`,`Customer Credit Card Invoice Address Checksum`,`Customer Credit Card Delivery Address Checksum` FROM `Customer Credit Card Dimension`  WHERE `Customer Credit Card Customer Key`=%d AND `Customer Credit Card CCUI`=%s',
                    $this->id, prepare_mysql($row['Customer Credit Card CCUI'])
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row2) {
                        $tokens[] = $this->get_credit_card_token(
                            $row2['Customer Credit Card Key'], $row2['Customer Credit Card Invoice Address Checksum'], $row2['Customer Credit Card Delivery Address Checksum']
                        );

                        $sql = sprintf(
                            'DELETE FROM `Customer Credit Card Dimension`  WHERE `Customer Credit Card Key`=%d', $row2['Customer Credit Card Key']
                        );

                        $this->db->exec($sql);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $tokens;

    }

    function get_credit_card_token($card_key, $delivery_address_checksum, $invoice_address_checksum) {

        $key = md5($this->id.','.$delivery_address_checksum.','.$invoice_address_checksum.','.CKEY);

        $token = false;
        $sql   = sprintf(
            "SELECT `Customer Credit Card Metadata` FROM `Customer Credit Card Dimension` WHERE `Customer Credit Card Customer Key`=%d AND `Customer Credit Card Invoice Address Checksum`=%s AND `Customer Credit Card Delivery Address Checksum`=%s AND   `Customer Credit Card Valid Until`>NOW() AND  `Customer Credit Card Key`=%d ",
            $this->id, prepare_mysql($invoice_address_checksum), prepare_mysql($delivery_address_checksum), $card_key
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $_card_data = json_decode(AESDecryptCtr($row['Customer Credit Card Metadata'], $key, 256), true);
                $token      = $_card_data['Token'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $token;

    }

    function get_custmon_fields() {

        $custom_field = array();
        $sql          = sprintf(
            "SELECT * FROM `Custom Field Dimension` WHERE `Custom Field In Showcase`='Yes' AND `Custom Field Table`='Customer'"
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $custom_field[$row['Custom Field Key']] = $row['Custom Field Name'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $show_case = array();
        $sql       = sprintf("SELECT * FROM `Customer Custom Field Dimension` WHERE `Customer Key`=%d", $this->id);


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                foreach ($custom_field as $key => $value) {
                    $show_case[$value] = $row[$key];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $show_case;

    }

    function get_correlation_info() {
        $correlation_msg = '';
        $msg             = '';
        $sql             = sprintf(
            "SELECT * FROM `Customer Correlation` WHERE `Customer A Key`=%d AND `Correlation`>200", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $msg .= ','.sprintf(
                        "<a style='color:SteelBlue' href='customer_split_view.php?id_a=%d&id_b=%d'>%s</a>", $this->id, $row2['Customer B Key'], sprintf("%05d", $row2['Customer B Key'])
                    );

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT * FROM `Customer Correlation` WHERE `Customer B Key`=%d AND `Correlation`>200", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $msg .= ','.sprintf(
                        "<a style='color:SteelBlue' href='customer_split_view.php?id_a=%d&id_b=%d'>%s</a>", $this->id, $row2['Customer A Key'], sprintf("%05d", $row2['Customer A Key'])
                    );

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $msg = preg_replace('/^,/', '', $msg);
        if ($msg != '') {
            $correlation_msg = '<p>'._('Potential duplicates').': '.$msg.'</p>';

        }

        return $correlation_msg;

    }

    function update_product_bridge() {


        $sql = sprintf(
            "DELETE FROM `Customer Product Bridge` WHERE `Customer Product Customer Key`=%d ", $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "SELECT `Product ID`, count(DISTINCT `Invoice Key`) invoices ,max(`Invoice Date`) AS date FROM `Order Transaction Fact`  WHERE     `Invoice Key`>0 AND (`Delivery Note Quantity`-`Refund Quantity`)>0  AND  `Customer Key`=%d  GROUP BY `Product ID` ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $penultimate_date = '';
                if ($row['invoices'] > 1) {

                    $sql = sprintf(
                        "SELECT `Invoice Date` FROM `Order Transaction Fact`  WHERE  `Invoice Key`>0 AND (`Delivery Note Quantity`-`Refund Quantity`)>0  AND   `Customer Key`=%d AND `Product ID`=%d  GROUP BY `Invoice Key` ORDER BY `Invoice Date` LIMIT  1,1   ",
                        $this->id, $row['Product ID']
                    );


                    if ($result2 = $this->db->query($sql)) {
                        if ($row2 = $result2->fetch()) {
                            $penultimate_date = $row2['Invoice Date'];
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }


                }


                $sql = sprintf(
                    "INSERT INTO `Customer Product Bridge` (`Customer Product Customer Key`,`Customer Product Product ID`,`Customer Product Invoices`,`Customer Product Last Invoice Date`,`Customer Product Penultimate Invoice Date`) VALUES (%d,%d,%s,%s,%s) ", $this->id,
                    $row['Product ID'], $row['invoices'], prepare_mysql($row['date']), prepare_mysql($penultimate_date)

                );


                $this->db->exec($sql);


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

    }

    function update_part_bridge() {


        $sql = sprintf(
            "DELETE FROM `Customer Part Bridge` WHERE `Customer Part Customer Key`=%d ", $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "SELECT `Part SKU`, count(DISTINCT ITF.`Delivery Note Key`) delivery_notes ,max(`Delivery Note Date`) AS date FROM `Inventory Transaction Fact` ITF LEFT JOIN `Delivery Note Dimension` DN ON (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) WHERE   `Inventory Transaction Type`='Sale'  AND  `Delivery Note Customer Key`=%d  GROUP BY `Part SKU` ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $penultimate_date = '';
                if ($row['delivery_notes'] > 1) {

                    $sql = sprintf(
                        "SELECT `Delivery Note Date` FROM `Inventory Transaction Fact`  ITF LEFT JOIN `Delivery Note Dimension` DN ON (DN.`Delivery Note Key`=ITF.`Delivery Note Key`)  WHERE   `Inventory Transaction Type`='Sale'  AND  `Delivery Note Customer Key`=%d AND `Part SKU`=%d  GROUP BY ITF.`Delivery Note Key` ORDER BY `Delivery Note Date` LIMIT  1,1   ",
                        $this->id, $row['Part SKU']
                    );


                    if ($result2 = $this->db->query($sql)) {
                        if ($row2 = $result2->fetch()) {
                            $penultimate_date = $row2['Delivery Note Date'];
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }


                }


                $sql = sprintf(
                    "INSERT INTO `Customer Part Bridge` (`Customer Part Customer Key`,`Customer Part Part SKU`,`Customer Part Delivery Notes`,`Customer Part Last Delivery Note Date`,`Customer Part Penultimate Delivery Note Date`) VALUES (%d,%d,%s,%s,%s) ", $this->id,
                    $row['Part SKU'], $row['delivery_notes'], prepare_mysql($row['date']), prepare_mysql($penultimate_date)

                );

                //print "$sql\n";
                $this->db->exec($sql);


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

    }

    function update_category_part_bridge() {


        $sql = sprintf(
            "DELETE FROM `Customer Part Category Bridge` WHERE `Customer Part Category Customer Key`=%d ", $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`='Part' AND `Category Branch Type`!='Root' "
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $category  = new Category($row['Category Key']);
                $part_skus = $category->get_part_skus();


                if ($part_skus != '') {
                    $sql = sprintf(
                        "SELECT count(DISTINCT ITF.`Delivery Note Key`) delivery_notes ,max(`Delivery Note Date`) AS date FROM `Inventory Transaction Fact` ITF LEFT JOIN `Delivery Note Dimension` DN ON (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) WHERE   `Inventory Transaction Type`='Sale'  AND  `Delivery Note Customer Key`=%d  AND  `Part SKU` IN (%s) ",
                        $this->id, $part_skus
                    );


                    if ($result2 = $this->db->query($sql)) {
                        foreach ($result2 as $row2) {

                            $penultimate_date = '';

                            if ($row2['delivery_notes'] > 0) {

                                $sql = sprintf(
                                    "INSERT INTO `Customer Part Category Bridge` (`Customer Part Category Customer Key`,`Customer Part Category Category Key`,`Customer Part Category Delivery Notes`,`Customer Part Category Last Delivery Note Date`,`Customer Part Category Penultimate Delivery Note Date`) VALUES (%d,%d,%s,%s,%s) ",
                                    $this->id, $category->id, $row2['delivery_notes'], prepare_mysql($row2['date']), prepare_mysql($penultimate_date)

                                );

                                print "$sql\n";
                                $this->db->exec($sql);

                            }


                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }


                }


            }

        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function update_account_balance() {
        $balance = 0;
        $sql     = sprintf(
            'SELECT sum(`Credit Transaction Amount`) AS balance FROM `Credit Transaction Fact`  WHERE `Credit Transaction Customer Key`=%d  ', $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $balance = $row['balance'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->fast_update(array('Customer Account Balance' => $balance));

    }

    function approve() {

        $this->update(array('Customer Type by Activity' => 'Active'));

    }

    function reject() {

        $this->update(array('Customer Type by Activity' => 'Rejected'));

    }

    function update_last_dispatched_order_key() {

        $order_key = '';
        $sql       = sprintf(
            "SELECT `Order Key` from `Order Dimension` WHERE `Order Customer Key`=%d  AND `Order State`='Dispatched' order by `Order Dispatched Date` desc limit 1 ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $order_key = $row['Order Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->fast_update(array('Customer Last Dispatched Order Key' => $order_key));


    }


}


?>
