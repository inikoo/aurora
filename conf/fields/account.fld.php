<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 11 August 2016 at 22:10:28 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
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
                'id'    => 'Account_Country',
                'value' => $object->get('Account Country Code'),
                'label' => _('Country')
            ),
            array(
                'id'    => 'Account_Currency',
                'value' => $object->get('Account Currency'),
                'label' => _('Currency')
            ),
            array(
                'id'    => 'Account_Timezone',
                'value' => $object->get('Account Timezone'),
                'label' => _('Timezone')
            )

        )
    ),


);


?>
