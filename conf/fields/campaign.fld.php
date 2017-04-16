<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2016 at 14:19:03 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

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
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Deal_Campaign_Name',
                'value'             => $object->get('Deal Campaign Name'),
                'label'             => ucfirst(
                    $object->get_field_label('Deal Campaign Name')
                ),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),

                'type' => 'value'
            ),
            array(
                'edit'              => ($edit ? 'textarea' : ''),
                'id'                => 'Deal_Campaign_Description',
                'value'             => $object->get('Deal Campaign Description'),
                'label'             => ucfirst($object->get_field_label('Deal Campaign Description')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => false,

                'type' => 'value'
            ),


        )
    ),
    array(
        'label'      => _('Duration'),
        'show_title' => true,
        'fields'     => array(


            array(
                'render' => ($new ? true : true),
                'edit'   => ($edit ? 'date' : ''),
                'id'     => 'Deal_Campaign_Valid_From',

                'time'            => '00:00:00',
                'value'           => $object->get('Deal Campaign Valid From'),
                'formatted_value' => $object->get('Valid From'),
                'label'           => ucfirst($object->get_field_label('Deal Campaign Valid From')),
                'invalid_msg'     => get_invalid_message('date'),
                'type'            => 'value',
                'required'        => true,
            ),

            array(
                'render' => ($new ? true : true),
                'edit'   => ($edit ? 'date' : ''),
                'id'     => 'Deal_Campaign_Valid_To',

                'time'            => '00:00:00',
                'value'           => $object->get('Deal Campaign Valid To'),
                'formatted_value' => $object->get('Valid To'),
                'label'           => ucfirst($object->get_field_label('Deal Campaign Valid To')),
                'invalid_msg'     => get_invalid_message('date'),
                'type'            => 'value',
                'required'        => false,
            ),


        )
    ),


);





if (!$new ) {
    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(

            array(
                'id'        => 'delete_part',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name(
                    ).'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete campaign")
                    .' <i class="fa fa-trash new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
}

?>
