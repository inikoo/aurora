<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 August 2017 at 18:19:05 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


include_once 'utils/static_data.php';

$new=false;





$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Payment_Transaction_ID',
                'value'             => $object->get('Payment Transaction ID'),
                'formatted_value' => $object->get('Transaction ID'),

                'label'             => ucfirst($object->get_field_label('Payment Transaction ID')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'type'              => 'value'


            ),



        )
    ),
    array(
        'label'      => _('Amount'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'              => 'Payment_Transaction_Amount',
                'edit'            => ($edit ? 'amount' : ''),
                'value'           => $object->get('Payment Transaction Amount'),
                'formatted_value' => $object->get('Transaction Amount'),
                'label'           => ucfirst($object->get_field_label('Payment Transaction Amount')),
                'type'            => 'value'
            ),


        )
    ),




);


if (!$new) {
    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(


            array(
                'id'        => 'delete_payment',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name(
                    ).'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._('Cancel payment')
                    .' <i class="fa fa-trash new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
}


?>
