<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 April 2016 at 01:01:23 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/
/** @var \Customer $object */
/** @var \User $user */
/** @var \PDO $db */
/** @var \Account $account */

/** @var \Smarty $smarty */

include_once 'utils/static_data.php';

$store = get_object('Store', $object->get('Customer Store Key'));
$smarty->assign('customer', $object);

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


if ($user->can_supervisor('customers')) {
    $can_supervisor_customer_services = true;
} else {
    $can_supervisor_customer_services = false;

}


$options_yes_no = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);

$options_sales_representative = array();

if ($object->get('Customer Sales Representative Key')) {
    $options_sales_representative[0] = array(
        'label'    => _('Remove account manager'),
        'label2'   => _('Remove account manager'),
        'selected' => false
    );
}
$sql =
    "SELECT `User Alias`,U.`User Key`,`User Handle` from `User Dimension` U LEFT JOIN `User Group User Bridge` B ON (U.`User Key`=B.`User Key`) WHERE  `User Type` in  ('Staff','Contractor')  and `User Group Key`=2     and `User Active`='Yes'  group by U.`User Key`  ";


foreach ($db->query($sql) as $row) {
    $options_sales_representative[$row['User Key']] = array(
        'label'    => $row['User Alias'].' ('.$row['User Handle'].')',
        'label2'   => $row['User Alias'].' ('.sprintf('%03d', $row['User Key']).')',
        'selected' => false
    );
}


if (isset($options['new']) and $options['new']) {
    $new   = true;
    $store = get_object('Store', $options['store_key']);

} else {
    $new   = false;
    $store = get_object('Store', $object->get('Store Key'));

}

$edit = true;


$_edit = ($store->get('Store Type') != 'External' ? true : false);


if ($user->can_supervisor('accounting')) {
    $can_supervisor_accounting = true;
    $customer_level_type_field = array(
        'id'     => 'Customer_Level_Type',
        'edit'   => 'no_icon',
        'render' => ($store->get('Store Type') == 'Dropshipping' ? false : true),

        'value'           => $object->get('Customer Level Type'),
        'formatted_value' => '<span class="button" onclick="toggle_customer_marketing_subscription(this)"  field="Customer_Level_Type_Partner"  style="margin-right:40px"><i class=" fa fa-fw '.($object->get('Customer Level Type') == 'Partner' ? 'fa-toggle-on' : 'fa-toggle-off')
            .'" aria-hidden="true"></i> <span class="'.($object->get('Customer Level Type') == 'Partner' ? 'discreet' : '').'">'._('Partner').'</span></span>',
        'label'           => _('Type'),
        'required'        => false,
        'type'            => 'value'
    );

} else {
    $can_supervisor_accounting = false;


    $customer_level_type_field = array(
        'id'   => 'Customer_Level_Type',
        'edit' => ($can_supervisor_accounting ? 'string' : ''),

        'value'           => $object->get('Customer Level Type'),
        'formatted_value' => $object->get('Level Type'),
        'options'         => $options_sales_representative,
        'label'           => _('Type'),
        'required'        => false,
        'type'            => ''

    );
}

$customer_category_fields = [];

$customer_category_fields[]=$customer_level_type_field;

$sql  = "select `Category Key`,`Category Code`,`Category Label` from `Category Dimension` where `Category Scope`='Customer' and  `Category Branch Type`='Node' and `Category Store Key`=? and `Category Children`>0";
$stmt = $db->prepare($sql);
$stmt->execute(
    array(
        $store->id
    )
);
while ($row = $stmt->fetch()) {


    $options = array();
    $options[0]=_('Not assigned');
    $sql     = 'SELECT `Category Key`,`Category Code`  FROM `Category Dimension` WHERE `Category Parent Key`=? ';
    $stmt2   = $db->prepare($sql);
    $stmt2->execute(
        array(
            $row['Category Key']
        )
    );
    while ($row2 = $stmt2->fetch()) {
        $options[$row2['Category Key']] = $row2['Category Code'];
    }

    $answer_key     = 0;
    $answer_label   = _('Not assigned');
    $category       = get_object('Category', $row['Category Key']);
    $current_values = $category->get_children_with_subject($object->id);
    if (count($current_values) > 0) {
        $current_value = array_pop($current_values);
        $answer_key    = $current_value[0];
        $answer_label  = $current_value[1];
    }


    $customer_category_fields[] = array(
        'id'              => 'Customer_Category_'.$row['Category Key'],
        'edit'            => ($edit ? 'option' : ''),
        'options'         => $options,
        'value'           => $answer_key,
        'formatted_value' => $answer_label,
        'label'           => $row['Category Code'],
        'type'            => 'value'


    );


}


if ($new) {

    $customer_fields = array(
        array(
            'label'      => _('Name, Ids'),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'              => 'Customer_Company_Name',
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => htmlspecialchars($object->get('Customer Company Name')),
                    'formatted_value' => $object->get('Company Name'),
                    'label'           => ucfirst($object->get_field_label('Customer Company Name')),
                    'required'        => false,
                    'type'            => 'value'
                ),

                array(

                    'id'              => 'Customer_Main_Contact_Name',
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => htmlspecialchars($object->get('Customer Main Contact Name')),
                    'formatted_value' => $object->get('Main Contact Name'),
                    'label'           => ucfirst($object->get_field_label('Customer Main Contact Name')),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Customer_Registration_Number',
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => $object->get('Customer Registration Number'),
                    'formatted_value' => $object->get('Registration Number'),
                    'label'           => ucfirst(
                        $object->get_field_label('Customer Registration Number')
                    ),
                    'required'        => false,
                    'type'            => 'value'
                ),

                array(
                    'id'              => 'Customer_EORI',
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => $object->get('Customer EORI'),
                    'formatted_value' => $object->get('EORI'),
                    'label'           => ucfirst($object->get_field_label('EORI number')),
                    'required'        => false,
                    'type'            => 'value'

                ),
                array(
                    'id'              => 'Customer_Tax_Number',
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => $object->get('Customer Tax Number'),
                    'formatted_value' => $object->get('Tax Number'),
                    'label'           => ucfirst($object->get_field_label('Customer Tax Number')),
                    'required'        => false,
                    'type'            => 'value'

                ),
                array(
                    'render'          => !($object->get('Customer Tax Number') == ''),
                    'id'              => 'Customer_Tax_Number_Valid',
                    'edit'            => ($edit ? 'option' : ''),
                    'options'         => $options_valid_tax_number,
                    'value'           => $object->get('Customer Tax Number Valid'),
                    'formatted_value' => $object->get('Tax Number Valid'),
                    'label'           => ucfirst(
                        $object->get_field_label('Customer Tax Number Valid')
                    ),
                ),
                array(
                    'id'              => 'Customer_Recargo_Equivalencia',
                    'edit'            => ($edit ? 'option' : ''),
                    'render'          => $account->get('Account Country Code') == 'ESP',
                    'options'         => $options_yes_no,
                    'value'           => 'No',
                    'formatted_value' => _('No'),
                    'label'           => _('Recargo de equivalencia').' <i class="fa fa-registered recargo_equivalencia"></i>',
                    'type'            => ''
                ),

            )
        ),
        array(
            'label'      => _('Email'),
            'show_title' => false,
            'fields'     => array(

                array(
                    'id'                => 'Customer_Main_Plain_Email',
                    'edit'              => ($_edit ? 'email' : ''),
                    'value'             => $object->get('Customer Main Plain Email'),
                    'formatted_value'   => $object->get('Main Plain Email'),
                    'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                    'label'             => ucfirst($object->get_field_label('Customer Main Plain Email')),
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
                    'id'              => 'Customer_Main_Plain_Mobile',
                    'edit'            => ($_edit ? 'telephone' : ''),
                    'value'           => $object->get('Customer Main Plain Mobile'),
                    'formatted_value' => $object->get('Customer Main XHTML Mobile'),
                    'label'           => ucfirst(
                            $object->get_field_label('Customer Main Plain Mobile')
                        ).($object->get('Customer Main Plain Mobile') != '' ? ($object->get('Customer Preferred Contact Number') == 'Mobile'
                            ? ''
                            : ' <i onClick="set_this_as_main(this)" title="'._(
                                'Set as main contact number'
                            ).'" class="far fa-star discreet button"></i>') : ''),
                    'invalid_msg'     => get_invalid_message('telephone'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(


                    'id'              => 'Customer_Main_Plain_Telephone',
                    'edit'            => ($_edit ? 'telephone' : ''),
                    'value'           => $object->get('Customer Main Plain Telephone'),
                    'formatted_value' => $object->get('Main Plain Telephone'),
                    'label'           => ucfirst(
                            $object->get_field_label(
                                'Customer Main Plain Telephone'
                            )
                        ).($object->get('Customer Main Plain Telephone') != '' ? ($object->get('Customer Preferred Contact Number') == 'Telephone'
                            ? ''
                            : ' <i onClick="set_this_as_main(this)" title="'._(
                                'Set as main contact number'
                            ).'" class="far fa-star discreet button"></i>') : ''),
                    'invalid_msg'     => get_invalid_message('telephone'),
                    'required'        => false,
                    'type'            => 'value'

                ),


                array(
                    'id'              => 'Customer_Website',
                    'edit'            => ($_edit ? 'string' : ''),
                    'value'           => $object->get('Customer Website'),
                    'formatted_value' => $object->get('Website'),
                    'label'           => ucfirst($object->get_field_label('Customer Website')),
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
                    'id'              => 'Customer_Contact_Address',
                    'edit'            => ($_edit ? 'address' : ''),
                    'countries'       => $countries,
                    'value'           => htmlspecialchars($object->get('Customer Contact Address')),
                    'formatted_value' => $object->get('Contact Address'),
                    'label'           => ucfirst($object->get_field_label('Customer Contact Address')),
                    'invalid_msg'     => get_invalid_message('address'),
                    'required'        => false,
                    'type'            => 'value'

                ),


                array(
                    'id'              => 'Customer_Invoice_Address',
                    'edit'            => ($_edit ? 'address' : ''),
                    'countries'       => $countries,
                    'value'           => htmlspecialchars($object->get('Customer Invoice Address')),
                    'formatted_value' => $object->get('Invoice Address'),
                    'label'           => ucfirst($object->get_field_label('Customer Invoice Address')),
                    'required'        => false
                ),
                array(
                    'id'              => 'Customer_Delivery_Address',
                    'edit'            => ($_edit and $store->get('Store Type') != 'Dropshipping' ? 'address' : ''),
                    'countries'       => $countries,
                    'value'           => htmlspecialchars($object->get('Customer Delivery Address')),
                    'formatted_value' => $object->get('Delivery Address'),
                    'label'           => ucfirst($object->get_field_label('Customer Delivery Address')),
                    'invalid_msg'     => get_invalid_message('address'),
                    'required'        => false
                ),


            )
        ),

        array(
            'label'      => _('Marketing'),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'              => 'Customer_Subscriptions',
                    'edit'            => 'no_icon',
                    'value'           => $object->get('Customer Subscriptions'),
                    'formatted_value' => '<span id="Customer_Send_Newsletter_field" class="button value valid" onclick="toggle_switch(this)" field_type="subscription" field="Customer_Send_Newsletter"  style="margin-right:40px"><i class=" fa fa-fw fa-toggle-on" aria-hidden="true"></i> <span >'
                        ._('Newsletter').'</span></span>'
                        .'<span id="Customer_Send_Email_Marketing_field" onclick="toggle_switch(this)"  field_type="subscription"  field="Customer_Send_Email_Marketing" class="button value valid" style="margin-right:40px"><i class=" fa fa-fw fa-toggle-on" aria-hidden="true"></i> <span >'
                        ._('Marketing emails').'</span></span>'
                        .'<span id="Customer_Send_Postal_Marketing_field" onclick="toggle_switch(this)"  field_type="subscription"  field="Customer_Send_Postal_Marketing" class="value valid button" style="margin-right:40px"><i class=" fa fa-fw fa-toggle-on" aria-hidden="true"></i> <span >'
                        ._('Postal marketing').'</span></span>',
                    'label'           => _('Subscriptions'),
                    'required'        => false,
                    'type'            => ''
                ),


            )
        ),

    );


} else {


    $customer_fields = array();


    if ($object->get('Customer Type by Activity') == 'ToApprove') {
        $customer_fields[] = array(
            'label'      => _('Approve customer'),
            'show_title' => true,
            'class'      => 'edit_fields',
            'fields'     => array(


                array(

                    'id'        => 'approve_customer',
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => '<i class="fa fa-fw fa-lock button invisible" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                        .'"}\' onClick="approve_object(this)" class="delete_object unselectable button">'._('Approve customer').' <i class="fa fa-check new_button link"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),

                array(

                    'id'        => 'reject_customer',
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => '<i class="fa fa-fw fa-lock button invisible" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                        .'"}\' onClick="reject_object(this)" class="delete_object unselectable button">'._('Reject customer').' <i class="fa fa-times new_button  error link"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),


                array(

                    'id'        => 'delete_customer',
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                        .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._('Delete customer').' <i class="far fa-trash-alt new_button link"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),


            )

        );
    }


    if ($object->get('Customer Type by Activity') == 'Rejected') {
        $customer_fields[] = array(
            'label'      => _('Approve customer'),
            'show_title' => true,
            'class'      => 'edit_fields',
            'fields'     => array(


                array(

                    'id'        => 'approve_customer',
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => '<i class="fa fa-fw fa-lock button invisible" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                        .'"}\' onClick="approve_object(this)" class="delete_object unselectable button">'._('Approve customer').' <i class="fa fa-check new_button link"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),


            )

        );
    }





    $customer_fields[] = array(
        'label'      => _('Name, Ids'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'              => 'Customer_Company_Name',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Customer Company Name')),
                'formatted_value' => $object->get('Company Name'),
                'label'           => ucfirst($object->get_field_label('Customer Company Name')),
                'required'        => ($object->get('Customer Main Contact Name') == '' ? true : false),
                'type'            => 'value'
            ),
            array(
                'id'              => 'Customer_Main_Contact_Name',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Customer Main Contact Name')),
                'formatted_value' => $object->get('Main Contact Name'),
                'label'           => ucfirst($object->get_field_label('Customer Main Contact Name')),
                'required'        => ($object->get('Customer Company Name') == '' ? true : false),
                'type'            => 'value'
            ),
            array(
                'id'              => 'Customer_Registration_Number',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => $object->get('Customer Registration Number'),
                'formatted_value' => $object->get('Registration Number'),
                'label'           => ucfirst(
                    $object->get_field_label('Customer Registration Number')
                ),
                'required'        => false,
                'type'            => 'value'
            ),
            array(
                'id'              => 'Customer_EORI',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => $object->get('Customer EORI'),
                'formatted_value' => $object->get('EORI'),
                'label'           => ucfirst($object->get_field_label('EORI number')),
                'required'        => false,
                'type'            => 'value'

            ),
            array(
                'id'              => 'Customer_Tax_Number',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => $object->get('Customer Tax Number'),
                'formatted_value' => $object->get('Tax Number'),
                'label'           => ucfirst(
                    $object->get_field_label('Customer Tax Number')
                ),
                'required'        => false,
                'type'            => 'value'
            ),
            array(
                'render'          => ($object->get('Customer Tax Number') == '' ? false : true),
                'id'              => 'Customer_Tax_Number_Valid',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_valid_tax_number,
                'value'           => $object->get('Customer Tax Number Valid'),
                'formatted_value' => $object->get('Tax Number Valid'),
                'label'           => ucfirst(
                    $object->get_field_label('Customer Tax Number Valid')
                ),
            ),
            array(
                'id'              => 'Customer_Recargo_Equivalencia',
                'edit'            => ($edit ? 'option' : ''),
                'render'          => ($account->get('Account Country Code') == 'ESP' ? true : false),
                'options'         => $options_yes_no,
                'value'           => $object->get('Customer Recargo Equivalencia'),
                'formatted_value' => $object->get('Recargo Equivalencia'),
                'label'           => _('Recargo de equivalencia').' <i class="fa fa-registered recargo_equivalencia"></i>',
                'type'            => ''
            ),


        )
    );
    $customer_fields[] = array(
        'label'      => _('Email').' ('._('Web login').')',
        'show_title' => false,
        'fields'     => array(

            array(
                'id'                => 'Customer_Main_Plain_Email',
                'edit'              => ($_edit ? 'email' : ''),
                'value'             => $object->get('Customer Main Plain Email'),
                'formatted_value'   => $object->get('Main Plain Email'),
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'label'             => ucfirst($object->get_field_label('Customer Main Plain Email')),
                'invalid_msg'       => get_invalid_message('email'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'new_email',
                'render'            => false,
                'edit'              => ($_edit ? 'new_email' : ''),
                'value'             => '',
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'formatted_value'   => '',
                'label'             => ucfirst($object->get_field_label('Customer Other Email')),
                'invalid_msg'       => get_invalid_message('email'),

                'required' => false
            ),

            array(
                'id'                => 'Customer_Other_Email',
                'render'            => false,
                'edit'              => ($_edit ? 'email' : ''),
                'value'             => '',
                'formatted_value'   => '',
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'label'             => ucfirst(
                        $object->get_field_label('Customer Other Email')
                    ).' <i onClick="set_this_as_main(this)" title="'._(
                        'Set as main email'
                    ).'" class="far fa-star very_discreet button"></i>',
                'invalid_msg'       => get_invalid_message('email'),
                'required'          => false
            ),

            array(
                //  'render'    => ($object->get('Customer Main Plain Email') == '' ? false : true),
                'render'    => false,
                'id'        => 'show_new_email',
                'class'     => 'new',
                'value'     => '',
                'label'     => _('Add email').' <i class="fa fa-plus new_button button"></i>',
                'reference' => ''
            ),
            array(
                'id'              => 'Customer_Web_Login_Password',
                'edit'            => ($_edit ? 'string' : ''),
                'value'           => $object->get('Customer Web Login Password'),
                'formatted_value' => $object->get('Web Login Password'),
                'label'           => _('Password'),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => true,
                'type'            => ''
            ),

        )
    );

    $customer_fields[] = array(
        'label'      => _('Contact'),
        'show_title' => false,
        'fields'     => array(

            array(
                'id'              => 'Customer_Main_Plain_Mobile',
                'edit'            => ($_edit ? 'telephone' : ''),
                'value'           => $object->get('Customer Main Plain Mobile'),
                'formatted_value' => $object->get('Main XHTML Mobile'),
                'label'           => ucfirst(
                        $object->get_field_label('Customer Main Plain Mobile')
                    ).($object->get('Customer Main Plain Mobile') != '' ? ($object->get('Customer Preferred Contact Number') == 'Mobile' ? ' <i  title="'._('Main contact number').'" class="fa yellow fa-star button "></i>'
                        : ' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="fal fa-star discreet button"></i>') : ''),
                'invalid_msg'     => get_invalid_message('telephone'),
                'required'        => false,
                'type'            => 'value'
            ),
            array(


                'id'              => 'Customer_Main_Plain_Telephone',
                'edit'            => ($_edit ? 'telephone' : ''),
                'value'           => $object->get(
                    'Customer Main Plain Telephone'
                ),
                'formatted_value' => $object->get('Main XHTML Telephone'),
                'label'           => ucfirst(
                        $object->get_field_label('Customer Main Plain Telephone')
                    ).($object->get('Customer Main Plain Telephone') != '' ? ($object->get('Customer Preferred Contact Number') == 'Telephone' ? ' <i  title="'._('Main contact number').'" class="fa fa-star button yellow"></i>'
                        : ' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="fal fa-star discreet button"></i>') : ''),
                'invalid_msg'     => get_invalid_message('telephone'),
                'required'        => false

            ),
            array(
                'id'              => 'new_telephone',
                'render'          => false,
                'edit'            => ($_edit ? 'new_telephone' : ''),
                'value'           => '',
                'formatted_value' => '',
                'label'           => ucfirst($object->get_field_label('Customer Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="far fa-star very_discreet button"></i>',
                'required'        => false
            ),

            array(
                'id'              => 'Customer_Other_Telephone',
                'render'          => false,
                'edit'            => ($_edit ? 'telephone' : ''),
                'value'           => '',
                'formatted_value' => '',
                'label'           => ucfirst($object->get_field_label('Customer Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="far fa-star very_discreet button"></i>',
                'required'        => false
            ),

            array(
                'render'    => ($object->get('Customer Main Plain Telephone') == '' ? false : true),
                'id'        => 'show_new_telephone',
                'class'     => 'new',
                'value'     => '',
                'label'     => _('Add telephone').' <i class="fa fa-plus new_button button"></i>',
                'required'  => false,
                'reference' => ''
            ),

            array(
                'id'              => 'Customer_Main_Plain_FAX',
                'render'          => ($object->get('Customer Main Plain FAX') != '' ? true : false),
                'edit'            => ($_edit ? 'telephone' : ''),
                'value'           => $object->get('Customer Main Plain FAX'),
                'formatted_value' => $object->get('Main Plain FAX'),
                'label'           => ucfirst($object->get_field_label('Customer Main Plain FAX')),
                'invalid_msg'     => get_invalid_message('telephone'),
                'required'        => false,
                'type'            => 'value'
            ),

            array(
                'id'              => 'Customer_Website',
                'edit'            => ($_edit ? 'string' : ''),
                'value'           => $object->get('Customer Website'),
                'formatted_value' => $object->get('Website'),
                'label'           => ucfirst($object->get_field_label('Customer Website')),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
            ),

        )
    );

    $customer_fields[] = array(
        'label'      => _('Address'),
        'show_title' => false,
        'fields'     => array(


            array(
                'id'              => 'Customer_Contact_Address',
                'edit'            => ($_edit ? 'address' : ''),
                'render'          => ($object->get('Customer Billing Address Link') == 'Contact' ? false : true),
                'countries'       => $countries,
                'value'           => htmlspecialchars($object->get('Customer Contact Address')),
                'formatted_value' => $object->get('Contact Address'),
                'label'           => ucfirst($object->get_field_label('Customer Contact Address')),
                'invalid_msg'     => get_invalid_message('address'),
                'required'        => false,

            ),


            array(
                'id'              => 'Customer_Invoice_Address',
                'edit'            => ($_edit ? 'address' : ''),
                'countries'       => $countries,
                'value'           => htmlspecialchars($object->get('Customer Invoice Address')),
                'formatted_value' => $object->get('Invoice Address'),
                'label'           => ucfirst($object->get_field_label('Customer Invoice Address')),
                'required'        => false
            ),


            array(
                'id'              => 'Customer_Delivery_Address_Link',
                'render'          => ($store->get('Store Type') == 'Dropshipping' ? false : true),
                'edit'            => (($_edit and $store->get('Store Type') != 'Dropshipping') ? 'option' : ''),
                'value'           => htmlspecialchars($object->get('Customer Delivery Address Link')),
                'formatted_value' => $object->get('Delivery Address Link'),
                'label'           => ucfirst($object->get_field_label('Customer Delivery Address Link')),
                'options'         => $options_delivery_address_link,
                'required'        => true
            ),

            array(
                'id'              => 'Customer_Delivery_Address',
                'edit'            => ($_edit ? 'address' : ''),
                'render'          => (($object->get('Customer Delivery Address Link') != 'None' or $store->get('Store Type') == 'Dropshipping') ? false : true),
                'countries'       => $countries,
                'value'           => htmlspecialchars($object->get('Customer Delivery Address')),
                'formatted_value' => $object->get('Delivery Address'),
                'label'           => ucfirst($object->get_field_label('Customer Delivery Address')),
                'invalid_msg'     => get_invalid_message('address'),
                'required'        => false
            ),
            array(
                'id'              => 'Customer_Other_Delivery_Address',
                'render'          => false,
                'edit'            => ($_edit ? 'address_to_clone' : ''),
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
                'edit'            => ($_edit ? 'new_delivery_address' : ''),
                'countries'       => $countries,
                'value'           => '',
                'formatted_value' => '',
                'label'           => _('New delivery address'),
                'required'        => false
            ),
            array(
                'id'     => 'show_new_delivery_address',
                'render' => ($object->get('Customer Delivery Address Link') != 'None' ? false : true),


                'class'     => 'new',
                'value'     => '',
                'label'     => _('Add delivery address').' <i class="fa fa-plus new_button button"></i>',
                'reference' => ''
            ),

        )
    );

    $customer_fields[] = array(
        'label'      => _('Marketing'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'              => 'Customer_Subscriptions',
                'edit'            => 'no_icon',
                'value'           => $object->get('Customer Subscriptions'),
                'formatted_value' => '<span class="button" onclick="save_toggle_switch(this)"  field="Customer_Send_Newsletter"  style="margin-right:40px"><i class=" fa fa-fw '.($object->get('Customer Send Newsletter') == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off')
                    .'" aria-hidden="true"></i> <span class="'.($object->get('Customer Send Newsletter') == 'No' ? 'discreet' : '').'">'._('Newsletter').'</span></span>'
                    .'<span onclick="save_toggle_switch(this)"  field="Customer_Send_Email_Marketing" class="button" style="margin-right:40px"><i class=" fa fa-fw '.($object->get('Customer Send Email Marketing') == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off')
                    .'" aria-hidden="true"></i> <span class="'.($object->get('Customer Send Email Marketing') == 'No' ? 'discreet' : '').'">'._('Marketing emails').'</span></span>'
                    .'<span onclick="save_toggle_switch(this)"  field="Customer_Send_Postal_Marketing" class="button" style="margin-right:40px"><i class=" fa fa-fw '.($object->get('Customer Send Postal Marketing') == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off')
                    .'" aria-hidden="true"></i> <span class="'.($object->get('Customer Send Postal Marketing') == 'No' ? 'discreet' : '').'">'._('Postal marketing').'</span></span>',
                'label'           => _('Subscriptions'),
                'required'        => false,
                'type'            => 'value'
            ),


        )
    );


    $customer_fields[] = array(
        'label' => _('Sales settings'),

        'show_title' => true,
        'class'      => 'edit_fields '.($store->get('Store Type') == 'Dropshipping' ? 'hide' : ''),
        'fields'     => array(


            array(
                'render' => (($object->get('Customer Level Type') == 'Partner' or $store->get('Store Type') == 'Dropshipping') ? false : true),
                'id'     => 'Customer_Sales_Representative',
                'edit'   => ($can_supervisor_customer_services ? 'option_multiple_choices' : ''),

                'value'           => $object->get('Customer Sales Representative'),
                'formatted_value' => $object->get('Sales Representative'),
                'options'         => $options_sales_representative,
                'label'           => ucfirst(_('Account manager')),
                'required'        => false,
                'type'            => 'value'

            ),

            array(
                'id'   => 'Customer_Credit_Limit',
                'edit' => ($can_supervisor_accounting ? 'amount' : ''),

                'value'           => $object->get('Customer Credit Limit'),
                'formatted_value' => $object->get('Credit Limit'),
                'options'         => $options_sales_representative,
                'label'           => _('Credit limit'),
                'invalid_msg'     => get_invalid_message('amount'),

                'required' => false,
                'type'     => 'value'

            ),

        )

    );

    $cat_fields= array(
        'label' => _('Categories'),

        'show_title' => true,
        'fields'     => $customer_category_fields

    );



    $customer_fields[] =$cat_fields;

    $customer_fields[] = array(
        'label' => _('Integrations'),

        'show_title' => true,
        // 'class'      => 'edit_fields '.($store->get('Store Type') != 'Dropshipping' ? 'hide' : ''),
        'fields'     => array(


            array(
                'id'              => 'Customer_Integration_Shopify',
                'edit'            => 'no_icon',
                'value'           => '',
                'formatted_value' => '<span class="button" onclick="set_up_customer_integration(this,\'Shopify\')"  style="margin-right:40px"> <span>'._('Get access code').' <i class="fa fa-arrow-right"></i></span></span><span class="integration_result"></span>',
                'label'           => _('Shopify'),
                'required'        => false,
                'type'            => 'value'
            ),

        )

    );




    if ($store->get('Store Type') == 'Dropshipping') {

        $customer_fields[] = array(
            'label'      => _('Fulfilment'),
            'show_title' => true,
            'class'      => 'edit_fields',
            'fields'     => array(


                array(
                    'id'              => 'Customer_Services',
                    'edit'            => 'no_icon',
                    'value'           => $object->get('Customer Fulfilment'),
                    'formatted_value' =>
                        '<span class="button" onclick="save_toggle_switch(this)"  field="Customer_Fulfilment"  style="margin-right:40px"><i class=" fa fa-fw '.($object->get('Customer Fulfilment') == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off')
                        .'" aria-hidden="true"></i> <span class="'.($object->get('Customer Fulfilment') == 'No' ? 'discreet' : '').'">'._('Full product procurement').'</span></span>'
                    ,
                    'label'           => '',
                    'required'        => false,
                    'type'            => 'value'
                ),



            )

        );
    }



    $customer_fields[] = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(


            array(

                'id'        => 'delete_customer',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                    .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._('Delete customer').' <i class="far fa-trash-alt new_button link"></i></span>',
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
                'id'                => 'Customer_Other_Email_'.$other_email_data_key,
                'edit'              => 'email',
                'value'             => $other_email_data['email'],
                'formatted_value'   => $other_email_data['email'],
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'label'             => ucfirst(
                        $object->get_field_label('Customer Other Email')
                    ).' <i onClick="set_this_as_main(this)" title="'._(
                        'Set as main email'
                    ).'" class="far fa-star very_discreet button"></i>',
                'required'          => false,
                'type'              => 'value'
            );
        }
        array_splice($customer_fields[1]['fields'], 1, 0, $other_emails_fields);
    }

    $other_telephones = $object->get_other_telephones_data();
    if (count($other_telephones) > 0) {
        $other_telephones_fields = array();
        foreach (
            $other_telephones as $other_telephone_data_key => $other_telephone_data
        ) {
            $other_telephones_fields[] = array(
                'id'              => 'Customer_Other_Telephone_'.$other_telephone_data_key,
                'edit'            => 'telephone',
                'value'           => $other_telephone_data['telephone'],
                'formatted_value' => $other_telephone_data['formatted_telephone'],
                'label'           => ucfirst(
                        $object->get_field_label('Customer Other Telephone')
                    ).' <i onClick="set_this_as_main(this)" title="'._(
                        'Set as main telephone'
                    ).'" class="far fa-star very_discreet button"></i>',
                'required'        => false
            );
        }
        array_splice($customer_fields[2]['fields'], 2, 0, $other_telephones_fields);
    }

    $other_delivery_addresses_fields = array();


    $other_delivery_addresses = $object->get_other_delivery_addresses_data();

    $smarty->assign('other_delivery_addresses', $other_delivery_addresses);

    $number_other_delivery_address = count($other_delivery_addresses);

    if ($number_other_delivery_address > 0) {

        foreach ($other_delivery_addresses as $other_delivery_address_key => $other_delivery_address) {

            //   addresses ready to be edited from  $other_delivery_addresses_fields_directory

            $other_delivery_addresses_fields[] = array(
                'id'              => 'Customer_Other_Delivery_Address_'.$other_delivery_address_key,
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

    array_splice($customer_fields[3]['fields'], 3, 0, $other_delivery_addresses_fields);

}



