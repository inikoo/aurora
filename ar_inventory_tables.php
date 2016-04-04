<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 15:38:12 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/natural_language.php';


if (!$user->can_view('parts')) {
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

case 'parts':
	parts(get_table_parameters(), $db, $user);
	break;
case 'supplier_parts':
	supplier_parts(get_table_parameters(), $db, $user);
	break;
default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}



function parts($_data, $db, $user) {


	$rtext_label='part';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {



			$adata[]=array(
				'id'=>(integer)$data['Part SKU'],
				'warehouse_key'=>(integer)$data['Warehouse Key'],
				'warehouse'=>$data['Warehouse Code'],
				'reference'=>$data['Part Reference'],
				'formatted_sku'=>sprintf("SKU%05d", $data['Part SKU']),
				'reference'=>$data['Part Reference'],
				'description'=>$data['Part Unit Description'],
				'products'=>$data['Part XHTML Currently Used In'],


			);


		}
	}else {
		print_r($error_info=$db->errorInfo());
		print $sql;
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


function supplier_parts($_data, $db, $user) {


	$rtext_label='supplier part';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {

			switch ($data['Supplier Part Status']) {
			case 'Available':
				$status=sprintf('<i class="fa fa-square success" title="%s"></i>', _('Available'));
				break;
			case 'NoAvailable':
				$status=sprintf('<i class="fa fa-square-o warning" title="%s"></i>', _('No available'));

				break;
			case 'Discontinued':
				$status=sprintf('<i class="fa fa-ban error" title="%s"></i>', _('Discontinued'));

				break;
			default:
				$status=$data['Supplier Part Status'];
				break;
			}

            if($data['Supplier Part Cost']!=''){
                $_cost=json_decode($data['Supplier Part Cost'],true);
                $cost=money($_cost['Cost'],$_cost['Currency']);
            }else{
                $cost='';
            }

			$adata[]=array(
				'id'=>(integer)$data['Supplier Part Part SKU'],
				'supplier_key'=>(integer)$data['Supplier Part Supplier Key'],
				'part_key'=>(integer)$data['Supplier Part Part SKU'],
				'part_reference'=>$data['Part Reference'],
				'reference'=>$data['Supplier Part Reference'],
				'formatted_sku'=>sprintf("SKU%05d", $data['Supplier Part Part SKU']),
				'part_description'=>'<span style="min-width:80px;display: inline-block;" class="link padding_right_10" onClick="part/'.$data['Supplier Part Part SKU'].'">'.$data['Part Reference'].'</span> '.$data['Part Unit Description'],

				'description'=>$data['Part Unit Description'],
				'status'=>$status,
				'cost'=>$cost,
				
			);


		}
	}else {
		print_r($error_info=$db->errorInfo());
		print $sql;
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
