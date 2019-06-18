<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2019 at 01:55:32 GMT+8, Kuala Lumpur, Malaysia

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
    'Percentage_Off' => _('Order'),
    'Get_Item_Free'  => _('Product'),
    'Category'       => _('Product category'),
    'Customer'       => _('Customer')

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
            'label'             => _('Promotion name'),
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => true,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'type'              => 'value'
        ),

        array(
            'id'   => 'Deal_Voucher_Auto_Code',
            'edit' => 'custom',

            'class'    => '',
            'value'    => false,
            'custom'   => '<span class="button value"  field_type="toggle" onclick="toggle_voucher_auto_code(this)"  field="Deal_Voucher_Auto_Code"  style="margin-right:40px"><i id="toggle_voucher_auto_code_icon" class="Deal_Voucher_Auto_Code_toggle fa fa-fw fa-toggle-on" aria-hidden="true"></i> <span class="discreet">'
                ._('Automatically generated').'</span></span>',
            'label'    => _('Voucher code'),
            'required' => false,
            'type'     => ''
        ),
        array(
            'edit'  => ($edit ? 'string' : ''),
            'id'    => 'Deal_Voucher_Code',
            'class' => 'hide',

            'value'             => '',
            'label'             => ucfirst($object->get_field_label('Voucher code')).' <i onClick="set_voucher_code_as_auto()" class="fa fa-magic button padding_left_10" title="'._('Automatically generated voucher code').'"></i>',
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => false,
            'placeholder'       => _('Voucher code'),
            'server_validation' => json_encode(array(
                'tipo' => 'check_for_duplicates',
                'parent'     => 'store',
                'parent_key' => $options['parent_object']->get('Store Key'),


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
            'edit'        => ($edit ? 'amount' : ''),
            'id'          => 'Trigger_Extra_Amount_Net',
            'value'       => '',
            'label'       => _('Minimum items net amount'),
            'invalid_msg' => get_invalid_message('amount'),
            'required'    => true,
            'type'        => 'value'
        ),

    ),


);


$object_fields[] = array(
    'label'      => _('Offer'),
    'show_title' => true,
    'fields'     => array(

        array(
            'id'       => 'Type',
            'edit'     => 'custom',
            'value'    => '',
            'custom'   => '
<div class="button_radio_options">
<span id="Deal_Type_Percentage_Off_field" field_type="button_radio_options" field="Deal_Type_Percentage_Off" onclick="toggle_voucher_deal_type(this)" class="button value" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Percentage off').'</span>
<span id="Deal_Type_Amount_Off_field" field_type="button_radio_options" field="Deal_Type_Amount_Off" onclick="toggle_voucher_deal_type(this)" class="button value" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Amount off').'</span>
<span id="Deal_Type_Get_Item_Free_field" field_type="button_radio_options" field="Deal_Type_Get_Item_Free" onclick="toggle_voucher_deal_type(this)" class="button value" style="border:1px solid #ccc;padding:5px;margin:4px">'.sprintf(
                    _('Get product free'), '<span>2</span>', 1
                ).'</span>
<span id="Deal_Type_Shipping_Off_field" field_type="button_radio_options" field="Deal_Type_Shipping_Off" onclick="toggle_voucher_deal_type(this)" class="button hide value" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Discounted shipping').'  </span>
</div>
',
            'label'    => _('Choose offer'),
            'required' => true,
            'type'     => ''
        ),

        array(
            'id'          => 'Percentage',
            'render'      => false,
            //'class'       => 'Deal_Type',
            'edit'        => 'percentage',
            'value'       => '',
            'placeholder' => '10%',

            //   'custom'   => '<input id="Percentage_Off_field" field_type="input_with_field" field="Percentage_Off" value="" class="value  input_field" style="margin-left:5px;width:30px" /> ',
            'label'       => _('% off'),
            'required'    => false,
            'type'        => 'value'
        ),

        array(
            'id'     => 'Amount_Off',
            'render' => false,
            //'class'  => 'Deal_Type',
            'edit'   => 'amount',
            'value'  => '',
            'placeholder' => _('Amount'),

           // 'custom'   => '<input id="Amount_Off_field" field_type="input_with_field" field="Amount_Off" value="" class="value input_field" style="margin-left:5px;width:90px"  placeholder="'._('Amount').'" />',
            'label'    => _('Amount off'),
            'required' => false,
            'type'     => 'value'
        ),

        array(
            'id'                       => 'Get_Item_Free_Product',
            'render' => false,
            'edit'                     => 'dropdown_select',
            'scope'                    => 'products',
            'parent'                   => 'store',
            'parent_key'               => $options['store_key'],
            'value'                    => '',
            'formatted_value'          => '',
            'stripped_formatted_value' => '',
            'placeholder'                    => _('Product code'),
            'label'                    => _('Product'),
            'required'                 => true,
            'type'                     => 'value'


        ),

        array(
            'id'     => 'Get_Item_Free_Quantity',
            'render' => false,


            'edit'  => 'smallint_unsigned',
            'value' => '1',
            'placeholder'=>_('Quantity'),

           // 'custom'   => '<input id="Get_Item_Free_Quantity_field" field_type="input_with_field" field="Get_Item_Free_Quantity" value="1" class="value input_field" style="margin-left:5px;width:60px"  placeholder="'._('Qty').'" />',
            'label'    => _('Quantity'),
            'required' => false,
            'type'     => 'value'
        ),

    ),


);


