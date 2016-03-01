<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 25 February 2016 at 19:04:00 GMT+8 Kuala Lumpur , Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';


$part=new Part(0);

$options_Packing_Group=array(
	'None'=>_('None'), 'I'=>'I', 'II'=>'II', 'III'=>'III'
);


$options_yn=array(
	'Yes'=>_('Yes'), 'No'=>_('No')
);



asort($options_yn);

$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Part_Reference',
				'edit'=>'string',
				'value'=>htmlspecialchars($part->get('Part Reference')),
				'formatted_value'=>$part->get('Reference'),
				'label'=>ucfirst($part->get_field_label('Part Reference')),
				'required'=>true,
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),

				'type'=>'value'
			),




		)
	),

	array(
		'label'=>_('Stock unit'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Part_Unit_Description',
				'edit'=>'string',
				'value'=>htmlspecialchars($part->get('Part Unit Description')),
				'formatted_value'=>$part->get('Unit Description'),
				'label'=>ucfirst($part->get_field_label('Part Unit Description')),
				'required'=>true,
				'type'=>'value'


			),

			array(
				'id'=>'Part_Package_Weight',
				'edit'=>'numeric',
				'value'=>$part->get('Part Package Weight') ,
				'formatted_value'=>$part->get('Package Weight') ,
				'label'=>ucfirst($part->get_field_label('Part Package Weight')),
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>false,
				'placeholder'=>_('Kg'),

				'type'=>'value'
			),
			array(
				'id'=>'Part_Package_Dimensions',
				'edit'=>'dimensions',
				'value'=>$part->get('Part Package Dimensions') ,
				'formatted_value'=>$part->get('Package Dimensions') ,
				'label'=>ucfirst($part->get_field_label('Part Package Dimensions')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>false,
				'placeholder'=>_('L x W x H (in cm)'),
				'type'=>'value'
			),


			array(
				'id'=>'Part_Tariff_Code',
				'edit'=>'numeric',
				'value'=>$part->get('Part Tariff Code') ,
				'formatted_value'=>$part->get('Tariff Code') ,
				'label'=>ucfirst($part->get_field_label('Part Tariff Code')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>false,
				'type'=>'value'

			),
			array(
				'id'=>'Part_Duty_Rate',
				'edit'=>'numeric',
				'value'=>$part->get('Part Duty Rate') ,
				'formatted_value'=>$part->get('Duty Rate') ,
				'label'=>ucfirst($part->get_field_label('Part Duty Rate')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>false,
				'type'=>'value'

			)


		)
	),
	array(
		'label'=>_('Health & Safety'),
		'show_title'=>true,
		'fields'=>array(

			array(
				'id'=>'Part_UN_Number',
				'edit'=>'string',
				'value'=>htmlspecialchars($part->get('Part UN Number')),
				'formatted_value'=>$part->get('UN Number'),
				'label'=>ucfirst($part->get_field_label('Part UN Number')),
				'required'=>false
			),
			array(
				'id'=>'Part_UN_Class',
				'edit'=>'string',
				'value'=>htmlspecialchars($part->get('Part UN Class')),
				'formatted_value'=>$part->get('UN Class'),
				'label'=>ucfirst($part->get_field_label('Part UN Class')),
				'required'=>false
			),
			array(
				'id'=>'Part_Packing_Group',
				'edit'=>'option',
				'options'=>$options_Packing_Group,
				'value'=>htmlspecialchars($part->get('Part Packing Group')),
				'formatted_value'=>$part->get('Packing Group'),
				'label'=>ucfirst($part->get_field_label('Part Packing Group')),
				'required'=>false
			),
			array(
				'id'=>'Part_Proper_Shipping_Name',
				'edit'=>'string',
				'value'=>htmlspecialchars($part->get('Part Proper Shipping Name')),
				'formatted_value'=>$part->get('Proper Shipping Name'),
				'label'=>ucfirst($part->get_field_label('Part Proper Shipping Name')),
				'required'=>false
			),
			array(
				'id'=>'Part_Hazard_Indentification_Number',
				'edit'=>'string',
				'value'=>htmlspecialchars($part->get('Part Hazard Indentification Number')),
				'formatted_value'=>$part->get('Hazard Indentification Number'),
				'label'=>ucfirst($part->get_field_label('Part Hazard Indentification Number')),
				'required'=>false
			)
		)






	),

	array(
		'label'=>_('Components'),
		'show_title'=>true,
		'fields'=>array(

			array(
				'id'=>'Part_Materials',
				'edit'=>'textarea',
				'value'=>htmlspecialchars($part->get('Part Materials')),
				'formatted_value'=>$part->get('Materials'),
				'label'=>ucfirst($part->get_field_label('Part Materials')),
				'required'=>false
			),

			array(
				'id'=>'Part_Origin_Country_Code',
				'edit'=>'country',
				'value'=>htmlspecialchars($part->get('Part Origin Country Code')),
				'formatted_value'=>$part->get('Origin Country Code'),
				'label'=>ucfirst($part->get_field_label('Part Origin Country Code')),
				'required'=>false
			),

		)





	)


);
$smarty->assign('state', $state);
$smarty->assign('object', $part);


$smarty->assign('object_name', $part->get_object_name());


$smarty->assign('object_fields', $object_fields);
//$smarty->assign('new_object_label', _('View new employee'));
//$smarty->assign('new_object_request','employee/__key__');



//$smarty->assign('js_code', file_get_contents('js/employee.new.js'));

$html=$smarty->fetch('new_object.tpl');

?>
