<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2016 at 12:10:26 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/



function get_object_fields($object, $db) {

	switch ($object->get_object_name()) {

	case 'Warehouse':


		$object_fields=array(
			array(
				'label'=>_('Id'),
				'show_title'=>true,
				'fields'=>array(

					array(
						'edit'=>'string',
						'id'=>'Warehouse_Code',
						'value'=>$object->get('Warehouse Code'),
						'formatted_value'=>$object->get('Code'),
						'label'=>ucfirst($object->get_field_label('Warehouse Code')),
						'invalid_msg'=>get_invalid_message('string'),
						'required'=>true,
						'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
						'type'=>'value'
					),
					array(
						'edit'=>'string',
						'id'=>'Warehouse_Name',
						'value'=>$object->get('Warehouse Name')  ,
						'formatted_value'=>$object->get('Name'),
						'label'=>ucfirst($object->get_field_label('Warehouse Name')),
						'invalid_msg'=>get_invalid_message('string'),
						'required'=>true,
						'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
						'type'=>'value'
					),

				)
			),
			
			array(
				'label'=>_('Address'),
				'show_title'=>true,
				'fields'=>array(

				
					array(
						'edit'=>'textarea',
						'id'=>'Warehouse_Address',
						'value'=>$object->get('Warehouse Address'),
						'formatted_value'=>$object->get('Address'),
						'label'=>ucfirst($object->get_field_label('Warehouse Address')),
						'invalid_msg'=>get_invalid_message('string'),
						'required'=>true,
						'type'=>'value'
					),
				)
			),


		);

		return $object_fields;

		break;
	case 'Store':

		$store=$object;
		$options_locale=array(
			'en_GB'=>'en_GB '._('British English'),
			'de_DE'=>'de_DE '._('German'),
			'fr_FR'=>'fr_FR '._('French'),
			'es_ES'=>'es_ES '._('Spanish'),
			'pl_PL'=>'pl_PL '._('Polish'),
			'it_IT'=>'it_IT '._('Italian'),
			'sk_SK'=>'sk_SK '._('Sloavak'),
			'pt_PT'=>'pt_PT '._('Portuguese'),
		);
		asort($options_locale);


		$options_timezones=array();
		foreach ( DateTimeZone::listIdentifiers() as $timezone) {
			$options_timezones[preg_replace('/\//', '_', $timezone)]=$timezone;
		}

		$options_currencies=array();
		$sql=sprintf("select `Currency Code`,`Currency Name`,`Currency Symbol`,`Currency Flag` from kbase.`Currency Dimension` ");
		if ($result=$db->query($sql)) {
			foreach ($result as $row) {
				$options_currencies[$row['Currency Code']]=_($row['Currency Name']).' '.$row['Currency Symbol'];
			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}

		asort($options_currencies);


		$object_fields=array(
			array(
				'label'=>_('Id'),
				'show_title'=>true,
				'fields'=>array(

					array(
						'edit'=>'string',
						'id'=>'Store_Code',
						'value'=>$store->get('Store Code')  ,
						'label'=>ucfirst($store->get_field_label('Store Code')),
						'invalid_msg'=>get_invalid_message('string'),
						'required'=>true,
						'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
						'type'=>'value'



					),
					array(
						'edit'=>'string',
						'id'=>'Store_Name',
						'value'=>$store->get('Store Name'),
						'label'=>ucfirst($store->get_field_label('Store Name')),
						'invalid_msg'=>get_invalid_message('string'),
						'required'=>true,
						'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),

						'type'=>'value'
					),


				)
			),
			array(
				'label'=>_('Localization'),
				'show_title'=>true,
				'fields'=>array(
					array(
						'id'=>'Store_Locale',
						'edit'=>'option',
						'options'=>$options_locale,
						'value'=>$store->get('Store Locale'),
						'formatted_value'=>$store->get('Locale'),
						'label'=>ucfirst($store->get_field_label('Store Locale')),
						'type'=>'value'
					),
					array(
						'id'=>'Store_Currency',
						'edit'=>'option',
						'options'=>$options_currencies,
						'value'=>$store->get('Store Currency') ,
						'formatted_value'=>$store->get(' urrency') ,
						'label'=>ucfirst($store->get_field_label('Store Currency')),
						'type'=>'value'
					),
					array(
						'id'=>'Store_Timezone',
						'edit'=>'option',
						'options'=>$options_timezones,
						'value'=>$store->get('Store Timezone') ,
						'formatted_value'=>$store->get('Timezone'),
						'label'=>ucfirst($store->get_field_label('Store Timezone')),
						'type'=>'value'
					)

				)
			),
			array(
				'label'=>_('Contact'),
				'show_title'=>true,
				'fields'=>array(

					array(
						'edit'=>'email',
						'id'=>'Store_Email',
						'value'=>$store->get('Store Email')  ,
						'label'=>ucfirst($store->get_field_label('Store Email')),
						'invalid_msg'=>get_invalid_message('email'),
						'required'=>false,

						'type'=>'value'


					),
					array(
						'edit'=>'telephone',
						'id'=>'Store_Telephone',
						'value'=>$store->get('Store Telephone'),
						'formatted_value'=>$store->get('Telephone'),
						'label'=>ucfirst($store->get_field_label('Store Telephone')),
						'invalid_msg'=>get_invalid_message('telephone'),
						'required'=>false,
						'type'=>'value'
					),

					array(
						'edit'=>'textarea',
						'id'=>'Store_Address',
						'value'=>$store->get('Store Address'),
						'formatted_value'=>$store->get('Address'),
						'label'=>ucfirst($store->get_field_label('Store Address')),
						'invalid_msg'=>get_invalid_message('string'),
						'required'=>false,
						'type'=>'value'
					),
					array(
						'edit'=>'string',
						'id'=>'Store_URL',
						'value'=>$store->get('Store URL'),
						'formatted_value'=>$store->get('Store URL'),
						'label'=>ucfirst($store->get_field_label('Store URL')),
						'invalid_msg'=>get_invalid_message('string'),
						'required'=>false,
						'type'=>'value'
					),

				)
			)

		);
		return $object_fields;
		break;
	case 'Staff':
		$employee=$object;
		$account=new Account();
		$options_Staff_Type=array(
			'Employee'=>_('Employee'), 'Volunteer'=>_('Volunteer'), 'TemporalWorker'=>_('Temporal Worker'), 'WorkExperience'=>_('Work Experience')
		);
		$options_yn=array(
			'Yes'=>_('Yes'), 'No'=>_('No')
		);
		include 'conf/roles.php';
		foreach ($roles as $_key=>$_data) {
			if (in_array($account->get('Setup Metadata')['size'], $_data['size'])) {

				foreach ($account->get('Setup Metadata')['instances'] as $instance) {
					if (in_array($instance, $_data['instances'])) {

						$options_Staff_Position[$_key]=array(
							'label'=>$_data['title'],
							'selected'=>false
						);
						break;
					}
				}
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
		asort($options_Staff_Position);
		asort($options_Staff_Supervisor);
		asort($options_Staff_Type);
		asort($options_yn);

		$object_fields=array(
			array(
				'label'=>_('Id'),
				'show_title'=>true,
				'class'=>'edit_fields',
				'fields'=>array(



					array(

						'id'=>'Staff_ID',
						'edit'=>'string',
						'value'=>'',
						'label'=>ucfirst($employee->get_field_label('Staff ID')),
						'invalid_msg'=>get_invalid_message('smallint_unsigned'),
						'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
						'required'=>false,
						'type'=>'value'
					),
					array(

						'id'=>'Staff_Alias',
						'edit'=>'string',
						'value'=>$employee->get('Staff Alias'),
						'label'=>ucfirst($employee->get_field_label('Staff Alias')),
						'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
						'invalid_msg'=>get_invalid_message('string'),
						'type'=>'value'
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
						'required'=>true,
						'type'=>'value'

					),
					array(

						'id'=>'Staff_Birthday',
						'edit'=>'date',
						'time'=>'00:00:00',
						'value'=>$employee->get('Staff Birthday'),
						'formatted_value'=>$employee->get('Birthday'),
						'label'=>ucfirst($employee->get_field_label('Staff Birthday')),
						'invalid_msg'=>get_invalid_message('date'),
						'required'=>false,
						'type'=>'value'
					),
					array(

						'id'=>'Staff_Official_ID',
						'edit'=>'string',
						'value'=>$employee->get('Staff Official ID'),
						'label'=>ucfirst($employee->get_field_label('Staff Official ID')),
						'invalid_msg'=>get_invalid_message('string'),
						'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
						'required'=>false,
						'type'=>'value'
					),
					array(

						'id'=>'Staff_Email',
						'edit'=>'email',
						'value'=>$employee->get('Staff Email'),
						'formatted_value'=>$employee->get('Email'),
						'label'=>ucfirst($employee->get_field_label('Staff Email')),
						'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
						'invalid_msg'=>get_invalid_message('email'),
						'required'=>false,
						'type'=>'value'
					),
					array(

						'id'=>'Staff_Telephone',
						'edit'=>'telephone',
						'value'=>$employee->get('Staff Telephone'),
						'formatted_value'=>$employee->get('Telephone'),
						'label'=>ucfirst($employee->get_field_label('Staff Telephone')),
						'invalid_msg'=>get_invalid_message('telephone'),
						'required'=>false,
						'type'=>'value'
					),
					array(

						'id'=>'Staff_Address',
						'edit'=>'textarea',
						'value'=>$employee->get('Staff Address'),
						'formatted_value'=>$employee->get('Staff Address'),
						'label'=>ucfirst($employee->get_field_label('Staff Address')),
						'invalid_msg'=>get_invalid_message('string'),
						'required'=>false,
						'type'=>'value'
					),
					array(

						'id'=>'Staff_Next_of_Kind',
						'edit'=>'string',
						'value'=>$employee->get('Staff Next of Kind'),
						'label'=>ucfirst($employee->get_field_label('Staff Next of Kind')),
						'invalid_msg'=>get_invalid_message('string'),
						'required'=>false,
						'type'=>'value'

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
						'value'=>'Employee',
						'formatted_value'=>_('Employee'),
						'options'=>$options_Staff_Type,
						'label'=>ucfirst($employee->get_field_label('Staff Type')),
						'type'=>'value',
						'required'=>false,
					),

					array(
						'render'=>false,
						'edit'=>'option',
						'id'=>'Staff_Currently_Working',
						'value'=>'Yes',
						'formatted_value'=>_('Yes'),
						'options'=>$options_yn,
						'label'=>ucfirst($employee->get_field_label('Staff Currently Working')),
						'type'=>'value',
						'required'=>false,
					),
					array(
						'render'=>false,
						'edit'=>'hidden',
						'id'=>'Staff_Valid_From',

						'time'=>'09:00:00',
						'value'=>$employee->get('Staff Valid From'),
						'formatted_value'=>$employee->get('Valid From'),
						'label'=>ucfirst($employee->get_field_label('Staff Valid From')),
						'invalid_msg'=>get_invalid_message('date'),
						'type'=>'value',
						'required'=>false,
					),
					array(
						'render'=>false,
						'edit'=>'hidden',
						'id'=>'Staff_Valid_To',

						'time'=>'09:00:00',
						'value'=>$employee->get('Staff Valid To'),
						'formatted_value'=>$employee->get('Valid To'),
						'label'=>ucfirst($employee->get_field_label('Staff Valid To')),
						'invalid_msg'=>get_invalid_message('date'),
						'type'=>'value',
						'required'=>false,
					),

					array(

						'id'=>'Staff_Job_Title',
						'edit'=>'string',
						'value'=>$employee->get('Staff Job Title'),
						'label'=>ucfirst($employee->get_field_label('Staff Job Title')),
						'required'=>false,
						'type'=>'value'
					),
					array(
						//   'render'=>($employee->get('Staff Currently Working')=='Yes'?true:false),
						'id'=>'Staff_Supervisor',
						'edit'=>'radio_option',
						'value'=>$employee->get('Staff Supervisor'),
						'formatted_value'=>$employee->get('Supervisor'),
						'options'=>$options_Staff_Supervisor,
						'label'=>ucfirst($employee->get_field_label('Staff Supervisor')),
						'required'=>false,
						'type'=>'value'

					),

				)
			),
			array(
				'label'=>_('System user'),
				'show_title'=>true,
				'class'=>'edit_fields',
				'fields'=>array(


					array(

						'id'=>'add_new_user',
						'class'=>'',
						'value'=>'',
						'label'=>_('Set up system user').' <i onClick="show_user_fields()" class="fa fa-plus new_button link"></i>',
						'required'=>false,
						'type'=>'util'
					),

					array(
						'render'=>false,
						'id'=>'dont_add_new_user',
						'class'=>'',
						'value'=>'',
						'label'=>_("Don't set up system user").' <i onClick="hide_user_fields()" class="fa fa-minus new_button link"></i>',
						'required'=>false,
						'type'=>'util'
					),


					array(
						'render'=>false,
						'id'=>'Staff_User_Active',
						'edit'=>'option',
						'options'=>$options_yn,
						'value'=>'Yes',
						'formatted_value'=>_('Yes'),
						'label'=>ucfirst($employee->get_field_label('Staff User Active')),
						'type'=>'user_value',
						'hidden'=>true
					),
					array(
						'render'=>false,
						'id'=>'Staff_User_Handle',
						'edit'=>'handle',
						'value'=>$employee->get('Staff User Handle'),
						'formatted_value'=>$employee->get('User Handle'),
						'label'=>ucfirst($employee->get_field_label('Staff User Handle')),
						'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
						'invalid_msg'=>get_invalid_message('handle'),
						'type'=>'user_value',
						'required'=>false,

					),
					array(
						'render'=>false,
						'id'=>'Staff_Position',
						'edit'=>'radio_option',
						'value'=>'',
						'formatted_value'=>'',
						'options'=>$options_Staff_Position,
						'label'=>ucfirst($employee->get_field_label('Staff Position')),
						'required'=>false,
						'type'=>'user_value'
					),


					array(
						'render'=>false,

						'id'=>'Staff_User_Password',
						'edit'=>'password',
						'value'=>'',
						'formatted_value'=>'******',
						'label'=>ucfirst($employee->get_field_label('Staff User Password')),
						'invalid_msg'=>get_invalid_message('password'),
						'type'=>'user_value',
						'required'=>false,


					),
					array(
						'render'=>false,
						'id'=>'Staff_PIN',
						'edit'=>'pin',
						'value'=>'',
						'formatted_value'=>'****',
						'label'=>ucfirst($employee->get_field_label('Staff PIN')),
						'invalid_msg'=>get_invalid_message('pin'),
						'type'=>'user_value',
						'required'=>false,

					),



				)
			)


		);

		return $object_fields;

		break;


	default:
		return '';
		break;
	}

}



?>
