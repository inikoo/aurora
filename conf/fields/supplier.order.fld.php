<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 May 2016 at 10:43:19 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

$options_supplier_order_type = [
    'Parcel'         => _('Parcel'),
    'Container' => _('Container'),
];


$options_Incoterm = array();
$sql              = sprintf(
    'SELECT `Incoterm Code`,`Incoterm Name`,`Incoterm Transport Type` FROM kbase.`Incoterm Dimension`  '
);
foreach ($db->query($sql) as $row) {
    $options_Incoterm[$row['Incoterm Code']] = '<b>'.$row['Incoterm Code'].'</b> '.$row['Incoterm Name'];
}
asort($options_Incoterm);
asort($options_supplier_order_type);

$object_fields = array(
    array(
        'label'      => _('Purchase order'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'                => 'Purchase_Order_Type',
                'edit' => ($edit ? 'option' : ''),

                'options'         => $options_supplier_order_type,
                'value'             => htmlspecialchars($object->get('Purchase Order Type')),
                'formatted_value'   => $object->get('Type'),
                'label'             => ucfirst($object->get_field_label('Purchase Order Type')),
                'required'          => true,

            ),

            array(
                'id'                => 'Purchase_Order_Public_ID',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => htmlspecialchars($object->get('Purchase Order Public ID')),
                'formatted_value'   => $object->get('Public ID'),
                'label'             => ucfirst($object->get_field_label('Purchase Order Public ID')),
                'required'          => true,
                'server_validation' => json_encode(
                    array(
                        'tipo'       => 'check_for_duplicates',
                        'parent'     => $object->get('Purchase Order Parent'),
                        'parent_key' => $object->get('Purchase Order Parent Key'),
                        'object'     => 'PurchaseOrder',
                        'key'        => $object->id
                    )
                ),
                'type'              => 'value'
            ),

        )
    ),

    array(
        'label'      => _('Estimated process dates'),
        'show_title' => true,
        'class'=>(($object->get('State Index')<40 or  $object->get('State Index')>70) ?'hide':''),

        'fields'     => array(

            array(


                'id'   => 'Purchase_Order_Estimated_Production_Date',
                'edit' => ($edit ? 'date' : ''),
                'render'=>(($object->get('State Index')<40 or  $object->get('State Index')>70)?false:true),

                'time'            => '00:00:00',
                'value'           => $object->get('Purchase Order Estimated Production Date'),
                'formatted_value' => $object->get('Estimated Production Date'),
                'label'           => ucfirst($object->get_field_label('Purchase Order Estimated Production Date')),
                'invalid_msg'     => get_invalid_message('date'),
                'required'        => false,
                'type'            => 'value'


            ),
            array(


                'id'   => 'Purchase_Order_Estimated_Receiving_Date',
                'edit' => ($edit ? 'date' : ''),
                'render'=>(($object->get('State Index')<40 or  $object->get('State Index')>70)?false:true),

                'time'            => '00:00:00',
                'value'           => $object->get('Purchase Order Estimated Receiving Date'),
                'formatted_value' => $object->get('Estimated Receiving Date'),
                'label'           => ucfirst($object->get_field_label('Purchase Order Estimated Receiving Date')),
                'invalid_msg'     => get_invalid_message('date'),
                'required'        => false,
                'type'            => 'value'


            ),


        )
    ),
    array(
        'label'      => _('Payment terms'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'              => 'payment_terms',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('payment terms')),
                'formatted_value' => $object->get('payment terms'),
                'label'           => ucfirst($object->get_field_label('payment terms')),
                'required'        => false,
                'type'            => 'value'
            ),
        )
    ),
    array(
        'label'      => _('Delivery terms'),
        'show_title' => true,
        'fields'     => array(

            array(


                'id'              => 'Purchase_Order_Incoterm',
                'edit'            => ($edit ? 'option' : ''),
                'render'=>($object->get('Purchase Order Type')=='Container'?true:false),
                'options'         => $options_Incoterm,
                'value'           => htmlspecialchars($object->get(' v')),
                'formatted_value' => $object->get('Incoterm'),
                'label'           => ucfirst($object->get_field_label('Purchase Order Incoterm')),
                'required'        => false,
                'type'            => 'value'


            ),
            array(
                'id'              => 'Purchase_Order_Port_of_Export',
                'edit'            => ($edit ? 'string' : ''),
                'render'=>($object->get('Purchase Order Type')=='Container'?true:false),
                'value'           => htmlspecialchars($object->get('Purchase Order Port of Export')),
                'formatted_value' => $object->get('Port of Export'),
                'label'           => ucfirst($object->get_field_label('Purchase Order Port of Export')),
                'required'        => false,
                'type'            => 'value'
            ),
            array(
                'id'              => 'Purchase_Order_Port_of_Import',
                'edit'            => ($edit ? 'string' : ''),
                'render'=>($object->get('Purchase Order Type')=='Container'?true:false),
                'value'           => htmlspecialchars($object->get('Purchase Order Port of Import')),
                'formatted_value' => $object->get('Port of Import'),
                'label'           => ucfirst($object->get_field_label('Purchase Order Port of Import')),
                'required'        => false,
                'type'            => 'value'
            ),
            array(
                'id'              => 'Purchase_Order_Warehouse_Address',
                'edit'            => ($edit ? 'textarea' : ''),
                'value'           => htmlspecialchars($object->get('Purchase Order Warehouse Address')),
                'formatted_value' => $object->get('Warehouse Address'),
                'label'           => ucfirst($object->get_field_label('Purchase Order Warehouse Address')),
                'required'        => false,
                'type'            => 'value'
            ),





        )
    ),
    array(
        'label'      => _('Labels'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'              => 'Purchase_Order_Terms_and_Conditions',
                'edit'            => ($edit ? 'editor' : ''),
                'class'           => 'editor',
                'editor_data'     => array(
                    'id'      => 'Purchase_Order_Terms_and_Conditions',
                    'content' => $object->get('Purchase Order Terms and Conditions'),

                    'data' => base64_encode(
                        json_encode(
                            array(
                                'mode'     => 'edit_object',
                                'field'    => 'Purchase_Order_Terms_and_Conditions',
                                'plugins'  => array(
                                    'align',
                                    'draggable',
                                    'image',
                                    'link',
                                    'save',
                                    'entities',
                                    'emoticons',
                                    'fullscreen',
                                    'lineBreaker',
                                    'table',
                                    'codeView',
                                    'codeBeautifier'
                                ),
                                'metadata' => array(
                                    'tipo'   => 'edit_field',
                                    'object' => 'PurchaseOrder',
                                    'key'    => $object->id,
                                    'field'  => 'Purchase Order Terms and Conditions',


                                )
                            )
                        )
                    )

                ),
                'value'           => $object->get('Purchase Order Terms and Conditions'),
                'formatted_value' => $object->get('Terms and Conditions'),
                'label'           => ucfirst($object->get_field_label('Purchase Order Terms and Conditions')
                ),
                'required'        => false,
                'type'            => 'value'
            ),

        )
    ),

);



