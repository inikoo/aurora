<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 December 2016 at 18:35:44 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'class.DBW_Table.php';
include_once 'trait.Address.php';
include_once 'trait.CustomerAiku.php';


class Public_Customer extends DBW_Table {
    use Address,CustomerAiku;


    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;
        $this->id = false;


        $this->table_name = 'Customer';

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        if ($arg1 == 'new') {
            $this->find($arg2, $arg3, 'create');

            return;
        }

        $this->get_data($arg1, $arg2, $arg3);


    }


    function get_data($key, $id, $id2 = false) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Customer Dimension` WHERE `Customer Key`=%d", $id
            );

        } elseif ($key == 'key_store') {
            $sql = sprintf(
                "SELECT * FROM `Customer Dimension` WHERE `Customer Key`=%d  AND `Customer Store Key`=%d ", $id, $id2
            );

        } else {

            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id       = $this->data['Customer Key'];
            $this->metadata = json_decode($this->data['Customer Metadata'], true);

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

    function create($raw_data, $address_raw_data) {


        $this->data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }
        $this->editor = $raw_data['editor'];

        $this->data['Customer First Contacted Date'] = gmdate('Y-m-d H:i:s');
        $this->data['Customer Sticky Note']          = '';
        $this->data['Customer Metadata']             = '{}';

        unset($this->data['Customer Lost Date']);
        unset($this->data['First Invoiced Order Date']);
        unset($this->data['Customer Last Invoiced Order Date']);
        unset($this->data['Customer Tax Number Validation Date']);
        unset($this->data['Customer Last Order Date']);
        unset($this->data['Customer First Order Date']);


        $sql = sprintf(
            "INSERT INTO `Customer Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($this->data)).'`', join(',', array_fill(0, count($this->data), '?'))
        );

        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($this->data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {


            $this->id = $this->db->lastInsertId();

            if(!$this->id){
                throw new Exception('Error inserting '.$this->table_name);
            }

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
                'History Abstract' => sprintf(_('Customer %s registered'), $this->get('Name')),
                'History Details'  => '',
                'Action'           => 'created',
                'Subject'          => 'Customer',
                'Subject Key'      => $this->id,
                'Author Name'      => _('Customer')
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $this->new = true;
            $this->fork_index_elastic_search();
            $this->model_updated('new',$this->id);

        } else {
            $this->error = true;
            print_r($stmt->errorInfo());
            $this->msg = 'Error inserting customer record';
        }


    }

    function get($key) {

        switch ($key) {

            case 'hokodo_array_data':

                if(empty($this->data['hokodo_data'])){
                    return [];
                }
                return json_decode($this->data['hokodo_data'],true);

            case 'Customer EORI':
            case 'EORI':
                return $this->metadata('eori');
            case 'Account Balance':
            case 'Credit Limit':

            return money($this->data['Customer Account Balance'], $this->metadata('cur'));
            case $this->table_name.' Contact Address':
            case $this->table_name.' Invoice Address':
            case $this->table_name.' Delivery Address':

                if ($key == $this->table_name.' Contact Address') {
                    $type = 'Contact';
                } elseif ($key == $this->table_name.' Delivery Address') {
                    $type = 'Delivery';
                } else {
                    $type = 'Invoice';
                }

                $address_fields = array(

                    'Address Recipient'            => $this->get($type.' Address Recipient'),
                    'Address Organization'         => $this->get($type.' Address Organization'),
                    'Address Line 1'               => $this->get($type.' Address Line 1'),
                    'Address Line 2'               => $this->get($type.' Address Line 2'),
                    'Address Sorting Code'         => $this->get($type.' Address Sorting Code'),
                    'Address Postal Code'          => $this->get($type.' Address Postal Code'),
                    'Address Dependent Locality'   => $this->get($type.' Address Dependent Locality'),
                    'Address Locality'             => $this->get($type.' Address Locality'),
                    'Address Administrative Area'  => $this->get($type.' Address Administrative Area'),
                    'Address Country 2 Alpha Code' => $this->get(
                        $type.' Address Country 2 Alpha Code'
                    ),


                );

                return json_encode($address_fields);
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
                        $source = '<i title=\''._('Validated online').'\' class=\'far fa-globe\'></i>';


                    } elseif ($this->data['Customer Tax Number Validation Source'] == 'Staff') {
                        $source = '<i title=\''._('Set up manually').'\' class=\'far fa-thumbtack\'></i>';
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
                            return '<span class="error">'._('Not valid').'</span>'.$validation_data;
                        default:
                            return $this->data['Customer Tax Number Valid'].$validation_data;

                            break;
                    }
                }
                break;
            case 'SNS Subscriptions':
                $customer_sns_keys = $this->metadata('sns_keys');
                if ($customer_sns_keys == '') {
                    $customer_sns_keys = [];
                } else {
                    $customer_sns_keys = json_decode($customer_sns_keys);
                }


                $subscriptions = [
                    'email' => [],
                    'https' => []
                ];


                foreach ($customer_sns_keys as $customer_sns_key) {
                    $sql  = "select * from `Customer SNS Fact` where `Customer SNS Key`=? ";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(
                        array(
                            $customer_sns_key
                        )
                    );
                    if ($row = $stmt->fetch()) {
                        $subscriptions[$row['Customer SNS Subscription Protocol']][] = $row;
                    }
                }

                return $subscriptions;


            default:

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Customer '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }

        }

    }

    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
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
        }


        return $order_key;
    }


    function get_orders_data() {

        $orders_data = array();
        $sql         = sprintf('select `Order Source Key`,`Order Invoice Key`,`Order Key`,`Order Public ID`,`Order Date`,`Order Total Amount`,`Order State`,`Order Currency` from `Order Dimension` where `Order Customer Key`=%d order by `Order Date` desc ', $this->id);

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {



                $marketplace=false;


                $sql="select `Order Source Type` from `Order Source Dimension` where `Order Source Key`=? ";
                $stmt2 = $this->db->prepare($sql);
                $stmt2->execute(
                    [
                       $row['Order Source Key']
                    ]
                );
                while ($row2 = $stmt2->fetch()) {
                    if($row2['Order Source Type']=='marketplace'){
                        $marketplace=true;
                    }
                }

                switch ($row['Order State']) {
                    default:
                        $state = $row['Order State'];
                }

                if(!$marketplace) {
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
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        return $orders_data;

    }


    function update_field_switcher($field, $value, $options = '', $metadata = array()) {


        if (is_string($value)) {
            $value = _trim($value);
        }


        switch ($field) {

            case 'Customer Delivery Address Link':

                $this->update_delivery_address_link($value, $options);
                $this->fork_index_elastic_search();
                break;

            case 'Customer Contact Address':

                $this->update_address('Contact', json_decode($value, true), $options);


                if (empty($metadata['no_propagate_addresses'])) {


                    if ($this->data['Customer Billing Address Link'] == 'Contact') {

                        $this->update_field_switcher('Customer Invoice Address', $value, $options, array('no_propagate_addresses' => true));

                        if ($this->data['Customer Delivery Address Link'] == 'Billing') {
                            $this->update_field_switcher('Customer Delivery Address', $value, $options, array('no_propagate_addresses' => true));

                        }


                    }
                    if ($this->data['Customer Delivery Address Link'] == 'Contact') {

                        $this->update_field_switcher('Customer Delivery Address', $value, $options, array('no_propagate_addresses' => true));
                    }

                }
                $this->fork_index_elastic_search();
                break;


            case 'Customer Invoice Address':

                $old_country = $this->data['Customer Invoice Address Country 2 Alpha Code'];


                $this->update_address('Invoice', json_decode($value, true), $options);

                $store   = get_object('Store', $this->data['Customer Store Key']);

                $change_delivery_in_basket=true;
                if($store->get('Store Type')=='Dropshipping'){
                    $change_delivery_in_basket=false;

                }

                //print_r(json_decode($value, true));

                if (empty($metadata['no_propagate_addresses'])) {


                    if ($this->data['Customer Billing Address Link'] == 'Contact') {


                        $this->update_field_switcher('Customer Contact Address', $value, $options, array('no_propagate_addresses' => true));

                        if ($this->data['Customer Delivery Address Link'] == 'Contact' and $change_delivery_in_basket) {

                            $this->update_field_switcher('Customer Delivery Address', $value, $options, array('no_propagate_addresses' => true));

                        }


                    }


                    if ($this->data['Customer Delivery Address Link'] == 'Billing' and $change_delivery_in_basket) {


                        $this->update_field_switcher('Customer Delivery Address', $value, $options, array('no_propagate_addresses' => true));
                    }

                }

                if (empty($metadata['no_propagate_orders'])) {

                    $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` IN ('InBasket')   AND `Order Customer Key`=%d ", $this->id);
                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            $order = get_object('Order', $row['Order Key']);

                            $order->editor = $this->editor;
                            $order->update(array('Order Invoice Address' => $value), $options, array('no_propagate_customer' => true));
                        }
                    }
                }

                if ($old_country != $this->data['Customer Invoice Address Country 2 Alpha Code']) {
                    $this->validate_customer_tax_number();
                }
                $this->fork_index_elastic_search();
                break;
            case 'Customer Delivery Address':


                $this->update_address('Delivery', json_decode($value, true), $options);

                $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` IN ('InBasket')   AND `Order Customer Key`=%d ", $this->id);
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $order         = get_object('Order', $row['Order Key']);
                        $order->editor = $this->editor;

                        $order->update(array('Order Delivery Address' => $value), $options, array('no_propagate_customer' => true));
                    }
                }

                $this->fork_index_elastic_search();
                break;


            case 'Customer Main Plain Mobile':
            case 'Customer Main Plain FAX':
            case 'Customer Main Plain Telephone':
                $value = preg_replace('/\s/', '', $value);
                if ($value == '+') {
                    $value = '';
                }
                if ($value != '') {

                    include_once 'utils/get_phoneUtil.php';
                    $phoneUtil = get_phoneUtil();
                    try {
                        if ($this->data['Customer Contact Address Country 2 Alpha Code'] == '' or $this->data['Customer Contact Address Country 2 Alpha Code'] == 'XX') {

                            if ($this->get('Store Key')) {
                                $store   = get_object('Store', $this->data['Customer Store Key']);
                                $country = $store->get('Home Country Code 2 Alpha');
                            } else {
                                $account = get_object('Account', 1);
                                $country = $account->get('Account Country 2 Alpha Code');
                            }

                        } else {
                            $country = $this->data['Customer Contact Address Country 2 Alpha Code'];
                        }
                        $proto_number    = $phoneUtil->parse($value, $country);
                        $formatted_value = $phoneUtil->format($proto_number, \libphonenumber\PhoneNumberFormat::INTERNATIONAL);

                        $value = $phoneUtil->format($proto_number, \libphonenumber\PhoneNumberFormat::E164);


                    } catch (\libphonenumber\NumberParseException $e) {
                        $this->error     = true;
                        $this->msg       = 'Error 1234';
                        $formatted_value = '';
                    }

                } else {
                    $formatted_value = '';
                }


                $this->update_field($field, $value, 'no_history');
                $this->update_field(preg_replace('/Plain/', 'XHTML', $field), $formatted_value, 'no_history');


                if ($field == 'Customer Main Plain Mobile' or $field == 'Customer Main Plain Telephone') {

                    $this->update_field_switcher('Customer Preferred Contact Number', '', $options);


                }

                $this->fork_index_elastic_search();

                return true;

            case 'Customer Preferred Contact Number':


                if ($value == '') {
                    $value = $this->data['Customer Preferred Contact Number'];

                    if ($value == '') {
                        $value = 'Mobile';
                    }

                    if ($this->data['Customer Main Plain Mobile'] == '' and $this->data['Customer Main Plain Telephone'] != '') {
                        $value = 'Telephone';
                    } elseif ($this->data['Customer Main Plain Mobile'] != '' and $this->data['Customer Main Plain Telephone'] == '') {
                        $value = 'Mobile';
                    } elseif ($this->data['Customer Main Plain Mobile'] == '' and $this->data['Customer Main Plain Telephone'] == '') {
                        $value = 'Mobile';
                    }

                }


                $this->update_field($field, $value, $options);
                $this->update_field('Customer Preferred Contact Number Formatted Number', $this->get('Customer Main XHTML '.$value), $options);

                $this->fork_index_elastic_search();
                break;


            case 'Customer Company Name':


                $old_value = $this->get('Company Name');

                if ($value == '' and $this->data[$this->table_name.' Main Contact Name'] == '') {
                    $this->msg   = _("Company name can't be empty if the contact name is empty as well");
                    $this->error = true;

                    return true;
                }

                $this->update_field($field, $value, $options);
                if ($value == '') {
                    $this->update_field(
                        $this->table_name.' Name', $this->data[$this->table_name.' Main Contact Name'], 'no_history'
                    );

                } else {
                    $this->update_field(
                        $this->table_name.' Name', $value, 'no_history'
                    );
                }

                if ($old_value == $this->get('Contact Address Organization')) {
                    $this->update_field(
                        $this->table_name.' Contact Address Organization', $value, 'no_history'
                    );
                    $this->update_address_formatted_fields('Contact');


                }


                $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order State`='InBasket'", $this->id);
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $order         = get_object('Order', $row['Order Key']);
                        $order->editor = $this->editor;
                        $order->update(array('Order Customer Name' => $this->get('Name')));


                    }
                }

                //if ($old_value == $this->get('Customer Invoice Address Organization')) {

                //   print $this->table_name.' Invoice Address Organization'.'--> '.$value;
                $this->update_field($this->table_name.' Invoice Address Organization', $value, 'no_history');
                $this->update_address_formatted_fields('Invoice');

                $this->update_field($this->table_name.' Contact Address Organization', $value, 'no_history');
                $this->update_address_formatted_fields('Contact');

                if ($this->data['Customer Delivery Address Link'] != 'None') {
                    $this->update_field($this->table_name.' Delivery Address Organization', $value, 'no_history');
                    $this->update_address_formatted_fields('Delivery');

                }

                //  }

                $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` ='InBasket'  AND `Order Customer Key`=%d ", $this->id);

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $order         = get_object('Order', $row['Order Key']);
                        $order->editor = $this->editor;


                        $_value = $this->get('Customer Invoice Address');


                        $order->update(array('Order Invoice Address' => $_value), 'no_history', array('no_propagate_customer' => true));


                        if ($this->data['Customer Delivery Address Link'] != 'None') {
                            $order->update(array('Order Delivery Address' => $_value), 'no_history', array('no_propagate_customer' => true));
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }
                $this->fork_index_elastic_search();
                break;
            case 'Customer Main Contact Name':
                //  $old_value = $this->get('Main Contact Name');

                if ($value == '' and $this->data[$this->table_name.' Company Name'] == '') {
                    $this->msg   = _("Contact name can't be empty if the company name is empty");
                    $this->error = true;

                    return;
                }

                $this->update_field($field, $value, $options);
                if ($this->data[$this->table_name.' Company Name'] == '') {
                    $this->update_field($this->table_name.' Name', $value, 'no_history');

                }


                $this->update_field($this->table_name.' Invoice Address Recipient', $value, 'no_history');
                $this->update_address_formatted_fields('Invoice');

                $this->update_field($this->table_name.' Contact Address Recipient', $value, 'no_history');
                $this->update_address_formatted_fields('Contact');

                if ($this->data['Customer Delivery Address Link'] != 'None') {
                    $this->update_field($this->table_name.' Delivery Address Recipient', $value, 'no_history');
                    $this->update_address_formatted_fields('Delivery');

                }

                //  }

                $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` = 'InBasket'   AND `Order Customer Key`=%d ", $this->id);
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $order         = get_object('Order', $row['Order Key']);
                        $order->editor = $this->editor;


                        $_value = $this->get('Customer Invoice Address');


                        $order->update(array('Order Invoice Address' => $_value), 'no_history', array('no_propagate_customer' => true));


                        if ($this->data['Customer Delivery Address Link'] != 'None') {
                            $order->update(array('Order Delivery Address' => $_value), 'no_history', array('no_propagate_customer' => true));
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                $this->fork_index_elastic_search();
                break;

            case 'Customer Main Plain Email':
                if ($value == '') {
                    $this->msg   = _("Email can't be empty");
                    $this->error = true;

                    return;
                }

                $sql = sprintf(
                    'SELECT `%s Key`,`%s Name` FROM `%s Dimension`  WHERE `%s Main Plain Email`=%s AND `%s Store Key`=%d AND `%s Key`!=%d ', addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name),
                    prepare_mysql($value), addslashes($this->table_name), $this->get('Store Key'), addslashes($this->table_name), $this->id
                );

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        if ($this->table_name == 'Customer') {
                            $msg = _('Another customer has this email');
                        } else {
                            $msg = _('Another object has this email');
                        }

                        $this->error = true;
                        $this->msg   = $msg;

                        return;
                    }

                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }

                $sql = sprintf(
                    'SELECT `%s Key`,`%s Name` FROM `%s Other Email Dimension` LEFT JOIN `%s Dimension` ON (`%s Key`=`%s Other Email %s Key`) WHERE `%s Other Email Email`=%s AND `%s Other Email Store Key`=%d ', addslashes($this->table_name),
                    addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), prepare_mysql($value),
                    addslashes($this->table_name), $this->get('Store Key')
                );
                //print "$sql\n";
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        if ($this->table_name == 'Customer') {
                            if ($row[$this->table_name.' Key'] == $this->id) {
                                $msg = _('Customer has already this email');
                            } else {
                                $msg = _('Another customer has this email');
                            }
                        } else {
                            $msg = _('Another object has this email');
                        }

                        $this->error = true;
                        $this->msg   = $msg;

                        return;
                    }

                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }

                $this->update_field($field, $value, $options);

                $website_user = get_object('Website_User', $this->get('Customer Website User Key'));

                $website_user->editor = $this->editor;
                $website_user->update(array('Website User Handle' => $value), $options);


                $sql = "SELECT `Order Key` FROM `Order Dimension` WHERE `Order Customer Key`=? AND `Order State`='InBasket'";

                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    array(
                        $this->id
                    )
                );
                if ($row = $stmt->fetch()) {
                    $order         = get_object('Order', $row['Order Key']);
                    $order->editor = $this->editor;
                    $order->update(array('Order Email' => $value));
                }


                $this->fork_index_elastic_search();
                break;
            case 'Customer Registration Number':
            case 'Customer Location':
            case 'Customer Tax Number Valid':
            case 'Customer Tax Number Details Match':
            case 'Customer Tax Number Validation Date':
            case 'Customer Tax Number Validation Source':
            case 'Customer Tax Number Validation Message':
            case 'Customer Website User Key':
            case 'Customer Invoice Address Organization':
            case 'Customer Send Newsletter':
            case 'Customer Send Email Marketing':
            case 'Customer Send Basket Emails':
            case 'Customer Send Postal Marketing':
                $this->update_field($field, $value, $options);

                break;

            case 'Customer Tax Number':
                $this->update_tax_number($value);

                $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` ='InBasket' AND `Order Customer Key`=%d ", $this->id);
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $order = get_object('Order', $row['Order Key']);
                        $order->update_tax_number($value);
                    }
                }


                break;
            default:


                if (preg_match('/^Customer Poll Query (\d+)/i', $field, $matches)) {
                    $poll_key = $matches[1];
                    $this->update_poll_answer($poll_key, $value, $options);

                    return;
                }

            // print ">>>".$field."\n";

        }
    }

    function update_delivery_address_link($value, $options) {

        $this->update_field('Customer Delivery Address Link', $value, $options);

        if ($value == 'Billing') {
            $address_data = array(
                'Address Line 1'               => $this->get('Customer Invoice Address Line 1'),
                'Address Line 2'               => $this->get('Customer Invoice Address Line 2'),
                'Address Sorting Code'         => $this->get('Customer Invoice Address Sorting Code'),
                'Address Postal Code'          => $this->get('Customer Invoice Address Postal Code'),
                'Address Dependent Locality'   => $this->get('Customer Invoice Address Dependent Locality'),
                'Address Locality'             => $this->get('Customer Invoice Address Locality'),
                'Address Administrative Area'  => $this->get('Customer Invoice Address Administrative Area'),
                'Address Country 2 Alpha Code' => $this->get('Customer Invoice Address Country 2 Alpha Code'),

            );
            $this->update_address('Delivery', $address_data, $options);

        }

    }

    function validate_customer_tax_number() {

        if ($this->data['Customer Tax Number'] == '') {
            $this->fast_update(
                array(
                    'Customer Tax Number Valid'              => 'Unknown',
                    'Customer Tax Number Details Match'      => '',
                    'Customer Tax Number Validation Date'    => '',
                    'Customer Tax Number Validation Source'  => '',
                    'Customer Tax Number Validation Message' => ''
                )
            );
        } else {

            include_once 'utils/validate_tax_number.php';

            $tax_validation_data = validate_tax_number($this->data['Customer Tax Number'], $this->data['Customer Invoice Address Country 2 Alpha Code']);

            if ($tax_validation_data['Tax Number Valid'] == 'API_Down') {
                if (!($this->data['Customer Tax Number Validation Source'] == '' and $this->data['Customer Tax Number Valid'] == 'No')) {

                    return false;
                }
            }

            $this->fast_update(
                array(
                    'Customer Tax Number Valid'              => $tax_validation_data['Tax Number Valid'],
                    'Customer Tax Number Details Match'      => $tax_validation_data['Tax Number Details Match'],
                    'Customer Tax Number Validation Date'    => $tax_validation_data['Tax Number Validation Date'],
                    'Customer Tax Number Validation Source'  => $tax_validation_data['Tax Number Validation Source'],
                    'Customer Tax Number Validation Message' => $tax_validation_data['Tax Number Validation Message'],
                )
            );
        }

        $this->model_updated( 'tax_number_validation',$this->id);

    }

    function update_tax_number($value) {

        $this->update_field('Customer Tax Number', $value);

        if ($this->updated) {
            $this->validate_customer_tax_number();
        }


        return true;

    }

    function update_poll_answer($poll_key, $value, $options) {

        /**
         * @var $poll \Public_Customer_Poll_Query
         */
        $poll = get_object('Customer_Poll_Query', $poll_key);

        $poll->add_customer($this, $value, $options);


    }

    function get_greetings($locale = false) {

        if ($locale) {

            if (preg_match('/^es_/', $locale)) {
                $unknown_name    = 'A quien corresponda';
                $greeting_prefix = 'Estimado';
            } else {
                $unknown_name    = _('To whom it corresponds');
                $greeting_prefix = _('Dear');
            }
        } else {
            $unknown_name    = _('To whom it corresponds');
            $greeting_prefix = _('Dear');
        }
        if ($this->data[$this->table_name.' Name'] == '' and $this->data[$this->table_name.' Main Contact Name'] == '') {
            return $unknown_name;
        }
        $greeting = $greeting_prefix.' '.$this->data[$this->table_name.' Main Contact Name'];
        if ($this->data[$this->table_name.' Company Name'] != '' and $this->data[$this->table_name.' Company Name'] != $this->data[$this->table_name.' Main Contact Name']) {
            $greeting .= ', '.$this->data[$this->table_name.' Name'];
        }

        return $greeting;

    }

    function get_telephone() {
        $phone = $this->get('Customer Main Plain Mobile');

        if ($phone == '') {
            $phone = $this->get('Customer Main Plain Telephone');
        }

        return $phone;

    }

    function create_order() {

        $account = get_object('Account', 1);


        $order_data = array(

            'Order Original Data MIME Type' => 'application/aurora',
            'Order Type'                    => 'Order',
            'editor'                        => $this->editor,


        );


        $order_data['Order Customer Key']          = $this->id;
        $order_data['Order Customer Name']         = $this->data['Customer Name'];
        $order_data['Order Customer Contact Name'] = $this->data['Customer Main Contact Name'];
        $order_data['Order Customer Level Type']   = $this->data['Customer Level Type'];

        $order_data['Order Source Key']   = 1;


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
        $order_data['Order Telephone']            = $this->get_telephone();


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

        $order_data['Recargo Equivalencia'] = $this->get('Customer Recargo Equivalencia');
        $order_data['Order External Invoicer Key'] = $store->get('Store External Invoicer Key');

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

    function save_credit_card($vault, $card_info, $delivery_address_checksum, $invoice_address_checksum) {
        include_once 'utils/aes.php';

        $key = md5($this->id.','.$delivery_address_checksum.','.$invoice_address_checksum.','.CKEY);

        $card_data = AESEncryptCtr(
            json_encode(
                array(
                    'Token'           => $card_info['token'],
                    'Card Type'       => preg_replace('/\s/', '', $card_info['cardType']),
                    'Card Number'     => substr($card_info['bin'], 0, 4).' ****  **** '.$card_info['last4'],
                    'Card Expiration' => $card_info['expirationMonth'].'/'.$card_info['expirationYear'],
                    'Card CVV Length' => ($card_info['cardType'] == 'American Express' ? 4 : 3),
                    'Random'          => password_hash(time(), PASSWORD_BCRYPT)

                )
            ), $key, 256
        );


        $sql = sprintf(
            "INSERT INTO `Customer Credit Card Dimension` (`Customer Credit Card Customer Key`,`Customer Credit Card Invoice Address Checksum`,`Customer Credit Card Delivery Address Checksum`,`Customer Credit Card CCUI`,`Customer Credit Card Metadata`,`Customer Credit Card Created`,`Customer Credit Card Updated`,`Customer Credit Card Valid Until`,`Customer Credit Card Vault`) 
              VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s)
		      ON DUPLICATE KEY UPDATE `Customer Credit Card Metadata`=%s , `Customer Credit Card Updated`=%s,`Customer Credit Card Valid Until`=%s", $this->id, prepare_mysql($invoice_address_checksum), prepare_mysql($delivery_address_checksum),
            prepare_mysql($card_info['uniqueNumberIdentifier']), prepare_mysql($card_data), prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql(gmdate('Y-m-d H:i:s')),
            prepare_mysql(gmdate('Y-m-d H:i:s', strtotime($card_info['expirationYear'].'-'.$card_info['expirationMonth'].'-01 +1 month'))), prepare_mysql($vault), prepare_mysql($card_data), prepare_mysql(gmdate('Y-m-d H:i:s')),
            prepare_mysql(gmdate('Y-m-d H:i:s', strtotime($card_info['expirationYear'].'-'.$card_info['expirationMonth'].'-01 +1 month')))

        );


        $this->db->exec($sql);


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
                }


            }
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
        }

        return $token;

    }

    function get_field_label($field) {


        switch ($field) {

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
            default:
                $label = $field;

        }

        return $label;

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


    function update_credit_account_running_balances() {
        $running_balance     = 0;
        $credit_transactions = 0;

        $sql  = 'SELECT `Credit Transaction Amount`,`Credit Transaction Key`  FROM `Credit Transaction Fact`  WHERE `Credit Transaction Customer Key`=? order by `Credit Transaction Date`,`Credit Transaction Key`    ';
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(
            array(
                $this->id
            )
        )) {
            while ($row = $stmt->fetch()) {

                $running_balance += $row['Credit Transaction Amount'];
                $sql             = 'update `Credit Transaction Fact` set `Credit Transaction Running Amount`=? where `Credit Transaction Key`=?  ';
                $this->db->prepare($sql)->execute(
                    array(
                        $running_balance,
                        $row['Credit Transaction Key']
                    )
                );
                $credit_transactions++;
            }
        } else {
            print_r($error_info = $stmt > errorInfo());
            exit();
        }

        $this->fast_update(array('Customer Number Credit Transactions' => $credit_transactions));


    }

    /**
     * @param $data
     *
     * @return bool|\Public_Customer_Client
     */
    public function create_client($data) {

        include_once 'class.Public_Customer_Client.php';

        $this->new_client = false;

        $data['editor'] = $this->editor;


        $data['Customer Client Store Key']     = $this->data['Customer Store Key'];
        $data['Customer Client Customer Key']  = $this->id;
        $data['Customer Client Currency Code'] = $this->metadata('cur');


        $address_fields = array(
            'Address Recipient'            => $data['Customer Client Main Contact Name'],
            'Address Organization'         => $data['Customer Client Company Name'],
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $data['Customer Client Contact Address country'],

        );
        unset($data['Customer Client Contact Address country']);

        if (isset($data['Customer Client Contact Address addressLine1'])) {
            $address_fields['Address Line 1'] = $data['Customer Client Contact Address addressLine1'];
            unset($data['Customer Client Contact Address addressLine1']);
        }
        if (isset($data['Customer Client Contact Address addressLine2'])) {
            $address_fields['Address Line 2'] = $data['Customer Client Contact Address addressLine2'];
            unset($data['Customer Client Contact Address addressLine2']);
        }
        if (isset($data['Customer Client Contact Address sortingCode'])) {
            $address_fields['Address Sorting Code'] = $data['Customer Client Contact Address sortingCode'];
            unset($data['Customer Client Contact Address sortingCode']);
        }
        if (isset($data['Customer Client Contact Address postalCode'])) {
            $address_fields['Address Postal Code'] = $data['Customer Client Contact Address postalCode'];
            unset($data['Customer Client Contact Address postalCode']);
        }

        if (isset($data['Customer Client Contact Address dependentLocality'])) {
            $address_fields['Address Dependent Locality'] = $data['Customer Client Contact Address dependentLocality'];
            unset($data['Customer Client Contact Address dependentLocality']);
        }

        if (isset($data['Customer Client Contact Address locality'])) {
            $address_fields['Address Locality'] = $data['Customer Client Contact Address locality'];
            unset($data['Customer Client Contact Address locality']);
        }

        if (isset($data['Customer Client Contact Address administrativeArea'])) {
            $address_fields['Address Administrative Area'] = $data['Customer Client Contact Address administrativeArea'];
            unset($data['Customer Client Contact Address administrativeArea']);
        }


        $client = new Public_Customer_Client('new', $data, $address_fields);

        if ($client->id) {
            $this->new_client_msg = $client->msg;

            if ($client->new) {
                $this->new_client = true;


            } else {
                $this->error = true;
                $this->msg   = $client->msg;

            }

            return $client;
        } else {
            $this->error = true;
            $this->msg   = $client->msg;
        }

        return false;
    }


    /**
     * @param $amount
     *
     * @return bool|\Public_Top_Up
     */
    public function create_top_up($amount) {


        include_once 'class.Public_Top_Up.php';

        if (!is_numeric($amount) or $amount <= 0) {
            $this->error = true;
            $this->msg   = _('Invalid top up amount');

            return false;
        }

        $top_uo_data = array(

            'Top Up Customer Key'  => $this->id,
            'Top Up Store key'     => $this->data['Customer Store Key'],
            'Top Up Date'          => gmdate('Y-m-d H:i:s'),
            'Top Up Amount'        => $amount,
            'Top Up Currency Code' => $this->metadata('cur'),
            'Top Up Metadata'      => '{}',
            'editor'               => $this->editor,


        );


        include_once 'class.Public_Top_Up.php';
        $top_up = new Public_Top_Up('new', $top_uo_data);


        if ($top_up->error) {
            $this->error = true;
            $this->msg   = $top_up->msg;

            return $top_up;
        }


        return $top_up;

    }


}

