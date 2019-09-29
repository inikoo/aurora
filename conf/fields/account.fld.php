<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 August 2016 at 22:10:28 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'utils/timezones.php';

if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}

if ($user->can_edit('account')) {
    $edit = true;
} else {
    $edit = false;
}




$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(


            array(


                'id'              => 'Account_Name',
                'edit'            => 'string',
                'value'           => htmlspecialchars(
                    $object->get('Account Name')
                ),
                'formatted_value' => $object->get('Name'),
                'label'           => ucfirst(
                    $object->get_field_label('Account Name')
                ),
                'required'        => false


            ),

        )
    ),
    array(
        'label'      => _('Localization'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'     => 'Account_Country',
                'render' => false,
                'value'  => $object->get('Account Country Code'),
                'label'  => _('Country')
            ),
            array(
                'id'     => 'Account_Currency',
                'render' => false,
                'value'  => $object->get('Account Currency'),
                'label'  => _('Currency')
            ),
            array(
                'id'              => 'Account_Timezone',
                'edit'            => ($edit ? 'timezone' : ''),
                'value'           => $object->get('Account Timezone'),
                'formatted_value' => $object->get('Timezone'),
                'timezones'       => get_normalized_timezones(),

                'label' => _('Timezone')
            )

        )
    ),


    array(
        'label'      => _('Product labels'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit'            => ($edit ? 'textarea' : ''),
                'id'              => 'Account_Label_Signature',
                'value'           => $object->get('Account Label Signature'),
                'formatted_value' => $object->get('Label Signature'),

                'label'    => ucfirst($object->get_field_label('Account Label Signature')),
                'required' => false,

                'type' => ''


            ),

        )
    ),


);



