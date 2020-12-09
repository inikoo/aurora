<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 May 2018 at 11:22:48 CEST ,Mijas Costa, SPAIN
 Copyright (c) 2018, Inikoo

 Version 3.0
*/

$new = true;

$from        = date('y-m-d');
$from_locale = date('d/m/y');
$from_mmddyy = date('m/d/Y');
$to_locale   = '';
$to_mmddyy   = '';

$object_fields = array();


$object_fields[] = array(
    'label'      => _('Offer'),
    'show_title' => true,
    'fields'     => array(


        array(
            'edit'              => ($edit ? 'string' : ''),
            'id'                => 'Deal_Name',
            'value'             => '',
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

    ),


);

$object_fields[] = array(
    'label'      => _('Beneficiary'),
    'class'      => '',
    'show_title' => true,
    'fields'     => array(


        array(
            'id'   => 'Who',
            'edit' => 'custom',

            'value'    => false,
            'custom'   => '
<div class="button_radio_options">
<span id="Entitled_To_Anyone_field" field_type="button_radio_options" field="Entitled_To_Anyone" onclick="toggle_new_category_deal_entitled_to(this)" class="button value" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Open to all').'</span>
<span id="Entitled_To_Voucher_field" field_type="button_radio_options" field="Entitled_To_Voucher" onclick="toggle_new_category_deal_entitled_to(this)" class="button value hide" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Voucher').'</span>

</div>
',
            'label'    => _('Who is entitled to this offer'),
            'required' => false,
            'type'     => 'value'
        ),

        array(
            'id'   => 'Deal_Voucher_Auto_Code',
            'edit' => 'custom',

            'class'    => 'hide',
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
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'type'              => 'value'
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
<span id="Deal_Type_Buy_n_get_n_free_field" field_type="button_radio_options" field="Deal_Type_Buy_n_get_n_free" onclick="toggle_category_deal_type(this)" class="button value" style="border:1px solid #ccc;padding:5px;margin:4px">'.sprintf(
                    _('Buy %s get %s free'), '<span>2</span>', 1
                ).'</span>
<span id="Deal_Type_Buy_n_pay_n_field" field_type="button_radio_options" field="Deal_Type_Buy_n_pay_n" onclick="toggle_category_deal_type(this)" class="button value" style="border:1px solid #ccc;padding:5px;margin:4px">'.sprintf(_("Buy %s cheapest %s free"), 3, 1).' ('
                ._('Mix & match').')  </span>
<span id="Deal_Type_Shipping_field" field_type="button_radio_options" field="Deal_Type_Shipping" onclick="toggle_category_deal_type(this)" class="button value valid" style="border:1px solid #ccc;padding:5px;margin:4px">'._("Discounted shipping").'</span>
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
            'class' => 'hide',
            'value' => '%',

            'custom'   => '<input id="Percentage_Off_field" field_type="input_with_field" field="Percentage_Off" value="10" class="value valid" style="margin-left:5px;width:30px" /> %',
            'label'    => '',
            'required' => false,
            'type'     => ''
        ),

        array(
            'id'       => 'Buy_n_get_n_free',
            'class'    => 'Deal_Type',
            'edit'     => 'custom',
            'class'    => 'hide',
            'value'    => false,
            'custom'   => sprintf(
                _('Buy %s get %s free'), '<input id="Deal_Buy_n_get_n_free_A_field" field_type="input_with_field" class="value valid"  field="Deal_Buy_n_get_n_free_A" value="2" style="margin-left:5px;width:30px">',
                '<input id="Deal_Buy_n_get_n_free_B_field" field="Deal_Buy_n_get_n_free_B" field_type="input_with_field"  class="value valid" value="1" style="margin-left:5px;width:30px">'
            ),
            'label'    => '',
            'required' => false,
            'type'     => ''
        ),
        array(
            'id'       => 'Buy_n_n_free',
            'class'    => 'Deal_Type',
            'edit'     => 'custom',
            'class'    => 'hide',
            'value'    => false,
            'custom'   => sprintf(
                _('Buy %s cheapest %s free'), '<input id="Deal_Buy_n_n_free_A_field" field_type="input_with_field" field="Deal_Buy_n_n_free_A" class="value valid" value="3" style="margin-left:5px;width:30px">',
                '<input id="Deal_Buy_n_n_free_B_field" field="Deal_Buy_n_n_free_B" field_type="input_with_field" class="value valid" value="1" style="margin-left:5px;width:30px">'
            ),
            'label'    => '',
            'required' => false,
            'type'     => ''
        ),

    ),


);


