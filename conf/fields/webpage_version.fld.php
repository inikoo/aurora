<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 November 2016 at 16:11:20 GMT+8, Cyberjaya, Malysia

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
                'id'                => 'Webpage_Version_Code',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => $object->get('Webpage Version Code'),
                'label'             => ucfirst(
                    $object->get_field_label('Code')
                ),
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'invalid_msg'       => get_invalid_message('string'),
            ),
          


        )
    ),


);

if (!$new) {
    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(

            array(
                'id'        => 'delete_website',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name(
                    ).'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete webpage version").' <i class="fa fa-trash new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
}


?>
