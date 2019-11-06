<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 May 2016 at 15:16:01 GMT+8, Puchong, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

/**
 * @var $website \Website
 */
$website=$object;

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


$store_key = $website->get('Website Store Key');

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

$object_fields = array();


if($website->get('Website Status')=='InProcess'){
    $object_fields[] =

        array(
            'label' => _('Website in construction'),
            'class' => 'operations error launch_website_header',

            'show_title' => true,
            'fields'     => array(


                array(
                    'id'        => 'launch_webpage',
                    'render'    => true,
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => ' <span style="margin:10px 0px;padding:10px;border:1px solid #ccc"  data-website_key="'.$website->id.'" onClick="launch_website(this)" class="save changed valid">'._("Launch website")
                        .' <i class="fa fa-fw fa-rocket save changed valid"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),

            )
        );
}

$object_fields[] = array(
    'label'      => _('Id'),
    'class'      => '',
    'show_title' => true,
    'fields'     => array(

        array(
            'id'                => 'Website_Code',
            'edit'              => ($supervisor_edit ? 'string' : ''),
            'right_code'        => 'WS-'.$store_key,
            'value'             => $website->get('Website Code'),
            'label'             => ucfirst($website->get_field_label('Code')),
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => true,
            'type'              => 'value'
        ),
        array(
            'id'                => 'Website_Name',
            'right_code'        => 'WS-'.$store_key,
            'edit'              => ($supervisor_edit ? 'string' : ''),
            'value'             => $website->get('Website Name'),
            'label'             => ucfirst($website->get_field_label('Name')),
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => true,
            'type'              => 'value',

        ),
        array(
            'id'                => 'Website_URL',
            'edit'              => ($supervisor_edit ? 'string' : ''),
            'right_code'        => 'WS-'.$store_key,
            'value'             => $website->get('Website URL'),
            'label'             => ucfirst($website->get_field_label('URL')),
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => true,
            'type'              => 'value',
            'placeholder'       => 'www.example.com',

        ),

    ),

);

$object_fields[] = array(
    'label'      => _('Menu basket button'),
    'show_title' => true,
    'fields'     => array(


        array(
            'id'         => 'Website_Settings_Info_Bar_Basket_Amount_Type',
            'edit'       => ($supervisor_edit ? 'option' : ''),
            'right_code' => 'WS-'.$store_key,
            'render'     => true,

            'options'         => $options_basket_amount,
            'value'           => ($website->get('Website Settings Info Bar Basket Amount Type') == '' ? 'total' : $website->get('Website Settings Info Bar Basket Amount Type')),
            'formatted_value' => ($website->get('Website Settings Info Bar Basket Amount Type') == '' ? _('Total') : $website->get('Settings Info Bar Basket Amount Type')),
            'label'           => _('Displayed amount'),
            'type'            => 'value'
        ),


    )
);

$object_fields[] = array(
    'label'      => _('Display stock levels'),
    'show_title' => true,
    'fields'     => array(


        array(
            'id'              => 'Website_Settings_Display_Stock_Levels_in_Product',
            'edit'            => ($supervisor_edit ? 'option' : ''),
            'right_code'      => 'WS-'.$store_key,
            'render'          => true,
            'options'         => $options_yes_no,
            'value'           => ($website->get('Website Settings Display Stock Levels in Product') == '' ? 'No' : $website->get('Website Settings Display Stock Levels in Product')),
            'formatted_value' => $website->get('Settings Display Stock Levels in Product'),
            'label'           => _('On product webpage'),
            'type'            => 'value'

        ),
        array(
            'id'              => 'Website_Settings_Display_Stock_Levels_in_Category',
            'edit'            => ($supervisor_edit ? 'option' : ''),
            'right_code'      => 'WS-'.$store_key,
            'render'          => true,
            'options'         => $options_product_in_category_stock_level_style,
            'value'           => ($website->get('Website Settings Display Stock Levels in Category') == '' ? 'No' : $website->get('Website Settings Display Stock Levels in Category')),
            'formatted_value' => $website->get('Settings Display Stock Levels in Category'),
            'label'           => _('On family webpage'),
            'type'            => 'value'
        ),
        array(
            'id'              => 'Website_Settings_Display_Stock_Quantity',
            'edit'            => ($supervisor_edit ? 'option' : ''),
            'right_code'      => 'WS-'.$store_key,
            'render'          => true,
            // 'render' => ($new ? false : ((
            //     $website->get('Website Settings Display Stock Levels in Category') == ''
            // )?true:false) ),
            'options'         => $options_show_stock_quantity,
            'value'           => ($website->get('Website Settings Display Stock Quantity') == '' ? 'No' : $website->get('Website Settings Display Stock Quantity')),
            'formatted_value' => $website->get('Settings Display Stock Quantity'),
            'label'           => _('Show stock quantities'),
            'type'            => 'value'
        ),

    )
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
                'label'     => ' <span data-data=\'{ "object": "'.$website->get_object_name().'", "key":"'.$website->id.'"}\' onClick="clean_cache(this)" class=" button">'._("Flush cache").' <i class="fa fa-shower new_button link"></i> </span>',
                'reference' => '',
                'type'      => 'operation'
            ),
            /*
            array(
                'id'        => 'delete_website',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$website->get_object_name(
                    ).'", "key":"'.$website->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete website & all webpages")
                    .' <i class="far fa-trash-alt new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),
*/

        )

    );
    $object_fields[] = $operations;
}








