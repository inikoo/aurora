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
	)

);

?>