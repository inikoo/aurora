<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 August 2017 at 10:49:48 CEST, Tranava, Slovakia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


include_once 'utils/static_data.php';

$new = false;


$invoice = $object;


$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Invoice_Public_ID',
                'value'             => $invoice->get('Invoice Public ID'),
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'label'             => _('Number').' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, editing invoice details can be illegal').'" ></i>',
            ),


        )
    ),
    array(
        'label'      => _('Customer'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'     => ($edit ? 'string' : ''),
                'id'       => 'Invoice_Customer_Name',
                'value'    => $invoice->get('Invoice Customer Name'),
                'label'    => _('Customer name').' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, editing invoice details can be illegal').'" ></i>',
                'required' => true
            ),
            array(
                'edit'     => ($edit ? 'string' : ''),
                'id'       => 'Invoice_Tax_Number',
                'value'    => $invoice->get('Invoice Tax Number'),
                'label'    => _('Customer tax number').' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, editing invoice details can be illegal').'" ></i>',
                'required' => false
            ),
            array(
                'edit'     => ($edit ? 'string' : ''),
                'id'       => 'Invoice_Registration_Number',
                'value'    => $invoice->get('Invoice Registration Number'),
                'label'    => _('Customer registration number').' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, editing invoice details can be illegal').'" ></i>',
                'required' => false
            ),


        )
    ),

    array(
        'label'      => _('Footer'),
        'show_title' => true,
        'fields'     => array(
            array(
                'edit'     => ($edit ? 'textarea' : ''),
                'id'       => 'Invoice_Message',
                'value'    => $object->get('Invoice Message'),
                'label'    => ucfirst($object->get_field_label('Invoice Message')),
                'required' => false,

                'type' => 'value'


            )
        )
    )


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
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-labels=\'{ "no_message":"'._('A reason should be provided').'", "button_text":"'._('Delete').'",  "title":"'._(
                        'Deleting invoice'
                    ).'", "text":"'._("This operation cannot be undone").'",  "placeholder":"'._('Write the reason for deleting this invoice').'" }\'  data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                    .'"}\' onClick="delete_invoice(this)" class="delete_object disabled">'._('Delete invoice').' <i class="far fa-trash-alt new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
    //}
} else {


    if ($invoice->get('Invoice Payments Amount') != 0) {
        $delete_ops = array(
            'id'        => 'info_delete_refund',
            'class'     => 'operation',
            'value'     => '',
            'label'     => _('To delete refund all payments have to be cancelled first'),
            'reference' => '',
            'type'      => 'operation'
        );
    } else {
        $delete_ops = array(
            'id'        => 'delete_refund',
            'class'     => 'operation',
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-labels=\'{ "no_message":"'._('A reason should be provided').'", "button_text":"'._('Delete').'",  "title":"'._(
                    'Deleting refund'
                ).'", "text":"'._("This operation cannot be undone").'",  "placeholder":"'._('Write the reason for deleting this refund').'" }\' data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                .'"}\' onClick="delete_invoice(this)" class="delete_object disabled">'._('Delete refund').' <i class="far fa-trash-alt new_button link "></i></span>',
            'reference' => '',
            'type'      => 'operation'
        );
    }


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

?>
