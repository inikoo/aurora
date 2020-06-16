<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 July 2016 at 00:51:46 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

$options_supplier_order_type = [
    'Parcel'         => _('Parcel'),
    'Container' => _('Container'),
];

$options_Incoterm = array();
$sql              = "SELECT `Incoterm Code`,`Incoterm Name`,`Incoterm Transport Type` FROM kbase.`Incoterm Dimension`";
$stmt             = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {
    $options_Incoterm[$row['Incoterm Code']] = '<b>'.$row['Incoterm Code'].'</b> '.$row['Incoterm Name'];
}

asort($options_Incoterm);
asort($options_supplier_order_type);


$object_fields = array(
    array(
        'label'      => _('Supplier delivery'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'                => 'Supplier_Delivery_Type',
                'edit' => ($edit ? 'option' : ''),

                'options'         => $options_supplier_order_type,
                'value'             => htmlspecialchars($object->get('Supplier Delivery Type')),
                'formatted_value'   => $object->get('Type'),
                'label'             => ucfirst($object->get_field_label('Supplier Delivery Type')),
                'required'          => true,

            ),

            array(
                'id'                => 'Supplier_Delivery_Public_ID',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => htmlspecialchars($object->get('Supplier Delivery Public ID')),
                'formatted_value'   => $object->get('Public ID'),
                'label'             => ucfirst($object->get_field_label('Supplier Delivery Public ID')),
                'required'          => true,
                'server_validation' => json_encode(
                    array(
                        'tipo'       => 'check_for_duplicates',
                        'parent'     => $object->get('Supplier Delivery Parent'),
                        'parent_key' => $object->get('Supplier Delivery Parent Key'),
                        'object'     => 'Supplier Delivery',
                        'key'        => $object->id
                    )
                ),
                'type'              => 'value'
            ),


        )
    ),
    array(
        'label'      => _('Invoice'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'              => 'Supplier_Delivery_Invoice_Public_ID',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Supplier Delivery Invoice Public ID')),
                'formatted_value' => $object->get('Invoice Public ID'),
                'label'           => ucfirst($object->get_field_label('Supplier Delivery Invoice Public ID')),
                'required'        => true,
                'type'            => 'value'
            ),

            array(


                'id'              => 'Supplier_Delivery_Invoice_Date',
                'edit'            => ($edit ? 'date' : ''),
                'time'            => '00:00:00',
                'value'           => $object->get('Supplier Delivery c Date'),
                'formatted_value' => $object->get('Invoice Date'),
                'label'           => ucfirst($object->get_field_label('Supplier Delivery Invoice Date')),
                'invalid_msg'     => get_invalid_message('date'),
                'required'        => false,
                'type'            => 'value'


            ),

        )
    ),
);


if ($object->get('Supplier Delivery Parent') != 'Order') {

    $object_fields[] = array(
        'label'      => _('Dates').' <i class="fa padding_left_10 fa-exclamation-triangle yellow" aria-hidden="true"></i> <span class="warning" style=";font-weight: normal">'._('Please be careful changing dates').'</span>',
        'show_title' => true,
        'fields'     => array(

            array(


                'id'   => 'Supplier_Delivery_Dispatched_Date',
                'edit' => ($edit ? 'date' : ''),

                'time'            => '00:00:00',
                'value'           => $object->get('Supplier Delivery Dispatched Date'),
                'formatted_value' => $object->get('Dispatched Date'),
                'label'           => ucfirst($object->get_field_label('Supplier Delivery Dispatched Date')),
                'invalid_msg'     => get_invalid_message('date'),
                'required'        => false,
                'type'            => 'value'


            ),

            array(


                'id'              => 'Supplier_Delivery_Estimated_Receiving_Date',
                'edit'            => ($edit ? 'date' : ''),
                'render'          => ($object->get('State Index') < 40 ? true : false),
                'time'            => '00:00:00',
                'value'           => $object->get('Supplier Delivery Estimated Receiving Date'),
                'formatted_value' => $object->get('Estimated Receiving Date'),
                'label'           => ucfirst($object->get_field_label('Supplier Delivery Estimated Receiving Date')),
                'invalid_msg'     => get_invalid_message('date'),
                'required'        => false,
                'type'            => 'value'


            ),


        )
    );
    $object_fields[] = array(
        'label'      => _('Delivery rules'),
        'show_title' => true,
        'fields'     => array(

            array(


                'id'              => 'Supplier_Delivery_Incoterm',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_Incoterm,
                'value'           => htmlspecialchars($object->get('Supplier Delivery Incoterm')),
                'formatted_value' => $object->get('Incoterm'),
                'label'           => ucfirst($object->get_field_label('Supplier Delivery Incoterm')),
                'required'        => false,
                'type'            => 'value'


            ),
            array(
                'id'              => 'Supplier_Delivery_Port_of_Export',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Supplier Delivery Port of Export')),
                'formatted_value' => $object->get('Port of Export'),
                'label'           => ucfirst($object->get_field_label('Supplier Delivery Port of Export')),
                'required'        => false,
                'type'            => 'value'
            ),
            array(
                'id'              => 'Supplier_Delivery_Port_of_Import',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Supplier Delivery Port of Import')),
                'formatted_value' => $object->get('Port of Import'),
                'label'           => ucfirst($object->get_field_label('Supplier Delivery Port of Import')),
                'required'        => false,
                'type'            => 'value'
            ),


        )
    );
}


