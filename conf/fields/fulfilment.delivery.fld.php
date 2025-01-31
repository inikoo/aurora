<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 09 Jul 2021 02:14:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


function get_fulfilment_delivery_fields(Fulfilment_Delivery $fulfilment_delivery, User $user): array
{
    $edit = $user->can_edit('fulfilment');

    return [];

    return array(
        array(
            'label'      => _('Fulfilment Delivery'),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'                => 'Fulfilment_Delivery_Public_ID',
                    'edit'              => ($edit ? 'string' : ''),
                    'value'             => htmlspecialchars($fulfilment_delivery->get('Fulfilment Delivery Public ID')),
                    'formatted_value'   => $fulfilment_delivery->get('Public ID'),
                    'label'             => ucfirst($fulfilment_delivery->get_field_label('Fulfilment Delivery Public ID')),
                    'required'          => true,
                    'server_validation' => json_encode(
                        array(
                            'tipo'       => 'check_for_duplicates',
                            'parent'     => 'customer',
                            'parent_key' => $fulfilment_delivery->get('Fulfilment Delivery Customer Key'),
                            'object'     => 'Fulfilment Delivery',
                            'key'        => $fulfilment_delivery->id
                        )
                    ),
                    'type'              => 'value'
                ),


            )
        ),

        array(
            'label'      => _('Estimated fields'),
            'show_title' => true,
            'class'      => (($fulfilment_delivery->get('State Index') == 10) ? '' : 'hide'),

            'fields' => array(


                array(


                    'id'     => 'Fulfilment_Delivery_Estimated_Receiving_Date',
                    'edit'   => ($edit ? 'date' : ''),
                    'render' => $fulfilment_delivery->get('State Index') == 10,

                    'time'            => '00:00:00',
                    'value'           => $fulfilment_delivery->get('Fulfilment Delivery Estimated Receiving Date'),
                    'formatted_value' => $fulfilment_delivery->get('Estimated Receiving Date'),
                    'label'           => ucfirst($fulfilment_delivery->get_field_label('Fulfilment Delivery Estimated Receiving Date')),
                    'invalid_msg'     => get_invalid_message('date'),
                    'required'        => true,


                ),
                array(


                    'id'     => 'Fulfilment_Delivery_Received_Date',
                    'edit'   => ($edit ? 'date' : ''),
                    'render' => $fulfilment_delivery->get('State Index') >=40 and  $fulfilment_delivery->get('State Index') <=60,

                    'time'            => '00:00:00',
                    'value'           => $fulfilment_delivery->get('Fulfilment Delivery Received Date'),
                    'formatted_value' => $fulfilment_delivery->get('Received Date'),
                    'label'           => ucfirst($fulfilment_delivery->get_field_label('Fulfilment Delivery Received Date')),
                    'invalid_msg'     => get_invalid_message('date'),
                    'required'        => true,


                ),
                array(
                    'id'     => 'Fulfilment_Delivery_Estimated_Pallets',
                    'edit'   => ($edit ? 'smallint_unsigned' : ''),
                    'render' => $fulfilment_delivery->get('State Index') == 10,
                    'value'           => $fulfilment_delivery->get('Fulfilment Delivery Estimated Pallets'),
                    'formatted_value' => $fulfilment_delivery->get('Estimated Pallets'),
                    'label'           => ucfirst($fulfilment_delivery->get_field_label('Fulfilment Delivery Estimated Pallets')),
                    'invalid_msg'     => get_invalid_message('smallint_unsigned'),
                    'required'        => true,
                ),
                array(
                    'id'     => 'Fulfilment_Delivery_Estimated_Boxes',
                    'edit'   => ($edit ? 'smallint_unsigned' : ''),
                    'render' => $fulfilment_delivery->get('State Index') == 10,
                    'value'           => $fulfilment_delivery->get('Fulfilment Delivery Estimated Boxes'),
                    'formatted_value' => $fulfilment_delivery->get('Estimated Boxes'),
                    'label'           => ucfirst($fulfilment_delivery->get_field_label('Fulfilment Delivery Estimated Boxes')),
                    'invalid_msg'     => get_invalid_message('smallint_unsigned'),
                    'required'        => true,
                ),


            )
        ),

    );
    
}

