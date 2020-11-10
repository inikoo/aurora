<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 May 2016 at 10:43:19 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'utils/static_data.php';

$new = true;

$shippers_options = [];
$sql              = sprintf(
    "SELECT `Shipper Key`,`Shipper Code` from `Shipper Dimension` where `Shipper Active`='Yes' and `Shipper API Key`>0 "
);

$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
$services = [];
while ($row = $stmt->fetch()) {
    $shippers_options[$row['Shipper Key']] = $row['Shipper Code'];

    $shipper                       = get_object('Shipper', $row['Shipper Key']);
    $services[$row['Shipper Key']] = $shipper->get_services();


}


$order = get_object('Order', $object->get('Delivery Note Order Key'));


$countries = get_countries($db);

$smarty->assign('order', $order);
$smarty->assign('dn', $object);
$smarty->assign('alt_save_label', _('Generate label'));


$shipper = get_object('Shipper', $object->get('Delivery Note Shipper Key'));


$courier_fields = array(


    array(
        'render'          => true,
        'class'           => 'shipper_courier',
        'id'              => 'Shipment_Shipper',
        'edit'            => 'option',
        'options'         => $shippers_options,
        'value'           => $object->get('Delivery Note Shipper Key'),
        'formatted_value' => $shipper->get('Code'),
        'label'           => _('Courier'),
        'type'            => 'value'

    )

);


foreach ($services as $key => $value) {
    if (is_array($value) and count($value) > 0) {


        $courier_fields[] = array(
            'render'          => true,
            'set_as_valid'    => true,
            'class'           => 'shipper_service shipper_service_'.$key.' '.($object->get('Delivery Note Shipper Key') != $key ? 'hide' : ''),
            'id'              => 'Service_'.$key,
            'edit'            => 'option',
            'options'         => $value,
            'value'           => (!empty($services[$key][$object->properties('last_shipment_service_type')]) ? $object->properties('last_shipment_service_type') : '_AUTO_'),
            'formatted_value' => (!empty($services[$key][$object->properties('last_shipment_service_type')]) ? $services[$key][$object->properties('last_shipment_service_type')] : 'Automatic'),
            'label'           => _('Service'),
            'type'            => 'value'

        );
    }
}


$object_fields = array(
    array(
        'label'      => _('Courier'),
        'show_title' => false,
        'class'      => '',
        'fields'     => $courier_fields
    ),

    array(
        'label'      => _('Delivery details'),
        'show_title' => false,
        'class'      => '',
        'fields'     => array(
            array(
                'id'              => 'Delivery_Note_Telephone',
                'render'          => true,
                'edit'            => 'string',
                'value'           => htmlspecialchars($object->get('Delivery Note Telephone')),
                'formatted_value' => $order->get('Telephone'),
                'label'           => ucfirst(_('Telephone')),
                'required'        => false,
                'type'            => 'value'

            ),
            array(
                'id'              => 'Delivery_Note_Email',
                'render'          => true,
                'edit'            => 'string',
                'value'           => htmlspecialchars($object->get('Delivery Note Email')),
                'formatted_value' => $order->get('Email'),
                'label'           => ucfirst(_('Email')),
                'required'        => false,
                'type'            => 'value'

            ),

            array(
                'id'              => 'Order_Delivery_Address',
                'render'          => true,
                'edit'            => 'filled_address',
                'countries'       => $countries,
                'value'           => htmlspecialchars($order->get('Order Delivery Address')),
                'formatted_value' => $order->get('Delivery Address'),
                'label'           => ucfirst($order->get_field_label('Order Delivery Address')),
                'invalid_msg'     => get_invalid_message('address'),
                'required'        => false,
                'type'            => 'value'

            ),


        )
    ),

    array(
        'label'      => _('Note'),
        'show_title' => false,
        'class'      => '',
        'fields'     => [
            [
                'id'              => 'Note',
                'render'          => true,
                'edit'            => 'textarea',
                'value'           => htmlspecialchars(strip_tags($order->get('Order Customer Message'))),
                'formatted_value' => strip_tags($order->get('Order Customer Message')),
                'label'           => ucfirst(_('Note')),
                'required'        => false,
                'type'            => 'value'
            ]
        ]
    ),

);

