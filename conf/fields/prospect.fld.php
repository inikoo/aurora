<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 May 2018 at 20:33:22 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

include_once 'utils/static_data.php';


$smarty->assign('prospect', $object);

$countries = get_countries($db);

$options_valid_tax_number = array(
    'Yes'     => _('Valid'),
    'No'      => _('Not Valid'),
    'Unknown' => _('Unknown'),
    'Auto'    => _('Check online'),
);


$options_delivery_address_link = array(
    'Billing' => _('Same as invoice address'),
    'None'    => _('Unrelated to invoice address'),
);


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


if ($new) {

    $prospect_fields = array(
        array(
            'label'      => _('Name, Ids'),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'              => 'Prospect_Company_Name',
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => htmlspecialchars($object->get('Prospect Company Name')),
                    'formatted_value' => $object->get('Company Name'),
                    'label'           => ucfirst($object->get_field_label('Prospect Company Name')),
                    'required'        => false,
                    'type'            => 'value'
                ),

                array(

                    'id'              => 'Prospect_Main_Contact_Name',
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => htmlspecialchars($object->get('Prospect Main Contact Name')),
                    'formatted_value' => $object->get('Main Contact Name'),
                    'label'           => ucfirst($object->get_field_label('Prospect Main Contact Name')),
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
                    'id'                => 'Prospect_Main_Plain_Email',
                    'edit'              => ($edit ? 'email' : ''),
                    'value'             => $object->get('Prospect Main Plain Email'),
                    'formatted_value'   => $object->get('Main Plain Email'),
                    'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                    'label'             => ucfirst($object->get_field_label('Prospect Main Plain Email')),
                    'invalid_msg'       => get_invalid_message('email'),
                    'required'          => true,
                    'type'              => 'value'
                ),


            )
        ),

        array(
            'label'      => _('Contact'),
            'show_title' => false,
            'fields'     => array(

                array(
                    'id'              => 'Prospect_Main_Plain_Mobile',
                    'edit'            => ($edit ? 'telephone' : ''),
                    'value'           => $object->get('Prospect Main Plain Mobile'),
                    'formatted_value' => $object->get('Prospect Main XHTML Mobile'),
                    'label'           => ucfirst(
                            $object->get_field_label('Prospect Main Plain Mobile')
                        ).($object->get('Prospect Main Plain Mobile') != '' ? ($object->get('Prospect Preferred Contact Number') == 'Mobile'
                            ? ''
                            : ' <i onClick="set_this_as_main(this)" title="'._(
                                'Set as main contact number'
                            ).'" class="far fa-star discreet button"></i>') : ''),
                    'invalid_msg'     => get_invalid_message('telephone'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(


                    'id'              => 'Prospect_Main_Plain_Telephone',
                    'edit'            => ($edit ? 'telephone' : ''),
                    'value'           => $object->get(
                        'Prospect Main Plain Telephone'
                    ),
                    'formatted_value' => $object->get('Main Plain Telephone'),
                    'label'           => ucfirst(
                            $object->get_field_label(
                                'Prospect Main Plain Telephone'
                            )
                        ).($object->get('Prospect Main Plain Telephone') != '' ? ($object->get('Prospect Preferred Contact Number') == 'Telephone'
                            ? ''
                            : ' <i onClick="set_this_as_main(this)" title="'._(
                                'Set as main contact number'
                            ).'" class="far fa-star discreet button"></i>') : ''),
                    'invalid_msg'     => get_invalid_message('telephone'),
                    'required'        => false,
                    'type'            => 'value'

                ),


                array(
                    'id'              => 'Prospect_Main_Plain_FAX',
                    'edit'            => ($edit ? 'telephone' : ''),
                    'value'           => $object->get('Prospect Main Plain FAX'),
                    'formatted_value' => $object->get('Main Plain FAX'),
                    'label'           => ucfirst($object->get_field_label('Prospect Main Plain FAX')),
                    'invalid_msg'     => get_invalid_message('telephone'),
                    'required'        => false,
                    'type'            => 'value'
                ),


                array(
                    'id'                => 'Prospect_Website',
                    'edit'              => ($edit ? 'string' : ''),
                    'value'             => $object->get('Prospect Website'),
                    'formatted_value'   => $object->get('Website'),
                    'label'             => ucfirst($object->get_field_label('Prospect Website')),
                    'invalid_msg'       => get_invalid_message('string'),
                    'required'          => false,
                    'type'              => 'value'
                ),

            )
        ),


        array(
            'label'      => _('Address'),
            'show_title' => false,
            'fields'     => array(


                array(
                    'id'              => 'Prospect_Contact_Address',
                    'edit'            => ($edit ? 'address' : ''),
                    'countries'       => $countries,
                    'value'           => htmlspecialchars($object->get('Prospect Contact Address')),
                    'formatted_value' => $object->get('Contact Address'),
                    'label'           => ucfirst($object->get_field_label('Prospect Contact Address')),
                    'invalid_msg'     => get_invalid_message('address'),
                    'required'        => false,
                    'type'            => 'value'

                ),


                array(
                    'id'              => 'Prospect_Invoice_Address',
                    'edit'            => ($edit ? 'address' : ''),
                    'countries'       => $countries,
                    'value'           => htmlspecialchars($object->get('Prospect Invoice Address')),
                    'formatted_value' => $object->get('Invoice Address'),
                    'label'           => ucfirst($object->get_field_label('Prospect Invoice Address')),
                    'required'        => false
                ),
                array(
                    'id'              => 'Prospect_Delivery_Address',
                    'edit'            => ($edit ? 'address' : ''),
                    'countries'       => $countries,
                    'value'           => htmlspecialchars(
                        $object->get('Prospect Delivery Address')
                    ),
                    'formatted_value' => $object->get('Delivery Address'),
                    'label'           => ucfirst(
                        $object->get_field_label('Prospect Delivery Address')
                    ),
                    'invalid_msg'     => get_invalid_message('address'),
                    'required'        => false
                ),


            )
        ),



    );


}
else {




    $prospect_fields =array();




    if($object->get('Prospect Type by Activity')=='ToApprove'){
       $prospect_fields[]= array(
           'label'      => _('Approve prospect'),
           'show_title' => true,
           'class'      => 'edit_fields',
           'fields'     => array(


               array(

                   'id'        => 'approve_prospect',
                   'class'     => 'operation',
                   'value'     => '',
                   'label'     => '<i class="fa fa-fw fa-lock button invisible" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                       .'"}\' onClick="approve_object(this)" class="delete_object unselectable button">'._('Approve prospect').' <i class="fa fa-check new_button link"></i></span>',
                   'reference' => '',
                   'type'      => 'operation'
               ),

               array(

                   'id'        => 'reject_prospect',
                   'class'     => 'operation',
                   'value'     => '',
                   'label'     => '<i class="fa fa-fw fa-lock button invisible" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                       .'"}\' onClick="reject_object(this)" class="delete_object unselectable button">'._('Reject prospect').' <i class="fa fa-times new_button  error link"></i></span>',
                   'reference' => '',
                   'type'      => 'operation'
               ),


               array(

                   'id'        => 'delete_prospect',
                   'class'     => 'operation',
                   'value'     => '',
                   'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                       .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._('Delete prospect').' <i class="fa fa-trash new_button link"></i></span>',
                   'reference' => '',
                   'type'      => 'operation'
               ),


           )

       );
    }





    $prospect_fields[]=  array(
            'label'      => _('Name, Ids'),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'              => 'Prospect_Company_Name',
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => htmlspecialchars($object->get('Prospect Company Name')),
                    'formatted_value' => $object->get('Company Name'),
                    'label'           => ucfirst($object->get_field_label('Prospect Company Name')),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Prospect_Main_Contact_Name',
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => htmlspecialchars($object->get('Prospect Main Contact Name')),
                    'formatted_value' => $object->get('Main Contact Name'),
                    'label'           => ucfirst($object->get_field_label('Prospect Main Contact Name')),
                    'required'        => true,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Prospect_Registration_Number',
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => $object->get('Prospect Registration Number'),
                    'formatted_value' => $object->get('Registration Number'),
                    'label'           => ucfirst(
                        $object->get_field_label('Prospect Registration Number')
                    ),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Prospect_Tax_Number',
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => $object->get('Prospect Tax Number'),
                    'formatted_value' => $object->get('Tax Number'),
                    'label'           => ucfirst(
                        $object->get_field_label('Prospect Tax Number')
                    ),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'render'          => ($object->get('Prospect Tax Number') == '' ? false : true),
                    'id'              => 'Prospect_Tax_Number_Valid',
                    'edit'            => ($edit ? 'option' : ''),
                    'options'         => $options_valid_tax_number,
                    'value'           => $object->get('Prospect Tax Number Valid'),
                    'formatted_value' => $object->get('Tax Number Valid'),
                    'label'           => ucfirst(
                        $object->get_field_label('Prospect Tax Number Valid')
                    ),
                )


            )
        );
    $prospect_fields[]=  array(
            'label'      => _('Email').' ('._('Web login').')',
            'show_title' => false,
            'fields'     => array(

                array(
                    'id'                => 'Prospect_Main_Plain_Email',
                    'edit'              => ($edit ? 'email' : ''),
                    'value'             => $object->get('Prospect Main Plain Email'),
                    'formatted_value'   => $object->get('Main Plain Email'),
                    'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                    'label'             => ucfirst($object->get_field_label('Prospect Main Plain Email')),
                    'invalid_msg'       => get_invalid_message('email'),
                    'required'          => true,
                    'type'              => 'value'
                ),
                array(
                    'id'                => 'new_email',
                    'render'            => false,
                    'edit'              => ($edit ? 'new_email' : ''),
                    'value'             => '',
                    'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                    'formatted_value'   => '',
                    'label'             => ucfirst($object->get_field_label('Prospect Other Email')),
                    'invalid_msg'       => get_invalid_message('email'),

                    'required' => false
                ),

                array(
                    'id'                => 'Prospect_Other_Email',
                    'render'            => false,
                    'edit'              => ($edit ? 'email' : ''),
                    'value'             => '',
                    'formatted_value'   => '',
                    'server_validation' => json_encode(
                        array('tipo' => 'check_for_duplicates')
                    ),
                    'label'             => ucfirst(
                            $object->get_field_label('Prospect Other Email')
                        ).' <i onClick="set_this_as_main(this)" title="'._(
                            'Set as main email'
                        ).'" class="far fa-star very_discreet button"></i>',
                    'invalid_msg'       => get_invalid_message('email'),
                    'required'          => false
                ),

                array(
                    //  'render'    => ($object->get('Prospect Main Plain Email') == '' ? false : true),
                    'render'    => false,
                    'id'        => 'show_new_email',
                    'class'     => 'new',
                    'value'     => '',
                    'label'     => _('Add email').' <i class="fa fa-plus new_button button"></i>',
                    'reference' => ''
                ),
                array(
                    'id'              => 'Prospect_Web_Login_Password',
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => $object->get('Prospect Web Login Password'),
                    'formatted_value' => $object->get('Web Login Password'),
                    'label'           => _('Password'),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => true,
                    'type'            => ''
                ),

            )
        );

        $prospect_fields[]= array(
            'label'      => _('Contact'),
            'show_title' => false,
            'fields'     => array(

                array(
                    'id'              => 'Prospect_Main_Plain_Mobile',
                    'edit'            => ($edit ? 'telephone' : ''),
                    'value'           => $object->get('Prospect Main Plain Mobile'),
                    'formatted_value' => $object->get('Main XHTML Mobile'),
                    'label'           => ucfirst(
                            $object->get_field_label('Prospect Main Plain Mobile')
                        ).($object->get('Prospect Main Plain Mobile') != '' ? ($object->get('Prospect Preferred Contact Number') == 'Mobile'
                            ? ' <i  title="'._('Main contact number').'" class="fa fa-star button discreet"></i>'
                            : ' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="fal fa-star discreet button"></i>') : ''),
                    'invalid_msg'     => get_invalid_message('telephone'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(


                    'id'              => 'Prospect_Main_Plain_Telephone',
                    'edit'            => ($edit ? 'telephone' : ''),
                    'value'           => $object->get(
                        'Prospect Main Plain Telephone'
                    ),
                    'formatted_value' => $object->get('Main XHTML Telephone'),
                    'label'           => ucfirst(
                            $object->get_field_label('Prospect Main Plain Telephone')
                        ).($object->get('Prospect Main Plain Telephone') != '' ? ($object->get('Prospect Preferred Contact Number') == 'Telephone'
                            ? ' <i  title="'._('Main contact number').'" class="fa fa-star button discreet"></i>'
                            : ' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="fal fa-star discreet button"></i>') : ''),
                    'invalid_msg'     => get_invalid_message('telephone'),
                    'required'        => false

                ),
                array(
                    'id'              => 'new_telephone',
                    'render'          => false,
                    'edit'            => ($edit ? 'new_telephone' : ''),
                    'value'           => '',
                    'formatted_value' => '',
                    'label'           => ucfirst($object->get_field_label('Prospect Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="far fa-star very_discreet button"></i>',
                    'required'        => false
                ),

                array(
                    'id'              => 'Prospect_Other_Telephone',
                    'render'          => false,
                    'edit'            => ($edit ? 'telephone' : ''),
                    'value'           => '',
                    'formatted_value' => '',
                    'label'           => ucfirst($object->get_field_label('Prospect Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="far fa-star very_discreet button"></i>',
                    'required'        => false
                ),

                array(
                    'render'    => ($object->get('Prospect Main Plain Telephone') == '' ? false : true),
                    'id'        => 'show_new_telephone',
                    'class'     => 'new',
                    'value'     => '',
                    'label'     => _('Add telephone').' <i class="fa fa-plus new_button button"></i>',
                    'required'  => false,
                    'reference' => ''
                ),

                array(
                    'id'              => 'Prospect_Main_Plain_FAX',
                    'edit'            => ($edit ? 'telephone' : ''),
                    'value'           => $object->get('Prospect Main Plain FAX'),
                    'formatted_value' => $object->get('Main Plain FAX'),
                    'label'           => ucfirst($object->get_field_label('Prospect Main Plain FAX')),
                    'invalid_msg'     => get_invalid_message('telephone'),
                    'required'        => false,
                    'type'            => 'value'
                ),

                array(
                    'id'                => 'Prospect_Website',
                    'edit'              => ($edit ? 'string' : ''),
                    'value'             => $object->get('Prospect Website'),
                    'formatted_value'   => $object->get('Website'),
                    'label'             => ucfirst($object->get_field_label('Prospect Website')),
                    'invalid_msg'       => get_invalid_message('string'),
                    'required'          => false,
                    'type'              => 'value'
                ),

            )
        );

        $prospect_fields[]= array(
            'label'      => _('Address'),
            'show_title' => false,
            'fields'     => array(


                array(
                    'id'              => 'Prospect_Contact_Address',
                    'edit'            => ($edit ? 'address' : ''),
                    'render'          => ($object->get('Prospect Billing Address Link') == 'Contact' ? false : true),
                    'countries'       => $countries,
                    'value'           => htmlspecialchars($object->get('Prospect Contact Address')),
                    'formatted_value' => $object->get('Contact Address'),
                    'label'           => ucfirst($object->get_field_label('Prospect Contact Address')),
                    'invalid_msg'     => get_invalid_message('address'),
                    'required'        => false,
                    'type'            => 'valuex'

                ),


                array(
                    'id'              => 'Prospect_Invoice_Address',
                    'edit'            => ($edit ? 'address' : ''),
                    'countries'       => $countries,
                    'value'           => htmlspecialchars($object->get('Prospect Invoice Address')),
                    'formatted_value' => $object->get('Invoice Address'),
                    'label'           => ucfirst($object->get_field_label('Prospect Invoice Address')),
                    'required'        => false
                ),


                array(
                    'id'              => 'Prospect_Delivery_Address_Link',
                    'edit'            => ($edit ? 'option' : ''),
                    'value'           => htmlspecialchars($object->get('Prospect Delivery Address Link')),
                    'formatted_value' => $object->get('Delivery Address Link'),
                    'label'           => ucfirst($object->get_field_label('Prospect Delivery Address Link')),
                    'options'         => $options_delivery_address_link,
                    'required'        => true
                ),

                array(
                    'id'              => 'Prospect_Delivery_Address',
                    'edit'            => ($edit ? 'address' : ''),
                    'render'          => ($object->get('Prospect Delivery Address Link') != 'None' ? false : true),
                    'countries'       => $countries,
                    'value'           => htmlspecialchars($object->get('Prospect Delivery Address')),
                    'formatted_value' => $object->get('Delivery Address'),
                    'label'           => ucfirst($object->get_field_label('Prospect Delivery Address')),
                    'invalid_msg'     => get_invalid_message('address'),
                    'required'        => false
                ),
                array(
                    'id'              => 'Prospect_Other_Delivery_Address',
                    'render'          => false,
                    'edit'            => ($edit ? 'address_to_clone' : ''),
                    'countries'       => $countries,
                    'field_type'      => 'other_delivery_address',
                    'value'           => '',
                    'formatted_value' => '',
                    'invalid_msg'     => get_invalid_message('address'),
                    'label'           => '',
                    'required'        => false
                ),

                array(
                    'id'              => 'new_delivery_address',
                    'render'          => false,
                    'edit'            => ($edit ? 'new_delivery_address' : ''),
                    'countries'       => $countries,
                    'value'           => '',
                    'formatted_value' => '',
                    'label'           => _('New delivery address'),
                    'required'        => false
                ),
                array(
                    'id'     => 'show_new_delivery_address',
                    'render' => ($object->get('Prospect Delivery Address Link') != 'None' ? false : true),


                    'class'     => 'new',
                    'value'     => '',
                    'label'     => _('Add delivery address').' <i class="fa fa-plus new_button button"></i>',
                    'reference' => ''
                ),

            )
        );

        $prospect_fields[]= array(
            'label'      => _('Marketing'),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'              => 'Prospect_Subscriptions',
                    'edit'            => 'no_icon',
                    'value'           => $object->get('Prospect Subscriptions'),
                    'formatted_value' => '<span class="button" onclick="toggle_subscription(this)"  field="Prospect_Send_Newsletter"  style="margin-right:40px"><i class=" fa fa-fw '.($object->get('Prospect Send Newsletter')=='Yes'?'fa-toggle-on':'fa-toggle-off').'" aria-hidden="true"></i> <span class="'.($object->get('Prospect Send Newsletter')=='No'?'discreet':'').'">'._('Newsletter').'</span></span>'.'<span onclick="toggle_subscription(this)"  field="Prospect_Send_Email_Marketing" class="button" style="margin-right:40px"><i class=" fa fa-fw '.($object->get('Prospect Send Email Marketing')=='Yes'?'fa-toggle-on':'fa-toggle-off').'" aria-hidden="true"></i> <span class="'.($object->get('Prospect Send Email Marketing')=='No'?'discreet':'').'">'._('Marketing emails').'</span></span>'.'<span onclick="toggle_subscription(this)"  field="Prospect_Send_Postal_Marketing" class="button" style="margin-right:40px"><i class=" fa fa-fw '.($object->get('Prospect Send Postal Marketing')=='Yes'?'fa-toggle-on':'fa-toggle-off').'" aria-hidden="true"></i> <span class="'.($object->get('Prospect Send Postal Marketing')=='No'?'discreet':'').'">'._('Postal marketing').'</span></span>',
                    'label'           => _('Subscriptions'),
                    'required'        => false,
                    'type'            => 'value'
                ),



            )
        );

        $prospect_fields[]= array(
            'label'      => _('Operations'),
            'show_title' => true,
            'class'      => 'edit_fields',
            'fields'     => array(


                array(

                    'id'        => 'delete_prospect',
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                        .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._('Delete prospect').' <i class="fa fa-trash new_button link"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),

            )

        );




    $other_emails = $object->get_other_emails_data();
    if (count($other_emails) > 0) {
        $other_emails_fields = array();
        foreach ($other_emails as $other_email_data_key => $other_email_data) {
            $other_emails_fields[] = array(
                'id'                => 'Prospect_Other_Email_'.$other_email_data_key,
                'edit'              => 'email',
                'value'             => $other_email_data['email'],
                'formatted_value'   => $other_email_data['email'],
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'label'             => ucfirst(
                        $object->get_field_label('Prospect Other Email')
                    ).' <i onClick="set_this_as_main(this)" title="'._(
                        'Set as main email'
                    ).'" class="far fa-star very_discreet button"></i>',
                'required'          => false,
                'type'              => 'value'
            );
        }
        array_splice($prospect_fields[1]['fields'], 1, 0, $other_emails_fields);
    }

    $other_telephones = $object->get_other_telephones_data();
    if (count($other_telephones) > 0) {
        $other_telephones_fields = array();
        foreach (
            $other_telephones as $other_telephone_data_key => $other_telephone_data
        ) {
            $other_telephones_fields[] = array(
                'id'              => 'Prospect_Other_Telephone_'.$other_telephone_data_key,
                'edit'            => 'telephone',
                'value'           => $other_telephone_data['telephone'],
                'formatted_value' => $other_telephone_data['formatted_telephone'],
                'label'           => ucfirst(
                        $object->get_field_label('Prospect Other Telephone')
                    ).' <i onClick="set_this_as_main(this)" title="'._(
                        'Set as main telephone'
                    ).'" class="far fa-star very_discreet button"></i>',
                'required'        => false
            );
        }
        array_splice($prospect_fields[2]['fields'], 2, 0, $other_telephones_fields);
    }

    $other_delivery_addresses_fields = array();


    $other_delivery_addresses = $object->get_other_delivery_addresses_data();

    $smarty->assign('other_delivery_addresses', $other_delivery_addresses);

    $number_other_delivery_address = count($other_delivery_addresses);

    if ($number_other_delivery_address > 0) {

        foreach ($other_delivery_addresses as $other_delivery_address_key => $other_delivery_address) {

            //   addresses ready to be edited from  $other_delivery_addresses_fields_directory

            $other_delivery_addresses_fields[] = array(
                'id'              => 'Prospect_Other_Delivery_Address_'.$other_delivery_address_key,
                'edit'            => 'address',
                'countries'       => $countries,
                'render'          => false,
                'value'           => htmlspecialchars($other_delivery_address['value']),
                'field_type'      => 'other_delivery_address',
                'formatted_value' => $other_delivery_address['formatted_value'],
                'invalid_msg'     => get_invalid_message('address'),
                'label'           => '',
                'required'        => false
            );
        }


    }

    //print_r($other_delivery_addresses);

    $other_delivery_addresses_fields_directory = $smarty->fetch('delivery_addresses_directory.tpl');

    $other_delivery_addresses_fields[] = array(
        'id'     => 'other_delivery_addresses',
        'render' => ($number_other_delivery_address > 0 ? true : false),
        'class'  => 'directory',

        'value'           => '',
        'label'           => _('Other delivery addresses'),
        'formatted_value' => $other_delivery_addresses_fields_directory,
        'reference'       => ''
    );

    array_splice($prospect_fields[3]['fields'], 3, 0, $other_delivery_addresses_fields);

}


?>
