<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 November 2018 at 14:06:07 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/
include_once 'utils/static_data.php';



$store=get_object('Store',$options['store_key']);

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
    'Percentage_Off'   => _('Order'),
    'Buy_n_get_n_free' => _('Product'),
    'Category'         => _('Product category'),
    'Customer'         => _('Customer')

);

$object_fields = array();

if (!$new) {
    $object_fields[] =
        array(
            'label'      => _('Status'),
            'show_title' => true,
            'fields'     => array(


                array(
                    'edit' => ($edit ? 'option' : ''),

                    'options'         => $options_yn,
                    'id'              => 'Shipping_Zone_Active',
                    'value'           => $object->get('Shipping Zone Active'),
                    'formatted_value' => $object->get('Active'),

                    'label'       => ucfirst($object->get_field_label('Shipping Zone Active')),
                    'invalid_msg' => get_invalid_message('string'),
                    'required'    => true,
                ),


            )

        );
}


$object_fields[] =


    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit'            => ($edit ? 'string' : ''),
                'id'              => 'Shipping_Zone_Code',
                'value'           => $object->get('Shipping Zone Code'),
                'formatted_value' => $object->get('Code'),

                'label'             => ucfirst($object->get_field_label('Shipping Zone Code')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'type'              => 'value'
            ),
            array(
                'edit'            => ($edit ? 'string' : ''),
                'id'              => 'Shipping_Zone_Name',
                'value'           => $object->get('Shipping Zone Name'),
                'formatted_value' => $object->get('Name'),

                'label'       => ucfirst($object->get_field_label('Shipping Zone Name')),
                'invalid_msg' => get_invalid_message('string'),
                'required'    => true,
                'type'        => 'value'
            ),


            array(
                'id'              => 'Shipping_Zone_Description',
                'edit'            => ($edit ? 'editor' : ''),
                'render'          => ($new ? false : true),
                'class'           => 'editor',
                'editor_data'     => array(
                    'id'      => 'Charge_Public_Description',
                    'content' => $object->get('Shipping Zone Description'),

                    'data' => base64_encode(
                        json_encode(
                            array(
                                'mode'     => 'edit_object',
                                'field'    => 'Shipping_Zone_Description',
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
                                    'field'  => 'Shipping Zone Description',


                                )
                            )
                        )
                    )

                ),
                'value'           => '',
                'formatted_value' => $object->get('Shipping Zone Description'),
                'label'           => ucfirst($object->get_field_label('Shipping Zone Description')),
                'required'        => true,
                'type'            => ''
            ),


        )

    );


$object_fields[] =
    array(
        'label'      => _('Delivery price'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'              => 'Shipping_Zone_Price',
                'edit'            => 'shipping_price_tier',
                'country_list'    => get_countries($db),

                'value'           => $object->get('Shipping Zone Price'),
                'formatted_value' => $object->get('Price'),
                'label'           => _('Price'),
                'required'        => false,
                'type'            => 'value'
            )


        )

    );

$object_fields[] =
    array(
        'label'      => _('Territories'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'              => 'Shipping_Zone_Territories',
                'edit'            => 'territories',
                'country_list'    => get_countries($db),
                'default_country'    => $store->get('Store Home Country Code 2 Alpha'),
                'value'           => $object->get('Shipping Zone Territories'),
                'formatted_value' => $object->get('Parts'),
                'label'           => _('Territories'),
                'required'        => false,
                'type'            => 'value'
            )


        )

    );


?>
