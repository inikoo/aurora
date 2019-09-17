<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17-09-2019 15:03:51 MYT, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

include_once 'utils/static_data.php';

if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}

$options_yn = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);

$options_incoterms = array();
$sql               = "SELECT `Incoterm Transport Type`,`Incoterm Name`,`Incoterm Code` FROM kbase.`Incoterm Dimension` ORDER BY `Incoterm Code` ";

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


$company_field = array();

$object_fields = array(


    array(
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
                'id'   => 'Supplier_Nickname',
                'edit' => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars($object->get('Supplier Nickname')),
                'formatted_value' => $object->get('Nickname'),
                'label'           => ucfirst($object->get_field_label('Supplier Nickname')),
                'required'        => false,
                'type'            => 'value'
            ),
            array(
                'id'   => 'Supplier_Company_Name',
                'edit' => ($edit ? 'string' : ''),
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
    ),



     array(
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
        )),



    array(
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
    ),

    array(
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
                        : ' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="far fa-star discreet button"></i>') : ''),
                'invalid_msg'     => get_invalid_message('telephone'),
                'required'        => false,
                'type'            => 'value'
            ),
            array(

                'id'   => 'Supplier_Main_Plain_Telephone',
                'edit' => ($edit ? 'telephone' : ''),

                'value'           => $object->get(
                    'Supplier Main Plain Telephone'
                ),
                'formatted_value' => $object->get('Main Plain Telephone'),
                'label'           => ucfirst(
                        $object->get_field_label(
                            'Supplier Main Plain Telephone'
                        )
                    ).($object->get('Supplier Main Plain Telephone') != '' ? ($object->get('Supplier Preferred Contact Number') == 'Telephone'
                        ? ' <i  title="'._('Main contact number').'" class="fa fa-star button discreet"></i>'
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
    ),

    array(
        'label'      => _('Address'),
        'show_title' => false,
        'fields'     => array(

            array(
                'id'        => 'Supplier_Contact_Address',
                'edit'      => ($edit ? 'address' : ''),
                'countries' => get_countries($db),

                'value'           => htmlspecialchars($object->get('Supplier Contact Address')),
                'formatted_value' => $object->get('Contact Address'),
                'label'           => ucfirst($object->get_field_label('Supplier Contact Address')
                ),
                'invalid_msg'     => get_invalid_message('address'),
                'required'        => false,
                'type'            => 'value'
            ),


        )
    ),


);


if ($object->get('Supplier Type') != 'Archived') {


    $object_fields[] = array(
        'label'      => _("Ordering"),
        'show_title' => false,
        'fields'     => array(

            array(
                'id'              => 'Supplier_Average_Delivery_Days',
                'edit'            => 'mediumint_unsigned',
                'value'           => $object->get('Supplier Average Delivery Days'),
                'formatted_value' => $object->get('Average Delivery Days'),
                'label'           => ucfirst($object->get_field_label('Supplier Average Delivery Days')),
                'required'        => false,
                'type'            => 'value'
            ),

            array(
                'id'              => 'Supplier_minimum_order_amount',
                'edit'            => ($edit ? 'mediumint_unsigned' : ''),
                'value'           => htmlspecialchars($object->get('Supplier minimum order amount')),
                'formatted_value' => $object->get('minimum order amount'),
                'label'           => ucfirst($object->get_field_label('Supplier minimum order amount')).' ('.$object->get('Default Currency Code').')',
                'required'        => false,
                'type'            => 'value'
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



