<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 April 2016 at 20:16:48 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}



$options_locale = array(
    'en_GB' => 'en_GB '._('British English'),
    'de_DE' => 'de_DE '._('German'),
    'fr_FR' => 'fr_FR '._('French'),
    'es_ES' => 'es_ES '._('Spanish'),
    'pl_PL' => 'pl_PL '._('Polish'),
    'it_IT' => 'it_IT '._('Italian'),
    'sk_SK' => 'sk_SK '._('Sloavak'),
    'pt_PT' => 'pt_PT '._('Portuguese'),
);
asort($options_locale);


$options_timezones = array();
foreach (DateTimeZone::listIdentifiers() as $timezone) {
    $options_timezones[preg_replace('/\//', '_', $timezone)] = $timezone;
}

$options_currencies = array();
$sql                = sprintf(
    "SELECT `Currency Code`,`Currency Name`,`Currency Symbol`,`Currency Flag` FROM kbase.`Currency Dimension` "
);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $options_currencies[$row['Currency Code']] = _($row['Currency Name']).' '.$row['Currency Symbol'];
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

asort($options_currencies);
$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Store_Code',
                'value'             => $object->get('Store Code'),
                'label'             => ucfirst(
                    $object->get_field_label('Store Code')
                ),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'type'              => 'value'


            ),
            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Store_Name',
                'value'             => $object->get('Store Name'),
                'label'             => ucfirst(
                    $object->get_field_label('Store Name')
                ),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),

                'type' => 'value'
            ),


        )
    ),
    array(
        'label'      => _('Localization'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'              => 'Store_Locale',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_locale,
                'value'           => $object->get('Store Locale'),
                'formatted_value' => $object->get('Locale'),
                'label'           => ucfirst(
                    $object->get_field_label('Store Locale')
                ),
                'type'            => 'value'
            ),
            array(
                'id'              => 'Store_Currency_Code',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_currencies,
                'value'           => $object->get('Store Currency Code'),
                'formatted_value' => $object->get('Currency Code'),
                'label'           => ucfirst(
                    $object->get_field_label('Store Currency Code')
                ),
                'type'            => 'value'
            ),
            array(
                'id'              => 'Store_Timezone',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_timezones,
                'value'           => $object->get('Store Timezone'),
                'formatted_value' => $object->get('Timezone'),
                'label'           => ucfirst(
                    $object->get_field_label('Store Timezone')
                ),
                'type'            => 'value'
            )

        )
    ),
    array(
        'label'      => _('Contact'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'        => ($edit ? 'email' : ''),
                'id'          => 'Store_Email',
                'value'       => $object->get('Store Email'),
                'label'       => ucfirst(
                    $object->get_field_label('Store Email')
                ),
                'invalid_msg' => get_invalid_message('email'),
                'required'    => false,

                'type' => 'value'


            ),
            array(
                'edit'            => ($edit ? 'telephone' : ''),
                'id'              => 'Store_Telephone',
                'value'           => $object->get('Store Telephone'),
                'formatted_value' => $object->get('Telephone'),
                'label'           => ucfirst(
                    $object->get_field_label('Store Telephone')
                ),
                'invalid_msg'     => get_invalid_message('telephone'),
                'required'        => false,
                'type'            => 'value'
            ),

            array(
                'edit'            => ($edit ? 'textarea' : ''),
                'id'              => 'Store_Address',
                'value'           => $object->get('Store Address'),
                'formatted_value' => $object->get('Address'),
                'label'           => ucfirst(
                    $object->get_field_label('Store Address')
                ),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
            ),
            array(
                'edit' => ($edit ? 'string' : ''),

                'id'              => 'Store_URL',
                'value'           => $object->get('Store URL'),
                'formatted_value' => $object->get('Store URL'),
                'label'           => ucfirst(
                    $object->get_field_label('Store URL')
                ),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
            ),

        )
    )

);

$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Store_Code',
                'value'             => $object->get('Store Code'),
                'label'             => ucfirst(
                    $object->get_field_label('Store Code')
                ),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'type'              => 'value'


            ),
            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Store_Name',
                'value'             => $object->get('Store Name'),
                'label'             => ucfirst(
                    $object->get_field_label('Store Name')
                ),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),

                'type' => 'value'
            ),


        )
    ),
    array(
        'label'      => _('Localization'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'              => 'Store_Locale',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_locale,
                'value'           => $object->get('Store Locale'),
                'formatted_value' => $object->get('Locale'),
                'label'           => ucfirst(
                    $object->get_field_label('Store Locale')
                ),
                'type'            => 'value'
            ),
            array(
                'id'              => 'Store_Currency_Code',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_currencies,
                'value'           => $object->get('Store Currency Code'),
                'formatted_value' => $object->get('Currency Code'),
                'label'           => ucfirst(
                    $object->get_field_label('Store Currency Code')
                ),
                'type'            => 'value'
            ),
            array(
                'id'              => 'Store_Timezone',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_timezones,
                'value'           => $object->get('Store Timezone'),
                'formatted_value' => $object->get('Timezone'),
                'label'           => ucfirst(
                    $object->get_field_label('Store Timezone')
                ),
                'type'            => 'value'
            )

        )
    ),
    array(
        'label'      => _('Contact'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'        => ($edit ? 'email' : ''),
                'id'          => 'Store_Email',
                'value'       => $object->get('Store Email'),
                'label'       => ucfirst(
                    $object->get_field_label('Store Email')
                ),
                'invalid_msg' => get_invalid_message('email'),
                'required'    => false,

                'type' => 'value'


            ),
            array(
                'edit'            => ($edit ? 'string' : ''),
                'id'              => 'Store_Telephone',
                'value'           => $object->get('Store Telephone'),
                'formatted_value' => $object->get('Telephone'),
                'label'           => ucfirst(
                    $object->get_field_label('Store Telephone')
                ),
                'invalid_msg'     => get_invalid_message('telephone'),
                'required'        => false,
                'type'            => 'value'
            ),

            array(
                'edit'            => ($edit ? 'textarea' : ''),
                'id'              => 'Store_Address',
                'value'           => $object->get('Store Address'),
                'formatted_value' => $object->get('Address'),
                'label'           => ucfirst(
                    $object->get_field_label('Store Address')
                ),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
            ),
            array(
                'edit' => ($edit ? 'string' : ''),

                'id'              => 'Store_URL',
                'value'           => $object->get('Store URL'),
                'formatted_value' => $object->get('Store URL'),
                'label'           => ucfirst(
                    $object->get_field_label('Store URL')
                ),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
            ),

        )
    )

);

if (!$new) {
    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(


            array(
                'id'        => 'delete_store',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'.($object->get('Store Contacts')>0?_('Close store'): _("Delete store")).' <i class="fa fa-trash new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
}



?>
