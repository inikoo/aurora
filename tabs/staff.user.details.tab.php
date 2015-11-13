<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2015 at 19:52:58 CEST,  Fuengirola Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';

$options_yn=array(
	'Yes'=>_('Yes'), 'No'=>_('No')
);

asort($options_yn);




$system_user=new User($state['key']);



$object_fields=array(
	array(
		'label'=>_('Employee'),
		'show_title'=>true,
		'class'=>'links',
		'fields'=>array(
			array(
			    'render'=>false,
				'class'=>'locked',
				'id'=>'User_ID',
				'value'=>$system_user->get_formated_id() ,
				'label'=>_('ID')
			),

			array(
				'class'=>'link',
				'id'=>'User_Alias',
				'value'=>'',
				'label'=>$system_user->get('User Alias').' ('.sprintf('%35d',$system_user->get('User Parent Key')).')',
				'reference'=>'employee/'.$system_user->get('User Parent Key')
			),

		)
	),

	array(
		'label'=>_('Access'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(
			array(
				
			'id'=>'User_Active',
			'edit'=>'option',
			'value'=>$system_user->get('User Active'),
			'formated_value'=>$system_user->get('Active'),
			'options'=>$options_yn,
			'label'=>_('Active')
				
			),

			array(

				'id'=>'User_Handle',
				'edit'=>'string',
				'value'=>$system_user->get('User Handle'),
				'formated_value'=>$system_user->get('Handle'),
				'label'=>_('Login'),
				'server_validation'=>'check_for_duplicates'



			),
			array(
				'render'=>($system_user->get('User Active')=='Yes'?true:false),
				'id'=>'User_Password',
				'edit'=>'password',
				'value'=>$system_user->get('User Password'),
				'formated_value'=>$system_user->get('Password'),
				'label'=>_('Password'),
				'invalid_msg'=>get_invalid_message('password'),
			),
			array(
				'render'=>($system_user->get('User Active')=='Yes'?true:false),
				'id'=>'User_PIN',
				'edit'=>'pin',
				'value'=>$system_user->get('User PIN'),
				'formated_value'=>$system_user->get('PIN'),
				'label'=>_('PIN'),
				'invalid_msg'=>get_invalid_message('pin'),
			),

		)
	),
	array(
		'label'=>_('Preferences'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(
			array(
				'id'=>'User_Preferred_Locale',
				'value'=>$system_user->get('User Preferred Locale') ,
				'label'=>_('Language')
			)

		)
	),
);
$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);


$html=$smarty->fetch('object_fields.tpl');

?>
