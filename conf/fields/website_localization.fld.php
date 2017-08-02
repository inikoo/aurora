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


$labels=$object->get('Localised Labels');

//print_r($labels);


$object_fields = array(
    array(
        'label'      => _('Registration'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'                => 'Localised_Labels_Register',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_Register'])  ?_('Register'):$labels['_Register']),
                'label'             => _('Register'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_Login',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_Login'])  ?_('Login'):$labels['_Login']),
                'label'             => _('Login'),
                'required'          => true,
                'type'              => 'value'
            ),

            array(
                'id'                => 'Localised_Labels_login_to_see',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_login_to_see'])  ?_('For prices, please login or register'):$labels['_login_to_see']),
                'label'             => _('Login to see message'),
                'required'          => true,
                'type'              => 'value'
            ),

        )
    ),


    array(
        'label'      => _('Products'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'                => 'Localised_Labels_product_price',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_product_price'])  ?_('Price'):$labels['_product_price']),
                'label'             => _('Price'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_product_rrp',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_product_rrp'])  ?_('RRP'):$labels['_product_rrp']),
                'label'             => _('RRP'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_product_code',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_product_code'])  ?_('Product Code'):$labels['_product_code']),
                'label'             => _('Code'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_product_origin',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_product_origin'])  ?_('Origin'):$labels['_product_origin']),
                'label'             => _('Origin'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_product_weight',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_product_weight'])  ?_('Weight'):$labels['_product_weight']),
                'label'             => _('Weight'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_product_dimensions',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_product_dimensions'])  ?_('Dimensions'):$labels['_product_dimensions']),
                'label'             => _('Dimensions'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_product_materials',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_product_materials'])  ?_('Materials/Ingredients'):$labels['_product_materials']),
                'label'             => _('Materials/Ingredients'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_product_barcode',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_product_barcode'])  ?_('Barcode'):$labels['_product_barcode']),
                'label'             => _('Barcode'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_product_cpnp',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_product_cpnp'])  ?_('Cosmetic Products Notification Portal'):$labels['_product_cpnp']),
                'label'             => _('CPNP'),
                'required'          => true,
                'type'              => 'value'
            ),




        )
    ),

    array(
        'label'      => _('Order button states'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'                => 'Localised_Labels_ordering_order_now',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_ordering_order_now'])  ?_('Order now'):$labels['_ordering_order_now']),
                'label'             => _('Order quantity =0'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_ordering_ordered',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_ordering_ordered'])  ?_('Ordered'):$labels['_ordering_ordered']),
                'label'             => _('Order quantity >0'),
                'required'          => true,
                'type'              => 'value'
            ),

            array(
                'id'                => 'Localised_Labels_ordering_click_to_update',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_ordering_click_to_update'])  ?_('Click to update'):$labels['_ordering_click_to_update']),
                'label'             => _('Order quantity changed'),
                'required'          => true,
                'type'              => 'value'
            ),

            array(
                'id'                => 'Localised_Labels_ordering_ordering_updated',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_ordering_updated'])  ?_('Update'):$labels['_ordering_updated']),
                'label'             => _('Order quantity saved'),
                'required'          => true,
                'type'              => 'value'
            ),



        )
    ),

    array(
        'label'      => _('Forms feedback'),
        'show_title' => true,
        'fields'     => array(




            array(
                'id'                => 'Localised_Labels_validation_password_missing',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_validation_password_missing'])  ?_('Please enter your password'):$labels['_validation_password_missing']),
                'label'             => _('Password missing'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_validation_minlength_password',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_validation_minlength_password'])  ?_('Enter at least 8 characters'):$labels['_validation_minlength_password']),
                'label'             => _('Password too short'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_validation_same_password',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_validation_same_password'])  ?_('Enter the same password as above'):$labels['_validation_same_password']),
                'label'             => _('Password not match'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_validation_required',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['validation_required'])  ?_('This field is required'):$labels['validation_required']),
                'label'             => _('Required field empty'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_validation_email_invalid',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_validation_email_invalid'])  ?_('Invalid email'):$labels['_validation_email_invalid']),
                'label'             => _('Invalid email'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_validation_handle_registered',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_validation_handle_registered'])  ?_('Email address is already in registered'):$labels['_validation_handle_registered']),
                'label'             => _('Duplicate email'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_validation_accept_terms',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_validation_accept_terms'])  ?_('Please accept our terms and conditions to proceed'):$labels['_validation_accept_terms']),
                'label'             => _('Terms and conditions not accepted'),
                'required'          => true,
                'type'              => 'value'
            ),






        )
    ),



);



?>
