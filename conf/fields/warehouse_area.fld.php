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

if ($user->can_supervisor('locations')) {
    $can_supervisor = true;
} else {
    $can_supervisor = false;

}


$options_warehouse_place = array(
    'Local'    => _('Local warehouse'),
    'External' => _('External warehouse'),
);

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
    ),
    array(
        'label'      => _('Location'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit' => ($edit ? 'option' : ''),

                'id'      => 'Warehouse_Area_Place',
                'value'   => $object->get('Warehouse Area Place'),
                'options' => $options_warehouse_place,

                'formatted_value' => $object->get('Place'),
                'label'           => ucfirst($object->get_field_label('Warehouse Area Place')),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => true,

                'type' => 'value'
            ),


        )
    )


);

if (!$new) {
    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(

            array(
                'id'        => 'delete_warehouse_area',
                'class'     => 'operation',
                'value'     => '',
                'render'    => true,
                'label'     => '<i class="fa fa-fw fa-'.($can_supervisor ? 'lock-alt' : 'lock').'  button" 
                 data-labels=\'{ "text":"'._('Please ask an authorised user to delete this area').'","title":"'._('Restricted operation').'","footer":"'._('Authorised users').': "}\'  
                onClick="'.($can_supervisor ? 'toggle_unlock_delete_object(this)' : 'not_authorised_toggle_unlock_delete_object(this,\'LS\')').'"  
                style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete area")
                    .' <i class="far fa-trash-alt new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
}