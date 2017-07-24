<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 May 2016 at 10:43:19 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'utils/static_data.php';


$options_valid_tax_number = array(
    'Yes'     => _('Valid'),
    'No'      => _('Not Valid'),
    'Unknown' => _('Unknown'),
    'Auto'    => _('Check online'),
);


$countries = get_countries($db);


$object_fields = array(

    array(
        'label'      => _('Customer'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'    => 'Order_Customer_Name',
                'edit'            => ($edit ? 'string' : ''),
                'value' => $object->get('Order Customer Name'),
                'label' => _('Customer name'),
                'required'        => false
            ),

            array(
                'id'    => 'Order_Telephone',
                'edit'            => ($edit ? 'string' : ''),
                'value' => $object->get('Order Telephone'),
                'label' => _('Contact telephone'),
                'required'        => false
            ),



            array(
                'id'                => 'Order_Email',
                'edit'              => ($edit ? 'email' : ''),
                'value'             => $object->get('Order Email'),
                'formatted_value'   => $object->get('Email'),
                'label' => _('Email'),
                'invalid_msg'       => get_invalid_message('email'),
                'required'          => false,
            ),
            array(
                'id'              => 'Order_Tax_Number',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => $object->get('Order Tax Number'),
                'formatted_value' => $object->get('Tax Number'),
                'label'           => ucfirst($object->get_field_label('Order Tax Number')),
                'required'        => false,
                'type'            => 'value'

            ),
            array(
                'render'          => ($object->get('Order Tax Number') == '' ? false : true),
                'id'              => 'Order_Tax_Number_Valid',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_valid_tax_number,
                'value'           => $object->get('Order Tax Number Valid'),
                'formatted_value' => $object->get('Tax Number Valid'),
                'label'           => ucfirst($object->get_field_label('Order Tax Number Valid')
                ),
            ),



        )
    ),


    array(
        'label'      => _('Address'),
        'show_title' => false,
        'fields'     => array(


            array(
                'id'              => 'Order_Delivery_Address',
                'edit'            => ($edit ? 'address' : ''),
                'countries'       => $countries,
                'value'           => htmlspecialchars($object->get('Order Delivery Address')),
                'formatted_value' => $object->get('Delivery Address'),
                'label'           => ucfirst($object->get_field_label('Order Delivery Address')),
                'invalid_msg'     => get_invalid_message('address'),
                'required'        => false
            ),

            array(
                'id'              => 'Order_Invoice_Address',
                'edit'            => ($edit ? 'address' : ''),
                'countries'       => $countries,
                'value'           => htmlspecialchars($object->get('Order Invoice Address')),
                'formatted_value' => $object->get('Invoice Address'),
                'label'           => ucfirst($object->get_field_label('Order Invoice Address')),
                'required'        => false
            ),



        )
    ),



);


?>
