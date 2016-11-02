<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 September 2016 at 12:47:25 GMT+8, Kuta, Bali, Indonesia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$options_yn = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);
asort($options_yn);

$object_fields = array(
    array(
        'label'      => _('Description'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(


            array(
                'id'              => 'Image_Caption',
                'edit'            => 'string',
                'value'           => $object->get('Image Caption'),
                'formatted_value' => $object->get('Caption'),


                'label'       => ucfirst(
                    $object->get_field_label('Image Caption')
                ),
                'invalid_msg' => get_invalid_message('string'),
                'required'    => true,
                'type'        => 'value'

            ),

        )
    ),

    array(
        'label'      => _('Restrictions'),
        'show_title' => true,
        'class'      => 'edit_fields hide',
        'fields'     => array(

            array(
                'render' => ($options['type'] == 'supplier' ? false : true),

                'id'              => 'Image_Public',
                'edit'            => 'option',
                'value'           => 'No',
                'formatted_value' => _('No'),

                'value'           => ($new
                    ? 'No'
                    : $object->get(
                        'Image Public'
                    )),
                'formatted_value' => ($new ? _('No') : $object->get('Public')),

                'options'  => $options_yn,
                'label'    => ucfirst($object->get_field_label('Image Public')),
                'required' => true,

                'type' => 'value'

            )

        )
    ),


);


$operations = array(
    'label'      => _('Operations'),
    'show_title' => true,
    'class'      => 'operations',
    'fields'     => array(

        array(
            'id'        => 'delete_image',
            'class'     => 'operation',
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{  "image_bridge_key":"'.$object->get(
                    'Image Bridge Key'
                ).'"}\' onClick="delete_image(this)" class="delete_object disabled">'._("Delete image").' <i class="fa fa-trash new_button link"></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),


    )

);

//	$object_fields[]=$operations;


?>
