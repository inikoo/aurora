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


        $this->data['Order Current Dispatch State']      = 'In Process';
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

        if (isset($data['Order Public ID'])) {
            $this->data['Order Public ID'] = $data['Order Public ID'];
            $this->data['Order File As']   = $this->prepare_file_as(
                $data['Order Public ID']
            );
        } else {

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
                $this->data['Order File As']   == $number;
            }



        }


        //calculate the order total
        $this->data['Order Items Gross Amount']    = 0;
        $this->data['Order Items Discount Amount'] = 0;


        /*

                $sql = sprintf(
                    "INSERT INTO `Order Dimension` (
                `Order Show in Warehouse Orders`,`Order Telephone`,`Order Customer Fiscal Name`,`Order Email`,		`Order Apply Auto Customer Account Payment`,`Order Tax Number`,`Order Tax Number Valid`,`Order Created Date`,`Order Payment Method`,`Order Customer Order Number`,
                `Order Tax Code`,`Order Tax Rate`,`Order Customer Contact Name`,`Order For`,`Order File As`,`Order Date`,`Order Last Updated Date`,`Order Public ID`,`Order Store Key`,`Order Main Source Type`,`Order Customer Key`,`Order Customer Name`,`Order Current Dispatch State`,`Order Current Payment State`,`Order Current XHTML Payment State`,`Order Customer Message`,`Order Original Data MIME Type`,
                `Order Items Gross Amount`,`Order Items Discount Amount`,`Order Original Metadata`,`Order Type`,`Order Currency`,`Order Currency Exchange`,`Order Original Data Filename`,`Order Original Data Source`,`Order Tax Name`,`Order Tax Operations`,`Order Tax Selection Type`) VALUES
                (%s,%s, %s,%s,%s,%s,%s,%s,%s,%d,
                %s,%f,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s ,
                %.2f,%.2f,%s,%s,%s,   %f,%s,%s,%s,%s,%s)", prepare_mysql($this->data['Order Show in Warehouse Orders']), prepare_mysql($this->data['Order Telephone']),
                    prepare_mysql($this->data['Order Customer Fiscal Name']), prepare_mysql($this->data['Order Email']), prepare_mysql(
                        $this->data['Order Apply Auto Customer Account Payment']
                    ), prepare_mysql($this->data['Order Tax Number']), prepare_mysql($this->data['Order Tax Number Valid']), prepare_mysql($this->data['Order Created Date']),
                    prepare_mysql($this->data['Order Payment Method']),

                    $this->data['Order Customer Order Number'], prepare_mysql($this->data['Order Tax Code'], false), $this->data['Order Tax Rate'],


                    prepare_mysql($this->data['Order Customer Contact Name'], false), prepare_mysql($this->data['Order For']), prepare_mysql($this->data['Order File As']),
                    prepare_mysql($this->data['Order Date']), prepare_mysql($this->data['Order Date']), prepare_mysql($this->data['Order Public ID']), prepare_mysql($this->data['Order Store Key']),

                    prepare_mysql($this->data['Order Main Source Type']), prepare_mysql($this->data['Order Customer Key']), prepare_mysql($this->data['Order Customer Name'], false),
                    prepare_mysql($this->data['Order Current Dispatch State']), prepare_mysql($this->data['Order Current Payment State']), prepare_mysql($this->data['Order Current XHTML Payment State']),
                    prepare_mysql($this->data['Order Customer Message']), prepare_mysql($this->data['Order Original Data MIME Type']),


                    $this->data['Order Items Gross Amount'], $this->data['Order Items Discount Amount'], prepare_mysql($this->data['Order Original Metadata']), prepare_mysql($this->data['Order Type']),
                    prepare_mysql($this->data['Order Currency']), $this->data['Order Currency Exchange'], prepare_mysql($this->data['Order Original Data Filename']),
                    prepare_mysql($this->data['Order Original Data Source']), prepare_mysql($this->data['Order Tax Name']), prepare_mysql($this->data['Order Tax Operations']),
                    prepare_mysql($this->data['Order Tax Selection Type'])
                );

        */
        //print_r($this->data);

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


}



?>
