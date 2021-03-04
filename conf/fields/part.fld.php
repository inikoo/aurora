<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 April 2016 at 18:41:36 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'utils/static_data.php';

$main_supplier_part = get_object('Supplier_Part', $object->get('Part Main Supplier Part Key'));
$supplier           = get_object('Supplier', $main_supplier_part->get('Supplier Part Supplier Key'));


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


if (isset($options['supplier_part_scope']) and $options['supplier_part_scope']) {
    $supplier_part_scope = true;
    $field_prefix        = 'Part ';
} else {
    $supplier_part_scope = false;
    $field_prefix        = '';
}

$options_Packing_Group = array(
    'None' => _('None'),
    'I'    => 'I',
    'II'   => 'II',
    'III'  => 'III'
);


$options_status = array(
    'In Use'        => _('Active'),
    'Discontinuing' => _('Discontinued')
);


$options_yes_no = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);


$options_symbols = array(
    'none'        => _('None'),
    'star'        => '&#9733; '._('Star'),
    'skull'       => '&#9760; '._('Skull'),
    'radioactive' => '&#9762; '._('Radioactive'),
    'peace'       => '&#9774; '._('Peace'),
    'sad'         => '&#9785; '._('Sad'),
    'gear'        => '&#9881; '._('Gear'),
    'love'        => '&#10084; '._('Love'),
);

if ($user->can_edit('parts')) {
    $edit = true;
} else {
    $edit = false;
}

if ($user->can_supervisor('suppliers')) {
    $super_edit = true;
} else {
    $super_edit = false;

}


if ($user->can_supervisor('production')) {
    $edit_production = true;
} else {
    $edit_production = false;
}


$part_fields = array();
$family      = $object->get('Family');


$part_fields[] = array(
    'label'      => _('Status/Id'),
    'show_title' => true,
    'fields'     => array(
        array(
            'render'     => ($new ? false : true),
            'id'         => 'Part_Status',
            'edit'       => ($super_edit ? 'option' : ''),
            'right_code' => 'BS',

            'options'         => $options_status,
            'value'           => htmlspecialchars($object->get('Part Status')),
            'formatted_value' => $object->get('Status'),
            'label'           => ucfirst($object->get_field_label('Part Status')),
            'required'        => ($new ? false : true),
            'type'            => 'skip'
        ),

        array(
            'id'                => 'Part_Reference',
            'edit'              => ($edit ? 'string' : ''),
            'right_code'        => 'PE',
            'value'             => htmlspecialchars($object->get('Part Reference')),
            'formatted_value'   => $object->get('Reference'),
            'label'             => ucfirst($object->get_field_label('Part Reference')),
            'required'          => true,
            'server_validation' => json_encode(
                array(
                    'tipo'       => 'check_for_duplicates',
                    'parent'     => 'account',
                    'parent_key' => 1,
                    'object'     => 'Part',
                    'key'        => $object->id
                )
            ),
            'type'              => 'value'
        ),

        array(
            'render'          => ($new ? false : true),
            'id'              => 'Part_Symbol',
            'edit'            => ($edit ? 'option' : ''),
            'right_code'      => 'PE',
            'options'         => $options_symbols,
            'value'           => htmlspecialchars($object->get('Part Symbol')),
            'formatted_value' => $object->get('Symbol'),
            'label'           => ucfirst($object->get_field_label('Part Symbol')),
            'required'        => false,
            'type'            => 'skip'
        ),

        array(
            'id'                       => 'Part_Family_Category_Key',
            'edit'                     => ($edit ? 'dropdown_select' : ''),
            'right_code'               => 'PE',
            'scope'                    => 'part_families',
            //'create_new'=>0,
            'parent'                   => 'account',
            'parent_key'               => 1,
            'value'                    => ($family ? $family->id : ''),
            'formatted_value'          => ($family ? $family->get('Code') : ''),
            'stripped_formatted_value' => ($family ? $family->get('Code') : ''),
            'label'                    => _('Family'),
            'required'                 => false,
            'placeholder'              => _("Part's family code"),
            'invalid_msg'              => array(
                'not_found'  => _(
                    "Part's family not found"
                ),
                'new_object' => _(
                    "Part's family will be created"
                )
            ),
            'type'                     => 'value'

        ),
    )
);


if (!$new) {

    $part_fields[] = array(
        'label' => _('Cost/Pricing').' <span style="font-weight: normal" class="padding_left_10 small">'._('Future delivered cost').': <span class="Unit_Delivered_Cost">'.$main_supplier_part->get('Unit Delivered Cost').'/'._('unit').'</span></span>',

        'show_title' => true,
        'fields'     => array(


            array(
                'id'              => 'Part_Supplier_Part_Unit_Cost',
                'edit'            => ($edit ? 'amount' : ''),
                'right_code'      => 'PE',
                'value'           => htmlspecialchars($main_supplier_part->get('Supplier Part Unit Cost')),
                'formatted_value' => $main_supplier_part->get('Unit Cost'),
                'label'           => ucfirst($main_supplier_part->get_field_label('Supplier Part Unit Cost')),
                'required'        => true,
                'placeholder'     => sprintf(_('amount in %s '), $supplier->get('Default Currency Code')),

                'type' => 'value'
            ),
            array(
                'id'              => 'Part_Supplier_Part_Unit_Expense',
                'edit'            => ($edit ? 'amount' : ''),
                'value'           => htmlspecialchars($main_supplier_part->get('Supplier Part Unit Expense')),
                'formatted_value' => $main_supplier_part->get('Unit Expense'),
                'label'           => ucfirst($main_supplier_part->get_field_label('Supplier Part Unit Expense')).' <span class="discreet very_small">('._('% extra costs not apply').')</span>',
                'required'        => true,
                'placeholder'     => sprintf(_('amount in %s '), $supplier->get('Default Currency Code')),

                'type' => 'value'
            ),
            array(
                'id'              => 'Part_Supplier_Part_Unit_Extra_Cost_Percentage',
                'edit'            => ($edit ? 'percentage' : ''),
                'right_code'      => 'PE',
                'value'           => htmlspecialchars($main_supplier_part->get('Supplier Part Unit Extra Cost Percentage')),
                'formatted_value' => $main_supplier_part->get('Unit Extra Cost Percentage'),
                'label'           => ucfirst($main_supplier_part->get_field_label('Supplier Part Unit Extra Cost Percentage')),
                'required'        => false,
                'placeholder'     => '%',
                'type'            => 'value'
            ),

            array(
                'id'              => 'Part_Unit_Price',
                'edit'            => ($edit ? 'amount_margin' : ''),
                'render'          => (!($supplier_part_scope or $new) ? true : false),
                'right_code'      => 'PE',
                'value'           => htmlspecialchars($object->get('Part Unit Price')),
                'formatted_value' => $object->get('Unit Price'),
                'label'           => ucfirst($object->get_field_label('Part Unit Price')),
                'required'        => true,
                'placeholder'     => sprintf(_('amount in %s or margin (%%)'), $account->get('Currency Code')),
                'type'            => 'value'
            ),
            array(
                'id'              => 'Part_Unit_RRP',
                'edit'            => ($edit ? 'amount_margin' : ''),
                'right_code'      => 'PE',
                'render'          => (!($supplier_part_scope or $new) ? true : false),
                'value'           => htmlspecialchars($object->get('Part Unit RRP')),
                'formatted_value' => $object->get('Unit RRP'),
                'label'           => ucfirst($object->get_field_label('Part Unit RRP')),
                'required'        => true,
                'placeholder'     => sprintf(
                    _('amount in %s or margin (%%)'), $account->get('Currency Code')
                ),
                'type'            => 'value'
            ),


        )
    );

}

$part_fields[] = array(
    'label' => ($supplier_part_scope ? _('Part unit') : _('Unit')),

    'show_title' => true,
    'fields'     => array(
        array(
            'id'              => 'Part_Recommended_Product_Unit_Name',
            'edit'            => ($edit ? 'string' : ''),
            'right_code'      => 'PE',
            'render'          => ($supplier_part_scope ? false : true),
            'value'           => htmlspecialchars($object->get('Part Recommended Product Unit Name')),
            'formatted_value' => $object->get('Recommended Product Unit Name'),
            'label'           => ucfirst($object->get_field_label('Part Recommended Product Unit Name')),
            'required'        => true,
            'type'            => 'value'
        ),
        array(
            'id'                => 'Part_Barcode',
            'render'            => ($supplier_part_scope ? false : true),
            'edit'              => ($edit ? 'barcode' : ''),
            'right_code'        => 'PE',
            'value'             => htmlspecialchars($object->get('Part Barcode Number')),
            'formatted_value'   => $object->get('Barcode Number'),
            'label'             => _('Unit barcode (EAN-13)'),
            'required'          => false,
            'invalid_msg'       => get_invalid_message('barcode_ean'),
            'server_validation' => json_encode(
                array(
                    'tipo'       => 'check_for_duplicates',
                    'parent'     => 'account',
                    'parent_key' => 1,
                    'object'     => 'Part',
                    'key'        => $object->id
                )
            ),
            'type'              => 'value'
        ),


        array(
            'id'              => 'Part_Unit_Label',
            //'class'=>($supplier_part_scope?'hide':''),
            'render'          => ($supplier_part_scope ? false : true),
            'edit'            => (($edit and !$supplier_part_scope) ? 'string' : ''),
            'right_code'      => 'PE',
            'value'           => htmlspecialchars($object->get('Part Unit Label')),
            'formatted_value' => $object->get('Unit Label'),
            'label'           => ucfirst($object->get_field_label('Part Unit Label')),
            'required'        => true,
            'type'            => 'value'


        ),

        array(
            'id'              => 'Part_Unit_Weight',
            'edit'            => ($edit ? 'numeric' : ''),
            'right_code'      => 'PE',
            'value'           => $object->get('Part Unit Weight'),
            'formatted_value' => $object->get('Unit Weight'),
            'label'           => ucfirst($object->get_field_label('Part Unit Weight')),
            'invalid_msg'     => get_invalid_message('numeric'),
            'required'        => false,
            'placeholder'     => 'Kg',

            'type' => 'value'
        ),
        array(
            'id'              => 'Part_Unit_Dimensions',
            'edit'            => ($edit ? 'dimensions' : ''),
            'right_code'      => 'PE',
            'value'           => $object->get('Part Unit Dimensions'),
            'formatted_value' => $object->get('Unit Dimensions'),
            'label'           => ucfirst(
                $object->get_field_label('Part Unit Dimensions')
            ),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'placeholder'     => _('L x W x H (in cm)'),
            'type'            => 'value'
        ),


    )
);


if ($object->get('Part Status') == 'In Process' and $object->get('Part Current On Hand Stock') == 0) {
    $can_edit_units_per_package = true;

    if ($object->get('Part Number Active Products') > 0 or $object->get('Part Number No Active Products') > 0) {
        $warning_units_per_package = '<br><span class="warning"><fa class="fa fa-exclamation-triangle padding_right_5"></fa> '._("Products associated with part").'</span>';
    } else {
        $warning_units_per_package = '';
    }
} else {
    $warning_units_per_package  = '<br><span class="error"><fa class="fa fa-ban padding_right_5"></fa> '._("Part already in warehouse").'</span>';
    $can_edit_units_per_package = false;

}
//todo remove this when we redo the post stuff
$warning_units_per_package  = '';
$can_edit_units_per_package = false;

$part_fields[] = array(
    'label' => ($supplier_part_scope
        ? _('Part stock keeping outer (SKO)')
        : _(
            'Stock keeping outer (SKO)'
        )),

    'show_title' => true,
    'fields'     => array(
        array(
            'render' => (!($supplier_part_scope or $new) ? true : false),

            'id'              => 'Part_Units_Per_Package',
            'edit'            => ($can_edit_units_per_package ? 'numeric' : ''),
            'value'           => $object->get('Part Units Per Package'),
            'formatted_value' => $object->get('Units Per Package'),
            'label'           => ucfirst($object->get_field_label('Part Units Per Package')).$warning_units_per_package,
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => ($supplier_part_scope ? false : true),
            'type'            => 'value'
        ),


        array(
            'render' => true,

            'id'              => 'Part_Recommended_Packages_Per_Selling_Outer',
            'edit'            => ($edit ? 'numeric' : ''),
            'right_code'      => 'PE',
            'value'           => $object->get('Part Recommended Packages Per Selling Outer'),
            'formatted_value' => $object->get('Part Recommended Packages Per Selling Outer'),
            'label'           => ucfirst($object->get_field_label('Part Recommended Packages Per Selling Outer')),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => true,
            'type'            => 'value'
        ),

        array(
            'id'                => 'Part_SKO_Barcode',
            'edit'              => ($edit ? 'string' : ''),
            'right_code'        => 'PE',
            'render'            => (($new and $supplier_part_scope) ? false : true),
            'value'             => htmlspecialchars($object->get('Part SKO Barcode')),
            'formatted_value'   => $object->get('SKO Barcode'),
            'label'             => ucfirst($object->get_field_label('SKO Barcode').' ('._('stock control').')'),
            'required'          => false,
            'server_validation' => json_encode(
                array(
                    'tipo'       => 'check_for_duplicates',
                    'parent'     => 'account',
                    'parent_key' => 1,
                    'object'     => 'Part',
                    'key'        => $object->id
                )
            ),
            'type'              => 'value'
        ),
        array(
            'render' => (!($supplier_part_scope or $new) ? true : false),

            'id'              => 'Part_Package_Description',
            'edit'            => ($edit ? 'string' : ''),
            'right_code'      => 'PE',
            'value'           => htmlspecialchars($object->get('Part Package Description')),
            'formatted_value' => $object->get('Package Description'),
            'label'           => ucfirst($object->get_field_label('Part Package Description').' ('._('for picking aid').')'),
            'required'        => true,
            'type'            => 'value'


        ),

        array(
            'render'          => (!($supplier_part_scope or $new) ? true : false),
            'id'              => 'Part_Package_Description_Note',
            'edit'            => ($edit ? 'string' : ''),
            'right_code'      => 'PE',
            'value'           => htmlspecialchars($object->get('Part Package Description Note')),
            'formatted_value' => $object->get('Package Description Note'),
            'label'           => ucfirst($object->get_field_label('Part Package Description Note')),
            'required'        => true,
            'type'            => 'value'


        ),
        array(
            'render'          => false,
            'id'              => 'Part_Package_Image',
            'edit'            => ($edit ? 'upload' : ''),
            'right_code'      => 'PE',
            'value'           => htmlspecialchars($object->get('Part Package Image')),
            'formatted_value' => $object->get('Package Description Image'),
            'label'           => ucfirst($object->get_field_label('Part Package Image')),
            'required'        => true,
            'type'            => 'value',
            'upload_data'     => array(
                'tipo'                => 'upload_images',
                'parent'              => 'part',
                'parent_key'          => $object->id,
                'object'              => 'image',
                'parent_object_scope' => 'SKO',

                'label' => _('Upload SKO image')


            )


        ),

        array(
            'id'              => 'Part_Package_Weight',
            'edit'            => ($edit ? 'numeric' : ''),
            'right_code'      => 'PE',
            'value'           => $object->get('Part Package Weight'),
            'formatted_value' => $object->get('Package Weight'),
            'label'           => ucfirst($object->get_field_label('Part Package Weight')),
            'invalid_msg'     => get_invalid_message('numeric'),
            'required'        => false,
            'placeholder'     => 'Kg',
            'type'            => 'value'
        ),
        array(
            'id'              => 'Part_Package_Dimensions',
            'edit'            => ($edit ? 'dimensions' : ''),
            'right_code'      => 'PE',
            'value'           => $object->get('Part Package Dimensions'),
            'formatted_value' => $object->get('Package Dimensions'),
            'label'           => ucfirst($object->get_field_label('Part Package Dimensions')),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'placeholder'     => _('L x W x H (in cm)'),
            'type'            => 'value'
        ),


    )
);

/*
if (!$supplier_part_scope) {

    $part_fields[] = array(
        'label' => _('Cartons').' <small class="padding_left_10" ><i class="fa fa-exclamation-triangle yellow" aria-hidden="true"></i> <span class="warning">'._("This field is independent of Supplier's product ordering SKOs per carton").'</span></small>',

        'show_title' => true,
        'fields'     => array(

            array(

                'id'     => 'Part_SKOs_per_Carton',
                'edit'   => ($edit ? 'numeric' : ''),
                'render' => (!($supplier_part_scope or $new) ? true : false),

                'value'           => $object->get('Part SKOs per Carton'),
                'formatted_value' => $object->get('SKOs per Carton'),
                'label'           => ucfirst(
                    $object->get_field_label('Part SKOs per Carton')
                ),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
            ),

            array(
                'id'     => 'Part_Carton_Barcode',
                'edit'   => ($edit ? 'string' : ''),
                'render' => (($new and $supplier_part_scope) ? false : true),

                'value'             => htmlspecialchars($object->get('Part Carton Barcode')),
                'formatted_value'   => $object->get('Carton Barcode'),
                'label'             => ucfirst($object->get_field_label('Part Carton Barcode')),
                'required'          => false,
                'server_validation' => json_encode(
                    array(
                        'tipo'       => 'check_for_duplicates',
                        'parent'     => 'account',
                        'parent_key' => 1,
                        'object'     => 'Part',
                        'key'        => $object->id
                    )
                ),
                'type'              => 'value'
            ),


        )
    );
}
*/

if ($account->get('Account Add Stock Value Type') == 'Last Price' and false) {

    if (!($supplier_part_scope or $new)) {

        $part_fields[] = array(
            'label' => _('Stock value'),

            'show_title' => true,
            'fields'     => array(
                array(


                    'id'              => 'Part_Cost_in_Warehouse',
                    'edit'            => ($edit ? 'amount' : ''),
                    'right_code'      => 'PE',
                    'value'           => $object->get('Part Cost in Warehouse'),
                    'formatted_value' => $object->get('Cost in Warehouse'),
                    'label'           => ucfirst($object->get_field_label('Part Cost in Warehouse')),
                    'invalid_msg'     => get_invalid_message('amount'),
                    'required'        => true,
                    'type'            => 'value'
                )


            )
        );
    }
}

$part_fields[] = array(
    'label' => ($supplier_part_scope ? _('Part properties') : _('Properties')),

    'show_title' => true,
    'fields'     => array(
        array(
            'id'              => 'Part_CPNP_Number',
            'edit'            => ($edit ? 'string' : ''),
            'right_code'      => 'PE',
            'value'           => htmlspecialchars($object->get('Part CPNP Number')),
            'formatted_value' => $object->get('CPNP Number'),
            'label'           => ucfirst($object->get_field_label('Part CPNP Number')),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'Part_UFI',
            'edit'            => ($edit ? 'string' : ''),
            'right_code'      => 'PE',
            'value'           => htmlspecialchars($object->get('Part UFI')),
            'formatted_value' => $object->get('UFI'),
            'label'           => ucfirst($object->get_field_label('Part UFI')),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'Part_Materials',
            'edit'            => ($edit ? 'textarea' : ''),
            'right_code'      => 'PE',
            'value'           => htmlspecialchars($object->get('Part Materials')),
            'formatted_value' => $object->get('Materials'),
            'label'           => ucfirst($object->get_field_label('Part Materials')),
            'required'        => false,
            'type'            => 'value'
        ),

        array(
            'id'                       => 'Part_Origin_Country_Code',
            'edit'                     => ($edit ? 'country_select' : ''),
            'right_code'               => 'PE',
            'options'                  => get_countries($db),
            'scope'                    => 'countries',
            'value'                    => (($new and $supplier_part_scope)
                ? strtolower(country_3alpha_to_2alpha($options['parent_object']->get('Supplier Products Origin Country Code')))
                : strtolower(
                    country_3alpha_to_2alpha(($object->get('Part Origin Country Code')))
                )),
            'formatted_value'          => (($new and $supplier_part_scope) ? $options['parent_object']->get('Products Origin Country Code') : $object->get('Origin Country Code')),
            'stripped_formatted_value' => (($new and $supplier_part_scope)
                ? ($options['parent_object']->get('Part Origin Country Code') != '' ? $options['parent_object']->get('Origin Country').' ('.$options['parent_object']->get('Part Origin Country Code').')' : '')
                : ($object->get('Part Origin Country Code') != '' ? $object->get('Origin Country').' ('.$object->get(
                        'Part Origin Country Code'
                    ).')' : '')),
            'label'                    => ucfirst(
                $object->get_field_label('Part Origin Country Code')
            ),
            'required'                 => false,
            'type'                     => 'value'
        ),
        array(
            'id'              => 'Part_Tariff_Code',
            'edit'            => ($edit ? 'numeric' : ''),
            'right_code'      => 'PE',
            'value'           => $object->get('Part Tariff Code'),
            'formatted_value' => $object->get('Tariff Code'),
            'label'           => ucfirst($object->get_field_label('Part Tariff Code')),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'type'            => 'value'

        ),
        array(
            'id'              => 'Part_Duty_Rate',
            'edit'            => ($edit ? 'numeric' : ''),
            'right_code'      => 'PE',
            'value'           => $object->get('Part Duty Rate'),
            'formatted_value' => $object->get('Duty Rate'),
            'label'           => ucfirst($object->get_field_label('Part Duty Rate')),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'Part_HTSUS_Code',
            'edit'            => ($edit ? 'numeric' : ''),
            'right_code'      => 'PE',
            'value'           => $object->get('Part HTSUS Code'),
            'formatted_value' => $object->get('HTSUS Code'),
            'label'           => '<span title="Harmonized Tariff Schedule of the United States Code ">HTS US <img src="/art/flags/us.png"/></span>',
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'type'            => 'value'

        ),

    )


);

$part_fields[] = array(
    'label' => ($supplier_part_scope
        ? _('Part health & safety')
        : _(
            'Health & Safety'
        )),

    'show_title' => true,
    'fields'     => array(

        array(
            'id'              => 'Part_UN_Number',
            'edit'            => ($edit ? 'string' : ''),
            'right_code'      => 'PE',
            'value'           => htmlspecialchars(
                $object->get('Part UN Number')
            ),
            'formatted_value' => $object->get('UN Number'),
            'label'           => ucfirst(
                $object->get_field_label('Part UN Number')
            ),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'Part_UN_Class',
            'edit'            => ($edit ? 'string' : ''),
            'right_code'      => 'PE',
            'value'           => htmlspecialchars(
                $object->get('Part UN Class')
            ),
            'formatted_value' => $object->get('UN Class'),
            'label'           => ucfirst(
                $object->get_field_label('Part UN Class')
            ),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'Part_Packing_Group',
            'edit'            => ($edit ? 'option' : ''),
            'right_code'      => 'PE',
            'options'         => $options_Packing_Group,
            'value'           => htmlspecialchars($object->get('Part Packing Group')),
            'formatted_value' => $object->get('Packing Group'),
            'label'           => ucfirst($object->get_field_label('Part Packing Group')),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'Part_Proper_Shipping_Name',
            'edit'            => ($edit ? 'string' : ''),
            'right_code'      => 'PE',
            'value'           => htmlspecialchars($object->get('Part Proper Shipping Name')),
            'formatted_value' => $object->get('Proper Shipping Name'),
            'label'           => ucfirst($object->get_field_label('Part Proper Shipping Name')),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'Part_Hazard_Identification_Number',
            'edit'            => ($edit ? 'string' : ''),
            'right_code'      => 'PE',
            'value'           => htmlspecialchars($object->get('Part Hazard Identification Number')),
            'formatted_value' => $object->get('Hazard Identification Number'),
            'label'           => ucfirst($object->get_field_label('Part Hazard Identification Number')),
            'required'        => false,
            'type'            => 'value'
        )
    )


);




// Remove this until you create an alert saying you will destroy this part for good
// Dont display if there is associated products (Add a message can not be deleted until all products are disassociated)





if (!$new and  (!$object->get('Part Raw Material Key') and  $edit_production )   ) {
    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(

            array(
                'id'     => 'create_raw_material',
                'class'  => 'operation',
                'render' => (($object->get('Part Raw Material Key') and $edit_production ) >0 ? false :true ),

                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock hide button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                    .'"}\' onClick="set_up_raw_material_object(this)" class="button">'._("Set as raw material").' <i class="fa fa-puzzle-piece success new_button"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),

            array(
                'id'        => 'delete_part',
                'class'     => 'operation',
                'value'     => '',
                'render'    => false,
                'label'     => '<i class="fa fa-fw fa-'.($super_edit ? 'lock-alt' : 'lock').'  button" 
                 data-labels=\'{ "text":"'._('Please ask an authorised user to delete this part').'","title":"'._('Restricted operation').'","footer":"'._('Authorised users').': "}\'  
                onClick="'.($super_edit ? 'toggle_unlock_delete_object(this)' : 'not_authorised_toggle_unlock_delete_object(this,\'BS\')').'"  
                style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete part & all related supplier's products")
                    .' <i class="far fa-trash-alt new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $part_fields[] = $operations;
}


