<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 April 2016 at 18:43:17 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

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

$options_Packing_Group = array(
    'None' => _('None'),
    'I'    => 'I',
    'II'   => 'II',
    'III'  => 'III'
);


$options_yes_no = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);
$supplier_part_fields = array();




$supplier_part_fields[] = array(
    'label' => _('Ordering'),

    'show_title' => true,
    'fields'     => array(

        array(
            'id'                       => 'Supplier_Part_Supplier_Key',
            'edit'                     => ($edit ? 'dropdown_select' : ''),
            'scope'                    => 'suppliers',
            //'create_new'=>0,
            'parent'                   => 'account',
            'parent_key'               => 1,
            'value'                    => $object->get(
                'Supplier Part Supplier Key'
            ),
            'formatted_value'          => $object->get('Supplier Key'),
            'stripped_formatted_value' => $object->get('Supplier Key'),
            'label'                    => _('Supplier'),
            'required'                 => true,
            'placeholder'              => _("Supplier code"),
            'invalid_msg'              => array(
                'not_found'  => _(
                    "Supplier not found"
                ),
                'new_object' => _(
                    "Supplier will be created"
                )
            ),
            'type'                     => 'value'

        ),
        array(
            'render' => ($new ? false : true),
            'id'     => 'Supplier_Part_Status',
            'edit'   => ($edit ? 'option' : ''),

            'options'         => $options_status,
            'value'           => htmlspecialchars(
                $object->get('Supplier Part Status')
            ),
            'formatted_value' => $object->get('Status'),
            'label'           => ucfirst(
                $object->get_field_label('Supplier Part Status')
            ),
            'required'        => true,
            'type'            => 'skip'
        ),

        array(

            'render' => ($supplier->get('Supplier On Demand') == 'Yes' ? true : false),

            'id'   => 'Supplier_Part_On_Demand',
            'edit' => ($edit ? 'option' : ''),

            'options'         => $options_yn,
            'value'           => ($new
                ? 'No'
                : $object->get(
                    'Supplier Part On Demand'
                )),
            'formatted_value' => ($new ? _('No') : $object->get('On Demand')),
            'label'           => ucfirst(
                    $object->get_field_label('Supplier Part On Demand')
                ).' <i class="fa fa-fighter-jet" aria-hidden="true"></i>',
            'required'        => false,
            'type'            => ($supplier->get('Supplier On Demand') == 'Yes' ? 'value' : ''),

        ),
        array(

            'render' => ($object->get('Supplier Part On Demand') == 'Yes' ? true : false),

            'id'   => 'Supplier_Part_Fresh',
            'edit' => ($edit ? 'option' : ''),

            'options'         => $options_yn,
            'value'           => ($new
                ? 'No'
                : $object->get(
                    'Supplier Part Fresh'
                )),
            'formatted_value' => ($new ? _('No') : $object->get('Fresh')),
            'label'           => ucfirst(
                    $object->get_field_label('Supplier Part Fresh')
                ).' <i class="fa fa-lemon" aria-hidden="true"></i>',
            'required'        => false,
            'type'            => ($supplier->get('Supplier Fresh') == 'Yes' ? 'value' : ''),

        ),


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
        array(
            'id'   => 'Supplier_Part_Average_Delivery_Days',
            'edit' => ($edit ? 'numeric' : ''),

            'value' => ($new
                ? ($part_scope
                    ? ''
                    : $options['parent_object']->get(
                        'Supplier Average Delivery Days'
                    ))
                : htmlspecialchars(
                    $object->get('Supplier Part Average Delivery Days')
                )),

            'formatted_value' => ($new ? ($part_scope
                ? ''
                : $options['parent_object']->get(
                    'Supplier Average Delivery Days'
                )) : $object->get('Average Delivery Days')),
            'label'           => ucfirst(
                $object->get_field_label('Supplier Part Average Delivery Days')
            ),
            'placeholder'     => _('days'),

            'required' => false,
            'type'     => 'value'
        ),

        array(
            'id'   => 'Supplier_Part_Reference',
            'edit' => ($edit ? 'string' : ''),

            'value'             => htmlspecialchars($object->get('Supplier Part Reference')),
            'formatted_value'   => $object->get('Reference'),
            'label'             => _('<b>Supplier\'s</b> unit code').' (for ordering)',
            'required'          => true,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'type'              => 'value'
        ),

        array(
            'id'   => 'Supplier_Part_Description',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars($object->get('Supplier Part Description')),
            'formatted_value' => $object->get('Supplier Part Description'),
            'label'             => _('<b>Supplier\'s</b> unit description').' (for ordering)',

            'required'        => true,
            'type'            => 'value'
        ),

    )
);


$supplier_part_fields[] = array(
    'label' => _('Cost/Pricing'),
    'show_title' => true,
    'fields'     => array(

        array(
            'id'              => 'Supplier_Part_Unit_Cost',
            'edit'            => ($edit ? 'amount' : ''),
            'value'           => htmlspecialchars($object->get('Supplier Part Unit Cost')),
            'formatted_value' => $object->get('Unit Cost'),
            'label'           => ucfirst($object->get_field_label('Supplier Part Unit Cost')),
            'required'        => true,
            'placeholder'     => sprintf(_('amount in %s '), $options['parent_object']->get('Default Currency Code')),
            'type'            => 'value'
        ),
/*
        array(
            'id'              => 'Supplier_Part_Unit_Extra_Cost',
            'render'          => false,
            'edit'            => 'amount_percentage',
            'value'           => htmlspecialchars(
                $object->get('Supplier Part Unit Extra Cost')
            ),
            'formatted_value' => $object->get('Unit Extra Cost'),
            'label'           => ucfirst($object->get_field_label('Supplier Part Unit Extra Cost')),
            'required'        => false,
            'placeholder'     =>  sprintf(_('amount in %s or %%'), $options['parent_object']->get('Default Currency Code')),

            'type'            => 'value'
        ),
*/
        array(
            'id'              => 'Supplier_Part_Unit_Extra_Cost_Percentage',
            'edit'            => 'percentage',
            'locked'          => ($part_scope ? 1 : 0),
            'value'           => htmlspecialchars($object->get('Supplier Part Unit Extra Cost Percentage')),
            'formatted_value' => $object->get('Unit Extra Cost Percentage'),
            'label'           => ucfirst($object->get_field_label('Supplier Part Unit Extra Cost Percentage')),
            'required'        => false,
            'placeholder'     => '%',
            'type'            => 'value'
        ),


        array(
            'id'     => 'Part_Unit_Price',
            'edit'   => 'amount_margin',
            'render' => true,

            'value'           => htmlspecialchars($object->part->get('Part Unit Price')),
            'formatted_value' => $object->part->get('Unit Price'),
            'label'           => ucfirst($object->part->get_field_label('Part Unit Price')),
            'required'        => true,
            'placeholder'     => sprintf(_('amount in %s or margin (%%)'), $account->get('Currency Code')),
            'type'            => 'value'
        ),
        array(
            'id'              => 'Part_Unit_RRP',
            'edit'            => 'amount_margin',
            'render' => true,
            'value'           => htmlspecialchars($object->part->get('Part Unit RRP')),
            'formatted_value' => $object->part->get('Unit RRP'),
            'label'           => ucfirst($object->part->get_field_label('Part Unit RRP')),
            'required'        => true,
            'placeholder'     => sprintf(
                _('amount in %s or margin (%%)'), $account->get('Currency Code')
            ),
            'type'            => 'value'
        ),


    )
);


$supplier_part_fields[] = array(
    'label' => _('Unit'),

    'show_title' => true,
    'fields'     => array(
        array(
            'id'              => 'Part_Recommended_Product_Unit_Name',
            'edit'            => ($edit ? 'string' : ''),
            'render'          => true,
            'value'           => htmlspecialchars($object->part->get('Part Recommended Product Unit Name')),
            'formatted_value' => $object->part->get('Recommended Product Unit Name'),
            'label'           => ucfirst($object->part->get_field_label('Part Recommended Product Unit Name')),
            'required'        => true,
            'type'            => 'value'
        ),
        array(
            'id'     => 'Part_Barcode',
            'render'          => true,
            'edit'   => ($edit ? 'barcode' : ''),

            'value'             => htmlspecialchars($object->part->get('Part Barcode Number')),
            'formatted_value'   => $object->part->get('Barcode Number'),
            'label'             => _('Unit barcode (EAN-13)'),
            'required'          => false,
            'invalid_msg'       => get_invalid_message('barcode_ean'),
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
            'id'     => 'Part_Unit_Label',
            //'class'=>($supplier_part_scope?'hide':''),
            'render'          => true,
            'edit'            => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $object->part->get('Part Unit Label')
            ),
            'formatted_value' => $object->part->get('Unit Label'),
            'label'           => ucfirst($object->part->get_field_label('Part Unit Label')),
            'required'        => true,
            'type'            => 'value'


        ),

        array(
            'id'   => 'Part_Unit_Weight',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $object->part->get('Part Unit Weight'),
            'formatted_value' => $object->part->get('Unit Weight'),
            'label'           => ucfirst($object->part->get_field_label('Part Unit Weight')),
            'invalid_msg'     => get_invalid_message('numeric'),
            'required'        => false,
            'placeholder'     => 'Kg',

            'type' => 'value'
        ),
        array(
            'id'              => 'Part_Unit_Dimensions',
            'edit'            => 'dimensions',
            'value'           => $object->part->get('Part Unit Dimensions'),
            'formatted_value' => $object->part->get('Unit Dimensions'),
            'label'           => ucfirst(
                $object->part->get_field_label('Part Unit Dimensions')
            ),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'placeholder'     => _('L x W x H (in cm)'),
            'type'            => 'value'
        ),


    


    )
);

$warning_units_per_package='';
$can_edit_units_per_package=false;

$supplier_part_fields[] = array(
    'label' => 'SKO <span class="small">('._('Units packed in').')</span>',



    'show_title' => true,
    'fields'     => array(


        array(
            'id'     => 'Part_Reference',
            'edit'   => ($edit ? 'string' : ''),
            'render' =>true,

            'value'             => htmlspecialchars($object->part->get('Part Part Reference')),
            'formatted_value'   => $object->part->get('Part Reference'),
            'label'             => _('Part reference'),
            'required'          => true,
            'server_validation' => json_encode(
                array(
                    'tipo'       => 'check_for_duplicates',
                    'field'      => 'Part_Reference',
                    'parent'     => 'account',
                    'parent_key' => 1,
                    'object'     => 'Part',
                    'key'        => $object->part->id,
                )
            ),
            'type'              => 'value'
        ),

        array(
            'render' =>true,

            'id'              => 'Part_Units_Per_Package',
            'edit'            => ($can_edit_units_per_package ? 'numeric' : ''),
            'value'           => $object->part->get('Part Part Units Per Package'),
            'formatted_value' => $object->part->get('Part Units Per Package'),
            'label'           => ucfirst($object->part->get_field_label('Part Units Per Package')).$warning_units_per_package,
            'invalid_msg'     => get_invalid_message('string'),
            'required'        =>true,
            'type'            => 'value'
        ),


        array(
            'render' => true,

            'id'   => 'Part_Recommended_Packages_Per_Selling_Outer',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $object->part->get('Part Recommended Packages Per Selling Outer'),
            'formatted_value' => $object->part->get('Part Recommended Packages Per Selling Outer'),
            'label'           => ucfirst($object->part->get_field_label('Part Recommended Packages Per Selling Outer')),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => true,
            'type'            => 'value'
        ),

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
            'id'     => 'Part_Package_Description',
            'render' => true,

            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars($object->part->get('Part Package Description')),
            'formatted_value' => $object->part->get('Part Package Description'),
            'label'           => _('SKO description').' ('._('for picking aid').')',
            'required'        => true,
            'type'            => 'value'
        ),



        array(
            'render' => true,
            'id'              => 'Part_Package_Description_Note',
            'edit'            => 'string',
            'value'           => htmlspecialchars($object->part->get('Part Package Description Note')),
            'formatted_value' => $object->part->get('Package Description Note'),
            'label'           => ucfirst($object->part->get_field_label('Part Package Description Note')),
            'required'        => true,
            'type'            => 'value'


        ),

        array(
            'render' => false,
            'id'              => 'Part_Package_Image',
            'edit'            => 'upload',
            'value'           => htmlspecialchars($object->part->get('Part Package Image')),
            'formatted_value' => $object->part->get('Package Description Image'),
            'label'           => ucfirst($object->part->get_field_label('Part Package Image')),
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
            'value'           => $object->part->get('Part Package Weight'),
            'formatted_value' => $object->part->get('Package Weight'),
            'label'           => ucfirst($object->part->get_field_label('Part Package Weight')),
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
    'label' =>_('Cartons'),

    'show_title' => true,
    'fields'     => array(



        array(
            'id'              => 'Supplier_Part_Packages_Per_Carton',
            'edit'            => 'smallint_unsigned',

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
                    'parent'     => 'supplier',
                    'parent_key' => $supplier->id,
                    'object'     => 'SupplierPart',
                    'key'        => $object->id
                )
            ),
            'type'              => 'value'
        ),


    )
);



$supplier_part_fields[] = array(
    'label' => _('Properties'),

    'show_title' => true,
    'fields'     => array(
        array(
            'id'              => 'Part_CPNP_Number',
            'edit'            => ($edit ? 'string' : ''),
            'value'           => htmlspecialchars($object->part->get('Part CPNP Number')),
            'formatted_value' => $object->part->get('CPNP Number'),
            'label'           => ucfirst($object->part->get_field_label('Part CPNP Number')),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Part_Materials',
            'edit' => ($edit ? 'textarea' : ''),

            'value'           => htmlspecialchars($object->part->get('Part Materials')),
            'formatted_value' => $object->part->get('Materials'),
            'label'           => ucfirst(
                $object->part->get_field_label('Part Materials')
            ),
            'required'        => false,
            'type'            => 'value'
        ),

        array(
            'id'                       => 'Part_Origin_Country_Code',
            'edit'                     => 'country_select',
            'options'                  => get_countries($db),
            'scope'                    => 'countries',
            'value'                    =>  htmlspecialchars($object->part->get('Part Origin Country Code')),
            'formatted_value'          =>  $object->part->get('Origin Country Code'),
            'stripped_formatted_value' =>  ($object->part->get('Part Origin Country Code') != '' ? $object->part->get('Origin Country').' ('.$object->part->get(
                        'Part Origin Country Code'
                    ).')' : ''),
            'label'                    => ucfirst(
                $object->part->get_field_label('Part Origin Country Code')
            ),
            'required'                 => false,
            'type'                     => 'value'
        ),
        array(
            'id'   => 'Part_Tariff_Code',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $object->part->get('Part Tariff Code'),
            'formatted_value' => $object->part->get('Tariff Code'),
            'label'           => ucfirst(
                $object->part->get_field_label('Part Tariff Code')
            ),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'type'            => 'value'

        ),
        array(
            'id'   => 'Part_Duty_Rate',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $object->part->get('Part Duty Rate'),
            'formatted_value' => $object->part->get('Duty Rate'),
            'label'           => ucfirst(
                $object->part->get_field_label('Part Duty Rate')
            ),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Part_HTSUS_Code',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $object->part->get('Part HTSUS Code'),
            'formatted_value' => $object->part->get('HTSUS Code'),
            'label'           => '<span title="Harmonized Tariff Schedule of the United States Code ">HTS US <img src="/art/flags/us.gif"/></span>',
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'type'            => 'value'

        ),

    )


);

$supplier_part_fields[] = array(
    'label' =>_('Health & Safety'),

    'show_title' => true,
    'fields'     => array(

        array(
            'id'   => 'Part_UN_Number',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $object->part->get('Part UN Number')
            ),
            'formatted_value' => $object->part->get('UN Number'),
            'label'           => ucfirst(
                $object->part->get_field_label('Part UN Number')
            ),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Part_UN_Class',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $object->part->get('Part UN Class')
            ),
            'formatted_value' => $object->part->get('UN Class'),
            'label'           => ucfirst(
                $object->part->get_field_label('Part UN Class')
            ),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'Part_Packing_Group',
            'edit'            => ($edit ? 'option' : ''),
            'options'         => $options_Packing_Group,
            'value'           => htmlspecialchars($object->part->get('Part Packing Group')),
            'formatted_value' => $object->part->get('Packing Group'),
            'label'           => ucfirst($object->part->get_field_label('Part Packing Group')),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Part_Proper_Shipping_Name',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $object->part->get('Part Proper Shipping Name')
            ),
            'formatted_value' => $object->part->get('Proper Shipping Name'),
            'label'           => ucfirst(
                $object->part->get_field_label('Part Proper Shipping Name')
            ),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Part_Hazard_Identification_Number',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $object->part->get('Part Hazard Identification Number')
            ),
            'formatted_value' => $object->part->get('Hazard Identification Number'),
            'label'           => ucfirst(
                $object->part->get_field_label('Part Hazard Identification Number')
            ),
            'required'        => false,
            'type'            => 'value'
        )
    )


);


$part_fields[] = array(
    'label' => _('Production'),

    'show_title' => true,
    'fields'     => array(


        array(
            'id'              => 'Part_Production_Supply',
            'edit'            => ($edit ? 'option' : ''),
            'options'         => $options_yes_no,
            'value'           => htmlspecialchars($object->part->get('Part Production Supply')),
            'formatted_value' => $object->part->get('Production Supply'),
            'label'           => ucfirst($object->part->get_field_label('Part Production Supply')),
            'required'        => false,
            'type'            => 'value'
        ),

    )


);


