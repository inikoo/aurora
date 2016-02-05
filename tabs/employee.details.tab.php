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


$options_Staff_Payment_Terms=array(
	'Monthy'=>_('Monthy (fixed)'), 'PerHour'=>_('Per hour (prorata)')
);

$options_Staff_Type=array(
	'Employee'=>_('Employee'), 'Volunteer'=>_('Volunteer'), 'TemporalWorker'=>_('Temporal Worker'), 'WorkExperience'=>_('Work Experience')
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

foreach (preg_split('/,/', $employee->get('Staff Position')) as $current_position_key) {
	if ( array_key_exists($current_position_key, $options_Staff_Position ) ) {

		$options_Staff_Position[$current_position_key]['selected']=true;
	}
}

$options_Staff_Supervisor=array();
$sql=sprintf('select `Staff Name`,`Staff Key`,`Staff Alias` from `Staff Dimension` where `Staff Currently Working`="Yes" ');
foreach ($db->query($sql) as $row) {
	$options_Staff_Supervisor[$row['Staff Key']]=array(
		'label'=>$row['Staff Alias'],

		'label2'=>$row['Staff Name'].' ('.sprintf('%03d', $row['Staff Key']).')',
		'selected'=>false
	);
}

foreach (preg_split('/,/', $employee->get('Staff Supervisor')) as $current_supervisor_key) {
	if ( array_key_exists($current_supervisor_key, $options_Staff_Supervisor ) ) {
		$options_Staff_Supervisor[$current_supervisor_key]['selected']=true;
	}
}


asort($options_Staff_Position);
asort($options_Staff_Supervisor);
asort($options_Staff_Payment_Terms);


asort($options_Staff_Type);
asort($options_yn);

$object_fields=array(

	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(


			array(

				'id'=>'Staff_Key',
				'value'=>$employee->get('Staff Key'),
				'label'=>ucfirst($employee->get_field_label('Staff Key')),
			),
			array(

				'id'=>'Staff_ID',
				'edit'=>'string',
				'value'=>$employee->get('Staff ID'),
				'label'=>ucfirst($employee->get_field_label('Staff ID')),
				'invalid_msg'=>get_invalid_message('smallint_unsigned'),
				'server_validation'=>'check_for_duplicates',
				'required'=>false
			),
			array(

				'id'=>'Staff_Alias',
				'edit'=>'string',
				'value'=>$employee->get('Staff Alias'),
				'label'=>ucfirst($employee->get_field_label('Staff Alias')),
				'server_validation'=>'check_for_duplicates',
				'invalid_msg'=>get_invalid_message('string'),
			),


		)
	),

	array(
		'label'=>_('Personal information'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(

			array(

				'id'=>'Staff_Name',
				'edit'=>'string',
				'value'=>$employee->get('Staff Name'),
				'label'=>ucfirst($employee->get_field_label('Staff Name')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true

			),
			array(

				'id'=>'Staff_Birthday',
				'edit'=>'date',
				'time'=>'00:00:00',
				'value'=>$employee->get('Staff Birthday'),
				'formatted_value'=>$employee->get('Birthday'),
				'label'=>ucfirst($employee->get_field_label('Staff Birthday')),
				'invalid_msg'=>get_invalid_message('date'),
				'required'=>false
			),
			array(

				'id'=>'Staff_Official_ID',
				'edit'=>'string',
				'value'=>$employee->get('Staff Official ID'),
				'label'=>ucfirst($employee->get_field_label('Staff Official ID')),
				'invalid_msg'=>get_invalid_message('string'),
				'server_validation'=>'check_for_duplicates',
				'required'=>false
			),
			array(

				'id'=>'Staff_Email',
				'edit'=>'email',
				'value'=>$employee->get('Staff Email'),
				'formatted_value'=>$employee->get('Email'),
				'label'=>ucfirst($employee->get_field_label('Staff Email')),
				'server_validation'=>'check_for_duplicates',
				'invalid_msg'=>get_invalid_message('email'),
			),
			array(

				'id'=>'Staff_Telephone',
				'edit'=>'telephone',
				'value'=>$employee->get('Staff Telephone'),
				'formatted_value'=>$employee->get('Telephone'),
				'label'=>ucfirst($employee->get_field_label('Staff Telephone')),
				'invalid_msg'=>get_invalid_message('telephone'),
			),
			array(

				'id'=>'Staff_Address',
				'edit'=>'textarea',
				'value'=>$employee->get('Staff Address'),
				'formatted_value'=>$employee->get('Address'),
				'label'=>ucfirst($employee->get_field_label('Staff Address')),
				'invalid_msg'=>get_invalid_message('string'),
			),
			array(

				'id'=>'Staff_Next_of_Kind',
				'edit'=>'string',
				'value'=>$employee->get('Staff Next of Kind'),
				'label'=>ucfirst($employee->get_field_label('Staff Next of Kind')),
				'invalid_msg'=>get_invalid_message('string'),

			),

		)
	),
	array(
		'label'=>_('Employment'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(
			array(

				'id'=>'Staff_Type',
				'edit'=>'option',
				'value'=>$employee->get('Staff Type'),
				'formatted_value'=>$employee->get('Type'),
				'options'=>$options_Staff_Type,
				'label'=>ucfirst($employee->get_field_label('Staff Type')),
			),

			array(

				'id'=>'Staff_Currently_Working',
				'edit'=>'option',
				
				'value'=>$employee->get('Staff Currently Working'),
				'formatted_value'=>$employee->get('Currently Working'),
				'options'=>$options_yn,
				'label'=>ucfirst($employee->get_field_label('Staff Currently Working')),
			),
			array(

				'id'=>'Staff_Valid_From',
				'edit'=>'date',
				'time'=>'09:00:00',
				'value'=>$employee->get('Staff Valid From'),
				'formatted_value'=>$employee->get('Valid From'),
				'label'=>ucfirst($employee->get_field_label('Staff Valid From')),
				'invalid_msg'=>get_invalid_message('date'),
			),
			array(
				'render'=>($employee->get('Staff Currently Working')=='Yes'?false:true),
				'id'=>'Staff_Valid_To',
				'edit'=>'date',
				'time'=>'17:00:00',
				'value'=>$employee->get('Staff Valid To'),
				'formatted_value'=>$employee->get('Valid To'),
				'label'=>ucfirst($employee->get_field_label('Staff Valid To')),
				'invalid_msg'=>get_invalid_message('date'),
			),

			array(

				'id'=>'Staff_Job_Title',
				'edit'=>'string',
				'value'=>$employee->get('Staff Job Title'),
				'label'=>ucfirst($employee->get_field_label('Staff Job Title')),
			),
			array(
				//   'render'=>($employee->get('Staff Currently Working')=='Yes'?true:false),
				'id'=>'Staff_Supervisor',
				'edit'=>'radio_option',
				'value'=>$employee->get('Staff Supervisor'),
				'formatted_value'=>$employee->get('Supervisor'),
				'options'=>$options_Staff_Supervisor,
				'label'=>ucfirst($employee->get_field_label('Staff Supervisor')),
				'required'=>false

			),

		)
	),

	array(
		'label'=>_('Working hours & salary'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(
			array(

				'id'=>'Staff_Working_Hours',
				'edit'=>'working_hours',
				'value'=>$employee->get('Staff Working Hours'),
				'formatted_value'=>$employee->get('Working Hours'),
				'options'=>$options_Staff_Type,
				'label'=>ucfirst($employee->get_field_label('Staff Working Hours')),
				'invalid_msg'=>get_invalid_message('working_hours'),
			),

			array(

				'id'=>'Staff_Salary',
				'edit'=>'salary',
				'value'=>$employee->get('Staff Salary'),
				'formatted_value'=>$employee->get('Salary'),
				'label'=>ucfirst($employee->get_field_label('Staff Salary')),
				'invalid_msg'=>get_invalid_message('salary'),
			)


		)
	),

	array(
		'label'=>_('System roles'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(

			array(
				'render'=>($employee->get('Staff Currently Working')=='Yes'?true:false),
				'id'=>'Staff_Position',
				'edit'=>'radio_option',
				'value'=>$employee->get('Staff Position'),
				'formatted_value'=>$employee->get('Position'),
				'options'=>$options_Staff_Position,
				'label'=>ucfirst($employee->get_field_label('Staff Position')),
			)

		)
	),

);

if ($employee->get('Staff User Key')) {


	$object_fields[]=array(
		'label'=>_('System user').' <i  onClick="change_view(\'account/user/'.$employee->get('Staff User Key').'\')" class="fa fa-link link"></i>',
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(

			array(

				'id'=>'Staff_User_Active',
				'edit'=>'option',
				'value'=>$employee->get('Staff User Active'),
				'formatted_value'=>$employee->get('User Active'),
				'options'=>$options_yn,
				'label'=>ucfirst($employee->get_field_label('Staff Active')),
			),
			array(

				'id'=>'Staff_User_Handle',
				'edit'=>'handle',
				'value'=>$employee->get('Staff User Handle'),
				'formatted_value'=>$employee->get('User Handle'),
				'label'=>ucfirst($employee->get_field_label('Staff User Handle')),
				'server_validation'=>'check_for_duplicates',
				'invalid_msg'=>get_invalid_message('handle'),
			),

			array(
				'render'=>($employee->get('Staff User Active')=='Yes'?true:false),

				'id'=>'Staff_User_Password',
				'edit'=>'password',
				'value'=>'',
				'formatted_value'=>'******',
				'label'=>ucfirst($employee->get_field_label('Staff User Password')),
				'invalid_msg'=>get_invalid_message('password'),
			),
			array(
				'render'=>($employee->get('Staff User Active')=='Yes'?true:false),

				'id'=>'Staff_User_PIN',
				'edit'=>'pin',
				'value'=>'',
				'formatted_value'=>'****',
				'label'=>ucfirst($employee->get_field_label('Staff User PIN')),
				'invalid_msg'=>get_invalid_message('pin'),
			),



		)
	);

}else {
	$object_fields[]=array(
		'label'=>_('System user'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(
			array(

				'id'=>'new_user',
				'class'=>'new',
				'value'=>'',
				'label'=>_('Set up system user').' <i class="fa fa-plus new_button link"></i>',
				'reference'=>'employee/'.$employee->id.'/new/user'
			),

		)
	);

}





$smarty->assign('working_hours', json_decode($employee->data['Staff Working Hours'], true));
$smarty->assign('salary', json_decode($employee->data['Staff Salary'], true));

$smarty->assign('day_labels', $day_labels=array(
		_('Weekdays'),
		_('Mon'),
		_('Tue'),
		_('Wed'),
		_('Thu'),
		_('Fri'),
		_('Weekend'),
		_('Sat'),
		_('Sun')
	));

$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);
$html=$smarty->fetch('edit_object.tpl');

?>
