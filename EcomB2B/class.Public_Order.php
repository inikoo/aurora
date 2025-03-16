<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 December 2016 at 20:02:17 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/

use Aurora\Models\Utils\TaxCategory;
use Aurora\Traits\ObjectTaxNumberTrait;


include_once __DIR__.'/trait.OrderShippingOperations.php';
include_once __DIR__.'/trait.OrderChargesOperations.php';
include_once __DIR__.'/trait.OrderDiscountOperations.php';
include_once __DIR__.'/trait.OrderItems.php';
include_once __DIR__.'/trait.OrderPayments.php';
include_once __DIR__.'/trait.Order_Calculate_Totals.php';
include_once __DIR__.'/trait.OrderOperations.php';
include_once __DIR__.'/trait.OrderTax.php';
include_once __DIR__.'/trait.OrderGet.php';
include_once __DIR__.'/trait.PublicOrderAiku.php';
include_once __DIR__.'/trait.Address.php';


include_once 'class.DBW_Table.php';


class Public_Order extends DBW_Table {
    use Address,OrderShippingOperations, OrderChargesOperations, OrderDiscountOperations, OrderItems, OrderPayments, Order_Calculate_Totals, OrderOperations, OrderTax,OrderGet,PublicOrderAiku;
    use ObjectTaxNumberTrait;


    var $amount_off_allowance_data = false;
    /**
     * @var array
     */
    public $metadata;

    function __construct($arg1 = false, $arg2 = false) {

        global $db;
        $this->db       = $db;
        $this->id       = false;
        $this->exchange = 1;
        $this->metadata        = array();


        $this->deleted_otfs = array();
        $this->new_otfs     = array();

        $this->table_name = 'Order';


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


    function get_data($key, $id) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Order Dimension` WHERE `Order Key`=%d", $id
            );
            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id            = $this->data['Order Key'];
                $this->currency_code = $this->data['Order Currency'];
                $this->metadata = json_decode($this->data['Order Metadata'], true);



            }
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

            case('Order State'):
                $this->update_state($value);
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

    function update_state($value) {

        $date = gmdate('Y-m-d H:i:s');

        include_once __DIR__.'/utils/new_fork.php';
        $account = get_object('Account', 1);


        $old_value         = $this->get('Order State');
        $operations        = array();
        $deliveries_xhtml  = '';
        $number_deliveries = 0;

        if ($old_value != $value) {

            switch ($value) {


                case 'InProcess':


                    if ($this->data['Order State'] != 'InBasket') {
                        $this->error = true;
                        $this->msg   = 'Order is not in basket: :(';

                        return;

                    }
                    $this->fast_update(
                        array(
                            'Order State'                      => 'InProcess',
                            'Order Date'                       => $date,
                            'Order Submitted by Customer Date' => $date,
                        )
                    );


                    $history_data = array(
                        'History Abstract' => _('Order submitted by customer'),
                        'History Details'  => '',
                    );
                    $this->add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $this->get_object_name(), $this->id, $update_history_records_data = true);

                    $operations = array('send_to_warehouse');


                    $sql = "update `Order Transaction Fact` set `Current Dispatching State`='Submitted by Customer' where `Order Key`=?  and `Current Dispatching State` in ('In Process by Customer','In Process')  ";
                    $this->db->prepare($sql)->execute(array($this->id));


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
                    $sql = "UPDATE `Order No Product Transaction Deal Bridge` SET `Order No Product Transaction Deal Pinned`='Yes' WHERE `Order Key`=?   ";

                    $this->db->prepare($sql)->execute(array($this->id));


                    $this->fast_update(
                        array(
                            'Order Pinned Deal Components' => json_encode($deals_component_data)
                        )
                    );


                    break;

                default:
                    $this->error = true;
                    $this->msg   = 'Unknown error';

                    return;
                    break;
            }


            $this->fork_index_elastic_search();
        }

        $this->update_metadata = array(
            'class_html'        => array(
                'Order_State'                  => $this->get('State'),
                'Order_Submitted_Date'         => '&nbsp;'.$this->get('Submitted by Customer Date'),
                'Order_Send_to_Warehouse_Date' => '&nbsp;'.$this->get('Send to Warehouse Date'),


            ),
            'operations'        => $operations,
            'state_index'       => $this->get('State Index'),
            'deliveries_xhtml'  => $deliveries_xhtml,
            'number_deliveries' => $number_deliveries
        );

        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'      => 'order_state_changed',
            'order_key' => $this->id,
        ), $account->get('Account Code')
        );


    }

    function get($key) {

        if(!$this->id){
            return $this->get_ghost_values($key);
        }


        switch ($key) {

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
            case ('State'):
                switch ($this->data['Order State']) {
                    case('InBasket'):
                        $state = _('In Basket');
                        break;
                    case('InProcess'):
                        $state = _('Submitted');
                        break;
                    case('InWarehouse'):
                        $state = _('Picking order');
                        break;
                    case('PackedDone'):
                    case('Approved'):
                        $state = _('Packed');
                        break;
                    case('Dispatch Approved'):
                        $state = _('Ready to dispatch');
                        break;
                    case('Dispatched'):
                        $state = _('Dispatched');
                        break;
                    case('Cancelled'):
                        $state = '<span class="error">'._('Cancelled').'</span>';
                        break;
                    default:
                        $state = $this->data['Order State'];

                }

                return $state;


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
            case 'Basket Items Discount Amount':
                return money(-1 * $this->data['Order Items Discount Amount'], $this->data['Order Currency']);
            case 'Basket Payments Amount':
                return money(-1 * $this->data['Order Payments Amount'], $this->data['Order Currency']);
            case 'Basket To Pay Amount':
                $credit_amount=($this->data['Order Available Credit Amount']==''?0:$this->data['Order Available Credit Amount']);

                if ($this->data['Order To Pay Amount'] > $credit_amount) {


                    return money($this->data['Order To Pay Amount'] - $credit_amount, $this->data['Order Currency']);
                } else {
                    return money(0, $this->data['Order Currency']);
                }
            case 'Order Basket To Pay Amount':

                if ($this->data['Order To Pay Amount'] > $this->data['Order Available Credit Amount']) {
                    return $this->data['Order To Pay Amount'] - $this->data['Order Available Credit Amount'];
                } else {
                    return 0;

                }
            case 'To Pay Amount':

                return money($this->data['Order To Pay Amount'], $this->data['Order Currency']);



            case('Deal Amount Off'):
                return money(
                    -1 * $this->data['Order Deal Amount Off'], $this->currency_code
                );
            case 'Products':
                return number($this->data['Order Number Items']);


            case 'Total':
                return money($this->data['Order Total Amount'], $this->data['Order Currency']);


            case 'Available Credit Amount':

                if ($this->data['Order Total Amount'] > $this->data['Order Available Credit Amount']) {
                    return money(-1 * ($this->data['Order Available Credit Amount']==''?0:$this->data['Order Available Credit Amount']), $this->data['Order Currency']);
                } else {
                    return money(-1 * ($this->data['Order Total Amount']==''?0:$this->data['Order Total Amount']), $this->data['Order Currency']);
                }


            case 'Shipping Net Amount':
                if ($this->data['Order Shipping Method'] == 'TBC') {
                    return 'TBC';
                } else {
                    return money(
                        $this->exchange * $this->data['Order Shipping Net Amount'], $this->currency_code
                    );
                }


            case 'Pinned Deal Deal Components':

                if ($this->data['Order Pinned Deal Components'] == '') {
                    return array();
                } else {
                    return json_decode($this->data['Order Pinned Deal Components'], true);
                }
            case('Estimated Weight'):

                return smart_weight($this->data['Order Estimated Weight']);
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

            case 'Tax Description':


                $tax_category=new TaxCategory($this->db);
                $tax_category->loadWithKey($this->data['Order Tax Category Key']);


                switch ($tax_category->get('Tax Category Type')) {
                    case 'Outside':
                        $tax_description = _('Outside the scope of tax');
                        break;
                    case 'EU_VTC':
                        $tax_description = sprintf(_('EU with %s'), $this->get('Tax Number Formatted'));
                        break;
                    default:
                        $tax_description= '<small class="discreet">'.$tax_category->get('Tax Category Code').'</small> '.$tax_category->get('Tax Category Name');

                }



                return $tax_description;
            default:


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

                $_key = ucwords($key);
                if (array_key_exists($_key, $this->data)) {
                    return $this->data[$_key];
                }

                if (array_key_exists('Order '.$key, $this->data)) {
                    return $this->data['Order '.$key];
                }

        }

        return '';
    }

    function get_ghost_values($key){

        switch ($key){
            case 'Order Payments Amount':
            case 'Order Available Credit Amount':
            case 'Order Charges Net Amount':
            case 'Order Items Discount Amount':
            case 'Order Deal Amount Off':
            case 'Order Charges Net Amount':
                return 0;
            case 'Total':
                return 0;
            default:
                if (preg_match(
                    '/^(Balance (Total|Net|Tax)|Invoiced Total Net Adjust|Invoiced Total Tax Adjust|Invoiced Refund Net|Invoiced Refund Tax|Total|Items|Invoiced Items|Invoiced Tax|Invoiced Net|Invoiced Charges|Payments|To Pay|Invoiced Shipping|Invoiced Insurance |(Shipping |Charges |Insurance )?Net).*(Amount)$/',
                    $key
                )) {

                    return 0;
                }
        }


        return '';
    }


    function get_date($field) {
        return strftime("%e %b %Y", strtotime($this->data[$field].' +0:00'));
    }

    function get_voucher_code() {

        $vouchers_data = $this->get_vouchers('data');
        $voucher_data  = reset($vouchers_data);

        if ($voucher_data) {


            return $voucher_data['Voucher Code'];
        } else {
            return '';
        }
    }


    function get_interactive_charges_data() {

        $charges = array();

        $sql = sprintf(
            "select `Charge Key` ,`Charge Name`,`Charge Scope` ,`Charge Store Key`,`Charge Description` ,`Charge Metadata` ,
 (select `Order No Product Transaction Fact Key` from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`='Charges'  and `Transaction Type Key`=`Charge Key`  limit 1  ) as onptf_key   
 from `Charge Dimension` where `Charge Store Key`=%d and `Charge Trigger`  = 'Selected by Customer'  and `Charge Active`='Yes'  ", $this->id, $this->get('Order Store Key')
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $onptf_key = $row['onptf_key'];

                $charges[] = array(


                    'description' => $row['Charge Description'].' ('.money($row['Charge Metadata'], $this->get('Currency Code')).')',

                    'quantity_edit' => '<i onclick="web_toggle_selected_by_customer_charge(this)"  data-order_key="'.$this->id.'"  data-charge_key="'.$row['Charge Key'].'" data-onptf_key="'.$onptf_key.'"    class="'.($onptf_key > 0 ? 'fa-toggle-on' : 'fa-toggle-off')
                        .' far  " style="cursor: pointer" ></i>',


                    'net' => sprintf('<span  class="  selected_by_customer_charge">%s</span>', ($onptf_key > 0 ? money($row['Charge Metadata'], $this->get('Currency Code')) : '')),


                );

            }
        }


        return $charges;

    }


    function get_interactive_deal_component_data() {

        $deal_components_choose_by_customer = array();


        $sql = sprintf(
            "select `Deal Name`,`Order Transaction Deal Key`,`Deal Component Allowance`,DCD.`Deal Component Key` ,`Order Transaction Deal Metadata` from `Order Transaction Deal Bridge` OTDB left join 
    `Deal Component Dimension` DCD on (DCD.`Deal Component Key`=OTDB.`Deal Component Key`) left join  `Deal Dimension` DD on (DCD.`Deal Component Deal Key`=DD.`Deal Key`) 
    
    where `Order Key`=%d and `Deal Component Allowance Type`='Get Free Customer Choose'  ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                //  print_r($row);
                $allowances         = json_decode($row['Deal Component Allowance'], true);
                $selected_allowance = json_decode($row['Order Transaction Deal Metadata'], true);


                if (!empty($selected_allowance['selected'])) {
                    $selected = $selected_allowance['selected'];
                } else {
                    $selected = $allowances['default'];
                }

                //  print_r($allowances);

                $options = '<div data-selected="'.$selected.'"  data-deal_component_key="'.$row['Deal Component Key'].'"  data-order_transaction_deal_bridge_key="'.$row['Order Transaction Deal Key'].'" class="deal_component_choose_by_customer">';
                foreach ($allowances['options'] as $product_id => $option) {
                    $options .= '<span onclick="web_select_deal_component_choose_by_customer(this)" data-product_id="'.$product_id.'" class="deal_component_item deal_component_item_'.$product_id.'    margin_right_30"  style="cursor:pointer"> <span>'
                        .$option['Description'].'</span>
<i class="margin_left_10 far '.($selected == $product_id ? 'fa-dot-circle' : 'fa-circle').' "></i>
</span><br/>';
                }

                $options .= '</div>';

                $deal_components_choose_by_customer[] = array(

                    'code'        => $row['Deal Name'],
                    'description' => $options,


                );

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $deal_components_choose_by_customer;


    }






}

