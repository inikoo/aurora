<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Sun 03 November 2019  17:54::52  +0800, Plane Bangkok - Oslo (Maymana)

 Copyright (c) 2016, Inikoo

 Version 3.0

*/


trait Address
{


    function get_address($prefix)
    {
        $address = new CommerceGuys\Addressing\Address();

        return $address
            ->withCountryCode($this->data[$prefix.' Address Country 2 Alpha Code'])
            ->withAdministrativeArea($this->data[$prefix.' Address Administrative Area'])
            ->withLocality($this->data[$prefix.' Address Locality'])
            ->withDependentLocality($this->data[$prefix.' Address Dependent Locality'])
            ->withPostalCode($this->data[$prefix.' Address Postal Code'])
            ->withSortingCode($this->data[$prefix.' Address Sorting Code'])
            ->withAddressLine1($this->data[$prefix.' Address Line 1'])
            ->withAddressLine2($this->data[$prefix.' Address Line 2'])
            ->withOrganization($this->data[$prefix.' Address Organization'])
            ->withGivenName($this->data[$prefix.' Address Recipient']);
    }

    function update_address($type, $fields, $options = '', $updated_from_invoice = false)
    {
        $old_value = $this->get(($type == '' ? '' : "$type ")."Address");

        $updated_fields_number = 0;


        if (preg_match('/gb|im|jy|gg/i', $fields['Address Country 2 Alpha Code'])) {
            include_once 'utils/geography_functions.php';
            $fields['Address Postal Code'] = gbr_pretty_format_post_code($fields['Address Postal Code']);
        }


        foreach ($fields as $field => $value) {
            $this->update_field($this->table_name.' '.($type == '' ? '' : "$type ").$field, $value, 'no_history');
            if ($this->updated) {
                $updated_fields_number++;
            }
        }


        if ($updated_fields_number > 0) {
            $this->updated = true;
        }

        if ($this->updated) {
            switch ($this->table_name) {
                case 'Customer':
                    $this->process_aiku_fetch('Customer', $this->id);
                    break;
                case 'Order':
                    if ($type == 'Invoice' and !$updated_from_invoice) {
                        $this->validate_order_tax_number();

                        $this->update_tax();
                    } elseif ($type == 'Delivery') {
                        $this->update_shipping();
                        $this->update_tax();

                        $sql  = "select `Delivery Note Key` from `Delivery Note Dimension` where `Delivery Note Order Key`=? and `Delivery Note State` not in ('Dispatched','Cancelled')  ";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute(array(
                            $this->id
                        ));
                        while ($row = $stmt->fetch()) {
                            $delivery_note         = get_object('Delivery Note', $row['Delivery Note Key']);
                            $delivery_note->editor = $this->editor;
                            $delivery_note->update(['Delivery Note Address' => $this->get('Order Delivery Address')]);
                        }
                    }

                    break;

                case 'Invoice':

                    /**
                     * @var $order Order
                     */
                    $order         = get_object('Order', $this->data['Invoice Order Key']);
                    $order->editor = $this->editor;

                    $order->update_address('Invoice', $fields, '', true);


                    break;
            }


            $this->update_address_formatted_fields($type);

            if (!preg_match('/no_history/i', $options)) {
                $this->add_changelog_record(
                    $this->table_name.' '.($type == '' ? '' : "$type Address"),
                    $old_value,
                    $this->get(($type == '' ? '' : "$type ")."Address"),
                    '',
                    $this->table_name,
                    $this->id
                );
            }


            if ($type == 'Contact') {
                $this->fast_update(array($this->table_name.' Main Plain Postal Code' => preg_replace('/\s|\n|\r/', '', $this->get($this->table_name.' Contact Address Postal Code'))));


                $location = $this->get('Contact Address Locality');
                if ($location == '') {
                    $location = $this->get('Contact Address Administrative Area');
                }
                if ($location == '') {
                    $location = $this->get($this->table_name.' Contact Address Postal Code');
                }


                $this->update(
                    array(
                        $this->table_name.' Location' => trim(
                            sprintf(
                                '<img src="/art/flags/%s.png" alt="%s" title="%s"> %s',
                                strtolower($this->get('Contact Address Country 2 Alpha Code')),
                                $this->get('Contact Address Country 2 Alpha Code'),
                                $this->get('Contact Address Country 2 Alpha Code'),
                                $location
                            )
                        )
                    ),
                    'no_history'
                );
            }
        }
    }

    function update_address_formatted_fields($type)
    {
        include_once 'utils/get_addressing.php';
        $store = get_object('Store', $this->get('Store Key'));


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
            'Address Country 2 Alpha Code' => $this->get($type.' Address Country 2 Alpha Code'),
        );


        // replace null to empty string do not remove
        array_walk_recursive($address_fields, function (&$item) {
            $item = strval($item);
        });


        $new_checksum = md5(
            json_encode($address_fields)
        );


        $this->update_field(
            $this->table_name.' '.$type.' Address Checksum',
            $new_checksum,
            'no_history'
        );


        $account = get_object('Account', '');
        $locale  = $account->get('Account Locale');

        if ($type == 'Delivery') {
            $country = $account->get('Account Country 2 Alpha Code');
        } else {
            if ($this->get('Store Key')) {
                $country = $store->get('Store Home Country Code 2 Alpha');
            } else {
                $country = $account->get('Account Country 2 Alpha Code');
            }
        }

        /**
         * @var $address                \CommerceGuys\Addressing\Address
         * @var $formatter              \CommerceGuys\Addressing\Formatter\DefaultFormatter
         * @var $postal_label_formatter \CommerceGuys\Addressing\Formatter\PostalLabelFormatter
         *
         */
        list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);


        $address =
            $address->withFamilyName($this->get($type.' Address Recipient'))->withOrganization($this->get($type.' Address Organization'))->withAddressLine1($this->get($type.' Address Line 1'))->withAddressLine2($this->get($type.' Address Line 2'))->withSortingCode(
                $this->get($type.' Address Sorting Code')
            )->withPostalCode($this->get($type.' Address Postal Code'))->withDependentLocality(
                $this->get($type.' Address Dependent Locality')
            )->withLocality($this->get($type.' Address Locality'))->withAdministrativeArea(
                $this->get($type.' Address Administrative Area')
            )->withCountryCode(
                $this->get($type.' Address Country 2 Alpha Code')
            );


        $xhtml_address = $formatter->format($address);


        if ($type == 'Contact' or $this->get('Company Name') == '') {
            if ($this->get($type.' Address Recipient') == $this->get('Main Contact Name')) {
                $xhtml_address = preg_replace('/(class="family-name">.+<\/span>)<br>/', '$1', $xhtml_address);
            }

            if ($this->get($type.' Address Organization') == $this->get('Company Name')) {
                $xhtml_address = preg_replace('/(class="organization">.+<\/span>)<br>/', '$1', $xhtml_address);
            }


            $xhtml_address = preg_replace(
                '/class="family-name"/',
                'class="recipient fn '.(($this->get($type.' Address Recipient') == $this->get('Main Contact Name')) ? 'hide' : '').'"',
                $xhtml_address
            );
        } else {
            // removing contact name from invoices and delivery notes
            $xhtml_address = preg_replace('/(class="family-name">.+<\/span>)<br>/', '$1', $xhtml_address);
            $xhtml_address = preg_replace(
                '/class="family-name"/',
                'class="recipient fn hide"',
                $xhtml_address
            );

            if ($this->get($type.' Address Organization') == $this->get('Company Name')) {
                $xhtml_address = preg_replace('/(class="organization">.+<\/span>)<br>/', '$1', $xhtml_address);
            }
        }
        $xhtml_address = preg_replace(
            '/class="organization"/',
            'class="organization org '.(($this->get($type.' Address Organization') == $this->get('Company Name')) ? 'hide' : '').'"',
            $xhtml_address
        );


        $xhtml_address = preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
        $xhtml_address = preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
        $xhtml_address = preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
        $xhtml_address = preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);


        //$xhtml_address = preg_replace('/(class="address-line1 street-address"><\/span>)<br>/', '$1', $xhtml_address);


        $this->update_field($this->table_name.' '.$type.' Address Formatted', $xhtml_address, 'no_history');
        $this->update_field($this->table_name.' '.$type.' Address Postal Label', $postal_label_formatter->format($address), 'no_history');

        if ($this->table_name == 'Customer') {
            if ($type == 'Invoice') {
                $_value = $this->get('Customer Invoice Address');
                if ($this->data['Customer Billing Address Link'] == 'Contact') {
                    $metadata['no_propagate_addresses'] = true;
                    $this->update_field_switcher('Customer Contact Address', $_value, 'no_history', $metadata);


                    if (!in_array($store->get('Store Type'), ['External', 'Dropshipping'])) {
                        if ($this->data['Customer Delivery Address Link'] == 'Contact') {
                            $this->update_field_switcher('Customer Delivery Address', $_value, 'no_history', $metadata);
                        }
                    }
                }
                if (!in_array($store->get('Store Type'), ['External', 'Dropshipping'])) {
                    if ($this->data['Customer Delivery Address Link'] == 'Billing') {
                        $metadata['no_propagate_addresses'] = true;
                        $this->update_field_switcher('Customer Delivery Address', $_value, 'no_history', $metadata);
                    }
                }
            }

            // print $type;


            if (!in_array($store->get('Store Type'), ['External', 'Dropshipping'])) {
                if ($type == 'Invoice') {
                    $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` IN ('InBasket')   AND `Order Customer Key`=%d ", $this->id);
                    // print "$sql\n";
                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            //  print_r($row);
                            $order = get_object('Order', $row['Order Key']);

                            $order->editor = $this->editor;

                            if (!empty($this->skip_validate_tax_number)) {
                                $order->skip_validate_tax_number = $this->skip_validate_tax_number;
                            }


                            $order->update(array('Order Invoice Address' => $this->get('Customer Invoice Address')), 'no_history', array('no_propagate_customer' => true));
                        }
                    }
                } elseif ($type == 'Delivery') {
                    $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` IN ('InBasket')   AND `Order Customer Key`=%d ", $this->id);

                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            $order         = get_object('Order', $row['Order Key']);
                            $order->editor = $this->editor;

                            $order->update(array('Order Delivery Address' => $this->get('Customer Delivery Address')), 'no_history', array('no_propagate_customer' => true));
                        }
                    }
                }
            }
        } elseif ($this->table_name == 'Customer Client') {
            $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` IN ('InBasket')   AND `Order Customer Client Key`=%d ", $this->id);

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $order         = get_object('Order', $row['Order Key']);
                    $order->editor = $this->editor;

                    $order->update(array('Order Delivery Address' => $this->get('Customer Client Contact Address')), 'no_history', array('no_propagate_customer' => true));
                }
            }
        }
    }


}



