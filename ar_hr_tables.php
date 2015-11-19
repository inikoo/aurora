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
	employees(get_table_parameters(), $db, $user, 'current');
	break;
case 'exemployees':
	employees(get_table_parameters(), $db, $user, 'ex');
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


function employees($_data, $db, $user, $type='') {
	global $db;

	if ($type=='current') {
		$extra_where=' and `Staff Currently Working`="Yes"';
		$rtext_label='employee';

	}elseif ($type=='ex') {
		$extra_where=' and `Staff Currently Working`="No"';
		$rtext_label='ex employee';

	}

	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	//print $sql;

	$adata=array();
	foreach ($db->query($sql) as $data) {
	

		switch ($data['User Active']) {
		case 'Yes':
			$user_active=_('Active');
			break;
		case 'No':
			$user_active=_('Suspended');
			break;
		case '':
			$user_active=_("Don't set up");
			break;
		default:
			$user_active=$data['User Active'];
			break;
		}
		
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


			'birthday'=>(($data['Staff Birthday']=='' or $data['Staff Birthday']=='0000-00-00 00:00:00' ) ?'': strftime("%e %b %Y", strtotime($data['Staff Birthday'].' +0:00'))),

			'official_id'=>$data['Staff Official ID' ],
			'email'=>$data['Staff Email'],
			'telephone'=>$data['Staff Telephone Formated'],
			'next_of_kind'=>$data['Staff Next of Kind'],
			'from'=>(($data['Staff Valid From']=='' or $data['Staff Valid From']=='0000-00-00 00:00:00' ) ?'': strftime("%e %b %Y", strtotime($data['Staff Valid From'].' +0:00'))),

			'until'=>(($data['Staff Valid To']=='' or  $data['Staff Valid To']=='0000-00-00 00:00:00' ) ?'': strftime("%e %b %Y", strtotime($data['Staff Valid To'].' +0:00'))),
			'type'=>$type,


			'supervisors'=>$data['supervisors'],

			'job_title'=>$data['Staff Job Title'],
			'user_login'=>$data['User Handle'],
			'user_active'=>$user_active,
			'user_last_login'=>($data['User Last Login']=='' ?'': strftime("%a %e %b %Y %H:%M %Z", strtotime($data['User Last Login'].' +0:00'))),
			'user_number_logins'=>($data['User Active']=='' ?'': number($data['User Login Count']) ),



			'roles'=>$data['roles']
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

function contractors($_data, $db, $user) {
	global $db;

	$rtext_label='contractor';

	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	//print $sql;

	$adata=array();
	foreach ($db->query($sql) as $data) {
	

		switch ($data['User Active']) {
		case 'Yes':
			$user_active=_('Active');
			break;
		case 'No':
			$user_active=_('Suspended');
			break;
		case '':
			$user_active=_("Don't set up");
			break;
		default:
			$user_active=$data['User Active'];
			break;
		}
		
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


			'birthday'=>(($data['Staff Birthday']=='' or $data['Staff Birthday']=='0000-00-00 00:00:00' ) ?'': strftime("%e %b %Y", strtotime($data['Staff Birthday'].' +0:00'))),

			'official_id'=>$data['Staff Official ID' ],
			'email'=>$data['Staff Email'],
			'telephone'=>$data['Staff Telephone Formated'],
			'next_of_kind'=>$data['Staff Next of Kind'],
			'from'=>(($data['Staff Valid From']=='' or $data['Staff Valid From']=='0000-00-00 00:00:00' ) ?'': strftime("%e %b %Y", strtotime($data['Staff Valid From'].' +0:00'))),

			'until'=>(($data['Staff Valid To']=='' or  $data['Staff Valid To']=='0000-00-00 00:00:00' ) ?'': strftime("%e %b %Y", strtotime($data['Staff Valid To'].' +0:00'))),
			'type'=>$type,


			'supervisors'=>$data['supervisors'],

			'job_title'=>$data['Staff Job Title'],
			'user_login'=>$data['User Handle'],
			'user_active'=>$user_active,
			'user_last_login'=>($data['User Last Login']=='' ?'': strftime("%a %e %b %Y %H:%M %Z", strtotime($data['User Last Login'].' +0:00'))),
			'user_number_logins'=>($data['User Active']=='' ?'': number($data['User Login Count']) ),



			'roles'=>$data['roles']
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
