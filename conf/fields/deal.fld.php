<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2016 at 14:19:03 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}

$options_trigger = array(
    'Order'    => _('Order'),
    'Product'  => _('Product'),
    'Category' => _('Product category'),
    'Customer' => _('Customer')

);



$options_offer_type = array(
    'Percentage_Off'    => _('Order'),
    'Buy_n_get_n_free'  => _('Product'),
    'Category' => _('Product category'),
    'Customer' => _('Customer')

);


$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Deal_Name',
                'value'             => $object->get('Deal Name'),
                'label'             => ucfirst(
                    $object->get_field_label('Deal Name')
                ),
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
        'label'      => _('Trigger'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'              => 'Deal_Trigger',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_trigger,
                'value'           => $object->get('Deal Trigger'),
                'formatted_value' => $object->get('Trigger'),
                'label'           => _('Target'),
                'placeholder'     => _('Choose one option'),
                'type'            => 'value'
            ),


            array(
                'id'                       => 'Product_Category_Key',
                'edit'                     => 'dropdown_select',
                'scope'                    => 'product_categories',
                'parent'                   => 'store',
                'parent_key'               => $options['store_key'],
                'value'                    => '',
                'formatted_value'          => '',
                'stripped_formatted_value' => '',
                'label'                    => _("Product category"),
                'required'                 => false,
                'type'                     => 'value'


            ),

            array(
                'id'                       => 'Product_Key',
                'edit'                     => 'dropdown_select',
                'scope'                    => 'products',
                'parent'                   => 'store',
                'parent_key'               => $options['store_key'],
                'value'                    => '',
                'formatted_value'          => '',
                'stripped_formatted_value' => '',
                'label'                    => _("Product"),
                'required'                 => false,
                'type'                     => 'value'


            ),


            array(
                'id'   => 'Minumun_Order_Amount',
                'edit' => 'amount',

                'value'           => '',
                'formatted_value' => '',
                'label'           => _('Minimum items net amount'),
                'invalid_msg'     => get_invalid_message('amount'),
                'required'        => true,
                'type'            => 'value'
            ),

        )
    ),

    array(
        'label'      => _('Discount/Allowance'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'              => 'Deal_Trigger',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_trigger,
                'value'           => $object->get('Deal Trigger'),
                'formatted_value' => $object->get('Trigger'),
                'label'           => _('Target'),
                'placeholder'     => _('Choose one option'),
                'type'            => 'value'
            ),


            array(
                'id'                       => 'Product_Category_Key',
                'edit'                     => 'dropdown_select',
                'scope'                    => 'product_categories',
                'parent'                   => 'store',
                'parent_key'               => $options['store_key'],
                'value'                    => '',
                'formatted_value'          => '',
                'stripped_formatted_value' => '',
                'label'                    => _("Product category"),
                'required'                 => false,
                'type'                     => 'value'


            ),

            array(
                'id'                       => 'Product_Key',
                'edit'                     => 'dropdown_select',
                'scope'                    => 'products',
                'parent'                   => 'store',
                'parent_key'               => $options['store_key'],
                'value'                    => '',
                'formatted_value'          => '',
                'stripped_formatted_value' => '',
                'label'                    => _("Product"),
                'required'                 => false,
                'type'                     => 'value'


            ),


        )
    ),


);


?>
