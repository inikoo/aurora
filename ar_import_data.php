<?php
require_once 'class.Timer.php';

require_once 'common.php';

require_once 'ar_edit_common.php';



if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}

$editor=array(
	'Author Name'=>$user->data['User Alias'],
	'Author Type'=>$user->data['User Type'],
	'Author Key'=>$user->data['User Parent Key'],
	'User Key'=>$user->id
);

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('read_files'):
case('read_file'):
	print_r($_REQUEST);
	$data=prepare_values($_REQUEST,array(
			'files'=>array('type'=>'json array')
			,'format'=>'string'
			,'scope'=>'string'
		));
	read_data($data);
	break;
case('new_customer'):

default:
	$response=array('state'=>404,'resp'=>_('Operation not found'));
	echo json_encode($response);
}

function read_data($data) {

	print_r($data);
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




?>
