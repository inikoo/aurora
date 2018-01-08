<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 December 2015 at 14:25:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$export_fields = array(
    'customers'                    => array(
        array(
            'name'    => 'C.`Customer Key`',
            'label'   => _('ID'),
            'checked' => 1
        ),
        array(
            'name'    => '`Customer Name`',
            'label'   => _('Name'),
            'checked' => 1
        ),
        array(
            'name'    => '`Customer Main Contact Name`',
            'label'   => _('Contact'),
            'checked' => 1
        ),
        array(
            'name'    => '`Customer Main Plain Email`',
            'label'   => _('Email'),
            'checked' => 1
        ),
        array(
            'name'    => '`Customer Main Plain Telephone`',
            'label'   => _('Telephone'),
            'checked' => 0
        ),
        array(
            'name'    => '`Customer Main Plain Mobile`',
            'label'   => _('Mobile'),
            'checked' => 0
        ),
        array(
            'name'    => '`Customer Tax Number`',
            'label'   => _('Tax Number'),
            'checked' => 0
        ),
        array(
            'name'    => 'REPLACE(`Customer Contact Address Formatted`,"<br/>","\n") as`Customer Address`',
            'label'   => _('Contact address'),
            'checked' => 0
        ),
        array(
            'name'    => '`Customer Contact Address Postal Label`',
            'label'   => _('Contact address (Postal label)'),
            'checked' => 0
        ),
        array(
            'name'    => '`Customer Main Address Line 1`,`Customer Main Address Line 2`,`Customer Main Address Line 3`,`Customer Main Town`,`Customer Main Postal Code`,`Customer Main Country Second Division`,`Customer Main Country First Division`,`Customer Main Country Code`',
            'label'   => _('Contact address (Separated fields)'),
            'checked' => 0
        ),
        array(
            'name'    => '`Customer Main Address Lines`',
            'label'   => _('Contact address (Lines)'),
            'checked' => 0
        ),

        array(
            'name'    => 'REPLACE(`Customer XHTML Billing Address`,"<br/>","\n") as`Customer Billing Address`',
            'label'   => _('Billing address'),
            'checked' => 0
        ),
        array(
            'name'    => '`Customer Billing Address Lines`,`Customer Billing Address Town`,`Customer Billing Address Country Code`',
            'label'   => _('Billing address (Separated fields)'),
            'checked' => 0
        ),
        array(
            'name'    => 'REPLACE(`Customer XHTML Main Delivery Address`,"<br/>","\n") as`Customer Delivery Address`',
            'label'   => _('Delivery address'),
            'checked' => 0
        ),
        array(
            'name'    => '`Customer Main Delivery Address Lines`,`Customer Main Delivery Address Town`,`Customer Main Delivery Address Postal Code`,`Customer Main Delivery Address Region`,`Customer Main Delivery Address Country Code`',
            'label'   => _('Delivery address (Separated fields)'),
            'checked' => 0
        ),
        array(
            'name'    => '`Customer Last Order Date`',
            'label'   => _('Last order date'),
            'checked' => 0
        ),
        array(
            'name'    => '`Customer Account Balance`',
            'label'   => _('Account balance'),
            'checked' => 0
        ),

    ),
    'orders'                       => array(
        array(
            'name'    => '`Order Public ID`',
            'label'   => _('ID'),
            'checked' => 1
        ),
        array(
            'name'    => '`Order Customer Name`',
            'label'   => _('Customer'),
            'checked' => 1
        ),
        array(
            'name'    => '`Order Customer Key`',
            'label'   => _('Customer Id'),
            'checked' => 0
        ),
        array(
            'name'    => '`Order Email`',
            'label'   => _('Email'),
            'checked' => 1
        ),
        array(
            'name'    => '`Order Date`',
            'label'   => _('Date'),
            'checked' => 1
        ),
        array(
            'name'    => '`Order Balance Total Amount`',
            'label'   => _('Total'),
            'checked' => 1
        ),
        array(
            'name'    => '`Order Payment Method`',
            'label'   => _('Payment method'),
            'checked' => 1
        ),

    ),
    'delivery_notes'               => array(
        array(
            'name'    => '`Delivery Note ID`',
            'label'   => _('ID'),
            'checked' => 1
        ),
        array(
            'name'    => '`Delivery Note Customer Name`',
            'label'   => _('Customer'),
            'checked' => 1
        ),
        array(
            'name'    => '`Delivery Note Customer Key`',
            'label'   => _('Customer Id'),
            'checked' => 0
        ),
        array(
            'name'    => '`Delivery Note Date`',
            'label'   => _('Date'),
            'checked' => 1
        ),
        array(
            'name'    => '`Delivery Note Weight`',
            'label'   => _('Weight'),
            'checked' => 1
        ),
    ),
    'invoices'                     => array(
        array(
            'name'    => '`Invoice Title`',
            'label'   => _('Type'),
            'checked' => 1
        ),
        array(
            'name'    => '`Invoice Public ID`',
            'label'   => _('ID'),
            'checked' => 1
        ),
        array(
            'name'    => '`Invoice Customer Name`',
            'label'   => _('Customer'),
            'checked' => 1
        ),
        array(
            'name'    => '`Invoice Customer Key`',
            'label'   => _('Customer Id'),
            'checked' => 0
        ),
        array(
            'name'    => '`Invoice Date`',
            'label'   => _('Date'),
            'checked' => 1
        ),
        array(
            'name'    => '`Invoice Currency`',
            'label'   => _('Currency'),
            'checked' => 1
        ),
        array(
            'name'    => '`Invoice Main Payment Method`',
            'label'   => _('Main payment method'),
            'checked' => 1
        ),
        array(
            'name'    => '`Invoice Total Net Amount`',
            'label'   => _('Net'),
            'checked' => 1
        ),
        array(
            'name'    => '`Invoice Total Tax Amount`',
            'label'   => _('Tax'),
            'checked' => 1
        )

    ),
    'timeserie_records'            => array(
        array(
            'name'    => '`Timeseries Record Date`',
            'label'   => _('Date'),
            'checked' => 1
        ),
        array(
            'name'    => '`Timeseries Record Float A`',
            'label'   => 'A',
            'checked' => 1
        ),
        array(
            'name'    => '`Timeseries Record Float B`',
            'label'   => 'B',
            'checked' => 0
        ),
        array(
            'name'    => '`Timeseries Record Float C`',
            'label'   => 'C',
            'checked' => 0
        ),
        array(
            'name'    => '`Timeseries Record Float D`',
            'label'   => 'D',
            'checked' => 0
        ),
        array(
            'name'    => '`Timeseries Record Integer A`',
            'label'   => 'E',
            'checked' => 0
        ),
        array(
            'name'    => '`Timeseries Record Integer B`',
            'label'   => 'F',
            'checked' => 0
        ),

    ),
    'timeserie_records_StoreSales' => array(
        array(
            'name'    => '`Timeseries Record Date`',
            'label'   => _('Date'),
            'checked' => 1
        ),
        array(
            'name'    => '`Timeseries Record Float A`',
            'label'   => _('Sales Net'),
            'checked' => 1
        ),
        array(
            'name'    => '`Timeseries Record Float B`',
            'label'   => _('Sales Net'),
            'checked' => 1
        ),

        array(
            'name'    => '`Timeseries Record Integer A`',
            'label'   => _('Invoices'),
            'checked' => 1
        ),
        array(
            'name'    => '`Timeseries Record Integer B`',
            'label'   => _('Refunds'),
            'checked' => 1
        ),

    ),


    'supplier_parts'                          => array(
        array(
            'name'    => '`Supplier Part Status`',
            'label'   => _('Availability'),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Reference`',
            'label'   => _("Supplier's part code"),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Description`',
            'label'   => _("Supplier's unit description"),
            'checked' => 1
        ),
        array(
            'name'    => '`Part Reference`',
            'label'   => _('Part reference'),
            'checked' => 1
        ),

        array(
            'name'    => '`Part Package Description`',
            'label'   => _('Part SKO description'),
            'checked' => 1
        ),
        array(
            'name'    => '`Part Units Per Package`',
            'label'   => _('Units per SKO'),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Packages Per Carton`',
            'label'   => _('SKOs per carton'),
            'checked' => 1
        ),


        array(
            'name'    => '`Supplier Part Minimum Carton Order`',
            'label'   => _('Minimum order (cartons)'),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Average Delivery Days`',
            'label'   => _('Average delivery time (days)'),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Carton CBM`',
            'label'   => _('Carton CBM'),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Unit Cost`',
            'label'   => _('Unit cost'),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Unit Extra Cost`',
            'label'   => _('Unit extra costs'),
            'checked' => 1
        ),
        array(
            'name'    => '`Part Unit Price`',
            'label'   => _('Unit recommended price'),
            'checked' => 1
        ),
        array(
            'name'    => '`Part Unit RRP`',
            'label'   => _('Unit recommended RRP'),
            'checked' => 1
        ),
        array(
            'name'    => '`Part Barcode Number`',
            'label'   => _('Unit barcode').' (for website)',
            'checked' => 1
        ),

    ),
    'agent_parts'                             => array(
        array(
            'name'    => '`Supplier Part Status`',
            'label'   => _('Availability'),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Reference`',
            'label'   => _("Supplier's part code"),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Description`',
            'label'   => _("Supplier's unit description"),
            'checked' => 1
        ),
        array(
            'name'    => '`Part Reference`',
            'label'   => _('Part reference'),
            'checked' => 1
        ),

        array(
            'name'    => '`Part Package Description`',
            'label'   => _('Part SKO description'),
            'checked' => 1
        ),
        array(
            'name'    => '`Part Units Per Package`',
            'label'   => _('Units per SKO'),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Packages Per Carton`',
            'label'   => _('SKOs per carton'),
            'checked' => 1
        ),


        array(
            'name'    => '`Supplier Part Minimum Carton Order`',
            'label'   => _('Minimum order (cartons)'),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Average Delivery Days`',
            'label'   => _('Average delivery time (days)'),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Carton CBM`',
            'label'   => _('Carton CBM'),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Unit Cost`',
            'label'   => _('Unit cost'),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Unit Extra Cost`',
            'label'   => _('Unit extra costs'),
            'checked' => 1
        ),
        array(
            'name'    => '`Part Unit Price`',
            'label'   => _('Unit recommended price'),
            'checked' => 1
        ),
        array(
            'name'    => '`Part Unit RRP`',
            'label'   => _('Unit recommended RRP'),
            'checked' => 1
        ),
        array(
            'name'    => '`Part Barcode Number`',
            'label'   => _('Unit barcode').' (for website)',
            'checked' => 1
        ),

    ),
    'ec_sales_list'                           => array(
        array(
            'name'    => '`Invoice Billing Country 2 Alpha Code`',
            'label'   => _('Country Code'),
            'checked' => 1
        ),
        array(
            'name'      => '`Invoice Tax Number`',
            'label'     => _('VAT registration number'),
            'checked'   => 1,
            'cell_type' => 'string'
        ),
        array(
            'name'    => 'ROUND(`Invoice Total Net Amount`*`Invoice Currency Exchange`,2)',
            'label'   => _('Net'),
            'checked' => 1
        ),
        array(
            'name'    => 'ROUND(`Invoice Total Tax Amount`*`Invoice Currency Exchange`)',
            'label'   => _('Tax'),
            'checked' => 1
        ),
        array(
            'name'    => '`Invoice Tax Number Valid`',
            'label'   => _('VAT registration number validation'),
            'checked' => 0
        ),
    ),
    'locations'                               => array(
        array(
            'name'    => '`Location Code`',
            'label'   => _('Code'),
            'checked' => 1
        ),
        /*
        array(
            'name'    => '`Location Mainly Used For`',
            'label'   => _('User for'),
            'checked' => 1
        ),
        */
        array(
            'name'    => '`Location Max Weight`',
            'label'   => ucfirst(_('max weight')).' (Kg)',
            'checked' => 1
        ),
        array(
            'name'    => '`Location Max Volume`',
            'label'   => ucfirst(_('max volume')).' (m³)',
            'checked' => 1
        ),
        array(
            'name'    => '`Location Distinct Parts`',
            'label'   => _('Parts'),
            'checked' => 1
        ),


    ),
    'parts'                                   => array(
        array(
            'name'    => '`Part Reference`',
            'label'   => _('Reference'),
            'checked' => 1
        ),

        array(
            'name'    => '`Part Package Description`',
            'label'   => _('SKO description'),
            'checked' => 1
        ),
        array(
            'name'    => '`Part Cost in Warehouse`',
            'label'   => _('Stock value per SKO'),
            'checked' => 1
        ),
        array(
            'name'    => '`Part SKO Barcode`',
            'label'   => _('SKO Barcode'),
            'checked' => 1
        ),
        array(
            'name'    => '`Part Tariff Code`',
            'label'   => _('Tariff code'),
            'checked' => 1
        ),

    ),
    'part_locations'                          => array(
        array(
            'name'    => '`Part Reference`',
            'label'   => _('Part reference'),
            'checked' => 1
        ),
        array(
            'name'    => '`Location Code`',
            'label'   => _('Location code'),
            'checked' => 1
        ),
        array(
            'name'    => '`Can Pick`',
            'label'   => _('Picking location'),
            'checked' => 1
        ),
        array(
            'name'    => '`Quantity On Hand`',
            'label'   => _('Stock'),
            'checked' => 1
        ),
        array(
            'name'    => '`Stock Value`',
            'label'   => _('Stock value'),
            'checked' => 1
        ),


    ),
    'supplier.order.items'                    => array(
        array(
            'name'    => '`Supplier Part Reference`',
            'label'   => _('Supplier product code'),
            'checked' => 1
        ),
        array(
            'name'    => '`Supplier Part Description`',
            'label'   => _("Supplier's unit description"),
            'checked' => 1
        ),
        array(
            'name'    => '`Purchase Order Quantity`*`Supplier Part Packages Per Carton`*`Part Units Per Package`',
            'label'   => _('Units'),
            'checked' => 1
        ),
        array(
            'name'    => '`Purchase Order Quantity`*`Supplier Part Packages Per Carton`',
            'label'   => _('Packs'),
            'checked' => 1
        ),
        array(
            'name'    => '`Purchase Order Quantity`',
            'label'   => _('Cartons'),
            'checked' => 1
        ),
        array(
            'name'    => '`Purchase Order Quantity`*`Supplier Part Packages Per Carton`*`Part Units Per Package`*`Supplier Part Unit Cost`  ',
            'label'   => _('Amount'),
            'checked' => 1
        ),


    ),
    'supplier_timeseries_drill_down_parts'    => array(
        array(
            'name'    => '`Part Reference`',
            'label'   => _('Part reference'),
            'checked' => 1
        ),

        array(
            'name'    => '`Part Package Description`',
            'label'   => _('Part description'),
            'checked' => 1
        ),
        array(
            'name'    => '`Timeseries Record Drill Down Integer B`',
            'label'   => _('Sales deliveries'),
            'checked' => 1
        ),
        array(
            'name'    => '`Timeseries Record Drill Down Integer A`',
            'label'   => _('Dispatched SKOs'),
            'checked' => 1
        ),
        array(
            'name'    => '`Timeseries Record Drill Down Float A`',
            'label'   => _('Sales'),
            'checked' => 1
        ),
        array(
            'name'    => '`Timeseries Record Drill Down Float C`',
            'label'   => _('Sales 1y ago'),
            'checked' => 1
        ),

    ),
    'supplier_timeseries_drill_down_families' => array(
        array(
            'name'    => '`Category Code`',
            'label'   => _('Part family code'),
            'checked' => 1
        ),

        array(
            'name'    => '`Category Label`',
            'label'   => _('Family name'),
            'checked' => 1
        ),
        array(
            'name'    => '`Timeseries Record Drill Down Integer B`',
            'label'   => _('Deliveries'),
            'checked' => 1
        ),
        array(
            'name'    => '`Timeseries Record Drill Down Integer A`',
            'label'   => _('Dispatched SKOs'),
            'checked' => 1
        ),
        array(
            'name'    => '`Timeseries Record Drill Down Float A`',
            'label'   => _('Sales'),
            'checked' => 1
        ),
        array(
            'name'    => '`Timeseries Record Drill Down Float C`',
            'label'   => _('Sales 1y ago'),
            'checked' => 1
        ),

    ),

    'part_barcode_errors' => array(
        array(
            'name'    => '`Part Reference`',
            'label'   => _('Reference'),
            'checked' => 1
        ),
        array(
            'name'    => '`Part Status`',
            'label'   => _('Part status'),
            'checked' => 1
        ),
        array(
            'name'    => '`Part Package Description`',
            'label'   => _('SKO description'),
            'checked' => 1
        ),

        array(
            'name'    => '`Part Barcode Number`',
            'label'   => _('Unit barcode'),
            'checked' => 1
        ),

        array(
            'name'    => '`Part Barcode Number Error`',
            'label'   => _('Barcode errors'),
            'checked' => 1
        ),

    ),
    'products'                                   => array(

        array(
            'name'    => '`Product Status`',
            'label'   => _('Status'),
            'checked' => 1
        ),

        array(
            'name'    => '`Product Code`',
            'label'   => _('Code'),
            'checked' => 1
        ),

        array(
            'name'    => '`Product Name`',
            'label'   => _('Name'),
            'checked' => 1
        ),
        array(
            'name'    => '`Product Units Per Case`',
            'label'   => _('Units per outer'),
            'checked' => 1
        ),
        array(
            'name'    => '`Product Unit Type`',
            'label'   => _('Unit type'),
            'checked' => 1
        ),
        array(
            'name'    => '`Product Price`',
            'label'   => _('Outer price'),
            'checked' => 1
        ),
        array(
            'name'    => '`Product RRP`',
            'label'   => _('Outer RRP'),
            'checked' => 1
        ),
        array(
            'name'    => '`Product Barcode Number`',
            'label'   => _('Barcode'),
            'checked' => 1
        ),
        array(
            'name'    => '`Product Web State`',
            'label'   => _('Web state'),
            'checked' => 1
        ),

    ),
    'intrastat' => array(
        array(
            'name'    => 'date_format( min(`Delivery Note Date`),\'%y%m\')',
            'label'   => _('Period'),
            'checked' => 1
        ),
        array(
            'name'    => 'LEFT(`Product Tariff Code`,8)',
            'label'   => _('Commodity code'),
            'checked' => 1
        ),
        array(
            'name'    => '`Delivery Note Address Country 2 Alpha Code`',
            'label'   => _('Country'),
            'checked' => 1
        ),
        array(
            'name'    => 'count(distinct OTF.`Order Key`) ',
            'label'   => _('Orders'),
            'checked' => 1
        ),
        array(
            'name'    => 'sum(`Delivery Note Quantity`*`Product Units Per Case`) ',
            'label'   => _('Units send'),
            'checked' => 1
        ),

        array(
            'name'    => 'sum(`Delivery Note Quantity`*`Product Unit Weight`*`Product Units Per Case`)',
            'label'   => _('Weight').' (Kg)',
            'checked' => 1
        ),

    ),

    'warehouse_parts_to_replenish_picking_location'=>array(
        array(
            'name'    => 'P.`Part Reference`',
            'label'   => _('Part reference'),
            'checked' => 1
        ),

        array(
            'name'    => 'P.`Part Package Description`',
            'label'   => _('Part description'),
            'checked' => 1
        ),
    )



);

?>
