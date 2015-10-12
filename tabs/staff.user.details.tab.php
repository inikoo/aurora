<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2015 at 19:52:58 CEST,  Fuengirola Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

$_user=new User($state['key']);



$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'class'=>'locked',
				'id'=>'User_ID',
				'value'=>$_user->get_formated_id() ,
				'label'=>_('ID')
			),
           
			array(
				'class'=>'string',
				'id'=>'User_Handle',
				'value'=>$_user->get('User Handle'),
				'label'=>_('Code')
			),
			array(
				'class'=>'string',
				'id'=>'User_Alias',
				'value'=>$_user->get('User Alias'),
				'label'=>_('Name')
			),

		)
	),
	
	array(
		'label'=>_('Access'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'User_Active',
				'value'=>$_user->get('User Active') ,
				'label'=>_('Active')
			),
            array(
				'id'=>'User_Password',
				'value'=>'********' ,
				'label'=>_('User_Password')
			),
			array(
				'class'=>'string',
				'id'=>'User_PIN',
				'value'=>'****' ,
				'label'=>_('PIN')
			)

		)
	),
	array(
		'label'=>_('Preferences'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'User_Preferred_Locale',
				'value'=>$_user->get('User Preferred Locale') ,
				'label'=>_('Language')
			)

		)
	),
);
$smarty->assign('object_fields',$object_fields);

$html=$smarty->fetch('object_fields.tpl');

?>
