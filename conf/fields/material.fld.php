<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 August 2016 at 13:55:29 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$options_Material_Type = array(
    'Material'   => _('Material'),
    'Ingredient' => _('Ingredient')
);


asort($options_Material_Type);

$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(

            array(

                'id'   => 'Material_Type',
                'edit' => ($edit ? 'option' : ''),

                'value'           => ($new
                    ? 'Material'
                    : $object->get(
                        'Material Type'
                    )),
                'formatted_value' => ($new
                    ? _('Material')
                    : $object->get(
                        'Type'
                    )),
                'options'         => $options_Material_Type,
                'label'           => ucfirst(
                    $object->get_field_label('Material Type')
                ),
                'type'            => 'value',
                'required'        => false,
            ),

            array(

                'id'   => 'Material_Name',
                'edit' => ($edit ? 'string' : ''),

                'value'             => $object->get('Material Name'),
                'label'             => ucfirst(
                    $object->get_field_label('Material Name')
                ),
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'invalid_msg'       => get_invalid_message('string'),
                'type'              => 'value'
            ),


        )
    ),


);


?>
