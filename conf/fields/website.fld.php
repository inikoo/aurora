<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 May 2016 at 15:16:01 GMT+8, Puchong, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$options_yes_no = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);

$options_product_in_category_stock_level_style = array(
    'Hint_Bar' => _('Hint bar'),
    'Dot'      => _('Dot'),
    'No'       => _('No')
);

$options_show_stock_quantity = array(
    'Yes'              => _('Yes'),
    'Only_if_very_low' => _('Only if stock very low'),
    'No'               => _('No'),

);


$store_key = $object->get('Website Store Key');

if ($user->can_edit('websites') and in_array($store_key, $user->stores)) {
    $edit = true;
} else {
    $edit = false;
}


if ($user->can_supervisor('websites') and in_array($store_key, $user->stores)) {
    $supervisor_edit = true;
} else {
    $supervisor_edit = false;
}


$options_basket_amount = array(
    'total'     => _('Total'),
    'items_net' => _('Items net'),

);


$object_fields = array(
    array(
        'label'      => _('Id'),
        'class'      => ($new ? 'hide' : ''),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'                => 'Website_Code',
                'edit'              => ($supervisor_edit ? 'string' : ''),
                'right_code'        => 'WS-'.$store_key,
                'value'             => $object->get('Website Code'),
                'label'             => ucfirst($object->get_field_label('Code')),
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Website_Name',
                'right_code'        => 'WS-'.$store_key,
                'edit'              => ($supervisor_edit ? 'string' : ''),
                'value'             => $object->get('Website Name'),
                'label'             => ucfirst($object->get_field_label('Name')),
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'type'              => 'value',

            ),
            array(
                'id'                => 'Website_URL',
                'edit'              => ($supervisor_edit ? 'string' : ''),
                'right_code'        => 'WS-'.$store_key,
                'value'             => $object->get('Website URL'),
                'label'             => ucfirst($object->get_field_label('URL')),
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'type'              => 'value',
                'placeholder'       => 'www.example.com',

            ),

        ),

    ),

    array(
        'label'      => _('Menu basket button'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'         => 'Website_Settings_Info_Bar_Basket_Amount_Type',
                'edit'       => ($supervisor_edit ? 'option' : ''),
                'right_code' => 'WS-'.$store_key,
                'render'     => ($new ? false : true),

                'options'         => $options_basket_amount,
                'value'           => ($object->get('Website Settings Info Bar Basket Amount Type') == '' ? 'total' : $object->get('Website Settings Info Bar Basket Amount Type')),
                'formatted_value' => ($object->get('Website Settings Info Bar Basket Amount Type') == '' ? _('Total') : $object->get('Settings Info Bar Basket Amount Type')),
                'label'           => _('Displayed amount'),
                'type'            => 'value'
            ),


        )
    ),

    array(
        'label'      => _('Display stock levels'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'              => 'Website_Settings_Display_Stock_Levels_in_Product',
                'edit'            => ($supervisor_edit ? 'option' : ''),
                'right_code'      => 'WS-'.$store_key,
                'render'          => ($new ? false : true),
                'options'         => $options_yes_no,
                'value'           => ($object->get('Website Settings Display Stock Levels in Product') == '' ? 'No' : $object->get('Website Settings Display Stock Levels in Product')),
                'formatted_value' => $object->get('Settings Display Stock Levels in Product'),
                'label'           => _('On product webpage'),
                'type'            => 'value'

            ),
            array(
                'id'              => 'Website_Settings_Display_Stock_Levels_in_Category',
                'edit'            => ($supervisor_edit ? 'option' : ''),
                'right_code'      => 'WS-'.$store_key,
                'render'          => ($new ? false : true),
                'options'         => $options_product_in_category_stock_level_style,
                'value'           => ($object->get('Website Settings Display Stock Levels in Category') == '' ? 'No' : $object->get('Website Settings Display Stock Levels in Category')),
                'formatted_value' => $object->get('Settings Display Stock Levels in Category'),
                'label'           => _('On family webpage'),
                'type'            => 'value'
            ),
            array(
                'id'              => 'Website_Settings_Display_Stock_Quantity',
                'edit'            => ($supervisor_edit ? 'option' : ''),
                'right_code'      => 'WS-'.$store_key,
                'render'          => ($new ? false : true),
                // 'render' => ($new ? false : ((
                //     $object->get('Website Settings Display Stock Levels in Category') == ''
                // )?true:false) ),
                'options'         => $options_show_stock_quantity,
                'value'           => ($object->get('Website Settings Display Stock Quantity') == '' ? 'No' : $object->get('Website Settings Display Stock Quantity')),
                'formatted_value' => $object->get('Settings Display Stock Quantity'),
                'label'           => _('Show stock quantities'),
                'type'            => 'value'
            ),

        )
    ),


);

if ($edit) {
    $operations      = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(
            array(
                'id'        => 'clean_cache',
                'class'     => 'operation',
                'value'     => '',
                'label'     => ' <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id.'"}\' onClick="clean_cache(this)" class=" button">'._("Flush cache").' <i class="fa fa-shower new_button link"></i> </span>',
                'reference' => '',
                'type'      => 'operation'
            ),
            /*
            array(
                'id'        => 'delete_website',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name(
                    ).'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete website & all webpages")
                    .' <i class="far fa-trash-alt new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),
*/

        )

    );
    $object_fields[] = $operations;
}








