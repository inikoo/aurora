<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 April 2016 at 18:41:36 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'utils/static_data.php';


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


$part_fields = array();


if (!($supplier_part_scope or $new)) {

    $part_fields[] = array(
        'label'      => _('Status'),
        'show_title' => true,
        'fields'     => array(
            array(
                'render' => ($new ? false : true),
                'id'     => 'Part_Status',
                'edit'   => ($edit ? 'option' : ''),

                'options'         => $options_status,
                'value'           => htmlspecialchars(
                    $object->get('Part Status')
                ),
                'formatted_value' => $object->get('Status'),
                'label'           => ucfirst(
                    $object->get_field_label('Part Status')
                ),
                'required'        => ($new ? false : true),
                'type'            => 'skip'
            ),
        )
    );

    $part_fields[] = array(
        'label'      => ($supplier_part_scope ? _('Part Id') : _('Id')),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'                => 'Part_Reference',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => htmlspecialchars(
                    $object->get('Part Reference')
                ),
                'formatted_value'   => $object->get('Reference'),
                'label'             => ucfirst(
                    $object->get_field_label('Part Reference')
                ),
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
                'id'              => 'Part_CPNP_Number',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => htmlspecialchars(
                    $object->get('Part CPNP Number')
                ),
                'formatted_value' => $object->get('CPNP Number'),
                'label'           => ucfirst(
                    $object->get_field_label('Part CPNP Number')
                ),
                'required'        => false,
                'type'            => 'value'
            ),

            array(
                'id'   => 'Part_Barcode_Number',
                'edit' => ($edit ? 'barcode' : ''),

                'value'             => htmlspecialchars(
                    $object->get('Part Barcode Number')
                ),
                'formatted_value'   => $object->get('Barcode Number'),
                'label'             => ucfirst(
                    $object->get_field_label('Part Barcode Number')
                ),
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

$family = $object->get('Family');


$part_fields[] = array(
    'label'      => _('Family'),
    'show_title' => true,
    'fields'     => array(
        array(
            'id'                       => 'Part_Family_Category_Key',
            'edit'                     => ($edit ? 'dropdown_select' : ''),
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


$part_fields[] = array(
    'label' => ($supplier_part_scope ? _('Part unit') : _('Unit')),

    'show_title' => true,
    'fields'     => array(

        array(
            'id'     => 'Part_Unit_Description',
            //'class'=>($supplier_part_scope?'hide':''),
            'render' => ($supplier_part_scope ? false : true),
            'edit'   => (($edit and !$supplier_part_scope) ? 'string' : ''),

            'value'           => htmlspecialchars(
                $object->get('Part Unit Description')
            ),
            'formatted_value' => $object->get('Unit Description'),
            'label'           => ucfirst(
                $object->get_field_label('Part Unit Description')
            ),
            'required'        => true,
            'type'            => 'value'


        ),

        array(
            'id'     => 'Part_Unit_Label',
            //'class'=>($supplier_part_scope?'hide':''),
            'render' => ($supplier_part_scope ? false : true),
            'edit'   => (($edit and !$supplier_part_scope) ? 'string' : ''),

            'value'           => htmlspecialchars(
                $object->get('Part Unit Label')
            ),
            'formatted_value' => $object->get('Unit Label'),
            'label'           => ucfirst(
                $object->get_field_label('Part Unit Label')
            ),
            'required'        => true,
            'type'            => 'value'


        ),

        array(
            'id'   => 'Part_Unit_Weight',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $object->get('Part Unit Weight'),
            'formatted_value' => $object->get('Unit Weight'),
            'label'           => ucfirst(
                $object->get_field_label('Part Unit Weight')
            ),
            'invalid_msg'     => get_invalid_message('numeric'),
            'required'        => false,
            'placeholder'     => 'Kg',

            'type' => 'value'
        ),
        array(
            'id'              => 'Part_Unit_Dimensions',
            'edit'            => 'dimensions',
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


        array(
            'id'     => 'Part_Unit_Price',
            'edit'   => 'amount_margin',
            'render' => (!($supplier_part_scope or $new) ? true : false),

            'value'           => htmlspecialchars(
                $object->get('Part Unit Price')
            ),
            'formatted_value' => $object->get('Unit Price'),
            'label'           => ucfirst(
                $object->get_field_label('Part Unit Price')
            ),
            'required'        => true,
            'placeholder'     => sprintf(
                _('amount in %s or margin (%%)'), $account->get('Currency')
            ),
            'type'            => 'value'
        ),
        array(
            'id'     => 'Part_Unit_RRP',
            'edit'   => 'amount_margin',
            'render' => (!($supplier_part_scope or $new) ? true : false),

            'value'           => htmlspecialchars(
                $object->get('Part Unit RRP')
            ),
            'formatted_value' => $object->get('Unit RRP'),
            'label'           => ucfirst(
                $object->get_field_label('Part Unit RRP')
            ),
            'required'        => true,
            'placeholder'     => sprintf(
                _('amount in %s or margin (%%)'), $account->get('Currency')
            ),
            'type'            => 'value'
        ),


    )
);
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

            'id'   => 'Part_Units_Per_Package',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $object->get('Part Units Per Package'),
            'formatted_value' => $object->get('Units Per Package'),
            'label'           => ucfirst(
                $object->get_field_label('Part Units Per Package')
            ),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => ($supplier_part_scope ? false : true),
            'type'            => 'value'
        ),

        array(
            'render' => (!($supplier_part_scope or $new) ? true : false),

            'id'   => 'Part_Package_Description',
            'edit' => (($edit and !$supplier_part_scope) ? 'string' : ''),

            'value'           => htmlspecialchars(
                $object->get('Part Package Description')
            ),
            'formatted_value' => $object->get('Package Description'),
            'label'           => ucfirst(
                $object->get_field_label('Part Package Description')
            ),
            'required'        => true,
            'type'            => 'value'


        ),

        array(
            'id'   => 'Part_Package_Weight',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $object->get('Part Package Weight'),
            'formatted_value' => $object->get('Package Weight'),
            'label'           => ucfirst(
                $object->get_field_label('Part Package Weight')
            ),
            'invalid_msg'     => get_invalid_message('numeric'),
            'required'        => false,
            'placeholder'     => 'Kg',

            'type' => 'value'
        ),
        array(
            'id'              => 'Part_Package_Dimensions',
            'edit'            => 'dimensions',
            'value'           => $object->get('Part Package Dimensions'),
            'formatted_value' => $object->get('Package Dimensions'),
            'label'           => ucfirst(
                $object->get_field_label('Part Package Dimensions')
            ),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'placeholder'     => _('L x W x H (in cm)'),
            'type'            => 'value'
        ),


    )
);
$part_fields[] = array(
    'label' => ($supplier_part_scope ? _('Part properties') : _('Properties')),

    'show_title' => true,
    'fields'     => array(

        array(
            'id'   => 'Part_Materials',
            'edit' => ($edit ? 'textarea' : ''),

            'value'           => htmlspecialchars(
                $object->get('Part Materials')
            ),
            'formatted_value' => $object->get('Materials'),
            'label'           => ucfirst(
                $object->get_field_label('Part Materials')
            ),
            'required'        => false,
            'type'            => 'value'
        ),

        array(
            'id'                       => 'Part_Origin_Country_Code',
            'edit'                     => 'country_select',
            'options'                  => get_countries($db),
            'scope'                    => 'countries',
            'value'                    => (($new and $supplier_part_scope) ? $options['parent_object']->get(
                'Supplier Products Origin Country Code'
            ) : htmlspecialchars($object->get('Part Origin Country Code'))),
            'formatted_value'          => (($new and $supplier_part_scope) ? $options['parent_object']->get('Products Origin Country Code') : $object->get('Origin Country Code')),
            'stripped_formatted_value' => (($new and $supplier_part_scope)
                ? ($options['parent_object']->get('Part Origin Country Code') != '' ? $options['parent_object']->get('Origin Country').' ('.$options['parent_object']->get('Part Origin Country Code')
                    .')' : '')
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
            'id'   => 'Part_Tariff_Code',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $object->get('Part Tariff Code'),
            'formatted_value' => $object->get('Tariff Code'),
            'label'           => ucfirst(
                $object->get_field_label('Part Tariff Code')
            ),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'type'            => 'value'

        ),
        array(
            'id'   => 'Part_Duty_Rate',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $object->get('Part Duty Rate'),
            'formatted_value' => $object->get('Duty Rate'),
            'label'           => ucfirst(
                $object->get_field_label('Part Duty Rate')
            ),
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
            'id'   => 'Part_UN_Number',
            'edit' => ($edit ? 'string' : ''),

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
            'id'   => 'Part_UN_Class',
            'edit' => ($edit ? 'string' : ''),

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
            'id'   => 'Part_Packing_Group',
            'edit' => ($edit ? 'option' : ''),

            'options'         => $options_Packing_Group,
            'value'           => htmlspecialchars(
                $object->get('Part Packing Group')
            ),
            'formatted_value' => $object->get('Packing Group'),
            'label'           => ucfirst(
                $object->get_field_label('Part Packing Group')
            ),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Part_Proper_Shipping_Name',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $object->get('Part Proper Shipping Name')
            ),
            'formatted_value' => $object->get('Proper Shipping Name'),
            'label'           => ucfirst(
                $object->get_field_label('Part Proper Shipping Name')
            ),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Part_Hazard_Indentification_Number',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $object->get('Part Hazard Indentification Number')
            ),
            'formatted_value' => $object->get('Hazard Indentification Number'),
            'label'           => ucfirst(
                $object->get_field_label('Part Hazard Indentification Number')
            ),
            'required'        => false,
            'type'            => 'value'
        )
    )


);


if (!$new and !$supplier_part_scope) {
    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(

            array(
                'id'        => 'delete_part',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name(
                    ).'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete part & related supplier's parts")
                    .' <i class="fa fa-trash new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $part_fields[] = $operations;
}

?>
