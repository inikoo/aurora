<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 March 2018 at 13:53:49 GMT+8, 
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

$object_fields[] = array(
    'label'      => _('Id'),
    'show_title' => true,
    'fields'     => array(


        array(
            'edit'              => ($edit ? 'string' : ''),
            'id'                => 'List_Name',
            'value'             => $object->get('List Name'),
            'label'             => ucfirst($object->get_field_label('List Name')),
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => true,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'type'              => 'value'
        ),


    ),


);

$operations = array(
    'label'      => _('Operations'),
    'show_title' => true,
    'class'      => 'operations',
    'fields'     => array(
        array(
            'id'        => 'delete_list',
            'class'     => 'operation',
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name()
                .'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._('Delete list').' <i class="far fa-trash-alt new_button link"></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),

    )

);

$object_fields[] = $operations;


?>
