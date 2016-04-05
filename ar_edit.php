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
require_once 'utils/natural_language.php';
require_once 'utils/parse_natural_language.php';


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

	edit_field($account, $db, $user, $editor, $data, $smarty);
	break;
case 'delete_object_component':

	$data=prepare_values($_REQUEST, array(
			'object'=>array('type'=>'string'),
			'key'=>array('type'=>'key'),
			'field'=>array('type'=>'string'),
			'metadata'=>array('type'=>'json array', 'optional'=>true),

		));

	delete_object_component($account, $db, $user, $editor, $data, $smarty);
	break;
case 'set_as_main':

	$data=prepare_values($_REQUEST, array(
			'object'=>array('type'=>'string'),
			'key'=>array('type'=>'key'),
			'field'=>array('type'=>'string'),
			'metadata'=>array('type'=>'json array', 'optional'=>true),

		));

	set_as_main($account, $db, $user, $editor, $data, $smarty);
	break;

case 'delete_image':
	$data=prepare_values($_REQUEST, array(
			'image_bridge_key'=>array('type'=>'key'),
		));

	delete_image($account, $db, $user, $editor, $data, $smarty);
	break;

case 'new_object':

	$data=prepare_values($_REQUEST, array(
			'object'=>array('type'=>'string'),
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'fields_data'=>array('type'=>'json array'),

		));

	new_object($account, $db, $user, $editor, $data, $smarty);
	break;
default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function edit_field($account, $db, $user, $editor, $data, $smarty) {


	$object=get_object($data['object'], $data['key'], $load_other_data=true);


	if (!$object->id) {
		$response=array('state'=>405, 'resp'=>'Object not found');
		echo json_encode($response);
		exit;

	}

	$object->editor=$editor;

	$field=preg_replace('/_/', ' ', $data['field']);


	$formatted_field= preg_replace('/^'.$object->get_object_name().' /', '', $field);



	if ($field=='Staff Position' and $data['object']=='User') {
		$formatted_field='Position';
	}



	if (preg_match('/ Telephone$/', $field)) {
		$options='no_history';
	}else {
		$options='';
	}


	if (isset($data['metadata'])) {
		$object->update(array($field=>$data['value']), $options, $data['metadata']);
	}else {
		$object->update(array($field=>$data['value']), $options);
	}


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
		$directory_field='';
		$directory='';
		$items_in_directory='';




		if ($object->updated) {
			$msg=sprintf('<span class="success"><i class="fa fa-check " onClick="hide_edit_field_msg(\'%s\')" ></i> %s</span>', $data['field'], _('Updated'));

			$formatted_value=$object->get($formatted_field);

			$action='updated';
		}elseif (isset($object->deleted)) {
			$msg=sprintf('<span class="discret"><i class="fa fa-check " onClick="hide_edit_field_msg(\'%s\')" ></i> %s</span>', $data['field'], _('Deleted'));
			$formatted_value=sprintf('<span class="deleted">%s</span>', $object->deleted_value);
			$action='deleted';
		}elseif (isset($object->field_created)) {
			$msg=sprintf('<span class="success"><i class="fa fa-check " onClick="hide_edit_field_msg(\'%s\')" ></i> %s</span>', $data['field'], _('Created'));
			$formatted_value='';
			$action='new_field';

			if ($field=='new delivery address') {
				$directory_field='other_delivery_addresses';
				$smarty->assign('customer', $object);
				$directory=$smarty->fetch('delivery_addresses_directory.tpl');
				$items_in_directory=count($object->get_other_delivery_addresses_data());
			}


		}else {
			$msg='';
			$formatted_value=$object->get($formatted_field);
			$action='';
		}




		$response=array(
			'state'=>200,
			'msg'=>$msg,
			'action'=>$action,
			'formatted_value'=>$formatted_value,
			'value'=>$object->get($field),
			'other_fields'=>$object->get_other_fields_update_info(),
			'new_fields'=>$object->get_new_fields_info(),
			'deleted_fields'=>$object->get_deleted_fields_info(),
			'directory_field'=>$directory_field,
			'directory'=>$directory,
			'items_in_directory'=>$items_in_directory,

		);




	}
	echo json_encode($response);

}


function set_as_main($account, $db, $user, $editor, $data, $smarty) {


	$object=get_object($data['object'], $data['key']);


	if (!$object->id) {
		$response=array('state'=>405, 'resp'=>'Object not found');
		echo json_encode($response);
		exit;

	}

	$object->editor=$editor;

	if ($data['field']=='Customer_Main_Plain_Mobile') {
		$object->update(array('Customer Preferred Contact Number'=>'Mobile'));
		$response=array(
			'state'=>200,
			'other_fields'=>$object->get_other_fields_update_info(),
			'new_fields'=>$object->get_new_fields_info(),
			'deleted_fields'=>$object->get_deleted_fields_info(),
			'action'=>($object->updated?'set_main_contact_number_Mobile':'')
		);

	}elseif ($data['field']=='Customer_Main_Plain_Telephone') {
		$object->update(array('Customer Preferred Contact Number'=>'Telephone'));
		$response=array(
			'state'=>200,
			'other_fields'=>$object->get_other_fields_update_info(),
			'new_fields'=>$object->get_new_fields_info(),
			'deleted_fields'=>$object->get_deleted_fields_info(),
			'action'=>($object->updated?'set_main_contact_number_Telephone':'')
		);

	}elseif (preg_match('/(.+)(_\d+)$/', $data['field'], $matches)) {

		$value=trim(preg_replace('/_/', ' ', $matches[2]));
		$field=trim(preg_replace('/_/', ' ', $matches[1]));

		$object->set_as_main($field, $value);

		if ($field=='Customer Other Delivery Address') {
			$smarty->assign('customer', $object);
			$directory_field='other_delivery_addresses';

			$directory=$smarty->fetch('delivery_addresses_directory.tpl');
			$items_in_directory=count($object->get_other_delivery_addresses_data());
			$action=($object->updated?'set_main_delivery_address':'');
			$value=$object->get('Customer Delivery Address');
		}else {
			$directory='';
			$directory_field='';
			$items_in_directory=0;
			$action='';
			$value='';
		}


		if ($object->error) {
			$response=array(
				'state'=>400,
				'msg'=>$object->msg,

			);
		}else {
			$response=array(
				'state'=>200,
				'other_fields'=>$object->get_other_fields_update_info(),
				'new_fields'=>$object->get_new_fields_info(),
				'deleted_fields'=>$object->get_deleted_fields_info(),
				'action'=>$action,
				'directory_field'=>$directory_field,
				'directory'=>$directory,
				'items_in_directory'=>$items_in_directory,
				'value'=>$value
			);


		}


	}else {
		$response=array(
			'state'=>400,
			'msg'=>'invalid field data',

		);

	}

	echo json_encode($response);


}


function delete_object_component($account, $db, $user, $editor, $data, $smarty) {


	$object=get_object($data['object'], $data['key']);


	if (!$object->id) {
		$response=array('state'=>405, 'resp'=>'Object not found');
		echo json_encode($response);
		exit;

	}

	$object->editor=$editor;


	if (preg_match('/(.+)(_\d+)$/', $data['field'], $matches)) {

		$value=trim(preg_replace('/_/', ' ', $matches[2]));
		$field=trim(preg_replace('/_/', ' ', $matches[1]));



		$object->delete_component($field, $value);





		if ($object->error) {
			$response=array(
				'state'=>400,
				'msg'=>$object->msg,

			);
		}else {


			if ($field=='Customer Other Delivery Address') {
				$smarty->assign('customer', $object);
				$directory_field='other_delivery_addresses';

				$directory=$smarty->fetch('delivery_addresses_directory.tpl');
				$items_in_directory=count($object->get_other_delivery_addresses_data());
			}else {
				$directory_field='';
				$directory='';
				$items_in_directory=0;
			}


			$response=array(
				'state'=>200,
				'other_fields'=>$object->get_other_fields_update_info(),
				'new_fields'=>$object->get_new_fields_info(),
				'deleted_fields'=>$object->get_deleted_fields_info(),
				'action'=>'',
				'directory_field'=>$directory_field,
				'directory'=>$directory,
				'items_in_directory'=>$items_in_directory,
			);


		}


	}else {
		$response=array(
			'state'=>400,
			'msg'=>'invalid field data',

		);

	}

	echo json_encode($response);


}


function new_object($account, $db, $user, $editor, $data, $smarty) {



	$parent=get_object($data['parent'], $data['parent_key']);
	$parent->editor=$editor;

	switch ($data['object']) {
	case 'Part':
		include_once 'class.Part.php';
		$object=$parent->create_part($data['fields_data']);

		if ($parent->new_part) {

			$smarty->assign('object', $object);
			$smarty->assign('warehouse', $parent);

			$pcard=$smarty->fetch('presentation_cards/part.pcard.tpl');
			$updated_data=array();
		}else {


			$response=array(
				'state'=>400,
				'msg'=>$parent->msg

			);
			echo json_encode($response);
			exit;
		}
		break;
	case 'Manufacture_Task':
		include_once 'class.Manufacture_Task.php';
		$object=$parent->create_manufacture_task($data['fields_data']);

		if ($parent->new_manufacture_task) {

			$smarty->assign('object', $object);

			$pcard=$smarty->fetch('presentation_cards/manufacture_task.pcard.tpl');
			$updated_data=array();
		}else {


			$response=array(
				'state'=>400,
				'msg'=>$parent->msg

			);
			echo json_encode($response);
			exit;
		}
		break;
	case 'User':
		include_once 'class.User.php';

		$parent->get_user_data();
		$parent->create_user($data['fields_data']);

		if ($parent->create_user_error) {
			$response=array(
				'state'=>400,
				'msg'=>$parent->create_user_msg

			);
			echo json_encode($response);
			exit;
		}

		$object=$parent->user;



		$smarty->assign('account', $account);
		$smarty->assign('object', $object);
		$smarty->assign('employee', $parent);



		$pcard=$smarty->fetch('presentation_cards/system_user.pcard.tpl');
		$updated_data=array();
		break;
	case 'Store':
		include_once 'class.Store.php';
		if (!$parent->error) {
			$object=$parent->create_store($data['fields_data']);

			if ($parent->new_object) {

				$smarty->assign('account', $account);
				$smarty->assign('object', $object);

				$pcard=$smarty->fetch('presentation_cards/store.pcard.tpl');
				$updated_data=array();

			}else {
				$response=array(
					'state'=>400,
					'msg'=>$parent->msg

				);
				echo json_encode($response);
				exit;

			}

		}
		break;
	case 'Warehouse':
		include_once 'class.Warehouse.php';
		if (!$parent->error) {
			$object=$parent->create_warehouse($data['fields_data']);
			if ($parent->new_object) {
				$smarty->assign('account', $account);
				$smarty->assign('object', $object);

				$pcard=$smarty->fetch('presentation_cards/warehouse.pcard.tpl');
				$updated_data=array();
			}else {
				$response=array(
					'state'=>400,
					'msg'=>$parent->msg

				);
				echo json_encode($response);
				exit;

			}
		}
		break;
	case 'Customer':
		include_once 'class.Customer.php';
		if (!$parent->error) {
			$object=$parent->create_customer($data['fields_data']);
			$smarty->assign('account', $account);
			$smarty->assign('object', $object);

			$pcard=$smarty->fetch('presentation_cards/customer.pcard.tpl');
			$updated_data=array();
		}
		break;
	case 'Supplier':
		include_once 'class.Supplier.php';
		$object=$parent->create_supplier($data['fields_data']);
		if (!$parent->error) {
			$smarty->assign('account', $account);
			$smarty->assign('object', $object);

			$pcard=$smarty->fetch('presentation_cards/supplier.pcard.tpl');
			$updated_data=array();
		}
		break;
	case 'Contractor':
		include_once 'class.Staff.php';

		$data['fields_data']['Staff Type']='Contractor';

		$object=$parent->create_staff($data['fields_data']);
		if (!$parent->error) {
			$smarty->assign('account', $account);
			$smarty->assign('object', $object);

			$pcard=$smarty->fetch('presentation_cards/contractor.pcard.tpl');
			$updated_data=array();
		}
		break;
	case 'Staff':
		include_once 'class.Staff.php';

		$object=$parent->create_staff($data['fields_data']);
		if (!$parent->error) {
			$smarty->assign('account', $account);
			$smarty->assign('object', $object);

			$pcard=$smarty->fetch('presentation_cards/employee.pcard.tpl');
			$updated_data=array();
		}


		break;
	case 'API_Key':
		include_once 'class.API_Key.php';

		$object=$parent->create_api_key($data['fields_data']);
		if (!$parent->error) {
			$smarty->assign('account', $account);
			$smarty->assign('object', $object);

			$pcard=$smarty->fetch('presentation_cards/api_key.pcard.tpl');
			$updated_data=array();
		}
		break;
	case 'Timesheet_Record':
		include_once 'class.Timesheet_Record.php';
		$object=$parent->create_timesheet_record($data['fields_data']);
		if (!$parent->error) {
			$pcard='';
			$updated_data=array(
				'Timesheet_Clocked_Hours'=>$parent->get('Clocked Hours')
			);
		}
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



function delete_image($account, $db, $user, $editor, $data, $smarty) {

	include_once 'class.Image.php';


	$sql=sprintf('select `Image Subject Object`,`Image Subject Object Key`,`Image Subject Image Key` from `Image Subject Bridge` where `Image Subject Key`=%d ', $data['image_bridge_key']);
	if ($result=$db->query($sql)) {
		if ($row = $result->fetch()) {

			$object=get_object($row['Image Subject Object'], $row['Image Subject Object Key']);
			$object->editor=$editor;

			if (!$object->id) {
				$msg= 'object key not found';
				$response= array('state'=>400, 'msg'=>$msg);
				echo json_encode($response);
				exit;
			}

			$object->delete_image( $data['image_bridge_key']);

			$response= array(
				'state'=>200,
				'msg'=>_('Image deleted'),
				'number_images'=>$object->get_number_images(),
				'main_image_key'=>$object->get_main_image_key()

			);
			echo json_encode($response);
			exit;

		}else {
			$msg=_('Image not found');
			$response= array('state'=>400, 'msg'=>$msg);
			echo json_encode($response);
			exit;
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}



}




?>
