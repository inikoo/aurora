<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 26 April 2016 at 12:32:20 GMT+8, Ubud, Bali, Indonesia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


$barcode_fields = array(
    array(
        'label'      => _('Notes'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'              => 'Barcode_Sticky_Note',
                'edit'            => ($edit ? 'textarea' : ''),
                'value'           => htmlspecialchars(
                    $object->get('Barcode Sticky Note')
                ),
                'formatted_value' => $object->get('Sticky Note'),
                'label'           => ucfirst(
                    $object->get_field_label('Barcode Sticky Note')
                ),
                'required'        => false,
                'type'            => 'value'
            ),
        )
    ),


);

$operations = array(
    'label'      => _('Operations'),
    'show_title' => true,
    'class'      => 'operations',
    'fields'     => array(
        array(
            'id'        => 'delete_barcode',
            'class'     => 'operation',
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name()
                .'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._('Delete barcode').' <i class="fa fa-trash new_button link"></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),

    )

);

$barcode_fields[] = $operations;


?>
