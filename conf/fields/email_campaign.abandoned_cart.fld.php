<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 February 2018 at 12:06:28 GMT+8 Kuala Lumpur, Malaysia

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
        )
    )
);

if($object->get('State Index')<50){
    $object_fields[] = array(
        'label'      => _('Recipients'),
        'show_title' => true,
        'fields'     => array(
            array(
                'edit'            => ($edit ? 'smallint_unsigned' : ''),
                'id'              => 'Email_Campaign_Abandoned_Cart_Days_Inactive_in_Basket',
                'value'           => $object->get('Email Campaign Abandoned Cart Days Inactive in Basket'),
                'formatted_value' => $object->get('Abandoned Cart Days Inactive in Basket'),

                'label'             => ucfirst($object->get_field_label('Email Campaign Abandoned Cart Days Inactive in Basket')),
                'invalid_msg'       => get_invalid_message('smallint_unsigned'),
                'required'          => true,
                'type'              => 'value'
            ),
        )

    );
}




?>
