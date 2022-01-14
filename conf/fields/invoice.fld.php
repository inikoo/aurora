<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 August 2017 at 10:49:48 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


include_once 'utils/static_data.php';

$countries = get_countries($db);

$options_valid_tax_number = array(
    'Yes'     => _('Valid'),
    'No'      => _('Not Valid'),
    'Unknown' => _('Unknown'),
    'Auto'    => _('Check online'),
);

$options_yes_no = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);

$new = false;


$invoice = $object;


if ($user->can_supervisor('accounting')) {
    $can_supervisor_accounting = true;
} else {
    $can_supervisor_accounting = false;

}

if ($user->can_edit('orders') and in_array($invoice->get('Invoice Store Key'), $user->stores)) {
    $edit = true;
} else {
    $edit = false;

}


$object_fields = array(

    array(
        'label'      => _('Footer'),
        'show_title' => true,
        'fields'     => array(
            array(
                'edit'       => ($can_supervisor_accounting ? 'textarea' : ''),
                'right_code' => 'IS',
                'id'         => 'Invoice_Message',
                'value'      => $object->get('Invoice Message'),
                'label'      => ucfirst($object->get_field_label('Invoice Message')),
                'required'   => false,

                'type' => 'value'


            )
        )
    ),

    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'              => ($can_supervisor_accounting ? 'string' : ''),
                'right_code'        => 'IS',
                'id'                => 'Invoice_Public_ID',
                'value'             => $invoice->get('Invoice Public ID'),
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'label'             => ucfirst($object->get_field_label('Invoice Public ID')),
            ),


        )
    ),
    array(
        'label'      => _('Customer'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'       => ($can_supervisor_accounting ? 'string' : ''),
                'right_code' => 'IS',
                'id'         => 'Invoice_Customer_Name',
                'value'      => $invoice->get('Invoice Customer Name'),
                'label'      => ucfirst($object->get_field_label('Invoice Customer Name')),
                'required'   => true
            ),

            array(
                'edit'       => ($can_supervisor_accounting ? 'string' : ''),
                'right_code' => 'IS',
                'id'         => 'Invoice_Registration_Number',
                'value'      => $invoice->get('Invoice Registration Number'),
                'label'      => ucfirst($object->get_field_label('Invoice Registration Number')),
                'required'   => false
            ),


            array(
                'id'     => 'Invoice_Address',
                'render' => true,

                'edit'            => ($can_supervisor_accounting ? 'address' : ''),
                'right_code'      => 'IS',
                'countries'       => $countries,
                'value'           => htmlspecialchars($object->get('Invoice Address')),
                'formatted_value' => $object->get('Address'),
                'label'           => ucfirst($object->get_field_label('Invoice Address')),
                'required'        => false
            ),
            array(
                'id'              => 'Invoice_Tax_Number',
                'edit'            => ($can_supervisor_accounting ? 'string' : ''),
                'right_code'      => 'IS',
                'value'           => $object->get('Invoice Tax Number'),
                'formatted_value' => $object->get('Tax Number'),
                'label'           => ucfirst($object->get_field_label('Invoice Tax Number')),
                'required'        => false,
                'type'            => 'value'

            ),
            array(
                'render'          => ($object->get('Invoice Tax Number') == '' ? false : true),
                'id'              => 'Invoice_Tax_Number_Valid',
                'edit'            => ($can_supervisor_accounting ? 'option' : ''),
                'right_code'      => 'IS',
                'options'         => $options_valid_tax_number,
                'value'           => $object->get('Invoice Tax Number Valid'),
                'formatted_value' => $object->get('Tax Number Valid'),
                'label'           => ucfirst($object->get_field_label('Invoice Tax Number Valid')),
            ),
            array(
                'id'              => 'Invoice_Recargo_Equivalencia',
                'edit'            => ($can_supervisor_accounting ? 'option' : ''),
                'render'          => ($account->get('Account Country Code') == 'ESP' ? true : false),
                'options'         => $options_yes_no,
                'value'           => $object->get('Invoice Recargo Equivalencia'),
                'formatted_value' => $object->get('Recargo Equivalencia'),
                'label'           => _('Recargo de equivalencia').' <i class="fa fa-registered recargo_equivalencia"></i>',
                'type'            => ''
            ),
            array(
                'id'              => 'Invoice_EORI',
                'edit'            => ($can_supervisor_accounting ? 'string' : ''),
                'right_code'      => 'IS',
                'value'           => $object->get('Invoice EORI'),
                'formatted_value' => $object->get('EORI'),
                'label'           => ucfirst($object->get_field_label('EORI')),
                'required'        => false,
                'type'            => 'value'

            ),

        ),

    ),

    array(
        'label'      => _('Dates'),
        'show_title' => true,
        'fields'     => array(
            array(
                'edit'            => ($can_supervisor_accounting ? 'date' : ''),
                'right_code'      => 'IS',
                'time'            => '08:00:00',
                'id'              => 'Invoice_Date',
                'value'           => $object->get('Invoice Date'),
                'formatted_value' => $object->get('Date'),
                'label'           => ucfirst($object->get_field_label('Invoice Date')),
                'invalid_msg'     => get_invalid_message('date'),
                'required'        => true,
                'type'            => ''
            ),

            array(
                'edit'            => ($can_supervisor_accounting ? 'date' : ''),
                'right_code'      => 'IS',
                'time'            => '08:00:00',
                'id'              => 'Invoice_Tax_Liability_Date',
                'value'           => $object->get('Invoice Tax Liability Date'),
                'formatted_value' => $object->get('Tax Liability Date'),
                'label'           => ucfirst($object->get_field_label('Invoice Tax Liability Date')),
                'invalid_msg'     => get_invalid_message('date'),
                'required'        => true,
                'type'            => ''
            ),


        )
    ),


);


if ($invoice->get('Invoice Type') == 'Invoice') {

    $order = get_object('order', $object->get('Invoice Order Key'));

    //if ($order->get('Order State') != 'Dispatched'   ) {
    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(


            array(
                'id'        => 'delete_invoice',
                'class'     => 'operation '.($object->get('Invoice Order Type')=='FulfilmentRent'?'hide':''),
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-'.($can_supervisor_accounting ? 'lock-alt' : 'lock').' button"  
                data-labels=\'{ "text":"'._('Please ask an authorised user to delete this invoice').'","title":"'._('Restricted operation').'","footer":"'._('Authorised users').': "}\'  
                onClick="'.($can_supervisor_accounting ? 'toggle_unlock_delete_object(this)' : 'not_authorised_toggle_unlock_delete_object(this,\'IS\')').'"  style="margin-right:20px"></i> <span data-labels=\'{ "no_message":"'._(
                        'A reason should be provided'
                    ).'", "button_text":"'._('Delete').'",  "title":"'._(
                        'Deleting invoice'
                    ).'", "text":"'._("This operation cannot be undone").'",  "placeholder":"'._('Write the reason for deleting this invoice').'" }\'  data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                    .'"}\' onClick="delete_object_with_note(this)" class="delete_object disabled">'._('Delete invoice').' <i class="far fa-trash-alt new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
    //}
} else {


    $delete_ops = array(
        'id'        => 'delete_refund',
        'class'     => 'operation',
        'value'     => '',
        'label'     => '<i class="fa fa-fw fa-'.($can_supervisor_accounting ? 'lock-alt' : 'lock').' button" onClick="'.($can_supervisor_accounting ? 'toggle_unlock_delete_object(this)' : 'not_authorised_toggle_unlock_delete_object(this,\'IS\')').'" 
                            style="margin-right:20px"></i> <span data-labels=\'{ "no_message":"'._('A reason should be provided').'", "button_text":"'._('Delete').'",  "title":"'._(
                'Deleting refund'
            ).'", "text":"'._("This operation cannot be undone").'",  "placeholder":"'._('Write the reason for deleting this refund').'" }\' data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
            .'"}\' onClick="delete_object_with_note(this)" class="delete_object disabled">'._('Delete refund').' <i class="far fa-trash-alt new_button link "></i></span>',
        'reference' => '',
        'type'      => 'operation'
    );


    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(


            $delete_ops,


        )

    );

    $object_fields[] = $operations;


}


