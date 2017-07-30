<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 July 2017 at 11:01:01 CEST, Trnava, Slovakia

 Copyright (c) 2017, Inikoo

 Version 3.0
*/




if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$can_update_code=true;


$object_fields = array();


$object_fields[] = array(
    'label'      => _('Id'),
    'show_title' => true,
    'fields'     => array(




        array(
            'id'     => 'Payment_Account_Code',
            'render' => ($can_update_code  ? true : false),
            'edit'   => ($edit ? 'string' : ''),
            'value'           => htmlspecialchars($object->get('Payment Account Code')),
            'formatted_value' => $object->get('Code'),
            'label'           => ucfirst($object->get_field_label('Payment Account Code')),
            'required'        => true,
            'type'            => 'value' ,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),



        ),
        array(
            'id'   => 'Payment_Account_Name',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars($object->get('Payment Account Name')),
            'formatted_value' => $object->get('Payment Account Name'),
            'label'           => ucfirst($object->get_field_label('Payment Account Name')),
            'required'        => true,
            'type'            => 'value' ,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),



        ),


    )
);


if (in_array(
    $object->get('Payment Account Block'), array(
                                     'Bank'
                                 )
)) {


    $object_fields[] = array(
        'label'      => _('Bank details'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'     => 'Payment_Account_Recipient_Holder',
                'render' => ($can_update_code  ? true : false),
                'edit'   => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Payment Account Recipient Holder')),
                'formatted_value' => $object->get('Payment Account Recipient Holder'),
                'label'           => ucfirst($object->get_field_label('Payment Account Recipient Holder')),
                'required'        => true,
                'type'            => 'value' ,
            ),
            array(
                'id'     => 'Payment_Account_Recipient_Bank_Account_Number',
                'render' => ($can_update_code  ? true : false),
                'edit'   => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Payment Account Recipient Bank Account Number')),
                'formatted_value' => $object->get('Payment Account Recipient Bank Account Number'),
                'label'           => ucfirst($object->get_field_label('Payment Account Recipient Bank Account Number')),
                'required'        => true,
                'type'            => 'value' ,
            ),
            array(
                'id'     => 'Payment_Account_Recipient_Bank_IBAN',
                'render' => ($can_update_code  ? true : false),
                'edit'   => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Payment Account Recipient Bank IBAN')),
                'formatted_value' => $object->get('Payment Account Recipient Bank IBAN'),
                'label'           => ucfirst($object->get_field_label('Payment Account Recipient Bank IBAN')),
                'required'        => true,
                'type'            => 'value' ,
            ),
            array(
                'id'     => 'Payment_Account_Recipient_Bank_Name',
                'render' => ($can_update_code  ? true : false),
                'edit'   => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Payment Account Recipient Bank Name')),
                'formatted_value' => $object->get('Payment Account Recipient Bank Name'),
                'label'           => ucfirst($object->get_field_label('Payment Account Recipient Bank Name')),
                'required'        => true,
                'type'            => 'value' ,
            ),
            array(
                'id'     => 'Payment_Account_Recipient_Bank_Code',
                'render' => ($can_update_code  ? true : false),
                'edit'   => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Payment Account Recipient Bank Code')),
                'formatted_value' => $object->get('Payment Account Recipient Bank Code'),
                'label'           => ucfirst($object->get_field_label('Payment Account Recipient Bank Code')),
                'required'        => false,
                'type'            => 'value' ,
            ),

            array(
                'id'     => 'Payment_Account_Recipient_Bank_Swift',
                'render' => ($can_update_code  ? true : false),
                'edit'   => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Payment Account Recipient Bank Swift')),
                'formatted_value' => $object->get('Payment Account Recipient Bank Swift'),
                'label'           => ucfirst($object->get_field_label('Payment Account Recipient Bank Swift')),
                'required'        => true,
                'type'            => 'value' ,
            ),

            array(
                'id'     => 'Payment_Account_Recipient_Address',
                'render' => ($can_update_code  ? true : false),
                'edit'   => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Payment Account Recipient Address')),
                'formatted_value' => $object->get('Payment Account Recipient Address'),
                'label'           => ucfirst($object->get_field_label('Payment Account Recipient Address')),
                'required'        => false,
                'type'            => 'value' ,
            ),


        )
    );
}


if (in_array($object->get('Payment Account Scope'), array('Contact'))) {

    $object_fields[] = array(
        'label'      => _('Contact'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'        => ($edit ? 'string' : ''),
                'id'          => 'Store_Company_Name',
                'value'       => $object->get('Store Company Name'),
                'label'       => _('Company Name'),
                'invalid_msg' => get_invalid_message('string'),
                'required'    => false,
                'type'        => 'value'
            ),
            array(
                'edit'        => ($edit ? 'string' : ''),
                'id'          => 'Store_VAT_Number',
                'value'       => $object->get('Store VAT Number'),
                'label'       => _('VAT Number'),
                'invalid_msg' => get_invalid_message('string'),
                'required'    => false,
                'type'        => 'value'
            ),
            array(
                'edit'        => ($edit ? 'string' : ''),
                'id'          => 'Store_Company_Number',
                'value'       => $object->get('Store Company Number'),
                'label'       => _('Company Number'),
                'invalid_msg' => get_invalid_message('string'),
                'required'    => false,
                'type'        => 'value'
            ),
            array(
                'edit'        => ($edit ? 'email' : ''),
                'id'          => 'Store_Email',
                'value'       => $object->get('Store Email'),
                'label'       => _('Email'),
                'invalid_msg' => get_invalid_message('email'),
                'required'    => false,
                'type'        => 'value'
            ),
            array(
                'edit'            => ($edit ? 'string' : ''),
                'id'              => 'Store_Telephone',
                'value'           => $object->get('Store Telephone'),
                'formatted_value' => $object->get('Telephone'),
                'label'           => _('Telephone'),
                'invalid_msg'     => get_invalid_message('telephone'),
                'required'        => false,
                'type'            => 'value'
            ),

            array(
                'edit'            => ($edit ? 'textarea' : ''),
                'id'              => 'Store_Address',
                'value'           => $object->get('Store Address'),
                'formatted_value' => $object->get('Address'),
                'label'           => _('Address'),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
            ),

            array(
                'edit'            => ($edit ? 'textarea' : ''),
                'id'              => 'Store_Google_Map_URL',
                'value'           => $object->get('Store Google Map URL'),
                'formatted_value' => $object->get('Google Map URL'),
                'label'           => _('Google Map URL'),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
            ),


        )
    );


}


if (in_array($object->get('Payment Account Scope'), array('Category Categories'))) {

    $template_options = array(
        'categories_classic_showcase' => _('Responsive grid'),
        'categories_showcase'         => _('Fixed grid')
    );


    $object_fields[] = array(
        'label'      => _('Template'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit' => ($edit ? 'option' : ''),

                'id'              => 'Payment_Account_Template_Filename',
                'value'           => $object->get('Payment Account Template Filename'),
                'formatted_value' => $object->get('Template Filename'),
                'options'         => $template_options,
                'label'           => _('Template'),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => true,
                'type'            => 'value'
            ),


        )
    );


}




$operations      = array(
    'label'      => _('Operations'),
    'show_title' => true,
    'class'      => 'operations',
    'fields'     => array(


        array(
            'id'        => 'reset_webpage',
            'class'     => 'operation',
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "webpage", "key":"'.$object->id
                .'"}\' onClick="reset_object(this)" class="delete_object disabled ">'._("Reset webpage").' <i class="fa fa-recycle  "></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),


    )

);

if(!$new) {

  //  $object_fields[] = $operations;
}

/*

if (in_array(
    $object->get('Payment Account Scope'), array(
                                     'Category Categories',
                                     'Product',
                                     'Category Products'
                                 )
)) {

    if (!$new and $can_delete) {
        $operations = array(
            'label'      => _('Operations'),
            'show_title' => true,
            'class'      => 'operations',
            'fields'     => array(

                array(
                    'id'        => 'delete_website',
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'
                        .$object->get_object_name().'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete webpage")
                        .' <i class="fa fa-trash new_button link"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),


            )

        );

        $object_fields[] = $operations;
    }

} else {

    $operations      = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(


            array(
                'id'        => 'reset_webpage',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "webpage", "key":"'
                    .$object->id.'"}\' onClick="reset_object(this)" class="delete_object disabled ">'._("Reset webpage").' <i class="fa fa-recycle  "></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );
    $object_fields[] = $operations;


}

*/

?>
