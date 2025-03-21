<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 April 2016 at 12:53:47 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

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
if ($object->get('Supplier Type') != 'Archived') {


    if ($object->get('Supplier Type') == 'Free') {
        $object_fields[] = array(
            'label'      => _('Type'),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'   => 'Supplier_Purchase_Order_Type',
                    'edit' => ($edit ? 'option' : ''),

                    'options'         => $options_supplier_order_type,
                    'value'           => $object->get('Supplier Purchase Order Type'),
                    'formatted_value' => $object->get('Purchase Order Type'),
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
                    ).($object->get('Supplier Main Plain Mobile') != '' ? ($object->get('Supplier Preferred Contact Number') == 'Mobile' ? ' <i  title="'._('Main contact number').'" class="fa fa-star button discreet"></i>'
                        : ' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="far fa-star discreet button"></i>') : ''),
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
                'id'   => 'Supplier_Website',
                'edit' => ($edit ? 'string' : ''),

                'value'           => $object->get('Supplier Website'),
                'formatted_value' => $object->get('Website'),
                'label'           => ucfirst($object->get_field_label('Supplier Website')),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
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
                'value'                    => strtolower(country_3alpha_to_2alpha($object->get('Supplier Products Origin Country Code'))),
                'formatted_value'          => $object->get('Products Origin Country Code'),
                'stripped_formatted_value' => $object->get('Products Origin Country Code'),
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
                'value'           => strtolower(get_country_code_from_currency($db, $object->get('Supplier Default Currency Code'))),
                'formatted_value' => $object->get('Default Currency'),
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
                    'label'           => ucfirst($object->get_field_label('Supplier minimum order amount')).' ('.$object->get('Default Currency Code').')',
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
                    'label'    => ucfirst($object->get_field_label('Supplier Order Last Order ID')).' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, misconfiguration of this variable can affect the creation of new orders')
                        .'" ></i>',
                    'required' => true,

                    'type' => 'value'


                ),


                array(
                    'id'              => 'Supplier_Default_PO_Terms_and_Conditions',
                    'edit'            => ($edit ? 'editor' : ''),
                    'class'           => 'editor',
                    'editor_data'     => array(
                        'id'      => 'Supplier_Default_PO_Terms_and_Conditions',
                        'content' => $object->get('Supplier Default PO Terms and Conditions'),

                        'data' => base64_encode(
                            json_encode(
                                array(
                                    'mode'     => 'edit_object',
                                    'field'    => 'Supplier_Default_PO_Terms_and_Conditions',
                                    'plugins'  => array(
                                        'align',
                                        'draggable',
                                        'image',
                                        'link',
                                        'save',
                                        'entities',
                                        'emoticons',
                                        'fullscreen',
                                        'lineBreaker',
                                        'table',
                                        'codeView',
                                        'codeBeautifier'
                                    ),
                                    'metadata' => array(
                                        'tipo'   => 'edit_field',
                                        'object' => 'Supplier',
                                        'key'    => $object->id,
                                        'field'  => 'Supplier Default PO Terms and Conditions',


                                    )
                                )
                            )
                        )

                    ),
                    'value'           => $object->get('Supplier Default PO Terms and Conditions'),
                    'formatted_value' => $object->get('Default PO Terms and Conditions'),
                    'label'           => ucfirst(
                        $object->get_field_label(
                            'Supplier Default PO Terms and Conditions'
                        )
                    ),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'   => 'Supplier_Show_Warehouse_TC_in_PO',
                    'edit' => ($edit ? 'option' : ''),

                    'options'         => $options_yn,
                    'value'           => $object->get('Supplier Show Warehouse TC in PO'),
                    'formatted_value' => $object->get('Show Warehouse TC in PO'),
                    'label'           => ucfirst($object->get_field_label('Supplier Show Warehouse TC in PO')),
                    'required'        => false,
                    'type'            => 'value'
                ),


            )
        );

        $object_fields[] = array(
            'label'      => _('Import settings'),
            'show_title' => false,
            'class'      => 'import_settings_tr '.($object->get('Supplier Purchase Order Type') == 'Container' ? '' : 'hide'),
            'fields'     => array(

                array(
                    'id'              => 'Supplier_Default_Incoterm',
                    'edit'            => ($edit ? 'option' : ''),
                    'render'          => ($object->get('Supplier Purchase Order Type') == 'Container' ? true : false),
                    'options'         => $options_incoterms,
                    'value'           => $object->get('Supplier Default Incoterm'),
                    'formatted_value' => $object->get('Default Incoterm'),
                    'label'           => ucfirst($object->get_field_label('Supplier Default Incoterm')),
                    'required'        => false,
                    'type'            => 'value'
                ),


                array(
                    'id'              => 'Supplier_Default_Port_of_Export',
                    'edit'            => ($edit ? 'string' : ''),
                    'render'          => ($object->get('Supplier Purchase Order Type') == 'Container' ? true : false),
                    'value'           => $object->get('Supplier Default Port of Export'),
                    'formatted_value' => $object->get('Default Port of Export'),
                    'label'           => ucfirst($object->get_field_label('Supplier Default Port of Export')),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Supplier_Default_Port_of_Import',
                    'edit'            => ($edit ? 'string' : ''),
                    'render'          => ($object->get('Supplier Purchase Order Type') == 'Container' ? true : false),
                    'value'           => $object->get('Supplier Default Port of Import'),
                    'formatted_value' => $object->get('Default Port of Import'),
                    'label'           => ucfirst($object->get_field_label('Supplier Default Port of Import')),
                    'required'        => false,
                    'type'            => 'value'
                ),


            )
        );

    }


    if ($options['parent'] == 'agent') {


        $sql = sprintf(
            'SELECT `Agent Key`,`Agent Code` FROM `Agent Supplier Bridge`  LEFT JOIN `Agent Dimension` ON (`Agent Key`=`Agent Supplier Agent Key`) WHERE `Agent Supplier Supplier Key`=%d ', $object->id
        );
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                $fields[] = array(

                    'id'        => 'unlink_agent_'.$row['Agent Key'],
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => '<i class="fa fa-fw fa-lock button invisible" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id.'", "agent_key":"'
                        .$row['Agent Key'].'"}\' onClick="unlink_agent(this)" class="delete_object ">'.sprintf(
                            _("Unlink agent %s"), $row['Agent Code']
                        ).' <i class="fa fa-unlink new_button button"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                );

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


    }

    $fields[] = array(

        'id'        => 'archive_supplier',
        'class'     => 'operation',
        'value'     => '',
        'label'     => '<i class="fa fa-fw fa-lock button invisible" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
            .'"}\' onClick="archive_object(this)" class="delete_object ">'._("Archive supplier").' <i class="fa fa-archive new_button button"></i></span>',
        'reference' => '',
        'type'      => 'operation'
    );

    if(false) {
        if ($user->get('User Type') == 'Staff' or $user->get('User Type') == 'Contractor') {
            $fields[] = array(

                'id'        => 'delete_supplier',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                    .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete supplier & supplier's products").' <i class="fa fa-trash new_button "></i></span>',
                'reference' => '',
                'type'      => 'operation'
            );
        }
    }

    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => $fields


    );

    $object_fields[] = $operations;


} else {
    $fields   = array();
    $fields[] = array(

        'id'        => 'unarchive_supplier',
        'class'     => 'operation',
        'value'     => '',
        'label'     => '<i class="fa fa-fw fa-lock button invisible" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
            .'"}\' onClick="unarchive_object(this)" class="delete_object ">'._(
                "Unarchive supplier"
            ).' <i class="fa fa-folder-open new_button button"></i></span>',
        'reference' => '',
        'type'      => 'operation'
    );

    /*
    if ($user->get('User Type') == 'Staff') {
        $fields[] = array(

            'id'        => 'delete_supplier',
            'class'     => 'operation',
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete supplier & supplier's products").' <i class="fa fa-trash new_button "></i></span>',
            'reference' => '',
            'type'      => 'operation'
        );
    }
*/

    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => $fields

    );

    $object_fields[] = $operations;

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



