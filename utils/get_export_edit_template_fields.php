<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2016 at 14:21:08 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

function get_export_edit_template_fields($object) {

    $export_edit_template_fields = array(

        'supplier_part' => array(
            array(
                'default_value' => '',
                'show_for_new'  => false,
                'required'      => true,
                'header'        => 'Supplier',
                'name'          => 'Supplier Code',
                'label'         => _("Supplier"),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => "Supplier's product code",
                'name'          => 'Supplier Part Reference',
                'label'         => _("Supplier's product code"),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => "Supplier's unit description",
                'name'          => 'Supplier Part Description',
                'label'         => _("Supplier's product unit description").' ('._("for supplier's POs").')',
                'checked'       => 0
            ),



            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Family',
                'name'          => 'Part Family Category Code',
                'label'         => _('Family'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Part reference',
                'name'          => 'Part Reference',
                'label'         => _('Part reference'),
                'checked'       => 0
            ),  array(
                'default_value' => _('Piece'),
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Unit label',
                'name'          => 'Part Unit Label',
                'label'         => _('Unit label'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Units per SKO',
                'name'          => 'Part Units Per Package',
                'label'         => _('Units per SKO'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'SKO description (picking aid)',
                'name'          => 'Part Package Description',
                'label'         => _('SKO description').' ('._('for picking aid').')',
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'SKO Barcode',
                'name'          => 'Part SKO Barcode',
                'label'         => _('SKO Barcode').' ('._('stock control').')',
                'checked'       => 0
            ),



            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'SKOs per carton',
                'name'          => 'Supplier Part Packages Per Carton',
                'label'         => _('SKOs per carton'),
                'checked'       => 0
            ),



            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Recommended SKOs per selling outer',
                'name'          => 'Part Recommended Packages Per Selling Outer',
                'label'         => _('Recommended SKOs per selling outer'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => false,
                'required'      => false,
                'header'        => 'Availability',
                'name'          => 'Supplier Part Status',
                'label'         => _('Availability'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => false,
                'required'      => false,
                'header'        => 'On Demand',
                'name'          => 'Supplier Part On Demand',
                'label'         => _('On Demand'),
                'checked'       => 0
            ),


            array(
                'default_value' => '1',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Minimum order (cartons)',
                'name'          => 'Supplier Part Minimum Carton Order',
                'label'         => _('Minimum order (cartons)'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Average delivery time (days)',
                'name'          => 'Supplier Part Average Delivery Days',
                'label'         => _('Average delivery time (days)'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Carton CBM',
                'name'          => 'Supplier Part Carton CBM',
                'label'         => _('Carton CBM'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Unit cost',
                'name'          => 'Supplier Part Unit Cost',
                'label'         => _('Unit cost'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Unit expense',
                'name'          => 'Supplier Part Unit Expense',
                'label'         => _('Unit expense'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit extra costs %',
                'name'          => 'Supplier Part Unit Extra Cost Percentage',
                'label'         => _('Unit extra costs (%)'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit recommended price',
                'name'          => 'Part Part Unit Price',
                'label'         => _('Unit recommended price'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit recommended RRP',
                'name'          => 'Part Part Unit RRP',
                'label'         => _('Unit recommended RRP'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Unit recommended description (website)',
                'name'          => 'Part Recommended Product Unit Name',
                'label'         => _('Unit recommended description').' ('._('website').')',
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit barcode (EAN-13, for website)',
                'name'          => 'Part Barcode',
                'label'         => _('Unit barcode'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit weight (kg)',
                'name'          => 'Part Part Unit Weight',
                'label'         => _('Weight shown in website'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit dimensions'.' (l x w x h) in cm',
                'name'          => 'Part Unit Dimensions',
                'label'         => _('Unit dimensions'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'SKO weight (kg)',
                'name'          => 'Part Part Package Weight',
                'label'         => _('SKO weight'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'SKO dimensions'.' (l x w x h) in cm',
                'name'          => 'Part Package Dimensions',
                'label'         => _('SKO dimensions'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Materials',
                'name'          => 'Part Part Materials',
                'label'         => _('Materials'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Country of origin',
                'name'          => 'Part Part Origin Country Code',
                'label'         => _('Country of origin'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Tariff code',
                'name'          => 'Part Part Tariff Code',
                'label'         => _('Tariff code'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Duty rate',
                'name'          => 'Part Part Duty Rate',
                'label'         => _('Duty rate'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'HTSUS',
                'name'          => 'Part Part HTSUS Code',
                'label'         => 'HTS US',
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'UN number',
                'name'          => 'Part Part UN Number',
                'label'         => _('UN number'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'UN class',
                'name'          => 'Part Part UN Class',
                'label'         => _('UN class'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Packing group',
                'name'          => 'Part Part Packing Group',
                'label'         => _('Packing group'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Proper shipping name',
                'name'          => 'Part Part Proper Shipping Name',
                'label'         => _('Proper shipping name'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Hazard identification number',
                'name'          => 'Part Part Hazard Identification Number',
                'label'         => _('Hazard identification number'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'CPNP Number',
                'name'          => 'Part Part CPNP Number',
                'label'         => _('CPNP number'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'UFI',
                'name'          => 'Part Part UFI',
                'label'         => _('UFI (Poison Centres)'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Carton Weight',
                'name'          => 'Supplier Part Carton Weight',
                'label'         => _('Carton gross weight'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Carton Barcode',
                'name'          => 'Supplier Part Carton Barcode',
                'label'         => _('Carton barcode (stock control)'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),


        ),
        'production_part' => array(

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => "Supplier's product code",
                'name'          => 'Supplier Part Reference',
                'label'         => _("Reference"),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => "Supplier's unit description",
                'name'          => 'Supplier Part Description',
                'label'         => _("Unit description"),
                'checked'       => 0
            ),



            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Family',
                'name'          => 'Part Family Category Code',
                'label'         => _('Family'),
                'checked'       => 0
            ),
            array(
                'default_value' => _('Piece'),
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Unit label',
                'name'          => 'Part Unit Label',
                'label'         => _('Unit label'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Units per SKO',
                'name'          => 'Part Units Per Package',
                'label'         => _('Units per SKO'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'SKO description (picking aid)',
                'name'          => 'Part Package Description',
                'label'         => _('SKO description').' ('._('for picking aid').')',
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'SKO Barcode',
                'name'          => 'Part SKO Barcode',
                'label'         => _('SKO Barcode').' ('._('stock control').')',
                'checked'       => 0
            ),



            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'SKOs per carton',
                'name'          => 'Supplier Part Packages Per Carton',
                'label'         => _('SKOs per carton'),
                'checked'       => 0
            ),



            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Recommended SKOs per selling outer',
                'name'          => 'Part Recommended Packages Per Selling Outer',
                'label'         => _('Recommended SKOs per selling outer'),
                'checked'       => 0
            ),



            array(
                'default_value' => '',
                'show_for_new'  => false,
                'required'      => false,
                'header'        => 'On Demand',
                'name'          => 'Supplier Part On Demand',
                'label'         => _('On Demand'),
                'checked'       => 0
            ),



            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Carton CBM',
                'name'          => 'Supplier Part Carton CBM',
                'label'         => _('Carton CBM'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Unit cost',
                'name'          => 'Supplier Part Unit Cost',
                'label'         => _('Unit cost'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit recommended price',
                'name'          => 'Part Part Unit Price',
                'label'         => _('Unit recommended price'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit recommended RRP',
                'name'          => 'Part Part Unit RRP',
                'label'         => _('Unit recommended RRP'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Unit recommended description (website)',
                'name'          => 'Part Recommended Product Unit Name',
                'label'         => _('Unit recommended description').' ('._('website').')',
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit barcode (EAN-13, for website)',
                'name'          => 'Part Barcode',
                'label'         => _('Unit barcode'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit weight (kg)',
                'name'          => 'Part Part Unit Weight',
                'label'         => _('Weight shown in website'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit dimensions'.' (l x w x h) in cm',
                'name'          => 'Part Part Unit Dimensions',
                'label'         => _('Unit dimensions'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'SKO weight (kg)',
                'name'          => 'Part Part Package Weight',
                'label'         => _('SKO weight'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'SKO dimensions'.' (l x w x h) in cm',
                'name'          => 'Part Part Package Dimensions',
                'label'         => _('SKO dimensions'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Materials',
                'name'          => 'Part Part Materials',
                'label'         => _('Materials'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Country of origin',
                'name'          => 'Part Part Origin Country Code',
                'label'         => _('Country of origin'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Tariff code',
                'name'          => 'Part Part Tariff Code',
                'label'         => _('Tariff code'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Duty rate',
                'name'          => 'Part Part Duty Rate',
                'label'         => _('Duty rate'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'HTSUS',
                'name'          => 'Part Part HTSUS Code',
                'label'         => 'HTS US',
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'UN number',
                'name'          => 'Part Part UN Number',
                'label'         => _('UN number'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'UN class',
                'name'          => 'Part Part UN Class',
                'label'         => _('UN class'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Packing group',
                'name'          => 'Part Part Packing Group',
                'label'         => _('Packing group'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Proper shipping name',
                'name'          => 'Part Part Proper Shipping Name',
                'label'         => _('Proper shipping name'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Hazard identification number',
                'name'          => 'Part Part Hazard Identification Number',
                'label'         => _('Hazard identification number'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'CPNP Number',
                'name'          => 'Part Part CPNP Number',
                'label'         => _('CPNP number'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'UFI',
                'name'          => 'Part Part UFI',
                'label'         => _('UFI (Poison Centres)'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),


            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Carton Weight',
                'name'          => 'Supplier Part Carton Weight',
                'label'         => _('Carton gross weight'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Carton Barcode',
                'name'          => 'Supplier Part Carton Barcode',
                'label'         => _('Carton barcode (stock control)'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),


        ),
        'part'          => array(
            array(
                'default_value' => '',
                'show_for_new'  => false,
                'required'      => false,
                'header'        => 'Status',
                'name'          => 'Part Status',
                'label'         => _('Status'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Reference',
                'name'          => 'Part Reference',
                'label'         => _('Reference'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit barcode (EAN-13)',
                'name'          => 'Part Barcode',
                'label'         => _('Unit barcode'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),

            array(
                'default_value' => _('piece'),
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Unit label',
                'name'          => 'Part Unit Label',
                'label'         => _('Unit label'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit weight',
                'name'          => 'Part Unit Weight',
                'label'         => _('Weight shown in website'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit dimensions',
                'name'          => 'Part Unit Dimensions',
                'label'         => _('Unit dimensions'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit recommended price',
                'name'          => 'Part Unit Price',
                'label'         => _('Unit recommended price'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit recommended RRP',
                'name'          => 'Part Unit RRP',
                'label'         => _('Unit recommended RRP'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Unit recommended description (website)',
                'name'          => 'Part Recommended Product Unit Name',
                'label'         => _('Unit recommended description').' ('._('website').')',
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Units per SKO',
                'name'          => 'Part Units Per Package',
                'label'         => _('Units per SKO'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Recommended SKOs per selling outer',
                'name'          => 'Part Recommended Packages Per Selling Outer',
                'label'         => _('Recommended SKOs per selling outer'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Part SKO Barcode',
                'name'          => 'Part SKO Barcode',
                'label'         => _('SKO barcode'),
                'checked'       => 0
            ),


            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'SKO description',
                'name'          => 'Part Package Description',
                'label'         => _('SKO description'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'SKO weight',
                'name'          => 'Part Package Weight',
                'label'         => _('SKO weight'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'SKO dimensions',
                'name'          => 'Part Package Dimensions',
                'label'         => _('SKO dimensions'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Materials/Ingredients',
                'name'          => 'Part Materials',
                'label'         => _('Materials/Ingredients'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Country of origin',
                'name'          => 'Part Origin Country Code',
                'label'         => _('Country of origin'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Tariff code',
                'name'          => 'Part Tariff Code',
                'label'         => _('Tariff code'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Duty rate',
                'name'          => 'Part Duty Rate',
                'label'         => _('Duty rate'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'HTSUS',
                'name'          => 'Part HTSUS Code',
                'label'         => 'HTSUS',
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'UN number',
                'name'          => 'Part UN Number',
                'label'         => _('UN number'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'UN class',
                'name'          => 'Part UN Class',
                'label'         => _('UN class'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Packing group',
                'name'          => 'Part Packing Group',
                'label'         => _('Packing group'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Proper shipping name',
                'name'          => 'Part Proper Shipping Name',
                'label'         => _('Proper shipping name'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Hazard identification number',
                'name'          => 'Part Hazard Identification Number',
                'label'         => _('Hazard identification number'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'CPNP Number',
                'name'          => 'Part CPNP Number',
                'label'         => _('CPNP number'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'UFI',
                'name'          => 'Part UFI',
                'label'         => _('UFI (Poison Centres)'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),


            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'SKO/Carton',
                'name'          => 'Supplier Part Packages Per Carton',
                'label'         => '<a class="warning fa fa-exclamation-circle" title="'._('Will change all supplier parts').'" ></a>'._('Packed units (SKOs) per carton'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Carton CBM',
                'name'          => 'Supplier Part Carton CBM',
                'label'         => '<a class="warning fa fa-exclamation-circle" title="'._('Will change all supplier parts').'" ></a>'._('Carton CBM'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Carton Weight',
                'name'          => 'Supplier Part Carton Weight',
                'label'         => '<a class="warning fa fa-exclamation-circle" title="'._('Will change all supplier parts').'" ></a>'._('Carton gross weight'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Carton Barcode',
                'name'          => 'Supplier Part Carton Barcode',
                'label'         => '<a class="warning fa fa-exclamation-circle" title="'._('Will change all supplier parts').'" ></a>'._('Carton barcode (stock control)'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Picking Band',
                'name'          => 'Part Picking Band Name',
                'label'         => _('Picking band'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Packing Band',
                'name'          => 'Part Packing Band Name',
                'label'         => _('Packing band'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'GPSR Manufacturer',
                'name'          => 'Part GPSR Manufacturer',
                'label'         => _('GPSR Manufacturer'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'GPSR EU Responsable',
                'name'          => 'Part GPSR EU Responsable',
                'label'         => 'GPSR EU Responsible',
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'GPSR Warnings',
                'name'          => 'Part GPSR Warnings',
                'label'         => _('GPSR Warnings'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'GPSR Manual',
                'name'          => 'Part GPSR Manual',
                'label'         => _('GPSR Manual'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'GPSR Class Category Danger',
                'name'          => 'Part GPSR Class Category Danger',
                'label'         => _('GPSR Class Category Danger'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'GPSR Languages',
                'name'          => 'Part GPSR Languages',
                'label'         => _('GPSR Languages'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),



        ),
        'product'  => array(
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Code',
                'name'          => 'Product Code',
                'label'         => _('Code'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Parts',
                'name'          => 'Parts',
                'label'         => _('Parts'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Family code',
                'name'          => 'Family Category Code',
                'label'         => _('Family code'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Label in family',
                'name'          => 'Product Label in Family',
                'label'         => _('Label in family'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Units per outer',
                'name'          => 'Product Units Per Case',
                'label'         => _('Units per outer'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Outer price',
                'name'          => 'Product Price',
                'label'         => _('Outer price'),
                'checked'       => 0
            ),


            array(
                'default_value' => _('piece'),
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Unit label',
                'name'          => 'Product Unit Label',
                'label'         => _('Unit label'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Unit name',
                'name'          => 'Product Name',
                'label'         => _('Unit name'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Brand',
                'name'          => 'Product Brand',
                'label'         => _('Brand'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit RRP',
                'name'          => 'Product Unit RRP',
                'label'         => _('Unit RRP'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Customer Id',
                'name'          => 'Product Customer Key',
                'label'         => _('Customer Id'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'GPSR Manufacturer',
                'name'          => 'Product GPSR Manufacturer',
                'label'         => _('GPSR Manufacturer'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'GPSR EU Responsable',
                'name'          => 'Product GPSR EU Responsable',
                'label'         => 'GPSR EU Responsible',
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'GPSR Warnings',
                'name'          => 'Product GPSR Warnings',
                'label'         => _('GPSR Warnings'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'GPSR Manual',
                'name'          => 'Product GPSR Manual',
                'label'         => _('GPSR Manual'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'GPSR Class Category Danger',
                'name'          => 'Product GPSR Class Category Danger',
                'label'         => _('GPSR Class Category Danger'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'GPSR Languages',
                'name'          => 'Product GPSR Languages',
                'label'         => _('GPSR Languages'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),


        ),

        'product_b2bc'  => array(
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Code',
                'name'          => 'Product Code',
                'label'         => _('Code'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Parts',
                'name'          => 'Parts',
                'label'         => _('Parts'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Family code',
                'name'          => 'Family Category Code',
                'label'         => _('Family code'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Label in family',
                'name'          => 'Product Label in Family',
                'label'         => _('Label in family'),
                'checked'       => 0
            ),
            array(
                'default_value' => _('piece'),
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Unit label',
                'name'          => 'Product Unit Label',
                'label'         => _('Unit label'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Unit name',
                'name'          => 'Product Name',
                'label'         => _('Unit name'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Unit price',
                'name'          => 'Product Unit Price',
                'label'         => _('Unit price'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Unit RRP',
                'name'          => 'Product Unit RRP',
                'label'         => _('Unit RRP'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Inners',
                'name'          => 'Product Inner',
                'label'         => _('Inners'),
                'checked'       => 0
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Units per carton',
                'name'          => 'Product Units Per Case',
                'label'         => _('Units per carton'),
                'checked'       => 0
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Customer Id',
                'name'          => 'Product Customer Key',
                'label'         => _('Customer Id'),
                'checked'       => 0
            ),


        ),

        'location' => array(
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Code',
                'name'          => 'Location Code',
                'label'         => _('Code'),
                'checked'       => 1,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Area',
                'name'          => 'Warehouse Area Code',
                'label'         => _('Area'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Flag',
                'name'          => 'Location Flag Color',
                'label'         => _('Flag'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            /*
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Used For',
                'name'          => 'Location Mainly Used For',
                'label'         => _('Used For'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            */
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Max weight',
                'name'          => 'Location Max Weight',
                'label'         => ucfirst(_('max weight')).' (Kg)',
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Max volume',
                'name'          => 'Location Max Volume',
                'label'         => ucfirst(_('max volume')).' (m³)',
                'checked'       => 0
            ),


        ),
        'warehouse_area' => array(
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Code',
                'name'          => 'Warehouse Area Code',
                'label'         => _('Code'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => true,
                'header'        => 'Name',
                'name'          => 'Warehouse Area Name',
                'label'         => _('Name'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Locations',
                'name'          => 'Warehouse Area Location Codes',
                'label'         => _('Locations'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),






        ),
        'prospect' => array(
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Company',
                'name'          => 'Prospect Company Name',
                'label'         => _('Company'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Contact Name',
                'name'          => 'Prospect Main Contact Name',
                'label'         => _('Contact name'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Email',
                'name'          => 'Prospect Main Plain Email',
                'label'         => _('Email'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Mobile',
                'name'          => 'Prospect Main Plain Mobile',
                'label'         => _('Mobile'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Telephone',
                'name'          => 'Prospect Main Plain Telephone',
                'label'         => _('Telephone'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),



        ),
        'fulfilment_asset' => array(
            array(
                'default_value' => 'Pallet',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Type',
                'name'          => 'Fulfilment Asset Type',
                'label'         => _('Type'),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),

            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Reference',
                'name'          => 'Fulfilment Asset Reference',
                'label'         => _("Customer's reference"),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Notes',
                'name'          => 'Fulfilment Asset Note',
                'label'         => _("Notes"),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),
            array(
                'default_value' => '',
                'show_for_new'  => true,
                'required'      => false,
                'header'        => 'Location',
                'name'          => 'Fulfilment Asset Location Code',
                'label'         => _("Location"),
                'checked'       => 0,
                'cell_type'     => 'string'
            ),



        ),
    );


    $fields = [];
    if (isset($export_edit_template_fields[$object])) {
        $fields = $export_edit_template_fields[$object];
    }

    return $fields;
}




