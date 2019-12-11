<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  11 December 2019  21:37::36  +0700, Bangkok Thailand

 Copyright (c) 2019, Inikoo

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
            'edit'              => ($edit ? 'string' : ''),
            'id'                => 'Email_Campaign_Name',
            'value'             => $object->get('Email Campaign Name'),
            'formatted_value'   => $object->get('Name'),
            'label'             => ucfirst($object->get_field_label('Email Campaign Name')),
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => true,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'type'              => 'value'
        )
    )
);

if ($object->get('State Index') < 50) {
    $object_fields[] = array(
        'label'      => _('Recipients'),
        'show_title' => true,


        'fields' => array(

            array(
                'edit'            => ($edit ? 'smallint_unsigned' : ''),
                'render'          => true,
                'id'              => 'Email_Campaign_Max_Number_Emails',
                'value'           => $object->get('Email Campaign Max Number Emails'),
                'formatted_value' => $object->get('Max Number Emails'),

                'label'       => ucfirst($object->get_field_label('Email Campaign Max Number Emails')),
                'invalid_msg' => get_invalid_message('smallint_unsigned'),
                'required'    => false,
                'type'        => 'value'
            ),

            array(
                'edit'   => ($edit ? 'smallint_unsigned' : ''),
                'render'          => true,

                'id'              => 'Email_Campaign_Cool_Down_Days',
                'value'           => $object->get('Email Campaign Cool Down Days'),
                'formatted_value' => $object->get('Cool Down Days'),

                'label'       => ucfirst($object->get_field_label('Email Campaign Cool Down Days')),
                'invalid_msg' => get_invalid_message('smallint_unsigned'),
                'required'    => true,
                'type'        => 'value'
            ),
        )

    );
}



