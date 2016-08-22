<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 November 2015 at 14:20:16 GMT Sheffied UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'class.Staff.php';


$api_key=$state['_object'];

$options_API_Key_Scope=array(
	'Timesheet'=>_('Timesheet')
);

asort($options_API_Key_Scope);

$object_fields=array(
	array(
		'label'=>_('Scope'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(



			array(
				'id'=>'API_Key_Scope',
				'edit'=>'option',
				'value'=>'',
				'formatted_value'=>'',
				'options'=>$options_API_Key_Scope,
				'label'=>ucfirst($api_key->get_field_label('API Scope')),
				'required'=>true,

				'type'=>'value'
			),


		)
	),

	array(
		'label'=>_('Restrictions'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(

			array(

				'id'=>'API_Key_Allowed_IP',
				'edit'=>'string',
				'value'=>'',
				'formatted_value'=>'',

				'label'=>ucfirst($api_key->get_field_label('API Key Allowed IP')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>false,
				'type'=>'value'

			),
			array(

				'id'=>'API_Key_Allowed_Requests_per_Hour',
				'edit'=>'smallint_unsigned',
				'value'=>60,
				'formatted_value'=>60,
				'label'=>ucfirst($api_key->get_field_label('API Key Allowed Requests per Hour')),
				'invalid_msg'=>get_invalid_message('smallint_unsigned'),
				'required'=>true,
				'type'=>'value'
			)

		)
	),


);



$smarty->assign('state', $state);
$smarty->assign('object', $api_key);

$smarty->assign('object_name', preg_replace('/ /','_',$api_key->get_object_name()));


$smarty->assign('object_fields', $object_fields);


$html=$smarty->fetch('new_object.tpl');

?>
