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

$options_yn = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);


$options_offer_type = array(
    'Percentage_Off'    => _('Order'),
    'Buy_n_get_n_free'  => _('Product'),
    'Category' => _('Product category'),
    'Customer' => _('Customer')

);

$object_fields=array();

$object_fields[]=
    array(
        'label'      => _('Status'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit' => ($edit ? 'option' : ''),

                'options'         => $options_yn,
                'id'                => 'Charge_Active',
                'value'             => $object->get('Charge Active'),
                'formatted_value' => $object->get('Active'),

                'label'             => ucfirst($object->get_field_label('Charge Active')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
            ),


        )

    );

$object_fields[]=



    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Charge_Name',
                'value'             => $object->get('Charge Name'),
                'formatted_value' => $object->get('Name'),

                'label'             => ucfirst($object->get_field_label('Charge Name')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'type' => 'value'
            ),
            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Charge_Description',
                'value'             => $object->get('Charge Description'),
                'formatted_value' => $object->get('Description'),

                'label'             => ucfirst($object->get_field_label('Charge Description')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'type' => 'value'
            ),


            array(
                'id'                => 'Charge_Public_Description',
                'edit'            => ($edit ? 'editor' : ''),
                'class'           => 'editor',
                'editor_data'     => array(
                    'id'      => 'Charge_Public_Description',
                    'content' => $object->get('Charge Public Description'),

                    'data' => base64_encode(
                        json_encode(
                            array(
                                'mode'     => 'edit_object',
                                'field'    => 'Charge_Public_Description',
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
                                    'object' => 'Charge',
                                    'key'    => $object->id,
                                    'field'  => 'Charge Public Description',


                                )
                            )
                        )
                    )

                ),
                'value'           => '',
                'formatted_value' => $object->get('Charge Public Description'),
                'label'             => ucfirst($object->get_field_label('Charge Public Description')),
                'required'        => true,
                'type'            => 'value'
            ),


        )

);

/*

$object_fields[]=array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Charge_Name',
                'value'             => $object->get('Charge Name'),
                'label'             => ucfirst($object->get_field_label('Charge Name')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'type' => 'value'
            ),


        )
    )
);
$object_fields[]=array(
    array(
        'label'      => _('Trigger'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'              => 'Charge_Trigger',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_trigger,
                'value'           => $object->get('Charge Trigger'),
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
                'id'              => 'Charge_Trigger',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_trigger,
                'value'           => $object->get('Charge Trigger'),
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

*/



?>
