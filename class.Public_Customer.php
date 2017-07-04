<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 December 2016 at 18:35:44 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'class.DBW_Table.php';


class Public_Customer extends DB_Table {


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

        $this->get_data($arg1, $arg2,$arg3);


    }


    function get_data($key, $id,$id2=false) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Customer Dimension` WHERE `Customer Key`=%d", $id
            );

        } elseif ($key == 'key_store') {
            $sql = sprintf(
                "SELECT * FROM `Customer Dimension` WHERE `Customer Key`=%d  and `Cusstomer Store Key`=%d ", $id,$id2
            );

        } else {

            return;
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
            'SELECT `Customer Key` FROM `Customer Dimension` WHERE `Customer Store Key`=%d AND `Customer Main Plain Email`=%s ', $raw_data['Customer Store Key'],
            prepare_mysql($raw_data['Customer Main Plain Email'])
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


        $keys   = '';
        $values = '';
        foreach ($this->data as $key => $value) {
            $keys   .= ",`".$key."`";
            $values .= ','.prepare_mysql($value, false);
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


            $history_data = array(
                'History Abstract' => sprintf(_('Customer %s registered'), $this->get('Name')),
                'History Details'  => '',
                'Action'           => 'created',
                'Subject'=>'Customer',
                'Subject Key'=>$this->id,
                'Author Name'=>_('Customer')
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $this->new = true;


        } else {
            $this->error = true;
            $this->msg   = 'Error inserting customer record';
        }





    }

    function update_address($type, $fields, $options = '') {


        $old_value    = $this->get("$type Address");
        $old_checksum = $this->get("$type Address Checksum");


        $address_fields           = array();
        $updated_fields_number    = 0;
        $updated_recipient_fields = false;
        $updated_address_fields   = false;

        foreach ($fields as $field => $value) {
            $this->update_field(
                $this->table_name.' '.$type.' '.$field, $value, 'no_history'
            );
            if ($this->updated) {
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


        if ($this->updated) {

            $this->update_address_formatted_fields($type, $options);


            if (!preg_match('/no( |\_)history|nohistory/i', $options)) {

                $this->add_changelog_record(
                    $this->table_name." $type Address", $old_value, $this->get("$type Address"), '', $this->table_name, $this->id
                );

            }


            if ($type == 'Contact') {


                $location = $this->get('Contact Address Locality');
                if ($location == '') {
                    $location = $this->get(
                        'Contact Address Administrative Area'
                    );
                }
                if ($location == '') {
                    $location = $this->get(
                        $this->table_name.' Contact Address Postal Code'
                    );
                }


                $this->update(
                    array(
                        $this->table_name.' Location' => trim(
                            sprintf(
                                '<img src="/art/flags/%s.gif" title="%s"> %s', strtolower(
                                $this->get(
                                    'Contact Address Country 2 Alpha Code'
                                )
                            ), $this->get(
                                'Contact Address Country 2 Alpha Code'
                            ), $location
                            )
                        )
                    ), 'no_history'
                );

            }

            if ($this->table_name == 'Customer') {

                if ($type == 'Contact' and $old_checksum == $this->get(
                        $this->table_name.' Invoice Address Checksum'
                    )
                ) {
                    $this->update_address('Invoice', $fields, $options);
                }

            }


        }

    }

    function get($key) {

        switch ($key) {


            default:

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Customer '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }

        }

    }

    function update_address_formatted_fields($type, $options) {


        include_once 'utils/get_addressing.php';

        $new_checksum = md5(
            json_encode(
                array(
                    'Address Recipient'            => $this->get($type.' Address Recipient'),
                    'Address Organization'         => $this->get($type.' Address Organization'),
                    'Address Line 1'               => $this->get($type.' Address Line 1'),
                    'Address Line 2'               => $this->get($type.' Address Line 2'),
                    'Address Sorting Code'         => $this->get($type.' Address Sorting Code'),
                    'Address Postal Code'          => $this->get($type.' Address Postal Code'),
                    'Address Dependent Locality'   => $this->get($type.' Address Dependent Locality'),
                    'Address Locality'             => $this->get($type.' Address Locality'),
                    'Address Administrative Area'  => $this->get($type.' Address Administrative Area'),
                    'Address Country 2 Alpha Code' => $this->get($type.' Address Country 2 Alpha Code'),
                )
            )
        );


        $this->update_field(
            $this->table_name.' '.$type.' Address Checksum', $new_checksum, 'no_history'
        );


        if ($type == 'Delivery') {
            include_once 'class.Public_Account.php';
            $account = new Public_Account();
            $country = $account->get('Account Country 2 Alpha Code');
            $locale  = $account->get('Account Locale');
        } else {

            if ($this->get('Store Key')) {
                include_once 'class.Public_Store.php';
                $store   = new Public_Store($this->get('Store Key'));
                $country = $store->get('Store Home Country Code 2 Alpha');
                $locale  = $store->get('Store Locale');
            } else {
                include_once 'class.Public_Account.php';
                $account = new Public_Account();
                $country = $account->get('Account Country 2 Alpha Code');
                $locale  = $account->get('Account Locale');
            }
        }

        list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);


        $address = $address->withFamilyName($this->get($type.' Address Recipient'))->withOrganization($this->get($type.' Address Organization'))->withAddressLine1($this->get($type.' Address Line 1'))
            ->withAddressLine2($this->get($type.' Address Line 2'))->withSortingCode($this->get($type.' Address Sorting Code'))->withPostalCode($this->get($type.' Address Postal Code'))
            ->withDependentLocality($this->get($type.' Address Dependent Locality'))->withLocality($this->get($type.' Address Locality'))->withAdministrativeArea(
                $this->get($type.' Address Administrative Area')
            )->withCountryCode($this->get($type.' Address Country 2 Alpha Code'));


        $xhtml_address = $formatter->format($address);


        if ($this->get($type.' Address Recipient') == $this->get('Main Contact Name')) {
            $xhtml_address = preg_replace('/(class="recipient">.+<\/span>)<br>/', '$1', $xhtml_address);
        }

        if ($this->get($type.' Address Organization') == $this->get('Company Name')) {
            $xhtml_address = preg_replace('/(class="organization">.+<\/span>)<br>/', '$1', $xhtml_address);
        }

        $xhtml_address = preg_replace(
            '/class="recipient"/', 'class="recipient fn '.($this->get($type.' Address Recipient') == $this->get('Main Contact Name') ? 'hide' : '').'"', $xhtml_address
        );


        $xhtml_address = preg_replace('/class="organization"/', 'class="organization org '.($this->get($type.' Address Organization') == $this->get('Company Name') ? 'hide' : '').'"', $xhtml_address);
        $xhtml_address = preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
        $xhtml_address = preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
        $xhtml_address = preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
        $xhtml_address = preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);


        $xhtml_address = preg_replace('/(class="address-line1 street-address"><\/span>)<br>/', '$1', $xhtml_address);


        //print $xhtml_address;
        $this->update_field($this->table_name.' '.$type.' Address Formatted', $xhtml_address, 'no_history');
        $this->update_field($this->table_name.' '.$type.' Address Postal Label', $postal_label_formatter->format($address), 'no_history');

    }

    function get_order_in_process_key($dispatch_state = 'all') {

        if ($dispatch_state == 'all') {
            $dispatch_state_valid_values = "'In Process by Customer','Waiting for Payment Confirmation'";
        } else {
            $dispatch_state_valid_values = "'In Process by Customer'";
        }

        $order_key = false;
        $sql       = sprintf(
            "SELECT `Order Key` FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order Current Dispatch State` IN (%s) ", $this->id, $dispatch_state_valid_values
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

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (is_string($value)) {
            $value = _trim($value);
        }


        switch ($field) {
            case 'Customer Location':
            case 'Customer Number History Records':
                $this->update_field($field, $value, $options);

                break;
            default:
                print $field."\n";

        }
    }
}

?>
