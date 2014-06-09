<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 9 June 2014 13:09:12 GMT+1, Sheffield , UK

 Version 2.0
*/



require_once 'common.php';
require_once 'class.Company.php';
require_once 'ar_edit_common.php';
include_once 'class.CustomField.php';
require_once 'class.SendEmail.php';
require_once 'common_detect_agent.php';

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

case('edit_contact'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key')
			,'value'=>array('type'=>'json array')
			,'subject_key'=>array('type'=>'key')
		));
	edit_contact($data);
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

default:

	$response=array('state'=>404,'resp'=>'Operation not found '.$tipo);
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
				$address_keys=$this->get_delivery_address_keys();
				$new_delivery_address_key=array_pop($address_keys);
				$subject_object->update_principal_delivery_address($new_delivery_address_key);
			}
			if ($subject_object->data['Customer Billing Address Key']==$address->id) {
				$address_keys=$this->get_billing_address_keys();
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
				$address_keys=$this->get_delivery_address_keys();
				$new_delivery_address_key=array_pop($address_keys);
				$subject_object->update_principal_delivery_address($new_delivery_address_key);
			}
			if ($subject_object->data['Customer Billing Address Key']==$address->id) {
				$address_keys=$this->get_billing_address_keys();
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
	$tax_number_data=check_tax_number($customer->data['Customer Tax Number'],$customer->data['Customer Billing Address 2 Alpha Country Code']);


	if (  ! ($tax_number_data['Tax Number Valid']=='Unknown' and in_array($customer->data['Customer Tax Number Valid'],array('Yes','No')))) {

		$customer->update(
			array(
				'Customer Tax Number Valid'=>$tax_number_data['Tax Number Valid'],
				'Customer Tax Number Validation Date'=>$tax_number_data['Tax Number Validation Date'],
				'Customer Tax Number Associated Name'=>$tax_number_data['Tax Number Associated Name'],
				'Customer Tax Number Associated Address'=>$tax_number_data['Tax Number Associated Address'],
			)
		);

	}


	$response= array(
		'state'=>200,
		'valid'=>$tax_number_data['Tax Number Valid'],
		'name'=>$tax_number_data['Tax Number Associated Name'],
		'addresss'=>$tax_number_data['Tax Number Associated Address'],
		'msg'=>$tax_number_data['msg'],
		'tax_number_valid'=>$customer->get('Tax Number Valid')


	);


	echo json_encode($response);

}





?>
