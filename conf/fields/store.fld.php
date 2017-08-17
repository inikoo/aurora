<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 April 2016 at 20:16:48 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


include_once 'utils/static_data.php';


$countries = get_countries($db);

if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}

$options_yes_no = array(
    'Yes' => _('Yes'),
    'No'  => _('No')

);

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
                'label'             => ucfirst($object->get_field_label('Store Code')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
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
        'label'      => _('Contact/Details'),
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
                'label'           => ucfirst($object->get_field_label('Store Address')),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
            ),
            array(
                'edit'            => ($edit ? 'string' : ''),
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
                'edit'            => ($edit ? 'string' : ''),
                'id'              => 'Store_URL',
                'value'           => $object->get('Store URL'),
                'formatted_value' => $object->get('URL'),
                'label'           => ucfirst($object->get_field_label('Store URL')),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
            ),
            array(
                'edit'            => ($edit ? 'string' : ''),
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
                'edit'            => ($edit ? 'string' : ''),
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

    array(
        'label'      => _('Collection'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'              => 'Store_Can_Collect',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_yes_no,
                'value'           => $object->get('Store Can Collect'),
                'formatted_value' => $object->get('Can Collect'),
                'label'           => _('Accept orders for collection'),
                'type'            => 'value'
            ),
            array(
                'id'              => 'Store_Collect_Address',
                'edit'            => 'address',
                'render'=>($object->get('Store Can Collect')=='Yes'?true:false),
                'countries'       => $countries,
                'value'           => htmlspecialchars($object->get('Store Collect Address')),
                'formatted_value' => $object->get('Store Collect Address Formatted'),
                'label'           => ucfirst($object->get_field_label('Store Collect Address')),
                'invalid_msg'     => get_invalid_message('address'),
                'required'        => false
            ),

        )
    ),


    array(
        'label'      => _('Orders'),
        'show_title' => true,
        'fields'     => array(


    array(
        'edit'     => ($edit ? 'string' : ''),
        'id'       => 'Store_Order_Public_ID_Format',
        'value'    => $object->get('Store Order Public ID Format'),
        'label'    => ucfirst($object->get_field_label('Store Order Public ID Format')).' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, misconfiguration of this variable can affect the creation of new orders').'" ></i>',
        'required' => true,

        'type' => 'value'


    ),

    array(
        'edit'     => ($edit ? 'numeric' : ''),
        'id'       => 'Store_Order_Last_Order_ID',
        'value'    => $object->get('Store Order Last Order ID'),
        'label'    => ucfirst($object->get_field_label('Store Order Last Order ID')).' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, misconfiguration of this variable can affect the creation of new orders').'" ></i>',
        'required' => true,

        'type' => 'value'


    ),



        )),



    array(
        'label'      => _('Signatures'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'     => ($edit ? 'textarea' : ''),
                'id'       => 'Store_Email_Template_Signature',
                'value'    => $object->get('Store Email Template Signature'),
                'label'    => ucfirst($object->get_field_label('Store Email Template Signature')),
                'required' => false,

                'type' => 'value'


            ),

            array(
                'edit'     => ($edit ? 'textarea' : ''),
                'id'       => 'Store_Invoice_Message',
                'value'    => $object->get('Store Invoice Message'),
                'label'    => ucfirst($object->get_field_label('Store Invoice Message')),
                'required' => false,

                'type' => 'value'


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
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name(
                    ).'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'.($object->get('Store Contacts') > 0 ? _('Close store') : _("Delete store"))
                    .' <i class="fa fa-trash new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
}


?>
