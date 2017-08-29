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

    case 'refund_payment':
        $data = prepare_values(
            $_REQUEST, array(
                         'operation'   => array('type' => 'string'),
                         'key'         => array('type' => 'key'),
                         'reference'   => array('type' => 'string'),
                         'submit_type' => array('type' => 'string'),
                         'amount'      => array('type' => 'string'),


                     )
        );

        refund_payment($data, $editor, $smarty, $db, $account,$user);


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


        new_payment($data, $editor, $smarty, $db, $account,$user);


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

function create_delivery_note($data, $editor, $smarty, $db, $account) {


    $order         = get_object('order', $data['key']);
    $order->editor = $editor;


    $dn = $order->send_to_warehouse(array('Warehouse Key' => 1));


    if (!$order->error) {
        include 'utils/new_fork.php';
        $msg = new_housekeeping_fork(
            'send_to_warehouse', array(
            'type'              => 'send_to_warehouse',
            'delivery_note_key' => $dn->id
        ), $account->get('Account Code')
        );

        $response = array(
            'state'     => 200,
            'order_key' => $order->id,
            'dn_key'    => $dn->id,

        );

    } else {

        $response = array(
            'state'        => 400,
            'msg'          => $order->msg,
            'number_items' => $order->get('Order Number Items'),
            'order_key'    => $order->id
        );


    }


    echo json_encode($response);

}

function new_payment($data, $editor, $smarty, $db, $account,$user) {

    include_once 'utils/currency_functions.php';


    $order         = get_object('Order', $data['parent_key']);
    $order->editor = $editor;

    $payment_account         = get_object('Payment_Account', $data['payment_account_key']);
    $payment_account->editor = $editor;


    $payment_data = array(
        'Payment Store Key'                   => $order->get('Store Key'),
        'Payment Customer Key'                => $order->get('Customer Key'),
        'Payment Transaction Amount'          => $data['amount'],
        'Payment Currency Code'               => $order->get('Currency Code'),
        'Payment Sender'                      => $order->get('Order Invoice Address Recipient'),
        'Payment Sender Country 2 Alpha Code' => $order->get('Order Invoice Address Country 2 Alpha Code'),
        'Payment Sender Email'                => $order->get('Email'),
        'Payment Sender Card Type'            => '',
        'Payment Created Date'                => gmdate('Y-m-d H:i:s'),

        'Payment Completed Date'         => gmdate('Y-m-d H:i:s'),
        'Payment Last Updated Date'      => gmdate('Y-m-d H:i:s'),
        'Payment Transaction Status'     => 'Completed',
        'Payment Transaction ID'         => $data['reference'],
        'Payment Method'                 => $data['payment_method'],
        'Payment Location'               => 'Order',
        'Payment Metadata'               => '',
        'Payment Submit Type'            => 'Manual',
        'Payment Currency Exchange Rate' => currency_conversion($db,$order->get('Currency Code'), $account->get('Currency Code')),
        'Payment User Key'=>$user->id


    );



    $payment = $payment_account->create_payment($payment_data);

    $order->add_payment($payment);
    $order->update_totals();


    $operations = array();


    $payments_xhtml = '';

    foreach ($order->get_payments('objects', 'Completed') as $payment) {
        $payments_xhtml .= sprintf(
            '<div class="payment node"><span class="node_label link" onClick="change_view(\'%s\')" >%s</span><span class="node_amount" >%s</span></div>', '/order/'.$order->id.'/payment/'.$payment->id,
            $payment->get('Payment Account Code'), $payment->get('Transaction Amount')

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

    $parent         = get_object('payment', $data['key']);
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


function refund_payment($data, $editor, $smarty, $db, $account,$user) {

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

                                $reference=$result->transaction->id;



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


            $payment_data = array(
                'Payment Store Key'                   => $order->get('Store Key'),
                'Payment Customer Key'                => $order->get('Customer Key'),
                'Payment Transaction Amount'          => -$data['amount'],
                'Payment Currency Code'               => $order->get('Currency Code'),
                'Payment Sender'                      => $order->get('Order Invoice Address Recipient'),
                'Payment Sender Country 2 Alpha Code' => $order->get('Order Invoice Address Country 2 Alpha Code'),
                'Payment Sender Email'                => $order->get('Email'),
                'Payment Sender Card Type'            => '',
                'Payment Created Date'                => gmdate('Y-m-d H:i:s'),

                'Payment Completed Date'         => gmdate('Y-m-d H:i:s'),
                'Payment Last Updated Date'      => gmdate('Y-m-d H:i:s'),
                'Payment Transaction Status'     => 'Completed',
                'Payment Transaction ID'         => $reference,
                'Payment Method'                 => $payment->get('Payment Method'),
                'Payment Location'               => 'Order',
                'Payment Metadata'               => '',
                'Payment Submit Type'            => ($data['submit_type']=='Online'?'EPS':$data['submit_type']),
                'Payment Type'                   => 'Refund',
                'Payment Currency Exchange Rate' => currency_conversion($db,$order->get('Currency Code'), $account->get('Currency Code')),
                'Payment Related Payment Key'=>$payment->id,
                'Payment Related Payment Transaction ID'=>$payment->get('Payment Transaction ID'),
                'Payment User Key'=>$user->id



            );



            $refund = $payment_account->create_payment($payment_data);


            $payment->update(array('Payment Transaction Amount Refunded'=>$payment->get('Payment Transaction Amount Refunded')+$data['amount']));


            $order->add_payment($refund);
            $order->update_totals();

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





    $operations = array();


    $payments_xhtml = '';

    foreach ($order->get_payments('objects', 'Completed') as $payment) {
        $payments_xhtml .= sprintf(
            '<div class="payment node"><span class="node_label link" onClick="change_view(\'%s\')" >%s</span><span class="node_amount" >%s</span></div>', '/order/'.$order->id.'/payment/'.$payment->id,
            $payment->get('Payment Account Code'), $payment->get('Transaction Amount')

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


?>
