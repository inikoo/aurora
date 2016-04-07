<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2016 at 12:10:26 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/



function get_object_fields($object, $db, $options=false) {

	switch ($object->get_object_name()) {

	case 'Supplier Part':

		$options_status=array('Available'=>_('Available'), 'NoAvailable'=>_('No stock'), 'Discontinued'=>_('Discontinued'));



		$object_fields=array(
			array(
				'label'=>_('Id'),
				'show_title'=>true,
				'fields'=>array(

					array(
						'id'=>'Supplier_Part_Reference',
						'edit'=>'string',
						'value'=>htmlspecialchars($object->get('Supplier Part Reference')),
						'formatted_value'=>$object->get('Reference'),
						'label'=>ucfirst($object->get_field_label('Supplier Part Reference')),
						'required'=>true,
						'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
					),




				)
			),

			array(
				'label'=>_('Ordering'),
				'show_title'=>true,
				'fields'=>array(
					array(
						'id'=>'Supplier_Part_Status',
						'edit'=>'option',
						'options'=>$options_status,
						'value'=>htmlspecialchars($object->get('Supplier Part Status')),
						'formatted_value'=>$object->get('Status'),
						'label'=>ucfirst($object->get_field_label('Supplier Part Status')),
						'required'=>true,
					),


					array(
						'id'=>'Supplier_Part_Batch',
						'edit'=>'string',
						'value'=>htmlspecialchars($object->get('Supplier Part Batch')),
						'formatted_value'=>$object->get('Batch'),
						'label'=>ucfirst($object->get_field_label('Supplier Part Batch')),
						'required'=>true,
					),

					array(
						'id'=>'Supplier_Part_Cost',
						'edit'=>'string',
						'value'=>htmlspecialchars($object->get('Supplier Part Cost')),
						'formatted_value'=>$object->get('Cost'),
						'label'=>ucfirst($object->get_field_label('Supplier Part Cost')),
						'required'=>true,
					),




				)
			),



		);

		return $object_fields;

		break;
	case 'Part':


		if (isset($options['show_full_label']) and  $options['show_full_label'] ) {
			$show_full_label=true;
			$field_prefix='Part ';
		}else {
			$show_full_label=false;
			$field_prefix='';
		}

		$options_Packing_Group=array(
			'None'=>_('None'), 'I'=>'I', 'II'=>'II', 'III'=>'III'
		);


		$object_fields=array(
			array(
				'label'=>($show_full_label?_('Part Id'):_('Id')),
				'show_title'=>true,
				'fields'=>array(

					array(
						'id'=>'Part_Reference',
						'edit'=>'string',
						'value'=>htmlspecialchars($object->get($field_prefix.'Part Reference')),
						'formatted_value'=>$object->get($field_prefix.'Reference'),
						'label'=>ucfirst($object->get_field_label('Part Reference')),
						'required'=>true,
						'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates', 'parent'=>'account', 'parent_key'=>1, 'object'=>'Part', 'key'=>$object->id)),
					),

array(
						'id'=>'Part_Tariff_Code',
						'edit'=>'numeric',
						'value'=>$object->get($field_prefix.'Part Tariff Code') ,
						'formatted_value'=>$object->get($field_prefix.'Tariff Code') ,
						'label'=>ucfirst($object->get_field_label('Part Tariff Code')),
						'invalid_msg'=>get_invalid_message('string'),
						'required'=>true,


					),
					array(
						'id'=>'Part_Duty_Rate',
						'edit'=>'numeric',
						'value'=>$object->get($field_prefix.'Part Duty Rate') ,
						'formatted_value'=>$object->get($field_prefix.'Duty Rate') ,
						'label'=>ucfirst($object->get_field_label('Part Duty Rate')),
						'invalid_msg'=>get_invalid_message('string'),
						'required'=>true,

					),


				)
			),
			array(
				'label'=>($show_full_label?_('Part stock keeping unit (Outer)'):_('Stock keeping unit (Outer)')),

				'show_title'=>true,
				'fields'=>array(
					array(
						'id'=>'Part_Package_Description',
						'edit'=>'string',
						'value'=>htmlspecialchars($object->get($field_prefix.'Part Package Description')),
						'formatted_value'=>$object->get($field_prefix.'Package Description'),
						'label'=>ucfirst($object->get_field_label('Package Unit Description')),
						'required'=>true,



					),

					array(
						'id'=>'Part_Package_Weight',
						'edit'=>'numeric',
						'value'=>$object->get($field_prefix.'Part Package Weight') ,
						'formatted_value'=>$object->get($field_prefix.'Package Weight') ,
						'label'=>ucfirst($object->get_field_label('Part Package Weight')),
						'invalid_msg'=>get_invalid_message('numeric'),
						'required'=>true,
					),
					array(
						'id'=>'Part_Package_Dimensions',
						'edit'=>'dimensions',
						'value'=>$object->get($field_prefix.'Part Package Dimensions') ,
						'formatted_value'=>$object->get($field_prefix.'Package Dimensions') ,
						'label'=>ucfirst($object->get_field_label('Part Package Dimensions')),
						'invalid_msg'=>get_invalid_message('string'),
						'required'=>true,
						'placeholder'=>_('L x W x H (in cm)')
					),


					
					


				)
			),
			array(
				'label'=>($show_full_label?_('Part commercial unit'):_('Commercial unit')),

				'show_title'=>true,
				'fields'=>array(
				array(
						'id'=>'Part_Units',
						'edit'=>'numeric',
						'value'=>$object->get($field_prefix.'Part Units') ,
						'formatted_value'=>$object->get($field_prefix.'Units') ,
						'label'=>ucfirst($object->get_field_label('Part Units')),
						'invalid_msg'=>get_invalid_message('string'),
						'required'=>true,

					),
					array(
						'id'=>'Part_Unit_Description',
						'edit'=>'string',
						'value'=>htmlspecialchars($object->get($field_prefix.'Part Unit Description')),
						'formatted_value'=>$object->get($field_prefix.'Unit Description'),
						'label'=>ucfirst($object->get_field_label('Part Unit Description')),
						'required'=>true,



					),

					array(
						'id'=>'Part_Unit_Weight',
						'edit'=>'numeric',
						'value'=>$object->get($field_prefix.'Part Unit Weight') ,
						'formatted_value'=>$object->get($field_prefix.'Unit Weight') ,
						'label'=>ucfirst($object->get_field_label('Part Unit Weight')),
						'invalid_msg'=>get_invalid_message('numeric'),
						'required'=>true,
					),
					array(
						'id'=>'Part_Unit_Dimensions',
						'edit'=>'dimensions',
						'value'=>$object->get($field_prefix.'Part Unit Dimensions') ,
						'formatted_value'=>$object->get($field_prefix.'Unit Dimensions') ,
						'label'=>ucfirst($object->get_field_label('Part Unit Dimensions')),
						'invalid_msg'=>get_invalid_message('string'),
						'required'=>true,
						'placeholder'=>_('L x W x H (in cm)')
					),


			


				)
			),
			array(
				'label'=>($show_full_label?_('Part health & safety'):_('Health & Safety')),

				'show_title'=>true,
				'fields'=>array(

					array(
						'id'=>'Part_UN_Number',
						'edit'=>'string',
						'value'=>htmlspecialchars($object->get($field_prefix.'Part UN Number')),
						'formatted_value'=>$object->get($field_prefix.'UN Number'),
						'label'=>ucfirst($object->get_field_label('Part UN Number')),
						'required'=>false
					),
					array(
						'id'=>'Part_UN_Class',
						'edit'=>'string',
						'value'=>htmlspecialchars($object->get($field_prefix.'Part UN Class')),
						'formatted_value'=>$object->get($field_prefix.'UN Class'),
						'label'=>ucfirst($object->get_field_label('Part UN Class')),
						'required'=>false
					),
					array(
						'id'=>'Part_Packing_Group',
						'edit'=>'option',
						'options'=>$options_Packing_Group,
						'value'=>htmlspecialchars($object->get($field_prefix.'Part Packing Group')),
						'formatted_value'=>$object->get($field_prefix.'Packing Group'),
						'label'=>ucfirst($object->get_field_label('Part Packing Group')),
						'required'=>false
					),
					array(
						'id'=>'Part_Proper_Shipping_Name',
						'edit'=>'string',
						'value'=>htmlspecialchars($object->get($field_prefix.'Part Proper Shipping Name')),
						'formatted_value'=>$object->get($field_prefix.'Proper Shipping Name'),
						'label'=>ucfirst($object->get_field_label('Part Proper Shipping Name')),
						'required'=>false
					),
					array(
						'id'=>'Part_Hazard_Indentification_Number',
						'edit'=>'string',
						'value'=>htmlspecialchars($object->get($field_prefix.'Part Hazard Indentification Number')),
						'formatted_value'=>$object->get($field_prefix.'Hazard Indentification Number'),
						'label'=>ucfirst($object->get_field_label('Part Hazard Indentification Number')),
						'required'=>false
					)
				)






			),

			array(
				'label'=>($show_full_label?_('Part components'):_('Components')),

				'show_title'=>true,
				'fields'=>array(

					array(
						'id'=>'Part_Materials',
						'edit'=>'textarea',
						'value'=>htmlspecialchars($object->get($field_prefix.'Part Materials')),
						'formatted_value'=>$object->get($field_prefix.'Materials'),
						'label'=>ucfirst($object->get_field_label('Part Materials')),
						'required'=>false
					),

					array(
						'id'=>'Part_Origin_Country_Code',
						'edit'=>'dropdown_select',
						'scope'=>'countries',
						'value'=>htmlspecialchars($object->get($field_prefix.'Part Origin Country Code')),
						'formatted_value'=>$object->get($field_prefix.'Origin Country Code'),
						'stripped_formatted_value'=>($object->get($field_prefix.'Part Origin Country Code')!=''?  $object->get($field_prefix.'Origin Country').' ('.$object->get($field_prefix.'Part Origin Country Code').')':''),
						'label'=>ucfirst($object->get_field_label('Part Origin Country Code')),
						'required'=>false
					),

				)





			) ,
			array(
				'label'=>_('Operations'),
				'show_title'=>true,
				'class'=>'edit_fields',
				'fields'=>array(
					array(

						'id'=>'delete_part',
						'class'=>'new',
						'value'=>'',
						'label'=>'<i class="fa fa-lock button" style="margin-right:20px"></i> <span class="disabled">'._('Delete part').' <i class="fa fa-trash new_button link"></i></span>',
						'reference'=>''
					),

				)

			),



		);

		return $object_fields;

		break;
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
