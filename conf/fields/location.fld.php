<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 28 April 2016 at 17:40:42 GMT+8, Lovina, Bali, Indonesia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

$used_for_options=array('Picking'=>_('Picking'),'Storing'=>_('Storing'),'Loading'=>_('Loading'),'Displaying'=>_('Displaying'));
asort($used_for_options);



$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(

			array(
				'edit'=>($edit?'string':''),

				'id'=>'Location_Code',
				'value'=>$object->get('Location Code'),
				'formatted_value'=>$object->get('Code'),
				'label'=>ucfirst($object->get_field_label('Location Code')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'type'=>'value'
			),
			array(
				'edit'=>($edit?'option':''),

				'id'=>'Location_Mainly_Used_For',
				'value'=>$object->get('LLocation Mainly Used For'),
				'formatted_value'=>$object->get('Mainly Used For'),
				'options'=>$used_for_options,
				'label'=>ucfirst($object->get_field_label('Location Mainly Used For')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'type'=>'value'
			),

		)
	),




);

?>
