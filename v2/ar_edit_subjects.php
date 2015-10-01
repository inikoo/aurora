<?php

require_once 'common.php';
require_once 'class.Company.php';
require_once 'class.Supplier.php';
require_once 'ar_edit_common.php';
include_once 'class.CompanyDepartment.php';
include_once 'class.Staff.php';
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



case('edit_quick_telephone'):
	$data=prepare_values($_REQUEST,array(
			'key'=>array('type'=>'key'),
			'key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')
		));

	edit_subject($data);

break;
case('edit_subject'):
	$data=prepare_values($_REQUEST,array(
			'subject_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')
		));

	edit_subject($data);
	break;

default:

	$response=array('state'=>404,'resp'=>'Operation not found '.$tipo);
	echo json_encode($response);
}



function edit_subject($data) {

	$subject=new subject($data['subject_key']);
	if (!$subject->id) {
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

		$responses[]=edit_subject_field($subject->id,$key,$values_data);
	}

	if (isset($data['submit']))
		return $responses;

	echo json_encode($responses);


}

function edit_subject_field($subject_key,$key,$value_data) {

	//print_r($value_data);
	//print "$subject_key,$key,$value_data ***";
	$subject=new subject($subject_key);
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
	$subject->editor=$editor;





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

	$the_new_value=_trim($value_data['value']);



	if (preg_match('/^email\d+$/i',$key)) {
		$email_id=preg_replace('/^email/','',$key);
		$subject->update_other_email($email_id,$the_new_value);

		if ($the_new_value=='') {
			$other_email_deleted=true;
		}

	}
	elseif (preg_match('/^email_label\d+$/i',$key)) {
		$email_id=preg_replace('/^email_label/','',$key);
		$subject->update_other_email_label($email_id,$the_new_value);
		$other_label=true;
		$other_label_scope='email';
		$other_label_scope_key=$email_id;
	}
	elseif (preg_match('/^telephone_label\d+$/i',$key)) {
		$telecom_id=preg_replace('/^telephone_label/','',$key);
		$subject->update_other_telecom_label('Telephone',$telecom_id,$the_new_value);
		$other_label=true;
		$other_label_scope='telephone';
		$other_label_scope_key=$telecom_id;
	}
	elseif (preg_match('/^mobile_label\d+$/i',$key)) {
		$telecom_id=preg_replace('/^mobile_label/','',$key);
		$subject->update_other_telecom_label('Mobile',$telecom_id,$the_new_value);
		$other_label=true;
		$other_label_scope='mobile';
		$other_label_scope_key=$telecom_id;
	}
	elseif (preg_match('/^fax_label\d+$/i',$key)) {
		$telecom_id=preg_replace('/^fax_label/','',$key);
		$subject->update_other_telecom_label('FAX',$telecom_id,$the_new_value);
		$other_label=true;
		$other_label_scope='fax';
		$other_label_scope_key=$telecom_id;
	}
	elseif (preg_match('/^telephone\d+$/i',$key)) {
		$telephone_id=preg_replace('/^telephone/','',$key);
		$subject->update_other_telephone($telephone_id,$the_new_value);

		if ($the_new_value=='') {
			$other_telephone_deleted=true;
		}

	}
	elseif (preg_match('/^fax\d+$/i',$key)) {
		$fax_id=preg_replace('/^fax/','',$key);
		$subject->update_other_fax($fax_id,$the_new_value);

		if ($the_new_value=='') {
			$other_fax_deleted=true;
		}

	}
	elseif (preg_match('/^mobile\d+$/i',$key)) {


		$mobile_id=preg_replace('/^mobile/','',$key);
		$subject->update_other_mobile($mobile_id,$the_new_value);

		if ($the_new_value=='') {
			$other_mobile_deleted=true;

		}

	}
	elseif ($key=='Customer Fiscal Name') {
		$subject->update_fiscal_name($the_new_value );
	}
	elseif ($key=='Customer Tax Number') {
		$subject->update_tax_number($the_new_value);
	}
	// elseif ($key=='Customer Registration Number') {
	//    $subject->update_registration_number($the_new_value);
	// }
	elseif (preg_match('/^custom_field_subject/i',$key)) {
		$custom_id=preg_replace('/^custom_field_/','',$key);
		//print $key;
		$subject->update_custom_fields($key, $the_new_value);

	}
	else {
		// print "$subject_key,$key,$the_new_value ***";




		$subject->update(array($key=>$the_new_value));
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






	if (!$subject->error ) {

		if ($other_email_deleted) {
			$response= array('state'=>200,'action'=>'other_email_deleted','newvalue'=>$subject->new_value,'key'=>$value_data['okey'],'email_key'=>$email_id,'warning_msg'=>$subject->warning_messages);
		}
		elseif ($other_mobile_deleted) {
			$response= array('state'=>200,'action'=>'other_mobile_deleted','newvalue'=>$subject->new_value,'key'=>$value_data['okey'],'mobile_key'=>$mobile_id,'warning_msg'=>$subject->warning_messages);
		}
		elseif ($other_fax_deleted) {
			$response= array('state'=>200,'action'=>'other_fax_deleted','newvalue'=>$subject->new_value,'key'=>$value_data['okey'],'fax_key'=>$fax_id,'warning_msg'=>$subject->warning_messages);
		}
		elseif ($other_telephone_deleted) {
			$response= array('state'=>200,'action'=>'other_telephone_deleted','newvalue'=>$subject->new_value,'key'=>$value_data['okey'],'telephone_key'=>$telephone_id,'warning_msg'=>$subject->warning_messages);
		}
		elseif ($other_email_added) {
			$response= array('state'=>200,'action'=>'other_email_added','newvalue'=>$subject->new_value,'key'=>$value_data['okey'],'new_email_key'=>$subject->new_email_key,'warning_msg'=>$subject->warning_messages);
		}
		elseif ($other_telephone_added) {
			$response= array('state'=>200,'action'=>'other_telephone_added','newvalue'=>$subject->new_value,'key'=>$value_data['okey'],'warning_msg'=>$subject->warning_messages);
		}
		elseif ($other_fax_added) {
			$response= array('state'=>200,'action'=>'other_fax_added','newvalue'=>$subject->new_value,'key'=>$value_data['okey'],'warning_msg'=>$subject->warning_messages);
		}
		elseif ($other_mobile_added) {
			$response= array('state'=>200,'action'=>'other_mobile_added','newvalue'=>$subject->new_value,'key'=>$value_data['okey'],'warning_msg'=>$subject->warning_messages);
		}
		elseif ($other_label) {
			$response= array('state'=>200,'action'=>'updated','newvalue'=>$subject->new_value,'key'=>$value_data['okey'],'scope_key'=>$other_label_scope_key,'scope'=>$other_label_scope,'warning_msg'=>$subject->warning_messages);
		}
		else {
			if ($subject->updated)
				$response= array('state'=>200,'action'=>'updated','newvalue'=>$subject->new_value,'key'=>$value_data['okey'],'warning_msg'=>$subject->warning_messages,'warning_msg'=>$subject->warning_messages);
			else
				$response= array('state'=>200,'action'=>'nochange','newvalue'=>$subject->new_value,'key'=>$value_data['okey'],'warning_msg'=>$subject->warning_messages,'warning_msg'=>$subject->warning_messages);

		}
	} else {

		$response= array('state'=>400,'msg'=>$subject->msg,'key'=>$value_data['okey'], 'warning_msg'=>$subject->warning_messages);
	}

	return $response;

}


?>
