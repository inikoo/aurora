<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 August 2017 at 08:29:30 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

trait OrderOperations {


    function create($data) {

        $account = get_object('Account', 1);


        $this->editor           = $data['editor'];
        $this->public_id_format = $data['public_id_format'];
        unset($data['editor']);
        unset($data['public_id_format']);


        if (isset($data['Recargo Equivalencia'])) {
            if ($data['Recargo Equivalencia'] == 'Yes') {
                $recargo_equivalencia = $data['Recargo Equivalencia'];
            }
            unset($data['Recargo Equivalencia']);
        }


        $this->data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $this->data[$key] = _trim($value);
            }
        }


        $this->data['Order Type'] = $data['Order Type'];
        if (!empty($data['Order Date'])) {
            $this->data['Order Date'] = $data['Order Date'];

        } else {
            $this->data['Order Date'] = gmdate('Y-m-d H:i:s');

        }
        $this->data['Order Created Date'] = $this->data['Order Date'];

        $this->data['Order Last Updated by Customer'] = $this->data['Order Date'];

        if (isset($data['Order Tax Code'])) {

            $tax_cat = new TaxCategory('code', $data['Order Tax Code']);
            if ($tax_cat->id) {
                $this->data['Order Tax Code'] = $tax_cat->data['Tax Category Code'];
                $this->data['Order Tax Rate'] = $tax_cat->data['Tax Category Rate'];
                $tax_name                     = $tax_cat->data['Tax Category Name'];
                $reason_tax_code_selected     = 'set';
            } else {
                $this->error = true;
                $this->msg   = 'Tax code not found';
                exit();
            }
        } else {
            $tax_code_data = $this->get_tax_data();

            $this->data['Order Tax Code'] = $tax_code_data['code'];
            $this->data['Order Tax Rate'] = $tax_code_data['rate'];
            $tax_name                     = $tax_code_data['name'];
            $reason_tax_code_selected     = $tax_code_data['reason_tax_code_selected'];


        }


        $this->data['Order State']                       = 'InBasket';
        $this->data['Order Current XHTML Payment State'] = _('Waiting for payment');


        if (isset($data['Order Payment Method'])) {
            $this->data['Order Payment Method'] = $data['Order Payment Method'];
        } else {
            $this->data['Order Payment Method'] = 'Unknown';
        }


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


        if ($this->data['Order Currency'] != $account->get('Currency Code')) {

            include_once 'utils/currency_functions.php';
            $exchange                              = currency_conversion($this->db, $this->data['Order Currency'], $account->get('Currency Code'), '1 hour');
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
            $part_number                 = $match[0];
            $this->data['Order File As'] = preg_replace('/^\d+/', sprintf("%012d", $part_number), $number);

        } elseif (preg_match("/\d+$/", $number, $match)) {
            $part_number                 = $match[0];
            $this->data['Order File As'] = preg_replace('/\d+$/', sprintf("%012d", $part_number), $number);

        } else {
            $this->data['Order File As'] = $number;
        }


        $this->data['Order Items Gross Amount']    = 0;
        $this->data['Order Items Discount Amount'] = 0;


        $this->data['Order Metadata'] = '{}';


        $sql = sprintf(
            "INSERT INTO `Order Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($this->data)).'`', join(',', array_fill(0, count($this->data), '?'))
        );

        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($this->data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $this->id = $this->db->lastInsertId();

            $this->fast_update_json_field('Order Metadata', 'tax_name', $tax_name);
            $this->fast_update_json_field('Order Metadata', 'why_tax', $reason_tax_code_selected);
            $this->get_data('id', $this->id);

            if (isset($recargo_equivalencia)) {
                $this->fast_update_json_field('Order Metadata', 'RE', 'Yes');
                $this->update_tax();
            }


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

            $this->fork_index_elastic_search();


        } else {

            $arr = $stmt->errorInfo();
            print_r($arr);

            print_r($data);

            exit ("Error, can't  create order ");
        }


    }


    function add_basket_history($data) {


        $sql = 'INSERT INTO `Order Basket History Dimension`  (`Order Basket History Date`,
                                               `Order Basket History Order Transaction Key`,
                                               `Order Basket History Website Key`,
                                               `Order Basket History Store Key`,
                                               `Order Basket History Customer Key`,
                                               `Order Basket History Order Key`,
                                               
                                               `Order Basket History Webpage Key`,
                                               `Order Basket History Product ID`,
                                               `Order Basket History Quantity Delta`,
                                               
                                               `Order Basket History Quantity`,
                                               `Order Basket History Net Amount Delta`,
                                               `Order Basket History Net Amount`,`Order Basket History Source`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?) ';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                gmdate('Y-m-d H:i:s'),
                $data['otf_key'],
                $this->data['Order Website Key'],

                $this->data['Order Store Key'],
                $this->data['Order Customer Key'],
                $this->id,

                $data['webpage_key'],
                $data['product_id'],
                $data['quantity_delta'],

                $data['quantity'],
                $data['net_amount_delta'],
                $data['net_amount'],

                $data['page_section_type']
            )
        );


    }

    function get_field_label($field) {

        switch ($field) {


            default:
                $label = $field;

        }

        return $label;

    }

    function update_for_collection($value, $options = false) {

        if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
            return;
        }


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

    function update_address($type, $fields, $options = '', $updated_from_invoice = false) {


        $old_value = $this->get("$type Address");


        $updated_fields_number = 0;

        if (preg_match('/gb|im|jy|gg/i', $fields['Address Country 2 Alpha Code'])) {
            include_once 'utils/geography_functions.php';
            $fields['Address Postal Code'] = gbr_pretty_format_post_code($fields['Address Postal Code']);
        }


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

            $this->update_address_formatted_fields($type);


            if (!preg_match('/no( |\_)history|nohistory/i', $options)) {

                $this->add_changelog_record(
                    $this->table_name." $type Address", $old_value, $this->get("$type Address"), '', $this->table_name, $this->id
                );

            }


            if ($type == 'Invoice' and !$updated_from_invoice) {


                $this->validate_order_tax_number();

                $this->update_tax();


            } elseif ($type == 'Delivery') {


                $this->update_shipping();
                $this->update_tax();


            }


        }

    }

    function update_address_formatted_fields($type) {

        include_once 'utils/get_addressing.php';

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
        array_walk_recursive(
            $address_fields, function (&$item) {
            $item = strval($item);
        }
        );


        $new_checksum = md5(
            json_encode($address_fields)
        );


        $this->update_field(
            $this->table_name.' '.$type.' Address Checksum', $new_checksum, 'no_history'
        );


        $account = get_object('Account', '');
        $locale  = $account->get('Account Locale');

        if ($type == 'Delivery') {

            $country = $account->get('Account Country 2 Alpha Code');
        } else {

            if ($this->get('Store Key')) {
                $store   = get_object('Store', $this->get('Store Key'));
                $country = $store->get('Store Home Country Code 2 Alpha');
                //$locale  = $store->get('Store Locale');
            } else {
                $country = $account->get('Account Country 2 Alpha Code');
                //$locale  = $account->get('Account Locale');
            }
        }

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


        $xhtml_address = preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
        $xhtml_address = preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
        $xhtml_address = preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
        $xhtml_address = preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);


        //$xhtml_address = preg_replace('/(class="address-line1 street-address"><\/span>)<br>/', '$1', $xhtml_address);


        $xhtml_address = preg_replace('/<br>/', '<br/>', $xhtml_address);


        $this->update_field($this->table_name.' '.$type.' Address Formatted', $xhtml_address, 'no_history');
        $this->update_field(
            $this->table_name.' '.$type.' Address Postal Label', $postal_label_formatter->format($address), 'no_history'
        );

    }

    function cancel($note = '', $fork = true) {

        if ($this->data['Order State'] == 'Dispatched') {
            $this->error = true;
            $this->msg   = _('Order can not be cancelled, because has already been dispatched');

            return false;
        }

        if ($this->data['Order State'] == 'Cancelled') {
            $this->error = true;
            $this->msg   = _('Order is already cancelled');

            return false;
        }


        if ($this->data['Order Payments Amount'] != 0) {

            $this->error = true;
            $this->msg   = _('Payments must be refunded or voided before cancel the order');

            return false;

        }

        $date = gmdate('Y-m-d H:i:s');

        $this->data['Order Cancelled Date'] = $date;
        $this->data['Order Cancel Note']    = $note;
        $this->data['Order Payment State']  = 'NA';
        $this->data['Order State']          = 'Cancelled';


        $this->data['Order Invoiced Balance Net Amount']               = 0;
        $this->data['Order Invoiced Balance Tax Amount']               = 0;
        $this->data['Order Invoiced Outstanding Balance Total Amount'] = 0;
        $this->data['Order Invoiced Outstanding Balance Net Amount']   = 0;
        $this->data['Order Invoiced Outstanding Balance Tax Amount']   = 0;
        $this->data['Order Balance Net Amount']                        = 0;
        $this->data['Order Balance Tax Amount']                        = 0;
        $this->data['Order Balance Total Amount']                      = 0;


        $this->data['Order To Pay Amount'] = round(
            $this->data['Order Balance Total Amount'] - $this->data['Order Payments Amount'], 2
        );

        $sql = "UPDATE `Order Dimension` SET  `Order Cancelled Date`=?, `Order State`=?,`Order Payment State`='NA',`Order To Pay Amount`=?,`Order Cancel Note`=?,
				`Order Balance Net Amount`=0,`Order Balance tax Amount`=0,`Order Balance Total Amount`=0,`Order Items Cost`=0,
				`Order Invoiced Balance Net Amount`=0,`Order Invoiced Balance Tax Amount`=0,`Order Invoiced Balance Total Amount`=0 ,`Order Invoiced Outstanding Balance Net Amount`=0,`Order Invoiced Outstanding Balance Tax Amount`=0,`Order Invoiced Outstanding Balance Total Amount`=0,`Order Invoiced Profit Amount`=0
				WHERE `Order Key`=?";


        $this->db->prepare($sql)->execute(
            array(
                $this->data['Order Cancelled Date'],
                $this->data['Order State'],
                round($this->data['Order To Pay Amount'], 2),
                $this->data['Order Cancel Note'],

                $this->id
            )
        );


        $sql = sprintf(
            "UPDATE `Order Transaction Fact` SET  `Delivery Note Key`=NULL,`Invoice Key`=NULL, `Consolidated`='Yes',`Current Dispatching State`=%s ,`Cost Supplier`=0  WHERE `Order Key`=%d ", prepare_mysql('Cancelled'), $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(

            "UPDATE `Order Transaction Fact` SET   `Delivery Note Quantity`=0, `No Shipped Due Out of Stock`=0,`Order Transaction Out of Stock Amount`=0 WHERE `Order Key`=%d ",

            $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "UPDATE `Order No Product Transaction Fact` SET `Delivery Note Date`=NULL,`Delivery Note Key`=NULL,`State`=%s ,`Consolidated`='Yes' WHERE `Order Key`=%d ", prepare_mysql('Cancelled'), $this->id
        );
        $this->db->exec($sql);


        $history_data = array(
            'History Abstract' => _('Order cancelled').($note != '' ? ', '.$note : ''),
            'History Details'  => '',
        );
        $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);


        $account = get_object('Account', '');

        if ($fork) {

            require_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'      => 'order_cancelled',
                'order_key' => $this->id,


                'editor' => $this->editor
            ), $account->get('Account Code'), $this->db
            );
        } else {

            //tmp
            $this->fork_index_elastic_search();

            return true;
            //

            $customer = get_object('Customer', $this->get('Order Customer Key'));
            $store    = get_object('Store', $this->get('Order Store Key'));

            $sql = sprintf("SELECT `Transaction Type Key` FROM `Order No Product Transaction Fact` WHERE `Transaction Type`='Charges' AND   `Order Key`=%d  ", $this->id);

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    /**
                     * @var $charge \Charge
                     */
                    $charge = get_object('Charge', $row['Transaction Type Key']);
                    $charge->update_charge_usage();

                }
            }


            $customer->update_orders();
            $store->update_orders();
            $account->update_orders();

            $deals     = array();
            $campaigns = array();
            $sql       = sprintf(
                "SELECT `Deal Component Key`,`Deal Key`,`Deal Campaign Key` FROM  `Order Deal Bridge` WHERE `Order Key`=%d", $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    /**
                     * @var $component \DealComponent
                     */
                    $component = get_object('DealComponent', $row['Deal Component Key']);
                    $component->update_usage();
                    $deals[$row['Deal Key']]              = $row['Deal Key'];
                    $campaigns[$row['Deal Campaign Key']] = $row['Deal Campaign Key'];
                }
            }


            foreach ($deals as $deal_key) {
                /**
                 * @var $deal \Deal
                 */
                $deal = get_object('Deal', $deal_key);
                $deal->update_usage();
            }

            foreach ($campaigns as $campaign_key) {
                $campaign = get_object('DealCampaign', $campaign_key);
                $campaign->update_usage();
            }


        }

        $this->fork_index_elastic_search();

        return true;

    }


}


