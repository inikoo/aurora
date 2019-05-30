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
    'label'      => _('Customer'),
    'show_title' => true,
    'fields'     => array(



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
            'formatted_value' => '
<div class="button_radio_options">
<span id="Trigger_Voucher_field" field_type="button_radio_options" field="Trigger_Voucher" class="button" onclick="toggle_new_deal_trigger(this)"  style="border:1px solid #ccc;padding:5px;margin:4px">'._('All products').'</span>  
<span id="Trigger_Asset_field" field_type="button_radio_options" field="Trigger_Asset" class="button" onclick="toggle_new_deal_trigger(this)"  style="border:1px solid #ccc;padding:5px;margin:4px">'._('Category').'</span> 
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
            'formatted_value' => '<span onClick="show_extra_term()" class="button "><i class="fa fa-plus fa-fw"></i> '._('Add minimum order').'</span>',
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
        ),






    ),


);

$object_fields[] = array(
    'label'      => _('Offer'),
    'show_title' => true,
    'class'      => 'deal_type_title hide',
    'fields'     => array(

        array(
            'id'       => 'Type',
            'edit'     => 'custom',
            'class'    => 'hide',
            'value'    => false,
            'custom'   => '
<div class="button_radio_options">
<span id="Deal_Type_Percentage_Off_field" field_type="button_radio_options" field="Deal_Type_Percentage_Off" onclick="toggle_category_deal_type(this)" class="button value" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Percentage off').'</span>
</div>
',
            'label'    => _('Choose offer'),
            'required' => false,
            'type'     => ''
        ),

        array(
            'id'    => 'Percentage',
            'class' => 'Deal_Type',
            'edit'  => 'custom',
            'class' => 'hidec',
            'value' => '%',

            'custom'   => '<input id="Percentage_Off_field" field_type="input_with_field" field="Percentage_Off" value="10" class="value valid" style="margin-left:5px;width:30px" /> %',
            'label'    => '',
            'required' => false,
            'type'     => ''
        ),



    ),


);



?>
