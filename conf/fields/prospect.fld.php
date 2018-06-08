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
                    'id'              => 'Prospect_Website',
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => $object->get('Prospect Website'),
                    'formatted_value' => $object->get('Website'),
                    'label'           => ucfirst($object->get_field_label('Prospect Website')),
                    'invalid_msg'     => get_invalid_message('string'),
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


} else {


    $prospect_fields = array();


    if ($object->get('Prospect Type by Activity') == 'ToApprove') {
        $prospect_fields[] = array(
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


    $prospect_fields[] = array(
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
    );


    $prospect_fields[] = array(
        'label'      => _('Contact'),
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
                'id'              => 'Prospect_Main_Plain_Mobile',
                'edit'            => ($edit ? 'telephone' : ''),
                'value'           => $object->get('Prospect Main Plain Mobile'),
                'formatted_value' => $object->get('Main XHTML Mobile'),
                'label'           => ucfirst(
                        $object->get_field_label('Prospect Main Plain Mobile')
                    ).($object->get('Prospect Main Plain Mobile') != '' ? ($object->get('Prospect Preferred Contact Number') == 'Mobile' ? ' <i  title="'._('Main contact number').'" class="fa fa-star button discreet"></i>'
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
                    ).($object->get('Prospect Main Plain Telephone') != '' ? ($object->get('Prospect Preferred Contact Number') == 'Telephone' ? ' <i  title="'._('Main contact number').'" class="fa fa-star button discreet"></i>'
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
                'id'              => 'Prospect_Website',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => $object->get('Prospect Website'),
                'formatted_value' => $object->get('Website'),
                'label'           => ucfirst($object->get_field_label('Prospect Website')),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
            ),

        )
    );

    $prospect_fields[] = array(
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


        )
    );

    $with_operations = false;

    if ($object->get('Prospect Status') == 'NoContacted') {
        $with_operations = true;
    }


    $prospect_fields[] = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'edit_fields '.($with_operations ? '' : 'hide'),

        'fields' => array(

            array(

                'id'        => 'activate_prospect',
                'class'     => 'operation',
                'render'    => (($object->get('Prospect Status') == 'NotInterested' and $object->get('Prospect Spam')=='No'   )? true : false),
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                    .'"}\' onClick="activate_object(this)" class="delete_object disabled">'._('Undo set as not interested').' <i class="fa fa-undo new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),

            array(

                'id'        => 'delete_prospect',
                'class'     => 'operation',
                'render'    => ($object->get('Prospect Status') == 'NoContacted' ? true : false),
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                    .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._('Delete prospect').' <i class="fa fa-trash new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),

        )

    );


}


?>
