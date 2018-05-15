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
    'label'      => _('Beneficiary'),
    'show_title' => true,
    'fields'     => array(

        array(
            'id'              => 'Who',
            'edit'            => 'no_icon',
            'value'           => false,
            'formatted_value' => '
<div class="button_radio_options">
<span id="Entitled_To_Anyone_field" field_type="button_radio_options" field="Entitled_To_Anyone" onclick="toggle_new_deal_entitled_to(this)" class="button selected" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Anyone').'</span>
<span id="Entitled_To_Customer_field" field_type="button_radio_options" field="Entitled_To_Customer" onclick="toggle_new_deal_entitled_to(this)" class="button" style="border:1px solid #ccc;padding:5px;margin:4px">'._('A customer').'</span>
<span id="Entitled_To_Customer_Category_field" field_type="button_radio_options" field="Entitled_To_Customer_Category" onclick="toggle_new_deal_entitled_to(this)" class="button hide" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Customers in a category').'</span>
<span id="Entitled_To_Customer_List_field" field_type="button_radio_options" field="Entitled_To_Customer_List" onclick="toggle_new_deal_entitled_to(this)" class="button" style="border:1px solid #ccc;padding:5px;margin:4px">'._("Customers' in a list").'</span>
</div>
',
            'label'           => _('Who is entitled to this offer'),
            'required'        => false,
            'type'            => 'value'
        ),

        array(
            'edit'        => 'customer',
            'class'       => 'hide',
            'id'          => 'Customer',
            'value'       => '',
            'label'       => _('Customer'),
            'invalid_msg' => get_invalid_message('string'),
            'required'    => false,
            'type'        => ''
        ),
        array(
            'edit'        => 'no_icon',
            'class'       => 'hide',
            'id'          => '_Customer_Selected',
            'value'       => '',
            'formatted_value' =>'',
            'label'       => _('Customer').' <i onclick="select_other_customer()" class="fa fa-eraser padding_left_5 button" title="'._('Choose other customer').'" ></i>'  ,
            'invalid_msg' => '',
            'required'    => false,
            'type'        => 'value'
        ),
        array(
            'edit'        => 'customer_list',
            'class'       => 'hide',
            'id'          => 'customer_list',
            'value'       => '',
            'label'       => _('Customer list'),
            'invalid_msg' => get_invalid_message('string'),
            'required'    => false,
            'type'        => 'value'
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
            'formatted_value' => '
<div class="button_radio_options">
<span id="Trigger_Voucher_field" field_type="button_radio_options" field="Trigger_Voucher" class="button" onclick="toggle_new_deal_trigger(this)"  style="border:1px solid #ccc;padding:5px;margin:4px">'._('Voucher').'</span>  
<span id="Trigger_Asset_field" field_type="button_radio_options" field="Trigger_Asset" class="button" onclick="toggle_new_deal_trigger(this)"  style="border:1px solid #ccc;padding:5px;margin:4px">'._('Order product/category').'</span> 
<span id="Trigger_Order_Nth_field" field_type="button_radio_options" field="Trigger_Order_Nth" class="button" onclick="toggle_new_deal_trigger(this)"  style="border:1px solid #ccc;padding:5px;margin:4px">'._('Customer nth order').'</span>
<span id="Trigger_Any_field" field_type="button_radio_options" field="Trigger_Any" class="button" onclick="toggle_new_deal_trigger(this)"  style="border:1px solid #ccc;padding:5px;margin:4px">'._('All orders').'</span>
</div>
',
            'label'           => _('When this offer is applied'),
            'required'        => false,
            'type'            => 'value'
        ),


        array(
            'id'              => 'Deal_Voucher_Type',
            'edit'            => 'no_icon',
            'class'           => 'hide',
            'value'           => false,
            'formatted_value' => '<span class="button" onclick="toggle_voucher_auto_code(this)"  field="Deal_Voucher_Type"  style="margin-right:40px"><i class=" fa fa-fw fa-toggle-on" aria-hidden="true"></i> <span class="discreet">'._('Automatically generated')
                .'</span></span>',
            'label'           => _('Voucher code'),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'edit'              => ($edit ? 'string' : ''),
            'id'                => 'Deal_Voucher_Code',
            'class'             => 'hide',
            'value'             => '',
            'label'             => ucfirst($object->get_field_label('Voucher code')).' <i onClick="set_voucher_code_as_auto()" class="fa fa-magic button padding_left_10" title="'._('Automatically generated voucher code').'"></i>',
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => false,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'type'              => 'value'
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
            'edit'        => ($edit ? 'smallint_unsigned' : ''),
            'id'          => 'Asset_Min_Qty',
            'class'       => 'hide',
            'value'       => '',
            'label'       => _('Minimum outers ordered'),
            'invalid_msg' => get_invalid_message('smallint_unsigned'),
            'required'    => false,
            'type'        => 'value'
        ),

        array(
            'edit'            => 'no_icon',
            'class'           => 'hide',
            'id'              => 'add_extra_term',
            'value'           => '',
            'formatted_value' => '<span onClick="show_extra_term()" class="button "><i class="fa fa-plus fa-fw"></i> '._('Add term').'</span>',
            'label'           => '',
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'type'            => 'value'
        ),

        array(
            'id'              => 'Extra_Terms',
            'edit'            => 'no_icon',
            'class'           => 'hide',
            'value'           => false,
            'formatted_value' => '
<div class="button_radio_options">
<span id="Trigger_Voucher_field" field_type="button_radio_options" field="Trigger_Extra_Amount_Net" class="button" onclick="toggle_new_deal_extra_trigger(this)"  style="border:1px solid #ccc;padding:5px;margin:4px">'._('Minimum order').' ('._('Items net').')</span>  
</div>
',
            'label'           => _('extra term'),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'edit'        => ($edit ? 'amount' : ''),
            'id'          => 'Trigger_Extra_Amount_Net',
            'class'       => 'hide',
            'value'       => '',
            'label'       => _('Minimum items net amount'),
            'invalid_msg' => get_invalid_message('amount'),
            'required'    => false,
            'type'        => 'value'
        )


    ),


);


?>
