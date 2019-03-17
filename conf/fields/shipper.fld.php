<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2018 at 15:59:35 GMT+8,  Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

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
            'id'              => 'Shipper_Code',
            'value'           => $object->get('Shipper Code'),
            'formatted_value' => $object->get('Code'),

            'label'             => ucfirst($object->get_field_label('Shipper Code')),
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => true,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'type'              => 'value'
        ),
        array(
            'edit'            => ($edit ? 'string' : ''),
            'id'              => 'Shipper_Name',
            'value'           => $object->get('Shipper Name'),
            'formatted_value' => $object->get('Name'),

            'label'       => ucfirst($object->get_field_label('Shipper Name')),
            'invalid_msg' => get_invalid_message('string'),
            'required'    => true,
            'type'        => 'value'
        ),
    ),


);

$object_fields[] = array(
    'label'      => _('Contact'),
    'show_title' => true,
    'fields'     => array(


        array(
            'id'              => 'Shipper_Telephone',
            'edit'            => ($edit ? 'telephone' : ''),
            'value'           => $object->get('Shipper Telephone'),
            'formatted_value' => $object->get('Telephone'),
            'label'           => ucfirst($object->get_field_label('Shipper Telephone')),
            'invalid_msg'     => get_invalid_message('telephone'),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'edit'            => ($edit ? 'string' : ''),
            'id'              => 'Shipper_Website',
            'value'           => $object->get('Shipper Website'),
            'formatted_value' => $object->get('Website'),

            'label'       => ucfirst($object->get_field_label('Shipper Website')),
            'invalid_msg' => get_invalid_message('string'),
            'required'    => true,
            'type'        => 'value'
        ),
    ),


);



$object_fields[] = array(
    'label'      => _('Tracking'),
    'show_title' => true,
    'fields'     => array(


        array(
            'edit'            => ($edit ? 'string' : ''),
            'id'              => 'Shipper_Tracking_URL',
            'value'           => $object->get('Shipper Tracking URL'),
            'formatted_value' => $object->get('Tracking URL'),

            'label'       => ucfirst($object->get_field_label('Shipper Tracking URL')),
            'invalid_msg' => get_invalid_message('www'),
            'required'    => false,
            'placeholder'=>'http://',
            'type'        => 'value'
        ),

    )

);


$operations = array(
    'label'      => _('Operations'),
    'show_title' => true,
    'class'      => 'operations',
    'fields'     => array(

        array(
            'id'        => 'suspend_shipper',
            'class'     => 'operation',
            'render'=>($object->get('Shipper Status')=='Suspended'?false:true),
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                .'"}\' onClick="suspend_object(this)" class="delete_object disabled">'._("Suspend shipper").' <i class="fa fa-stop error new_button link"></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),

        array(
            'id'        => 'activate_shipper',
            'class'     => 'operation',
            'render'=>($object->get('Shipper Status')=='Active'?false:true),

            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock hide button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                .'"}\' onClick="activate_object(this)" class="button">'._("Activate shipper").' <i class="fa fa-play success new_button"></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),


    )

);

$object_fields[] = $operations;

?>
