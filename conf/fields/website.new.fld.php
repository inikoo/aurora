<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  05 November 2019  08:26::54  +0100, Mijas Costa, Spain

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

$store = get_object('store', $options['store_key']);

if (!$user->can_supervisor('stores') or !in_array($store->id, $user->stores)) {
    $object_fields = array();
} else {


    $options_yes_no = array(
        'Yes' => _('Yes'),
        'No'  => _('No')
    );


    $object_fields = array(
        array(
            'label'      => _('Id'),
            'class'      => '',
            'show_title' => true,
            'fields'     => array(

                array(
                    'id'                => 'Website_Code',
                    'edit'              => 'string',
                    'right_code'        => 'WS-'.$store->id,
                    'value'             => $store->get('Code'),
                    'label'             => ucfirst($object->get_field_label('Code')),
                    'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                    'invalid_msg'       => get_invalid_message('string'),
                    'required'          => true,
                    'type'              => 'value'
                ),
                array(
                    'id'                => 'Website_Name',
                    'right_code'        => 'WS-'.$store->id,
                    'edit'              => 'string',
                    'value'             => '',
                    'label'             => ucfirst($object->get_field_label('Name')),
                    'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                    'invalid_msg'       => get_invalid_message('string'),
                    'required'          => true,
                    'type'              => 'value',

                ),
                array(
                    'id'                => 'Website_URL',
                    'edit'              => 'string',
                    'right_code'        => 'WS-'.$store->id,
                    'value'             => '',
                    'label'             => ucfirst($object->get_field_label('URL')),
                    'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                    'invalid_msg'       => get_invalid_message('string'),
                    'required'          => true,
                    'type'              => 'value',
                    'placeholder'       => 'www.example.com',

                ),

            ),

        ),


    );


}