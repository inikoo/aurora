<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 April 2016 at 20:16:48 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_store_fields(Store $object, User $user, PDO $db, $smarty): array {

    if ($user->can_supervisor('stores') and in_array($object->id, $user->stores)) {
        $edit = true;
    } else {
        $edit = false;
    }

    include_once 'utils/static_data.php';


    $countries = get_countries($db);




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
        'ro_RO' => 'ro_RO '._('Romanian'),

    );
    asort($options_locale);


    //'Order ID','Invoice Public ID','Account Wide Invoice Public ID'
    $options_next_invoice_number = array(
        'Order ID'          => _('Same as order'),
        'Invoice Public ID' => _('Own consecutive number'),
        //  'Account Wide Invoice Public ID'  => _('Own consecutive number (shared all stores)'),

    );

    //'Same Invoice ID','Next Invoice ID','Account Wide Own Index','Store Own Index'
    $options_next_refund_number = array(
        'Same Invoice ID' => _('Same as invoice'),
        'Next Invoice ID' => _('Next consecutive invoice number'),
        'Store Own Index' => _('Own consecutive number'),
        //  'Account Wide Own Index'  => _('Own consecutive number (shared all stores)'),


    );


    $options_timezones = array();
    foreach (DateTimeZone::listIdentifiers() as $timezone) {
        $options_timezones[preg_replace('/\//', '_', $timezone)] = $timezone;
    }

    $options_currencies = array();
    $sql                = "SELECT `Currency Code`,`Currency Name`,`Currency Symbol`,`Currency Flag` FROM kbase.`Currency Dimension` ";
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $options_currencies[$row['Currency Code']] = _($row['Currency Name']).' '.$row['Currency Symbol'];
        }
    }
    asort($options_currencies);

    $options_Staff    = array();
    $options_Staff[0] = _('No default');
    $sql              = "SELECT `Staff Name`,`Staff Key`,`Staff Alias` FROM `Staff Dimension` WHERE `Staff Currently Working`='Yes' order by `Staff Alias` ";
    $stmt             = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $options_Staff[$row['Staff Key']] = $row['Staff Alias'];

    }


    $options_Shipper    = array();
    $options_Shipper[0] = _('No default');
    $sql                = "SELECT `Shipper Name`,`Shipper Key`,`Shipper Code` FROM `Shipper Dimension` WHERE  `Shipper Status`='Active' order by `Shipper Name` ";
    $stmt               = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $options_Shipper[$row['Shipper Key']] = $row['Shipper Name'].' ('.$row['Shipper Code'].')';
    }


    $object->smarty = $smarty;

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
                    'label'             => ucfirst($object->get_field_label('Store Name')),
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
                    'label'           => ucfirst($object->get_field_label('Store Locale')),
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Store_Currency_Code',
                    'edit'            => ($edit ? 'option' : ''),
                    'options'         => $options_currencies,
                    'value'           => $object->get('Store Currency Code'),
                    'formatted_value' => $object->get('Currency Code'),
                    'label'           => ucfirst($object->get_field_label('Store Currency Code')),
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
                    'label'       => ucfirst($object->get_field_label('Store Email')),
                    'invalid_msg' => get_invalid_message('email'),
                    'required'    => false,

                    'type' => 'value'


                ),
                array(
                    'edit'            => ($edit ? 'string' : ''),
                    'id'              => 'Store_Telephone',
                    'value'           => $object->get('Store Telephone'),
                    'formatted_value' => $object->get('Telephone'),
                    'label'           => ucfirst($object->get_field_label('Store Telephone')),
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
            'class'      => '',
            'fields'     => array(

                array(
                    'id'              => 'Store_Can_Collect',
                    'edit'            => ($edit ? 'option' : ''),
                    'render'          => true,
                    'options'         => $options_yes_no,
                    'value'           => $object->get('Store Can Collect'),
                    'formatted_value' => $object->get('Can Collect'),
                    'label'           => _('Accept orders for collection'),
                    'type'            => ''
                ),
                array(
                    'id'              => 'Store_Collect_Address',
                    'edit'            => 'address',
                    'render'          => $object->get('Store Can Collect') == 'Yes',
                    'countries'       => $countries,
                    'value'           => htmlspecialchars($object->get('Store Collect Address')),
                    'formatted_value' => ($object->get('Store Collect Address Formatted') == ''
                        ? '<span class="warning" style="position: relative;top:5px"><i class="fa fa-warning "></i>  '._('Input collection address').'</span>'
                        : $object->get(
                            'Store Collect Address Formatted'
                        )),
                    'label'           => ucfirst($object->get_field_label('Store Collect Address')),
                    'invalid_msg'     => get_invalid_message('address'),
                    'required'        => false
                ),

            )
        ),


        array(
            'label' => _('Order numbering'),
            'class' => '',

            'show_title' => true,
            'fields'     => array(


                array(
                    'edit'   => ($edit ? 'string' : ''),
                    'render' => true,

                    'id'       => 'Store_Order_Public_ID_Format',
                    'value'    => $object->get('Store Order Public ID Format'),
                    'label'    => ucfirst($object->get_field_label('Store Order Public ID Format')).' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, misconfiguration of this variable can affect the creation of new orders')
                        .'" ></i>',
                    'required' => true,

                    'type' => ''


                ),

                array(
                    'edit'   => ($edit ? 'numeric' : ''),
                    'render' => true,

                    'id'       => 'Store_Order_Last_Order_ID',
                    'value'    => $object->get('Store Order Last Order ID'),
                    'label'    => ucfirst($object->get_field_label('Store Order Last Order ID')).' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, misconfiguration of this variable can affect the creation of new orders').'" ></i>',
                    'required' => true,

                    'type' => ''


                ),

                array(
                    'edit'   => ($edit ? 'option' : ''),
                    'render' => true,

                    'options'         => $options_next_invoice_number,
                    'id'              => 'Store_Next_Invoice_Public_ID_Method',
                    'value'           => $object->get('Store Next Invoice Public ID Method'),
                    'formatted_value' => $object->get('Next Invoice Public ID Method'),
                    'label'           => ucfirst($object->get_field_label('Store Next Invoice Public ID Method')),
                    'required'        => true,

                    'type' => ''


                ),

                array(
                    'edit'   => ($edit ? 'string' : ''),
                    'id'     => 'Store_Invoice_Public_ID_Format',
                    'render' => $object->get('Store Next Invoice Public ID Method') == 'Invoice Public ID',
                    'value'  => $object->get('Store Invoice Public ID Format'),

                    'label'    => ucfirst($object->get_field_label('Store Invoice Public ID Format')).' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, misconfiguration of this variable can affect the creation of new invoices')
                        .'" ></i>',
                    'required' => true,

                    'type' => ''


                ),

                array(
                    'edit'     => ($edit ? 'numeric' : ''),
                    'id'       => 'Store_Invoice_Last_Invoice_Public_ID',
                    'value'    => $object->get('Store Invoice Last Invoice Public ID'),
                    'render'   => $object->get('Store Next Invoice Public ID Method') == 'Invoice Public ID',
                    'label'    => ucfirst($object->get_field_label('Store Invoice Last Invoice Public ID')).' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._(
                            'Warning, misconfiguration of this variable can affect the creation of new invoices'
                        ).'" ></i>',
                    'required' => true,

                    'type' => ''


                ),


                array(
                    'edit'    => ($edit ? 'option' : ''),
                    'options' => $options_next_refund_number,
                    'render'  => $object->get('Store Next Invoice Public ID Method') == 'Invoice Public ID',

                    'id'              => 'Store_Refund_Public_ID_Method',
                    'value'           => $object->get('Store Refund Public ID Method'),
                    'formatted_value' => $object->get('Refund Public ID Method'),
                    'label'           => ucfirst($object->get_field_label('Store Next Refund Public ID Method')),
                    'required'        => true,

                    'type' => ''


                ),

                array(
                    'edit'   => ($edit ? 'string' : ''),
                    'id'     => 'Store_Refund_Public_ID_Format',
                    'render' => !(($object->get('Store Next Invoice Public ID Method') == 'Same Invoice ID' or $object->get('Store Refund Public ID Method') != 'Store Own Index')),
                    'value'  => $object->get('Store Refund Public ID Format'),

                    'label'    => ucfirst($object->get_field_label('Store Refund Public ID Format')).' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, misconfiguration of this variable can affect the creation of new refunds')
                        .'" ></i>',
                    'required' => true,

                    'type' => ''


                ),

                array(
                    'edit'     => ($edit ? 'numeric' : ''),
                    'id'       => 'Store_Invoice_Last_Refund_Public_ID',
                    'value'    => $object->get('Store Invoice Last Refund Public ID'),
                    'render'   => !(($object->get('Store Next Invoice Public ID Method') == 'Same Invoice ID' or $object->get('Store Refund Public ID Method') != 'Store Own Index')),
                    'label'    => ucfirst($object->get_field_label('Store Invoice Last Refund Public ID')).' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, misconfiguration of this variable can affect the creation of new refunds')
                        .'" ></i>',
                    'required' => true,

                    'type' => ''


                ),


            )
        ),


        array(
            'label'      => _('Data entry of paper picking aid').' <i class="fa fa-keyboard padding_left_5" style="font-size: 110%;position: relative;top:.5px"></i>',
            'show_title' => true,
            'class'      => '',

            'fields' => array(


                array(
                    'edit'   => 'no_icon',
                    'render' => true,

                    'id'              => 'Store_Allow_Data_Entry_Picking_Aid',
                    'value'           => $object->settings('data_entry_picking_aid'),
                    'formatted_value' => '<span class="button" onclick="toggle_allow_data_entry_picking_aid(this)"  field="data_entry_picking_aid"  style="margin-right:40px"><i class=" fa fa-fw '.($object->settings('data_entry_picking_aid') == 'Yes' ? 'fa-toggle-on'
                            : 'fa-toggle-off').'" aria-hidden="true"></i> <span class="'.($object->settings('data_entry_picking_aid') == 'Yes' ? '' : 'discreet').'">'._('Allow').'</span></span>  
                    
                    ',

                    'label'    => _('Data entry of paper picking aid'),
                    'required' => true,

                    'type' => ''


                ),
                array(
                    'edit'            => 'no_icon',
                    'id'              => 'Store_Allow_Data_Entry_Picking_Aid_Settings',
                    'class'           => 'data_entry_picking_aid_defaults',
                    'render'          => $object->settings('data_entry_picking_aid') == 'Yes',
                    'value'           => '',
                    'formatted_value' => '
                    
                    <span  data-value="0"  class="button  " onclick="toggle_allow_data_entry_picking_aid(this)"  field="data_entry_picking_aid_state_after_save"  >'._('Just set as packed').' <i class=" far fa-fw fa-check-circle" aria-hidden="true"></i></span>  <i class="fas fa-angle-double-right"></i> 
                    <span  data-value="10" class="button data_entry_picking_aid_state_after_save level_10  '.($object->settings('data_entry_picking_aid_state_after_save') >= 10 ? '' : 'very_discreet')
                        .' " onclick="toggle_allow_data_entry_picking_aid(this)"  field="data_entry_picking_aid_state_after_save"  >'._('Set as closed').' <i class=" far fa-fw '.($object->settings('data_entry_picking_aid_state_after_save') >= 10 ? 'fa-check-circle'
                            : 'fa-circle').'" aria-hidden="true"></i></span>   <i class="fas fa-angle-double-right"></i>
                     <span  data-value="20"  class="button data_entry_picking_aid_state_after_save level_20 '.($object->settings('data_entry_picking_aid_state_after_save') >= 20 ? '' : 'very_discreet')
                        .'" onclick="toggle_allow_data_entry_picking_aid(this)"  field="data_entry_picking_aid_state_after_save"  >'._('Create invoice').' <i class=" far fa-fw '.($object->settings('data_entry_picking_aid_state_after_save') >= 20 ? 'fa-check-circle'
                            : 'fa-circle').'" aria-hidden="true"></i></span>  <i class="fas fa-angle-double-right"></i>
                    <span  data-value="30"  class="button data_entry_picking_aid_state_after_save level_30 '.($object->settings('data_entry_picking_aid_state_after_save') >= 30 ? '' : 'very_discreet')
                        .'" onclick="toggle_allow_data_entry_picking_aid(this)"  field="data_entry_picking_aid_state_after_save" >'._('Set as dispatched').' <i class=" far fa-fw '.($object->settings('data_entry_picking_aid_state_after_save') >= 30 ? 'fa-check-circle'
                            : 'fa-circle').'" aria-hidden="true"></i></span> 

                    ',

                    'label'    => _('Actions after data entry'),
                    'required' => true,

                    'type' => ''


                ),

                array(
                    'render'          => $object->settings('data_entry_picking_aid') == 'Yes',
                    'class'           => 'data_entry_picking_aid_defaults',
                    'id'              => 'data_entry_picking_aid_default_picker',
                    'edit'            => ($edit ? 'option' : ''),
                    'value'           => $object->settings('data_entry_picking_aid_default_picker'),
                    'formatted_value' => $object->get('data entry picking aid default picker'),
                    'options'         => $options_Staff,
                    'label'           => _('Default picker'),
                    'required'        => false,
                    'type'            => ''

                ),
                array(
                    'render' => $object->settings('data_entry_picking_aid') == 'Yes',
                    'class'  => 'data_entry_picking_aid_defaults',

                    'id'              => 'data_entry_picking_aid_default_packer',
                    'edit'            => ($edit ? 'option' : ''),
                    'value'           => $object->settings('data_entry_picking_aid_default_packer'),
                    'formatted_value' => $object->get('data entry picking aid default packer'),
                    'options'         => $options_Staff,
                    'label'           => _('Default packer'),
                    'required'        => false,
                    'type'            => ''

                ),

                array(
                    'render' => $object->settings('data_entry_picking_aid') == 'Yes',
                    'class'  => 'data_entry_picking_aid_defaults',

                    'id'              => 'data_entry_picking_aid_default_shipper',
                    'edit'            => ($edit ? 'option' : ''),
                    'value'           => $object->settings('data_entry_picking_aid_default_shipper'),
                    'formatted_value' => $object->get('data entry picking aid default shipper'),
                    'options'         => $options_Shipper,
                    'label'           => _('Default courier'),
                    'required'        => false,
                    'type'            => ''

                ),


            )
        ),


        array(
            'label'      => _('PDF Invoices'),
            'show_title' => true,
            'class'      => '',
            'fields'     => array(

                array(
                    'edit'            => 'no_icon',
                    'id'              => 'Store_Allow_Data_Entry_Picking_Aid_Settings',
                    'class'           => 'data_entry_picking_aid_defaults',
                    'render'          => $object->settings('data_entry_picking_aid') == 'Yes',
                    'value'           => '',
                    'formatted_value' => '
                    <div style="line-height: 20px">
                    <span   class="button  " onclick="toggle_invoice_show(this)"  data-field="invoice_show_rrp"  ><i class=" far fa-fw '.($object->settings('invoice_show_rrp') == 'Yes' ? 'fa-check-square' : 'fa-square').'" ></i> '._('Recommended retail prices').'</span>  <br>
             
                    <span   class="button  " onclick="toggle_invoice_show(this)"  data-field="invoice_show_parts"  ><i class=" far fa-fw '.($object->settings('invoice_show_parts') == 'Yes' ? 'fa-check-square' : 'fa-square').'" ></i> '._('Parts').' </span>  <br>
                    <span   class="button  " onclick="toggle_invoice_show(this)"  data-field="invoice_show_tariff_codes"><i class=" far fa-fw '.($object->settings('invoice_show_tariff_codes') == 'Yes' ? 'fa-check-square' : 'fa-square').'" ></i> '._('Commodity codes').' </span>  <br>
                    <span   class="button  " onclick="toggle_invoice_show(this)"  data-field="invoice_show_barcode"  ><i class=" far fa-fw '.($object->settings('invoice_show_barcode') == 'Yes' ? 'fa-check-square' : 'fa-square').'" ></i> '._('Product barcode').' </span>  <br>
                    <span   class="button  " onclick="toggle_invoice_show(this)"  data-field="invoice_show_weight"  ><i class=" far fa-fw '.($object->settings('invoice_show_weight') == 'Yes' ? 'fa-check-square' : 'fa-square').'" ></i> '._('Weight').' </span>  <br>
                    <span   class="button  " onclick="toggle_invoice_show(this)"  data-field="invoice_show_origin"  > <i class=" far fa-fw '.($object->settings('invoice_show_origin') == 'Yes' ? 'fa-check-square' : 'fa-square').'" ></i> '._('Country of origin').'</span>  <br>
                    <span   class="button  " onclick="toggle_invoice_show(this)"  data-field="invoice_show_CPNP"  > <i class=" far fa-fw '.($object->settings('invoice_show_CPNP') == 'Yes' ? 'fa-check-square' : 'fa-square').'" ></i> '._('CPNP').'</span>  <br>
                   </div>
                    ',

                    'label'    => _('Display').':',
                    'required' => true,

                    'type' => ''


                ),
                array(
                    'id'              => 'send_invoice_attachment_in_delivery_confirmation',
                    'edit'            => ($edit ? 'option' : ''),
                    'render'          => true,
                    'options'         => $options_yes_no,
                    'value'           => $object->settings('send_invoice_attachment_in_delivery_confirmation'),
                    'formatted_value' => $object->get('send invoice attachment in delivery confirmation'),
                    'label'           => sprintf(_('Send %s in delivery confirmation email'), '<i class="fal fa-paperclip"></i>'),
                    'type'            => ''
                ),


            )
        ),

        array(
            'label'      => _('PDF Delivery notes'),
            'show_title' => true,
            'class'      => '',
            'fields'     => array(

                array(
                    'id'              => 'send_dn_attachment_in_delivery_confirmation',
                    'edit'            => ($edit ? 'option' : ''),
                    'render'          => true,
                    'options'         => $options_yes_no,
                    'value'           => $object->settings('send_dn_attachment_in_delivery_confirmation'),
                    'formatted_value' => $object->get('send dn attachment in delivery confirmation'),
                    'label'           => sprintf(_('Send %s in delivery confirmation email'), '<i class="fal fa-paperclip"></i>'),
                    'type'            => ''
                ),


            )
        ),

        array(
            'label'      => _('Signatures'),
            'class'      => '',
            'show_title' => true,
            'fields'     => array(

                array(
                    'edit'   => ($edit ? 'textarea' : ''),
                    'render' => true,

                    'id'       => 'Store_Email_Template_Signature',
                    'value'    => $object->get('Store Email Template Signature'),
                    'label'    => ucfirst($object->get_field_label('Store Email Template Signature')),
                    'required' => false,

                    'type' => 'value'


                ),

                array(
                    'edit'   => ($edit ? 'textarea' : ''),
                    'render' => true,

                    'id'       => 'Store_Invoice_Message',
                    'value'    => $object->get('Store Invoice Message'),
                    'label'    => ucfirst($object->get_field_label('Store Invoice Message')),
                    'required' => false,

                    'type' => 'value'


                ),
                array(
                    'edit'   => ($edit ? 'textarea' : ''),
                    'render' => true,

                    'id'       => 'Store_Proforma_Message',
                    'value'    => $object->get('Store Proforma Message'),
                    'label'    => ucfirst($object->get_field_label('Store Proforma Message')),
                    'required' => false,

                    'type' => 'value'


                ),


            )
        ),


        array(
            'label'      => _('Email BCC').' <span class="margin_left_5 small warning">Use only when no attachments</span>',
            'show_title' => true,
            'class'      => '',
            'fields'     => array(


                array(
                    'id'     => 'Store_BCC_Delivery_Note_Dispatched_Recipients',
                    'render' => true,

                    'edit'            => 'mixed_recipients',
                    'value'           => '',
                    'formatted_value' => $object->get('BCC Delivery Note Dispatched Recipients'),
                    'label'           => _('Order dispatched'),
                    'required'        => false,
                    'type'            => ''
                ),




            )
        ),

        array(
            'label'      => _('Notifications'),
            'show_title' => true,
            'class'      => '',
            'fields'     => array(


                array(
                    'id'     => 'Store_Notification_New_Order_Recipients',
                    'render' => true,

                    'edit'            => 'mixed_recipients',
                    'value'           => '',
                    'formatted_value' => $object->get('Notification New Order Recipients'),
                    'label'           => _('New order'),
                    'required'        => false,
                    'type'            => ''
                ),
                array(
                    'id'     => 'Store_Notification_New_Customer_Recipients',
                    'render' => true,

                    'edit'            => 'mixed_recipients',
                    'value'           => '',
                    'formatted_value' => $object->get('Notification New Customer Recipients'),
                    'label'           => _('Customer registration'),
                    'required'        => false,
                    'type'            => ''
                ),
                array(
                    'id'     => 'Store_Notification_Invoice_Deleted_Recipients',
                    'render' => true,

                    'edit'            => 'mixed_recipients',
                    'value'           => '',
                    'formatted_value' => $object->get('Notification Invoice Deleted Recipients'),
                    'label'           => _('Invoice deleted'),
                    'required'        => false,
                    'type'            => ''
                ),
                array(
                    'id'     => 'Store_Notification_Delivery_Note_Dispatched_Recipients',
                    'render' => true,

                    'edit'            => 'mixed_recipients',
                    'value'           => '',
                    'formatted_value' => $object->get('Notification Delivery Note Dispatched Recipients'),
                    'label'           => _('Delivery note dispatched'),
                    'required'        => false,
                    'type'            => ''
                ),
                array(
                    'id'     => 'Store_Notification_Delivery_Note_Undispatched_Recipients',
                    'render' => true,

                    'edit'            => 'mixed_recipients',
                    'value'           => '',
                    'formatted_value' => $object->get('Notification Delivery Note Undispatched Recipients'),
                    'label'           => _('Delivery note undispatched'),
                    'required'        => false,
                    'type'            => ''
                ),



            )
        ),


        array(
            'label'      => _('Product labels'),
            'show_title' => true,
            'class'      => '',

            'fields' => array(


                array(
                    'edit'   => ($edit ? 'textarea' : ''),
                    'render' => true,

                    'id'              => 'Store_Label_Signature',
                    'value'           => $object->get('Store Label Signature'),
                    'formatted_value' => $object->get('Label Signature'),

                    'label'    => ucfirst($object->get_field_label('Store Label Signature')),
                    'required' => false,

                    'type' => ''


                ),

            )
        ),

    );


    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(


            array(
                'id'    => 'delete_store',
                'class' => 'operation',
                'value' => '',

                'label' => '<i class="fa fa-fw fa-'.($edit ? 'lock-alt' : 'lock').' button" onClick="'.($edit ? 'toggle_unlock_delete_object(this)' : 'not_authorised_toggle_unlock_delete_object(this,\'SS\')').'" 
                            style="margin-right:20px"></i> 
                            <span 
                                    data-labels=\'{ "no_message":"'._('A reason should be provided').'", "button_text":"'._('Delete').'",  "title":"'._('Deleting store').'","text":"'._("This operation cannot be undone").'",  
                                                "placeholder":"'._('Write the reason for deleting this store').'" }\' 
                                    data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id.'"}\' 
                                    onClick="delete_object_with_note(this)" class="delete_object disabled">'._('Delete store').' <i class="far fa-trash-alt new_button link "></i>
                            </span>',

                'reference' => '',
                'type'      => 'operation'
            ),

            array(
                'id' => 'create_website',

                'class'     => 'operation '.($object->get('Store Website Key') > 0 ? 'hide' : ''),
                'value'     => '',
                'label'     => '<span  onClick="change_view(\'store/'.$object->id.'/website/new\')" class="create_object button" style="margin-left:42px">'._('Create website').' <i class="far fa-plus new_button link"></i>',
                'reference' => '',
                'type'      => 'operation'
            ),
        )

    );

    $object_fields[] = $operations;


    return $object_fields;

}




