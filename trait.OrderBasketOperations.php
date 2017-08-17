<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 August 2017 at 08:29:30 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

trait OrderBasketOperations {



    function create_order($data) {

        global $account;


        $this->editor           = $data['editor'];
        $this->public_id_format = $data['public_id_format'];


        unset($data['editor']);
        unset($data['public_id_format']);

        $this->data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $this->data[$key] = _trim($value);
            }
        }


        $this->data['Order Type'] = $data['Order Type'];
        if (isset($data['Order Date'])) {
            $this->data['Order Date'] = $data['Order Date'];

        } else {
            $this->data['Order Date'] = gmdate('Y-m-d H:i:s');

        }
        $this->data['Order Created Date'] = $this->data['Order Date'];


        $this->data['Order Tax Code']           = '';
        $this->data['Order Tax Rate']           = 0;
        $this->data['Order Tax Name']           = '';
        $this->data['Order Tax Operations']     = '';
        $this->data['Order Tax Selection Type'] = '';


        if (isset($data['Order Tax Code'])) {

            $tax_cat = new TaxCategory('code', $data['Order Tax Code']);
            if ($tax_cat->id) {
                $this->data['Order Tax Code']           = $tax_cat->data['Tax Category Code'];
                $this->data['Order Tax Rate']           = $tax_cat->data['Tax Category Rate'];
                $this->data['Order Tax Name']           = $tax_cat->data['Tax Category Name'];
                $this->data['Order Tax Operations']     = '';
                $this->data['Order Tax Selection Type'] = 'set';
            } else {
                $this->error = true;
                $this->msg   = 'Tax code not found';
                exit();
            }
        } else {
            $tax_code_data = $this->get_tax_data();

            $this->data['Order Tax Code']           = $tax_code_data['code'];
            $this->data['Order Tax Rate']           = $tax_code_data['rate'];
            $this->data['Order Tax Name']           = $tax_code_data['name'];
            $this->data['Order Tax Operations']     = $tax_code_data['operations'];
            $this->data['Order Tax Selection Type'] = '';


        }


        $this->data['Order State']      = 'InBasket';
        $this->data['Order Current XHTML Payment State'] = _('Waiting for payment');


        if (isset($data['Order Apply Auto Customer Account Payment'])) {
            $this->data['Order Apply Auto Customer Account Payment'] = $data['Order Apply Auto Customer Account Payment'];
        } else {
            $this->data['Order Apply Auto Customer Account Payment'] = 'Yes';
        }

        if (isset($data['Order Payment Method'])) {
            $this->data['Order Payment Method'] = $data['Order Payment Method'];
        } else {
            $this->data['Order Payment Method'] = 'Unknown';
        }

        $this->data['Order Current Payment State'] = 'Waiting Payment';

        // if (array_key_exists('Order Sales Representative Keys', $data)) {
        //     $this->data['Order Sales Representative Keys'] = $data['Order Sales Representative Keys'];
        // } else {
        //     $this->data['Order Sales Representative Keys'] = array($this->editor['User Key']);
        // }

        $this->data['Order For'] = 'Customer';

        $this->data['Order Customer Message'] = '';


        if (isset($data['Order Original Data MIME Type'])) {
            $this->data['Order Original Data MIME Type'] = $data['Order Original Data MIME Type'];
        } else {
            $this->data['Order Original Data MIME Type'] = 'none';
        }

        if (isset($data['Order Original Metadata'])) {
            $this->data['Order Original Metadata'] = $data['Order Original Metadata'];
        } else {
            $this->data['Order Original Metadata'] = '';
        }

        if (isset($data['Order Original Data Source'])) {
            $this->data['Order Original Data Source'] = $data['Order Original Data Source'];
        } else {
            $this->data['Order Original Data Source'] = 'Other';
        }


        if (isset($data['Order Original Data Filename'])) {
            $this->data['Order Original Data Filename'] = $data['Order Original Data Filename'];
        } else {
            $this->data['Order Original Data Filename'] = 'Other';
        }


        $this->data['Order Currency Exchange'] = 1;


        if ($this->data['Order Currency'] != $account->get('Account Currency')) {


            //take off this and only use curret exchenge whan get rid off excel
            $date_difference = date('U') - strtotime($this->data['Order Date'].' +0:00');
            if ($date_difference > 3600) {
                $currency_exchange = new CurrencyExchange(
                    $this->data['Order Currency'].$account->get('Account Currency'), $this->data['Order Date']
                );
                $exchange          = $currency_exchange->get_exchange();
            } else {
                include_once 'utils/currency_functions.php';

                $exchange = currency_conversion(
                    $this->db, $this->data['Order Currency'], $account->get('Account Currency'), 'now'
                );
            }
            $this->data['Order Currency Exchange'] = $exchange;
        }

        $this->data['Order Main Source Type'] = 'Call';
        if (isset($data['Order Main Source Type']) and preg_match(
                '/^(Internet|Call|Store|Unknown|Email|Fax)$/i'
            )) {
            $this->data['Order Main Source Type'] = $data['Order Main Source Type'];
        }



            $sql = sprintf(
                "UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) WHERE `Store Key`=%d", $this->data['Order Store Key']
            );

            $this->db->exec($sql);


            $public_id = $this->db->lastInsertId();

            $this->data['Order Public ID'] = sprintf($this->public_id_format, $public_id);

            $number = strtolower($this->data['Order Public ID']);
            if (preg_match("/^\d+/", $number, $match)) {
                $part_number = $match[0];
                $this->data['Order File As']   = preg_replace('/^\d+/', sprintf("%012d", $part_number), $number);

            } elseif (preg_match("/\d+$/", $number, $match)) {
                $part_number = $match[0];
                $this->data['Order File As']   = preg_replace('/\d+$/', sprintf("%012d", $part_number), $number);

            } else {
                $this->data['Order File As']   = $number;
            }






        //calculate the order total
        $this->data['Order Items Gross Amount']    = 0;
        $this->data['Order Items Discount Amount'] = 0;



        $keys   = '(';
        $values = 'values (';
        foreach ($this->data as $key => $value) {
            $keys .= "`$key`,";
            if (preg_match('/xxxxxx/i', $key)) {
                $values .= prepare_mysql($value, false).",";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }

        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);


        $sql = "insert into `Order Dimension` $keys  $values";

        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();


            /*

            if (count($this->data['Order Sales Representative Keys']) == 0) {
                $sql = sprintf(
                    "INSERT INTO `Order Sales Representative Bridge` VALUES (%d,0,1)", $this->id
                );
                $this->db->exec($sql);
            } else {
                $share = 1 / count($this->data['Order Sales Representative Keys']);
                foreach (
                    $this->data['Order Sales Representative Keys'] as $sale_rep_key
                ) {
                    $sql = sprintf(
                        "INSERT INTO `Order Sales Representative Bridge` VALUES (%d,%d,%f)", $this->id, $sale_rep_key, $share
                    );
                    $this->db->exec($sql);
                }
            }
*/

            $this->get_data('id', $this->id);

            $this->update_charges();

            if ($this->data['Order Shipping Method'] == 'Calculated') {
                $this->update_shipping();

            }



                $this->update_totals();


            $sql = sprintf(
                "UPDATE `Deal Component Dimension` SET `Deal Component Allowance Target Key`=%d WHERE `Deal Component Terms Type`='Next Order' AND  `Deal Component Trigger`='Customer' AND `Deal Component Trigger Key`=%d AND `Deal Component Allowance Target Key`=0 AND `Deal Component Status`='Active' ",
                $this->id, $this->data['Order Customer Key']
            );

            $this->db->exec($sql);


            $history_data = array(
                'History Abstract' => _('Order created'),
                'History Details'  => '',
                'Action'           => 'created'
            );
            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


        } else {
            exit ("\n\n$sql\n\n  Error, can't  create order ");
        }


    }


    function add_basket_history($data) {

        $sql = sprintf(
            "INSERT INTO `Order Basket History Dimension`  (
		`Date`,`Order Transaction Key`,`Site Key`,`Store Key`,`Customer Key`,`Order Key`,`Page Key`,`Product ID`,`Quantity Delta`,`Quantity`,`Net Amount Delta`,`Net Amount`,`Page Store Section Type`)
	VALUE (%s,%s,%d,%d,%d,%d,%d,%d,
		%f,%f,%.2f,%.2f,%s
		) ", prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql($data['otf_key']), $this->data['Order Site Key'], $this->data['Order Store Key'], $this->data['Order Customer Key'], $this->id,
            $data['Webpage Key'], $data['Product ID'], $data['Quantity Delta'], $data['Quantity'], $data['Net Amount Delta'], $data['Net Amount'], prepare_mysql($data['Page Store Section Type'])


        );
        //print $sql;

        $this->db->exec($sql);


    }


    function update_address($type, $fields, $options = '') {


        $old_value = $this->get("$type Address");


        $updated_fields_number = 0;

        foreach ($fields as $field => $value) {
            $this->update_field(
                $this->table_name.' '.$type.' '.$field, $value, 'no_history'
            );
            if ($this->updated) {
                $updated_fields_number++;

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


            if ($type == 'Invoice') {


                $this->update_tax_number_validation();

                $this->update_tax();


            } elseif ($type == 'Delivery') {


                $this->update_shipping();
                $this->update_tax();


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

            $account = get_object('Account', 1);
            $country = $account->get('Account Country 2 Alpha Code');
            $locale  = $account->get('Account Locale');
        } else {

            if ($this->get('Store Key')) {
                $store   =  get_object('Store', $this->get('Store Key'));
                $country = $store->get('Store Home Country Code 2 Alpha');
                $locale  = $store->get('Store Locale');
            } else {
                $account = get_object('Account', 1);
                $country = $account->get('Account Country 2 Alpha Code');
                $locale  = $account->get('Account Locale');
            }
        }

        list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);


        $address = $address->withFamilyName($this->get($type.' Address Recipient'))->withOrganization($this->get($type.' Address Organization'))->withAddressLine1($this->get($type.' Address Line 1'))
            ->withAddressLine2($this->get($type.' Address Line 2'))->withSortingCode($this->get($type.' Address Sorting Code'))->withPostalCode($this->get($type.' Address Postal Code'))
            ->withDependentLocality(
                $this->get($type.' Address Dependent Locality')
            )->withLocality($this->get($type.' Address Locality'))->withAdministrativeArea(
                $this->get($type.' Address Administrative Area')
            )->withCountryCode(
                $this->get($type.' Address Country 2 Alpha Code')
            );


        $xhtml_address = $formatter->format($address);




        $xhtml_address = preg_replace(
            '/class="family-name"/', 'class="recipient fn '.($this->get($type.' Address Recipient') == $this->get('Main Contact Name') and $type == 'Contact' ? 'hide' : '').'"', $xhtml_address
        );


        $xhtml_address = preg_replace(
            '/class="organization"/', 'class="organization org '.($this->get($type.' Address Organization') == $this->get('Company Name') and $type == 'Contact' ? 'hide' : '').'"', $xhtml_address
        );
        $xhtml_address = preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
        $xhtml_address = preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
        $xhtml_address = preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
        $xhtml_address = preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);


        $xhtml_address = preg_replace('/(class="address-line1 street-address"><\/span>)<br>/', '$1', $xhtml_address);


        $xhtml_address = preg_replace('/<br>/', '<br/>', $xhtml_address);



        $this->update_field($this->table_name.' '.$type.' Address Formatted', $xhtml_address, 'no_history');
        $this->update_field(
            $this->table_name.' '.$type.' Address Postal Label', $postal_label_formatter->format($address), 'no_history'
        );

    }

    function get_field_label($field) {

        switch ($field) {


            default:
                $label = $field;

        }

        return $label;

    }



    function update_for_collection($value, $options) {

        if ($value != 'Yes') {
            $value = 'No';
        }



        $old_value = $this->data['Order For Collection'];


        if ($old_value != $value or true) {


            if ($value == 'Yes') {
                $store = get_object('Store', $this->data['Order Store Key']);


                $address_data = array(
                    'Address Recipient'            => '',
                    'Address Organization'         => $store->get('Store Name'),
                    'Address Line 1'               => $store->get('Store Collect Address Line 1'),
                    'Address Line 2'               => $store->get('Store Collect Address Line 2'),
                    'Address Sorting Code'         => $store->get('Store Collect Address Sorting Code'),
                    'Address Postal Code'          => $store->get('Store Collect Address Postal Code'),
                    'Address Dependent Locality'   => $store->get('Store Collect Address Dependent Locality'),
                    'Address Locality'             => $store->get('Store Collect Address Locality'),
                    'Address Administrative Area'  => $store->get('Store Collect Address Administrative Area'),
                    'Address Country 2 Alpha Code' => $store->get('Store Collect Address Country 2 Alpha Code'),

                );




                $this->update_address('Delivery', $address_data, $options);


            } else {

                $customer = get_object('Customer', $this->get('Order Customer Key'));

                $address_data = array(
                    'Address Recipient'            => $customer->get('Customer Main Contact Name'),
                    'Address Organization'         => $customer->get('Customer Company Name'),
                    'Address Line 1'               => $customer->get('Customer Delivery Address Line 1'),
                    'Address Line 2'               => $customer->get('Customer Delivery Address Line 2'),
                    'Address Sorting Code'         => $customer->get('Customer Delivery Address Sorting Code'),
                    'Address Postal Code'          => $customer->get('Customer Delivery Address Postal Code'),
                    'Address Dependent Locality'   => $customer->get('Customer Delivery Address Dependent Locality'),
                    'Address Locality'             => $customer->get('Customer Delivery Address Locality'),
                    'Address Administrative Area'  => $customer->get('Customer Delivery Address Administrative Area'),
                    'Address Country 2 Alpha Code' => $customer->get('Customer Delivery Address Country 2 Alpha Code'),

                );

                // print_r($address_data);

                $this->update_address('Delivery', $address_data, $options);


            }


            $this->update_field('Order For Collection', $value, $options);

            $this->update_shipping();
            $this->update_tax();
            $this->update_totals();




            //    $this->apply_payment_from_customer_account();


        } else {
            $this->msg = _('Nothing to change');

        }


    }

}



?>
