<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 26 July 2016 at 10:52:36 GMT+7, Bangkok, Thailand

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
asort($options_Attachment_Subject_Type);
asort($options_yn);

$object_fields = array(
    array(
        'label'      => _('Description'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(


            array(
                'id'   => 'Attachment_Subject_Type',
                'edit' => 'option',

                'value'           => ($new
                    ? 'Other'
                    : $object->get(
                        'Attachment Subject Type'
                    )),
                'formatted_value' => ($new
                    ? _('Other')
                    : $object->get(
                        'Subject Type'
                    )),

                'options'  => $options_Attachment_Subject_Type,
                'label'    => ucfirst(
                    $object->get_field_label('Attachment Subject Type')
                ),
                'required' => true,

                'type' => 'value'
            ),
            array(
                'id'              => 'Attachment_Caption',
                'edit'            => 'string',
                'value'           => $object->get('Attachment Caption'),
                'formatted_value' => $object->get('Caption'),


                'label'       => ucfirst(
                    $object->get_field_label('Attachment Caption')
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

                'id'              => 'Attachment_Public',
                'edit'            => 'option',
                'value'           => 'No',
                'formatted_value' => _('No'),

                'value'           => ($new
                    ? 'No'
                    : $object->get(
                        'Attachment Public'
                    )),
                'formatted_value' => ($new ? _('No') : $object->get('Public')),

                'options'  => $options_yn,
                'label'    => ucfirst(
                    $object->get_field_label('Attachment Public')
                ),
                'required' => true,

                'type' => 'value'

            )

        )
    ),


);


if ($new) {

    $object_fields[] = array(
        'label'      => _('Attachment'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(

            array(

                'id'              => 'Attachment_File',
                'edit'            => 'attachment',
                'value'           => '',
                'formatted_value' => '',
                'label'           => ucfirst(
                    $object->get_field_label('Attachment File')
                ),
                'required'        => true,

                'type' => 'value'

            )

        )
    );

} else {
    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(

            array(
                'id'        => 'delete_attachment',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{  "attachment_bridge_key":"'
                    .$object->get(
                        'Attachment Bridge Key'
                    ).'"}\' onClick="delete_attachment(this)" class="delete_object disabled">'._("Delete attachment").' <i class="fa fa-trash new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;

}


?>
