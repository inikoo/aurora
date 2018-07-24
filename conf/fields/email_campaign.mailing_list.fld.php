<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 July 2018 at 19:01:07 GMT+8fddss Kuala Lumpur, Malaysia

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}
$new = true;

include 'conf/fields/customers_new_list.fld.php';


$list_fields=$object_fields;
unset($list_fields[0]);
//print_r($list_fields);







/*


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

        'show_title' => true,

        'fields'     => array(
        )



    );
}

*/


?>
