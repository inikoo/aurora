<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 December 2015 at 14:25:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

function get_export_fields($element, $account_currency_code = ''): array
{

    $export_fields = array(
        'customers'              => array(
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
                'name'    => '`Customer Contact Address Line 1`,`Customer Contact Address Line 2`,`Customer Contact Address Sorting Code`,`Customer Contact Address Postal Code`,`Customer Contact Address Dependent Locality`,`Customer Contact Address Locality`,`Customer Contact Address Administrative Area`,`Customer Contact Address Country 2 Alpha Code`',
                'label'   => _('Contact address (Separated fields)'),
                'checked' => 0,
                'labels'  => array(
                    _('Contact address'),
                    _('Contact address line 2'),
                    _('Contact address sorting code'),
                    _('Contact address postal code'),
                    _('Contact address dependent locality'),
                    _('Contact address locality'),
                    _('Contact address administrative area'),
                    _('Contact address country'),

                )
            ),

            array(
                'name'    => 'REPLACE(`Customer Invoice Address Formatted`,"<br/>","\n") as`Customer Billing Address`',
                'label'   => _('Billing address'),
                'checked' => 0
            ),
            array(
                'name'    => '`Customer Invoice Address Line 1`,`Customer Invoice Address Line 2`,`Customer Invoice Address Sorting Code`,`Customer Invoice Address Postal Code`,`Customer Invoice Address Dependent Locality`,`Customer Invoice Address Locality`,`Customer Invoice Address Administrative Area`,`Customer Invoice Address Country 2 Alpha Code`',
                'label'   => _('Billing address (Separated fields)'),
                'checked' => 0,
                'labels'  => array(
                    _('Billing address'),
                    _('Billing address line 2'),
                    _('Billing address sorting code'),
                    _('Billing address postal code'),
                    _('Billing address dependent locality'),
                    _('Billing address locality'),
                    _('Billing address administrative area'),
                    _('Billing address country'),

                )
            ),
            array(
                'name'    => 'REPLACE(`Customer Delivery Address Formatted`,"<br/>","\n") as`Customer Delivery Address`',
                'label'   => _('Delivery address'),
                'checked' => 0
            ),
            array(
                'name'    => '`Customer Delivery Address Line 1`,`Customer Delivery Address Line 2`,`Customer Delivery Address Sorting Code`,`Customer Delivery Address Postal Code`,`Customer Delivery Address Dependent Locality`,`Customer Delivery Address Locality`,`Customer Delivery Address Administrative Area`,`Customer Delivery Address Country 2 Alpha Code`',
                'label'   => _('Delivery address (Separated fields)'),
                'checked' => 0,
                'labels'  => array(
                    _('Delivery address'),
                    _('Delivery address line 2'),
                    _('Delivery address sorting code'),
                    _('Delivery address postal code'),
                    _('Delivery address dependent locality'),
                    _('Delivery address locality'),
                    _('Delivery address administrative area'),
                    _('Delivery address country'),

                )
            ),
            array(
                'name'    => '`Customer First Contacted Date`',
                'label'   => _('Creation date'),
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
            array(
                'name'    => '`Customer Number Invoices`',
                'label'   => _('Number invoiced'),
                'checked' => 0
            ),

            array(
                'name'    => '`Customer Sales Amount`',
                'label'   => _('Total sales'),
                'checked' => 0
            ),





        ),
        'customers_dropshipping' => array(
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
                'name'    => '`Customer Contact Address Line 1`,`Customer Contact Address Line 2`,`Customer Contact Address Sorting Code`,`Customer Contact Address Postal Code`,`Customer Contact Address Dependent Locality`,`Customer Contact Address Locality`,`Customer Contact Address Administrative Area`,`Customer Contact Address Country 2 Alpha Code`',
                'label'   => _('Contact address (Separated fields)'),
                'checked' => 0,
                'labels'  => array(
                    _('Contact address'),
                    _('Contact address line 2'),
                    _('Contact address sorting code'),
                    _('Contact address postal code'),
                    _('Contact address dependent locality'),
                    _('Contact address locality'),
                    _('Contact address administrative area'),
                    _('Contact address country'),

                )
            ),

            array(
                'name'    => 'REPLACE(`Customer Invoice Address Formatted`,"<br/>","\n") as`Customer Billing Address`',
                'label'   => _('Billing address'),
                'checked' => 0
            ),
            array(
                'name'    => '`Customer Invoice Address Line 1`,`Customer Invoice Address Line 2`,`Customer Invoice Address Sorting Code`,`Customer Invoice Address Postal Code`,`Customer Invoice Address Dependent Locality`,`Customer Invoice Address Locality`,`Customer Invoice Address Administrative Area`,`Customer Invoice Address Country 2 Alpha Code`',
                'label'   => _('Billing address (Separated fields)'),
                'checked' => 0,
                'labels'  => array(
                    _('Billing address'),
                    _('Billing address line 2'),
                    _('Billing address sorting code'),
                    _('Billing address postal code'),
                    _('Billing address dependent locality'),
                    _('Billing address locality'),
                    _('Billing address administrative area'),
                    _('Billing address country'),

                )
            ),
            array(
                'name'    => 'REPLACE(`Customer Delivery Address Formatted`,"<br/>","\n") as`Customer Delivery Address`',
                'label'   => _('Delivery address'),
                'checked' => 0
            ),
            array(
                'name'    => '`Customer Delivery Address Line 1`,`Customer Delivery Address Line 2`,`Customer Delivery Address Sorting Code`,`Customer Delivery Address Postal Code`,`Customer Delivery Address Dependent Locality`,`Customer Delivery Address Locality`,`Customer Delivery Address Administrative Area`,`Customer Delivery Address Country 2 Alpha Code`',
                'label'   => _('Delivery address (Separated fields)'),
                'checked' => 0,
                'labels'  => array(
                    _('Delivery address'),
                    _('Delivery address line 2'),
                    _('Delivery address sorting code'),
                    _('Delivery address postal code'),
                    _('Delivery address dependent locality'),
                    _('Delivery address locality'),
                    _('Delivery address administrative area'),
                    _('Delivery address country'),

                )
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
        'prospects'              => array(

            array(
                'name'    => '`Prospect Name`',
                'label'   => _('Name'),
                'checked' => 1
            ),
            array(
                'name'    => '`Prospect Main Contact Name`',
                'label'   => _('Contact'),
                'checked' => 1
            ),
            array(
                'name'    => '`Prospect Main Plain Email`',
                'label'   => _('Email'),
                'checked' => 1
            ),
            array(
                'name'    => '`Prospect Main Plain Telephone`',
                'label'   => _('Telephone'),
                'checked' => 0
            ),
            array(
                'name'    => '`Prospect Main Plain Mobile`',
                'label'   => _('Mobile'),
                'checked' => 0
            ),

            array(
                'name'    => 'REPLACE(`Prospect Contact Address Formatted`,"<br/>","\n") as`Prospect Address`',
                'label'   => _('Contact address'),
                'checked' => 0
            ),
            array(
                'name'    => '`Prospect Contact Address Postal Label`',
                'label'   => _('Contact address (Postal label)'),
                'checked' => 0
            ),

            array(
                'name'    => '`Prospect Contact Address Line 1`,`Prospect Contact Address Line 2`,`Prospect Contact Address Sorting Code`,`Prospect Contact Address Postal Code`,`Prospect Contact Address Dependent Locality`,`Prospect Contact Address Locality`,`Prospect Contact Address Administrative Area`,`Prospect Contact Address Country 2 Alpha Code`',
                'label'   => _('Contact address (Separated fields)'),
                'checked' => 0,
                'labels'  => array(
                    _('Contact address'),
                    _('Contact address line 2'),
                    _('Contact address sorting code'),
                    _('Contact address postal code'),
                    _('Contact address dependent locality'),
                    _('Contact address locality'),
                    _('Contact address administrative area'),
                    _('Contact address country'),

                )
            ),


        ),

        'orders'         => array(
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
                'name'    => '`Order Telephone`',
                'label'   => _('Telephone'),
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
            array(
                'name'    => 'REPLACE(`Order Invoice Address Formatted`,"<br/>","\n") as `Order Billing Address`',
                'label'   => _('Billing address'),
                'checked' => 0
            ),
            array(
                'name'    => '`Order Invoice Address Postal Label`',
                'label'   => _('Billing address (Postal label)'),
                'checked' => 0
            ),
            array(
                'name'    => '`Order Invoice Address Line 1`,`Order Invoice Address Line 2`,`Order Invoice Address Sorting Code`,`Order Invoice Address Postal Code`,`Order Invoice Address Dependent Locality`,`Order Invoice Address Locality`,`Order Invoice Address Administrative Area`,`Order Invoice Address Country 2 Alpha Code`',
                'label'   => _('Billing address (Separated fields)'),
                'checked' => 0,
                'labels'  => array(
                    _('Billing address'),
                    _('Billing address line 2'),
                    _('Billing address sorting code'),
                    _('Billing address postal code'),
                    _('Billing address dependent locality'),
                    _('Billing address locality'),
                    _('Billing address administrative area'),
                    _('Billing address country'),

                )
            ),
            array(
                'name'    => 'REPLACE(`Order Delivery Address Formatted`,"<br/>","\n") as `Order Delivery Address`',
                'label'   => _('Delivery address'),
                'checked' => 0
            ),
            array(
                'name'    => '`Order Delivery Address Postal Label`',
                'label'   => _('Delivery address (Postal label)'),
                'checked' => 0
            ),
            array(
                'name'    => '`Order Delivery Address Line 1`,`Order Delivery Address Line 2`,`Order Delivery Address Sorting Code`,`Order Delivery Address Postal Code`,`Order Delivery Address Dependent Locality`,`Order Delivery Address Locality`,`Order Delivery Address Administrative Area`,`Order Delivery Address Country 2 Alpha Code`',
                'label'   => _('Delivery address (Separated fields)'),
                'checked' => 0,
                'labels'  => array(
                    _('Delivery address'),
                    _('Delivery address line 2'),
                    _('Delivery address sorting code'),
                    _('Delivery address postal code'),
                    _('Delivery address dependent locality'),
                    _('Delivery address locality'),
                    _('Delivery address administrative area'),
                    _('Delivery address country'),

                )
            ),
            array(
                'name'    => '`Order Customer Purchase Order ID`',
                'label'   => _('Customer purchase number'),
                'checked' => 0
            ),

        ),
        'delivery_notes' => array(
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
            array(
                'name'    => '`Delivery Note Number Boxes`',
                'label'   => _('Number boxes'),
                'checked' => 1
            ),
        ),

        'delivery_note.units'        => array(
            array(
                'name'    => '`Part Reference`',
                'label'   => _('Reference'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Recommended Product Unit Name`',
                'label'   => _('Unit description'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Tariff Code`',
                'label'   => _('Tariff code'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Origin Country Code`',
                'label'   => _('Origin'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part UN Number`',
                'label'   => _('UN Number'),
                'checked' => 1
            ),
            array(
                'name'    => 'round(sum(`Inventory Transaction Weight`),3) ',
                'label'   => _('Weight'),
                'checked' => 1
            ),
            array(
                'name'    => 'sum(- 1.0 * `Part Units` * `Inventory Transaction Quantity`)',
                'label'   => _('Units'),
                'checked' => 1
            ),
            array(
                'name'    => 'sum(`Amount In`)',
                'label'   => _('Amount').' ('._('Account currency').')',
                'checked' => 1
            ),

        ),
        'delivery_note.tariff_codes' => array(
            array(
                'name'    => '`Part Tariff Code`',
                'label'   => _('Tariff code'),
                'checked' => 1,

            ),
            array(
                'name'    => '(select GROUP_CONCAT(`Commodity Name`) from kbase.`Commodity Code Dimension` where SUBSTRING(`Commodity Code`,1,8)=SUBSTRING(`Part Tariff Code`,1,8)  and `Commodity Name` IS NOT NULL ) as tc_name ',
                'label'   => _('Tariff code description'),
                'checked' => 1,

            ),
            array(
                'name'    => '`Part Origin Country Code`',
                'label'   => _('Origin'),
                'checked' => 1
            ),


            array(
                'name'    => 'GROUP_CONCAT(DISTINCT `Part UN Number`)',
                'label'   => _('UN numbers'),
                'checked' => 1
            ),
            array(
                'name'    => 'GROUP_CONCAT(DISTINCT `Part Reference`)',
                'label'   => _('References'),
                'checked' => 1
            ),
            array(
                'name'    => 'round(sum(`Inventory Transaction Weight`),3) ',
                'label'   => _('Weight'),
                'checked' => 1
            ),
            array(
                'name'    => 'sum(- 1.0 * `Part Units` * `Inventory Transaction Quantity`)',
                'label'   => _('Units'),
                'checked' => 0
            ),
            array(
                'name'    => 'sum(`Amount In`)',
                'label'   => _('Amount'),
                'checked' => 1
            ),

        ),
        'consignments'               => array(
            array(
                'name'    => '`Consignment Public ID`',
                'label'   => _('Number'),
                'checked' => 1
            ),
            array(
                'name'    => '`Consignment State`',
                'label'   => _('State'),
                'checked' => 1
            ),
            array(
                'name'    => '`Consignment Date`',
                'label'   => _('Date'),
                'checked' => 1
            ),
            array(
                'name'    => '`Consignment Number Delivery Notes`',
                'label'   => _('Delivery notes'),
                'checked' => 1
            ),
            array(
                'name'    => '`Consignment Number Boxes`',
                'label'   => _('Boxes'),
                'checked' => 1
            ),
        ),

        'shipper_consignments' => array(
            array(
                'name'    => '`Delivery Note ID`',
                'label'   => _('ID'),
                'checked' => 1
            ),
            array(
                'name'    => '`Delivery Note Shipper Tracking`',
                'label'   => _('Tracking'),
                'checked' => 1
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
            array(
                'name'    => '`Delivery Note Number Parcels`',
                'label'   => _('Number parcels'),
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
        ),

        'invoices'                     => array(
            array(
                'name'    => '`Invoice Type`',
                'label'   => _('Type'),
                'checked' => 1
            ),
            array(
                'name'    => '`Invoice Public ID`',
                'label'   => _('ID'),
                'checked' => 1
            ),
            array(
                'name'    => '`Order Public ID`',
                'label'   => _('Order ID'),
                'checked' => 1
            ),
            array(
                'name'    => '`Invoice Customer Name`',
                'label'   => _('Customer'),
                'checked' => 1
            ),
            array(
                'name'    => '`Invoice Tax Number`',
                'label'   => _('Tax number'),
                'checked' => 1
            ),
            array(
                'name'    => '`Invoice Customer Key`',
                'label'   => _('Customer Id'),
                'checked' => 0
            ),

            array(
                'name'    => '`Invoice Address Country 2 Alpha Code`',
                'label'   => _('Country'),
                'checked' => 1
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
                'name'    => '`Invoice Items Net Amount`',
                'label'   => _('Items net'),
                'checked' => 1
            ),

            array(
                'name'    => '`Invoice Shipping Net Amount`',
                'label'   => _('Shipping net'),
                'checked' => 1
            ),


            array(
                'name'    => '`Invoice Charges Net Amount`',
                'label'   => _('Charges net'),
                'checked' => 1
            ),

            array(
                'name'    => '`Invoice Insurance Net Amount`',
                'label'   => _('Insurance net'),
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
            ),
            array(
                'name'            => '(select group_concat(CONCAT_WS(\'|\',`Invoice Tax Code`,`Invoice Tax Amount`))  from `Invoice Tax Bridge` ITB where ITB.`Invoice Tax Invoice Key`= I.`Invoice Key`  )',
                'label'           => _('Tax codes (Separated fields)'),
                'checked'         => 1,
                'type'            => 'dynamic_headers',
                'header_field'    => 'B.`Invoice Tax Code`',
                'header_table'    => 'left join  `Invoice Tax Bridge` B on (I.`Invoice Key`=B.`Invoice Tax Invoice Key`)',
                'header_group_by' => 'group by B.`Invoice Tax Code`',

                'header_prefix' => _('Tax code').": "
            ),
            array(
                'name'            => '(select group_concat(CONCAT_WS(\'|\',`Invoice Tax Code`,`Invoice Tax Net`))  from `Invoice Tax Bridge` ITB where ITB.`Invoice Tax Invoice Key`= I.`Invoice Key`  )',
                'label'           => _('Tax base (Separated fields)'),
                'checked'         => 1,
                'type'            => 'dynamic_headers',
                'header_field'    => 'B.`Invoice Tax Code`',
                'header_table'    => 'left join  `Invoice Tax Bridge` B on (I.`Invoice Key`=B.`Invoice Tax Invoice Key`)',
                'header_group_by' => 'group by B.`Invoice Tax Code`',

                'header_prefix' => _('Tax base').": "
            ),

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

        'production_products' => array(
            array(
                'name'    => '`Supplier Code`',
                'label'   => _('Supplier'),
                'checked' => 0
            ),


            array(
                'name'    => '`Supplier Part Status`',
                'label'   => _('Availability'),
                'checked' => 1
            ),
            array(
                'name'    => '`Supplier Part Reference`',
                'label'   => _("Supplier's product code"),
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
                'name'    => '`Part Unit Label`',
                'label'   => _('Unit label'),
                'checked' => 1
            ),

            array(
                'name'    => '`Part Package Description`',
                'label'   => _('Part SKO description').' ('._('for picking aid').')',
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
                'name'    => '`Part SKO Barcode`',
                'label'   => _('SKO barcode'),
                'checked' => 1
            ),
            array(
                'name'    => '`Supplier Part Carton Barcode`',
                'label'   => _('Carton barcode'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Recommended Packages Per Selling Outer`',
                'label'   => _('Recommended SKOs per selling outer'),
                'checked' => 1
            ),

            array(
                'name'    => '`Supplier Part Status`',
                'label'   => _('Availability'),
                'checked' => 1
            ),

            array(
                'name'    => '`Supplier Part On Demand`',
                'label'   => _('On demand'),
                'checked' => 1
            ),


            array(
                'name'    => '`Part Unit Weight`',
                'label'   => _('Weight shown in website'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Unit Dimensions`',
                'label'   => _('Unit dimensions'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Package Weight`',
                'label'   => _('SKO Weight'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Package Dimensions`',
                'label'   => _('SKO dimensions'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Materials`',
                'label'   => _('Materials'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Origin Country Code`',
                'label'   => _('Country of origin'),
                'checked' => 1
            ),

            array(
                'name'    => '`Part Tariff Code`',
                'label'   => _('Tariff code'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Duty Rate`',
                'label'   => _('Duty Rate'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part HTSUS Code`',
                'label'   => _('HTSUS Code'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part UN Number`',
                'label'   => _('UN Number'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part UN Class`',
                'label'   => _('UN class'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Packing Group`',
                'label'   => _('Packing group'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Proper Shipping Name`',
                'label'   => _('Proper shipping name'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Hazard Identification Number`',
                'label'   => _('Hazard identification number'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part UFI`',
                'label'   => _('UFI (Poison Centres)'),
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
                'name'    => '`Supplier Part Unit Expense`',
                'label'   => _('Unit expense'),
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
                'checked' => 1,
                'type'    => 'text'
            ),


        ),

        'supplier_parts'    => array(
            array(
                'name'    => '`Supplier Code`',
                'label'   => _('Supplier'),
                'checked' => 0
            ),


            array(
                'name'    => '`Supplier Part Status`',
                'label'   => _('Availability'),
                'checked' => 1
            ),
            array(
                'name'    => '`Supplier Part Reference`',
                'label'   => _("Supplier's product code"),
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
                'name'    => '`Part Unit Label`',
                'label'   => _('Unit label'),
                'checked' => 1
            ),

            array(
                'name'    => '`Part Package Description`',
                'label'   => _('Part SKO description').' ('._('for picking aid').')',
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
                'name'    => '`Part SKO Barcode`',
                'label'   => _('SKO barcode'),
                'checked' => 1
            ),
            array(
                'name'    => '`Supplier Part Carton Barcode`',
                'label'   => _('Carton barcode'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Recommended Packages Per Selling Outer`',
                'label'   => _('Recommended SKOs per selling outer'),
                'checked' => 1
            ),

         

            array(
                'name'    => '`Supplier Part On Demand`',
                'label'   => _('On demand'),
                'checked' => 1
            ),


            array(
                'name'    => '`Part Unit Weight`',
                'label'   => _('Weight shown in website'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Unit Dimensions`',
                'label'   => _('Unit dimensions'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Package Weight`',
                'label'   => _('SKO Weight'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Package Dimensions`',
                'label'   => _('SKO dimensions'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Materials`',
                'label'   => _('Materials'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Origin Country Code`',
                'label'   => _('Country of origin'),
                'checked' => 1
            ),

            array(
                'name'    => '`Part Tariff Code`',
                'label'   => _('Tariff code'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Duty Rate`',
                'label'   => _('Duty Rate'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part HTSUS Code`',
                'label'   => _('HTSUS Code'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part UN Number`',
                'label'   => _('UN Number'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part UN Class`',
                'label'   => _('UN class'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Packing Group`',
                'label'   => _('Packing group'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Proper Shipping Name`',
                'label'   => _('Proper shipping name'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Hazard Identification Number`',
                'label'   => _('Hazard identification number'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part UFI`',
                'label'   => _('UFI (Poison Centres)'),
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
                'name'    => '`Supplier Part Unit Expense`',
                'label'   => _('Unit expense'),
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
                'checked' => 1,
                'type'    => 'text'
            ),


        ),
        'agent_parts'       => array(
            array(
                'name'    => '`Supplier Part Status`',
                'label'   => _('Availability'),
                'checked' => 1
            ),
            array(
                'name'    => '`Supplier Part Reference`',
                'label'   => _("Supplier's product code"),
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
                'name'    => '`Supplier Part Unit Expense`',
                'label'   => _('Unit expense'),
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
                'checked' => 1,
                'type'    => 'text'
            ),

        ),
        'ec_sales_list'     => array(
            array(
                'name'    => '`Invoice Address Country 2 Alpha Code`',
                'label'   => _('Country Code'),
                'checked' => 1
            ),
            array(
                'name'      => '`Invoice Customer Key`',
                'label'     => _('Customer ID'),
                'checked'   => 1,
                'cell_type' => 'string'
            ),
            array(
                'name'      => '`Invoice Tax Number`',
                'label'     => _('VAT registration number'),
                'checked'   => 1,
                'cell_type' => 'string'
            ),
            array(
                'name'      => 'sum(if(`Invoice Type`=\'Invoice\',1,0))',
                'label'     => _('Invoices'),
                'checked'   => 1,
                'cell_type' => 'string'
            ),
            array(
                'name'      => 'sum(if(`Invoice Type`=\'Refund\',1,0))',
                'label'     => _('Refunds'),
                'checked'   => 1,
                'cell_type' => 'string'
            ),

            array(
                'name'    => 'ROUND(sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`),2)',
                'label'   => _('Net').' ('.$account_currency_code.')',
                'checked' => 1
            ),
            array(
                'name'    => 'ROUND(sum(`Invoice Total Tax Amount`*`Invoice Currency Exchange`))',
                'label'   => _('Tax').' ('.$account_currency_code.')',
                'checked' => 1
            ),
            array(
                'name'    => '`Invoice Tax Number Valid`',
                'label'   => _('VAT registration number validation'),
                'checked' => 0
            ),
        ),
        'locations'         => array(
            array(
                'name'    => '`Location Code`',
                'label'   => _('Code'),
                'checked' => 1,
                'type'    => 'text'
            ),

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
            array(
                'name'    => '`Location Current Weight`',
                'label'   => _('Current weight').' (Kg)',
                'checked' => 1
            ),
            array(
                'name'    => 'CONCAT ("!W",`Location Warehouse Key`,"L",LPAD(`Location Key`,8,0))',
                'label'   => _('Barcode'),
                'checked' => 0,
                'type'    => 'text'
            ),
            array(
                'name'    => '`Location Stock Value`',
                'label'   => _('Stock value'),
                'checked' => 1
            ),


        ),
        'deleted_locations' => array(
            array(
                'name'    => '`Location Deleted Code`',
                'label'   => _('Code'),
                'checked' => 1
            ),
            array(
                'name'    => '`Location Deleted Warehouse Area Code`',
                'label'   => _('Area'),
                'checked' => 1
            ),
            array(
                'name'    => '`Location Deleted Date`',
                'label'   => _('Date'),
                'checked' => 1
            ),
            array(
                'name'    => '`Location Deleted Note`',
                'label'   => _('Note'),
                'checked' => 1
            ),


        ),
        'parts'             => array(
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
                'label'   => _('SKO barcode'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Carton Barcode`',
                'label'   => _('Carton barcode'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Tariff Code`',
                'label'   => _('Tariff code'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Duty Rate`',
                'label'   => _('Duty rate'),
                'checked' => 0
            ),
            array(
                'name'    => '`Part HTSUS Code`',
                'label'   => 'HTS US',
                'checked' => 1
            ),
            array(
                'name'    => '`Part UFI`',
                'label'   => _('UFI (Poison Centres)'),
                'checked' => 1
            ),

            array(
                'name'    => '`Part Package Weight`',
                'label'   => _('SKO weight (Kg)'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Units Per Package`',
                'label'   => _('Units per SKO'),
                'checked' => 0
            ),
            array(
                'name'    => '`Part Unit Label`',
                'label'   => _('Unit label'),
                'checked' => 0
            ),

            array(
                'name'    => '`Part Unit Weight`',
                'label'   => _('Weight shown in website').' (Kg)',
                'checked' => 0
            ),
            array(
                'name'    => '`Part Distinct Locations`',
                'label'   => _('Number locations'),
                'checked' => 1
            ),
            array(
                'name'    => '( select group_concat(`Location Code` SEPARATOR \' \') from `Part Location Dimension` PL left join `Location Dimension` L on (PL.`Location Key`=L.`Location Key`)  where PL.`Part SKU`=P.`Part SKU`   ) as locations',
                'label'   => _('Locations'),
                'checked' => 0
            ),
            array(
                'name'    => '`Part Picking Band Name`',
                'label'   => _('Picking band'),
                'checked' => 0
            ),
            array(
                'name'    => '`Part Packing Band Name`',
                'label'   => _('Packing band'),
                'checked' => 0
            ),


        ),
        'part_categories'   => array(
            array(
                'name'    => '`Category Code`',
                'label'   => _('Code'),
                'checked' => 1
            ),

            array(
                'name'    => '`Category Label`',
                'label'   => _('Name'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Category Status`',
                'label'   => _('Status'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Category In Process`',
                'label'   => _('In process parts'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Category Active`',
                'label'   => _('Active parts'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Category Discontinuing`',
                'label'   => _('Discontinuing parts'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Category Discontinued`',
                'label'   => _('Discontinued parts'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Category Number Surplus Parts`',
                'label'   => _('Active parts with surplus stock'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Category Number Optimal Parts`',
                'label'   => _('Active parts with optimal stock'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Category Number Low Parts`',
                'label'   => _('Active parts with low stock'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Category Number Critical Parts`',
                'label'   => _('Active parts with critical stock'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Category Number Out Of Stock Parts`',
                'label'   => _('Active parts out of stock'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Category Number Error Parts`',
                'label'   => _('Parts with stock errors'),
                'checked' => 1
            ),


        ),

        'part_locations'                          => array(
            array(
                'name'    => '`Part Reference`',
                'label'   => _('Part reference'),
                'checked' => 1,
                'type'    => 'text'
            ),
            array(
                'name'    => '`Location File As`',
                'label'   => _('Location expanded code'),
                'checked' => 0,
                'type'    => 'text'
            ),
            array(
                'name'    => '`Location Code`',
                'label'   => _('Location code'),
                'checked' => 1,
                'type'    => 'text'
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
                'label'   => _('Product Code'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Barcode Number`',
                'label'   => _('Unit barcode'),
                'checked' => 1,
                'type'    => 'text'
            ),
            array(
                'name'    => '`Supplier Part Description`',
                'label'   => _('Unit description'),
                'checked' => 1
            ),
            array(
                'name'    => '`Supplier Part Unit Cost`',
                'label'   => _('Unit cost'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Units Per Package`',
                'label'   => _('Packed in'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Units Per Package`*`Supplier Part Packages Per Carton`',
                'label'   => _('Units per carton'),
                'checked' => 1
            ),
            array(
                'name'    => '`Supplier Part Packages Per Carton`',
                'label'   => _('Packs per carton'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part SKO Barcode`',
                'label'   => _('SKO barcode'),
                'checked' => 1,
                'type'    => 'text'
            ),
            array(
                'name'    => '`Part Carton Barcode`',
                'label'   => _('Carton barcode'),
                'checked' => 1,
                'type'    => 'text'
            ),
            array(
                'name'    => '`Part Materials`',
                'label'   => _('Materials'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Tariff Code`',
                'label'   => _('Tariff code'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Origin Country Code`',
                'label'   => _('Origin'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part UN Number`',
                'label'   => _('UN Number'),
                'checked' => 1
            ),
            array(
                'name'    => '`Purchase Order Submitted Units`',
                'label'   => _('Ordered units'),
                'checked' => 1
            ),
            array(
                'name'    => '`Purchase Order Submitted Units`/`Part Units Per Package`',
                'label'   => _('Ordered packs'),
                'checked' => 1
            ),
            array(
                'name'    => '`Purchase Order Submitted Units`/`Part Units Per Package`/`Supplier Part Packages Per Carton`',
                'label'   => _('Ordered cartons'),
                'checked' => 1
            ),
            array(
                'name'    => '`Supplier Part Unit Cost`*`Purchase Order Submitted Units`',
                'label'   => _('Amount'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Main Image Key`',
                'label'   => _('Picture'),
                'checked' => 0
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

        'part_barcode_errors'     => array(
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
                'checked' => 1,
                'type'    => 'text'
            ),

            array(
                'name'    => '`Part Barcode Number Error`',
                'label'   => _('Barcode errors'),
                'checked' => 1
            ),

        ),
        'parts_weight_errors'     => array(
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
                'name'    => '`Part Package Weight`',
                'label'   => _('SKO weight'),
                'checked' => 1,
            ),
            array(
                'name'    => '`Part Package Weight Status`',
                'label'   => _('Weight status'),
                'checked' => 1
            ),


        ),
        'products'                => array(

            array(
                'name'    => '`Product Status`',
                'label'   => _('Status'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Type`',
                'label'   => _('Type'),
                'checked' => 1
            ),


            array(
                'name'    => '`Product Code`',
                'label'   => _('Code'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Barcode Number`',
                'label'   => _('Barcode'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product CPNP Number`',
                'label'   => _('CPNP number'),
                'checked' => 0
            ),
            array(
                'name'    => '`Product UFI`',
                'label'   => _('UFI (Poison Centres)'),
                'checked' => 0
            ),

            array(
                'name'    => '`Product XHTML Parts`',
                'label'   => _('Parts'),
                'checked' => 0
            ),


            array(
                'name'    => '( select `Category Code` from `Category Dimension` where `Category Key`=`Product Family Category Key`)',
                'label'   => _('Family'),
                'checked' => 0
            ),


            array(
                'name'    => '`Product Label in Family`',
                'label'   => _('Label in family'),
                'checked' => 0
            ),


            array(
                'name'    => '`Product Units Per Case`',
                'label'   => _('Units per outer'),
                'checked' => 1
            ),

            array(
                'name'    => '`Product Price`',
                'label'   => _('Outer price'),
                'checked' => 1
            ),

            array(
                'name'    => '`Product Cost`',
                'label'   => _('Outer cost'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Price`/`Product Units Per Case`',
                'label'   => _('Unit price'),
                'checked' => 0
            ),


            array(
                'name'    => '100*(`Product Price`-`Product Cost`)/`Product Price`',
                'label'   => _('Margin'),
                'checked' => 1
            ),


            array(
                'name'    => '`Product Unit Type`',
                'label'   => _('Unit label'),
                'checked' => 1
            ),

            array(
                'name'    => '`Product Name`',
                'label'   => _('Unit Name'),
                'checked' => 1
            ),


            array(
                'name'    => '`Product RRP`/`Product Units Per Case`',
                'label'   => _('Unit RRP'),
                'checked' => 1
            ),

            array(
                'name'    => '`Product Unit Weight`',
                'label'   => _('Unit weight (marketing)'),
                'checked' => 1
            ),

            array(
                'name'    => '`Product Unit XHTML Dimensions`',
                'label'   => _('Unit dimensions'),
                'checked' => 1
            ),

            array(
                'name'    => '`Product Unit XHTML Materials`',
                'label'   => _('Materials/Ingredients'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Published Webpage Description`',
                'label'   => _('Webpage description (html)'),
                'checked' => 0,
                'type'    => 'html'
            ),

            array(
                'name'    => '(`Product Published Webpage Description`) as plain',
                'label'   => _('Webpage description (plain text)'),
                'checked' => 0,
            ),


            array(
                'name'    => '`Product Origin Country Code`',
                'label'   => _('Country of origin'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Tariff Code`',
                'label'   => _('Tariff code'),
                'checked' => 0
            ),
            array(
                'name'    => '`Product Duty Rate`',
                'label'   => _('Duty rate'),
                'checked' => 0
            ),
            array(
                'name'    => '`Product HTSUS Code`',
                'label'   => 'HTS US',
                'checked' => 0
            ),
            array(
                'name'    => '`Product Availability`',
                'label'   => _('Stock'),
                'checked' => 1
            ),

            array(
                'name'    => '`Product Web State`',
                'label'   => _('Web state'),
                'checked' => 1
            ),
            array(
                'name'    => '( select group_concat(concat("[image_address]",`Image Subject Image Key`) order by `Image Subject Order`) from `Image Subject Bridge` where `Image Subject Object`="Product" and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`="Yes"  ) as images',
                'label'   => _('Images'),
                'checked' => 0
            ),
            array(
                'name'    => '(select concat("[image_address]",`Image Subject Image Key`)  from `Image Subject Bridge` where `Image Subject Object`="Product" and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`="Yes"  order by `Image Subject Order` limit 1 offset 0) as img1',
                'label'   => _('1st image'),
                'checked' => 0
            ),
            array(
                'name'    => '(select concat("[image_address]",`Image Subject Image Key`)  from `Image Subject Bridge` where `Image Subject Object`="Product" and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`="Yes"  order by `Image Subject Order` limit 1 offset 1) as img2',
                'label'   => _('2nd image'),
                'checked' => 0
            ),
            array(
                'name'    => '(select concat("[image_address]",`Image Subject Image Key`)  from `Image Subject Bridge` where `Image Subject Object`="Product" and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`="Yes"  order by `Image Subject Order` limit 1 offset 2) as img3',
                'label'   => _('3rd image'),
                'checked' => 0
            ),
            array(
                'name'    => '(select `Category Label` from `Category Dimension` where `Category Key`=`Product Family Category Key` ) as family',
                'label'   => _('Family'),
                'checked' => 0
            ),
            array(
                'name'    => '(select `Category Label` from `Category Dimension` where `Category Key`=`Product Department Category Key` ) as department',
                'label'   => _('Department'),
                'checked' => 0
            ),
            array(
                'name'    => '`Product Valid From`',
                'label'   => _('Created date'),
                'checked' => 0
            ),

        ),
        'portfolio_items'         => array(

            array(
                'name'    => '`Product Status`',
                'code'    => 'product_status',
                'label'   => _('Status'),
                'checked' => 1
            ),

            array(
                'name'    => '`Product Code`',
                'code'    => 'product_code',
                'label'   => _('Product code'),
                'checked' => 1
            ),
            array(
                'name'    => '`Customer Portfolio Reference`',
                'code'    => 'product_user_reference',
                'label'   => _("Product user reference"),
                'checked' => 1
            ),
            array(
                'name'    => '( select `Category Code` from `Category Dimension` where `Category Key`=`Product Family Category Key`)',
                'code'    => 'family',
                'label'   => _('Family'),
                'checked' => 0
            ),
            array(
                'name'    => '`Product Barcode Number`',
                'code'    => 'product_barcode',
                'label'   => _('Barcode'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product CPNP Number`',
                'code'    => 'product_cpnp',
                'label'   => _('CPNP number'),
                'checked' => 0
            ),


            array(
                'name'    => '`Product Price`',
                'code'    => 'product_price',
                'label'   => _('Price'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Units Per Case`',
                'code'    => 'units_per_outer',
                'label'   => _('Units per outer'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Unit Type`',
                'code'    => 'product_unit_type',
                'label'   => _('Unit label'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Price`/`Product Units Per Case`',
                'code'    => 'product_unit_price',
                'label'   => _('Unit price'),
                'checked' => 0
            ),


            array(
                'name'    => '`Product Name`',
                'code'    => 'product_unit_name',
                'label'   => _('Unit Name'),
                'checked' => 1
            ),


            array(
                'name'    => '`Product RRP`/`Product Units Per Case`',
                'code'    => 'product_unit_rrp',
                'label'   => _('Unit RRP'),
                'checked' => 1
            ),

            array(
                'name'    => '`Product Unit Weight`',
                'code'    => 'product_unit_weight',
                'label'   => _('Unit net weight'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Package Weight`',
                'code'    => 'product_package_weight',
                'label'   => _('Package weight (shipping)'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Unit XHTML Dimensions`',
                'code'    => 'product_unit_dimensions',
                'label'   => _('Unit dimensions'),
                'checked' => 1
            ),

            array(
                'name'    => '`Product Unit XHTML Materials`',
                'code'    => 'product_materials',
                'label'   => _('Materials/Ingredients'),
                'checked' => 1,
                'type'    => 'text'
            ),
            array(
                'name'    => '`Product Published Webpage Description`',
                'code'    => 'webpage_product_description_html',
                'label'   => _('Webpage description (html)'),
                'checked' => 0,
                'type'    => 'html'
            ),

            array(
                'name'    => '(`Product Published Webpage Description`) as plain',
                'code'    => 'webpage_product_description_text',
                'label'   => _('Webpage description (plain text)'),
                'checked' => 0,
            ),


            array(
                'name' => '`Product Origin Country Code`',
                'code' => 'product_origin_country',

                'label'   => _('Country of origin'),
                'checked' => 1
            ),
            array(
                'name' => '`Product Tariff Code`',
                'code' => 'product_tariff_code',

                'label'   => _('Tariff code'),
                'checked' => 0
            ),
            array(
                'name' => '`Product Duty Rate`',
                'code' => 'product_duty_rate',

                'label'   => _('Duty rate'),
                'checked' => 0
            ),
            array(
                'name' => '`Product HTSUS Code`',
                'code' => 'product_hts_us',

                'label'   => 'HTS US',
                'checked' => 0
            ),
            array(
                'name'    => '`Product Availability State`',
                'code'    => 'product_stock',
                'label'   => _('Stock'),
                'checked' => 1
            ),


            array(
                'name'    => '( select group_concat(concat("[image_address]",`Image Subject Image Key`,".",`Image Subject Image File Format`) order by `Image Subject Order`) from `Image Subject Bridge` where `Image Subject Object`="Product" and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`="Yes"  ) as images',
                'label'   => _('Images'),
                'checked' => 0,
                'type'    => 'array',
            ),
            array(
                'name'        => '(select concat("[image_address]",`Image Subject Image Key`,".",`Image Subject Image File Format`)  from `Image Subject Bridge` where `Image Subject Object`="Product" and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`="Yes"  order by `Image Subject Order` limit 1 offset 0) as img1',
                'label'       => _('1st image'),
                'checked'     => 0,
                'ignore_json' => true
            ),
            array(
                'name'        => '(select concat("[image_address]",`Image Subject Image Key`,".",`Image Subject Image File Format`)  from `Image Subject Bridge` where `Image Subject Object`="Product" and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`="Yes"  order by `Image Subject Order` limit 1 offset 1) as img2',
                'label'       => _('2nd image'),
                'checked'     => 0,
                'ignore_json' => true
            ),
            array(
                'name'        => '(select concat("[image_address]",`Image Subject Image Key`,".",`Image Subject Image File Format`)  from `Image Subject Bridge` where `Image Subject Object`="Product" and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`="Yes"  order by `Image Subject Order` limit 1 offset 2) as img3',
                'label'       => _('3rd image'),
                'checked'     => 0,
                'ignore_json' => true
            ),
            array(
                'name'        => '(select concat("[image_address]",`Image Subject Image Key`,".",`Image Subject Image File Format`)  from `Image Subject Bridge` where `Image Subject Object`="Product" and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`="Yes"  order by `Image Subject Order` limit 1 offset 3) as img4',
                'label'       => _('4th image'),
                'checked'     => 0,
                'ignore_json' => true
            ),
            array(
                'name'    => '`Product Data Updated`,`Product Stock Updated`,`Product Price Updated`,`Product Images Updated`',
                'label'   => _('Last updated'),
                'codes'   => array(
                    'data_last_updated_datetime',
                    'stock_last_updated_datetime',
                    'price_last_updated_datetime',
                    'images__updated_datetime',


                ),
                'labels'  => array(
                    _('Data updated'),
                    _('Stock updated'),
                    _('Price updated'),
                    _('Images updated'),


                ),
                'checked' => 1
            ),


        ),
        'website_catalogue_items' => array(

            array(
                'name'    => '`Product Status`',
                'code'    => 'product_status',
                'label'   => _('Status'),
                'checked' => 1
            ),

            array(
                'name'    => '`Product Code`',
                'code'    => 'product_code',
                'label'   => _('Product code'),
                'checked' => 1
            ),
            array(
                'name'    => '( select `Category Code` from `Category Dimension` where `Category Key`=`Product Family Category Key`)',
                'code'    => 'family',
                'label'   => _('Family'),
                'checked' => 0
            ),
            array(
                'name'    => '`Product Barcode Number`',
                'code'    => 'product_barcode',
                'label'   => _('Barcode'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product CPNP Number`',
                'code'    => 'product_cpnp',
                'label'   => _('CPNP number'),
                'checked' => 0
            ),

            array(
                'name'    => '`Product Price`',
                'code'    => 'product_price',
                'label'   => _('Price'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Units Per Case`',
                'code'    => 'units_per_outer',
                'label'   => _('Units per outer'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Unit Type`',
                'code'    => 'product_unit_type',
                'label'   => _('Unit label'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Price`/`Product Units Per Case`',
                'code'    => 'product_unit_price',
                'label'   => _('Unit price'),
                'checked' => 0
            ),


            array(
                'name'    => '`Product Name`',
                'code'    => 'product_unit_name',
                'label'   => _('Unit Name'),
                'checked' => 1
            ),


            array(
                'name'    => '`Product RRP`/`Product Units Per Case`',
                'code'    => 'product_unit_rrp',
                'label'   => _('Unit RRP'),
                'checked' => 1
            ),

            array(
                'name'    => '`Product Unit Weight`',
                'code'    => 'product_unit_weight',
                'label'   => _('Unit net weight'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Package Weight`',
                'code'    => 'product_package_weight',
                'label'   => _('Package weight (shipping)'),
                'checked' => 1
            ),
            array(
                'name'    => '`Product Unit XHTML Dimensions`',
                'code'    => 'product_unit_dimensions',
                'label'   => _('Unit dimensions'),
                'checked' => 1
            ),

            array(
                'name'    => '`Product Unit XHTML Materials`',
                'code'    => 'product_materials',
                'label'   => _('Materials/Ingredients'),
                'checked' => 1,
                'type'    => 'text'
            ),
            array(
                'name'    => '`Product Published Webpage Description`',
                'code'    => 'webpage_product_description_html',
                'label'   => _('Webpage description (html)'),
                'checked' => 0,
                'type'    => 'html'
            ),

            array(
                'name'    => '(`Product Published Webpage Description`) as plain',
                'code'    => 'webpage_product_description_text',
                'label'   => _('Webpage description (plain text)'),
                'checked' => 0,
            ),


            array(
                'name' => '`Product Origin Country Code`',
                'code' => 'product_origin_country',

                'label'   => _('Country of origin'),
                'checked' => 1
            ),
            array(
                'name' => '`Product Tariff Code`',
                'code' => 'product_tariff_code',

                'label'   => _('Tariff code'),
                'checked' => 0
            ),
            array(
                'name' => '`Product Duty Rate`',
                'code' => 'product_duty_rate',

                'label'   => _('Duty rate'),
                'checked' => 0
            ),
            array(
                'name' => '`Product HTSUS Code`',
                'code' => 'product_hts_us',

                'label'   => 'HTS US',
                'checked' => 0
            ),
            array(
                'name'    => '`Product Availability State`',
                'code'    => 'product_stock',
                'label'   => _('Stock'),
                'checked' => 1
            ),


            array(
                'name'    => '( select group_concat(concat("[image_address]",`Image Subject Image Key`,".",`Image Subject Image File Format`) order by `Image Subject Order`) from `Image Subject Bridge` where `Image Subject Object`="Product" and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`="Yes"  ) as images',
                'label'   => _('Images'),
                'checked' => 0,
                'type'    => 'array',
            ),
            array(
                'name'        => '(select concat("[image_address]",`Image Subject Image Key`,".",`Image Subject Image File Format`)  from `Image Subject Bridge` where `Image Subject Object`="Product" and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`="Yes"  order by `Image Subject Order` limit 1 offset 0) as img1',
                'label'       => _('1st image'),
                'checked'     => 0,
                'ignore_json' => true
            ),
            array(
                'name'        => '(select concat("[image_address]",`Image Subject Image Key`,".",`Image Subject Image File Format`)  from `Image Subject Bridge` where `Image Subject Object`="Product" and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`="Yes"  order by `Image Subject Order` limit 1 offset 1) as img2',
                'label'       => _('2nd image'),
                'checked'     => 0,
                'ignore_json' => true
            ),
            array(
                'name'        => '(select concat("[image_address]",`Image Subject Image Key`,".",`Image Subject Image File Format`)  from `Image Subject Bridge` where `Image Subject Object`="Product" and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`="Yes"  order by `Image Subject Order` limit 1 offset 2) as img3',
                'label'       => _('3rd image'),
                'checked'     => 0,
                'ignore_json' => true
            ),
            array(
                'name'        => '(select concat("[image_address]",`Image Subject Image Key`,".",`Image Subject Image File Format`)  from `Image Subject Bridge` where `Image Subject Object`="Product" and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`="Yes"  order by `Image Subject Order` limit 1 offset 3) as img4',
                'label'       => _('4th image'),
                'checked'     => 0,
                'ignore_json' => true
            ),
            array(
                'name'    => '`Product Data Updated`,`Product Stock Updated`,`Product Price Updated`,`Product Images Updated`',
                'label'   => _('Last updated'),
                'codes'   => array(
                    'data_last_updated_datetime',
                    'stock_last_updated_datetime',
                    'price_last_updated_datetime',
                    'images__updated_datetime',


                ),
                'labels'  => array(
                    _('Data updated'),
                    _('Stock updated'),
                    _('Price updated'),
                    _('Images updated'),


                ),
                'checked' => 1
            ),


        ),
        'intrastat'               => array(
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
                'name'    => 'sum(`Invoice Currency Exchange Rate`*`Order Transaction Amount`) ',
                'label'   => _('Amount').' ('.$account_currency_code.')',
                'checked' => 1
            ),
            array(
                'name'    => 'sum(`Delivery Note Quantity`*`Product Package Weight`)',
                'label'   => _('Weight').' (Kg)',
                'checked' => 1
            ),

        ),

        'warehouse_parts_to_replenish_picking_location' => array(
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
        ),


        'abandoned_cart.mail_list' => array(
            array(
                'name'    => '`Customer Main Plain Email`',
                'label'   => _('Email'),
                'checked' => 1
            ),
            array(
                'name'    => '`Customer Name`',
                'label'   => _('Customer name'),
                'checked' => 1
            ),
            array(
                'name'    => '`Customer Company Name`',
                'label'   => _('Company name'),
                'checked' => 1
            ),
            array(
                'name'    => '`Customer Main Contact Name`',
                'label'   => _('Contact name'),
                'checked' => 1
            ),
            array(
                'name'    => '`Order Public ID`',
                'label'   => _('Order number'),
                'checked' => 1
            ),

            array(
                'name'    => 'DATEDIFF(NOW(), `Order Last Updated Date`) ',
                'label'   => _('Inactive days in basket'),
                'checked' => 1
            ),
            array(
                'name'    => '`Order Last Updated Date`',
                'label'   => _('Order last updated'),
                'checked' => 0
            ),
        ),

        'mail_list' => array(
            array(
                'name'    => '`Customer Main Plain Email`',
                'label'   => _('Email'),
                'checked' => 1
            ),
            array(
                'name'    => '`Customer Key`',
                'label'   => _('Customer Id'),
                'checked' => 1
            ),
            array(
                'name'    => '`Customer Name`',
                'label'   => _('Customer name'),
                'checked' => 1
            ),
            array(
                'name'    => '`Customer Company Name`',
                'label'   => _('Company name'),
                'checked' => 1
            ),
            array(
                'name'    => '`Customer Main Contact Name`',
                'label'   => _('Contact name'),
                'checked' => 1
            ),

        ),

        'inventory_stock_history_day' => array(
            array(
                'name'    => 'part_reference',
                'label'   => _('Part'),
                'checked' => 1
            ),
            array(
                'name'    => 'part_description',
                'label'   => _('Part SKO description'),
                'checked' => 0
            ),
            array(
                'name'    => 'stock_on_hand',
                'label'   => _('Stock'),
                'checked' => 1
            ),
            array(
                'name'    => 'stock_cost',
                'label'   => _('Stock value'),
                'checked' => 1
            ),
            array(
                'name'    => 'sko_cost',
                'label'   => _('SKO value'),
                'checked' => 1
            ),
            array(
                'name'    => 'no_sales_1_year',
                'label'   => _('Dormant (No sales 1 year)'),
                'checked' => 1
            ),
            array(
                'name'    => 'stock_left_1_year_ago',
                'label'   => _('Stock older than 1 year'),
                'checked' => 1
            ),


        ),
        'client_order_items'          => array(

            array(
                'name'    => '`Supplier Code`',
                'label'   => _('Supplier code'),
                'checked' => 1
            ),
            array(
                'name'    => '`Suppler Name`',
                'label'   => _('Supplier name'),
                'checked' => 0
            ),
            array(
                'name'    => '`Supplier Part Reference`',
                'label'   => _('Product Code'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Barcode Number`',
                'label'   => _('Unit barcode'),
                'checked' => 1,
                'type'    => 'text'
            ),
            array(
                'name'    => '`Supplier Part Description`',
                'label'   => _('Unit description'),
                'checked' => 1
            ),
            array(
                'name'    => '`Supplier Part Unit Cost`',
                'label'   => _('Unit cost'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Units Per Package`',
                'label'   => _('Packed in'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Units Per Package`*`Supplier Part Packages Per Carton`',
                'label'   => _('Units per carton'),
                'checked' => 1
            ),
            array(
                'name'    => '`Supplier Part Packages Per Carton`',
                'label'   => _('Packs per carton'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part SKO Barcode`',
                'label'   => _('SKO barcode'),
                'checked' => 1,
                'type'    => 'text'
            ),
            array(
                'name'    => '`Part Carton Barcode`',
                'label'   => _('Carton barcode'),
                'checked' => 1,
                'type'    => 'text'
            ),
            array(
                'name'    => '`Part Materials`',
                'label'   => _('Materials'),
                'checked' => 1
            ),
            array(
                'name'    => '`Purchase Order Submitted Units`',
                'label'   => _('Ordered units'),
                'checked' => 1
            ),
            array(
                'name'    => '`Purchase Order Submitted Units`/`Part Units Per Package`',
                'label'   => _('Ordered packs'),
                'checked' => 1
            ),
            array(
                'name'    => '`Purchase Order Submitted Units`/`Part Units Per Package`/`Supplier Part Packages Per Carton`',
                'label'   => _('Ordered cartons'),
                'checked' => 1
            ),
            array(
                'name'    => '`Supplier Part Unit Cost`*`Purchase Order Submitted Units`',
                'label'   => _('Amount'),
                'checked' => 1
            ),
            array(
                'name'    => '`Part Main Image Key`',
                'label'   => _('Picture'),
                'checked' => 1
            ),


        ),
        'customer_sent_emails'        => array(
            array(
                'name'    => '`Email Tracking Email`',
                'label'   => _('Email'),
                'checked' => 1
            ),
            array(
                'name'    => '`Customer Key`',
                'label'   => _('Customer ID'),
                'checked' => 1
            ),
            array(
                'name'    => '`Customer Name`',
                'label'   => _('Customer name'),
                'checked' => 1
            ),
            array(
                'name'    => '`Email Tracking State`',
                'label'   => _('State'),
                'checked' => 1
            ),
            array(
                'name'    => '`Email Tracking Sent Date`',
                'label'   => _('Date sent'),
                'checked' => 1
            ),
            array(
                'name'    => '`Email Tracking Number Reads`',
                'label'   => _('Number of times opened'),
                'checked' => 1
            ),
            array(
                'name'    => '`Email Tracking Number Clicks`',
                'label'   => _('Number of clicks'),
                'checked' => 1
            ),
            array(
                'name'    => '`Email Tracking Spam`',
                'label'   => _('Marked as spam'),
                'checked' => 1
            ),
            array(
                'name'    => '`Email Tracking Unsubscribed`',
                'label'   => _('Unsubscribed'),
                'checked' => 1
            ),


        ),


    );

    return $export_fields[$element] ?? [];


}