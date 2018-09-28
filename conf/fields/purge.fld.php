<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 627 September 2018 at 20:40:29 GMT+8 Kuala Lumpur, Malaysia

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}




$object_fields = array();



if($object->get('State Index')<20){
    $object_fields[] = array(
        'label'      => _('Days ilde'),
        'show_title' => true,
        'fields'     => array(
            array(
                'edit'            => ($edit ? 'smallint_unsigned' : ''),
                'id'              => 'Order_Basket_Purge_Inactive_Days',
                'value'           => $object->get('Order Basket Purge Inactive Days'),
                'formatted_value' => $object->get('Inactive Days'),

                'label'             => ucfirst($object->get_field_label('Order Basket Purge Inactive Days')),
                'invalid_msg'       => get_invalid_message('smallint_unsigned'),
                'required'          => true,
                'type'              => 'value'
            ),
        )

    );
}




?>
