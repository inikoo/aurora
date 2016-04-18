<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 16 April 2016 at 20:27:49 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/



$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(

			array(
				'edit'=>($edit?'string':''),

				'id'=>'Warehouse_Code',
				'value'=>$object->get('Warehouse Code'),
				'formatted_value'=>$object->get('Code'),
				'label'=>ucfirst($object->get_field_label('Warehouse Code')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'type'=>'value'
			),
			array(
				'edit'=>($edit?'string':''),

				'id'=>'Warehouse_Name',
				'value'=>$object->get('Warehouse Name')  ,
				'formatted_value'=>$object->get('Name'),
				'label'=>ucfirst($object->get_field_label('Warehouse Name')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'type'=>'value'
			),

		)
	),

	array(
		'label'=>_('Address'),
		'show_title'=>true,
		'fields'=>array(


			array(
				'edit'=>($edit?'textarea':''),

				'id'=>'Warehouse_Address',
				'value'=>$object->get('Warehouse Address'),
				'formatted_value'=>$object->get('Address'),
				'label'=>ucfirst($object->get_field_label('Warehouse Address')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,
				'type'=>'value'
			),
		)
	),


);

?>
