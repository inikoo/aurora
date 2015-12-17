<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 16 December 2015 at 23:56:43 CET, Barcelona Airport, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


if (!$user->can_view('staff')) {
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
case 'operatives':
	operatives(get_table_parameters(), $db, $user);
	break;
case 'manufacture_tasks':
	manufacture_tasks(get_table_parameters(), $db, $user, $account);
	break;
	

default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function operatives($_data, $db, $user) {
	
	$rtext_label='operative';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			switch ($data['Staff Type']) {
			case 'Employee':
				$type=_('Employee');
				break;
			case 'Volunteer':
				$type=_('Volunteer');
				break;
			case 'TemporalWorker':
				$type=_("Temporal worker");
				break;
			case 'WorkExperience':
				$type=_("Work experience");
				break;
			default:
				$type=$data['Staff Type'];
				break;
			}

			$adata[]=array(
				'id'=>(integer) $data['Staff Key'],
				'formated_id'=>sprintf("%04d", $data['Staff Key']),
				'payroll_id'=>$data['Staff ID'],
				'name'=>$data['Staff Name'],
				'code'=>$data['Staff Alias'],
				'code_link'=>$data['Staff Alias'],
				'type'=>$type,
				'supervisors'=>$data['supervisors']
			);

		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
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

function manufacture_tasks($_data, $db, $user,$account) {
	
	$rtext_label='manufacture task';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			
			$adata[]=array(
				'id'=>(integer) $data['Manufacture Task Key'],
				'name'=>$data['Manufacture Task Name'],
				'work_cost'=>($data['Manufacture Task Work Cost']!=''?money($data['Manufacture Task Work'],$account->get('Account Currency')):_('NA')),
				
			);

		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
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
