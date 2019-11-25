<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  25 November 2019  22:00::39  +0100, Mijas Costa, Spain
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';

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

    case 'set_order_for_collection':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key' => array('type' => 'key'),
                     )
        );
        set_order_for_collection($data, $editor);
        break;
    case 'use_delivery_address_form_directory':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key'                  => array('type' => 'key'),
                         'other_delivery_address_key' => array('type' => 'string'),
                         'type'                       => array('type' => 'string'),

                     )
        );
        use_delivery_address_form_directory($data, $editor, $db);
        break;

    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        break;
}

/**
 * @param $data
 * @param $editor
 * @param $db \PDO
 */
function use_delivery_address_form_directory($data, $editor, $db) {

    $order         = get_object('Order', $data['order_key']);
    $order->editor = $editor;
    $customer      = get_object('Customer', $order->get('Order Customer Key'));


    switch ($data['type']) {
        case 'invoice':

            $fields = array(
                'Address Recipient'            => $customer->get('Customer Invoice Address Recipient'),
                'Address Organization'         => $customer->get('Customer Invoice Address Organization'),
                'Address Line 1'               => $customer->get('Customer Invoice Address Line 1'),
                'Address Line 2'               => $customer->get('Customer Invoice Address Line 2'),
                'Address Sorting Code'         => $customer->get('Customer Invoice Address Sorting Code'),
                'Address Postal Code'          => $customer->get('Customer Invoice Address Postal Code'),
                'Address Dependent Locality'   => $customer->get('Customer Invoice Address Dependent Locality'),
                'Address Locality'             => $customer->get('Customer Invoice Address Locality'),
                'Address Administrative Area'  => $customer->get('Customer Invoice Address Administrative Area'),
                'Address Country 2 Alpha Code' => $customer->get('Customer Invoice Address Country 2 Alpha Code'),

            );
            $order->fast_update(array('Order For Collection' => 'No'));


            $order->update_address('Delivery', $fields);
            break;

        case 'delivery':

            $fields = array(
                'Address Recipient'            => $customer->get('Customer Delivery Address Recipient'),
                'Address Organization'         => $customer->get('Customer Delivery Address Organization'),
                'Address Line 1'               => $customer->get('Customer Delivery Address Line 1'),
                'Address Line 2'               => $customer->get('Customer Delivery Address Line 2'),
                'Address Sorting Code'         => $customer->get('Customer Delivery Address Sorting Code'),
                'Address Postal Code'          => $customer->get('Customer Delivery Address Postal Code'),
                'Address Dependent Locality'   => $customer->get('Customer Delivery Address Dependent Locality'),
                'Address Locality'             => $customer->get('Customer Delivery Address Locality'),
                'Address Administrative Area'  => $customer->get('Customer Delivery Address Administrative Area'),
                'Address Country 2 Alpha Code' => $customer->get('Customer Delivery Address Country 2 Alpha Code'),

            );

            $order->fast_update(array('Order For Collection' => 'No'));

            $order->update_address('Delivery', $fields);


            break;
        case 'other_delivery':
            $sql = "SELECT 
                    `Customer Other Delivery Address Recipient`,
                    `Customer Other Delivery Address Organization`,
                    `Customer Other Delivery Address Line 1`,
                     `Customer Other Delivery Address Line 2`,
                     `Customer Other Delivery Address Sorting Code`,
                    `Customer Other Delivery Address Postal Code`,
                    `Customer Other Delivery Address Dependent Locality`,
                     `Customer Other Delivery Address Locality`,
                            `Customer Other Delivery Address Recipient`,
                     `Customer Other Delivery Address Locality`,
                     `Customer Other Delivery Address Administrative Area`,
                     `Customer Other Delivery Address Country 2 Alpha Code`

                    FROM `Customer Other Delivery Address Dimension` WHERE  `Customer Other Delivery Address Customer Key`=? and  `Customer Other Delivery Address Key`=? ";


            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    $customer->id,
                    $data['other_delivery_address_key']
                )
            );
            if ($row = $stmt->fetch()) {

                $order->fast_update(array('Order For Collection' => 'No'));

                $fields = array(
                    'Address Recipient'            => $row['Customer Other Delivery Address Recipient'],
                    'Address Organization'         => $row['Customer Other Delivery Address Organization'],
                    'Address Line 1'               => $row['Customer Other Delivery Address Line 1'],
                    'Address Line 2'               => $row['Customer Other Delivery Address Line 2'],
                    'Address Sorting Code'         => $row['Customer Other Delivery Address Sorting Code'],
                    'Address Postal Code'          => $row['Customer Other Delivery Address Postal Code'],
                    'Address Dependent Locality'   => $row['Customer Other Delivery Address Dependent Locality'],
                    'Address Locality'             => $row['Customer Other Delivery Address Locality'],
                    'Address Administrative Area'  => $row['Customer Other Delivery Address Administrative Area'],
                    'Address Country 2 Alpha Code' => $row['Customer Other Delivery Address Country 2 Alpha Code'],

                );

                $order->update_address('Delivery', $fields);

            } else {
                $response = array(
                    'state' => 400,
                    'msg'   => 'address not found'
                );
                echo json_encode($response);
                exit;
            }

            break;


    }
    $metadata = array(

        'class_html'     => array(
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
            'To_Pay_Amount_Absolute'        => $order->get('To Pay Amount Absolute'),
            'Order_Delivery_Address'        => $order->get('Order Delivery Address Formatted'),


            'Order_Number_items' => $order->get('Number Items')

        ),
        'hide'           => array('for_collection_label'),
        'show'           => array(
            'deliver_to_label',
            'Order_Delivery_Address'
        ),
        //  'operations'    => $operations,
        'state_index'    => $order->get('State Index'),
        'to_pay'         => $order->get('Order To Pay Amount'),
        'total'          => $order->get('Order Total Amount'),
        'shipping'       => $order->get('Order Shipping Net Amount'),
        'charges'        => $order->get('Order Charges Net Amount'),
        'for_collection' => $order->get('Order For Collection'),


    );


    $response = array(
        'state'                                     => 200,
        'metadata'                                  => $metadata,
        'other_fields'                              => array(
            'Order_Delivery_Address' => array(
                'field'           => 'Order_Delivery_Address',
                'render'          => true,
                'value'           => htmlspecialchars($order->get('Order Delivery Address')),
                'formatted_value' => $order->get('Delivery Address'),


            )
        ),
        'Order_Delivery_Address_recipient'          => $order->get('Order Delivery Address Recipient'),
        'Order_Delivery_Address_organization'       => $order->get('Order Delivery Address Organization'),
        'Order_Delivery_Address_addressLine1'       => $order->get('Order Delivery Address Line 1'),
        'Order_Delivery_Address_addressLine2'       => $order->get('Order Delivery Address Line 2'),
        'Order_Delivery_Address_sortingCode'        => $order->get('Order Delivery Address Sorting Code'),
        'Order_Delivery_Address_postalCode'         => $order->get('Order Delivery Address Postal Code'),
        'Order_Delivery_Address_dependentLocality'  => $order->get('Order Delivery Address Dependent Locality'),
        'Order_Delivery_Address_locality'           => $order->get('Order Delivery Address Locality'),
        'Order_Delivery_Address_administrativeArea' => $order->get('Order Delivery Address Administrative Area'),
        'Order_Delivery_Address_country'            => $order->get('Order Delivery Address Country 2 Alpha Code'),
    );
    echo json_encode($response);
    exit;

}

/**
 * @param $data
 * @param $editor
 */
function set_order_for_collection($data, $editor) {

   $order         = get_object('Order', $data['order_key']);
    $order->editor = $editor;
    $order->update_for_collection('Yes');


    $metadata = array(

        'class_html'       => array(
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
            'To_Pay_Amount_Absolute'        => $order->get('To Pay Amount Absolute'),

            'Order_Delivery_Address' => $order->get('Order Delivery Address Formatted'),

            'Order_Number_items' => $order->get('Number Items')

        ),
        'hide'             => array(
            'deliver_to_label',
            'Order_Delivery_Address'
        ),
        'show'             => array(
            'for_collection_label'

        ),
        //  'operations'    => $operations,
        'state_index'      => $order->get('State Index'),
        'to_pay'           => $order->get('Order To Pay Amount'),
        'total'            => $order->get('Order Total Amount'),
        'shipping'         => $order->get('Order Shipping Net Amount'),
        'charges'          => $order->get('Order Charges Net Amount'),
        'for_collection'   => $order->get('Order For Collection'),
        'delivery_address' => $order->get('Order Delivery Address Formatted')


    );

    $response = array(
        'state'        => 200,
        'metadata'     => $metadata,
        'other_fields' => array(
            'Order_Delivery_Address' => array(
                'field'  => 'Order_Delivery_Address',
                'render' => false


            )
        )
    );
    echo json_encode($response);
    exit;

}