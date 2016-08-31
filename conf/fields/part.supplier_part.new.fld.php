<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 31 August 2016 at 12:59:08 GMT+8, Kuta, Bali, Indonedia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'utils/static_data.php';

$account=new Account();

$new=true;
$show_full_label=true;
$part_scope=true;


$options_status=array('Available'=>_('Available'), 'NoAvailable'=>_('No stock'), 'Discontinued'=>_('Discontinued'));


$supplier_part_fields=array();






$supplier_part_fields[]=array(
	'label'=>($show_full_label?_("Supplier's part description"):_('Description')),

	'show_title'=>true,
	'fields'=>array(
		array(
			'id'=>'Supplier_Part_Supplier_Key',
			'render'=>(($new and $part_scope)?true:false ),
			'edit'=>'dropdown_select',
			'scope'=>'suppliers',
			'parent'=>'account',
			'parent_key'=>1,
			'value'=>0,
			'formatted_value'=>'',
			'stripped_formatted_value'=>'',
			'label'=>("Supplier's code"),
			'placeholder'=>_("Supplier's code"),
			'required'=>($part_scope?true:false ),
			'type'=>'value'
		),
		array(
			'id'=>'Supplier_Part_Reference',
			'edit'=>($edit?'string':''),

			'value'=>htmlspecialchars($object->get('Supplier Part Reference')),
			'formatted_value'=>$object->get('Reference'),
			'label'=>ucfirst($object->get_field_label('Supplier Part Reference')),
			'required'=>true,
			'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
			'type'=>'value'
		),




		array(
			'id'=>'Supplier_Part_Packages_Per_Carton',
			'edit'=>'smallint_unsigned',
			'value'=>($new?1:htmlspecialchars($object->get('Supplier Part Packages Per Carton'))),
			'formatted_value'=>($new?1:$object->get('Packages Per Carton')),
			'label'=>ucfirst($object->get_field_label('Supplier Part Packages Per Carton')),
			'required'=>true,
			'type'=>'value'
		),

	)
);



$supplier_part_fields[]=array(
	'label'=>($show_full_label?_("Supplier's part ordering"):_('Ordering')),

	'show_title'=>true,
	'fields'=>array(
		array(
			'render'=>($new?false:true),
			'id'=>'Supplier_Part_Status',
			'edit'=>($edit?'option':''),

			'options'=>$options_status,
			'value'=>htmlspecialchars($object->get('Supplier Part Status')),
			'formatted_value'=>$object->get('Status'),
			'label'=>ucfirst($object->get_field_label('Supplier Part Status')),
			'required'=>($new?false:true),
			'type'=>'skip'
		),




		array(
			'id'=>'Supplier_Part_Minimum_Carton_Order',
			'edit'=>'smallint_unsigned',
			'value'=>($new?1:htmlspecialchars($object->get('Supplier Part Minimum Carton Order'))),
			'formatted_value'=>($new?1:$object->get('Minimum Carton Order')),
			'label'=>ucfirst($object->get_field_label('Supplier Part Minimum Carton Order')),
			'placeholder'=>_('cartons'),

			'required'=>true,
			'type'=>'value'
		),
		array(
			'id'=>'Supplier_Part_Average_Delivery_Days',
			'edit'=>($edit?'numeric':''),

			'value'=>($new?  ''  :htmlspecialchars($object->get('Supplier Part Average Delivery Days'))),

			'formatted_value'=>($new? '':$object->get('Average Delivery Days')),
			'label'=>ucfirst($object->get_field_label('Supplier Part Average Delivery Days')),
			'placeholder'=>_('days'),

			'required'=>false,
			'type'=>'value'
		),
		array(
			'id'=>'Supplier_Part_Carton_CBM',
			'edit'=>($edit?'numeric':''),

			'value'=>htmlspecialchars($object->get('Supplier Part Carton CBM')),
			'formatted_value'=>$object->get('Carton CBM'),
			'label'=>ucfirst($object->get_field_label('Supplier Part Carton CBM')),
			'placeholder'=>_('cubic meters'),
			'required'=>false,
			'type'=>'value'
		),


	)
);

$supplier_part_fields[]=array(
	'label'=>($show_full_label?_("Supplier's part cost/price"):_('Cost/price')),

	'show_title'=>true,
	'fields'=>array(

		array(
			'id'=>'Supplier_Part_Unit_Cost',
			'edit'=>($edit?'amount':''),
			'locked'=>($part_scope?1:0),
			'value'=>htmlspecialchars($object->get('Supplier Part Unit Cost')),
			'formatted_value'=>$object->get('Unit Cost'),
			'label'=>ucfirst($object->get_field_label('Supplier Part Unit Cost')),
			'required'=>true,
			'placeholder'=>($part_scope? _('select supplier'):sprintf(_('amount in %s '), $options['supplier']->get('Default Currency Code'))),
			'type'=>'value'
		),

		array(
			'id'=>'Supplier_Part_Unit_Extra_Cost',
			'edit'=>'amount_percentage',
			'locked'=>($part_scope?1:0),
			'value'=>htmlspecialchars($object->get('Supplier Part Unit Extra Cost')),
			'formatted_value'=>$object->get('Unit Extra Cost'),
			'label'=>ucfirst($object->get_field_label('Supplier Part Unit Extra Cost')),
			'required'=>false,
			'placeholder'=>($part_scope?_('select supplier'): sprintf(_('amount in %s or %%'), $options['supplier']->get('Default Currency Code'))),
			'type'=>'value'
		),
	

	)
);








?>
