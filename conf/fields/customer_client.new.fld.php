<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-10-2019 17:11:13 MYT,, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

include_once 'utils/static_data.php';


$countries = get_countries($db);


$_edit = true;


$object_fields = array(
    array(
        'label'      => _('Ids, Name'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'                => 'Customer_Client_Code',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => htmlspecialchars($object->get('Customer Client Code')),
                'formatted_value'   => $object->get('Code'),
                'label'             => ucfirst($object->get_field_label('Customer Client Code')),
                'required'          => true,
                'server_validation' => json_encode(
                    array(
                        'tipo'       => 'check_for_duplicates',
                        'parent'     => 'customer',
                        'parent_key' => $options['customer_key'],
                        'object'     => 'Customer Client',
                    )
                ),
                'type'              => 'value'
            ),
            array(
                'id'              => 'Customer_Client_Company_Name',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Customer Client Company Name')),
                'formatted_value' => $object->get('Company Name'),
                'label'           => ucfirst($object->get_field_label('Customer Client Company Name')),
                'required'        => false,
                'type'            => 'value'
            ),

            array(

                'id'              => 'Customer_Client_Main_Contact_Name',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Customer Client Main Contact Name')),
                'formatted_value' => $object->get('Main Contact Name'),
                'label'           => ucfirst($object->get_field_label('Customer Client Main Contact Name')),
                'required'        => true,
                'type'            => 'value'
            ),


        )
    ),
    array(
        'label'      => _('Email'),
        'show_title' => false,
        'fields'     => array(

            array(
                'id'                => 'Customer_Client_Main_Plain_Email',
                'edit'              => ($_edit ? 'email' : ''),
                'value'             => $object->get('Customer Client Main Plain Email'),
                'formatted_value'   => $object->get('Main Plain Email'),
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'label'             => ucfirst($object->get_field_label('Customer Client Main Plain Email')),
                'invalid_msg'       => get_invalid_message('email'),
                'required'          => false,
                'type'              => 'value'
            ),


        )
    ),

    array(
        'label'      => _('Contact'),
        'show_title' => false,
        'fields'     => array(

            array(
                'id'              => 'Customer_Client_Main_Plain_Mobile',
                'edit'            => ($_edit ? 'telephone' : ''),
                'value'           => $object->get('Customer Client Main Plain Mobile'),
                'formatted_value' => $object->get('Customer Client Main XHTML Mobile'),
                'label'           => ucfirst(
                        $object->get_field_label('Customer Client Main Plain Mobile')
                    ).($object->get('Customer Client Main Plain Mobile') != '' ? ($object->get('Customer Client Preferred Contact Number') == 'Mobile'
                        ? ''
                        : ' <i onClick="set_this_as_main(this)" title="'._(
                            'Set as main contact number'
                        ).'" class="far fa-star discreet button"></i>') : ''),
                'invalid_msg'     => get_invalid_message('telephone'),
                'required'        => false,
                'type'            => 'value'
            ),
            array(


                'id'              => 'Customer_Client_Main_Plain_Telephone',
                'edit'            => ($_edit ? 'telephone' : ''),
                'value'           => $object->get('Customer Client Main Plain Telephone'),
                'formatted_value' => $object->get('Main Plain Telephone'),
                'label'           => ucfirst(
                        $object->get_field_label(
                            'Customer Client Main Plain Telephone'
                        )
                    ).($object->get('Customer Client Main Plain Telephone') != '' ? ($object->get('Customer Client Preferred Contact Number') == 'Telephone'
                        ? ''
                        : ' <i onClick="set_this_as_main(this)" title="'._(
                            'Set as main contact number'
                        ).'" class="far fa-star discreet button"></i>') : ''),
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
                'id'              => 'Customer_Client_Contact_Address',
                'edit'            => ($_edit ? 'address' : ''),
                'countries'       => $countries,
                'value'           => htmlspecialchars($object->get('Customer Client Contact Address')),
                'formatted_value' => $object->get('Contact Address'),
                'label'           => ucfirst($object->get_field_label('Customer Client Contact Address')),
                'invalid_msg'     => get_invalid_message('address'),
                'required'        => false,
                'type'            => 'value'

            ),


        )
    ),


);







