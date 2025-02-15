<?php
/*
 File: Invoice.php

 This file contains the Invoice Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

use Aurora\Models\Utils\TaxCategory;

include_once 'class.DB_Table.php';
include_once 'class.Order.php';
include_once 'class.Category.php';
include_once 'class.DeliveryNote.php';
include_once 'trait.Address.php';
include_once 'trait.InvoiceAiku.php';

class Invoice extends DB_Table
{

    use Address;
    use InvoiceAiku;

    public array $metadata;

    /**
     * @throws ErrorException
     */
    function __construct($arg1 = false, $arg2 = false, $arg3 = false)
    {
        $this->table_name    = 'Invoice';
        $this->ignore_fields = array('Invoice Key');

        $this->deleted  = false;
        $this->metadata = array();

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

        $this->get_data($arg1, $arg2);
    }


    function get_data($tipo, $tag)
    {
        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Invoice Dimension` WHERE  `Invoice Key`=%d",
                $tag
            );
        } elseif ($tipo == 'public_id') {
            $sql = sprintf(
                "SELECT * FROM `Invoice Dimension` WHERE  `Invoice Public ID`=%s",
                prepare_mysql($tag)
            );
        } elseif ($tipo == 'deleted') {
            $this->get_deleted_data($tag);

            return;
        } else {
            return;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id       = $this->data['Invoice Key'];
            $this->metadata = json_decode($this->data['Invoice Metadata'], true);
        }
    }

    function get_deleted_data($tag)
    {
        $this->deleted = true;
        $sql           = sprintf(
            "SELECT * FROM `Invoice Deleted Dimension` WHERE `Invoice Deleted Key`=%d",
            $tag
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

    /**
     * @throws ErrorException
     */
    function create_refund($invoice_data, $transactions)
    {
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


        $exchange_data = $this->get_exchange_data($base_data['Invoice Currency'], $account->get('Account Currency'), $account->get('Account Country Code'), $base_data['Invoice Date']);

        $base_data['Invoice Currency Exchange'] = $exchange_data['exchange'];

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

            $this->post_create_set_invoice_data();


            if (isset($recargo_equivalencia)) {
                $this->fast_update_json_field('Invoice Metadata', 'RE', 'Yes');
            }
            $this->fast_update_json_field('Invoice Metadata', 'fx', json_encode($exchange_data['metadata']));

            $feedback = array();

            foreach ($transactions as $transaction) {
                if ($transaction['type'] == 'otf') {
                    $sql = "SELECT `Order Transaction Amount`,`Product Key`,`Product ID`,`Store Key`,`Customer Key`,`Order Transaction Tax Category Key`,`Transaction Tax Rate`,`Transaction Tax Code`,`Order Currency Code` FROM `Order Transaction Fact` WHERE `Order Key`=? AND `Order Transaction Fact Key`=? AND `Order Transaction Type`='Order'";

                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(array(
                                       $this->data['Invoice Order Key'],
                                       $transaction['id']
                                   ));
                    while ($row = $stmt->fetch()) {
                        if ($transaction['amount'] > 0 and $transaction['amount'] <= ($row['Order Transaction Amount'])) {
                            $amount = round(-1.0 * $transaction['amount'], 2);

                            $sql = "INSERT INTO  `Order Transaction Fact` (`Order Date`,`Order Last Updated Date`,`Invoice Date`,`Order Transaction Type`,`Order Key`,`Invoice Key`,

                                `Product Key`,`Product ID`,`Store Key`,`Customer Key`,
                                `Order Transaction Gross Amount`,`Order Transaction Amount`,`Order Transaction Tax Category Key`,`Transaction Tax Rate`,`Transaction Tax Code`,`Order Currency Code`,`Invoice Currency Exchange Rate`
                               
                                ) VALUES (?,?,?,?, ?,?,?,?,?, ?,?,?,?, ?,?,?,? ) ";

                            $this->db->prepare($sql)->execute(array(
                                                                  $date,
                                                                  $date,
                                                                  $date,
                                                                  'Refund',

                                                                  $this->data['Invoice Order Key'],
                                                                  $this->id,
                                                                  $row['Product Key'],
                                                                  $row['Product ID'],

                                                                  $row['Store Key'],
                                                                  $row['Customer Key'],
                                                                  $amount,
                                                                  $amount,
                                                                  $row['Order Transaction Tax Category Key'],
                                                                  $row['Transaction Tax Rate'],
                                                                  $row['Transaction Tax Code'],
                                                                  $row['Order Currency Code'],
                                                                  $base_data['Invoice Currency Exchange']
                                                              ));


                            $this->db->exec($sql);
                            $refund_otf       = $this->db->lastInsertId();
                            $_feedback        = $transaction['feedback'];
                            $_feedback['otf'] = $refund_otf;
                            $feedback[]       = $_feedback;
                        }
                    }
                }
                if ($transaction['type'] == 'otf_tax') {
                    $sql = "SELECT `Product Key`,`Product ID`,`Store Key`,`Customer Key`,`Transaction Tax Rate`,`Order Transaction Tax Category Key`,`Transaction Tax Code`,`Order Currency Code` FROM `Order Transaction Fact` WHERE `Order Key`=? AND `Order Transaction Fact Key`=? AND `Order Transaction Type`='Order' ";


                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(array(
                                       $this->data['Invoice Order Key'],
                                       $transaction['id']
                                   ));
                    while ($row = $stmt->fetch()) {
                        if ($transaction['amount'] > 0) {
                            $tax_amount = -1.0 * $transaction['amount'];
                            $amount     = 0;

                            $sql = "INSERT INTO  `Order Transaction Fact` (`Order Date`,`Order Last Updated Date`,`Invoice Date`,`Order Transaction Type`,`Order Key`,`Invoice Key`,

                                `Product Key`,`Product ID`,`Store Key`,`Customer Key`,
                                `Order Transaction Gross Amount`,`Order Transaction Amount`,`Order Transaction Tax Category Key`,`Transaction Tax Rate`,`Transaction Tax Code`,`Order Currency Code`,`Invoice Currency Exchange Rate`,
                                `Order Transaction Metadata`
                                ) VALUES (?,?,?,?, ?,?,?,?,?, ?,?,?,?, ?,?,?,?, ?)";


                            $this->db->prepare($sql)->execute(array(
                                                                  $date,
                                                                  $date,
                                                                  $date,
                                                                  'Refund',

                                                                  $this->data['Invoice Order Key'],
                                                                  $this->id,
                                                                  $row['Product Key'],
                                                                  $row['Product ID'],

                                                                  $row['Store Key'],
                                                                  $row['Customer Key'],
                                                                  $amount,
                                                                  $amount,
                                                                  $row['Order Transaction Tax Category Key'],
                                                                  $row['Transaction Tax Rate'],
                                                                  $row['Transaction Tax Code'],
                                                                  $row['Order Currency Code'],
                                                                  $base_data['Invoice Currency Exchange'],


                                                                  json_encode(array('TORA' => $tax_amount))
                                                              ));
                        }
                    }
                } elseif ($transaction['type'] == 'onptf') {
                    $sql = "SELECT `Transaction Net Amount`,`Transaction Refund Net Amount`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,`Order No Product Transaction Tax Category Key`,`Tax Category Code`,`Currency Code`,`Currency Exchange` FROM `Order No Product Transaction Fact` WHERE `Order Key`=? AND `Order No Product Transaction Fact Key`=? AND `Type`='Order'";

                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(array(
                                       $this->data['Invoice Order Key'],
                                       $transaction['id']
                                   ));
                    while ($row = $stmt->fetch()) {
                        if ($transaction['amount'] > 0 and $transaction['amount'] <= ($row['Transaction Net Amount'] - $row['Transaction Refund Net Amount'])) {
                            $amount = round(-1.0 * $transaction['amount'], 2);

                            $sql = "INSERT INTO  `Order No Product Transaction Fact` (`Order Date`,`Invoice Date`,`Type`,`Order Key`,`Invoice Key`,`Transaction Type`,`Transaction Type Key`,
                                `Transaction Description`,
                                `Transaction Gross Amount`,`Transaction Net Amount`,`Order No Product Transaction Tax Category Key`,`Tax Category Code`,
                                `Currency Code`,`Currency Exchange`
                            
                                ) VALUES (?,?,?,?,?, ?,?,?,?, ?,?,?,?, ?)";


                            $this->db->prepare($sql)->execute(array(
                                                                  $date,
                                                                  $date,
                                                                  'Refund',
                                                                  $this->data['Invoice Order Key'],

                                                                  $this->id,
                                                                  $row['Transaction Type'],
                                                                  $row['Transaction Type Key'],
                                                                  $row['Transaction Description'],

                                                                  $amount,
                                                                  $amount,
                                                                  $row['Order No Product Transaction Tax Category Key'],
                                                                  $row['Tax Category Code'],
                                                                  $row['Currency Code'],

                                                                  $row['Currency Exchange']
                                                              ));


                            $refund_onptf       = $this->db->lastInsertId();
                            $_feedback          = $transaction['feedback'];
                            $_feedback['onptf'] = $refund_onptf;
                            $feedback[]         = $_feedback;
                            //    print "$sql\n";
                        }
                    }
                } elseif ($transaction['type'] == 'onptf_tax') {
                    $sql = "SELECT `Transaction Type`,`Transaction Type Key`,`Transaction Description`,`Order No Product Transaction Tax Category Key`,`Tax Category Code`,`Currency Code`,`Currency Exchange`  FROM `Order No Product Transaction Fact` WHERE `Order Key`=? AND `Order No Product Transaction Fact Key`=? AND `Type`='Order'";


                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(array(
                                       $this->data['Invoice Order Key'],
                                       $transaction['id']
                                   ));
                    while ($row = $stmt->fetch()) {
                        if ($transaction['amount'] > 0) {
                            $tax_amount = round(-1.0 * $transaction['amount'], 2);
                            $amount     = 0;
                            $sql        = "INSERT INTO  `Order No Product Transaction Fact` (`Order Date`,`Invoice Date`,`Type`,`Order Key`,`Invoice Key`,`Transaction Type`,`Transaction Type Key`,
                                `Transaction Description`,
                                `Transaction Gross Amount`,`Transaction Net Amount`,`Order No Product Transaction Tax Category Key`,`Tax Category Code`,
                                `Currency Code`,`Currency Exchange`,`Order No Product Transaction Metadata`
                            
                                ) VALUES (?,?,?,?,?, ?,?,?,?, ?,?,?,?, ?,?)";

                            $this->db->prepare($sql)->execute(array(
                                                                  $date,
                                                                  $date,
                                                                  'Refund',
                                                                  $this->data['Invoice Order Key'],

                                                                  $this->id,
                                                                  $row['Transaction Type'],
                                                                  $row['Transaction Type Key'],
                                                                  $row['Transaction Description'],

                                                                  $amount,
                                                                  $amount,
                                                                  $row['Order No Product Transaction Tax Category Key'],
                                                                  $row['Tax Category Code'],
                                                                  $row['Currency Code'],

                                                                  $row['Currency Exchange'],
                                                                  json_encode(array('TORA' => $tax_amount))
                                                              ));
                        }
                    }
                }
            }


            $shipping_net  = 0;
            $charges_net   = 0;
            $insurance_net = 0;
            $other_net     = 0;
            $item_net      = 0;
            $tax_total     = 0;


            $sql = sprintf(
                "SELECT   sum(`Transaction Net Amount`) AS net ,`Transaction Type` FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d  GROUP BY  `Transaction Type`  ",
                $this->id
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
            }

            $sql = sprintf(
                "SELECT  `Transaction Tax Code`,sum(`Order Transaction Amount`) AS net FROM `Order Transaction Fact` WHERE `Invoice Key`=%d  GROUP BY  `Transaction Tax Code`  ",
                $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $item_net += $row['net'];
                }
            }


            if ($this->get('Invoice Tax Type') == 'Tax_Only') {
                $tax_total_data = [];


                $sql  = "SELECT  `Order No Product Transaction Tax Category Key`,`Order No Product Transaction Metadata`  FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=?  ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                                   $this->id
                               ));
                while ($row = $stmt->fetch()) {
                    if ($row['Order No Product Transaction Metadata'] != '') {
                        $_data = json_decode($row['Order No Product Transaction Metadata'], true);


                        if (isset($_data['TORA'])) {
                            if (isset($tax_total_data[$row['Order No Product Transaction Tax Category Key']])) {
                                $tax_total_data[$row['Order No Product Transaction Tax Category Key']] += $_data['TORA'];
                            } else {
                                $tax_total_data[$row['Order No Product Transaction Tax Category Key']] = $_data['TORA'];
                            }
                        }
                    }
                }


                $sql  = "SELECT  `Order Transaction Tax Category Key`,`Order Transaction Metadata`  FROM `Order Transaction Fact` WHERE `Invoice Key`=?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                                   $this->id
                               ));
                while ($row = $stmt->fetch()) {
                    if ($row['Order Transaction Metadata'] != '') {
                        $_data = json_decode($row['Order Transaction Metadata'], true);


                        if (isset($_data['TORA'])) {
                            if (isset($tax_total_data[$row['Order Transaction Tax Category Key']])) {
                                $tax_total_data[$row['Order Transaction Tax Category Key']] += $_data['TORA'];
                            } else {
                                $tax_total_data[$row['Order Transaction Tax Category Key']] = $_data['TORA'];
                            }
                        }
                    }
                }

                foreach ($tax_total_data as $tax_category_key => $tax) {
                    $tax_total    += $tax;
                    $tax_category = new TaxCategory($this->db);
                    $tax_category->loadWithKey($tax_category_key);
                    $this->save_tax_bridge($tax_category, $tax, 0);
                }
            } else {
                $transactions_tax_data = $this->group_transactions_per_tax_category_key(false);

                foreach ($transactions_tax_data as $tax_category_key => $amount) {
                    $tax_category = new TaxCategory($this->db);
                    $tax_category->loadWithKey($tax_category_key);

                    $tax       = round($tax_category->get('Tax Category Rate') * $amount, 2);
                    $tax_total += $tax;


                    $this->save_tax_bridge($tax_category, $tax, $amount);
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


            /**
             * @var $customer Customer
             */
            $customer = get_object('Customer', $this->get('Invoice Customer Key'));
            $customer->update_invoices();


            $this->update_profit();


            $this->update_billing_region();

            $history_data = array(
                'History Abstract' => sprintf(_('Refund %s created'), $this->get('Public ID')),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data,
                true,
                'No',
                'Changes',
                $this->get_object_name(),
                $this->id
            );

            $this->categorize();

            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'         => 'invoice_created',
                    'invoice_key'  => $this->id,
                    'customer_key' => $this->get('Invoice Customer Key'),
                    'store_key'    => $this->get('Invoice Store Key')
                ),
                $account->get('Account Code')
            );


            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'       => 'feedback',
                    'feedback'   => $feedback,
                    'user_key'   => $this->editor['User Key'],
                    'parent'     => 'Refund',
                    'parent_key' => $this->id,
                    'store_key'  => $this->get('Store Key'),
                    'editor'     => $this->editor
                ),
                $account->get('Account Code'),
                $this->db
            );


            return $this;
        }

        return false;
    }

    /**
     * @throws ErrorException
     */
    private function get_exchange_data($currency, $account_currency, $account_country, $date): array
    {
        if ($currency != $account_currency) {
            if (in_array($account_country, [
                'SVK',
                'ESP'
            ])) {
                $sql  = "select `ECB Currency Exchange Rate`,`ECB Currency Exchange Date` from  kbase.`ECB Currency Exchange Dimension` where `ECB Currency Exchange Currency Pair`=? and `ECB Currency Exchange Date`<? and `ECB Currency Exchange Date`>?  order by `ECB Currency Exchange Date` desc limit 1 ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                                   $currency.$account_currency,
                                   gmdate('Y-m-d', strtotime($date.' +0:00')),
                                   gmdate('Y-m-d', strtotime($date.' -5 days')),

                               ));


                if ($row = $stmt->fetch()) {
                    $exchange          = $row['ECB Currency Exchange Rate'];
                    $exchange_metadata = [
                        'type' => 'ECB',
                        'date' => $row['ECB Currency Exchange Date']
                    ];
                } else {
                    $exchange          = currency_conversion($this->db, $currency, $account_currency);
                    $exchange_metadata = ['type' => 'au'];
                }
            } else {
                $exchange          = currency_conversion($this->db, $currency, $account_currency);
                $exchange_metadata = ['type' => 'au'];
            }
        } else {
            $exchange          = 1;
            $exchange_metadata = ['type' => 'na'];
        }

        return [
            'exchange' => $exchange,
            'metadata' => $exchange_metadata
        ];
    }

    function get($key)
    {
        if (!$this->id) {
            return false;
        }


        switch ($key) {

            case 'EORI':
            return $this->data['Invoice EORI'];

            case 'Source Key':

                $souce='<span class="very_discreet">'._('Unknown sell channel').'</span>';

                $sql="select `Order Source Name`,`Order Source Type` from `Order Source Dimension` where `Order Source Key`=?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    [
                        $this->data['Invoice Source Key']
                    ]
                );
                while ($row = $stmt->fetch()) {

                    switch ($row['Order Source Type']){
                        case 'phone':
                            $souce=_('Order by phone');
                            break;
                        case 'website':
                            $souce=_('Ordered online');
                            break;
                        case 'other':
                            $souce='<span class="discret">'._('Other sell channel').'</span>';
                            break;
                        case 'email':
                            $souce=_('Ordered by email');
                            break;
                        case 'marketplace':
                            $souce=_('Marketplace').": ".$row['Order Source Name'];
                            break;
                        default:
                            $souce=$row['Order Source Name'];

                    }


                }


                return $souce;

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

                $date = $this->get_formatted_tax_validation_date();

                $msg = $this->data['Invoice Tax Number Validation Message'];

                $title = htmlspecialchars(trim($date.' '.$msg));

                if ($this->data['Invoice Tax Number'] != '') {
                    if ($this->data['Invoice Tax Number Valid'] == 'Yes') {
                        return sprintf(
                            '<i style="margin-right: 0" class="fa fa-check success" title="'._('Valid').'"></i> <span title="'.$title.'" >%s</span>',
                            $this->data['Invoice Tax Number'].$source
                        );
                    } elseif ($this->data['Invoice Tax Number Valid'] == 'Unknown') {
                        return sprintf(
                            '<i style="margin-right: 0" class="fal fa-question-circle discreet" title="'._('Validity is unknown').'"></i> <span class="discreet" title="'.$title.'">%s</span>',
                            $this->data['Invoice Tax Number'].$source
                        );
                    } elseif ($this->data['Invoice Tax Number Valid'] == 'API_Down') {
                        return sprintf(
                            '<i style="margin-right: 0"  class="fal fa-question-circle discreet" title="'._('Validity is unknown').'"> </i> <span class="discreet" title="'.$title.'">%s</span> %s',
                            $this->data['Invoice Tax Number'],
                            ' <i  title="'._('Online validation service down').'" class="fa fa-wifi-slash error"></i>'
                        );
                    } else {
                        return sprintf(
                            '<i style="margin-right: 0" class="fa fa-ban error" title="'._('Invalid').'"></i> <span class="discreet" title="'.$title.'">%s</span>',
                            $this->data['Invoice Tax Number'].$source
                        );
                    }
                }

                break;
            case('Tax Number Valid'):
                if ($this->data['Invoice Tax Number'] != '') {
                    $date = $this->get_formatted_tax_validation_date();


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
                        case 'Yes':
                            return _('Validated').$validation_data;
                        case 'No':
                            return _('Not valid').$validation_data;
                        default:
                            return $this->data['Invoice Tax Number Valid'].$validation_data;
                    }
                }
                break;
            case 'Address':

                return $this->get('Invoice Address Formatted');

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

            case 'Currency Code':

                return $this->data['Invoice Currency'];

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
            case('Total Profit'):

                return money(
                    $this->data['Invoice '.$key],
                    $this->data['Invoice Currency']
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
                        -1 * $this->data['Invoice '.$key],
                        $this->data['Invoice Currency']
                    );
                }

            case ('Net Amount Off'):
                return money(
                    -1 * $this->data['Invoice '.$key],
                    $this->data['Invoice Currency']
                );


            case('Corporate Currency Total Amount'):


                $account = get_object('Account', 1);
                $_key    = preg_replace('/Corporate Currency /', '', $key);

                return money(
                    $this->data['Invoice '.$_key] * $this->data['Invoice Currency Exchange'],
                    $account->get('Account Currency Code')
                );
            case('Date'):
            case('Tax Liability Date'):
                return strftime(
                    "%a %e %b %Y %H:%M %Z",
                    strtotime($this->data['Invoice '.$key].' +0:00')
                );
            case('Deleted Date'):
                return strftime(
                    "%a %e %b %Y %H:%M %Z",
                    strtotime($this->data['Invoice Deleted Date'].' +0:00')
                );
            case('Payment Method'):

                switch ($this->data['Invoice Main Payment Method']) {
                    case 'Credit Card':
                        return _('Credit Card');
                    case 'Cash':
                        return _('Cash');
                    case 'Paypal':
                        return _('Paypal');
                    case 'Check':
                        return _('Check');
                    case 'Bank Transfer':
                        return _('Bank Transfer');
                    case 'Other':
                        return _('Other');
                    case 'Unknown':
                        return _('Unknown');
                    default:
                        return $this->data['Invoice Main Payment Method'];
                }
            case('Payment State'):
                return $this->get_formatted_payment_state();

            case 'State':

                switch ($this->data['Invoice Paid']) {
                    case 'Yes':
                        return _('Paid');
                    case 'No':
                        return _('Not paid');
                    case 'Partially':
                        return _('Partially paid');
                    default:
                        return $this->data['Invoice Paid'];
                }
            case 'Margin':

                if ($this->data['Invoice Items Net Amount'] > 0) {
                    return percentage($this->data['Invoice Total Profit'] / $this->data['Invoice Items Net Amount'], 1);
                } else {
                    return '-';
                }


            case 'Category Object':

                return get_object('Category', $this->data['Invoice Category Key']);

            case 'number_otfs':

                $number_otfs = 0;
                $sql         = sprintf('select count(*) as num from `Order Transaction Fact` where `Invoice Key`=%d  ', $this->id);

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $number_otfs = $row['num'];
                    }
                }

                return $number_otfs;
            case 'number_onptf':

                $number_onptf = 0;
                $sql          = sprintf('select count(*) as num from `Order No Product Transaction Fact` where `Invoice Key`=%d  ', $this->id);

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $number_onptf = $row['num'];
                    }
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
                if ($this->data['Invoice Type'] == 'Invoice') {
                    return 'fal fa-file-invoice';
                } else {
                    return 'fal error fa-file-invoice';
                }
            case 'exchange_type':
                $exchange_data = $this->metadata('fx');
                if ($exchange_data != '') {
                    $exchange_data = json_decode($exchange_data, true);

                    return $exchange_data['type'];
                } else {
                    return 'unk';
                }
            case 'exchange_ECB_date':
                $exchange_data = $this->metadata('fx');
                if ($exchange_data != '') {
                    $exchange_data = json_decode($exchange_data, true);

                    return $exchange_data['date'];
                } else {
                    return '';
                }
            case 'account_currency_label':
                $account = get_object('Account', 1);


                if ($account->get('Account Currency') == 'EUR') {
                    if ($this->get('exchange_type') == 'ECB') {
                        return '<i title="'._('ECB exchange rate').' ('.$this->get('exchange_ECB_date').')" style="--fa-primary-color: #003399;--fa-secondary-color: #003399;" class="fad fa-euro-sign"></i> ';
                    } else {
                        return $account->get('Account Currency');
                    }
                } else {
                    return $account->get('Account Currency');
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

    function get_formatted_payment_state()
    {
        if ($this->data['Invoice Type'] == 'Refund') {
            switch ($this->data['Invoice Paid']) {
                case 'Yes':
                    return _('Paid back in full');
                case 'No':
                    return _('Not paid back');
                case 'Partially':
                    return _('Partially paid back');
                default:
                    return _('Unknown');
            }
        } else {
            switch ($this->data['Invoice Paid']) {
                case 'Yes':
                    return _('Paid in full');
                case 'No':
                    return _('Not paid');
                case 'Partially':
                    return _('Partially Paid');
                default:
                    return _('Unknown');
            }
        }
    }

    function metadata($key)
    {
        return ($this->metadata[$key] ?? '');
    }

    function update_payments_totals()
    {
        $payments = 0;

        $sql = sprintf(
            "SELECT sum(`Payment Transaction Amount`) AS amount  FROM `Order Payment Bridge` B LEFT JOIN `Payment Dimension` P  ON (B.`Payment Key`=P.`Payment Key`) WHERE `Invoice Key`=%d AND `Payment Transaction Status`='Completed'",
            $this->id
        );

        // print $sql;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                //print_r($row);

                $payments = round($row['amount'], 2);
            }
        }

        $to_pay = round($this->data['Invoice Total Amount'] - $payments, 2);


        $this->fast_update(array(

                               'Invoice Payments Amount' => $payments,
                               'Invoice To Pay Amount'   => $to_pay,
                               'Invoice Paid'            => ($to_pay <= 0 ? 'Yes' : ($payments == 0 ? 'No' : 'Partially')),

                           ));

        if ($to_pay == 0) {
            if ($this->data['Invoice Paid Date'] == '') {
                $this->update_field('Invoice Paid Date', gmdate('Y-m-d H:i:s'), 'no_history');
            }
        } else {
            $this->update_field('Invoice Paid Date', '', 'no_history');
        }
    }

    function update_billing_region()
    {
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

    /**
     * @throws ErrorException
     * @throws Exception
     */
    function create($invoice_data)
    {
        include_once 'utils/currency_functions.php';
        include_once 'utils/new_fork.php';

        $account = get_object('Account', 1);
        $account->load_properties();

        $base_data = $this->base_data();

        $this->editor = $invoice_data['editor'];

        unset($invoice_data['editor']);

        $extra_data = [];
        if (isset($invoice_data['extra_data'])) {
            $extra_data = $invoice_data['extra_data'];
            unset($invoice_data['extra_data']);
        }

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

        $exchange_data = $this->get_exchange_data($base_data['Invoice Currency'], $account->get('Account Currency'), $account->get('Account Country Code'), $base_data['Invoice Date']);


        $base_data['Invoice Currency Exchange'] = $exchange_data['exchange'];


        $keys   = '(';
        $values = 'values (';
        foreach ($base_data as $key => $value) {
            $keys   .= "`$key`,";
            $values .= prepare_mysql($value).",";
        }

        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);


        $sql = "insert into `Invoice Dimension` $keys  $values ;";


        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();

            if (!$this->id) {
                throw new Exception('Error inserting invoice');
            }

            $this->post_create_set_invoice_data();

            if (isset($extra_data['ups']) and $extra_data['ups']) {
                if ($account->properties('ups_tax_number') != '') {
                    $this->fast_update_json_field('Invoice Metadata', 'store_vat_number', $account->properties('ups_tax_number'));
                }
                $this->fast_update_json_field('Invoice Metadata', 'ups', true);

                if (isset($extra_data['dn_key']) and $extra_data['dn_key']) {
                    $dn = get_object('DeliveryNote', $extra_data['dn_key']);
                    $dn->fast_update_json_field('Delivery Note Properties', 'ups', true);
                    if ($account->properties('ups_tax_number') != '') {
                        $dn->fast_update_json_field('Delivery Note Properties', 'store_vat_number', $account->properties('ups_tax_number'));
                    }
                }
            }


            if (isset($recargo_equivalencia)) {
                $this->fast_update_json_field('Invoice Metadata', 'RE', 'Yes');
            }

            $this->fast_update_json_field('Invoice Metadata', 'fx', json_encode($exchange_data['metadata']));


            $sql = sprintf(
                'SELECT OTF.`Order Transaction Fact Key` FROM `Order Transaction Fact` OTF WHERE OTF.`Order Key`=%d  ',
                $this->data['Invoice Order Key']
            );

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $sql = sprintf(
                        "UPDATE `Order Transaction Fact` SET `Invoice Currency Exchange Rate`=%f,`Invoice Date`=%s, `Invoice Key`=%d WHERE `Order Transaction Fact Key`=%d",
                        ($this->data['Invoice Currency Exchange'] == '' ? 1 : $this->data['Invoice Currency Exchange']),
                        prepare_mysql($this->data['Invoice Date']),
                        $this->id,
                        $row['Order Transaction Fact Key']
                    );
                    $this->db->exec($sql);
                }
            }


            $sql = sprintf(
                "SELECT `Order No Product Transaction Fact Key`,`Transaction Net Amount`,`Transaction Tax Amount`,`Transaction Type`  FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d AND ISNULL(`Invoice Key`) ",
                $this->data['Invoice Order Key']
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $sql = sprintf(
                        "UPDATE `Order No Product Transaction Fact` SET `Invoice Date`=%s,`Invoice Key`=%d WHERE `Order No Product Transaction Fact Key`=%d",
                        prepare_mysql($this->data['Invoice Date']),
                        $this->id,
                        $row['Order No Product Transaction Fact Key']
                    );
                    $this->db->exec($sql);
                }
            }


            $data = $this->group_transactions_per_tax_category_key();


            foreach ($data as $tax_category_key => $amount) {
                $tax_category = new TaxCategory($this->db);
                $tax_category->loadWithKey($tax_category_key);


                $tax = round($tax_category->get('Tax Category Rate') * $amount, 2);
                $this->save_tax_bridge($tax_category, $tax, $amount);
            }


            $sql = sprintf(
                "UPDATE `Payment Dimension` SET `Payment Invoice Key`=%d  WHERE `Payment Order Key`=%d",

                $this->id,
                $this->data['Invoice Order Key']
            );
            $this->db->exec($sql);


            $sql = sprintf(
                "UPDATE `Order Payment Bridge` SET `Invoice Key`=%d  WHERE `Order Key`=%d",

                $this->id,
                $this->data['Invoice Order Key']
            );
            $this->db->exec($sql);


            $this->update_payments_totals();

            $this->update_profit();


            $this->update_billing_region();


            $history_data = array(
                'History Abstract' => sprintf(_('Invoice %s created'), $this->get('Public ID')),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data,
                true,
                'No',
                'Changes',
                $this->get_object_name(),
                $this->id
            );

            $this->categorize(true);

            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'         => 'invoice_created',
                    'invoice_key'  => $this->id,
                    'customer_key' => $this->get('Invoice Customer Key'),
                    'store_key'    => $this->get('Invoice Store Key')
                ),
                $account->get('Account Code')
            );

            $this->fork_index_elastic_search();
            $this->model_updated('new',$this->id);
        }
    }

    function categorize($skip_update_sales = false)
    {
        $category_key = 0;


        include_once 'conf/invoice_categorize_functions.php';

        $categorize_invoices_functions = get_categorize_invoices_functions();

        $store                      = get_object('Store', $this->get('Invoice Store Key'));
        $invoice_data               = $this->data;
        $invoice_data['Store Type'] = $store->get('Store Type');
        $sql                        = "SELECT `Invoice Category Key`,`Invoice Category Function Code`,`Invoice Category Function Argument` FROM `Invoice Category Dimension` WHERE  `Invoice Category Status` in ('Normal','ClosingDown') and   `Invoice Category Function Code` is not null ORDER BY `Invoice Category Function Order` desc ";



        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                if (isset($categorize_invoices_functions[$row['Invoice Category Function Code']])) {
                    if ($categorize_invoices_functions[$row['Invoice Category Function Code']]($invoice_data, $row['Invoice Category Function Argument'], $this->db)) {
                        $category_key = $row['Invoice Category Key'];
                        break;
                    }
                }
            }
        }

        if ($category_key) {
            /**
             * @var $category Category
             */
            $category                    = get_object('Category', $category_key);
            $category->skip_update_sales = $skip_update_sales;


            if ($category->id) {
                $category->associate_subject($this->id);


                $this->fast_update(array(
                                       'Invoice Category Key' => $category->id
                                   )

                );
            }
        }
    }

    /**
     * @throws Exception
     */
    function update_field_switcher($field, $value, $options = '', $metadata = '')
    {
        switch ($field) {
            case 'Invoice Date':

                $account = get_object('Account', 1);

                $this->update_field($field, $value, $options);

                $products = array();
                $dates    = array();

                $sql = "SELECT `Invoice Date`,`Product ID` FROM `Order Transaction Fact`  WHERE `Invoice Key`=?";


                $stmt = $this->db->prepare($sql);
                if ($stmt->execute(array(
                                       $this->id
                                   ))) {
                    while ($row = $stmt->fetch()) {
                        $products[$row['Product ID']] = 1;
                        $dates[$row['Invoice Date']]  = 1;
                    }
                }


                include_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_asset_sales',
                    array(
                        'type'                 => 'update_edited_invoice_products_sales_data',
                        'products'             => $products,
                        'dates'                => $dates,
                        'customer_key'         => $this->get('Invoice Customer Key'),
                        'store_key'            => $this->get('Invoice Store Key'),
                        'invoice_category_key' => $this->get('Invoice Category Key'),
                        'invoice_date'         => gmdate('Y-m-d', strtotime($this->get('Invoice Date').' +0:00'))
                    ),
                    $account->get('Account Code')
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

                /**
                 * @var $order Order
                 */
                $order         = get_object('Order', $this->data['Invoice Order Key']);
                $order->editor = $this->editor;

                $order_old_formatted_value = $order->get('Recargo Equivalencia');
                $order->fast_update_json_field('Order Metadata', 'RE', $value);
                $order->update_tax(false, $this->id);


                $this->fast_update([
                                       'Invoice Tax Code' => $order->get('Order Tax Code')
                                   ]);

                $this->update_tax_data();

                if ($order_old_formatted_value != $order->get('Recargo Equivalencia')) {
                    $this->add_changelog_record('Order Recargo Equivalencia', $order_old_formatted_value, $order->get('Recargo Equivalencia'), '', 'Order', $order->id);
                }

                $this->post_operation_invoice_totals_changed();

                break;
            case('Invoice Tax Number'):
                $this->update_tax_number($value);
                /**
                 * @var $order Order
                 */
                $order         = get_object('Order', $this->data['Invoice Order Key']);
                $order->editor = $this->editor;

                $order_old_formatted_value = $order->get('Tax Number Formatted');
                $order->update_tax_number($value, 'no_history', true);

                $order->fast_update(array(
                                        'Order Tax Number Valid'              => $this->data['Invoice Tax Number Valid'],
                                        'Order Tax Number Details Match'      => $this->data['Invoice Tax Number Details Match'],
                                        'Order Tax Number Validation Date'    => $this->data['Invoice Tax Number Validation Date'],
                                        'Order Tax Number Validation Source'  => $this->data['Invoice Tax Number Validation Source'],
                                        'Order Tax Number Validation Message' => $this->data['Invoice Tax Number Validation Message'],
                                    ));

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
                /**
                 * @var $order Order
                 */
                $order                     = get_object('Order', $this->data['Invoice Order Key']);
                $order->editor             = $this->editor;
                $order_old_formatted_value = $order->get('Tax Number Valid');
                $order->fast_update(array(
                                        'Order Tax Number Valid'              => $this->data['Invoice Tax Number Valid'],
                                        'Order Tax Number Details Match'      => $this->data['Invoice Tax Number Details Match'],
                                        'Order Tax Number Validation Date'    => $this->data['Invoice Tax Number Validation Date'],
                                        'Order Tax Number Validation Source'  => $this->data['Invoice Tax Number Validation Source'],
                                        'Order Tax Number Validation Message' => $this->data['Invoice Tax Number Validation Message'],
                                    ));
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

                $this->update_address('', json_decode($value, true));

                /** @var Order $order */
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

    function update_tax_data()
    {
        $old_invoice_total = $this->get('Invoice Total Amount');

        $tax_transactions_data = $this->group_transactions_per_tax_category_key();


        $sql = sprintf("DELETE FROM `Invoice Tax Bridge` WHERE `Invoice Tax Invoice Key`=%d", $this->id);
        $this->db->exec($sql);


        $total_tax = 0;

        foreach ($tax_transactions_data as $tax_category_key => $amount) {
            $tax_category = new TaxCategory($this->db);
            $tax_category->loadWithKey($tax_category_key);
            $tax       = round($tax_category->get('Tax Category Rate') * $amount, 2);
            $total_tax += $tax;

            $this->save_tax_bridge($tax_category, $tax, $amount);
        }


        $invoice_total = $total_tax + $this->data['Invoice Total Net Amount'];


        $this->fast_update(array(
                               'Invoice Total Tax Amount' => $total_tax,
                               'Invoice Total Amount'     => $invoice_total,

                           )

        );

        $this->update_payments_totals();

        if ($old_invoice_total != $this->get('Invoice Total Amount')) {
            $this->fork_index_elastic_search();
        }
    }


    private function save_tax_bridge(TaxCategory $tax_category, $tax, $net)
    {
        $sql = "INSERT INTO `Invoice Tax Bridge` (`Invoice Tax Invoice Key`,`Invoice Tax Category Key`,`Invoice Tax Code`,`Invoice Tax Amount`,`Invoice Tax Net`,`Invoice Tax Metadata`) 
            VALUES (?,?,?,?,?,'{}') ON DUPLICATE KEY UPDATE `Invoice Tax Amount`=? , `Invoice Tax Net`=?";


        $this->db->prepare($sql)->execute(array(
                                              $this->id,
                                              $tax_category->id,
                                              $tax_category->get('Tax Category Code'),
                                              $tax,
                                              $net,
                                              $tax,
                                              $net
                                          ));
    }

    function post_operation_invoice_totals_changed()
    {
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

    function update_tax_number($value)
    {
        include_once 'utils/validate_tax_number.php';

        $old_formatted_value = $this->get('Tax Number Formatted');
        $this->update_field('Invoice Tax Number', $value, 'no_history');


        if ($this->updated) {
            if ($value == '') {
                $this->fast_update(array(
                                       'Invoice Tax Number Valid'              => 'Unknown',
                                       'Invoice Tax Number Details Match'      => '',
                                       'Invoice Tax Number Validation Date'    => '',
                                       'Invoice Tax Number Validation Source'  => '',
                                       'Invoice Tax Number Validation Message' => ''
                                   ));
            } else {
                $tax_validation_data = validate_tax_number($this->data['Invoice Tax Number'], $this->data['Invoice Address Country 2 Alpha Code']);

                if ($tax_validation_data['Tax Number Valid'] == 'API_Down') {
                    if (!($this->data['Invoice Tax Number Validation Source'] == '' and $this->data['Invoice Tax Number Valid'] == 'No')) {
                        return;
                    }
                }

                $this->fast_update(array(
                                       'Invoice Tax Number Valid'              => $tax_validation_data['Tax Number Valid'],
                                       'Invoice Tax Number Details Match'      => $tax_validation_data['Tax Number Details Match'],
                                       'Invoice Tax Number Validation Date'    => $tax_validation_data['Tax Number Validation Date'],
                                       'Invoice Tax Number Validation Source'  => 'Online',
                                       'Invoice Tax Number Validation Message' => 'B: '.$tax_validation_data['Tax Number Validation Message'],
                                   ));
            }


            $this->new_value = $value;

            $this->add_changelog_record('Invoice Tax Number', $old_formatted_value, $this->get('Tax Number Formatted'), '', 'Invoice', $this->id);
        }

        $this->other_fields_updated = array(
            'Invoice_Tax_Number_Valid' => array(
                'field'           => 'Invoice_Tax_Number_Valid',
                'render'          => !($this->get('Invoice Tax Number') == ''),
                'value'           => $this->get('Invoice Tax Number Valid'),
                'formatted_value' => $this->get('Tax Number Valid'),


            )
        );
    }

    function update_tax_number_valid($value)
    {
        if (!empty($this->skip_validate_tax_number)) {
            return;
        }

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

            $this->fast_update(array(
                                   //'Invoice Tax Number Valid' => $tax_validation_data['Tax Number Valid'],

                                   'Invoice Tax Number Details Match'      => $tax_validation_data['Tax Number Details Match'],
                                   'Invoice Tax Number Validation Date'    => ($tax_validation_data['Tax Number Validation Date'] == '' ? gmdate('Y-m-d H:i:s') : $tax_validation_data['Tax Number Validation Date']),
                                   'Invoice Tax Number Validation Source'  => 'Online',
                                   'Invoice Tax Number Validation Message' => $tax_validation_data['Tax Number Validation Message'],
                               ));
            $this->update_field('Invoice Tax Number Valid', $tax_validation_data['Tax Number Valid']);
        } else {
            $this->fast_update(array(
                                   'Invoice Tax Number Details Match'      => 'Unknown',
                                   'Invoice Tax Number Validation Date'    => $this->editor['Date'],
                                   'Invoice Tax Number Validation Source'  => 'Staff',
                                   'Invoice Tax Number Validation Message' => $this->editor['Author Name'],
                               ));
            $this->update_field('Invoice Tax Number Valid', $value);
        }

        $this->other_fields_updated = array(
            'Invoice_Tax_Number' => array(
                'field'           => 'Invoice_Tax_Number',
                'render'          => true,
                'value'           => $this->get('Invoice Tax Number'),
                'formatted_value' => $this->get('Tax Number'),


            )
        );
    }

    function update_address_old($fields, $options = '')
    {
        $old_value             = $this->get("Address");
        $updated_fields_number = 0;


        if (preg_match('/gb|im|jy|gg/i', $fields['Address Country 2 Alpha Code'])) {
            include_once 'utils/geography_functions.php';
            $fields['Address Postal Code'] = gbr_pretty_format_post_code($fields['Address Postal Code']);
        }

        foreach ($fields as $field => $value) {
            $this->update_field(
                'Invoice '.$field,
                $value,
                'no_history'
            );
            if ($this->updated) {
                $updated_fields_number++;
            }
        }


        if ($updated_fields_number > 0) {
            $this->updated = true;
        }


        if ($this->updated) {
            /**
             * @var $order Order
             */
            $order         = get_object('Order', $this->data['Invoice Order Key']);
            $order->editor = $this->editor;

            $order->update_address('Invoice', $fields, '', true);


            $this->update_address_formatted_fields();


            if (!preg_match('/no_history/i', $options)) {
                $this->add_changelog_record(
                    "Address",
                    $old_value,
                    $this->get("Address"),
                    '',
                    $this->table_name,
                    $this->id
                );
            }
        }
    }

    function update_address_formatted_fields()
    {
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
        array_walk_recursive($address_fields, function (&$item) {
            $item = strval($item);
        });


        $new_checksum = md5(
            json_encode($address_fields)
        );


        $this->update_field(
            'Invoice Address Checksum',
            $new_checksum,
            'no_history'
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

    function get_date($field)
    {
        return strftime("%e %b %Y", strtotime($this->data[$field].' +0:00'));
    }

    /**
     * @throws Exception
     */
    function delete($note = '', $fix_mode = false): string
    {
        $is_refund = $this->data['Invoice Type'] == 'Refund';


        /**
         * @var $order Order
         */
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
O.`Order Transaction Fact Key`,`Order Currency Code`,`Product History Price`,`Product History Code`,`Order Transaction Amount`,`Delivery Note Quantity`,`Product History Name`,`Product History Price`,`Order Transaction Total Discount Amount`,`Order Transaction Gross Amount`,
`Product Units Per Case`,`Product Name`,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,P.`Product ID`,`Product History Code`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Product Type`,`Order Quantity`


FROM `Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`) WHERE `Invoice Key`=?";


        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(array(
                               $this->id
                           ))) {
            while ($row = $stmt->fetch()) {
                $products[$row['Product ID']] = 1;
                $dates[$row['Invoice Date']]  = 1;


                $discount = ($row['Order Transaction Total Discount Amount'] == 0 ? '' : percentage(
                    $row['Order Transaction Total Discount Amount'],
                    $row['Order Transaction Gross Amount'],
                    0
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
                    $quantity = number(($row['Order Transaction Product Type']=='Product'?$row['Delivery Note Quantity']:$row['Order Quantity']));
                    $factor   = 1;
                } else {
                    $quantity = '<span class="italic discreet"><span >~</span>'.number(-1 * $row['Order Transaction Amount'] / $row['Product History Price']).'</span>';
                    $factor   = -1;
                }


                $items_data[] = array(
                    'product_pid' => $row['Product ID'],
                    'code'        => $row['Product History Code'],
                    'type'        => $row['Order Transaction Product Type'],
                    'description' => $description,
                    'quantity'    => $quantity,
                    'net'         => money($factor * $row['Order Transaction Amount'], $row['Order Currency Code'])
                );
            }
        }


        $sql = sprintf("DELETE FROM `Invoice Tax Bridge` WHERE `Invoice Tax Invoice Key`=%d", $this->id);
        $this->db->exec($sql);


        $sql = sprintf("DELETE FROM `Invoice Sales Representative Bridge`  WHERE   `Invoice Key`=%d", $this->id);
        $this->db->exec($sql);

        $sql = sprintf("DELETE FROM `Invoice Delivery Note Bridge` WHERE `Invoice Key`=%d   ", $this->id);
        $this->db->exec($sql);

        $sql = sprintf("DELETE FROM `History Dimension`  WHERE   `Direct Object`='Invoice' AND `Direct Object Key`=%d", $this->id);
        $this->db->exec($sql);


        $payments = array();
        $sql      = sprintf(
            "SELECT * FROM `Invoice Payment Bridge` WHERE `Invoice Key`=%d",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $payments[] = get_object('Payment', $row['Payment Key']);
            }
        }


        if (!$is_refund) {
            $sql = sprintf(
                "DELETE FROM `Invoice Payment Bridge`  WHERE    `Invoice Key`=%d",
                $this->id
            );
            $this->db->exec($sql);


            $sql = sprintf(
                "UPDATE `Payment Dimension`  SET `Payment Invoice Key`=NULL   WHERE `Payment Invoice Key`=%d",
                $this->id
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


        if ($is_refund) {
            $sql = "select `Feedback Key` from  `Feedback Dimension` where `Feedback Parent`='Refund' and  `Feedback Parent Key`=?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(array(
                               $this->id
                           ));
            while ($row = $stmt->fetch()) {
                $sql = "delete from `Feedback Dimension` where `Feedback Key`=?";
                $this->db->prepare($sql)->execute(array(
                                                      $row['Feedback Key']
                                                  ));

                $sql = "delete from `Feedback OTF Bridge` where `Feedback OTF Feedback Key`=?";
                $this->db->prepare($sql)->execute(array(
                                                      $row['Feedback Key']
                                                  ));

                $sql = "delete from `Feedback ONPTF Bridge` where `Feedback ONPTF Feedback Key`=?";
                $this->db->prepare($sql)->execute(array(
                                                      $row['Feedback Key']
                                                  ));

                $sql = "delete from `Feedback ITF Bridge` where `Feedback ITF Feedback Key`=?";
                $this->db->prepare($sql)->execute(array(
                                                      $row['Feedback Key']
                                                  ));
            }
        }


        if (!$is_refund) {
            $this->data ['Order Invoiced Balance Total Amount']             = 0;
            $this->data ['Order Invoiced Balance Net Amount']               = 0;
            $this->data ['Order Invoiced Balance Tax Amount']               = 0;
            $this->data ['Order Invoiced Outstanding Balance Total Amount'] = 0;
            $this->data ['Order Invoiced Outstanding Balance Net Amount']   = 0;
            $this->data ['Order Invoiced Outstanding Balance Tax Amount']   = 0;


            $sql = sprintf(
                "DELETE FROM `Order Transaction Fact`  WHERE    `Invoice Key`=%d  AND (`Order Key`=0 OR `Order Key` IS NULL) ",
                $this->id
            );
            $this->db->exec($sql);

            $sql = sprintf(
                "UPDATE `Order Transaction Fact` SET `Invoice Date`=NULL , `Invoice Key`=NULL ,`Consolidated`='No'  WHERE  `Invoice Key`=%d",
                $this->id
            );
            $this->db->exec($sql);


            $sql = sprintf(
                "DELETE FROM `Order No Product Transaction Fact`  WHERE    `Invoice Key`=%d  AND (`Order Key`=0 OR `Order Key` IS NULL) ",
                $this->id
            );
            $this->db->exec($sql);

            $sql = sprintf(
                "UPDATE `Order No Product Transaction Fact` SET `Invoice Key`=NULL , `Consolidated`='No'   WHERE  `Invoice Key`=%d",
                $this->id
            );
            $this->db->exec($sql);
        } else {
            $this->db->prepare("DELETE FROM `Order Transaction Fact`  WHERE   `Invoice Key`=?")->execute(array(
                                                                                                             $this->id
                                                                                                         ));
            $this->db->prepare("DELETE FROM `Order No Product Transaction Fact`  WHERE `Invoice Key`=?")->execute(array(
                                                                                                                      $this->id
                                                                                                                  ));
        }

        $sql = sprintf(
            "DELETE FROM `Invoice Dimension`  WHERE  `Invoice Key`=%d",
            $this->id
        );
        $this->db->exec($sql);


        try {
            $this->delete_index_elastic_search(get_elasticsearch_hosts());
        } catch (Throwable $exception) {
            Sentry\captureException($exception);
        }


        $this->data['items'] = $items_data;


        $sql = "INSERT INTO `Invoice Deleted Dimension` (`Invoice Deleted Key`,`Invoice Deleted Store Key`,`Invoice Deleted Order Key`,`Invoice Deleted Public ID`,`Invoice Deleted Metadata`,`Invoice Deleted Date`,`Invoice Deleted Note`,`Invoice Deleted User Key`,`Invoice Deleted Type`,`Invoice Deleted Total Amount`) VALUE (?,?,?,?,?,?,?,?,?,?) ";


        $this->db->prepare($sql)->execute(array(
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
                                          ));

        $this->fork_index_elastic_search();

        if (!$fix_mode) {
            if (!$is_refund) {
                $dn = get_object('DeliveryNote', $order->get('Order Delivery Note Key'));

                if ($dn->id) {
                    $dn->fast_update(array(
                                         'Delivery Note Invoiced'                    => 'No',
                                         'Delivery Note Invoiced Net DC Amount'      => 0,
                                         'Delivery Note Invoiced Shipping DC Amount' => 0

                                     ));
                }
                if ($order->id) {
                    $order->update_state('Invoice Deleted', '', array('note' => $note));
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
            $history_data,
            true,
            'No',
            'Changes',
            $this->get_object_name(),
            $this->id
        );


        include_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_asset_sales',
            array(
                'type'                 => 'update_edited_invoice_products_sales_data',
                'products'             => $products,
                'dates'                => $dates,
                'customer_key'         => $customer_key,
                'store_key'            => $store_key,
                'invoice_category_key' => $invoice_category_key,
                'invoice_date'         => $invoice_date
            ),
            $account->get('Account Code')
        );


        // here we update elastic customer.assets index
        new_housekeeping_fork(
            'au_housekeeping',
            array(
                'type'         => 'invoice_deleted',
                'invoice_key'  => $this->id,
                'customer_key' => $customer_key,
                'store_key'    => $store_key,

            ),
            $account->get('Account Code')
        );
        $this->model_updated('deleted',$this->id);

        if ($order->id) {
            return sprintf('/orders/%d/%d', $store_key, $order->id);
        } else {
            return sprintf('/orders/%d', $store_key);
        }
    }



    public function get_payments($scope = 'keys', $filter = ''): array
    {
        if ($filter == 'Completed') {
            $where = ' and `Payment Transaction Status`="Completed" ';
        } else {
            $where = '';
        }


        $payments = array();
        $sql      = sprintf(
            "SELECT B.`Payment Key` FROM `Order Payment Bridge` B LEFT JOIN `Payment Dimension` P  ON (P.`Payment Key`=B.`Payment Key`)  WHERE `Invoice Key`=%d %s ",
            $this->id,
            $where
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
        }


        return $payments;
    }


    function add_payment(Payment $payment)
    {
        $payment->update(array('Payment Invoice Key' => $this->id), 'no_history');

        $sql = sprintf('UPDATE `Order Payment Bridge` SET `Invoice Key`=%d WHERE `Payment Key`=%d ', $this->id, $payment->id);

        $this->db->exec($sql);
        $this->update_payments_totals();
    }

    function get_field_label($field)
    {
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

    function upload_pdf_to_google_drive($google_drive)
    {
        $account = get_object('Account', 1);
        $account->load_acc_data();

        if ($account->properties('google_drive_folder_key')) {
            $store     = get_object('Store', $this->get('Invoice Store Key'));
            $auth_data = json_encode(array(
                                         'auth_token' => array(
                                             'logged_in'      => true,
                                             'user_key'       => 0,
                                             'logged_in_page' => 0
                                         )
                                     ));
            $sak       = safeEncrypt($auth_data, md5('82$je&4WN1g2B^{|bRbcEdx!Nz$OAZDI3ZkNs[cm9Q1)8buaLN'.SKEY));


            $file_key = $google_drive->upload(
                $store->properties('google_drive_folder_invoices_key'),
                [
                    gmdate('Y', strtotime($this->get('Invoice Date'))),
                    gmdate('F', strtotime($this->get('Invoice Date'))),
                    gmdate('Y-m-d', strtotime($this->get('Invoice Date'))),
                    $this->get('Public ID')
                ],
                [
                    [
                        'au_location' => 'invoice_year',
                        'store_key'   => $store->id
                    ],
                    [
                        'au_location' => 'invoice_year_month',
                        'store_key'   => $store->id
                    ],
                    [
                        'au_location' => 'invoice_year_month_day',
                        'store_key'   => $store->id
                    ],
                    [
                        'au_location' => 'invoice',
                        'store_key'   => $store->id,
                        'invoice_key' => $this->id
                    ],
                ],
                file_get_contents($account->get('Account System Public URL').'/pdf/invoice.pdf.php?id='.$this->id.'&sak='.$sak)

            );

            $this->fast_update_json_field('Invoice Metadata', 'google_drive_file_key', $file_key);
        }
    }

    function update_profit()
    {
        $profit = 0;
        if ($this->data['Invoice Type'] == 'Invoice') {
            $sql = sprintf(
                "SELECT sum(`Cost Supplier`) AS cost, sum(`Order Transaction Amount`) AS net  FROM `Order Transaction Fact` WHERE `Invoice Key`=%d AND `Order Transaction Type`='Order' ",
                $this->id
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $profit = $row['net'] - $row['cost'];
                }
            }
        } else {
            $sql = sprintf(
                "SELECT sum(`Order Transaction Amount`) AS net  FROM `Order Transaction Fact` WHERE `Invoice Key`=%d AND `Order Transaction Type`='Refund' ",
                $this->id
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $profit = $row['net'];
                }
            }
        }
        $this->fast_update(array(

                               'Invoice Total Profit' => $profit
                           ));
    }

    private function post_create_set_invoice_data()
    {
        $this->get_data('id', $this->id);

        $store = get_object('Store', $this->get('Store Key'));

        $this->fast_update_json_field('Invoice Metadata', 'store_name', $store->get('Store Name'));
        $this->fast_update_json_field('Invoice Metadata', 'store_address', $store->get('Store Address'));
        $this->fast_update_json_field('Invoice Metadata', 'store_url', $store->get('Store URL'));
        $this->fast_update_json_field('Invoice Metadata', 'store_company_name', $store->get('Store Company Name'));
        $this->fast_update_json_field('Invoice Metadata', 'store_company_name', $store->get('Store Company Name'));
        $this->fast_update_json_field('Invoice Metadata', 'store_vat_number', $store->get('Store VAT Number'));
        $this->fast_update_json_field('Invoice Metadata', 'store_company_number', $store->get('Store Company Number'));
        $this->fast_update_json_field('Invoice Metadata', 'store_telephone', $store->get('Store Telephone'));
        $this->fast_update_json_field('Invoice Metadata', 'store_email', $store->get('Store Email'));
        $this->fast_update_json_field('Invoice Metadata', 'store_message', $store->get('Store Invoice Message'));
    }

    private function get_formatted_tax_validation_date()
    {
        if ($this->data['Invoice Tax Number Validation Date'] != '') {
            $_tmp = gmdate("U") - gmdate(
                    "U",
                    strtotime(
                        $this->data['Invoice Tax Number Validation Date'].' +0:00'
                    )
                );

            if ($_tmp < 3600) {
                $date = strftime("%e %b %Y %H:%M:%S %Z", strtotime($this->data['Invoice Tax Number Validation Date'].' +0:00'));
            } elseif ($_tmp < 86400) {
                $date = strftime(
                    "%e %b %Y %H:%M %Z",
                    strtotime($this->data['Invoice Tax Number Validation Date'].' +0:00')
                );
            } else {
                $date = strftime(
                    "%e %b %Y",
                    strtotime($this->data['Invoice Tax Number Validation Date'].' +0:00')
                );
            }
        } else {
            $date = '';
        }


        return $date;
    }

    public function group_transactions_per_tax_category_key($include_amount_off = true): array
    {
        $data = [];

        $sql = "SELECT  `Order Transaction Tax Category Key`,sum(`Order Transaction Amount`) AS net   FROM `Order Transaction Fact` WHERE `Invoice Key`=?  GROUP BY  `Order Transaction Tax Category Key`";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->id
                       ));
        while ($row = $stmt->fetch()) {
            $data[$row['Order Transaction Tax Category Key']] = $row['net'];
        }


        $sql = "SELECT  `Order No Product Transaction Tax Category Key`, sum(`Transaction Net Amount`) AS net  FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=?  GROUP BY  `Order No Product Transaction Tax Category Key`  ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
                           $this->id
                       ]);
        while ($row = $stmt->fetch()) {
            if (isset($data[$row['Order No Product Transaction Tax Category Key']])) {
                $data[$row['Order No Product Transaction Tax Category Key']] += $row['net'];
            } else {
                $data[$row['Order No Product Transaction Tax Category Key']] = $row['net'];
            }
        }



        if ($include_amount_off and $this->data['Invoice Net Amount Off'] != 0) {
            if (isset($data[$this->data['Invoice Tax Category Key']])) {
                $data[$this->data['Invoice Tax Category Key']] -= $this->data['Invoice Net Amount Off'];
            } else {
                $data[$this->data['Invoice Tax Category Key']] = -$this->data['Invoice Net Amount Off'];
            }
        }

        return $data;
    }


}



