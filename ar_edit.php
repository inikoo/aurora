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

    global $account,$smarty;

	$parent=get_object($data['parent'], $data['parent_key']);
	$parent->editor=$editor;

	switch ($data['object']) {
	case 'Staff':
		include_once 'class.Staff.php';
		$object=$parent->create_staff($data['fields_data']);
		$pcard_template='presentation_cards/api_key.pcard.tpl';
		break;
	case 'API_Key':
		include_once 'class.API_Key.php';
		$object=$parent->create_api_key($data['fields_data']);
		$pcard_template='presentation_cards/api_key.pcard.tpl';
		break;
	default:

		break;
	}

	if ($parent->error) {
		$response=array(
			'state'=>400,
			'msg'=>$parent->msg,

		);


	}else {
		$smarty->assign('account', $account);

		$smarty->assign('object', $object);

		$response=array(
			'state'=>200,
			'pcard'=>$smarty->fetch($pcard_template),



		);


	}
	echo json_encode($response);

}


?>
