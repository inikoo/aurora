<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 January 2019 at 15:19:06 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/


include_once 'utils/static_data.php';

$account = new Account();

if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


if (isset($options['part_scope']) and $options['part_scope']) {
    $part_scope = true;
} else {
    $part_scope = false;
}


if (isset($options['show_full_label']) and $options['show_full_label']) {
    $show_full_label = true;
    $field_prefix    = 'Part ';
} else {
    $show_full_label = false;
    $field_prefix    = '';
}


$options_yn = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);


$options_status = array(
    'Available'    => _('Available'),
    'NoAvailable'  => _('No stock'),
    'Discontinued' => _('Discontinued')
);


if ($user->can_edit('parts')) {
    $edit = true;
} else {
    $edit = false;
}


$supplier_part_fields = array();

$can_edit_units_per_package = false;
$warning_units_per_package  = '';


$supplier_part_fields[] = array(
    'label'      => _('Batch & Packing'),
    'show_title' => true,
    'fields'     => array(

        array(
            'render' => true,

            'id'              => 'Part_Units_Per_Package',
            'edit'            => ($can_edit_units_per_package ? 'numeric' : ''),
            'value'           => $object->part->get('Part Part Units Per Package'),
            'formatted_value' => $object->part->get('Part Units Per Package'),
            'label'           => ucfirst($object->part->get_field_label('Part Units Per Package')).$warning_units_per_package,
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => true,
            'type'            => 'value'
        ),


        array(
            'id'              => 'Supplier_Part_Packages_Per_Carton',
            'edit'            => ($edit ? 'smallint_unsigned' : ''),
            'right_code'      => 'PE',
            'value'           => htmlspecialchars($object->get('Supplier Part Packages Per Carton')),
            'formatted_value' => $object->get('Packages Per Carton'),
            'label'           => ucfirst($object->get_field_label('Supplier Part Packages Per Carton')),
            'required'        => true,
            'type'            => 'value'
        ),

        array(
            'id'              => 'Supplier_Part_Carton_CBM',
            'edit'            => ($edit ? 'numeric' : ''),
            'right_code'      => 'PE',
            'render'          => (($object->get('Supplier Part Packages Per Carton') == '' or $object->get('Supplier Part Packages Per Carton') == 1) ? false : true),
            'value'           => htmlspecialchars($object->get('Supplier Part Carton CBM')),
            'formatted_value' => $object->get('Carton CBM'),
            'label'           => ucfirst($object->get_field_label('Supplier Part Carton CBM')),
            'placeholder'     => _('cubic meters'),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'Supplier_Part_Carton_Weight',
            'edit'            => ($edit ? 'numeric' : ''),
            'right_code'      => 'PE',
            'render'          => (($object->get('Supplier Part Packages Per Carton') == '' or $object->get('Supplier Part Packages Per Carton') == 1) ? false : true),

            'value'           => $object->get('Supplier Part Carton Weight'),
            'formatted_value' => $object->get('Carton Weight'),
            'label'           => ucfirst($object->get_field_label('Supplier Part Carton Weight')),
            'invalid_msg'     => get_invalid_message('numeric'),
            'required'        => false,
            'placeholder'     => 'Kg',
            'type'            => 'value'
        ),

        array(
            'id'                => 'Supplier_Part_Carton_Barcode',
            'render'          => (($object->get('Supplier Part Packages Per Carton') == '' or $object->get('Supplier Part Packages Per Carton') == 1) ? false : true),

            'edit'              => ($edit ? 'string' : ''),

            'right_code'        => 'PE',
            'value'             => htmlspecialchars($object->get('Supplier Part Carton Barcode')),
            'formatted_value'   => $object->get('Carton Barcode'),
            'label'             => _('Carton barcode').' ('._('stock control').')',
            'required'          => false,
            'server_validation' => json_encode(
                array(
                    'tipo'       => 'check_for_duplicates',
                    'parent'     => 'supplier',
                    'parent_key' => $account->properties('production_supplier_key'),
                    'object'     => 'SupplierPart',
                    'key'        => $object->id
                )
            ),
            'type'              => 'value'
        ),



    )

);



$supplier_part_fields[] = array(
    'label'      => _('Batch'),
    'show_title' => true,
    'fields'     => array(


        array(
            'edit' => ($edit ? 'smallint_unsigned' : ''),

            'id'              => 'Production_Part_Batch_Size',
            'value'           => $object->get('Production Part Batch Size'),
            'formatted_value' => $object->get('Batch Size'),

            'label'       => ucfirst($object->get_field_label('Production Part Batch Size')),
            'invalid_msg' => get_invalid_message('smallint_unsigned'),
            'required'    => true,
        ),


    )

);

$supplier_part_fields[] =array(
    'label'      => _('Raw materials'),
    'show_title' => true,
    'fields'     => array(
        array(
            'id'              => 'Raw_Materials',
            'edit'            => 'raw_materials',
            'value'           => $object->get('Production Part Raw Materials'),
            'formatted_value' => $object->get('Raw Materials'),
            'label'           => ucfirst($object->get_field_label('Production Part Raw Materials')),
            'required'        => false,
            'type'            => 'value'
        )

    )
);