<?php


require_once 'class.Timer.php';

require_once 'common.php';
require_once 'class.Company.php';
require_once 'ar_edit_common.php';
include_once 'class.CustomField.php';
require_once 'class.SendEmail.php';
require_once 'common_detect_agent.php';
require_once 'class.Supplier.php';



if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}

$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case('check_tax_number'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key')
		));
	check_customer_tax_number($data);
	break;


case('update_tax_number_match'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key'),
			'value'=>array('type'=>'value'),
		));
	update_tax_number_match($data);
	break;

case('delete_all_customers_in_list'):

	$data=prepare_values($_REQUEST,array(
			'list_key'=>array('type'=>'key'),
			'store_key'=>array('type'=>'key'),
		));

	delete_all_customers_in_list($data);
	break;
case('delete_all_customers_in_store'):

	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
		));

	delete_all_customers_in_store($data);
	break;

case('new_list'):
	if (!$user->can_view('customers'))
		exit();

	$data=prepare_values($_REQUEST,array(
			'awhere'=>array('type'=>'json array'),
			'store_id'=>array('type'=>'key'),
			'list_name'=>array('type'=>'string'),
			'list_type'=>array('type'=>'enum',
				'valid values regex'=>'/static|Dynamic/i'
			)
		));


	new_customers_list($data);
	break;



case('forgot_password'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key'),
			'store_key'=>array('type'=>'key'),
			'email'=>array('type'=>'string'),
			'url'=>array('type'=>'string'),
			'site_key'=>array('type'=>'key')

		));
	forgot_password($data);


	break;
case('customer_merge'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key'),
			'merge_key'=>array('type'=>'key')

		));
	customer_merge($data);
	break;
case('delete_customer'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key'),


		));
	delete_customer($data);
	break;

case('delete_customer_list'):
		$data=prepare_values($_REQUEST,array(
			'subject_key'=>array('type'=>'key'),
			'table_id'=>array('type'=>'numeric','optional'=>true),
			'recordIndex'=>array('type'=>'numeric','optional'=>true)
		));
	delete_customer_list($data);
	break;
case('delete_post_to_send'):
	$data=prepare_values($_REQUEST,array(
			'key'=>array('type'=>'key'),


		));
	delete_post_to_send($data);
	break;
case('edit_post_to_send'):
	$data=prepare_values($_REQUEST,array(
			'key'=>array('type'=>'key'),


		));
	edit_post_to_send($data);
	break;
case('convert_customer_to_company'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key'),
			'company_name'=>array('type'=>'string'),

		));
	convert_customer_to_company($data);
	break;
case('convert_customer_to_person'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key')
		));
	convert_customer_to_person($data);
	break;


case('set_main_address'):
	$data=prepare_values($_REQUEST,array(
			'value'=>array('type'=>'key'),
			'key'=>array('type'=>'string'),
			'subject'=>array('type'=>'string'),
			'subject_key'=>array('type'=>'key'),



		));
	update_main_address($data);
	break;
case('new_company'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array')

		));
	new_company($data);

	break;

case('clone_customer'):

	$data=prepare_values($_REQUEST,array(
			'scope'=>array('type'=>'json array'),
			'customer_key'=>array('type'=>'key')

		));
	clone_customer($data);


	break;

case('new_customer'):

	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array')

		));
	new_customer($data);


	break;

case('create_contact'):

case('new_contact'):
	$data=prepare_values($_REQUEST,array(
			'value'=>array('type'=>'json array'),
			'subject'=>array('type'=>'string'),
			'subject_key'=>array('type'=>'key')
		));
	new_contact($data);

	break;
case('new_address'):
	$data=prepare_values($_REQUEST,array(
			'value'=>array('type'=>'json array'),
			'subject'=>array('type'=>'string'),
			'subject_key'=>array('type'=>'key'),

		));
	$data['address_type']='Delivery';
	new_address($data);
	break;
case('new_Delivery_address'):
case('new_delivery_address'):

	$data=prepare_values($_REQUEST,array(
			'value'=>array('type'=>'json array'),
			'subject'=>array('type'=>'string'),
			'subject_key'=>array('type'=>'key')

		));


	$data['address_type']='Delivery';
	new_address($data);
	break;

case('new_Billing_address'):
	$data=prepare_values($_REQUEST,array(
			'value'=>array('type'=>'json array'),
			'subject'=>array('type'=>'string'),
			'subject_key'=>array('type'=>'key')


		));
	$data['address_type']='Billing';
	new_address($data);
	break;

case('edit_address_type'):
	edit_address_type();
	break;
case('edit_address'):
	$data=prepare_values($_REQUEST,array(
			'value'=>array('type'=>'json array'),
			'subject'=>array('type'=>'enum',
				'valid values regex'=>'/company|contact|supplier|customer/i'
			),
			'subject_key'=>array('type'=>'key'),
			'id'=>array('type'=>'key')
		));

	edit_address($data);
	break;
case('edit_delivery_address'):
	edit_delivery_address();
	break;
case('edit_company'):
	edit_company();
	break;
case('edit_customer_field'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key'),
			'newvalue'=>array('type'=>'string'),

			'key'=>array('type'=>'string')

		));

	$response=edit_customer_field($data['customer_key'],$data['key'],array('value'=>$data['newvalue'],'okey'=>$data['key']));
	echo json_encode($response);
	break;

case('edit_billing_quick'):
case('edit_billing_data'):


	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')

		));

	edit_customer($data);
	break;



	break;
case('edit_customer_quick'):
case('edit_customer'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')
		));

	edit_customer($data);
	break;
case 'site_edit_customer':
	//xxc
	break;
case('add_customer_send_post'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key'),
			'post_type'=>array('type'=>'string')

		));
	add_customer_send_post($data);
	break;
case('cancel_customer_send_post'):
	$data=prepare_values($_REQUEST,array(
			'send_post_key'=>array('type'=>'key'),

		));
	cancel_customer_send_post($data);
	break;
case('send_customer_send_post'):
	$data=prepare_values($_REQUEST,array(
			'send_post_key'=>array('type'=>'key'),

		));
	send_customer_send_post($data);
	break;

case('edit_customers'):
	list_customers();
	break;
case('create_company_area'):
	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')

		));


	new_company_area($data);
	break;
case('create_company_department'):
	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')

		));

	new_company_department($data);
	break;
case('create_company_position'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array')
			,'parent_key'=>array('type'=>'key')
		));
	new_company_position($data);
	break;
case('new_employee'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array')

		));
	new_employee($data);

	break;

case('edit_company_department'):
	edit_company_department();
	break;
case('edit_contact'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key')
			,'value'=>array('type'=>'json array')
			,'subject_key'=>array('type'=>'key')
		));
	edit_contact($data);
	break;
case('edit_email'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),
			'value'=>array('type'=>'json array','required elements'=>array(
					'Email'=>'string',
					'Email Key'=>'numeric'
				)),
			'subject_key'=>array('type'=>'key'),
			'subject'=>array('type'=>'enum',
				'valid values regex'=>'/company|contact/i'
			)
		));

	edit_email($data);
	break;
case('delete_company_area'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key')
			,'delete_type'=>array('type'=>'string')
		));
	delete_company_area($data);
	break;
case('delete_company_department'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key')
			,'delete_type'=>array('type'=>'string')
		));
	delete_company_department($data);
	break;
case ('edit_corporation'):
	$data=prepare_values($_REQUEST,array(
			'key'=>array('type'=>'string'),
			'newvalue'=>array('type'=>'string')
		));
	edit_corporation($data);
	break;

case('delete_contact'):
	$data=prepare_values($_REQUEST,array(
			'contact_key'=>array('type'=>'key')
		));
	delete_contact($data);
	break;
case('remove_address'):
case('delete_address'):
	$data=prepare_values($_REQUEST,array(
			'address_key'=>array('type'=>'key'),
			'type'=>array('type'=>'string'),
			'subject_key'=>array('type'=>'key'),
			'subject'=>array('type'=>'string')
		));


	delete_address($data);
	break;
case('remove_email'):
case('delete_email'):
	delete_email();
	break;
case('delete_mobile'):
	delete_mobile();
	break;
case('edit_telecom'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),
			'value'=>array(
				'type'=>'json array',
				'required elements'=>array(
					'Telecom'=>'string',
					'Telecom Key'=>'numeric',
					'Telecom Type'=>'string',

					'Telecom Is Main'=>'string',
				)),
			'subject_key'=>array('type'=>'key'),
			'subject'=>array('type'=>'enum',
				'valid values regex'=>'/company|contact/i'
			)
		));
	edit_telecom($data);
	break;

case('edit_mobile'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),
			'value'=>array(
				'type'=>'json array',
				'required elements'=>array(
					'Telecom'=>'string',
					'Telecom Key'=>'numeric',
					'Telecom Type'=>'string',
					'Telecom Is Main'=>'string',
				)),
			'subject_key'=>array('type'=>'key'),
			'subject'=>array('type'=>'enum',
				'valid values regex'=>'/company|contact/i'
			)
		));
	edit_mobile($data);
	break;
case('add_mobile'):
	$data=prepare_values($_REQUEST,array(
			'value'=>array(
				'type'=>'json array',
				'required elements'=>array(
					'Telecom'=>'string',
					'Telecom Key'=>'numeric',
					'Telecom Type'=>'string',
					'Telecom Is Main'=>'string',
				)),
			'subject_key'=>array('type'=>'key'),
			'subject'=>array('type'=>'enum',
				'valid values regex'=>'/company|contact/i'
			)
		));
	add_mobile($data);
	break;
case('new_corporation'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array')

		));
	new_corporation($data);

	break;
case('edit_company_areas'):
	list_company_areas();
	break;


case('edit_company_area'):
	$data=prepare_values($_REQUEST,array('id'=>array('type'=>'key'),'newvalue' =>array('type'=>'string'),'key' =>array('type'=>'string_value')));
	edit_company_area($data);
	break;


case('create_custom_field'):

	$data=prepare_values($_REQUEST,array('values'=>array('type'=>'json array'),
			'parent' =>array('type'=>'string'),
			'parent_key' =>array('type'=>'key')));
	create_custom_field($data);
	break;

case('create_email_field'):

	$data=prepare_values($_REQUEST,array('values'=>array('type'=>'json array'),
			'parent' =>array('type'=>'string'),
			'parent_key' =>array('type'=>'key')));
	create_email_field($data);
	break;
	//break;
default:

	$response=array('state'=>404,'resp'=>'Operation not found '.$tipo);
	echo json_encode($response);
}

function edit_contact($data) {
	global $editor;


	$contact=new Contact($data['id']);

	if (!$contact->id) {
		$response=array('state'=>400,'msg'=>_('Contact not found'));
	}

	$translator=array(
		'Contact_Name_Components'=>'Contact Name Components'
		,'Contact_Gender'=>'Contact Gender'
		,'Contact_Title'=>'Contact Title'
		,'Contact_Profession'=>'Contact Profession'
	);
	$components_translator=array(
		'Contact_First_Name'=>'Contact First Name'
		,'Contact_Surname'=>'Contact Surname'
		,'Contact_Suffix'=>'Contact Suffix'
		,'Contact_Salutation'=>'Contact Salutation'

	);


	foreach ($data['value'] as $key=>$value) {
		if (array_key_exists($key, $translator)) {

			if ($key=='Contact_Name_Components') {
				$components=array();
				foreach ($value as $component_key => $component_value) {
					if (array_key_exists($component_key, $components_translator))
						$components[$components_translator[$component_key]]=$component_value;

				}
				$contact_data[$translator[$key]]=$components;

			} else
				$contact_data[$translator[$key]]=$value;

		}

	}

	$contact->editor=$editor;


	// print_r($contact_data);
	// return;
	$contact->update($contact_data);

	$contact->reread();
	if ($contact->error_updated) {
		$response=array('state'=>200,'action'=>'error','msg'=>$contact->msg_updated);
	} else {

		if ($contact->updated) {
			$contact->reread();
			$updated_data_name_components=array(
				'Contact_First_Name'=>$contact->data['Contact First Name']
				,'Contact_Surname'=>$contact->data['Contact Surname']
				,'Contact_Suffix'=>$contact->data['Contact Suffix']
				,'Contact_Salutation'=>$contact->data['Contact Salutation']

			);

			$updated_data=array(
				'Contact_Name'=>$contact->data['Contact Name']
				,'Name_Data'=>$updated_data_name_components
				,'Contact_Gender'=>$contact->data['Contact Gender']
				,'Contact_Title'=>$contact->data['Contact Title']
				,'Contact_Profession'=>$contact->data['Contact Profession']
			);



			$response=array('state'=>200,'action'=>'updated','msg'=>$contact->msg_updated,'xhtml_subject'=>$contact->display('card'),'updated_data'=>$updated_data);
		} else {
			$response=array('state'=>200,'action'=>'nochange','msg'=>$contact->msg_updated);

		}

	}

	echo json_encode($response);

}
function edit_company() {
	global $editor;
	if (!isset($_REQUEST['key']) ) {
		$response=array('state'=>400,'msg'=>'Error no key');
		echo json_encode($response);
		return;
	}
	if ( !isset($_REQUEST['newvalue']) ) {
		$response=array('state'=>400,'msg'=>'Error no value');
		echo json_encode($response);
		return;
	}
	if ( !isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])  ) {
		$company_key=$_SESSION['state']['company']['id'];
	} else
		$company_key=$_REQUEST['id'];

	$company=new Company($company_key);
	$company->editor=$editor;
	if (!$company->id) {
		$response=array('state'=>400,'msg'=>_('Company not found'));
		echo json_encode($response);
		return;
	}

	$translator=array(
		'name'=>'Company Name',
		'fiscal_name'=>'Company Fiscal Name',
		'tax_number'=>'Company Tax Number',
		'registration_number'=>'Company Registration Number'


	);

	if (array_key_exists($_REQUEST['key'], $translator)) {

		$update_data=array(

			$translator[$_REQUEST['key']]=>stripslashes(urldecode($_REQUEST['newvalue']))
		);
		//print_r($update_data);
		$company->update($update_data);

		if ($company->error_updated) {
			$response=array('state'=>200,'action'=>'error','msg'=>$company->msg_updated,'key'=>$_REQUEST['key']);
		} else {

			if ($company->updated) {
				$response=array('state'=>200,'action'=>'updated','msg'=>$company->msg_updated,'key'=>$_REQUEST['key'],'newvalue'=>$company->new_value);
			} else {
				$response=array('state'=>200,'action'=>'nochange','msg'=>$company->msg_updated,'key'=>$_REQUEST['key']);

			}

		}


	} else {
		$response=array('state'=>400,'msg'=>_('Key not in Company'));
	}
	echo json_encode($response);

}
function edit_email($data) {
	global $editor;
	//  print_r($data);
	if (preg_match('/^company$/i',$data['subject']))
		$subject=new Company($data['subject_key']);
	else {
		$subject=new Contact($data['subject_key']);
	}

	if (!$subject->id) {
		$response=array('state'=>400,'msg'=>'Subject not found');
		echo json_encode($response);
		return;
	}

	if (!isset($data['value']['Email'])) {
		$response=array('state'=>400,'msg'=>'No email value');
		echo json_encode($response);
		return;
	}

	$editing=false;
	$creating=false;

	$msg=_('No changes');

	if ($data['value']['Email Key']>0) {
		$action='updated';
		$email=new Email('id',$data['value']['Email Key']);
		if (!$email->id) {
			$response=array('state'=>400,'msg'=>'Email not found');
			echo json_encode($response);
			return;
		}
		$email->set_editor($editor);
		$email->update(array('Email'=>$data['value']['Email']));
		if ($email->error_updated) {
			$response=array('state'=>200,'action'=>'error','msg'=>$email->msg_updated,'email_key'=>$data['value']['Email Key']);
			echo json_encode($response);
			return;
		}

		if ($email->updated)
			$msg=_('Email updated');

		$update_data=array(
			'Email Key'=>$data['value']['Email Key'],
			'Email Description'=>$data['value']['Email Description'],
			'Email Is Main'=>$data['value']['Email Is Main'],
			'Email Contact Name'=>$data['value']['Email Contact Name']
		);


		$subject->associate_email($email->id);
		if ($data['value']['Email Is Main']=='Yes')
			$subject->update_principal_email($email->id);
		if ($subject->updated)
			$msg=_('Email updated');
		$email->set_scope($data['subject'],$data['subject_key']);

	} else {
		$action='created';
		$email_data=array(
			'Email'=>$data['value']['Email'],
			'Email Description'=>$data['value']['Email Description'],
			'Email Is Main'=>$data['value']['Email Is Main'],
			'Email Contact Name'=>$data['value']['Email Contact Name']
		);


		$email=new Email('find create',$email_data);
		if ($email->found) {
			$response=array('state'=>200,'action'=>'error','msg'=>'Email Found','email_key'=>$email->id);
			echo json_encode($response);
			return;
		}

		$subject->associate_email($email->id);


		if ($subject->error) {
			$response=array('state'=>200,'action'=>'error','msg'=>$subject->msg_updated,'email_key'=>$data['value']['Email Key']);
			echo json_encode($response);
			return;
		}
		if ($subject->inserted_email) {
			$email->set_scope($data['subject'],$data['subject_key']);
			$msg=_("Email created");
		} else {
			$response=array('state'=>200,'action'=>'nochange','msg'=>$subject->msg_updated,'email_key'=>$data['value']['Email Key']);
			echo json_encode($response);
			return;
		}
	}
	$updated_email_data=array(
		'Email'=>$email->data['Email'],
		'Email_Description'=>$email->data['Email Description'],
		'Email_Contact_Name'=> $email->data['Email Contact Name'],
		'Email_Is_Main'=> $email->data['Email Is Main']
	);
	$subject->reread();
	$response=array(
		'state'=>200,
		'action'=>$action,
		'msg'=>$msg,
		'email_key'=>$data['value']['Email Key'],
		'updated_data'=>$updated_email_data,
		'xhtml_subject'=>$subject->display('card'),
		'main_email_key'=>$subject->get_principal_email_key()
	);

	echo json_encode($response);

}



function add_mobile($data) {
	global $editor;
	if (preg_match('/^company$/i',$data['subject'])) {
		//todo things here
	}

	$contact=new Contact($data['subject_key']);

	$action='created';


	$mobile_data=array(
		'Telecom'=>$data['value']['Telecom'],
		'Telecom Type'=>$data['value']['Telecom Type'],
		'Telecom Type'=>'Mobile',
		'Telecom Raw Number'=>$data['value']['Telecom'],
		'editor'=>$editor
	);

	$mobile=new Telecom("find in Contact ".$contact->id." create  country code ".$contact->data['Contact Main Country Code']."   ",$mobile_data);




	if (!$mobile->id) {
		$response=array('state'=>200,'action'=>'error','msg'=>$mobile->msg);
		echo json_encode($response);
		return;
	}

	$contact->associate_mobile($mobile->id);
	if ($data['value']['Telecom Is Main']=='Yes' ) {
		$contact->update_principal_mobil($mobile->id);
	}

	if ($contact->add_telecom) {

		$updated_telecom_data=array(
			"Mobile_Key"=>$mobile->id,
			"Mobile"=>$mobile->display(),
			"Country_Code"=>$mobile->data['Telecom Country Telephone Code'],
			"National_Access_Code"=>$mobile->data['Telecom National Access Code'],
			"Number"=>$mobile->data['Telecom Number'],
			"Telecom_Is_Main"=>$data['value']['Telecom Is Main'],
			"Telecom Type Description"=>$mobile->data['Telecom Type'],
		);

		$msg='';
		$response=array(
			'state'=>200,
			'action'=>$action,
			'msg'=>$msg,
			'telecom_key'=>$mobile->id,
			'updated_data'=>$updated_telecom_data,
			'xhtml_subject'=>$contact->display('card'),
			'main_mobile_key'=>$contact->get_principal_mobile_key()
		);

		echo json_encode($response);
		return;
	} else {
		$response=array('state'=>200,'action'=>'nochange','msg'=>$contact->msg_updated);
		echo json_encode($response);
		return;

	}



}
function edit_mobile($data) {
	global $editor;
	if (preg_match('/^company$/i',$data['subject'])) {
		//todo things here
	}

	$contact=new Contact($data['subject_key']);


	$mobile=new Telecom('id',$data['value']['Telecom Key']);
	if (!$mobile->id) {
		$response=array('state'=>400,'msg'=>'Telecom not found');
		echo json_encode($response);
		return;
	}
	$mobile->set_editor($editor);
	$mobile->update_number($data['value']['Telecom']);
	if ($mobile->error_updated) {
		$response=array('state'=>200,'action'=>'error','msg'=>$mobile->msg_updated);
		echo json_encode($response);
		return;
	}




	if ($data['value']['Telecom Is Main']=='Yes' ) {
		$contact->update_principal_mobil($mobile->id);
	}

	if ($mobile->updated or $contact->updated) {

		$updated_telecom_data=array(
			"Mobile_Key"=>$mobile->id,
			"Mobile"=>$mobile->display(),
			"Country_Code"=>$mobile->data['Telecom Country Telephone Code'],
			"National_Access_Code"=>$mobile->data['Telecom National Access Code'],
			"Number"=>$mobile->data['Telecom Number'],
			"Telecom_Is_Main"=>$data['value']['Telecom Is Main'],
			"Telecom Type Description"=>$mobile->data['Telecom Type'],
		);
		$action='updated';
		$msg=_('Telecom updated');
		$response=array(
			'state'=>200,
			'action'=>$action,
			'msg'=>$msg,
			'telecom_key'=>$mobile->id,
			'updated_data'=>$updated_telecom_data,
			'xhtml_subject'=>$contact->display('card'),
			'main_mobile_key'=>$contact->get_principal_mobile_key()
		);

		echo json_encode($response);
		return;
	} else {
		$response=array('state'=>200,'action'=>'nochange','msg'=>$mobile->msg_updated);
		echo json_encode($response);
		return;

	}


}


function edit_address_main_telephone($number,$address_key) {

	global $editor;

	$address=new Address($address_key);
	if (!$address->id) {
		return -2;
		//$response=array('state'=>400,'msg'=>"Address not found $address_key");
		//echo json_encode($response);
		//exit;



	}
	$telecom_key=$address->get_principal_telecom_key('Telephone');

	if ($telecom_key and $number=='') {
		$telephone=new Telecom($telecom_key);
		$telephone->delete();
		return 1;
	}

	$telephone_data=array();
	$telephone_data['editor']=$editor;
	$telephone_data['Telecom Raw Number']=$number;
	$telephone_data['Telecom Type']='Telephone';
	$proposed_telephone=new Telecom("find complete country code ".$address->data['Address Country Code'],$telephone_data);
	if ($proposed_telephone->found) {

		return -1;
		// $response=array('state'=>400,'msg'=>'Telephone found in another address');
		// echo json_encode($response);
		// exit;
	}
	if (!$telecom_key) {
		$telephone=new Telecom("find complete create country code ".$address->data['Address Country Code'],$telephone_data);
		$address->associate_telecom($telephone->id,'Telephone');
		return 1;
	} else {
		$address->update_telecom($telecom_key,$number);
		return $address->updated;
	}
}

function edit_address_main_contact($contact,$address_key) {

	global $editor;

	$address=new Address($address_key);
	if (!$address->id) {
		$response=array('state'=>400,'msg'=>"Address not found $address_key");
		echo json_encode($response);
		exit;
	}
	$address->editor=$editor;
	$address->update_field_switcher('Address Contact',$contact);
	return $address->updated;
}

function edit_telecom($data) {
	global $editor;

	if (preg_match('/^company$/i',$data['subject'])) {
		$subject=new Company($data['subject_key']);
		$subject_type='Company';
	} else {
		$subject=new Contact($data['subject_key']);
		$subject_type='Contact';

	}

	if (!$subject->id) {
		$response=array('state'=>400,'msg'=>'Subject not found');
		echo json_encode($response);
		return;
	}

	$address_key=0;
	if (array_key_exists('Address Key',$data['value'])) {
		$address_key=$data['value']['Address Key'];
	}

	$editing=false;
	$creating=false;

	$msg=_('No changes');

	if ($data['value']['Telecom Key']>0) {
		$action='updated';
		$telecom=new Telecom('id',$data['value']['Telecom Key']);
		if (!$telecom->id) {
			$response=array('state'=>400,'msg'=>'Telecom not found');
			echo json_encode($response);
			return;
		}
		$telecom->set_editor($editor);
		$telecom->update_number($data['value']['Telecom']);
		if ($telecom->error_updated) {
			$response=array('state'=>200,'action'=>'error','msg'=>$telecom->msg_updated);
			echo json_encode($response);
			return;
		}

		if ($telecom->updated)
			$msg=_('Telecom updated');
		/*
                $update_data=array(
                                 'Telecom Key'=>$data['value']['Telecom Key'],
                                 'Telecom Is Main'=>$data['value']['Telecom Is Main'],
                                 'Telecom Type'=>$data['value']['Telecom Type']
                             );
                $subject->add_tel($update_data);
                if ($subject->updated)
                    $msg=_('Telecom updated');
                $telecom->set_scope($data['subject'],$data['subject_key']);
        */


	} else {
		$action='created';


		$telephone_data=array(
			'Telecom'=>$data['value']['Telecom'],
			//                        'Telecom Is Main'=>$data['value']['Telecom Is Main'],
			'Telecom Type'=>$data['value']['Telecom Type']
		);




		if ($data['value']['Telecom Category']=='Mobile') {
			$telephone_data['Telecom Type']='Mobile';
		}


		$telephone_data['Telecom Raw Number']=$data['value']['Telecom'];
		$telephone_data['editor']=$editor;
		// print_r($telephone_data);
		//exit;
		$telephone=new Telecom("find in $subject_type ".$subject->id." create  country code ".$subject->data[$subject_type.' Main Country Code']."   ",$telephone_data);

		if (!$telephone->id) {
			$response=array('state'=>200,'action'=>'error','msg'=>'Error finding the telecom');
			echo json_encode($response);
			return;
		}


		if ($data['value']['Telecom Category']=='Mobile') {
			$subject->associate_mobile($telephone->id);
		}

	}



	if ($data['value']['Telecom Is Main']=='Yes' and $data['value']['Telecom Category']=='Mobile') {
		$subject->update_principal_mobil($telephone->id);

	}



	if ($subject->error) {
		$response=array('state'=>200,'action'=>'error','msg'=>$subject->msg_updated);
		echo json_encode($response);
		return;
	}

	if ($subject->add_telecom) {
		$updated_telecom_data=array();

		$msg='';
		$response=array(
			'state'=>200,
			'action'=>$action,
			'msg'=>$msg,
			'telecom_key'=>$telephone->id,
			'updated_data'=>$updated_telecom_data,
			'xhtml_subject'=>$subject->display('card'),
			'main_telecom_key'=>$subject->get_main_telecom_key()
		);

		echo json_encode($response);
		return;
	} else {
		$response=array('state'=>200,'action'=>'nochange','msg'=>$subject->msg_updated);
		echo json_encode($response);
		return;

	}


}
function new_address($_data) {
	global $editor;
	$warning='';



	$raw_data=$_data['value'];




	$subject=$_data['subject'];
	$subject_key=$_data['subject_key'];
	$address_type=$_data['address_type'];
	switch ($subject) {
	case('Company'):
		$subject_object=new Company($subject_key);
		break;
	case('Contact'):
		$subject_object=new Contact($subject_key);
		break;
	case('Customer'):
		$subject_object=new Customer($subject_key);
		break;
	default:

		$response=array('state'=>400,'msg'=>'Error wrong subject/subject key (2)');
		echo json_encode($response);
		return;

	}

	$translator=array(
		'country_code'=>'Address Country Code',
		'country_d1'=>'Address Country First Division',
		'country_d2'=>'Address Country Second Division',
		'town'=>'Address Town',
		'town_d1'=>'Address Town First Division',
		'town_d2'=>'Address Town Second Division',
		'postal_code'=>'Address Postal Code',
		'street'=>'Street Data',
		'internal'=>'Address Internal',
		'building'=>'Address Building',
		'type'=>'Address Type',
		'function'=>'Address Function',
		'description'=>'Address Description',
		'contact'=>'Address Contact'
	);





	$data=array('editor'=>$editor);
	foreach ($raw_data as $key=>$value) {
		if (array_key_exists($key, $translator)) {

			if (is_string($value))
				$value=strip_tags($value);

			$data[$translator[$key]]=$value;
		}
	}
	// print $subject;
	//print_r($data);
	$address=new Address("find in $subject $subject_key create",$data);

	if (!$address->id) {
		$response=array('state'=>400,'msg'=>'Error can not create address');
		echo json_encode($response);
		return;
	}
	if ($address->found) {
		$address_parents=  $address->get_parent_keys($subject);
		if (array_key_exists($subject_key,$address_parents)) {

			if ($address_type=='Delivery') {

				$address_keys=$subject_object->get_delivery_address_keys();
				if (array_key_exists($address->id,$address_keys)) {
					$response=array('state'=>200,'action'=>'nochange','msg'=>'address in delivery address');
					echo json_encode($response);
					return;

				}



			}elseif ($address_type=='Billing') {

				$address_keys=$subject_object->get_billing_address_keys();
				if (array_key_exists($address->id,$address_keys)) {
					$response=array('state'=>200,'action'=>'nochange','msg'=>'address in billing address');
					echo json_encode($response);
					return;

				}



			}else {

				$response=array('state'=>200,'action'=>'nochange','msg'=>_('Address already in company'));
				echo json_encode($response);
				return;
			}
		} else {
			$warning=_('Warning, address found also associated with')." ";
			switch ($subject) {
			case 'Customer':
				$parent_label='';
				foreach ($address_parents as $parent_key) {
					$parent=new Customer($parent_key);
					$parent_label.=sprintf(', <a href="customer.php?id=%d">%s</a>',$parent->id,$parent->data['Customer Name']);
				}
				$parent_label=preg_replace('/^,/','',$parent_label);
				$warning.=ngettext(count($address_parents),'Customer','Customers').' '.$parent_label;
				break;
			case 'Company':
				$parent_label='';
				foreach ($address_parents as $parent_key) {
					$parent=new Company($parent_key);
					$parent_label.=sprintf(', <a href="company.php?id=%d">%s</a>',$parent->id,$parent->data['Company Name']);
				}
				$parent_label=preg_replace('/^,/','',$parent_label);
				$warning.=ngettext(count($address_parents),'Company','Companies').' '.$parent_label;
				break;
			case('Contact'):


				$parent_label='';
				foreach ($address_parents as $parent_key) {
					$parent=new Contact($parent_key);
					if ($parent->data['Contact Company Key']!=$subject->data['Contact Company Key'] )
						$parent_label.=sprintf(', <a href="contact.php?id=%d">%s</a>',$parent->id,$parent->display('name'));
				}
				if ($parent_label=='')
					$warning='';
				else {
					$parent_label=preg_replace('/^,/','',$parent_label);
					$warning.=ngettext(count($address_parents),'Contact','Contacts').' '.$parent_label;
				}
				break;


			default:
				break;
			}


		}

	}

	if ($subject=='Customer') {


		if ($address_type=='Billing') {

			$subject_object->associate_billing_address($address->id);
			//            $subject_object->update_principal_billing_address($address->id);
		} elseif ($address_type=='Delivery') {
			$subject_object->associate_delivery_address($address->id);
		}

	} else
		$subject_object->associate_address($address->id);

	if ($subject_object->updated) {

		$address_bridge_data=$subject_object->get_address_bridge_data($address->id);
		if (!$address_bridge_data) {
			$response=array('state'=>400,'action'=>'error','msg'=>'Address Not bridged');
			echo json_encode($response);
			return;
		}

		if ($raw_data['use_tel'] and $raw_data['telephone']!='') {
			edit_address_main_telephone($raw_data['telephone'],$address->id);
		}


		$updated_address_data=array(
			'country'=>$address->data['Address Country Name'],
			'country_code'=>$address->data['Address Country Code'],
			'country_d1'=> $address->data['Address Country First Division'],
			'country_d2'=> $address->data['Address Country Second Division'],
			'town'=> $address->data['Address Town'],
			'postal_code'=> $address->data['Address Postal Code'],
			'town_d1'=> $address->data['Address Town First Division'],
			'town_d2'=> $address->data['Address Town Second Division'],
			'fuzzy'=> $address->data['Address Fuzzy'],
			'street'=> $address->display('street'),
			'building'=>  $address->data['Address Building'],
			'internal'=> $address->data['Address Internal'],
			'type'=>$address_bridge_data['Address Type'],
			'function'=>$address_bridge_data['Address Function'],
			'description'=>$address->data['Address Description'],
			'telephone'=>$address->get_formated_principal_telephone(),
			'contact'=>$address->data['Address Contact'],
			'key'=>$address->id

		);


		$response=array(
			'state'=>200,
			'action'=>'created',
			'msg'=>$subject_object->msg,
			'updated_data'=>$updated_address_data,
			'xhtml_address'=>$address->display('xhtml'),
			'address_key'=>$address->id
		);
		echo json_encode($response);
		return;

	} else {
		$response=array('state'=>200,'action'=>'nochange','msg'=>_('Address already in company'));
		echo json_encode($response);
		return;
	}

}


function update_main_address($data) {

	$address_key=$data['value'];

	$subject=$data['subject'];
	$subject_key=$data['subject_key'];

	$type=$_REQUEST['key'];


	switch ($subject) {
	case('Company'):
		$subject_object=new Company($subject_key);
		break;
	case('Contact'):
		$subject_object=new Contact($subject_key);
		break;
	case('Customer'):
		$subject_object=new Customer($subject_key);
		break;
	default:

		$response=array('state'=>400,'msg'=>'Error wrong subject/subject key (2)');
		echo json_encode($response);
		return;

	}



	if ($subject=='Customer') {

		if ($type=='Delivery') {
			$subject_object->update_principal_delivery_address($address_key);
			if ($subject_object->error) {
				$response=array('state'=>400,'msg'=>$subject_object->msg);

			}
			elseif ($subject_object->updated) {

				if ( $subject_object->get('Customer Delivery Address Link')=='Contact') {
					$address_comment='<span style="font-weight:600">'._('Same as contact address').'</span>';

				}else {
					$address_comment=$subject_object->delivery_address_xhtml();
				}

				$response=array(
					'state'=>200
					,'action'=>'changed'
					,'new_main_address'=>$subject_object->display_delivery_address('xhtml')
					,'new_main_address_bis'=>$address_comment

					,'new_main_delivery_address_key'=>$subject_object->data['Customer Main Delivery Address Key']

				);

			}
			else {
				$response=array('state'=>200,'action'=>'no_change','msg'=>_('Nothing to change'));


			}
			echo json_encode($response);
			return;

		}
		elseif ($type=='Billing') {



			$subject_object->update_principal_billing_address($address_key);
			if ($subject_object->error) {
				$response=array('state'=>400,'msg'=>$subject_object->msg);

			}
			elseif ($subject_object->updated) {

				if ( $subject_object->get('Customer Billing Address Link')=='Contact') {
					$address_comment='<span style="font-weight:600">'._('Same as contact address').'</span>';

				}
				else {
					$address_comment=$subject_object->billing_address_xhtml();
				}

				$response=array(
					'state'=>200
					,'action'=>'changed'
					,'new_main_address'=>$subject_object->display_billing_address('xhtml')
					,'new_main_address_bis'=>$address_comment

					,'new_main_billing_address_key'=>$subject_object->data['Customer Billing Address Key']

				);

			}
			else {
				$response=array('state'=>200,'action'=>'no_change','msg'=>_('Nothing to change'));


			}
			echo json_encode($response);
			return;

		}

	}



}


function edit_address_type() {
	global $editor;

	if ( !isset($_REQUEST['value']) ) {
		$response=array('state'=>400,'msg'=>'Error no value');
		echo json_encode($response);
		return;
	}

	$tmp=preg_replace('/\\\"/','"',$_REQUEST['value']);
	$tmp=preg_replace('/\\\\\"/','"',$tmp);
	//$tmp=$_REQUEST['value'];
	$raw_data=json_decode($tmp, true);
	//   print "$tmp";
	// print_r($raw_data);

	if (!is_array($raw_data)) {
		$response=array('state'=>400,'msg'=>'Wrong value');
		echo json_encode($response);
		return;
	}
	if ( !isset($_REQUEST['id'])  or !is_numeric($_REQUEST['id']) or $_REQUEST['id']<=0  ) {
		$response=array('state'=>400,'msg'=>'Error wrong id');
		echo json_encode($response);
		return;
	}



	if ( !isset($_REQUEST['subject'])
		or !is_numeric($_REQUEST['subject_key'])
		or $_REQUEST['subject_key']<=0
		or !preg_match('/^company|contact$/i',$_REQUEST['subject'])

	) {
		$response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
		echo json_encode($response);
		return;
	}

	$subject=$_REQUEST['subject'];
	$subject_key=$_REQUEST['subject_key'];


	$address=new Address('id',$_REQUEST['id']);

	if (!$address->id) {
		$response=array('state'=>400,'msg'=>'Address not found');
		echo json_encode($response);
		return;
	}
	$address->set_editor($editor);
	$address->set_scope($subject,$subject_key);
	$address->update_metadata(
		array('Type'=>$raw_data)
	);


	$updated_data=array();
	foreach ($address->get('Type') as $type)
		$updated_data[]=$type;

	if ($address->updated) {
		$response=array(
			'state'=>200
			,'action'=>'updated'
			,'msg'=>$address->msg_updated
			,'key'=>''
			,'updated_data'=>$updated_data
		);
	} else {
		if ($address->error_updated)
			$response=array('state'=>200,'action'=>'error','msg'=>$company->msg_updated,'key'=>'');
		else
			$response=array('state'=>200,'action'=>'nochange','msg'=>$address->msg_updated,'key'=>'');

	}


	echo json_encode($response);
}





function edit_address($data) {
	global $editor;
	$warning='';




	$id=$data['id'];
	$subject=$data['subject'];
	$subject_key=$data['subject_key'];
	$raw_data=$data['value'];
	//    if ($subject=='Customer' and $_REQUEST['key']=='Billing') {
	//        edit_billing_address($raw_data);
	//        exit;
	//    }

	//$subject_key=$_REQUEST['subject_key'];
	switch ($subject) {
	case('Company'):
		$subject_object=new Company($subject_key);
		break;
	case('Contact'):
		$subject_object=new Contact($subject_key);
		break;
	case('Customer'):
		$subject_object=new Customer($subject_key);
		break;
	case('Supplier'):
		$subject_object=new Supplier($subject_key);
		break;


	}

	$address=new Address('id',$id);

	if (!$address->id) {
		$response=array('state'=>400,'msg'=>'Address not found');
		echo json_encode($response);
		return;
	}
	$address->set_editor($editor);



	$translator=array(
		'country_code'=>'Address Country Code',
		'country_d1'=>'Address Country First Division',
		'country_d2'=>'Address Country Second Division',
		'town'=>'Address Town',
		'town_d1'=>'Address Town First Division',
		'town_d2'=>'Address Town Second Division',
		'postal_code'=>'Address Postal Code',
		'street'=>'Street Data',
		'internal'=>'Address Internal',
		'building'=>'Address Building',
		'contact'=>'Address Contact'
	);


	$update_data=array('editor'=>$editor);

	foreach ($raw_data as $key=>$value) {
		if (array_key_exists($key, $translator)) {
			$update_data[$translator[$key]]=strip_tags($value);
		}
	}

	$proposed_address=new Address("find complete in $subject $subject_key",$update_data);

	if ($proposed_address->id) {

		//  print "xxxxaaxxx";

		if ($subject=='Customer') {

			if (preg_match('/^contact$/i',$_REQUEST['key'])) {

				if ($address->id==$proposed_address->id) {

					$address->update($update_data,'cascade');

					if ($address->updated) {
						$response=address_response($address->id,$subject,$subject_object,$warning);
						echo json_encode($response);
					} else {
						$response=array('state'=>200,'action'=>'nochange','msg'=>$address->msg_updated,'key'=>'','xxx'=>'xx');
						echo json_encode($response);
					}


					exit;
				} else {



					$subject_object->update_principal_address($proposed_address->id);

					$response=address_response($proposed_address->id,$subject,$subject_object);
					$response=array('state'=>200,'action'=>'error','msg'=>$address->msg_updated,'key'=>$translator[$_REQUEST['key']],'zzz'=>'x');
					echo json_encode($response);
					return;

				}



			}
			else {

				//print_r($data['value']);

				if ($data['value']['use_tel'] or $data['value']['use_contact']) {

					edit_address_main_telephone($data['value']['telephone'],$proposed_address->id);
					edit_address_main_contact($data['value']['contact'],$proposed_address->id);

					$response=address_response($proposed_address->id,$subject,$subject_object);



					echo json_encode($response);
					return;

					exit();



				} else {
					$msg="This Customer has already another address with this data";
					$response=array('state'=>200,'action'=>'nochange','msg'=>$msg );
					echo json_encode($response);
					return;
				}
			}
		}
		else if ($subject=='Supplier') {
				if (preg_match('/^contact$/i',$_REQUEST['key'])) {
					$subject_object->update_principal_address($proposed_address->id);

					// print "new Address address".$subject_object->data['Customer Main Address Key']."\n";
					$address->delete();

					return;
				} else {
					$msg="This $subject has already another address with this data";
					$response=array('state'=>200,'action'=>'nochange','msg'=>$msg ,'zzz'=>'x2');
					echo json_encode($response);
					return;
				}
			}




	}
	else {// address not found inside customer
		$proposed_address=new Address("find complete ",$update_data);

		if ($proposed_address->id) {
			$address_parents=$proposed_address->get_parent_keys($subject);

			$warning=_('Warning, address found also associated with')." ";
			switch ($subject) {
			case 'Customer':
				$parent_label='';

				foreach ($address_parents as $parent_key) {
					$parent=new Customer($parent_key);
					$parent_label.=sprintf(', <a href="customer.php?id=%d">%s</a>',$parent->id,$parent->data['Customer Name']);
				}
				$parent_label=preg_replace('/^,/','',$parent_label);
				$warning.=ngettext(count($address_parents),'Customer','Customers').' '.$parent_label;
				break;
			case 'Supplier':
				$parent_label='';
				foreach ($address_parents as $parent_key) {
					$parent=new Supplier($parent_key);
					$parent_label.=sprintf(', <a href="supplier.php?id=%d">%s</a>',$parent->id,$parent->data['Customer Name']);
				}
				$parent_label=preg_replace('/^,/','',$parent_label);
				$warning.=ngettext(count($address_parents),'Supplier','Suppliers').' '.$parent_label;
				break;
			case 'Company':
				$parent_label='';
				foreach ($address_parents as $parent_key) {
					$parent=new Company($parent_key);
					$parent_label.=sprintf(', <a href="company.php?id=%d">%s</a>',$parent->id,$parent->data['Company Name']);
				}
				$parent_label=preg_replace('/^,/','',$parent_label);
				$warning.=ngettext(count($address_parents),'Company','Companies').' '.$parent_label;
				break;
			case('Contact'):


				$parent_label='';
				foreach ($address_parents as $parent_key) {
					$parent=new Contact($parent_key);
					if ($parent->data['Contact Company Key']!=$subject->data['Contact Company Key'] )
						$parent_label.=sprintf(', <a href="contact.php?id=%d">%s</a>',$parent->id,$parent->display('name'));
				}
				if ($parent_label=='')
					$warning='';
				else {
					$parent_label=preg_replace('/^,/','',$parent_label);
					$warning.=ngettext(count($address_parents),'Contact','Contacts').' '.$parent_label;
				}
				break;


			default:
				break;
			}

		}
	}

	// print_r($update_data);

	$address->update($update_data,'cascade');


	//print "-------------";

	$updated=$address->updated;
	if ($data['value']['use_tel']) {
		if ($data['value']['telephone']!='') {
			$tel_updated=edit_address_main_telephone($data['value']['telephone'],$address->id);
			if (!$updated and $tel_updated)
				$updated=true;

		}
	}



	//print_r($data);


	if ($updated) {


		$response=address_response($address->id,$subject,$subject_object,$warning);


	} else {
		if ($address->error_updated)
			$response=array('state'=>200,'action'=>'error','msg'=>$address->msg_updated,'key'=>$translator[$_REQUEST['key']],'zzz'=>'x3');
		else {
			//$response=array('state'=>200,'action'=>'nochange','msg'=>$address->msg_updated,'key'=>'','zzz'=>'x4');
			$response=address_response($address->id,$subject,$subject_object);



			echo json_encode($response);
			return;

		}

	}


	echo json_encode($response);

}

function address_response($address_key,$subject,$subject_object,$warning='') {

	$address=new Address($address_key);

	$updated_address_data=array(
		'country'=>$address->data['Address Country Name'],
		'country_code'=>$address->data['Address Country Code'],
		'country_d1'=> $address->data['Address Country First Division'],
		'country_d2'=> $address->data['Address Country Second Division'],
		'town'=> $address->data['Address Town'],
		'postal_code'=> $address->data['Address Postal Code'],
		'town_d1'=> $address->data['Address Town First Division'],
		'town_d2'=> $address->data['Address Town Second Division'],
		'fuzzy'=> $address->data['Address Fuzzy'],
		'street'=> $address->display('street'),
		'building'=>  $address->data['Address Building'],
		'internal'=> $address->data['Address Internal'],
		'description'=>$address->data['Address Description'],
		'telephone'=>$address->get_formated_principal_telephone(),
		'contact'=>$address->data['Address Contact']
	);
	$is_main='No';
	$is_main_delivery='No';
	$is_main_billing='No';
	$address_comment='';

	if ($subject_object->get_main_address_key()==$address->id) {
		$is_main='Yes';
	}
	if ($subject=='Customer'  ) {



		if ($subject_object->data['Customer Main Delivery Address Key']==$address->id) {

			$is_main_delivery='Yes';

			if ( ($subject_object->get('Customer Delivery Address Link')=='Contact') ) {
				$address_comment='<span style="font-weight:600">'._('Same as contact address').'</span>';

			}

			else {
				$address_comment=$subject_object->delivery_address_xhtml();
			}

		}

		if ($subject_object->data['Customer Billing Address Key']==$address->id) {
			$is_main_billing='Yes';

		}

		if ( ($subject_object->get('Customer Billing Address Link')=='Contact')  ) {
			$billing_address='<span style="font-weight:600">'._('Same as contact address').'</span>';

		} else {

			$billing_address=$subject_object->billing_address_xhtml();
		}


	}
	$response=array('state'=>200,'action'=>'updated','warning'=>$warning,'is_main'=>$is_main,'is_main_delivery'=>$is_main_delivery,'is_main_billing'=>$is_main_billing,'msg'=>$address->msg_updated,'key'=>$address->id,'updated_data'=>$updated_address_data,'xhtml_address'=>$address->display('xhtml'));
	if ($subject=='Customer') {
		$response['xhtml_delivery_address_bis']=$address_comment;
		$response['xhtml_billing_address']=$billing_address;


	}

	return $response;

}


function delete_email() {
	global $editor;
	if ( !isset($_REQUEST['value'])  ) {
		$response=array('state'=>400,'msg'=>'Error no value');
		echo json_encode($response);
		return;
	}
	if ( !isset($_REQUEST['subject'])
		or !is_numeric($_REQUEST['subject_key'])
		or $_REQUEST['subject_key']<=0       or !preg_match('/^company|contact$/i',$_REQUEST['subject'])

	) {
		$response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
		echo json_encode($response);
		return;
	}
	$subject_type=$_REQUEST['subject'];
	$subject_key=$_REQUEST['subject_key'];


	if (preg_match('/^company$/i',$subject_type)) {
		$subject=new Company($subject_key);
		$is_company=true;
	} else {
		$subject=new Contact($subject_key);
		$is_company=false;
	}


	if (!$subject->id) {
		$response=array('state'=>400,'msg'=>'Subject not found');
		echo json_encode($response);
		return;
	}

	$email_key=$_REQUEST['value'];
	if (!is_numeric($email_key)) {
		$email = new Email('email',$email_key);
		$email_key=$email->id;
	} else {
		$email = new Email($email_key);
		$email_key=$email->id;

	}
	$email->delete();
	if ($is_company) {
		$contact_found_keys=$subject->get_contact_keys();
		//print_r($contact_found_keys);
		foreach ($contact_found_keys as $contact_found_key) {
			$contact=new Contact($contact_found_key);
			$contact->editor=$subject->editor;
			$contact->remove_email($email->id);
		}
	}
	if ($email->deleted) {
		$action='deleted';
		$msg=_('Email deleted');
		$subject->reread();
	} else {
		$action='nochange';
		$msg=_('Email could not be deleted');
	}

	$response=array('state'=>200,'action'=>$action,'msg'=>$msg,'email_key'=>$email_key,'xhtml_subject'=>$subject->display('card'),'main_email_key'=>$subject->get_principal_email_key());
	echo json_encode($response);
}




function delete_mobile() {
	global $editor;
	if ( !isset($_REQUEST['value'])  ) {
		$response=array('state'=>400,'msg'=>'Error no value');
		echo json_encode($response);
		return;
	}
	if ( !isset($_REQUEST['subject'])
		or !is_numeric($_REQUEST['subject_key'])
		or $_REQUEST['subject_key']<=0       or !preg_match('/^contact$/i',$_REQUEST['subject'])

	) {
		$response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
		echo json_encode($response);
		return;
	}
	$subject_type=$_REQUEST['subject'];
	$subject_key=$_REQUEST['subject_key'];



	$subject=new Contact($subject_key);



	if (!$subject->id) {
		$response=array('state'=>400,'msg'=>'Contact not found');
		echo json_encode($response);
		return;
	}
	$mobil = new Telecom($_REQUEST['value']);

	if (!$mobil->id) {
		$response=array('state'=>400,'msg'=>'Mobile not found');
		echo json_encode($response);
		return;
	}




	$mobil_key=$mobil->id;


	$mobil->delete();

	if ($mobil->deleted) {
		$action='deleted';
		$msg=_('Mobile deleted');
		$subject->reread();
	} else {
		$action='nochange';
		$msg=_('Mobile could not be deleted');
	}

	$response=array('state'=>200,'action'=>$action,'msg'=>$msg,'telecom_key'=>$mobil_key,'xhtml_subject'=>$subject->display('card'),'main_mobil_key'=>$subject->get_principal_mobile_key());
	echo json_encode($response);
}

function delete_address($data) {
	global $editor;




	$address_key=$data['address_key'];

	$address=new Address($address_key);



	$subject=$data['subject'];
	$subject_key=$data['subject_key'];
	switch ($subject) {
		// case('Company'):
		//  $subject_object=new Company($subject_key);
		//  break;
		// case('Contact'):
		//  $subject_object=new Contact($subject_key);
		//  break;
	case('Customer'):
		$subject_object=new Customer($subject_key);

		break;
	default:

		$response=array('state'=>400,'msg'=>'Error wrong subject/subject key (2)');
		echo json_encode($response);
		return;

	}



	if ($subject=='Customer' ) {

		if ($data['type']=='Delivery') {


			$sql=sprintf("delete from `Address Bridge` where `Address Key`=%d and `Address Function`='Shipping' and `Subject Type`=%s and `Subject Key`=%d   ",
				$address->id,
				prepare_mysql($subject),
				$subject_key
			);
			//print $sql;
			mysql_query($sql);

			if (!$address->has_parents()) {
				$address->delete();
			}





			if ($subject_object->data['Customer Main Delivery Address Key']==$address->id) {
				$address_keys=$subject_object->get_delivery_address_keys();
				$new_delivery_address_key=array_pop($address_keys);
				$subject_object->update_principal_delivery_address($new_delivery_address_key);
			}
			if ($subject_object->data['Customer Billing Address Key']==$address->id) {
				$address_keys=$subject_object->get_billing_address_keys();
				$new_billing_address_key=array_pop($address_keys);
				$subject_object->update_principal_billing_address($new_billing_address_key);
			}


		}
		elseif ($data['type']=='Billing') {



			$sql=sprintf("delete from `Address Bridge` where `Address Key`=%d and `Address Function`='Billing' and `Subject Type`=%s and `Subject Key`=%d   ",
				$address->id,
				prepare_mysql($subject),
				$subject_key
			);

			mysql_query($sql);

			if (!$address->has_parents()) {
				$address->delete();
			}




			if ($subject_object->data['Customer Main Delivery Address Key']==$address->id) {
				$address_keys=$subject_object->get_delivery_address_keys();
				$new_delivery_address_key=array_pop($address_keys);
				$subject_object->update_principal_delivery_address($new_delivery_address_key);
			}
			if ($subject_object->data['Customer Billing Address Key']==$address->id) {
				$address_keys=$subject_object->get_billing_address_keys();
				$new_billing_address_key=array_pop($address_keys);
				$subject_object->update_principal_billing_address($new_billing_address_key);
			}






		}

	}





	$action='deleted';
	$msg=_('Address Deleted');
	$subject_object->get_data('id',$subject_object->id);
	$main_address_key=$subject_object->get_main_address_key();

	$main_address=new Address($main_address_key);

	$main_address_data=array(
		'country'=>$main_address->data['Address Country Name'],
		'country_code'=>$main_address->data['Address Country Code'],
		'country_d1'=> $main_address->data['Address Country First Division'],
		'country_d2'=> $main_address->data['Address Country Second Division'],
		'town'=> $main_address->data['Address Town'],
		'postal_code'=> $main_address->data['Address Postal Code'],
		'town_d1'=> $main_address->data['Address Town First Division'],
		'town_d2'=> $main_address->data['Address Town Second Division'],
		'fuzzy'=> $main_address->data['Address Fuzzy'],
		'street'=> $main_address->display('street'),
		'building'=>  $main_address->data['Address Building'],
		'internal'=> $main_address->data['Address Internal'],
		'description'=>$main_address->data['Address Description']

	);


	$address_comment='';


	$address_main_delivery='';
	$address_main_delivery_key='';
	$billing_address='';
	if ($subject=='Customer' ) {



		$address_main_delivery=$subject_object->delivery_address_xhtml();
		$address_main_delivery_key=$subject_object->data['Customer Main Delivery Address Key'];
		$address_main_billing_key=$subject_object->data['Customer Billing Address Key'];


		$billing_address=$subject_object->billing_address_xhtml();
		if ( ($subject_object->get('Customer Delivery Address Link')=='Contact')) {
			$address_comment='<span style="font-weight:600">'._('Same as contact address').'</span>';

		}

		else {
			$address_comment=$subject_object->delivery_address_xhtml();
		}




	}




	$response=array(
		'state'=>200,
		'action'=>'deleted',
		'key'=>'','main_address_data'=>$main_address_data,
		'xhtml_main_address'=>$main_address->display('xhtml'),
		'xhtml_delivery_address'=>$address_main_delivery,
		'xhtml_delivery_address_bis'=>$address_comment,
		'address_main_delivery_key'=>$address_main_delivery_key,
		'xhtml_billing_address'=>$billing_address,
		'address_main_billing_key'=>$address_main_billing_key


	);



	//  $response=array('state'=>200,'action'=>$action,'msg'=>$msg,'address_key'=>$address_key);


	echo json_encode($response);
}


function delete_company_area($data) {
	include_once 'class.CompanyArea.php';
	global $editor;
	$subject=new CompanyArea($data['id']);
	if (!$subject->id) {
		$response=array('state'=>400,'msg'=>'Area not found');
		echo json_encode($response);
		return;
	}
	$subject->editor=$editor;
	$subject->delete();
	if ($subject->deleted) {
		$action='deleted';
		$msg=_('Area deleted');

	} else {
		$action='nochage';
		$msg=_('Area could not be deleted');
	}
	$response=array('state'=>200,'action'=>$action);
	echo json_encode($response);
}

function delete_company_department($data) {
	include_once 'class.CompanyDepartment.php';
	global $editor;
	$subject=new CompanyDepartment($data['id']);
	if (!$subject->id) {
		$response=array('state'=>400,'msg'=>'Department not found');
		echo json_encode($response);
		return;
	}
	$subject->editor=$editor;
	$subject->delete();
	if ($subject->deleted) {
		$action='deleted';
		$msg=_('Department deleted');

	} else {
		$action='nochage';
		$msg=_('Department could not be deleted');
	}
	$response=array('state'=>200,'action'=>$action);
	echo json_encode($response);
}



function edit_company2() {
	$company=new Company($_REQUEST['id']);
	$company->update($_REQUEST['key'],stripslashes(urldecode($_REQUEST['newvalue'])),stripslashes(urldecode($_REQUEST['oldvalue'])));

	if ($company->updated) {
		$response= array('state'=>200,'newvalue'=>$company->newvalue,'key'=>$_REQUEST['key']);

	} else {
		$response= array('state'=>400,'msg'=>$company->msg,'key'=>$_REQUEST['key']);
	}
	echo json_encode($response);
}
function new_company($data) {
	//Timer::timing_milestone('begin');
	global $editor;
	$data['editor']=$editor;

	$company=new Company('find create',$data['values']);
	if ($company->new) {
		$response= array('state'=>200,'action'=>'created','company_key'=>$company->id);
	} else {
		if ($company->found)
			$response= array('state'=>400,'action'=>'found','company_key'=>$company->found_key);
		else
			$response= array('state'=>400,'action'=>'error','company_key'=>0,'msg'=>$company->msg);
	}

	//Timer::dump_profile();

	echo json_encode($response);

}

function convert_customer_to_company($data) {

	global $editor;

	if (!preg_match('/[a-z0-9]+/i',$data['company_name'])) {
		$response= array('state'=>400,'action'=>'error','msg'=>_('Invalid company name'));
		echo json_encode($response);
		return;

	}


	$customer=new Customer($data['customer_key']);
	if (!$customer->id) {
		$response= array('state'=>400,'action'=>'error','msg'=>'customer not found');
		echo json_encode($response);
		return;
	}

	if ($customer->data['Customer Type']=='Company') {
		$response= array('state'=>400,'action'=>'error','msg'=>_('Customer is already a company'));
		echo json_encode($response);
		return;

	}

	$contact_key=$customer->data['Customer Main Contact Key'];
	$address_key=$customer->data['Customer Main Address Key'];

	$company_data=array(
		'Company Name'=>$data['company_name'],
		'Company Tax Number'=>$customer->data['Customer Tax Number']
	);
	$company=new Company();
	$company->create($company_data,array(),'use contact '.$contact_key.' use address '.$address_key);




	$sql=sprintf('update `Customer Dimension` set `Customer Type`="Company"  where `Customer Key`=%d',

		$customer->id

	);

	mysql_query($sql);
	$customer->create_company_bridge($company->id);


	$history_data=array(
		'History Abstract'=>_('Customer set up as Company'),
		'History Details'=>_trim(_('Customer now known as')." ".$company->data['Company Name']),
		'Action'=>'edited'
	);
	$customer->add_customer_history($history_data);
	$response= array('state'=>200,'action'=>'changed','name'=>$company->data['Company Name']);
	echo json_encode($response);


}


function convert_customer_to_person($data) {

	global $editor;




	$customer=new Customer($data['customer_key']);
	if (!$customer->id) {
		$response= array('state'=>400,'action'=>'error','msg'=>'customer not found');
		echo json_encode($response);
		return;
	}

	if ($customer->data['Customer Type']=='Person') {
		$response= array('state'=>400,'action'=>'error','msg'=>_('Customer is already a person'));
		echo json_encode($response);
		return;

	}
	$contact=new contact ($customer->data['Customer Main Contact Key']);
	$company=new Company($customer->data['Customer Company Key']);
	$company_customer_keys=$company->get_parent_keys('Customer');
	$company_supplier_keys=$company->get_parent_keys('Supplier');
	$company_account_keys=$company->get_parent_keys('Account');

	unset($company_customer_keys[$customer->id]);


	$sql=sprintf('delete from `Company Bridge` where `Subject Type`="Customer" and `Subject Key`=%d',
		$customer->id
	);
	mysql_query($sql);

	$sql=sprintf('update `Customer Dimension` set `Customer Type`="Person", `Customer Company Key`=0 ,`Customer Name`=%s,`Customer File As`=%s,`Customer Company Name`="" where `Customer Key`=%d',
		prepare_mysql($contact->display('name')),
		prepare_mysql( $contact->display('file as')),
		$customer->id
	);
	mysql_query($sql);


	if (count($company_customer_keys)==0 and count($company_supplier_keys)==0  and count($company_account_keys)==0) {
		$company->delete();
	}





	$history_data=array(
		'History Abstract'=>_('Customer set up as a person'),
		'History Details'=>_trim(_('Customer now known as')." ".$contact->display('name')),
		'Action'=>'edited'
	);
	$customer->add_customer_history($history_data);
	$response= array('state'=>200,'action'=>'changed','name'=>$contact->display('name'));
	echo json_encode($response);


}


function clone_customer($data) {

	global $editor,$user;

	if (!in_array($data['scope']['store_key'],$user->stores)) {
		$response= array('state'=>400,'action'=>'error','msg'=>_('Forbidden operation'));
		echo json_encode($response);
		return;

	}

	$customer=new Customer($data['customer_key']);

	if (!$customer->id) {
		$response= array('state'=>400,'action'=>'error','msg'=>'customer not found');
		echo json_encode($response);
		return;
	}

	if ($customer->data['Customer Store Key']==$data['scope']['store_key']) {

		$response= array('state'=>400,'action'=>'error','msg'=>'customer in same store');
		echo json_encode($response);
		return;
	}

	$customer_data=array(
		'Customer Type'=>$customer->data['Customer Type'],
		'Customer Company Key'=>$customer->data['Customer Company Key'],
		'Customer Main Contact Key'=>$customer->data['Customer Main Contact Key'],
		'Customer Store Key'=>$data['scope']['store_key']
	);
	$customer=new Customer();
	$customer->editor=$editor;
	$customer->create($customer_data);



	if ($customer->new) {
		$store=new Store($customer->data['Customer Store Key']);


		$customer->update_orders();

		$customer->update_activity();
		$store->update_customers_data();

		$response= array('state'=>200,'action'=>'created','customer_key'=>$customer->id);





	} else {

		$response= array('state'=>400,'action'=>'error','customer_key'=>0,'msg'=>$customer->msg);
	}

	//Timer::dump_profile();

	echo json_encode($response);


	//print_r($data);

}







function new_customer($data) {
	include_once 'edit_customers_functions.php';

	global $editor,$user;
	/*
    if (!in_array($data['values']['Customer Store Key'],$user->stores)) {
        $response= array('state'=>400,'action'=>'error','msg'=>_('Forbidden operation'));
        echo json_encode($response);
        return;
    }
*/



	if ($data['values']['Customer Address Country Code']=='')
		$data['values']['Customer Address Country Code']='UNK';

	$data['values']['editor']=$editor;

	$response=add_customer($data['values']) ;
	echo json_encode($response);
}






function new_corporation($data) {
	//Timer::timing_milestone('begin');
	global $editor;

	$company=new Company('find create',$data['values']);

	if (!$company->id) {
		$response= array('state'=>400,'action'=>'error','company_key'=>0,'msg'=>$company->msg);
		echo json_encode($response);
		exit;
	}


	$sql=sprintf("insert into `Account Dimension` (`Account Name`,`Account Company Key`) values (%s,%d)"
		,prepare_mysql($company->data['Company Name'])
		,$company->id
	);
	mysql_query($sql);

	$response= array('state'=>200,'action'=>'created','company_key'=>$company->id);


	echo json_encode($response);

}

function new_contact($data) {

	global $editor;
	$contact_data=array();
	foreach ($data['value'] as $key=>$values) {

		if ($key=='Contact_Name_Components') {
			$tmp=array();
			foreach ($values as $_key=>$_values) {
				$tmp[preg_replace('/\_/',' ',$_key)]=$_values;
			}
			$values=$tmp;
		}
		$contact_data[preg_replace('/\_/',' ',$key)]=$values;

	}

	switch ($data['subject']) {
	case('Company'):
		$company=new Company($data['subject_key']);
		$contact=new Contact('find create',$contact_data);
		$company->create_contact_bridge($contact->id);
		break;
	default:
		$contact=new Contact('find create',$contact_data);

	}



	if ($contact->new) {
		$response= array('state'=>200,'action'=>'created','contact_key'=>$contact->id);
	} else {
		if ($contact->found)
			$response= array('state'=>400,'action'=>'found','contact_key'=>$contact->found_key);
		else
			$response= array('state'=>400,'action'=>'error','contact_key'=>0,'msg'=>$contact->msg);
	}

	//Timer::dump_profile();

	echo json_encode($response);

}



function edit_customer($data) {

	$customer=new customer($data['customer_key']);
	if (!$customer->id) {
		$response= array('state'=>400,'msg'=>'Customer not found','key'=>$data['key']);
		echo json_encode($response);

		exit;
	}
	$values=array();
	foreach ($data['values'] as $value_key=>$value_data) {
		if ($value_data['value']=='') {
			$values[$value_key]=$value_data;
			unset($data['values'][$value_key]);
		}
	}

	foreach ($data['values'] as $value_key=>$value_data) {

		$values[$value_key]=$value_data;

	}


	$responses=array();
	foreach ($values as $key=>$values_data) {

		$responses[]=edit_customer_field($customer->id,$key,$values_data);
	}

	if (isset($data['submit']))
		return $responses;

	echo json_encode($responses);


}

function edit_customer_field($customer_key,$key,$value_data) {

	//print_r($value_data);
	//print "$customer_key,$key,$value_data ***";
	$customer=new customer($customer_key);
	$other_email_deleted=false;
	$other_email_added=false;




	$other_telephone_deleted=false;
	$other_telephone_added=false;

	$other_fax_deleted=false;
	$other_fax_added=false;

	$other_mobile_deleted=false;
	$other_mobile_added=false;

	$other_label=false;


	global $editor;
	$customer->editor=$editor;





	$key_dic=array(
		'fiscal_name'=>'Customer Fiscal Name',
		'registration_number'=>'Customer Registration Number',
		'name'=>'Customer Name',
		'email'=>'Customer Main Plain Email',
		'other_email'=>'Add Other Email',
		'other_telephone'=>'Add Other Telephone',
		'other_fax'=>'Add Other FAX',
		'other_mobile'=>'Add Other Mobile',
		'telephone'=>'Customer Main Plain Telephone',
		'mobile'=>'Customer Main Plain Mobile',
		'fax'=>'Customer Main Plain FAX',
		'contact'=>'Customer Main Contact Name',
		'contact_name'=>'Customer Main Contact Name',

		"address"=>'Address',
		"town"=>'Main Address Town',
		"tax_number"=>'Customer Tax Number',
		"postcode"=>'Main Address Town',
		"region"=>'Main Address Town',
		"country"=>'Main Address Country',
		"ship_address"=>'Main Ship To',
		"ship_town"=>'Main Ship To Town',
		"ship_postcode"=>'Main Ship To Postal Code',
		"ship_region"=>'Main Ship To Country Region',
		"ship_country"=>'Main Ship To Country',
		"sticky_note"=>'Customer Sticky Note',
		"new_sticky_note"=>'Customer Sticky Note',
		"preferred_contact_number"=>'Customer Preferred Contact Number',
		"company_name"=>'Customer Company Name',
		"web"=>'Customer Website'
	);





	if (array_key_exists($key,$key_dic))
		$key=$key_dic[$key];

	$the_new_value=_trim(strip_tags($value_data['value']));



	if (preg_match('/^email\d+$/i',$key)) {
		$email_id=preg_replace('/^email/','',$key);




		$customer->update_other_email($email_id,$the_new_value);

		if ($the_new_value=='') {
			$other_email_deleted=true;
		}

	}
	elseif (preg_match('/^email_label\d+$/i',$key)) {
		$email_id=preg_replace('/^email_label/','',$key);
		$customer->update_other_email_label($email_id,$the_new_value);
		$other_label=true;
		$other_label_scope='email';
		$other_label_scope_key=$email_id;
	}
	elseif (preg_match('/^telephone_label\d+$/i',$key)) {
		$telecom_id=preg_replace('/^telephone_label/','',$key);
		$customer->update_other_telecom_label('Telephone',$telecom_id,$the_new_value);
		$other_label=true;
		$other_label_scope='telephone';
		$other_label_scope_key=$telecom_id;
	}
	elseif (preg_match('/^mobile_label\d+$/i',$key)) {
		$telecom_id=preg_replace('/^mobile_label/','',$key);
		$customer->update_other_telecom_label('Mobile',$telecom_id,$the_new_value);
		$other_label=true;
		$other_label_scope='mobile';
		$other_label_scope_key=$telecom_id;
	}
	elseif (preg_match('/^fax_label\d+$/i',$key)) {
		$telecom_id=preg_replace('/^fax_label/','',$key);
		$customer->update_other_telecom_label('FAX',$telecom_id,$the_new_value);
		$other_label=true;
		$other_label_scope='fax';
		$other_label_scope_key=$telecom_id;
	}
	elseif (preg_match('/^telephone\d+$/i',$key)) {
		$telephone_id=preg_replace('/^telephone/','',$key);
		$customer->update_other_telephone($telephone_id,$the_new_value);

		if ($the_new_value=='') {
			$other_telephone_deleted=true;
		}

	}
	elseif (preg_match('/^fax\d+$/i',$key)) {
		$fax_id=preg_replace('/^fax/','',$key);
		$customer->update_other_fax($fax_id,$the_new_value);

		if ($the_new_value=='') {
			$other_fax_deleted=true;
		}

	}
	elseif (preg_match('/^mobile\d+$/i',$key)) {


		$mobile_id=preg_replace('/^mobile/','',$key);
		$customer->update_other_mobile($mobile_id,$the_new_value);

		if ($the_new_value=='') {
			$other_mobile_deleted=true;

		}

	}
	elseif ($key=='Customer Fiscal Name') {
		$customer->update_fiscal_name($the_new_value );
	}
	elseif ($key=='Customer Tax Number') {
		$customer->update_tax_number($the_new_value);
	}
	// elseif ($key=='Customer Registration Number') {
	//    $customer->update_registration_number($the_new_value);
	// }
	elseif (preg_match('/^custom_field_customer/i',$key)) {
		$custom_id=preg_replace('/^custom_field_/','',$key);
		//print $key;
		$customer->update_custom_fields($key, $the_new_value);

	}
	else {
		// print "$customer_key,$key,$the_new_value ***";




		$customer->update(array($key=>$the_new_value));
	}


	if ($key=='Add Other Email') {
		$other_email_added=true;
	}
	elseif ($key=='Add Other Telephone') {
		$other_telephone_added=true;
	}
	elseif ($key=='Add Other FAX') {
		$other_fax_added=true;
	}
	elseif ($key=='Add Other Mobile') {
		$other_mobile_added=true;
	}






	if (!$customer->error ) {

		if ($other_email_deleted) {
			$response= array('state'=>200,'action'=>'other_email_deleted','newvalue'=>$customer->new_value,'key'=>$value_data['okey'],'email_key'=>$email_id,'warning_msg'=>$customer->warning_messages);
		}
		elseif ($other_mobile_deleted) {
			$response= array('state'=>200,'action'=>'other_mobile_deleted','newvalue'=>$customer->new_value,'key'=>$value_data['okey'],'mobile_key'=>$mobile_id,'warning_msg'=>$customer->warning_messages);
		}
		elseif ($other_fax_deleted) {
			$response= array('state'=>200,'action'=>'other_fax_deleted','newvalue'=>$customer->new_value,'key'=>$value_data['okey'],'fax_key'=>$fax_id,'warning_msg'=>$customer->warning_messages);
		}
		elseif ($other_telephone_deleted) {
			$response= array('state'=>200,'action'=>'other_telephone_deleted','newvalue'=>$customer->new_value,'key'=>$value_data['okey'],'telephone_key'=>$telephone_id,'warning_msg'=>$customer->warning_messages);
		}
		elseif ($other_email_added) {
			$response= array('state'=>200,'action'=>'other_email_added','newvalue'=>$customer->new_value,'key'=>$value_data['okey'],'new_email_key'=>$customer->new_email_key,'warning_msg'=>$customer->warning_messages);
		}
		elseif ($other_telephone_added) {
			$response= array('state'=>200,'action'=>'other_telephone_added','newvalue'=>$customer->new_value,'key'=>$value_data['okey'],'warning_msg'=>$customer->warning_messages);
		}
		elseif ($other_fax_added) {
			$response= array('state'=>200,'action'=>'other_fax_added','newvalue'=>$customer->new_value,'key'=>$value_data['okey'],'warning_msg'=>$customer->warning_messages);
		}
		elseif ($other_mobile_added) {
			$response= array('state'=>200,'action'=>'other_mobile_added','newvalue'=>$customer->new_value,'key'=>$value_data['okey'],'warning_msg'=>$customer->warning_messages);
		}
		elseif ($other_label) {
			$response= array('state'=>200,'action'=>'updated','newvalue'=>$customer->new_value,'key'=>$value_data['okey'],'scope_key'=>$other_label_scope_key,'scope'=>$other_label_scope,'warning_msg'=>$customer->warning_messages);
		}
		else {
			if ($customer->updated)
				$response= array('state'=>200,'action'=>'updated','newvalue'=>$customer->new_value,'key'=>$value_data['okey'],'warning_msg'=>$customer->warning_messages,'warning_msg'=>$customer->warning_messages);
			else
				$response= array('state'=>200,'action'=>'nochange','newvalue'=>$customer->new_value,'key'=>$value_data['okey'],'warning_msg'=>$customer->warning_messages,'warning_msg'=>$customer->warning_messages);

		}
	} else {

		$response= array('state'=>400,'msg'=>$customer->msg,'key'=>$value_data['okey'], 'warning_msg'=>$customer->warning_messages);
	}

	return $response;

}


function send_customer_send_post($data) {

	$send_post_key=$data['send_post_key'];
	$date=gmdate("Y-m-d H:i:s");


	$sql=sprintf("update `Customer Send Post` set `Send Post Status`='Send',`Date Send`=%s where `Customer Send Post Key`=%d ",
		prepare_mysql($date),
		$send_post_key

	);

	$query=mysql_query($sql);
	if ($query) {
		$response= array('state'=>200);

	} else {
		$response= array('state'=>400,'msg'=>"error");
	}
	echo json_encode($response);

}

function cancel_customer_send_post($data) {

	$send_post_key=$data['send_post_key'];



	$sql=sprintf("update `Customer Send Post` set `Send Post Status`='Cancelled' where `Customer Send Post Key`=%d ",
		$send_post_key

	);

	$query=mysql_query($sql);
	if ($query) {
		$response= array('state'=>200);

	} else {
		$response= array('state'=>400,'msg'=>"error");
	}
	echo json_encode($response);

}


function add_customer_send_post($data) {
	$date=gmdate("Y-m-d H:i:s");
	$customer_key=$data['customer_key'];
	$post_type=$data['post_type'];


	$sql=sprintf("select `Customer Key` from  `Customer Send Post` where `Customer Key`=%d and `Send Post Status`='To Send' and `Post Type`=%s ",
		$customer_key,

		prepare_mysql($post_type)
	);

	$res=mysql_query($sql);
	if (!$row=mysql_fetch_assoc($res)) {

		$sql=sprintf("insert into `Customer Send Post` (`Customer Key`,`Send Post Status`,`Date Creation`,`Post Type`) values (%d,'To Send',%s,%s)",
			$customer_key,
			prepare_mysql($date),
			prepare_mysql($post_type)
		);

		$query=mysql_query($sql);
		if ($query) {
			$response= array('state'=>200);

		} else {
			$response= array('state'=>400,'msg'=>"Not Added To Send Post queue");
		}

	} else {
		$response= array('state'=>200);
	}

	echo json_encode($response);

}

function list_customers() {


	global $myconf;


	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		return;
	}
	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		return;
	}



	switch ($parent) {
	case 'store':
		$conf_table='customers';
		break;
	case 'category':
		$conf_table='customer_categories';
		break;
	case 'list':
		$conf_table='customers_list';
		break;
	}

	$conf=$_SESSION['state'][$conf_table]['edit_customers'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))
		$awhere=$_REQUEST['where'];
	else
		$awhere=$conf['where'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	if (isset( $_REQUEST['elements']))
		$elements=$_REQUEST['elements'];
	else
		$elements=$conf['elements'];

	if (isset( $_REQUEST['elements_Active'])) {
		$elements['activity']['Active']=$_REQUEST['elements_Active'];
	}


	if (isset( $_REQUEST['elements_Lost'])) {
		$elements['activity']['Lost']=$_REQUEST['elements_Lost'];
	}


	if (isset( $_REQUEST['elements_Losing'])) {
		$elements['activity']['Losing']=$_REQUEST['elements_Losing'];
	}

	if (isset( $_REQUEST['elements_Normal'])) {
		$elements['level_type']['Normal']=$_REQUEST['elements_Normal'];
	}


	if (isset( $_REQUEST['elements_Partner'])) {
		$elements['level_type']['Partner']=$_REQUEST['elements_Partner'];
	}


	if (isset( $_REQUEST['elements_VIP'])) {
		$elements['level_type']['VIP']=$_REQUEST['elements_VIP'];
	}
	if (isset( $_REQUEST['elements_Staff'])) {
		$elements['level_type']['Staff']=$_REQUEST['elements_Staff'];
	}

	if (isset( $_REQUEST['elements_type'])) {
		$elements_type=$_REQUEST['elements_type'];
	}else {
		$elements_type=$_SESSION['state'][$conf_table]['edit_customers']['elements_type'];
	}
	if (isset( $_REQUEST['orders_type'])) {
		$orders_type=$_REQUEST['orders_type'];
	}else {
		$orders_type=$_SESSION['state'][$conf_table]['edit_customers']['orders_type'];
	}


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_SESSION['state'][$conf_table]['edit_customers']['order']=$order;
	$_SESSION['state'][$conf_table]['edit_customers']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_table]['edit_customers']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['edit_customers']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['edit_customers']['where']=$awhere;
	$_SESSION['state'][$conf_table]['edit_customers']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['edit_customers']['f_value']=$f_value;


	$filter_msg='';

	include_once 'splinters/customers_prepare_list.php';


	$sql="select count(Distinct C.`Customer Key`) as total from $table   $where $wheref $where_type";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(Distinct C.`Customer Key`) as total_without_filters from $table  $where  $where_type";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);


	$rtext=number($total_records)." ".ngettext('customer','customers',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';



	//if($total_records>$number_results)
	// $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('customer name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer like")." <b>$f_value</b> ";
			break;
		case('postcode'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with postcode like")." <b>$f_value</b> ";
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer based in").$find_data;
			break;
		case('id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with ID like")." <b>$f_value</b> ";
			break;
		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."> <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
			break;
		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."< <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."< <b>".money($f_value,$currency)."</b> ";
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."> <b>".money($f_value,$currency)."</b> ";
			break;


		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('customer name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with name like')." <b>*".$f_value."*</b>";
			break;
		case('id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with ID  like')." <b>".$f_value."*</b>";
			break;
		case('postcode'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with postcode like')." <b>".$f_value."*</b>";
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('based in').$find_data;
			break;
		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."> ".number($f_value)."  ".ngettext('day','days',$f_value);
			break;
		case('last_less'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."< ".number($f_value)."  ".ngettext('day','days',$f_value);
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."< ".money($f_value,$currency);
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."> ".money($f_value,$currency);
			break;
		}
	}
	else
		$filter_msg='';





	$_order=$order;
	$_dir=$order_direction;

	if ($order=='name')
		$order='`Customer File As`';
	elseif ($order=='id')
		$order='`Customer Key`';
	elseif ($order=='location')
		$order='`Customer Main Location`';
	elseif ($order=='orders')
		$order='`Customer Orders`';
	elseif ($order=='email')
		$order='`Customer Main Plain Email`';
	elseif ($order=='telephone')
		$order='`Customer Main Telehone`';
	elseif ($order=='last_order')
		$order='`Customer Last Order Date`';
	elseif ($order=='contact_name')
		$order='`Customer Main Contact Name`';
	elseif ($order=='address')
		$order='`Customer Main Location`';
	elseif ($order=='town')
		$order='`Customer Main Town`';
	elseif ($order=='postcode')
		$order='`Customer Main Postal Code`';
	elseif ($order=='region')
		$order='`Customer Main Country First Division`';
	elseif ($order=='country')
		$order='`Customer Main Country`';
	//  elseif($order=='ship_address')
	//  $order='`customer main ship to header`';
	elseif ($order=='ship_town')
		$order='`Customer Main Delivery Address Town`';
	elseif ($order=='ship_postcode')
		$order='`Customer Main Delivery Address Postal Code`';
	elseif ($order=='ship_region')
		$order='`Customer Main Delivery Address Country Region`';
	elseif ($order=='ship_country')
		$order='`Customer Main Delivery Address Country`';
	elseif ($order=='net_balance')
		$order='`Customer Net Balance`';
	elseif ($order=='balance')
		$order='`Customer Outstanding Net Balance`';
	elseif ($order=='total_profit')
		$order='`Customer Profit`';
	elseif ($order=='total_payments')
		$order='`Customer Net Payments`';
	elseif ($order=='top_profits')
		$order='`Customer Profits Top Percentage`';
	elseif ($order=='top_balance')
		$order='`Customer Balance Top Percentage`';
	elseif ($order=='top_orders')
		$order='``Customer Orders Top Percentage`';
	elseif ($order=='top_invoices')
		$order='``Customer Invoices Top Percentage`';
	elseif ($order=='total_refunds')
		$order='`Customer Total Refunds`';

	elseif ($order=='activity')
		$order='`Customer Type by Activity`';

	$sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds`  from $table   $where $wheref $where_type  $group_by order by $order $order_direction limit $start_from,$number_results";

	$adata=array();



	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$id="<a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer Key']).'</a>';

		if ($data['Customer Orders']==0)
			$last_order_date='';
		else
			$last_order_date=strftime("%e %b %y", strtotime($data['Customer Last Order Date']." +00:00"));

		$contact_since=strftime("%e %b %y", strtotime($data['Customer First Contacted Date']." +00:00"));


		switch ($data['Customer Type by Activity']) {
		case 'Inactive':
			$activity=_('Lost');
			break;
		case 'Active':
			$activity=_('Active');
			break;
		case 'Prospect':
			$activity=_('Prospect');
			break;
		default:
			$activity=$data['Customer Type by Activity'];
			break;
		}

		$checkbox_unchecked=sprintf('<img src="art/icons/checkbox_unchecked.png" style="width:14px;cursor:pointer" checked=0  id="assigned_subject_%d" onClick="check_assigned_subject(%d)"/>',
			$data['Customer Key'],
			$data['Customer Key']
		);
		$checkbox_checked=sprintf('<img src="art/icons/checkbox_checked.png" style="width:14px;cursor:pointer" checked=1  id="assigned_subject_%d" onClick="check_assigned_subject(%d)"/>',
			$data['Customer Key'],
			$data['Customer Key']
		);

		$adata[]=array(
			'checkbox'=>'',
			'checkbox_checked'=>$checkbox_checked,
			'checkbox_unchecked'=>$checkbox_unchecked,
			'id'=>$id,
			'customer_key'=>$data['Customer Key'],
			'activity'=>$activity,
			'orders'=>number($data['Customer Orders']),
			'last_order'=>$last_order_date,
			'contact_since'=>$contact_since,
			'customer_key'=>$data['Customer Key'],
			'name'=>$data['Customer Name'],
			'email'=>$data['Customer Main Plain Email'],
			'telephone'=>$data['Customer Main XHTML Telephone'],
			'contact_name'=>$data['Customer Main Contact Name'],
			'address'=>$data['Customer Main Location'],
			'town'=>$data['Customer Main Town'],
			'postcode'=>$data['Customer Main Postal Code'],
			'region'=>$data['Customer Main Country First Division'],
			'country'=>$data['Customer Main Country'],

			'ship_town'=>$data['Customer Main Delivery Address Town'],
			'ship_postcode'>$data['Customer Main Delivery Address Postal Code'],
			'ship_region'=>$data['Customer Main Delivery Address Region'],
			'ship_country'=>$data['Customer Main Delivery Address Country'],

			// 'go'=>sprintf("<a href='edit_customer.php?id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$data['Customer Key'])

		);
	}
	mysql_free_result($result);




	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total
		)
	);
	echo json_encode($response);
}


function list_company_areas() {
	$conf=$_SESSION['state']['company_areas']['table'];
	if (isset( $_REQUEST['view']))
		$view=$_REQUEST['view'];
	else
		$view=$_SESSION['state']['company_areas']['view'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;




	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent=$conf['parent'];

	if (isset( $_REQUEST['mode']))
		$mode=$_REQUEST['mode'];
	else
		$mode=$conf['mode'];

	if (isset( $_REQUEST['restrictions']))
		$restrictions=$_REQUEST['restrictions'];
	else
		$restrictions=$conf['restrictions'];




	$_SESSION['state']['company_areas']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
		,'mode'=>$mode,'restrictions'=>'','parent'=>$parent
	);




	$group='';





	$filter_msg='';

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	//  if(!is_numeric($start_from))
	//        $start_from=0;
	//      if(!is_numeric($number_results))
	//        $number_results=25;


	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='company name' and $f_value!='')
		$wheref.=" and  `Company Name` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='email' and $f_value!='')
		$wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from `Company Area Dimension`  $where $wheref   ";
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Company Area Dimension`  $where   ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}
	mysql_free_result($res);

	$rtext=number($total_records)." ".ngettext('company area','company areas',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
			break;
		case('email'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with name like')." <b>".$f_value."*</b>";
			break;
		case('email'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with email like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;
	$order='`Company Area Name`';

	if ($order=='code')
		$order='`Company Area Code`';



	$sql="select  * from `Company Area Dimension` P  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();

	// print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		if ($row['Company Area Number Employees']>0) {
			$delete='';
		} else {
			$delete='<img src="art/icons/delete.png"/>';
		}

		$adata[]=array(


			'id'=>$row['Company Area Key']

			,'go'=>sprintf("<a href='company_area.php?edit=1&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Company Area Key'])

			,'code'=>$row['Company Area Code']
			,'name'=>$row['Company Area Name']
			,'delete'=>$delete
			,'delete_type'=>'delete'
		);
	}
	mysql_free_result($res);


	// $total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);




	echo json_encode($response);

}





function new_company_area($data) {
	global $editor;
	$company=new Company($data['parent_key']);
	$company->editor=$editor;
	if ($company->id) {
		$company->add_area($data['values']);
		if ($company->updated) {
			$response= array('state'=>200,'action'=>'created');

		} else {
			$response= array('state'=>400,'action'=>'error','company_key'=>0,'msg'=>$company->msg);

		}

	} else {
		$response= array('state'=>400,'action'=>'error','company_key'=>0,'msg'=>$company->msg);

	}
	echo json_encode($response);

}



function new_company_department($data) {

	global $editor;
	$company=new Company($data['parent_key']);
	$company->editor=$editor;




	if ($company->id) {
		$company->add_department($data['values']);
		if ($company->updated) {
			$response= array('state'=>200,'action'=>'created');

		} else {
			$response= array('state'=>400,'action'=>'error','company_key'=>0,'msg'=>$company->msg);

		}

	} else {
		$response= array('state'=>400,'action'=>'error','company_key'=>0,'msg'=>$company->msg);

	}
	echo json_encode($response);

}

function new_employee($data) {
	include_once 'class.Staff.php';

	global $editor;
	$staff_data=array();
	foreach ($data['values'] as $key=>$value) {
		$staff_data[preg_replace('/^company /i','Staff ',$key)]=$value;
	}

	$staff_data['editor']=$editor;
	//print_r($supplier_data);
	//return;

	$staff=new Staff('find',$staff_data,'create');
	if ($staff->new) {
		$response= array('state'=>200,'action'=>'created','staff_key'=>$staff->id);
	} else {
		if ($staff->found)
			$response= array('state'=>400,'action'=>'found','staff_key'=>$staff->found_key);
		else
			$response= array('state'=>400,'action'=>'error','staff_key'=>0,'msg'=>$staff->msg);
	}


	echo json_encode($response);

}





function new_company_position($data) {
	global $editor;
	$company=new Company($data['parent_key']);
	$company->editor=$editor;
	if ($company->id) {
		$company->add_position($data['values']);
		if ($company->updated) {
			$response= array('state'=>200,'action'=>'created');

		} else {
			$response= array('state'=>400,'action'=>'error','company_key'=>0,'msg'=>$company->msg);

		}

	} else {
		$response= array('state'=>400,'action'=>'error','company_key'=>0,'msg'=>$company->msg);

	}
	echo json_encode($response);

}





function edit_company_area($data) {
	include_once 'class.CompanyArea.php';
	global $editor;


	$company_area=new CompanyArea($data['id']);
	$company_area->editor=$editor;

	if (!$company_area->id) {
		$response=array('state'=>400,'msg'=>_('Company Area not found'));
		echo json_encode($response);
		return;
	}

	$translator=array(
		'name'=>'Company Area Name'
		,'code'=>'Company Area Code'
		,'description'=>'Company Area Description'

	);

	if (array_key_exists($data['key'], $translator)) {

		$update_data=array(

			$translator[$data['key']]=>$data['newvalue']
		);
		//print_r($update_data);
		$company_area->update($update_data);

		if ($company_area->error_updated) {
			$response=array('state'=>200,'action'=>'error','msg'=>$company_area->msg_updated,'key'=>$_REQUEST['key']);
		} else {

			if ($company_area->updated) {
				$response=array('state'=>200,'action'=>'updated','msg'=>$company_area->msg_updated,'key'=>$_REQUEST['key'],'newvalue'=>$company_area->new_value);
			} else {
				$response=array('state'=>200,'action'=>'nochange','msg'=>$company_area->msg_updated,'key'=>$_REQUEST['key']);

			}

		}


	} else {
		$response=array('state'=>400,'msg'=>'Key not in Scope');
	}
	echo json_encode($response);

}


function delete_customer($data) {
	global $editor,$myconf;

	$customer=new Customer($data['customer_key']);
	$customer->editor=$editor;
	// print_r($customer->editor);
	// exit;
	if ($customer->id) {
		$customer->delete('',$myconf['customer_id_prefix']);
		if ($customer->deleted) {
			$response=array('state'=>200,'action'=>'deleted','msg'=>$customer->msg);
			echo json_encode($response);
			exit;
		} else {
			$response=array('state'=>400,'action'=>'nochange','msg'=>$customer->msg);
			echo json_encode($response);
			exit;
		}
	}
	$response=array('state'=>400,'action'=>'error','msg'=>'Error');
	echo json_encode($response);
	exit;

}

function delete_contact($data) {
	$contact=new Contact($data['contact_key']);
	if ($contact->id) {
		$contact->delete();
		if ($contact->deleted) {
			$response=array('state'=>200,'action'=>'deleted','msg'=>$contact->msg);
			echo json_encode($response);
			exit;
		} else {
			$response=array('state'=>400,'action'=>'nochange','msg'=>$contact->msg);
			echo json_encode($response);
			exit;
		}
	}
	$response=array('state'=>400,'action'=>'error','msg'=>'Error');
	echo json_encode($response);
	exit;

}




function edit_corporation($data) {
	include_once 'class.Account.php';
	$corporation=new Account();
	$corporation->update(array($data['key']=>$data['newvalue']));
	if ($corporation->updated) {
		$response= array('state'=>200,'newvalue'=>$corporation->new_value,'key'=>$_REQUEST['okey']);

	} else {
		$response= array('state'=>400,'msg'=>$corporation->msg,'key'=>$_REQUEST['okey']);
	}
	echo json_encode($response);

}


function edit_company_department() {
	include_once 'class.CompanyDepartment.php';

	$key=$_REQUEST['key'];


	$company_department=new CompanyDepartment($_REQUEST['department_key']);
	global $editor;
	$company_department->editor=$editor;

	if ($key=='Attach') {
		// print_r($_FILES);
		$note=stripslashes(urldecode($_REQUEST['newvalue']));
		$target_path = "uploads/".'attach_'.date('U');
		$original_name=$_FILES['testFile']['name'];
		$type=$_FILES['testFile']['type'];
		$data=array('Caption'=>$note,'Original Name'=>$original_name,'Type'=>$type);

		if (move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path )) {
			$company_department->add_attach($target_path,$data);

		}
	} else {



		$key_dic=array(
			'name'=>'Company Department Name'
			,'code'=>'Company Department Code'
			,'description'=>'Company Department Description'
			// ,'type'=>'Staff Type'


		);
		if (array_key_exists($_REQUEST['key'],$key_dic))
			$key=$key_dic[$_REQUEST['key']];

		$update_data=array($key=>stripslashes(urldecode($_REQUEST['newvalue'])));
		$company_department->update($update_data);
	}


	if ($company_department->updated) {
		$response= array('state'=>200,'newvalue'=>$company_department->new_value,'key'=>$_REQUEST['key']);

	} else {
		$response= array('state'=>400,'msg'=>$company_department->msg,'key'=>$_REQUEST['key']);
	}
	echo json_encode($response);

}
function delete_customer_list($data) {
	global $user;
	$sql=sprintf("select `List Parent Key`,`List Key` from `List Dimension` where `List Key`=%d",$data['subject_key']);

	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {

		if (in_array($row['List Parent Key'],$user->stores)) {
			$sql=sprintf("delete from  `List Customer Bridge` where `List Key`=%d",$data['subject_key']);
			mysql_query($sql);
			$sql=sprintf("delete from  `List Dimension` where `List Key`=%d",$data['subject_key']);
			mysql_query($sql);
			
			
			$response=array('state'=>200,
			'action'=>'deleted',
			'table_id'=>(isset($data['table_id'])?$data['table_id']:''),
			'recordIndex'=>(isset($data['recordIndex'])?$data['recordIndex']:''),
		);
			
			echo json_encode($response);
			return;



		} else {
			$response=array('state'=>400,'msg'=>_('Forbidden Operation'));
			echo json_encode($response);
			return;
		}



	} else {
		$response=array('state'=>400,'msg'=>'Error no customer list');
		echo json_encode($response);
		return;

	}



}

function delete_post_to_send($data) {
	global $user;
	$sql=sprintf("select * from `Customer Send Post` where `Customer Send Post Key`=%d", $data['key']);

	$res=mysql_query($sql);

	if ($row=mysql_fetch_assoc($res)) {

		$sql=sprintf("delete from `Customer Send Post` where `Customer Send Post Key`=%d", $data['key']);
		$res=mysql_query($sql);

		$response=array('state'=>200,'action'=>'deleted');
		echo json_encode($response);
		return;

	} else {
		$response=array('state'=>400,'msg'=>'Error no send to post');
		echo json_encode($response);
		return;

	}



}

function edit_post_to_send($data) {
	global $user;
	$sql=sprintf("select * from `Customer Send Post` where `Customer Send Post Key`=%d", $data['key']);

	$res=mysql_query($sql);

	if ($row=mysql_fetch_assoc($res)) {

		$sql=sprintf("update `Customer Send Post` set `Send Post Status` = 'Send' where `Customer Send Post Key`=%d", $data['key']);
		$res=mysql_query($sql);

		$response=array('state'=>200,'action'=>'edited');
		echo json_encode($response);
		return;

	} else {
		$response=array('state'=>400,'msg'=>'Error no send to post');
		echo json_encode($response);
		return;

	}



}

function customer_merge($data) {
	global $user,$editor,$myconf;
	$customer=new Customer($data['customer_key']);
	$customer->editor=$editor;
	$customer_to_be_deleted=new Customer($data['merge_key']);
	$customer_to_be_deleted->editor=$editor;
	if (!$customer->id or !$customer_to_be_deleted->id) {
		$response=array('state'=>400,'msg'=>'Customer(s) not found');
		echo json_encode($response);
		return;
	}

	if (!in_array($customer->data['Customer Store Key'],$user->stores) or !in_array($customer_to_be_deleted->data['Customer Store Key'],$user->stores)) {
		$response=array('state'=>400,'msg'=>_('Forbidden operation'));
		echo json_encode($response);
		return;
	}

	$customer->merge($customer_to_be_deleted->id,$myconf['customer_id_prefix']);

	if ($customer->merged) {
		$response=array('state'=>200,'action'=>'merged');
		echo json_encode($response);
		return;
	} else {
		$response=array('state'=>400,'msg'=>$customer->msg);
		echo json_encode($response);
		return;

	}




}

function new_customers_list($data) {

	$list_name=$data['list_name'];
	$store_id=$data['store_id'];

	$sql=sprintf("select * from `List Dimension`  where `List Name`=%s and `List Parent Key`=%d ",
		prepare_mysql($list_name),
		$store_id
	);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$response=array('resultset'=>
			array(
				'state'=>400,
				'msg'=>_('Another list has the same name')
			)
		);
		echo json_encode($response);
		return;
	}

	$list_type=$data['list_type'];

	$awhere=$data['awhere'];

	include_once 'list_functions_customer.php';
	list($where,$table,$group)=customers_awhere($awhere);



	$sql="select count(Distinct C.`Customer Key`) as total from $table  $where";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		if ($row['total']==0) {
			$response=array('resultset'=>
				array(
					'state'=>400,
					'msg'=>_('No customer match this criteria')
				)
			);
			echo json_encode($response);
			return;

		}
		$list_total_items=$row['total'];

	}else {
		$response=array('resultset'=>
			array(
				'state'=>400,
				'msg'=>_('No customer match this criteria')
			)
		);
		echo json_encode($response);
		return;

	}


	mysql_free_result($res);

	$list_sql=sprintf("insert into `List Dimension` (`List Scope`,`List Parent Key`,`List Name`,`List Type`,`List Metadata`,`List Creation Date`,`List Number Items`) values ('Customer',%d,%s,%s,%s,NOW(),%d)",
		$store_id,
		prepare_mysql($list_name),
		prepare_mysql($list_type),
		prepare_mysql(json_encode($data['awhere'])),
		$list_total_items

	);
	mysql_query($list_sql);
	$customer_list_key=mysql_insert_id();

	if ($list_type=='Static') {


		$sql="select C.`Customer Key` from $table  $where $group";
		//   print $sql;
		$result=mysql_query($sql);
		while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$customer_key=$data['Customer Key'];
			$sql=sprintf("insert into `List Customer Bridge` (`List Key`,`Customer Key`) values (%d,%d)",
				$customer_list_key,
				$customer_key
			);
			mysql_query($sql);

		}
		mysql_free_result($result);




	}




	$response=array(
		'state'=>200,
		'customer_list_key'=>$customer_list_key

	);
	echo json_encode($response);

}

function create_custom_field($data) {

	//print_r ($data['values']);
	$custom_field = new Customfield('find', $data['values'], 'create');

	//print_r ($custom_field);
	if ($custom_field->new) {
		return;

	}elseif ($custom_field->error) {
		$response=array('state'=>400,'msg'=>$custom_field->msg);
		echo json_encode($response);
		return;


	}
}

function create_email_field($data) {
	$info=$data['values'];
	//print_r($info);
	$sql=sprintf("select * from `Email Credentials` where `Store Key`=%d and `Email Address`='%s'", $data['parent_key'], $info['Email Address']);
	//print $sql;
	$result=mysql_query($sql);
	if (mysql_fetch_array($result)) {
		$response=array(
			'state'=>450,
			'msg'=>'Email Exist'

		);
	} else {
		$sql=sprintf("insert into `Email Credentials` (`Store Key`,`Store Scope`,`Email Address`,`Password`,`Incoming Mail Server`,`Outgoing Mail Sever`) values (%d, '%s', '%s', '%s', '%s', '%s')"
			,$data['parent_key']
			,$data['parent']
			,$info['Email Address']
			,$info['Password']
			,$info['Incoming Mail Server']
			,$info['Outgoing Mail Server']
		);

		if (mysql_query($sql)) {
			$response=array(
				'state'=>200,
				'msg'=>'Email Added'
			);


		}
		//print $sql;

	}
	echo json_encode($response);

}




function delete_all_customers_in_list($data) {

	global $editor,$myconf;
	$list_key=$data['list_key'];
	$store_key=$data['store_key'];

	$wheref='';
	$where='where true';
	$table='`Customer Dimension` C ';
	$where_type='';


	$conf=$_SESSION['state']['customers']['edit_customers'];

	$f_field=$conf['f_field'];

	$f_value=$conf['f_value'];

	$deleted_customers=0;
	$total_customers=0;
	$group;
	$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$list_key);

	$res=mysql_query($sql);
	if ($customer_list_data=mysql_fetch_assoc($res)) {
		$awhere=false;
		if ($customer_list_data['List Type']=='Static') {
			$table='`List Customer Bridge` CB left join `Customer Dimension` C  on (CB.`Customer Key`=C.`Customer Key`)';
			$where_type=sprintf(' and `List Key`=%d ',$list_key);
		} else {
			$tmp=preg_replace('/\\\"/','"',$customer_list_data['List Metadata']);
			$tmp=preg_replace('/\\\\\"/','"',$tmp);
			$tmp=preg_replace('/\'/',"\'",$tmp);
			$raw_data=json_decode($tmp, true);
			$raw_data['store_key']=$store;
			include_once 'list_functions_customer.php';
			list($where,$table,$group)=customers_awhere($raw_data);
		}


		if (is_numeric($store_key)) {
			$where.=sprintf(' and `Customer Store Key`=%d ',$store_key);
		}

		if (($f_field=='customer name'     )  and $f_value!='') {
			$wheref="  and  `Customer Name` like '%".addslashes($f_value)."%'";
		}
		elseif (($f_field=='postcode'     )  and $f_value!='') {
			$wheref="  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
		}
		elseif ($f_field=='id'  )
			$wheref.=" and  `Customer Key` like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
		elseif ($f_field=='maxdesde' and is_numeric($f_value) )
			$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
		elseif ($f_field=='mindesde' and is_numeric($f_value) )
			$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
		elseif ($f_field=='max' and is_numeric($f_value) )
			$wheref.=" and  `Customer Orders`<=".$f_value."    ";
		elseif ($f_field=='min' and is_numeric($f_value) )
			$wheref.=" and  `Customer Orders`>=".$f_value."    ";
		elseif ($f_field=='maxvalue' and is_numeric($f_value) )
			$wheref.=" and  `Customer Net Balance`<=".$f_value."    ";
		elseif ($f_field=='minvalue' and is_numeric($f_value) )
			$wheref.=" and  `Customer Net Balance`>=".$f_value."    ";


		$sql="select C.`Customer Key` from $table   $where $wheref $where_type $group";
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			$customer=new Customer($row['Customer Key']);
			$customer->editor=$editor;
			if ($customer->id) {
				$customer->delete('',$myconf['customer_id_prefix']);

				$total_customers++;
				if ($customer->deleted) {
					$deleted_customers++;
				}
			}
		}
		$response= array('state'=>200,'number_deleted'=>$deleted_customers,'number_customers'=>$total_customers);
		echo json_encode($response);
		return;


	} else {
		$response= array('state'=>400,'msg'=>'List not found');
		echo json_encode($response);
		return;

	}

}



function update_tax_number_match($data) {

	if ($data['value']=='Yes') {
		$match=true;
	}else if ($data['value']=='No') {
			$match=false;
		}else {
		$response= array('state'=>400,'msg'=>'Wrong value');
		echo json_encode($response);
		return;
	}

	$customer= new Customer($data['customer_key']);
	$customer->update(array('Customer Tax Number Details Match'=>$data['value']));


	$response= array('state'=>200,'match'=>$match,
		'tax_number_details_match'=>$customer->get('Tax Number Details Match'),


	);
	echo json_encode($response);
	return;


}



function delete_all_customers_in_store() {


}

function generate_password($length=9) {

	$letters='qwrtyuiopsghjklzxvnmQWRTYUIOPSGHJKLZXVNM!=/[]{}~\<>$%^&*()_+-@#.,)(*?|!';
	$password = '';
	for ($i = 0; $i < $length; $i++) {
		$password .= $letters[(mt_rand() % strlen($letters))];
	}
	return $password;
}


function check_customer_tax_number($data) {

	$customer= new Customer($data['customer_key']);

	include_once 'common_tax_number_functions.php';

//print_r($customer->data);
	$tax_number_data=check_tax_number($customer->data['Customer Tax Number'],$customer->data['Customer Billing Address 2 Alpha Country Code']);


	if (  ! ($tax_number_data['Tax Number Valid']=='Unknown' and in_array($customer->data['Customer Tax Number Valid'],array('Yes','No')))) {

		$customer->update(
			array(
				'Customer Tax Number Valid'=>$tax_number_data['Tax Number Valid'],
				'Customer Tax Number Details Match'=>$tax_number_data['Tax Number Details Match'],
				'Customer Tax Number Validation Date'=>$tax_number_data['Tax Number Validation Date'],
				'Customer Tax Number Registered Name'=>$tax_number_data['Tax Number Associated Name'],
				'Customer Tax Number Registered Address'=>$tax_number_data['Tax Number Associated Address'],


			)
		);

	}


	$response= array(
		'state'=>200,
		'valid'=>$tax_number_data['Tax Number Valid'],
		'name'=>$tax_number_data['Tax Number Associated Name'],
		'address'=>$tax_number_data['Tax Number Associated Address'],
		'msg'=>$tax_number_data['msg'],
		'tax_number_valid'=>$customer->get('Tax Number Valid'),
		'formated_date'=>$customer->get('Tax Number Validation Date'),
		'date'=>$customer->data['Customer Tax Number Validation Date'],
		'tax_number_details_match'=>$customer->get('Tax Number Details Match'),



	);


	echo json_encode($response);

}

?>
