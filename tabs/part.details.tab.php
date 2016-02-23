<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 9 October 2015 at 12:43:25 CEST, Malaga Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';


$part=$state['_object'];



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
				'server_validation'=>'check_for_duplicates'
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



			),

			array(
				'id'=>'Part_Package_Weight',
				'edit'=>'numeric',
				'value'=>$part->get('Part Package Weight') ,
				'formatted_value'=>$part->get('Package Weight') ,
				'label'=>ucfirst($part->get_field_label('Part Package Weight')),
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>true,
			),
			array(
				'id'=>'Part_Package_XHTML_Dimensions',
				'edit'=>'numeric',
				'value'=>$part->get('Part Package XHTML Dimensions') ,
				'formatted_value'=>$part->get('Package XHTML Dimensions') ,
				'label'=>ucfirst($part->get_field_label('Part Package XHTML Dimensions')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,
			),


			array(
				'id'=>'Part_Tariff_Code',
				'edit'=>'numeric',
				'value'=>$part->get('Part Tariff Code') ,
				'formatted_value'=>$part->get('Tariff Code') ,
				'label'=>ucfirst($part->get_field_label('Part Tariff Code')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,


			),
			array(
				'id'=>'Part_Duty_Rate',
				'edit'=>'numeric',
				'value'=>$part->get('Part Duty Rate') ,
				'formatted_value'=>$part->get('Duty Rate') ,
				'label'=>ucfirst($part->get_field_label('Part Duty Rate')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,

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
			)


		)
	),


);
$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);

$html=$smarty->fetch('edit_object.tpl');

?>
