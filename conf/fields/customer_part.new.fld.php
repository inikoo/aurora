<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 April 2016 at 18:43:17 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


include_once 'utils/static_data.php';
include_once 'utils/country_functions.php';

$account = get_object('Account',1);

$new=true;

if ($user->can_supervisor('fulfilment')) {
    $edit = true;
} else {
    $edit = false;

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
$customer_part_fields = array();




$customer_part_fields[] = array(
    'label' => _('Ordering'),

    'show_title' => true,
    'fields'     => array(




        array(
            'id'   => 'Customer_Part_Reference',
            'edit' => ($edit ? 'string' : ''),

            'value'             => htmlspecialchars($object->get('Customer Part Reference')),
            'formatted_value'   => $object->get('Reference'),
            'label'             => _('Reference'),
            'required'          => true,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'type'              => 'value'
        ),

        array(
            'id'   => 'Customer_Part_Description',
            'edit' => ($edit ? 'string' : ''),
            'value'           => htmlspecialchars($object->get('Customer Part Description')),
            'formatted_value' => $object->get('Customer Part Description'),
            'label'             => _('Unit description'),
            'required'        => true,
            'type'            => 'value'
        ),

    )
);


$customer_part_fields[] = array(
    'label' => _('Cost/Pricing'),
    'show_title' => true,
    'fields'     => array(

        array(
            'id'              => 'Customer_Part_Unit_Cost',
            'edit'            => ($edit ? 'amount' : ''),
            'value'           => htmlspecialchars($object->get('Customer Part Unit Cost')),
            'formatted_value' => $object->get('Unit Cost'),
            'label'           => ucfirst($object->get_field_label('Customer Part Unit Cost')),
            'required'        => true,
            'placeholder'     => sprintf(_('amount in %s '), $options['parent_object']->get('Default Currency Code')),
            'type'            => 'value'
        ),
        array(
            'id'              => 'Customer_Part_Unit_Expense',
            'edit'            => ($edit ? 'amount' : ''),
            'value'           => htmlspecialchars($object->get('Customer Part Unit Expense')),
            'formatted_value' => $object->get('Unit Expense'),
            'label'           => ucfirst($object->get_field_label('Customer Part Unit Expense')).' <span class="discreet very_small">('._('% extra costs not apply').')</span>',
            'required'        => true,
            'placeholder'     => sprintf(_('amount in %s '), $options['parent_object']->get('Default Currency Code')),

            'type' => 'value'
        ),




        array(
            'id'     => 'Part_Unit_Price',
            'edit'   => 'amount_margin',
            'render' => true,

            'value'           => htmlspecialchars($part->get('Part Unit Price')),
            'formatted_value' => $part->get('Unit Price'),
            'label'           => ucfirst($part->get_field_label('Part Unit Price')),
            'required'        => true,
            'placeholder'     => sprintf(_('amount in %s or margin (%%)'), $account->get('Currency Code')),
            'type'            => 'value'
        ),
        array(
            'id'              => 'Part_Unit_RRP',
            'edit'            => 'amount_margin',
            'render' => true,
            'value'           => htmlspecialchars($part->get('Part Unit RRP')),
            'formatted_value' => $part->get('Unit RRP'),
            'label'           => ucfirst($part->get_field_label('Part Unit RRP')),
            'required'        => true,
            'placeholder'     => sprintf(
                _('amount in %s or margin (%%)'), $account->get('Currency Code')
            ),
            'type'            => 'value'
        ),


    )
);


$customer_part_fields[] = array(
    'label' => _('Unit'),

    'show_title' => true,
    'fields'     => array(
        array(
            'id'              => 'Part_Recommended_Product_Unit_Name',
            'edit'            => ($edit ? 'string' : ''),
            'render'          => true,
            'value'           => htmlspecialchars($part->get('Part Recommended Product Unit Name')),
            'formatted_value' => $part->get('Recommended Product Unit Name'),
            'label'           => ucfirst($part->get_field_label('Part Recommended Product Unit Name')),
            'required'        => true,
            'type'            => 'value'
        ),
        array(
            'id'     => 'Part_Barcode',
            'render'          => true,
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
            //'class'=>($customer_part_scope?'hide':''),
            'render'          => true,
            'edit'            => ($edit ? 'string' : ''),

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

$warning_units_per_package='';
$can_edit_units_per_package=false;

$show_full_label=true;

$customer_part_fields[] = array(
    'label' => ($show_full_label
        ? _('Units packed in').' ('._("Part SKO").')'
        : _(
            'SKO'
        )),

    'show_title' => true,
    'fields'     => array(


        array(
            'id'     => 'Part_Reference',
            'edit'   => ($edit ? 'string' : ''),
            'render' =>true,

            'value'             => htmlspecialchars($part->get('Part Part Reference')),
            'formatted_value'   => $part->get('Part Reference'),
            'label'             => _('Part reference'),
            'required'          => true,
            'server_validation' => json_encode(
                array(
                    'tipo'       => 'check_for_duplicates',
                    'field'      => 'Part_Reference',
                    'parent'     => 'account',
                    'parent_key' => 1,
                    'object'     => 'Part',
                    'key'        => $part->id,
                )
            ),
            'type'              => 'value'
        ),

        array(
            'id'     => 'Part_Units_Per_Package',
            'render' => true,

            'edit'            => 'smallint_unsigned',
            'value'           => ($new ? 1 : htmlspecialchars($object->get('Part Part Units Per Package'))),
            'formatted_value' => ($new ? 1 : $object->get('Part Units Per Package')),
            'label'           => ucfirst($object->get_field_label('Part Units Per Package')),
            'required'        => true,
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
            'render' =>true,
            //'render'=>true,
            'edit' => ($edit ? 'string' : ''),

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
            'render' => true,
            'id'              => 'Part_Package_Description_Note',
            'edit'            => 'string',
            'value'           => htmlspecialchars($part->get('Part Package Description Note')),
            'formatted_value' => $part->get('Package Description Note'),
            'label'           => ucfirst($part->get_field_label('Part Package Description Note')),
            'required'        => true,
            'type'            => 'value'


        ),

        array(
            'render' => false,
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


$customer_part_fields[] = array(
    'label' => ($show_full_label
        ? _("Customer's product cartons")
        : _(
            'Cartons'
        )),

    'show_title' => true,
    'fields'     => array(



        array(
            'id'              => 'Customer_Part_Packages_Per_Carton',
            'edit'            => 'smallint_unsigned',

            'value'           => ($new ? 1 : htmlspecialchars($object->get('Customer Part Packages Per Carton'))),
            'formatted_value' => ($new ? 1 : $object->get('Packages Per Carton')),
            'label'           => ucfirst($object->get_field_label('Customer Part Packages Per Carton')),
            'required'        => true,
            'type'            => 'value'
        ),


        array(
            'id'   => 'Customer_Part_Carton_CBM',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => htmlspecialchars(
                $object->get('Customer Part Carton CBM')
            ),
            'formatted_value' => $object->get('Carton CBM'),
            'label'           => ucfirst(
                $object->get_field_label('Customer Part Carton CBM')
            ),
            'placeholder'     => _('cubic meters'),
            'required'        => false,
            'type'            => 'value'
        ),


        array(
            'id'     => 'Customer_Part_SKO_Barcode',
            'render' => true,
            'edit' => ($edit ? 'string' : ''),
            'value'             => htmlspecialchars($object->get('Customer Part Carton Barcode')),
            'formatted_value'   => $object->get('Carton Barcode'),
            'label'             => _('Carton barcode').' ('._('stock control').')',
            'required'          => false,
            'server_validation' => json_encode(
                array(
                    'tipo'       => 'check_for_duplicates',
                    'parent'     => 'account',
                    'parent_key' => 1,
                    'object'     => 'Customer_Part',
                    'key'        => $object->id
                )
            ),
            'type'              => 'value'
        ),


    )
);



$customer_part_fields[] = array(
    'label' => _('Properties'),

    'show_title' => true,
    'fields'     => array(
        array(
            'id'              => 'Part_CPNP_Number',
            'edit'            => ($edit ? 'string' : ''),
            'value'           => htmlspecialchars($part->get('Part CPNP Number')),
            'formatted_value' => $part->get('CPNP Number'),
            'label'           => ucfirst($part->get_field_label('Part CPNP Number')),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'Part_UFI',
            'edit'            => ($edit ? 'string' : ''),
            'value'           => htmlspecialchars($part->get('Part UFI')),
            'formatted_value' => $part->get('UFI'),
            'label'           => ucfirst($part->get_field_label('Part UFI')),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Part_Materials',
            'edit' => ($edit ? 'textarea' : ''),

            'value'           => htmlspecialchars($part->get('Part Materials')),
            'formatted_value' => $part->get('Materials'),
            'label'           => ucfirst(
                $part->get_field_label('Part Materials')
            ),
            'required'        => false,
            'type'            => 'value'
        ),

        array(
            'id'                       => 'Part_Origin_Country_Code',
            'edit'                     => 'country_select',
            'options'                  => get_countries($db),
            'scope'                    => 'countries',
            'value'                    => (($new ) ? strtolower(country_3alpha_to_2alpha($customer->get('Customer Products Origin Country Code'))) : strtolower(country_3alpha_to_2alpha($part->get('Part Origin Country Code')))),
            'formatted_value'          => $customer->get('Products Origin Country Code'),
            'stripped_formatted_value' => (($new )
                ? ($customer->get('Part Origin Country Code') != '' ? $customer->get('Origin Country').' ('.$customer->get('Part Origin Country Code').')' : '')
                : ($part->get('Part Origin Country Code') != '' ? $part->get('Origin Country').' ('.$part->get(
                        'Part Origin Country Code'
                    ).')' : '')),
            'label'                    => ucfirst($part->get_field_label('Part Origin Country Code')),
            'required'                 => false,
            'type'                     => 'value'
        ),
        array(
            'id'   => 'Part_Tariff_Code',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $part->get('Part Tariff Code'),
            'formatted_value' => $part->get('Tariff Code'),
            'label'           => ucfirst(
                $part->get_field_label('Part Tariff Code')
            ),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'type'            => 'value'

        ),
        array(
            'id'   => 'Part_Duty_Rate',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $part->get('Part Duty Rate'),
            'formatted_value' => $part->get('Duty Rate'),
            'label'           => ucfirst(
                $part->get_field_label('Part Duty Rate')
            ),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Part_HTSUS_Code',
            'edit' => ($edit ? 'numeric' : ''),

            'value'           => $part->get('Part HTSUS Code'),
            'formatted_value' => $part->get('HTSUS Code'),
            'label'           => '<span title="Harmonized Tariff Schedule of the United States Code ">HTS US <img src="/art/flags/us.png"/></span>',
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'type'            => 'value'

        ),

    )


);

$customer_part_fields[] = array(
    'label' =>_('Health & Safety'),

    'show_title' => true,
    'fields'     => array(

        array(
            'id'   => 'Part_UN_Number',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $part->get('Part UN Number')
            ),
            'formatted_value' => $part->get('UN Number'),
            'label'           => ucfirst(
                $part->get_field_label('Part UN Number')
            ),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Part_UN_Class',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $part->get('Part UN Class')
            ),
            'formatted_value' => $part->get('UN Class'),
            'label'           => ucfirst(
                $part->get_field_label('Part UN Class')
            ),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'              => 'Part_Packing_Group',
            'edit'            => ($edit ? 'option' : ''),
            'options'         => $options_Packing_Group,
            'value'           => htmlspecialchars($part->get('Part Packing Group')),
            'formatted_value' => $part->get('Packing Group'),
            'label'           => ucfirst($part->get_field_label('Part Packing Group')),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Part_Proper_Shipping_Name',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $part->get('Part Proper Shipping Name')
            ),
            'formatted_value' => $part->get('Proper Shipping Name'),
            'label'           => ucfirst(
                $part->get_field_label('Part Proper Shipping Name')
            ),
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Part_Hazard_Identification_Number',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $part->get('Part Hazard Identification Number')
            ),
            'formatted_value' => $part->get('Hazard Identification Number'),
            'label'           => ucfirst(
                $part->get_field_label('Part Hazard Identification Number')
            ),
            'required'        => false,
            'type'            => 'value'
        )
    )


);




