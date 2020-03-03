<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2016 at 14:19:03 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


$options_type = array(
    'Options' => _('Multiple choice'),
    'Open'  => _('Open answer')

);

$options_yes_no = array(
    'Yes' => _('Yes'),
    'No'  => _('No')

);


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
                'id'              => 'Customer_Poll_Query_Type',
                'edit'            => ($edit ? 'option' : ''),
                'render'            => ($new ?true : false),
                'options'         => $options_type,
                'value'           => 'Options',
                'formatted_value' => _('Multiple choice'),
                'label'           => ucfirst($object->get_field_label('Customer Poll Query Type')),
                'type'            => 'value'
            ),

            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Customer_Poll_Query_Name',
                'value'             => $object->get('Customer Poll Query Name'),
                'label'             => ucfirst($object->get_field_label('Customer Poll Query Name')),
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
        'label'      => _('Public query'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'              => ($edit ? 'textarea' : ''),
                'id'                => 'Customer_Poll_Query_Label',
                'value'             => $object->get('Customer Poll Query Label'),
                'label'             => ucfirst($object->get_field_label('Customer Poll Query Label')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,

                'type' => 'value'
            ),
            array(
                'id'              => 'Customer_Poll_Query_In_Registration',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_yes_no,
                'value'           => ($new?'Yes':$object->get('Customer Poll Query In Registration')),
                'formatted_value' =>  ($new?_('Yes'):$object->get('In Registration')),
                'label'           => ucfirst($object->get_field_label('Customer Poll Query In Registration')),
                'type' => 'value'
            ),
            array(
                'id'              => 'Customer_Poll_Query_Registration_Required',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_yes_no,
                'value'           => ($new?'No':$object->get('Customer Poll Query Registration Required')),
                'formatted_value' =>  ($new?_('No'):$object->get('Registration Required')),
                'label'           => ucfirst($object->get_field_label('Customer Poll Query Registration Required')),
                'type' => 'value'
            ),
            array(
                'id'              => 'Customer_Poll_Query_In_Profile',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_yes_no,
                'value'           => ($new?'Yes':$object->get('Customer Poll Query In Profile')),
                'formatted_value' =>  ($new?_('Yes'):$object->get('In Profile')),
                'label'           => ucfirst($object->get_field_label('Customer Poll Query In Profile')),
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
                'id'        => 'delete_poll_query',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                    .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete poll query").' <i class="far fa-trash-alt new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
}


