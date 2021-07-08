<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 09 Jul 2021 02:14:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

/** @var PDO $db */
/** @var \SupplierDelivery $object */

$edit=true;

$object_fields = array(
    array(
        'label'      => _('Fulfilment Delivery'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'                => 'Fulfilment_Delivery_Public_ID',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => htmlspecialchars($object->get('Fulfilment Delivery Public ID')),
                'formatted_value'   => $object->get('Public ID'),
                'label'             => ucfirst($object->get_field_label('Fulfilment Delivery Public ID')),
                'required'          => true,
                'server_validation' => json_encode(
                    array(
                        'tipo'       => 'check_for_duplicates',
                        'parent'     => 'customer',
                        'parent_key' => $object->get('Fulfilment Delivery Customer Key'),
                        'object'     => 'Fulfilment Delivery',
                        'key'        => $object->id
                    )
                ),
                'type'              => 'value'
            ),


        )
    ),
  
);




