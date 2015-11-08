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

		));

	edit_field($data, $db, $user);
	break;

default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function edit_field($data, $db, $user) {
	global  $account;

	$object=get_object($data['object'], $data['key']);

	$field=preg_replace('/_/', ' ', $data['field']);
	
	
	$formated_field= preg_replace('/^'.$object->get_object_name().' /','',$field);
	
	$object->update(array($field=>$data['value']));

	if ($object->error) {
		$response=array('resultset'=>
			array(
				'state'=>400,
				'msg'=>$object->msg,


			)
		);


	}else {
		$object->reread();
		if ($object->updated) {
			$msg=sprintf('<i class="fa fa-check" onClick="hide_edit_field_msg(\'%s\')" ></i> %s', $data['field'], _('Updated'));
		}else {
			$msg='';
		}

		$response=array(
			'state'=>200,
			'msg'=>$msg,
			'formated_value'=>$object->get($formated_field),

			'value'=>$object->get($field)


		);

	}
	echo json_encode($response);

}




?>
