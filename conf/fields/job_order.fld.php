<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Tuesday, 23 June 2020, 6:26 pm, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/



$object_fields = array(
    array(
        'label'      => _('Purchase order'),
        'show_title' => true,
        'fields'     => array(


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
        'label'      => _('Estimated manufacture dates'),
        'show_title' => true,
        'class'=>(  $object->get('State Index')>70 ?'hide':''),

        'fields'     => array(
            array(


                'id'   => 'Purchase_Order_Estimated_Start_Production_Date',
                'edit' => ($edit ? 'date' : ''),
                'render'=>(($object->get('State Index')<=0 or  $object->get('State Index')>=40)?false:true),

                'time'            => '00:00:00',
                'value'           => $object->get('Purchase Order Estimated Start Production Date'),
                'formatted_value' => $object->get('Estimated Start Production Date'),
                'label'           => ucfirst($object->get_field_label('Purchase Order Estimated Start Production Date')),
                'invalid_msg'     => get_invalid_message('date'),
                'required'        => false,
                'type'            => 'value'


            ),

            array(


                'id'   => 'Purchase_Order_Estimated_Receiving_Date',
                'edit' => ($edit ? 'date' : ''),
                'render'=>(($object->get('State Index')<=0 or  $object->get('State Index')>55)?false:true),

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



);



