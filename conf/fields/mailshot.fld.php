<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 October 2017 at 15:03:56 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$object_fields = array();

$object_fields[] = array(
    'label'      => _('Id'),
    'show_title' => true,
    'fields'     => array(


        array(
            'edit'            => ($edit ? 'string' : ''),
            'id'              => 'Email_Campaign_Name',
            'value'           => $object->get('Email Campaign Name'),
            'formatted_value' => $object->get('Name'),

            'label'             => ucfirst($object->get_field_label('Email Campaign Name')),
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => true,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'type'              => 'value'
        ),


    ),


);


$object_fields[] = array(
    'label'      => _('Second Wave'),
    'show_title' => true,
    'fields'     => array(


        array(
            'edit'            => ($edit ? 'string' : ''),
            'id'              => 'Email_Campaign_Second_Wave_Subject',
            'value'           => $object->get('Email Campaign Second Wave Subject'),
            'formatted_value' => $object->get('Second Wave Subject'),

            'label'       => _('Subject'),
            'invalid_msg' => get_invalid_message('string'),
            'required'    => false,
            'type'        => 'value'
        ),


    ),


);


