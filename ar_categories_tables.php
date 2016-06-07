<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 28 December 2015 at 16:58:19 GMT+8, Kuala Lumpur, Malaysia
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

case 'categories':
	categories(get_table_parameters(), $db, $user);
	break;
case 'subject_categories':
	subject_categories(get_table_parameters(), $db, $user);
	break;
	
default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}



function categories($_data, $db, $user) {
	$rtext_label='category';
	include_once 'prepare_table/init.php';


	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();


	foreach ($db->query($sql) as $data) {

		switch ($data['Category Branch Type']) {
		case 'Root':
			$level=_('Root');
			break;
		case 'Head':
			$level=_('Head');
			break;
		case 'Node':
			$level=_('Node');
			break;
		default:
			$level=$data['Category Branch Type'];
			break;
		}
		$level=$data['Category Branch Type'];


		$adata[]=array(
			'id'=>(integer) $data['Category Key'],
			'store_key'=>(integer) $data['Category Store Key'],
			'code'=>$data['Category Code'],
			'label'=>$data['Category Label'],
			'subjects'=>number($data['Category Number Subjects']),
			'level'=>$level,
			'subcategories'=>number($data['Category Children']),
			'percentage_assigned'=>percentage($data['Category Number Subjects'], ($data['Category Number Subjects']+$data['Category Subjects Not Assigned']))
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


function subject_categories($_data, $db, $user) {

	$rtext_label='category';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();


	foreach ($db->query($sql) as $data) {

		switch ($data['Category Branch Type']) {
		case 'Root':
			$level=_('Root');
			break;
		case 'Head':
			$level=_('Head');
			break;
		case 'Node':
			$level=_('Node');
			break;
		default:
			$level=$data['Category Branch Type'];
			break;
		}
		$level=$data['Category Branch Type'];


		$adata[]=array(
			'id'=>(integer) $data['Category Key'],
			'position'=>$data['Category Position'],
			'store_key'=>(integer) $data['Category Store Key'],
			'code'=>$data['Category Code'],
			'label'=>$data['Category Label'],
			'subjects'=>number($data['Category Number Subjects']),
			'level'=>$level,
			'subcategories'=>number($data['Category Children']),
			'percentage_assigned'=>percentage($data['Category Number Subjects'], ($data['Category Number Subjects']+$data['Category Subjects Not Assigned']))
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
