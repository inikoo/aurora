<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2015 at 12:27:49 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';



if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'reports':
	reports(get_table_parameters(), $db, $user);
	break;


default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function reports($_data, $db, $user) {
	global $db;
	$rtext_label='report';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	// print $sql;


	foreach ($db->query($sql) as $data) {

		$adata[]=array(
			'id'=>(integer) $data['Report Key'],
			'name'=>$data['Report Name'],
			'section'=>$data['Report Section Name'],
			'section_request'=>$data['Report Section Request'],
			'report_request'=>$data['Report Request'],

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
