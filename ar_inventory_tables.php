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
	parts(get_table_parameters(), $db, $user,'active');
	break;
case 'discontinued_parts':
	parts(get_table_parameters(), $db, $user,'discontinued');
	break;	
case 'barcodes':
	barcodes(get_table_parameters(), $db, $user);
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



function parts($_data, $db, $user, $type) {

	if ($type=='discontinued') {
		$extra_where=' and `Part Status`="Not In Use"';
		$rtext_label='discontinued part';

	}elseif($type=='active') {
		$extra_where=' and `Part Status`="In Use"';
		$rtext_label='part';

	}else {
		$extra_where='';
		$rtext_label='part';

	}


	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {



			$adata[]=array(
				'id'=>(integer)$data['Part SKU'],

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
				$status=sprintf('<i class="fa fa-stop success" title="%s"></i>', _('Available'));
				break;
			case 'NoAvailable':
				$status=sprintf('<i class="fa fa-stop warning" title="%s"></i>', _('No available'));

				break;
			case 'Discontinued':
				$status=sprintf('<i class="fa fa-ban error" title="%s"></i>', _('Discontinued'));

				break;
			default:
				$status=$data['Supplier Part Status'];
				break;
			}

			switch ($data['Part Stock Status']) {
			case 'Surplus':
				$stock_status='<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Optimal':
				$stock_status='<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Low':
				$stock_status='<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Critical':
				$stock_status='<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Out_Of_Stock':
				$stock_status='<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
				break;
			case 'Error':
				$stock_status='<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
				break;
			default:
				$stock_status=$data['Part Stock Status'];
				break;
			}



			$adata[]=array(
				'id'=>(integer)$data['Supplier Part Key'],
				'supplier_key'=>(integer)$data['Supplier Part Supplier Key'],
				'part_key'=>(integer)$data['Supplier Part Part SKU'],
				'part_reference'=>$data['Part Reference'],
				'reference'=>$data['Supplier Part Reference'],
				'formatted_sku'=>sprintf("SKU%05d", $data['Supplier Part Part SKU']),
				'part_description'=>'<span style="min-width:80px;display: inline-block;" class="link padding_right_10" onClick="change_view(\'part/'.$data['Supplier Part Part SKU'].'\')">'.$data['Part Reference'].'</span> '.$data['Part Unit Description'],

				'description'=>$data['Part Unit Description'],
				'status'=>$status,
				'cost'=>money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']),
				'packing'=>'<div style="float:left;min-width:20px;text-align:right"><span>'.$data['Supplier Part Units Per Package'].'</span></div><div style="float:left;min-width:70px;text-align:left"> <i  class="fa fa-arrow-right very_discret padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div> <span class="discret">'.($data['Supplier Part Units Per Package']*$data['Supplier Part Packages Per Carton'].'</span>'),
				'stock'=>number(floor($data['Part Current Stock']))." $stock_status"
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


function barcodes($_data, $db, $user) {


	$rtext_label='barcodes';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	//print $sql;
	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {

			switch ($data['Barcode Status']) {
			case 'Available':
				$status=sprintf('<i class="fa fa-barcode fa-fw" ></i> %s', _('Available'));
				break;
			case 'Used':
				$status=sprintf('<span class="disabled"><i class="fa fa-cube fa-fw " ></i> %s', _('Used').'</span>');

				break;
			case 'Reserved':
				$status=sprintf('<span class="disabled"> <i class="fa fa-shield fa-fw " ></i> %s', _('Reserved').'</span>');

				break;
			default:
				$status=$data['Barcode Status'];
				break;
			}
			if ($data['parts']!='') {
				$_parts=preg_split('/,/', $data['parts']);
				$assets=sprintf('<i class="fa fa-square fa-fw"></i> <span class="link" onClick="change_view(\'part/%d\')">%s</span>', $_parts[0], $_parts[1]);
			}else {
				$assets='';
			}

			$adata[]=array(
				'id'=>(integer)$data['Barcode Key'],

				'number'=>$data['Barcode Number'],

				'status'=>$status,
				'notes'=>$data['Barcode Sticky Note'],
				'assets'=>$assets

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
