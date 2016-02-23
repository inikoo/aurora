<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 January 2016 at 14:53:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/date_functions.php';





if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {



case 'csv':
	$data=prepare_values($_REQUEST, array(
			'id'=>array('type'=>'key', 'optional'=>true),
			'type'=>array('type'=>'string', 'optional'=>true),
			'parent'=>array('type'=>'string', 'optional'=>true),
			'parent_key'=>array('type'=>'string', 'optional'=>true),

		));
	get_csv_records($db, $data);
	break;
default:
	$response=array('state'=>405, 'resp'=>'tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function get_csv_records($db, $data) {


	if (!isset($data['id'])   ) {
		if ( isset($data['type']) and isset($data['parent']) and isset($data['parent_key'])) {
			$sql=sprintf('select `Timeseries Key` from `Timeseries Dimension` where `Timeseries Type`=%s and `Timeseries Parent`=%s and `Timeseries Parent Key`=%d',
				prepare_mysql($data['type']),
				prepare_mysql( $data['parent']),
				$data['parent_key']
			);
			if ($result=$db->query($sql)) {
				if ($row = $result->fetch()) {
					$data['id']=$row['Timeseries Key'];
				}else {
					$response=array('state'=>405, 'resp'=>'unable to find timeserie');
					echo json_encode($response);
					exit;
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}


		}else {
			$response=array('state'=>405, 'resp'=>'unable to indentify timeserie');
			echo json_encode($response);
			exit;
		}
	}



	$table='`Timeseries Record Dimension` TR ';


	$where=sprintf(' where `Timeseries Record Timeseries Key`=%d', $data['id']);
	$order='`Timeseries Record Date`';
	$fields="`Timeseries Record Type`,`Timeseries Record Date`,`Timeseries Record Float A`,`Timeseries Record Float B`,`Timeseries Record Float C`,`Timeseries Record Float D`,`Timeseries Record Integer A`,`Timeseries Record Integer B`";

	$sql="select $fields from $table $where  order by $order ";
	$adata=array();

	if ($result=$db->query($sql)) {

		$adata[]=array(
			'date'=>'timestamp',
			'float_a'=>'open',
			'float_b'=>'float_b',
			'float_c'=>'float_c',
			'float_d'=>'float_d',
			'integer_a'=>'volume',
			'integer_b'=>'integer_b'

		);

		foreach ($result as $data) {
			$adata[]=array(
				'date'=>date("U", strtotime($data['Timeseries Record Date'].' +0:00')),
				'float_a'=>$data['Timeseries Record Float A'],
				'float_b'=>$data['Timeseries Record Float B'],
				'float_c'=>$data['Timeseries Record Float C'],
				'float_d'=>$data['Timeseries Record Float D'],
				'integer_a'=>$data['Timeseries Record Integer A'],
				'integer_b'=>$data['Timeseries Record Integer B'],

			);
		}




		outputCSV($adata);


	}else {
		print_r($error_info=$db->errorInfo());
		print $sql;
		exit;
	}



}


function outputCSV($data) {

	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=file.csv");
	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

	$output = fopen("php://output", "w");
	foreach ($data as $row) {
		fputcsv($output, $row);
	}
	fclose($output);
}


?>
