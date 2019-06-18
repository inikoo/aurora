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

if($options['parent']=='campaign') {

    $object_fields[] = array(
        'label'      => _('Category'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'                       => 'Product_Family_Category_Key',
                'edit'                     => 'dropdown_select',
                'scope'                    => 'product_categories',
                'parent'                   => 'store',
                'parent_key'               => $store->id,
                'value'                    => '',
                'formatted_value'          => '',
                'stripped_formatted_value' => '',
                'label'                    => _('Category'),
                'required'                 => true,
                'type'                     => 'value'


            ),


        ),


    );
}






$object_fields[] = array(
    'label'      => _('Offer'),
    'show_title' => true,
    'fields'     => array(


        array(
            'edit'              => ($edit ? 'string' : ''),
            'id'                => 'Deal_Name',
            'value'             => '',
            'label'             => ucfirst($object->get_field_label('Deal Name')),
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => true,
            'server_validation' => json_encode(
                array(
                    'tipo' => 'check_for_duplicates'
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
    'label'      => _('Offer'),
    'show_title' => true,
    'class'      => 'deal_type_title hide',
    'fields'     => array(

        array(
            'id'       => 'Allowance_Type',
            'edit'     => 'no_icon',
            'class'    => '',
            'value'    => false,
            'formatted_value'   => '<input id="Allowance_Type" type="hidden" value="">
<div class="button_radio_options">
<span  field_type="button_radio_options" field="Deal_Type_Percentage_Off" onclick="toggle_category_deal_type(this)" class="button " style="border:1px solid #ccc;padding:5px;margin:4px">'._('Percentage off').'</span>
<span  field_type="button_radio_options" field="Deal_Type_Buy_n_get_n_free" onclick="toggle_category_deal_type(this)" class="button " style="border:1px solid #ccc;padding:5px;margin:4px">'.sprintf(
                    _('Buy %s get %s free'), '<span>2</span>', 1
                ).'</span>
<span field_type="button_radio_options" field="Deal_Type_Buy_n_pay_n" onclick="toggle_category_deal_type(this)" class="button " style="border:1px solid #ccc;padding:5px;margin:4px">'.sprintf(_("Buy %s cheapest %s free"), 3, 1).' ('
                ._('Mix & match').')  </span>
<span  field_type="button_radio_options" field="Deal_Type_Amount_Off" onclick="toggle_category_deal_type(this)" class="button " style="border:1px solid #ccc;padding:5px;margin:4px">'._('Amount off').'</span>

</div>
',
            'label'    => _('Choose offer'),
            'required' => false,
            'type'     => 'value'
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

        array(
            'edit'        => ($edit ? 'amount' : ''),
            'id'          => 'Amount_Off',
            'class'       => 'hide',
            'value'       => '',
            'placeholder'=>_('amount'),

            'label'       => _('Amount off'),
            'invalid_msg' => get_invalid_message('amount'),
            'required'    => false,
            'type'        => 'value'
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


?>
