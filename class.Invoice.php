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

    function __construct($arg1 = false, $arg2 = false, $arg3 = false, $arg4 = false) {

        $this->table_name      = 'Invoice';
        $this->ignore_fields   = array('Invoice Key');
        $this->update_customer = true;
        $this->deleted         = false;

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
        } elseif ($tipo == 'deleted') {
            $this->get_deleted_data($tag);

            return;
        } else {
            return;
        }
        //print $sql;


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Invoice Key'];
        }


    }

    function get_deleted_data($tag) {

        $this->deleted = true;
        $sql           = sprintf(
            "SELECT * FROM `Invoice Deleted Dimension` WHERE `Invoice Deleted Key`=%d", $tag
        );
        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Invoice Deleted Key'];


            foreach (
                json_decode($this->data['Invoice Deleted Metadata'], true) as $key => $value
            ) {


                if ($key == 'items') {
                    $this->items = $value;
                } else {
                    $this->data[$key] = $value;
                }


            }

            unset($this->data['Invoice Deleted Metadata']);

        }
    }

    function create_refund($invoice_data, $transactions) {

        include_once 'utils/new_fork.php';

        $date = $invoice_data['Invoice Date'];

        include_once 'utils/currency_functions.php';

        $account = get_object('Account', 1);


        $this->editor = $invoice_data['editor'];

        unset($invoice_data['editor']);

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

            $values .= prepare_mysql($value).",";

        }

        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);


        $sql = "insert into `Invoice Dimension` $keys  $values ;";
        //print "$sql\n\n";

        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();


            $this->get_data('id', $this->id);


            //  print_r($transactions);

            $feedback = array();

            foreach ($transactions as $transaction) {


                if ($transaction['type'] == 'otf') {


                    $sql = sprintf(
                        'SELECT * FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Order Transaction Fact Key`=%d AND `Order Transaction Type`="Order" ', $this->data['Invoice Order Key'], $transaction['id']
                    );

                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {

                            if ($transaction['amount'] > 0 and $transaction['amount'] <= ($row['Order Transaction Amount'])) {
                                $amount = -1.0 * $transaction['amount'];

                                $sql = sprintf(
                                    'INSERT INTO  `Order Transaction Fact` (`Order Date`,`Order Last Updated Date`,`Invoice Date`,`Order Transaction Type`,`Order Key`,`Invoice Key`,

                                `Product Key`,`Product ID`,`Store Key`,`Customer Key`,
                                `Order Transaction Gross Amount`,`Order Transaction Amount`,`Transaction Tax Rate`,`Transaction Tax Code`,`Order Currency Code`,`Invoice Currency Exchange Rate`,
                                `Product Code`
                                ) VALUES (%s,%s,%s,%s,%d,%d,
                                        %d,%d,%d,%d,
                                        %.2f, %.2f,%f,%s,%s,%f,%s
                                        
                                        
                                ) ', prepare_mysql($date), prepare_mysql($date), prepare_mysql($date), prepare_mysql('Refund'), $this->data['Invoice Order Key'], $this->id, $row['Product Key'], $row['Product ID'], $row['Store Key'], $row['Customer Key'], $amount,
                                    $amount, $row['Transaction Tax Rate'], prepare_mysql($row['Transaction Tax Code']), prepare_mysql($row['Order Currency Code']), $base_data['Invoice Currency Exchange'], prepare_mysql($row['Product Code'])
                                );


                                $this->db->exec($sql);
                                $refund_otf       = $this->db->lastInsertId();
                                $_feedback        = $transaction['feedback'];
                                $_feedback['otf'] = $refund_otf;
                                $feedback[]       = $_feedback;


                            }
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                }
                if ($transaction['type'] == 'otf_tax') {


                    $sql = sprintf(
                        'SELECT * FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Order Transaction Fact Key`=%d AND `Order Transaction Type`="Order" ', $this->data['Invoice Order Key'], $transaction['id']
                    );

                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {


                            if ($transaction['amount'] > 0) {
                                $tax_amount = -1.0 * $transaction['amount'];
                                $amount     = 0;

                                $sql = sprintf(
                                    'INSERT INTO  `Order Transaction Fact` (`Order Date`,`Order Last Updated Date`,`Invoice Date`,`Order Transaction Type`,`Order Key`,`Invoice Key`,

                                `Product Key`,`Product ID`,`Store Key`,`Customer Key`,
                                `Order Transaction Gross Amount`,`Order Transaction Amount`,`Transaction Tax Rate`,`Transaction Tax Code`,`Order Currency Code`,`Invoice Currency Exchange Rate`,
                                `Product Code`,`Order Transaction Metadata`
                                ) VALUES (%s,%s,%s,%s,%d,%d,
                                        %d,%d,%d,%d,
                                        %.2f, %.2f,%f,%s,%s,%f,%s,%s
                                        
                                        
                                ) ', prepare_mysql($date), prepare_mysql($date), prepare_mysql($date), prepare_mysql('Refund'), $this->data['Invoice Order Key'], $this->id, $row['Product Key'], $row['Product ID'], $row['Store Key'], $row['Customer Key'], $amount,
                                    $amount, $row['Transaction Tax Rate'], prepare_mysql($row['Transaction Tax Code']), prepare_mysql($row['Order Currency Code']), $base_data['Invoice Currency Exchange'], prepare_mysql($row['Product Code']),
                                    prepare_mysql(json_encode(array('TORA' => $tax_amount)))
                                );


                                $this->db->exec($sql);


                            }
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                } elseif ($transaction['type'] == 'onptf') {


                    $sql = sprintf(
                        'SELECT * FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d AND `Order No Product Transaction Fact Key`=%d AND `Type`="Order" ', $this->data['Invoice Order Key'], $transaction['id']
                    );

                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {

                            if ($transaction['amount'] > 0 and $transaction['amount'] <= ($row['Transaction Net Amount'] - $row['Transaction Refund Net Amount'])) {
                                $amount = -1.0 * $transaction['amount'];

                                $sql = sprintf(
                                    'INSERT INTO  `Order No Product Transaction Fact` (
`Order Date`,`Invoice Date`,`Type`,`Order Key`,
`Invoice Key`,`Transaction Type`,`Transaction Type Key`,
                                `Transaction Description`,
                                `Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,
                                `Currency Code`,`Currency Exchange`
                            
                                ) VALUES (%s,%s,%s,%d,
                                %d,%s,%d,
                                        %s,
                                        %.2f, %.2f,%s,
                                        %s,%f
                                        
                                        
                                ) ', prepare_mysql($date), prepare_mysql($date), prepare_mysql('Refund'), $this->data['Invoice Order Key'], $this->id, prepare_mysql($row['Transaction Type']), $row['Transaction Type Key'], prepare_mysql($row['Transaction Description']),
                                    $amount, $amount, prepare_mysql($row['Tax Category Code']), prepare_mysql($row['Currency Code']), $row['Currency Exchange']
                                );


                                $this->db->exec($sql);
                                $refund_onptf       = $this->db->lastInsertId();
                                $_feedback          = $transaction['feedback'];
                                $_feedback['onptf'] = $refund_onptf;
                                $feedback[]         = $_feedback;

                                //    print "$sql\n";
                            }
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                } elseif ($transaction['type'] == 'onptf_tax') {


                    $sql = sprintf(
                        'SELECT * FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d AND `Order No Product Transaction Fact Key`=%d AND `Type`="Order" ', $this->data['Invoice Order Key'], $transaction['id']
                    );

                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {

                            if ($transaction['amount'] > 0) {

                                $tax_amount = -1.0 * $transaction['amount'];
                                $amount     = 0;
                                $sql        = sprintf(
                                    'INSERT INTO  `Order No Product Transaction Fact` (
`Order Date`,`Invoice Date`,`Type`,`Order Key`,
`Invoice Key`,`Transaction Type`,`Transaction Type Key`,
                                `Transaction Description`,
                                `Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,
                                `Currency Code`,`Currency Exchange`,`Order No Product Transaction Metadata`
                            
                                ) VALUES (%s,%s,%s,%d,
                                %d,%s,%d,
                                        %s,
                                        %.2f, %.2f,%s,
                                        %s,%f,%s
                                        
                                        
                                ) ', prepare_mysql($date), prepare_mysql($date), prepare_mysql('Refund'), $this->data['Invoice Order Key'], $this->id, prepare_mysql($row['Transaction Type']), $row['Transaction Type Key'], prepare_mysql($row['Transaction Description']),
                                    $amount, $amount, prepare_mysql($row['Tax Category Code']), prepare_mysql($row['Currency Code']), $row['Currency Exchange'], prepare_mysql(json_encode(array('TORA' => $tax_amount)))
                                );


                                $this->db->exec($sql);
                                //    print "$sql\n";
                            }
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                }


            }


            //exit;
            $data = array();

            $shipping_net  = 0;
            $charges_net   = 0;
            $insurance_net = 0;
            $other_net     = 0;
            $item_net      = 0;
            $tax_total     = 0;

            $sql = sprintf(
                "SELECT  `Transaction Tax Code`,sum(`Order Transaction Amount`) AS net FROM `Order Transaction Fact` WHERE `Invoice Key`=%d  GROUP BY  `Transaction Tax Code`  ", $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $data[$row['Transaction Tax Code']] = $row['net'];
                    $item_net                           += $row['net'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }
            //'Credit','Unknown','Refund','Shipping','Charges','Adjust','Other','Deal','Insurance','Discount'

            $sql = sprintf(
                "SELECT   sum(`Transaction Net Amount`) AS net ,`Transaction Type` FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d  GROUP BY  `Transaction Type`  ", $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {


                    if ($row['Transaction Type'] == 'Shipping') {
                        $shipping_net += $row['net'];
                    } elseif ($row['Transaction Type'] == 'Charges') {
                        $charges_net += $row['net'];
                    } elseif ($row['Transaction Type'] == 'Insurance') {
                        $insurance_net += $row['net'];
                    } else {
                        $other_net += $row['net'];
                    }


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $sql = sprintf(
                "SELECT  `Tax Category Code`, sum(`Transaction Net Amount`) AS net  FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d  GROUP BY  `Tax Category Code`  ", $this->id
            );


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

            //print "$sql\n";

            $this->db->exec($sql);


            if ($this->get('Invoice Tax Type') == 'Tax_Only') {

                $tax_total_data = array();


                $sql = sprintf(
                    "SELECT  `Tax Category Code`,`Order No Product Transaction Metadata`  FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d  ", $this->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {


                        if ($row['Order No Product Transaction Metadata'] != '') {

                            $_data = json_decode($row['Order No Product Transaction Metadata'], true);


                            if (isset($_data['TORA'])) {

                                if (isset($tax_total_data[$row['Tax Category Code']])) {
                                    $tax_total_data[$row['Tax Category Code']] += $_data['TORA'];

                                } else {
                                    $tax_total_data[$row['Tax Category Code']] = $_data['TORA'];

                                }

                            }

                        }


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $sql = sprintf(
                    "SELECT  `Transaction Tax Code`,`Order Transaction Metadata`  FROM `Order Transaction Fact` WHERE `Invoice Key`=%d  ", $this->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {


                        if ($row['Order Transaction Metadata'] != '') {

                            $_data = json_decode($row['Order Transaction Metadata'], true);


                            if (isset($_data['TORA'])) {

                                if (isset($tax_total_data[$row['Transaction Tax Code']])) {
                                    $tax_total_data[$row['Transaction Tax Code']] += $_data['TORA'];

                                } else {
                                    $tax_total_data[$row['Transaction Tax Code']] = $_data['TORA'];

                                }

                            }

                        }


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                foreach ($tax_total_data as $tax_code => $tax) {

                    $tax_total += $tax;

                    $is_base = 'Yes';

                    $sql = sprintf(
                        "    UPDATE `Invoice Tax Dimension` SET `%s`=%.2f WHERE `Invoice Key`=%d", addslashes($tax_code), $tax, $this->id
                    );
                    $this->db->exec($sql);
                    // print "$sql\n";
                    $sql = sprintf(
                        "INSERT INTO `Invoice Tax Bridge` (`Invoice Key`,`Tax Code`,`Tax Amount`,`Tax Base`) VALUES   (%d,%s,%.2f,%s) ON DUPLICATE KEY UPDATE `Tax Amount`=%.2f, `Tax Base`=%s", $this->id, prepare_mysql($tax_code), $tax, prepare_mysql($is_base), $tax,
                        prepare_mysql($is_base)

                    );
                    $this->db->exec($sql);

                }


            } else {
                foreach ($data as $tax_code => $amount) {


                    $tax_category = get_object('Tax_Category', $tax_code);
                    $tax          = round($tax_category->get('Tax Category Rate') * $amount, 2);
                    $tax_total    += $tax;
                    $is_base      = 'Yes';

                    $sql = sprintf(
                        "    UPDATE `Invoice Tax Dimension` SET `%s`=%.2f WHERE `Invoice Key`=%d", addslashes($tax_code), $tax, $this->id
                    );
                    $this->db->exec($sql);
                    // print "$sql\n";
                    $sql = sprintf(
                        "INSERT INTO `Invoice Tax Bridge` (`Invoice Key`,`Tax Code`,`Tax Amount`,`Tax Base`) VALUES   (%d,%s,%.2f,%s) ON DUPLICATE KEY UPDATE `Tax Amount`=%.2f, `Tax Base`=%s", $this->id, prepare_mysql($tax_code), $tax, prepare_mysql($is_base), $tax,
                        prepare_mysql($is_base)

                    );
                    $this->db->exec($sql);
                    //print "$sql\n";


                }
            }


            $net_total = $item_net + $shipping_net + $charges_net + $insurance_net;
            $total     = $tax_total + $net_total;


            $this->fast_update(

                array(
                    'Invoice Items Gross Amount'        => $item_net,
                    'Invoice Items Discount Amount'     => 0,
                    'Invoice Items Net Amount'          => $item_net,
                    'Invoice Items Out of Stock Amount' => 0,
                    'Invoice Shipping Net Amount'       => $shipping_net,
                    'Invoice Charges Net Amount'        => $charges_net,
                    'Invoice Insurance Net Amount'      => $insurance_net,
                    'Invoice Total Net Amount'          => $net_total,
                    'Invoice Total Tax Amount'          => $tax_total,
                    'Invoice Payments Amount'           => 0,
                    'Invoice To Pay Amount'             => $total,
                    'Invoice Total Amount'              => $total,
                )

            );


            $this->update_payments_totals();


            //todo distribute_insurance_over_the_otf
            //$this->distribute_insurance_over_the_otf();


            $customer = get_object('Customer', $this->get('Invoice Customer Key'));
            $customer->update_invoices();


            $profit = 0;
            $sql    = sprintf(
                "SELECT sum(`Order Transaction Amount`) AS net  FROM `Order Transaction Fact` WHERE `Invoice Key`=%d AND `Order Transaction Type`='Refund' ", $this->id
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {


                    $profit = $row['net'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }
            $this->fast_update(
                array(

                    'Invoice Total Profit' => $profit
                )
            );


            $this->update_billing_region();

            $history_data = array(
                'History Abstract' => sprintf(_('Refund %s created'), $this->get('Public ID')),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'         => 'invoice_created',
                'invoice_key'  => $this->id,
                'customer_key' => $this->get('Invoice Customer Key'),
                'store_key'    => $this->get('Invoice Store Key')
            ), $account->get('Account Code')
            );


            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'       => 'feedback',
                'feedback'   => $feedback,
                'user_key'   => $this->editor['User Key'],
                'parent'     => 'Refund',
                'parent_key' => $this->id,
                'store_key'  => $this->get('Store Key'),
                'editor'     => $this->editor
            ), $account->get('Account Code'), $this->db
            );


            return $this;

        } else {

            print "\n".$sql."\n";

            print_r($this->db->errorInfo());
        }


    }


    function update_payments_totals() {


        $payments = 0;

        $sql = sprintf(
            'SELECT sum(`Payment Transaction Amount`) AS amount  FROM `Order Payment Bridge` B LEFT JOIN `Payment Dimension` P  ON (B.`Payment Key`=P.`Payment Key`) WHERE `Invoice Key`=%d AND `Payment Transaction Status`="Completed" ', $this->id
        );

        // print $sql;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                //print_r($row);

                $payments = round($row['amount'], 2);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $to_pay = round($this->data['Invoice Total Amount'] - $payments, 2);


        $this->fast_update(
            array(

                'Invoice Payments Amount' => $payments,
                'Invoice To Pay Amount'   => $to_pay,
                'Invoice Paid'            => ($to_pay <= 0 ? 'Yes' : ($payments == 0 ? 'No' : 'Partially')),

            )
        );

        if ($to_pay == 0) {

            if ($this->data['Invoice Paid Date'] == '') {
                $this->update_field('Invoice Paid Date', gmdate('Y-m-d H:i:s'), 'no_history');
            }

        } else {
            $this->update_field('Invoice Paid Date', '', 'no_history');
        }


    }

    function get($key) {

        if (!$this->id) {
            return false;
        }


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
            case('Total Profit'):

                return money(
                    $this->data['Invoice '.$key], $this->data['Invoice Currency']
                );

            case('Refund Items Gross Amount'):
            case('Refund Items Discount Amount'):
            case('Refund Items Net Amount'):
            case('Refund Items Tax Amount'):
            case('Refund Charges Net Amount'):
            case('Refund Shipping Net Amount'):
            case('Refund Insurance Net Amount'):
            case('Refund Total Net Amount'):
            case('Refund Total Tax Amount'):
            case('Refund Total Amount'):
            case('Refund Total Net Adjust Amount'):
            case('Refund Total Tax Adjust Amount'):
            case('Refund Payments Amount'):
            case 'Refund To Pay Amount':
                $key = preg_replace('/Refund /', '', $key);


                if ($this->data['Invoice '.$key] == 0) {
                    return money(0, $this->data['Invoice Currency']);

                } else {
                    return money(
                        -1 * $this->data['Invoice '.$key], $this->data['Invoice Currency']
                    );
                }


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
            case('Deleted Date'):
                return strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($this->data['Invoice Deleted Date'].' +0:00')
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
            case 'Margin':

                if ($this->data['Invoice Items Net Amount'] > 0) {
                    return percentage($this->data['Invoice Total Profit'] / $this->data['Invoice Items Net Amount'], 1);
                } else {
                    return '-';
                }


                break;

            case 'Category Object':

                return get_object('Category', $this->data['Invoice Category Key']);
                break;

            case 'number_otfs':

                $number_otfs = 0;
                $sql         = sprintf('select count(*) as num from `Order Transaction Fact` where `Invoice Key`=%d  ', $this->id);

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $number_otfs = $row['num'];
                    }
                }

                return $number_otfs;
                break;
            case 'number_onptf':

                $number_onptf = 0;
                $sql          = sprintf('select count(*) as num from `Order No Product Transaction Fact` where `Invoice Key`=%d  ', $this->id);

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $number_onptf = $row['num'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                return $number_onptf;
                break;

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

        if ($this->data['Invoice Type'] == 'Refund') {
            switch ($this->data['Invoice Paid']) {
                case 'Yes':
                    return _('Paid back in full');
                    break;
                case 'No':
                    return _('Not paid back');
                    break;
                case 'Partially':
                    return _('Partially paid back');
                    break;
                default:
                    return _('Unknown');

            }
        } else {
            switch ($this->data['Invoice Paid']) {
                case 'Yes':
                    return _('Paid in full');
                    break;
                case 'No':
                    return _('Not paid');
                    break;
                case 'Partially':
                    return _('Partially Paid');
                    break;
                default:
                    return _('Unknown');

            }
        }


    }

    function update_billing_region() {
        $account               = get_object('Account', 1);
        $european_union_2alpha = array(
            'NL',
            'BE',
            'GB',
            'BG',
            'ES',
            'IE',
            'IT',
            'AT',
            'GR',
            'CY',
            'LV',
            'LT',
            'LU',
            'MT',
            'PT',
            'PL',
            'FR',
            'RO',
            'SE',
            'DE',
            'SK',
            'SI',
            'FI',
            'DK',
            'CZ',
            'HU',
            'EE'
        );


        $european_union = array(
            'NLD',
            'BEL',
            'GBR',
            'BGR',
            'ESP',
            'IRL',
            'ITA',
            'AUT',
            'GRC',
            'CYP',
            'LVA',
            'LTU',
            'LUX',
            'MLT',
            'PRT',
            'POL',
            'FRA',
            'ROU',
            'SWE',
            'DEU',
            'SVK',
            'SVN',
            'FIN',
            'DNK',
            'CZE',
            'HUN',
            'EST'
        );


        include_once('class.Country.php');

        $account_country = new Country('code', $account->get('Account Country Code'));


        if ($account->get('Account Country Code') == 'GBR') {


            if ($this->get('Invoice Address Country 2 Alpha Code') == 'GB' or $this->get('Invoice Address Country 2 Alpha Code') == 'IM') {
                $billing_region = 'GBIM';
            } else {
                if (in_array($this->get('Invoice Address Country 2 Alpha Code'), $european_union_2alpha)) {
                    $billing_region = 'EU';
                } elseif ($this->get('Invoice Address Country 2 Alpha Code') == 'XX') {
                    $billing_region = 'Unknown';
                } else {
                    $billing_region = 'NOEU';
                }
            }

        } elseif (in_array($account->get('Account Country Code'), $european_union)) {

            //   exit;

            if ($this->get('Invoice Address Country 2 Alpha Code') == $account_country->get('Country 2 Alpha Code')) {
                $billing_region = $account->get('Account Country Code');
            } else {
                if (in_array($this->get('Invoice Address Country 2 Alpha Code'), $european_union_2alpha)) {
                    $billing_region = 'EU';
                } elseif ($this->get('Invoice Address Country 2 Alpha Code') == 'XX') {

                    $billing_region = 'Unknown';


                } else {
                    $billing_region = 'NOEU';
                }
            }


        } else {

            if ($this->get('Invoice Address Country 2 Alpha Code') == $account_country->get('Country 2 Alpha Code')) {
                $billing_region = $account->get('Account Country Code');
            } elseif ($this->get('Invoice Address Country 2 Alpha Code') == 'XX') {
                $billing_region = 'Unknown';
            } else {
                $billing_region = 'Export';
            }


        }


        $this->update(array('Invoice Billing Region' => $billing_region), 'no_history');
    }

    function create($invoice_data) {

        include_once 'utils/currency_functions.php';
        include_once 'utils/new_fork.php';

        $account = get_object('Account', 1);

        $base_data = $this->base_data();

        $this->editor = $invoice_data['editor'];

        unset($invoice_data['editor']);

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
                        "UPDATE `Order Transaction Fact` SET `Invoice Currency Exchange Rate`=%f,`Invoice Date`=%s, `Invoice Key`=%d WHERE `Order Transaction Fact Key`=%d", ($this->data['Invoice Currency Exchange'] == '' ? 1 : $this->data['Invoice Currency Exchange']),
                        prepare_mysql($this->data['Invoice Date']), $this->id, $row['Order Transaction Fact Key']
                    );
                    $this->db->exec($sql);

                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $sql = sprintf(
                "SELECT `Order No Product Transaction Fact Key`,`Transaction Net Amount`,`Transaction Tax Amount`,`Transaction Type`  FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d AND ISNULL(`Invoice Key`) ", $this->data['Invoice Order Key']
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $sql = sprintf(
                        "UPDATE `Order No Product Transaction Fact` SET `Invoice Date`=%s,`Invoice Key`=%d WHERE `Order No Product Transaction Fact Key`=%d", prepare_mysql($this->data['Invoice Date']), $this->id, $row['Order No Product Transaction Fact Key']
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
                "SELECT  `Transaction Tax Code`,sum(`Order Transaction Amount`) AS net   FROM `Order Transaction Fact` WHERE `Invoice Key`=%d  GROUP BY  `Transaction Tax Code`  ", $this->id
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
                "SELECT  `Tax Category Code`, sum(`Transaction Net Amount`) AS net  FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d  GROUP BY  `Tax Category Code`  ", $this->id
            );


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




            if($this->data['Invoice Net Amount Off']!=0 ){


                if (isset($data[$this->data['Invoice Tax Code']] )) {
                    $data[$this->data['Invoice Tax Code']] -= $this->data['Invoice Net Amount Off'];
                } else {
                    $data[$this->data['Invoice Tax Code']] = -$this->data['Invoice Net Amount Off'];
                }


            }



            $sql = sprintf(
                "    INSERT INTO `Invoice Tax Dimension` (`Invoice Key`) VALUES (%d)", $this->id
            );

          //  print "$sql\n";

            $this->db->exec($sql);


            foreach ($data as $tax_code => $amount) {


                $tax_category = get_object('Tax_Category', $tax_code);
                $tax          = round($tax_category->get('Tax Category Rate') * $amount, 2);

                $is_base = 'Yes';

                $sql = sprintf(
                    "    UPDATE `Invoice Tax Dimension` SET `%s`=%.2f WHERE `Invoice Key`=%d", addslashes($tax_code), $tax, $this->id
                );
                $this->db->exec($sql);
                // print "$sql\n";
                $sql = sprintf(
                    "INSERT INTO `Invoice Tax Bridge` (`Invoice Key`,`Tax Code`,`Tax Amount`,`Tax Base`) VALUES   (%d,%s,%.2f,%s) ON DUPLICATE KEY UPDATE `Tax Amount`=%.2f, `Tax Base`=%s", $this->id, prepare_mysql($tax_code), $tax, prepare_mysql($is_base), $tax,
                    prepare_mysql($is_base)

                );
                $this->db->exec($sql);
                //print "$sql\n";


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


            $profit = 0;
            $sql    = sprintf(
                "SELECT sum(`Cost Supplier`) AS cost, sum(`Order Transaction Amount`) AS net  FROM `Order Transaction Fact` WHERE `Invoice Key`=%d AND `Order Transaction Type`='Order' ", $this->id
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {


                    $profit = $row['net'] - $row['cost'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }
            $this->fast_update(
                array(

                    'Invoice Total Profit' => $profit
                )
            );


            $this->update_billing_region();

            //todo distribute_insurance_over_the_otf
            //$this->distribute_insurance_over_the_otf();


            $history_data = array(
                'History Abstract' => sprintf(_('Invoice %s created'), $this->get('Public ID')),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'         => 'invoice_created',
                'invoice_key'  => $this->id,
                'customer_key' => $this->get('Invoice Customer Key'),
                'store_key'    => $this->get('Invoice Store Key')
            ), $account->get('Account Code')
            );


        }


    }


    function categorize($skip_update_sales = false) {

        $category_key = 0;


        include_once 'conf/invoice_categorize_functions.php';

        $categorize_invoices_functions = get_categorize_invoices_functions();

        $sql = sprintf(
            "SELECT `Invoice Category Key`,`Invoice Category Function Code`,`Invoice Category Function Argument` FROM `Invoice Category Dimension` WHERE `Invoice Category Function Code` is not null ORDER BY `Invoice Category Function Order` desc "
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                if (isset($categorize_invoices_functions[$row['Invoice Category Function Code']])) {
                    if ($categorize_invoices_functions[$row['Invoice Category Function Code']]($this->data, $row['Invoice Category Function Argument'])) {
                        $category_key = $row['Invoice Category Key'];
                        break;
                    }


                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($category_key) {
            $category                    = get_object('Category', $category_key);
            $category->skip_update_sales = $skip_update_sales;


            if ($category->id) {
                $category->associate_subject($this->id);
                $this->fast_update(
                    array(
                        'Invoice Category Key' => $category->id
                    )

                );
            }
        }

    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {

            case 'Invoice Public ID':
                $this->update_field($field, $value, $options);


                $number = strtolower($value);
                if (preg_match("/^\d+/", $number, $match)) {
                    $invoice_number = $match[0];
                    $file_as        = preg_replace('/^\d+/', sprintf("%012d", $invoice_number), $number);

                } elseif (preg_match("/\d+$/", $number, $match)) {
                    $invoice_number = $match[0];
                    $file_as        = preg_replace('/\d+$/', sprintf("%012d", $invoice_number), $number);

                } else {
                    $file_as = $number;
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


    function get_date($field) {
        return strftime("%e %b %Y", strtotime($this->data[$field].' +0:00'));
    }

    function delete($note = '', $fix_mode = false) {

        $is_refund = ($this->data['Invoice Type'] == 'Refund' ? true : false);


        $order   = get_object('Order', $this->data['Invoice Order Key']);
        $account = get_object('Account', '');

        $order->editor = $this->editor;

        /*
        if ($order->get('Order State') == 'Dispatched' and !$is_refund) {
            $this->error = true;
            $this->msg   = 'invoice cant be deleted';

            return;

        }
*/

        $products             = array();
        $dates                = array();
        $customer_key         = $this->get('Invoice Customer Key');
        $store_key            = $this->get('Invoice Store Key');
        $invoice_category_key = $this->get('Invoice Category Key');
        $invoice_date         = gmdate('Y-m-d', strtotime($this->get('Invoice Date').' +0:00'));


        $items_data = array();


        $sql = "SELECT `Invoice Date`,
O.`Order Transaction Fact Key`,`Order Currency Code`,`Product History Price`,`Product History Code`,`Order Transaction Amount`,`Delivery Note Quantity`,`Product History Name`,`Product History Price`,`Order Transaction Total Discount Amount`,`Order Transaction Gross Amount`
`Product Units Per Case`,`Product Name`,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,P.`Product ID`,O.`Product Code`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`


FROM `Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`) WHERE `Invoice Key`=?";


        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(
            array(
                $this->id
            )
        )) {
            while ($row = $stmt->fetch()) {
                $products[$row['Product ID']] = 1;
                $dates[$row['Invoice Date']]  = 1;


                $discount = ($row['Order Transaction Total Discount Amount'] == 0
                    ? ''
                    : percentage(
                        $row['Order Transaction Total Discount Amount'], $row['Order Transaction Gross Amount'], 0
                    ));

                $units    = $row['Product Units Per Case'];
                $name     = $row['Product History Name'];
                $price    = $row['Product History Price'];
                $currency = $row['Order Currency Code'];

                $desc = '';
                if ($units > 1) {
                    $desc = number($units).'x ';
                }
                $desc .= ' '.$name;
                if ($price > 0) {
                    $desc .= ' ('.money($price, $currency).')';
                }

                $description = $desc;

                if ($discount != '') {
                    $description .= ' '._('Discount').':'.$discount;
                }

                if ($row['Product RRP'] != 0) {
                    $description .= ' <br>'._('RRP').': '.money($row['Product RRP'], $row['Order Currency Code']);
                }

                if ($row['Product Tariff Code'] != '') {
                    $description .= '<br>'._('Tariff Code').': '.$row['Product Tariff Code'];
                }


                if ($this->get('Invoice Type') == 'Invoice') {
                    $quantity = number($row['Delivery Note Quantity']);
                    $factor   = 1;
                } else {
                    $quantity = '<span class="italic discreet"><span >~</span>'.number(-1 * $row['Order Transaction Amount'] / $row['Product History Price']).'</span>';
                    $factor   = -1;
                }


                $items_data[] = array(
                    'product_pid' => $row['Product ID'],
                    'code'        => $row['Product Code'],
                    'description' => $description,
                    'quantity'    => $quantity,
                    'net'         => money($factor * $row['Order Transaction Amount'], $row['Order Currency Code'])
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit();
        }


        $sql = sprintf("DELETE FROM `Invoice Tax Bridge` WHERE `Invoice Key`=%d", $this->id);
        $this->db->exec($sql);


        $sql = sprintf("DELETE FROM `Invoice Sales Representative Bridge`  WHERE   `Invoice Key`=%d", $this->id);
        $this->db->exec($sql);


        $sql = sprintf("DELETE FROM `Invoice Tax Dimension` WHERE `Invoice Key`=%d", $this->id);
        $this->db->exec($sql);

        $sql = sprintf("DELETE FROM `Invoice Delivery Note Bridge` WHERE `Invoice Key`=%d   ", $this->id);
        $this->db->exec($sql);

        $sql = sprintf("DELETE FROM `History Dimension`  WHERE   `Direct Object`='Invoice' AND `Direct Object Key`=%d", $this->id);
        $this->db->exec($sql);


        $payments = array();
        $sql      = sprintf(
            "SELECT * FROM `Invoice Payment Bridge` WHERE `Invoice Key`=%d", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $payments[] = get_object('Payment', $row['Payment Key']);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if (!$is_refund) {

            $sql = sprintf(
                "DELETE FROM `Invoice Payment Bridge`  WHERE    `Invoice Key`=%d", $this->id
            );
            $this->db->exec($sql);


            $sql = sprintf(
                "UPDATE `Payment Dimension`  SET `Payment Invoice Key`=NULL   WHERE `Payment Invoice Key`=%d", $this->id
            );
            $this->db->exec($sql);
        }

        //foreach ($payments as $payment) {
        //    $payment->update_balance();
        //}

        $_category_keys = array();
        $sql            = sprintf("SELECT `Category Key` FROM `Category Bridge`  WHERE   `Subject`='Invoice' AND `Subject Key`=%d", $this->id);
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $_category_keys[$row['Category Key']] = $row['Category Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf("DELETE FROM `Category Bridge`  WHERE   `Subject`='Invoice' AND `Subject Key`=%d", $this->id);
        $this->db->exec($sql);


        foreach ($_category_keys as $_category_key) {
            $_category = new Category($_category_key);
            $_category->update_children_data();
            $_category->update_subjects_data();
        }


        if (!$is_refund) {

            $this->data ['Order Invoiced Balance Total Amount']             = 0;
            $this->data ['Order Invoiced Balance Net Amount']               = 0;
            $this->data ['Order Invoiced Balance Tax Amount']               = 0;
            $this->data ['Order Invoiced Outstanding Balance Total Amount'] = 0;
            $this->data ['Order Invoiced Outstanding Balance Net Amount']   = 0;
            $this->data ['Order Invoiced Outstanding Balance Tax Amount']   = 0;


            $sql = sprintf(
                "DELETE FROM `Order Transaction Fact`  WHERE    `Invoice Key`=%d  AND (`Order Key`=0 OR `Order Key` IS NULL) ", $this->id
            );
            $this->db->exec($sql);

            $sql = sprintf(
                "UPDATE `Order Transaction Fact` SET `Invoice Date`=NULL , `Invoice Key`=NULL ,`Consolidated`='No'  WHERE  `Invoice Key`=%d", $this->id
            );
            $this->db->exec($sql);


            $sql = sprintf(
                "DELETE FROM `Order No Product Transaction Fact`  WHERE    `Invoice Key`=%d  AND (`Order Key`=0 OR `Order Key` IS NULL) ", $this->id
            );
            $this->db->exec($sql);

            $sql = sprintf(
                "UPDATE `Order No Product Transaction Fact` SET `Invoice Key`=NULL , `Consolidated`='No'   WHERE  `Invoice Key`=%d", $this->id
            );
            $this->db->exec($sql);


        } else {

            $sql = sprintf(
                "DELETE FROM `Order Transaction Fact`  WHERE    `Invoice Key`=%d   ", $this->id
            );
            $this->db->exec($sql);
            $sql = sprintf(
                "DELETE FROM `Order No Product Transaction Fact`  WHERE    `Invoice Key`=%d  ", $this->id
            );
            $this->db->exec($sql);


        }

        $sql = sprintf(
            "DELETE FROM `Invoice Dimension`  WHERE  `Invoice Key`=%d", $this->id
        );
        $this->db->exec($sql);

        $this->data['items'] = $items_data;


        $sql =
            "INSERT INTO `Invoice Deleted Dimension` (`Invoice Deleted Key`,`Invoice Deleted Store Key`,`Invoice Deleted Order Key`,`Invoice Deleted Public ID`,`Invoice Deleted Metadata`,`Invoice Deleted Date`,`Invoice Deleted Note`,`Invoice Deleted User Key`,`Invoice Deleted Type`,`Invoice Deleted Total Amount`) VALUE (?,?,?,?,?,?,?,?,?,?) ";


        $this->db->prepare($sql)->execute(
            array(
                $this->id,
                $this->data['Invoice Store Key'],
                $this->data['Invoice Order Key'],
                $this->data['Invoice Public ID'],
                json_encode($this->data),
                $this->editor['Date'],
                $note,
                $this->editor['User Key'],
                $this->data['Invoice Type'],
                $this->data['Invoice Total Amount']
            )
        );


        if (!$fix_mode) {



            if (!$is_refund) {


                $dn = get_object('DeliveryNote', $order->get('Order Delivery Note Key'));


                $dn->fast_update(
                    array(
                        'Delivery Note Invoiced'                    => 'No',
                        'Delivery Note Invoiced Net DC Amount'      => 0,
                        'Delivery Note Invoiced Shipping DC Amount' => 0

                    )
                );

                $order->update_state('Invoice Deleted', $options = '', $metadata = array('note' => $note));
            } else {

                $order->update_totals();
            }
        }

        $history_data = array(
            'History Abstract' => sprintf(_('Invoice %s deleted'), $this->get('Public ID')),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


        include_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_asset_sales', array(
            'type'                 => 'update_deleted_invoice_products_sales_data',
            'products'             => $products,
            'dates'                => $dates,
            'customer_key'         => $customer_key,
            'store_key'            => $store_key,
            'invoice_category_key' => $invoice_category_key,
            'invoice_date'         => $invoice_date
        ), $account->get('Account Code')
        );


        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'         => 'invoice_deleted',
            'invoice_key'  => $this->id,
            'customer_key' => $customer_key,
            'store_key'    => $store_key,

        ), $account->get('Account Code')
        );


        return sprintf('/orders/%d/%d', $order->get('Store Key'), $order->id);

    }

    function get_payments($scope = 'keys', $filter = '') {


        if ($filter == 'Completed') {
            $where = ' and `Payment Transaction Status`="Completed" ';
        } else {
            $where = '';
        }


        $payments = array();
        $sql      = sprintf(
            "SELECT B.`Payment Key` FROM `Order Payment Bridge` B LEFT JOIN `Payment Dimension` P  ON (P.`Payment Key`=B.`Payment Key`)  WHERE `Invoice Key`=%d %s ", $this->id, $where
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Payment Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {
                    $_object = get_object('Payment', $row['Payment Key']);
                    $_object->load_payment_account();
                    $payments[$row['Payment Key']] = $_object;

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

    function add_payment($payment) {

        $payment->update(array('Payment Invoice Key' => $this->id), 'no_history');

        $sql = sprintf('UPDATE `Order Payment Bridge` SET `Invoice Key`=%d WHERE `Payment Key`=%d ', $this->id, $payment->id);

        $this->db->exec($sql);
        $this->update_payments_totals();


    }

    function update_tax_data(){



        $data = array();

        $sql = sprintf(
            "SELECT  `Transaction Tax Code`,sum(`Order Transaction Amount`) AS net   FROM `Order Transaction Fact` WHERE `Invoice Key`=%d  GROUP BY  `Transaction Tax Code`  ", $this->id
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
            "SELECT  `Tax Category Code`, sum(`Transaction Net Amount`) AS net  FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d  GROUP BY  `Tax Category Code`  ", $this->id
        );


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




        if($this->data['Invoice Net Amount Off']!=0 ){



            if (isset($data[$this->data['Invoice Tax Code']] )) {
                $data[$this->data['Invoice Tax Code']] -= $this->data['Invoice Net Amount Off'];
            } else {
                $data[$this->data['Invoice Tax Code']] = -$this->data['Invoice Net Amount Off'];
            }


        }

        $sql = sprintf("DELETE FROM `Invoice Tax Bridge` WHERE `Invoice Key`=%d", $this->id);
        $this->db->exec($sql);

        $sql = sprintf("DELETE FROM `Invoice Tax Dimension` WHERE `Invoice Key`=%d", $this->id);
        $this->db->exec($sql);

        $sql = sprintf(
            "    INSERT INTO `Invoice Tax Dimension` (`Invoice Key`) VALUES (%d)", $this->id
        );

        //print "$sql\n";

        $this->db->exec($sql);




        foreach ($data as $tax_code => $amount) {

            $tax_category = get_object('Tax_Category', $tax_code);
            $tax          = round($tax_category->get('Tax Category Rate') * $amount, 2);

            $is_base = 'Yes';

            $sql = sprintf(
                "    UPDATE `Invoice Tax Dimension` SET `%s`=%.2f WHERE `Invoice Key`=%d", addslashes($tax_code), $tax, $this->id
            );
            $this->db->exec($sql);
            // print "$sql\n";
            $sql = sprintf(
                "INSERT INTO `Invoice Tax Bridge` (`Invoice Key`,`Tax Code`,`Tax Amount`,`Tax Base`) VALUES   (%d,%s,%.2f,%s) 
                ON DUPLICATE KEY UPDATE `Tax Amount`=%.2f, `Tax Base`=%s", $this->id, prepare_mysql($tax_code), $tax, prepare_mysql($is_base), $tax,
                prepare_mysql($is_base)

            );
            $this->db->exec($sql);
            //print "$sql\n";


        }






    }


}



