<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 2 February 2016 at 18:39:33 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';

include_once 'utils/invalid_messages.php';
include_once 'class.Customer.php';


$customer = new Customer(0);

$options_valid_tax_number = array(
    'Yes'     => _('Valid'),
    'No'      => _('Not Valid'),
    'Unknown' => _('Unknown'),
    'Auto'    => _('Check online'),
);

$options_yn = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);


asort($options_yn);

$object_fields = array(
    array(
        'label'      => _('Name, Ids'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'              => 'Customer_Company_Name',
                'edit'            => 'string',
                'value'           => htmlspecialchars(
                    $customer->get('Customer Company Name')
                ),
                'formatted_value' => $customer->get('Company Name'),
                'label'           => ucfirst(
                    $customer->get_field_label('Customer Company Name')
                ),
                'required'        => false,
                'type'            => 'value'
            ),

            array(

                'id'              => 'Customer_Main_Contact_Name',
                'edit'            => 'string',
                'value'           => htmlspecialchars(
                    $customer->get('Customer Main Contact Name')
                ),
                'formatted_value' => $customer->get('Main Contact Name'),
                'label'           => ucfirst(
                    $customer->get_field_label('Customer Main Contact Name')
                ),
                'required'        => true,
                'type'            => 'value'
            ),
            array(
                'id'              => 'Customer_Registration_Number',
                'edit'            => 'string',
                'value'           => $customer->get(
                    'Customer Registration Number'
                ),
                'formatted_value' => $customer->get('Registration Number'),
                'label'           => ucfirst(
                    $customer->get_field_label('Customer Registration Number')
                ),
                'required'        => false,
                'type'            => 'value'
            ),
            array(
                'id'              => 'Customer_Tax_Number',
                'edit'            => 'string',
                'value'           => $customer->get('Customer Tax Number'),
                'formatted_value' => $customer->get('Tax Number'),
                'label'           => ucfirst(
                    $customer->get_field_label('Customer Tax Number')
                ),
                'required'        => false,
                'type'            => 'value'

            )


        )
    )

    ,
    array(
        'label'      => _('Email'),
        'show_title' => false,
        'fields'     => array(

            array(
                'id'                => 'Customer_Main_Plain_Email',
                'edit'              => 'email',
                'value'             => $customer->get(
                    'Customer Main Plain Email'
                ),
                'formatted_value'   => $customer->get('Main Plain Email'),
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'label'             => ucfirst(
                    $customer->get_field_label('Customer Main Plain Email')
                ),
                'invalid_msg'       => get_invalid_message('email'),
                'required'          => true,
                'type'              => 'value'
            )

        )
    ),

    array(
        'label'      => _('Telephones'),
        'show_title' => false,
        'fields'     => array(

            array(
                'id'              => 'Customer_Main_Plain_Mobile',
                'edit'            => 'telephone',
                'value'           => $customer->get(
                    'Customer Main Plain Mobile'
                ),
                'formatted_value' => $customer->get('Main Plain Mobile'),
                'label'           => ucfirst(
                    $customer->get_field_label('Customer Main Plain Mobile')
                ),
                'invalid_msg'     => get_invalid_message('telephone'),
                'required'        => false,
                'type'            => 'value'
            ),
            array(


                'id'              => 'Customer_Main_Plain_Telephone',
                'edit'            => 'telephone',
                'value'           => $customer->get(
                    'Customer Main Plain Telephone'
                ),
                'formatted_value' => $customer->get('Main Plain Telephone'),
                'label'           => ucfirst(
                    $customer->get_field_label('Customer Main Plain Telephone')
                ),
                'invalid_msg'     => get_invalid_message('telephone'),
                'required'        => false,
                'type'            => 'value'

            ),
            array(
                'id'              => 'Customer_Main_Plain_FAX',
                'edit'            => 'telephone',
                'value'           => $customer->get('Customer Main Plain FAX'),
                'formatted_value' => $customer->get('Main Plain FAX'),
                'label'           => ucfirst(
                    $customer->get_field_label('Customer Main Plain FAX')
                ),
                'invalid_msg'     => get_invalid_message('telephone'),
                'required'        => false,
                'type'            => 'value'
            ),

        )
    ),

    array(
        'label'      => _('Address'),
        'show_title' => false,
        'fields'     => array(

            array(
                'id'              => 'Customer_Contact_Address',
                'edit'            => 'address',
                'value'           => htmlspecialchars(
                    $customer->get('Customer Contact Address')
                ),
                'formatted_value' => $customer->get('Contact Address'),
                'label'           => ucfirst(
                    $customer->get_field_label('Customer Contact Address')
                ),
                'invalid_msg'     => get_invalid_message('address'),
                'required'        => false,
                'type'            => 'value'
            ),


        )
    ),


);
$smarty->assign('state', $state);
$smarty->assign('object', $customer);


$smarty->assign('object_name', $customer->get_object_name());


$smarty->assign('object_fields', $object_fields);

$store = new Store($state['parent_key']);
$smarty->assign(
    'default_country', $store->get('Store Home Country Code 2 Alpha')
);
$smarty->assign(
    'preferred_countries', '"'.join(
        '", "', preferred_countries($store->get('Store Home Country Code 2 Alpha'))
    ).'"'
);


$html = $smarty->fetch('new_object.tpl');

?>
