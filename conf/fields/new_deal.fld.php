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
            'label'             => ucfirst($object->get_field_label('Deal Name')),
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => true,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'type'              => 'value'
        )


    ),


);






$object_fields[] = array(
    'label'      => _('Terms'),
    'show_title' => true,
    'fields'     => array(

        array(
            'id'              => 'Who',
            'edit'            => 'no_icon',
            'value'           => false,
            'formatted_value' => '
<span class="button" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Anyone').'</span>
<span class="button" style="border:1px solid #ccc;padding:5px;margin:4px">'._('A customer').'</span>
<span class="button" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Customers in a category').'</span>
<span class="button" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Customers in a list').'</span>

',
            'label'           => _('Who is entitled to this offer'),
            'required'        => false,
            'type'            => 'value'
        ),

        array(
            'id'              => 'Terms',
            'edit'            => 'no_icon',
            'value'           => false,
            'formatted_value' => '<span class="button" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Voucher').'</span>  <span class="button" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Buy a product').'</span> <span class="button" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Buy in category').'</span>
<span class="button" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Order amount').'</span>
<span class="button" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Customer nth order').'</span>
<span class="button" style="border:1px solid #ccc;padding:5px;margin:4px">'._('No conditions').'</span>

',
            'label'           => _('When this offer is applied'),
            'required'        => false,
            'type'            => 'value'
        ),


        array(
            'id'              => 'Deal_Voucher_Type',
            'edit'            => 'no_icon',
            'value'           => false,
            'formatted_value' => '<span class="button" onclick="toggle_subscription(this)"  field="Deal_Voucher_Type"  style="margin-right:40px"><i class=" fa fa-fw fa-toggle-on" aria-hidden="true"></i> <span class="discreet">'._('Automatically generated').'</span></span>',
            'label'           => _('Voucher code'),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'edit'              => ($edit ? 'string' : ''),
            'id'                => 'Deal_Voucher_Code',
            'value'             => '',
            'label'             => ucfirst($object->get_field_label('Voucher code')).' <i class="fa fa-magic button padding_left_10" title="'._('Automatically generated voucher code').'"></i>',
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => false,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'type'              => 'value'
        )


    ),


);



?>
