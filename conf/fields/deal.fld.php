<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2016 at 14:19:03 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


if (isset($options['new']) and  $options['new'] ) {
	$new=true;
}else {
	$new=false;
}


$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(

	
			array(
				'edit'=>($edit?'string':''),
				'id'=>'Deal_Name',
				'value'=>$object->get('Deal Name'),
				'label'=>ucfirst($object->get_field_label('Deal Name')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),

				'type'=>'value'
			),


		)
	),
	

);



?>
