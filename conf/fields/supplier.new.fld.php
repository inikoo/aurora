<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  11 June 2020  13:32::44  +0800 Kuala Lumpur, Malaysia

 Copyright (c) 2020, Inikoo

 Version 3.0
*/

include_once 'utils/static_data.php';


$options_yn = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);

$options_supplier_order_type = [
    'Parcel'         => _('Parcels'),
    'Container' => _('Containers'),
];

$options_incoterms = array();

$sql = "SELECT `Incoterm Transport Type`,`Incoterm Name`,`Incoterm Code` FROM kbase.`Incoterm Dimension` ORDER BY `Incoterm Code` ";

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        if ($row['Incoterm Transport Type'] == 'Sea') {
            $transport_method = sprintf(
                '<img style="height:12px" src="art/icons/transport_sea.png" alt="sea" title="%s">', _('Maritime and inland waterways')
            );
        } else {
            $transport_method = sprintf(
                '<img  style="height:12px" src="art/icons/transport_land.png" alt="land" title="%s"> <img style="height:12px" src="art/icons/transport_sea.png" alt="sea" title="%s"> <img  style="height:12px" src="art/icons/transport_air.png" alt="air" title="%s">',
                _('Land'), _('Maritime and inland waterway'), _('Air')
            );

        }
        $options_incoterms[$row['Incoterm Code']] = sprintf(
            "%s %s", $row['Incoterm Code'], $row['Incoterm Name']
        );
    }

}

$options_currencies = array();
$sql                = "SELECT `Currency Code`,`Currency Name`,`Currency Symbol` FROM kbase.`Currency Dimension` ORDER BY `Currency Code`";

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $options_currencies[$row['Currency Code']] = sprintf(
            "%s %s (%s)", $row['Currency Code'], $row['Currency Name'], $row['Currency Symbol']
        );
    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


asort($options_yn);
asort($options_incoterms);
asort($options_supplier_order_type);


$company_field = array();


$object_fields = [];


if ($options['parent'] != 'agent') {
    $object_fields[] = array(
        'label'      => _('Type'),
        'show_title' => true,


        'fields' => array(
            array(
                'id'   => 'Supplier_Purchase_Order_Type',
                'edit' => ($edit ? 'option' : ''),

                'options'         => $options_supplier_order_type,
                'value'           => 'Parcel',
                'formatted_value' => _('Parcels'),
                'label'           => ucfirst($object->get_field_label('Supplier Purchase Order Type')),
                'required'        => false,
                'type'            => 'value'
            ),


        )
    );
}
$object_fields[] = array(
    'label'      => _('Code, name'),
    'show_title' => true,
    'fields'     => array(
        array(

            'id'                => 'Supplier_Code',
            'edit'              => ($edit ? 'string' : ''),
            'value'             => $object->get('Supplier Code'),
            'label'             => ucfirst(
                $object->get_field_label('Code')
            ),
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'invalid_msg'       => get_invalid_message('string'),
            'type'              => 'value'
        ),
        array(
            'id'              => 'Supplier_Nickname',
            'edit'            => ($edit ? 'string' : ''),
            'value'           => htmlspecialchars($object->get('Supplier Nickname')),
            'formatted_value' => $object->get('Nickname'),
            'label'           => ucfirst($object->get_field_label('Supplier Nickname')),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'Supplier_Company_Name',
            'edit'            => ($edit ? 'string' : ''),
            'value'           => htmlspecialchars($object->get('Supplier Company Name')),
            'formatted_value' => $object->get('Company Name'),
            'label'           => ucfirst($object->get_field_label('Supplier Company Name')),
            'required'        => false,
            'type'            => 'value'
        ),


        array(

            'id'   => 'Supplier_Main_Contact_Name',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $object->get('Supplier Main Contact Name')
            ),
            'formatted_value' => $object->get('Main Contact Name'),
            'label'           => ucfirst(
                $object->get_field_label('Supplier Main Contact Name')
            ),
            'required'        => true,
            'type'            => 'value'
        ),

    )
);

$object_fields[] = array(
    'label'      => _('Our Id in Supplier records'),
    'show_title' => false,
    'fields'     => array(
        array(
            'id'   => 'Supplier_Account_Number',
            'edit' => ($edit ? 'string' : ''),

            'value'           => $object->get('Supplier Account Number'),
            'formatted_value' => $object->get('Account Number'),
            'label'           => ucfirst($object->get_field_label('Supplier Account Number')),
            'required'        => false,
            'type'            => 'value'
        ),
    )
);


$object_fields[] = array(
    'label'      => _('Email'),
    'show_title' => false,
    'fields'     => array(

        array(
            'id'   => 'Supplier_Main_Plain_Email',
            'edit' => ($edit ? 'email' : ''),

            'value'             => $object->get(
                'Supplier Main Plain Email'
            ),
            'formatted_value'   => $object->get('Main Plain Email'),
            'server_validation' => json_encode(
                array('tipo' => 'check_for_duplicates')
            ),
            'label'             => ucfirst(
                $object->get_field_label('Supplier Main Plain Email')
            ),
            'invalid_msg'       => get_invalid_message('email'),
            'required'          => false,
            'type'              => 'value'
        ),
        array(
            'id'                => 'new_email',
            'render'            => false,
            'edit'              => 'new_email',
            'value'             => '',
            'server_validation' => json_encode(
                array('tipo' => 'check_for_duplicates')
            ),
            'formatted_value'   => '',
            'label'             => ucfirst(
                $object->get_field_label('Supplier Other Email')
            ),
            'invalid_msg'       => get_invalid_message('email'),

            'required' => false,
            'type'     => 'ignore'
        ),

        array(
            'id'     => 'Supplier_Other_Email',
            'render' => false,
            'edit'   => ($edit ? 'email' : ''),

            'value'             => '',
            'formatted_value'   => '',
            'server_validation' => json_encode(
                array('tipo' => 'check_for_duplicates')
            ),
            'label'             => ucfirst(
                    $object->get_field_label('Supplier Other Email')
                ).' <i onClick="set_this_as_main(this)" title="'._(
                    'Set as main email'
                ).'" class="far fa-star very_discreet button"></i>',
            'invalid_msg'       => get_invalid_message('email'),
            'required'          => false,
            'type'              => 'value'
        ),

        array(
            'render'    => ($object->get('Supplier Main Plain Email') == '' ? false : true),
            'id'        => 'show_new_email',
            'class'     => 'new',
            'value'     => '',
            'label'     => _('Add email').' <i class="fa fa-plus new_button button"></i>',
            'reference' => '',
            'type'      => 'value'
        ),

    )
);

$object_fields[] = array(
    'label'      => _('Contact'),
    'show_title' => false,
    'fields'     => array(

        array(
            'id'              => 'Supplier_Main_Plain_Mobile',
            'edit'            => ($edit ? 'telephone' : ''),
            'mobile'          => true,
            'value'           => $object->get('Supplier Main Plain Mobile'),
            'formatted_value' => $object->get('Main Plain Mobile'),
            'label'           => ucfirst(
                    $object->get_field_label('Supplier Main Plain Mobile')
                ).($object->get('Supplier Main Plain Mobile') != '' ? ($object->get('Supplier Preferred Contact Number') == 'Mobile'
                    ? ' <i  title="'._('Main contact number').'" class="fa fa-star button discreet"></i>'
                    : ' <i onClick="set_this_as_main(this)" title="'._(
                        'Set as main contact number'
                    ).'" class="far fa-star discreet button"></i>') : ''),
            'invalid_msg'     => get_invalid_message('telephone'),
            'required'        => false,
            'type'            => 'value'
        ),
        array(

            'id'   => 'Supplier_Main_Plain_Telephone',
            'edit' => ($edit ? 'telephone' : ''),

            'value'           => $object->get('Supplier Main Plain Telephone'),
            'formatted_value' => $object->get('Main Plain Telephone'),
            'label'           => ucfirst(
                    $object->get_field_label(
                        'Supplier Main Plain Telephone'
                    )
                ).($object->get('Supplier Main Plain Telephone') != '' ? ($object->get('Supplier Preferred Contact Number') == 'Telephone' ? ' <i  title="'._('Main contact number').'" class="fa fa-star button discreet"></i>'
                    : ' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="far fa-star discreet button"></i>') : ''),
            'invalid_msg'     => get_invalid_message('telephone'),
            'required'        => false,
            'type'            => 'value'

        ),
        array(
            'id'              => 'new_telephone',
            'render'          => false,
            'edit'            => 'new_telephone',
            'value'           => '',
            'formatted_value' => '',
            'label'           => ucfirst(
                    $object->get_field_label('Supplier Other Telephone')
                ).' <i onClick="set_this_as_main(this)" title="'._(
                    'Set as main telephone'
                ).'" class="far fa-star very_discreet button"></i>',
            'required'        => false,
            'type'            => 'ignore'
        ),

        array(
            'id'              => 'Supplier_Other_Telephone',
            'render'          => false,
            'edit'            => ($edit ? 'telephone' : ''),
            'clone_template'  => true,
            'value'           => '',
            'formatted_value' => '',
            'label'           => ucfirst(
                    $object->get_field_label('Supplier Other Telephone')
                ).' <i onClick="set_this_as_main(this)" title="'._(
                    'Set as main telephone'
                ).'" class="far fa-star very_discreet button"></i>',
            'required'        => false,
            'type'            => 'ignore'
        ),

        array(
            'render'    => ($object->get('Supplier Main Plain Telephone') == '' ? false : true),
            'id'        => 'show_new_telephone',
            'class'     => 'new',
            'value'     => '',
            'label'     => _('Add telephone').' <i class="fa fa-plus new_button button"></i>',
            'required'  => false,
            'reference' => '',
            'type'      => 'ignore'
        ),


        array(
            'id'   => 'Supplier_QQ',
            'edit' => ($edit ? 'string' : ''),

            'value'           => $object->get('Supplier QQ'),
            'formatted_value' => $object->get('QQ'),
            'label'           => ucfirst($object->get_field_label('Supplier QQ')),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'type'            => 'value'
        ),

        array(
            'id'   => 'Supplier_Main_Plain_FAX',
            'edit' => ($edit ? 'telephone' : ''),

            'value'           => $object->get('Supplier Main Plain FAX'),
            'formatted_value' => $object->get('Main Plain FAX'),
            'label'           => ucfirst(
                $object->get_field_label('Supplier Main Plain FAX')
            ),
            'invalid_msg'     => get_invalid_message('telephone'),
            'required'        => false,
            'type'            => 'value'
        ),

    )
);
$object_fields[] = array(
    'label'      => _('Address'),
    'show_title' => false,
    'fields'     => array(

        array(
            'id'        => 'Supplier_Contact_Address',
            'edit'      => ($edit ? 'address' : ''),
            'countries' => get_countries($db),

            'value'           => htmlspecialchars($object->get('Supplier Contact Address')),
            'formatted_value' => $object->get('Contact Address'),
            'label'           => ucfirst(
                $object->get_field_label('Supplier Contact Address')
            ),
            'invalid_msg'     => get_invalid_message('address'),
            'required'        => false,
            'type'            => 'value'
        ),


    )
);


$object_fields[] = array(
    'label'      => _("Supplier's products settings"),
    'show_title' => false,
    'fields'     => array(
        array(
            'id'   => 'Supplier_On_Demand',
            'edit' => ($edit ? 'option' : ''),

            'options'         => $options_yn,
            'value'           => $object->get('Supplier On Demand'),
            'formatted_value' => $object->get('On Demand'),
            'label'           => ucfirst($object->get_field_label('Supplier On Demand')),
            'required'        => false,
            'type'            => 'value'
        ),


        array(
            'id'                       => 'Supplier_Products_Origin_Country_Code',
            'edit'                     => ($edit ? 'country_select' : ''),
            'options'                  => get_countries($db),
            'scope'                    => 'countries',
            'value'                    => strtolower(country_3alpha_to_2alpha($options['country_origin'])),
            'formatted_value'          => $options['country_origin'],
            'stripped_formatted_value' => $options['country_origin'],
            'label'                    => ucfirst($object->get_field_label('Part Origin Country Code')),
            'required'                 => false,
            'type'                     => 'value',

        ),

    )
);
$object_fields[] = array(
    'label'      => _("Waiting times"),
    'show_title' => false,
    'fields'     => array(

        array(
            'id'              => 'Supplier_Average_Production_Days',
            'edit'            => 'mediumint_unsigned',
            'value'           => $object->get('Supplier Average Production Days'),
            'formatted_value' => $object->get('Average Production Days'),
            'label'           => ucfirst($object->get_field_label('Supplier Average Production Days')),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'Supplier_Average_Delivery_Days',
            'edit'            => 'mediumint_unsigned',
            'value'           => $object->get('Supplier Average Delivery Days'),
            'formatted_value' => $object->get('Average Delivery Days'),
            'label'           => ucfirst($object->get_field_label('Supplier Average Delivery Days')),
            'required'        => false,
            'type'            => 'value'
        ),


    )
);

$object_fields[] = array(
    'label'      => _('Payment'),
    'show_title' => false,
    'fields'     => array(

        array(
            'render'          => ($options['parent'] == 'agent' ? false : true),
            'id'              => 'Supplier_Default_Currency_Code',
            'edit'            => ($edit ? 'country_select' : ''),
            'options'         => get_currencies($db),
            'scope'           => 'currencies',
            'value'           => strtolower(get_country_code_from_currency($db, $options['currency'])),
            'formatted_value' => $options['currency'],
            'label'           => ucfirst(
                $object->get_field_label('Supplier Default Currency Code')
            ),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'payment_terms',
            'edit'            => ($edit ? 'string' : ''),
            'value'           => htmlspecialchars($object->get('payment terms')),
            'formatted_value' => $object->get('payment terms'),
            'label'           => ucfirst($object->get_field_label('payment terms')),
            'required'        => false,
            'type'            => 'value'
        ),
    )
);


if ($options['parent'] != 'agent') {

    $object_fields[] = array(
        'label'      => _('Purchase order settings'),
        'show_title' => false,
        'fields'     => array(


            array(
                'id'              => 'Supplier_minimum_order_amount',
                'edit'            => ($edit ? 'mediumint_unsigned' : ''),
                'value'           => htmlspecialchars($object->get('Supplier minimum order amount')),
                'formatted_value' => $object->get('minimum order amount'),
                'label'           => ucfirst($object->get_field_label('Supplier minimum order amount')).' (<span class="Supplier_minimum_order_amount_currency">'.$options['currency'].'</span>)',
                'required'        => false,
                'type'            => 'value'
            ),
            array(
                'id'              => 'Supplier_cooling_order_interval_days',
                'edit'            => ($edit ? 'amount' : ''),
                'value'           => htmlspecialchars($object->get('Supplier cooling order interval days')),
                'formatted_value' => $object->get('cooling order interval days'),
                'label'           => ucfirst($object->get_field_label('Supplier cooling order interval days')),
                'required'        => false,
                'type'            => 'value'
            ),


            array(
                'edit'     => ($edit ? 'string' : ''),
                'id'       => 'Supplier_Order_Public_ID_Format',
                'value'    => $object->get('Supplier Order Public ID Format'),
                'label'    => ucfirst($object->get_field_label('Supplier Order Public ID Format')).' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, misconfiguration of this variable can affect the creation of new orders')
                    .'" ></i>',
                'required' => true,

                'type' => 'value'


            ),

            array(
                'edit'     => ($edit ? 'numeric' : ''),
                'id'       => 'Supplier_Order_Last_Order_ID',
                'value'    => $object->get('Supplier Order Last Order ID'),
                'label'    => ucfirst($object->get_field_label('Supplier Order Last Order ID')).' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, misconfiguration of this variable can affect the creation of new orders').'" ></i>',
                'required' => true,

                'type' => 'value'


            ),





        )
    );



}


$other_emails = $object->get_other_emails_data();
if (count($other_emails) > 0) {
    $other_emails_fields = array();
    foreach ($other_emails as $other_email_data_key => $other_email_data) {
        $other_emails_fields[] = array(
            'id'   => 'Supplier_Other_Email_'.$other_email_data_key,
            'edit' => ($edit ? 'email' : ''),

            'value'             => $other_email_data['email'],
            'formatted_value'   => $other_email_data['email'],
            'server_validation' => json_encode(
                array('tipo' => 'check_for_duplicates')
            ),
            'label'             => ucfirst(
                    $object->get_field_label('Supplier Other Email')
                ).' <i onClick="set_this_as_main(this)" title="'._(
                    'Set as main email'
                ).'" class="far fa-star very_discreet button"></i>',
            'required'          => false
        );
    }
    array_splice($object_fields[1]['fields'], 1, 0, $other_emails_fields);
}

$other_telephones = $object->get_other_telephones_data();
if (count($other_telephones) > 0) {
    $other_telephones_fields = array();
    foreach (
        $other_telephones as $other_telephone_data_key => $other_telephone_data
    ) {
        $other_telephones_fields[] = array(
            'id'   => 'Supplier_Other_Telephone_'.$other_telephone_data_key,
            'edit' => ($edit ? 'telephone' : ''),

            'value'           => $other_telephone_data['telephone'],
            'formatted_value' => $other_telephone_data['formatted_telephone'],
            'label'           => ucfirst(
                    $object->get_field_label('Supplier Other Telephone')
                ).' <i onClick="set_this_as_main(this)" title="'._(
                    'Set as main telephone'
                ).'" class="far fa-star very_discreet button"></i>',
            'required'        => false
        );
    }
    array_splice($object_fields[2]['fields'], 2, 0, $other_telephones_fields);
}



