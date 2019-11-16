<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 March 2017 at 18:30:34 GMT+8, , Sanur, Bali, Indonesia

 Copyright (c) 2017, Inikoo

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


$supplier_part_fields = array();




$supplier_part_fields[] = array(
    'label' => _('Ordering'),

    'show_title' => true,
    'fields'     => array(



        array(
            'id'              => 'Supplier_Part_Minimum_Carton_Order',
            'edit'            => 'smallint_unsigned',
            'value'           => ($new
                ? 1
                : htmlspecialchars(
                    $object->get('Supplier Part Minimum Carton Order')
                )),
            'formatted_value' => ($new
                ? 1
                : $object->get(
                    'Minimum Carton Order'
                )),
            'label'           => ucfirst(
                $object->get_field_label('Supplier Part Minimum Carton Order')
            ),
            'placeholder'     => _('cartons'),

            'required' => true,
            'type'     => 'value'
        ),




    )
);


$warning_units_per_package='';
$can_edit_units_per_package=false;

$supplier_part_fields[] = array(
    'label' => ($show_full_label
        ? _('Units packed in').' ('._("Part SKO").')'
        : _(
            'SKO'
        )),

    'show_title' => true,
    'fields'     => array(


        array(
            'id'     => 'Part_SKO_Barcode',
            'render' =>true,
            //'render'=>true,
            'edit' => ($edit ? 'string' : ''),

            'value'             => htmlspecialchars($object->part->get('Part SKO Barcode')),
            'formatted_value'   => $object->part->get('SKO Barcode'),
            'label'             => _('SKO barcode').' ('._('stock control').')',
            'required'          => false,
            'server_validation' => json_encode(
                array(
                    'tipo'       => 'check_for_duplicates',
                    'parent'     => 'account',
                    'parent_key' => 1,
                    'object'     => 'Part',
                    'key'        => $object->part->id
                )
            ),
            'type'              => 'value'
        ),


        array(
            'id'   => 'Part_Package_Weight',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $object->part->get('Part Package Weight'),
            'formatted_value' => $object->part->get('Package Weight'),
            'label'           => ucfirst(
                $object->part->get_field_label('Part Package Weight')
            ),
            'invalid_msg'     => get_invalid_message('numeric'),
            'required'        => false,
            'placeholder'     => 'Kg',

            'type' => 'value'
        ),
        array(
            'id'              => 'Part_Package_Dimensions',
            'edit'            => 'dimensions',
            'value'           => $object->part->get('Part Package Dimensions'),
            'formatted_value' => $object->part->get('Package Dimensions'),
            'label'           => ucfirst(
                $object->part->get_field_label('Part Package Dimensions')
            ),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'placeholder'     => _('L x W x H (in cm)'),
            'type'            => 'value'
        ),



    )
);


$supplier_part_fields[] = array(
    'label' => ($show_full_label
        ? _("Supplier's product cartons")
        : _(
            'Cartons'
        )),

    'show_title' => true,
    'fields'     => array(





        array(
            'id'   => 'Supplier_Part_Carton_CBM',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => htmlspecialchars(
                $object->get('Supplier Part Carton CBM')
            ),
            'formatted_value' => $object->get('Carton CBM'),
            'label'           => ucfirst(
                $object->get_field_label('Supplier Part Carton CBM')
            ),
            'placeholder'     => _('cubic meters'),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Supplier_Part_Carton_Weight',
            'edit' => ($edit ? 'numeric' : ''),
            'value'           => $object->get('Supplier Part Carton Weight'),
            'formatted_value' => $object->get('Carton Weight'),
            'label'           => ucfirst($object->get_field_label('Supplier Part Carton Weight')),
            'invalid_msg'     => get_invalid_message('numeric'),
            'required'        => false,
            'placeholder'     => 'Kg',
            'type' => 'value'
        ),

        array(
            'id'     => 'Supplier_Part_Carton_Barcode',
            'render' => true,
            'edit' => ($edit ? 'string' : ''),
            'value'             => htmlspecialchars($object->get('Supplier Part Carton Barcode')),
            'formatted_value'   => $object->get('Carton Barcode'),
            'label'             => _('Carton barcode').' ('._('stock control').')',
            'required'          => false,
            'server_validation' => json_encode(
                array(
                    'tipo'       => 'check_for_duplicates',
                    'parent'     => 'agent',
                    'parent_key' => $agent->id,
                    'object'     => 'SupplierPart',
                    'key'        => $object->id
                )
            ),
            'type'              => 'value'
        ),


    )
);

