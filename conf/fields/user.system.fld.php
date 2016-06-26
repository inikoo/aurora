<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 25 June 2016 at 15:45:05 GMT+8 Kuta, Bali, Indonesia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


if (isset($options['new']) and  $options['new'] ) {
	$new=true;
}else {
	$new=false;
}

$options_yn=array(
	'Yes'=>_('Yes'), 'No'=>_('No')
);
asort($options_yn);



$object_fields=array(
	array(
		'label'=>_('System user'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(




			array(
				'render'=>true,
				'id'=>'User_Active',
				'edit'=>'option',
				'value'=>'Yes',
				'formatted_value'=>_('Yes'),
				'options'=>$options_yn,
				'label'=>ucfirst($object->get_field_label($object->get_object_name().' User Active')),
				'type'=>'value'
			),
			array(
				'render'=>true,
				'id'=>'User_Handle',
				'edit'=>'handle',
				'value'=>$object->get($object->get_object_name().' User Handle'),
				'formatted_value'=>$object->get('User Handle'),
				'label'=>ucfirst($object->get_field_label($object->get_object_name().' User Handle')),
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates', 'parent'=>'account', 'parent_key'=>1, 'object'=>'User', 'key'=>$object->id)),   'invalid_msg'=>get_invalid_message('handle'),
				'type'=>'value'

			),

			array(
				'render'=>true,

				'id'=>'User_Password',
				'edit'=>'password',
				'value'=>'',
				'formatted_value'=>'******',
				'label'=>ucfirst($object->get_field_label($object->get_object_name().' User Password')),
				'invalid_msg'=>get_invalid_message('password'),
				'type'=>'value'

			),
			array(
				'render'=>($object->get_object_name()=='Staff'?true:false  ),
				'id'=>'User_PIN',
				'edit'=>'pin',
				'value'=>'',
				'formatted_value'=>'****',
				'label'=>ucfirst($object->get_field_label($object->get_object_name().' User PIN')),
				'invalid_msg'=>get_invalid_message('pin'),
				'type'=>($object->get_object_name()=='Staff'?'value':''  )

			),



		)
	)
);


if (!$new) {
	$operations=array(
		'label'=>_('Operations'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(
			array(

				'id'=>'delete_agent',
				'class'=>'new',
				'value'=>'',
				'label'=>'<i class="fa fa-lock button" style="margin-right:20px"></i> <span class="disabled">'._('Delete agent').' <i class="fa fa-trash new_button link"></i></span>',
				'reference'=>'',
				'type'=>'ignore'
			),

		)

	);

	$ibject_fields[]=$operations;
}



?>
