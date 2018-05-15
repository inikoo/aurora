<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 May 2018 at 11:22:48 CEST ,Mijas Costa, SPAIN
 Copyright (c) 2018, Inikoo

 Version 3.0
*/

$new = true;




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
    'class'=>'',
    'show_title' => true,
    'fields'     => array(


        array(
            'id'              => 'Who',
            'edit'            => 'no_icon',

            'value'           => false,
            'formatted_value' => '
<div class="button_radio_options">
<span id="Entitled_To_Anyone_field" field_type="button_radio_options" field="Entitled_To_Anyone" onclick="toggle_new_category_deal_entitled_to(this)" class="button " style="border:1px solid #ccc;padding:5px;margin:4px">'._('Open to all').'</span>
<span id="Entitled_To_Voucher_field" field_type="button_radio_options" field="Entitled_To_Voucher" onclick="toggle_new_category_deal_entitled_to(this)" class="button " style="border:1px solid #ccc;padding:5px;margin:4px">'._('Voucher').'</span>

</div>
',
            'label'           => _('Who is entitled to this offer'),
            'required'        => false,
            'type'            => 'value'
        ),

        array(
            'id'              => 'Deal_Voucher_Type',
            'edit'            => 'no_icon',

            'class'           => 'hide',
            'value'           => false,
            'formatted_value' => '<span class="button"  onclick="toggle_voucher_auto_code(this)"  field="Deal_Voucher_Type"  style="margin-right:40px"><i id="toggle_voucher_auto_code_icon" class=" fa fa-fw fa-toggle-on" aria-hidden="true"></i> <span class="discreet">'._('Automatically generated')
                .'</span></span>',
            'label'           => _('Voucher code'),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'edit'              => ($edit ? 'string' : ''),
            'id'                => 'Deal_Voucher_Code',
            'class'           => 'hide',

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
    'class'=>'deal_type_title hide',
    'fields'     => array(

        array(
            'id'              => 'Type',
            'edit'            => 'no_icon',
            'class'=>'hide',
            'value'           => false,
            'formatted_value' => '
<div class="button_radio_options">
<span id="Deal_Type_Percentage_Off_field" field_type="button_radio_options" field="Deal_Type_Percentage_Off" onclick="toggle_category_deal_type(this)" class="button " style="border:1px solid #ccc;padding:5px;margin:4px">'._('Percentage off').'</span>
<span id="Deal_Type_Buy_n_get_n_free_field" field_type="button_radio_options" field="Deal_Type_Buy_n_get_n_free" onclick="toggle_category_deal_type(this)" class="button" style="border:1px solid #ccc;padding:5px;margin:4px">'.sprintf(_('Buy %s get %s free'),'<span>2</span>',1).'</span>
<span id="Deal_Type_Buy_n_pay_n_field" field_type="button_radio_options" field="Deal_Type_Buy_n_pay_n" onclick="toggle_category_deal_type(this)" class="button " style="border:1px solid #ccc;padding:5px;margin:4px">'.sprintf(_('Buy %s pay only for %s'),3,2).'</span>
</div>
',
            'label'           => _('Choose offer'),
            'required'        => false,
            'type'            => 'value'
        ),

        array(
            'id'              => 'Percentage_Off',
            'class'=>'Deal_Type',
            'edit'            => 'no_icon',
            'class'=>'hide',
            'value'           => false,
            'formatted_value' => '<input id="Deal_Percentage_Off_field" value="10" style="margin-left:5px;width:30px"> %',
            'label'           => '',
            'required'        => false,
            'type'            => 'value'
        ),

        array(
            'id'              => 'Buy_n_get_n_free',
            'class'=>'Deal_Type',
            'edit'            => 'no_icon',
            'class'=>'hide',
            'value'           => false,
            'formatted_value' => sprintf(_('Buy %s get %s free'),'<input id="Deal_Buy_n_get_n_free_A_field" value="2" style="margin-left:5px;width:30px">','<input id="Deal_Buy_n_get_n_free_B_field" value="1" style="margin-left:5px;width:30px">'),
            'label'           => '',
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'Buy_n_n_free',
            'class'=>'Deal_Type',
            'edit'            => 'no_icon',
            'class'=>'hide',
            'value'           => false,
            'formatted_value' => sprintf(_('Buy %s pay only for %s'),'<input id="Deal_Buy_n_get_n_free_A_field" value="3" style="margin-left:5px;width:30px">','<input id="Deal_Buy_n_get_n_free_B_field" value="2" style="margin-left:5px;width:30px">'),
            'label'           => '',
            'required'        => false,
            'type'            => 'value'
        ),

    ),


);




?>
