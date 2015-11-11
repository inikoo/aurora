<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 4 November 2015 at 22:57:18 CET Tessera Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';

$employee=$state['_object'];
$employee->get_user_data();

$options_Staff_Type=array(
	'Employee'=>_('Employee'), 'Volunteer'=>_('Volunteer'), 'Contractor'=>_('Contractor'), 'TemporalWorker'=>_('Temporal Worker'), 'WorkExperience'=>_('Work Experience')
);

$options_yn=array(
	'Yes'=>_('Yes'), 'No'=>_('No')
);

$options_Staff_Position=array();
$sql=sprintf('select `Company Position Key`,`Company Position Code`,`Company Position Title` from `Company Position Dimension`  ');
foreach ($db->query($sql) as $row) {
	$options_Staff_Position[$row['Company Position Key']]=array(
	'label'=>$row['Company Position Title'],
	'selected'=>false
	);
}

foreach(preg_split('/,/',$employee->get('Staff Position')) as $current_position_key){
$options_Staff_Position[$current_position_key]['selected']=true;
}


asort($options_Staff_Position);

asort($options_Staff_Type);
asort($options_yn);

$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(


			array(

				'id'=>'Staff_Key',
				'value'=>$employee->get('Staff Key'),
				'label'=>_('Id')
			),
			array(

				'id'=>'Staff_ID',
				'edit'=>'smallint_unsigned',
				'value'=>$employee->get('Staff ID'),
				'label'=>_('Payroll Id'),
				'invalid_msg'=>get_invalid_message('smallint_unsigned'),
				'server_validation'=>'check_for_duplicates',
				'required'=>false
			),
			array(

				'id'=>'Staff_Alias',
				'edit'=>'string',
				'value'=>$employee->get('Staff Alias'),
				'label'=>_('Code'),
				'server_validation'=>'check_for_duplicates',
				'invalid_msg'=>get_invalid_message('string'),
			),
			array(

				'id'=>'Staff_Name',
				'edit'=>'string',
				'value'=>$employee->get('Staff Name'),
				'label'=>_('Name'),
				'invalid_msg'=>get_invalid_message('string'),
				
			),

		)
	),
	array(
		'label'=>_('Employment'),
		'show_title'=>true,
		'fields'=>array(
			array(

				'id'=>'Staff_Type',
				'edit'=>'option',
				'value'=>$employee->get('Staff Type'),
				'formated_value'=>$employee->get('Type'),
				'options'=>$options_Staff_Type,
				'label'=>_('Type')
			),

			array(

				'id'=>'Staff_Currently_Working',
				'edit'=>'option',
				'value'=>$employee->get('Staff Currently Working'),
				'formated_value'=>$employee->get('Currently Working'),
				'options'=>$options_yn,
				'label'=>_('Currently working')
			),
			array(

				'id'=>'Staff_Valid_From',
				'edit'=>'date',
				'time'=>'09:00:00',
				'value'=>$employee->get('Staff Valid From'),
				'formated_value'=>$employee->get('Valid From'),
				'label'=>_('Working from'),
				'invalid_msg'=>get_invalid_message('date'),
			),
			array(
				'render'=>($employee->get('Staff Currently Working')=='Yes'?false:true),
				'id'=>'Staff_Valid_To',
				'edit'=>'date',
				'time'=>'17:00:00',
				'value'=>$employee->get('Staff Valid To'),
				'formated_value'=>$employee->get('Valid To'),
				'label'=>_('End of employement'),
				'invalid_msg'=>get_invalid_message('date'),
			),
			array(
				'render'=>($employee->get('Staff Currently Working')=='Yes'?true:false),
				'id'=>'Staff_Position',
				'edit'=>'radio_option',
				'value'=>$employee->get('Staff Position'),
				'formated_value'=>$employee->get('Position'),
				'options'=>$options_Staff_Position,
				'label'=>_('Position')
			),

		)
	),

);


$object_fields[]=array(
	'label'=>_('System user'),
	'show_title'=>true,
	'fields'=>array(

		array(

			'id'=>'Staff_User_Active',
			'edit'=>'option',
			'value'=>$employee->get('Staff User Active'),
			'formated_value'=>$employee->get('User Active'),
			'options'=>$options_yn,
			'label'=>_('Enabled')
		),
		array(

			'id'=>'Staff_User_Handle',
			'edit'=>'string',
			'value'=>$employee->get('Staff User Handle'),
			'formated_value'=>$employee->get('User Handle'),
			'label'=>_('Login'),
			'server_validation'=>'check_for_duplicates'
		),

		array(

			'id'=>'Staff_User_Password',
			'edit'=>'password',
			'value'=>'********',
			'label'=>_('Password'),
		),
		array(

			'id'=>'Staff_User_PIN',
			'edit'=>'password',
			'value'=>'****',
			'label'=>_('PIN'),
		),



	)
);

$smarty->assign('state', $state);

$smarty->assign('object_fields', $object_fields);

$html=$smarty->fetch('object_fields.tpl');

?>
