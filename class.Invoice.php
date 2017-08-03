<?php
/*
 File: Invoice.php

 This file contains the Invoice Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

include_once 'class.Order.php';
include_once 'class.Category.php';

include_once 'class.DeliveryNote.php';

/* class: Invoice
 Class to manage the *Invoice Dimension* table
*/


class Invoice extends DB_Table {

    /*
     Constructor: Invoice
     Initializes the class, trigger  Search/Load/Create for the data set

     If first argument is find it will try to match the data or create if not found

     Parameters:
     arg1 -    Tag for the Search/Load/Create Options *or* the Contact Key for a simple object key search
     arg2 -    (optional) Data used to search or create the object

     Returns:
     void

     Example:
     (start example)
     // Load data from `Invoice Dimension` table where  `Invoice Key`=3
     $key=3;
     $invoice = New Invoice($key);

     // Load data from `Invoice Dimension` table where  `Invoice`='raul@gmail.com'
     $invoice = New Invoice('raul@gmail.com');



    */
    function Invoice($arg1 = false, $arg2 = false, $arg3 = false, $arg4 = false) {

        $this->table_name      = 'Invoice';
        $this->ignore_fields   = array('Invoice Key');
        $this->update_customer = true;
        global $db;
        $this->db = $db;

        if (!$arg1 and !$arg2) {
            $this->error = true;
            $this->msg   = 'No data provided';

            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        if (preg_match('/create refund/i', $arg1)) {
            $this->create_refund($arg2, $arg3, $arg4);

            return;
        }

        if (preg_match('/create|new/i', $arg1)) {
            $this->create($arg2);

            return;
        }
        //   if(preg_match('/find/i',$arg1)){
        //  $this->find($arg2,$arg1);
        //  return;
        // }
        $this->get_data($arg1, $arg2);
    }


    function get_data($tipo, $tag) {
        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Invoice Dimension` WHERE  `Invoice Key`=%d", $tag
            );
        } elseif ($tipo == 'public_id') {
            $sql = sprintf(
                "SELECT * FROM `Invoice Dimension` WHERE  `Invoice Public ID`=%s", prepare_mysql($tag)
            );
        } else {
            return;
        }
        //print $sql;

        $result = mysql_query($sql);
        if ($this->data = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id = $this->data['Invoice Key'];
        }
    }

    function create_refund($invoice_data) {


        $this->data                  = $this->base_data();
        $this->data ['Invoice Type'] = 'Refund';

        if (!isset($invoice_data['Invoice Date'])) {
            $this->data ['Invoice Date'] = gmdate("Y-m-d H:i:s");
        }

        $customer = $this->set_data_from_customer(
            $invoice_data['Invoice Customer Key'], $invoice_data['Invoice Store Key']
        );
        foreach ($invoice_data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }

        if (array_key_exists(
            'Invoice Sales Representative Keys', $invoice_data
        )) {
            $this->data ['Invoice Sales Representative Keys']
                = $invoice_data['Invoice Sales Representative Keys'];
        } else {
            $this->data ['Invoice Sales Representative Keys']
                = array($this->editor['User Key']);
        }

        if (array_key_exists('Invoice Processed By Keys', $invoice_data)) {
            $this->data ['Invoice Processed By Keys']
                = $invoice_data['Invoice Processed By Keys'];
        } else {
            $this->data ['Invoice Processed By Keys']
                = array($this->editor['User Key']);
        }

        if (array_key_exists('Invoice Charged By Keys', $invoice_data)) {
            $this->data ['Invoice Charged By Keys']
                = $invoice_data['Invoice Charged By Keys'];
        } else {
            $this->data ['Invoice Charged By Keys']
                = array($this->editor['User Key']);
        }

        if (array_key_exists('Invoice Tax Number', $invoice_data)) {
            $this->data ['Invoice Tax Number']
                = $invoice_data['Invoice Tax Number'];
        }
        if (array_key_exists('Invoice Tax Number Valid', $invoice_data)) {
            $this->data ['Invoice Tax Number Valid']
                = $invoice_data['Invoice Tax Number Valid'];
        }
        if (array_key_exists(
            'Invoice Tax Number Validation Date', $invoice_data
        )) {
            $this->data ['Invoice Tax Number Validation Date']
                = $invoice_data['Invoice Tax Number Validation Date'];
        }
        if (array_key_exists(
            'Invoice Tax Number Associated Name', $invoice_data
        )) {
            $this->data ['Invoice Tax Number Associated Name']
                = $invoice_data['Invoice Tax Number Associated Name'];
        }
        if (array_key_exists(
            'Invoice Tax Number Associated Address', $invoice_data
        )) {
            $this->data ['Invoice Tax Number Associated Address']
                = $invoice_data['Invoice Tax Number Associated Address'];
        }

        if (array_key_exists('Invoice Billing To Key', $invoice_data)) {
            $billing_to = new Billing_To(
                $invoice_data['Invoice Billing To Key']
            );
        } else {
            $billing_to = $customer->get_billing_to(
                $this->data ['Invoice Date']
            );
        }

        if (array_key_exists('Invoice Net Amount Off', $invoice_data)) {
            $this->data ['Invoice Net Amount Off']
                = $invoice_data['Invoice Net Amount Off'];
        } else {
            $this->data ['Invoice Net Amount Off'] = 0;
        }


        $this->data ['Invoice Billing To Key'] = $billing_to->id;
        $this->data ['Invoice XHTML Address']
                                               = $billing_to->data['Billing To XHTML Address'];
        $this->data ['Invoice Billing Country 2 Alpha Code']
                                               = ($billing_to->data['Billing To Country 2 Alpha Code'] == '' ? 'XX' : $billing_to->data['Billing To Country 2 Alpha Code']);

        $this->data ['Invoice Billing Country Code']
                                                          = ($billing_to->data['Billing To Country Code'] == '' ? 'UNK' : $billing_to->data['Billing To Country Code']);
        $this->data ['Invoice Billing World Region Code'] = $billing_to->get(
            'World Region Code'
        );
        $this->data ['Invoice Billing Town']
                                                          = $billing_to->data['Billing To Town'];
        $this->data ['Invoice Billing Postal Code']
                                                          = $billing_to->data['Billing To Postal Code'];

        $store = new Store($this->data['Invoice Store Key']);

        //===


        if (!isset($this->data['Invoice Public ID']) or $this->data['Invoice Public ID'] == '') {

            if ($store->data['Store Refund Public ID Method'] == 'Same Invoice ID') {


                if (!isset($this->data['Invoice Public ID']) or $this->data['Invoice Public ID'] == '') {

                    //Next Invoice ID


                    if ($store->data['Store Next Invoice Public ID Method'] == 'Invoice Public ID') {

                        $sql = sprintf(
                            "UPDATE `Store Dimension` SET `Store Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Store Invoice Last Invoice Public ID` + 1) WHERE `Store Key`=%d",
                            $this->data['Invoice Store Key']
                        );
                        mysql_query($sql);
                        $invoice_public_id = sprintf(
                            $store->data['Store Invoice Public ID Format'], mysql_insert_id()
                        );

                    } elseif ($store->data['Store Next Invoice Public ID Method'] == 'Order ID') {

                        $sql = sprintf(
                            "UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) WHERE `Store Key`=%d", $this->data['Order Store Key']
                        );
                        mysql_query($sql);
                        $invoice_public_id = mysql_insert_id();
                        $invoice_public_id = sprintf(
                            $store->data['Store Order Public ID Format'], mysql_insert_id()
                        );


                    } else {

                        $sqla = sprintf(
                            "UPDATE `Account Dimension` SET `Account Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Public ID` + 1) WHERE `Account Key`=1"
                        );
                        mysql_query($sqla);
                        $public_id = mysql_insert_id();
                        include_once 'class.Account.php';
                        $account           = new Account();
                        $invoice_public_id = sprintf(
                            $account->data['Account Invoice Public ID Format'], $public_id
                        );

                    }


                } else {

                    $invoice_public_id = $this->data['Invoice Public ID'];
                }

            } elseif ($store->data['Store Refund Public ID Method'] == 'Account Wide Own Index') {

                $account = new Account();
                $sql     = sprintf(
                    "UPDATE `Account Dimension` SET `Account Invoice Last Refund Public ID` = LAST_INSERT_ID(`Account Invoice Last Refund Public ID` + 1) WHERE `Account Key`=1"
                );
                mysql_query($sql);

                $invoice_public_id = sprintf(
                    $account->data['Account Refund Public ID Format'], mysql_insert_id()
                );


            } elseif ($store->data['Store Refund Public ID Method'] == 'Store Own Index') {

                $sql = sprintf(
                    "UPDATE `Store Dimension` SET `Store Invoice Last Refund Public ID` = LAST_INSERT_ID(`Store Invoice Last Refund Public ID` + 1) WHERE `Store Key`=%d",
                    $this->data['Invoice Store Key']
                );
                mysql_query($sql);
                $invoice_public_id = sprintf(
                    $store->data['Store Refund Public ID Format'], mysql_insert_id()
                );


            } else { //Next Invoice ID


                if ($store->data['Store Next Invoice Public ID Method'] == 'Invoice Public ID') {

                    $sql = sprintf(
                        "UPDATE `Store Dimension` SET `Store Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Store Invoice Last Invoice Public ID` + 1) WHERE `Store Key`=%d",
                        $this->data['Invoice Store Key']
                    );
                    mysql_query($sql);
                    $invoice_public_id = sprintf(
                        $store->data['Store Invoice Public ID Format'], mysql_insert_id()
                    );

                } elseif ($store->data['Store Next Invoice Public ID Method'] == 'Order ID') {

                    $sql = sprintf(
                        "UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) WHERE `Store Key`=%d", $this->data['Order Store Key']
                    );
                    mysql_query($sql);
                    $invoice_public_id = mysql_insert_id();
                    $invoice_public_id = sprintf(
                        $store->data['Store Order Public ID Format'], mysql_insert_id()
                    );


                } else {

                    $sqla = sprintf(
                        "UPDATE `Account Dimension` SET `Account Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Public ID` + 1) WHERE `Account Key`=1"
                    );
                    mysql_query($sqla);
                    $public_id = mysql_insert_id();
                    include_once 'class.Account.php';
                    $account           = new Account();
                    $invoice_public_id = sprintf(
                        $account->data['Account Invoice Public ID Format'], $public_id
                    );

                }

            }

            if ($invoice_public_id != '') {
                $invoice_public_id = $this->get_refund_public_id(
                    $invoice_public_id.$store->data['Store Refund Suffix']
                );
            }
            //====


            $this->data['Invoice Public ID'] = $invoice_public_id;
        }


        $this->data['Invoice File As'] = $this->prepare_file_as(
            $this->data['Invoice Public ID']
        );

        $this->data ['Invoice Currency Exchange'] = 1;
        $sql                                      = sprintf(
            "SELECT `Account Currency` FROM `Account Dimension`"
        );
        $res                                      = mysql_query($sql);
        if ($row = mysql_fetch_array($res)) {
            $corporation_currency_code = $row['Account Currency'];
        } else {
            $corporation_currency_code = 'GBP';
        }
        if ($this->data ['Invoice Currency'] != $corporation_currency_code) {


            $currency_exchange = new CurrencyExchange(
                $this->data ['Invoice Currency'].$corporation_currency_code, gmdate('Y-m-d', strtotime($this->data['Invoice Date'].' +0:00'))
            );


            $this->data ['Invoice Currency Exchange']
                = $currency_exchange->get_exchange();


        }


        $this->create_header();

        if (count($this->data ['Invoice Sales Representative Keys']) == 0) {
            $sql = sprintf(
                "INSERT INTO `Invoice Sales Representative Bridge` VALUES (%d,0,1)", $this->id
            );
            mysql_query($sql);
        } else {
            $share = 1 / count(
                    $this->data ['Invoice Sales Representative Keys']
                );
            foreach (
                $this->data ['Invoice Sales Representative Keys'] as $sale_rep_key
            ) {
                $sql = sprintf(
                    "INSERT INTO `Invoice Sales Representative Bridge` VALUES (%d,%d,%f)", $this->id, $sale_rep_key, $share
                );
                mysql_query($sql);
            }
        }


        if (isset($invoice_data['Order Key']) and $invoice_data['Order Key']) {
            $sql = sprintf(
                "INSERT INTO `Order Invoice Bridge` VALUES (%d,%d)", $invoice_data['Order Key'], $this->id
            );
            mysql_query($sql);
            //print $sql;

        }


        //$this->categorize();
        $this->update_title();
    }

    function set_data_from_customer($customer_key, $store_key = false) {


        $customer = new Customer($customer_key);
        if (!$customer->id) {
            exit("error customer not found");

            //$customer= new Customer('create anonymous');
        } else {
            $store_key = $customer->data['Customer Store Key'];
        }


        $this->data['Invoice Customer Name']         = $customer->get(
            'Customer Name'
        );
        $this->data['Invoice Customer Contact Name'] = $customer->get(
            'Customer Main Contact Name'
        );


        $this->data['Invoice For Partner'] = 'No';
        $this->data['Invoice For']         = 'Customer';

        switch ($customer->data['Customer Level Type']) {
            case'Partner':
                $this->data['Invoice For Partner'] = 'Yes';
                break;
            case'Staff':
                $this->data['Invoice For'] = 'Staff';
                break;

        }

        $this->data['Invoice Customer Level Type']
            = $customer->data['Customer Level Type'];


        $this->data['Invoice Main Payment Method'] = $customer->get(
            'Customer Last Payment Method'
        );

        //print_r($this->data);
        $this->set_data_from_store($store_key);


        return $customer;


    }

    function set_data_from_store($store_key) {
        $store = new Store($store_key);
        if (!$store->id) {
            $this->error = true;

            return;
        }


        $this->data['Invoice Currency']    = $store->data['Store Currency Code'];
        $this->data['Invoice Store Code']  = $store->data['Store Code'];
        $this->data['Invoice XHTML Store'] = sprintf(
            "<a href='store.php?id=%d'>%s</a>", $store->id, $store->get('Store Name')
        );

        $this->public_id_format_order
            = $store->data['Store Order Public ID Format'];
        $this->public_id_format_invoice
            = $store->data['Store Invoice Public ID Format'];


    }

    function get_refund_public_id($refund_id, $suffix_counter = '') {
        $sql = sprintf(
            "SELECT `Invoice Public ID` FROM `Invoice Dimension` WHERE `Invoice Store Key`=%d AND `Invoice Public ID`=%s ", $this->data['Invoice Store Key'], prepare_mysql($refund_id.$suffix_counter)
        );
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            if ($suffix_counter > 100) {
                return $refund_id.$suffix_counter;
            }

            if (!$suffix_counter) {
                $suffix_counter = 2;
            } else {
                $suffix_counter++;
            }

            return $this->get_refund_public_id($refund_id, $suffix_counter);

        } else {
            return $refund_id.$suffix_counter;
        }

    }

    function prepare_file_as($number) {

        $number = strtolower($number);
        if (preg_match("/^\d+/", $number, $match)) {
            $part_number = $match[0];
            $file_as     = preg_replace(
                '/^\d+/', sprintf("%012d", $part_number), $number
            );

        } elseif (preg_match("/\d+$/", $number, $match)) {
            $part_number = $match[0];
            $file_as     = preg_replace(
                '/\d+$/', sprintf("%012d", $part_number), $number
            );

        } else {
            $file_as = $number;
        }

        return $file_as;
    }

    function create_header() {

        //calculate the order total
        $this->data ['Invoice Gross Amount']    = 0;
        $this->data ['Invoice Discount Amount'] = 0;

        if (!isset($this->data ['Invoice Delivery Town'])) {
            $this->data ['Invoice Delivery Town'] = '';
        }
        if (!isset($this->data ['Invoice Delivery Postal Code'])) {
            $this->data ['Invoice Delivery Postal Code'] = '';
        }
        if (!isset($this->data ['Invoice Billing Town'])) {
            $this->data ['Invoice Billing Town'] = '';
        }
        if (!isset($this->data ['Invoice Billing Postal Code'])) {
            $this->data ['Invoice Billing Postal Code'] = '';
        }

        if (!isset($this->data ['Invoice Billing Country 2 Alpha Code'])) {
            $this->data ['Invoice Billing Country 2 Alpha Code'] = 'XX';
            $this->data ['Invoice Billing Country Code']         = 'UNK';
            $this->data ['Invoice Billing World Region Code']    = 'UNKN';

        }
        if (!isset($this->data ['Invoice Delivery Country 2 Alpha Code'])) {
            $this->data ['Invoice Delivery Country 2 Alpha Code'] = 'XX';
            $this->data ['Invoice Delivery World Region Code']    = 'UNKN';
            $this->data ['Invoice Delivery Country Code']         = 'UNK';

        }


        $sql = sprintf(
            "INSERT INTO `Invoice Dimension` (
		`Invoice Tax Number`,`Invoice Tax Number Valid`,`Invoice Tax Number Validation Date`,`Invoice Tax Number Associated Name`,`Invoice Tax Number Associated Address`,

		`Invoice Customer Level Type`,

                         `Invoice Tax Charges Code`,`Invoice Customer Contact Name`,`Invoice Currency`,
                         `Invoice Currency Exchange`,
                         `Invoice For`,`Invoice Date`,`Invoice Public ID`,`Invoice File As`,`Invoice Store Key`,`Invoice Store Code`,`Invoice Main Source Type`,`Invoice Customer Key`,`Invoice Customer Name`,

                         `Invoice Items Gross Amount`,`Invoice Items Discount Amount`,
                         `Invoice Charges Net Amount`,`Invoice Total Tax Amount`,`Invoice Refund Net Amount`,`Invoice Refund Tax Amount`,`Invoice Total Amount`,


                         `Invoice Metadata`,
                         `Invoice XHTML Address`,`Invoice XHTML Orders`,`Invoice XHTML Delivery Notes`,`Invoice XHTML Store`,`Invoice Has Been Paid In Full`,`Invoice Main Payment Method`
                         ,`Invoice Charges Tax Amount`,


                         `Invoice Billing Country 2 Alpha Code`,
                         `Invoice Billing Country Code`,
                         `Invoice Billing World Region Code`,
                         `Invoice Billing Town`,
                         `Invoice Billing Postal Code`,

                         `Invoice Delivery Country 2 Alpha Code`,
                         `Invoice Delivery Country Code`,
                         `Invoice Delivery World Region Code`,
                         `Invoice Delivery Town`,
                         `Invoice Delivery Postal Code`,

                         `Invoice Dispatching Lag`,`Invoice Taxable`,`Invoice Tax Code`,`Invoice Type`,`Invoice Outstanding Total Amount`,
                         `Invoice Net Amount Off`
                         ) VALUES
                         (
                          %s,%s,%s,%s,%s,
                         %s,
                         %s,%s,%s,
                         %f,
                         %s,%s,%s,%s,%s,%s,%s,%s,%s,
                         %.2f,%.2f,%.2f,%.2f,%.2f,%.2f,%.2f,
                         %s,%s, %s, %s,%s,%s,%s,
                         %.2f,


                         %s, %s, %s, %s,%s,

                         %s, %s, %s, %s,%s,

                         %s,%s,%s,%s,%f,%.2f)"

            , prepare_mysql($this->data ['Invoice Tax Number']), prepare_mysql($this->data ['Invoice Tax Number Valid']), prepare_mysql($this->data ['Invoice Tax Number Validation Date']),
            prepare_mysql($this->data ['Invoice Tax Number Associated Name']), prepare_mysql($this->data ['Invoice Tax Number Associated Address'])

            , prepare_mysql($this->data ['Invoice Customer Level Type'])

            , prepare_mysql($this->data ['Invoice Tax Charges Code']), prepare_mysql($this->data ['Invoice Customer Contact Name'], false), prepare_mysql($this->data ['Invoice Currency'])

            , $this->data ['Invoice Currency Exchange']

            , prepare_mysql($this->data ['Invoice For']), prepare_mysql($this->data ['Invoice Date']), prepare_mysql($this->data ['Invoice Public ID']), prepare_mysql($this->data ['Invoice File As']),
            prepare_mysql($this->data ['Invoice Store Key']), prepare_mysql($this->data ['Invoice Store Code']), prepare_mysql($this->data ['Invoice Main Source Type']),
            prepare_mysql($this->data ['Invoice Customer Key']), prepare_mysql($this->data ['Invoice Customer Name'], false),


            $this->data ['Invoice Items Gross Amount'], $this->data ['Invoice Items Discount Amount'], $this->data ['Invoice Charges Net Amount'], $this->data ['Invoice Total Tax Amount'],
            $this->data ['Invoice Refund Net Amount'], $this->data ['Invoice Refund Tax Amount'], $this->data ['Invoice Total Amount']

            , prepare_mysql($this->data ['Invoice Metadata']), prepare_mysql($this->data ['Invoice XHTML Address']), prepare_mysql($this->data ['Invoice XHTML Orders']),
            prepare_mysql($this->data ['Invoice XHTML Delivery Notes']), prepare_mysql($this->data ['Invoice XHTML Store']), prepare_mysql($this->data ['Invoice Has Been Paid In Full']),
            prepare_mysql($this->data ['Invoice Main Payment Method'])

            , $this->data ['Invoice Charges Tax Amount']


            , prepare_mysql($this->data ['Invoice Billing Country 2 Alpha Code']), prepare_mysql($this->data ['Invoice Billing Country Code']),
            prepare_mysql($this->data ['Invoice Billing World Region Code']), prepare_mysql($this->data ['Invoice Billing Town']), prepare_mysql($this->data ['Invoice Billing Postal Code'])


            , prepare_mysql($this->data ['Invoice Delivery Country 2 Alpha Code']), prepare_mysql($this->data ['Invoice Delivery Country Code']),
            prepare_mysql($this->data ['Invoice Delivery World Region Code']), prepare_mysql($this->data ['Invoice Delivery Town']), prepare_mysql($this->data ['Invoice Delivery Postal Code'])

            , prepare_mysql($this->data ['Invoice Dispatching Lag']), prepare_mysql($this->data ['Invoice Taxable']), prepare_mysql($this->data ['Invoice Tax Code']),
            prepare_mysql($this->data ['Invoice Type']), $this->data ['Invoice Total Amount'], $this->data['Invoice Net Amount Off']

        );


        if (mysql_query($sql)) {

            $this->data ['Invoice Key'] = mysql_insert_id();

            $this->id = $this->data ['Invoice Key'];
            $sql      = sprintf(
                "INSERT INTO `Invoice Tax Dimension` (`Invoice Key`) VALUES (%d)", $this->data ['Invoice Key']
            );

            mysql_query($sql);


        } else {

            exit ("$sql Error can not create order header");
        }

    }



    function update_title() {

        $this->data['Invoice Title'] = $this->get_title();

        $sql = sprintf(
            "UPDATE `Invoice Dimension` SET `Invoice Title`=%s WHERE `Invoice Key`=%d", prepare_mysql($this->data['Invoice Title']), $this->id
        );
        mysql_query($sql);
    }

    function get_title() {

        $orders = $this->get_orders_objects();

        $number_of_orders = count($orders);

        if ($number_of_orders == 0) {
            if ($this->data['Invoice Type'] == 'Invoice') {
                $title = _("Invoice");
            } elseif ($this->data['Invoice Type'] == 'CreditNote') {
                $title = _("Credit Note");
            } else {
                $title = _("Refund");

            }

            return $title;
        }


        if ($this->data['Invoice Type'] == 'Invoice') {
            $title = ngettext(
                    "Invoice for order", "Invoice for orders", $number_of_orders
                ).' ';
        } elseif ($this->data['Invoice Type'] == 'CreditNote') {
            $title = ngettext(
                    "Credit note for order", "Credit note for orders", $number_of_orders
                ).' ';
        } else {
            $title = ngettext(
                    "Refund for order", "Refund for orders", $number_of_orders
                ).' ';

        }

        foreach ($orders as $order) {
            $title .= sprintf(
                '<a class="id" href="order.php?id=%d">%s</a>, ', $order->id, $order->data['Order Public ID']
            );
        }

        $title = preg_replace('/\, $/', '', $title);

        return $title;
    }

    function get_orders_objects() {

        $orders     = array();
        $orders_ids = $this->get_orders_ids();
        foreach ($orders_ids as $order_id) {
            $order = new Order($order_id);
            if ($order->id) {
                $orders[$order_id] = $order;
            }
        }

        return $orders;
    }

    function get_orders_ids() {
        $orders = array();
        $sql    = sprintf(
            "SELECT `Order Key` FROM `Order Transaction Fact` WHERE `Invoice Key`=%d  OR  `Refund Key`=%d  GROUP BY `Order Key`", $this->id, $this->id
        );
        //print "$sql\n";
        $res = mysql_query($sql);

        while ($row = mysql_fetch_assoc($res)) {
            if ($row['Order Key'] > 0) {
                $orders[$row['Order Key']] = $row['Order Key'];
            }

        }

        $sql = sprintf(
            "SELECT `Order Key` FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d  OR  `Refund Key`=%d  GROUP BY `Order Key`", $this->id, $this->id
        );
        //print "$sql\n";

        $res = mysql_query($sql);

        while ($row = mysql_fetch_assoc($res)) {
            if ($row['Order Key'] > 0) {
                $orders[$row['Order Key']] = $row['Order Key'];
            }

        }


        return $orders;

    }

    protected function create($invoice_data) {


        $order_key = $invoice_data['Order Key'];

        $this->data = $this->base_data();
        $customer   = $this->set_data_from_customer(
            $invoice_data['Invoice Customer Key'], $invoice_data['Invoice Store Key']
        );

        foreach ($invoice_data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }


        if (array_key_exists(
            'Invoice Sales Representative Keys', $invoice_data
        )) {
            $this->data ['Invoice Sales Representative Keys']
                = $invoice_data['Invoice Sales Representative Keys'];
        } else {
            $this->data ['Invoice Sales Representative Keys']
                = array($this->editor['User Key']);
        }

        if (array_key_exists('Invoice Processed By Keys', $invoice_data)) {
            $this->data ['Invoice Processed By Keys']
                = $invoice_data['Invoice Processed By Keys'];
        } else {
            $this->data ['Invoice Processed By Keys']
                = array($this->editor['User Key']);
        }

        if (array_key_exists('Invoice Net Amount Off', $invoice_data)) {
            $this->data ['Invoice Net Amount Off']
                = $invoice_data['Invoice Net Amount Off'];
        } else {
            $this->data ['Invoice Net Amount Off'] = 0;
        }


        if (array_key_exists('Invoice Charged By Keys', $invoice_data)) {
            $this->data ['Invoice Charged By Keys']
                = $invoice_data['Invoice Charged By Keys'];
        } else {
            $this->data ['Invoice Charged By Keys']
                = array($this->editor['User Key']);
        }

        if (array_key_exists('Invoice Tax Number', $invoice_data)) {
            $this->data ['Invoice Tax Number']
                = $invoice_data['Invoice Tax Number'];
        }
        if (array_key_exists('Invoice Tax Number Valid', $invoice_data)) {
            $this->data ['Invoice Tax Number Valid']
                = $invoice_data['Invoice Tax Number Valid'];
        }
        if (array_key_exists(
            'Invoice Tax Number Validation Date', $invoice_data
        )) {
            $this->data ['Invoice Tax Number Validation Date']
                = $invoice_data['Invoice Tax Number Validation Date'];
        }
        if (array_key_exists(
            'Invoice Tax Number Associated Name', $invoice_data
        )) {
            $this->data ['Invoice Tax Number Associated Name']
                = $invoice_data['Invoice Tax Number Associated Name'];
        }
        if (array_key_exists(
            'Invoice Tax Number Associated Address', $invoice_data
        )) {
            $this->data ['Invoice Tax Number Associated Address']
                = $invoice_data['Invoice Tax Number Associated Address'];
        }


        if ($invoice_data['Invoice Billing To Key']) {
            $billing_to = new Billing_To(
                $invoice_data['Invoice Billing To Key']
            );
        } else {
            $billing_to = $customer->get_billing_to(
                $this->data ['Invoice Date']
            );
        }


        $this->data ['Invoice Billing To Key'] = $billing_to->id;
        $this->data ['Invoice XHTML Address']
                                               = $billing_to->data['Billing To XHTML Address'];
        $this->data ['Invoice Billing Country 2 Alpha Code']
                                               = ($billing_to->data['Billing To Country 2 Alpha Code'] == '' ? 'XX' : $billing_to->data['Billing To Country 2 Alpha Code']);

        $this->data ['Invoice Billing Country Code']
                                                          = ($billing_to->data['Billing To Country Code'] == '' ? 'UNK' : $billing_to->data['Billing To Country Code']);
        $this->data ['Invoice Billing World Region Code'] = $billing_to->get(
            'World Region Code'
        );
        $this->data ['Invoice Billing Town']
                                                          = $billing_to->data['Billing To Town'];
        $this->data ['Invoice Billing Postal Code']
                                                          = $billing_to->data['Billing To Postal Code'];


        if (array_key_exists('Invoice Public ID', $invoice_data) and $this->data['Invoice Public ID'] != '') {
            $this->data['Invoice File As'] = $this->prepare_file_as(
                $this->data['Invoice Public ID']
            );


        } else {
            $store = new Store($this->data['Invoice Store Key']);
            if ($store->data['Store Next Invoice Public ID Method'] == 'Invoice Public ID') {

                $this->next_public_id($store->data['Store Refund Suffix']);

            } else {

                $this->next_order_public_id(
                    $store->data['Store Refund Suffix']
                );
            }


        }


        $exchange = 1;
        $sql      = sprintf(
            "SELECT `Account Currency` FROM `Account Dimension`"
        );
        $res      = mysql_query($sql);
        if ($row = mysql_fetch_array($res)) {
            $corporation_currency_code = $row['Account Currency'];
        } else {
            $corporation_currency_code = 'GBP';
        }
        if ($this->data ['Invoice Currency'] != $corporation_currency_code) {


            $currency_exchange = new CurrencyExchange(
                $this->data ['Invoice Currency'].$corporation_currency_code, gmdate('Y-m-d', strtotime($this->data['Invoice Date'].' +0:00'))
            );
            $exchange          = $currency_exchange->get_exchange();


        }


        $this->data ['Invoice Currency Exchange'] = $exchange;

        $this->create_header();

        if (count($this->data ['Invoice Sales Representative Keys']) == 0) {
            $sql = sprintf(
                "INSERT INTO `Invoice Sales Representative Bridge` VALUES (%d,0,1)", $this->id
            );
            mysql_query($sql);
        } else {
            $share = 1 / count(
                    $this->data ['Invoice Sales Representative Keys']
                );
            foreach (
                $this->data ['Invoice Sales Representative Keys'] as $sale_rep_key
            ) {
                $sql = sprintf(
                    "INSERT INTO `Invoice Sales Representative Bridge` VALUES (%d,%d,%f)", $this->id, $sale_rep_key, $share
                );
                mysql_query($sql);
            }
        }


        $delivery_notes_ids = array();
        foreach (
            preg_split('/\,/', $invoice_data['Delivery Note Keys']) as $dn_key
        ) {
            $delivery_notes_ids[$dn_key] = $dn_key;
        }
        $dn_keys       = join(',', $delivery_notes_ids);
        $shipping_net  = 0;
        $shipping_tax  = 0;
        $charges_net   = 0;
        $charges_tax   = 0;
        $insurance_net = 0;

        $insurance_tax = 0;


        $tax_category = $this->data['Invoice Tax Code'];
        $sql          = sprintf(
            'SELECT OTF.`Order Transaction Fact Key`,IFNULL(`Fraction Discount`,0) AS `Fraction Discount` ,`Product History Price`,`No Shipped Due Other`,`No Shipped Due Not Found`,`No Shipped Due No Authorized`,`No Shipped Due Out of Stock`,OTF.`Order Quantity`,`Order Transaction Amount`,`Transaction Tax Rate`
		FROM `Order Transaction Fact` OTF LEFT JOIN
		`Product History Dimension` PHD ON (PHD.`Product Key`=OTF.`Product Key`)  LEFT JOIN `Order Transaction Deal Bridge` OTDB ON (OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) WHERE OTF.`Order Key`=%d AND ISNULL(OTF.`Invoice Key`)  ',
            $order_key
        );
        $res          = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {


            //$gross=$row['Order Transaction Gross Amount'];
            //$discount=$row['Order Transaction Total Discount Amount'];
            //$net=$row['Order Transaction Amount'];
            // $tax=round($net*$row['Transaction Tax Rate'],2);


            $chargeable_qty    = $row['Order Quantity'] - $row['No Shipped Due Out of Stock'] - $row['No Shipped Due No Authorized'] - $row['No Shipped Due Not Found'] - $row['No Shipped Due Other'];
            $gross             = $chargeable_qty * $row['Product History Price'];
            $discount_fraction = 0;
            $sql2              = sprintf(
                "SELECT max(`Fraction Discount`) AS fraction FROM `Order Transaction Deal Bridge` WHERE `Order Transaction Fact Key`=%d", $row['Order Transaction Fact Key']
            );
            $res2              = mysql_query($sql2);
            if ($row2 = mysql_fetch_assoc($res2)) {
                $discount_fraction = $row2['fraction'];
            }

            $discount = round($gross * $discount_fraction, 2);
            $net      = round($gross - $discount, 2);
            $tax      = round($net * $row['Transaction Tax Rate'], 2);


            $sql = sprintf(
                "UPDATE `Order Transaction Fact` SET
					`Invoice Currency Exchange Rate`=%f,
					`Invoice Date`=%s,
					`Invoice Currency Code`=%s,
					`Invoice Key`=%d,
					`Invoice Public ID`=%s,
					`Invoice Quantity`=%f,
					`Invoice Transaction Gross Amount`=%.2f,
					`Invoice Transaction Total Discount Amount`=%.2f,
					`Invoice Transaction Item Tax Amount`=%.3f,
					`Invoice Transaction Outstanding Net Balance`=%.2f,
					`Invoice Transaction Outstanding Tax Balance`=%.2f


						WHERE `Order Transaction Fact Key`=%d", ($this->data['Invoice Currency Exchange'] == '' ? 1 : $this->data['Invoice Currency Exchange']),
                prepare_mysql($this->data['Invoice Date']), prepare_mysql($this->data['Invoice Currency']), $this->id, prepare_mysql($this->data['Invoice Public ID']), $chargeable_qty,

                $gross, $discount, $tax, $net, $tax, $row['Order Transaction Fact Key']
            );
            mysql_query($sql);
            //  print "$sql\n";
        }


        $sql = sprintf(
            "SELECT `Order No Product Transaction Fact Key`,`Transaction Net Amount`,`Transaction Tax Amount`,`Transaction Type`  FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d AND ISNULL(`Invoice Key`) ",
            $order_key
        );
        $res = mysql_query($sql);

        while ($row = mysql_fetch_assoc($res)) {
            $sql = sprintf(
                "UPDATE `Order No Product Transaction Fact` SET
				`Invoice Date`=%s,
				`Invoice Key`=%d,
				`Transaction Invoice Net Amount`=%.2f,
				`Transaction Invoice Tax Amount`=%.2f,
				`Transaction Outstanding Net Amount Balance`=%.2f,
				`Transaction Outstanding Tax Amount Balance`=%.2f WHERE `Order No Product Transaction Fact Key`=%d", prepare_mysql($this->data['Invoice Date']), $this->id,
                $row['Transaction Net Amount'], $row['Transaction Tax Amount'], $row['Transaction Net Amount'], $row['Transaction Tax Amount'], $row['Order No Product Transaction Fact Key']
            );
            mysql_query($sql);


            if ($row['Transaction Type'] == 'Shipping') {

                $shipping_net += $row['Transaction Net Amount'];
                $shipping_tax += $row['Transaction Tax Amount'];
            }

            if ($row['Transaction Type'] == 'Charges') {

                $charges_net += $row['Transaction Net Amount'];
                $charges_tax += $row['Transaction Tax Amount'];
            }

            if ($row['Transaction Type'] == 'Insurance') {

                $insurance_net += $row['Transaction Net Amount'];
                $insurance_tax += $row['Transaction Tax Amount'];
            }


        }

        /*


			$sql=sprintf('select *  from `Order No Product Transaction Fact` where `Order Key`=%d and ISNULL(`Invoice Key`) ',
				$orders_key
				);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {

				$sql=sprintf("update `Order No Product Transaction Fact` set `Invoice Date`=%s,`Invoice Key`=%d,
				`Transaction Invoice Net Amount`=%.2f,
				`Transaction Invoice Tax Amount`=%.2f,`Transaction Outstanding Net Amount Balance`=%.2f,`Transaction Outstanding Tax Amount Balance`=%.2f where `Order No Product Transaction Fact Key`=%d",
					prepare_mysql($this->data['Invoice Date']),
					$this->id,
					$row['Transaction Net Amount'],
					$row['Transaction Tax Amount'],
					$row['Transaction Net Amount'],
					$row['Transaction Tax Amount'],
					$row['Order No Product Transaction Fact Key']
				);

				//print $sql;
				mysql_query($sql);



			}

	*/


        $sql = sprintf(
            "UPDATE `Invoice Dimension` SET `Invoice Charges Net Amount`=%f,`Invoice Charges Tax Amount`=%f WHERE `Invoice Key`=%d", $charges_net, $charges_tax, $this->id
        );
        mysql_query($sql);
        $this->data['Invoice Charges Net Amount'] = $charges_net;
        $this->data['Invoice Charges Tax Amount'] = $charges_tax;

        $this->distribute_charges_over_the_otf();


        $sql = sprintf(
            "UPDATE `Invoice Dimension` SET `Invoice Shipping Net Amount`=%f,`Invoice Shipping Tax Amount`=%f WHERE `Invoice Key`=%d", $shipping_net, $shipping_tax, $this->id
        );
        mysql_query($sql);
        $this->data['Invoice Shipping Net Amount'] = $shipping_net;
        $this->data['Invoice Shipping Tax Amount'] = $shipping_tax;

        $this->distribute_shipping_over_the_otf();


        $sql = sprintf(
            "UPDATE `Invoice Dimension` SET `Invoice Insurance Net Amount`=%f,`Invoice Insurance Tax Amount`=%f WHERE `Invoice Key`=%d", $insurance_net, $insurance_tax, $this->id
        );
        mysql_query($sql);
        $this->data['Invoice Insurance Net Amount'] = $insurance_net;
        $this->data['Invoice Insurance Tax Amount'] = $insurance_tax;

        $this->distribute_insurance_over_the_otf();

        $this->update_totals();

        //$this->update_shipping(array('Amount'=>$shipping_net,'Tax'=>$shipping_tax),true);
        //$this->update_charges(array('Transaction Invoice Net Amount'=>$charges_net,'Invoice Charges Tax Amount'=>$charges_tax,'Transaction Description'=>_('Charges')),true);

        $this->update_refund_totals();


        $this->update_title();
        $this->update_totals();
    }

    function next_public_id($suffix = '') {
        $sql = sprintf(
            "UPDATE `Store Dimension` SET `Store Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Store Invoice Last Invoice Public ID` + 1) WHERE `Store Key`=%d", $this->data['Invoice Store Key']
        );
        mysql_query($sql);
        $public_id = mysql_insert_id();


        $this->data['Invoice Public ID'] = sprintf(
                $this->public_id_format_invoice, $public_id
            ).$suffix;
        $this->data['Invoice File As']   = $this->prepare_file_as(
            $this->data['Invoice Public ID']
        );
    }

    function next_order_public_id($suffix = '') {
        $sql = sprintf(
            "UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) WHERE `Store Key`=%d", $this->data['Invoice Store Key']
        );
        mysql_query($sql);
        $public_id = mysql_insert_id();


        $this->data['Invoice Public ID'] = sprintf(
                $this->public_id_format_order, $public_id
            ).$suffix;
        $this->data['Invoice File As']   = $this->prepare_file_as(
            $this->data['Invoice Public ID']
        );
    }

    function distribute_charges_over_the_otf() {
        $sql = sprintf(
            "SELECT `Order Transaction Fact Key`,`Order Transaction Gross Amount` FROM `Order Transaction Fact` WHERE `Invoice Key`=%d", $this->id
        );

        //print $sql;
        $result = mysql_query($sql);

        $total_charge  = 0;
        $charge_factor = array();

        $items = 0;
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            //print_r($row);
            $items++;
            $charge = $row ['Order Transaction Gross Amount'];
            $total_charge += $charge;
            $charge_factor [$row ['Order Transaction Fact Key']] = $charge;
        }
        if ($items == 0) {
            return;
        }

        foreach ($charge_factor as $line_number => $factor) {
            if ($total_charge == 0) {
                $charges    = $this->data ['Invoice Charges Net Amount'] * $factor / $items;
                $charge_tax = $this->data ['Invoice Charges Tax Amount'] * $factor / $items;
            } else {
                $charges    = $this->data ['Invoice Charges Net Amount'] * $factor / $total_charge;
                $charge_tax = $this->data ['Invoice Charges Tax Amount'] * $factor / $total_charge;

            }


            $sql = sprintf(
                "UPDATE `Order Transaction Fact` SET `Invoice Transaction Charges Amount`=%.4f, `Invoice Transaction Charges Tax Amount`=%.6f WHERE `Order Transaction Fact Key`=%d ", $charges,
                $charge_tax, $line_number
            );
            mysql_query($sql);
            //print "$sql\n";
        }


    }

    function distribute_shipping_over_the_otf() {


        $sql           = sprintf(
            "SELECT `Order Transaction Fact Key`,`Estimated Weight` FROM `Order Transaction Fact` WHERE `Invoice Key`=%d", $this->id
        );
        $result        = mysql_query($sql);
        $total_weight  = 0;
        $weight_factor = array();

        //print $this->data ['Invoice Shipping Net Amount']." <---   $sql\n\n";
        $items = 0;
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $items++;
            $weight = $row ['Estimated Weight'];
            $total_weight += $weight;
            $weight_factor [$row ['Order Transaction Fact Key']] = $weight;
        }
        //print "i: $items  $w: \n\n";
        // TODO horrible hack when there is not stitamed weight in system, it should be not extimted weights in system!!!!!
        if ($total_weight == 0) {
            foreach ($weight_factor as $_key => $_value) {
                $weight_factor[$_key] = 1;
            }

        }


        if ($items == 0) {
            return;
        }
        foreach ($weight_factor as $line_number => $factor) {
            if ($total_weight == 0) {
                $shipping     = $this->data ['Invoice Shipping Net Amount'] * $factor / $items;
                $shipping_tax = $this->data ['Invoice Shipping Tax Amount'] * $factor / $items;
            } else {
                $shipping     = $this->data ['Invoice Shipping Net Amount'] * $factor / $total_weight;
                $shipping_tax = $this->data ['Invoice Shipping Tax Amount'] * $factor / $total_weight;
            }


            $sql = sprintf(
                "UPDATE `Order Transaction Fact` SET `Invoice Transaction Shipping Amount`=%.4f, `Invoice Transaction Shipping Tax Amount`=%.6f WHERE `Order Transaction Fact Key`=%d ", $shipping,
                $shipping_tax, $line_number
            );
            // print "$sql\n\n";
            mysql_query($sql);
        }


    }

    function distribute_insurance_over_the_otf() {
        $sql = sprintf(
            "SELECT `Order Transaction Fact Key`,`Order Transaction Gross Amount` FROM `Order Transaction Fact` WHERE `Invoice Key`=%d", $this->id
        );

        //print $sql;
        $result = mysql_query($sql);

        $total_insurance  = 0;
        $insurance_factor = array();

        $items = 0;
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            //print_r($row);
            $items++;
            $_insurance = $row ['Order Transaction Gross Amount'];
            $total_insurance += $_insurance;
            $insurance_factor [$row ['Order Transaction Fact Key']]
                = $_insurance;
        }
        if ($items == 0) {
            return;
        }

        foreach ($insurance_factor as $line_number => $factor) {
            if ($total_insurance == 0) {
                $insurance     = $this->data ['Invoice Insurance Net Amount'] * $factor / $items;
                $insurance_tax = $this->data ['Invoice Insurance Tax Amount'] * $factor / $items;
            } else {
                $insurance     = $this->data ['Invoice Insurance Net Amount'] * $factor / $total_insurance;
                $insurance_tax = $this->data ['Invoice Insurance Tax Amount'] * $factor / $total_insurance;

            }


            $sql = sprintf(
                "UPDATE `Order Transaction Fact` SET `Invoice Transaction Insurance Amount`=%.4f, `Invoice Transaction Insurance Tax Amount`=%.6f WHERE `Order Transaction Fact Key`=%d ", $insurance,
                $insurance_tax, $line_number
            );
            mysql_query($sql);
            //print "$sql\n";
        }

    }

    function update_totals() {
        global $account;

        if ($account->data['Apply Tax Method'] == 'Per Item') {
            $this->update_totals_per_item_method();
        } else {
            $this->update_totals_per_total_method();
        }
    }

    function update_totals_per_item_method() {


        $shipping_net                         = 0;
        $shipping_tax                         = 0;
        $charges_net                          = 0;
        $charges_tax                          = 0;
        $insurance_tax                        = 0;
        $insurance_net                        = 0;
        $items_gross                          = 0;
        $items_discounts                      = 0;
        $items_net                            = 0;
        $items_tax                            = 0;
        $items_refund_net                     = 0;
        $items_refund_tax                     = 0;
        $items_net_outstanding_balance        = 0;
        $items_tax_outstanding_balance        = 0;
        $items_refund_net_outstanding_balance = 0;
        $items_refund_tax_outstanding_balance = 0;
        $deal_credit_net                      = 0;
        $deal_credit_tax                      = 0;
        $adjust_tax                           = 0;
        $adjust_net                           = 0;


        $sql = sprintf(
            "SELECT `Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Outstanding Net Balance`,`Invoice Transaction Outstanding Tax Balance`,`Invoice Transaction Outstanding Refund Net Balance`,`Invoice Transaction Outstanding Refund Tax Balance`,`Invoice Transaction Net Refund Amount`,`Invoice Transaction Tax Refund Amount`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Invoice Transaction Charges Amount`,`Invoice Transaction Charges Tax Amount`,`Invoice Transaction Shipping Amount`,`Invoice Transaction Shipping Tax Amount`,`Order Transaction Fact Key`,`Invoice Transaction Shipping Tax Amount`,`Invoice Transaction Charges Tax Amount`,(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) AS item_net ,`Invoice Transaction Item Tax Amount`
                       FROM `Order Transaction Fact` WHERE `Invoice Key`=%d   ", $this->data ['Invoice Key']
        );

        //  print $sql;
        // print "$\n";
        $counter = 0;
        $res     = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $counter++;
            $items_net += $row['item_net'];
            $items_tax += $row['Invoice Transaction Item Tax Amount'];
            $items_net_outstanding_balance += $row['Invoice Transaction Outstanding Net Balance'];
            $items_tax_outstanding_balance += $row['Invoice Transaction Outstanding Tax Balance'];


            $items_gross += $row['Invoice Transaction Gross Amount'];
            $items_discounts += $row['Invoice Transaction Total Discount Amount'];
        }


        $sql = sprintf(
            "SELECT * FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d", $this->id
        );

        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            if ($row['Transaction Type'] == 'Shipping') {
                $shipping_net += $row['Transaction Invoice Net Amount'];
                $shipping_tax += $row['Transaction Invoice Tax Amount'];
            } elseif ($row['Transaction Type'] == 'Charges') {
                $charges_net += $row['Transaction Invoice Net Amount'];
                $charges_tax += $row['Transaction Invoice Tax Amount'];
            } elseif ($row['Transaction Type'] == 'Insurance') {
                $insurance_net += $row['Transaction Invoice Net Amount'];
                $insurance_tax += $row['Transaction Invoice Tax Amount'];
            } elseif ($row['Transaction Type'] == 'Adjust') {
                $adjust_net += $row['Transaction Invoice Net Amount'];
                $adjust_tax += $row['Transaction Invoice Tax Amount'];
            } elseif ($row['Transaction Type'] == 'Deal') {
                $deal_credit_net += $row['Transaction Invoice Net Amount'];
                $deal_credit_tax += $row['Transaction Invoice Tax Amount'];
            } elseif ($row['Transaction Type'] == 'Credit') {

                $items_refund_net += $row['Transaction Invoice Net Amount'];
                $items_refund_tax += $row['Transaction Invoice Tax Amount'];
                $items_refund_net_outstanding_balance += $row['Transaction Outstanding Net Amount Balance'];
                $items_refund_tax_outstanding_balance += $row['Transaction Outstanding Tax Amount Balance'];

            } else {


            }
        }
        $this->data['Invoice Total Net Adjust Amount'] = $adjust_net;
        $this->data['Invoice Total Tax Adjust Amount'] = $adjust_tax;
        $this->data['Invoice Total Adjust Amount']     = $adjust_tax + $adjust_net;
        $this->data['Invoice Refund Net Amount']       = $items_refund_net;
        $this->data['Invoice Refund Tax Amount']       = $items_refund_tax;
        $this->data['Invoice Shipping Tax Amount']     = $shipping_tax;
        $this->data['Invoice Shipping Net Amount']     = $shipping_net;
        $this->data['Invoice Charges Tax Amount']      = $charges_tax;
        $this->data['Invoice Charges Net Amount']      = $charges_net;
        $this->data['Invoice Insurance Tax Amount']    = $insurance_tax;
        $this->data['Invoice Insurance Net Amount']    = $insurance_net;


        $this->data['Invoice Items Tax Amount']       = $items_tax;
        $this->data['Invoice Items Net Amount']       = $items_net;
        $this->data['Invoice Deal Credit Tax Amount'] = $deal_credit_tax;
        $this->data['Invoice Deal Credit Net Amount'] = $deal_credit_net;

        $this->data['Invoice Items Gross Amount']    = $items_gross;
        $this->data['Invoice Items Discount Amount'] = $items_discounts;


        $tax_rate = 0;

        $tax_category = new TaxCategory($this->data['Invoice Tax Code']);
        $tax_rate     = $tax_category->data['Tax Category Rate'];


        $this->data['Invoice Total Net Amount']
                                                = $this->data['Invoice Deal Credit Net Amount'] + $this->data['Invoice Refund Net Amount'] + $this->data['Invoice Total Net Adjust Amount']
            + $this->data['Invoice Shipping Net Amount'] + $this->data['Invoice Items Net Amount'] + $this->data['Invoice Charges Net Amount'] + $this->data['Invoice Insurance Net Amount']
            - $this->data['Invoice Net Amount Off'];
        $this->data['Invoice Total Tax Amount'] = round(
            $this->data['Invoice Deal Credit Tax Amount'] + $this->data['Invoice Refund Tax Amount'] + $this->data['Invoice Shipping Tax Amount'] + $this->data['Invoice Items Tax Amount']
            + $this->data['Invoice Charges Tax Amount'] + $this->data['Invoice Insurance Tax Amount'] + $this->data['Invoice Total Tax Adjust Amount'] - (round(
                $this->data['Invoice Net Amount Off'] * $tax_rate, 2
            )), 2
        );

        $this->data['Invoice Outstanding Net Balance']
            = $items_net_outstanding_balance + $items_refund_net_outstanding_balance;
        $this->data['Invoice Outstanding Tax Balance']
            = $items_tax_outstanding_balance + $items_refund_tax_outstanding_balance;

        $this->data['Invoice Total Amount']
            = $this->data['Invoice Total Net Amount'] + $this->data['Invoice Total Tax Amount'];
        $this->data['Invoice Outstanding Total Amount']
            = $this->data['Invoice Total Amount'] - $this->data['Invoice Paid Amount'];

        //print_r($this->data);
        $total_costs = 0;
        $sql         = sprintf(
            "SELECT ifnull(sum(`Cost Supplier`/`Invoice Currency Exchange Rate`),0) AS `Cost Supplier`  ,ifnull(sum(`Cost Storing`/`Invoice Currency Exchange Rate`),0) AS `Cost Storing`,ifnull(sum(`Cost Handing`/`Invoice Currency Exchange Rate`),0)  AS  `Cost Handing`,ifnull(sum(`Cost Shipping`/`Invoice Currency Exchange Rate`),0) AS `Cost Shipping` FROM `Order Transaction Fact` WHERE `Invoice Key`=%d",
            $this->id
        );

        $this->data ['Invoice Total Profit'] = 0;
        $result                              = mysql_query($sql);
        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_costs = $row['Cost Supplier'] + $row['Cost Storing'] + $row['Cost Handing'] + $row['Cost Shipping'];

        }
        $this->data ['Invoice Total Profit']
            = $this->data ['Invoice Total Net Amount'] - $this->data ['Invoice Refund Net Amount'] - $total_costs;


        $sql = sprintf(
            "UPDATE  `Invoice Dimension` SET `Invoice Outstanding Total Amount`=%f,`Invoice Refund Net Amount`=%f,`Invoice Refund Tax Amount`=%f,`Invoice Total Net Adjust Amount`=%f,`Invoice Total Tax Adjust Amount`=%f,`Invoice Total Adjust Amount`=%f,`Invoice Outstanding Net Balance`=%f,`Invoice Outstanding Tax Balance`=%f,`Invoice Items Gross Amount`=%f,`Invoice Items Discount Amount`=%f ,`Invoice Items Net Amount`=%f,`Invoice Shipping Net Amount`=%f ,`Invoice Charges Net Amount`=%f ,`Invoice Total Net Amount`=%f ,`Invoice Items Tax Amount`=%f ,`Invoice Shipping Tax Amount`=%f,`Invoice Charges Tax Amount`=%f ,`Invoice Total Tax Amount`=%f,`Invoice Total Amount`=%f ,`Invoice Total Profit`=%f WHERE `Invoice Key`=%d",
            $this->data['Invoice Outstanding Total Amount'], $this->data['Invoice Refund Net Amount'], $this->data['Invoice Refund Tax Amount'], $this->data['Invoice Total Net Adjust Amount'],
            $this->data['Invoice Total Tax Adjust Amount'], $this->data['Invoice Total Adjust Amount'], $this->data['Invoice Outstanding Net Balance'], $this->data['Invoice Outstanding Tax Balance'],
            $this->data['Invoice Items Gross Amount'], $this->data['Invoice Items Discount Amount'], $this->data['Invoice Items Net Amount'], $this->data['Invoice Shipping Net Amount'],
            $this->data['Invoice Charges Net Amount'], $this->data['Invoice Total Net Amount'], $this->data['Invoice Items Tax Amount'], $this->data['Invoice Shipping Tax Amount'],
            $this->data['Invoice Charges Tax Amount'], $this->data['Invoice Total Tax Amount'], $this->data['Invoice Total Amount'],

            $this->data ['Invoice Total Profit'], $this->id
        );
        mysql_query($sql);

        //print "$sql\n<br>";
        $this->update_tax();


    }

    function update_tax() {
        global $account;

        if ($account->data['Apply Tax Method'] == 'Per Item') {
            $this->update_tax_per_item_method();
        } else {
            $this->update_tax_per_total_method();
        }

    }

    function update_tax_per_item_method() {


        $sql = sprintf(
            "DELETE FROM `Invoice Tax Bridge` WHERE `Invoice Key`=%d", $this->id
        );
        mysql_query($sql);


        $invoice_tax_fields = array();
        $result             = mysql_query(
            "SHOW COLUMNS FROM `Invoice Tax Dimension`"
        );
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                if ($row['Field'] != 'Invoice Key') {
                    $invoice_tax_fields[] = $row['Field'];
                }
            }
        }


        $_sql = '';
        foreach ($invoice_tax_fields as $invoice_tax_field) {
            $_sql .= ", `".$invoice_tax_field."`=NULL ";
        }
        $_sql = preg_replace('/^,/', '', $_sql);
        $sql  = 'UPDATE `Invoice Tax Dimension` SET '.$_sql.sprintf(
                ' where `Invoice Key`=%d', $this->id
            );

        $tax_sum_by_code = array();

        $sql = sprintf(
            "SELECT IFNULL(`Transaction Tax Code`,'UNK') AS tax_code,sum(`Invoice Transaction Item Tax Amount`) AS amount FROM `Order Transaction Fact`  WHERE `Invoice Key`=%d  GROUP BY `Transaction Tax Code`",
            $this->id
        );
        //print "$sql\n";
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            // print_r($row);

            $tax_sum_by_code[$row['tax_code']] = $row['amount'];
        }

        //print_r($tax_sum_by_code);
        $sql = sprintf(
            "SELECT IFNULL(`Tax Category Code`,'UNK') AS tax_code,sum(`Transaction Invoice Tax Amount`) AS amount FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d AND `Transaction Type`!='Adjust'  GROUP BY `Tax Category Code`",
            $this->id
        );
        // print "$sql\n";
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            if (array_key_exists($row['tax_code'], $tax_sum_by_code)) {
                $tax_sum_by_code[$row['tax_code']] += $row['amount'];
            } else {
                $tax_sum_by_code[$row['tax_code']] = $row['amount'];
            }
        }

        // print_r($tax_sum_by_code);


        foreach ($tax_sum_by_code as $tax_code => $amount) {
            $tax_category = new TaxCategory($tax_code);
            if ($tax_category->data['Composite'] == 'Yes') {

                $sql = sprintf(
                    "SELECT `Tax Category Rate`,`Tax Category Code` FROM kbase.`Tax Category Dimension` WHERE `Tax Category Key` IN (%s) ", $tax_category->data['Composite Metadata']
                );
                $res = mysql_query($sql);

                if ($tax_category->data['Tax Category Rate'] == 0) {
                    contunue;
                }
                $x = $amount / $tax_category->data['Tax Category Rate'];


                if ($tax_sum_by_code[$tax_code] == $amount) {
                    unset($tax_sum_by_code[$tax_code]);
                } else {
                    $tax_sum_by_code[$tax_code] = $tax_sum_by_code[$tax_code] - $amount;
                }


                while ($row = mysql_fetch_assoc($res)) {


                    if (array_key_exists(
                        $row['Tax Category Code'], $tax_sum_by_code
                    )) {
                        $tax_sum_by_code[$row['Tax Category Code']] += $x * $row['Tax Category Rate'];
                    } else {
                        $tax_sum_by_code[$row['Tax Category Code']] = $x * $row['Tax Category Rate'];
                    }
                }


            }


        }


        if ($this->data['Invoice Net Amount Off']) {

            $tax_category = new TaxCategory($this->data['Invoice Tax Code']);

            if (array_key_exists(
                $this->data['Invoice Tax Code'], $tax_sum_by_code
            )) {
                $tax_sum_by_code[$this->data['Invoice Tax Code']] -= round(
                    $this->data['Invoice Net Amount Off'] * $tax_category->data['Tax Category Rate'], 2
                );
            } else {
                $tax_sum_by_code[$this->data['Invoice Tax Code']] = round(
                    -1 * $this->data['Invoice Net Amount Off'] * $tax_category->data['Tax Category Rate'], 2
                );
            }

        }


        foreach ($tax_sum_by_code as $tax_code => $amount) {

            $this->add_tax_item($tax_code, $amount);
        }

        //print "\n\End updatinf  tax\n";

    }

    function add_tax_item($code = 'UNK', $amount = 0, $is_base = 'Yes') {


        $amount = round($amount, 2);
        $sql    = sprintf(
            "UPDATE `Invoice Tax Dimension` SET `%s`=%.2f WHERE `Invoice Key`=%d", addslashes($code), $amount, $this->id
        );
        mysql_query($sql);
        // print "$sql\n";
        $sql = sprintf(
            "INSERT INTO `Invoice Tax Bridge` VALUES (%d,%s,%.2f,%s) ON DUPLICATE KEY UPDATE `Tax Amount`=%.2f, `Tax Base`=%s", $this->id, prepare_mysql($code), $amount, prepare_mysql($is_base),
            $amount, prepare_mysql($is_base)

        );
        // print "$sql\n";
        mysql_query($sql);
    }

    function update_tax_per_total_method() {


        $sql = sprintf(
            "DELETE FROM `Invoice Tax Bridge` WHERE `Invoice Key`=%d", $this->id
        );
        mysql_query($sql);


        $invoice_tax_fields = array();
        $result             = mysql_query(
            "SHOW COLUMNS FROM `Invoice Tax Dimension`"
        );
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                if ($row['Field'] != 'Invoice Key') {
                    $invoice_tax_fields[] = $row['Field'];
                }
            }
        }


        $_sql = '';
        foreach ($invoice_tax_fields as $invoice_tax_field) {
            $_sql .= ", `".$invoice_tax_field."`=NULL ";
        }
        $_sql = preg_replace('/^,/', '', $_sql);
        $sql  = 'UPDATE `Invoice Tax Dimension` SET '.$_sql.sprintf(
                ' where `Invoice Key`=%d', $this->id
            );

        $tax_sum_by_code = array();

        $sql = sprintf(
            "SELECT IFNULL(`Transaction Tax Code`,'UNK') AS tax_code,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) AS net_amount , `Transaction Tax Rate` FROM `Order Transaction Fact`  WHERE `Invoice Key`=%d  GROUP BY `Transaction Tax Code`",
            $this->id
        );

        $res = mysql_query($sql);


        while ($row = mysql_fetch_assoc($res)) {


            $tax_sum_by_code[$row['tax_code']]
                = array(
                'net'  => $row['net_amount'],
                'rate' => $row['Transaction Tax Rate']
            );
        }

        //print_r($tax_sum_by_code);
        $sql = sprintf(
            "SELECT IFNULL(ONPTF.`Tax Category Code`,'UNK') AS tax_code,sum(`Transaction Invoice Net Amount`) AS net_amount, `Tax Category Rate` FROM `Order No Product Transaction Fact` ONPTF LEFT JOIN kbase.`Tax Category Dimension` TCD  ON (TCD.`Tax Category Code`=ONPTF.`Tax Category Code`)  WHERE `Invoice Key`=%d AND `Transaction Type`!='Adjust'  GROUP BY ONPTF.`Tax Category Code`",
            $this->id
        );
        // print "$sql\n";
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            if (array_key_exists($row['tax_code'], $tax_sum_by_code)) {
                $tax_sum_by_code[$row['tax_code']]['net'] += $row['net_amount'];
            } else {
                $tax_sum_by_code[$row['tax_code']]
                    = array(
                    'net'  => $row['net_amount'],
                    'rate' => $row['Tax Category Rate']
                );
            }
        }

        // print_r($tax_sum_by_code);


        foreach ($tax_sum_by_code as $tax_code => $data) {
            $tax_category = new TaxCategory($tax_code);
            if ($tax_category->data['Composite'] == 'Yes') {

                $sql = sprintf(
                    "SELECT `Tax Category Rate`,`Tax Category Code` FROM kbase.`Tax Category Dimension` WHERE `Tax Category Key` IN (%s) ", $tax_category->data['Composite Metadata']
                );
                $res = mysql_query($sql);

                if ($tax_category->data['Tax Category Rate'] == 0) {
                    continue;
                }

                $net = $tax_sum_by_code[$tax_code]['net'];
                unset($tax_sum_by_code[$tax_code]);


                while ($row = mysql_fetch_assoc($res)) {


                    if (array_key_exists(
                        $row['Tax Category Code'], $tax_sum_by_code
                    )) {
                        $tax_sum_by_code[$row['Tax Category Code']]['net'] += $net;
                    } else {
                        $tax_sum_by_code[$row['Tax Category Code']]
                            = array(
                            'net'  => $net,
                            'rate' => $row['Tax Category Rate']
                        );
                    }
                }


            }


        }


        if ($this->data['Invoice Net Amount Off']) {
            if (array_key_exists(
                $this->data['Invoice Tax Code'], $tax_sum_by_code
            )) {
                $tax_sum_by_code[$this->data['Invoice Tax Code']]['net'] -= $this->data['Invoice Net Amount Off'];
            } else {
                $tax_sum_by_code[$this->data['Invoice Tax Code']]
                    = array(
                    'net'  => (-1 * $this->data['Invoice Net Amount Off']),
                    'rate' => $this->data['Invoice Tax Code']
                );
            }

        }


        foreach ($tax_sum_by_code as $tax_code => $data) {
            $tax = $data['net'] * $data['rate'];
            $this->add_tax_item($tax_code, $tax);
        }

        //print "\n\End updatinf  tax\n";

    }

    function update_totals_per_total_method() {


        $shipping_net                         = 0;
        $shipping_tax                         = 0;
        $charges_net                          = 0;
        $charges_tax                          = 0;
        $insurance_tax                        = 0;
        $insurance_net                        = 0;
        $items_gross                          = 0;
        $items_discounts                      = 0;
        $items_net                            = 0;
        $items_tax                            = 0;
        $items_refund_net                     = 0;
        $items_refund_tax                     = 0;
        $items_net_outstanding_balance        = 0;
        $items_tax_outstanding_balance        = 0;
        $items_refund_net_outstanding_balance = 0;
        $items_refund_tax_outstanding_balance = 0;
        $deal_credit_net                      = 0;
        $deal_credit_tax                      = 0;
        $adjust_tax                           = 0;
        $adjust_net                           = 0;


        $sql = sprintf(
            "SELECT `Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Outstanding Net Balance`,`Invoice Transaction Outstanding Tax Balance`,`Invoice Transaction Outstanding Refund Net Balance`,`Invoice Transaction Outstanding Refund Tax Balance`,`Invoice Transaction Net Refund Amount`,`Invoice Transaction Tax Refund Amount`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Invoice Transaction Charges Amount`,`Invoice Transaction Charges Tax Amount`,`Invoice Transaction Shipping Amount`,`Invoice Transaction Shipping Tax Amount`,`Order Transaction Fact Key`,`Invoice Transaction Shipping Tax Amount`,`Invoice Transaction Charges Tax Amount`,(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) AS item_net ,`Invoice Transaction Item Tax Amount`
                       FROM `Order Transaction Fact` WHERE `Invoice Key`=%d   ", $this->data ['Invoice Key']
        );

        //  print $sql;
        // print "$\n";
        $counter = 0;
        $res     = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $counter++;
            $items_net += $row['item_net'];
            $items_tax += $row['Invoice Transaction Item Tax Amount'];
            $items_net_outstanding_balance += $row['Invoice Transaction Outstanding Net Balance'];
            $items_tax_outstanding_balance += $row['Invoice Transaction Outstanding Tax Balance'];


            $items_gross += $row['Invoice Transaction Gross Amount'];
            $items_discounts += $row['Invoice Transaction Total Discount Amount'];
        }


        $sql = sprintf(
            "SELECT * FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d", $this->id
        );

        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            if ($row['Transaction Type'] == 'Shipping') {
                $shipping_net += $row['Transaction Invoice Net Amount'];
                $shipping_tax += $row['Transaction Invoice Tax Amount'];
            } elseif ($row['Transaction Type'] == 'Charges') {
                $charges_net += $row['Transaction Invoice Net Amount'];
                $charges_tax += $row['Transaction Invoice Tax Amount'];
            } elseif ($row['Transaction Type'] == 'Insurance') {
                $insurance_net += $row['Transaction Invoice Net Amount'];
                $insurance_tax += $row['Transaction Invoice Tax Amount'];
            } elseif ($row['Transaction Type'] == 'Adjust') {
                $adjust_net += $row['Transaction Invoice Net Amount'];
                $adjust_tax += $row['Transaction Invoice Tax Amount'];
            } elseif ($row['Transaction Type'] == 'Deal') {
                $deal_credit_net += $row['Transaction Invoice Net Amount'];
                $deal_credit_tax += $row['Transaction Invoice Tax Amount'];
            } elseif ($row['Transaction Type'] == 'Credit') {

                $items_refund_net += $row['Transaction Invoice Net Amount'];
                $items_refund_tax += $row['Transaction Invoice Tax Amount'];
                $items_refund_net_outstanding_balance += $row['Transaction Outstanding Net Amount Balance'];
                $items_refund_tax_outstanding_balance += $row['Transaction Outstanding Tax Amount Balance'];

            } else {


            }
        }
        $this->data['Invoice Total Net Adjust Amount'] = $adjust_net;
        $this->data['Invoice Total Tax Adjust Amount'] = $adjust_tax;
        $this->data['Invoice Total Adjust Amount']     = $adjust_tax + $adjust_net;
        $this->data['Invoice Refund Net Amount']       = $items_refund_net;
        $this->data['Invoice Refund Tax Amount']       = $items_refund_tax;
        $this->data['Invoice Shipping Tax Amount']     = $shipping_tax;
        $this->data['Invoice Shipping Net Amount']     = $shipping_net;
        $this->data['Invoice Charges Tax Amount']      = $charges_tax;
        $this->data['Invoice Charges Net Amount']      = $charges_net;
        $this->data['Invoice Insurance Tax Amount']    = $insurance_tax;
        $this->data['Invoice Insurance Net Amount']    = $insurance_net;


        $this->data['Invoice Items Tax Amount']       = $items_tax;
        $this->data['Invoice Items Net Amount']       = $items_net;
        $this->data['Invoice Deal Credit Tax Amount'] = $deal_credit_tax;
        $this->data['Invoice Deal Credit Net Amount'] = $deal_credit_net;

        $this->data['Invoice Items Gross Amount']    = $items_gross;
        $this->data['Invoice Items Discount Amount'] = $items_discounts;


        $this->data['Invoice Total Net Amount']
            = $this->data['Invoice Deal Credit Net Amount'] + $this->data['Invoice Refund Net Amount'] + $this->data['Invoice Total Net Adjust Amount'] + $this->data['Invoice Shipping Net Amount']
            + $this->data['Invoice Items Net Amount'] + $this->data['Invoice Charges Net Amount'] + $this->data['Invoice Insurance Net Amount'] - $this->data['Invoice Net Amount Off'];


        $this->data['Invoice Outstanding Net Balance']
            = $items_net_outstanding_balance + $items_refund_net_outstanding_balance;


        $this->data['Invoice Outstanding Tax Balance']
            = $items_tax_outstanding_balance + $items_refund_tax_outstanding_balance;


        $this->data['Invoice Total Amount']
            = $this->data['Invoice Total Net Amount'] + $this->data['Invoice Total Tax Amount'];
        $this->data['Invoice Outstanding Total Amount']
            = $this->data['Invoice Total Amount'] - $this->data['Invoice Paid Amount'];

        //print_r($this->data);
        $total_costs = 0;
        $sql         = sprintf(
            "SELECT ifnull(sum(`Cost Supplier`/`Invoice Currency Exchange Rate`),0) AS `Cost Supplier`  ,ifnull(sum(`Cost Storing`/`Invoice Currency Exchange Rate`),0) AS `Cost Storing`,ifnull(sum(`Cost Handing`/`Invoice Currency Exchange Rate`),0)  AS  `Cost Handing`,ifnull(sum(`Cost Shipping`/`Invoice Currency Exchange Rate`),0) AS `Cost Shipping` FROM `Order Transaction Fact` WHERE `Invoice Key`=%d",
            $this->id
        );

        $this->data ['Invoice Total Profit'] = 0;
        $result                              = mysql_query($sql);
        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_costs = $row['Cost Supplier'] + $row['Cost Storing'] + $row['Cost Handing'] + $row['Cost Shipping'];

        }
        $this->data ['Invoice Total Profit']
            = $this->data ['Invoice Total Net Amount'] - $this->data ['Invoice Refund Net Amount'] - $total_costs;


        $sql = sprintf(
            "UPDATE  `Invoice Dimension` SET `Invoice Outstanding Total Amount`=%f,`Invoice Refund Net Amount`=%f,`Invoice Refund Tax Amount`=%f,`Invoice Total Net Adjust Amount`=%f,`Invoice Total Tax Adjust Amount`=%f,`Invoice Total Adjust Amount`=%f,`Invoice Outstanding Net Balance`=%f,`Invoice Outstanding Tax Balance`=%f,`Invoice Items Gross Amount`=%f,`Invoice Items Discount Amount`=%f ,`Invoice Items Net Amount`=%f,`Invoice Shipping Net Amount`=%f ,`Invoice Charges Net Amount`=%f ,`Invoice Total Net Amount`=%f ,`Invoice Items Tax Amount`=%f ,`Invoice Shipping Tax Amount`=%f,`Invoice Charges Tax Amount`=%f ,`Invoice Total Tax Amount`=%f,`Invoice Total Amount`=%f ,`Invoice Total Profit`=%f WHERE `Invoice Key`=%d",
            $this->data['Invoice Outstanding Total Amount'], $this->data['Invoice Refund Net Amount'], $this->data['Invoice Refund Tax Amount'], $this->data['Invoice Total Net Adjust Amount'],
            $this->data['Invoice Total Tax Adjust Amount'], $this->data['Invoice Total Adjust Amount'], $this->data['Invoice Outstanding Net Balance'], $this->data['Invoice Outstanding Tax Balance'],
            $this->data['Invoice Items Gross Amount'], $this->data['Invoice Items Discount Amount'], $this->data['Invoice Items Net Amount'], $this->data['Invoice Shipping Net Amount'],
            $this->data['Invoice Charges Net Amount'], $this->data['Invoice Total Net Amount'], $this->data['Invoice Items Tax Amount'], $this->data['Invoice Shipping Tax Amount'],
            $this->data['Invoice Charges Tax Amount'], $this->data['Invoice Total Tax Amount'], $this->data['Invoice Total Amount'],

            $this->data ['Invoice Total Profit'], $this->id
        );
        mysql_query($sql);

        //print "$sql\n<br>";
        $this->update_tax();

        $tax = 0;
        $sql = sprintf(
            ' SELECT IFNULL(sum(IFNULL(`Tax Amount`,0)),0) AS tax FROM `Invoice Tax Bridge` WHERE `Invoice Key`=%d', $this->id
        );
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {

            $tax = $row['tax'];
        }

        $this->data['Invoice Total Tax Amount'] = $tax;


        $this->data['Invoice Total Amount']
            = $this->data['Invoice Total Net Amount'] + $this->data['Invoice Total Tax Amount'];
        $this->data['Invoice Outstanding Total Amount']
            = $this->data['Invoice Total Amount'] - $this->data['Invoice Paid Amount'];

        $sql = sprintf(
            "UPDATE  `Invoice Dimension` SET `Invoice Total Tax Amount`=%.2f,`Invoice Total Amount`=%.2f,`Invoice Outstanding Total Amount`=%.2f    WHERE `Invoice Key`=%d",
            $this->data['Invoice Total Tax Amount'], $this->data['Invoice Total Amount'], $this->data['Invoice Outstanding Total Amount'],

            $this->id
        );
        mysql_query($sql);


    }

    function update_refund_totals() {
        global $account;

        if ($account->data['Apply Tax Method'] == 'Per Item') {
            $this->update_refund_totals_per_item_method();
        } else {
            $this->update_refund_totals_per_total_method();
        }
    }

    function update_refund_totals_per_item_method() {
        $shipping_net  = 0;
        $shipping_tax  = 0;
        $charges_net   = 0;
        $charges_tax   = 0;
        $insurance_net = 0;
        $insurance_tax = 0;
        $credit_net    = 0;
        $credit_tax    = 0;

        $items_gross                          = 0;
        $items_discounts                      = 0;
        $items_net                            = 0;
        $items_tax                            = 0;
        $items_refund_net                     = 0;
        $items_refund_tax                     = 0;
        $items_net_outstanding_balance        = 0;
        $items_tax_outstanding_balance        = 0;
        $items_refund_net_outstanding_balance = 0;
        $items_refund_tax_outstanding_balance = 0;
        $sql                                  = sprintf(
            "SELECT `Invoice Transaction Outstanding Net Balance`,`Invoice Transaction Outstanding Tax Balance`,`Invoice Transaction Outstanding Refund Net Balance`,`Invoice Transaction Outstanding Refund Tax Balance`,`Invoice Transaction Net Refund Items`,`Invoice Transaction Tax Refund Items`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Invoice Transaction Charges Amount`,`Invoice Transaction Charges Tax Amount`,`Invoice Transaction Shipping Amount`,`Invoice Transaction Shipping Tax Amount`,`Order Transaction Fact Key`,`Invoice Transaction Shipping Tax Amount`,`Invoice Transaction Charges Tax Amount`,(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) AS item_net ,(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`)*`Transaction Tax Rate` AS tax_item FROM `Order Transaction Fact` WHERE `Refund Key`=%d",
            $this->data ['Invoice Key']
        );
        //print $sql;
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            //print_r($row);
            $items_refund_net += round(
                $row['Invoice Transaction Net Refund Items'], 2
            );
            $items_refund_tax += round(
                $row['Invoice Transaction Tax Refund Items'], 2
            );
            $items_refund_net_outstanding_balance += round(
                $row['Invoice Transaction Outstanding Refund Net Balance'], 2
            );
            $items_refund_tax_outstanding_balance += round(
                $row['Invoice Transaction Outstanding Refund Tax Balance'], 2
            );

        }
        //print "::::$items_refund_net--->  \n";
        //print $items_refund_net_outstanding_balance;
        $sql = sprintf(
            "SELECT * FROM `Order No Product Transaction Fact` WHERE `Refund Key`=%d AND `Transaction Type`='Shipping'", $this->data ['Invoice Key']
        );
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {

            $shipping_net += round($row['Transaction Refund Net Amount'], 2);
            $shipping_tax += round($row['Transaction Refund Tax Amount'], 2);
            $items_refund_net_outstanding_balance += round(
                $row['Transaction Outstanding Refund Net Amount Balance'], 2
            );
            $items_refund_tax_outstanding_balance += round(
                $row['Transaction Outstanding Refund Tax Amount Balance'], 2
            );

        }


        $sql = sprintf(
            "SELECT * FROM `Order No Product Transaction Fact` WHERE `Refund Key`=%d AND `Transaction Type`='Charges'", $this->data ['Invoice Key']
        );
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {

            $charges_net += round($row['Transaction Refund Net Amount'], 2);
            $charges_tax += round($row['Transaction Refund Tax Amount'], 2);
            $items_refund_net_outstanding_balance += round(
                $row['Transaction Outstanding Refund Net Amount Balance'], 2
            );
            $items_refund_tax_outstanding_balance += round(
                $row['Transaction Outstanding Refund Tax Amount Balance'], 2
            );

        }


        $sql = sprintf(
            "SELECT * FROM `Order No Product Transaction Fact` WHERE `Refund Key`=%d AND `Transaction Type`='Insurance'", $this->data ['Invoice Key']
        );
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {

            $insurance_net += round($row['Transaction Refund Net Amount'], 2);
            $insurance_tax += round($row['Transaction Refund Tax Amount'], 2);
            $items_refund_net_outstanding_balance += round(
                $row['Transaction Outstanding Refund Net Amount Balance'], 2
            );
            $items_refund_tax_outstanding_balance += round(
                $row['Transaction Outstanding Refund Tax Amount Balance'], 2
            );

        }

        $sql = sprintf(
            "SELECT * FROM `Order No Product Transaction Fact` WHERE `Refund Key`=%d AND `Transaction Type`='Credit'", $this->data ['Invoice Key']
        );
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {

            $credit_net += round($row['Transaction Refund Net Amount'], 2);
            $credit_tax += round($row['Transaction Refund Tax Amount'], 2);
            $items_refund_net_outstanding_balance += round(
                $row['Transaction Outstanding Refund Net Amount Balance'], 2
            );
            $items_refund_tax_outstanding_balance += round(
                $row['Transaction Outstanding Refund Tax Amount Balance'], 2
            );

        }


        $this->data['Invoice Items Tax Amount']   = $items_refund_tax;
        $this->data['Invoice Items Net Amount']   = $items_refund_net;
        $this->data['Invoice Items Gross Amount'] = $items_refund_net;


        $this->data['Invoice Shipping Net Amount']  = $shipping_net;
        $this->data['Invoice Shipping Tax Amount']  = $shipping_tax;
        $this->data['Invoice Charges Net Amount']   = $charges_net;
        $this->data['Invoice Charges Tax Amount']   = $charges_tax;
        $this->data['Invoice Insurance Net Amount'] = $insurance_net;
        $this->data['Invoice Insurance Tax Amount'] = $insurance_tax;

        $this->data['Invoice Credit Net Amount'] = $credit_net;
        $this->data['Invoice Credit Tax Amount'] = $credit_tax;

        $this->data['Invoice Total Net Amount'] = round(
            $items_refund_net + $shipping_net + $charges_net + $insurance_net + $credit_net, 2
        );
        $this->data['Invoice Total Tax Amount'] = round(
            $items_refund_tax + $shipping_tax + $charges_tax + $insurance_tax + $credit_tax, 2
        );

        $this->data['Invoice Outstanding Net Balance']
            = $items_refund_net_outstanding_balance;
        $this->data['Invoice Outstanding Tax Balance']
            = $items_refund_tax_outstanding_balance;


        $this->data['Invoice Total Amount'] = round(
            $this->data['Invoice Total Net Amount'] + $this->data['Invoice Total Tax Amount'], 2
        );
        $this->data['Invoice Outstanding Total Amount']
                                            = $this->data['Invoice Outstanding Net Balance'] + $this->data['Invoice Outstanding Tax Balance'];
        $sql                                = sprintf(
            "UPDATE  `Invoice Dimension` SET `Invoice Outstanding Total Amount`=%f,`Invoice Outstanding Net Balance`=%f,`Invoice Outstanding Tax Balance`=%f,`Invoice Items Gross Amount`=%f,`Invoice Items Discount Amount`=%f ,`Invoice Items Net Amount`=%f,`Invoice Shipping Net Amount`=%f ,`Invoice Charges Net Amount`=%f ,`Invoice Total Net Amount`=%f ,`Invoice Items Tax Amount`=%f ,`Invoice Shipping Tax Amount`=%f,`Invoice Charges Tax Amount`=%f ,`Invoice Total Tax Amount`=%f,`Invoice Total Amount`=%f ,
		`Invoice Credit Net Amount`=%.2f ,`Invoice Credit Tax Amount`=%.2f
		WHERE `Invoice Key`=%d", $this->data['Invoice Outstanding Total Amount'], $this->data['Invoice Outstanding Net Balance'], $this->data['Invoice Outstanding Tax Balance'],
            $this->data['Invoice Items Gross Amount'], $this->data['Invoice Items Discount Amount'], $this->data['Invoice Items Net Amount'], $this->data['Invoice Shipping Net Amount'],
            $this->data['Invoice Charges Net Amount'], $this->data['Invoice Total Net Amount'], $this->data['Invoice Items Tax Amount'], $this->data['Invoice Shipping Tax Amount'],
            $this->data['Invoice Charges Tax Amount'], $this->data['Invoice Total Tax Amount'], $this->data['Invoice Total Amount'], $this->data['Invoice Credit Net Amount'],
            $this->data['Invoice Credit Tax Amount'],


            $this->id
        );
        mysql_query($sql);
        //print $sql;
        $this->update_refund_tax();


    }

    function update_refund_tax() {


        $sql = sprintf(
            "DELETE FROM `Invoice Tax Bridge` WHERE `Invoice Key`=%d", $this->id
        );
        mysql_query($sql);


        $invoice_tax_fields = array();
        $result             = mysql_query(
            "SHOW COLUMNS FROM `Invoice Tax Dimension`"
        );
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                if ($row['Field'] != 'Invoice Key') {
                    $invoice_tax_fields[] = $row['Field'];
                }
            }
        }


        $_sql = '';
        foreach ($invoice_tax_fields as $invoice_tax_field) {
            $_sql .= ", `".$invoice_tax_field."`=NULL ";
        }
        $_sql = preg_replace('/^,/', '', $_sql);
        $sql  = 'UPDATE `Invoice Tax Dimension` SET '.$_sql.sprintf(
                ' where `Invoice Key`=%d', $this->id
            );

        $tax_sum_by_code = array();

        $sql = sprintf(
            "SELECT IFNULL(`Transaction Tax Code`,'UNK') AS tax_code,sum(`Invoice Transaction Tax Refund Items`) AS amount FROM `Order Transaction Fact`  WHERE `Refund Key`=%d  GROUP BY `Transaction Tax Code`",
            $this->id
        );
        //print "$sql\n";
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            // print_r($row);

            $tax_sum_by_code[$row['tax_code']] = $row['amount'];
        }

        //print_r($tax_sum_by_code);
        $sql = sprintf(
            "SELECT IFNULL(`Tax Category Code`,'UNK') AS tax_code,sum(`Transaction Refund Tax Amount`) AS amount FROM `Order No Product Transaction Fact` WHERE `Refund Key`=%d AND `Transaction Type`!='Adjust'  GROUP BY `Tax Category Code`",
            $this->id
        );
        // print "$sql\n";
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            if (array_key_exists($row['tax_code'], $tax_sum_by_code)) {
                $tax_sum_by_code[$row['tax_code']] += $row['amount'];
            } else {
                $tax_sum_by_code[$row['tax_code']] = $row['amount'];
            }
        }


        foreach ($tax_sum_by_code as $tax_code => $amount) {
            $tax_category = new TaxCategory($tax_code);
            if ($tax_category->data['Composite'] == 'Yes') {

                $sql = sprintf(
                    "SELECT `Tax Category Rate`,`Tax Category Code` FROM kbase.`Tax Category Dimension` WHERE `Tax Category Key` IN (%s) ", $tax_category->data['Composite Metadata']
                );
                $res = mysql_query($sql);

                if ($tax_category->data['Tax Category Rate'] == 0) {
                    contunue;
                }
                $x = $amount / $tax_category->data['Tax Category Rate'];


                if ($tax_sum_by_code[$tax_code] == $amount) {
                    unset($tax_sum_by_code[$tax_code]);
                } else {
                    $tax_sum_by_code[$tax_code] = $tax_sum_by_code[$tax_code] - $amount;
                }


                while ($row = mysql_fetch_assoc($res)) {


                    if (array_key_exists(
                        $row['Tax Category Code'], $tax_sum_by_code
                    )) {
                        $tax_sum_by_code[$row['Tax Category Code']] += $x * $row['Tax Category Rate'];
                    } else {
                        $tax_sum_by_code[$row['Tax Category Code']] = $x * $row['Tax Category Rate'];
                    }
                }


            }


        }


        // exit;
        foreach ($tax_sum_by_code as $tax_code => $amount) {

            $this->add_tax_item($tax_code, round($amount, 2));
        }

        //print "\n\End updatinf  tax\n";

    }

    function update_refund_totals_per_total_method() {
        $shipping_net  = 0;
        $shipping_tax  = 0;
        $charges_net   = 0;
        $charges_tax   = 0;
        $insurance_net = 0;
        $insurance_tax = 0;
        $credit_net    = 0;
        $credit_tax    = 0;

        $items_gross                          = 0;
        $items_discounts                      = 0;
        $items_net                            = 0;
        $items_tax                            = 0;
        $items_refund_net                     = 0;
        $items_refund_tax                     = 0;
        $items_net_outstanding_balance        = 0;
        $items_tax_outstanding_balance        = 0;
        $items_refund_net_outstanding_balance = 0;
        $items_refund_tax_outstanding_balance = 0;
        $sql                                  = sprintf(
            "SELECT `Invoice Transaction Outstanding Net Balance`,`Invoice Transaction Outstanding Tax Balance`,`Invoice Transaction Outstanding Refund Net Balance`,`Invoice Transaction Outstanding Refund Tax Balance`,`Invoice Transaction Net Refund Items`,`Invoice Transaction Tax Refund Items`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Invoice Transaction Charges Amount`,`Invoice Transaction Charges Tax Amount`,`Invoice Transaction Shipping Amount`,`Invoice Transaction Shipping Tax Amount`,`Order Transaction Fact Key`,`Invoice Transaction Shipping Tax Amount`,`Invoice Transaction Charges Tax Amount`,(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) AS item_net ,(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`)*`Transaction Tax Rate` AS tax_item FROM `Order Transaction Fact` WHERE `Refund Key`=%d",
            $this->data ['Invoice Key']
        );
        //print $sql;
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            //print_r($row);
            $items_refund_net += round(
                $row['Invoice Transaction Net Refund Items'], 2
            );
            $items_refund_tax += round(
                $row['Invoice Transaction Tax Refund Items'], 2
            );
            $items_refund_net_outstanding_balance += round(
                $row['Invoice Transaction Outstanding Refund Net Balance'], 2
            );
            $items_refund_tax_outstanding_balance += round(
                $row['Invoice Transaction Outstanding Refund Tax Balance'], 2
            );

        }
        //print "::::$items_refund_net--->  \n";
        //print $items_refund_net_outstanding_balance;
        $sql = sprintf(
            "SELECT * FROM `Order No Product Transaction Fact` WHERE `Refund Key`=%d AND `Transaction Type`='Shipping'", $this->data ['Invoice Key']
        );
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {

            $shipping_net += round($row['Transaction Refund Net Amount'], 2);
            $shipping_tax += round($row['Transaction Refund Tax Amount'], 2);
            $items_refund_net_outstanding_balance += round(
                $row['Transaction Outstanding Refund Net Amount Balance'], 2
            );
            $items_refund_tax_outstanding_balance += round(
                $row['Transaction Outstanding Refund Tax Amount Balance'], 2
            );

        }


        $sql = sprintf(
            "SELECT * FROM `Order No Product Transaction Fact` WHERE `Refund Key`=%d AND `Transaction Type`='Charges'", $this->data ['Invoice Key']
        );
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {

            $charges_net += round($row['Transaction Refund Net Amount'], 2);
            $charges_tax += round($row['Transaction Refund Tax Amount'], 2);
            $items_refund_net_outstanding_balance += round(
                $row['Transaction Outstanding Refund Net Amount Balance'], 2
            );
            $items_refund_tax_outstanding_balance += round(
                $row['Transaction Outstanding Refund Tax Amount Balance'], 2
            );

        }


        $sql = sprintf(
            "SELECT * FROM `Order No Product Transaction Fact` WHERE `Refund Key`=%d AND `Transaction Type`='Insurance'", $this->data ['Invoice Key']
        );
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {

            $insurance_net += round($row['Transaction Refund Net Amount'], 2);
            $insurance_tax += round($row['Transaction Refund Tax Amount'], 2);
            $items_refund_net_outstanding_balance += round(
                $row['Transaction Outstanding Refund Net Amount Balance'], 2
            );
            $items_refund_tax_outstanding_balance += round(
                $row['Transaction Outstanding Refund Tax Amount Balance'], 2
            );

        }

        $sql = sprintf(
            "SELECT * FROM `Order No Product Transaction Fact` WHERE `Refund Key`=%d AND `Transaction Type`='Credit'", $this->data ['Invoice Key']
        );
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {

            $credit_net += round($row['Transaction Refund Net Amount'], 2);
            $credit_tax += round($row['Transaction Refund Tax Amount'], 2);
            $items_refund_net_outstanding_balance += round(
                $row['Transaction Outstanding Refund Net Amount Balance'], 2
            );
            $items_refund_tax_outstanding_balance += round(
                $row['Transaction Outstanding Refund Tax Amount Balance'], 2
            );

        }


        $this->data['Invoice Items Tax Amount']   = $items_refund_tax;
        $this->data['Invoice Items Net Amount']   = $items_refund_net;
        $this->data['Invoice Items Gross Amount'] = $items_refund_net;


        $this->data['Invoice Shipping Net Amount']  = $shipping_net;
        $this->data['Invoice Shipping Tax Amount']  = $shipping_tax;
        $this->data['Invoice Charges Net Amount']   = $charges_net;
        $this->data['Invoice Charges Tax Amount']   = $charges_tax;
        $this->data['Invoice Insurance Net Amount'] = $insurance_net;
        $this->data['Invoice Insurance Tax Amount'] = $insurance_tax;

        $this->data['Invoice Credit Net Amount'] = $credit_net;
        $this->data['Invoice Credit Tax Amount'] = $credit_tax;

        $this->data['Invoice Total Net Amount'] = round(
            $items_refund_net + $shipping_net + $charges_net + $insurance_net + $credit_net, 2
        );
        $this->data['Invoice Total Tax Amount'] = round(
            $items_refund_tax + $shipping_tax + $charges_tax + $insurance_tax + $credit_tax, 2
        );


        $this->data['Invoice Outstanding Net Balance']
            = $items_refund_net_outstanding_balance;
        $this->data['Invoice Outstanding Tax Balance']
            = $items_refund_tax_outstanding_balance;


        $this->data['Invoice Total Amount'] = round(
            $this->data['Invoice Total Net Amount'] + $this->data['Invoice Total Tax Amount'], 2
        );
        $this->data['Invoice Outstanding Total Amount']
                                            = $this->data['Invoice Outstanding Net Balance'] + $this->data['Invoice Outstanding Tax Balance'];
        $sql                                = sprintf(
            "UPDATE  `Invoice Dimension` SET `Invoice Outstanding Total Amount`=%f,`Invoice Outstanding Net Balance`=%f,`Invoice Outstanding Tax Balance`=%f,`Invoice Items Gross Amount`=%f,`Invoice Items Discount Amount`=%f ,`Invoice Items Net Amount`=%f,`Invoice Shipping Net Amount`=%f ,`Invoice Charges Net Amount`=%f ,`Invoice Total Net Amount`=%f ,`Invoice Items Tax Amount`=%f ,`Invoice Shipping Tax Amount`=%f,`Invoice Charges Tax Amount`=%f ,`Invoice Total Tax Amount`=%f,`Invoice Total Amount`=%f ,
		`Invoice Credit Net Amount`=%.2f ,`Invoice Credit Tax Amount`=%.2f
		WHERE `Invoice Key`=%d", $this->data['Invoice Outstanding Total Amount'], $this->data['Invoice Outstanding Net Balance'], $this->data['Invoice Outstanding Tax Balance'],
            $this->data['Invoice Items Gross Amount'], $this->data['Invoice Items Discount Amount'], $this->data['Invoice Items Net Amount'], $this->data['Invoice Shipping Net Amount'],
            $this->data['Invoice Charges Net Amount'], $this->data['Invoice Total Net Amount'], $this->data['Invoice Items Tax Amount'], $this->data['Invoice Shipping Tax Amount'],
            $this->data['Invoice Charges Tax Amount'], $this->data['Invoice Total Tax Amount'], $this->data['Invoice Total Amount'], $this->data['Invoice Credit Net Amount'],
            $this->data['Invoice Credit Tax Amount'],


            $this->id
        );
        mysql_query($sql);
        //print $sql;
        $this->update_refund_tax();

        $tax = 0;
        $sql = sprintf(
            ' SELECT IFNULL(sum(`Tax Amount`),0) AS tax FROM `Invoice Tax Bridge` WHERE `Invoice Key`=%d', $this->id
        );
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            $tax = $row['tax'];
        }

        $this->data['Invoice Total Tax Amount'] = $tax;
        $this->data['Invoice Total Amount']
                                                = $this->data['Invoice Total Net Amount'] + $this->data['Invoice Total Tax Amount'];
        $this->data['Invoice Outstanding Total Amount']
                                                = $this->data['Invoice Total Amount'] - $this->data['Invoice Paid Amount'];

        $sql = sprintf(
            "UPDATE  `Invoice Dimension` SET `Invoice Total Tax Amount`=%.2f,`Invoice Total Amount`=%.2f,`Invoice Outstanding Total Amount`=%.2f    WHERE `Invoice Key`=%d",
            $this->data['Invoice Total Tax Amount'], $this->data['Invoice Total Amount'], $this->data['Invoice Outstanding Total Amount'],

            $this->id
        );
        mysql_query($sql);


    }

    function next_account_wide_public_id($suffix = '') {
        $sql = sprintf(
            "UPDATE `Store Dimension` SET `Account Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Public ID` + 1) WHERE `Account Key`=1"
        );
        mysql_query($sql);
        $public_id = mysql_insert_id();

        include_once 'class.Account.php';
        $account = new Account();

        $this->data['Invoice Public ID'] = sprintf(
                $account->data['Account Invoice Public ID Format'], $public_id
            ).$suffix;
        $this->data['Invoice File As']   = $this->prepare_file_as(
            $this->data['Invoice Public ID']
        );
    }



    function update_xhtml_processed_by() {

        $xhtml_sale_representatives = '';
        $tag                        = '&view=csr';
        $sql                        = sprintf(
            "SELECT S.`Staff Key`,`Staff Alias` FROM `Invoice Processed By Bridge` B  LEFT JOIN `Staff Dimension` S ON (B.`Staff Key`=S.`Staff Key`) WHERE `Invoice Key`=%s", $this->id
        );
        //print $sql;
        $result = mysql_query($sql) or die('Query failed: '.mysql_error());
        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $id       = $row['Staff Key'];
            $ids[$id] = $id;

            $xhtml_sale_representatives .= sprintf(
                ', <a href="staff.php?id=%d%s">%s</a>', $id, $tag, mb_ucwords($row['Staff Alias'])
            );

        }
        $xhtml_sale_representatives = preg_replace(
            "/^\,\s*/", "", $xhtml_sale_representatives
        );
        if ($xhtml_sale_representatives == '') {
            $xhtml_sale_representatives = _('Unknown');
        }

        $sql = sprintf(
            "UPDATE `Invoice Dimension` SET `Invoice XHTML Processed By`=%s WHERE `Invoice Key`=%d", prepare_mysql($xhtml_sale_representatives), $this->id
        );
        //print $sql;
        mysql_query($sql);
    }

    function update_xhtml_charged_by() {

        $xhtml_sale_representatives = '';
        $tag                        = '&view=csr';
        $sql                        = sprintf(
            "SELECT S.`Staff Key`,`Staff Alias` FROM `Invoice Charged By Bridge` B  LEFT JOIN `Staff Dimension` S ON (B.`Staff Key`=S.`Staff Key`) WHERE `Invoice Key`=%s", $this->id
        );
        //print $sql;
        $result = mysql_query($sql) or die('Query failed: '.mysql_error());
        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $id       = $row['Staff Key'];
            $ids[$id] = $id;

            $xhtml_sale_representatives .= sprintf(
                ', <a href="staff.php?id=%d%s">%s</a>', $id, $tag, mb_ucwords($row['Staff Alias'])
            );

        }
        $xhtml_sale_representatives = preg_replace(
            "/^\,\s*/", "", $xhtml_sale_representatives
        );
        if ($xhtml_sale_representatives == '') {
            $xhtml_sale_representatives = _('Unknown');
        }

        $sql = sprintf(
            "UPDATE `Invoice Dimension` SET `Invoice XHTML Charged By`=%s WHERE `Invoice Key`=%d", prepare_mysql($xhtml_sale_representatives), $this->id
        );
        //print $sql;
        mysql_query($sql);
    }

    function update_billing_to($billing_to_key) {

        $billing_to = new Billing_To($billing_to_key);

        $this->data ['Invoice Billing To Key'] = $billing_to->id;
        $this->data ['Invoice XHTML Address']
                                               = $billing_to->data['Billing To XHTML Address'];
        $this->data ['Invoice Billing Country 2 Alpha Code']
                                               = ($billing_to->data['Billing To Country 2 Alpha Code'] == '' ? 'XX' : $billing_to->data['Billing To Country 2 Alpha Code']);

        $this->data ['Invoice Billing Country Code']
                                                          = ($billing_to->data['Billing To Country Code'] == '' ? 'UNK' : $billing_to->data['Billing To Country Code']);
        $this->data ['Invoice Billing World Region Code'] = $billing_to->get(
            'World Region Code'
        );
        $this->data ['Invoice Billing Town']
                                                          = $billing_to->data['Billing To Town'];
        $this->data ['Invoice Billing Postal Code']
                                                          = $billing_to->data['Billing To Postal Code'];


        $sql = sprintf(
            "UPDATE `Invoice Dimension` SET  `Invoice Billing To Key`=%d ,`Invoice XHTML Address`=%s,`Invoice Billing Country 2 Alpha Code`=%s,`Invoice Billing Country Code`=%s,`Invoice Billing World Region Code`=%s,`Invoice Billing Town`=%s,`Invoice Billing Postal Code`=%s  WHERE `Invoice Key`=%d ",
            $this->data ['Invoice Billing To Key'], prepare_mysql($this->data ['Invoice XHTML Address']), prepare_mysql($this->data ['Invoice Billing Country 2 Alpha Code']),
            prepare_mysql($this->data ['Invoice Billing Country Code']), prepare_mysql($this->data ['Invoice Billing World Region Code']), prepare_mysql($this->data ['Invoice Billing Town']),
            prepare_mysql($this->data ['Invoice Billing Postal Code']), $this->id
        );
        mysql_query($sql);


        $sql = sprintf(
            "UPDATE `Order Transaction Fact` SET `Billing To Key`=%d WHERE `Invoice Key`=%d", $billing_to->id, $this->id
        );


    }

    function update_charges_old($charge_data) {

        //print_r($charge_data);

        //$this->update_charges(array('Transaction Invoice Net Amount'=>$charges_net,'Invoice Charges Tax Amount'=>$charges_tax),true);
        //print "caca ";

        $amount = $charge_data['Transaction Invoice Net Amount'];
        //if ($amount==$this->data['Invoice Charges Net Amount']) {
        // $this->msg='Nothing to change';
        // return;
        //}
        $this->data['Invoice Charges Net Amount'] = $amount;
        $this->data['Invoice Charges Tax Amount'] = $amount * ($this->get_tax_rate('charges'));
        $old_charges_data                         = array();
        $sql                                      = sprintf(
            "SELECT * FROM `Order No Product Transaction Fact` WHERE `Transaction Type`='Charges' AND `Invoice Key`=%d  ", $this->id
        );
        $result                                   = mysql_query($sql);
        $old_total                                = 0;
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $old_charges_data[$row['Order No Product Transaction Fact Key']]
                = array(
                'amount'                                => $row['Transaction Net Amount'],
                'Order No Product Transaction Fact Key' => $row['Order No Product Transaction Fact Key']
            );
        }
        if ($old_total != 0) {
            foreach ($old_charges_data as $key => $charges_data) {
                $old_charges_data[$key]['factor'] = $charges_data['amount'] / $old_total;
            }
        } else {
            foreach ($old_charges_data as $key => $charges_data) {
                $old_charges_data[$key]['factor'] = 1.0 / count(
                        $old_charges_data
                    );
            }
        }


        if (count($old_charges_data) == 0) {


            $sql = sprintf(
                "INSERT INTO `Order No Product Transaction Fact` (`Invoice Key`,`Invoice Date`,`Transaction Type`,`Transaction Description`,`Transaction Invoice Net Amount`,`Tax Category Code`,`Transaction Invoice Tax Amount`,`Transaction Outstanding Net Amount Balance`,`Transaction Outstanding Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)
                         VALUES (%d,%s,%s,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ", $this->id, prepare_mysql($this->data['Invoice Date']), prepare_mysql('Charges'),

                prepare_mysql($charge_data['Transaction Description']), $this->data['Invoice Charges Net Amount'], prepare_mysql($this->data['Invoice Tax Charges Code']),
                $this->data['Invoice Charges Tax Amount'], $this->data['Invoice Charges Net Amount'], $this->data['Invoice Charges Tax Amount'], prepare_mysql($this->data['Invoice Currency']),
                $this->data['Invoice Currency Exchange'], prepare_mysql($this->data['Invoice Metadata'])
            );


            mysql_query($sql);


        } elseif (count($old_charges_data) == 1) {
            $_tmp = array_pop($old_charges_data);
            $sql  = sprintf(
                "UPDATE  `Order No Product Transaction Fact` SET `Transaction Invoice Net Amount`=%f,`Transaction Invoice Tax Amount`=%f,`Transaction Outstanding Net Amount Balance`=%f,`Transaction Outstanding Tax Amount Balance`=%.2f WHERE `Order No Product Transaction Fact Key`=%d",
                $this->data['Invoice Charges Net Amount'], $this->data['Invoice Charges Tax Amount'], $this->data['Invoice Charges Net Amount'], $this->data['Invoice Charges Tax Amount'],
                $_tmp['Order No Product Transaction Fact Key']
            );
            mysql_query($sql);

        } else {
            foreach ($old_charges_data as $onptfk => $charges_data) {
                $net = $this->data['Invoice Charges Net Amount'] * $charges_data['factor'];
                $tax = $this->data['Invoice Charges Tax Amount'] * $charges_data['factor'];
                $sql = sprintf(
                    "UPDATE  `Order No Product Transaction Fact` SET `Transaction Invoice Net Amount`=%f,`Transaction Invoice Tax Amount`=%f,`Transaction Outstanding Net Amount Balance`=%f,`Transaction Outstanding Tax Amount Balance`=%.2f WHERE `Order No Product Transaction Fact Key`=%d",
                    $net, $tax, $net, $tax, $onptfk
                );
                mysql_query($sql);

            }

        }


        $sql = sprintf(
            "UPDATE `Invoice Dimension` SET `Invoice Charges Net Amount`=%f,`Invoice Charges Tax Amount`=%f WHERE `Invoice Key`=%d", $this->data['Invoice Charges Net Amount'],
            $this->data['Invoice Charges Tax Amount'], $this->id
        );
        mysql_query($sql);
        $this->distribute_charges_over_the_otf();

    }

    function get_tax_rate($item) {
        $rate = 0;
        switch ($item) {
            case 'shipping':
                $sql = sprintf(
                    "SELECT `Tax Category Rate` FROM kbase.`Tax Category Dimension` WHERE `Tax Category Code`=%s", prepare_mysql($this->data['Invoice Tax Shipping Code'])
                );
                $res = mysql_query($sql);

                $rate = 0;
                if ($row = mysql_fetch_assoc($res)) {
                    $rate = $row['Tax Category Rate'];
                }


                break;
            case('charges'):
                $sql  = sprintf(
                    "SELECT `Tax Category Rate` FROM kbase.`Tax Category Dimension` WHERE `Tax Category Code`=%s", prepare_mysql($this->data['Invoice Tax Charges Code'])
                );
                $res  = mysql_query($sql);
                $rate = 0;
                if ($row = mysql_fetch_assoc($res)) {
                    $rate = $row['Tax Category Rate'];
                }

                break;
            default:
                if (is_numeric($item)) {
                    $sql  = sprintf(
                        "SELECT `Transaction Tax Code`,`Transaction Tax Rate`FROM `Order Transaction Fact` WHERE `Order Transaction Fact Key`=%s", $item
                    );
                    $res2 = mysql_query($sql);
                    if ($row2['Transaction Tax Code'] == 'UNK') {
                        $rate = $row2['Transaction Tax Rate'];
                    } else {
                        $rate = 0;
                        if ($row2 = mysql_fetch_assoc($res2)) {

                            $sql  = sprintf(
                                "SELECT `Tax Category Rate` FROM kbase.`Tax Category Dimension` WHERE `Tax Category Code`=%s", prepare_mysql($row2['Transaction Tax Code'])
                            );
                            $res  = mysql_query($sql);
                            $rate = 0;
                            if ($row = mysql_fetch_assoc($res)) {
                                $rate = $row['Tax Category Rate'];
                            }
                        }
                    }
                }
                break;
        }

        return $rate;
    }

    function update_delivery_note_data($data) {
        $this->data['Invoice Delivery Country 2 Alpha Code']
            = $data['Invoice Delivery Country 2 Alpha Code'];
        $this->data['Invoice Delivery Country Code']
            = $data['Invoice Delivery Country Code'];
        $this->data['Invoice Delivery World Region Code']
            = $data['Invoice Delivery World Region Code'];
        $this->data['Invoice Delivery Town']
            = $data['Invoice Delivery Town'];
        $this->data['Invoice Delivery Postal Code']
            = $data['Invoice Delivery Postal Code'];


        $sql = sprintf(
            "UPDATE `Invoice Dimension` SET
                     `Invoice Delivery Country 2 Alpha Code`=%s ,
                     `Invoice Delivery Country Code`=%s,
                     `Invoice Delivery World Region Code`=%s,
                     `Invoice Delivery Town`=%s,
                     `Invoice Delivery Postal Code`=%s


                     WHERE `Invoice Key`=%d ", prepare_mysql($this->data['Invoice Delivery Country 2 Alpha Code']), prepare_mysql($this->data['Invoice Delivery Country Code']),
            prepare_mysql($this->data['Invoice Delivery World Region Code']), prepare_mysql($this->data['Invoice Delivery Town']), prepare_mysql($this->data['Invoice Delivery Postal Code']), $this->id
        );
        mysql_query($sql);

    }

    function display($tipo = 'xml') {


        switch ($tipo) {

            default:
                return 'todo';

        }


    }

    function get_number_orders() {

        $number_orders = 0;
        $sql           = sprintf(
            "SELECT count(*) AS num FROM `Order Invoice Bridge` WHERE `Invoice Key`=%d ", $this->id
        );

        $res = mysql_query($sql);

        if ($row = mysql_fetch_assoc($res)) {
            $number_orders = $row['num'];
        }

        return $number_orders;
    }

    function get_number_delivery_notes() {

        $number_delivery_notes = 0;
        $sql                   = sprintf(
            "SELECT count(*) AS num FROM `Invoice Delivery Note Bridge` WHERE `Invoice Key`=%d ", $this->id
        );

        $res = mysql_query($sql);

        if ($row = mysql_fetch_assoc($res)) {
            $number_delivery_notes = $row['num'];
        }

        return $number_delivery_notes;
    }

    function get_operations($user, $parent = 'order') {
        include_once 'utils/order_functions.php';

        return get_invoice_operations($this->data, $user, $parent);
    }

    function apply_payment($payment) {


        /*

		if ($this->data['Invoice Outstanding Total Amount']>0) {

			//print $payment->data['Payment Balance'].'='.$this->data['Invoice Outstanding Total Amount']." xx";

			if ($payment->data['Payment Balance']>=$this->data['Invoice Outstanding Total Amount']) {

				$to_pay=$this->data['Invoice Outstanding Total Amount'];


				$payment_amount_not_used=round($payment->data['Payment Balance']-$to_pay,2);
				$payment_amount_used=round($to_pay,2);
			}else {
				$this->set_as_parcially_paid($payment);
				$payment_amount_not_used= round(0.00,2);
				$payment_amount_used=round($payment->data['Payment Balance'],2);
			}

		}
		else {// Refund

			if ($payment->data['Payment Balance']<=$this->data['Invoice Outstanding Total Amount']) {
				$to_pay=$this->data['Invoice Outstanding Total Amount'];
				$payment_amount_not_used=$payment->data['Payment Balance']-$to_pay;
				$payment_amount_used=$to_pay;
			}else {
				$this->set_as_parcially_paid($payment);
				$payment_amount_not_used= 0;
				$payment_amount_used=$payment->data['Payment Balance'];
			}


		}

		*/


        $payment_date_to_update = array(
            'Payment Invoice Key' => $this->id,
            // 'Payment Balance'=>$payment_amount_not_used,
            //  'Payment Amount Invoiced'=>$payment_amount_used
        );
        //print_r($payment_date_to_update);
        $payment->update($payment_date_to_update);

        $sql = sprintf(
            "INSERT INTO `Invoice Payment Bridge`  (`Invoice Key`,`Payment Key`,`Payment Account Key`,`Payment Service Provider Key`,`Amount`) VALUES (%d,%d,%d,%d,%.2f) ", $this->id, $payment->id,
            $payment->data['Payment Account Key'], $payment->data['Payment Service Provider Key'], $payment->data['Payment Amount']
        );
        mysql_query($sql);


        $this->update_payment_state();


        return 0;

    }

    function update_payment_state() {


        $paid_amount = 0;
        $sql         = sprintf(
            "SELECT ifnull(sum(`Amount`),0) AS amount FROM `Invoice Payment Bridge` B LEFT JOIN `Payment Dimension` P  ON (B.`Payment Key`=P.`Payment Key`)  WHERE `Invoice Key`=%d AND `Payment Transaction Status`='Completed'",
            $this->id
        );


        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            $paid_amount = round($row['amount'], 2);
        }


        $this->data['Invoice Paid Amount']              = $paid_amount;
        $this->data['Invoice Outstanding Total Amount'] = round(
            $this->data['Invoice Total Amount'] - $this->data['Invoice Paid Amount'], 2
        );
        list($this->data['Invoice Main Payment Method'], $this->data['Invoice Payment Account Key'], $this->data['Invoice Payment Account Code'])
            = $this->get_main_payment_method();


        $sql = sprintf(
            "UPDATE `Invoice Dimension`  SET `Invoice Outstanding Total Amount`=%.2f, `Invoice Paid Amount`=%.2f,`Invoice Main Payment Method`=%s ,`Invoice Payment Account Key`=%s,`Invoice Payment Account Code`=%s     WHERE `Invoice Key`=%d",
            $this->data['Invoice Outstanding Total Amount'], $this->data['Invoice Paid Amount'], prepare_mysql($this->data['Invoice Main Payment Method']),
            prepare_mysql($this->data['Invoice Payment Account Key']), prepare_mysql($this->data['Invoice Payment Account Code']), $this->id
        );
        mysql_query($sql);

        //print $this->data['Invoice Outstanding Total Amount'].'<-';
        if ($this->data['Invoice Total Amount'] >= 0) {

            if ($this->data['Invoice Paid Amount'] == 0) {
                $this->set_as_not_paid();

                return;
            }


            if ($this->data['Invoice Outstanding Total Amount'] <= 0) {

                $this->set_as_full_paid();

            } else {
                $this->set_as_parcially_paid();
            }

        } else {//refund

            if ($this->data['Invoice Paid Amount'] == 0) {
                $this->set_as_not_paid();

                return;
            }


            if ($this->data['Invoice Outstanding Total Amount'] < 0) {
                $this->set_as_parcially_paid();
            } else {
                $this->set_as_full_paid();

            }

        }


    }

    function get_main_payment_method() {

        $method              = 'Unknown';
        $payent_account_key  = '';
        $payent_account_code = '';
        $sql                 = sprintf(
            "SELECT `Payment Method`,P.`Payment Account Key`,P.`Payment Account Code`   FROM `Invoice Payment Bridge` B LEFT JOIN `Payment Dimension` P ON (B.`Payment Key`=P.`Payment Key`) WHERE `Invoice Key`=%d  GROUP BY `Payment Method` ORDER BY sum(ABS(`Amount`)) DESC LIMIT 1  ",
            $this->id
        );

        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            $method              = $row['Payment Method'];
            $payent_account_key  = $row['Payment Account Key'];
            $payent_account_code = $row['Payment Account Code'];
        }


        return array(
            $method,
            $payent_account_key,
            $payent_account_code
        );

    }

    function set_as_not_paid() {

        $this->data['Invoice Paid']                  = 'No';
        $this->data['Invoice Has Been Paid In Full'] = 'No';

        $sql = sprintf(
            "UPDATE `Invoice Dimension`  SET `Invoice Paid`=%s,`Invoice Has Been Paid In Full`=%s WHERE `Invoice Key`=%d", prepare_mysql($this->data['Invoice Paid']),
            prepare_mysql($this->data['Invoice Has Been Paid In Full']), $this->id
        );
        mysql_query($sql);


    }

    function set_as_full_paid() {


        $this->data['Invoice Paid Date'] = gmdate("Y-m-d H:i:s");
        $sql                             = sprintf(
            "SELECT `Invoice Currency Exchange Rate`,`Invoice Transaction Net Refund Items`,`Order Transaction Fact Key`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Gross Amount` FROM `Order Transaction Fact` WHERE `Invoice Key`=%d  AND `Consolidated`='No' ",
            $this->id
        );

        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $sql = sprintf(
                "UPDATE  `Order Transaction Fact`  SET `Payment Method`=%s,`Invoice Transaction Outstanding Net Balance`=0,
			`Invoice Transaction Outstanding Tax Balance`=0,`Paid Factor`=1,`Current Payment State`='Paid',`Consolidated`='Yes',`Paid Date`=%s,`Invoice Transaction Outstanding Tax Balance`=0 ,`Invoice Transaction Outstanding Tax Balance`=0 WHERE `Order Transaction Fact Key`=%d ",
                prepare_mysql($this->data['Invoice Main Payment Method']), prepare_mysql($this->data['Invoice Paid Date']), $row['Order Transaction Fact Key']
            );

            mysql_query($sql);


            //print "$sql\n";
            $sql = sprintf(
                "UPDATE  `Inventory Transaction Fact`  SET `Amount In`=%f WHERE `Map To Order Transaction Fact Key`=%d ",
                $row['Invoice Currency Exchange Rate'] * ($row['Invoice Transaction Gross Amount'] - $row['Invoice Transaction Total Discount Amount'] - $row['Invoice Transaction Net Refund Items']),
                $row['Order Transaction Fact Key']
            );

            mysql_query($sql);
            //print "$sql\n";
        }

        $sql = sprintf(
            "SELECT `Order No Product Transaction Fact Key` FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d  AND `Consolidated`='No' ", $this->id
        );

        $res = mysql_query($sql);
        //print "\n\n$sql\n";
        while ($row = mysql_fetch_assoc($res)) {
            $sql = sprintf(
                "UPDATE  `Order No Product Transaction Fact`  SET `Payment Method`=%s,`Transaction Outstanding Net Amount Balance`=0,`Transaction Outstanding Tax Amount Balance`=0,`Paid Factor`=1,`Current Payment State`='Paid',`Consolidated`='Yes',`Paid Date`=%s WHERE `Order No Product Transaction Fact Key`=%d ",
                prepare_mysql($this->data['Invoice Main Payment Method']), prepare_mysql($this->data['Invoice Paid Date']), $row['Order No Product Transaction Fact Key']
            );

            mysql_query($sql);


        }

        $this->data['Invoice Paid']                  = 'Yes';
        $this->data['Invoice Has Been Paid In Full'] = 'Yes';

        $sql = sprintf(
            "UPDATE `Invoice Dimension`  SET `Invoice Paid Date`=%s,`Invoice Paid`=%s,`Invoice Has Been Paid In Full`=%s WHERE `Invoice Key`=%d", prepare_mysql($this->data['Invoice Paid Date']),
            prepare_mysql($this->data['Invoice Paid']), prepare_mysql($this->data['Invoice Has Been Paid In Full']), $this->id
        );
        mysql_query($sql);


    }

    function set_as_parcially_paid() {

        $this->data['Invoice Paid']                  = 'Partially';
        $this->data['Invoice Has Been Paid In Full'] = 'No';

        $sql = sprintf(
            "UPDATE `Invoice Dimension`  SET `Invoice Paid`=%s,`Invoice Has Been Paid In Full`=%s WHERE `Invoice Key`=%d", prepare_mysql($this->data['Invoice Paid']),
            prepare_mysql($this->data['Invoice Has Been Paid In Full']), $this->id
        );
        mysql_query($sql);


    }


    // this function to be deleted (used by old read order from excel)

    function pay($tipo = 'full', $data) {

        if (!array_key_exists('Invoice Paid Date', $data) or !$data['Invoice Paid Date']) {
            $data['Invoice Paid Date'] = gmdate('Y-m-d H:i:s');
        }

        if ($tipo == 'full' or $data['amount'] == $this->data['Invoice Outstanding Total Amount']) {
            $this->pay_full_amount($data);
        } else {
            $this->pay_partial_amount($data);
        }


        foreach ($this->get_orders_objects() as $key => $order) {

            // print_r($order);
            //exit;

            $order->update_payment_state();
            $order->update_totals();
            $order->update_full_search();
            if ($this->data['Invoice Type'] == 'Refund' or $this->data['Invoice Type'] == 'CreditNote') {
                $customer = new Customer($this->data['Invoice Customer Key']);
                $customer->add_history_order_refunded($this);

            }


        }

    }

    function pay_full_amount($data) {
        $this->data['Invoice Paid Date'] = $data['Invoice Paid Date'];
        $sql                             = sprintf(
            "SELECT `Invoice Currency Exchange Rate`,`Invoice Transaction Net Refund Items`,`Order Transaction Fact Key`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Gross Amount` FROM `Order Transaction Fact` WHERE `Invoice Key`=%d  AND `Consolidated`='No' ",
            $this->id
        );

        $res = mysql_query($sql);
        //print "$sql\n";
        while ($row = mysql_fetch_assoc($res)) {
            $sql = sprintf(
                "UPDATE  `Order Transaction Fact`  SET `Payment Method`=%s,`Invoice Transaction Outstanding Net Balance`=0,
			`Invoice Transaction Outstanding Tax Balance`=0,`Paid Factor`=1,`Current Payment State`='Paid',`Consolidated`='Yes',`Paid Date`=%s,`Invoice Transaction Outstanding Tax Balance`=0 ,`Invoice Transaction Outstanding Tax Balance`=0 WHERE `Order Transaction Fact Key`=%d ",
                prepare_mysql($data['Payment Method']), prepare_mysql($this->data['Invoice Paid Date']), $row['Order Transaction Fact Key']
            );

            mysql_query($sql);


            //print "$sql\n";
            $sql = sprintf(
                "UPDATE  `Inventory Transaction Fact`  SET `Amount In`=%f WHERE `Map To Order Transaction Fact Key`=%d ",
                $row['Invoice Currency Exchange Rate'] * ($row['Invoice Transaction Gross Amount'] - $row['Invoice Transaction Total Discount Amount'] - $row['Invoice Transaction Net Refund Items']),
                $row['Order Transaction Fact Key']
            );

            mysql_query($sql);
            //print "$sql\n";
        }

        $sql = sprintf(
            "SELECT `Order No Product Transaction Fact Key` FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d  AND `Consolidated`='No' ", $this->id
        );

        $res = mysql_query($sql);
        //print "\n\n$sql\n";
        while ($row = mysql_fetch_assoc($res)) {
            $sql = sprintf(
                "UPDATE  `Order No Product Transaction Fact`  SET `Payment Method`=%s,`Transaction Outstanding Net Amount Balance`=0,`Transaction Outstanding Tax Amount Balance`=0,`Paid Factor`=1,`Current Payment State`='Paid',`Consolidated`='Yes',`Paid Date`=%s WHERE `Order No Product Transaction Fact Key`=%d ",
                prepare_mysql($data['Payment Method']), prepare_mysql($this->data['Invoice Paid Date']), $row['Order No Product Transaction Fact Key']
            );

            mysql_query($sql);


        }


        $sql = sprintf(
            "UPDATE `Invoice Dimension`  SET `Invoice Outstanding Total Amount`=0,`Invoice Paid Amount`=%f,`Invoice Paid Date`=%s ,`Invoice Paid`='Yes',`Invoice Has Been Paid In Full`='Yes' WHERE `Invoice Key`=%d",
            $this->data['Invoice Total Amount'], prepare_mysql($this->data['Invoice Paid Date'])

            , $this->id
        );
        mysql_query($sql);

        $this->get_data('id', $this->id);


        list($this->data['Invoice Main Payment Method'], $this->data['Invoice Payment Account Key'], $this->data['Invoice Payment Account Code'])
            = $this->get_main_payment_method();

        $sql = sprintf(
            "UPDATE `Invoice Dimension`  SET `Invoice Main Payment Method`=%s,`Invoice Paid Date`=%s ,`Invoice Paid`='Yes',`Invoice Has Been Paid In Full`='Yes' ,`Invoice Payment Account Key`=%s,`Invoice Payment Account Code`=%s  WHERE `Invoice Key`=%d",
            prepare_mysql($this->data['Invoice Main Payment Method']), prepare_mysql($this->data['Invoice Paid Date']), prepare_mysql($this->data['Invoice Payment Account Key']),
            prepare_mysql($this->data['Invoice Payment Account Code']), $this->id
        );
        mysql_query($sql);


        $this->updated = true;

    }


    // this function has to go after retire excel

    function pay_partial_amount($data) {

    }

    function get_delivery_notes_objects() {
        $delivery_notes     = array();
        $delivery_notes_ids = $this->get_delivery_notes_ids();
        foreach ($delivery_notes_ids as $order_id) {
            $delivery_notes[$order_id] = new DeliveryNote($order_id);
        }

        return $delivery_notes;
    }

    function get_delivery_notes_ids() {
        $sql = sprintf(
            "SELECT `Delivery Note Key` FROM `Order Transaction Fact` WHERE `Invoice Key`=%d  OR  `Refund Key`=%d  GROUP BY `Delivery Note Key`", $this->id, $this->id
        );

        $res            = mysql_query($sql);
        $delivery_notes = array();
        while ($row = mysql_fetch_assoc($res)) {
            if ($row['Delivery Note Key']) {
                $delivery_notes[$row['Delivery Note Key']]
                    = $row['Delivery Note Key'];
            }

        }

        return $delivery_notes;

    }

    function categorize($skip_update_sales = false) {


        $sql = sprintf(
            "SELECT * FROM `Category Dimension` WHERE `Category Subject`='Invoice' AND `Category Store Key`=%d ORDER BY `Category Function Order`, `Category Key` ", $this->data['Invoice Store Key']
        );
        // print $sql;
        $res           = mysql_query($sql);
        $function_code = '';
        while ($row = mysql_fetch_assoc($res)) {
            if ($row['Category Function'] != '') {
                $function_code .= sprintf(
                    "%s return %d;", $row['Category Function'], $row['Category Key']
                );
            }


        }
        $function_code .= "return 0;";
        //print $function_code."\n";exit;
        $newfunc = create_function('$data', $function_code);

        // $this->data['Invoice Customer Level Type'];

        $category_key = $newfunc($this->data);

        //print "Cat $category_key\n";

        if ($category_key) {
            $category                    = new Category($category_key);
            $category->skip_update_sales = $skip_update_sales;


            if ($category->id) {
                //print "HOLA";
                $category->associate_subject($this->id);
                $this->update_field_switcher(
                    'Invoice Category Key', $category->id, 'no_history'
                );
            }
        }

    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {

        switch ($field) {


            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                }
        }
    }


    function add_refund_no_product_transaction($refund_transaction_data) {


        $sql = sprintf(
            "UPDATE `Order No Product Transaction Fact` SET
		`Affected Order Key`=%d,
		`Refund Key`=%d,
		`Refund Date`=%s,
		`Transaction Refund Net Amount`=%.2f,
		`Transaction Refund Tax Amount`=%.2f,
		`Transaction Outstanding Refund Net Amount Balance`=%.2f,
		`Transaction Outstanding Refund Tax Amount Balance`=%.2f


		 WHERE `Order No Product Transaction Fact Key`=%d
		", $refund_transaction_data['Order Key'], $this->id, prepare_mysql(gmdate('Y-m-d H:i:s')), $refund_transaction_data['Transaction Refund Net Amount'],
            $refund_transaction_data['Transaction Refund Tax Amount'], $refund_transaction_data['Transaction Refund Net Amount'], $refund_transaction_data['Transaction Refund Tax Amount'],
            $refund_transaction_data['Order No Product Transaction Fact Key']
        );
        mysql_query($sql);
        $this->update_refund_totals();
    }

    function add_credit_no_product_transaction($credit_transaction_data) {

        // Used when editing Customer Account Balance Value (Adding a credit)


        $sql = sprintf(
            "INSERT INTO `Order No Product Transaction Fact` (`Affected Order Key`,`Order Key`,`Order Date`,`Refund Key`,`Refund Date`,`Transaction Type`,`Transaction Description`,
		`Transaction Refund Net Amount`,`Tax Category Code`,`Transaction Refund Tax Amount`,`Transaction Outstanding Refund Net Amount Balance`,`Transaction Outstanding Refund Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)
		VALUES (%s,%s,%s,%d,%s,%s,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ", prepare_mysql($credit_transaction_data['Affected Order Key']), prepare_mysql($credit_transaction_data['Order Key']),
            prepare_mysql($credit_transaction_data['Order Date']), $this->id, prepare_mysql(gmdate("Y-m-d H:i:s")), prepare_mysql($credit_transaction_data['Transaction Type']),
            prepare_mysql($credit_transaction_data['Transaction Description']),

            $credit_transaction_data['Transaction Invoice Net Amount'], prepare_mysql($credit_transaction_data['Tax Category Code']), $credit_transaction_data['Transaction Invoice Tax Amount'],
            $credit_transaction_data['Transaction Invoice Net Amount'], $credit_transaction_data['Transaction Invoice Tax Amount'], prepare_mysql($this->data['Invoice Currency']),
            $this->data['Invoice Currency Exchange'], prepare_mysql($credit_transaction_data['Metadata'])


        );
        mysql_query($sql);

        $this->update_refund_totals();
    }

    function add_orphan_refund_no_product_transaction($refund_transaction_data) {


        $sql = sprintf(
            "INSERT INTO `Order No Product Transaction Fact` (`Order Key`,`Affected Order Key`,`Refund Key`,`Refund Date`,`Transaction Type`,`Transaction Description`,`Transaction Invoice Net Amount`,`Tax Category Code`,`Transaction Invoice Tax Amount`,`Transaction Outstanding Net Amount Balance`,`Transaction Outstanding Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)   VALUES (%s,%s,%d,%s,%s,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ",
            prepare_mysql($refund_transaction_data['Order Key']), prepare_mysql($refund_transaction_data['Affected Order Key']), $this->id, prepare_mysql($this->data['Invoice Date']),
            prepare_mysql('Refund'), prepare_mysql($refund_transaction_data['Transaction Description']), $refund_transaction_data['Transaction Invoice Net Amount'],
            prepare_mysql($refund_transaction_data['Tax Category Code']), $refund_transaction_data['Transaction Invoice Tax Amount'], $refund_transaction_data['Transaction Invoice Net Amount'],
            $refund_transaction_data['Transaction Invoice Tax Amount'], prepare_mysql($this->data['Invoice Currency']), $this->data['Invoice Currency Exchange'],
            prepare_mysql($this->data['Invoice Metadata'])
        );
        mysql_query($sql);
        // print $sql;
        $this->update_refund_totals();
    }

    function add_refund_transaction($refund_transaction_data) {


        $sql = sprintf(
            "UPDATE `Order Transaction Fact` SET
					`Refund Quantity`=%f,
					`Refund Metadata`=%s,
					`Refund Key`=%d,
                     `Invoice Transaction Net Refund Items`=%f,
                     `Invoice Transaction Net Refund Shipping`=%f,
                     `Invoice Transaction Net Refund Charges`=%f,
                     `Invoice Transaction Tax Refund Items`=%f,
                     `Invoice Transaction Tax Refund Shipping`=%f,
                     `Invoice Transaction Tax Refund Charges`=%f,

                     `Invoice Transaction Net Refund Amount`=%f,
                     `Invoice Transaction Tax Refund Amount`=%f  ,
                     `Invoice Transaction Outstanding Refund Net Balance`=%f ,
                     `Invoice Transaction Outstanding Refund Tax Balance`=%f

                     WHERE `Order Transaction Fact Key`=%d ", $refund_transaction_data['Refund Quantity'], prepare_mysql($refund_transaction_data['Refund Metadata']), $this->id,
            $refund_transaction_data['Invoice Transaction Net Refund Items'], $refund_transaction_data['Invoice Transaction Net Refund Shipping'],
            $refund_transaction_data['Invoice Transaction Net Refund Charges'], $refund_transaction_data['Invoice Transaction Tax Refund Items'],
            $refund_transaction_data['Invoice Transaction Tax Refund Shipping'], $refund_transaction_data['Invoice Transaction Tax Refund Charges'],


            $refund_transaction_data['Invoice Transaction Net Refund Amount'], $refund_transaction_data['Invoice Transaction Tax Refund Amount'],
            $refund_transaction_data['Invoice Transaction Net Refund Items'], $refund_transaction_data['Invoice Transaction Tax Refund Items'], $refund_transaction_data['Order Transaction Fact Key']

        );
        mysql_query($sql);
        //print $sql;
        //print "$sql\n";
        $this->update_refund_totals();
    }

    function get_payment_objects($status = '', $load_payment_account = false, $load_payment_service_provider = false) {


        $payments = array();

        if ($status) {
            $where = ' and `Payment Transaction Status`='.prepare_mysql(
                    $status
                );
        } else {
            $where = '';
        }

        $sql = sprintf(
            "SELECT `Payment Currency Code`,B.`Payment Key`,`Amount` FROM `Payment Dimension` PD LEFT JOIN `Invoice Payment Bridge` B ON (B.`Payment Key`=PD.`Payment Key`)  WHERE `Invoice Key`=%d %s",
            $this->id, $where
        );


        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {

            $payment = new Payment($row['Payment Key']);
            if ($load_payment_account) {
                $payment->load_payment_account();
            }
            if ($load_payment_service_provider) {
                $payment->load_payment_service_provider();
            }

            $payment->amount                   = $row['Amount'];
            $payment->formatted_invoice_amount = money(
                $row['Amount'], $row['Payment Currency Code']
            );


            $payments[$row['Payment Key']] = $payment;
        }

        return $payments;


    }

    function get_number_payments($status = '') {


        return count($this->get_payment_keys($status));
    }

    function get_payment_keys($status = '') {

        $payments = array();

        if ($status) {
            $where = ' and `Payment Transaction Status`='.prepare_mysql(
                    $status
                );
        } else {
            $where = '';
        }

        $sql = sprintf(
            "SELECT B.`Payment Key` FROM `Payment Dimension` PD LEFT JOIN `Invoice Payment Bridge` B ON (B.`Payment Key`=PD.`Payment Key`)  WHERE `Invoice Key`=%d %s", $this->id, $where
        );

        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $payments[$row['Payment Key']] = $row['Payment Key'];
        }

        return $payments;
    }

    function get_date($field) {
        return strftime("%e %b %Y", strtotime($this->data[$field].' +0:00'));
    }

    function delete() {


        $products     = array();
        $dates        = array();
        $customer_key = $this->get('Invoice Customer Key');
        $store_key    = $this->get('Invoice Store Key');
        $invoice_category_key == $this->get('Invoice Category Key');

        $sql = sprintf(
            "SELECT `Product ID`,`Invoice Date` FROM `Order Transaction Fact` WHERE `Invoice Key`=%d", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $products[$row['Product ID']] = 1;
                $dates[$row['Invoice Date']]  = 1;


            }
        }


        $orders = $this->get_orders_objects();
        $dns    = $this->get_delivery_notes_objects();

        $sql = sprintf(
            "DELETE FROM `Order Invoice Bridge` WHERE `Invoice Key`=%d   ", $this->id
        );
        mysql_query($sql);

        $sql = sprintf(
            "DELETE FROM `Invoice Tax Bridge` WHERE `Invoice Key`=%d", $this->id
        );
        mysql_query($sql);

        $sql = sprintf(
            "DELETE FROM `Invoice Sales Representative Bridge`  WHERE   `Invoice Key`=%d", $this->id
        );
        mysql_query($sql);

        $sql = sprintf(
            "DELETE FROM `Invoice Processed By Bridge`  WHERE   `Invoice Key`=%d", $this->id
        );
        mysql_query($sql);

        $sql = sprintf(
            "DELETE FROM `Invoice Charged By Bridge`  WHERE   `Invoice Key`=%d", $this->id
        );
        mysql_query($sql);

        $sql = sprintf(
            "DELETE FROM `Invoice Tax Dimension` WHERE `Invoice Key`=%d", $this->id
        );
        mysql_query($sql);

        $sql = sprintf(
            "DELETE FROM `Invoice Delivery Note Bridge` WHERE `Invoice Key`=%d   ", $this->id
        );
        mysql_query($sql);

        $sql = sprintf(
            "DELETE FROM `History Dimension`  WHERE   `Direct Object`='Invoice' AND `Direct Object Key`=%d", $this->id
        );
        mysql_query($sql);


        $payments = array();
        $sql      = sprintf(
            "SELECT * FROM `Invoice Payment Bridge` WHERE `Invoice Key`=%d", $this->id
        );
        $res      = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $payment    = new Payment($row['Payment Key']);
            $payments[] = $payment;

        }


        $sql = sprintf(
            "DELETE FROM `Invoice Payment Bridge`  WHERE    `Invoice Key`=%d", $this->id
        );
        mysql_query($sql);


        $sql = sprintf(
            "UPDATE `Payment Dimension`  SET `Payment Invoice Key`=NULL   WHERE `Payment Invoice Key`=%d", $this->id
        );
        mysql_query($sql);


        foreach ($payments as $payment) {
            $payment->update_balance();
        }


        $sql                       = sprintf(
            "SELECT `Category Key` FROM `Category Bridge`  WHERE   `Subject`='Invoice' AND `Subject Key`=%d", $this->id
        );
        $result_test_category_keys = mysql_query($sql);
        $_category_keys            = array();
        while ($row_test_category_keys = mysql_fetch_array(
            $result_test_category_keys, MYSQL_ASSOC
        )) {
            $_category_keys[$row_test_category_keys['Category Key']]
                = $row_test_category_keys['Category Key'];
        }
        $sql = sprintf(
            "DELETE FROM `Category Bridge`  WHERE   `Subject`='Invoice' AND `Subject Key`=%d", $this->id
        );
        mysql_query($sql);

        foreach ($_category_keys as $_category_key) {
            $_category = new Category($_category_key);
            $_category->update_children_data();
            $_category->update_subjects_data();
        }


        $this->data ['Order Invoiced Balance Total Amount']             = 0;
        $this->data ['Order Invoiced Balance Net Amount']               = 0;
        $this->data ['Order Invoiced Balance Tax Amount']               = 0;
        $this->data ['Order Invoiced Outstanding Balance Total Amount'] = 0;
        $this->data ['Order Invoiced Outstanding Balance Net Amount']   = 0;
        $this->data ['Order Invoiced Outstanding Balance Tax Amount']   = 0;


        $sql = sprintf(
            "DELETE FROM `Order Transaction Fact`  WHERE    `Invoice Key`=%d  AND (`Order Key`=0 OR `Order Key` IS NULL) ", $this->id
        );
        mysql_query($sql);

        $sql = sprintf(
            "UPDATE `Order Transaction Fact` SET `Invoice Date`=NULL , `Invoice Key`=NULL ,`Consolidated`='No'  WHERE  `Invoice Key`=%d", $this->id
        );
        mysql_query($sql);


        $sql = sprintf(
            "DELETE FROM `Order No Product Transaction Fact`  WHERE    `Invoice Key`=%d  AND (`Order Key`=0 OR `Order Key` IS NULL) ", $this->id
        );
        mysql_query($sql);

        $sql = sprintf(
            "UPDATE `Order No Product Transaction Fact` SET `Invoice Key`=NULL , `Consolidated`='No'   WHERE  `Invoice Key`=%d", $this->id
        );
        mysql_query($sql);


        $sql = sprintf(
            "DELETE FROM `Invoice Dimension`  WHERE  `Invoice Key`=%d", $this->id
        );
        mysql_query($sql);

        foreach ($dns as $dn) {


            $dn->update(
                array(
                    'Delivery Note Invoiced'                    => 'No',
                    'Delivery Note Invoiced Net DC Amountd'     => 0,
                    'Delivery Note Invoiced Shipping DC Amount' => 0

                )
            );

        }


        if ($this->data['Invoice Type'] == 'Refund') {
            $sql = sprintf(
                "UPDATE `Order Post Transaction Dimension` SET `State`=%s  WHERE `Refund Key`=%d   ", prepare_mysql('In Process'), $this->id
            );
            mysql_query($sql);

            $sql = sprintf(
                "UPDATE  `Order Transaction Fact` SET
			`Invoice Transaction Net Refund Items`=0.00 ,
			`Invoice Transaction Net Refund Shipping`=0,
			`Invoice Transaction Net Refund Charges`=0,
			`Invoice Transaction Net Refund Insurance`=0,
			`Invoice Transaction Tax Refund Items`=0.00 ,
			`Invoice Transaction Tax Refund Shipping`=0,
			`Invoice Transaction Tax Refund Charges`=0,
			`Invoice Transaction Tax Refund Insurance`=0,
			`Invoice Transaction Net Refund Amount`=0,
			`Invoice Transaction Tax Refund Amount`=0,
			`Invoice Transaction Outstanding Refund Net Balance`=0,
			`Invoice Transaction Outstanding Refund Tax Balance`=0,
			 `Refund Key`=NULL   WHERE  `Refund Key`=%d", $this->id
            );
            mysql_query($sql);

            $sql = sprintf(
                "UPDATE  `Order No Product Transaction Fact` SET
			`Affected Order Key`=NULL ,
			`Transaction Refund Net Amount`=0,
			`Transaction Refund Tax Amount`=0,
			 `Refund Key`=NULL   WHERE  `Refund Key`=%d", $this->id
            );
            mysql_query($sql);


        }

        foreach ($orders as $order) {
            if ($this->data['Invoice Type'] == 'Invoice') {
                $order->update(array('Order Invoiced' => 'No'));
            }

            $order->update_totals();


        }

        include_once 'new_fork.php';
        global $account_code;
        $msg = new_housekeeping_fork(
            'au_asset_sales', array(
                'type'                 => 'update_deleted_invoice_products_sales_data',
                'products'             => $products,
                'dates'                => $dates,
                'customer_key'         => $customer_key,
                'store_key'            => $store_key,
                'invoice_category_key' => $invoice_category_key
            ), $account_code
        );


        $this->deleted = true;
    }

    function get($key) {

        switch ($key) {
            case('Items Gross Amount'):
            case('Items Discount Amount'):
            case('Items Net Amount'):
            case('Items Tax Amount'):
            case('Refund Net Amount'):
            case('Charges Net Amount'):
            case('Shipping Net Amount'):
            case('Insurance Net Amount'):
            case('Total Net Amount'):
            case('Total Tax Amount'):
            case('Total Amount'):
            case('Total Net Adjust Amount'):
            case('Total Tax Adjust Amount'):
            case('Outstanding Total Amount'):
            case('Credit Net Amount'):
            case('Credit Net Amount'):


                return money(
                    $this->data['Invoice '.$key], $this->data['Invoice Currency']
                );
                break;
            case ('Net Amount Off'):
                return money(
                    -1 * $this->data['Invoice '.$key], $this->data['Invoice Currency']
                );

                break;

            case('Corporate Currency Total Amount'):
                global $corporate_currency;
                $_key = preg_replace('/Corporate Currency /', '', $key);

                return money(
                    $this->data['Invoice '.$_key] * $this->data['Invoice Currency Exchange'], $corporate_currency
                );
                break;
            case('Date'):
                return strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($this->data['Invoice Date'].' +0:00')
                );
                break;
            case('Payment Method'):

                switch ($this->data['Invoice Main Payment Method']) {
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
                    case 'Other':
                        return _('Other');
                        break;
                    case 'Unknown':
                        return _('Unknown');
                        break;


                        break;
                    default:
                        return $this->data['Invoice Main Payment Method'];
                        break;
                }
                break;
            case('Payment State'):
                return $this->get_formatted_payment_state();

            case 'State':

                switch ($this->data['Invoice Paid']) {
                    case 'Yes':
                        return _('Paid');
                        break;
                    case 'No':
                        return _('Not paid');
                        break;
                    case 'Partially':
                        return _('Partially paid');
                        break;
                    default:
                        return $this->data['Invoice Paid'];
                        break;
                }


        }


        if (isset($this->data[$key])) {
            return $this->data[$key];
        }


        if (array_key_exists('Invoice '.$key, $this->data)) {
            return $this->data[$this->table_name.' '.$key];
        }


        return false;
    }

    function get_formatted_payment_state() {

        switch ($this->data['Invoice Paid']) {
            case 'Yes':
                return _('Paid in full');
                break;
            case 'No':
                return _('Not Paid');
                break;
            case 'Partially':
                return _('Partially Paid');
                break;
            default:
                return _('Unknown');

        }
    }

    private function find($raw_data, $options = '') {

    }


}


?>
