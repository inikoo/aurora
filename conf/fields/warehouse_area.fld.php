<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 November 2018 at 15:10:05 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit' => ($edit ? 'string' : ''),

                'id'                => 'Warehouse_Area_Code',
                'value'             => $object->get('Warehouse Area Code'),
                'formatted_value'   => $object->get('Code'),
                'label'             => ucfirst(
                    $object->get_field_label('Warehouse Area Code')
                ),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'type'              => 'value'
            ),
            array(
                'edit' => ($edit ? 'string' : ''),

                'id'                => 'Warehouse_Area_Name',
                'value'             => $object->get('Warehouse Area Name'),
                'formatted_value'   => $object->get('Name'),
                'label'             => ucfirst(
                    $object->get_field_label('Warehouse Area Name')
                ),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'type'              => 'value'
            ),

        )
    )


);
