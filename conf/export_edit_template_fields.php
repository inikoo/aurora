<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2016 at 14:21:08 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$export_edit_template_fields=array(

	'supplier_part'=>array(
		array('default_value'=>'', 'show_for_new'=>false, 'required'=>true, 'header'=>'Supplier', 'name'=>'Supplier Code', 'label'=>_("Supplier"), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>"Supplier's SKU", 'name'=>'Supplier Part Reference', 'label'=>_("Supplier's SKU"), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Part reference', 'name'=>'Part Reference', 'label'=>_('Part reference'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Part barcode', 'name'=>'Part Barcode Number', 'label'=>_('Part barcode'), 'checked'=>0, 'cell_type'=>'string'),
				array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Family', 'name'=>'Part Family Category Code', 'label'=>_('Family'), 'checked'=>0),

		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Outers (SKO) description', 'name'=>'Part Package Description', 'label'=>_('Outers (SKO) description'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Unit description', 'name'=>'Part Unit Description', 'label'=>_('Unit description'), 'checked'=>0),
		array('default_value'=>_('Piece'), 'show_for_new'=>true, 'required'=>true, 'header'=>'Unit label', 'name'=>'Part Unit Label', 'label'=>_('Unit label'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Outers (SKO) per carton', 'name'=>'Supplier Part Packages Per Carton', 'label'=>_('Outers (SKO) per carton'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Units per SKO', 'name'=>'Part Units Per Package', 'label'=>_('Units per SKO'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>false, 'required'=>false, 'header'=>'Availability', 'name'=>'Supplier Part Status', 'label'=>_('Availability'), 'checked'=>0),
		array('default_value'=>'1', 'show_for_new'=>true, 'required'=>true, 'header'=>'Minimum order (cartons)', 'name'=>'Supplier Part Minimum Carton Order', 'label'=>_('Minimum order (cartons)'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Average delivery time (days)', 'name'=>'Supplier Part Average Delivery Days', 'label'=>_('Average delivery time (days)'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Carton CBM', 'name'=>'Supplier Part Carton CBM', 'label'=>_('Carton CBM'), 'checked'=>0),
		
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Unit cost', 'name'=>'Supplier Part Unit Cost', 'label'=>_('Unit cost'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Unit extra costs', 'name'=>'Supplier Part Unit Extra Cost', 'label'=>_('Unit extra costs'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Unit recommended price', 'name'=>'Part Part Unit Price', 'label'=>_('Unit recommended price'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Unit recommended RRP', 'name'=>'Part Part Unit RRP', 'label'=>_('Unit recommended RRP'), 'checked'=>0),
		
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Unit weight', 'name'=>'Part Part Unit Weight', 'label'=>_('Unit weight'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Unit dimensions', 'name'=>'Part Part Unit Dimensions', 'label'=>_('Unit dimensions'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'SKO weight', 'name'=>'Part Part Package Weight', 'label'=>_('SKO weight'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'SKO dimensions', 'name'=>'Part Part Package Dimensions', 'label'=>_('SKO dimensions'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Materials', 'name'=>'Part Part Materials', 'label'=>_('Materials'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Country of origin', 'name'=>'Part Part Origin Country Code', 'label'=>_('Country of origin'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Tariff code', 'name'=>'Part Part Tariff Code', 'label'=>_('Tariff code'), 'checked'=>0 , 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Duty rate', 'name'=>'Part Part Duty Rate', 'label'=>_('Duty rate'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'UN number', 'name'=>'Part Part UN Number', 'label'=>_('UN number'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'UN class', 'name'=>'Part Part UN Class', 'label'=>_('UN class'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Packing group', 'name'=>'Part Part Packing Group', 'label'=>_('Packing group'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Proper shipping name', 'name'=>'Part Part Proper Shipping Name', 'label'=>_('Proper shipping name'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Hazard indentification number', 'name'=>'Part Part Hazard Indentification Number', 'label'=>_('Hazard indentification number'), 'checked'=>0, 'cell_type'=>'string'),



	),
	'part'=>array(
		array('default_value'=>'', 'show_for_new'=>false, 'required'=>false, 'header'=>'Status', 'name'=>'Part Status', 'label'=>_('Status'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Reference', 'name'=>'Part Reference', 'label'=>_('Reference'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Barcode', 'name'=>'Part Barcode Number', 'label'=>_('Part barcode'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Unit description', 'name'=>'Part Unit Description', 'label'=>_('Unit description'), 'checked'=>0),
		array('default_value'=>_('piece'), 'show_for_new'=>true, 'required'=>true, 'header'=>'Unit label', 'name'=>'Part Unit Label', 'label'=>_('Unit label'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Unit weight', 'name'=>'Part Unit Weight', 'label'=>_('Unit weight'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Unit dimensions', 'name'=>'Part Unit Dimensions', 'label'=>_('Unit dimensions'), 'checked'=>0),
		
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Unit recommended price', 'name'=>'Part Unit Price', 'label'=>_('Unit recommended price'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Unit recommended RRP', 'name'=>'Part Unit RRP', 'label'=>_('Unit recommended RRP'), 'checked'=>0),		
		
		
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Units per SKO', 'name'=>'Part Units Per Package', 'label'=>_('Units per SKO'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'SKO description', 'name'=>'Part Package Description', 'label'=>_('SKO description'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'SKO weight', 'name'=>'Part Package Weight', 'label'=>_('SKO weight'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'SKO dimensions', 'name'=>'Part Package Dimensions', 'label'=>_('SKO dimensions'), 'checked'=>0),

		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Materials/Ingredients', 'name'=>'Part Materials', 'label'=>_('Materials/Ingredients'), 'checked'=>0),

		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Country of origin', 'name'=>'Part Origin Country Code', 'label'=>_('Country of origin'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Tariff code', 'name'=>'Part Tariff Code', 'label'=>_('Tariff code'), 'checked'=>0 , 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Duty rate', 'name'=>'Part Duty Rate', 'label'=>_('Duty rate'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'UN number', 'name'=>'Part UN Number', 'label'=>_('UN number'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'UN class', 'name'=>'Part UN Class', 'label'=>_('UN class'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Packing group', 'name'=>'Part Packing Group', 'label'=>_('Packing group'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Proper shipping name', 'name'=>'Part Proper Shipping Name', 'label'=>_('Proper shipping name'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Hazard indentification number', 'name'=>'Part Hazard Indentification Number', 'label'=>_('Hazard indentification number'), 'checked'=>0, 'cell_type'=>'string'),



	),

	'product'=>array(
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Code', 'name'=>'Product Code', 'label'=>_('Code'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Parts', 'name'=>'Parts', 'label'=>_('Parts'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Family code', 'name'=>'Family Category Code', 'label'=>_('Family code'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Label in family', 'name'=>'Product Label in Family', 'label'=>_('Label in family'), 'checked'=>0),

		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Units per outer', 'name'=>'Product Units Per Case', 'label'=>_('Units per outer'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Outer price', 'name'=>'Product Price', 'label'=>_('Outer price'), 'checked'=>0),


		array('default_value'=>_('piece'), 'show_for_new'=>true, 'required'=>true, 'header'=>'Unit label', 'name'=>'Product Unit Label', 'label'=>_('Unit label'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'header'=>'Unit name', 'name'=>'Product Name', 'label'=>_('Unit name'), 'checked'=>0, 'cell_type'=>'string'),

		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'header'=>'Unit RRP', 'name'=>'Product Unit RRP', 'label'=>_('Unit RRP'), 'checked'=>0),




	),
	

);

?>
