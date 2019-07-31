<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 July 2018 at 19:01:07 GMT+8fddss Kuala Lumpur, Malaysia

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


$options_scope = array(
    'List'  => _('Customer list'),
    'Category Target' => _('Category (Targeted)'),
    'Category Wide' => _('Category (Spread out)'),
    'Product Target' => _('Product (Targeted)'),
    'Product Wide' => _('Product (Spread out)'),
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
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'type'              => 'value'
        )
    )
);






$object_fields[] = array(
    'label'      => _('Mailing list'),
    'show_title' => true,
    'fields'     => array(

        array(
            'id'       => 'Type',
            'edit'     => 'custom',
            'value'    => '',
            'custom'   => '
<div class="button_radio_options">
<span field_type="button_radio_options" field="Customer_List" onclick="toggle_mailshot_scope(this)" class="button value" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Customer list').'</span>
<span field_type="button_radio_options" field="Product_Category" onclick="toggle_mailshot_scope(this)" class="button value" style="border:1px solid #ccc;padding:5px;margin:4px">'._('Category/Product').'</span>
</div>
',
            'label'    => _('Choose source'),
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
            'formatted_value'          => '',

            'placeholder'=>_('Quantity'),

            // 'custom'   => '<input id="Get_Item_Free_Quantity_field" field_type="input_with_field" field="Get_Item_Free_Quantity" value="1" class="value input_field" style="margin-left:5px;width:60px"  placeholder="'._('Qty').'" />',
            'label'    => _('Quantity'),
            'required' => false,
            'type'     => 'value'
        ),

    ),


);

