<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2015 at 09:35:34 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/date_functions.php';


if (!$user->can_view('stores')) {
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
case 'stores':
	stores(get_table_parameters(), $db, $user);
	break;

case 'products':
	products(get_table_parameters(), $db, $user);
	break;
case 'categories':
	categories(get_table_parameters(), $db, $user);
	break;

case 'category_all_products':
	category_all_products(get_table_parameters(), $db, $user);
	break;


default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function stores($_data, $db, $user) {


	$rtext_label='store';

	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	// print $sql;
	foreach ($db->query($sql) as $data) {


		$adata[]=array(
			'access'=>(in_array($data['Store Key'], $user->stores)?'':'<i class="fa fa-lock "></i>'),

			'id'=>(integer) $data['Store Key'],
			'code'=>$data['Store Code'],
			'name'=>$data['Store Name'],

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


function products($_data, $db, $user) {



	if ($_data['parameters']['parent']=='customer_favourites')
		$rtext_label='product favourited';
	else
		$rtext_label='product';





	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	$adata=array();
	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			$associated=sprintf('<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Product ID']);


			$adata[]=array(

				'id'=>(integer) $data['Product ID'],
				'store_key'=>(integer) $data['Store Key'],
				'associated'=>$associated,
				'store'=>$data['Store Code'],
				'code'=>$data['Product Code'],
				'name'=>$data['Product Name'],
				'price'=>money($data['Product Price'], $data['Store Currency Code']),
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


function categories($_data, $db, $user) {

	$rtext_label='category';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

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

function category_all_products($_data, $db, $user) {


	$rtext_label='product';

	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	$adata=array();
	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			if ($data['associated'])
				$associated=sprintf('<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Product ID']);
			else
				$associated=sprintf('<i key="%d" class="fa fa-fw fa-unlink button very_discreet" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Product ID']);


			$adata[]=array(
				'id'=>(integer) $data['Product ID'],
				'associated'=>$associated,
				'code'=>$data['Product Code'],
				'name'=>$data['Product Name'],
				'price'=>money($data['Product Price'], $data['Store Currency Code']),
				'family'=>$data['Category Code']
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
