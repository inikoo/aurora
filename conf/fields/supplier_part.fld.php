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


$supplier_part_fields = array();

if (!$new) {
    $supplier_part_fields[] = array(
        'label'      => _('Supplier'),
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


        )
    );

}


$supplier_part_fields[] = array(
    'label' => ($show_full_label
        ? _("Supplier's part description")
        : _(
            'Description'
        )),

    'show_title' => true,
    'fields'     => array(
        array(
            'id'                       => 'Supplier_Part_Supplier_Key',
            'render'                   => (($new and $part_scope) ? true : false),
            'edit'                     => 'dropdown_select',
            'scope'                    => 'suppliers',
            'parent'                   => 'account',
            'parent_key'               => 1,
            'value'                    => htmlspecialchars(
                $object->get('Supplier Part Supplier Key')
            ),
            'formatted_value'          => $object->get('Supplier Key'),
            'stripped_formatted_value' => $object->get('Supplier Key'),
            'label'                    => ("Supplier's code"),
            'placeholder'              => _("Supplier's code"),
            'required'                 => ($part_scope ? true : false),
            'type'                     => 'value'
        ),
        array(
            'id'   => 'Supplier_Part_Reference',
            'edit' => ($edit ? 'string' : ''),

            'value'             => htmlspecialchars(
                $object->get('Supplier Part Reference')
            ),
            'formatted_value'   => $object->get('Reference'),
            'label'             => ucfirst(
                $object->get_field_label('Supplier Part Reference')
            ),
            'required'          => true,
            'server_validation' => json_encode(
                array('tipo' => 'check_for_duplicates')
            ),
            'type'              => 'value'
        ),

        array(
            'id'   => 'Part_Reference',
            'edit' => ($edit ? 'string' : ''),

            'value'             => htmlspecialchars(
                $object->get('Part Part Reference')
            ),
            'formatted_value'   => $object->get('Part Reference'),
            'label'             => ucfirst(
                $object->get_field_label('Part Reference')
            ),
            'required'          => true,
            'server_validation' => json_encode(
                array(
                    'tipo'       => 'check_for_duplicates',
                    'field'      => 'Part_Reference',
                    'parent'     => 'account',
                    'parent_key' => 1,
                    'object'     => 'Part',
                    'key'        => (isset($object->part->id) ? $object->part->id : false)
                )
            ),
            'type'              => 'value'
        ),

        array(
            'id'   => 'Supplier_Part_Package_Description',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $object->get('Part Part Package Description')
            ),
            'formatted_value' => $object->get('Part Package Description'),
            'label'           => _('Part SKO description'),
            'required'        => true,
            'type'            => 'value'
        ),


        array(
            'id'   => 'Part_SKO_Barcode',
            'edit' => ($edit ? 'string' : ''),

            'value'             => htmlspecialchars($object->get('Part Part SKO Barcode')),
            'formatted_value'   => $object->get('SKO Barcode'),
            'label'             => _('Part SKO barcode').' ('._('Stock control').')',
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
            'id'              => 'Part_Units_Per_Package',
            'edit'            => 'smallint_unsigned',
            'value'           => ($new
                ? 1
                : htmlspecialchars(
                    $object->get('Part Part Units Per Package')
                )),
            'formatted_value' => ($new
                ? 1
                : $object->get(
                    'Part Units Per Package'
                )),
            'label'           => ucfirst(
                $object->get_field_label('Part Units Per Package')
            ),
            'required'        => true,
            'type'            => 'value'
        ),

        array(
            'id'   => 'Part_Barcode_Number',
            'edit' => ($edit ? 'barcode' : ''),

            'value'             => htmlspecialchars($object->get('Part Part Barcode Number')),
            'formatted_value'   => $object->get('Part Barcode Number'),
            'label'             => _('Unit barcode (EAN-13)'),
            'required'          => false,
            'server_validation' => json_encode(
                array(
                    'tipo'       => 'check_for_duplicates',
                    'parent'     => 'account',
                    'parent_key' => 1,
                    'object'     => 'Part',
                    'key'        => (isset($object->part) ? $object->part->id : 0)
                )
            ),
            'type'              => 'value'
        ),
        array(
            'id'              => 'Part_Barcode_Number_Next_Available',
            'edit'            => ($edit ? 'string' : ''),
            'render'          => false,
            'value'           => 'No',
            'formatted_value' => 'No',
            'label'           => 'Next barcode',
            'required'        => false,
            'type'            => 'value'
        ),

        array(
            'id'              => 'Part_Barcode_Number_Next_Available',
            'edit'            => ($edit ? 'string' : ''),
            'render'          => false,
            'value'           => 'No',
            'formatted_value' => 'No',
            'label'           => 'Next barcode',
            'required'        => false,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Supplier_Part_Unit_Description',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars(
                $object->get('Part Part Unit Description')
            ),
            'formatted_value' => $object->get('Part Unit Description'),
            'label'           => ucfirst(
                $object->get_field_label('Part Unit Description')
            ),
            'required'        => true,
            'type'            => 'value'
        ),
        array(
            'id'   => 'Supplier_Part_Unit_Label',
            'edit' => ($edit ? 'string' : ''),

            'value'           => ($new
                ? _('Piece')
                : htmlspecialchars(
                    $object->get('Part Part Unit Label')
                )),
            'formatted_value' => ($new
                ? _('Piece')
                : $object->get(
                    'Part Unit Label'
                )),
            'label'           => ucfirst(
                $object->get_field_label('Part Unit Label')
            ),
            'required'        => true,
            'type'            => 'value'
        ),


    )
);


$supplier_part_fields[] = array(
    'label' => ($show_full_label
        ? _("Supplier's part ordering")
        : _(
            'Ordering'
        )),

    'show_title' => true,
    'fields'     => array(
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
            'required'        => ($new ? false : true),
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
                ).' <i class="fa fa-lemon-o" aria-hidden="true"></i>',
            'required'        => false,
            'type'            => ($supplier->get('Supplier Fresh') == 'Yes' ? 'value' : ''),

        ),

        array(
            'id'              => 'Supplier_Part_Packages_Per_Carton',
            'edit'            => 'smallint_unsigned',
            'value'           => ($new
                ? 1
                : htmlspecialchars(
                    $object->get('Supplier Part Packages Per Carton')
                )),
            'formatted_value' => ($new
                ? 1
                : $object->get(
                    'Packages Per Carton'
                )),
            'label'           => ucfirst(
                $object->get_field_label('Supplier Part Packages Per Carton')
            ),
            'required'        => true,
            'type'            => 'value'
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


    )
);

$supplier_part_fields[] = array(
    'label' => ($show_full_label
        ? _("Supplier's part cost/price")
        : _(
            'Cost/price'
        )),

    'show_title' => true,
    'fields'     => array(
        array(
            'render' => false,
            'id'     => 'Supplier_Part_Currency_Code',
            'edit'   => ($edit ? 'string' : ''),

            'value'           => ($new
                ? ($part_scope
                    ? ''
                    : $options['parent_object']->get(
                        'Supplier Default Currency Code'
                    ))
                : htmlspecialchars(
                    $object->get('Supplier Part Currency Code')
                )),
            'formatted_value' => ($new ? ($part_scope ? '' : $options['parent_object']->get('Default Currency Code ')) : htmlspecialchars($object->get('Currency Code'))),
            'label'           => ucfirst(
                $object->get_field_label('Supplier Part Currency Code')
            ),
            'required'        => false,
            'type'            => 'value'
        ),

        array(
            'id'              => 'Supplier_Part_Unit_Cost',
            'edit'            => ($edit ? 'amount' : ''),
            'locked'          => ($part_scope ? 1 : 0),
            'value'           => htmlspecialchars(
                $object->get('Supplier Part Unit Cost')
            ),
            'formatted_value' => $object->get('Unit Cost'),
            'label'           => ucfirst(
                $object->get_field_label('Supplier Part Unit Cost')
            ),
            'required'        => true,
            'placeholder'     => ($part_scope
                ? ''
                : sprintf(
                    _('amount in %s '), $options['parent_object']->get('Default Currency Code')
                )),
            'type'            => 'value'
        ),

        array(
            'id'              => 'Supplier_Part_Unit_Extra_Cost',
            'edit'            => 'amount_percentage',
            'locked'          => ($part_scope ? 1 : 0),
            'value'           => htmlspecialchars(
                $object->get('Supplier Part Unit Extra Cost')
            ),
            'formatted_value' => $object->get('Unit Extra Cost'),
            'label'           => ucfirst(
                $object->get_field_label('Supplier Part Unit Extra Cost')
            ),
            'required'        => false,
            'placeholder'     => ($part_scope
                ? ''
                : sprintf(
                    _('amount in %s or %%'), $options['parent_object']->get('Default Currency Code')
                )),
            'type'            => 'value'
        ),
        array(
            'id'   => 'Part_Unit_Price',
            'edit' => 'amount_margin',

            'value'           => htmlspecialchars(
                $object->get('Part Part Unit Price')
            ),
            'formatted_value' => $object->get('Part Unit Price'),
            'label'           => ucfirst(
                $object->get_field_label('Part Unit Price')
            ),
            'required'        => false,
            'placeholder'     => sprintf(
                _('amount in %s or margin (%%)'), $account->get('Currency')
            ),
            'type'            => 'value'
        ),
        array(
            'id'              => 'Part_Unit_RRP',
            'edit'            => 'amount_margin',
            'value'           => htmlspecialchars(
                $object->get('Part Part Unit RRP')
            ),
            'formatted_value' => $object->get('Part Unit RRP'),
            'label'           => ucfirst(
                $object->get_field_label('Part Unit RRP')
            ),
            'required'        => false,
            'placeholder'     => sprintf(
                _('amount in %s or margin (%%)'), $account->get('Currency')
            ),
            'type'            => 'value'
        ),

    )
);


?>
