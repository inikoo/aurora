<?php
/*


  About:
  Author: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/

use Aurora\Traits\ObjectTaxNumberTrait;
use Aurora\Models\Utils\TaxCategory;

include_once 'class.DB_Table.php';

require_once 'utils/order_functions.php';
require_once 'utils/natural_language.php';
include_once 'trait.AttachmentSubject.php';
include_once 'trait.OrderShippingOperations.php';
include_once 'trait.OrderChargesOperations.php';
include_once 'trait.OrderDiscountOperations.php';
include_once 'trait.OrderItems.php';
include_once 'trait.OrderPayments.php';
include_once 'trait.Order_Calculate_Totals.php';
include_once 'trait.OrderOperations.php';
include_once 'trait.OrderTax.php';
include_once 'trait.OrderGet.php';
include_once 'trait.Address.php';
include_once 'trait.OrderAiku.php';


class Order extends DB_Table
{

    use Address, AttachmentSubject, OrderShippingOperations, OrderChargesOperations, OrderDiscountOperations, OrderItems, OrderPayments, Order_Calculate_Totals, OrderOperations, OrderTax, OrderGet;
    use ObjectTaxNumberTrait;
    use OrderAiku;


    function __construct($arg1 = false, $arg2 = false)
    {
        global $db;
        $this->db = $db;


        $this->deleted_otfs = array();
        $this->new_otfs     = array();
        $this->metadata     = array();

        $this->skip_update_after_individual_transaction = false;
        $this->amount_off_allowance_data                = false;

        $this->table_name    = 'Order';
        $this->ignore_fields = array('Order Key');


        if (preg_match('/new/i', $arg1)) {
            $this->create($arg2);

            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        $this->get_data($arg1, $arg2);
    }


    function get_data($key, $id)
    {
        if ($key == 'id') {
            $sql = sprintf("SELECT * FROM `Order Dimension` WHERE `Order Key`=%d", $id);
        } elseif ($key == 'public id' or $key == 'public_id') {
            $sql = sprintf("SELECT * FROM `Order Dimension` WHERE `Order Public ID`=%s", prepare_mysql($id));
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Order Key'];
        }


        if ($this->id) {
            $this->set_display_currency($this->data['Order Currency'], 1.0);
            $this->metadata = json_decode($this->data['Order Metadata'], true);
        }
    }

    function set_display_currency($currency_code, $exchange)
    {
        $this->currency_code = $currency_code;
        $this->exchange      = $exchange;
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '')
    {
        switch ($field) {
            case('Replacement State'):
                $delivery_note = get_object('delivery note', $metadata['Delivery Note Key']);

                if ($delivery_note->get('Delivery Note Order Key') != $this->id or $delivery_note->get('Delivery Note Type') != 'Replacement') {
                    $this->error = true;
                }

                $delivery_note->update(array('Delivery Note State' => $value));
                $number_deliveries = 0;
                $deliveries_xhtml  = '';

                $store = get_object('Store', $this->get('Store Key'));

                foreach ($this->get_deliveries('objects') as $dn) {
                    $number_deliveries++;
                    $deliveries_xhtml .= sprintf(
                        ' <div class="node"  id="delivery_node_%d"><span class="node_label"><i class="fa fa-truck fa-flip-horizontal fa-fw" aria-hidden="true"></i> 
                               <span class="link" onClick="change_view(\'%s\')">%s</span> (<span class="Delivery_Note_State">%s</span>)
                                <a title="%s" class="pdf_link %s" target="_blank" href="/pdf/order_pick_aid.pdf.php?id=%d"> <i class="fal fa-clipboard-list-check " style="font-size: larger"></i></a>
                                <a class="pdf_link %s" target=\'_blank\' href="/pdf/dn.pdf.php?id=%d"> <img alt="pdf" style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif"></a>
                               </span>
                                <div class="order_operation data_entry_delivery_note %s"><div class="square_button right" title="%s"><i class="fa fa-keyboard" aria-hidden="true" onclick="data_entry_delivery_note(%s)"></i></div></div>
                               </div>',
                        $dn->id,
                        'delivery_notes/'.$dn->get('Delivery Note Store Key').'/'.$dn->id,
                        $dn->get('ID'),
                        $dn->get('Abbreviated State'),
                        _('Picking sheet'),
                        ($dn->get('State Index') != 10 ? 'hide' : ''),
                        $dn->id,
                        ($dn->get('State Index') < 90 ? 'hide' : ''),
                        $dn->id,
                        (($dn->get('State Index') != 10 or $store->settings('data_entry_picking_aid') != 'Yes') ? 'hide' : ''),
                        _('Input picking sheet data'),
                        $dn->id

                    );
                }


                $this->update_metadata = array(

                    'deliveries_xhtml' => $deliveries_xhtml

                );


                break;
            case('Order For Collection'):
                if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
                    return;
                }

                $this->update_for_collection($value, $options);
                break;
            case('Order Tax Number'):
                if ($this->get('State Index') <= 0) {
                    return;
                }


                $this->update_tax_number($value);
                $this->post_operation_order_totals_changed();
                break;
            case('Order Tax Number Valid'):
                if ($this->get('State Index') <= 0) {
                    return;
                }
                $this->update_tax_number_valid($value);
                $this->post_operation_order_totals_changed();
                break;
            case 'Order Invoice Address':
                if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
                    return;
                }
                $this->update_address('Invoice', json_decode($value, true));
                $this->post_operation_order_totals_changed();
                break;


            case 'Order Delivery Address':


                if (!preg_match('/force/', $options) and ($this->get('State Index') >= 90 or $this->get('State Index') <= 0)) {
                    return;
                }
                $this->update_address('Delivery', json_decode($value, true));
                $this->post_operation_order_totals_changed();
                break;

            case('Order State'):
                $this->update_state($value, $options, $metadata);
                break;


            case('Order Customer Purchase Order ID'):
                $this->update_field('Order Customer Purchase Order ID', $value);


                if ($value == '') {
                    $this->update_metadata['hide'] = array('Order_Customer_Purchase_Order_ID_container');
                } else {
                    $this->update_metadata['show'] = array('Order_Customer_Purchase_Order_ID_container');
                }

                $this->fork_index_elastic_search();
                foreach ($this->get_invoices('objects') as $invoice) {
                    $invoice->fork_index_elastic_search();
                }
                foreach ($this->get_deliveries('objects') as $delivery_motes) {
                    $delivery_motes->fork_index_elastic_search();
                }

                break;
            case('Sticky Note'):
                $this->update_field('Order '.$field, $value, 'no_null');
                $this->new_value = html_entity_decode($this->new_value);
                break;

            case 'Order Recargo Equivalencia':

                if (!($value == 'Yes' or $value == 'No')) {
                    $this->error = true;
                    $this->msg   = 'invalid value';

                    return;
                }

                $this->fast_update_json_field('Order Metadata', 'RE', $value);
                $this->update_tax();
                $this->post_operation_order_totals_changed();
                break;


            case 'Order Public ID':
            case 'Order Customer Name':
                $this->update_field($field, $value, $options);
                foreach ($this->get_invoices('objects') as $invoice) {
                    $invoice->fork_index_elastic_search();
                }
                foreach ($this->get_deliveries('objects') as $delivery_motes) {
                    $delivery_motes->fork_index_elastic_search();
                }
                break;

            case 'Order Source Key':
                $this->update_field($field, $value, $options);
                foreach ($this->get_invoices('objects') as $invoice) {
                    $invoice->editor = $this->editor;
                    $invoice->update(
                        ['Invoice Source Key' => $value]
                    );
                    $invoice->categorize();
                }
                break;
            default:
                $base_data = $this->base_data();


                if (array_key_exists($field, $base_data)) {
                    $this->update_field($field, $value, $options);
                }
        }
    }

    function get($key = '')
    {
        if (!$this->id) {
            return '';
        }


        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }


        if ($key == 'Shipping Net Amount' and $this->data['Order Shipping Method'] == 'TBC') {
            return _('TBC');
        }

        if (preg_match(
            '/^(Balance (Total|Net|Tax)|Invoiced Total Net Adjust|Invoiced Total Tax Adjust|Invoiced Refund Net|Invoiced Refund Tax|Total|Items|Invoiced Items|Invoiced Tax|Invoiced Net|Invoiced Charges|Payments|To Pay|Invoiced Shipping|Invoiced Insurance |(Shipping Discount|Charges Discount|Insurance Discount)|(Shipping |Charges |Insurance )?Net|Profit).*(Amount)$/',
            $key
        )) {
            $amount = 'Order '.$key;

            return money(
                $this->exchange * $this->data[$amount],
                $this->currency_code
            );
        }
        if (preg_match('/^Number (Items|Products)$/', $key)) {
            $amount = 'Order '.$key;

            return number($this->data[$amount]);
        }


        switch ($key) {
            case 'Order Source':
            case 'Source Key':

                $souce = '<span class="very_discreet">'._('Unknown sell channel').'</span>';

                $sql  = "select `Order Source Name`,`Order Source Type` from `Order Source Dimension` where `Order Source Key`=?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    [
                        $this->data['Order Source Key']
                    ]
                );
                while ($row = $stmt->fetch()) {
                    switch ($row['Order Source Type']) {
                        case 'phone':
                            $souce = _('Order by phone');
                            break;
                        case 'website':
                            $souce = _('Ordered online');
                            break;
                        case 'other':
                            $souce = '<span class="discret">'._('Other sell channel').'</span>';
                            break;
                        case 'email':
                            $souce = _('Ordered by email');
                            break;
                        case 'marketplace':
                            $souce = _('Marketplace').": ".$row['Order Source Name'];
                            break;
                        default:
                            $souce = $row['Order Source Name'];
                    }
                }


                return $souce;


            case 'Last Updated by Customer':
                $_tmp = gmdate("U") - gmdate("U", strtotime($this->data['Order Last Updated by Customer'].' +0:00'));
                if ($_tmp < 3600) {
                    $date = strftime("%H:%M:%S %Z", strtotime($this->data['Order Last Updated by Customer'].' +0:00'));
                } elseif ($_tmp < 86400) {
                    $date = strftime("%e %b %Y %H:%M %Z", strtotime($this->data['Order Last Updated by Customer'].' +0:00'));
                } else {
                    $date = strftime("%e %b %Y", strtotime($this->data['Order Last Updated by Customer'].' +0:00'));
                }

                return $date;


            case 'Tax Description':


                $tax_category = new \Aurora\Models\Utils\TaxCategory($this->db);
                $tax_category->loadWithKey($this->data['Order Tax Category Key']);


                switch ($tax_category->get('Tax Category Type')) {
                    case 'Outside':
                        $tax_description = _('Outside the scope of tax');
                        break;
                    case 'EU_VTC':
                        $tax_description = sprintf(_('EU with %s'), $this->get('Tax Number Formatted'));
                        break;
                    default:
                        $tax_description = '<small class="discreet">'.$tax_category->get('Tax Category Code').'</small> '.$tax_category->get('Tax Category Name');
                }

                return $tax_description;
            case 'Tax Description With Warnings':
                $tax_description = $this->get('Tax Description');

                if ($this->metadata('original_tax_code') != '' and $this->metadata('original_tax_code') != $this->get('Order Tax Code')) {
                    $tax_description = '<span class="error italic"> <i class="fa fa-exclamation-circle error"></i> ('._('Edited').')</span> '.$tax_description;
                }

                return $tax_description;
            case 'Margin':

                if (is_numeric($this->data['Order Margin'])) {
                    return percentage($this->data['Order Margin'], 1);
                } else {
                    return '';
                }


            case 'Cash on Delivery Expected Payment Amount':

                $cash_on_delivery_to_pay_account = 0;

                if ($this->data['Order Checkout Block Payment Account Key'] != '' and $this->data['Order To Pay Amount'] != 0) {
                    $payment_account = get_object('Payment_Account', $this->data['Order Checkout Block Payment Account Key']);

                    if ($payment_account->get('Payment Account Block') == 'ConD') {
                        $cash_on_delivery_to_pay_account = $this->data['Order To Pay Amount'];
                    }
                }

                return $cash_on_delivery_to_pay_account;

            case 'Expected Payment Amount':


                if ($this->data['Order Checkout Block Payment Account Key'] != '' and $this->data['Order To Pay Amount'] != 0) {
                    $payment_account = get_object('Payment_Account', $this->data['Order Checkout Block Payment Account Key']);

                    switch ($payment_account->get('Payment Account Block')) {
                        case 'ConD':
                            return _('Cash on delivery').' (<b>'.money($this->data['Order To Pay Amount'], $this->data['Order Currency']).'</b>)';

                        case 'Bank':
                            return sprintf(_('Waiting for a %s bank transfer'), '<b>'.money($this->data['Order To Pay Amount'], $this->data['Order Currency']).'</b>');

                        case 'Cash':
                            return sprintf(_('Will pay %s with cash'), '<b>'.money($this->data['Order To Pay Amount'], $this->data['Order Currency']).'</b>');

                        default:
                            return '';
                    }
                } else {
                    return '';
                }

            case 'Expected Payment':


                if ($this->data['Order Checkout Block Payment Account Key'] != '' and $this->data['Order To Pay Amount'] != 0) {
                    $payment_account = get_object('Payment_Account', $this->data['Order Checkout Block Payment Account Key']);

                    switch ($payment_account->get('Payment Account Block')) {
                        case 'ConD':
                            return '<i class="fa fa-handshake" aria-hidden="true"></i> '._('Cash on delivery');

                        case 'Bank':
                            return _('Waiting bank transfer');

                        case 'Hokodo':


                            $_payments = $this->get_payments('keys', 'Approving');
                            if (count($_payments) > 0) {
                                return _('Waiting hokodo approval').' ('.count($_payments).')';
                            } else {
                                return '';
                            }


                        case 'Cash':
                            return _('Will pay with cash');

                        default:
                            return '';
                    }
                } else {
                    return '';
                }


            case 'Items Discount Percentage':
                return percentage($this->data['Order Items Discount Amount'], $this->data['Order Items Gross Amount']);
            case 'Charges Discount Percentage':
                return percentage($this->data['Order Charges Discount Amount'], $this->data['Order Charges Gross Amount']);
            case 'Shipping Discount Percentage':
                return percentage($this->data['Order Shipping Discount Amount'], $this->data['Order Shipping Gross Amount']);
            case 'Insurance Discount Percentage':
                return percentage($this->data['Order Insurance Discount Amount'], $this->data['Order Insurance Gross Amount']);
            case 'Amount Off Percentage':
                return percentage($this->data['Order Deal Amount Off'], $this->data['Order Total Net Amount']);
            case 'Amount Off':
                return money($this->data['Order Deal Amount Off'], $this->data['Order Currency']);
            case 'Waiting days':
                return floor((gmdate('U') - strtotime($this->data['Order Submitted by Customer Date'].' +0:00')) / 3600 / 24);
            case 'Waiting days decimal':
                return number((gmdate('U') - strtotime($this->data['Order Submitted by Customer Date'].' +0:00')) / 3600 / 24, 1, true).' '._('days');

            case 'Currency Code':

                return $this->data['Order Currency'];

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
            case 'Clean Order Invoice Address':
            case 'Clean Order Delivery Address':

                if ($key == 'Order Delivery Address') {
                    $type = 'Delivery';
                } else {
                    $type = 'Invoice';
                }


                $address_fields = array(

                    'Address Recipient'            => preg_replace('/"/', ' ', $this->get($type.' Address Recipient')),
                    'Address Organization'         => preg_replace('/"/', ' ', $this->get($type.' Address Organization')),
                    'Address Line 1'               => preg_replace('/"/', ' ', $this->get($type.' Address Line 1')),
                    'Address Line 2'               => preg_replace('/"/', ' ', $this->get($type.' Address Line 2')),
                    'Address Sorting Code'         => $this->get($type.' Address Sorting Code'),
                    'Address Postal Code'          => $this->get($type.' Address Postal Code'),
                    'Address Dependent Locality'   => preg_replace('/"/', ' ', $this->get($type.' Address Dependent Locality')),
                    'Address Locality'             => preg_replace('/"/', ' ', $this->get($type.' Address Locality')),
                    'Address Administrative Area'  => preg_replace('/"/', ' ', $this->get($type.' Address Administrative Area')),
                    'Address Country 2 Alpha Code' => $this->get($type.' Address Country 2 Alpha Code'),


                );

                return json_encode($address_fields);
            case 'Invoice Address':
            case 'Delivery Address':

                return $this->get('Order '.$key.' Formatted');
            case ('State Index'):


                switch ($this->data['Order State']) {
                    case 'InBasket':
                        return 10;


                    case 'InProcess':
                        return 30;

                    case 'InWarehouse':
                        return 40;
                    case 'Packed':
                        return 50;

                    case 'PackedDone':
                        return 80;

                    case 'Approved':
                        return 90;

                    case 'Dispatched':
                        return 100;

                    case 'Cancelled':
                        return -10;


                    default:
                        return 0;
                }
            case ('Icon'):


                switch ($this->data['Order State']) {
                    case 'InBasket':
                        return 'fal fa-shopping-basket';


                    case 'InProcess':
                        return 'fal fa-clock yellow';

                    case 'InWarehouse':
                        return 'fal fa-warehouse';

                    case 'Packed':
                        return 'fal fa-box';

                    case 'PackedDone':
                        return 'fal fa-box-check';

                    case 'Approved':
                        return 'fal fa-box-usd';

                    case 'Dispatched':
                        return 'fas fa-paper-plane green';

                    case 'Cancelled':
                        return 'fal fa-minus-circle red';


                    default:
                        return 'fal fa-shopping-cart';
                }

            case('DC Total Amount'):
                $account = get_object('Account', 1);

                return money(
                    $this->data['Order Total Amount'] * $this->data['Order Currency Exchange'],
                    $account->get('Currency Code')
                );


            case("Sticky Note"):
                return nl2br($this->data['Order Sticky Note']);

            case('Deal Amount Off'):
                return money(
                    -1 * $this->data['Order Deal Amount Off'],
                    $this->currency_code
                );
            case('Items Gross Amount After No Shipped'):
                return money(
                    $this->data['Order Items Gross Amount'] - $this->data['Order Out of Stock Net Amount'],
                    $this->currency_code
                );


            case('Order Out of Stock Amount'):
                return $this->data['Order Out of Stock Net Amount'] + $this->data['Order Out of Stock Tax Amount'];
            case('Out of Stock Amount'):
                return money(
                    -1 * ($this->data['Order Out of Stock Net Amount'] + $this->data['Order Out of Stock Tax Amount']),
                    $this->data['Order Currency']
                );
            case('Invoiced Total Tax Amount'):
                return money(
                    $this->data['Order Invoiced Tax Amount'],
                    $this->data['Order Currency']
                );

            case('Out of Stock Net Amount'):
                return money(
                    -1 * $this->data['Order Out of Stock Net Amount'],
                    $this->data['Order Currency']
                );

            case('Not Found Net Amount'):
                return money(
                    -1 * $this->data['Order Not Found Net Amount'],
                    $this->data['Order Currency']
                );

            case('Not Due Other Net Amount'):
                return money(
                    -1 * $this->data['Order Not Due Other Net Amount'],
                    $this->data['Order Currency']
                );

            case('No Authorized Net Amount'):
                return money(
                    -1 * $this->data['Order No Authorized Net Amount'],
                    $this->data['Order Currency']
                );

            case('Invoiced Total Net Amount'):
                return money(
                    $this->data['Order Invoiced Net Amount'],
                    $this->data['Order Currency']
                );

            case('Invoiced Total Amount'):
                return money(
                    $this->data['Order Invoiced Net Amount'] + $this->data['Order Invoiced Tax Amount'],
                    $this->data['Order Currency']
                );

            case ('Invoiced Refund Total Amount'):
                return money(
                    $this->data['Order Invoiced Refund Net Amount'] + $this->data['Order Invoiced Refund Tax Amount'],
                    $this->data['Order Currency']
                );


            case('Total Balance'):
            case('Total Refunds'):
                return money(
                    $this->data['Order '.$key],
                    $this->data['Order Currency']
                );


            case('To Pay Amount Absolute'):
                return money(
                    abs($this->data['Order To Pay Amount']),
                    $this->data['Order Currency']
                );

            case('Order To Pay Amount Absolute'):
                return abs($this->data['Order To Pay Amount']);

            case('Shipping And Handing Net Amount'):
                return money($this->data['Order Shipping Net Amount'] + $this->data['Order Charges Net Amount']);

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
            case('Invoiced Date'):
                if ($this->data['Order '.$key] == '') {
                    return '';
                } else {
                    return strftime("%e %b %y %H:%M", strtotime($this->data['Order '.$key].' +0:00'));
                }


            case ('Estimated Weight'):
                include_once 'utils/natural_language.php';

                return "&#8494;".smart_weight($this->data['Order Estimated Weight'], 1);

            case ('State'):


                switch ($this->data['Order State']) {
                    case('InBasket'):
                        $state = _('In Basket');
                        break;
                    case('InProcess'):
                        $state = _('Submitted');
                        break;
                    case('InWarehouse'):
                        $state = _('In Warehouse');
                        break;
                    case('PackedDone'):
                        $state = _('Packed & Closed');
                        break;
                    case('Approved'):
                        $state = _('Invoiced');
                        break;
                    case('Dispatch Approved'):
                        $state = _('Dispatch Approved');
                        break;
                    case('Dispatched'):

                        if ($this->data['Order For Collection'] == 'Yes') {
                            $state = _('Collected');
                        } else {
                            $state = _('Dispatched');
                        }

                        break;
                    case('Cancelled'):
                        $state = _('Cancelled');
                        break;
                    default:
                        $state = $this->data['Order State'];
                }

                return $state;


            case 'Number Items':
            case 'Number Items Out of Stock':
            case 'Number Items Returned':
            case 'Number Items with Deals':

                return number($this->data['Order '.$key]);


            case 'Pinned Deal Deal Components':

                if ($this->data['Order Pinned Deal Components'] == '') {
                    return array();
                } else {
                    return json_decode($this->data['Order Pinned Deal Components'], true);
                }


            case 'Available Credit Amount':

                if ($this->data['Order Total Amount'] > $this->data['Order Available Credit Amount']) {
                    return money(-1 * $this->data['Order Available Credit Amount'], $this->data['Order Currency']);
                } else {
                    return money(-1 * $this->data['Order Total Amount'], $this->data['Order Currency']);
                }


            case 'Basket To Pay Amount':


                if ($this->data['Order To Pay Amount'] > $this->data['Order Available Credit Amount']) {
                    return money($this->data['Order To Pay Amount'] - $this->data['Order Available Credit Amount'], $this->data['Order Currency']);
                } else {
                    return money(0, $this->data['Order Currency']);
                }


            case 'Order Basket To Pay Amount':

                if ($this->data['Order To Pay Amount'] > $this->data['Order Available Credit Amount']) {
                    return $this->data['Order To Pay Amount'] - $this->data['Order Available Credit Amount'];
                } else {
                    return 0;
                }


            case 'Order Hanging Charges Net Amount':

                $amount = 0;
                $sql    = sprintf(
                    "select sum(`Transaction Net Amount`) as amount from `Order No Product Transaction Fact`  left join `Charge Dimension` on (`Charge Key`=`Transaction Type Key`)   where  ( `Charge Scope`='Hanging' or `Transaction Type Key` is null  )and  `Transaction Type`='Charges' and `Order Key`=%d  ",
                    $this->id
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $amount = $row['amount'];
                    }
                }

                return $amount;


            case 'replacements_in_process':
                return $this->data['Order Replacements In Warehouse without Alerts'] + $this->data['Order Replacements In Warehouse with Alerts'] + $this->data['Order Replacements Packed Done'] + $this->data['Order Replacements Approved'];


            case('Tax Number Valid'):
                if ($this->data['Order Tax Number'] != '') {
                    if ($this->data['Order Tax Number Validation Date'] != '') {
                        $_tmp = gmdate("U") - gmdate(
                                "U",
                                strtotime(
                                    $this->data['Order Tax Number Validation Date'].' +0:00'
                                )
                            );
                        if ($_tmp < 3600) {
                            $date = strftime("%e %b %Y %H:%M:%S %Z", strtotime($this->data['Order Tax Number Validation Date'].' +0:00'));
                        } elseif ($_tmp < 86400) {
                            $date = strftime(
                                "%e %b %Y %H:%M %Z",
                                strtotime(
                                    $this->data['Order Tax Number Validation Date'].' +0:00'
                                )
                            );
                        } else {
                            $date = strftime(
                                "%e %b %Y",
                                strtotime(
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
                        $source = '<i title=\''._('Validated online').'\' class=\'far fa-globe\'></i>';
                    } elseif ($this->data['Order Tax Number Validation Source'] == 'Staff') {
                        $source = '<i title=\''._('Set up manually').'\' class=\'far fa-thumbtack\'></i>';
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

                        case 'Yes':
                            return _('Validated').$validation_data;

                        case 'No':
                            return _('Not valid').$validation_data;
                        default:
                            return $this->data['Order Tax Number Valid'].$validation_data;
                    }
                } else {
                    return '';
                }


            case 'Tax Number Formatted':


                switch ($this->data['Order Tax Number Validation Source']) {
                    case 'Online':
                        $source = ' <i class="fal fa-globe"></i>';
                        break;
                    case 'Staff':
                        $source = ' <i class="fal fa-thumbtack"></i>';
                        break;
                    default:
                        $source = '';
                }

                if ($this->data['Order Tax Number Validation Date'] != '') {
                    $_tmp = gmdate("U") - gmdate("U", strtotime($this->data['Order Tax Number Validation Date'].' +0:00'));
                    if ($_tmp < 3600) {
                        $date = strftime("%e %b %Y %H:%M:%S %Z", strtotime($this->data['Order Tax Number Validation Date'].' +0:00'));
                    } elseif ($_tmp < 86400) {
                        $date = strftime("%e %b %Y %H:%M %Z", strtotime($this->data['Order Tax Number Validation Date'].' +0:00'));
                    } else {
                        $date = strftime("%e %b %Y", strtotime($this->data['Order Tax Number Validation Date'].' +0:00'));
                    }
                } else {
                    $date = '';
                }

                $msg = $this->data['Order Tax Number Validation Message'];

                $title = htmlspecialchars(trim($date.' '.$msg));

                if ($this->data['Order Tax Number'] != '') {
                    if ($this->data['Order Tax Number Valid'] == 'Yes') {
                        return sprintf(
                            '<i style="margin-right: 0" class="fa fa-check success" title="'._('Valid').'"></i> <span title="'.$title.'" >%s</span>',
                            $this->data['Order Tax Number'].$source
                        );
                    } elseif ($this->data['Order Tax Number Valid'] == 'Unknown') {
                        return sprintf(
                            '<i style="margin-right: 0" class="fal fa-question-circle discreet" title="'._('Unknown if is valid').'"></i> <span class="discreet" title="'.$title.'">%s</span>',
                            $this->data['Order Tax Number'].$source
                        );
                    } elseif ($this->data['Order Tax Number Valid'] == 'API_Down') {
                        return sprintf(
                            '<i style="margin-right: 0"  class="fal fa-question-circle discreet" title="'._('Validity is unknown').'"> </i> <span class="discreet" title="'.$title.'">%s</span> %s',
                            $this->data['Order Tax Number'],
                            ' <i  title="'._('Online validation service down').'" class="fa fa-wifi-slash error"></i>'
                        );
                    } else {
                        return sprintf(
                            '<i style="margin-right: 0" class="fa fa-ban error" title="'._('Invalid').'"></i> <span class="discreet" title="'.$title.'">%s</span>',
                            $this->data['Order Tax Number'].$source
                        );
                    }
                }

                break;


            case 'Recargo Equivalencia':
                if ($this->metadata('RE') == 'Yes') {
                    return _('Yes');
                } else {
                    return _('No');
                }


            case 'Order Recargo Equivalencia':
                if ($this->metadata('RE') == 'Yes') {
                    return 'Yes';
                } else {
                    return 'No';
                }
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


    function post_operation_order_totals_changed()
    {
        $this->update_metadata = array(
            'class_html' => array(
                'Order_Tax_Number_Formatted' => $this->get('Tax Number Formatted'),
                'Tax_Description'            => $this->get('Tax Description'),
                'Total_Tax_Amount'           => $this->get('Total Tax Amount'),
                'Total_Amount'               => $this->get('Total Amount'),
                'To_Pay_Amount_Absolute'     => $this->get('To Pay Amount Absolute')
            )
        );

        if ($this->get('Order To Pay Amount') == 0) {
            $this->update_metadata['hide'] = array(
                'Order_To_Pay_Amount',
                'Order_Payments_Amount'
            );
            if ($this->get('Order Total Amount') == 0) {
                array_push($this->update_metadata['hide'], 'Order_Paid');
            } else {
                $this->update_metadata['show'] = array('Order_Paid');
            }
        } elseif ($this->get('Order To Pay Amount') > 0) {
            $this->update_metadata['show'] = array(
                'Order_To_Pay_Amount',
                'To_Pay_Label',
                'Order_Payments_Amount'
            );
            $this->update_metadata['hide'] = array(
                'To_Refund_Label',
                'Order_Paid'
            );
        } elseif ($this->get('Order To Pay Amount') < 0) {
            $this->update_metadata['show'] = array(
                'Order_To_Pay_Amount',
                'To_Refund_Label',
                'Order_Payments_Amount'
            );
            $this->update_metadata['hide'] = array(
                'To_Pay_Label',
                'Order_Paid'
            );
        }
    }

    function update_state($value, $options = '{}', $metadata = array())
    {
        include_once 'utils/new_fork.php';

        $store = get_object('Store', $this->data['Order Store Key']);

        $options = json_decode($options, true);
        if (!empty($options['date'])) {
            $date = $options['date'];
        } else {
            $date = gmdate('Y-m-d H:i:s');
        }

        $hide = array();
        $show = array();

        $account = get_object('Account', 1);
        $account->load_properties();

        $old_value         = $this->get('Order State');
        $operations        = array();
        $deliveries_xhtml  = '';
        $number_deliveries = 0;
        $invoices_xhtml    = '';
        $number_invoices   = 0;


        if ($old_value != $value) {
            switch ($value) {
                case 'Cancelled':
                    $this->updated = $this->cancel();
                    break;
                case 'InBasket':


                    if ($this->data['Order State'] != 'InProcess') {
                        $this->error = true;
                        $this->msg   = 'Order is not in process: :(';

                        return false;
                    }
                    $this->fast_update(array(
                        'Order State'                      => $value,
                        'Order Submitted by Customer Date' => '',
                        'Order Date'                       => $date,
                        'Order Last Updated by Customer'   => $date,
                    ));


                    $history_data = array(
                        'History Abstract' => _('Order send back to basket'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id);

                    $operations = array(
                        'send_to_warehouse_operations',
                        'cancel_operations',
                        'submit_operations'
                    );


                    $sql = sprintf(
                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='In Process' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('Out of Stock in Basket')  ",
                        $this->id


                    );

                    // todo check 'Out of Stock in Basket' etc

                    $this->db->exec($sql);


                    //todo if user want to keep old allowances  you must do an if here
                    $this->fast_update(array(
                        'Order Pinned Deal Components' => json_encode(array())
                    ));


                    $sql = sprintf(
                        "UPDATE `Order Transaction Deal Bridge` SET `Order Transaction Deal Pinned`='No' WHERE `Order Key`=%d   ",


                        $this->id
                    );

                    $this->db->exec($sql);

                    $old_used_deals = $this->get_used_deals();

                    $this->update_totals();
                    $this->update_discounts_items();
                    $this->update_totals();
                    $this->update_shipping();
                    $this->update_charges(false, false);
                    $this->update_discounts_no_items();
                    $this->update_deal_bridge();

                    $new_used_deals = $this->get_used_deals();


                    $intersect      = array_intersect($old_used_deals[0], $new_used_deals[0]);
                    $campaigns_diff = array_merge(array_diff($old_used_deals[0], $intersect), array_diff($new_used_deals[0], $intersect));

                    $intersect = array_intersect($old_used_deals[1], $new_used_deals[1]);
                    $deal_diff = array_merge(array_diff($old_used_deals[1], $intersect), array_diff($new_used_deals[1], $intersect));

                    $intersect            = array_intersect($old_used_deals[2], $new_used_deals[2]);
                    $deal_components_diff = array_merge(array_diff($old_used_deals[2], $intersect), array_diff($new_used_deals[2], $intersect));


                    if (count($campaigns_diff) > 0 or count($deal_diff) > 0 or count($deal_components_diff) > 0) {
                        $account = get_object('Account', '');

                        require_once 'utils/new_fork.php';
                        new_housekeeping_fork(
                            'au_housekeeping',
                            array(
                                'type'            => 'update_deals_usage',
                                'campaigns'       => $campaigns_diff,
                                'deals'           => $deal_diff,
                                'deal_components' => $deal_components_diff,


                            ),
                            $account->get('Account Code'),
                            $this->db
                        );
                    }

                    $this->update_totals();

                    $show = array('customer_balance');

                    if (count($this->get_payments('keys', 'Completed')) == 0) {
                        $hide = array(
                            'order_payments_list',
                            'payment_overview'
                        );
                    }
                    $customer = get_object('Customer', $this->data['Order Customer Key']);
                    $customer->update_orders();
                    $customer->update_activity();

                    break;
                case 'InProcess':
                case 'Delivery_Note_deleted':


                    if ($this->data['Order State'] == 'Cancelled' or $this->data['Order State'] == 'Dispatched') {
                        $this->error = true;
                        $this->msg   = 'Cant set as in process :(';

                        return false;
                    }

                    $this->fast_update(array(
                        'Order State' => 'InProcess',
                        'Order Date'  => $date
                    ));


                    if ($value == 'InProcess') {
                        $this->fast_update(array(
                            'Order Submitted by Customer Date' => $date,
                        ));


                        $history_data = array(
                            'History Abstract' => _('Order submitted'),
                            'History Details'  => '',
                        );
                    } else {
                        $history_data = array(
                            'History Abstract' => _('Delivery note deleted'),
                            'History Details'  => '',
                        );
                    }


                    $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

                    $operations = array(
                        'send_to_warehouse_operations',
                        'cancel_operations',
                        'undo_submit_operations'
                    );

                    $sql = sprintf(
                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Submitted by Customer' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('Out of Stock in Basket')  ",


                        $this->id
                    );

                    $this->db->exec($sql);

                    $deals_component_data = array();

                    $deal_components = '';

                    $sql = sprintf('select group_concat(`Deal Component Key`) as deal_components from `Order Transaction Deal Bridge` where `Order Key`=%d ', $this->id);
                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $deal_components = $row['deal_components'];
                        }
                    }


                    $sql = sprintf('select group_concat(`Deal Component Key`) as deal_components from `Order No Product Transaction Deal Bridge` where `Order Key`=%d ', $this->id);
                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            if ($row['deal_components'] != '') {
                                if ($deal_components == '') {
                                    $deal_components = $row['deal_components'];
                                } else {
                                    $deal_components .= ','.$row['deal_components'];
                                }
                            }
                        }
                    }

                    if ($deal_components != '') {
                        $sql = sprintf(
                            "select * from `Deal Component Dimension` left join `Deal Dimension` D on (D.`Deal Key`=`Deal Component Deal Key`)  where `Deal Component Key` in (%s)",
                            $deal_components
                        );


                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {
                                $deals_component_data[$row['Deal Component Key']] = $row;
                            }
                        }
                    }


                    $sql = sprintf(
                        "UPDATE `Order Transaction Deal Bridge` SET `Order Transaction Deal Pinned`='Yes' WHERE `Order Key`=%d   ",
                        $this->id
                    );
                    $this->db->exec($sql);
                    $sql = sprintf(
                        "UPDATE `Order No Product Transaction Deal Bridge` SET `Order No Product Transaction Deal Pinned`='Yes' WHERE `Order Key`=%d   ",
                        $this->id
                    );

                    $this->db->exec($sql);

                    $this->fast_update(array(
                        'Order Pinned Deal Components' => json_encode($deals_component_data)
                    ));

                    $hide = array('customer_balance');

                    $show = array(
                        'order_payments_list',
                        'payment_overview'
                    );


                    $customer = get_object('Customer', $this->data['Order Customer Key']);
                    $customer->update_orders();
                    $customer->update_activity();


                    break;


                case 'Delivery Note Cancelled':


                    if ($this->data['Order State'] == 'Cancelled') {
                        return false;
                    }


                    if ($this->get('Order State') >= 90) {
                        $this->error = true;
                        $this->msg   = 'Cant set as back to in process :(';

                        return false;
                    }

                    $value = 'InProcess';

                    $this->update_field('Order State', $value, 'no_history');
                    $this->update_field('Order Date', $date, 'no_history');


                    $this->update_field('Order Send to Warehouse Date', '', 'no_history');
                    $this->update_field('Order Packed Done Date', '', 'no_history');


                    $this->update_field('Order Date', $this->data['Order Submitted by Customer Date'], 'no_history');
                    $this->update_field('Order Delivery Note Key', '', 'no_history');


                    $history_data = array(
                        'History Abstract' => _('Delivery note cancelled, order back to submitted state'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

                    $operations = array(
                        'send_to_warehouse_operations',
                        'cancel_operations',
                        'undo_submit_operations'
                    );

                    $sql = sprintf(


                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Submitted by Customer' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('Out of Stock in Basket')  ",
                        $this->id

                    );

                    $this->db->exec($sql);

                    /** @var Customer $customer */
                    $customer = get_object('Customer', $this->data['Order Customer Key']);
                    $customer->update_last_dispatched_order_key();


                    break;

                case 'InWarehouse':


                    $warehouse_key = $_SESSION['current_warehouse'];

                    include_once('class.DeliveryNote.php');


                    if (!($this->data['Order State'] == 'InProcess' or $this->data['Order State'] == 'InBasket')) {
                        $this->error = true;
                        $this->msg   = 'Order is not in process';

                        return false;
                    }


                    if ($this->data['Order For Collection'] == 'Yes') {
                        $dispatch_method = 'Collection';
                    } else {
                        $dispatch_method = 'Dispatch';
                    }


                    $telephone = $this->data['Order Telephone'];
                    $email     = $this->data['Order Email'];
                    if ($this->data['Order Customer Client Key']) {
                        $client = get_object('Customer Client', $this->data['Order Customer Client Key']);
                        if ($client->id) {
                            $telephone = $client->get_telephone();
                            $email     = $client->get('Customer Client Main Plain Email');
                        }
                    }

                    $data_dn = array(
                        'editor'                      => $this->editor,
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
                        'Delivery Note Customer Key'                 => $this->data['Order Customer Key'],
                        'Delivery Note Metadata'                     => $this->data['Order Original Metadata'],
                        'Delivery Note Customer Name'                => $this->data['Order Customer Name'],
                        'Delivery Note Customer Contact Name'        => $this->data['Order Customer Contact Name'],
                        'Delivery Note Telephone'                    => $telephone,
                        'Delivery Note Email'                        => $email,
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
                        'Delivery Note External Invoicer Key'        => $this->data['Order External Invoicer Key'],

                    );

                    /** @var DeliveryNote $dn */
                    $delivery_note                       = new DeliveryNote('create', $data_dn, $this);
                    $delivery_note_key_send_to_warehouse = $delivery_note->id;

                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'              => 'delivery_note_created',
                            'delivery_note_key' => $delivery_note->id,
                            'customer_key'      => $delivery_note->get('Delivery Note Customer Key'),
                            'store_key'         => $delivery_note->get('Delivery Note Store Key'),
                        ),
                        $account->get('Account Code')
                    );


                    if ($this->get('Order State') == 'InBasket') {
                        $this->update_field('Order Submitted by Customer Date', $date);
                    }
                    $this->update_field('Order State', $value, 'no_history');
                    $this->update_field('Order Send to Warehouse Date', $date, 'no_history');
                    $this->update_field('Order Date', $date, 'no_history');
                    $this->update_field('Order Delivery Note Key', $delivery_note->id, 'no_history');


                    $history_data = array(
                        'History Abstract' => _('Order send to warehouse'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, true, 'No', 'Changes', $this->table_name, $this->id);


                    $operations = array('cancel_operations');


                    $sql = sprintf(


                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Ready to Pick' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('Out of Stock in Basket')  ",
                        $this->id


                    );

                    $this->db->exec($sql);


                    break;

                case 'Packed':


                    if ($this->data['Order State'] != 'InWarehouse') {
                        $this->error = true;
                        $this->msg   = 'Order is not in warehouse: :(';

                        return false;
                    }

                    $this->fast_update([
                        'Order State'       => 'Packed',
                        'Order Packed Date' => $date,
                        'Order Date'        => $date,
                    ]);

                    $history_data = array(
                        'History Abstract' => _('Order packed and closed'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

                    $this->update_totals();

                    break;

                case 'PackedDone':


                    if (!($this->data['Order State'] == 'InWarehouse' or $this->data['Order State'] == 'Packed')) {
                        $this->error = true;
                        $this->msg   = 'Order is not in warehouse: or packed :(';

                        return false;
                    }


                    $this->update_field('Order State', $value, 'no_history');
                    $this->update_field('Order Packed Done Date', $date, 'no_history');
                    $this->update_field('Order Date', $date, 'no_history');


                    $history_data = array(
                        'History Abstract' => _('Order packing checked'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

                    $operations = array(
                        'invoice_operations',
                        'cancel_operations'
                    );

                    $sql = sprintf(
                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Packed Done' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Out of Stock in Basket')  ",
                        $this->id

                    );

                    $this->db->exec($sql);

                    $this->update_totals();

                    break;

                case 'Undo PackedDone':


                    if ($this->data['Order State'] != 'PackedDone') {
                        $this->error = true;
                        $this->msg   = 'Order is PackedDone: :(';

                        return false;
                    }


                    $this->update_field('Order State', 'InWarehouse', 'no_history');
                    $this->update_field('Order Packed Done Date', '', 'no_history');
                    $this->update_field('Order Date', $date, 'no_history');


                    $history_data = array(
                        'History Abstract' => _('Undo packed and closed'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

                    $operations = array(
                        'cancel_operations'
                    );


                    $this->update_totals();

                    break;

                case 'Invoice Deleted':


                    switch ($this->get('Order State')) {
                        case 'Approved':

                            $this->fast_update(array(
                                'Order State'            => 'PackedDone',
                                'Order Packed Done Date' => $date,
                                'Order Invoiced Date'    => '',
                                'Order Invoice Key'      => '',
                                'Order Date'             => $date
                            ));


                            $history_data = array(
                                'History Abstract' => _('Invoice deleted, order state back to packed and closed'),
                                'History Details'  => '',
                            );
                            $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

                            $operations = array(
                                'invoice_operations',
                                'cancel_operations'
                            );

                            $sql = sprintf(
                                "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Packed Done' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Out of Stock in Basket')  ",
                                $this->id


                            );

                            $this->db->exec($sql);
                            break;

                        case 'Dispatched':


                            $this->fast_update(array(
                                'Order Invoiced Date' => '',
                                'Order Invoice Key'   => '',
                                'Order Date'          => $date
                            ));


                            $history_data = array(
                                'History Abstract' => _('Invoice deleted'),
                                'History Details'  => '',
                            );
                            $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

                            $operations = array();





                            break;
                        default:
                            $this->error = true;
                            $this->msg   = 'Order is not in Approved: :(';

                            return false;
                    }

                    $this->fast_update_json_field('Order Metadata', 'original_tax_code', '');
                    $this->fast_update_json_field('Order Metadata', 'original_tax_description', '');

                    $dn         = get_object('DeliveryNote', $this->data['Order Delivery Note Key']);
                    $dn->editor = $this->editor;
                    $dn->fast_update(array(
                        'Delivery Note Invoiced'                    => 'No',
                        'Delivery Note Invoiced Net DC Amount'      => 0,
                        'Delivery Note Invoiced Shipping DC Amount' => 0,
                    ));

                    $dn->update(array(

                        'Delivery Note State' => 'Invoice Deleted',
                    ));


                    break;


                case 'InvoiceServices':
                    include_once('class.Invoice.php');
                    if (!$this->data['Order State'] == 'InProcess') {
                        $this->error = true;
                        $this->msg   = 'Order is not in process';

                        return false;
                    }


                    $extra_data = [];

                    $this->fast_update_json_field('Order Metadata', 'ups', false);


                    $invoice = $this->create_invoice($date, $extra_data);


                    $this->update_field('Order Invoiced Date', $date, 'no_history');
                    $this->update_field('Order Date', $date, 'no_history');
                    $this->update_field('Order Invoice Key', $invoice->id, 'no_history');


                    $this->fast_update_json_field('Order Metadata', 'original_tax_code', $this->get('Order Tax Code'));
                    $this->fast_update_json_field('Order Metadata', 'original_tax_description', $this->get('Tax Description'));

                    $history_data = array(
                        'History Abstract' => _('Order invoiced'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, true, 'No', 'Changes', $this->table_name, $this->id);


                    $sql = sprintf(

                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Ready to Ship' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Out of Stock in Basket')  ",
                        $this->id

                    );

                    $this->db->exec($sql);


                    $this->update_field('Order State', 'Dispatched', 'no_history');
                    $this->update_field('Order Dispatched Date', $date, 'no_history');


                    $history_data = array(
                        'History Abstract' => _('Order completed'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

                    $operations = array();


                    $sql = sprintf(
                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Dispatched' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Out of Stock in Basket')  ",
                        $this->id

                    );

                    $this->db->exec($sql);


                    $customer = get_object('Customer', $this->data['Order Customer Key']);


                    $customer->fast_update(array(
                        'Customer Last Dispatched Order Key'  => $this->id,
                        'Customer Last Dispatched Order Date' => gmdate('Y-m-d', strtotime($date.' +0:00'))
                    ));


                    if ($this->data['Order Type'] == 'FulfilmentRent') {
                        $sql =
                            "update `Fulfilment Asset Dimension` left join `Fulfilment Rent Transaction Fact` on (`Fulfilment Rent Transaction Asset Key`=`Fulfilment Asset Key`) set `Fulfilment Asset Last Rent Order Date`=?   where `Fulfilment Rent Transaction Order Key`=?  ";
                        $this->db->prepare($sql)->execute(
                            [
                                gmdate('Y-m-d'),
                                $this->id
                            ]
                        );


                        $sql =
                            "update `Fulfilment Asset Dimension` left join `Fulfilment Rent Transaction Fact` on (`Fulfilment Rent Transaction Asset Key`=`Fulfilment Asset Key`) set `Fulfilment Asset State`='Invoiced'    where `Fulfilment Rent Transaction Order Key`=?  and `Fulfilment Asset State`='BookedOut'   ";
                        $this->db->prepare($sql)->execute(
                            [
                                $this->id
                            ]
                        );

                        $customer = get_object('Customer_Fulfilment', $this->data['Order Customer Key']);
                        $customer->fast_update([
                            'Customer Fulfilment Current Rent Order Key' => null
                        ]);
                    }


                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'      => 'order_completed',
                            'order_key' => $this->id,
                        ),
                        $account->get('Account Code')
                    );


                    break;
                case 'Approved':


                    include_once('class.Invoice.php');
                    if (!$this->data['Order State'] == 'PackedDone') {
                        $this->error = true;
                        $this->msg   = 'Order is not in packed done';

                        return false;
                    }
                    $this->update_field('Order State', $value, 'no_history');

                    /** @var DeliveryNote $dn */
                    $dn = get_object('DeliveryNote', $this->data['Order Delivery Note Key']);

                    $extra_data = [];
                    if ($account->properties('ups_shipper_key') == $dn->get('Delivery Note Shipper Key')) {
                        $extra_data['ups']    = true;
                        $extra_data['dn_key'] = $dn->id;
                        $this->fast_update_json_field('Order Metadata', 'ups', true);
                    } else {
                        $this->fast_update_json_field('Order Metadata', 'ups', false);
                    }

                    $invoice = $this->create_invoice($date, $extra_data);

                    $dn->editor = $this->editor;

                    $dn->fast_update(array(
                        'Delivery Note Invoiced'                    => 'Yes',
                        'Delivery Note Invoiced Net DC Amount'      => $invoice->get('Invoice Total Net Amount') * $invoice->get('Invoice Currency Exchange'),
                        'Delivery Note Invoiced Shipping DC Amount' => $invoice->get('Invoice Shipping Net Amount') * $invoice->get('Invoice Currency Exchange'),
                    ));


                    $dn->update_state('Approved', json_encode(array('date' => $date)));


                    $this->update_field('Order Invoiced Date', $date, 'no_history');
                    $this->update_field('Order Date', $date, 'no_history');
                    $this->update_field('Order Invoice Key', $invoice->id, 'no_history');


                    $this->fast_update_json_field('Order Metadata', 'original_tax_code', $this->get('Order Tax Code'));
                    $this->fast_update_json_field('Order Metadata', 'original_tax_description', $this->get('Tax Description'));

                    $history_data = array(
                        'History Abstract' => _('Order invoiced'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, true, 'No', 'Changes', $this->table_name, $this->id);


                    $operations = array('cancel_operations');


                    $sql = sprintf(

                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Ready to Ship' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Out of Stock in Basket')  ",
                        $this->id

                    );

                    $this->db->exec($sql);


                    if ($this->data['hokodo_order_id']) {
                        $this->send_hokodo_invoice($invoice->id);
                    }


                    break;
                case 'un_dispatch':


                    if ($this->data['Order State'] != 'Dispatched') {
                        $this->error = true;
                        $this->msg   = 'Order is not in Dispatched: :(';

                        return false;
                    }


                    if ($this->get('Order Invoice Key')) {
                        $value = 'Approved';
                    } else {
                        $value = 'PackedDone';
                    }


                    $this->fast_update(array(
                        'Order State'           => $value,
                        'Order Dispatched Date' => null
                    ));
                    $history_data = array(
                        'History Abstract' => _('Order set as not dispatched'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

                    $operations = array();

                    $sql = sprintf(
                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Ready to Ship' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Out of Stock in Basket')  ",
                        $this->id
                    );

                    $this->db->exec($sql);

                    /** @var Customer $customer */
                    $customer = get_object('Customer', $this->data['Order Customer Key']);
                    $customer->update_last_dispatched_order_key();


                    break;

                case 'Dispatched':


                    if ($this->data['Order State'] != 'Approved') {
                        $this->error = true;
                        $this->msg   = 'Order is not in Approved: :(';

                        return false;
                    }


                    $this->update_field('Order State', $value, 'no_history');
                    $this->update_field('Order Dispatched Date', $date, 'no_history');


                    $history_data = array(
                        'History Abstract' => _('Order dispatched'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

                    $operations = array();


                    $sql = sprintf(
                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Dispatched' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Out of Stock in Basket')  ",
                        $this->id

                    );

                    $this->db->exec($sql);

                    $customer = get_object('Customer', $this->data['Order Customer Key']);


                    $customer->fast_update(array(
                        'Customer Last Dispatched Order Key'  => $this->id,
                        'Customer Last Dispatched Order Date' => gmdate('Y-m-d', strtotime($date.' +0:00'))
                    ));


                    $this->confirm_hokodo_order_fulfilment();

                    $pastpay=false;
                    foreach($this->get_payments('objects') as $payment){
                        if($payment->get('Payment Account Code')=='Pastpay'  and $payment->get('Payment Transaction Status')=='Completed' ){
                            $pastpay=true;
                        }

                    }


                    if ($pastpay) {
                        $this->submit_pastpay_invoice();
                    }


                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'              => 'order_dispatched',
                            'order_key'         => $this->id,
                            'delivery_note_key' => $metadata['delivery_note_key']
                        ),
                        $account->get('Account Code')
                    );


                    break;
                case 'ReInvoice':


                    if ($this->get('State Index') <= 80) {
                        $this->error = true;
                        $this->msg   = 'try invoice_recreated but Order has State Index <=80';

                        return false;
                    }

                    include_once('class.Invoice.php');


                    $dn = get_object('DeliveryNote', $this->data['Order Delivery Note Key']);


                    $extra_data = [];
                    if ($account->properties('ups_shipper_key') == $dn->get('Delivery Note Shipper Key')) {
                        $extra_data['ups']    = true;
                        $extra_data['dn_key'] = $dn->id;
                        $this->fast_update_json_field('Order Metadata', 'ups', true);
                    } else {
                        $this->fast_update_json_field('Order Metadata', 'ups', false);
                    }

                    $invoice = $this->create_invoice($date, $extra_data);

                    $dn->editor = $this->editor;

                    $dn->update(array(
                        'Delivery Note Invoiced'                    => 'Yes',
                        'Delivery Note Invoiced Net DC Amount'      => $invoice->get('Invoice Total Net Amount') * $invoice->get('Invoice Currency Exchange'),
                        'Delivery Note Invoiced Shipping DC Amount' => $invoice->get('Invoice Shipping Net Amount') * $invoice->get('Invoice Currency Exchange'),
                    ));


                    $this->fast_update(array(
                        'Order Invoiced Date' => $date,
                        'Order Date'          => $date,
                        'Order Invoice Key'   => $invoice->id,
                    ));


                    $history_data = array(
                        'History Abstract' => _('Order invoiced again'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, true, 'No', 'Changes', $this->table_name, $this->id);


                    $operations = array('');


                    break;

                default:
                    exit('unknown state:::'.$value);
            }
        }

        foreach ($this->get_deliveries('objects') as $dn) {
            $number_deliveries++;
            $deliveries_xhtml .= sprintf(
                ' <div class="node"  id="delivery_node_%d"><span class="node_label"><i class="fa fa-truck fa-flip-horizontal fa-fw" aria-hidden="true"></i> 
                               <span class="link" onClick="change_view(\'%s\')">%s</span> (<span class="Delivery_Note_State">%s</span>)
                                <a title="%s" class="pdf_link %s" target="_blank" href="/pdf/order_pick_aid.pdf.php?id=%d"> <i class="fal fa-clipboard-list-check " style="font-size: larger"></i></a>
                                <a class="pdf_link %s" target=\'_blank\' href="/pdf/dn.pdf.php?id=%d"> <img style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif" alt=""></a>
                               </span>
                                <div class="order_operation data_entry_delivery_note %s"><div class="square_button right" title="%s"><i class="fa fa-keyboard" aria-hidden="true" onclick="data_entry_delivery_note(%s)"></i></div></div>
                               </div>',
                $dn->id,
                'delivery_notes/'.$dn->get('Delivery Note Store Key').'/'.$dn->id,
                $dn->get('ID'),
                $dn->get('Abbreviated State'),
                _('Picking sheet'),
                ($dn->get('State Index') != 10 ? 'hide' : ''),
                $dn->id,
                ($dn->get('State Index') < 90 ? 'hide' : ''),
                $dn->id,
                (($dn->get('State Index') != 10 or $store->settings('data_entry_picking_aid') != 'Yes') ? 'hide' : ''),
                _('Input picking sheet data'),
                $dn->id

            );
        }

        foreach ($this->get_invoices('objects') as $invoice) {
            $number_invoices++;
            $invoices_xhtml .= sprintf(
                ' <div class="node" id="invoice_%d">
                    <span class="node_label" >
                        <i class="fal fa-file-invoice-dollar fa-fw %s" aria-hidden="true"></i>
                        <span class="link %s" onClick="change_view(\'%s\')">%s</span>
                        <img class="button pdf_link" onclick="download_pdf_from_ui($(\'.pdf_asset_dialog.invoice\'),\'invoice\',%d,\'invoice\')" style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif">
                        <i onclick="show_pdf_settings_dialog(this,\'invoice\',%d,\'invoice\')" title="%s" class="far very_discreet fa-sliders-h-square button"></i>
                    </span>
                    <div class="red" style="float: right;padding-right: 10px;padding-top: 5px">%s
                    </div>
                </div>',
                $invoice->id,
                ($invoice->get('Invoice Type') == 'Refund' ? 'error' : ''),
                ($invoice->get('Invoice Type') == 'Refund' ? 'error' : ''),
                'invoices/'.$invoice->get('Invoice Store Key').'/'.$invoice->id,
                $invoice->get('Invoice Public ID'),
                $invoice->id,
                $invoice->id,
                _('PDF invoice display settings'),
                ($invoice->get('Invoice Type') == 'Refund' ? $invoice->get('Refund Total Amount').' '.($invoice->get('Invoice Paid') != 'Yes' ? '<i class="fa fa-exclamation-triangle warning fa-fw" aria-hidden="true" title="'._(
                            'Return payment pending'
                        ).'"></i>' : '') : '')

            );
        }


        $this->update_metadata = array(
            'class_html'        => array(
                'Order_State'                  => $this->get('State'),
                'Order_Submitted_Date'         => '&nbsp;'.$this->get('Submitted by Customer Date'),
                'Order_Send_to_Warehouse_Date' => '&nbsp;'.$this->get('Send to Warehouse Date'),
                'Order_Invoiced_Date'          => '&nbsp;'.$this->get('Invoiced Date'),


            ),
            'operations'        => $operations,
            'state_index'       => $this->get('State Index'),
            'deliveries_xhtml'  => $deliveries_xhtml,
            'invoices_xhtml'    => $invoices_xhtml,
            'number_deliveries' => $number_deliveries,
            'number_invoices'   => $number_invoices,
            'hide'              => $hide,
            'show'              => $show
        );


        $this->model_updated( 'Order State', $this->id);

        if ($this->data['Order State'] != 'Cancelled') {
            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'      => 'order_state_changed',
                    'order_key' => $this->id,
                ),
                $account->get('Account Code')
            );
        }

        if ($old_value != $this->get('Order State')) {
            $this->fork_index_elastic_search();

            return $delivery_note_key_send_to_warehouse ?? true;
        } else {
            return false;
        }
    }

    /**
     * @throws ErrorException
     */
    function create_invoice($date, $extra_data = []): Invoice
    {
        $store   = get_object('Store', $this->data['Order Store Key']);
        $account = get_object('Account', 1);
        $account->load_properties();

        $id_done           = false;
        $invoice_public_id = '';
        $file_as           = '';

        if (isset($extra_data['ups']) and $extra_data['ups']) {
            if ($account->properties('ups_public_id_type') != '') {
                if ($account->properties('ups_public_id_type') == 'alt_account_field') {
                    $sql = "UPDATE `Account Dimension` SET `Account Invoice Last Invoice Alt Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Alt Public ID` + 1) WHERE `Account Key`=1";
                    $this->db->exec($sql);
                    $public_id = $this->db->lastInsertId();

                    $invoice_public_id = sprintf(
                        $account->properties('ups_public_id_invoice_format'),
                        $public_id
                    );

                    $file_as = get_file_as($invoice_public_id);
                    $id_done = true;
                }
            }
        }

        if (!$id_done) {
            if ($store->data['Store Next Invoice Public ID Method'] == 'Order ID') {
                $invoice_public_id = $this->data['Order Public ID'];
                $file_as           = $this->data['Order File As'];
            } elseif ($store->data['Store Next Invoice Public ID Method'] == 'Invoice Public ID') {
                $sql = sprintf(
                    "UPDATE `Store Dimension` SET `Store Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Store Invoice Last Invoice Public ID` + 1) WHERE `Store Key`=%d",
                    $this->data['Order Store Key']
                );
                $this->db->exec($sql);
                $public_id = $this->db->lastInsertId();

                $invoice_public_id = sprintf($store->data['Store Invoice Public ID Format'], $public_id);
                $file_as           = get_file_as($invoice_public_id);
            } else {
                $sql = "UPDATE `Account Dimension` SET `Account Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Public ID` + 1) WHERE `Account Key`=1";
                $this->db->exec($sql);
                $public_id = $this->db->lastInsertId();

                $invoice_public_id = sprintf(
                    $account->data['Account Invoice Public ID Format'],
                    $public_id
                );

                $file_as = get_file_as($invoice_public_id);
            }
        }


        $customer = get_object('Customer', $this->data['Order Customer Key']);
        $eori     = $customer->get('EORI');

        $data_invoice = array(
            'editor'                   => $this->editor,
            'Invoice Date'             => $date,
            'Invoice Type'             => 'Invoice',
            'Invoice Public ID'        => $invoice_public_id,
            'Invoice File As'          => $file_as,
            'Invoice Order Key'        => $this->id,
            'Invoice Store Key'        => $this->data['Order Store Key'],
            'Invoice Customer Key'     => $this->data['Order Customer Key'],
            'Invoice Tax Category Key' => $this->data['Order Tax Category Key'],
            'Invoice Tax Code'         => $this->data['Order Tax Code'],

            'Invoice Metadata'                      => '{}',
            'Invoice Tax Number'                    => $this->data['Order Tax Number'],
            'Invoice Tax Number Valid'              => $this->data['Order Tax Number Valid'],
            'Invoice Tax Number Validation Date'    => $this->data['Order Tax Number Validation Date'],
            'Invoice Tax Number Validation Source'  => $this->data['Order Tax Number Validation Source'],
            'Invoice Tax Number Validation Message' => $this->data['Order Tax Number Validation Message'],


            'Invoice Tax Number Associated Name'    => $this->data['Order Tax Number Associated Name'],
            'Invoice Tax Number Associated Address' => $this->data['Order Tax Number Associated Address'],


            'Invoice Net Amount Off'        => $this->data['Order Deal Amount Off'],
            'Invoice Customer Contact Name' => $this->data['Order Customer Contact Name'],
            'Invoice Customer Name'         => $this->data['Order Customer Name'],
            'Invoice Customer Level Type'   => $this->data['Order Customer Level Type'],


            'Invoice Sales Representative Key' => $this->data['Order Sales Representative Key'],


            'Invoice Address Recipient'            => $this->data['Order Invoice Address Recipient'],
            'Invoice Address Organization'         => $this->data['Order Invoice Address Organization'],
            'Invoice Address Line 1'               => $this->data['Order Invoice Address Line 1'],
            'Invoice Address Line 2'               => $this->data['Order Invoice Address Line 2'],
            'Invoice Address Sorting Code'         => $this->data['Order Invoice Address Sorting Code'],
            'Invoice Address Postal Code'          => $this->data['Order Invoice Address Postal Code'],
            'Invoice Address Dependent Locality'   => $this->data['Order Invoice Address Dependent Locality'],
            'Invoice Address Locality'             => $this->data['Order Invoice Address Locality'],
            'Invoice Address Administrative Area'  => $this->data['Order Invoice Address Administrative Area'],
            'Invoice Address Country 2 Alpha Code' => $this->data['Order Invoice Address Country 2 Alpha Code'],
            'Invoice Address Checksum'             => $this->data['Order Invoice Address Checksum'],
            'Invoice Address Formatted'            => $this->data['Order Invoice Address Formatted'],
            'Invoice Address Postal Label'         => $this->data['Order Invoice Address Postal Label'],
            'Invoice Registration Number'          => $this->data['Order Registration Number'],


            'Invoice Tax Liability Date' => $this->data['Order Packed Done Date'],


            'Invoice Items Gross Amount'        => $this->data['Order Items Gross Amount'],
            'Invoice Items Discount Amount'     => $this->data['Order Items Discount Amount'],
            'Invoice Items Net Amount'          => $this->data['Order Items Net Amount'],
            'Invoice Items Out of Stock Amount' => ($this->data['Order Items Out of Stock Amount'] == '' ? 0 : $this->data['Order Items Out of Stock Amount']),
            'Invoice Shipping Net Amount'       => $this->data['Order Shipping Net Amount'],
            'Invoice Charges Net Amount'        => $this->data['Order Charges Net Amount'],
            'Invoice Insurance Net Amount'      => $this->data['Order Insurance Net Amount'],
            'Invoice Total Net Amount'          => $this->data['Order Total Net Amount'],

            'Invoice Total Tax Amount' => $this->data['Order Total Tax Amount'],

            'Invoice Payments Amount'       => $this->data['Order Payments Amount'],
            'Invoice To Pay Amount'         => $this->data['Order To Pay Amount'],
            'Invoice Total Amount'          => $this->data['Order Total Amount'],
            'Invoice Currency'              => $this->data['Order Currency'],
            'Recargo Equivalencia'          => $this->metadata('RE'),
            'Invoice External Invoicer Key' => $this->data['Order External Invoicer Key'],
            'extra_data'                    => $extra_data,
            'Invoice Order Type'            => ($this->data['Order Type'] == 'FulfilmentRent' ? 'FulfilmentRent' : 'Order'),
            'Invoice Source Key'            => $this->data['Order Source Key'],

            'Invoice EORI' => $eori


        );


        return new Invoice ('create', $data_invoice);
    }


    function confirm_hokodo_order_fulfilment()
    {
        $items      = [];
        $items_keys = [];


        $new_items      = [];
        $new_items_keys = [];

        $debug_total = 0;

        if ($this->get('State Index') >= 100 and $this->data['hokodo_order_id']) {
            $db      = $this->db;
            $store   = get_object('Store', $this->get('Order Store Key'));
            $website = get_object('Website', $store->get('Store Website Key'));
            $api_key = $website->get_api_key('Hokodo');

            $payment      = get_object('Payment', $this->data['pending_hokodo_payment_id']);
            $payment_data = json_decode($payment->get('Payment Metadata'), true);


            foreach ($payment_data['data']['order']['items'] as $item) {
                if ($item['type'] == 'product') {
                    $id = $item['item_id'];

                    $sql  = "select * from `Order Transaction Fact` OTF  left join `Product Dimension` P  on (OTF.`Product ID`=P.`Product Id`) where `Order Transaction Fact Key`=? 
                                                                                                                  ";
                    $stmt = $db->prepare($sql);
                    $stmt->execute(
                        [
                            $id
                        ]
                    );
                    if ($row = $stmt->fetch()) {
                        $item_total = (int)round(100 * $row['Order Transaction Amount'] * (1 + $row['Transaction Tax Rate']));
                        $item_tax   = (int)round(100 * $row['Order Transaction Amount'] * $row['Transaction Tax Rate']);


                        // $qty = number($item_total / $item['unit_price'], 3);

                        $qty = round($row['Delivery Note Quantity'], 3);

                        if ($qty > 0) {
                            //  $unit_price = number($item_total / $row['Delivery Note Quantity'],2);


                            if ($qty > $item['quantity'] or $item_total > $item['total_amount']) {
                                $qty        = $item['quantity'];
                                $item_total = $item['total_amount'];
                                $item_tax   = $item['tax_amount'];
                            }

                            $debug_total += $item_total;

                            $items[] = [
                                "item_id"      => $row['Order Transaction Fact Key'],
                                //   "description"  => $row['Product Code'].' '.$row['Product Name'],
                                "quantity"     => $qty,
                                //    "unit_price"   => $unit_price,
                                "total_amount" => $item_total,
                                // 'total_amount_2'=>(100 * $row['Order Transaction Amount'] * (1 + $row['Transaction Tax Rate'])),
                                "tax_amount"   => $item_tax,
                            ];

                            /*
                                                        print_r(
                                                            [
                                                                "item_id"      => $row['Order Transaction Fact Key'],
                                                                "description"  => $row['Product Code'].' '.$row['Product Name'],
                                                                "quantity"     => $qty,
                                                                "unit_price"   => $unit_price,
                                                                "total_amount" => $item_total,
                                                                "tax_amount"   => $item_tax,
                                                            ]
                                                        );
                                                        exit;
                            */

                            $items_keys[$row['Order Transaction Fact Key']] = $row['Order Transaction Fact Key'];
                        }
                    }
                } elseif ($item['type'] == 'discount') {
                    if ($this->get('Order Deal Amount Off') != '' and $this->get('Order Deal Amount Off') > 0) {
                        $discount_net = -$this->get('Order Deal Amount Off');

                        $tax_category = new TaxCategory($db);
                        $tax_category->loadWithKey($this->data['Order Tax Category Key']);

                        if ($tax_category->id) {
                            $tax_rate = $tax_category->get('Tax Category Rate');
                        } else {
                            $tax_rate = 0;
                        }


                        $discount_tax   = $discount_net * $tax_rate;
                        $discount_total = $discount_net + $discount_tax;
                        $sql            = "SELECT  `Order Transaction Tax Category Key`  FROM `Order Transaction Fact` WHERE `Order Key`=?  AND `Order Transaction Type`='Order' GROUP BY  `Order Transaction Tax Category Key`";


                        $stmt = $db->prepare($sql);
                        $stmt->execute(
                            array(
                                $this->id
                            )
                        );

                        $amount_off_processed = false;


                        while ($row = $stmt->fetch()) {
                            if ($this->data['Order Tax Category Key'] == $row['Order Transaction Tax Category Key']) {
                                $amount_off_processed = true;
                            }
                        }


                        if ($amount_off_processed) {
                            $debug_total += $discount_total;
                            $items[]     = [
                                "item_id"      => 'discount-'.$this->id,
                                "description"  => 'Discount',
                                "quantity"     => 1,
                                "total_amount" => round($discount_total * 100),
                                "tax_amount"   => round($discount_tax * 100),
                            ];
                        }
                    }

                    $items_keys['discount-'.$this->id] = 'discount-'.$this->id;
                } else {
                    $id = str_replace("np-", "", $item['item_id']);

                    $sql  = "select * from `Order No Product Transaction Fact` OTF  where `Order No Product Transaction Fact Key`=? 
                                                                                                                   ";
                    $stmt = $db->prepare($sql);
                    $stmt->execute(
                        [
                            $id
                        ]
                    );
                    if ($row = $stmt->fetch()) {
                        $item_total = (int)round(100 * ($row['Transaction Net Amount'] + $row['Transaction Tax Amount']));
                        $item_tax   = (int)round(100 * $row['Transaction Tax Amount']);

                        $debug_total                                                     += $item_total;
                        $items[]                                                         = [
                            "item_id"      => 'np-'.$row['Order No Product Transaction Fact Key'],
                            "description"  => $row['Transaction Type'],
                            "quantity"     => 1,
                            "total_amount" => $item_total,
                            "tax_amount"   => $item_tax,
                        ];
                        $items_keys['np-'.$row['Order No Product Transaction Fact Key']] = 'np-'.$row['Order No Product Transaction Fact Key'];
                    }
                }
            }


            $new_charges_items = [];

            $sql  = "select * from `Order No Product Transaction Fact` OTF  where `Order Key`=? and (`Transaction Net Amount`+`Transaction Tax Amount`)>0
                                                                                                                   ";
            $stmt = $db->prepare($sql);
            $stmt->execute(
                [
                    $this->id
                ]
            );
            while ($row = $stmt->fetch()) {
                $item_total = floor(100 * ($row['Transaction Net Amount'] + $row['Transaction Tax Amount']));

                $found = false;

                foreach ($payment_data['data']['order']['items'] as $__rep_item) {
                    if ($__rep_item['item_id'] == 'np-'.$row['Order No Product Transaction Fact Key']) {
                        $found = true;
                    }
                }

                if (!$found) {
                    $new_charges_items['np-'.$row['Order No Product Transaction Fact Key']] = $item_total;
                }
            }


            //print "new charges\n";
            //print_r($new_charges_items);

            // print_r($payment_data['data']['order']['items']);


            $reported_items = $payment_data['data']['order']['items'];

            foreach ($reported_items as $key => $reported_item) {
                if (isset($items_keys[$reported_item['item_id']])) {
                    unset($reported_items[$key]);
                }
            }


            foreach ($reported_items as $key => $reported_item) {
                if ($reported_item['type'] == 'fee') {
                    foreach ($new_charges_items as $new_charges_item_key => $value) {
                        if ($value == $reported_item['unit_price']) {
                            unset($new_charges_items[$new_charges_item_key]);

                            $items[] = [
                                "item_id"     => $reported_item['item_id'],
                                "description" => $reported_item['description'],
                                "quantity"    => 1,
                                //  "unit_price"  => $reported_item['unit_price'],

                                "total_amount" => $reported_item['total_amount'],
                                "tax_amount"   => $reported_item['tax_amount'],

                            ];

                            unset($reported_items[$key]);

                            break;
                        }
                    }
                }
            }

            //  print "   $debug_total\n";

            //  print_r($items);
            // exit;

            //  if(count($reported_items)>0){
            //     print_r($reported_items);
            // }

            // print_r($items);
            //exit;


            include_once 'EcomB2B/hokodo/api_call.php';

            $res = api_post_call(
                'payment/orders/'.$this->data['hokodo_order_id'].'/fulfill',
                ['items' => $items],
                $api_key,
                'PUT',
                $this->db
            );
            //  print_r($res);

        }
    }

    function send_hokodo_invoice($invoice_key)
    {
        // return;
        $db = $this->db;


        //cancel out of stocks;

        $payment = get_object('Payment', $this->data['pending_hokodo_payment_id']);

        $payment_data = json_decode($payment->get('Payment Metadata'), true);

        if ($payment->get('Payment Transaction Status') == 'Pending' and $payment->get('Payment Metadata') == '') {
            $payment->delete();

            return;
        }


        //print_r($payment_data);
        $new_items = [];
        foreach ($payment_data['data']['order']['items'] as $item) {
            if ($item['type'] == 'product') {
                $id = $item['item_id'];

                // print_r($item);

                $sql  = "select * from `Order Transaction Fact` OTF  left join `Product Dimension` P  on (OTF.`Product ID`=P.`Product Id`) where `Order Transaction Fact Key`=? 
                                                                                                                  ";
                $stmt = $db->prepare($sql);
                $stmt->execute(
                    [
                        $id
                    ]
                );
                while ($row = $stmt->fetch()) {
                    $tax_rate = $row['Transaction Tax Rate'];

                    $item_total = (int)round(100 * $row['Order Transaction Amount'] * (1 + $tax_rate));
                    $item_tax   = (int)round(100 * $row['Order Transaction Amount'] * $tax_rate);


                    if ($item_total < $item['total_amount'] or $item_tax < $item['tax_amount']) {
                        $new_items[] = [
                            'item_id'      => $item['item_id'],
                            'quantity'     => round($item['quantity'] - $row['Delivery Note Quantity'], 3),
                            'total_amount' => $item['total_amount'] - $item_total,
                            'tax_amount'   => $item['tax_amount'] - $item_tax,
                        ];
                    }
                }
            } else {
                $id = str_replace("np-", "", $item['item_id']);

                $sql  = "select * from `Order No Product Transaction Fact` OTF  where `Order No Product Transaction Fact Key`=? 
                                                                                                                   ";
                $stmt = $db->prepare($sql);
                $stmt->execute(
                    [
                        $id
                    ]
                );
                while ($row = $stmt->fetch()) {
                    $item_total = (int)round(100 * ($row['Transaction Net Amount'] + $row['Transaction Tax Amount']));
                    $item_tax   = (int)round(100 * $row['Transaction Tax Amount']);


                    if ($item_total < $item['total_amount'] or $item_tax < $item['tax_amount']) {
                        $new_items[] = [
                            'item_id'      => $item['item_id'],
                            'quantity'     => round(($item['total_amount'] - $item_total) / $item['total_amount'], 3),
                            'total_amount' => $item['total_amount'] - $item_total,
                            'tax_amount'   => $item['tax_amount'] - $item_tax,
                        ];
                    }
                }
            }
        }


        $store   = get_object('Store', $this->get('Order Store Key'));
        $website = get_object('Website', $store->get('Store Website Key'));
        $api_key = $website->get_api_key('Hokodo');

        include_once 'EcomB2B/hokodo/api_call.php';


        if (count($new_items) > 0) {
            $res = api_post_call(
                'payment/orders/'.$this->data['hokodo_order_id'].'/cancel',
                ['items' => $new_items],
                $api_key,
                'PUT',
                $this->db
            );

            if (isset($res['total_amount'])) {
                $payment->fast_update(
                    [
                        'Payment Transaction Amount' => $res['total_amount'] / 100
                    ]
                );
                $payment->update_payment_parents();
                $payment->fork_index_elastic_search();
            }
        }
        //print_r($res);


        try {
            $smarty = new Smarty();
            //$smarty->caching_type = 'redis';
            $smarty->setTemplateDir('templates');
            $smarty->setCompileDir('server_files/smarty/templates_c');
            $smarty->setCacheDir('server_files/smarty/cache');
            $smarty->setConfigDir('server_files/smarty/configs');
            $smarty->addPluginsDir('./smarty_plugins');
            $db      = $this->db;
            $account = get_object('Account', 1);

            $_REQUEST['id'] = $invoice_key;
            $save_to_file   = 'server_files/tmp/'.$this->data['hokodo_order_id'].'.pdf';
            require_once 'invoice.pdf.common.php';
            $store     = get_object('Store', $this->get('Order Store Key'));
            $website   = get_object('Website', $store->get('Store Website Key'));
            $api_key   = $website->get_api_key('Hokodo');
            $_base_url = 'https://api.hokodo.co/v1';
            if (ENVIRONMENT == 'DEVEL') {
                $_base_url = 'https://api-sandbox.hokodo.co/v1';
            }

            $hokodo_order_id = $this->data['hokodo_order_id'];
            $invoice         = get_object('Invoice', $invoice_key);
            $amount          = floor($invoice->get('Invoice Total Amount') * 100);

            $url = $_base_url.'/payment/orders/'.$hokodo_order_id.'/documents';

            $cmd    = "curl --request POST \
  --url $url \
  --header 'Authorization: Token $api_key' \
  --form doc_type=invoice \
  --form description=invoice_description \
  --form amount=$amount \
  --form file=@$save_to_file";
            $output = shell_exec($cmd);

            $sql = "insert into hokodo_debug (`request`,`data`,`response`,`date`,`status`) values (?,?,?,?,?) ";
            $db->prepare($sql)->execute(
                [
                    $url,
                    json_encode([
                        'amount' => $amount
                    ]),
                    $output,
                    gmdate('Y-m-d H:i:s'),
                    'ok-v2'

                ]
            );
            // print_r(json_decode($output,true));

        } catch (Exception $e) {
            //
        }
    }

    function get_invoices($scope = 'keys', $options = '')
    {
        $invoices = array();


        switch ($options) {
            case 'refunds_only':
                $where = " and `Invoice Type`='Refund'";
                break;
            case 'invoices_only':
                $where = " and `Invoice Type`!='Refund'";
                break;
            default:
                $where = '';
        }


        $sql = sprintf(
            "SELECT `Invoice Key` FROM `Invoice Dimension` WHERE `Invoice Order Key`=%d  %s ",
            $this->id,
            $where
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Invoice Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {
                    $invoices[$row['Invoice Key']] = get_object('Invoice', $row['Invoice Key']);
                } else {
                    $invoices[$row['Invoice Key']] = $row['Invoice Key'];
                }
            }
        }


        return $invoices;
    }


    function get_deleted_invoices($scope = 'keys')
    {
        $deleted_invoices = array();
        $sql              = sprintf(
            "SELECT `Invoice Deleted Key` FROM `Invoice Deleted Dimension` WHERE `Invoice Deleted Order Key`=%d  ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Invoice Deleted Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {
                    $deleted_invoices[$row['Invoice Deleted Key']] = get_object('Invoice_Deleted', $row['Invoice Deleted Key']);
                } else {
                    $deleted_invoices[$row['Invoice Deleted Key']] = $row['Invoice Deleted Key'];
                }
            }
        }

        return $deleted_invoices;
    }


    function send_review_invitation()
    {
        if (ENVIRONMENT == 'DEVEL' or $this->get('Order Email') == '') {
            return;
        }


        $store = get_object('Store', $this->get('Order Store Key'));

        $settings = $store->get('Reviews Settings');

        if (is_array($settings)) {
            if (!isset($settings['provider'])) {
                return;
            }


            if (!empty($settings['max_product_reviews']) and is_numeric($settings['max_product_reviews']) and $settings['max_product_reviews'] >= 0) {
                $max_product_reviews = $settings['max_product_reviews'];
            } else {
                $max_product_reviews = 20;
            }


            if ($settings['provider'] == 'reviews.io') {
                $delay = $settings['data']['delay'] ?? '7';


                $headersRequest = array(
                    "store: ".$settings['data']['store'],
                    "apikey: ".$settings['data']['apikey'],
                    "Content-Type: application/json"
                );


                $products = array();


                $items = $this->get_items();

                shuffle($items);


                $counter = 1;
                foreach ($items as $item) {
                    if ($item['webpage_state'] == 'Online') {
                        if ($counter > $max_product_reviews) {
                            break;
                        }

                        $products[] = array(
                            "sku"     => $item['code'],
                            "name"    => $item['description'],
                            "image"   => $item['image'],
                            "pageUrl" => $item['webpage_url'],

                        );
                        $counter++;
                    }
                }

                $bodyRequest = array(
                    "name"     => $this->get('Order Customer Name'),
                    "email"    => $this->get('Order Email'),
                    "order_id" => $this->get('Public ID'),
                    'delay'    => $delay,
                    "products" => $products
                );


                try {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://api.reviews.co.uk/product/invitation');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headersRequest);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($bodyRequest));
                    $response = curl_exec($ch);

                    $sql = sprintf(
                        'insert into `Order Review Invitation Dimension` (`Order Review Invitation Order Key`,`Order Review Invitation Provider`,`Order Review Invitation Date`,`Order Review Invitation Metadata`) values(%d,%s,%s,%s)',
                        $this->id,
                        prepare_mysql($settings['provider']),
                        prepare_mysql(gmdate('Y-m-d H:i:s')),
                        prepare_mysql($response)


                    );

                    $this->db->exec($sql);
                } catch (Exception $e) {
                    return;
                }
            }
        }
    }

    function get_items()
    {
        $sql = sprintf(
            'SELECT `Deal Info`,`Current Dispatching State`,`Webpage State`,`Webpage URL`,`Product Main Image`,`Order State`,`Delivery Note Quantity`,`Order State`,OTF.`Product ID`,OTF.`Product Key`,OTF.`Order Transaction Fact Key`,`Order Currency Code`,`Order Transaction Amount`,`Order Quantity`,`Product History Name`,`Product History Units Per Case`,PD.`Product Code`,`Product Name`,`Product Units Per Case` 
      FROM `Order Transaction Fact` OTF LEFT JOIN `Product History Dimension` PHD ON (OTF.`Product Key`=PHD.`Product Key`) LEFT JOIN 
      `Product Dimension` PD ON (PD.`Product ID`=PHD.`Product ID`)  LEFT JOIN 
        `Order Dimension` O ON (O.`Order Key`=OTF.`Order Key`) Left join 
        `Page Store Dimension` W on (`Page Key`=`Product Webpage Key`) left join 
        `Order Transaction Deal Bridge` B on (OTF.`Order Transaction Fact Key`=B.`Order Transaction Fact Key`)

      WHERE OTF.`Order Key`=%d  ORDER BY `Product Code File As` ',
            $this->id
        );

        $items = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $edit_quantity = sprintf(
                    '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-plus fa-fw like_button button"  style="cursor:pointer" aria-hidden="true"></i></span>',
                    $row['Order Transaction Fact Key'],
                    $row['Product ID'],
                    $row['Product Key'],
                    $row['Order Quantity'] + 0,
                    $row['Order Quantity'] + 0
                );
                //'InBasket','InProcess','InWarehouse','PackedDone','Approved','Dispatched','Cancelled'
                if ($row['Order State'] == 'Dispatched' or $row['Order State'] == 'Approved' or $row['Order State'] == 'PackedDone') {
                    $qty = number($row['Delivery Note Quantity']);
                } else {
                    $qty = number($row['Order Quantity']);
                }

                $deal_info = $row['Deal Info'];

                if ($row['Current Dispatching State'] == 'Out of Stock in Basket') {
                    $out_of_stock_info = _('Product out of stock, removed from basket');
                } else {
                    $out_of_stock_info = '';
                }


                if ($row['Product Units Per Case'] >= 1) {
                    $name = get_html_fractions($row['Product Units Per Case']).'x '.$row['Product Name'];
                } else {
                    $name = get_html_fractions($row['Product Units Per Case']).' '.$row['Product Name'];
                }


                $items[] = array(
                    'otf_key'              => $row['Order Transaction Fact Key'],
                    'code'                 => $row['Product Code'],
                    'product_id'           => $row['Product ID'],
                    'product_historic_key' => $row['Product Key'],
                    'description'          => $name,
                    'deal_info'            => $deal_info,
                    'out_of_stock_info'    => $out_of_stock_info,
                    'qty'                  => $qty,
                    'edit_qty'             => $edit_quantity,
                    'amount'               => '<span class="item_amount">'.money($row['Order Transaction Amount'], $row['Order Currency Code']).'</span>',
                    'webpage_url'          => $row['Webpage URL'],
                    'image'                => $row['Product Main Image'],
                    'webpage_state'        => $row['Webpage State']

                );
            }
        }


        return $items;
    }


    function get_formatted_payment_state()
    {
        return get_order_formatted_payment_state($this->data, $this->db);
    }

    function get_date($field)
    {
        return strftime("%e %b %Y", strtotime($this->data[$field].' +0:00'));
    }

    function remove_out_of_stocks_from_basket($product_pid)
    {
        $sql           = sprintf(
            "SELECT `Order Transaction Fact Key`,`Order Quantity`,`Product Key`,`Product ID`,`Order Transaction Amount` FROM `Order Transaction Fact` WHERE `Current Dispatching State` IN ('In Process','In Process by Customer') AND  `Product ID`=%d AND `Order Key`=%d ",
            $product_pid,
            $this->id
        );
        $affected_rows = 0;


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $sql = "INSERT INTO `Order Transaction Out of Stock in Basket Bridge` (`Order Transaction Fact Key`,`Date`,`Store Key`,`Order Key`,`Product Key`,`Product ID`,`Quantity`,`Amount`) VALUES (?,?,?,?,?,?,?,?)";

                $this->db->prepare($sql)->execute(array(
                    $row['Order Transaction Fact Key'],
                    gmdate('Y-m-d H:i:s'),
                    $this->data['Order Store Key'],
                    $this->id,
                    $row['Product Key'],
                    $row['Product ID'],
                    $row['Order Quantity'],
                    round($row['Order Transaction Amount'], 2)
                ));


                $sql = sprintf(
                    'UPDATE `Order Transaction Fact` SET `Current Dispatching State`=%s,`Order Quantity`=0,`Order Bonus Quantity`=0 ,`Order Transaction Gross Amount`=0 ,`Order Transaction Total Discount Amount`=0,`Order Transaction Amount`=0 WHERE `Order Transaction Fact Key`=%d   ',
                    prepare_mysql('Out of Stock in Basket'),
                    $row['Order Transaction Fact Key']
                );
                $this->db->exec($sql);

                $affected_rows++;
            }
        }

        if ($affected_rows) {
            $dn_key = 0;

            $old_used_deals = $this->get_used_deals();


            $this->update_discounts_items();
            $this->update_totals();


            $this->update_shipping($dn_key);
            $this->update_charges($dn_key, false);
            $this->update_discounts_no_items($dn_key);


            $this->update_deal_bridge();


            $this->update_totals();


            $new_used_deals = $this->get_used_deals();


            $intersect      = array_intersect($old_used_deals[0], $new_used_deals[0]);
            $campaigns_diff = array_merge(array_diff($old_used_deals[0], $intersect), array_diff($new_used_deals[0], $intersect));

            $intersect = array_intersect($old_used_deals[1], $new_used_deals[1]);
            $deal_diff = array_merge(array_diff($old_used_deals[1], $intersect), array_diff($new_used_deals[1], $intersect));


            $intersect            = array_intersect($old_used_deals[2], $new_used_deals[2]);
            $deal_components_diff = array_merge(array_diff($old_used_deals[2], $intersect), array_diff($new_used_deals[2], $intersect));


            if (count($campaigns_diff) > 0 or count($deal_diff) > 0 or count($deal_components_diff) > 0) {
                $account = get_object('Account', '');

                require_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'            => 'update_deals_usage',
                        'campaigns'       => $campaigns_diff,
                        'deals'           => $deal_diff,
                        'deal_components' => $deal_components_diff,


                    ),
                    $account->get('Account Code'),
                    $this->db
                );
            }
            //$this->apply_payment_from_customer_account();


        }
    }

    function restore_back_to_stock_to_basket($product_pid)
    {
        if ($this->data['Order State'] != 'InBasket') {
            return;
        }

        $affected_rows = 0;

        $sql = sprintf(
            "SELECT `Order Transaction Fact Key`,`Quantity` FROM `Order Transaction Out of Stock in Basket Bridge` WHERE  `Product ID`=%d AND `Order Key`=%d ",
            $product_pid,
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $product = new Product('id', $product_pid);

                $gross = round($row['Quantity'] * $product->data['Product Price'], 2);

                $sql = "UPDATE `Order Transaction Fact` SET `Current Dispatching State`=?,`Order Quantity`=?,`No Shipped Due Out of Stock`=0,`Order Transaction Gross Amount`=? ,`Order Transaction Total Discount Amount`=?,`Order Transaction Amount`=? 
WHERE `Order Transaction Fact Key`=?";

                $this->db->prepare($sql)->execute(array(
                    'In Process',
                    $row['Quantity'],
                    $gross,
                    0,
                    $gross,
                    $row['Order Transaction Fact Key']
                ));


                $sql = sprintf(
                    'DELETE FROM `Order Transaction Out of Stock in Basket Bridge` WHERE `Order Transaction Fact Key`=%d',
                    $row['Order Transaction Fact Key']
                );
                $this->db->exec($sql);

                $affected_rows++;
            }
        }


        if ($affected_rows) {
            $dn_key = 0;

            $old_used_deals = $this->get_used_deals();

            $this->update_discounts_items();
            $this->update_totals();


            $this->update_shipping($dn_key);
            $this->update_charges($dn_key, false);
            $this->update_discounts_no_items($dn_key);


            $this->update_deal_bridge();


            $this->update_totals();


            $new_used_deals = $this->get_used_deals();


            $intersect      = array_intersect($old_used_deals[0], $new_used_deals[0]);
            $campaigns_diff = array_merge(array_diff($old_used_deals[0], $intersect), array_diff($new_used_deals[0], $intersect));

            $intersect = array_intersect($old_used_deals[1], $new_used_deals[1]);
            $deal_diff = array_merge(array_diff($old_used_deals[1], $intersect), array_diff($new_used_deals[1], $intersect));


            $intersect            = array_intersect($old_used_deals[2], $new_used_deals[2]);
            $deal_components_diff = array_merge(array_diff($old_used_deals[2], $intersect), array_diff($new_used_deals[2], $intersect));


            if (count($campaigns_diff) > 0 or count($deal_diff) > 0 or count($deal_components_diff) > 0) {
                $account = get_object('Account', '');

                require_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'            => 'update_deals_usage',
                        'campaigns'       => $campaigns_diff,
                        'deals'           => $deal_diff,
                        'deal_components' => $deal_components_diff,


                    ),
                    $account->get('Account Code'),
                    $this->db
                );
            }
            //$this->apply_payment_from_customer_account();


        }
    }


    function create_refund($date, $transactions, $tax_only = false): Invoice
    {
        include_once 'class.Invoice.php';

        $store   = get_object('Store', ($this->data['Order Store Key']));
        $account = get_object('Account', 1);
        $account->load_properties();


        $id_done           = false;
        $invoice_public_id = '';

        if ($this->metadata('ups')) {
            if ($account->properties('ups_public_id_type') != '') {
                if ($account->properties('ups_public_id_type') == 'alt_account_field') {
                    $sql = "UPDATE `Account Dimension` SET `Account Invoice Last Invoice Alt Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Alt Public ID` + 1) WHERE `Account Key`=1";
                    $this->db->exec($sql);
                    $public_id = $this->db->lastInsertId();

                    $invoice_public_id = sprintf(
                        $account->properties('ups_public_id_refund_format'),
                        $public_id
                    );

                    $id_done = true;
                }
            }
        }


        if (!$id_done) {
            if ($store->data['Store Refund Public ID Method'] == 'Same Invoice ID') {
                foreach ($this->get_invoices('objects') as $_invoice) {
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
                            $store->data['Store Invoice Public ID Format'],
                            $this->db->lastInsertId()
                        );
                    } elseif ($store->data['Store Next Invoice Public ID Method'] == 'Order ID') {
                        $sql = sprintf(
                            "UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) WHERE `Store Key`=%d",
                            $this->data['Order Store Key']
                        );
                        $this->db->exec($sql);
                        $invoice_public_id = sprintf(
                            $store->data['Store Order Public ID Format'],
                            $this->db->lastInsertId()
                        );
                    } else {
                        $sql = "UPDATE `Account Dimension` SET `Account Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Public ID` + 1) WHERE `Account Key`=1";
                        $this->db->exec($sql);
                        $public_id = $this->db->lastInsertId();
                        include_once 'class.Account.php';
                        $account           = new Account();
                        $invoice_public_id = sprintf(
                            $account->data['Account Invoice Public ID Format'],
                            $public_id
                        );
                    }
                }


                if ($invoice_public_id != '') {
                    $invoice_public_id = $this->get_refund_public_id($invoice_public_id.$store->data['Store Refund Suffix']);
                }
            } elseif ($store->data['Store Refund Public ID Method'] == 'Account Wide Own Index') {
                include_once 'class.Account.php';
                $account = new Account();
                $sql     = "UPDATE `Account Dimension` SET `Account Invoice Last Refund Public ID` = LAST_INSERT_ID(`Account Invoice Last Refund Public ID` + 1) WHERE `Account Key`=1";
                $this->db->exec($sql);
                $invoice_public_id = sprintf(
                    $account->data['Account Refund Public ID Format'],
                    $this->db->lastInsertId()
                );
            } elseif ($store->data['Store Refund Public ID Method'] == 'Store Own Index') {
                $sql = sprintf(
                    "UPDATE `Store Dimension` SET `Store Invoice Last Refund Public ID` = LAST_INSERT_ID(`Store Invoice Last Refund Public ID` + 1) WHERE `Store Key`=%d",
                    $this->data['Order Store Key']
                );
                $this->db->exec($sql);
                $invoice_public_id = sprintf(
                    $store->data['Store Refund Public ID Format'],
                    $this->db->lastInsertId()
                );
            } else { //Next Invoice ID


                if ($store->data['Store Next Invoice Public ID Method'] == 'Invoice Public ID') {
                    $sql = sprintf(
                        "UPDATE `Store Dimension` SET `Store Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Store Invoice Last Invoice Public ID` + 1) WHERE `Store Key`=%d",
                        $this->data['Order Store Key']
                    );
                    $this->db->exec($sql);
                    $invoice_public_id = sprintf(
                        $store->data['Store Invoice Public ID Format'],
                        $this->db->lastInsertId()
                    );
                } elseif ($store->data['Store Next Invoice Public ID Method'] == 'Order ID') {
                    $sql = sprintf(
                        "UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) WHERE `Store Key`=%d",
                        $this->data['Order Store Key']
                    );
                    $this->db->exec($sql);
                    $invoice_public_id = sprintf(
                        $store->data['Store Order Public ID Format'],
                        $this->db->lastInsertId()
                    );
                } else {
                    $sql = "UPDATE `Account Dimension` SET `Account Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Public ID` + 1) WHERE `Account Key`=1";
                    $this->db->exec($sql);
                    $public_id = $this->db->lastInsertId();
                    include_once 'class.Account.php';
                    $account           = new Account();
                    $invoice_public_id = sprintf(
                        $account->data['Account Invoice Public ID Format'],
                        $public_id
                    );
                }
            }
        }

        $file_as = get_file_as($invoice_public_id);


        $customer = get_object('Customer', $this->data['Order Customer Key']);
        $eori     = $customer->get('EORI');

        $refund_data = array(
            'editor'                   => $this->editor,
            'Invoice EORI'             => $eori,
            'Invoice Date'             => $date,
            'Invoice Type'             => 'Refund',
            'Invoice Public ID'        => $invoice_public_id,
            'Invoice File As'          => $file_as,
            'Invoice Order Key'        => $this->id,
            'Invoice Store Key'        => $this->data['Order Store Key'],
            'Invoice Customer Key'     => $this->data['Order Customer Key'],
            'Invoice Tax Category Key' => $this->data['Order Tax Category Key'],
            'Invoice Tax Code'         => $this->data['Order Tax Code'],

            'Invoice Metadata'                      => '{}',
            'Invoice Tax Number'                    => $this->data['Order Tax Number'],
            'Invoice Tax Number Valid'              => $this->data['Order Tax Number Valid'],
            'Invoice Tax Number Validation Date'    => $this->data['Order Tax Number Validation Date'],
            'Invoice Tax Number Validation Source'  => $this->data['Order Tax Number Validation Source'],
            'Invoice Tax Number Validation Message' => $this->data['Order Tax Number Validation Message'],

            'Invoice Tax Number Associated Name'    => $this->data['Order Tax Number Associated Name'],
            'Invoice Tax Number Associated Address' => $this->data['Order Tax Number Associated Address'],


            'Invoice Net Amount Off'           => 0,
            'Invoice Customer Contact Name'    => $this->data['Order Customer Contact Name'],
            'Invoice Customer Name'            => $this->data['Order Customer Name'],
            'Invoice Customer Level Type'      => $this->data['Order Customer Level Type'],
            'Invoice Sales Representative Key' => $this->data['Order Sales Representative Key'],


            'Invoice Address Recipient'            => $this->data['Order Invoice Address Recipient'],
            'Invoice Address Organization'         => $this->data['Order Invoice Address Organization'],
            'Invoice Address Line 1'               => $this->data['Order Invoice Address Line 1'],
            'Invoice Address Line 2'               => $this->data['Order Invoice Address Line 2'],
            'Invoice Address Sorting Code'         => $this->data['Order Invoice Address Sorting Code'],
            'Invoice Address Postal Code'          => $this->data['Order Invoice Address Postal Code'],
            'Invoice Address Dependent Locality'   => $this->data['Order Invoice Address Dependent Locality'],
            'Invoice Address Locality'             => $this->data['Order Invoice Address Locality'],
            'Invoice Address Administrative Area'  => $this->data['Order Invoice Address Administrative Area'],
            'Invoice Address Country 2 Alpha Code' => $this->data['Order Invoice Address Country 2 Alpha Code'],
            'Invoice Address Checksum'             => $this->data['Order Invoice Address Checksum'],
            'Invoice Address Formatted'            => $this->data['Order Invoice Address Formatted'],
            'Invoice Address Postal Label'         => $this->data['Order Invoice Address Postal Label'],
            'Invoice Registration Number'          => $this->data['Order Registration Number'],
            'Invoice Tax Type'                     => ($tax_only ? 'Tax_Only' : 'Normal'),
            'Invoice Tax Liability Date'           => $date,
            'Invoice Items Gross Amount'           => 0,
            'Invoice Items Discount Amount'        => 0,
            'Invoice Items Net Amount'             => 0,
            'Invoice Items Out of Stock Amount'    => 0,
            'Invoice Shipping Net Amount'          => 0,
            'Invoice Charges Net Amount'           => 0,
            'Invoice Insurance Net Amount'         => 0,
            'Invoice Total Net Amount'             => 0,
            'Invoice Total Tax Amount'             => 0,
            'Invoice Payments Amount'              => 0,
            'Invoice To Pay Amount'                => 0,
            'Invoice Total Amount'                 => 0,
            'Invoice Currency'                     => $this->data['Order Currency'],
            'Recargo Equivalencia'                 => $this->metadata('RE'),
            'Invoice External Invoicer Key'        => $this->data['Order External Invoicer Key'],
            'Invoice Order Type'                   => ($this->data['Order Type'] == 'FulfilmentRent' ? 'FulfilmentRent' : 'Order'),
            'Invoice Source Key'                   => $this->data['Order Source Key']


        );


        if ($this->data['hokodo_order_id']) {
            $this->hokodo_submit_refund($transactions);
        }


        return new Invoice('create refund', $refund_data, $transactions);
    }

    function hokodo_submit_refund($transactions)
    {
        $store   = get_object('Store', $this->get('Order Store Key'));
        $website = get_object('Website', $store->get('Store Website Key'));
        $api_key = $website->get_api_key('Hokodo');
        $items   = [];
        $payment = get_object('Payment', $this->data['pending_hokodo_payment_id']);


        //  print_r($transactions);


        foreach ($transactions as $transaction) {
            if ($transaction['type'] == 'otf') {
                $sql  = "select * from `Order Transaction Fact` OTF  left join `Product Dimension` P  on (OTF.`Product ID`=P.`Product Id`) where `Order Transaction Fact Key`=? 
                                                                                                                    and `Order Quantity`>0
                                                                                                                    and `Order Transaction Amount`!=0 ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    [
                        $transaction['id']
                    ]
                );


                while ($row = $stmt->fetch()) {
                    //$item_total = floor(100 * ($row['Order Transaction Amount'] + ($row['Order Transaction Amount'] * $row['Transaction Tax Rate'])));
                    //$item_tax   = floor(100 * $row['Order Transaction Amount'] * $row['Transaction Tax Rate']);

                    //$unit_price = floor($item_total / $row['Order Quantity']);
                    //$unit_tax   = floor($item_tax / $row['Order Quantity']);


                    /*
                    $net=-$row['Order Transaction Amount'];

                    $tax_rate=$row['Transaction Tax Rate'];
                    $total_tax_rate=1+$tax_rate;

                    $total_with_tax=$net*$total_tax_rate;

                    print "tax rate $tax_rate\n";
                    print "amount $net  $total_with_tax   \n";
                    print "wrong\n";
                    print (-$transaction['amount'])."\n";
                    print (-$transaction['amount'] * $row['Transaction Tax Rate'])."\n";
                    print (-$transaction['amount'] * $row['Transaction Tax Rate'] * 100)."\n";
                    print floor(-$transaction['amount'] * $row['Transaction Tax Rate'] * 100)."\n";
*/

                    $net = (int)round(-$transaction['amount'] * (1 + $row['Transaction Tax Rate']) * 100);

                    $tax = (int)round(-$transaction['amount'] * $row['Transaction Tax Rate'] * 100);


                    //   print "=== \n";
                    $items[] = [
                        "item_id"      => $row['Order Transaction Fact Key'].'-ref-'.date('U'),
                        'description'  => 'refund',
                        "quantity"     => 1,
                        "unit_price"   => $net,
                        "total_amount" => $net,
                        "tax_amount"   => $tax

                    ];
                }
            } elseif ($transaction['type'] == 'onptf') {
                $sql  = "select * from `Order No Product Transaction Fact` OTF  where `Order No Product Transaction Fact Key`=? 
                                                                                                                   ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    [
                        $transaction['id']
                    ]
                );
                while ($row = $stmt->fetch()) {
                    $tax_rate = 0;
                    $sql      = "select `Tax Category Rate` from kbase.`Tax Category Dimension` where `Tax Category Key`=? ";
                    $stmt2    = $this->db->prepare($sql);
                    $stmt2->execute(
                        [
                            $row['Order No Product Transaction Tax Category Key']
                        ]
                    );
                    while ($row2 = $stmt2->fetch()) {
                        $tax_rate = $row2['Tax Category Rate'];
                    }


                    $net = (int)round(-$transaction['amount'] * (1 + $tax_rate) * 100);

                    $tax = (int)round(-$transaction['amount'] * $tax_rate * 100);


                    $items[] = [
                        "item_id"      => 'np-'.$row['Order No Product Transaction Fact Key'].'-ref-'.date('U'),
                        'description'  => 'refund',
                        "quantity"     => 1,
                        "unit_price"   => $net,
                        "total_amount" => $net,
                        "tax_amount"   => $tax

                    ];
                }
            }
        }

        // print_r($items);
        // exit;
        include_once 'EcomB2B/hokodo/api_call.php';


        $res = api_post_call(
            'payment/orders/'.$this->data['hokodo_order_id'].'/discount',
            ['items' => $items],
            $api_key,
            'PUT',
            $this->db
        );


        $payment->fast_update(
            [
                'Payment Transaction Amount' => $res['total_amount'] / 100
            ]
        );
        $payment->update_payment_parents();
        $payment->fork_index_elastic_search();
        //print_r($res);

    }

    function get_refund_public_id($refund_id, $suffix_counter = '')
    {
        $sql = sprintf(
            "SELECT `Invoice Public ID` FROM `Invoice Dimension` WHERE `Invoice Store Key`=%d AND `Invoice Public ID`=%s ",
            $this->data['Order Store Key'],
            prepare_mysql($refund_id.$suffix_counter)
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
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
    }

    function create_replacement($transactions): DeliveryNote
    {
        include_once 'utils/new_fork.php';


        $date = gmdate('Y-m-d H:i:s');

        $account = get_object('Account', 1);


        $warehouse_key = $_SESSION['current_warehouse'];

        include_once('class.DeliveryNote.php');


        if ($this->data['Order For Collection'] == 'Yes') {
            $dispatch_method = 'Collection';
        } else {
            $dispatch_method = 'Dispatch';
        }

        $store = get_object('Store', $this->data['Order Store Key']);


        $replacement_public_id = $this->get_replacement_public_id($this->data['Order Public ID'].$store->data['Store Replacement Suffix']);


        $telephone = $this->data['Order Telephone'];
        $email     = $this->data['Order Email'];
        if ($this->data['Order Customer Client Key']) {
            $client = get_object('Customer Client', $this->data['Order Customer Client Key']);
            if ($client->id) {
                $telephone = $client->get_telephone();
                $email     = $client->get('Customer Client Main Plain Email');
            }
        }

        $data_dn = array(
            'editor'                      => $this->editor,
            'Delivery Note Warehouse Key' => $warehouse_key,
            'Delivery Note Date Created'  => $date,
            'Delivery Note Date'          => $date,
            'Delivery Note Order Key'     => $this->id,
            'Delivery Note Store Key'     => $this->data['Order Store Key'],

            'Delivery Note Order Date Placed'            => $this->data['Order Date'],
            'Delivery Note ID'                           => $replacement_public_id,
            'Delivery Note File As'                      => $replacement_public_id,
            'Delivery Note Type'                         => 'Replacement',
            'Delivery Note Dispatch Method'              => $dispatch_method,
            'Delivery Note Customer Key'                 => $this->data['Order Customer Key'],
            'Delivery Note Metadata'                     => '',
            'Delivery Note Customer Name'                => $this->data['Order Customer Name'],
            'Delivery Note Customer Contact Name'        => $this->data['Order Customer Contact Name'],
            'Delivery Note Telephone'                    => $telephone,
            'Delivery Note Email'                        => $email,
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
            'Delivery Note External Invoicer Key'        => $this->data['Order External Invoicer Key'],

        );


        $replacement = new DeliveryNote('create replacement', $data_dn, $transactions);


        $this->update_number_replacements();


        if ($this->get('replacements_in_process') == 1) {
            $this->fast_update(array(
                'Order Replacement Created Date' => $date
            ));
        }


        require_once 'utils/new_fork.php';

        new_housekeeping_fork(
            'au_housekeeping',
            array(
                'type'              => 'replacement_created',
                'order_key'         => $this->id,
                'editor'            => $this->editor,
                'delivery_note_key' => $replacement->id
            ),
            $account->get('Account Code'),
            $this->db
        );


        return $replacement;
    }

    function get_replacement_public_id($dn_id, $suffix_counter = '')
    {
        $sql = "SELECT `Delivery Note ID` FROM `Delivery Note Dimension` WHERE `Delivery Note Store Key`=? AND `Delivery Note ID`=? ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $this->data['Order Store Key'],
            $dn_id.$suffix_counter
        ));
        if ($row = $stmt->fetch()) {
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

    function update_number_replacements($update_parents = true)
    {
        $old_in_warehouse_no_alerts   = $this->get('Order Replacements In Warehouse without Alerts');
        $old_in_warehouse_with_alerts = $this->get('Order Replacements In Warehouse with Alerts');
        $old_packed_done              = $this->get('Order Replacements Packed Done');
        $old_approved                 = $this->get('Order Replacements Approved');
        $old_dispatched_today         = $this->get('Order Replacements Dispatched Today');


        $in_warehouse             = 0;
        $in_warehouse_with_alerts = 0;
        $packed_done              = 0;
        $approved                 = 0;
        $dispatched_today         = 0;


        $sql = "SELECT  `Delivery Note State`,count(*) as num  FROM `Delivery Note Dimension` WHERE `Delivery Note Order Key`=?  and  `Delivery Note Type` in ('Replacement & Shortages', 'Replacement', 'Shortages') group by `Delivery Note State` ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $this->id
        ));
        if ($row = $stmt->fetch()) {
            if ($row['num'] > 0) {
                switch ($row['Delivery Note State']) {
                    case 'Ready to be Picked':
                    case 'Picker Assigned':
                    case 'Picking':
                    case 'Picked':
                    case 'Packing':
                    case 'Packed':

                        $in_warehouse += $row['num'];


                        break;

                    case 'Packed Done':
                        $packed_done += $row['num'];
                        break;
                    case 'Approved':
                        $approved += $row['num'];
                        break;
                }
            }
        }


        $sql =
            "SELECT  `Delivery Note State`,count(*) as num  FROM `Delivery Note Dimension` WHERE `Delivery Note Order Key`=?  and  `Delivery Note Type` in ('Replacement & Shortages', 'Replacement', 'Shortages') and `Delivery Note Waiting State`='Customer'  group by `Delivery Note State` ";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $this->id
        ));
        if ($row = $stmt->fetch()) {
            if ($row['num'] > 0) {
                switch ($row['Delivery Note State']) {
                    case 'Ready to be Picked':
                    case 'Picker Assigned':
                    case 'Picking':
                    case 'Picked':
                    case 'Packing':
                    case 'Packed':
                        $in_warehouse_with_alerts += $row['num'];
                        break;
                }
            }
        }


        $in_warehouse_no_alerts = $in_warehouse - $in_warehouse_with_alerts;


        $sql = "SELECT count(*) AS num FROM `Delivery Note Dimension` 
            WHERE  `Delivery Note Order Key`=?  and  `Delivery Note Type` in ('Replacement & Shortages', 'Replacement', 'Shortages') AND   `Delivery Note State` ='Dispatched' AND `Delivery Note Date Dispatched`>=? ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $this->id,
            gmdate('Y-m-d 00:00:00')
        ));
        if ($row = $stmt->fetch()) {
            if ($row['num'] > 0) {
                $dispatched_today = $row['num'];
            }
        }


        $this->fast_update(array(
            'Order Replacements In Warehouse without Alerts' => $in_warehouse_no_alerts,
            'Order Replacements In Warehouse with Alerts'    => $in_warehouse_with_alerts,
            'Order Replacements Packed Done'                 => $packed_done,
            'Order Replacements Approved'                    => $approved,
            'Order Replacements Dispatched Today'            => $dispatched_today,


        ));


        if ($update_parents) {
            $account = get_object('Account', 1);

            require_once 'utils/new_fork.php';


            if ($old_in_warehouse_no_alerts != $in_warehouse_no_alerts or $old_in_warehouse_with_alerts != $in_warehouse_with_alerts) {
                $update_in_warehouse = true;
            } else {
                $update_in_warehouse = false;
            }

            if ($old_packed_done != $packed_done) {
                $update_packed_done = true;
            } else {
                $update_packed_done = false;
            }

            if ($old_approved != $approved) {
                $update_approved = true;
            } else {
                $update_approved = false;
            }

            if ($old_dispatched_today != $dispatched_today) {
                $update_dispatched_today = true;
            } else {
                $update_dispatched_today = false;
            }

            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'                    => 'order_replacements_updated',
                    'order_key'               => $this->id,
                    'update_in_warehouse'     => $update_in_warehouse,
                    'update_packed_done'      => $update_packed_done,
                    'update_approved'         => $update_approved,
                    'update_dispatched_today' => $update_dispatched_today,
                ),
                $account->get('Account Code'),
                $this->db
            );
        }
    }

    function create_return($transactions): SupplierDelivery
    {
        include_once 'utils/currency_functions.php';

        include_once 'class.SupplierDelivery.php';

        $account = get_object('Account', 1);


        $store = get_object('Store', $this->data['Order Store Key']);

        $delivery_data = array(
            'Supplier Delivery Public ID'           => $this->get_return_public_id($this->data['Order Public ID'].$store->data['Store Return Suffix']),
            'Supplier Delivery Parent'              => 'Order',
            'Supplier Delivery Parent Key'          => $this->id,
            'Supplier Delivery Parent Name'         => $this->get('Order Public ID'),
            'Supplier Delivery Parent Code'         => $this->get('Order Public ID'),
            'Supplier Delivery Parent Contact Name' => $this->get('Order Customer Name'),
            'Supplier Delivery Parent Email'        => $this->get('Order Email'),
            'Supplier Delivery Parent Telephone'    => $this->get('Order Telephone'),
            'Supplier Delivery Parent Address'      => $this->get('Delivery Address'),
            'Supplier Delivery Currency Code'       => $account->get('Account Currency Code'),
            'Supplier Delivery Incoterm'            => '',
            'Supplier Delivery Warehouse Key'       => $account->get('Account Default Warehouse'),
            'Supplier Delivery Port of Import'      => '',
            'Supplier Delivery Port of Export'      => '',
            'Supplier Delivery Purchase Order Key'  => '',
            'Supplier Delivery Currency Exchange'   => 1,
            'Supplier Delivery Dispatched Date'     => gmdate('Y-m-d H:i:s'),
            'Supplier Delivery State'               => 'Dispatched',


            'editor' => $this->editor
        );


        $delivery = new SupplierDelivery('new', $delivery_data);


        if ($delivery->error) {
            $this->error = true;
            $this->msg   = $delivery->msg;
        } else {
            foreach ($transactions as $transaction_key => $transaction) {
                switch ($transaction['type']) {
                    case 'itf':
                        $sql = sprintf('select `Part SKU`,`Inventory Transaction Amount`,`Inventory Transaction Key`,`Map To Order Transaction Fact Key` from `Inventory Transaction Fact` where `Inventory Transaction Key`=%d ', $transaction['id']);
                        if ($result = $this->db->query($sql)) {
                            if ($row = $result->fetch()) {
                                // print_r($row);
                                $date = gmdate('Y-m-d H:i:s');

                                $transactions[$transaction_key]['otf'] = $row['Map To Order Transaction Fact Key'];

                                $part         = get_object('Part', $row['Part SKU']);
                                $cbm_per_unit = $part->get('CBM per Unit');


                                $unit_qty = $transaction['amount'] * $part->get('Part Units Per Package');

                                if (is_numeric($cbm_per_unit)) {
                                    $cbm = $cbm_per_unit * $unit_qty;
                                } else {
                                    $cbm = 0;
                                }

                                if ($part->get('Part Package Weight') != '' and is_numeric($part->get('Part Package Weight'))) {
                                    $weight = $transaction['amount'] * $part->get('Part Package Weight');
                                } else {
                                    $weight = '';
                                }

                                $amount       = -1.0 * $row['Inventory Transaction Amount'];
                                $extra_amount = 0;

                                $sql = "INSERT INTO `Purchase Order Transaction Fact` (`Purchase Order Transaction Type`,`Purchase Order Transaction Return ITF Key`,`Purchase Order Transaction Part SKU`,`Currency Code`,`Purchase Order Last Updated Date`,`Supplier Delivery Transaction State`,
					`Supplier Delivery Units`,`Supplier Delivery Net Amount`,`Supplier Delivery Extra Cost Amount`,`Supplier Delivery CBM`,`Supplier Delivery Weight`,
					`User Key`,`Creation Date`,`Supplier Delivery Key`,`Supplier Delivery Transaction Placed`,`Supplier Delivery Last Updated Date`
					)
					VALUES (?,?,?,?,?,?,
			         ?,?,?,?,?,
					 ?,?,
					 ?,'No',?
					 )";

                                $this->db->prepare($sql)->execute(array(
                                    'Return',
                                    $row['Inventory Transaction Key'],
                                    $part->id,
                                    $account->get('Account Currency Code'),
                                    $date,
                                    'Dispatched',

                                    round($unit_qty, 6),
                                    round($amount, 2),
                                    $extra_amount,
                                    $cbm,
                                    $weight,
                                    $this->editor['User Key'],
                                    $date,
                                    $delivery->id,
                                    $date
                                ));
                            }
                        }

                        break;
                }
            }


            $delivery->update_totals();
        }


        // if ($this->data['hokodo_order_id']) {
        //     $this->hokado_submit_return($transactions);
        // }

        return $delivery;
    }


    function hokado_submit_return($transactions)
    {
        return false;
        // removing this because the followinf refund will refund twice
        $store   = get_object('Store', $this->get('Order Store Key'));
        $website = get_object('Website', $store->get('Store Website Key'));
        $api_key = $website->get_api_key('Hokodo');


        foreach ($transactions as $transaction) {
            $sql  = "select * from `Order Transaction Fact` OTF  left join `Product Dimension` P  on (OTF.`Product ID`=P.`Product Id`) where `Order Transaction Fact Key`=? 
                                                                                                                    and `Order Quantity`>0
                                                                                                                    and `Order Transaction Amount`!=0 ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                [
                    $transaction['otf']
                ]
            );
            while ($row = $stmt->fetch()) {
                $item_total = floor(100 * ($row['Order Transaction Amount'] + ($row['Order Transaction Amount'] * $row['Transaction Tax Rate'])));
                $item_tax   = floor(100 * $row['Order Transaction Amount'] * $row['Transaction Tax Rate']);

                $unit_price = floor($item_total / $row['Order Quantity']);
                $unit_tax   = floor($item_tax / $row['Order Quantity']);

                $items[] = [
                    "item_id"      => $row['Order Transaction Fact Key'],
                    "quantity"     => $transaction['amount'],
                    "total_amount" => $unit_price * $transaction['amount'],
                    "tax_amount"   => $unit_tax * $transaction['amount'],

                ];
            }
        }


        include_once 'EcomB2B/hokodo/api_call.php';

        $res = api_post_call(
            'payment/orders/'.$this->data['hokodo_order_id'].'/return',
            ['items' => $items],
            $api_key,
            'PUT',
            $this->db
        );
    }


    function get_return_public_id($supplier_delivery_id, $suffix_counter = '')
    {
        $sql = sprintf(
            "SELECT `Supplier Delivery Public ID` FROM `Supplier Delivery Dimension` WHERE `Supplier Delivery Parent`='Order' and `Supplier Delivery Parent Key`=%d AND `Supplier Delivery Public ID`=%s ",
            $this->id,
            prepare_mysql($supplier_delivery_id.$suffix_counter)
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($suffix_counter > 100) {
                    return $supplier_delivery_id.$suffix_counter;
                }

                if (!$suffix_counter) {
                    $suffix_counter = 2;
                } else {
                    $suffix_counter++;
                }

                return $this->get_return_public_id($supplier_delivery_id, $suffix_counter);
            } else {
                return $supplier_delivery_id.$suffix_counter;
            }
        }
    }


    function get_field_label($field)
    {
        switch ($field) {
            case 'Order Customer Purchase Order ID':
                return _("customer's purchase order number");

            case 'Order Tax Number':
                return _("tax number");

            case 'Order Tax Number Valid':
                return _("tax number valid");

            case 'Order Delivery Address':
                return _("delivery address");

            case 'Order Invoice Address':
                return _("invoice address");

            case 'Order Customer Name':
                return _("customer name");

            case 'Order Registration Number':
                return _("registration number");

            case 'Order Source Key':
                return _("Sell channel");

            default:
                return $field;
        }

    }

    function submit_pastpay_invoice($debug=false)
    {
        $store = get_object('Store', $this->get('Order Store Key'));
        $website = get_object('Website', $store->get('Store Website Key'));
        $payment_account_key = $website->get_payment_account__key('Pastpay');
        $payment_account = get_object('Payment_Account', $payment_account_key);
        $api_key = $payment_account->get('Payment Account Password');

        $customer = get_object('Customer', $this->get('Order Customer Key'));


        if ($this->get('Order Invoice Address Country 2 Alpha Code') == 'GB') {
            $tax_number = 'GB' . $customer->get('Customer Registration Number');
        } else {
            $tax_number = $customer->get('Customer Tax Number');

            if(preg_match('/^\d/',$tax_number)){
                $tax_number=$this->get('Order Invoice Address Country 2 Alpha Code').$tax_number;
            }

            $tax_number = preg_replace('/^(PL|HU)/i', '', $tax_number);

        }


        $invoices = $this->get_invoices();
        $invoice_key = array_pop($invoices);

        $invoice = get_object('invoice', $invoice_key);

        $currency = $this->get('Order Currency');

        $pastpay_data = json_decode($this->get('Order Pastpay Data'), true);
        $term = $pastpay_data['term'];

        $smarty = new Smarty();
        //$smarty->caching_type = 'redis';
        $smarty->setTemplateDir('templates');
        $smarty->setCompileDir('server_files/smarty/templates_c');
        $smarty->setCacheDir('server_files/smarty/cache');
        $smarty->setConfigDir('server_files/smarty/configs');
        $smarty->addPluginsDir('./smarty_plugins');
        $db = $this->db;
        $account = get_object('Account', 1);

        $_REQUEST['id'] = $invoice->id;
        $save_to_file = 'server_files/tmp/' . $invoice->id . '_invoice.pdf';
        require_once 'invoice.pdf.common.php';


        $encoded_invoice = base64_encode(file_get_contents($save_to_file));


        if (DNS_ACCOUNT_CODE == 'AW') {
            $creditorTaxNumber = 'GB04108870';

        } elseif (DNS_ACCOUNT_CODE == 'AROMA') {
            $creditorTaxNumber = 'GB12796117';

        } elseif (DNS_ACCOUNT_CODE == 'ES') {
            $creditorTaxNumber = 'ESB93657658';

        } else {
            $creditorTaxNumber = 'SK2120525440';

        }
    

        $data = [
            'creditorTaxNumber' => $creditorTaxNumber,
            'invoiceNo'         => $invoice->get('Invoice Public ID'),


            'totalPrice' => [
                'amount' => (float)$invoice->get('Invoice Total Amount'),
                'currency'   => $currency
            ],

            'issueDate'  => date('Y-m-d', strtotime($invoice->get('Invoice Date'))),
            'dueDate'    => date('Y-m-d', strtotime($invoice->get('Invoice Date')." + $term days")),
            'invoicePdf' => 'data:application/pdf;base64,'.$encoded_invoice

        ];


        $order_id = $this->get('Order Public ID');



        $res = $this->pastpay_api_post_call("/debtors/$tax_number/order/$order_id/finalize", $data, $api_key);




        if($debug) {

            print_r(json_encode($data));
            //        exit;
            print "=========\n";


            print "=========\n";

            print "=========\n";
            print "/debtors/$tax_number/order/$order_id/finalize";
            print "=========\n";

            print_r($res);
            exit;
        }
    }

    function pastpay_api_post_call($url, $data, $api_key = false, $type = 'POST', $db = false)
    {
        $ch = curl_init();

        $base_url = 'https://api.pastpay.com';
        if (ENVIRONMENT == 'DEVEL') {
            $base_url = 'https://api.demo.pastpay.com';
        }

        curl_setopt($ch, CURLOPT_URL, $base_url.$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($type == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $headers   = array();
        $headers[] = 'Content-Type: application/json';
        if ($api_key) {
            $headers[] = "X-Api-Key: $api_key";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);


        if ($res === false) {
            Sentry\captureMessage('Curl error: '.curl_error($ch));
            curl_close($ch);

            return false;
        } else {
            curl_close($ch);

            if ($db) {
                $sql = "insert into hokodo_debug (`request`,`data`,`response`,`date`,`status`) values (?,?,?,?,?) ";
                $db->prepare($sql)->execute(
                    [
                        $url,
                        json_encode($data),
                        $res,
                        gmdate('Y-m-d H:i:s'),
                        'ok'

                    ]
                );
            }

            return json_decode($res, true);
        }
    }

}



