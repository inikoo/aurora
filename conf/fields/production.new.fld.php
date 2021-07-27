<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12:44:09 MYT Friday, 3 July 2020 Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


include_once 'utils/static_data.php';
include_once 'utils/country_functions.php';

$account = get_object('Account', 1);

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

$options_SKO_Type=array(
    'Pack'=>_('Packed units'),
    'Carton'=>_('Cartons')
);

$options_status = array(
    'Available'    => _('Available'),
    'NoAvailable'  => _('No stock'),
    'Discontinued' => _('Discontinued')
);

$options_Packing_Group = array(
    'None' => _('None'),
    'I'    => 'I',
    'II'   => 'II',
    'III'  => 'III'
);


$options_yes_no       = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);
$supplier_part_fields = array();


$supplier_part_fields[] = array(
    'label' => _('Unit'),

    'show_title' => true,
    'fields'     => array(


        array(

            'render' => ($object->get('Supplier Part On Demand') == 'Yes' ? true : false),

            'id'   => 'Supplier_Part_Fresh',
            'edit' => ($edit ? 'option' : ''),

            'options'         => $options_yn,
            'value'           => ($new ? 'No' : $object->get('Supplier Part Fresh')),
            'formatted_value' => ($new ? _('No') : $object->get('Fresh')),
            'label'           => ucfirst($object->get_field_label('Supplier Part Fresh')).' <i class="fa fa-lemon" aria-hidden="true"></i>',
            'required'        => false,
            'type'            => ($supplier->get('Supplier Fresh') == 'Yes' ? 'value' : ''),

        ),


        array(
            'id'   => 'Supplier_Part_Reference',
            'edit' => ($edit ? 'string' : ''),

            'value'             => htmlspecialchars($object->get('Supplier Part Reference')),
            'formatted_value'   => $object->get('Reference'),
            'label'             => _('Reference'),
            'required'          => true,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'type'              => 'value'
        ),

        array(
            'id'              => 'Supplier_Part_Description',
            'edit'            => ($edit ? 'string' : ''),
            'value'           => htmlspecialchars($object->get('Supplier Part Description')),
            'formatted_value' => $object->get('Supplier Part Description'),
            'label'           => _('Production unit description'),
            'required'        => true,
            'type'            => 'value'
        ),


        array(
            'id'              => 'Part_Recommended_Product_Unit_Name',
            'edit'            => ($edit ? 'string' : ''),
            'render'          => true,
            'value'           => htmlspecialchars($part->get('Part Recommended Product Unit Name')),
            'formatted_value' => $part->get('Recommended Product Unit Name'),
            'label'           => _('Marketing unit description'),
            'required'        => true,
            'type'            => 'value'
        ),
        array(
            'id'     => 'Part_Barcode',
            'render' => true,
            'edit'   => ($edit ? 'barcode' : ''),

            'value'             => htmlspecialchars($part->get('Part Barcode Number')),
            'formatted_value'   => $part->get('Barcode Number'),
            'label'             => _('Unit barcode (EAN-13)'),
            'required'          => false,
            'invalid_msg'       => get_invalid_message('barcode_ean'),
            'server_validation' => json_encode(
                array(
                    'tipo'       => 'check_for_duplicates',
                    'parent'     => 'account',
                    'parent_key' => 1,
                    'object'     => 'Part',
                    'key'        => $part->id
                )
            ),
            'type'              => 'value'
        ),


        array(
            'id'     => 'Part_Unit_Label',
            //'class'=>($supplier_part_scope?'hide':''),
            'render' => true,
            'edit'   => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $part->get('Part Unit Label')
            ),
            'formatted_value' => $part->get('Unit Label'),
            'label'           => ucfirst($part->get_field_label('Part Unit Label')),
            'required'        => true,
            'type'            => 'value'


        ),

        array(
            'id'   => 'Part_Unit_Weight',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $part->get('Part Unit Weight'),
            'formatted_value' => $part->get('Unit Weight'),
            'label'           => ucfirst($part->get_field_label('Part Unit Weight')),
            'invalid_msg'     => get_invalid_message('numeric'),
            'required'        => false,
            'placeholder'     => 'Kg',

            'type' => 'value'
        ),
        array(
            'id'              => 'Part_Unit_Dimensions',
            'edit'            => 'dimensions',
            'value'           => $part->get('Unit Dimensions'),
            'formatted_value' => $part->get('Unit Dimensions'),
            'label'           => ucfirst(
                $part->get_field_label('Part Unit Dimensions')
            ),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'placeholder'     => _('L x W x H (in cm)'),
            'type'            => 'value'
        ),


    )
);

$warning_units_per_package  = '';
$can_edit_units_per_package = false;

$supplier_part_fields[] = array(
    'label' => _('Packing'),

    'show_title' => true,
    'fields'     => array(


        array(
            'id'     => 'Part_Units_Per_Package',
            'render' => true,

            'edit'            => 'smallint_unsigned',
            'value'           => ($new ? 1 : htmlspecialchars($object->get('Part Part Units Per Package'))),
            'formatted_value' => ($new ? 1 : $object->get('Part Units Per Package')),
            'label'           => _('Packed in (quantity)'),
            'required'        => true,
            'type'            => 'value'
        ),




        array(
            'id'   => 'Supplier_Part_Packages_Per_Carton',
            'edit' => 'smallint_unsigned',

            'value'           => ($new ? 1 : htmlspecialchars($object->get('Supplier Part Packages Per Carton'))),
            'formatted_value' => ($new ? 1 : $object->get('Packages Per Carton')),
            'label'           => ucfirst($object->get_field_label('Supplier Part Packages Per Carton')),
            'required'        => true,
            'type'            => 'value'
        ),


        array(
            'id'   => 'Supplier_Part_Carton_CBM',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => htmlspecialchars($object->get('Supplier Part Carton CBM')),
            'formatted_value' => $object->get('Carton CBM'),
            'label'           => ucfirst($object->get_field_label('Supplier Part Carton CBM')),
            'placeholder'     => _('cubic meters'),
            'required'        => false,
            'type'            => 'value'
        ),


        array(
            'id'                => 'Supplier_Part_SKO_Barcode',
            'render'            => true,
            'edit'              => ($edit ? 'string' : ''),
            'value'             => htmlspecialchars($object->get('Supplier Part Carton Barcode')),
            'formatted_value'   => $object->get('Carton Barcode'),
            'label'             => _('Carton barcode').' ('._('stock control').')',
            'required'          => false,
            'server_validation' => json_encode(
                array(
                    'tipo'       => 'check_for_duplicates',
                    'parent'     => 'account',
                    'parent_key' => 1,
                    'object'     => 'SupplierPart',
                    'key'        => $object->id
                )
            ),
            'type'              => 'value'
        ),


    )
);

$supplier_part_fields[] = array(
    'label'      => _('SKO'),
    'show_title' => true,
    'fields'     => array(
        array(
            'id'              => 'Part_SKO_Type',
            'edit'            => ($edit ? 'option' : ''),
            'options'         => $options_SKO_Type,
            'value'           => 'Pack',
            'formatted_value' => _('Packed units'),
            'label'           => ucfirst($part->get_field_label('Part SKO Type')),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'render' => true,

            'id'   => 'Part_Recommended_Packages_Per_Selling_Outer',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => 1,
            'formatted_value' => 1,
            'label'           => ucfirst($part->get_field_label('Part Recommended Packages Per Selling Outer')),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => true,
            'type'            => 'value'
        ),

        array(
            'id'     => 'Part_SKO_Barcode',
            'render' => true,
            //'render'=>true,
            'edit'   => ($edit ? 'string' : ''),

            'value'             => htmlspecialchars($part->get('Part SKO Barcode')),
            'formatted_value'   => $part->get('SKO Barcode'),
            'label'             => _('SKO barcode').' ('._('stock control').')',
            'required'          => false,
            'server_validation' => json_encode(
                array(
                    'tipo'       => 'check_for_duplicates',
                    'parent'     => 'account',
                    'parent_key' => 1,
                    'object'     => 'Part',
                    'key'        => $part->id
                )
            ),
            'type'              => 'value'
        ),

        array(
            'id'     => 'Part_Package_Description',
            'render' => true,

            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars($part->get('Part Package Description')),
            'formatted_value' => $part->get('Part Package Description'),
            'label'           => _('SKO description').' ('._('for picking aid').')',
            'required'        => true,
            'type'            => 'value'
        ),



        array(
            'render'          => false,
            'id'              => 'Part_Package_Image',
            'edit'            => 'upload',
            'value'           => htmlspecialchars($part->get('Part Package Image')),
            'formatted_value' => $part->get('Package Description Image'),
            'label'           => ucfirst($part->get_field_label('Part Package Image')),
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
            'id'   => 'Part_Package_Weight',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $part->get('Part Package Weight'),
            'formatted_value' => $part->get('Package Weight'),
            'label'           => ucfirst(
                $part->get_field_label('Part Package Weight')
            ),
            'invalid_msg'     => get_invalid_message('numeric'),
            'required'        => false,
            'placeholder'     => 'Kg',

            'type' => 'value'
        ),
        array(
            'id'              => 'Part_Package_Dimensions',
            'edit'            => 'dimensions',
            'value'           => $part->get('Package Dimensions'),
            'formatted_value' => $part->get('Package Dimensions'),
            'label'           => ucfirst(
                $part->get_field_label('Part Package Dimensions')
            ),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'placeholder'     => _('L x W x H (in cm)'),
            'type'            => 'value'
        ),
    )

);
