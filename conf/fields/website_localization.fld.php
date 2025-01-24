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

$store_key = $object->get('Website Store Key');


$labels = $object->get('Localised Labels');

if ($user->can_supervisor('websites') and in_array($store_key, $user->stores)) {
    $supervisor_edit = true;
} else {
    $supervisor_edit = false;
}


$object_fields = array(
    array(
        'label'      => _('Actions'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'         => 'Localised_Labels_Register',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_Register']) ? _('Register') : $labels['_Register']),
                'label'      => _('Register'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_Login',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_Login']) ? _('Login') : $labels['_Login']),
                'label'      => _('Login'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labels_login_to_see',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_login_to_see']) ? _('For prices, please login or register') : $labels['_login_to_see']),
                'label'      => _('Login to see message'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_Logout',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_Logout']) ? _('Log out') : $labels['_Logout']),
                'label'      => _('Log out'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labels_Profile',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_Profile']) ? _('Profile') : $labels['_Profile']),
                'label'      => _('Profile'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_Basket',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_Basket']) ? _('Basket') : $labels['_Basket']),
                'label'      => _('Basket'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_Favourites',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_Favourites']) ? _('My favourites') : $labels['_Favourites']),
                'label'      => _('Favourites'),
                'required'   => true,
                'type'       => 'value'
            ),

        )
    ),


    array(
        'label'      => _('Products'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'         => 'Localised_Labels_product_price',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_product_price']) ? _('Price') : $labels['_product_price']),
                'label'      => _('Price'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_product_rrp',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_product_rrp']) ? _('RRP') : $labels['_product_rrp']),
                'label'      => _('RRP'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_product_code',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_product_code']) ? _('Code') : $labels['_product_code']),
                'label'      => _('Code'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_product_name',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_product_name']) ? _('Name') : $labels['_product_name']),
                'label'      => _('Code'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_product_origin',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_product_origin']) ? _('Origin') : $labels['_product_origin']),
                'label'      => _('Origin'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_product_weight',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_product_weight']) ? _('Weight') : $labels['_product_weight']),
                'label'      => _('Weight'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_product_dimensions',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_product_dimensions']) ? _('Dimensions') : $labels['_product_dimensions']),
                'label'      => _('Dimensions'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_product_materials',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_product_materials']) ? _('Materials/Ingredients') : $labels['_product_materials']),
                'label'      => _('Materials/Ingredients'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_product_barcode',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_product_barcode']) ? _('Barcode') : $labels['_product_barcode']),
                'label'      => _('Barcode'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_product_cpnp',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_product_cpnp']) ? _('Cosmetic Products Notification Portal') : $labels['_product_cpnp']),
                'label'      => _('CPNP'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_product_ufi',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_product_ufi']) ? _('Unique Formula Identifier - Poison Centres') : $labels['_product_ufi']),
                'label'      => 'UFI',
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_more_discounts_in',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_more_discounts_in']) ? _('More discounts in') : $labels['_more_discounts_in']),
                'label'      => 'More discounts in ...',
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labels_family_page',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_family_page']) ? _('Family page') : $labels['_family_page']),
                'label'      => '... family page',
                'required'   => true,
                'type'       => 'value'
            ),

        )
    ),


    array(
        'label'      => _('Stock'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'         => 'Localised_Labels_stock',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_stock']) ? _('Stock') : $labels['_stock']),
                'label'      => _('Stock'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_stock_OnDemand',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_stock_OnDemand']) ? _('Product made on demand') : $labels['_stock_OnDemand']),
                'label'      => _('On demand'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_stock_Excess',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_stock_Excess']) ? _('Plenty of stock') : $labels['_stock_Excess']),
                'label'      => _('Excess stock'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_stock_Normal',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_stock_Normal']) ? _('Plenty of stock') : $labels['_stock_Normal']),
                'label'      => _('Normal'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labels_stock_Low',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_stock_Low']) ? _('Limited stock') : $labels['_stock_Low']),
                'label'      => _('Low'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_stock_VeryLow',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_stock_VeryLow']) ? _('Very low stock') : $labels['_stock_VeryLow']),
                'label'      => _('Very low'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_stock_OutofStock',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_stock_OutofStock']) ? _('Out of stock') : $labels['_stock_OutofStock']),
                'label'      => _('Out of Stock'),
                'required'   => true,
                'type'       => 'value'
            ),


        )
    ),

    array(
        'label'      => _('Order button states'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'         => 'Localised_Labels_ordering_order_now',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_ordering_order_now']) ? _('Order now') : $labels['_ordering_order_now']),
                'label'      => _('Order quantity =0'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_ordering_ordered',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_ordering_ordered']) ? _('Ordered') : $labels['_ordering_ordered']),
                'label'      => _('Order quantity >0'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labels_ordering_click_to_update',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_ordering_click_to_update']) ? _('Click to update') : $labels['_ordering_click_to_update']),
                'label'      => _('Order quantity changed'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labels_ordering_ordering_updated',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_ordering_updated']) ? _('Update') : $labels['_ordering_updated']),
                'label'      => _('Order quantity saved'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labelsout_of_stock',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['out_of_stock']) ? _('Out of stock') : $labels['out_of_stock']),
                'label'      => _('Out of stock'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_variant_options',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_variant_options']) ? _('More buying options') : $labels['_variant_options']),
                'label'      => _('More buying options (variants)'),
                'required'   => true,
                'type'       => 'value'
            ),

        )
    ),
    array(
        'label'      => _('Profile'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'         => 'Localised_Labels_balance',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_balance']) ? _('Balance') : $labels['_balance']),
                'label'      => _('Balance (Customer credits)'),
                'required'   => true,
                'type'       => 'value'
            ),



        )
    ),
    array(
        'label'      => _('Order'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'         => 'Localised_Labels_delivery_address_label',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_delivery_address_label']) ? _('Delivery address') : $labels['_delivery_address_label']),
                'label'      => _('Delivery address'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_invoice_address_label',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_invoice_address_label']) ? _('Invoice address') : $labels['_invoice_address_label']),
                'label'      => _('Invoice address'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_items_gross',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_items_gross']) ? _('Items gross') : $labels['_items_gross']),
                'label'      => _('Items gross'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_items_discounts',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_items_discounts']) ? _('Items gross') : $labels['_items_discounts']),
                'label'      => _('Items discounts'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_items_net',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_items_net']) ? _('Items net') : $labels['_items_net']),
                'label'      => _('Items net'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_items_charges',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_items_charges']) ? _('Charges') : $labels['_items_charges']),
                'label'      => _('Charges'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_items_shipping',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_items_shipping']) ? _('Shipping') : $labels['_items_shipping']),
                'label'      => _('Shipping'),
                'required'   => true,
                'type'       => 'value'
            ),

        )
    ),
    array(
        'label'      => _('Forms feedback'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'         => 'Localised_Labels_validation_password_missing',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_validation_password_missing']) ? _('Please enter your password') : $labels['_validation_password_missing']),
                'label'      => _('Password missing'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_validation_minlength_password',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_validation_minlength_password']) ? _('Enter at least 8 characters') : $labels['_validation_minlength_password']),
                'label'      => _('Password too short'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_validation_same_password',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_validation_same_password']) ? _('Enter the same password as above') : $labels['_validation_same_password']),
                'label'      => _('Password not match'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_validation_required',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['validation_required']) ? _('This field is required') : $labels['validation_required']),
                'label'      => _('Required field empty'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_validation_email_invalid',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_validation_email_invalid']) ? _('Invalid email') : $labels['_validation_email_invalid']),
                'label'      => _('Invalid email'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_validation_handle_registered',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_validation_handle_registered']) ? _('Email address is already in registered') : $labels['_validation_handle_registered']),
                'label'      => _('Duplicate email'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_validation_accept_terms',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_validation_accept_terms']) ? _('Please accept our terms and conditions to proceed') : $labels['_validation_accept_terms']),
                'label'      => _('Terms and conditions not accepted'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labels_captcha_fail',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_captcha_fail']) ? _('Robot verification failed, please try again') : $labels['_captcha_fail']),
                'label'      => _('reCAPTCHA fail'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_captcha_missing',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_captcha_missing']) ? _('Please check on the reCAPTCHA box') : $labels['_captcha_missing']),
                'label'      => _('Forgot click reCAPTCHA'),
                'required'   => true,
                'type'       => 'value'
            ),

        )
    ),


    array(
        'label'      => _('Address fields'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'         => 'Localised_Labelsaddress_addressLine1',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['address_addressLine1']) ? _('Address Line 1') : $labels['address_addressLine1']),
                'label'      => _('Address Line 1'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labelsaddress_addressLine2',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['address_addressLine2']) ? _('Address Line 2') : $labels['address_addressLine2']),
                'label'      => _('Address Line 2'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_LabelsdependentLocality_neighborhood',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['dependentLocality_neighborhood']) ? _('Neighborhood') : $labels['dependentLocality_neighborhood']),
                'label'      => _('Neighborhood'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_LabelsdependentLocality_district',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['dependentLocality_district']) ? _('District') : $labels['dependentLocality_district']),
                'label'      => _('District'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_LabelsdependentLocality_townland',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['dependentLocality_townland']) ? _('Townland') : $labels['dependentLocality_townland']),
                'label'      => _('Townland (Ireland)'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_LabelsdependentLocality_village_township',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['dependentLocality_village_township']) ? _('Village (Township)') : $labels['dependentLocality_village_township']),
                'label'      => _('Village (Township)'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_LabelsdependentLocality_suburb',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['dependentLocality_suburb']) ? _('Suburb') : $labels['dependentLocality_suburb']),
                'label'      => _('Suburb'),
                'required'   => true,
                'type'       => 'value'
            ),


            array(
                'id'         => 'Localised_Labelslocality_city',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['locality_city']) ? _('City') : $labels['locality_city']),
                'label'      => _('City'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labelslocality_suburb',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['locality_suburb']) ? _('locality_city') : $labels['locality_suburb']),
                'label'      => _('locality_city'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labelslocality_district',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['locality_district']) ? _('District') : $labels['locality_district']),
                'label'      => _('District'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labelslocality_post_town',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['locality_post_town']) ? _('Post town') : $labels['locality_post_town']),
                'label'      => _('Post town (United Kingdom)'),
                'required'   => true,
                'type'       => 'value'
            ),


            array(
                'id'         => 'Localised_LabelsadministrativeArea_state',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['administrativeArea_state']) ? _('State') : $labels['administrativeArea_state']),
                'label'      => _('State'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_LabelsadministrativeArea_province',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['administrativeArea_province']) ? _('Province') : $labels['administrativeArea_province']),
                'label'      => _('Province'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_LabelsadministrativeArea_island',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['administrativeArea_island']) ? _('Island') : $labels['administrativeArea_island']),
                'label'      => _('Island'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_LabelsDepartment',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['Department']) ? _('Department') : $labels['Department']),
                'label'      => _('Department (country subdivision)'),
                'required'   => true,
                'type'       => 'value'
            ),


            array(
                'id'         => 'Localised_LabelsadministrativeArea_county',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['administrativeArea_county']) ? _('County') : $labels['administrativeArea_county']),
                'label'      => _('County'),
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_LabelsadministrativeArea_area',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['administrativeArea_area']) ? _('Area') : $labels['administrativeArea_area']),
                'label'      => _('Area (country subdivision)'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_LabelsadministrativeArea_prefecture',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['administrativeArea_prefecture']) ? _('Prefecture') : $labels['administrativeArea_prefecture']),
                'label'      => _('Prefecture'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_LabelsadministrativeArea_district',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['administrativeArea_district']) ? _('District') : $labels['administrativeArea_district']),
                'label'      => _('District (country subdivision)'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_LabelsadministrativeArea_emirate',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['administrativeArea_emirate']) ? _('Emirate') : $labels['administrativeArea_emirate']),
                'label'      => _('Emirate'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_LabelspostalCode_postal',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['postalCode_postal']) ? _('Postal code') : $labels['postalCode_postal']),
                'label'      => _('Postal code'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labelsaddress_country',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['address_country']) ? _('Country') : $labels['address_country']),
                'label'      => _('Country'),
                'required'   => true,
                'type'       => 'value'
            )

        )
    ),

    array(
        'label'      => _('Unsubscribe'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'         => 'Localised_Labels_unsubscribe_text',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_unsubscribe_text']) ? _('If you do not wish to receive more marketing emails from us') : $labels['_unsubscribe_text']),
                'label'      => _('Unsubscribe text'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_unsubscribe_basket_emails_text',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_unsubscribe_basket_emails_text']) ? _('If you do not wish to receive more basket engagement emails from us') : $labels['_unsubscribe_basket_emails_text']),
                'label'      => _('Unsubscribe basket emails text'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_unsubscribe_link',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_unsubscribe_link']) ? _('Unsubscribe here') : $labels['_unsubscribe_link']),
                'label'      => _('Unsubscribe link text'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_remove_from_junk_text',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_remove_from_junk_text']) ? _('You want to be removed from this mailing list?') : $labels['_remove_from_junk_text']),
                'label'      => _('Stop junk mail text'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_remove_from_junk_link',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_remove_from_junk_link']) ? _('Click here to be removed') : $labels['_remove_from_junk_link']),
                'label'      => _('Stop junk mail link text'),
                'required'   => true,
                'type'       => 'value'
            ),


            array(
                'id'         => 'Localised_Labels_removed_from_mailing_list',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_removed_from_mailing_list']) ? _('You have been removed from the mailing list') : $labels['_removed_from_mailing_list']),
                'label'      => _('Removed from mailing list'),
                'required'   => true,
                'type'       => 'value'
            ),

        )
    ),


    array(
        'label'      => _('General'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'         => 'Localised_Labels_choose_one',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_choose_one']) ? _('Please, choose one') : $labels['_choose_one']),
                'label'      => _('Pick an option'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_we_will_contact_you',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_we_will_contact_you']) ? _('We will contact you') : $labels['_we_will_contact_you']),
                'label'      => _('Unknown shipping amount'),
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_see_also',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_see_also']) ? _('See also') : $labels['_see_also']),
                'label'      => _('See also'),
                'required'   => true,
                'type'       => 'value'
            ),


        )
    ),

    array(
        'label'      => _('Discounts'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'         => 'Localised_Labels_gold_reward_member',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_gold_reward_member']) ? _('Gold Reward Member') : $labels['_gold_reward_member']),
                'label'      => 'Gold reward member',
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_gold_reward_member_until',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_gold_reward_member_until']) ? _('Until') : $labels['_gold_reward_member_until']),
                'label'      => 'GR <b>'.until.'</b> ...',
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labels_more_info',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_more_info']) ? _('More info') : $labels['_more_info']),
                'label'      => 'More info',
                'required'   => true,
                'type'       => 'value'
            ),



            array(
                'id'         => 'Localised_Labels_gold_reward_url',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_gold_reward_url']) ? '' : $labels['_gold_reward_url']),
                'label'      => 'GR url include https://...',
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labels_gold_reward_active',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_gold_reward_active']) ? 'Gold Reward Discount applied' : $labels['_gold_reward_active']),
                'label'      => 'GR active',
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labels_gold_reward_inactive',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_gold_reward_inactive']) ? 'Become a Gold Reward Member today' : $labels['_gold_reward_inactive']),
                'label'      => 'GR inactive',
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labels_first_order_bonus_url',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_first_order_bonus_url']) ? '' : $labels['_first_order_bonus_url']),
                'label'      => 'FOB url include https://... ',
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labels_first_order_bonus_active',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_first_order_bonus_active']) ? 'First Order Discount applied' : $labels['_first_order_bonus_active']),
                'label'      => 'FOB active',
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labels_first_order_bonus_inactive',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_first_order_bonus_inactive']) ? 'First Order Discount level not reached.' : $labels['_first_order_bonus_inactive']),
                'label'      => 'FOB inactive',
                'required'   => true,
                'type'       => 'value'
            ),

            array(
                'id'         => 'Localised_Labels_gra_t1',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_gra_t1']) ? 'Gold Reward Amnesty Week' : $labels['_gra_t1']),
                'label'      => 'Gold Reward Amnesty Week',
                'required'   => true,
                'type'       => 'value'
            ),
            array(
                'id'         => 'Localised_Labels_gra_t2',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_gra_t2']) ? 'GR for all! until' : $labels['_gra_t2']),
                'label'      => 'GR for all! until',
                'required'   => true,
                'type'       => 'value'
            ),


        )
    ),

    array(
        'label'      => _('Greetings'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'         => 'Localised_Labels_hello',
                'edit'       => ($supervisor_edit ? 'string' : ''),
                'right_code' => 'WS-'.$store_key,
                'value'      => (empty($labels['_hello']) ? _('Hello') : $labels['_hello']),
                'label'      => 'hello',
                'required'   => true,
                'type'       => 'value'
            ),



        )
    ),

);


