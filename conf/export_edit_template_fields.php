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
		array('default_value'=>'', 'show_for_new'=>false, 'required'=>true, 'field'=>'Supplier Part Supplier Code', 'name'=>'Supplier Code', 'label'=>_("Supplier"), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Supplier Part Reference', 'name'=>'Supplier Part Reference', 'label'=>_("Supplier's SKU"), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Part Reference', 'name'=>'Part Reference', 'label'=>_('Part reference'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Barcode Number', 'name'=>'Part Barcode Number', 'label'=>_('Part barcode'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Part Package Description', 'name'=>'Part Package Description', 'label'=>_('Outers (SKO) description'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Part Unit Description', 'name'=>'Part Unit Description', 'label'=>_('Unit description'), 'checked'=>0),
		array('default_value'=>_('Piece'), 'show_for_new'=>true, 'required'=>true, 'field'=>'Part Unit Label', 'name'=>'Part Unit Label', 'label'=>_('Unit label'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Supplier Part Packages Per Carton', 'name'=>'Supplier Part Packages Per Carton', 'label'=>_('Outers (SKO) per carton'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Part Units Per Package', 'name'=>'Part Units Per Package', 'label'=>_('Units per SKO'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>false, 'required'=>false, 'field'=>'Supplier Part Status', 'name'=>'Supplier Part Status', 'label'=>_('Availability'), 'checked'=>0),
		array('default_value'=>'1', 'show_for_new'=>true, 'required'=>true, 'field'=>'Supplier Part Minimum Carton Order', 'name'=>'Supplier Part Minimum Carton Order', 'label'=>_('Minimum order (cartons)'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Supplier Part Average Delivery Days', 'name'=>'Supplier Part Average Delivery Days', 'label'=>_('Average delivery time (days)'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Supplier Part Carton CBM', 'name'=>'Supplier Part Carton CBM', 'label'=>_('Carton CBM'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Supplier Part Unit Cost', 'name'=>'Supplier Part Unit Cost', 'label'=>_('Unit cost'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Supplier Part Unit Extra Cost', 'name'=>'Supplier Part Unit Extra Cost', 'label'=>_('Unit extra costs'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Unit Price', 'name'=>'Part Part Unit Price', 'label'=>_('Unit recommended price'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Unit RRP', 'name'=>'Part Part Unit RRP', 'label'=>_('Unit recommended RRP'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Family Code', 'name'=>'Part Family Category Code', 'label'=>_('Part family'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Unit Weight', 'name'=>'Part Part Unit Weight', 'label'=>_('Unit weight'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Unit Dimensions', 'name'=>'Part Part Unit Dimensions', 'label'=>_('Unit dimensions'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Package Weight', 'name'=>'Part Part Package Weight', 'label'=>_('Package weight'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Package Dimensions', 'name'=>'Part Part Package Dimensions', 'label'=>_('Package dimensions'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Materials', 'name'=>'Part Part Materials', 'label'=>_('Materials'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Origin Country Code', 'name'=>'Part Part Origin Country Code', 'label'=>_('Country of origin'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Tariff Code', 'name'=>'Part Part Tariff Code', 'label'=>_('Tariff code'), 'checked'=>0 , 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Duty Rate', 'name'=>'Part Part Duty Rate', 'label'=>_('Duty rate'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part UN Number', 'name'=>'Part UN Number', 'label'=>_('UN number'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part UN Class', 'name'=>'Part Part UN Class', 'label'=>_('UN class'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Packing Group', 'name'=>'Part Part Packing Group', 'label'=>_('Packing group'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Proper Shipping Name', 'name'=>'Part Part Proper Shipping Name', 'label'=>_('Proper shipping name'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Hazard Indentification Number', 'name'=>'Part Part Hazard Indentification Number', 'label'=>_('Hazard indentification number'), 'checked'=>0, 'cell_type'=>'string'),



	),
	'part'=>array(
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Part Reference', 'name'=>'Part Reference', 'label'=>_('Part reference'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Barcode Number', 'name'=>'Part Barcode Number', 'label'=>_('Part barcode'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Part Package Description', 'name'=>'Part Package Description', 'label'=>_('Outers (SKO) description'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Part Unit Description', 'name'=>'Part Unit Description', 'label'=>_('Unit description'), 'checked'=>0),
		array('default_value'=>_('piece'), 'show_for_new'=>true, 'required'=>true, 'field'=>'Part Unit Label', 'name'=>'Part Unit Label', 'label'=>_('Unit label'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Part Units Per Package', 'name'=>'Part Units Per Package', 'label'=>_('Units per SKO'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>false, 'required'=>false, 'field'=>'Part Status', 'name'=>'Part Status', 'label'=>_('Status'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Unit Price', 'name'=>'Part Unit Price', 'label'=>_('Unit recommended price'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Unit RRP', 'name'=>'Part Unit RRP', 'label'=>_('Unit recommended RRP'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Family Code', 'name'=>'Part Family Category Code', 'label'=>_('Part family'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Unit Weight', 'name'=>'Part Unit Weight', 'label'=>_('Unit weight'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Unit Dimensions', 'name'=>'Part Unit Dimensions', 'label'=>_('Unit dimensions'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Package Weight', 'name'=>'Part Package Weight', 'label'=>_('Package weight'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Package Dimensions', 'name'=>'Part Package Dimensions', 'label'=>_('Package dimensions'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Materials', 'name'=>'Part Materials', 'label'=>_('Materials'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Origin Country Code', 'name'=>'Part Origin Country Code', 'label'=>_('Country of origin'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Tariff Code', 'name'=>'Part Tariff Code', 'label'=>_('Tariff code'), 'checked'=>0 , 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Duty Rate', 'name'=>'Part Duty Rate', 'label'=>_('Duty rate'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part UN Number', 'name'=>'Part UN Number', 'label'=>_('UN number'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part UN Class', 'name'=>'Part UN Class', 'label'=>_('UN class'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Packing Group', 'name'=>'Part Packing Group', 'label'=>_('Packing group'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Proper Shipping Name', 'name'=>'Part Proper Shipping Name', 'label'=>_('Proper shipping name'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Hazard Indentification Number', 'name'=>'Part Hazard Indentification Number', 'label'=>_('Hazard indentification number'), 'checked'=>0, 'cell_type'=>'string'),



	),

	'product'=>array(
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Product Code', 'name'=>'Product Code', 'label'=>_('Code'), 'checked'=>1, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Parts', 'name'=>'Parts', 'label'=>_('Parts'), 'checked'=>1, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Product Family Code', 'name'=>'Product Family Category Code', 'label'=>_('Family code'), 'checked'=>1, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Product Label in Family', 'name'=>'Product Label in Family', 'label'=>_('Label in family'), 'checked'=>1),

		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Product Units Per Case', 'name'=>'Product Units Per Case', 'label'=>_('Units per outer'), 'checked'=>1),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Product Price', 'name'=>'Product Price', 'label'=>_('Outer price'), 'checked'=>1),


		array('default_value'=>_('piece'), 'show_for_new'=>true, 'required'=>true, 'field'=>'Product Unit Label', 'name'=>'Product Unit Label', 'label'=>_('Unit label'), 'checked'=>1),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Product Name', 'name'=>'Product Name', 'label'=>_('Unit name'), 'checked'=>1, 'cell_type'=>'string'),

		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Product Unit RRP', 'name'=>'Product Unit RRP', 'label'=>_('Unit RRP'), 'checked'=>1),




	),
	'product_no_part'=>array(
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Product Code', 'name'=>'Product Code', 'label'=>_('Code'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Product Family Code', 'name'=>'Product Family Category Code', 'label'=>_('Family code'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Product Label in Family', 'name'=>'Product Label in Family', 'label'=>_('Label in family'), 'checked'=>0),
		array('default_value'=>_('piece'), 'show_for_new'=>true, 'required'=>true, 'field'=>'Product Unit Label', 'name'=>'Product Unit Label', 'label'=>_('Unit label'), 'checked'=>0),


		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Barcode Number', 'name'=>'Part Barcode Number', 'label'=>_('Part barcode'), 'checked'=>0, 'cell_type'=>'string'),

		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Part Unit Description', 'name'=>'Part Unit Description', 'label'=>_('Unit description'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>true, 'field'=>'Part Units Per Package', 'name'=>'Part Units Per Package', 'label'=>_('Units per SKO'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>false, 'required'=>false, 'field'=>'Part Status', 'name'=>'Part Status', 'label'=>_('Status'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Unit Price', 'name'=>'Part Unit Price', 'label'=>_('Unit recommended price'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Unit RRP', 'name'=>'Part Unit RRP', 'label'=>_('Unit recommended RRP'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Family Code', 'name'=>'Part Family Category Code', 'label'=>_('Part family'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Unit Weight', 'name'=>'Part Unit Weight', 'label'=>_('Unit weight'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Unit Dimensions', 'name'=>'Part Unit Dimensions', 'label'=>_('Unit dimensions'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Package Weight', 'name'=>'Part Package Weight', 'label'=>_('Package weight'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Package Dimensions', 'name'=>'Part Package Dimensions', 'label'=>_('Package dimensions'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Materials', 'name'=>'Part Materials', 'label'=>_('Materials'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Origin Country Code', 'name'=>'Part Origin Country Code', 'label'=>_('Country of origin'), 'checked'=>0),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Tariff Code', 'name'=>'Part Tariff Code', 'label'=>_('Tariff code'), 'checked'=>0 , 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Duty Rate', 'name'=>'Part Duty Rate', 'label'=>_('Duty rate'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part UN Number', 'name'=>'Part UN Number', 'label'=>_('UN number'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part UN Class', 'name'=>'Part UN Class', 'label'=>_('UN class'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Packing Group', 'name'=>'Part Packing Group', 'label'=>_('Packing group'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Proper Shipping Name', 'name'=>'Part Proper Shipping Name', 'label'=>_('Proper shipping name'), 'checked'=>0, 'cell_type'=>'string'),
		array('default_value'=>'', 'show_for_new'=>true, 'required'=>false, 'field'=>'Part Hazard Indentification Number', 'name'=>'Part Hazard Indentification Number', 'label'=>_('Hazard indentification number'), 'checked'=>0, 'cell_type'=>'string'),



	),

);

?>
