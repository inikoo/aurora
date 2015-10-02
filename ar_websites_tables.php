<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 20:14:17 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


if (!$user->can_view('sites')) {
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
case 'websites':
	websites(get_table_parameters(), $db, $user);
	break;
case 'pages':
	pages(get_table_parameters(), $db, $user);
	break;

default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function websites($_data, $db, $user) {
	global $db;
	$rtext_label='website';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	// print $sql;


	foreach ($db->query($sql) as $data) {


		$adata[]=array(
			'id'=>(integer) $data['Site Key'],
			'code'=>$data['Site Code'],
			'name'=>$data['Site Name'],
			'url'=>($data['Site SSL']=='Yes'?'https://':'http://').$data['Site URL'],
			'users'=>number($data['Site Total Acc Users']),
			'visitors'=>number($data['Site Total Acc Visitors']),
			'requests'=>number($data['Site Total Acc Requests']),
			'sessions'=>number($data['Site Total Acc Sessions']),
			'pages'=>number($data['Site Number Pages']),
			'pages_products'=>number($data['Site Number Pages with Products']),
			'pages_out_of_stock'=>number($data['Site Number Pages with Out of Stock Products']),
			'pages_out_of_stock_percentage'=>percentage($data['Site Number Pages with Out of Stock Products'], $data['Site Number Pages with Products']),
			'products'=>number($data['Site Number Products']),
			'out_of_stock'=>number($data['Site Number Out of Stock Products']),
			'out_of_stock_percentage'=>percentage($data['Site Number Out of Stock Products'], $data['Site Number Products']),
			'email_reminders_customers'=>number($data['Site Number Back in Stock Reminder Customers']),
			'email_reminders_products'=>number($data['Site Number Back in Stock Reminder Products']),
			'email_reminders_waiting'=>number($data['Site Number Back in Stock Reminder Waiting']),
			'email_reminders_ready'=>number($data['Site Number Back in Stock Reminder Ready']),
			'email_reminders_sent'=>number($data['Site Number Back in Stock Reminder Sent']),
			'email_reminders_cancelled'=>number($data['Site Number Back in Stock Reminder Cancelled'])

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
