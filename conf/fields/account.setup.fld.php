<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 August 2016 at 22:10:28 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/



include_once 'utils/static_data.php';


$options_locale = array(
    'en_GB' => 'en_GB '._('British English'),
    'de_DE' => 'de_DE '._('German'),
    'fr_FR' => 'fr_FR '._('French'),
    'es_ES' => 'es_ES '._('Spanish'),
    'pl_PL' => 'pl_PL '._('Polish'),
    'it_IT' => 'it_IT '._('Italian'),
    'sk_SK' => 'sk_SK '._('Slovak'),
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

$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(


            array(


                'id'              => 'Account_Name',
                'edit'            => 'string',
                'value'           => htmlspecialchars($object->get('Account Name')),
                'formatted_value' => $object->get('Name'),
                'label'           => ucfirst($object->get_field_label('Account Name')),
                'required'        => false


            ),

        )
    ),
    array(
        'label'      => _('Localization'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'                       => 'Account_Country_Code',
                'edit'                     => ($edit ? 'country_select' : ''),
                'options'                  => get_countries($db),
                'scope'                    => 'countries',
                'value'                    => $object->get('Account Country Code'),
                'formatted_value'          => $object->get('Country Code'),
                'stripped_formatted_value' => $object->get('Country Code'),
                'label' => _('Country'),
                'required'                 => true,
                'type'                     => 'value',

            ),
            
             array(
                'id'              => 'Account_Currency',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_currencies,
                'value'           => $object->get('Account Currency'),
                'formatted_value' => $object->get('Currency'),
                'label'           => ucfirst($object->get_field_label('Account Currency')),
                'type'            => 'value'
            ),
            
            
              array(
                'id'              => 'Account_Timezone',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_timezones,
                'value'           => $object->get('Account Timezone'),
                'formatted_value' => $object->get('Timezone'),
                'label'           => ucfirst(
                    $object->get_field_label('Account Timezone')
                ),
                'type'            => 'value'
            ),

            array(
                'id'              => 'Account_Locale',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_locale,
                'value'           => $object->get('Account Locale'),
                'formatted_value' => $object->get('Locale'),
                'label'           => ucfirst(
                    $object->get_field_label('Account Locale')
                ),
                'type'            => 'value'
            ),
            
          

        )
    ),


);


?>
