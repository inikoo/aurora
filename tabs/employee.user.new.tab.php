<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 5 January 2016 at 23:26:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'class.Staff.php';

$system_user=$state['_object'];
$employee=new Staff($state['parent_key']);

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
			'label'=>ucfirst($employee->get_field_label('Staff User Active')),
			'type'=>'value'
		),
		array(
			'render'=>true,
			'id'=>'User_Handle',
			'edit'=>'string',
			'value'=>$employee->get('Staff User Handle'),
			'formatted_value'=>$employee->get('User Handle'),
			'label'=>ucfirst($employee->get_field_label('Staff User Handle')),
			'server_validation'=>'check_for_duplicates',
			'type'=>'value'

		),

		array(
			'render'=>true,

			'id'=>'User_Password',
			'edit'=>'password',
			'value'=>'',
			'formatted_value'=>'******',
			'label'=>ucfirst($employee->get_field_label('Staff User Password')),
			'invalid_msg'=>get_invalid_message('password'),
			'type'=>'value'

		),
		array(
			'render'=>true,
			'id'=>'User_PIN',
			'edit'=>'pin',
			'value'=>'',
			'formatted_value'=>'****',
			'label'=>ucfirst($employee->get_field_label('Staff User PIN')),
			'invalid_msg'=>get_invalid_message('pin'),
			'type'=>'value'

		),



	)
)
);
$smarty->assign('state', $state);
$smarty->assign('object', $system_user);

$smarty->assign('object_name', preg_replace('/ /', '_', $system_user->get_object_name()));



$smarty->assign('object_fields', $object_fields);


$html=$smarty->fetch('new_object.tpl');

?>
