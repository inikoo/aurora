<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 February 2018 at 14:14:04 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

$new = true;


$options_trigger = array(
    'Order'    => _('Order'),
    'Product'  => _('Product'),
    'Category' => _('Product category'),
    'Customer' => _('Customer')

);


$options_offer_type = array(
    'Percentage_Off'   => _('Order'),
    'Buy_n_get_n_free' => _('Product'),
    'Category'         => _('Product category'),
    'Customer'         => _('Customer')

);

$object_fields = array();


$object_fields[] = array(
    'label'      => _('Id'),
    'show_title' => true,
    'fields'     => array(

        array(
            'edit'              => ($edit ? 'string' : ''),
            'id'                => 'Deal_Name',
            'value'             => $object->get('Deal Name'),
            'label'             => _('Private code'),
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => true,
            'server_validation' => json_encode(
                array(
                    'tipo' => 'check_for_duplicates',
                    'parent'     => 'store',
                    'parent_key' => $options['store_key'],
                    'object'     => 'Deal',
                )
            ),
            'type'              => 'value'
        ),


    ),


);


$object_fields[] = array(
    'label'      => _('Terms'),
    'show_title' => true,
    'fields'     => array(


        array(

            'id'              => 'Deal_Interval',
            'edit'            => 'date_interval',
            'class'           => 'valid',
            'time'            => array(
                'From' => '00:00:00',
                'To'   => '23:59:59'
            ),
            'value'           => array(
                'From' => date('Y-m-d'),
                'To'   => date('Y-m-d', strtotime('now + 7 days'))
            ),
            'formatted_value' => array(
                'From' => date('d/m/Y'),
                'To'   => date('d/m/Y', strtotime('now + 7 days'))
            ),
            'placeholder'     => array(
                'From' => _('from'),
                'To'   => _('until')
            ),
            'label'           => _('Duration'),
            'invalid_msg'     => get_invalid_message('date'),
            'required'        => true,
            'type'            => ''
        ),

        array(
            'id'                       => 'Customer_Key',
            'edit'                     => 'dropdown_select',
            'scope'                    => 'customers',
            'parent'                   => 'store',
            'parent_key'               => $store->id,
            'value'                    => '',
            'formatted_value'          => '',
            'stripped_formatted_value' => '',
            'label'                    => _('Customer'),
            'required'                 => true,
            'type'                     => 'value'


        ),



    ),


);

$object_fields[] = array(
    'label'      => _('Terms'),
    'show_title' => true,
    'fields'     => array(


        array(
            'id'              => 'Terms',
            'edit'            => 'no_icon',
            'value'           => false,
            'formatted_value' => '<input id="Terms" type="hidden" value="">
<div class="button_radio_options">
<span  field_type="button_radio_options" field="All_products" class="button" onclick="toggle_customer_term_type(this)"  style="border:1px solid #ccc;padding:5px;margin:4px">'._('All products').'</span>  
<span  field_type="button_radio_options" field="Product_Category" class="button" onclick="toggle_customer_term_type(this)"  style="border:1px solid #ccc;padding:5px;margin:4px">'._('Category/Product').'</span> 
</div>
',
            'label'           => _('When this offer is applied'),
            'required'        => false,
            'type'            => 'value'
        ),



        array(
            'edit'        => 'asset',
            'class'       => 'hide',
            'id'          => 'Asset',
            'value'       => '',
            'label'       => _('Product/Category'),
            'invalid_msg' => get_invalid_message('string'),
            'required'    => false,
            'type'        => 'value'
        ),



        array(
            'edit'        => ($edit ? 'amount' : ''),
            'id'          => 'Trigger_Extra_Amount_Net',
            'class'       => 'hide',
            'value'       => '',
            'placeholder'=>_('amount'),
            'label'       => _('Minimum order (items gross net)'),
            'invalid_msg' => get_invalid_message('amount'),
            'required'    => false,
            'type'        => 'value'
        ),


        array(
            'edit'        => ($edit ? 'amount' : ''),
            'id'          => 'Trigger_Extra_Items_Amount_Net',
            'class'       => 'hide',
            'value'       => '',
            'placeholder'=>_('amount'),

            'label'       => _('Minimum category/product ordered amount'),
            'invalid_msg' => get_invalid_message('amount'),
            'required'    => false,
            'type'        => 'value'
        ),










    ),


);

$object_fields[] = array(
    'label'      => _('Offer'),
    'show_title' => true,
    'class'      => 'deal_type_title hide',
    'fields'     => array(



        array(
            'id'    => 'Allowance_Percentage',
            'class' => 'Allowance_Percentage',
            'edit'  => 'custom',
            'class' => 'hide',
            'value' => '%',

            'custom'   => '<input id="Percentage_Off_field" field_type="input_with_field" field="Percentage_Off" value="10" class="value valid" style="margin-left:5px;width:30px" /> %',
            'label'    => '',
            'required' => false,
            'type'     => ''
        ),



    ),


);



?>
