<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 February 2018 at 19:55:19 GMT+8, Kuala Lumpur, Malaysia

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
                'id'                => 'Customer_Poll_Query_Option_Name',
                'value'             => $object->get('Customer Poll Query Option Name'),
                'label'             => ucfirst($object->get_field_label('Customer Poll Query Option Name')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),

                'type' => 'value'
            ),




        )
    ),
    array(
        'label'      => _('Option show to customer'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'              => ($edit ? 'textarea' : ''),
                'id'                => 'Customer_Poll_Query_Option_Label',
                'value'             => $object->get('Customer Poll Query Option Label'),
                'label'             => ucfirst($object->get_field_label('Customer Poll Query Option Label')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,

                'type' => 'value'
            ),
          
        ),

    ),


);


if (!$new) {
    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(

            array(
                'id'        => 'delete_poll_query_option',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                    .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete option").' <i class="far fa-trash-alt new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
}

?>
