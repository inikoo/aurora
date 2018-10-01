<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 January 2017 at 13:57:01 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/parse_natural_language.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {


    case 'create_replacement':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'          => array('type' => 'key'),
                         'transactions' => array('type' => 'json array'),


                     )
        );

        create_replacement($data, $editor, $smarty, $db, $account, $user);


        break;
    case 'create_refund':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'          => array('type' => 'key'),
                         'transactions' => array('type' => 'json array'),


                     )
        );

        create_refund($data, $editor, $smarty, $db, $account, $user);


        break;

    case 'refund_payment':
        $data = prepare_values(
            $_REQUEST, array(
                         'operation'      => array('type' => 'string'),
                         'key'            => array('type' => 'key'),
                         'reference'      => array('type' => 'string'),
                         'submit_type'    => array('type' => 'string'),
                         'amount'         => array('type' => 'string'),
                         'payback_refund' => array('type' => 'string'),


                     )
        );

        refund_payment($data, $editor, $smarty, $db, $account, $user);


        break;


    case 'new_payment':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent'         => array('type' => 'string'),
                         'parent_key'     => array('type' => 'key'),
                         'reference'      => array('type' => 'string'),
                         'payment_method' => array('type' => 'string'),

                         'amount'              => array('type' => 'string'),
                         'payment_account_key' => array('type' => 'key'),


                     )
        );


        new_payment($data, $editor, $smarty, $db, $account, $user);


        break;

    case 'pick_order_offline':
        $data = prepare_values(
            $_REQUEST, array(
                         'items' => array('type' => 'json array'),

                         'delivery_note_key' => array('type' => 'key'),

                     )
        );
        pick_order_offline($data, $editor, $smarty, $db, $account);
        break;


    case 'create_delivery_note':
        $data = prepare_values(
            $_REQUEST, array(
                         'object' => array('type' => 'string'),

                         'key' => array('type' => 'key'),

                     )
        );
        create_delivery_note($data, $editor, $smarty, $db, $account);
        break;

    case 'set_state':
        $data = prepare_values(
            $_REQUEST, array(
                         'object' => array('type' => 'string'),

                         'key'   => array('type' => 'key'),
                         'value' => array('type' => 'string'),

                     )
        );
        set_state($data, $editor, $smarty, $db);
        break;
    case 'set_picker':
        $data = prepare_values(
            $_REQUEST, array(
                         'delivery_note_key' => array('type' => 'key'),
                         'staff_key'         => array('type' => 'numeric'),

                     )
        );
        set_order_handler('Picker', $data, $editor, $smarty, $db);
        break;

    case 'set_packer':
        $data = prepare_values(
            $_REQUEST, array(
                         'delivery_note_key' => array('type' => 'key'),
                         'staff_key'         => array('type' => 'numeric'),

                     )
        );
        set_order_handler('Packer', $data, $editor, $smarty, $db);
        break;

    case 'set_shipping_as_auto':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key' => array('type' => 'key'),

                     )
        );
        set_shipping_as_auto($data, $editor);
        break;

    case 'set_shipping_value':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key' => array('type' => 'key'),
                         'amount'    => array('type' => 'string'),

                     )
        );
        set_shipping_value($data, $editor);
        break;

    case 'set_charges_as_auto':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key' => array('type' => 'key'),

                     )
        );
        set_charges_as_auto($data, $editor);
        break;

    case 'set_charges_value':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key' => array('type' => 'key'),
                         'amount'    => array('type' => 'string'),

                     )
        );
        set_charges_value($data, $editor);
        break;
    case 'edit_item_discount':
        $data = prepare_values(
            $_REQUEST, array(
                         'field'      => array('type' => 'string'),
                         'parent_key' => array('type' => 'key'),

                         'transaction_key' => array('type' => 'key'),

                         'value' => array('type' => 'string'),

                     )
        );
        edit_item_discount($account, $db, $user, $editor, $data, $smarty);
        break;

    case 'edit_item_in_order':
        $data = prepare_values(
            $_REQUEST, array(
                         'field'             => array('type' => 'string'),
                         'parent'            => array('type' => 'string'),
                         'parent_key'        => array('type' => 'key'),
                         'item_key'          => array('type' => 'key'),
                         'item_historic_key' => array(
                             'type'     => 'key',
                             'optional' => true
                         ),
                         'transaction_key'   => array(
                             'type'     => 'numeric',
                             'optional' => true
                         ),
                         'picker_key'        => array(
                             'type'     => 'numeric',
                             'optional' => true
                         ),
                         'packer_key'        => array(
                             'type'     => 'numeric',
                             'optional' => true
                         ),
                         'tab'        => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'qty'               => array('type' => 'numeric'),

                     )
        );
        edit_item_in_order($account, $db, $user, $editor, $data, $smarty);
        break;

    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function edit_item_in_order($account, $db, $user, $editor, $data, $smarty) {

    $parent         = get_object($data['parent'], $data['parent_key']);
    $parent->editor = $editor;


    //print_r($data);

    if ($data['parent'] == 'order') {

        $parent->skip_update_after_individual_transaction = false;

        if (in_array(
            $parent->data['Order State'], array(
                                            'InWarehouse',
                                            'PackedDone'
                                        )
        )) {
            $dispatching_state = 'Ready to Pick';
        } else {

            $dispatching_state = 'In Process';
        }

        $payment_state = 'Waiting Payment';

        $data['Current Dispatching State'] = $dispatching_state;
        $data['Current Payment State']     = $payment_state;
        $data['Metadata']                  = '';


    }


    $transaction_data = $parent->update_item($data);


    $discounts_data = array();

    if ($data['parent'] == 'order') {
        $sql = sprintf('SELECT `Order Transaction Amount`,OTF.`Product ID`,OTF.`Product Key`,`Order Transaction Total Discount Amount`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Amount`,`Order Currency Code`,OTF.`Order Transaction Fact Key`, `Deal Info` FROM `Order Transaction Fact` OTF left join  `Order Transaction Deal Bridge` B on (OTF.`Order Transaction Fact Key`=B.`Order Transaction Fact Key`) WHERE OTF.`Order Key`=%s ', $parent->id);

        if ($result=$db->query($sql)) {
        		foreach ($result as $row) {





                    if (in_array(
                        $parent->get('Order State'), array(
                                                               'Cancelled',
                                                               'Approved',
                                                               'Dispatched',
                                                           )
                    )) {
                        $discounts_class = '';
                        $discounts_input = '';
                    } else {
                        $discounts_class = 'button';
                        $discounts_input = sprintf(
                            '<span class="hide order_item_percentage_discount_form" data-settings=\'{ "field": "Percentage" ,"transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   ><input class="order_item_percentage_discount_input" style="width: 70px" value="%s"> <i class="fa save fa-cloud" aria-hidden="true"></i></span>',
                            $row['Order Transaction Fact Key'], $row['Product ID'], $row['Product Key'], percentage($row['Order Transaction Total Discount Amount'], $row['Order Transaction Gross Amount'])
                        );
                    }
                    $discounts = $discounts_input.'<span class="order_item_percentage_discount   '.$discounts_class.' '.($row['Order Transaction Total Discount Amount'] == 0 ? 'super_discreet' : '').'"><span style="padding-right:5px">'.percentage(
                            $row['Order Transaction Total Discount Amount'], $row['Order Transaction Gross Amount']
                        ).'</span> <span class="'.($row['Order Transaction Total Discount Amount'] == 0 ? 'hide' : '').'">'.money($row['Order Transaction Total Discount Amount'], $row['Order Currency Code']).'</span></span>';




                    if(isset($data['tab']) and $data['tab']=='order.all_products'){
                        $discounts_data[$row['Product ID']]= array(
                            'deal_info'=> $row['Deal Info'],
                            'discounts'=>$discounts,
                            'item_net'=>money($row['Order Transaction Amount'], $row['Order Currency Code'])
                        );
                    }else{
                        $discounts_data[$row['Order Transaction Fact Key']]= array(
                            'deal_info'=> $row['Deal Info'],
                            'discounts'=>$discounts,
                            'item_net'=>money($row['Order Transaction Amount'], $row['Order Currency Code'])
                        );
                    }





        		}
        }else {
        		print_r($error_info=$db->errorInfo());
        		print "$sql\n";
        		exit;
        }


    }


    if ($parent->error) {
        $response = array(
            'state' => 400,
            'msg'   => $parent->msg
        );
    } else {

        $response = array(
            'state'            => 200,
            'transaction_data' => $transaction_data,
            'metadata'         => $parent->get_update_metadata(),
            'discounts_data'   => $discounts_data
        );
    }
    echo json_encode($response);

}


function set_shipping_value($data, $editor) {


    $order         = get_object('order', $data['order_key']);
    $order->editor = $editor;

    $order->update_shipping_amount($data['amount']);


    $metadata = array(

        'class_html'  => array(
            'Order_State'                   => $order->get('State'),
            'Items_Net_Amount'              => $order->get('Items Net Amount'),
            'Shipping_Net_Amount'           => $order->get('Shipping Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Total_Net_Amount'              => $order->get('Total Net Amount'),
            'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
            'Total_Amount'                  => $order->get('Total Amount'),
            'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
            'To_Pay_Amount'                 => $order->get('To Pay Amount'),
            'Payments_Amount'               => $order->get('Payments Amount'),


            'Order_Number_items' => $order->get('Number Items')

        ),
        //  'operations'    => $operations,
        'state_index' => $order->get('State Index'),
        'to_pay'      => $order->get('Order To Pay Amount'),
        'total'       => $order->get('Order Total Amount'),
        'shipping'    => $order->get('Order Shipping Net Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),


    );


    $response = array(
        'state'    => 200,
        'metadata' => $metadata
    );
    echo json_encode($response);


}

function set_shipping_as_auto($data, $editor) {


    $order         = get_object('order', $data['order_key']);
    $order->editor = $editor;

    $order->use_calculated_shipping();


    $metadata = array(

        'class_html'  => array(
            'Order_State'                   => $order->get('State'),
            'Items_Net_Amount'              => $order->get('Items Net Amount'),
            'Shipping_Net_Amount'           => $order->get('Shipping Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Total_Net_Amount'              => $order->get('Total Net Amount'),
            'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
            'Total_Amount'                  => $order->get('Total Amount'),
            'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
            'To_Pay_Amount'                 => $order->get('To Pay Amount'),
            'Payments_Amount'               => $order->get('Payments Amount'),


            'Order_Number_items' => $order->get('Number Items')

        ),
        //  'operations'    => $operations,
        'state_index' => $order->get('State Index'),
        'to_pay'      => $order->get('Order To Pay Amount'),
        'total'       => $order->get('Order Total Amount'),
        'shipping'    => $order->get('Order Shipping Net Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),


    );


    $response = array(
        'state'    => 200,
        'metadata' => $metadata
    );
    echo json_encode($response);


}


function set_charges_value($data, $editor) {


    $order         = get_object('order', $data['order_key']);
    $order->editor = $editor;


    $order->update_charges_amount($data['amount']);


    $metadata = array(

        'class_html'  => array(
            'Order_State'                   => $order->get('State'),
            'Items_Net_Amount'              => $order->get('Items Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Total_Net_Amount'              => $order->get('Total Net Amount'),
            'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
            'Total_Amount'                  => $order->get('Total Amount'),
            'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
            'To_Pay_Amount'                 => $order->get('To Pay Amount'),
            'Payments_Amount'               => $order->get('Payments Amount'),


            'Order_Number_items' => $order->get('Number Items')

        ),
        //  'operations'    => $operations,
        'state_index' => $order->get('State Index'),
        'to_pay'      => $order->get('Order To Pay Amount'),
        'total'       => $order->get('Order Total Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),


    );


    $response = array(
        'state'    => 200,
        'metadata' => $metadata
    );
    echo json_encode($response);


}

function set_charges_as_auto($data, $editor) {


    $order         = get_object('order', $data['order_key']);
    $order->editor = $editor;

    $order->use_calculated_items_charges();


    $metadata = array(

        'class_html'  => array(
            'Order_State'                   => $order->get('State'),
            'Items_Net_Amount'              => $order->get('Items Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Total_Net_Amount'              => $order->get('Total Net Amount'),
            'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
            'Total_Amount'                  => $order->get('Total Amount'),
            'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
            'To_Pay_Amount'                 => $order->get('To Pay Amount'),
            'Payments_Amount'               => $order->get('Payments Amount'),


            'Order_Number_items' => $order->get('Number Items')

        ),
        //  'operations'    => $operations,
        'state_index' => $order->get('State Index'),
        'to_pay'      => $order->get('Order To Pay Amount'),
        'total'       => $order->get('Order Total Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),


    );


    $response = array(
        'state'    => 200,
        'metadata' => $metadata
    );
    echo json_encode($response);


}


function pick_order_offline($data, $editor, $smarty, $db, $account) {


    $dn         = get_object('delivery_note', $data['delivery_note_key']);
    $dn->editor = $editor;

    $dn->consolidate($data['items']);


    $response = array(
        'state'           => 200,
        'update_metadata' => $dn->get_update_metadata(),
        'state_index'     => $dn->get('State Index')
    );

    echo json_encode($response);


}

function set_order_handler($type, $data, $editor, $smarty, $db) {


    $dn         = get_object('delivery_note', $data['delivery_note_key']);
    $dn->editor = $editor;

    $staff = get_object('staff', $data['staff_key']);

    if ($staff->id) {
        $dn->update(
            array(
                'Delivery Note Assigned '.$type.' Key'   => $staff->id,
                'Delivery Note Assigned '.$type.' Alias' => $staff->get('Alias')
            )
        );
        $response = array(
            'state'       => 200,
            'staff_alias' => $staff->get('Alias'),
            'staff_key'   => $staff->id
        );
    } else {
        $response = array(
            'state' => 400,
            'msg'   => 'Staff not found'
        );
    }


    echo json_encode($response);

}

function set_state($data, $editor, $smarty, $db) {


    $object         = get_object($data['object'], $data['key']);
    $object->editor = $editor;


    $object->set_state($data['value']);


    $response = array(
        'state'       => 200,
        'metadata'    => $object->get_update_metadata(),
        'state_index' => $object->get('State Index')
    );

    echo json_encode($response);

}

function new_payment($data, $editor, $smarty, $db, $account, $user) {

    include_once 'utils/currency_functions.php';


    $payback_refund = false;

    if ($data['parent'] == 'invoice') {

        $invoice = get_object($data['parent'], $data['parent_key']);

        if ($invoice->get('Invoice Type') == 'Refund') {

            $payback_refund = true;
            $data['amount'] = -1 * $data['amount'];


        }

        $order = get_object('Order', $invoice->get('Invoice Order Key'));


    } else {
        $order = get_object('Order', $data['parent_key']);

    }


    $order->editor = $editor;

    $payment_account         = get_object('Payment_Account', $data['payment_account_key']);
    $payment_account->editor = $editor;

    $date     = gmdate('Y-m-d H:i:s');
    $exchange = currency_conversion($db, $order->get('Currency Code'), $account->get('Currency Code'));

    $payment_data = array(
        'Payment Store Key'                   => $order->get('Store Key'),
        'Payment Customer Key'                => $order->get('Customer Key'),
        'Payment Transaction Amount'          => $data['amount'],
        'Payment Currency Code'               => $order->get('Currency Code'),
        'Payment Sender'                      => $order->get('Order Invoice Address Recipient'),
        'Payment Sender Country 2 Alpha Code' => $order->get('Order Invoice Address Country 2 Alpha Code'),
        'Payment Sender Email'                => $order->get('Email'),
        'Payment Sender Card Type'            => '',
        'Payment Created Date'                => $date,

        'Payment Completed Date'         => $date,
        'Payment Last Updated Date'      => $date,
        'Payment Transaction Status'     => 'Completed',
        'Payment Transaction ID'         => $data['reference'],
        'Payment Method'                 => $data['payment_method'],
        'Payment Location'               => 'Order',
        'Payment Metadata'               => '',
        'Payment Submit Type'            => 'Manual',
        'Payment Currency Exchange Rate' => $exchange,
        'Payment User Key'               => $user->id,
        'Payment Type'                   => ($payback_refund ? 'Refund' : 'Payment')


    );

    $customer = get_object('Customer', $order->get('Customer Key'));

    if ($payment_account->get('Payment Account Block') == 'Accounts' and !$payback_refund) {


        if ($customer->get('Customer Account Balance') < $data['amount']) {
            $response = array(
                'state' => 400,
                'msg'   => _('Payment amount exceeds customer account balance')
            );
            echo json_encode($response);
            exit;
        }

    }


    $payment = $payment_account->create_payment($payment_data);

    if ($payment_account->get('Payment Account Block') == 'Accounts') {

        $sql = sprintf(
            'INSERT INTO `Credit Transaction Fact` 
                    (`Credit Transaction Date`,`Credit Transaction Amount`,`Credit Transaction Currency Code`,`Credit Transaction Currency Exchange Rate`,`Credit Transaction Customer Key`,`Credit Transaction Payment Key`) 
                    VALUES (%s,%.2f,%s,%f,%d,%d) ', prepare_mysql($date), -$data['amount'], prepare_mysql($order->get('Currency Code')), $exchange, $order->get('Customer Key'), $payment->id


        );

        $db->exec($sql);

        $reference = $db->lastInsertId();

        $payment->fast_update(array('Payment Transaction ID' => sprintf('%05d', $reference)));


        $customer->update_account_balance();


    }


    $order->add_payment($payment);
    $order->update_totals();

    if (!$payback_refund) {

        $invoice = get_object('invoice', $order->get('Order Invoice Key'));
    }

    if($invoice->id){
        $invoice->add_payment($payment);
    }





    $operations = array();


    $payments_xhtml = '';

    foreach ($order->get_payments('objects', 'Completed') as $payment) {


        if ($payment->payment_account->get('Payment Account Block') == 'Accounts' or $payment->get('Payment Type') == 'Credit') {
            $_code = _('Credit');
        } else {
            $_code = $payment->get('Payment Account Code');

        }

        $payments_xhtml .= sprintf(
            '<div class="payment node"><span class="node_label link" onClick="change_view(\'%s\')" >%s</span><span class="node_amount" >%s</span></div>', '/order/'.$order->id.'/payment/'.$payment->id, $_code, $payment->get('Transaction Amount')

        );
    }


    $metadata = array(

        'class_html'  => array(
            'Order_State'                   => $order->get('State'),
            'Items_Net_Amount'              => $order->get('Items Net Amount'),
            'Shipping_Net_Amount'           => $order->get('Shipping Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Total_Net_Amount'              => $order->get('Total Net Amount'),
            'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
            'Total_Amount'                  => $order->get('Total Amount'),
            'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
            'To_Pay_Amount'                 => $order->get('To Pay Amount'),
            'Payments_Amount'               => $order->get('Payments Amount'),


            'Order_Number_items' => $order->get('Number Items')

        ),
        'operations'  => $operations,
        'state_index' => $order->get('State Index'),
        'to_pay'      => $order->get('Order To Pay Amount'),
        'total'       => $order->get('Order Total Amount'),
        'shipping'    => $order->get('Order Shipping Net Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),
        'payments'    => $order->get('Order Payments Amount'),

        'payments_xhtml' => $payments_xhtml
    );


    $response = array(
        'state'    => 200,
        'metadata' => $metadata
    );
    echo json_encode($response);


}


function edit_item_discount($account, $db, $user, $editor, $data, $smarty) {

    $parent         = get_object('Order', $data['parent_key']);
    $parent->editor = $editor;


    if ($data['field'] == 'Percentage') {


        $percentage       = $data['value'];
        $transaction_data = $parent->update_transaction_discount_percentage($data['transaction_key'], $percentage);
    } else {
        $amount           = $data['value'];
        $transaction_data = $parent->update_transaction_discount_amount($data['transaction_key'], $amount);
    }

    if ($parent->error) {
        $response = array(
            'state' => 400,
            'msg'   => $parent->msg
        );
    } else {

        $response = array(
            'state'            => 200,
            'transaction_data' => $transaction_data,
            'metadata'         => $parent->get_update_metadata()
        );
    }
    echo json_encode($response);

}


function refund_payment($data, $editor, $smarty, $db, $account, $user) {

    include_once 'utils/currency_functions.php';


    $payment         = get_object('payment', $data['key']);
    $payment->editor = $editor;

    $payment_account         = get_object('Payment_Account', $payment->get('Payment Account Key'));
    $payment_account->editor = $editor;

    $order = get_object('Order', $payment->get('Payment Order Key'));


    switch ($data['operation']) {
        case 'Refund':

            switch ($data['submit_type']) {
                case 'Manual':


                    $reference = $data['reference'];

                    break;
                case 'Online':

                    switch ($payment_account->get('Payment Account Block')) {
                        case 'BTree':


                            require_once 'external_libs/braintree-php-3.2.0/lib/Braintree.php';

                            Braintree_Configuration::environment('production');
                            Braintree_Configuration::merchantId($payment_account->get('Payment Account ID'));
                            Braintree_Configuration::publicKey($payment_account->get('Payment Account Login'));
                            Braintree_Configuration::privateKey($payment_account->get('Payment Account Password'));


                            $result = Braintree_Transaction::refund($payment->data['Payment Transaction ID'], $data['amount']);


                            if ($result->success) {

                                $reference = $result->transaction->id;


                            } else {

                                if (isset($result->transaction->processorSettlementResponseText)) {
                                    $msg = $result->transaction->processorSettlementResponseText.' ('.$result->transaction->processorSettlementResponseCode.')';

                                } else {
                                    $msg = $result->message;

                                }


                                $response = array(
                                    'state' => 400,
                                    'msg'   => $msg
                                );
                                echo json_encode($response);
                                exit;


                            }


                            break;
                        default:
                            $response = array(
                                'state' => 400,
                                'msg'   => 'Payment account cant make online refunds'
                            );
                            echo json_encode($response);
                            exit;


                            break;
                    }


                    break;
                default:
                    $response = array(
                        'state' => 400,
                        'msg'   => 'unknown refund method '.$data['submit_type']
                    );
                    echo json_encode($response);
                    exit;

            }
            $store=get_object('Store',$order->get('Store Key'));


            $payment_data = array(
                'Payment Store Key'                   => $order->get('Store Key'),
                'Payment Website Key'                   => $store->get('Store Website Key'),

                'Payment Customer Key'                => $order->get('Customer Key'),
                'Payment Transaction Amount'          => -$data['amount'],
                'Payment Currency Code'               => $order->get('Currency Code'),
                'Payment Sender'                      => $order->get('Order Invoice Address Recipient'),
                'Payment Sender Country 2 Alpha Code' => $order->get('Order Invoice Address Country 2 Alpha Code'),
                'Payment Sender Email'                => $order->get('Email'),
                'Payment Sender Card Type'            => '',
                'Payment Created Date'                => gmdate('Y-m-d H:i:s'),

                'Payment Completed Date'                 => gmdate('Y-m-d H:i:s'),
                'Payment Last Updated Date'              => gmdate('Y-m-d H:i:s'),
                'Payment Transaction Status'             => 'Completed',
                'Payment Transaction ID'                 => $reference,
                'Payment Method'                         => $payment->get('Payment Method'),
                'Payment Location'                       => 'Order',
                'Payment Metadata'                       => '',
                'Payment Submit Type'                    => ($data['submit_type'] == 'Online' ? 'EPS' : 'Manual'),
                'Payment Type'                           => 'Refund',
                'Payment Currency Exchange Rate'         => currency_conversion($db, $order->get('Currency Code'), $account->get('Currency Code')),
                'Payment Related Payment Key'            => $payment->id,
                'Payment Related Payment Transaction ID' => $payment->get('Payment Transaction ID'),
                'Payment User Key'                       => $user->id


            );


            $refund = $payment_account->create_payment($payment_data);


            $payment->fast_update(array('Payment Transaction Amount Refunded' => $payment->get('Payment Transaction Amount Refunded') + $data['amount']));


            break;

        case 'Credit':

            $date     = gmdate('Y-m-d H:i:s');
            $customer = get_object('Customer', $order->get('Customer Key'));

            $exchange = currency_conversion($db, $order->get('Currency Code'), $account->get('Currency Code'));

            $store=get_object('Store',$order->get('Store Key'));

            $reference    = '';
            $payment_data = array(
                'Payment Store Key'                   => $order->get('Store Key'),
                'Payment Website Key'                   => $store->get('Store Website Key'),
                'Payment Customer Key'                => $order->get('Customer Key'),
                'Payment Transaction Amount'          => -$data['amount'],
                'Payment Currency Code'               => $order->get('Currency Code'),
                'Payment Sender'                      => $order->get('Order Invoice Address Recipient'),
                'Payment Sender Country 2 Alpha Code' => $order->get('Order Invoice Address Country 2 Alpha Code'),
                'Payment Sender Email'                => $order->get('Email'),
                'Payment Sender Card Type'            => '',
                'Payment Created Date'                => $date,

                'Payment Completed Date'                 => $date,
                'Payment Last Updated Date'              => $date,
                'Payment Transaction Status'             => 'Completed',
                'Payment Transaction ID'                 => $reference,
                'Payment Method'                         => $payment->get('Payment Method'),
                'Payment Location'                       => 'Order',
                'Payment Metadata'                       => '',
                'Payment Submit Type'                    => ($data['submit_type'] == 'Online' ? 'EPS' : 'Manual'),
                'Payment Type'                           => 'Credit',
                'Payment Currency Exchange Rate'         => $exchange,
                'Payment Related Payment Key'            => $payment->id,
                'Payment Related Payment Transaction ID' => $payment->get('Payment Transaction ID'),
                'Payment User Key'                       => $user->id


            );


            $refund = $payment_account->create_payment($payment_data);

            $sql = sprintf(
                'INSERT INTO `Credit Transaction Fact` 
                    (`Credit Transaction Date`,`Credit Transaction Amount`,`Credit Transaction Currency Code`,`Credit Transaction Currency Exchange Rate`,`Credit Transaction Customer Key`,`Credit Transaction Payment Key`) 
                    VALUES (%s,%.2f,%s,%f,%d,%d) ', prepare_mysql($date), $data['amount'], prepare_mysql($order->get('Currency Code')), $exchange, $order->get('Customer Key'), $refund->id


            );

            $db->exec($sql);

            $reference = $db->lastInsertId();

            $refund->fast_update(array('Payment Transaction ID' => sprintf('%05d', $reference)));


            $customer->update_account_balance();
            $payment->fast_update(array('Payment Transaction Amount Credited' => $payment->get('Payment Transaction Amount Credited') + $data['amount']));


            break;
        default:

            $response = array(
                'state' => 400,
                'msg'   => 'unknown refund operation '.$data['operation']
            );
            echo json_encode($response);
            exit;
            break;
    }


    $order->add_payment($refund);
    $order->update_totals();


    if ($data['payback_refund']) {
        $invoice = get_object('invoice', $data['payback_refund']);
    } else {
        $invoice = get_object('invoice', $order->get('Invoice Order Key'));

    }

    if($invoice->id){
        $invoice->add_payment($refund);
    }



    $operations = array();


    $payments_xhtml = '';

    foreach ($order->get_payments('objects', 'Completed') as $payment) {
        $payments_xhtml .= sprintf(
            '<div class="payment node"><span class="node_label link" onClick="change_view(\'%s\')" >%s</span><span class="node_amount" >%s</span></div>', '/order/'.$order->id.'/payment/'.$payment->id, $payment->get('Payment Account Code'),
            $payment->get('Transaction Amount')

        );
    }


    $metadata = array(

        'class_html'  => array(
            'Order_State'                   => $order->get('State'),
            'Items_Net_Amount'              => $order->get('Items Net Amount'),
            'Shipping_Net_Amount'           => $order->get('Shipping Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Total_Net_Amount'              => $order->get('Total Net Amount'),
            'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
            'Total_Amount'                  => $order->get('Total Amount'),
            'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
            'To_Pay_Amount'                 => $order->get('To Pay Amount'),
            'Payments_Amount'               => $order->get('Payments Amount'),


            'Order_Number_items' => $order->get('Number Items')

        ),
        'operations'  => $operations,
        'state_index' => $order->get('State Index'),
        'to_pay'      => $order->get('Order To Pay Amount'),
        'total'       => $order->get('Order Total Amount'),
        'shipping'    => $order->get('Order Shipping Net Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),
        'payments'    => $order->get('Order Payments Amount'),

        'payments_xhtml' => $payments_xhtml
    );


    $response = array(
        'state'    => 200,
        'metadata' => $metadata
    );
    echo json_encode($response);


}


function create_refund($data, $editor, $smarty, $db) {


    $object         = get_object('order', $data['key']);
    $object->editor = $editor;


    $refund = $object->create_refund($data['transactions']);


    if ($refund->id) {
        $response = array(
            'state'      => 200,
            'refund_key' => $refund->id,
            'store_key'  => $refund->get('Store Key')

        );
    } else {
        $response = array(
            'state' => 400,
            'msg'   => $object->msg
        );
    }


    echo json_encode($response);

}


function create_replacement($data, $editor, $smarty, $db) {


    $object         = get_object('order', $data['key']);
    $object->editor = $editor;


    $refund = $object->create_replacement($data['transactions']);


    if ($refund->id) {
        $response = array(
            'state'           => 200,
            'replacement_key' => $refund->id,
            'store_key'       => $refund->get('Store Key')

        );
    } else {
        $response = array(
            'state' => 400,
            'msg'   => $object->msg
        );
    }


    echo json_encode($response);

}


?>
