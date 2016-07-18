<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2016 at 22:40:52 CEST, Mijas Costa, Spain

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
				'id'=>'Webpage_Code',
				'edit'=>($edit?'string':''),
				'value'=>$object->get('Webpage Code'),
				'label'=>ucfirst($object->get_field_label('Code')),
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'invalid_msg'=>get_invalid_message('string'),
			),
			array(
				'id'=>'Webpage_Name',
				'edit'=>($edit?'string':''),
				'value'=>$object->get('Webpage Name'),
				'label'=>ucfirst($object->get_field_label('Name')),
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'invalid_msg'=>get_invalid_message('string'),
			),


		)
	),


);

if (!$new) {
	$operations=array(
		'label'=>_('Operations'),
		'show_title'=>true,
		'class'=>'operations',
		'fields'=>array(

			array(
				'id'=>'delete_website',
				'class'=>'operation',
				'value'=>'',
				'label'=>'<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data='{ "object": "{$object->get_object_name()}", "key":"{$object->id}"}' onClick="delete_object(this)" class="delete_object disabled">'._("Delete webpage version").' <i class="fa fa-trash new_button link"></i></span>',
				'reference'=>'',
				'type'=>'operation'
			),




		)

	);

	$object_fields[]=$operations;
}





?>
