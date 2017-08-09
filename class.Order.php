<?php
/*


  About:
  Author: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/
include_once 'class.DB_Table.php';

require_once 'utils/order_functions.php';
require_once 'utils/natural_language.php';

include_once 'trait.OrderShippingOperations.php';
include_once 'trait.OrderChargesOperations.php';
include_once 'trait.OrderDiscountOperations.php';
include_once 'trait.OrderItems.php';
include_once 'trait.OrderPayments.php';
include_once 'trait.OrderCalculateTotals.php';
include_once 'trait.OrderBasketOperations.php';
include_once 'trait.OrderTax.php';


class Order extends DB_Table {

    use OrderShippingOperations, OrderChargesOperations, OrderDiscountOperations, OrderItems, OrderPayments, OrderCalculateTotals,OrderBasketOperations,OrderTax;

    //Public $data = array ();
    // Public $items = array ();
    // Public $status_names = array ();
    // Public $id = false;
    // Public $tipo;
    // Public $staus = 'new';

    var $amount_off_allowance_data = false;
    var $ghost_order = false;
    var $update_stock = true;
    public $skip_update_product_sales = false;
    var $skip_update_after_individual_transaction = true;

    function __construct($arg1 = false, $arg2 = false) {


        global $db;
        $this->db = $db;


        $this->table_name      = 'Order';
        $this->ignore_fields   = array('Order Key');
        $this->update_customer = true;

        $this->status_names = array(0 => 'new');
        if (preg_match('/new/i', $arg1)) {
            $this->create_order($arg2);

            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        $this->get_data($arg1, $arg2);

    }





    function get_data($key, $id) {
        if ($key == 'id') {
            $sql = sprintf("SELECT * FROM `Order Dimension` WHERE `Order Key`=%d", $id);


        } elseif ($key == 'public id' or $key == 'public_id') {
            $sql = sprintf("SELECT * FROM `Order Dimension` WHERE `Order Public ID`=%s", prepare_mysql($id));
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Order Key'];
        }


        if ($this->id) {
            $this->set_display_currency($this->data['Order Currency'], 1.0);
        }

    }

    function set_display_currency($currency_code, $exchange) {
        $this->currency_code = $currency_code;
        $this->exchange      = $exchange;

    }


    function add_credit_no_product_transaction($credit_transaction_data) {

        $order_date = $this->data['Order Date'];

        $sql = sprintf(
            "INSERT INTO `Order No Product Transaction Fact` (
  					`Transaction Gross Amount`,`Transaction Net Amount`,`Transaction Tax Amount`,
  					`Affected Order Key`,`Order Key`,`Order Date`,`Transaction Type`,`Transaction Description`,`Tax Category Code`,`Currency Code`)
  				VALUES (%f,%f,%s,%d,%s,%s,%s,%s,%s) ",

            $credit_transaction_data['Transaction Net Amount'], $credit_transaction_data['Transaction Net Amount'], $credit_transaction_data['Transaction Tax Amount'],
            prepare_mysql($credit_transaction_data['Affected Order Key']), $this->id, prepare_mysql($order_date), prepare_mysql('Credit'),
            prepare_mysql($credit_transaction_data['Transaction Description']),

            prepare_mysql($credit_transaction_data['Tax Category Code']),

            prepare_mysql($this->data['Order Currency'])

        );
        //print $sql;
        $this->db->exec($sql);
        $this->update_totals();
    }

    function update_credit_no_product_transaction($credit_transaction_data) {


        $sql = sprintf(
            "UPDATE `Order No Product Transaction Fact` SET `Transaction Outstanding Net Amount Balance`=%f,`Transaction Outstanding Tax Amount Balance`=%f,`Transaction Net Amount`=%f,`Transaction Tax Amount`=%f,`Transaction Description`=%s,`Tax Category Code`=%s WHERE `Order No Product Transaction Fact Key`=%d AND `Order Key`=%d ",
            $credit_transaction_data['Transaction Net Amount'], $credit_transaction_data['Transaction Tax Amount'], $credit_transaction_data['Transaction Net Amount'],
            $credit_transaction_data['Transaction Tax Amount'], prepare_mysql($credit_transaction_data['Transaction Description']),

            prepare_mysql($credit_transaction_data['Tax Category Code']), $credit_transaction_data['Order No Product Transaction Fact Key'], $this->id


        );
        $this->db->exec($sql);
        $this->update_totals();
    }

    function delete_credit_transaction($transaction_key) {
        $sql = sprintf(
            "DELETE FROM `Order No Product Transaction Fact`  WHERE `Order No Product Transaction Fact Key`=%d AND `Order Key`=%d ", $transaction_key, $this->id


        );
        //print $sql;
        $this->db->exec($sql);
        $this->update_totals();
    }

    function create_refund($data = false) {


        $store = new Store($this->data['Order Store Key']);


        $invoice_public_id = '';


        if ($store->data['Store Refund Public ID Method'] == 'Same Invoice ID') {

            foreach ($this->get_invoices_objects() as $_invoice) {
                if ($_invoice->data['Invoice Type'] == 'Invoice') {
                    $invoice_public_id = $_invoice->data['Invoice Public ID'];
                }
            }


            if ($invoice_public_id == '') {
                //Next Invoice ID


                if ($store->data['Store Next Invoice Public ID Method'] == 'Invoice Public ID') {

                    $sql = sprintf(
                        "UPDATE `Store Dimension` SET `Store Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Store Invoice Last Invoice Public ID` + 1) WHERE `Store Key`=%d",
                        $this->data['Order Store Key']
                    );
                    $this->db->exec($sql);
                    $invoice_public_id = sprintf(
                        $store->data['Store Invoice Public ID Format'], mysql_insert_id()
                    );

                } elseif ($store->data['Store Next Invoice Public ID Method'] == 'Order ID') {

                    $sql = sprintf(
                        "UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) WHERE `Store Key`=%d", $this->data['Order Store Key']
                    );
                    $this->db->exec($sql);
                    $invoice_public_id = mysql_insert_id();
                    $invoice_public_id = sprintf(
                        $store->data['Store Order Public ID Format'], mysql_insert_id()
                    );


                } else {

                    $sql = sprintf(
                        "UPDATE `Account Dimension` SET `Account Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Public ID` + 1) WHERE `Account Key`=1"
                    );
                    $this->db->exec($sql);
                    $public_id = mysql_insert_id();
                    include_once 'class.Account.php';
                    $account           = new Account();
                    $invoice_public_id = sprintf(
                        $account->data['Account Invoice Public ID Format'], $public_id
                    );

                }


            }


        } elseif ($store->data['Store Refund Public ID Method'] == 'Account Wide Own Index') {
            include_once 'class.Account.php';
            $account = new Account();
            $sql     = sprintf(
                "UPDATE `Account Dimension` SET `Account Invoice Last Refund Public ID` = LAST_INSERT_ID(`Account Invoice Last Refund Public ID` + 1) WHERE `Account Key`=1"
            );
            $this->db->exec($sql);
            $invoice_public_id = sprintf(
                $account->data['Account Refund Public ID Format'], mysql_insert_id()
            );


        } elseif ($store->data['Store Refund Public ID Method'] == 'Store Own Index') {

            $sql = sprintf(
                "UPDATE `Store Dimension` SET `Store Invoice Last Refund Public ID` = LAST_INSERT_ID(`Store Invoice Last Refund Public ID` + 1) WHERE `Store Key`=%d", $this->data['Order Store Key']
            );
            mysql_query($sql);
            $invoice_public_id = sprintf(
                $store->data['Store Refund Public ID Format'], mysql_insert_id()
            );


        } else { //Next Invoice ID


            if ($store->data['Store Next Invoice Public ID Method'] == 'Invoice Public ID') {

                $sql = sprintf(
                    "UPDATE `Store Dimension` SET `Store Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Store Invoice Last Invoice Public ID` + 1) WHERE `Store Key`=%d",
                    $this->data['Order Store Key']
                );
                $this->db->exec($sql);
                $invoice_public_id = sprintf(
                    $store->data['Store Invoice Public ID Format'], mysql_insert_id()
                );

            } elseif ($store->data['Store Next Invoice Public ID Method'] == 'Order ID') {

                $sql = sprintf(
                    "UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) WHERE `Store Key`=%d", $this->data['Order Store Key']
                );
                $this->db->exec($sql);
                $invoice_public_id = mysql_insert_id();
                $invoice_public_id = sprintf(
                    $store->data['Store Order Public ID Format'], mysql_insert_id()
                );


            } else {

                $sql = sprintf(
                    "UPDATE `Account Dimension` SET `Account Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Public ID` + 1) WHERE `Account Key`=1"
                );
                $this->db->exec($sql);
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

        $refund_data = array(
            'Invoice Customer Key' => $this->data['Order Customer Key'],
            'Invoice Store Key'    => $this->data['Order Store Key'],
            'Order Key'            => $this->id

        );


        if ($invoice_public_id != '') {
            $refund_data['Invoice Public ID'] = $invoice_public_id;
        }


        if (!$data) {
            $data = array();
        }

        if (array_key_exists('Invoice Metadata', $data)) {
            $refund_data['Invoice Metadata'] = $data['Invoice Metadata'];
        }
        if (array_key_exists('Invoice Date', $data)) {
            $refund_data['Invoice Date'] = $data['Invoice Date'];
        }
        if (array_key_exists('Invoice Tax Code', $data)) {
            $refund_data['Invoice Tax Code'] = $data['Invoice Tax Code'];
        }

        $refund = new Invoice('create refund', $refund_data);


        return $refund;
    }

    function get_invoices_objects() {
        $invoices     = array();
        $invoices_ids = $this->get_invoices_ids();
        foreach ($invoices_ids as $order_id) {
            $invoices[$order_id] = new Invoice($order_id);
        }

        return $invoices;
    }

    function get_invoices_ids() {

        $invoices = array();

        $sql = sprintf(
            "SELECT `Invoice Key` FROM `Order Invoice Bridge` WHERE `Order Key`=%d ", $this->id
        );

        //print "$sql\n";
        $res = mysql_query($sql);
        while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
            if ($row['Invoice Key']) {
                $invoices[$row['Invoice Key']] = $row['Invoice Key'];
            }

        }


        return $invoices;

    }

    function get_refund_public_id($refund_id, $suffix_counter = '') {
        $sql = sprintf(
            "SELECT `Invoice Public ID` FROM `Invoice Dimension` WHERE `Invoice Store Key`=%d AND `Invoice Public ID`=%s ", $this->data['Order Store Key'], prepare_mysql($refund_id.$suffix_counter)
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


    function update_field_switcher($field, $value, $options = '', $metadata = '') {

        switch ($field) {


            case('Order For Collection'):

                $this->update_for_collection($value, $options);
                break;
            case('Order Tax Number'):
                $this->update_tax_number($value);
                break;
            case('Order Tax Number Valid'):
                $this->update_tax_number_valid($value);
                break;
            case 'Order Invoice Address':
                $this->update_address('Invoice', json_decode($value, true));


                $customer = get_object('Customer', $this->data['Order Customer Key']);
                $customer->update_field_switcher('Customer Invoice Address', $value, '', array('no_propagate_orders' => true));


                break;
            case 'Order Delivery Address':
                $this->update_address('Delivery', json_decode($value, true));
                break;

            case('Order Current Dispatch State'):
                $this->update_state($value, $options, $metadata);
                break;
            case 'auto_account_payments':
                $this->auto_account_payments($value, $options);
                break;


                break;

            case('Sticky Note'):
                $this->update_field('Order '.$field, $value, 'no_null');
                $this->new_value = html_entity_decode($this->new_value);
                break;
            default:
                $base_data = $this->base_data();


                if (array_key_exists($field, $base_data)) {
                    // print "xxx-> $field : $value -> ".$this->data[$field]." \n";

                    if ($value != $this->data[$field]) {

                        $this->update_field($field, $value, $options);
                    }
                }
        }

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

    function get($key = '') {


        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }


        if ($key == 'Shipping Net Amount' and $this->data['Order Shipping Method'] == 'TBC') {
            return _('TBC');
        }

        if (preg_match(
            '/^(Balance (Total|Net|Tax)|Invoiced Total Net Adjust|Invoiced Total Tax Adjust|Invoiced Refund Net|Invoiced Refund Tax|Total|Items|Invoiced Items|Invoiced Tax|Invoiced Net|Invoiced Charges|Payments|To Pay|Invoiced Shipping|Invoiced Insurance |(Shipping |Charges |Insurance )?Net).*(Amount)$/',
            $key
        )) {
            $amount = 'Order '.$key;

            return money(
                $this->exchange * $this->data[$amount], $this->currency_code
            );
        }
        if (preg_match('/^Number (Items|Products)$/', $key)) {

            $amount = 'Order '.$key;

            return number($this->data[$amount]);
        }


        switch ($key) {

            case 'Items Discount Percentage':

                return percentage($this->data['Order Items Discount Amount'],$this->data['Order Items Gross Amount']);

            case 'Currency Code':

                return $this->data['Order Currency'];
                break;

            case('Tax Number Valid'):
                if ($this->data['Order Tax Number'] != '') {

                    if ($this->data['Order Tax Number Validation Date'] != '') {
                        $_tmp = gmdate("U") - gmdate(
                                "U", strtotime(
                                       $this->data['Order Tax Number Validation Date'].' +0:00'
                                   )
                            );
                        if ($_tmp < 3600) {
                            $date = strftime(
                                "%e %b %Y %H:%M:%S %Z", strtotime(
                                                          $this->data['Order Tax Number Validation Date'].' +0:00'
                                                      )
                            );

                        } elseif ($_tmp < 86400) {
                            $date = strftime(
                                "%e %b %Y %H:%M %Z", strtotime(
                                                       $this->data['Order Tax Number Validation Date'].' +0:00'
                                                   )
                            );

                        } else {
                            $date = strftime(
                                "%e %b %Y", strtotime(
                                              $this->data['Order Tax Number Validation Date'].' +0:00'
                                          )
                            );
                        }
                    } else {
                        $date = '';
                    }


                    // print_r($this->data);

                    $msg = $this->data['Order Tax Number Validation Message'];

                    if ($this->data['Order Tax Number Validation Source'] == 'Online') {
                        $source = '<i title=\''._('Validated online').'\' class=\'fa fa-globe\'></i>';


                    } elseif ($this->data['Order Tax Number Validation Source'] == 'Manual') {
                        $source = '<i title=\''._('Set up manually').'\' class=\'fa fa-hand-rock-o\'></i>';
                    } else {
                        $source = '';
                    }

                    $validation_data = trim($date.' '.$source.' '.$msg);
                    if ($validation_data != '') {
                        $validation_data = ' <span class=\'discreet\'>('.$validation_data.')</span>';
                    }

                    switch ($this->data['Order Tax Number Valid']) {
                        case 'Unknown':
                            return _('Not validated').$validation_data;
                            break;
                        case 'Yes':
                            return _('Validated').$validation_data;
                            break;
                        case 'No':
                            return _('Not valid').$validation_data;
                        default:
                            return $this->data['Order Tax Number Valid'].$validation_data;

                            break;
                    }
                }
                break;

            case 'Order Invoice Address':
            case 'Order Delivery Address':

                if ($key == 'Order Delivery Address') {
                    $type = 'Delivery';
                } else {
                    $type = 'Invoice';
                }

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

                return json_encode($address_fields);
                break;
            case 'Invoice Address':
            case 'Delivery Address':

                return $this->get('Order '.$key.' Formatted');
                break;


            case ('State Index'):
                //'In Process by Customer','Waiting for Payment Confirmation','In Process','Submitted by Customer','Ready to Pick',
                //'Picking & Packing','Packing','Packed','Packed Done','Ready to Ship','Dispatched','Cancelled','Suspended','Cancelled by Customer'

                switch ($this->data['Order Current Dispatch State']) {
                    case 'In Process':
                        return 10;
                        break;

                    case 'Waiting for Payment Confirmation':
                        return 25;
                        break;
                    case 'Submitted by Customer':
                        return 30;
                        break;
                    case 'In Warehouse':
                        return 40;
                        break;

                    case 'Packed Done':
                        return 80;
                        break;
                    case 'Ready to Ship':
                        return 90;
                        break;
                    case 'Dispatched':
                        return 100;
                        break;
                    case 'Cancelled':
                        return -10;
                        break;

                    case 'Suspended':
                        return -5;
                        break;

                    default:
                        return 0;
                        break;
                }

                break;

            case('Corporate Currency Invoiced Total Amount'):

                global $corporate_currency;
                $_key = preg_replace('/Corporate Currency /', '', $key);

                return money(
                    ($this->data['Order Invoiced Net Amount'] + $this->data['Order Invoiced Tax Amount']) * $this->data['Order Currency Exchange'], $corporate_currency
                );
                break;
            case('Corporate Currency Balance Total Amount'):
                global $corporate_currency;
                $_key = preg_replace('/Corporate Currency /', '', $key);

                return money(
                    $this->data['Order '.$_key] * $this->data['Order Currency Exchange'], $corporate_currency
                );
                break;

            case("Sticky Note"):
                return nl2br($this->data['Order Sticky Note']);
                break;
            case('Deal Amount Off'):
                return money(
                    -1 * $this->data['Order Deal Amount Off'], $this->currency_code
                );
            case('Items Gross Amount After No Shipped'):
                return money(
                    $this->data['Order Items Gross Amount'] - $this->data['Order Out of Stock Net Amount'], $this->currency_code
                );
            case('Tax Rate'):
                return percentage($this->data['Order Tax Rate'], 1);
                break;
            case('Order Out of Stock Amount'):
                return $this->data['Order Out of Stock Net Amount'] + $this->data['Order Out of Stock Tax Amount'];
            case('Out of Stock Amount'):
                return money(
                    -1 * ($this->data['Order Out of Stock Net Amount'] + $this->data['Order Out of Stock Tax Amount']), $this->data['Order Currency']
                );
            case('Invoiced Total Tax Amount'):
                return money(
                    $this->data['Order Invoiced Tax Amount'], $this->data['Order Currency']
                );
                break;
            case('Out of Stock Net Amount'):
                return money(
                    -1 * $this->data['Order Out of Stock Net Amount'], $this->data['Order Currency']
                );
                break;
            case('Not Found Net Amount'):
                return money(
                    -1 * $this->data['Order Not Found Net Amount'], $this->data['Order Currency']
                );
                break;
            case('Not Due Other Net Amount'):
                return money(
                    -1 * $this->data['Order Not Due Other Net Amount'], $this->data['Order Currency']
                );
                break;
            case('No Authorized Net Amount'):
                return money(
                    -1 * $this->data['Order No Authorized Net Amount'], $this->data['Order Currency']
                );
                break;
            case('Invoiced Total Net Amount'):
                return money(
                    $this->data['Order Invoiced Net Amount'], $this->data['Order Currency']
                );
                break;
            case('Invoiced Total Amount'):
                return money(
                    $this->data['Order Invoiced Net Amount'] + $this->data['Order Invoiced Tax Amount'], $this->data['Order Currency']
                );
                break;
            case ('Invoiced Refund Total Amount'):
                return money(
                    $this->data['Order Invoiced Refund Net Amount'] + $this->data['Order Invoiced Refund Tax Amount'], $this->data['Order Currency']
                );

                break;
            case('Shipping And Handing Net Amount'):
                return money($this->data['Order Shipping Net Amount'] + $this->data['Order Charges Net Amount']);
                break;
            case('Date'):
            case('Last Updated Date'):
            case('Cancelled Date'):
            case('Created Date'):
            case('Send to Warehouse Date'):
            case('Suspended Date'):
            case('Checkout Submitted Payment Date'):
            case('Checkout Completed Payment Date'):
            case('Submitted by Customer Date'):
            case('Dispatched Date'):
            case('Post Transactions Dispatched Date'):
            case('Packed Done Date'):
                if ($this->data['Order '.$key] == '') {
                    return '';
                } else {
                    return strftime("%e %b %y %H:%M", strtotime($this->data['Order '.$key].' +0:00'));
                }


                break;
            case('Submitted by Customer Interval'):
                if ($this->data['Order Submitted by Customer Date'] == '') {
                    return '';
                }
                include_once 'common_natural_language.php';

                return seconds_to_string(
                    gmdate(
                        'U', strtotime(
                               $this->data['Order Submitted by Customer Date']
                           )
                    ) - gmdate(
                        'U', strtotime($this->data['Order Created Date'])
                    )
                );
                break;
            case('Send to Warehouse Interval'):
                if ($this->data['Order Submitted by Customer Date'] == '' or $this->data['Order Send to Warehouse Date'] == '') {
                    return '';
                }
                include_once 'common_natural_language.php';

                return seconds_to_string(
                    gmdate(
                        'U', strtotime($this->data['Order Send to Warehouse Date'])
                    ) - gmdate(
                        'U', strtotime(
                               $this->data['Order Submitted by Customer Date']
                           )
                    )
                );
                break;
            case('Packed Done Interval'):
                if ($this->data['Order Send to Warehouse Date'] == '' or $this->data['Order Packed Done Date'] == '') {
                    return '';
                }
                include_once 'common_natural_language.php';

                return seconds_to_string(
                    gmdate(
                        'U', strtotime($this->data['Order Packed Done Date'])
                    ) - gmdate(
                        'U', strtotime($this->data['Order Send to Warehouse Date'])
                    )
                );
                break;
            case('Dispatched Interval'):
                if ($this->data['Order Packed Done Date'] == '' or $this->data['Order Dispatched Date'] == '') {
                    return '';
                }
                include_once 'common_natural_language.php';

                return seconds_to_string(
                    gmdate('U', strtotime($this->data['Order Dispatched Date'])) - gmdate(
                        'U', strtotime($this->data['Order Packed Done Date'])
                    )
                );
                break;

            case ('Order Main Ship To Key') :
                $sql = sprintf(
                    "SELECT `Ship To Key`,count(*) AS  num FROM `Order Transaction Fact` WHERE `Order Key`=%d GROUP BY `Ship To Key` ORDER BY num DESC LIMIT 1", $this->id
                );
                $res = mysql_query($sql);
                if ($row2 = mysql_fetch_array($res, MYSQL_ASSOC)) {
                    return $row2 ['Ship To Key'];
                } else {
                    return '';
                }

                break;
            case ('Order Main Billing To Key') :
                $sql = sprintf(
                    "SELECT `Billing To Key`,count(*) AS  num FROM `Order Transaction Fact` WHERE `Order Key`=%d GROUP BY `Billing To Key` ORDER BY num DESC LIMIT 1", $this->id
                );
                $res = mysql_query($sql);
                if ($row2 = mysql_fetch_array($res, MYSQL_ASSOC)) {
                    return $row2 ['Billing To Key'];
                } else {
                    return '';
                }

                break;


            case ('Weight'):

                include_once 'utils/natural_language.php';

                if ($this->data['Order Current Dispatch State'] == 'Dispatched') {
                    if ($this->data['Order Weight'] == '') {
                        return "&#8494;".weight(
                                $this->data['Order Dispatched Estimated Weight']
                            );
                    } else {
                        return weight($this->data['Order Weight']);
                    }
                } else {
                    return "&#8494;".weight(
                            $this->data['Order Estimated Weight']
                        );
                }
                break;


            case ('State'):
                //'In Process by Customer','In Process','Submitted by Customer','Ready to Pick','Picking & Packing','Ready to Ship','Dispatched','Unknown','Packing','Packed','Cancelled','Suspended'  case('Current Dispatch State'):
                switch ($this->data['Order Current Dispatch State']) {
                    case 'In Process':
                        return _('In Basket');
                        break;

                    case 'Submitted by Customer':
                        return _('Submitted');
                        break;

                    case 'In Warehouse':
                        return _('In warehouse');
                        break;


                    case 'Packed Done':
                        return _('Packed & Checked');
                        break;
                    case 'Ready to Ship':
                        return _('Ready to Ship');
                        break;
                    case 'Dispatched':
                        return _('Dispatched');
                        break;


                        break;
                    case 'Cancelled':
                        return _('Cancelled');
                        break;
                    case 'Suspended':
                        return _('Suspended');
                        break;

                    default:
                        return $this->data['Order Current Dispatch State'];
                        break;
                }

                break;

            case 'Number Items':
            case 'Number Items Out of Stock':
            case 'Number Items Returned':
            case 'Number Items with Deals':

                return number($this->data['Order '.$key]);
                break;


        }
        $_key = ucwords($key);
        if (array_key_exists($_key, $this->data)) {
            return $this->data[$_key];
        }

        if (array_key_exists('Order '.$key, $this->data)) {
            return $this->data['Order '.$key];
        }

        return false;
    }


    function update_state($value, $options = '', $metadata = array()) {
        $date = gmdate('Y-m-d H:i:s');


        $old_value        = $this->get('Order State');
        $operations       = array();
        $deliveries_xhtml = '';
        $number_deliveries=0;

        if ($old_value != $value) {

            switch ($value) {


                case 'Cancelled':


                    $this->updated = $this->cancel();


                    break;

                case 'Submitted by Customer':


                    if (!($this->data['Order Current Dispatch State'] == 'In Process' or $this->data['Order Current Dispatch State'] == 'Waiting for Payment Confirmation')) {
                        $this->error = true;
                        $this->msg   = 'Order is not in process: :(  '.$this->data['Order Current Dispatch State'];

                        return;

                    }


                    $this->update_field('Order Current Dispatch State', $value, 'no_history');
                    $this->update_field('Order Submitted by Customer Date', $date, 'no_history');
                    $this->update_field('Order Date', $date, 'no_history');
                    $this->update_field('Order Class', 'InProcess', 'no_history');


                    $history_data = array(
                        'History Abstract' => _('Order submited'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);

                    $operations=array('send_to_warehouse');


                    break;
                case 'In Warehouse':


                    $warehouse_key = 1;

                    include_once('class.DeliveryNote.php');


                    if (!($this->data['Order Current Dispatch State'] == 'In Process' or $this->data['Order Current Dispatch State'] == 'Submitted by Customer')) {
                        $this->error = true;
                        $this->msg   = 'Order is not in process';

                        return false;

                    }


                    if ($this->data['Order For Collection'] == 'Yes') {
                        $dispatch_method = 'Collection';
                    } else {
                        $dispatch_method = 'Dispatch';
                    }

                    $store = get_object('Store', $this->data['Order Store Key']);

                    $data_dn                                              = array(
                        'Delivery Note Warehouse Key' => $warehouse_key,
                        'Delivery Note Date Created'  => $date,
                        'Delivery Note Date'          => $date,
                        'Delivery Note Order Key'     => $this->id,
                        'Delivery Note Store Key'     => $this->data['Order Store Key'],

                        'Delivery Note Order Date Placed'            => $this->data['Order Date'],
                        'Delivery Note ID'                           => $this->data['Order Public ID'],
                        'Delivery Note File As'                      => $this->data['Order File As'],
                        'Delivery Note Type'                         => $this->data['Order Type'],
                        'Delivery Note Dispatch Method'              => $dispatch_method,
                        'Delivery Note Title'                        => '',
                        'Delivery Note Customer Key'                 => $this->data['Order Customer Key'],
                        'Delivery Note Metadata'                     => $this->data['Order Original Metadata'],
                        'Delivery Note Customer Name'                => $this->data['Order Customer Name'],
                        'Delivery Note Customer Contact Name'        => $this->data['Order Customer Contact Name'],
                        'Delivery Note Telephone'                    => $this->data['Order Telephone'],
                        'Delivery Note Email'                        => $this->data['Order Email'],
                        'Delivery Note Address Recipient'            => $this->data['Order Delivery Address Recipient'],
                        'Delivery Note Address Organization'         => $this->data['Order Delivery Address Organization'],
                        'Delivery Note Address Line 1'               => $this->data['Order Delivery Address Line 1'],
                        'Delivery Note Address Line 2'               => $this->data['Order Delivery Address Line 2'],
                        'Delivery Note Address Sorting Code'         => $this->data['Order Delivery Address Sorting Code'],
                        'Delivery Note Address Postal Code'          => $this->data['Order Delivery Address Postal Code'],
                        'Delivery Note Address Dependent Locality'   => $this->data['Order Delivery Address Dependent Locality'],
                        'Delivery Note Address Locality'             => $this->data['Order Delivery Address Locality'],
                        'Delivery Note Address Administrative Area'  => $this->data['Order Delivery Address Administrative Area'],
                        'Delivery Note Address Country 2 Alpha Code' => $this->data['Order Delivery Address Country 2 Alpha Code'],
                        'Delivery Note Address Checksum'             => $this->data['Order Delivery Address Checksum'],
                        'Delivery Note Address Formatted'            => $this->data['Order Delivery Address Formatted'],
                        'Delivery Note Address Postal Label'         => $this->data['Order Delivery Address Postal Label'],
                        'Delivery Note Show in Warehouse Orders'     => $store->get('Store Show in Warehouse Orders')
                    );
                    $this->data['Delivery Note Show in Warehouse Orders'] = $store->data['Store Show in Warehouse Orders'];


                    $delivery_note = new DeliveryNote('create', $data_dn, $this);


                    include 'utils/new_fork.php';
                    $account = get_object('Account', 1);
                    new_housekeeping_fork(
                        'au_housekeeping', array(
                        'type'              => 'delivery_note_created',
                        'delivery_note_key' => $delivery_note->id,
                    ), $account->get('Account Code')
                    );


                    if ($this->get('Order Current Dispatch State') == 'In Process') {

                        $this->update_field('Order Submitted by Customer Date', $date);

                    }
                    $this->update_field('Order Class', 'InProcess', 'no_history');
                    $this->update_field('Order Current Dispatch State', $value, 'no_history');
                    $this->update_field('Order Send to Warehouse Date', $date, 'no_history');
                    $this->update_field('Order Date', $date, 'no_history');


                    $history_data = array(
                        'History Abstract' => _('Order send to warehouse'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->table_name, $this->id);


                    foreach ($this->get_deliveries('objects') as $dn) {
                        $number_deliveries++;
                        $deliveries_xhtml = sprintf(
                            ' <div class="node"  id="delivery_node_%d"><span class="node_label"><i class="fa fa-truck fa-flip-horizontal fa-fw" aria-hidden="true"></i> 
                               <span class="link" onClick="change_view(\'%s\')">%s</span>(<span class="Delivery_Note_State">%s</span>)</span></div>', $dn->id,
                            'delivery_notes/'.$dn->get('Delivery Note Store Key').'/'.$dn->id, $dn->get('ID'), $dn->get('Abbreviated State')

                        );

                    }


                    break;
                default:
                    exit('unknown state:::'.$value);
                    break;
            }


        }

        $this->update_metadata = array(
            'class_html'       => array(
                'Order_State'                  => $this->get('State'),
                'Order_Submitted_Date'         => '&nbsp;'.$this->get('Submitted by Customer Date'),
                'Order_Send_to_Warehouse_Date' => '&nbsp;'.$this->get('Send to Warehouse Date'),


            ),
            'operations'       => $operations,
            'state_index'      => $this->get('State Index'),
            'deliveries_xhtml' => $deliveries_xhtml,
            'number_deliveries' => $number_deliveries
        );


    }

    function cancel($note = '', $date = false, $force = false, $by_customer = false) {


        $store = get_object('Store', $this->get('Order Store Key'));

        $cancelled = false;
        if (preg_match(
            '/Dispatched/', $this->data['Order Current Dispatch State']
        )) {
            $this->msg = _(
                'Order can not be cancelled, because has already been dispatched'
            );

        }
        if (preg_match(
            '/Cancelled/', $this->data['Order Current Dispatch State']
        )) {
            $this->msg = _('Order is already cancelled');

        } else {

            $current_amount_in_customer_account_payments = 0;
            $sql                                         = sprintf(
                "SELECT B.`Payment Key`,`Amount`,`Payment Transaction Status` FROM `Order Payment Bridge` B LEFT JOIN `Payment Dimension` P ON (P.`Payment Key`=B.`Payment Key`) WHERE `Is Account Payment`='Yes' AND `Order Key`=%d ",
                $this->id

            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $current_amount_in_customer_account_payments += $row['Amount'];

                    if ($row['Payment Transaction Status'] == 'Pending') {
                        $sql = sprintf(
                            "DELETE  FROM `Order Payment Bridge` WHERE `Payment Key`=%d ", $row['Payment Key']

                        );
                        mysql_query($sql);

                        $sql = sprintf(
                            "DELETE  FROM `Payment Dimension` WHERE `Payment Key`=%d ", $row['Payment Key']

                        );
                        mysql_query($sql);

                    } else {

                        $payment        = new Payment($row['Payment Key']);
                        $data_to_update = array(

                            'Payment Completed Date'          => '',
                            'Payment Last Updated Date'       => gmdate(
                                'Y-m-d H:i:s'
                            ),
                            'Payment Cancelled Date'          => gmdate(
                                'Y-m-d H:i:s'
                            ),
                            'Payment Transaction Status'      => 'Cancelled',
                            'Payment Transaction Status Info' => _(
                                'Cancelled by user'
                            ),


                        );
                        $payment->update($data_to_update);


                    }
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            if ($by_customer) {
                $state = 'Cancelled by Customer';

            } else {
                $state = 'Cancelled';
            }

            if (!$date) {
                $date = gmdate('Y-m-d H:i:s');
            }
            $this->data['Order Cancelled Date'] = $date;

            $this->data['Order Cancel Note'] = $note;

            $this->data['Order Current Payment State'] = 'No Applicable';


            $this->data['Order Current Dispatch State'] = $state;

            $this->data['Order Current XHTML Dispatch State']              = _(
                'Cancelled'
            );
            $this->data['Order Current XHTML Payment State']               = _(
                'Order cancelled'
            );
            $this->data['Order XHTML Invoices']                            = '';
            $this->data['Order XHTML Delivery Notes']                      = '';
            $this->data['Order Invoiced Balance Total Amount']             = 0;
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

            $sql = sprintf(
                "UPDATE `Order Dimension` SET  `Order Cancelled Date`=%s, `Order Current Payment State`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s,`Order Current XHTML Payment State`=%s,
				`Order XHTML Invoices`='',`Order XHTML Delivery Notes`=''
				,`Order Invoiced Balance Net Amount`=0,`Order Invoiced Balance Tax Amount`=0,`Order Invoiced Balance Total Amount`=0 ,`Order Invoiced Outstanding Balance Net Amount`=0,`Order Invoiced Outstanding Balance Tax Amount`=0,`Order Invoiced Outstanding Balance Total Amount`=0,`Order Invoiced Profit Amount`=0,`Order Cancel Note`=%s
				,`Order Balance Net Amount`=0,`Order Balance tax Amount`=0,`Order Balance Total Amount`=0,`Order To Pay Amount`=%.2f
				WHERE `Order Key`=%d"//     ,$no_shipped
                , prepare_mysql($this->data['Order Cancelled Date']), prepare_mysql($this->data['Order Current Payment State']), prepare_mysql($this->data['Order Current Dispatch State']),
                prepare_mysql(
                    $this->data['Order Current XHTML Dispatch State']
                ), prepare_mysql($this->data['Order Current XHTML Payment State']), prepare_mysql($this->data['Order Cancel Note']), $this->data['Order To Pay Amount'], $this->id
            );

            $this->db->exec($sql);


            $this->update(array('Order Class' => 'Archived'), 'no_history');


            $sql = sprintf(
                "UPDATE `Order Transaction Fact` SET  `Delivery Note Key`=NULL,  `Delivery Note ID`=NULL,`Invoice Key`=NULL, `Invoice Public ID`=NULL,`Picker Key`=NULL,`Picker Key`=NULL, `Consolidated`='Yes',`Current Dispatching State`=%s WHERE `Order Key`=%d ",
                prepare_mysql($state), $this->id
            );
            $this->db->exec($sql);

            $sql = sprintf(
                "UPDATE `Order Transaction Fact` SET  `Picking Factor`=0,  `Picking Factor`=0,`Picked Quantity`=0, `Estimated Dispatched Weight`=0,`Delivery Note Quantity`=0,`Shipped Quantity`=0, `No Shipped Due Out of Stock`=0,`No Shipped Due No Authorized`=0,`No Shipped Due Not Found`=0,`No Shipped Due Other`=0,`Order Out of Stock Lost Amount`=0,`Invoice Quantity`=0 WHERE `Order Key`=%d ",

                $this->id
            );
            $this->db->exec($sql);


            $sql = sprintf(
                "UPDATE `Order No Product Transaction Fact` SET `Delivery Note Date`=NULL,`Delivery Note Key`=NULL,`State`=%s ,`Consolidated`='Yes' WHERE `Order Key`=%d ", prepare_mysql($state),
                $this->id
            );
            $this->db->exec($sql);


            foreach ($this->get_delivery_notes_objects() as $dn) {
                $dn->cancel($note, $date, $force);

                $sql = sprintf(
                    "DELETE FROM  `Order Delivery Note Bridge` WHERE `Order Key`=%d AND `Delivery Note Key`=%d", $this->id, $dn->id
                );
                $this->db->exec($sql);
            }


            /*


            if (!isset($_SESSION ['lang'])) {
                $lang = 0;
            } else {
                $lang = $_SESSION ['lang'];
            }

            switch ($lang) {
                default :
                    $note = sprintf(
                        'Order <a href="order.php?id=%d">%s</a> (Cancelled)', $this->data['Order Key'], $this->data['Order Public ID']
                    );
                    if ($this->editor['Author Alias'] != '' and $this->editor['Author Key']) {
                        $details = sprintf(
                            _('%s cancel (%s) order %s'),

                            sprintf(
                                '<a href="staff.php?id=%d">%s</a>', $this->editor['Author Key'], $this->editor['Author Alias']
                            ),

                            sprintf(
                                '<a href="customer.php?id=%d">%s</a>', $this->data['Order Customer Key'], $this->data['Order Customer Name']
                            ), sprintf(
                                '<a href="order.php?id=%d">%s</a>', $this->data['Order Key'], $this->data['Order Public ID']
                            )
                        );
                    } elseif ($this->editor['Author Alias'] == 'System Cron' and !$this->editor['Author Key']) {
                        $details = sprintf(
                            _('A cron job cancel (%s) order %s'), sprintf(
                            '<a href="customer.php?id=%d">%s</a>', $this->data['Order Customer Key'], $this->data['Order Customer Name']
                        ), sprintf(
                                '<a href="order.php?id=%d">%s</a>', $this->data['Order Key'], $this->data['Order Public ID']
                            )
                        );

                    } else {
                        $details = sprintf(
                            _('Someone cancel (%s) order %s'), sprintf(
                            '<a href="customer.php?id=%d">%s</a>', $this->data['Order Customer Key'], $this->data['Order Customer Name']
                        ), sprintf(
                                '<a href="order.php?id=%d">%s</a>', $this->data['Order Key'], $this->data['Order Public ID']
                            )
                        );
                    }


                    if ($this->data['Order Cancel Note'] != '') {
                        $details .= '<div> Note: '.$this->data['Order Cancel Note'].'</div>';
                    }


            }

            if ($this->editor['Author Alias'] == 'System Cron' and !$this->editor['Author Key']) {
                $subject     = 'System';
                $subject_key = 0;
            } else {
                $subject     = 'Staff';
                $subject_key = $this->editor['Author Key'];

            }

            $history_data = array(
                'Date'              => $this->data['Order Cancelled Date'],
                'Subject'           => $subject,
                'Subject Key'       => $subject_key,
                'Direct Object'     => 'Order',
                'Direct Object Key' => $this->data['Order Key'],
                'History Details'   => $details,
                'History Abstract'  => $note,
                'Metadata'          => 'Cancelled'

            );


            $history_key = $this->add_subject_history($history_data);






            */
            $customer         = new Customer($this->data['Order Customer Key']);
            $customer->editor = $this->editor;
            //$customer->add_history_order_cancelled($history_key);
            $customer->update_orders();

            $customer->update(
                array(
                    'Customer Account Balance' => round(
                        $customer->data['Customer Account Balance'] + $current_amount_in_customer_account_payments, 2
                    )
                ), 'no_history'
            );


            $store->update_orders();

            $this->update_deals_usage();
            $cancelled = true;


        }


        return $cancelled;

    }


    function update_deals_usage() {

        include_once 'class.DealCampaign.php';
        include_once 'class.DealComponent.php';


        $deals     = array();
        $campaigns = array();
        $sql       = sprintf(
            "SELECT `Deal Component Key`,`Deal Key`,`Deal Campaign Key` FROM  `Order Deal Bridge` WHERE `Order Key`=%d", $this->id
        );
        // exit("$sql\n");


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $component = new DealComponent($row['Deal Component Key']);
                $component->update_usage();
                $deals[$row['Deal Key']]              = $row['Deal Key'];
                $campaigns[$row['Deal Campaign Key']] = $row['Deal Campaign Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        foreach ($deals as $deal_key) {
            $deal = new Deal($deal_key);
            $deal->update_usage();
        }

        foreach ($campaigns as $campaign_key) {
            $campaign = new DealCampaign($campaign_key);
            $campaign->update_usage();
        }

    }

    function get_deliveries($scope = 'keys') {


        $deliveries = array();
        $sql        = sprintf(
            "SELECT `Delivery Note Key` FROM `Delivery Note Dimension` WHERE `Delivery Note Order Key`=%d  ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Delivery Note Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {

                    $deliveries[$row['Delivery Note Key']] = get_object('DeliveryNote', $row['Delivery Note Key']);

                } else {
                    $deliveries[$row['Delivery Note Key']] = $row['Delivery Note Key'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $deliveries;

    }

    function auto_account_payments($value, $options = '') {

        $this->update_field(
            'Order Apply Auto Customer Account Payment', $value, $options
        );


        if ($value == 'Yes') {
          //  $this->apply_payment_from_customer_account();
        } else {


        }

    }

    function send_post_action_to_warehouse($date = false, $type = false, $metadata = '') {
        if (!$date) {
            $date = gmdate('Y-m-d H:i:s');
        }

        if (!$this->data['Order Current Dispatch State'] == 'Dispatched') {
            $this->error = true;
            $this->msg   = 'Order is not already dispatched';

            return;

        }
        if (!$type) {
            $type = 'Replacement & Shortages';
        }


        $type_formatted = $type;
        $title          = "Delivery Note for $type of ".$this->data['Order Type'].' <a href="order.php?id='.$this->id.'">'.$this->data['Order Public ID'].'</a>';

        if ($this->data['Order For Collection'] == 'Yes') {
            $dispatch_method = 'Collection';
        } else {
            $dispatch_method = 'Dispatch';
        }

        if ($type == 'Replacement') {
            $suffix = 'rpl';
        } elseif ($type == 'Missing') {
            $suffix = 'sh';
            $type   = 'Shortages';
        } else {
            $suffix = 'r';
        }


        $dn_id = $this->get_replacement_public_id(
            $this->data['Order Public ID'].$suffix
        );


        $data_dn = array(
            'Delivery Note Date Created'    => $date,
            'Delivery Note ID'              => $dn_id,
            'Delivery Note File As'         => $dn_id,
            'Delivery Note Type'            => $type,
            'Delivery Note Title'           => $title,
            'Delivery Note Dispatch Method' => $dispatch_method,
            'Delivery Note Metadata'        => $metadata,
            'Delivery Note Customer Key'    => $this->data['Order Customer Key']

        );


        $dn = new DeliveryNote('create', $data_dn, $this);
        $dn->create_post_order_inventory_transaction_fact($this->id, $date);

        //TODO!!!
        //$this->update_post_dispatch_state();

        $this->update_full_search();

        $customer = new Customer($this->data['Order Customer Key']);
        $customer->add_history_post_order_in_warehouse($dn, $type);

        return $dn;
    }

    function get_replacement_public_id($dn_id, $suffix_counter = '') {
        $sql = sprintf(
            "SELECT `Delivery Note ID` FROM `Delivery Note Dimension` WHERE `Delivery Note Store Key`=%d AND `Delivery Note ID`=%s ", $this->data['Order Store Key'],
            prepare_mysql($dn_id.$suffix_counter)
        );
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            if ($suffix_counter > 100) {
                return $dn_id.$suffix_counter;
            }

            if (!$suffix_counter) {
                $suffix_counter = 2;
            } else {
                $suffix_counter++;
            }

            return $this->get_replacement_public_id($dn_id, $suffix_counter);

        } else {
            return $dn_id.$suffix_counter;
        }

    }

    function update_full_search() {

        $first_full_search  = $this->data['Order Public ID'].' '.$this->data['Order Customer Name'].' '.strftime(
                "%d %b %B %Y", strtotime($this->data['Order Date'])
            );
        $second_full_search = strip_tags(
                preg_replace(
                    '/\<br\/\>/', ' ', $this->data['Order XHTML Ship Tos']
                )
            ).' '.$this->data['Order Customer Contact Name'];
        $img                = '';

        $amount = '';
        if ($this->data['Order Current Payment State'] == 'Waiting Payment' or $this->data['Order Current Payment State'] == 'Partially Paid') {
            $amount = ' '.money(
                    $this->data['Order Total Amount'], $this->data['Order Currency']
                );
        } elseif ($this->data['Order Current Payment State'] == 'Paid' or $this->data['Order Current Payment State'] == 'Payment Refunded') {
            $amount = ' '.money(
                    $this->data['Order Invoiced Balance Total Amount'], $this->data['Order Currency']
                );
        }

        $show_description = $this->data['Order Customer Name'].' ('.strftime(
                "%e %b %Y", strtotime($this->data['Order Date'])
            ).') '.$this->data['Order Current XHTML Payment State'].$amount;

        $description1 = '<b><a href="order.php?id='.$this->id.'">'.$this->data['Order Public ID'].'</a></b>';
        $description  = '<table ><tr style="border:none;"><td  class="col1"'.$description1.'</td><td class="col2">'.$show_description.'</td></tr></table>';


        $sql = sprintf(
            "INSERT INTO `Search Full Text Dimension` (`Store Key`,`Subject`,`Subject Key`,`First Search Full Text`,`Second Search Full Text`,`Search Result Name`,`Search Result Description`,`Search Result Image`) VALUES  (%s,'Order',%d,%s,%s,%s,%s,%s) ON DUPLICATE KEY
		UPDATE `First Search Full Text`=%s ,`Second Search Full Text`=%s ,`Search Result Name`=%s,`Search Result Description`=%s,`Search Result Image`=%s", $this->data['Order Store Key'], $this->id,
            prepare_mysql($first_full_search), prepare_mysql($second_full_search, false), prepare_mysql($this->data['Order Public ID'], false), prepare_mysql($description, false),
            prepare_mysql($img, false), prepare_mysql($first_full_search), prepare_mysql($second_full_search, false), prepare_mysql($this->data['Order Public ID'], false),
            prepare_mysql($description, false)


            , prepare_mysql($img, false)
        );
        $this->db->exec($sql);


    }

    function cancel_by_customer($note) {
        $this->cancel($note, false, false, $by_customer = true);
    }

    function undo_cancel() {


        if (!preg_match(
            '/Cancelled/', $this->data['Order Current Dispatch State']
        )) {
            $this->msg   = _('Order is not cancelled');
            $this->error = true;

            return;
        }


        $state = 'In Process';

        $date                               = gmdate('Y-m-d H:i:s');
        $this->data['Order Cancelled Date'] = '';

        $this->data['Order Cancel Note'] = '';

        $this->data['Order Current Payment State'] = 'No Applicable';


        $this->data['Order Current Dispatch State'] = $state;

        $this->data['Order Current XHTML Dispatch State']              = _(
            'In Process'
        );
        $this->data['Order Current XHTML Payment State']               = '';
        $this->data['Order XHTML Invoices']                            = '';
        $this->data['Order XHTML Delivery Notes']                      = '';
        $this->data['Order Invoiced Balance Total Amount']             = 0;
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

        $sql = sprintf(
            "UPDATE `Order Dimension` SET    `Order Cancelled Date`=%s, `Order Current Payment State`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s,`Order Current XHTML Payment State`=%s,
		`Order XHTML Invoices`='',`Order XHTML Delivery Notes`=''
		,`Order Invoiced Balance Net Amount`=0,`Order Invoiced Balance Tax Amount`=0,`Order Invoiced Balance Total Amount`=0 ,`Order Invoiced Outstanding Balance Net Amount`=0,`Order Invoiced Outstanding Balance Tax Amount`=0,`Order Invoiced Outstanding Balance Total Amount`=0,`Order Invoiced Profit Amount`=0,`Order Cancel Note`=%s
		,`Order Balance Net Amount`=0,`Order Balance tax Amount`=0,`Order Balance Total Amount`=0,`Order To Pay Amount`=%.2f
		WHERE `Order Key`=%d"//     ,$no_shipped
            , prepare_mysql($this->data['Order Cancelled Date']), prepare_mysql($this->data['Order Current Payment State']), prepare_mysql($this->data['Order Current Dispatch State']),
            prepare_mysql($this->data['Order Current XHTML Dispatch State']), prepare_mysql($this->data['Order Current XHTML Payment State']), prepare_mysql($this->data['Order Cancel Note']),
            $this->data['Order To Pay Amount'], $this->id
        );


        $this->db->exec($sql);


        $sql = sprintf(
            "UPDATE `Order Transaction Fact` SET `Delivery Note Key`=NULL,  `Delivery Note ID`=NULL,`Invoice Key`=NULL, `Invoice Public ID`=NULL,`Picker Key`=NULL,`Picker Key`=NULL, `Consolidated`='No',`Current Dispatching State`=%s WHERE `Order Key`=%d ",
            prepare_mysql($state),

            $this->id
        );

        //print $sql;

        $this->db->exec($sql);


        $sql = sprintf(
            "UPDATE `Order No Product Transaction Fact` SET `State`=%s ,`Consolidated`='No' WHERE `Order Key`=%d ", prepare_mysql($state), $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "DELETE FROM `Order Transaction Deal Bridge` WHERE `Order Key` =%d ", $this->id
        );
        $this->db->exec($sql);


        $this->update_number_products();
        $this->update_insurance();

        $this->update_discounts_items();
        $this->update_totals();


        $this->update_shipping(false, false);
        $this->update_charges(false, false);
        $this->update_discounts_no_items();


        $this->update_deal_bridge();

        $this->update_deals_usage();

        $this->update_totals();

        $this->update_number_products();

        //$this->apply_payment_from_customer_account();


        $customer = new Customer($this->data['Order Customer Key']);
        $customer->update_orders();

        $store = new Store($this->data['Order Store Key']);
        $store->update_orders();

        $this->update_deals_usage();
        //$this->cancelled=true;

    }

    function update_number_products() {
        $this->data['Order Number Products'] = $this->get_number_products();
        $sql                                 = sprintf(
            "UPDATE `Order Dimension` SET `Order Number Products`=%d WHERE `Order Key`=%d", $this->data['Order Number Products'], $this->id
        );
        $this->db->exec($sql);
    }

    function get_number_products() {
        $sql = sprintf(
            "SELECT count(*) AS num FROM `Order Transaction Fact` WHERE `Order Key`=%d  ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number = ($row['num'] == '' ? 0 : $row['num']);

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $number;
    }

    function update_insurance($dn_key = false) {
        $valid_insurances = $this->get_insurances($dn_key);

        $sql = sprintf(
            "SELECT `Transaction Type Key`,`Order No Product Transaction Fact Key`  FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d  AND `Transaction Type`='Insurance' ", $this->id

        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if (!array_key_exists($row['Transaction Type Key'], $valid_insurances)) {

                    $sql = sprintf(
                        "DELETE FROM `Order No Product Transaction Fact` WHERE `Order No Product Transaction Fact Key`=%d ", $row['Order No Product Transaction Fact Key']
                    );
                    $this->db->exec($sql);
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update_totals();
        $this->apply_payment_from_customer_account();

    }

    function get_insurances($dn_key = false) {
        $insurances = array();
        if ($this->data['Order Number Items'] == 0) {

            return $insurances;
        }


        $sql = sprintf(
            "SELECT * FROM `Insurance Dimension` WHERE `Insurance Trigger`='Order' AND (`Insurance Trigger Key`=%d  OR `Insurance Trigger Key` IS NULL) AND `Insurance Store Key`=%d", $this->id,
            $this->data['Order Store Key']
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $apply_insurance = false;

                $order_amount = $this->data[$row['Insurance Terms Type']];


                if ($dn_key) {
                    switch ($row['Insurance Terms Type']) {

                        case 'Order Items Net Amount':

                            $sql = sprintf(
                                "SELECT sum(`Order Transaction Net Amount`*(`Delivery Note Quantity`/`Order Quantity`)) AS amount FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Delivery Note Key`=%d AND `Order Quantity`!=0",
                                $this->id, $dn_key
                            );
                            $res = mysql_query($sql);
                            if ($row2 = mysql_fetch_assoc($res)) {
                                $order_amount = $row2['amount'];
                            } else {
                                $order_amount = 0;
                            }
                            break;


                        case 'Order Items Gross Amount':
                        default:
                            $sql = sprintf(
                                "SELECT sum(`Order Transaction Gross Amount`*(`Delivery Note Quantity`/`Order Quantity`)) AS amount FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Delivery Note Key`=%d AND `Order Quantity`!=0",
                                $this->id, $dn_key
                            );
                            $res = mysql_query($sql);
                            if ($row2 = mysql_fetch_assoc($res)) {
                                $order_amount = $row2['amount'];
                            } else {
                                $order_amount = 0;
                            }
                            break;
                    }
                }


                $terms_components = preg_split(
                    '/;/', $row['Insurance Terms Metadata']
                );
                $operator         = $terms_components[0];
                $amount           = $terms_components[1];

                //print_r($order_amount);


                switch ($operator) {
                    case('<'):
                        if ($order_amount < $amount) {
                            $apply_insurance = true;
                        }
                        break;
                    case('>'):
                        if ($order_amount > $amount) {
                            $apply_insurance = true;
                        }
                        break;
                    case('<='):
                        if ($order_amount <= $amount) {
                            $apply_insurance = true;
                        }
                        break;
                    case('>='):
                        if ($order_amount >= $amount) {
                            $apply_insurance = true;
                        }
                        break;
                }


                if ($row['Insurance Tax Category Code'] == '') {
                    $tax_category_code = $this->data['Order Tax Code'];
                    $tax_rate          = $this->data['Order Tax Rate'];
                } else {
                    $tax_category      = new TaxCategory(
                        $row['Insurance Tax Category Code']
                    );
                    $tax_category_code = $tax_category->data['Tax Category Code'];
                    $tax_rate          = $tax_category->data['Tax Category Rate'];

                }


                if ($row['Insurance Type'] == 'Amount') {
                    $charge_net_amount = $row['Insurance Metadata'];


                    $charge_tax_amount = $row['Insurance Metadata'] * $tax_rate;
                } else {

                    exit("still to do");
                }


                $sql  = sprintf(
                    "SELECT `Order No Product Transaction Fact Key`  FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d  AND `Transaction Type`='Insurance' AND `Transaction Type Key`=%d ",
                    $this->id, $row['Insurance Key']
                );
                $res2 = mysql_query($sql);
                if ($row2 = mysql_fetch_assoc($res2)) {
                    $onptf_key = $row2['Order No Product Transaction Fact Key'];
                } else {
                    $onptf_key = 0;
                }

                if ($apply_insurance) {
                    $insurances[$row['Insurance Key']] = array(
                        'Insurance Net Amount'                  => $charge_net_amount,
                        'Insurance Tax Amount'                  => $charge_tax_amount,
                        'Insurance Formatted Net Amount'        => money(
                            $this->exchange * $charge_net_amount, $this->currency_code
                        ),
                        'Insurance Formatted Tax Amount'        => money(
                            $this->exchange * $charge_tax_amount, $this->currency_code
                        ),
                        'Insurance Tax Code'                    => $tax_category_code,
                        'Insurance Key'                         => $row['Insurance Key'],
                        'Insurance Description'                 => $row['Insurance Name'],
                        'Order No Product Transaction Fact Key' => $onptf_key
                    );
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $insurances;

    }

    function activate($date = false) {


        if (!preg_match(
            '/Suspended/', $this->data['Order Current Dispatch State']
        )) {
            $this->msg = _('Order is not suspended');

        } else {

            if (!$date) {
                $date = gmdate('Y-m-d H:i:s');
            }
            $this->data['Order Suspended Date'] = $date;

            $this->data['Order Suspend Note'] = $note;

            $this->data['Order Current Payment State']                     = 'No Applicable';
            $this->data['Order Current Dispatch State']                    = 'Suspended';
            $this->data['Order Current XHTML Dispatch State']              = _(
                'Suspended'
            );
            $this->data['Order Current XHTML Payment State']               = _(
                'Order Suspended'
            );
            $this->data['Order XHTML Invoices']                            = '';
            $this->data['Order XHTML Delivery Notes']                      = '';
            $this->data['Order Invoiced Balance Total Amount']             = 0;
            $this->data['Order Invoiced Balance Net Amount']               = 0;
            $this->data['Order Invoiced Balance Tax Amount']               = 0;
            $this->data['Order Invoiced Outstanding Balance Total Amount'] = 0;
            $this->data['Order Invoiced Outstanding Balance Net Amount']   = 0;
            $this->data['Order Invoiced Outstanding Balance Tax Amount']   = 0;


            $sql = sprintf(
                "UPDATE `Order Dimension` SET `Order Suspended Date`=%s, `Order Current Payment State`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s,`Order Current XHTML Payment State`=%s,`Order XHTML Invoices`='',`Order XHTML Delivery Notes`='' ,`Order Invoiced Balance Net Amount`=0,`Order Invoiced Balance Tax Amount`=0,`Order Invoiced Balance Total Amount`=0 ,`Order Invoiced Outstanding Balance Net Amount`=0,`Order Invoiced Outstanding Balance Tax Amount`=0,`Order Invoiced Outstanding Balance Total Amount`=0,`Order Invoiced Profit Amount`=0,`Order Suspend Note`=%s  WHERE `Order Key`=%d",
                prepare_mysql($this->data['Order Suspended Date']), prepare_mysql($this->data['Order Current Payment State']), prepare_mysql($this->data['Order Current Dispatch State']),
                prepare_mysql(
                    $this->data['Order Current XHTML Dispatch State']
                ), prepare_mysql($this->data['Order Current XHTML Payment State']), prepare_mysql($this->data['Order Suspend Note'])

                , $this->id
            );
            $this->db->exec($sql);

            $sql = sprintf(
                "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Suspended',`Current Payment State`='No Applicable' WHERE `Order Key`=%d ", $this->id
            );
            $this->db->exec($sql);
            $sql = sprintf(
                "UPDATE `Order No Product Transaction Fact` SET `State`='Suspended'  WHERE `Order Key`=%d ", $this->id
            );
            $this->db->exec($sql);

            foreach ($this->get_delivery_notes_objects() as $dn) {
                $dn->suspend($note, $date);
            }

            $customer         = new Customer($this->data['Order Customer Key']);
            $customer->editor = $this->editor;
            $customer->add_history_order_activate($this);//<--- Not done yet
            $this->suspended = true;

            $history_data = array(
                'History Abstract' => _('Order activated'),
                'History Details'  => '',
            );
            $this->add_subject_history($history_data);

        }


    }

    function suspend($note = '', $date = false) {

        $this->suspended = false;
        if (preg_match(
            '/Dispatched/', $this->data['Order Current Dispatch State']
        )) {
            $this->msg = _(
                'Order can not be suspended, because has already been dispatched'
            );

        } elseif (preg_match(
            '/Suspended/', $this->data['Order Current Dispatch State']
        )) {
            $this->msg = _('Order is cancelled');

        } elseif (preg_match(
            '/Suspended/', $this->data['Order Current Dispatch State']
        )) {
            $this->msg = _('Order is already suspended');

        } else {

            if (!$date) {
                $date = gmdate('Y-m-d H:i:s');
            }
            $this->data['Order Suspended Date'] = $date;

            $this->data['Order Suspend Note'] = $note;

            $this->data['Order Current Payment State']                     = 'No Applicable';
            $this->data['Order Current Dispatch State']                    = 'Suspended';
            $this->data['Order Current XHTML Dispatch State']              = _(
                'Suspended'
            );
            $this->data['Order Current XHTML Payment State']               = _(
                'Order Suspended'
            );
            $this->data['Order XHTML Invoices']                            = '';
            $this->data['Order XHTML Delivery Notes']                      = '';
            $this->data['Order Invoiced Balance Total Amount']             = 0;
            $this->data['Order Invoiced Balance Net Amount']               = 0;
            $this->data['Order Invoiced Balance Tax Amount']               = 0;
            $this->data['Order Invoiced Outstanding Balance Total Amount'] = 0;
            $this->data['Order Invoiced Outstanding Balance Net Amount']   = 0;
            $this->data['Order Invoiced Outstanding Balance Tax Amount']   = 0;


            $sql = sprintf(
                "UPDATE `Order Dimension` SET `Order Suspended Date`=%s, `Order Current Payment State`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s,`Order Current XHTML Payment State`=%s,`Order XHTML Invoices`='',`Order XHTML Delivery Notes`='' ,`Order Invoiced Balance Net Amount`=0,`Order Invoiced Balance Tax Amount`=0,`Order Invoiced Balance Total Amount`=0 ,`Order Invoiced Outstanding Balance Net Amount`=0,`Order Invoiced Outstanding Balance Tax Amount`=0,`Order Invoiced Outstanding Balance Total Amount`=0,`Order Invoiced Profit Amount`=0,`Order Suspend Note`=%s  WHERE `Order Key`=%d",
                prepare_mysql($this->data['Order Suspended Date']), prepare_mysql($this->data['Order Current Payment State']), prepare_mysql($this->data['Order Current Dispatch State']),
                prepare_mysql(
                    $this->data['Order Current XHTML Dispatch State']
                ), prepare_mysql($this->data['Order Current XHTML Payment State']), prepare_mysql($this->data['Order Suspend Note'])

                , $this->id
            );
            $this->db->exec($sql);

            $sql = sprintf(
                "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Suspended',`Current Payment State`='No Applicable' WHERE `Order Key`=%d ", $this->id
            );
            $this->db->exec($sql);
            $sql = sprintf(
                "UPDATE `Order No Product Transaction Fact` SET `State`='Suspended'  WHERE `Order Key`=%d ", $this->id
            );
            $this->db->exec($sql);

            foreach ($this->get_delivery_notes_objects() as $dn) {
                $dn->suspend($note, $date);
            }

            $customer         = new Customer($this->data['Order Customer Key']);
            $customer->editor = $this->editor;
            $customer->add_history_order_suspended($this);
            $store = new Store($this->data['Order Store Key']);
            $store->update_orders();
            $this->suspended = true;
            $history_data    = array(
                'History Abstract' => _('Order suspended'),
                'History Details'  => '',
            );
            $this->add_subject_history($history_data);

        }


    }

    function create_invoice($date = false) {
        // intended to be used in services

        if (!$date) {
            $date = gmdate("Y-m-d H:i:s");
        }

        $tax_code   = 'UNK';
        $orders_ids = '';

        $tax_code = $this->data['Order Tax Code'];


        $delivery_note_keys = '';
        foreach ($this->get_delivery_notes_ids() as $dn_key) {

            $delivery_note_keys = $dn_key.',';

        }
        $delivery_note_keys = preg_replace('/\,$/', '', $delivery_note_keys);


        $store = new Store($this->data['Order Store Key']);
        if ($store->data['Store Next Invoice Public ID Method'] == 'Order ID') {
            $invoice_public_id = $this->data['Order Public ID'];
        } elseif ($store->data['Store Next Invoice Public ID Method'] == 'Invoice Public ID') {

            $sql = sprintf(
                "UPDATE `Store Dimension` SET `Store Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Store Invoice Last Invoice Public ID` + 1) WHERE `Store Key`=%d", $this->data['Order Store Key']
            );
            $this->db->exec($sql);
            $public_id = $this->db->lastInsertId();

            $invoice_public_id = sprintf(
                $store->data['Store Invoice Public ID Format'], $public_id
            );

        } else {

            $sql = sprintf(
                "UPDATE `Account Dimension` SET `Account Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Public ID` + 1) WHERE `Account Key`=1"
            );
            $this->db->exec($sql);
            $public_id = $this->db->lastInsertId();

            include_once 'class.Account.php';
            $account           = new Account();
            $invoice_public_id = sprintf(
                $account->data['Account Invoice Public ID Format'], $public_id
            );
        }


        $data_invoice = array(
            'Invoice Date'                          => $date,
            'Invoice Type'                          => 'Invoice',
            'Invoice Public ID'                     => $invoice_public_id,
            'Delivery Note Keys'                    => $delivery_note_keys,
            'Order Key'                             => $this->id,
            'Invoice Store Key'                     => $this->data['Order Store Key'],
            'Invoice Customer Key'                  => $this->data['Order Customer Key'],
            'Invoice Tax Code'                      => $tax_code,
            'Invoice Tax Shipping Code'             => $tax_code,
            'Invoice Tax Charges Code'              => $tax_code,
            'Invoice Sales Representative Keys'     => $this->get_sales_representative_keys(),
            'Invoice Metadata'                      => $this->data['Order Original Metadata'],
            'Invoice Billing To Key'                => $this->data['Order Billing To Key To Bill'],
            'Invoice Tax Number'                    => $this->data['Order Tax Number'],
            'Invoice Tax Number Valid'              => $this->data['Order Tax Number Valid'],
            'Invoice Tax Number Validation Date'    => $this->data['Order Tax Number Validation Date'],
            'Invoice Tax Number Associated Name'    => $this->data['Order Tax Number Associated Name'],
            'Invoice Tax Number Associated Address' => $this->data['Order Tax Number Associated Address'],
            'Invoice Net Amount Off'                => $this->data['Order Deal Amount Off']


        );


        $invoice = new Invoice ('create', $data_invoice);


        $this->update_totals();


        return $invoice;
    }

    function get_sales_representative_keys() {
        $sales_representative_keys = array();
        $sql                       = sprintf(
            "SELECT `Staff Key` FROM `Order Sales Representative Bridge` WHERE `Order Key`=%s", $this->id
        );
        $result = mysql_query($sql) or die('aa0 Query failed: '.mysql_error());
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $sales_representative_keys[] = $row['Staff Key'];
        }

        return $sales_representative_keys;
    }

    function no_payment_applicable() {


        $this->data['Order Current Payment State']  = 'No Applicable';
        $this->data['Order Current Dispatch State'] = 'Dispatched';

        $dn_txt = _('Dispatched');
        if ($this->data['Order Type'] == 'Order') {
            $dn_txt = _("No value order, Dispatched");
        }


        $sql = sprintf(
            "UPDATE `Order Dimension` SET `Order Current XHTML Payment State`=%s WHERE `Order Key`=%d", prepare_mysql($dn_txt), $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "UPDATE `Order Dimension` SET `Order Current Payment State`=%s ,`Order Current Dispatch State`=%s WHERE `Order Key`=%d", prepare_mysql($this->data['Order Current Payment State']),
            prepare_mysql($this->data['Order Current Dispatch State']), $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "UPDATE `Order Transaction Fact` SET `Consolidated`='Yes',`Current Payment State`=%s ,`Current Dispatching State`=%s WHERE `Order Key`=%d",
            prepare_mysql($this->data['Order Current Payment State']), prepare_mysql($this->data['Order Current Dispatch State']), $this->id
        );
        $this->db->exec($sql);

    }

    function formatted_net() {
        return money(
            $this->data['Order Total Net Amount'] - $this->data['Order Out of Stock Net Amount'] - $this->data['Order No Authorized Net Amount'] - $this->data['Order Not Found Net Amount']
            - $this->data['Order Not Due Other Net Amount'], $this->data['Order Currency']
        );
    }

    function formatted_tax() {
        return money(
            $this->data['Order Total Tax Amount'] - $this->data['Order Out of Stock Tax Amount'] - $this->data['Order No Authorized Tax Amount'] - $this->data['Order Not Found Tax Amount']
            - $this->data['Order Not Due Other Tax Amount'], $this->data['Order Currency']
        );

    }

    function formatted_total() {
        return money(
            $this->data['Order Total Net Amount'] + $this->data['Order Total Tax Amount'] - $this->data['Order Out of Stock Net Amount'] - $this->data['Order No Authorized Net Amount']
            - $this->data['Order Not Found Net Amount'] - $this->data['Order Not Due Other Net Amount'] - $this->data['Order Out of Stock Tax Amount'] - $this->data['Order No Authorized Tax Amount']
            - $this->data['Order Not Found Tax Amount'] - $this->data['Order Not Due Other Tax Amount'], $this->data['Order Currency']
        );

    }

    function get_invoices($scope = 'keys') {

        if ($scope == 'objects') {
            include_once 'class.Invoice.php';
        }


        $invoices = array();
        $sql      = sprintf(
            "SELECT `Invoice Key` FROM `Order Transaction Fact` WHERE `Order Key`=%d  GROUP BY `Invoice Key`", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Invoice Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {

                    $invoices[$row['Invoice Key']] = new Invoice(
                        $row['Invoice Key']
                    );

                } else {
                    $invoices[$row['Invoice Key']] = $row['Invoice Key'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $invoices;

    }


    function get_invoices_to_pay_abs_amount() {
        $invoices_to_pay_abs_amount = 0;
        $sql                        = sprintf(
            "SELECT sum(abs(`Invoice Outstanding Total Amount`)) AS amount FROM `Order Invoice Bridge` B LEFT JOIN `Invoice Dimension` I ON (I.`Invoice Key`=B.`Invoice Key`) WHERE `Order Key`=%d",
            $this->id
        );
        $res                        = mysql_query($sql);
        while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
            $invoices_to_pay_abs_amount = $row['amount'];
        }

        return $invoices_to_pay_abs_amount;
    }

    function get_number_invoices() {

        return count($this->get_invoices_ids());
    }


    function update_product_sales() {
        return;
        if ($this->skip_update_product_sales) {
            return;
        }


        $stores      = array();
        $family      = array();
        $departments = array();
        $sql         =
            "SELECT OTF.`Product Key` ,`Product Family Key`,`Product Store Key` FROM `Order Transaction Fact` OTF LEFT JOIN `Product Dimension` PD ON (PD.`Product Key`=OTF.`Product Key`)WHERE `Order Key`="
            .$this->data['Order Key']." GROUP BY OTF.`Product Key`";
        $result      = mysql_query($sql);
        //   print $sql;
        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $product = new Product($row['Product Key']);
            $product->update_sales();
            $family[$row['Product Family Key']] = true;
            $store[$row['Product Store Key']]   = true;
        }
        foreach ($family as $key => $val) {
            $family = new Family($key);
            $family->update_sales_data();
            $sql    = sprintf(
                "SELECT `Product Department Key`  FROM `Product Family Department Bridge` WHERE `Product Family Key`=%d", $key
            );
            $result = mysql_query($sql);
            while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $departments[$row['Product Department Key']] = true;
            }

        }
        foreach ($departments as $key => $val) {
            $department = new Department($key);
            $department->update_sales_data();
        }


        foreach ($store as $key => $val) {
            $store = new Store($key);
            $store->update_sales();
        }

    }

    function accept() {
        $this->data['Order Invoiced Balance Net Amount']   = $this->data['Order Items Net Amount'];
        $this->data['Order Invoiced Balance Tax Amount']   = $this->data['Order Items Tax Amount'];
        $this->data['Order Invoiced Balance Total Amount'] = $this->data['Order Items Total Amount'];

    }

    function update_invoices($args = '') {
        global $myconf;
        $sql = sprintf(
            "SELECT `Invoice Key` FROM `Order Transaction Fact` WHERE `Order Key`=%d GROUP BY `Invoice Key`", $this->id
        );

        $res            = mysql_query($sql);
        $this->invoices = array();
        while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
            if ($row['Invoice Key']) {
                $invoice                             = new Invoice(
                    $row['Invoice Key']
                );
                $this->invoices[$row['Invoice Key']] = $invoice;
            }

        }
        //update no normal fields
        $this->data['Order XHTML Invoices'] = '';
        foreach ($this->invoices as $invoice) {
            $this->data['Order XHTML Invoices'] .= sprintf(
                '<a href="invoice.php?id=%d">%s</a>, ', $invoice->data['Invoice Key'], $invoice->data['Invoice Public ID']
            );

        }
        $this->data['Order XHTML Invoices'] = _trim(
            preg_replace('/\, $/', '', $this->data['Order XHTML Invoices'])
        );
        //$where_dns=preg_replace('/\,$/',')',$where_dns);

        if (!preg_match('/no save/i', $args)) {
            $sql = sprintf(
                "UPDATE `Order Dimension`  SET `Order XHTML Invoices`=%s WHERE `Order Key`=%d", prepare_mysql($this->data['Order XHTML Invoices']), $this->id
            );

            $this->db->exec($sql);

        }

    }

    function update_estimated_weight() {

        $sql = sprintf(
            "SELECT `Order Transaction Fact Key`, `Product Package Weight`, P.`Product ID`,`Order Bonus Quantity`,`Order Quantity` FROM `Order Transaction Fact` OTF LEFT JOIN `Product Dimension` P ON (OTF.`Product ID`=P.`Product ID`)  WHERE `Order Key`=%d ",
            $this->id
        );

        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {

            if ($row['Product ID']) {
                $estimated_weight = $row['Product Package Weight'] * ($row['Order Bonus Quantity'] + $row['Order Quantity']);
                $sql              = sprintf(
                    "UPDATE `Order Transaction Fact` SET `Estimated Weight`=%f WHERE `Order Transaction Fact Key`=%d", $estimated_weight, $row['Order Transaction Fact Key']
                );
                $this->db->exec($sql);

            }

        }


        $this->update_totals();


        foreach ($this->get_delivery_notes_objects() as $dn) {
            $dn->update_item_totals();
        }

    }

    function update_post_dispatch_state() {


        //print "update_post_dispatch_state\n";

        $old_dispatch_state = $this->data['Order Current Post Dispatch State'];

        $xhtml_dispatch_state = '';

        $dispatch_state = 'NA';

        //

        $sql = sprintf(
            "SELECT `Delivery Note XHTML State`,`Delivery Note State`,DN.`Delivery Note Key`,DN.`Delivery Note ID`,`Delivery Note Fraction Picked`,`Delivery Note Assigned Picker Alias`,`Delivery Note Fraction Packed`,`Delivery Note Assigned Packer Alias` FROM `Order Post Transaction Dimension` B  LEFT JOIN `Delivery Note Dimension` DN  ON (DN.`Delivery Note Key`=B.`Delivery Note Key`) WHERE `Order Key`=%d GROUP BY B.`Delivery Note Key`  ORDER BY Field (`Delivery Note State`,  'Dispatched','Cancelled','Cancelled to Restock','Approved' ,'Packed Done' , 'Packed','Ready to be Picked','Picker Assigned','Packer Assigned','Picker & Packer Assigned','Picked','Picking' ,'Packing' ,'Picking & Packing') ",
            $this->id
        );

        $res            = mysql_query($sql);
        $delivery_notes = array();


        //print $sql;
        //exit;

        while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {


            //print_r($row);
            if ($row['Delivery Note Key']) {
                if ($row['Delivery Note State'] == 'Ready to be Picked') {
                    $dispatch_state = 'Ready to Pick';
                } elseif (in_array(
                    $row['Delivery Note State'], array(
                                                   'Picker & Packer Assigned',
                                                   'Picking & Packing',
                                                   'Packer Assigned',
                                                   'Ready to be Picked',
                                                   'Picker Assigned',
                                                   'Picking',
                                                   'Picked',
                                                   'Packing',
                                                   'Packed'
                                               )
                )) {
                    $dispatch_state = 'Picking & Packing';

                } elseif ($row['Delivery Note State'] == 'Packed Done') {
                    $dispatch_state = 'Packed Done';
                } elseif ($row['Delivery Note State'] == 'Approved') {
                    $dispatch_state = 'Ready to Ship';
                } elseif ($row['Delivery Note State'] == 'Dispatched') {
                    $dispatch_state = 'Dispatched';
                } else {
                    $dispatch_state = 'Unknown';
                }

                $status = $row['Delivery Note XHTML State'];


                //$xhtml_dispatch_state.=sprintf('<a href="dn.php?id=%d">%s</a> %s',$row['Delivery Note Key'],$row['Delivery Note ID'],$status);
            }

        }
        //$this->data['Order Current XHTML Dispatch State']=$xhtml_dispatch_state;


        //print $dispatch_state;


        $sql = sprintf(
            "UPDATE `Order Dimension` SET `Order Current XHTML Post Dispatch State`=%s WHERE `Order Key`=%d", prepare_mysql($xhtml_dispatch_state, false), $this->id
        );
        $this->db->exec($sql);


        $this->data['Order Current Post Dispatch State'] = $dispatch_state;

        if ($old_dispatch_state != $this->data['Order Current Dispatch State']) {

            $sql = sprintf(
                "UPDATE `Order Dimension` SET `Order Current Post Dispatch State`=%s  WHERE `Order Key`=%d", prepare_mysql($this->data['Order Current Post Dispatch State'])

                , $this->id
            );
            //print $sql;
            $this->db->exec($sql);
            //$this->update_customer_history();
            //$this->update_full_search();
        }


    }

    function set_order_as_dispatched($date) {

        // TODO dont set as dispatched until all the DN are dispatched (no inclide post transactions)

        $this->data['Order Current Dispatch State']       = 'Dispatched';
        $this->data['Order Current XHTML Dispatch State'] = _('Dispatched');

        $sql = sprintf(
            "UPDATE `Order Dimension` SET `Order Dispatched Date`=%s , `Order Current XHTML Dispatch State`=%s ,`Order Current Dispatch State`=%s WHERE `Order Key`=%d", prepare_mysql($date),
            prepare_mysql($this->data['Order Current XHTML Dispatch State']), prepare_mysql($this->data['Order Current Dispatch State']), $this->id
        );
        $this->db->exec($sql);

        $this->update_customer_history();
        $this->update_full_search();
        $customer = new Customer($this->data['Order Customer Key']);
        $customer->update_orders();

        $history_data = array(
            'History Abstract' => _('Order dispatched'),
            'History Details'  => '',
        );
        $this->add_subject_history($history_data);


    }

    function update_customer_history() {
        //print $this->data['Order Current Dispatch State']."\n";
        $customer = new Customer ($this->data['Order Customer Key']);
        switch ($this->data['Order Current Dispatch State']) {

            case ('Picking & Packing'):
            case('Ready to Pick'):
            case('Ready to Ship'):
            case('Dispatched'):
                $customer->update_history_order_in_warehouse($this);
                break;
            default:

                break;
        }


    }

    function set_order_as_completed($date) {

        // TODO dont set as dispatched until all the DN are dispatched (no inclide post transactions)

        $this->data['Order Current Dispatch State']       = 'Dispatched';
        $this->data['Order Current XHTML Dispatch State'] = _('Dispatched');

        $sql = sprintf(
            "UPDATE `Order Dimension` SET `Order Dispatched Date`=%s , `Order Current XHTML Dispatch State`=%s ,`Order Current Dispatch State`=%s WHERE `Order Key`=%d", prepare_mysql($date),
            prepare_mysql($this->data['Order Current XHTML Dispatch State']), prepare_mysql($this->data['Order Current Dispatch State']), $this->id
        );
        $this->db->exec($sql);
        //print "$sql\n";
        $this->update_customer_history();
        $this->update_full_search();

        $customer = new Customer($this->data['Order Customer Key']);
        $customer->update_orders();

    }

    function calculate_state($invoice_extra_info = '') {

        $payment_state  = '';
        $dispatch_state = '';
        switch ($this->data['Order Current Dispatch State']) {

            case 'In Process':
                $dispatch_state = _('In Process');
                break;
            case 'Submitted by Customer':
                $dispatch_state = _('Submitted by Customer');
                break;
            case 'Ready to Pick':
                $dispatch_state = _('Ready to Pick');
                break;
            case 'Picking & Packing':
                $dispatch_state = _('Picking & Packing');
                break;
            case 'Ready to Ship':
                $dispatch_state = _('Ready to Ship');
                break;
            case 'Dispatched':
                $dispatch_state = _('Dispatched');
                break;
            case 'Packing':
                $dispatch_state = _('Packing');
                break;
            case 'Packed':
                $dispatch_state = _('Packed');
                break;
            case 'Cancelled':
                $dispatch_state = _('Cancelled');
                break;
            case 'Suspended':
                $dispatch_state = _('Suspended');
                break;
            default:
                $dispatch_state = $this->data['Order Current Dispatch State'];
        }

        $state = $dispatch_state;


        return $state;
    }


    function has_products_without_parts() {
        $has_products_without_parts = false;

        $sql = sprintf(
            "SELECT count(*) AS products_with_out_parts	FROM `Order Transaction Fact` OTF  LEFT JOIN `Product History Dimension` PHD ON (PHD.`Product Key`=OTF.`Product Key`) LEFT JOIN `Product Dimension` P ON (PHD.`Product ID`=P.`Product ID`)  WHERE `Order Key`=%d AND `Product Number of Parts`=0  ",
            $this->id
        );
        $res = mysql_query($sql);

        if ($row = mysql_fetch_assoc($res)) {
            if ($row['products_with_out_parts'] > 0) {
                $has_products_without_parts = true;
            }
        }

        return $has_products_without_parts;
    }

    function update_charges_amount($charge_data) {

        //print_r($charge_data);

        if ($charge_data['Charge Net Amount'] != $this->data['Order Charges Net Amount']) {

            $this->data['Order Charges Net Amount'] = $charge_data['Charge Net Amount'];

            $sql = sprintf(
                'DELETE FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d AND `Transaction Type`="Charges" AND `Consolidated`="No"', $this->id
            );
            mysql_query($sql);
            // print "$sql\n";

            $total_charges_net = $charge_data['Charge Net Amount'];
            $total_charges_tax = $charge_data['Charge Tax Amount'];
            if ($charge_data['Charge Tax Amount'] != 0 or $charge_data['Charge Net Amount'] != 0) {
                $sql = sprintf(
                    "INSERT INTO `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,`Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,
					`Currency Code`,`Currency Exchange`,`Metadata`)  VALUES (%d,%s,%s,%d,%s,%.2f,%.2f,%s,%.2f,%s,%.2f,%s)  ", $this->id, prepare_mysql($this->data['Order Date']),
                    prepare_mysql('Charges'), $charge_data['Charge Key'], prepare_mysql($charge_data['Charge Description']), $charge_data['Charge Net Amount'], $charge_data['Charge Net Amount'],
                    prepare_mysql($this->data['Order Tax Code']), $charge_data['Charge Tax Amount'],

                    prepare_mysql($this->data['Order Currency']), $this->data['Order Currency Exchange'], prepare_mysql($this->data['Order Original Metadata'])
                );

                // print ("$sql\n");
                mysql_query($sql);
            }


            $this->data['Order Charges Net Amount'] = $total_charges_net;
            $this->data['Order Charges Tax Amount'] = $total_charges_tax;


            $sql = sprintf(
                "UPDATE `Order Dimension` SET `Order Charges Net Amount`=%.2f ,`Order Charges Tax Amount`=%.2f WHERE `Order Key`=%d", $this->data['Order Charges Net Amount'],
                $this->data['Order Charges Tax Amount'], $this->id
            );
            $this->db->exec($sql);
            //print "*a $sql\n";

            // exit;

            $this->updated   = true;
            $this->new_value = $this->data['Order Charges Net Amount'];

            $this->update_totals();
          //  $this->apply_payment_from_customer_account();


        }


    }

    function get_next_line_number() {

        $sql = sprintf(
            "SELECT count(*) AS num_lines FROM `Order Transaction Fact` WHERE `Order Key`=%d ", $this->id
        );
        $res = mysql_query($sql);

        $line_number = 1;
        if ($row = mysql_fetch_array($res)) {
            $line_number += $row['num_lines'];
        }

        return $line_number;


    }

    function remove_insurance($onptf_key) {

        $sql = sprintf(
            "DELETE FROM `Order No Product Transaction Fact` WHERE `Order No Product Transaction Fact Key`=%d AND `Order Key`=%d", $onptf_key, $this->id
        );
        mysql_query($sql);

        $this->update_totals();
       // $this->apply_payment_from_customer_account();
    }

    function add_insurance($insurance_key, $dn_key = false) {

        $valid_insurances = $this->get_insurances($dn_key);

        if (array_key_exists($insurance_key, $valid_insurances)) {

            if (!$valid_insurances[$insurance_key]['Order No Product Transaction Fact Key']) {


                $sql = sprintf(
                    "INSERT INTO `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`
					,`Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,`Currency Code`,`Currency Exchange`,`Metadata`,`Delivery Note Key`)
				VALUES (%d,%s,%s,%d,%s,%.2f,%.2f,%s,%.2f,%s,%.2f,%s,%s)  ", $this->id, prepare_mysql(gmdate("Y-m-d H:i:s")), prepare_mysql('Insurance'), $insurance_key, prepare_mysql(
                    $valid_insurances[$insurance_key]['Insurance Description']
                ), $valid_insurances[$insurance_key]['Insurance Net Amount'], $valid_insurances[$insurance_key]['Insurance Net Amount'], prepare_mysql(
                        $valid_insurances[$insurance_key]['Insurance Tax Code']
                    ), $valid_insurances[$insurance_key]['Insurance Tax Amount'],

                    prepare_mysql($this->data['Order Currency']), $this->data['Order Currency Exchange'], prepare_mysql($this->data['Order Original Metadata']), prepare_mysql($dn_key)

                );
                mysql_query($sql);

                $onptf_key = mysql_insert_id();

                $this->update_totals();

             //   $this->apply_payment_from_customer_account();
            } else {
                $onptf_key = $valid_insurances[$insurance_key]['Order No Product Transaction Fact Key'];
            }

        } else {
            $onptf_key = 0;
        }

        return $onptf_key;
    }



    function get_number_post_order_transactions() {


        $sql    = sprintf(
            "SELECT count(*) AS num FROM `Order Post Transaction Dimension` WHERE `Order Key`=%d  ", $this->id
        );
        $res    = mysql_query($sql);
        $number = 0;
        if ($row = mysql_fetch_assoc($res)) {
            $number = $row['num'];
        }

        return $number;
    }

    function mark_all_transactions_for_refund_to_be_deleted($data) {


        $sql = sprintf(
            "DELETE FROM `Order Post Transaction Dimension` WHERE `Order Key`=%d  AND `State`='In Process'  AND ", $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "SELECT `Order Transaction Fact Key`, `Invoice Quantity`,`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) AS VALUE  FROM  `Order Transaction Fact` OTF LEFT JOIN `Order Post Transaction Dimension` POT  ON (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) WHERE `Invoice Quantity`>0 AND OTF.`Order Key`=%d ",
            $this->id

        );
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {

            $sql = sprintf(
                "INSERT INTO `Order Post Transaction Dimension` (`Order Transaction Fact Key`,`Order Key`,`Quantity`,`Operation`,`Reason`,`To Be Returned`,`Customer Key`,'Credit') VALUES (%d,%d,%f,%s,%s,%s,%d,%f)",
                $row['Order Transaction Fact Key'], $this->id, $row['Invoice Quantity'], prepare_mysql('Refund'), prepare_mysql($data['Reason']), prepare_mysql($data['To Be Returned']),
                $this->data['Order Customer Key'], $row['value']
            );
            $this->db->exec($sql);


        }

    }

    function get_post_transactions_in_process_data() {
        $data = array(
            'Refund' => array(
                'In_Process_Products' => 0,

                'Amount'             => 0,
                'Tax_Amount'         => 0,
                'Other_Items_Amount' => $this->data['Order Invoiced Items Amount'],


                'Net_Amount'            => 0,
                'Tax_Amount'            => 0,
                'Formatted_Net_Amount'  => money(
                    0, $this->data['Order Currency']
                ),
                'Formatted_Tax_Amount'  => money(
                    0, $this->data['Order Currency']
                ),
                'Formatted_Zero_Amount' => money(
                    0, $this->data['Order Currency']
                ),

                'Refunded_Products'               => 0,
                'Refunded_No_Products'            => 0,
                'Refunded_Transactions'           => 0,
                'Refunded_Net_Amount'             => 0,
                'Refunded_Tax_Amount'             => 0,
                'Refunded_Total_Amount'           => 0,
                'Refunded_Formatted_Net_Amount'   => money(
                    0, $this->data['Order Currency']
                ),
                'Refunded_Formatted_Tax_Amount'   => money(
                    0, $this->data['Order Currency']
                ),
                'Refunded_Formatted_Total_Amount' => money(
                    0, $this->data['Order Currency']
                )

            ),
            'Resend' => array(
                'In_Process_Products'    => 0,
                'Distinct_Products'      => 0,
                'Market_Value'           => 0,
                'Formatted_Market_Value' => money(
                    0, $this->data['Order Currency']
                ),
                'state'                  => ''
            ),
            // 'Saved_Credit'=>array('Distinct_Products'=>0,'Amount'=>0,'Formatted_Amount'=>money(0,$this->data['Order Currency']),'State'=>'')


        );


        $sql = sprintf(
            "SELECT `Invoice Currency Code`,
		 sum(`Quantity`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)/`Invoice Quantity`) AS net_value,
		 sum(`Quantity`*(`Invoice Transaction Item Tax Amount`)/`Invoice Quantity`) AS tax_value,
          count(DISTINCT OTF.`Product Key` ) AS num FROM `Order Post Transaction Dimension` POT LEFT JOIN `Order Transaction Fact` OTF ON (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) WHERE `Invoice Quantity`>0 AND POT.`Order Key`=%d AND   `Operation`='Refund'  AND `State`='In Process'",
            $this->id

        );
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            if ($row['num'] > 0) {
                $data['Refund']['In_Process_Products'] = $row['num'];
                $data['Refund']['Net_Amount']          = $row['net_value'];
                $data['Refund']['Tax_Amount']          = $row['tax_value'];

                $data['Refund']['Formatted_Net_Amount'] = money(
                    $row['net_value'], $row['Invoice Currency Code']
                );
                $data['Refund']['Formatted_Tax_Amount'] = money(
                    $row['tax_value'], $row['Invoice Currency Code']
                );

            }
        }


        $sql = sprintf(
            "SELECT `Invoice Currency Code`,
		 sum(OTF.`Invoice Transaction Net Refund Items`) AS net_value,
		 sum(OTF.`Invoice Transaction Tax Refund Items`) AS tax_value,
          count(DISTINCT OTF.`Product Key` ) AS num FROM `Order Post Transaction Dimension` POT LEFT JOIN `Order Transaction Fact` OTF ON (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) WHERE   POT.`Order Key`=%d AND   `Operation`='Refund'  AND `State`!='In Process'",
            $this->id

        );
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            if ($row['num'] > 0) {
                $currency                                = $row['Invoice Currency Code'];
                $data['Refund']['Refunded_Products']     = $row['num'];
                $data['Refund']['Refunded_Transactions'] = $row['num'];

                $data['Refund']['Refunded_Net_Amount']   = $row['net_value'];
                $data['Refund']['Refunded_Tax_Amount']   = $row['tax_value'];
                $data['Refund']['Refunded_Total_Amount'] = $row['net_value'] + $row['tax_value'];

            }
        }


        $sql = sprintf(
            "SELECT `Currency Code`,
		 sum(`Transaction Refund Net Amount`) AS net_value,
		 sum(`Transaction Refund Tax Amount`) AS tax_value,
          count(*) AS num FROM `Order No Product Transaction Fact`    WHERE (`Transaction Refund Net Amount`!=0 OR `Transaction Refund Tax Amount`!=0 ) AND  `Order Key`=%d ", $this->id

        );

        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            if ($row['num'] > 0) {
                $currency = $row['Currency Code'];

                $data['Refund']['Refunded_No_Products']  = $row['num'];
                $data['Refund']['Refunded_Transactions'] += $row['num'];

                $data['Refund']['Refunded_Net_Amount']   += $row['net_value'];
                $data['Refund']['Refunded_Tax_Amount']   += $row['tax_value'];
                $data['Refund']['Refunded_Total_Amount'] += $row['net_value'] + $row['tax_value'];

            }
        }

        if ($data['Refund']['Refunded_Transactions'] > 0) {
            $data['Refund']['Refunded_Formatted_Net_Amount']   = money(
                $data['Refund']['Refunded_Net_Amount'], $currency
            );
            $data['Refund']['Refunded_Formatted_Tax_Amount']   = money(
                $data['Refund']['Refunded_Tax_Amount'], $currency
            );
            $data['Refund']['Refunded_Formatted_Total_Amount'] = money(
                $data['Refund']['Refunded_Total_Amount'], $currency
            );
        }

        /*
		$sql=sprintf("select `Invoice Currency Code`, sum(`Quantity`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)/`Invoice Quantity`) as value, count(DISTINCT OTF.`Product Key` ) as num from `Order Post Transaction Dimension` POT left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) where `Invoice Quantity`>0 and POT.`Order Key`=%d and   `Operation`='Credit'",
			$this->id
		);


		$sql=sprintf("select `Invoice Currency Code`, sum(POT.`Credit`) as value, count(DISTINCT OTF.`Product Key` ) as num from `Order Post Transaction Dimension` POT left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) where   POT.`Order Key`=%d and   `Operation`='Credit' and `State`='Saved'  ",
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$data['Saved_Credit']['Distinct_Products']=$row['num'];
			$data['Saved_Credit']['Amount']=$row['value'];
			$data['Saved_Credit']['Formatted_Amount']=money($row['value'],$row['Invoice Currency Code']);
		}



		$sql=sprintf("select `Invoice Currency Code`, sum(POT.`Credit`) as value, count(DISTINCT OTF.`Product Key` ) as num from `Order Post Transaction Dimension` POT left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) where   POT.`Order Key`=%d and   `Operation`='Credit' and `State`='In Process'  ",
			$this->id
		);


		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$data['Credit']['Distinct_Products']=$row['num'];
			$data['Credit']['Amount']=$row['value'];
			$data['Credit']['Formatted_Amount']=money($row['value'],$row['Invoice Currency Code']);
		}
*/
        $sql = sprintf(
            "SELECT  `State`,`Product Currency`,sum(`Quantity`*`Product History Price`) AS value,  count(DISTINCT OTF.`Product Key` ) AS num FROM `Order Post Transaction Dimension` POT LEFT JOIN `Order Transaction Fact` OTF ON (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) LEFT JOIN `Product History Dimension` PH ON (OTF.`Product Key`=PH.`Product Key`) LEFT JOIN `Product Dimension` P ON (P.`Product ID`=PH.`Product ID`)  WHERE `Operation`='Resend' AND POT.`Order Key`=%d ",
            $this->id
        );
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            $data['Resend']['Distinct_Products'] = $row['num'];
            $data['Resend']['State']             = $row['State'];

            $data['Resend']['Market_Value']           = $row['value'];
            $data['Resend']['Formatted_Market_Value'] = money(
                $row['value'], $row['Product Currency']
            );

        }


        $sql = sprintf(
            "SELECT  count(DISTINCT OTF.`Product Key` ) AS num FROM `Order Post Transaction Dimension` POT LEFT JOIN `Order Transaction Fact` OTF ON (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) WHERE `Operation`='Resend' AND (POT.`Delivery Note Key`=0 OR POT.`Delivery Note Key` IS NULL)  AND POT.`Order Key`=%d ",
            $this->id
        );

        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            $data['Resend']['In_Process_Products'] = $row['num'];

        }


        $data['Refund']['Other_Items_Amount']           -= $data['Refund']['Amount'];
        $data['Refund']['Formatted_Other_Items_Amount'] = money(
            $data['Refund']['Other_Items_Amount'], $this->data['Order Currency']
        );


        return $data;

    }

    function cancel_post_transactions_in_process() {
        $this->deleted_post_transactions = 0;
        $sql                             = sprintf(
            "DELETE FROM `Order Post Transaction Dimension` WHERE `Order Key`=%d AND `State`='In Process' ", $this->id
        );
        mysql_query($sql);
        $this->deleted_post_transactions = mysql_affected_rows();


    }

    function cancel_submited_credits() {
        $sql = sprintf(
            "DELETE  FROM `Order Post Transaction Dimension` WHERE `Order Key`=%d AND `State`='Saved' AND `Operation`='Credit'", $this->id
        );
        $this->db->exec($sql);

    }

    function submit_credits() {
        $sql = sprintf(
            "UPDATE `Order Post Transaction Dimension` SET `Credit Saved`=`Credit` , `State`='Saved'  WHERE `Order Key`=%d AND `State`='In Process' AND `Operation`='Credit'", $this->id
        );
        $this->db->exec($sql);

    }

    function create_post_transaction_in_process($otf_key, $key, $values) {


        if (!preg_match(
            '/^(Quantity|Operation|Reason|To Be Returned)$/', $key
        )) {
            $this->error = true;

            return;
        }


        $this->deleted_post_transaction = false;
        $this->update_post_transaction  = false;
        $this->created_post_transaction = false;
        $this->updated                  = false;
        $sql                            = sprintf(
            'SELECT * FROM `Order Post Transaction Dimension` WHERE `Order Transaction Fact Key`=%d', $otf_key
        );
        $res                            = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            if ($row['Order Key'] != $this->id) {
                $this->error = true;

                return;
            }

            if ($key == 'Quantity' and $values[$key] <= 0) {
                $sql = sprintf(
                    "DELETE FROM `Order Post Transaction Dimension` WHERE `Order Post Transaction Key`=%d ", $row['Order Post Transaction Key']
                );
                mysql_query($sql);
                if (mysql_affected_rows() > 0) {
                    $this->update_post_transaction = true;
                    $this->updated                 = true;

                    $opt_key                        = $row['Order Post Transaction Key'];
                    $this->deleted_post_transaction = true;
                }
            } else {


                $sql = sprintf(
                    "UPDATE `Order Post Transaction Dimension` SET `%s`=%s WHERE `Order Post Transaction Key`=%d ", $key, prepare_mysql($values[$key]), $row['Order Post Transaction Key']
                );
                mysql_query($sql);
                $affected_rows = mysql_affected_rows();
                if ($key == 'Quantity' and $row['Operation'] == 'Credit') {
                    $sql = sprintf(
                        "SELECT `Invoice Currency Code`, (`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)/`Invoice Quantity` AS value,OTF.`Order Transaction Fact Key` FROM  `Order Transaction Fact`  OTF WHERE OTF.`Order Transaction Fact Key`=%d",
                        $otf_key
                    );


                    $res2 = mysql_query($sql);
                    if ($row2 = mysql_fetch_assoc($res2)) {
                        $sql = sprintf(
                            "UPDATE `Order Post Transaction Dimension` SET `Credit`=%.2f WHERE `Order Post Transaction Key`=%d ", $row2['value'] * $values[$key], $row['Order Post Transaction Key']
                        );
                        $this->db->exec($sql);
                    }


                }


                if ($key == 'Operation') {
                    $sql = sprintf(
                        "SELECT `Invoice Currency Code`, (`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)/`Invoice Quantity` AS value,OTF.`Order Transaction Fact Key` FROM  `Order Transaction Fact`  OTF WHERE OTF.`Order Transaction Fact Key`=%d",
                        $otf_key
                    );


                    $qty = 0;
                    if (is_numeric($row['Quantity'])) {
                        $qty = $row['Quantity'];
                    }

                    $res2 = mysql_query($sql);
                    if ($row2 = mysql_fetch_assoc($res2)) {
                        $sql = sprintf(
                            "UPDATE `Order Post Transaction Dimension` SET `Credit`=%.2f WHERE `Order Post Transaction Key`=%d ", $row2['value'] * $qty, $row['Order Post Transaction Key']
                        );
                        $this->db->exec($sql);
                    }


                }

                if ($affected_rows > 0) {


                    $this->update_post_transaction = true;
                    $this->updated                 = true;
                    $opt_key                       = $row['Order Post Transaction Key'];


                }
            }

        } else {
            $sql = sprintf(
                "INSERT INTO `Order Post Transaction Dimension` (`Order Transaction Fact Key`,`Order Key`,`Quantity`,`Operation`,`Reason`,`To Be Returned`,`Customer Key`) VALUES (%d,%d,%f,%s,%s,%s,%d)",
                $otf_key, $this->id, $values['Quantity'], prepare_mysql($values['Operation']), prepare_mysql($values['Reason']), prepare_mysql($values['To Be Returned']),
                $this->data['Order Customer Key']
            );

            mysql_query($sql);
            if (mysql_affected_rows() > 0) {
                $this->created_post_transaction = true;
                $this->updated                  = true;
                $opt_key                        = mysql_insert_id();


                if ($values['Operation'] = 'Credit') {
                    $sql  = sprintf(
                        "SELECT `Invoice Currency Code`, sum(`Quantity`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)/`Invoice Quantity`) AS value FROM `Order Post Transaction Dimension` POT LEFT JOIN `Order Transaction Fact` OTF ON (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) WHERE `Invoice Quantity`>0 AND OTF.`Order Transaction Fact Key`=%d AND  `Operation`='Credit' AND `State`='In Process'",
                        $otf_key
                    );
                    $res2 = mysql_query($sql);
                    if ($row2 = mysql_fetch_assoc($res2)) {
                        $sql = sprintf(
                            "UPDATE `Order Post Transaction Dimension` SET `Credit`=%.2f WHERE `Order Post Transaction Key`=%d ", $row2['value'], $opt_key
                        );
                        $this->db->exec($sql);
                    }


                }


            }

        }
        $transaction_data = array();


        $sql  = sprintf(
            'SELECT `Order Key`,`State`,`Operation`,`Reason`,`Quantity`,`To Be Returned` FROM `Order Post Transaction Dimension` WHERE `Order Transaction Fact Key`=%d', $otf_key
        );
        $res2 = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res2)) {
            $transaction_data['Quantity']       = $row['Quantity'];
            $transaction_data['Operation']      = $row['Operation'];
            $transaction_data['Reason']         = $row['Reason'];
            $transaction_data['State']          = $row['State'];
            $transaction_data['To Be Returned'] = $row['To Be Returned'];
            $transaction_data['Order Key']      = $row['Order Key'];
        }

        if ($this->created_post_transaction or $this->update_post_transaction) {

            $transaction_data['Order Post Transaction Key'] = $opt_key;
        }
        if ($this->deleted_post_transaction) {
            $transaction_data['Quantity']       = '';
            $transaction_data['Operation']      = '';
            $transaction_data['Reason']         = '';
            $transaction_data['State']          = '';
            $transaction_data['To Be Returned'] = '';
            $transaction_data['Order Key']      = '';
        }


        return $transaction_data;

    }

    function add_post_order_transactions($data) {
        $otf_key = array();
        $sql     = sprintf(
            "SELECT `Order Post Transaction Key`,OTF.`Product ID`,`Product Package Weight`,`Quantity`,`Product Units Per Case` FROM `Order Post Transaction Dimension` POT  LEFT JOIN `Order Transaction Fact` OTF ON (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) LEFT JOIN `Product History Dimension`  PH ON (PH.`Product Key`=OTF.`Product Key`) LEFT JOIN `Product Dimension` P ON (P.`Product ID`=PH.`Product ID`)   WHERE POT.`Order Key`=%d  AND `State`='In Process' ",
            $this->id
        );
        //print $sql;
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $order_key       = $this->id;
            $order_date      = gmdate('Y-m-d H:i:s');
            $order_public_id = $this->data['Order Public ID'];

            $product = new Product('id', $row['Product ID']);

            $bonus_quantity = 0;
            $sql            = sprintf(
                "INSERT INTO `Order Transaction Fact` (`Order Date`,`Order Key`,`Order Public ID`,`Delivery Note Key`,`Delivery Note ID`,`Order Bonus Quantity`,`Order Transaction Type`,`Transaction Tax Rate`,`Transaction Tax Code`,`Order Currency Code`,`Estimated Weight`,`Order Last Updated Date`,
			`Product Key`,`Product ID`,`Product Code`,`Product Family Key`,`Product Department Key`,
			`Current Dispatching State`,`Current Payment State`,`Customer Key`,`Delivery Note Quantity`,`Ship To Key`,`Billing To Key`,
			`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Amount`,`Metadata`,`Store Key`,`Units Per Case`,`Customer Message`)
VALUES (%s,%s,%s,%d,%s,%f,%s,%f,%s,%s,%s,  %s,
	%d,%d,%s,%d,%d,
	%s,%s,%d,%s,%s,%s,
	%.2f,%.2f,%.2f,%s,%s,%f,'') ", prepare_mysql($order_date), prepare_mysql($order_key), prepare_mysql($order_public_id),

                0, prepare_mysql(''),

                $bonus_quantity, prepare_mysql('Resend'), $data['Order Tax Rate'], prepare_mysql($data['Order Tax Code']), prepare_mysql($this->data['Order Currency']),
                $row['Product Package Weight'] * $row['Quantity'],

                prepare_mysql($order_date), $product->historic_id, $product->data['Product ID'], prepare_mysql($product->data['Product Code']), $product->data['Product Family Key'],
                $product->data['Product Main Department Key'],

                prepare_mysql('In Process'), prepare_mysql($data['Current Payment State']), prepare_mysql($this->data['Order Customer Key']),

                $row['Quantity'], prepare_mysql($data['Ship To Key']), prepare_mysql($data['Billing To Key']), $data['Gross'], 0, $data['Gross'], prepare_mysql($data['Metadata'], false),
                prepare_mysql($this->data['Order Store Key']), $row['Product Units Per Case']

            );

            if (!mysql_query($sql)) {
                exit ("$sql can not update xx orphan transaction\n");
            }
            $otf_key = mysql_insert_id();

            $sql = sprintf(
                "UPDATE  `Order Post Transaction Dimension` SET `Order Post Transaction Fact Key`=%d WHERE `Order Post Transaction Key`=%d   ", $otf_key, $row['Order Post Transaction Key']
            );
            $this->db->exec($sql);
            //print $sql;
        }

        if (array_key_exists('Supplier Metadata', $data)) {

            $sql = sprintf(
                "update`Order Transaction Fact` set  `Supplier Metadata`=%s  where `Order Transaction Fact Key`=%d ", prepare_mysql($data['Supplier Metadata']), $otf_key

            );
            //        print "$sql\n";
            mysql_query($sql);
        }

        return array('otf_key' => $otf_key);

    }

    function get_notes() {

        $notes = '';
        if ($this->data['Order Customer Sevices Note'] != '') {
            $notes .= "<div><div style='color:#777;font-size:90%;padding-bottom:5px'>"._('Note').":</div>".$this->data['Order Customer Sevices Note']."</div>";
        }
        if ($this->data['Order Customer Message'] != '') {
            $notes .= "<div><div style='color:#777;font-size:90%;padding-bottom:5px'>"._('Customer Note').":</div>".$this->data['Order Customer Message']."</div>";
        }

        return $notes;

    }

    function get_currency_symbol() {
        return currency_symbol($this->data['Order Currency']);
    }

    function get_formatted_tax_info() {
        $selection_type     = $this->data['Order Tax Selection Type'];
        $formatted_tax_info = '<span title="'.$selection_type.'">'.$this->data['Order Tax Name'].'</span>';

        return $formatted_tax_info;
    }

    function get_formatted_tax_info_with_operations() {
        $operations         = $this->data['Order Tax Operations'];
        $selection_type     = $this->data['Order Tax Selection Type'];
        $formatted_tax_info = '<span title="'.$selection_type.'">'.$this->data['Order Tax Name'].'</span>'.$operations;

        return $formatted_tax_info;
    }

    function get_formatted_dispatch_state() {
        return get_order_formatted_dispatch_state(
            $this->data['Order Current Dispatch State'], $this->id
        );

    }

    function get_formatted_payment_state() {
        return get_order_formatted_payment_state($this->data);

    }

    function set_as_invoiced($invoice) {


        $sql = sprintf(
            "UPDATE `Order Dimension` SET `Order Invoiced`='Yes'   WHERE `Order Key`=%d ", $this->id
        );

        $this->db->exec($sql);

        $this->data['Order Invoiced'] = 'Yes';

        $customer = new Customer($this->data['Order Customer Key']);

        $customer->update_orders();


        $invoice_link = sprintf(
            '<a href="invoice.php?id=%d">%s</a>', $invoice->id, $invoice->data['Invoice Public ID']
        );
        $history_data = array(
            'History Abstract' => sprintf(
                _('Order invoiced (%s)'), $invoice_link
            ),
            'History Details'  => '',
        );
        $this->add_subject_history($history_data);


    }

    function get_no_product_deal_info($type) {
        $deal_info = '';
        $sql       = sprintf(
            "SELECT `Deal Info` FROM `Order No Product Transaction Deal Bridge` B LEFT JOIN `Order No Product Transaction Fact` OTF ON (OTF.`Order No Product Transaction Fact Key`=B.`Order No Product Transaction Fact Key`) WHERE B.`Order Key`=%d AND `Transaction Type`=%s",
            $this->id, prepare_mysql($type)
        );

        $res = mysql_query($sql);

        if ($row = mysql_fetch_assoc($res)) {
            $deal_info = $row['Deal Info'];
        }

        return $deal_info;
    }

    function get_vouchers_info() {
        $vouchers_info = array();
        $sql           = sprintf(
            'SELECT V.`Voucher Key`,`Voucher Code`,D.`Deal Key`,`Deal Name`,`Deal Description` FROM `Voucher Order Bridge` B LEFT JOIN `Deal Dimension` D ON (B.`Deal Key`=D.`Deal Key`) LEFT JOIN `Voucher Dimension` V ON (B.`Voucher Key`=V.`Voucher Key`) WHERE `Order Key`=%d',
            $this->id
        );
        $res           = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $vouchers_info[] = array(
                'key'              => $row['Voucher Key'],
                'code'             => $row['Voucher Code'],
                //   'state'=>$row['State'],
                'deal_key'         => $row['Deal Key'],
                'deal_name'        => $row['Deal Name'],
                'deal_description' => $row['Deal Description']

            );
        }

        return $vouchers_info;

    }

    function get_deals_info() {
        $deals_info = array();
        $sql        = sprintf(
            'SELECT B.`Deal Key`,`Deal Name`,`Deal Description`,`Deal Term Allowances` FROM `Order Deal Bridge` B LEFT JOIN `Deal Dimension` D ON (B.`Deal Key`=D.`Deal Key`) WHERE `Order Key`=%d  AND `Deal Trigger` IN ("Order","Customer") AND `Deal Terms Type`!="Voucher" GROUP BY B.`Deal Key`',
            $this->id
        );

        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $deals_info[] = array(
                'key'              => $row['Deal Key'],
                'name'             => $row['Deal Name'],
                'description'      => $row['Deal Description'],
                'terms_allowances' => $row['Deal Term Allowances'],
            );
        }

        return $deals_info;

    }


    function get_last_basket_page() {
        $page_key = 0;
        $sql      = sprintf(
            "SELECT `Page Key` FROM `Order Basket History Dimension` WHERE `Order Key`=%d AND `Page Store Section Type`!='System' ORDER BY `Date` DESC LIMIT 1 ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $page_key = $row['Page Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $page_key;
    }

    function get_items() {

        $sql = sprintf(
            'SELECT OTF.`Product ID`,OTF.`Product Key`,`Order Transaction Fact Key`,`Order Currency Code`,`Order Transaction Amount`,`Order Quantity`,`Product History Name`,`Product History Units Per Case`,PD.`Product Code`,`Product Name`,`Product Units Per Case` FROM `Order Transaction Fact` OTF LEFT JOIN `Product History Dimension` PHD ON (OTF.`Product Key`=PHD.`Product Key`) LEFT JOIN `Product Dimension` PD ON (PD.`Product ID`=PHD.`Product ID`)  WHERE `Order Key`=%d  ORDER BY `Product Code File As` ',
            $this->id
        );

        $items = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $edit_quantity = sprintf(
                    '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-plus fa-fw like_button button"  style="cursor:pointer" aria-hidden="true"></i></span>',
                    $row['Order Transaction Fact Key'], $row['Product ID'], $row['Product Key'], $row['Order Quantity'] + 0, $row['Order Quantity'] + 0
                );


                $items[] = array(
                    'code'        => $row['Product Code'],
                    'description' => $row['Product History Units Per Case'].'x '.$row['Product History Name'],
                    'qty'         => number($row['Order Quantity']),
                    'edit_qty'    => $edit_quantity,
                    'amount'      => '<span class="item_amount">'.money($row['Order Transaction Amount'], $row['Order Currency Code']).'</span>'

                );


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $items;

    }

    function get_items_info_to_delete() {
        $items_info = array();
        $sql        = sprintf(
            "SELECT (SELECT `Page Key` FROM `Page Product Dimension` B  WHERE B.`State`='Online' AND  B.`Product ID`=OTF.`Product ID` LIMIT 1 ) `Page Key`,(SELECT `Page URL` FROM `Page Product Dimension` B LEFT JOIN `Page Dimension`  PA  ON (PA.`Page Key`=B.`Page Key`) WHERE B.`State`='Online' AND  B.`Product ID`=OTF.`Product ID` LIMIT 1 ) `Page URL`,`Order Last Updated Date`,`Order Date`,`Order Bonus Quantity`,`Order Quantity`,`Order Transaction Gross Amount`,`Order Currency Code`,`Order Transaction Total Discount Amount`,OTF.`Product ID`,OTF.`Product Code`,`Product XHTML Short Description`,`Product Tariff Code`,(SELECT GROUP_CONCAT(`Deal Info`) FROM `Order Transaction Deal Bridge` OTDB WHERE OTDB.`Order Key`=OTF.`Order Key` AND OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) AS `Deal Info` FROM `Order Transaction Fact` OTF LEFT JOIN `Product Dimension` P ON (P.`Product ID`=OTF.`Product ID`)  WHERE `Order Key`=%d ORDER BY OTF.`Product Code` ",
            $this->id

        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Page URL'] != '') {
                    $code = sprintf(
                        '<a href="%s">%s</a>', $row['Page URL'], $row['Product Code']
                    );
                    $code = sprintf(
                        '<a href="page.php?id=%d">%s</a>', $row['Page Key'], $row['Product Code']
                    );
                } else {
                    $code = $row['Product Code'];
                }

                if ($row['Deal Info']) {
                    $deal_info =
                        '<br/><span style="font-style:italics;color:#555555;font-size:90%">'.$row['Deal Info'].($row['Order Transaction Total Discount Amount'] ? ', <span style="font-weight:800">-'
                            .money(
                                $row['Order Transaction Total Discount Amount'], $row['Order Currency Code']
                            ).'</span>' : '').'</span>';
                } else {
                    $deal_info = '';
                }


                $qty = number($row['Order Quantity']);
                if ($row['Order Bonus Quantity'] != 0) {
                    if ($row['Order Quantity'] != 0) {
                        $qty .= '<br/> +'.number($row['Order Bonus Quantity']).' '._('free');
                    } else {
                        $qty = number($row['Order Bonus Quantity']).' '._('free');
                    }
                }

                $items_info[] = array(
                    'pid'          => $row['Product ID'],
                    'code'         => $code,
                    'code_plain'   => $row['Product Code'],
                    'description'  => $row['Product XHTML Short Description'].$deal_info,
                    'tariff_code'  => $row['Product Tariff Code'],
                    'quantity'     => $qty,
                    'gross'        => money(
                        $row['Order Transaction Gross Amount'], $row['Order Currency Code']
                    ),
                    'discount'     => money(
                        $row['Order Transaction Total Discount Amount'], $row['Order Currency Code']
                    ),
                    'to_charge'    => money(
                        $row['Order Transaction Gross Amount'] - $row['Order Transaction Total Discount Amount'], $row['Order Currency Code']
                    ),
                    'created'      => strftime(
                        "%a %e %b %Y %H:%M %Z", strtotime($row['Order Date'].' +0:00')
                    ),
                    'last_updated' => strftime(
                        "%a %e %b %Y %H:%M %Z", strtotime($row['Order Last Updated Date'].' +0:00')
                    )

                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $items_info;
    }

    function get_formatted_pending_payment_amount_from_account_balance() {
        return money(
            $this->get_pending_payment_amount_from_account_balance(), $this->data['Order Currency']
        );
    }

    function get_pending_payment_amount_from_account_balance() {
        $pending_amount = 0;
        $sql            = sprintf("SELECT `Amount` FROM `Order Payment Bridge` WHERE `Is Account Payment`='Yes' AND `Order Key`=%d ", $this->id);


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $pending_amount = $row['Amount'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $pending_amount;
    }

    function get_date($field) {
        return strftime("%e %b %Y", strtotime($this->data[$field].' +0:00'));
    }

    function get_to_refund_amount() {
        $to_refund_amount = 0;

        foreach ($this->get_invoices_objects() as $invoice) {


            if ($invoice->data['Invoice Type'] == 'Refund') {
                $to_refund_amount += $invoice->data['Invoice Outstanding Total Amount'];
            }

        }

        return $to_refund_amount;

    }

    function remove_out_of_stocks_from_basket($product_pid) {


        $sql           = sprintf(
            "SELECT `Order Transaction Fact Key`,`Order Quantity`,`Product Key`,`Product ID`,`Order Transaction Amount` FROM `Order Transaction Fact` WHERE `Current Dispatching State`='In Process' AND  `Product ID`=%d AND `Order Key`=%d ",
            $product_pid, $this->id
        );
        $affected_rows = 0;


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $sql = sprintf(
                    'INSERT INTO `Order Transaction Out of Stock in Basket Bridge` (`Order Transaction Fact Key`,`Date`,`Store Key`,`Order Key`,`Product Key`,`Product ID`,`Quantity`,`Amount`) VALUES (%d,%s,%d,%d,%d,%d,%f,%.2f)',
                    $row['Order Transaction Fact Key'], prepare_mysql(gmdate('Y-m-d H:i:s')), $this->data['Order Store Key'], $this->id, $row['Product Key'], $row['Product ID'],
                    $row['Order Quantity'], $row['Order Transaction Amount']
                );

                $this->db->exec($sql);


                $sql = sprintf(
                    'UPDATE `Order Transaction Fact` SET `Current Dispatching State`=%s,`Order Quantity`=0,`Order Bonus Quantity`=0 ,`Order Transaction Gross Amount`=0 ,`Order Transaction Total Discount Amount`=0,`Order Transaction Amount`=0 WHERE `Order Transaction Fact Key`=%d   ',
                    prepare_mysql('Out of Stock in Basket'), $row['Order Transaction Fact Key']
                );
                $this->db->exec($sql);

                $affected_rows++;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($affected_rows) {
            $dn_key = 0;

            $this->update_number_products();
            $this->update_insurance();

            $this->update_discounts_items();
            $this->update_totals();


            $this->update_shipping($dn_key, false);
            $this->update_charges($dn_key, false);
            $this->update_discounts_no_items($dn_key);


            $this->update_deal_bridge();

            $this->update_deals_usage();

            $this->update_totals();


            $this->update_number_products();

          //  $this->apply_payment_from_customer_account();
        }


    }

    function restore_back_to_stock_to_basket($product_pid) {

        if ($this->data['Order Current Dispatch State'] != 'In Process') {
            return;
        }

        $affected_rows = 0;;

        $sql = sprintf(
            "SELECT `Order Transaction Fact Key`,`Quantity` FROM `Order Transaction Out of Stock in Basket Bridge` WHERE  `Product ID`=%d AND `Order Key`=%d ", $product_pid, $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $product = new Product('id', $product_pid);

                $gross = $row['Quantity'] * $product->data['Product Price'];

                $sql = sprintf(
                    'UPDATE `Order Transaction Fact` SET `Current Dispatching State`=%s,`Order Quantity`=%d,`No Shipped Due Out of Stock`=0,`Order Transaction Gross Amount`=%.2f ,`Order Transaction Total Discount Amount`=%.2f,`Order Transaction Amount`=%.2f  WHERE `Order Transaction Fact Key`=%d   ',
                    prepare_mysql('In Process'), $row['Quantity'], $gross, 0, $gross, $row['Order Transaction Fact Key']
                );

                $this->db->exec($sql);

                $sql = sprintf(
                    'DELETE FROM `Order Transaction Out of Stock in Basket Bridge` WHERE `Order Transaction Fact Key`=%d', $row['Order Transaction Fact Key']
                );
                $this->db->exec($sql);

                $affected_rows++;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($affected_rows) {
            $dn_key = 0;


            $this->update_number_products();
            $this->update_insurance();

            $this->update_discounts_items();
            $this->update_totals();


            $this->update_shipping($dn_key, false);
            $this->update_charges($dn_key, false);
            $this->update_discounts_no_items($dn_key);


            $this->update_deal_bridge();

            $this->update_deals_usage();

            $this->update_totals();


            $this->update_number_products();

            //$this->apply_payment_from_customer_account();


        }

    }


}


?>
