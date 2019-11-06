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

    use OrderShippingOperations, OrderChargesOperations, OrderDiscountOperations, OrderItems, OrderPayments, OrderCalculateTotals, OrderBasketOperations, OrderTax;


    var $amount_off_allowance_data = false;
    var $ghost_order = false;
    var $update_stock = true;
    var $skip_update_after_individual_transaction = false;

    /** @var PDO */
    var $db;

    function __construct($arg1 = false, $arg2 = false) {


        global $db;
        $this->db = $db;


        $this->deleted_otfs = array();
        $this->new_otfs     = array();


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

    function set_display_currency($currency_code, $exchange) {
        $this->currency_code = $currency_code;
        $this->exchange      = $exchange;

    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {

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
                               </div>', $dn->id, 'delivery_notes/'.$dn->get('Delivery Note Store Key').'/'.$dn->id, $dn->get('ID'), $dn->get('Abbreviated State'), _('Picking sheet'), ($dn->get('State Index') != 10 ? 'hide' : ''), $dn->id,
                        ($dn->get('State Index') < 90 ? 'hide' : ''), $dn->id, (($dn->get('State Index') != 10 or $store->settings('data_entry_picking_aid') != 'Yes') ? 'hide' : ''), _('Input picking sheet data'), $dn->id

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
                $this->order_totals_changed_post_operation();
                break;
            case('Order Tax Number Valid'):
                if ($this->get('State Index') <= 0) {
                    return;
                }
                $this->update_tax_number_valid($value);
                $this->order_totals_changed_post_operation();
                break;
            case 'Order Invoice Address':
                if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
                    return;
                }
                $this->update_address('Invoice', json_decode($value, true));
                $this->order_totals_changed_post_operation();
                break;
            case 'Order Delivery Address':
                if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
                    return;
                }
                $this->update_address('Delivery', json_decode($value, true));
                $this->order_totals_changed_post_operation();
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

                break;
            case('Sticky Note'):
                $this->update_field('Order '.$field, $value, 'no_null');
                $this->new_value = html_entity_decode($this->new_value);
                break;


            default:
                $base_data = $this->base_data();


                if (array_key_exists($field, $base_data)) {


                    $this->update_field($field, $value, $options);

                }
        }

    }

    function get($key = '') {

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
                $this->exchange * $this->data[$amount], $this->currency_code
            );
        }
        if (preg_match('/^Number (Items|Products)$/', $key)) {

            $amount = 'Order '.$key;

            return number($this->data[$amount]);
        }


        switch ($key) {

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

                switch ($this->data['Order Tax Code']) {

                    case 'OUT':
                        $tax_description = _('Outside the scope od VAT');
                        break;
                    case 'EU':
                        $tax_description = sprintf(_('EC with %s'), $this->get('Tax Number Formatted'));
                        break;
                    default:

                        switch ($this->metadata('why_tax')) {
                            case 'EC with invalid tax number':
                                $tax_description = sprintf(_('EC with %s'), $this->get('Tax Number Formatted'));
                                break;
                            default:
                                $why_tax_formatted = '';
                                $tax_description   = $this->metadata('tax_name').$why_tax_formatted.$this->data['Order Tax Code'];

                        }


                }


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


            case('DC Total Amount'):
                $account = get_object('Account', 1);

                return money(
                    $this->data['Order Total Amount'] * $this->data['Order Currency Exchange'], $account->get('Currency Code')
                );


            case("Sticky Note"):
                return nl2br($this->data['Order Sticky Note']);

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

            case('Out of Stock Net Amount'):
                return money(
                    -1 * $this->data['Order Out of Stock Net Amount'], $this->data['Order Currency']
                );

            case('Not Found Net Amount'):
                return money(
                    -1 * $this->data['Order Not Found Net Amount'], $this->data['Order Currency']
                );

            case('Not Due Other Net Amount'):
                return money(
                    -1 * $this->data['Order Not Due Other Net Amount'], $this->data['Order Currency']
                );
                break;
            case('No Authorized Net Amount'):
                return money(
                    -1 * $this->data['Order No Authorized Net Amount'], $this->data['Order Currency']
                );

            case('Invoiced Total Net Amount'):
                return money(
                    $this->data['Order Invoiced Net Amount'], $this->data['Order Currency']
                );
                break;
            case('Invoiced Total Amount'):
                return money(
                    $this->data['Order Invoiced Net Amount'] + $this->data['Order Invoiced Tax Amount'], $this->data['Order Currency']
                );

            case ('Invoiced Refund Total Amount'):
                return money(
                    $this->data['Order Invoiced Refund Net Amount'] + $this->data['Order Invoiced Refund Tax Amount'], $this->data['Order Currency']
                );


            case('Total Balance'):
            case('Total Refunds'):
                return money(
                    $this->data['Order '.$key], $this->data['Order Currency']
                );


            case('To Pay Amount Absolute'):
                return money(
                    abs($this->data['Order To Pay Amount']), $this->data['Order Currency']
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


            case('Submitted by Customer Interval'):
                if ($this->data['Order Submitted by Customer Date'] == '') {
                    return '';
                }
                include_once 'common_natural_language.php';

                return seconds_to_string(
                    gmdate('U', strtotime($this->data['Order Submitted by Customer Date'].' +0:00')) - gmdate('U', strtotime($this->data['Order Created Date'].' +0:00'))
                );
                break;
            case('Send to Warehouse Interval'):
                if ($this->data['Order Submitted by Customer Date'] == '' or $this->data['Order Send to Warehouse Date'] == '') {
                    return '';
                }
                include_once 'common_natural_language.php';

                return seconds_to_string(
                    gmdate(
                        'U', strtotime($this->data['Order Send to Warehouse Date'].' +0:00')
                    ) - gmdate(
                        'U', strtotime(
                               $this->data['Order Submitted by Customer Date'].' +0:00'
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
                        'U', strtotime($this->data['Order Packed Done Date'].' +0:00')
                    ) - gmdate(
                        'U', strtotime($this->data['Order Send to Warehouse Date'].' +0:00')
                    )
                );
                break;
            case('Dispatched Interval'):
                if ($this->data['Order Packed Done Date'] == '' or $this->data['Order Dispatched Date'] == '') {
                    return '';
                }
                include_once 'common_natural_language.php';

                return seconds_to_string(
                    gmdate('U', strtotime($this->data['Order Dispatched Date'].' +0:00')) - gmdate(
                        'U', strtotime($this->data['Order Packed Done Date'].' +0:00')
                    )
                );
                break;


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
                    "select sum(`Transaction Net Amount`) as amount from `Order No Product Transaction Fact`  left join `Charge Dimension` on (`Charge Key`=`Transaction Type Key`)   where  `Charge Scope`='Hanging' and  `Transaction Type`='Charges' and `Order Key`=%d  ",
                    $this->id
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $amount = $row['amount'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                return $amount;


            case 'replacements_in_process':
                return $this->data['Order Replacements In Warehouse without Alerts'] + $this->data['Order Replacements In Warehouse with Alerts'] + $this->data['Order Replacements Packed Done'] + $this->data['Order Replacements Approved'];


            case('Tax Number Valid'):
                if ($this->data['Order Tax Number'] != '') {

                    if ($this->data['Order Tax Number Validation Date'] != '') {
                        $_tmp = gmdate("U") - gmdate(
                                "U", strtotime(
                                       $this->data['Order Tax Number Validation Date'].' +0:00'
                                   )
                            );
                        if ($_tmp < 3600) {
                            $date = strftime("%e %b %Y %H:%M:%S %Z", strtotime($this->data['Order Tax Number Validation Date'].' +0:00'));

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
                            '<i style="margin-right: 0px" class="fa fa-check success" title="'._('Valid').'"></i> <span title="'.$title.'" >%s</span>', $this->data['Order Tax Number'].$source
                        );
                    } elseif ($this->data['Order Tax Number Valid'] == 'Unknown') {
                        return sprintf(
                            '<i style="margin-right: 0px" class="fal fa-question-circle discreet" title="'._('Unknown if is valid').'"></i> <span class="discreet" title="'.$title.'">%s</span>', $this->data['Order Tax Number'].$source
                        );
                    } elseif ($this->data['Order Tax Number Valid'] == 'API_Down') {
                        return sprintf(
                            '<i style="margin-right: 0px"  class="fal fa-question-circle discreet" title="'._('Validity is unknown').'"> </i> <span class="discreet" title="'.$title.'">%s</span> %s', $this->data['Order Tax Number'],
                            ' <i  title="'._('Online validation service down').'" class="fa fa-wifi-slash error"></i>'
                        );
                    } else {
                        return sprintf(
                            '<i style="margin-right: 0px" class="fa fa-ban error" title="'._('Invalid').'"></i> <span class="discreet" title="'.$title.'">%s</span>', $this->data['Order Tax Number'].$source
                        );
                    }
                }

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

                    $msg = $this->data['Order Tax Number Validation Message'];

                    if ($this->data['Order Tax Number Validation Source'] == 'Online') {
                        $source = '<i title=\''._('Validated online').'\' class=\'far fa-globe\'></i>';


                    } elseif ($this->data['Order Tax Number Validation Source'] == 'Staff') {
                        $source = '<i title=\''._('Set up manually').'\' class=\'far fa-hand-rock\'></i>';
                    } else {
                        $source = '';
                    }

                    $validation_data = trim($date.' '.$source.' '.$msg);
                    if ($validation_data != '') {
                        $validation_data = ' <span class=\'discreet\'>('.$validation_data.')</span>';
                    }

                    switch ($this->data['Order Tax Number Valid']) {
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
                            return $this->data['Order Tax Number Valid'].$validation_data;

                            break;
                    }
                }
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

    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }

    function get_deliveries($scope = 'keys') {


        $deliveries = array();
        $sql        = sprintf(
            "SELECT `Delivery Note Key` FROM `Delivery Note Dimension` WHERE `Delivery Note Order Key`=%d ORDER BY `Delivery Note Key` DESC ", $this->id
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

    function order_totals_changed_post_operation() {


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

    function update_state($value, $options = '{}', $metadata = array()) {

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

                        return;

                    }
                    $this->fast_update(
                        array(
                            'Order State'                      => $value,
                            'Order Submitted by Customer Date' => '',
                            'Order Date'                       => $date,
                            'Order Last Updated by Customer'   => $date,
                        )
                    );


                    $history_data = array(
                        'History Abstract' => _('Order send back to basket'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);

                    $operations = array(
                        'send_to_warehouse_operations',
                        'cancel_operations',
                        'submit_operations'
                    );


                    $sql = sprintf(
                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='In Process' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('Out of Stock in Basket')  ", $this->id


                    );

                    // todo check 'Out of Stock in Basket' etc

                    $this->db->exec($sql);


                    //todo if user want to keep old allowances  you must do an if here
                    $this->fast_update(
                        array(
                            'Order Pinned Deal Components' => json_encode(array())
                        )
                    );


                    $sql = sprintf(
                        "UPDATE `Order Transaction Deal Bridge` SET `Order Transaction Deal Pinned`='No' WHERE `Order Key`=%d   ",


                        $this->id
                    );

                    $this->db->exec($sql);

                    $old_used_deals = $this->get_used_deals();

                    $this->update_totals();
                    $this->update_discounts_items();
                    $this->update_totals();
                    $this->update_shipping(false, false);
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
                            'au_housekeeping', array(
                            'type'            => 'update_deals_usage',
                            'campaigns'       => $campaigns_diff,
                            'deals'           => $deal_diff,
                            'deal_components' => $deal_components_diff,


                        ), $account->get('Account Code'), $this->db
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


                    break;
                case 'InProcess':
                case 'Delivery_Note_deleted':


                    if ($this->data['Order State'] == 'Cancelled' or $this->data['Order State'] == 'Dispatched') {
                        $this->error = true;
                        $this->msg   = 'Cant set as in process :(';

                        return;

                    }

                    $this->fast_update(
                        array(
                            'Order State' => 'InProcess',
                            'Order Date'  => $date
                        )
                    );


                    if ($value == 'InProcess') {


                        $this->fast_update(
                            array(
                                'Order Submitted by Customer Date' => $date,
                            )
                        );


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


                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);

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
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
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
                            "select * from `Deal Component Dimension` left join `Deal Dimension` D on (D.`Deal Key`=`Deal Component Deal Key`)  where `Deal Component Key` in (%s)", $deal_components
                        );


                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {

                                $deals_component_data[$row['Deal Component Key']] = $row;
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }
                    }


                    $sql = sprintf(
                        "UPDATE `Order Transaction Deal Bridge` SET `Order Transaction Deal Pinned`='Yes' WHERE `Order Key`=%d   ", $this->id
                    );
                    $this->db->exec($sql);
                    $sql = sprintf(
                        "UPDATE `Order No Product Transaction Deal Bridge` SET `Order No Product Transaction Deal Pinned`='Yes' WHERE `Order Key`=%d   ", $this->id
                    );

                    $this->db->exec($sql);

                    $this->fast_update(
                        array(
                            'Order Pinned Deal Components' => json_encode($deals_component_data)
                        )
                    );

                    $hide = array('customer_balance');

                    $show = array(
                        'order_payments_list',
                        'payment_overview'
                    );

                    break;


                case 'Delivery Note Cancelled':


                    if ($this->data['Order State'] == 'Cancelled') {
                        return;
                    }


                    if ($this->get('Order State') >= 90) {
                        $this->error = true;
                        $this->msg   = 'Cant set as back to in process :(';

                        return;

                    }

                    $value = 'InProcess';

                    $this->update_field('Order State', $value, 'no_history');
                    $this->update_field('Order Date', $date, 'no_history');


                    $this->update_field('Order Send to Warehouse Date', '', 'no_history');
                    $this->update_field('Order Packed Done Date', '', 'no_history');


                    $this->update_field('Order Date', $this->data['Order Submitted by Customer Date'], 'no_history');
                    $this->update_field('Order Delivery Note Key', '', 'no_history');


                    $history_data = array(
                        'History Abstract' => _('Delivery note cancelled, order back to submited state'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);

                    $operations = array(
                        'send_to_warehouse_operations',
                        'cancel_operations',
                        'undo_submit_operations'
                    );

                    $sql = sprintf(


                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Submitted by Customer' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('Out of Stock in Basket')  ", $this->id

                    );

                    $this->db->exec($sql);

                    $customer = get_object('Customer', $this->data['Order Customer Key']);
                    $customer->update_last_dispatched_order_key();


                    break;


                case 'InWarehouse':

                    global $session;
                    $warehouse_key = $session->get('current_warehouse');

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
                    );


                    $delivery_note = new DeliveryNote('create', $data_dn, $this);
                    //059-645919

                    new_housekeeping_fork(
                        'au_housekeeping', array(
                        'type'              => 'delivery_note_created',
                        'delivery_note_key' => $delivery_note->id,
                        'customer_key'      => $delivery_note->get('Delivery Note Customer Key'),
                        'store_key'         => $delivery_note->get('Delivery Note Store Key'),
                    ), $account->get('Account Code')
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
                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->table_name, $this->id);


                    $operations = array('cancel_operations');


                    $sql = sprintf(


                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Ready to Pick' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('Out of Stock in Basket')  ", $this->id


                    );

                    $this->db->exec($sql);


                    break;
                case 'PackedDone':


                    if ($this->data['Order State'] != 'InWarehouse') {
                        $this->error = true;
                        $this->msg   = 'Order is not in warehouse: :(';

                        return;

                    }


                    $this->update_field('Order State', $value, 'no_history');
                    $this->update_field('Order Packed Done Date', $date, 'no_history');
                    $this->update_field('Order Date', $date, 'no_history');


                    $history_data = array(
                        'History Abstract' => _('Order packed and closed'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);

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

                        return;

                    }


                    $this->update_field('Order State', 'InWarehouse', 'no_history');
                    $this->update_field('Order Packed Done Date', '', 'no_history');
                    $this->update_field('Order Date', $date, 'no_history');


                    $history_data = array(
                        'History Abstract' => _('Undo packed and closed'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);

                    $operations = array(
                        'cancel_operations'
                    );


                    $this->update_totals();

                    break;

                case 'Invoice Deleted':


                    switch ($this->get('Order State')) {
                        case 'Approved':

                            $this->fast_update(
                                array(
                                    'Order State'            => 'PackedDone',
                                    'Order Packed Done Date' => $date,
                                    'Order Invoiced Date'    => '',
                                    'Order Invoice Key'      => '',
                                    'Order Date'             => $date
                                )
                            );


                            $history_data = array(
                                'History Abstract' => _('Invoice deleted, order state back to packed and closed'),
                                'History Details'  => '',
                            );
                            $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);

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

                            $this->fast_update(
                                array(
                                    'Order Invoiced Date' => '',
                                    'Order Invoice Key'   => '',
                                    'Order Date'          => $date
                                )
                            );


                            $history_data = array(
                                'History Abstract' => _('Invoice deleted'),
                                'History Details'  => '',
                            );
                            $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);

                            $operations = array();


                            break;
                        default:
                            $this->error = true;
                            $this->msg   = 'Order is not in Approved: :(';

                            return;


                    }

                    $this->fast_update_json_field('Order Metadata', 'original_tax_code', '');
                    $this->fast_update_json_field('Order Metadata', 'original_tax_description', '');

                    $dn         = get_object('DeliveryNote', $this->data['Order Delivery Note Key']);
                    $dn->editor = $this->editor;
                    $dn->fast_update(
                        array(
                            'Delivery Note Invoiced'                    => 'No',
                            'Delivery Note Invoiced Net DC Amount'      => 0,
                            'Delivery Note Invoiced Shipping DC Amount' => 0,
                        )
                    );

                    $dn->update(
                        array(

                            'Delivery Note State' => 'Invoice Deleted',
                        )
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


                    $invoice = $this->create_invoice($date);

                    $dn         = get_object('DeliveryNote', $this->data['Order Delivery Note Key']);
                    $dn->editor = $this->editor;

                    $dn->fast_update(
                        array(
                            'Delivery Note Invoiced'                    => 'Yes',
                            'Delivery Note Invoiced Net DC Amount'      => $invoice->get('Invoice Total Net Amount') * $invoice->get('Invoice Currency Exchange'),
                            'Delivery Note Invoiced Shipping DC Amount' => $invoice->get('Invoice Shipping Net Amount') * $invoice->get('Invoice Currency Exchange'),
                        )
                    );


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
                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->table_name, $this->id);


                    $operations = array('cancel_operations');

                    //'In Process by Customer','Submitted by Customer','In Process','Ready to Pick','Picking','Ready to Pack','Ready to Ship','Dispatched','Unknown','Packing','Packed','Packed Done','Cancelled','No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Suspended','Cancelled by Customer','Out of Stock in Basket'

                    $sql = sprintf(

                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Ready to Ship' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Out of Stock in Basket')  ",
                        $this->id

                    );

                    $this->db->exec($sql);


                    break;


                case 'un_dispatch':


                    if ($this->data['Order State'] != 'Dispatched') {
                        $this->error = true;
                        $this->msg   = 'Order is not in Dispatched: :(';

                        return;

                    }


                    if ($this->get('Order Invoice Key')) {
                        $value = 'Approved';
                    } else {
                        $value = 'PackedDone';
                    }


                    $this->update_field('Order State', $value, 'no_history');
                    $this->update_field('Order Dispatched Date', '', 'no_history');


                    $history_data = array(
                        'History Abstract' => _('Order set as not dispatched'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);

                    $operations = array();
                    //'In Process by Customer','Submitted by Customer','In Process','Ready to Pick','Picking','Ready to Pack','Ready to Ship','Dispatched','Unknown','Packing','Packed','Packed Done','Cancelled','No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Suspended','Cancelled by Customer','Out of Stock in Basket'

                    $sql = sprintf(
                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Ready to Ship' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Out of Stock in Basket')  ",
                        $this->id
                    );

                    $this->db->exec($sql);

                    $customer = get_object('Customer', $this->data['Order Customer Key']);
                    $customer->update_last_dispatched_order_key();


                    break;

                case 'Dispatched':


                    if ($this->data['Order State'] != 'Approved') {
                        $this->error = true;
                        $this->msg   = 'Order is not in Approved: :(';

                        return;

                    }


                    $this->update_field('Order State', $value, 'no_history');
                    $this->update_field('Order Dispatched Date', $date, 'no_history');


                    $history_data = array(
                        'History Abstract' => _('Order dispatched'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);

                    $operations = array();


                    $sql = sprintf(
                        "UPDATE `Order Transaction Fact` SET `Current Dispatching State`='Dispatched' WHERE `Order Key`=%d  AND `Current Dispatching State` NOT IN ('No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Out of Stock in Basket')  ",
                        $this->id

                    );

                    $this->db->exec($sql);

                    $customer = get_object('Customer', $this->data['Order Customer Key']);


                    $customer->fast_update(
                        array(
                            'Customer Last Dispatched Order Key'  => $this->id,
                            'Customer Last Dispatched Order Date' => gmdate('Y-m-d', strtotime($date.' +0:00'))
                        )
                    );


                    new_housekeeping_fork(
                        'au_housekeeping', array(
                        'type'              => 'order_dispatched',
                        'order_key'         => $this->id,
                        'delivery_note_key' => $metadata['delivery_note_key']
                    ), $account->get('Account Code')
                    );


                    break;
                case 'ReInvoice':


                    if ($this->get('State Index') <= 80) {
                        $this->error = true;
                        $this->msg   = 'try invoice_recreated but Order has State Index <=80';

                        return false;

                    }

                    include_once('class.Invoice.php');


                    $invoice = $this->create_invoice($date);

                    $dn         = get_object('DeliveryNote', $this->data['Order Delivery Note Key']);
                    $dn->editor = $this->editor;

                    $dn->update(
                        array(
                            'Delivery Note Invoiced'                    => 'Yes',
                            'Delivery Note Invoiced Net DC Amount'      => $invoice->get('Invoice Total Net Amount') * $invoice->get('Invoice Currency Exchange'),
                            'Delivery Note Invoiced Shipping DC Amount' => $invoice->get('Invoice Shipping Net Amount') * $invoice->get('Invoice Currency Exchange'),
                        )
                    );


                    $this->fast_update(
                        array(
                            'Order Invoiced Date' => $date,
                            'Order Date'          => $date,
                            'Order Invoice Key'   => $invoice->id,
                        )
                    );


                    $history_data = array(
                        'History Abstract' => _('Order invoiced again'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->table_name, $this->id);


                    $operations = array('');


                    break;

                default:
                    exit('unknown state:::'.$value);
                    break;
            }


        }

        foreach ($this->get_deliveries('objects') as $dn) {
            $number_deliveries++;
            $deliveries_xhtml .= sprintf(
                ' <div class="node"  id="delivery_node_%d"><span class="node_label"><i class="fa fa-truck fa-flip-horizontal fa-fw" aria-hidden="true"></i> 
                               <span class="link" onClick="change_view(\'%s\')">%s</span> (<span class="Delivery_Note_State">%s</span>)
                                <a title="%s" class="pdf_link %s" target="_blank" href="/pdf/order_pick_aid.pdf.php?id=%d"> <i class="fal fa-clipboard-list-check " style="font-size: larger"></i></a>
                                <a class="pdf_link %s" target=\'_blank\' href="/pdf/dn.pdf.php?id=%d"> <img style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif"></a>
                               </span>
                                <div class="order_operation data_entry_delivery_note %s"><div class="square_button right" title="%s"><i class="fa fa-keyboard" aria-hidden="true" onclick="data_entry_delivery_note(%s)"></i></div></div>
                               </div>', $dn->id, 'delivery_notes/'.$dn->get('Delivery Note Store Key').'/'.$dn->id, $dn->get('ID'), $dn->get('Abbreviated State'), _('Picking sheet'), ($dn->get('State Index') != 10 ? 'hide' : ''), $dn->id,
                ($dn->get('State Index') < 90 ? 'hide' : ''), $dn->id, (($dn->get('State Index') != 10 or $store->settings('data_entry_picking_aid') != 'Yes') ? 'hide' : ''), _('Input picking sheet data'), $dn->id

            );

        }

        foreach ($this->get_invoices('objects') as $invoice) {
            $number_invoices++;
            $invoices_xhtml .= sprintf(
                ' <div class="node" id="invoice_%d">
                    <span class="node_label" >
                        <i class="fal fa-file-invoice-dollar fa-fw %s" aria-hidden="true"></i>
                        <span class="link %s" onClick="change_view(\'%s\')">%s</span>
                        <img class="button pdf_link" onclick="download_pdf_from_list(%d,$(\'.pdf_invoice_dialog img\'))" style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif">
                        <i onclick="show_pdf_invoice_dialog(this,%d)" title="%s" class="far very_discreet fa-sliders-h-square button"></i>
                    </span>
                    <div class="red" style="float: right;padding-right: 10px;padding-top: 5px">%s
                    </div>
                </div>', $invoice->id, ($invoice->get('Invoice Type') == 'Refund' ? 'error' : ''), ($invoice->get('Invoice Type') == 'Refund' ? 'error' : ''), 'invoices/'.$invoice->get('Invoice Store Key').'/'.$invoice->id, $invoice->get('Invoice Public ID'),
                $invoice->id, $invoice->id, _('PDF invoice display settings'),
                ($invoice->get('Invoice Type') == 'Refund' ? $invoice->get('Refund Total Amount').' '.($invoice->get('Invoice Paid') != 'Yes' ? '<i class="fa fa-exclamation-triangle warning fa-fw" aria-hidden="true" title="'._('Return payment pending').'"></i>' : '')
                    : '')

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


        if ($this->data['Order State'] != 'Cancelled') {

            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'      => 'order_state_changed',
                'order_key' => $this->id,
            ), $account->get('Account Code')
            );
        }


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

        $sql = sprintf(
            "UPDATE `Order Dimension` SET  `Order Cancelled Date`=%s, `Order State`=%s,`Order Payment State`='NA',`Order To Pay Amount`=%.2f,`Order Cancel Note`=%s,
				`Order Balance Net Amount`=0,`Order Balance tax Amount`=0,`Order Balance Total Amount`=0,`Order Items Cost`=0,
				`Order Invoiced Balance Net Amount`=0,`Order Invoiced Balance Tax Amount`=0,`Order Invoiced Balance Total Amount`=0 ,`Order Invoiced Outstanding Balance Net Amount`=0,`Order Invoiced Outstanding Balance Tax Amount`=0,`Order Invoiced Outstanding Balance Total Amount`=0,`Order Invoiced Profit Amount`=0
				WHERE `Order Key`=%d", prepare_mysql($this->data['Order Cancelled Date']), prepare_mysql($this->data['Order State']), $this->data['Order To Pay Amount'], prepare_mysql($this->data['Order Cancel Note']),

            $this->id
        );


        $this->db->exec($sql);


        $sql = sprintf(
            "UPDATE `Order Transaction Fact` SET  `Delivery Note Key`=NULL,`Invoice Key`=NULL, `Consolidated`='Yes',`Current Dispatching State`=%s ,`Cost Supplier`=0  WHERE `Order Key`=%d ", prepare_mysql('Cancelled'), $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(

            "UPDATE `Order Transaction Fact` SET   `Delivery Note Quantity`=0, `No Shipped Due Out of Stock`=0,`Order Out of Stock Lost Amount`=0 WHERE `Order Key`=%d ",

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


        return true;

    }


    function create_invoice($date) {


        $store = get_object('Store', $this->data['Order Store Key']);


        if ($store->data['Store Next Invoice Public ID Method'] == 'Order ID') {
            $invoice_public_id = $this->data['Order Public ID'];
            $file_as           = $this->data['Order File As'];
        } elseif ($store->data['Store Next Invoice Public ID Method'] == 'Invoice Public ID') {

            $sql = sprintf(
                "UPDATE `Store Dimension` SET `Store Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Store Invoice Last Invoice Public ID` + 1) WHERE `Store Key`=%d", $this->data['Order Store Key']
            );
            $this->db->exec($sql);
            $public_id = $this->db->lastInsertId();

            $invoice_public_id = sprintf($store->data['Store Invoice Public ID Format'], $public_id);
            $file_as           = get_file_as($invoice_public_id);

        } else {

            $sql = sprintf(
                "UPDATE `Account Dimension` SET `Account Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Public ID` + 1) WHERE `Account Key`=1"
            );
            $this->db->exec($sql);
            $public_id = $this->db->lastInsertId();

            $account           = get_object('Account', 1);
            $invoice_public_id = sprintf(
                $account->data['Account Invoice Public ID Format'], $public_id
            );

            $file_as = get_file_as($invoice_public_id);
        }


        $data_invoice = array(
            'editor'               => $this->editor,
            'Invoice Date'         => $date,
            'Invoice Type'         => 'Invoice',
            'Invoice Public ID'    => $invoice_public_id,
            'Invoice File As'      => $file_as,
            'Invoice Order Key'    => $this->id,
            'Invoice Store Key'    => $this->data['Order Store Key'],
            'Invoice Customer Key' => $this->data['Order Customer Key'],
            'Invoice Tax Code'     => $this->data['Order Tax Code'],

            'Invoice Metadata'                      => $this->data['Order Original Metadata'],
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


            'Invoice Sales Representative Key'     => $this->data['Order Sales Representative Key'],

            //   'Invoice Telephone'                    => $this->data['Order Telephone'],
            //     'Invoice Email'                        => $this->data['Order Email'],
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

            'Invoice Payments Amount' => $this->data['Order Payments Amount'],
            'Invoice To Pay Amount'   => $this->data['Order To Pay Amount'],
            'Invoice Total Amount'    => $this->data['Order Total Amount'],
            'Invoice Currency'        => $this->data['Order Currency'],


        );


        $invoice = new Invoice ('create', $data_invoice);

        return $invoice;

    }

    function get_invoices($scope = 'keys', $options = '') {


        $invoices = array();


        switch ($options) {
            case 'refunds_only':
                $where = " and `Invoice Type`='Refund'";
                break;
            case 'invoices_only':
                $where = " and `Invoice Type`='Refund'";
                break;
            default:
                $where = '';

        }


        $sql = sprintf(
            "SELECT `Invoice Key` FROM `Invoice Dimension` WHERE `Invoice Order Key`=%d  %s ", $this->id, $where
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
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $invoices;

    }


    function get_deleted_invoices($scope = 'keys') {


        $deleted_invoices = array();
        $sql              = sprintf(
            "SELECT `Invoice Deleted Key` FROM `Invoice Deleted Dimension` WHERE `Invoice Deleted Order Key`=%d  ", $this->id
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
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $deleted_invoices;

    }

    function get_returns($scope = 'keys') {


        $returns = array();
        $sql     = sprintf(
            "SELECT `Supplier Delivery Key` FROM `Supplier Delivery Dimension` WHERE `Supplier Delivery Parent`='Order' and `Supplier Delivery Parent Key`=%d ORDER BY `Supplier Delivery Key` DESC ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Supplier Delivery Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {

                    $returns[$row['Supplier Delivery Key']] = get_object('SupplierDelivery', $row['Supplier Delivery Key']);

                } else {
                    $returns[$row['Supplier Delivery Key']] = $row['Supplier Delivery Key'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $returns;

    }

    function send_review_invitation() {


        if (preg_match('/bali|sasi|sakoi|geko/', gethostname()) or $this->get('Order Email') == '') {
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

                if (isset($settings['data']['delay'])) {
                    $delay = settings['data']['delay'];
                } else {
                    $delay = '7';
                }


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
                        'insert into `Order Review Invitation Dimension` (`Order Review Invitation Order Key`,`Order Review Invitation Provider`,`Order Review Invitation Date`,`Order Review Invitation Metadata`) values(%d,%s,%s,%s)', $this->id,
                        prepare_mysql($settings['provider']), prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql($response)


                    );

                    $this->db->exec($sql);


                } catch (Exception $e) {
                    return;

                }


            }

        }


    }

    function get_items() {


        $sql = sprintf(
            'SELECT `Deal Info`,`Current Dispatching State`,`Webpage State`,`Webpage URL`,`Product Main Image`,`Order State`,`Delivery Note Quantity`,`Order State`,OTF.`Product ID`,OTF.`Product Key`,OTF.`Order Transaction Fact Key`,`Order Currency Code`,`Order Transaction Amount`,`Order Quantity`,`Product History Name`,`Product History Units Per Case`,PD.`Product Code`,`Product Name`,`Product Units Per Case` 
      FROM `Order Transaction Fact` OTF LEFT JOIN `Product History Dimension` PHD ON (OTF.`Product Key`=PHD.`Product Key`) LEFT JOIN 
      `Product Dimension` PD ON (PD.`Product ID`=PHD.`Product ID`)  LEFT JOIN 
        `Order Dimension` O ON (O.`Order Key`=OTF.`Order Key`) Left join 
        `Page Store Dimension` W on (`Page Key`=`Product Webpage Key`) left join 
        `Order Transaction Deal Bridge` B on (OTF.`Order Transaction Fact Key`=B.`Order Transaction Fact Key`)

      WHERE OTF.`Order Key`=%d  ORDER BY `Product Code File As` ', $this->id
        );

        $items = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $edit_quantity = sprintf(
                    '<span    data-settings=\'{"field": "Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-plus fa-fw like_button button"  style="cursor:pointer" aria-hidden="true"></i></span>',
                    $row['Order Transaction Fact Key'], $row['Product ID'], $row['Product Key'], $row['Order Quantity'] + 0, $row['Order Quantity'] + 0
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


                $items[] = array(
                    'code'                 => $row['Product Code'],
                    'product_id'           => $row['Product ID'],
                    'product_historic_key' => $row['Product Key'],
                    'description'          => $row['Product History Units Per Case'].'x '.$row['Product History Name'],
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
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $items;

    }


    function get_formatted_payment_state() {
        return get_order_formatted_payment_state($this->data);

    }

    function get_date($field) {
        return strftime("%e %b %Y", strtotime($this->data[$field].' +0:00'));
    }

    function remove_out_of_stocks_from_basket($product_pid) {


        $sql           = sprintf(
            "SELECT `Order Transaction Fact Key`,`Order Quantity`,`Product Key`,`Product ID`,`Order Transaction Amount` FROM `Order Transaction Fact` WHERE `Current Dispatching State` IN ('In Process','In Process by Customer') AND  `Product ID`=%d AND `Order Key`=%d ",
            $product_pid, $this->id
        );
        $affected_rows = 0;


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $sql = sprintf(
                    'INSERT INTO `Order Transaction Out of Stock in Basket Bridge` (`Order Transaction Fact Key`,`Date`,`Store Key`,`Order Key`,`Product Key`,`Product ID`,`Quantity`,`Amount`) VALUES (%d,%s,%d,%d,%d,%d,%f,%.2f)', $row['Order Transaction Fact Key'],
                    prepare_mysql(gmdate('Y-m-d H:i:s')), $this->data['Order Store Key'], $this->id, $row['Product Key'], $row['Product ID'], $row['Order Quantity'], $row['Order Transaction Amount']
                );

                $this->db->exec($sql);


                $sql = sprintf(
                    'UPDATE `Order Transaction Fact` SET `Current Dispatching State`=%s,`Order Quantity`=0,`Order Bonus Quantity`=0 ,`Order Transaction Gross Amount`=0 ,`Order Transaction Total Discount Amount`=0,`Order Transaction Amount`=0 WHERE `Order Transaction Fact Key`=%d   ',
                    prepare_mysql('Out of Stock in Basket'), $row['Order Transaction Fact Key']
                );
                $this->db->exec($sql);

                $affected_rows++;
            }
        }


    }

    function restore_back_to_stock_to_basket($product_pid) {

        if ($this->data['Order State'] != 'InBasket') {
            return;
        }

        $affected_rows = 0;

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
        }


        if ($affected_rows) {
            $dn_key = 0;

            $old_used_deals = $this->get_used_deals();


            $this->update_number_products();
            //$this->update_insurance();

            $this->update_discounts_items();
            $this->update_totals();


            $this->update_shipping($dn_key, false);
            $this->update_charges($dn_key, false);
            $this->update_discounts_no_items($dn_key);


            $this->update_deal_bridge();


            $this->update_totals();


            $this->update_number_products();

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
                    'au_housekeeping', array(
                    'type'            => 'update_deals_usage',
                    'campaigns'       => $campaigns_diff,
                    'deals'           => $deal_diff,
                    'deal_components' => $deal_components_diff,


                ), $account->get('Account Code'), $this->db
                );
            }


            //$this->apply_payment_from_customer_account();


        }

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

    /*
    function update_insurance($dn_key = false) {

        if ($this->get('State Index') >= 90 or $this->get('State Index') <= 0) {
            return;
        }

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
        // $this->apply_payment_from_customer_account();

    }

    function get_insurances($dn_key = false) {


        include_once('class.TaxCategory.php');

        $insurances = array();
        if ($this->data['Order Number Items'] == 0) {

            return $insurances;
        }


        $sql = sprintf(
            "SELECT * FROM `Insurance Dimension` WHERE `Insurance Trigger`='Order' AND (`Insurance Trigger Key`=%d  OR `Insurance Trigger Key` IS NULL) AND `Insurance Store Key`=%d", $this->id, $this->data['Order Store Key']
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $apply_insurance = false;

                $order_amount = $this->data[$row['Insurance Terms Type']];


                if ($dn_key) {
                    switch ($row['Insurance Terms Type']) {

                        case 'Order Items Net Amount':

                            $sql = sprintf(
                                "SELECT sum(`Order Transaction Net Amount`*(`Delivery Note Quantity`/`Order Quantity`)) AS amount FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Delivery Note Key`=%d AND `Order Quantity`!=0", $this->id, $dn_key
                            );


                            if ($result2 = $this->db->query($sql)) {
                                if ($row2 = $result2->fetch()) {
                                    $order_amount = $row2['amount'];
                                } else {
                                    $order_amount = 0;
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                print "$sql\n";
                                exit;
                            }


                            break;


                        case 'Order Items Gross Amount':
                        default:
                            $sql = sprintf(
                                "SELECT sum(`Order Transaction Gross Amount`*(`Delivery Note Quantity`/`Order Quantity`)) AS amount FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Delivery Note Key`=%d AND `Order Quantity`!=0", $this->id, $dn_key
                            );


                            if ($result2 = $this->db->query($sql)) {
                                if ($row2 = $result2->fetch()) {
                                    $order_amount = $row2['amount'];
                                } else {
                                    $order_amount = 0;
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                print "$sql\n";
                                exit;
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


                $sql = sprintf(
                    "SELECT `Order No Product Transaction Fact Key`  FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d  AND `Transaction Type`='Insurance' AND `Transaction Type Key`=%d ", $this->id, $row['Insurance Key']
                );


                if ($result2 = $this->db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        $onptf_key = $row2['Order No Product Transaction Fact Key'];
                    } else {
                        $onptf_key = 0;
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                if ($apply_insurance) {
                    $insurances[$row['Insurance Key']] = array(
                        'Insurance Net Amount'                  => $charge_net_amount,
                        'Insurance Tax Amount'                  => $charge_tax_amount,
                        'Insurance Formatted Net Amount'        => money($this->exchange * $charge_net_amount, $this->currency_code),
                        'Insurance Formatted Tax Amount'        => money($this->exchange * $charge_tax_amount, $this->currency_code),
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
    */
    function create_refund($date, $transactions, $tax_only = false) {


        include_once 'class.Invoice.php';

        $store = get_object('Store', ($this->data['Order Store Key']));


        $invoice_public_id = '';


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
                        "UPDATE `Store Dimension` SET `Store Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Store Invoice Last Invoice Public ID` + 1) WHERE `Store Key`=%d", $this->data['Order Store Key']
                    );
                    $this->db->exec($sql);


                    $invoice_public_id = sprintf(
                        $store->data['Store Invoice Public ID Format'], $this->db->lastInsertId()
                    );


                } elseif ($store->data['Store Next Invoice Public ID Method'] == 'Order ID') {

                    $sql = sprintf(
                        "UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) WHERE `Store Key`=%d", $this->data['Order Store Key']
                    );
                    $this->db->exec($sql);
                    $invoice_public_id = sprintf(
                        $store->data['Store Order Public ID Format'], $this->db->lastInsertId()
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


            }


            if ($invoice_public_id != '') {
                $invoice_public_id = $this->get_refund_public_id($invoice_public_id.$store->data['Store Refund Suffix']);
            }

        } elseif ($store->data['Store Refund Public ID Method'] == 'Account Wide Own Index') {


            include_once 'class.Account.php';
            $account = new Account();
            $sql     = sprintf(
                "UPDATE `Account Dimension` SET `Account Invoice Last Refund Public ID` = LAST_INSERT_ID(`Account Invoice Last Refund Public ID` + 1) WHERE `Account Key`=1"
            );
            $this->db->exec($sql);
            $invoice_public_id = sprintf(
                $account->data['Account Refund Public ID Format'], $this->db->lastInsertId()
            );


        } elseif ($store->data['Store Refund Public ID Method'] == 'Store Own Index') {


            $sql = sprintf(
                "UPDATE `Store Dimension` SET `Store Invoice Last Refund Public ID` = LAST_INSERT_ID(`Store Invoice Last Refund Public ID` + 1) WHERE `Store Key`=%d", $this->data['Order Store Key']
            );
            $this->db->exec($sql);
            $invoice_public_id = sprintf(
                $store->data['Store Refund Public ID Format'], $this->db->lastInsertId()
            );


        } else { //Next Invoice ID


            if ($store->data['Store Next Invoice Public ID Method'] == 'Invoice Public ID') {

                $sql = sprintf(
                    "UPDATE `Store Dimension` SET `Store Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Store Invoice Last Invoice Public ID` + 1) WHERE `Store Key`=%d", $this->data['Order Store Key']
                );
                $this->db->exec($sql);
                $invoice_public_id = sprintf(
                    $store->data['Store Invoice Public ID Format'], $this->db->lastInsertId()
                );

            } elseif ($store->data['Store Next Invoice Public ID Method'] == 'Order ID') {

                $sql = sprintf(
                    "UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) WHERE `Store Key`=%d", $this->data['Order Store Key']
                );
                $this->db->exec($sql);
                $invoice_public_id = sprintf(
                    $store->data['Store Order Public ID Format'], $this->db->lastInsertId()
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

        }


        $file_as = get_file_as($invoice_public_id);


        $refund_data = array(
            'editor'               => $this->editor,
            'Invoice Date'         => $date,
            'Invoice Type'         => 'Refund',
            'Invoice Public ID'    => $invoice_public_id,
            'Invoice File As'      => $file_as,
            'Invoice Order Key'    => $this->id,
            'Invoice Store Key'    => $this->data['Order Store Key'],
            'Invoice Customer Key' => $this->data['Order Customer Key'],
            'Invoice Tax Code'     => $this->data['Order Tax Code'],

            'Invoice Metadata'                      => $this->data['Order Original Metadata'],
            'Invoice Tax Number'                    => $this->data['Order Tax Number'],
            'Invoice Tax Number Valid'              => $this->data['Order Tax Number Valid'],
            'Invoice Tax Number Validation Date'    => $this->data['Order Tax Number Validation Date'],
            'Invoice Tax Number Validation Source'  => $this->data['Order Tax Number Validation Source'],
            'Invoice Tax Number Validation Message' => $this->data['Order Tax Number Validation Message'],

            'Invoice Tax Number Associated Name'    => $this->data['Order Tax Number Associated Name'],
            'Invoice Tax Number Associated Address' => $this->data['Order Tax Number Associated Address'],


            'Invoice Net Amount Off'               => 0,
            'Invoice Customer Contact Name'        => $this->data['Order Customer Contact Name'],
            'Invoice Customer Name'                => $this->data['Order Customer Name'],
            'Invoice Customer Level Type'          => $this->data['Order Customer Level Type'],
            'Invoice Sales Representative Key'     => $this->data['Order Sales Representative Key'],

            //   'Invoice Telephone'                    => $this->data['Order Telephone'],
            //     'Invoice Email'                        => $this->data['Order Email'],
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


        );


        $refund = new Invoice('create refund', $refund_data, $transactions);


        return $refund;
    }

    function get_refund_public_id($refund_id, $suffix_counter = '') {
        $sql = sprintf(
            "SELECT `Invoice Public ID` FROM `Invoice Dimension` WHERE `Invoice Store Key`=%d AND `Invoice Public ID`=%s ", $this->data['Order Store Key'], prepare_mysql($refund_id.$suffix_counter)
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

    function create_replacement($transactions) {

        global $session;


        include_once 'utils/new_fork.php';


        $date = gmdate('Y-m-d H:i:s');

        $account = get_object('Account', 1);


        $warehouse_key = $session->get('current_warehouse');

        include_once('class.DeliveryNote.php');


        if ($this->data['Order For Collection'] == 'Yes') {
            $dispatch_method = 'Collection';
        } else {
            $dispatch_method = 'Dispatch';
        }

        $store = get_object('Store', $this->data['Order Store Key']);


        $replacement_public_id = $this->get_replacement_public_id($this->data['Order Public ID'].$store->data['Store Replacement Suffix']);


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
        );


        $replacement = new DeliveryNote('create replacement', $data_dn, $transactions);


        $this->update_number_replacements();


        if ($this->get('replacements_in_process') == 1) {
            $this->fast_update(
                array(
                    'Order Replacement Created Date' => $date
                )
            );
        }


        require_once 'utils/new_fork.php';

        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'      => 'replacement_created',
            'order_key' => $this->id,
            'editor'    => $this->editor
        ), $account->get('Account Code'), $this->db
        );


        return $replacement;
    }

    function get_replacement_public_id($dn_id, $suffix_counter = '') {
        $sql = sprintf(
            "SELECT `Delivery Note ID` FROM `Delivery Note Dimension` WHERE `Delivery Note Store Key`=%d AND `Delivery Note ID`=%s ", $this->data['Order Store Key'], prepare_mysql($dn_id.$suffix_counter)
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
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
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    function update_number_replacements() {

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


        // if($this->id){


        $sql = sprintf(
            "SELECT  `Delivery Note State`,count(*) as num  FROM `Delivery Note Dimension` WHERE `Delivery Note Order Key`=%d  and  `Delivery Note Type` in ('Replacement & Shortages', 'Replacement', 'Shortages') group by `Delivery Note State` ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                //'Ready to be Picked','Picker Assigned','Picking','Picked','Packing','Packed','Packed Done','Approved','Dispatched','Cancelled','Cancelled to Restock'

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
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "SELECT  `Delivery Note State`,count(*) as num  FROM `Delivery Note Dimension` WHERE `Delivery Note Order Key`=%d  and  `Delivery Note Type` in ('Replacement & Shortages', 'Replacement', 'Shortages') and `Delivery Note Waiting State`='Customer'  group by `Delivery Note State` ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

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
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $in_warehouse_no_alerts = $in_warehouse - $in_warehouse_with_alerts;


        $sql = sprintf(
            "SELECT count(*) AS num FROM `Delivery Note Dimension` 
            WHERE  `Delivery Note Order Key`=%d  and  `Delivery Note Type` in ('Replacement & Shortages', 'Replacement', 'Shortages') AND   `Delivery Note State` ='Dispatched' AND `Delivery Note Date Dispatched`>=%s ", $this->id, prepare_mysql(gmdate('Y-m-d 00:00:00'))

        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                if ($row['num'] > 0) {
                    $dispatched_today = $row['num'];
                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->fast_update(
            array(
                'Order Replacements In Warehouse without Alerts' => $in_warehouse_no_alerts,
                'Order Replacements In Warehouse with Alerts'    => $in_warehouse_with_alerts,
                'Order Replacements Packed Done'                 => $packed_done,
                'Order Replacements Approved'                    => $approved,
                'Order Replacements Dispatched Today'            => $dispatched_today,


            )
        );

        $store = get_object('Store', $this->get('Store Key'));
        $store->update_orders_in_warehouse_data();
        $account = get_object('Account', 1);
        $account->update_orders_in_warehouse_data();


        if ($old_in_warehouse_no_alerts != $in_warehouse_no_alerts or $old_in_warehouse_with_alerts != $in_warehouse_with_alerts) {
            $store->update_orders_in_warehouse_data();
            $account->update_orders_in_warehouse_data();

        }
        if ($old_packed_done != $packed_done) {
            $store->update_orders_packed_data();
            $account->update_orders_packed_data();

        }
        if ($old_approved != $approved) {
            $store->update_orders_approved_data();
            $account->update_orders_approved_data();

        }
        if ($old_dispatched_today != $dispatched_today) {
            $store->update_orders_dispatched_today();
            $account->update_orders_dispatched_today();

        }


    }

    function create_return($transactions) {
        include_once 'utils/currency_functions.php';

        include_once 'class.SupplierDelivery.php';

        $account = get_object('Account', 1);
        // $warehouse=ger_object('Warehouse',$data['Supplier Delivery Warehouse Key']);

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
            //'Supplier Delivery Warehouse Key'=>$warehouse->id,
            //'Supplier Delivery Warehouse Metadata'=>json_encode($warehouse->data),

            'editor' => $this->editor
        );


        $delivery = new SupplierDelivery('new', $delivery_data);


        if ($delivery->error) {
            $this->error = true;
            $this->msg   = $delivery->msg;

        } elseif ($delivery->new or true) {


            foreach ($transactions as $transaction) {

                switch ($transaction['type']) {
                    case 'itf':
                        $sql = sprintf('select `Part SKU`,`Inventory Transaction Amount`,`Inventory Transaction Key` from `Inventory Transaction Fact` where `Inventory Transaction Key`=%d ', $transaction['id']);
                        if ($result = $this->db->query($sql)) {
                            if ($row = $result->fetch()) {
                                // print_r($row);
                                $date = gmdate('Y-m-d H:i:s');

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

                                $sql = sprintf(
                                    "INSERT INTO `Purchase Order Transaction Fact` (`Purchase Order Transaction Return ITF Key`,`Purchase Order Transaction Part SKU`,`Currency Code`,`Purchase Order Last Updated Date`,`Supplier Delivery Transaction State`,
					`Supplier Delivery Units`,`Supplier Delivery Net Amount`,`Supplier Delivery Extra Cost Amount`,`Supplier Delivery CBM`,`Supplier Delivery Weight`,
					`User Key`,`Creation Date`,`Supplier Delivery Key`,`Supplier Delivery Transaction Placed`,`Supplier Delivery Last Updated Date`
					)
					VALUES (%d,%d,%s,%s,%s,
			          %.6f,%.2f,%.2f,%s,%s,
					 %d,%s,
					 %d,'No',%s
					 )", $row['Inventory Transaction Key'], $part->id, prepare_mysql($account->get('Account Currency Code')), prepare_mysql($date), prepare_mysql('Dispatched'),

                                    $unit_qty, $amount, $extra_amount, $cbm, $weight, $this->editor['User Key'], prepare_mysql($date), $delivery->id, prepare_mysql($date)


                                );
                                $this->db->exec($sql);


                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }

                        break;
                }

            }


            $delivery->update_totals();


        }


        return $delivery;

    }


    function get_return_public_id($supplier_delivery_id, $suffix_counter = '') {
        $sql = sprintf(
            "SELECT `Supplier Delivery Public ID` FROM `Supplier Delivery Dimension` WHERE `Supplier Delivery Parent`='Order' and `Supplier Delivery Parent Key`=%d AND `Supplier Delivery Public ID`=%s ", $this->id, prepare_mysql($supplier_delivery_id.$suffix_counter)
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
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }


    function get_field_label($field) {

        switch ($field) {
            case 'Order Customer Purchase Order ID':
                return _("customer's purchase order number");
                break;
            case 'Order Tax Number':
                return _("tax number");
                break;
            case 'Order Tax Number Valid':
                return _("tax number valid");
                break;
            case 'Order Delivery Address':
                return _("delivery address");
                break;
            case 'Order Invoice Address':
                return _("invoice address");
                break;
            case 'Order Customer Name':
                return _("customer name");
                break;
            case 'Order Registration Number':
                return _("registration number");
                break;
            default:
                return $field;

        }

    }

}



