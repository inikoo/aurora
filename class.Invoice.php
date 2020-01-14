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

    /**
     * @var \PDO
     */
    public $db;

    /**
     * @var array
     */
    public $metadata;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        $this->table_name      = 'Invoice';
        $this->ignore_fields   = array('Invoice Key');
        $this->update_customer = true;
        $this->deleted         = false;
        $this->metadata        = array();

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
            $this->create_refund($arg2, $arg3);

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


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Invoice Key'];

            $this->metadata = json_decode($this->data['Invoice Metadata'], true);

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

        if (isset($invoice_data['Recargo Equivalencia'])) {
            if ($invoice_data['Recargo Equivalencia'] == 'Yes') {
                $recargo_equivalencia = $invoice_data['Recargo Equivalencia'];
            }
            unset($invoice_data['Recargo Equivalencia']);
        }


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


            if (isset($recargo_equivalencia)) {
                $this->fast_update_json_field('Invoice Metadata', 'RE', 'Yes');
            }

            $feedback = array();

            foreach ($transactions as $transaction) {


                if ($transaction['type'] == 'otf') {


                    $sql = sprintf(
                        "SELECT * FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Order Transaction Fact Key`=%d AND `Order Transaction Type`='Order'", $this->data['Invoice Order Key'], $transaction['id']
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
                        "SELECT * FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Order Transaction Fact Key`=%d AND `Order Transaction Type`='Order' ", $this->data['Invoice Order Key'], $transaction['id']

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

    function get($key) {

        if (!$this->id) {
            return false;
        }


        switch ($key) {
            case 'Tax Number Formatted':


                switch ($this->data['Invoice Tax Number Validation Source']) {
                    case 'Online':
                        $source = ' <i class="fal fa-globe"></i>';
                        break;
                    case 'Staff':
                        $source = ' <i class="fal fa-thumbtack"></i>';

                        break;
                    default:
                        $source = '';

                }

                if ($this->data['Invoice Tax Number Validation Date'] != '') {
                    $_tmp = gmdate("U") - gmdate(
                            "U", strtotime(
                                   $this->data['Invoice Tax Number Validation Date'].' +0:00'
                               )
                        );
                    if ($_tmp < 3600) {
                        $date = strftime("%e %b %Y %H:%M:%S %Z", strtotime($this->data['Invoice Tax Number Validation Date'].' +0:00'));

                    } elseif ($_tmp < 86400) {
                        $date = strftime(
                            "%e %b %Y %H:%M %Z", strtotime($this->data['Invoice Tax Number Validation Date'].' +0:00')
                        );

                    } else {
                        $date = strftime(
                            "%e %b %Y", strtotime($this->data['Invoice Tax Number Validation Date'].' +0:00')
                        );
                    }
                } else {
                    $date = '';
                }

                $msg = $this->data['Invoice Tax Number Validation Message'];

                $title = htmlspecialchars(trim($date.' '.$msg));

                if ($this->data['Invoice Tax Number'] != '') {
                    if ($this->data['Invoice Tax Number Valid'] == 'Yes') {
                        return sprintf(
                            '<i style="margin-right: 0px" class="fa fa-check success" title="'._('Valid').'"></i> <span title="'.$title.'" >%s</span>', $this->data['Invoice Tax Number'].$source
                        );
                    } elseif ($this->data['Invoice Tax Number Valid'] == 'Unknown') {
                        return sprintf(
                            '<i style="margin-right: 0px" class="fal fa-question-circle discreet" title="'._('Validity is unknown').'"></i> <span class="discreet" title="'.$title.'">%s</span>', $this->data['Invoice Tax Number'].$source
                        );
                    } elseif ($this->data['Invoice Tax Number Valid'] == 'API_Down') {
                        return sprintf(
                            '<i style="margin-right: 0px"  class="fal fa-question-circle discreet" title="'._('Validity is unknown').'"> </i> <span class="discreet" title="'.$title.'">%s</span> %s', $this->data['Invoice Tax Number'],
                            ' <i  title="'._('Online validation service down').'" class="fa fa-wifi-slash error"></i>'
                        );
                    } else {
                        return sprintf(
                            '<i style="margin-right: 0px" class="fa fa-ban error" title="'._('Invalid').'"></i> <span class="discreet" title="'.$title.'">%s</span>', $this->data['Invoice Tax Number'].$source
                        );
                    }
                }

                break;
            case('Tax Number Valid'):
                if ($this->data['Invoice Tax Number'] != '') {

                    if ($this->data['Invoice Tax Number Validation Date'] != '') {
                        $_tmp = gmdate("U") - gmdate(
                                "U", strtotime(
                                       $this->data['Invoice Tax Number Validation Date'].' +0:00'
                                   )
                            );
                        if ($_tmp < 3600) {
                            $date = strftime(
                                "%e %b %Y %H:%M:%S %Z", strtotime(
                                                          $this->data['Invoice Tax Number Validation Date'].' +0:00'
                                                      )
                            );

                        } elseif ($_tmp < 86400) {
                            $date = strftime(
                                "%e %b %Y %H:%M %Z", strtotime(
                                                       $this->data['Invoice Tax Number Validation Date'].' +0:00'
                                                   )
                            );

                        } else {
                            $date = strftime(
                                "%e %b %Y", strtotime(
                                              $this->data['Invoice Tax Number Validation Date'].' +0:00'
                                          )
                            );
                        }
                    } else {
                        $date = '';
                    }

                    $msg = $this->data['Invoice Tax Number Validation Message'];

                    if ($this->data['Invoice Tax Number Validation Source'] == 'Online') {
                        $source = '<i title=\''._('Validated online').'\' class=\'far fa-globe\'></i>';


                    } elseif ($this->data['Invoice Tax Number Validation Source'] == 'Staff') {
                        $source = '<i title=\''._('Set up manually').'\' class=\'far fa-thumbtack\'></i>';
                    } else {
                        $source = '';
                    }

                    $validation_data = trim($date.' '.$source.' '.$msg);
                    if ($validation_data != '') {
                        $validation_data = ' <span class=\'discreet\'>('.$validation_data.')</span>';
                    }

                    switch ($this->data['Invoice Tax Number Valid']) {
                        case 'Unknown':
                        case 'API_Down':
                            return _('Not validated').$validation_data;
                            break;
                        case 'Yes':
                            return _('Validated').$validation_data;
                            break;
                        case 'No':
                            return _('Not valid').$validation_data;
                            break;
                        default:
                            return $this->data['Invoice Tax Number Valid'].$validation_data;

                            break;
                    }
                }
                break;
            case 'Address':

                return $this->get('Invoice Address Formatted');
                break;

            case 'Invoice Address':


                $address_fields = array(

                    'Address Recipient'            => $this->get('Invoice Address Recipient'),
                    'Address Organization'         => $this->get('Invoice Address Organization'),
                    'Address Line 1'               => $this->get('Invoice Address Line 1'),
                    'Address Line 2'               => $this->get('Invoice Address Line 2'),
                    'Address Sorting Code'         => $this->get('Invoice Address Sorting Code'),
                    'Address Postal Code'          => $this->get('Invoice Address Postal Code'),
                    'Address Dependent Locality'   => $this->get('Invoice Address Dependent Locality'),
                    'Address Locality'             => $this->get('Invoice Address Locality'),
                    'Address Administrative Area'  => $this->get('Invoice Address Administrative Area'),
                    'Address Country 2 Alpha Code' => $this->get('Invoice Address Country 2 Alpha Code'),


                );

                return json_encode($address_fields);
                break;
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


                $account = get_object('Account', 1);
                $_key    = preg_replace('/Corporate Currency /', '', $key);

                return money(
                    $this->data['Invoice '.$_key] * $this->data['Invoice Currency Exchange'], $account->get('Account Currency Code')
                );
                break;
            case('Date'):
            case('Tax Liability Date'):
                return strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($this->data['Invoice '.$key].' +0:00')
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

            case 'Recargo Equivalencia':


                if ($this->metadata('RE') == 'Yes') {
                    return _('Yes');
                } else {
                    return _('No');
                }


            case 'Invoice Recargo Equivalencia':
                if ($this->metadata('RE') == 'Yes') {
                    return 'Yes';
                } else {
                    return 'No';
                }
            case 'Icon':
                if ($this->get('Invoice Type') == 'Invoice') {
                    return 'fal fa-file-invoice';
                } else {
                    return 'fal error fa-file-invoice';
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

    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }

    function update_payments_totals() {


        $payments = 0;

        $sql = sprintf(
            "SELECT sum(`Payment Transaction Amount`) AS amount  FROM `Order Payment Bridge` B LEFT JOIN `Payment Dimension` P  ON (B.`Payment Key`=P.`Payment Key`) WHERE `Invoice Key`=%d AND `Payment Transaction Status`='Completed'", $this->id
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

        if (isset($invoice_data['Recargo Equivalencia'])) {
            if ($invoice_data['Recargo Equivalencia'] == 'Yes') {
                $recargo_equivalencia = $invoice_data['Recargo Equivalencia'];
            }
            unset($invoice_data['Recargo Equivalencia']);
        }


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

            if (isset($recargo_equivalencia)) {
                $this->fast_update_json_field('Invoice Metadata', 'RE', 'Yes');
            }


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
            }


            $data = array();

            $sql = sprintf(
                "SELECT  `Transaction Tax Code`,sum(`Order Transaction Amount`) AS net   FROM `Order Transaction Fact` WHERE `Invoice Key`=%d  GROUP BY  `Transaction Tax Code`  ", $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $data[$row['Transaction Tax Code']] = $row['net'];

                }
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
            }


            if ($this->data['Invoice Net Amount Off'] != 0) {


                if (isset($data[$this->data['Invoice Tax Code']])) {
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

            $this->fork_index_elastic_search();


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

            case 'Invoice Date':

                $account = get_object('Account', 1);

                $this->update_field($field, $value, $options);

                $products = array();
                $dates    = array();

                $sql = "SELECT `Invoice Date`,`Product ID` FROM `Order Transaction Fact`  WHERE `Invoice Key`=?";


                $stmt = $this->db->prepare($sql);
                if ($stmt->execute(
                    array(
                        $this->id
                    )
                )) {
                    while ($row = $stmt->fetch()) {
                        $products[$row['Product ID']] = 1;
                        $dates[$row['Invoice Date']]  = 1;


                    }
                }


                include_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_asset_sales', array(
                    'type'                 => 'update_edited_invoice_products_sales_data',
                    'products'             => $products,
                    'dates'                => $dates,
                    'customer_key'         => $this->get('Invoice Customer Key'),
                    'store_key'            => $this->get('Invoice Store Key'),
                    'invoice_category_key' => $this->get('Invoice Category Key'),
                    'invoice_date'         => gmdate('Y-m-d', strtotime($this->get('Invoice Date').' +0:00'))
                ), $account->get('Account Code')
                );

                break;
            case 'Invoice Registration Number':

                $this->update_field($field, $value, $options);
                $order         = get_object('Order', $this->data['Invoice Order Key']);
                $order->editor = $this->editor;
                $order->update(array(preg_replace('/^Invoice /', 'Order ', $field) => $value), $options);


                break;
            case('Invoice Customer Name'):

                $this->update_field($field, $value, $options);
                $order         = get_object('Order', $this->data['Invoice Order Key']);
                $order->editor = $this->editor;
                $order->update(array(preg_replace('/^Invoice /', 'Order ', $field) => $value), $options);
                $this->fork_index_elastic_search();

                break;
            case 'Invoice Recargo Equivalencia':


                if (!($value == 'Yes' or $value == 'No')) {
                    $this->error = true;
                    $this->msg   = 'invalid value';

                    return;
                }

                $this->fast_update_json_field('Invoice Metadata', 'RE', $value);


                $order         = get_object('Order', $this->data['Invoice Order Key']);
                $order->editor = $this->editor;

                $order_old_formatted_value = $order->get('Recargo Equivalencia');
                $order->fast_update_json_field('Order Metadata', 'RE', $value);
                $order->update_tax(false, $this->id);
                $this->update_tax_data();

                if ($order_old_formatted_value != $order->get('Recargo Equivalencia')) {
                    $this->add_changelog_record('Order Recargo Equivalencia', $order_old_formatted_value, $order->get('Recargo Equivalencia'), '', 'Order', $order->id);
                }

                $this->post_operation_invoice_totals_changed();

                break;
            case('Invoice Tax Number'):
                $this->update_tax_number($value);
                $order         = get_object('Order', $this->data['Invoice Order Key']);
                $order->editor = $this->editor;

                $order_old_formatted_value = $order->get('Tax Number Formatted');
                $order->update_tax_number($value, 'no_history', $updated_from_invoice = true);

                $order->fast_update(
                    array(
                        'Order Tax Number Valid'              => $this->data['Invoice Tax Number Valid'],
                        'Order Tax Number Details Match'      => $this->data['Invoice Tax Number Details Match'],
                        'Order Tax Number Validation Date'    => $this->data['Invoice Tax Number Validation Date'],
                        'Order Tax Number Validation Source'  => $this->data['Invoice Tax Number Validation Source'],
                        'Order Tax Number Validation Message' => $this->data['Invoice Tax Number Validation Message'],
                    )
                );

                if ($order_old_formatted_value != $order->get('Tax Number Formatted')) {
                    $this->add_changelog_record('Order Tax Number', $order_old_formatted_value, $order->get('Tax Number Formatted'), '', 'Order', $order->id);

                }


                $order->update_tax(false, $this->id);

                $this->update_tax_data();


                $this->update_metadata = array(
                    'class_html' => array(
                        'Invoice_Tax_Number_Formatted' => $this->get('Tax Number Formatted'),
                    ),

                );

                break;
            case('Invoice Tax Number Valid'):
                $this->update_tax_number_valid($value);
                $order                     = get_object('Order', $this->data['Invoice Order Key']);
                $order->editor             = $this->editor;
                $order_old_formatted_value = $order->get('Tax Number Valid');
                $order->fast_update(
                    array(
                        'Order Tax Number Valid'              => $this->data['Invoice Tax Number Valid'],
                        'Order Tax Number Details Match'      => $this->data['Invoice Tax Number Details Match'],
                        'Order Tax Number Validation Date'    => $this->data['Invoice Tax Number Validation Date'],
                        'Order Tax Number Validation Source'  => $this->data['Invoice Tax Number Validation Source'],
                        'Order Tax Number Validation Message' => $this->data['Invoice Tax Number Validation Message'],
                    )
                );
                if ($order_old_formatted_value != $order->get('Order Tax Number Valid')) {
                    $order->add_changelog_record('Order Tax Number Valid', $order_old_formatted_value, $order->get('Tax Number Valid'), '', 'Order', $order->id);

                }

                $order->update_tax(false, $this->id);

                $this->update_tax_data();


                $this->update_metadata = array(
                    'class_html' => array(
                        'Invoice_Tax_Number_Formatted' => $this->get('Tax Number Formatted'),
                    ),

                );

                break;
            case 'Invoice Address':

                $this->update_address(json_decode($value, true));

                $order         = get_object('Order', $this->data['Invoice Order Key']);
                $order->editor = $this->editor;

                $order->update_tax(false, $this->id);

                $this->update_tax_data();

                break;
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
                $this->fork_index_elastic_search();
                $order = get_object('Order', $this->data['Invoice Order Key']);
                $order->fork_index_elastic_search();

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

    function update_tax_data() {


        $old_invoice_total = $this->get('Invoice Total Amount');

        $data = array();

        $sql = sprintf(
            "SELECT  `Transaction Tax Code`,sum(`Order Transaction Amount`) AS net   FROM `Order Transaction Fact` WHERE `Invoice Key`=%d  GROUP BY  `Transaction Tax Code`  ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data[$row['Transaction Tax Code']] = $row['net'];
            }
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
        }

        if ($this->data['Invoice Net Amount Off'] != 0) {
            if (isset($data[$this->data['Invoice Tax Code']])) {
                $data[$this->data['Invoice Tax Code']] -= $this->data['Invoice Net Amount Off'];
            } else {
                $data[$this->data['Invoice Tax Code']] = -$this->data['Invoice Net Amount Off'];
            }
        }

        $sql = sprintf("DELETE FROM `Invoice Tax Bridge` WHERE `Invoice Key`=%d", $this->id);
        $this->db->exec($sql);

        $sql = sprintf("DELETE FROM `Invoice Tax Dimension` WHERE `Invoice Key`=%d", $this->id);
        $this->db->exec($sql);

        $sql = sprintf("INSERT INTO `Invoice Tax Dimension` (`Invoice Key`) VALUES (%d)", $this->id);
        $this->db->exec($sql);


        $total_tax = 0;


        foreach ($data as $tax_code => $amount) {

            $tax_category = get_object('Tax_Category', $tax_code);
            $tax          = round($tax_category->get('Tax Category Rate') * $amount, 2);
            $total_tax    += $tax;
            $is_base      = 'Yes';

            $sql = sprintf("UPDATE `Invoice Tax Dimension` SET `%s`=%.2f WHERE `Invoice Key`=%d", addslashes($tax_code), $tax, $this->id);
            $this->db->exec($sql);
            // print "$sql\n";
            $sql = sprintf(
                "INSERT INTO `Invoice Tax Bridge` (`Invoice Key`,`Tax Code`,`Tax Amount`,`Tax Base`) VALUES   (%d,%s,%.2f,%s) 
                ON DUPLICATE KEY UPDATE `Tax Amount`=%.2f, `Tax Base`=%s", $this->id, prepare_mysql($tax_code), $tax, prepare_mysql($is_base), $tax, prepare_mysql($is_base)

            );
            $this->db->exec($sql);
            //print "$sql\n";


        }


        $invoice_total = $total_tax + $this->data['Invoice Total Net Amount'];


        $this->fast_update(
            array(
                'Invoice Total Tax Amount' => $total_tax,
                'Invoice Total Amount'     => $invoice_total,

            )

        );

        $this->update_payments_totals();

        if ($old_invoice_total != $this->get('Invoice Total Amount')) {
            $this->fork_index_elastic_search();
        }


    }

    function post_operation_invoice_totals_changed() {

        //todo finish this

        $this->update_metadata = array(
            'class_html' => array(
                'Items_Gross_Amount'    => $this->get('Items Gross Amount'),
                'Items_Discount_Amount' => $this->get('Items Discount Amount'),
                'Items_Net_Amount'      => $this->get('Items Net Amount'),
                'Net_Amount_Off'        => $this->get('Net Amount Off'),


                'Total_Tax_Adjust_Amount'         => $this->get('Total Tax Adjust Amount'),
                'Total_Amount'                    => $this->get('Total Amount'),
                'Corporate_Currency_Total_Amount' => $this->get('Corporate Currency Total Amount'),

            )
        );


    }

    function update_tax_number($value) {

        include_once 'utils/validate_tax_number.php';

        $old_formatted_value = $this->get('Tax Number Formatted');
        $this->update_field('Invoice Tax Number', $value, 'no_history');


        if ($this->updated) {

            if ($value == '') {

                $this->fast_update(
                    array(
                        'Invoice Tax Number Valid'              => 'Unknown',
                        'Invoice Tax Number Details Match'      => '',
                        'Invoice Tax Number Validation Date'    => '',
                        'Invoice Tax Number Validation Source'  => '',
                        'Invoice Tax Number Validation Message' => ''
                    )
                );
            } else {

                $tax_validation_data = validate_tax_number($this->data['Invoice Tax Number'], $this->data['Invoice Address Country 2 Alpha Code']);

                if ($tax_validation_data['Tax Number Valid'] == 'API_Down') {
                    if (!($this->data['Invoice Tax Number Validation Source'] == '' and $this->data['Invoice Tax Number Valid'] == 'No')) {
                        return;
                    }
                }

                $this->fast_update(
                    array(
                        'Invoice Tax Number Valid'              => $tax_validation_data['Tax Number Valid'],
                        'Invoice Tax Number Details Match'      => $tax_validation_data['Tax Number Details Match'],
                        'Invoice Tax Number Validation Date'    => $tax_validation_data['Tax Number Validation Date'],
                        'Invoice Tax Number Validation Source'  => 'Online',
                        'Invoice Tax Number Validation Message' => 'B: '.$tax_validation_data['Tax Number Validation Message'],
                    )
                );
            }


            $this->new_value = $value;

            $this->add_changelog_record('Invoice Tax Number', $old_formatted_value, $this->get('Tax Number Formatted'), '', 'Invoice', $this->id);


        }

        $this->other_fields_updated = array(
            'Invoice_Tax_Number_Valid' => array(
                'field'           => 'Invoice_Tax_Number_Valid',
                'render'          => ($this->get('Invoice Tax Number') == '' ? false : true),
                'value'           => $this->get('Invoice Tax Number Valid'),
                'formatted_value' => $this->get('Tax Number Valid'),


            )
        );


    }

    function update_tax_number_valid($value) {

        include_once 'utils/validate_tax_number.php';


        if ($value == 'Auto') {


            $tax_validation_data = validate_tax_number($this->data['Invoice Tax Number'], $this->data['Invoice Address Country 2 Alpha Code']);

            if ($tax_validation_data['Tax Number Valid'] == 'API_Down') {
                if (!($this->data['Invoice Tax Number Validation Source'] == '' and $this->data['Invoice Tax Number Valid'] == 'No')) {
                    $this->error = true;
                    $this->msg   = '<span class="error"><i class="fa fa-exclamation-circle"></i> '.$tax_validation_data['Tax Number Validation Message'].'</span>';

                    return;
                }
            }

            $this->fast_update(
                array(
                    //'Invoice Tax Number Valid' => $tax_validation_data['Tax Number Valid'],

                    'Invoice Tax Number Details Match'      => $tax_validation_data['Tax Number Details Match'],
                    'Invoice Tax Number Validation Date'    => ($tax_validation_data['Tax Number Validation Date'] == '' ? gmdate('Y-m-d H:i:s') : $tax_validation_data['Tax Number Validation Date']),
                    'Invoice Tax Number Validation Source'  => 'Online',
                    'Invoice Tax Number Validation Message' => $tax_validation_data['Tax Number Validation Message'],
                )
            );
            $this->update_field('Invoice Tax Number Valid', $tax_validation_data['Tax Number Valid']);

        } else {


            $this->fast_update(
                array(
                    'Invoice Tax Number Details Match'      => 'Unknown',
                    'Invoice Tax Number Validation Date'    => $this->editor['Date'],
                    'Invoice Tax Number Validation Source'  => 'Staff',
                    'Invoice Tax Number Validation Message' => $this->editor['Author Name'],
                )
            );
            $this->update_field('Invoice Tax Number Valid', $value);
        }


        // print_r($this->data);

        $this->other_fields_updated = array(
            'Invoice_Tax_Number' => array(
                'field'           => 'Invoice_Tax_Number',
                'render'          => true,
                'value'           => $this->get('Invoice Tax Number'),
                'formatted_value' => $this->get('Tax Number'),


            )
        );


    }

    function update_address($fields, $options = '') {


        $old_value = $this->get("Address");


        $updated_fields_number = 0;


        if (preg_match('/gb|im|jy|gg/i', $fields['Address Country 2 Alpha Code'])) {
            include_once 'utils/geography_functions.php';
            $fields['Address Postal Code'] = gbr_pretty_format_post_code($fields['Address Postal Code']);
        }

        foreach ($fields as $field => $value) {

            $this->update_field(
                'Invoice '.$field, $value, 'no_history'
            );
            if ($this->updated) {
                $updated_fields_number++;

            }
        }


        if ($updated_fields_number > 0) {


            $this->updated = true;
        }


        if ($this->updated) {


            $order         = get_object('Order', $this->data['Invoice Order Key']);
            $order->editor = $this->editor;

            $order->update_address('Invoice', $fields, '', $updated_from_invoice = true);


            $this->update_address_formatted_fields();


            if (!preg_match('/no([ _])history|nohistory/i', $options)) {


                $this->add_changelog_record(
                    "Address", $old_value, $this->get("Address"), '', $this->table_name, $this->id
                );

            }


        }

    }

    function update_address_formatted_fields() {

        include_once 'utils/get_addressing.php';

        $address_fields = array(
            'Address Recipient'            => $this->get('Invoice Address Recipient'),
            'Address Organization'         => $this->get('Invoice Address Organization'),
            'Address Line 1'               => $this->get('Invoice Address Line 1'),
            'Address Line 2'               => $this->get('Invoice Address Line 2'),
            'Address Sorting Code'         => $this->get('Invoice Address Sorting Code'),
            'Address Postal Code'          => $this->get('Invoice Address Postal Code'),
            'Address Dependent Locality'   => $this->get('Invoice Address Dependent Locality'),
            'Address Locality'             => $this->get('Invoice Address Locality'),
            'Address Administrative Area'  => $this->get('Invoice Address Administrative Area'),
            'Address Country 2 Alpha Code' => $this->get('Invoice Address Country 2 Alpha Code'),
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
            'Invoice Address Checksum', $new_checksum, 'no_history'
        );

        $account = get_object('Account', 1);
        $locale  = $account->get('Account Locale');

        if ($this->get('Store Key')) {
            $store   = get_object('Store', $this->get('Store Key'));
            $country = $store->get('Store Home Country Code 2 Alpha');
        } else {
            $country = $account->get('Account Country 2 Alpha Code');
        }


        list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);


        $address = $address->withFamilyName($this->get('Address Recipient'))->withOrganization($this->get('Address Organization'))->withAddressLine1($this->get('Address Line 1'))->withAddressLine2($this->get('Address Line 2'))->withSortingCode(
            $this->get('Address Sorting Code')
        )->withPostalCode($this->get('Address Postal Code'))->withDependentLocality(
            $this->get('Address Dependent Locality')
        )->withLocality($this->get('Address Locality'))->withAdministrativeArea(
            $this->get('Address Administrative Area')
        )->withCountryCode(
            $this->get('Address Country 2 Alpha Code')
        );


        $xhtml_address = $formatter->format($address);
        $xhtml_address = preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
        $xhtml_address = preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
        $xhtml_address = preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
        $xhtml_address = preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);
        $xhtml_address = preg_replace('/<br>/', '<br/>', $xhtml_address);


        $this->update_field('Invoice Address Formatted', $xhtml_address, 'no_history');
        $this->update_field('Invoice Address Postal Label', $postal_label_formatter->format($address), 'no_history');

    }

    function get_date($field) {
        return strftime("%e %b %Y", strtotime($this->data[$field].' +0:00'));
    }

    function delete($note = '', $fix_mode = false) {

        $is_refund = ($this->data['Invoice Type'] == 'Refund' ? true : false);


        $order   = get_object('Order', $this->data['Invoice Order Key']);
        $account = get_object('Account', '');

        $order->editor = $this->editor;


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
                "DELETE FROM `Order Transaction Fact`  WHERE   `Invoice Key`=%d   ", $this->id
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
        $this->delete_index_elastic_search(get_ES_hosts());

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

        $this->fork_index_elastic_search();

        if (!$fix_mode) {


            if (!$is_refund) {


                $dn = get_object('DeliveryNote', $order->get('Order Delivery Note Key'));

                if ($dn->id) {
                    $dn->fast_update(
                        array(
                            'Delivery Note Invoiced'                    => 'No',
                            'Delivery Note Invoiced Net DC Amount'      => 0,
                            'Delivery Note Invoiced Shipping DC Amount' => 0

                        )
                    );

                }
                if ($order->id) {
                    $order->update_state('Invoice Deleted', $options = '', $metadata = array('note' => $note));
                }
            } else {

                if ($order->id) {
                    $order->update_totals();
                    $order->fork_index_elastic_search();
                }
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
            'type'                 => 'update_edited_invoice_products_sales_data',
            'products'             => $products,
            'dates'                => $dates,
            'customer_key'         => $customer_key,
            'store_key'            => $store_key,
            'invoice_category_key' => $invoice_category_key,
            'invoice_date'         => $invoice_date
        ), $account->get('Account Code')
        );


        // here we update elastic customer.assets index
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'         => 'invoice_deleted',
            'invoice_key'  => $this->id,
            'customer_key' => $customer_key,
            'store_key'    => $store_key,

        ), $account->get('Account Code')
        );

        if ($order->id) {
            return sprintf('/orders/%d/%d', $store_key, $order->id);

        } else {
            return sprintf('/orders/%d', $store_key);

        }

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

    function get_field_label($field) {


        switch ($field) {


            case 'Invoice Registration Number':
                $label = _('registration number');
                break;
            case 'Invoice Tax Number':
                $label = _('tax number');
                break;
            case 'Invoice Tax Number Valid':
                $label = _('tax number validity');
                break;
            case 'Invoice Customer Name':
                $label = _('customer name');
                break;
            case 'Invoice Public ID':
                $label = _('invoice number');
                break;

            default:
                $label = $field;

        }

        return $label;

    }

}



