<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:8 November 2015 at 13:37:41 GMT, Sheffield UK
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
case 'check_for_duplicates':

	$data=prepare_values($_REQUEST, array(
			'object'=>array('type'=>'string'),
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'field'=>array('type'=>'string'),
			'value'=>array('type'=>'string'),

		));

	check_for_duplicates($data, $db, $user);
	break;

default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function check_for_duplicates($data, $db, $user) {
	global  $account;


	$field=preg_replace('/_/', ' ', $data['field']);

	switch ($data['object']) {
	case 'Staff':

		switch ($field) {
		case 'Staff ID':
			$invalid_msg=_('Another employee is using this payroll Id');
			break;
		case 'Staff Alias':
			$invalid_msg=_('Another employee is using this code');
			break;
		case 'Staff User Handle':
			$invalid_msg=_('Another user is using this login handle');
			$sql=sprintf("select `User Key`as `key` ,`User Handle` as field from `User Dimension` where `User Type`='Staff' and `User Handle`=%s",
				prepare_mysql($data['value'])
			);
			break;
		default:

			break;
		}
		case 'Store':

		switch ($field) {
		case 'Store Code':
			$invalid_msg=_('Another store is using this code');
			break;
		
		default:

			break;
		}


		break;
	default:


		break;
	}

	if (!isset($sql)) {
		switch ($data['parent']) {
		case 'store':
			$parent_where=sprintf(' and `%s Store Key`=%d ', $data['object'], $data['parent_key']);
			break;
		default:
			$parent_where='';
		}

		$sql=sprintf('select `%s Key` as `key` ,`%s` as field from `%s Dimension` where `%s`=%s %s ',
			addslashes($data['object']),
			addslashes($field),
			addslashes($data['object']),
			addslashes($field),
			prepare_mysql($data['value']),
			$parent_where

		);

	}



	if (!isset($invalid_msg)) {
		$invalid_msg=_('Another object with same value found');
	}
	$validation='valid';
	$msg='';

	//print $sql;

	if ($row = $db->query($sql)->fetch()) {
		if ($row['key']!=$data['key']) {
			$validation='invalid';
			$msg=$invalid_msg;
		}
	}

	$response=array(
		'state'=>200,
		'validation'=>$validation,
		'msg'=>$msg,
	);
	echo json_encode($response);



}




?>
