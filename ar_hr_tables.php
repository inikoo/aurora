<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 15:19:13 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


if (!$user->can_view('customers')) {
	echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
	exit;
}


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'employees':
	employees(get_table_parameters(), $db, $user);
	break;
case 'timesheets':
	timesheets(get_table_parameters(), $db, $user);
	break;
case 'contractors':
	contractors(get_table_parameters(), $db, $user);
	break;

default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function employees($_data, $db, $user) {
	global $db;
	$rtext_label='employee';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	foreach ($db->query($sql) as $data) {
		$adata[]=array(
			'id'=>(integer) $data['Staff Key'],
			'formated_id'=>sprintf("%04d", $data['Staff Key']),
			'payroll_id'=>$data['Staff ID'],
			'name'=>$data['Staff Name'],
			'position'=>$data['position']
		);

	}

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'total_records'=> $total

		)
	);
	echo json_encode($response);
}


function timesheets($_data, $db, $user) {
	global $db;
	$rtext_label='timesheet_record';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	foreach ($db->query($sql) as $data) {
		$adata[]=array(
			'id'=>(integer) $data['Staff Key'],
			'formated_id'=>sprintf("%04d", $data['Staff Key']),

			'payroll_id'=>$data['Staff ID'],
			'name'=>$data['Staff Name'],
			'date'=>($data['Valid Date']!=''?strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Valid Date'])):''),
		);

	}

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'total_records'=> $total

		)
	);
	echo json_encode($response);
}



?>
