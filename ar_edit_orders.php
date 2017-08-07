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

        if ($data['parent'] == 'order') {
            add_payment_to_order($data, $editor, $smarty, $db, $account);
        } elseif ($data['parent'] == 'order') {
            add_payment_to_invoice($data, $editor, $smarty, $db, $account);
        } else {
            $response = array(
                'state' => 400,
                'msg'   => 'Unsupported parent for create new payment '.$parent->get_object_name()

            );
            echo json_encode($response);
            exit;
        }


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
            $parent->data['Order Current Dispatch State'], array(
                                                             'Ready to Pick',
                                                             'Picking & Packing',
                                                             'Packed',
                                                             'Packed Done',
                                                             'Packing'
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
            //  'dispatch_state'=>get_order_formated_dispatch_state($order->get('Order Current Dispatch State'),$order->id),
            // 'operations'=>get_orders_operations($order->data,$user)

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


function add_payment_to_order($data, $editor, $smarty, $db, $account) {


    $order         = get_object($data['parent'], $data['parent_key']);
    $order->editor = $editor;

    $payment_account         = get_object('Payment_Account', $data['payment_account_key']);
    $payment_account->editor = $editor;


    $sender_field  = 'Order Invoice Address Recipient';
    $country_field = 'Order Invoice Address Country 2 Alpha Code';


    $payment_data = array(
        'Payment Store Key'                   => $order->get('Store Key'),
        'Payment Customer Key'                => $order->get('Customer Key'),
        'Payment Transaction Amount'          => $data['amount'],
        'Payment Currency Code'               => $order->get('Currency Code'),
        'Payment Sender'                      => $order->get($sender_field),
        'Payment Sender Country 2 Alpha Code' => $order->get($country_field),
        'Payment Sender Email'                => $order->get('Email'),
        'Payment Sender Card Type'            => '',
        'Payment Created Date'                => gmdate('Y-m-d H:i:s'),

        'Payment Completed Date'     => gmdate('Y-m-d H:i:s'),
        'Payment Last Updated Date'  => gmdate('Y-m-d H:i:s'),
        'Payment Transaction Status' => 'Completed',
        'Payment Transaction ID'     => $data['reference'],
        'Payment Method'             => $data['payment_method'],
        'Payment Location'           => 'Order',
        'Payment Metadata'           => '',


    );


    $payment = $payment_account->create_payment($payment_data);

    $order->add_payment($payment);
    $order->update_totals();


    $operations = array();


    $payments_xhtml = '';

    foreach ($order->get_payments('objects','Completed') as $payment) {
        $payments_xhtml .= sprintf(
            '<div class="payment node"><span class="node_label link" onClick="change_view(\'%s\')" >%s</span><span class="node_amount" >%s</span></div>',
            '/order/'.$order->id.'/payment/'.$payment->id,
            $payment->get('Payment Account Code'),
            $payment->get('Transaction Amount')

        );
    }


    $metadata = array(
        'to_pay' => $order->get('Order To Pay Amount'),

        'class_html'    => array(
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
        'operations'    => $operations,
        'state_index'   => $order->get('State Index'),
        'to_pay'        => $order->get('Order To Pay Amount'),
        'total'         => $order->get('Order Total Amount'),
        'payments'         => $order->get('Order Payments Amount'),

        'payments_xhtml' => $payments_xhtml
    );


    $response = array(
        'state'    => 200,
        'metadata' => $metadata
    );
    echo json_encode($response);


}

function add_payment_to_invoice($data, $editor, $smarty, $db, $account) {

    //todo add_payment_to_invoice

    $parent         = get_object($data['parent'], $data['parent_key']);
    $parent->editor = $editor;

    $payment_account         = get_object('Payment_Account', $data['payment_account_key']);
    $payment_account->editor = $editor;

    if ($parent->get_object_name() == 'Order') {
        $sender_field  = 'Order Invoice Address Recipient';
        $country_field = 'Order Invoice Address Country 2 Alpha Code';
    } elseif ($parent->get_object_name() == 'Order') {
        $sender_field  = 'Order Invoice Address Recipient';
        $country_field = 'Order Invoice Address Country 2 Alpha Code';
    } else {
        $response = array(
            'state' => 400,
            'msg'   => 'Unsupported parent for create new payment '.$parent->get_object_name()

        );
        echo json_encode($response);
        exit;
    }


    $payment_data = array(
        'Payment Store Key'                   => $parent->get('Store Key'),
        'Payment Customer Key'                => $parent->get('Customer Key'),
        'Payment Transaction Amount'          => $data['amount'],
        'Payment Currency Code'               => $parent->get('Currency Code'),
        'Payment Sender'                      => $parent->get($sender_field),
        'Payment Sender Country 2 Alpha Code' => $parent->get($country_field),
        'Payment Sender Email'                => $parent->get('Email'),
        'Payment Sender Card Type'            => '',
        'Payment Created Date'                => gmdate('Y-m-d H:i:s'),

        'Payment Completed Date'     => gmdate('Y-m-d H:i:s'),
        'Payment Last Updated Date'  => gmdate('Y-m-d H:i:s'),
        'Payment Transaction Status' => 'Completed',
        'Payment Transaction ID'     => $data['reference'],
        'Payment Method'             => $data['payment_method'],
        'Payment Location'           => 'Order',
        'Payment Metadata'           => '',


    );


    $payment = $payment_account->create_payment($payment_data);

    $parent->add_payment($payment);
    $parent->update_totals();

    if ($parent->get_object_name() == 'Order') {

        $metadata = array(
            'to_pay' => $parent->get('Order To Pay Amount'),

            'class_html'  => array(
                'Order_State'                   => $this->get('State'),
                'Items_Net_Amount'              => $this->get('Items Net Amount'),
                'Shipping_Net_Amount'           => $this->get('Shipping Net Amount'),
                'Charges_Net_Amount'            => $this->get('Charges Net Amount'),
                'Total_Net_Amount'              => $this->get('Total Net Amount'),
                'Total_Tax_Amount'              => $this->get('Total Tax Amount'),
                'Total_Amount'                  => $this->get('Total Amount'),
                'Total_Amount_Account_Currency' => $this->get('Total Amount Account Currency'),
                'To_Pay_Amount'                 => $this->get('To Pay Amount'),
                'Payments_Amount'               => $this->get('Payments Amount'),


                'Order_Number_items' => $this->get('Number Items')

            ),
            'operations'  => $operations,
            'state_index' => $this->get('State Index'),

        );


        $response = array(
            'state'    => 200,
            'metadata' => $metadata
        );
        echo json_encode($response);

    } else {

    }


}

?>
