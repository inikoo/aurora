<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 July 2016 at 10:52:36 GMT+7, Bangkok, Thailand

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_attachment_fields($object, $user, $options): array {

    $options_Attachment_Subject_Type_default_value='';
    $options_Attachment_Subject_Type=[];

    if ($options['type'] == 'employee') {

        $options_Attachment_Subject_Type               = array(
            'CV'       => _('Curriculum vitae'),
            'Contract' => _('Employment contract'),
            'Other'    => _('Other'),

        );
        $options_Attachment_Subject_Type_default_value = 'Contract';
    } elseif ($options['type'] == 'supplier') {
        $options_Attachment_Subject_Type               = array(
            'Invoice'       => _('Invoice'),
            'PurchaseOrder' => _('Purchase order'),
            'Catalogue'     => _('Catalogue'),
            'Image'         => _('Image'),
            'Contact Card'  => _('Contact card'),
            'Other'         => _('Other'),
        );
        $options_Attachment_Subject_Type_default_value = 'Contact Card';

    } elseif ($options['type'] == 'part') {
        $options_Attachment_Subject_Type               = array(
            'Other' => _('Other'),
            'MSDS'  => _('MSDS'),


        );
        $options_Attachment_Subject_Type_default_value = 'MSDS';
    } elseif ($options['type'] == 'supplier_delivery') {
        $options_Attachment_Subject_Type = array(
            'Delivery Paperwork' => _('Delivery paperwork'),
            'Invoice'            => _('Invoice'),
            'Other'              => _('Other'),

        );

        $options_Attachment_Subject_Type_default_value = 'Delivery Paperwork';

    }

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

    $fields=[];
    if(count($options_Attachment_Subject_Type)>1){
        $fields[]=array(
            'id'   => 'Attachment_Subject_Type',
            'edit' => 'option',

            'value'           => ($new ? $options_Attachment_Subject_Type_default_value : $object->get('Attachment Subject Type')),
            'formatted_value' => ($new ? $options_Attachment_Subject_Type[$options_Attachment_Subject_Type_default_value] : $object->get('Subject Type')),

            'options'  => $options_Attachment_Subject_Type,
            'label'    => ucfirst($object->get_field_label('Attachment Subject Type')),
            'required' => true,

            'type' => 'value'
        );
    }
    $fields[]= array(
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

    );


    $object_fields = array(
        array(
            'label'      => _('Description'),
            'show_title' => true,
            'class'      => 'edit_fields',
            'fields'     => $fields
        ),

        array(
            'label'      => _('Restrictions'),
            'show_title' => true,
            'class'      => 'edit_fields hide',
            'fields'     => array(

                array(
                    'render' => !in_array(
                        $options['type'], array(
                                            'supplier','customer','order',
                                            'supplier_delivery'
                                        )
                    ),

                    'id'              => 'Attachment_Public',
                    'edit'            => 'option',


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
                    'label'     => '<i class="fa fa-fw fa-lock-alt button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{  "attachment_bridge_key":"'.$object->get(
                            'Attachment Bridge Key'
                        ).'"}\' onClick="delete_attachment(this)" class="delete_object disabled">'._("Delete attachment").' <i class="far fa-trash-alt new_button link"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),


            )

        );

        $object_fields[] = $operations;

    }

    return $object_fields;

}
