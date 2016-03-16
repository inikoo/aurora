<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2015 at 19:52:58 CEST,  Fuengirola Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'class.Staff.php';

include_once 'utils/invalid_messages.php';
include 'utils/available_locales.php';
include 'conf/user_groups.php';

$system_user=new User($state['key']);


$options_yn=array(
	'Yes'=>_('Yes'), 'No'=>_('No')
);

$options_locales=array();
foreach ($available_locales as $locale) {

	$options_locales[$locale['Locale']]=$locale['Language Name'].($locale['Language Name']!=$locale['Language Original Name']?' ('.$locale['Language Original Name'].')':'');
}



$options_Groups=array();
foreach ($user_groups as $key=>$user_group) {
	$options_Groups[$key]=array(
		'label'=>$user_group['Name'],
		'selected'=>false
	);
}
foreach (preg_split('/,/', $system_user->get('User Groups')) as $key) {
	if ($key) {

		//TODO remove after migrating to aurora, and taking off un used grous from User Group User Bridge
		if ($key==4 or $key==7 or $key==12) {
			continue;
		}

		$options_Groups[$key]['selected']=true;
	}
}


$options_Stores=array();
$sql=sprintf('select `Store Key` as `key` ,`Store Name`,`Store Code`   from `Store Dimension`  ');
foreach ($db->query($sql) as $row) {
	$options_Stores[$row['key']]=array(
		'label'=>$row['Store Code'],
		'selected'=>false
	);
}
foreach (preg_split('/,/', $system_user->get('User Stores')) as $key) {
	if (array_key_exists($key, $options_Stores))
		$options_Stores[$key]['selected']=true;
}

$options_Websites=array();
$sql=sprintf('select `Site Key` as `key` ,`Site Name`,`Site Code` from `Site Dimension`  ');
foreach ($db->query($sql) as $row) {
	$options_Websites[$row['key']]=array(
		'label'=>$row['Site Code'],
		'selected'=>false
	);
}
foreach (preg_split('/,/', $system_user->get('User Websites')) as $key) {
	if (array_key_exists($key, $options_Websites))
		$options_Websites[$key]['selected']=true;
}

$options_Warehouses=array();
$sql=sprintf('select `Warehouse Key` as `key` ,`Warehouse Name`,`Warehouse Code` from `Warehouse Dimension`  ');
foreach ($db->query($sql) as $row) {
	$options_Warehouses[$row['key']]=array(
		'label'=>$row['Warehouse Code'],
		'selected'=>false
	);
}
foreach (preg_split('/,/', $system_user->get('User Warehouses')) as $key) {
	if (array_key_exists($key, $options_Warehouses))

		$options_Warehouses[$key]['selected']=true;
}



asort($options_yn);
asort($options_locales);
asort($options_Groups);


$staff=new Staff($system_user->get('User Parent Key'));


$object_fields=array(
	array(
		'label'=>($staff->get('Type')=='Contractor'?_('Contractor'):_('Employee')),
		'show_title'=>true,
		'class'=>'links',
		'fields'=>array(


			array(
				'class'=>'link',
				'id'=>'User_Alias',
				'value'=>'',
				'label'=>$system_user->get('User Alias').' ('.sprintf('%35d', $system_user->get('User Parent Key')).')',
				'reference'=>($staff->get('Type')=='Contractor'?'contractor':'employee').'/'.$system_user->get('User Parent Key')
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
				'formatted_value'=>$system_user->get('Active'),
				'options'=>$options_yn,
				'label'=>ucfirst($system_user->get_field_label('User Active')),

			),

			array(

				'id'=>'User_Handle',
				'edit'=>'handle',
				'value'=>$system_user->get('User Handle'),
				'formatted_value'=>$system_user->get('Handle'),
				'label'=>_('Login'),
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'invalid_msg'=>get_invalid_message('handle'),



			),
			array(
				'render'=>($system_user->get('User Active')=='Yes'?true:false),
				'id'=>'User_Password',
				'edit'=>'password',
				'value'=>$system_user->get('User Password'),
				'formatted_value'=>$system_user->get('Password'),
				'label'=>_('Password'),
				'invalid_msg'=>get_invalid_message('password'),
			),
			array(
				'render'=>($system_user->get('User Active')=='Yes'?true:false),
				'id'=>'User_PIN',
				'edit'=>'pin',
				'value'=>$system_user->get('User PIN'),
				'formatted_value'=>$system_user->get('PIN'),
				'label'=>_('PIN'),
				'invalid_msg'=>get_invalid_message('pin'),
			),

		)
	),
	array(
		'label'=>_('Permissions'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(
			array(
				'id'=>'User_Groups',
				'edit'=>'radio_option',
				'value'=>$system_user->get('User Groups') ,
				'formatted_value'=>$system_user->get('Groups') ,
				'label'=>ucfirst($system_user->get_field_label('User Groups')),
				'options'=>$options_Groups,


			),
			array(
				'id'=>'User_Stores',
				'edit'=>'radio_option',
				'value'=>$system_user->get('User Stores') ,
				'formatted_value'=>$system_user->get('Stores') ,
				'label'=>ucfirst($system_user->get_field_label('User Stores')),
				'options'=>$options_Stores,
				'required'=>false

			),
			array(
				'id'=>'User_Websites',
				'edit'=>'radio_option',
				'value'=>$system_user->get('User Websites') ,
				'formatted_value'=>$system_user->get('Websites') ,
				'label'=>ucfirst($system_user->get_field_label('User Websites')),
				'options'=>$options_Websites,
				'required'=>false

			), array(
				'id'=>'User_Warehouses',
				'edit'=>'radio_option',
				'value'=>$system_user->get('User Warehouses') ,
				'formatted_value'=>$system_user->get('Warehouses') ,
				'label'=>ucfirst($system_user->get_field_label('User Warehouses')),
				'options'=>$options_Warehouses,
				'required'=>false


			)

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
				'formatted_value'=>$system_user->get('Preferred Locale') ,
				'label'=>ucfirst($system_user->get_field_label('Preferred Locale')),
				'options'=>$options_locales,


			)

		)
	),
);
$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);


$html=$smarty->fetch('edit_object.tpl');

?>
