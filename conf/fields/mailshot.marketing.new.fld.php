<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 July 2018 at 19:01:07 GMT+8fddss Kuala Lumpur, Malaysia

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


$options_scope = array(
    'List'            => _('Customer list'),
    'Category Target' => _('Category (Targeted)'),
    'Category Wide'   => _('Category (Spread out)'),
    'Product Target'  => _('Product (Targeted)'),
    'Product Wide'    => _('Product (Spread out)'),
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
<div class="button_radio_options" class="Mailshot_Type ">
<input id="_Type" class="value  " field="_Type" type="hidden" value="" field_type="string" data-skip_validation="true"  >
<span field_type="button_radio_options" field="Customer_List" onclick="toggle_mailshot_scope(this)" class="button " style="border:1px solid #ccc;padding:5px;margin:4px">'._('Customer list').'</span>
<span field_type="button_radio_options" field="Product_Category" onclick="toggle_mailshot_scope(this)" class="button " style="border:1px solid #ccc;padding:5px;margin:4px">'._('Category/Product').'</span>
</div>
',
            'label'    => _('Choose source'),
            'required' => true,
            'type'     => ''
        ),

        array(
            'edit'        => 'asset',
            'class'       => 'hide',
            'id'          => 'Asset',
            'scope'       => 'Mailshot',
            'value'       => '',
            'label'       => _('Product/Category'),
            'invalid_msg' => get_invalid_message('string'),
            'required'    => false,
            'type'        => 'value'
        ),

        array(
            'edit'        => 'list',
            'class'       => 'hide',
            'id'          => 'List',
            'value'       => '',
            'label'       => _('List'),
            'invalid_msg' => get_invalid_message('string'),
            'required'    => false,
            'type'        => 'value'
        ),
        array(
            'id'       => 'Scope_Type',
            'class'    => 'hide',
            'edit'     => 'custom',
            'value'    => '',
            'custom'   => '
<div class="button_radio_options">
<input id="_Scope_Type" class="value  " field="_Scope_Type" type="hidden" value="" field_type="string" data-skip_validation="true"  >

<span field_type="button_radio_options" field="Targeted" onclick="toggle_mailshot_scope_type(this)" class="button " style="border:1px solid #ccc;padding:5px;margin:4px">'._('Targeted').'</span>
<span field_type="button_radio_options" field="Wide" onclick="toggle_mailshot_scope_type(this)" class="button " style="border:1px solid #ccc;padding:5px;margin:4px">'._('Spread').'</span>
</div>
',
            'label'    => _('Choose one'),
            'required' => true,
            'type'     => ''
        ),


    ),


);
