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


class Invoice extends DB_Table {

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


        if ($this->data = $this->db->query($sql)->fetch()) {
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
            $this->data ['Invoice Sales Representative Keys'] = $invoice_data['Invoice Sales Representative Keys'];
        } else {
            $this->data ['Invoice Sales Representative Keys'] = array($this->editor['User Key']);
        }

        if (array_key_exists('Invoice Processed By Keys', $invoice_data)) {
            $this->data ['Invoice Processed By Keys'] = $invoice_data['Invoice Processed By Keys'];
        } else {
            $this->data ['Invoice Processed By Keys'] = array($this->editor['User Key']);
        }

        if (array_key_exists('Invoice Charged By Keys', $invoice_data)) {
            $this->data ['Invoice Charged By Keys'] = $invoice_data['Invoice Charged By Keys'];
        } else {
            $this->data ['Invoice Charged By Keys'] = array($this->editor['User Key']);
        }

        if (array_key_exists('Invoice Tax Number', $invoice_data)) {
            $this->data ['Invoice Tax Number'] = $invoice_data['Invoice Tax Number'];
        }
        if (array_key_exists('Invoice Tax Number Valid', $invoice_data)) {
            $this->data ['Invoice Tax Number Valid'] = $invoice_data['Invoice Tax Number Valid'];
        }
        if (array_key_exists(
            'Invoice Tax Number Validation Date', $invoice_data
        )) {
            $this->data ['Invoice Tax Number Validation Date'] = $invoice_data['Invoice Tax Number Validation Date'];
        }
        if (array_key_exists(
            'Invoice Tax Number Associated Name', $invoice_data
        )) {
            $this->data ['Invoice Tax Number Associated Name'] = $invoice_data['Invoice Tax Number Associated Name'];
        }
        if (array_key_exists(
            'Invoice Tax Number Associated Address', $invoice_data
        )) {
            $this->data ['Invoice Tax Number Associated Address'] = $invoice_data['Invoice Tax Number Associated Address'];
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
            $this->data ['Invoice Net Amount Off'] = $invoice_data['Invoice Net Amount Off'];
        } else {
            $this->data ['Invoice Net Amount Off'] = 0;
        }


        $this->data ['Invoice Billing To Key']               = $billing_to->id;
        $this->data ['Invoice XHTML Address']                = $billing_to->data['Billing To XHTML Address'];
        $this->data ['Invoice Billing Country 2 Alpha Code'] = ($billing_to->data['Billing To Country 2 Alpha Code'] == '' ? 'XX' : $billing_to->data['Billing To Country 2 Alpha Code']);

        $this->data ['Invoice Billing Country Code']      = ($billing_to->data['Billing To Country Code'] == '' ? 'UNK' : $billing_to->data['Billing To Country Code']);
        $this->data ['Invoice Billing World Region Code'] = $billing_to->get(
            'World Region Code'
        );
        $this->data ['Invoice Billing Town']              = $billing_to->data['Billing To Town'];
        $this->data ['Invoice Billing Postal Code']       = $billing_to->data['Billing To Postal Code'];

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


            $this->data ['Invoice Currency Exchange'] = $currency_exchange->get_exchange();


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


    function create($invoice_data) {

        include_once 'utils/currency_functions.php';

        $account = get_object('Account', 1);

        $base_data = $this->base_data();


        foreach ($invoice_data as $key => $value) {
            if (array_key_exists($key, $invoice_data)) {
                $base_data[$key] = _trim($value);
            }
        }


        $base_data['Invoice Currency Exchange'] = currency_conversion($this->db, $base_data['Invoice Currency'], $account->get('Account Currency'));


        $keys   = '(';
        $values = 'values (';
        foreach ($base_data as $key => $value) {
            $keys .= "`$key`,";
            if (preg_match('/xxxxxx/i', $key)) {
                $values .= prepare_mysql($value, false).",";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }

        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);


        $sql = "insert into `Invoice Dimension` $keys  $values ;";


        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();


            $this->get_data('id', $this->id);


            $sql = sprintf(
                'SELECT OTF.`Order Transaction Fact Key` FROM `Order Transaction Fact` OTF WHERE OTF.`Order Key`=%d  ', $this->data['Invoice Order Key']
            );

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {


                    $sql = sprintf(
                        "UPDATE `Order Transaction Fact` SET `Invoice Currency Exchange Rate`=%f,`Invoice Date`=%s, `Invoice Key`=%d WHERE `Order Transaction Fact Key`=%d",
                        ($this->data['Invoice Currency Exchange'] == '' ? 1 : $this->data['Invoice Currency Exchange']), prepare_mysql($this->data['Invoice Date']), $this->id,
                        $row['Order Transaction Fact Key']
                    );
                    $this->db->exec($sql);

                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $sql = sprintf(
                "SELECT `Order No Product Transaction Fact Key`,`Transaction Net Amount`,`Transaction Tax Amount`,`Transaction Type`  FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d AND ISNULL(`Invoice Key`) ",
                $this->data['Invoice Order Key']
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $sql = sprintf(
                        "UPDATE `Order No Product Transaction Fact` SET `Invoice Date`=%s,`Invoice Key`=%d WHERE `Order No Product Transaction Fact Key`=%d",
                        prepare_mysql($this->data['Invoice Date']), $this->id, $row['Order No Product Transaction Fact Key']
                    );
                    $this->db->exec($sql);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $data = array();

            $sql = sprintf(
                "SELECT  `Transaction Tax Code`,sum(`Order Transaction Amount`) AS net   FROM `Order Transaction Fact` WHERE
		`Invoice Key`=%d  GROUP BY  `Transaction Tax Code`  ", $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $data[$row['Transaction Tax Code']] = $row['net'];

                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $sql = sprintf(
                "SELECT  `Tax Category Code`, sum(`Transaction Net Amount`) AS net  FROM `Order No Product Transaction Fact` WHERE
		`Order Key`=%d  GROUP BY  `Tax Category Code`  ", $this->id
            );

            //print $sql;

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    if (isset($data[$row['Tax Category Code']])) {
                        $data[$row['Tax Category Code']] += $row['net'];
                    } else {
                        $data[$row['Tax Category Code']] = $row['net'];
                    }


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

            // print_r($data);

            $sql = sprintf(
                "    INSERT INTO `Invoice Tax Dimension` (`Invoice Key`) VALUES (%d)", $this->id
            );
            $this->db->exec($sql);


            foreach ($data as $tax_code => $amount) {

                $tax_category = get_object('Tax_Category', $tax_code);


                $tax = round($tax_category->get('Tax Category Rate') * $amount, 2);


                $is_base = 'Yes';

                $sql = sprintf(
                    "    UPDATE `Invoice Tax Dimension` SET `%s`=%.2f WHERE `Invoice Key`=%d", addslashes($tax_code), $tax, $this->id
                );
                $this->db->exec($sql);
                //    print "$sql\n";
                $sql = sprintf(
                    "INSERT INTO `Invoice Tax Bridge` VALUES (%d,%s,%.2f,%s) ON DUPLICATE KEY UPDATE `Tax Amount`=%.2f, `Tax Base`=%s", $this->id, prepare_mysql($tax_code), $tax,
                    prepare_mysql($is_base), $tax, prepare_mysql($is_base)

                );
                $this->db->exec($sql);
                //   print "$sql\n";


            }


            $sql = sprintf(
                "UPDATE `Payment Dimension` SET `Payment Invoice Key`=%d  WHERE `Payment Order Key`=%d",

                $this->id, $this->data['Invoice Order Key']
            );
            $this->db->exec($sql);



            $sql = sprintf(
                "UPDATE `Order Payment Bridge` SET `Invoice Key`=%d  WHERE `Order Key`=%d",

                $this->id, $this->data['Invoice Order Key']
            );
            $this->db->exec($sql);


            $this->update_payments_totals();


            //todo distribute_insurance_over_the_otf
            //$this->distribute_insurance_over_the_otf();


        } else {

            print "\n".$sql."\n";

            print_r($this->db->errorInfo());
        }


    }

    function update_payments_totals() {

        $payments = 0;

        $sql = sprintf(
            'SELECT sum(`Payment Transaction Amount`) AS amount  FROM `Order Payment Bridge` B LEFT JOIN `Payment Dimension` P  ON (B.`Payment Key`=P.`Payment Key`) WHERE `Invoice Key`=%d AND `Payment Transaction Status`="Completed" ',
            $this->id
        );

        // print $sql;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $payments = round($row['amount'], 2);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $to_pay = round($this->data['Invoice Total Amount'] - $payments, 2);

        $this->update(
            array(

                'Invoice Payments Amount'       => $payments,
                'Invoice To Pay Amount'         => $to_pay,
                'Invoice Has Been Paid In Full' => ($to_pay == 0 ? 'Yes' : 'No'),
                'Invoice Paid'                  => ($to_pay == 0 ? 'Yes' : ($payments == 0 ? 'No' : 'Partially')),

            ), 'no_history'
        );

        if($to_pay == 0){

            if($this->data['Invoice Paid Date']==''){
                $this->update_field('Invoice Paid Date',gmdate('Y-m-d H:i:s'),'no_history');
            }

        }else{
            $this->update_field('Invoice Paid Date','','no_history');
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
            $weight                                              = $row ['Estimated Weight'];
            $total_weight                                        += $weight;
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
            $_insurance                                             = $row ['Order Transaction Gross Amount'];
            $total_insurance                                        += $_insurance;
            $insurance_factor [$row ['Order Transaction Fact Key']] = $_insurance;
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
            $charge                                              = $row ['Order Transaction Gross Amount'];
            $total_charge                                        += $charge;
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

    function update_delivery_note_data($data) {
        $this->data['Invoice Delivery Country 2 Alpha Code'] = $data['Invoice Delivery Country 2 Alpha Code'];
        $this->data['Invoice Delivery Country Code']         = $data['Invoice Delivery Country Code'];
        $this->data['Invoice Delivery World Region Code']    = $data['Invoice Delivery World Region Code'];
        $this->data['Invoice Delivery Town']                 = $data['Invoice Delivery Town'];
        $this->data['Invoice Delivery Postal Code']          = $data['Invoice Delivery Postal Code'];


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


    // this function to be deleted (used by old read order from excel)

    function update_field_switcher($field, $value, $options = '', $metadata = '') {




        switch ($field) {

            case 'Invoice Public ID':
                $this->update_field($field, $value, $options);


                $number = strtolower($value);
                if (preg_match("/^\d+/", $number, $match)) {
                    $invoice_number = $match[0];
                    $file_as   = preg_replace('/^\d+/', sprintf("%012d", $invoice_number), $number);

                } elseif (preg_match("/\d+$/", $number, $match)) {
                    $invoice_number = $match[0];
                    $file_as  = preg_replace('/\d+$/', sprintf("%012d", $invoice_number), $number);

                } else {
                    $file_as   = $number;
                }

                $this->update_field('Invoice File As', $file_as, $options);

            break;
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


    // this function has to go after retire excel

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
            $items_refund_net                     += round(
                $row['Invoice Transaction Net Refund Items'], 2
            );
            $items_refund_tax                     += round(
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

            $shipping_net                         += round($row['Transaction Refund Net Amount'], 2);
            $shipping_tax                         += round($row['Transaction Refund Tax Amount'], 2);
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

            $charges_net                          += round($row['Transaction Refund Net Amount'], 2);
            $charges_tax                          += round($row['Transaction Refund Tax Amount'], 2);
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

            $insurance_net                        += round($row['Transaction Refund Net Amount'], 2);
            $insurance_tax                        += round($row['Transaction Refund Tax Amount'], 2);
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

            $credit_net                           += round($row['Transaction Refund Net Amount'], 2);
            $credit_tax                           += round($row['Transaction Refund Tax Amount'], 2);
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

        $this->data['Invoice Outstanding Net Balance'] = $items_refund_net_outstanding_balance;
        $this->data['Invoice Outstanding Tax Balance'] = $items_refund_tax_outstanding_balance;


        $this->data['Invoice Total Amount']             = round(
            $this->data['Invoice Total Net Amount'] + $this->data['Invoice Total Tax Amount'], 2
        );
        $this->data['Invoice Outstanding Total Amount'] = $this->data['Invoice Outstanding Net Balance'] + $this->data['Invoice Outstanding Tax Balance'];
        $sql                                            = sprintf(
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
            $items_refund_net                     += round(
                $row['Invoice Transaction Net Refund Items'], 2
            );
            $items_refund_tax                     += round(
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

            $shipping_net                         += round($row['Transaction Refund Net Amount'], 2);
            $shipping_tax                         += round($row['Transaction Refund Tax Amount'], 2);
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

            $charges_net                          += round($row['Transaction Refund Net Amount'], 2);
            $charges_tax                          += round($row['Transaction Refund Tax Amount'], 2);
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

            $insurance_net                        += round($row['Transaction Refund Net Amount'], 2);
            $insurance_tax                        += round($row['Transaction Refund Tax Amount'], 2);
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

            $credit_net                           += round($row['Transaction Refund Net Amount'], 2);
            $credit_tax                           += round($row['Transaction Refund Tax Amount'], 2);
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


        $this->data['Invoice Outstanding Net Balance'] = $items_refund_net_outstanding_balance;
        $this->data['Invoice Outstanding Tax Balance'] = $items_refund_tax_outstanding_balance;


        $this->data['Invoice Total Amount']             = round(
            $this->data['Invoice Total Net Amount'] + $this->data['Invoice Total Tax Amount'], 2
        );
        $this->data['Invoice Outstanding Total Amount'] = $this->data['Invoice Outstanding Net Balance'] + $this->data['Invoice Outstanding Tax Balance'];
        $sql                                            = sprintf(
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

        $this->data['Invoice Total Tax Amount']         = $tax;
        $this->data['Invoice Total Amount']             = $this->data['Invoice Total Net Amount'] + $this->data['Invoice Total Tax Amount'];
        $this->data['Invoice Outstanding Total Amount'] = $this->data['Invoice Total Amount'] - $this->data['Invoice Paid Amount'];

        $sql = sprintf(
            "UPDATE  `Invoice Dimension` SET `Invoice Total Tax Amount`=%.2f,`Invoice Total Amount`=%.2f,`Invoice Outstanding Total Amount`=%.2f    WHERE `Invoice Key`=%d",
            $this->data['Invoice Total Tax Amount'], $this->data['Invoice Total Amount'], $this->data['Invoice Outstanding Total Amount'],

            $this->id
        );
        mysql_query($sql);


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

    function get_date($field) {
        return strftime("%e %b %Y", strtotime($this->data[$field].' +0:00'));
    }

    function delete() {


        $products             = array();
        $dates                = array();
        $customer_key         = $this->get('Invoice Customer Key');
        $store_key            = $this->get('Invoice Store Key');
        $invoice_category_key = $this->get('Invoice Category Key');

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
            $_category_keys[$row_test_category_keys['Category Key']] = $row_test_category_keys['Category Key'];
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

            case 'Currency Code':

                return $this->data['Invoice Currency'];
                break;

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


    function get_payments($scope = 'keys',$filter='') {


        if($filter=='Completed'){
            $where=' and `Payment Transaction Status`="Completed" ';
        }else{
            $where='';
        }


        $payments = array();
        $sql      = sprintf(
            "SELECT B.`Payment Key` FROM `Order Payment Bridge` B left join `Payment Dimension` P  on (P.`Payment Key`=B.`Payment Key`)  WHERE `Invoice Key`=%d %s ", $this->id,$where
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Payment Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {
                    $payments[$row['Payment Key']] =  get_object('Payment',$row['Payment Key']);

                } else {
                    $payments[$row['Payment Key']] = $row['Payment Key'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $payments;

    }

}



?>
