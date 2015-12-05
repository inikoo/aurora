<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2015 at 13:57:45 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}



$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'edit_field':

	$data=prepare_values($_REQUEST, array(
			'object'=>array('type'=>'string'),
			'key'=>array('type'=>'key'),
			'field'=>array('type'=>'string'),
			'value'=>array('type'=>'string'),
			'metadata'=>array('type'=>'json array', 'optional'=>true),

		));

	edit_field($account, $db, $user, $editor, $data);
	break;
case 'upload_attachment':

	$data=prepare_values($_REQUEST, array(
			'object'=>array('type'=>'string'),
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'fields_data'=>array('type'=>'json array'),

		));

	upload_attachment($account, $db, $user, $editor, $data);
	break;

case 'new_object':

	$data=prepare_values($_REQUEST, array(
			'object'=>array('type'=>'string'),
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'fields_data'=>array('type'=>'json array'),

		));

	new_object($account, $db, $user, $editor, $data);
	break;
default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function edit_field($account, $db, $user, $editor, $data) {


	$object=get_object($data['object'], $data['key']);


	if (!$object->id) {
		$response=array('state'=>405, 'resp'=>'Object not found');
		echo json_encode($response);
		exit;

	}

	$object->editor=$editor;

	$field=preg_replace('/_/', ' ', $data['field']);


	$formated_field= preg_replace('/^'.$object->get_object_name().' /', '', $field);


	if (preg_match('/ Telephone$/', $field)) {
		$options='no_history';
	}else {
		$options='';
	}



	$object->update(array($field=>$data['value']), $options);

	if (isset($data['metadata'])) {
		if (isset($data['metadata']['extra_fields'])) {
			foreach ( $data['metadata']['extra_fields'] as $extra_field) {

				$options='';
				$_field=preg_replace('/_/', ' ', $extra_field['field']);

				$_value=$extra_field['value'];

				$object->update(array($_field=>$_value), $options);

			}

		}


	}

	if ($object->error) {
		$response=array(
			'state'=>400,
			'msg'=>$object->msg,

		);


	}else {

		if ($object->updated) {
			$msg=sprintf('<i class="fa fa-check" onClick="hide_edit_field_msg(\'%s\')" ></i> %s', $data['field'], _('Updated'));
		}else {
			$msg='';
		}




		$response=array(
			'state'=>200,
			'msg'=>$msg,
			'formated_value'=>$object->get($formated_field),
			'value'=>$object->get($field),
			'other_fields'=>$object->get_other_fields_update_info()
		);




	}
	echo json_encode($response);

}


function new_object($account, $db, $user, $editor, $data) {

	global $smarty;

	$parent=get_object($data['parent'], $data['parent_key']);
	$parent->editor=$editor;

	switch ($data['object']) {
	case 'Staff':
		include_once 'class.Staff.php';
		$object=$parent->create_staff($data['fields_data']);
		$smarty->assign('account', $account);
		$smarty->assign('object', $object);

		$pcard=$smarty->fetch('presentation_cards/employee.pcard.tpl');
		$updated_data=array();
		break;
	case 'API_Key':
		include_once 'class.API_Key.php';
		$object=$parent->create_api_key($data['fields_data']);
		$smarty->assign('account', $account);
		$smarty->assign('object', $object);

		$pcard=$smarty->fetch('presentation_cards/api_key.pcard.tpl');
		$updated_data=array();

		break;
	case 'Timesheet_Record':
		include_once 'class.Timesheet_Record.php';
		$object=$parent->create_timesheet_record($data['fields_data']);
		$pcard='';
		$updated_data=array(
			'Timesheet_Clocked_Hours'=>$parent->get('Clocked Hours')
		);
		break;
	default:
		$response=array(
			'state'=>400,
			'msg'=>'object process not found'

		);

		echo json_encode($response);
		exit;
		break;
	}
	if ($parent->error) {
		$response=array(
			'state'=>400,
			'msg'=>'<i class="fa fa-exclamation-circle"></i> '.$parent->msg,

		);

	}else {

		$response=array(
			'state'=>200,
			'msg'=>'<i class="fa fa-check"></i> '._('Success'),
			'pcard'=>$pcard,
			'new_id'=>$object->id,
			'updated_data'=>$updated_data
		);


	}
	echo json_encode($response);

}


function upload_attachment($account, $db, $user, $editor, $data) {


	global $smarty;

	$parent=get_object($data['parent'], $data['parent_key']);
	$parent->editor=$editor;


	if (!$parent->id) {
		$msg= 'object key not found';
		$response= array('state'=>400, 'msg'=>$msg);
		echo json_encode($response);
		exit;
	}

	// print_r($data);



	if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') { //catch file overload error...
		$postMax = ini_get('post_max_size'); //grab the size limits...
		$msg= "File can not be attached, please note files larger than {$postMax} will result in this error!, let's us know, an we will increase the size limits"; // echo out error and solutions...
		$response= array('state'=>400, 'msg'=>_('Files could not be attached').".<br>".$msg, 'key'=>'attach');
		echo json_encode($response);
		exit;

	}

	foreach ($_FILES as $file_data) {

		if ($file_data['size']==0) {
			$msg= _("This file seems that is empty, have a look and try again").'.';
			$response= array('state'=>400, 'msg'=>$msg, 'key'=>'attach');
			echo base64_encode(json_encode($response));
			exit;

		}

		if ($file_data['error']) {
			$msg=$file_data['error'];
			if ($file_data['error']==4) {
				$msg=' '._('please choose a file, and try again');

			}
			$response= array('state'=>400, 'msg'=>_('Files could not be attached')." ".$msg, 'key'=>'attach');
			echo base64_encode(json_encode($response));
			exit;
		}



		$data['fields_data']['Filename']=$file_data['tmp_name'];
		$data['fields_data']['Attachment File Original Name']=$file_data['name'];
		//$data['fields_data']['Subject']=$parent->get_object_name();

		switch ($data['object']) {
		case 'Attachment':



			$object=$parent->add_attachment($data['fields_data']);
			
			
			
			$smarty->assign('account', $account);
			$smarty->assign('object', $object);

			$pcard=$smarty->fetch('presentation_cards/attachment.pcard.tpl');
			$updated_data=array();
			break;
		case 'Image':

			break;
		default:
			$response=array(
				'state'=>400,
				'msg'=>'object process not found'

			);

			echo json_encode($response);
			exit;
			break;
		}
		if ($parent->error) {
			$response=array(
				'state'=>400,
				'msg'=>'<i class="fa fa-exclamation-circle"></i> '.$parent->msg,

			);

		}else {

			$response=array(
				'state'=>200,
				'msg'=>'<i class="fa fa-check"></i> '._('Success'),
				'pcard'=>$pcard,
				'new_id'=>$object->id,
				'updated_data'=>$updated_data
			);


		}
		echo json_encode($response);



		exit;

		/*
		if ($subject->updated) {
			$updated=$subject->updated;



		} else {
			$msg=$subject->msg;
		}

		*/

		break;// only 1 file support
	}


	exit;


	switch ($data['object']) {
	case 'Attachment':







		$object=$parent->add_attachment($data['fields_data']);
		$smarty->assign('account', $account);
		$smarty->assign('object', $object);

		$pcard=$smarty->fetch('presentation_cards/employee.pcard.tpl');
		$updated_data=array();
		break;
	case 'Image':

		break;
	default:
		$response=array(
			'state'=>400,
			'msg'=>'object process not found'

		);

		echo json_encode($response);
		exit;
		break;
	}
	if ($parent->error) {
		$response=array(
			'state'=>400,
			'msg'=>'<i class="fa fa-exclamation-circle"></i> '.$parent->msg,

		);

	}else {

		$response=array(
			'state'=>200,
			'msg'=>'<i class="fa fa-check"></i> '._('Success'),
			'pcard'=>$pcard,
			'new_id'=>$object->id,
			'updated_data'=>$updated_data
		);


	}
	echo json_encode($response);



	exit;

	$subject=get_parent_object($data);
	$subject->editor=$editor;
	$db_field=get_parent_db_field($data);
	$msg='';
	$updated=false;


	if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') { //catch file overload error...
		$postMax = ini_get('post_max_size'); //grab the size limits...
		$msg= "File can not be attached, please note files larger than {$postMax} will result in this error!, let's us know, an we will increase the size limits"; // echo out error and solutions...
		$response= array('state'=>400, 'msg'=>_('Files could not be attached').".<br>".$msg, 'key'=>'attach');
		echo base64_encode(json_encode($response));
		exit;

	}

	foreach ($_FILES as $file_data) {

		if ($file_data['size']==0) {
			$msg= _("This file seems that is empty, have a look and try again").'.';
			$response= array('state'=>400, 'msg'=>$msg, 'key'=>'attach');
			echo base64_encode(json_encode($response));
			exit;

		}

		if ($file_data['error']) {
			$msg=$file_data['error'];
			if ($file_data['error']==4) {
				$msg=' '._('please choose a file, and try again');

			}
			$response= array('state'=>400, 'msg'=>_('Files could not be attached')."<br/>".$msg, 'key'=>'attach');
			echo base64_encode(json_encode($response));
			exit;
		}


		$_data=array(
			'Filename'=>$file_data['tmp_name'],
			'Attachment Caption'=>$data['caption'],
			'Attachment File Original Name'=>$file_data['name']
		);


		$subject->add_attachment($_data);

		if ($subject->updated) {
			$updated=$subject->updated;



		} else {
			$msg=$subject->msg;
		}
	}

	if ($updated) {
		$elements_numbers=array('Notes'=>0, 'Orders'=>0, 'Changes'=>0, 'Attachments'=>0, 'Emails'=>0, 'WebLog'=>0);

		if ($db_field=='Product' or $db_field=='Supplier Product') {
			$db_field_key=$db_field.' ID';
		}else if ($db_field=='Part' ) {
			$db_field_key=$db_field.' SKU';
		}else if ($db_field=='Product Family' or $db_field=='Product Department') {
			$db_field_key=preg_replace('/Product /', '', $db_field).' Key';
		}else {
			$db_field_key=$db_field.' Key';
		}

		$sql=sprintf("select count(*) as num , `Type` from  `%s History Bridge` where `%s`=%d group by `Type`",
			$db_field,
			$db_field_key,
			$data['parent_key']
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Type']]=number($row['num']);
		}

		$response= array('state'=>200, 'newvalue'=>1, 'key'=>'attach', 'elements_numbers'=>$elements_numbers);

	} else {
		$response= array('state'=>400, 'msg'=>_('Files could not be attached')."<br/>".$msg, 'key'=>'attach');
	}

	echo base64_encode(json_encode($response));
}


?>
