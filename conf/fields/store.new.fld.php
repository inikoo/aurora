<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 April 2016 at 20:16:48 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_new_store_fields(Store $object, User $user, PDO $db, $smarty): array {


    if (!in_array($object->id, $user->stores)) {
        $edit = true;
    } else {
        $edit = false;
    }

    include_once 'utils/static_data.php';



    $options_locale = array(
        'en_GB' => 'en_GB '._('British English'),
        'de_DE' => 'de_DE '._('German'),
        'fr_FR' => 'fr_FR '._('French'),
        'es_ES' => 'es_ES '._('Spanish'),
        'pl_PL' => 'pl_PL '._('Polish'),
        'it_IT' => 'it_IT '._('Italian'),
        'sk_SK' => 'sk_SK '._('Slovak'),
        'nl_NL' => 'nl_NL '._('Dutch'),
        'pt_PT' => 'pt_PT '._('Portuguese'),
        'ro_RO' => 'ro_RO '._('Romanian'),
        'sv_SE' => 'sv_SE '._('Swedish'),
    );
    asort($options_locale);



    $options_timezones = array();
    foreach (DateTimeZone::listIdentifiers() as $timezone) {
        $options_timezones[preg_replace('/\//', '_', $timezone)] = $timezone;
    }

    $options_currencies = array();
    $sql                = "SELECT `Currency Code`,`Currency Name`,`Currency Symbol`,`Currency Flag` FROM kbase.`Currency Dimension` ";
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $options_currencies[$row['Currency Code']] = _($row['Currency Name']).' '.$row['Currency Symbol'];
        }
    } 

    asort($options_currencies);



    $object->smarty = $smarty;

    return array(
        array(
            'label'      => _('Id'),
            'show_title' => true,
            'fields'     => array(

                array(
                    'edit'              => 'string',
                    'id'                => 'Store_Code',
                    'value'             => $object->get('Store Code'),
                    'label'             => ucfirst($object->get_field_label('Store Code')),
                    'invalid_msg'       => get_invalid_message('string'),
                    'required'          => true,
                    'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                    'type'              => 'value'


                ),
                array(
                    'edit'              => 'string',
                    'id'                => 'Store_Name',
                    'value'             => $object->get('Store Name'),
                    'label'             => ucfirst($object->get_field_label('Store Name')),
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
                    'edit'            => 'option',
                    'options'         => $options_locale,
                    'value'           => $object->get('Store Locale'),
                    'formatted_value' => $object->get('Locale'),
                    'label'           => ucfirst($object->get_field_label('Store Locale')),
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Store_Currency_Code',
                    'edit'            => 'option',
                    'options'         => $options_currencies,
                    'value'           => $object->get('Store Currency Code'),
                    'formatted_value' => $object->get('Currency Code'),
                    'label'           => ucfirst($object->get_field_label('Store Currency Code')),
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Store_Timezone',
                    'edit'            => 'option',
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
            'label'      => _('Contact/Details'),
            'show_title' => true,
            'fields'     => array(

                array(
                    'edit'        => ($edit ? 'email' : ''),
                    'id'          => 'Store_Email',
                    'value'       => $object->get('Store Email'),
                    'label'       => ucfirst($object->get_field_label('Store Email')),
                    'invalid_msg' => get_invalid_message('email'),
                    'required'    => false,

                    'type' => 'value'


                ),
                array(
                    'edit'            => 'string',
                    'id'              => 'Store_Telephone',
                    'value'           => $object->get('Store Telephone'),
                    'formatted_value' => $object->get('Telephone'),
                    'label'           => ucfirst($object->get_field_label('Store Telephone')),
                    'invalid_msg'     => get_invalid_message('telephone'),
                    'required'        => false,
                    'type'            => 'value'
                ),

                array(
                    'edit'            => ($edit ? 'textarea' : ''),
                    'id'              => 'Store_Address',
                    'value'           => $object->get('Store Address'),
                    'formatted_value' => $object->get('Address'),
                    'label'           => ucfirst($object->get_field_label('Store Address')),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'edit'            => 'string',
                    'id'              => 'Store_Company_Name',
                    'value'           => $object->get('Store Company Name'),
                    'formatted_value' => $object->get('Company Name'),
                    'label'           => ucfirst(
                        $object->get_field_label('Store Company Name')
                    ),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'edit'            => 'string',
                    'id'              => 'Store_URL',
                    'value'           => $object->get('Store URL'),
                    'formatted_value' => $object->get('URL'),
                    'label'           => ucfirst($object->get_field_label('Store URL')),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'edit'            => 'string',
                    'id'              => 'Store_Company_Number',
                    'value'           => $object->get('Store Company Number'),
                    'formatted_value' => $object->get('Company Number'),
                    'label'           => ucfirst(
                        $object->get_field_label('Store Company Number')
                    ),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'edit'            => 'string',
                    'id'              => 'Store_VAT_Number',
                    'value'           => $object->get('Store VAT Number'),
                    'formatted_value' => $object->get('VAT Number'),
                    'label'           => ucfirst(
                        $object->get_field_label('Store VAT Number')
                    ),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => false,
                    'type'            => 'value'
                ),

            )
        ),


    );

}

