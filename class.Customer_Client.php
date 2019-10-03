<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30-09-2019 15:17:40 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
*/

include_once 'class.Subject.php';

class Customer_Client extends Subject {


    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;
        $this->id = false;


        $this->table_name = 'Customer Client';

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        if ($arg1 == 'new') {
            $this->create($arg2, $arg3);

            return;
        }

        $this->get_data($arg1, $arg2);


    }


    function get_data($key, $id) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Customer Client Dimension` WHERE `Customer Client Key`=%d", $id
            );

        } else {

            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id       = $this->data['Customer Client Key'];
            $this->metadata = json_decode($this->data['Customer Client Metadata'], true);

        }


    }

    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }


    function create($raw_data, $address_raw_data) {


        $this->data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }
        $this->editor = $raw_data['editor'];

        $this->data['Customer Client Creation Date'] = gmdate('Y-m-d H:i:s');
        $this->data['Customer Client Metadata']      = '{}';


        $sql = sprintf(
            "INSERT INTO `Customer Client Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($this->data)).'`', join(',', array_fill(0, count($this->data), '?'))
        );

        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($this->data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {


            $this->id = $this->db->lastInsertId();
            $this->get_data('id', $this->id);


            if ($this->data['Customer Client Company Name'] != '') {
                $customer_name = $this->data['Customer Client Company Name'];
            } else {
                $customer_name = $this->data['Customer Client Main Contact Name'];
            }
            $this->fast_update(
                array(
                    'Customer Client Name' => $customer_name
                )
            );


            $this->update_address('Contact', $address_raw_data, 'no_history');


            $this->fast_update(
                array(
                    'Customer Client Main Plain Mobile'    => $this->get('Customer Client Main Plain Mobile'),
                    'Customer Client Main Plain Telephone' => $this->get('Customer Client Main Plain Telephone'),
                )

            );


            $history_data = array(
                'History Abstract' => sprintf(_("Customer's client created (%s)"), $this->get('Name')),
                'History Details'  => '',
                'Action'           => 'created',
                'Subject'          => 'Customer Client',
                'Subject Key'      => $this->id,
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $this->new = true;


        } else {
            $this->error = true;
            print_r($stmt->errorInfo());
            $this->msg = 'Error inserting customer client record';
        }


    }


    function get($key) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {
            case('Creation Date'):
                if ($this->data['Customer Client '.$key] == '') {
                    return '';
                }

                return '<span title="'.strftime(
                        "%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Customer Client '.$key]." +00:00")
                    ).'">'.strftime(
                        "%a %e %b %Y", strtotime($this->data['Customer Client '.$key]." +00:00")
                    ).'</span>';
                break;
            case 'Customer Client Contact Address':


                $address_fields = array(

                    'Address Recipient'            => $this->get('Contact Client Address Recipient'),
                    'Address Organization'         => $this->get('Contact Client Address Organization'),
                    'Address Line 1'               => $this->get('Contact Client Address Line 1'),
                    'Address Line 2'               => $this->get('Contact Client Address Line 2'),
                    'Address Sorting Code'         => $this->get('Contact Client Address Sorting Code'),
                    'Address Postal Code'          => $this->get('Contact Client Address Postal Code'),
                    'Address Dependent Locality'   => $this->get('Contact Client Address Dependent Locality'),
                    'Address Locality'             => $this->get('Contact Client Address Locality'),
                    'Address Administrative Area'  => $this->get('Contact Client Address Administrative Area'),
                    'Address Country 2 Alpha Code' => $this->get('Contact Client Address Country 2 Alpha Code'),


                );

                return json_encode($address_fields);
                break;


            default:

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Customer Client '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }

                return '';

        }

    }

    function get_order_in_process_key() {


        $order_key = false;
        $sql       = sprintf(
            "SELECT `Order Key` FROM `Order Dimension` WHERE `Order Customer Client Key`=%d AND `Order State`='InBasket' ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $order_key = $row['Order Key'];
            }
        }


        return $order_key;
    }

    /*
        function get_orders_data() {

            $orders_data = array();
            $sql         = sprintf('select `Order Invoice Key`,`Order Key`,`Order Public ID`,`Order Date`,`Order Total Amount`,`Order State`,`Order Currency` from `Order Dimension` where `Order Customer Client Key`=%d order by `Order Date` desc ', $this->id);

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    switch ($row['Order State']) {
                        default:
                            $state = $row['Order State'];
                    }

                    $orders_data[] = array(
                        'key'         => $row['Order Key'],
                        'invoice_key' => $row['Order Invoice Key'],
                        'number'      => $row['Order Public ID'],
                        'date'        => strftime("%e %b %Y", strtotime($row['Order Date'].' +0:00')),
                        'state'       => $state,
                        'total'       => money($row['Order Total Amount'], $row['Order Currency'])

                    );
                }
            }

            return $orders_data;

        }
    */

    function update_field_switcher($field, $value, $options = '', $metadata = array()) {


        if (is_string($value)) {
            $value = _trim($value);
        }


        switch ($field) {


            case 'Customer Client Contact Address':

                $this->update_address('Contact', json_decode($value, true), $options);


                break;


                break;
            case 'Customer Client Location':

                $this->update_field($field, $value, $options);

                break;

            default:


        }
    }


    function create_order() {

        global $account;


        $order_data = array(

            'Order Original Data MIME Type' => 'application/aurora',
            'Order Type'                    => 'Order',
            'editor'                        => $this->editor,


        );


        $order_data['Order Customer Client Key']   = $this->id;
        $order_data['Order Customer Name']         = $this->data['Customer Name'];
        $order_data['Order Customer Contact Name'] = $this->data['Customer Main Contact Name'];
        $order_data['Order Customer Level Type']   = $this->data['Customer Level Type'];

        $order_data['Order Registration Number'] = $this->data['Customer Registration Number'];

        $order_data['Order Tax Number']                    = $this->data['Customer Tax Number'];
        $order_data['Order Tax Number Valid']              = $this->data['Customer Tax Number Valid'];
        $order_data['Order Tax Number Validation Date']    = $this->data['Customer Tax Number Validation Date'];
        $order_data['Order Tax Number Validation Source']  = $this->data['Customer Tax Number Validation Source'];
        $order_data['Order Tax Number Validation Message'] = $this->data['Customer Tax Number Validation Message'];

        $order_data['Order Tax Number Details Match']      = $this->data['Customer Tax Number Details Match'];
        $order_data['Order Tax Number Registered Name']    = $this->data['Customer Tax Number Registered Name'];
        $order_data['Order Tax Number Registered Address'] = $this->data['Customer Tax Number Registered Address'];

        $order_data['Order Available Credit Amount']  = $this->data['Customer Account Balance'];
        $order_data['Order Sales Representative Key'] = $this->data['Customer Sales Representative Key'];

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
        $order_data['Order Invoice Address Checksum']             = $this->data['Customer Invoice Address Checksum'];
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
        $order_data['Order Delivery Address Checksum']             = $this->data['Customer Delivery Address Checksum'];
        $order_data['Order Delivery Address Formatted']            = $this->data['Customer Delivery Address Formatted'];
        $order_data['Order Delivery Address Postal Label']         = $this->data['Customer Delivery Address Postal Label'];


        $order_data['Order Sticky Note']          = $this->data['Customer Order Sticky Note'];
        $order_data['Order Delivery Sticky Note'] = $this->data['Customer Delivery Sticky Note'];


        $order_data['Order Customer Order Number'] = $this->get_number_of_orders() + 1;

        $store = get_object('Store', $this->get('Customer Store Key'));

        $order_data['Order Store Key']                = $store->id;
        $order_data['Order Currency']                 = $store->get('Store Currency Code');
        $order_data['Order Show in Warehouse Orders'] = $store->get('Store Show in Warehouse Orders');
        $order_data['public_id_format']               = $store->get('Store Order Public ID Format');


        include_once 'class.Public_Order.php';
        $order = new Public_Order('new', $order_data);


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
            "SELECT count(*) AS number FROM `Order Dimension` WHERE `Order Customer Client Key`=%d ", $this->id
        );
        $number = 0;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number = $row['number'];
            }
        }


        return $number;


    }


    function get_field_label($field) {


        switch ($field) {

            case 'Customer Client Company Name':
                $label = _('company name');
                break;
            case 'Customer Client Main Contact Name':
                $label = _('contact name');
                break;
            case 'Customer Client Main Plain Email':
                $label = _('email');
                break;
            case 'Customer Client Main Email':
                $label = _('main email');
                break;
            case 'Customer Client Other Email':
                $label = _('other email');
                break;
            case 'Customer Client Main Plain Telephone':
            case 'Customer Client Main XHTML Telephone':
                $label = _('telephone');
                break;
            case 'Customer Client Main Plain Mobile':
            case 'Customer Client Main XHTML Mobile':
                $label = _('mobile');
                break;
            case 'Customer Client Main Plain FAX':
            case 'Customer Client Main XHTML Fax':
                $label = _('fax');
                break;
            case 'Customer Client Other Telephone':
                $label = _('other telephone');
                break;
            case 'Customer Client Preferred Contact Number':
                $label = _('main contact number');
                break;
            case 'Customer Client Fiscal Name':
                $label = _('fiscal name');
                break;

            case 'Customer Client Contact Address':
                $label = _('contact address');
                break;
            case 'Customer Client Code':
                $label = _('Reference');
                break;

            default:
                $label = $field;

        }

        return $label;

    }


}

