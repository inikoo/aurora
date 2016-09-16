<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 December 2015 at 14:25:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$export_fields=array(
	'customers'=>array(
		array('name'=>'C.`Customer Key`', 'label'=>_('ID'), 'checked'=>1),
		array('name'=>'`Customer Name`', 'label'=>_('Name'), 'checked'=>1),
		array('name'=>'`Customer Main Contact Name`', 'label'=>_('Contact'), 'checked'=>1),
		array('name'=>'`Customer Main Plain Email`', 'label'=>_('Email'), 'checked'=>1),
		array('name'=>'`Customer Main Plain Telephone`', 'label'=>_('Telephone'), 'checked'=>0),
		array('name'=>'`Customer Main Plain Mobile`', 'label'=>_('Mobile'), 'checked'=>0),
		array('name'=>'`Customer Tax Number`', 'label'=>_('Tax Number'), 'checked'=>0),
		array('name'=>'REPLACE(`Customer Main XHTML Address`,"<br/>","\n") as`Customer Address`', 'label'=>_('Contact address'), 'checked'=>0),
		array('name'=>'`Customer Main Postal Address`', 'label'=>_('Contact address (Postal label)'), 'checked'=>0),
		array('name'=>'`Customer Main Address Line 1`,`Customer Main Address Line 2`,`Customer Main Address Line 3`,`Customer Main Town`,`Customer Main Postal Code`,`Customer Main Country Second Division`,`Customer Main Country First Division`,`Customer Main Country Code`', 'label'=>_('Contact address (Separated fields)'), 'checked'=>0),
		array('name'=>'`Customer Main Address Lines`', 'label'=>_('Contact address (Lines)'), 'checked'=>0),

		array('name'=>'REPLACE(`Customer XHTML Billing Address`,"<br/>","\n") as`Customer Billing Address`', 'label'=>_('Billing address'), 'checked'=>0),
		array('name'=>'`Customer Billing Address Lines`,`Customer Billing Address Town`,`Customer Billing Address Country Code`', 'label'=>_('Billing address (Separated fields)'), 'checked'=>0),
		array('name'=>'REPLACE(`Customer XHTML Main Delivery Address`,"<br/>","\n") as`Customer Delivery Address`', 'label'=>_('Delivery address'), 'checked'=>0),
		array('name'=>'`Customer Main Delivery Address Lines`,`Customer Main Delivery Address Town`,`Customer Main Delivery Address Postal Code`,`Customer Main Delivery Address Region`,`Customer Main Delivery Address Country Code`', 'label'=>_('Delivery address (Separated fields)'), 'checked'=>0),
		array('name'=>'`Customer Last Order Date`', 'label'=>_('Last order date'), 'checked'=>0),
	),
	'orders'=>array(
		array('name'=>'`Order Public ID`', 'label'=>_('ID'), 'checked'=>1),
		array('name'=>'`Order Customer Name`', 'label'=>_('Customer'), 'checked'=>1),
		array('name'=>'`Order Customer Key`', 'label'=>_('Customer Id'), 'checked'=>0),
		array('name'=>'`Order Date`', 'label'=>_('Date'), 'checked'=>1),
		array('name'=>'`Order Balance Total Amount`', 'label'=>_('Total'), 'checked'=>1),
		array('name'=>'`Order Payment Method`', 'label'=>_('Payment method'), 'checked'=>1),

	),
	'delivery_notes'=>array(
		array('name'=>'`Delivery Note ID`', 'label'=>_('ID'), 'checked'=>1),
		array('name'=>'`Delivery Note Customer Name`', 'label'=>_('Customer'), 'checked'=>1),
		array('name'=>'`Delivery Note Customer Key`', 'label'=>_('Customer Id'), 'checked'=>0),
		array('name'=>'`Delivery Note Date`', 'label'=>_('Date'), 'checked'=>1),
		array('name'=>'`Delivery Note Weight`', 'label'=>_('Weight'), 'checked'=>1),
	),
	'invoices'=>array(
		array('name'=>'`Invoice Title`', 'label'=>_('Type'), 'checked'=>1),
		array('name'=>'`Invoice Public ID`', 'label'=>_('ID'), 'checked'=>1),
		array('name'=>'`Invoice Customer Name`', 'label'=>_('Customer'), 'checked'=>1),
		array('name'=>'`Invoice Customer Key`', 'label'=>_('Customer Id'), 'checked'=>0),
		array('name'=>'`Invoice Date`', 'label'=>_('Date'), 'checked'=>1),
		array('name'=>'`Invoice Currency`', 'label'=>_('Currency'), 'checked'=>1),
		array('name'=>'`Invoice Total Net Amount`', 'label'=>_('Net'), 'checked'=>1),
		array('name'=>'`Invoice Total Tax Amount`', 'label'=>_('Tax'), 'checked'=>1),
		array('name'=>'`Payment Type`', 'label'=>_('Payment type'), 'checked'=>1),
		array('name'=>'`Payment Account Name`', 'label'=>_('Payment Account'), 'checked'=>1),

	),
	'timeserie_records'=>array(
	  		array('name'=>'`Timeseries Record Date`', 'label'=>_('Date'), 'checked'=>1),
	  		array('name'=>'`Timeseries Record Float A`', 'label'=>'A', 'checked'=>1),
	  		array('name'=>'`Timeseries Record Float B`', 'label'=>'B', 'checked'=>0),
	  		array('name'=>'`Timeseries Record Float C`', 'label'=>'C', 'checked'=>0),
	  		array('name'=>'`Timeseries Record Float D`', 'label'=>'D', 'checked'=>0),
	  		array('name'=>'`Timeseries Record Integer A`', 'label'=>'E', 'checked'=>0),
	  		array('name'=>'`Timeseries Record Integer B`', 'label'=>'F', 'checked'=>0),
  
	),
	'timeserie_records_StoreSales'=>array(
	  		array('name'=>'`Timeseries Record Date`', 'label'=>_('Date'), 'checked'=>1),
	  		array('name'=>'`Timeseries Record Float A`', 'label'=>_('Sales Net'), 'checked'=>1),
	  		array('name'=>'`Timeseries Record Float B`', 'label'=>_('Sales Net'), 'checked'=>1),

	  		array('name'=>'`Timeseries Record Integer A`', 'label'=>_('Invoices'), 'checked'=>1),
	  		array('name'=>'`Timeseries Record Integer B`', 'label'=>_('Refunds'), 'checked'=>1),
  
	),
	'supplier_parts'=>array(
		array('name'=>'`Supplier Part Reference`', 'label'=>_("Supplier's SKU"), 'checked'=>1),
		array('name'=>'`Part Reference`', 'label'=>_('Part reference'), 'checked'=>1),
		array('name'=>'`Part Barcode Number`', 'label'=>_('Part barcode'), 'checked'=>1),
		array('name'=>'`Part Package Description`', 'label'=>_('Outers (SKO) description'), 'checked'=>1),
		array('name'=>'`Part Unit Description`', 'label'=>_('Unit description'), 'checked'=>1),
		array('name'=>'`Supplier Part Packages Per Carton`', 'label'=>_('Outers (SKO) per carton'), 'checked'=>1),
		array('name'=>'`Part Units Per Package`', 'label'=>_('Units per SKO'), 'checked'=>1),
		array('name'=>'`Supplier Part Status`', 'label'=>_('Availability'), 'checked'=>1),
		array('name'=>'`Supplier Part Minimum Carton Order`', 'label'=>_('Minimum order (cartons)'), 'checked'=>1),
		array('name'=>'`Supplier Part Average Delivery Days`', 'label'=>_('Average delivery time (days)'), 'checked'=>1),
		array('name'=>'`Supplier Part Carton CBM`', 'label'=>_('Carton CBM'), 'checked'=>1),
		array('name'=>'`Supplier Part Unit Cost`', 'label'=>_('Unit cost'), 'checked'=>1),
		array('name'=>'`Supplier Part Unit Extra Cost`', 'label'=>_('Unit extra costs'), 'checked'=>1),
		array('name'=>'`Part Unit Price`', 'label'=>_('Unit recommended price'), 'checked'=>1),
		array('name'=>'`Part Unit RRP`', 'label'=>_('Unit recommended RRP'), 'checked'=>1),
		
		
		
	),
	'ec_sales_list'=>array(
		array('name'=>'`Invoice Billing Country 2 Alpha Code`', 'label'=>_('Country Code'), 'checked'=>1),
		array('name'=>'`Invoice Tax Number`', 'label'=>_('VAT registration number'), 'checked'=>1),
		array('name'=>'ROUND(`Invoice Total Net Amount`*`Invoice Currency Exchange`,2)', 'label'=>_('Net'), 'checked'=>1),
		array('name'=>'ROUND(`Invoice Total Tax Amount`*`Invoice Currency Exchange`)', 'label'=>_('Tax'), 'checked'=>1),
		array('name'=>'`Invoice Tax Number Valid`', 'label'=>_('VAT registration number validation'), 'checked'=>0),
	)
	
	

);

?>
