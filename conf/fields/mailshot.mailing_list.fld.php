<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 July 2018 at 19:01:07 GMT+8fddss Kuala Lumpur, Malaysia

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}
$new = true;


$new = true;


$options_list_type = array(
    'Static'  => _('Static'),
    'Dynamic' => _('Dynamic'),

);

$object_fields = array();


    $object_fields[] = array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(
            array(
                'edit'            => ($edit ? 'string' : ''),
                'id'              => 'Email_Campaign_Name',
                'value'           => $object->get('Email Campaign Name'),
                'formatted_value' => $object->get('Name'),

                'label'             => ucfirst($object->get_field_label('Email Campaign Name')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(
                    array(
                        'tipo' => 'check_for_duplicates',
                        'key'  => $object->id
                    )
                ),
                'type'              => 'value'
            )
        )
    );



$object_fields[] = array(
    'label'      => _('Contact status'),
    'show_title' => true,
    'fields'     => array(


        array(
            'id'              => 'Customer_Status',
            'edit'            => 'no_icon',
            'value'           => '',
            'formatted_value' => '<span id="Customer_Status_Active_field" class="button value valid" onclick="toggle_list_elements(this)" field_type="elements" field="Customer_Status_Active"  style="margin-right:40px"><i class=" far fa-fw fa-check-square" aria-hidden="true"></i> <span class="unselectable">'
                ._('Active').'</span></span>'
                .'<span id="Customer_Status_Loosing_field" onclick="toggle_list_elements(this)"  field_type="elements"  field="Customer_Status_Loosing" class="button value valid" style="margin-right:40px"><i class=" far fa-fw fa-check-square" aria-hidden="true"></i> <span class="unselectable">'
                ._('Loosing').'</span></span>'
                .'<span id="Customer_Status_Lost_field" onclick="toggle_list_elements(this)"  field_type="elements"  field="Customer_Status_Lost" class="value valid button" style="margin-right:40px"><i class=" far fa-fw fa-check-square" aria-hidden="true"></i> <span class="unselectable">'
                ._('Lost').'</span></span>'
                     .'<span id="Customer_Status_NeverOrder_field" onclick="toggle_list_elements(this)"  field_type="elements"  field="Customer_Status_NeverOrder" class="value valid button" style="margin-right:40px"><i class=" far fa-fw fa-check-square" aria-hidden="true"></i> <span class="unselectable">'
            ._('Never order').'</span></span>',
            'label'           => _('Customer status'),
            'required'        => false,
            'type'            => ''
        ),

        array(

            'id'              => 'Register_Date',
            'edit'            => 'date_interval',
            'class'           => 'valid',
            'time'            => array(
                'From' => '00:00:00',
                'To'   => '23:59:59'
            ),
            'value'           => array(
                'From' => '',
                'To'   => ''
            ),
            'formatted_value' => array(
                'From' => '',
                'To'   => ''
            ),
            'placeholder'     => array(
                'From' => _('from'),
                'To'   => _('until')
            ),
            'label'           => _('Registered interval'),
            'invalid_msg'     => get_invalid_message('date'),
            'required'        => false,
            'type'            => ''
        ),


    ),


);

$object_fields[] = array(
    'label'      => _('Contact properties'),
    'show_title' => true,
    'fields'     => array(


        array(
            'edit'        => 'string',
            'class'       => 'width_400',
            'id'          => 'Location',
            'value'       => '',
            'label'       => _('Geographical location'),
            'invalid_msg' => get_invalid_message('string'),
            'required'    => false,
            'type'        => 'value'
        ),


        array(
            'id'              => 'With_valid',
            'edit'            => 'no_icon',
            'value'           => $object->get('Customer Subscriptions'),
            'formatted_value' => '
<span id="With_Email_field" class="button value valid " onclick="toggle_list_with(this)" field_type="with_field" field="With_Email"  style="margin-right:40px"><i class=" fa fa-fw fa-random" aria-hidden="true"></i> <span class="unselectable">'._('Email').'</span></span>'
                .'
<span id="With_Mobile_field" onclick="toggle_list_with(this)"  field_type="with_field"  field="With_Mobile" class="button value valid" style="margin-right:40px"><i class=" fa fa-fw fa-random" aria-hidden="true"></i> <span class="unselectable">'._('Mobile')
                .'</span></span>'.'
<span id="With_Telephone_field" onclick="toggle_list_with(this)"  field_type="with_field"  field="With_Telephone" class="button value valid" style="margin-right:40px"><i class=" fa fa-fw fa-random" aria-hidden="true"></i> <span class="unselectable">'._('Telephone')
                .'</span></span>'.'
<span id="With_Tax_Number_field" onclick="toggle_list_with(this)"  field_type="with_field"  field="With_Tax_Number" class="button value valid" style="margin-right:40px"><i class=" fa fa-fw fa-random" aria-hidden="true"></i> <span class="unselectable">'._('Tax Number')
                .'</span></span>'.'
<span id="With_Credits_field" onclick="toggle_list_with(this)"  field_type="with_field"  field="With_Credits" class="button value valid" style="margin-right:40px"><i class=" fa fa-fw fa-random" aria-hidden="true"></i> <span class="unselectable">'._('Credits')



                .'</span></span>',


            'label'    => '<small>'._('Any value').' <i class=" fa fa-fw fa-random" aria-hidden="true"></i> | '._('with').' <i class=" fa fa-fw fa-toggle-on" aria-hidden="true"></i>  | '._('with out')
                .' <i class=" fa fa-fw fa-toggle-off" aria-hidden="true"></i></small>',
            'required' => false,
            'type'     => ''
        ),

    ),


);


$object_fields[] = array(
    'label'      => _('Ordered  products'),
    'show_title' => true,
    'fields'     => array(


        array(
            'edit'        => 'string',
            'class'       => 'width_400',
            'id'          => 'Assets',
            'value'       => '',
            'label'       => _('Products').'/'._('Categories'),
            'invalid_msg' => get_invalid_message('string'),
            'required'    => false,
            'type'        => 'value'
        ),


        array(

            'id'              => 'Ordered_Date',
            'edit'            => 'date_interval',
            'class'           => 'valid',
            'time'            => array(
                'From' => '00:00:00',
                'To'   => '23:59:59'
            ),
            'value'           => array(
                'From' => '',
                'To'   => ''
            ),
            'formatted_value' => array(
                'From' => '',
                'To'   => ''
            ),
            'placeholder'     => array(
                'From' => _('from'),
                'To'   => _('until')
            ),
            'label'           => _('Ordered interval'),
            'invalid_msg'     => get_invalid_message('date'),
            'required'        => false,
            'type'            => ''
        ),

        array(
            'id'              => 'Order_State',
            'edit'            => 'no_icon',
            'class'           => 'super_discreet',
            'value'           => '',
            'formatted_value' => '<span id="Order_State_Basket_field" class="button value valid" onclick="toggle_list_elements(this)" field_type="elements" field="Order_State_Basket"  style="margin-right:40px"><i class=" fa fa-fw fa-check-square" aria-hidden="true"></i> <span class="unselectable">'
                ._('Basket').'</span></span>'
                .'<span id="Order_State_Processing_field" onclick="toggle_list_elements(this)"  field_type="elements"  field="Order_State_Processing" class="button value valid" style="margin-right:40px"><i class=" fa fa-fw fa-check-square" aria-hidden="true"></i> <span class="unselectable">'
                ._('Processing').'</span></span>'
                .'<span id="Order_State_Dispatched_field" onclick="toggle_list_elements(this)"  field_type="elements"  field="Order_State_Dispatched" class="value valid button" style="margin-right:40px"><i class=" fa fa-fw fa-check-square" aria-hidden="true"></i> <span class="unselectable">'
                ._('Dispatched').'</span></span>'
                .'<span id="Order_State_Cancelled_field" onclick="toggle_list_elements(this)"  field_type="elements"  field="Order_State_Cancelled" class="value valid button" style="margin-right:40px"><i class=" fa fa-fw fa-check-square" aria-hidden="true"></i> <span class="unselectable">'
                ._('Cancelled').'</span></span>',
            'label'           => _('Order state'),
            'required'        => false,
            'type'            => ''
        ),


    ),


);

$object_fields[] = array(
    'label'      => '',
    'show_title' => false,
    'fields'     => array(


        array(
            'edit'            => 'no_icon',
            'id'              => '',
            'formatted_value' => '<span  class=" button discreet italic"  onclick="estimate_number_list_items()" >'._('Calculate estimated number of recipients').'</span> <span class="calculated_number_list_items hide"></span>',
            'label'           => '',
            'required'        => false,
            'type'            => ''
        ),
        array(
            'edit'            => 'no_icon',
            'id'              => '',
            'formatted_value' => '<span  class="save changed valid button "  onclick="set_mailing_list()" >'._('Set mailing list').' <i class="fa fa-cloud"></i></span>',
            'label'           => '',
            'required'        => false,
            'type'            => ''
        ),


    ),


);

$list_fields = $object_fields;
unset($list_fields[0]);


?>
