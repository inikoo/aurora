<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 11 December 2015 at 23:07:03 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include 'utils/available_locales.php';
include 'utils/user_groups.php';

$system_user=new User($state['key']);


$options_yn=array(
	'Yes'=>_('Yes'), 'No'=>_('No')
);

$options_locales=array();
foreach ($available_locales as $locale) {

	$options_locales[$locale['Locale']]=$locale['Language Name'].($locale['Language Name']!=$locale['Language Original Name']?' ('.$locale['Language Original Name'].')':'');
}





$object_fields=array(


	array(
		'label'=>_('Access'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(
	
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
				'edit'=>'option',
				'value'=>$system_user->get('User Preferred Locale') ,
				'formated_value'=>$system_user->get('Preferred Locale') ,
				'label'=>ucfirst($system_user->get_field_label('Preferred Locale')),
				'options'=>$options_locales,


			)

		)
	),
);
$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);


$html=$smarty->fetch('object_fields.tpl');

?>
