<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 July 2018 at 19:01:07 GMT+8fddss Kuala Lumpur, Malaysia

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


$options_scope = array(
    'List'  => _('Customer list'),
    'Category Target' => _('Category (Targeted)'),
    'Category Wide' => _('Category (Spread out)'),
    'Product Target' => _('Product (Targeted)'),
    'Product Wide' => _('Product (Spread out)'),
);

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




