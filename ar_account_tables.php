<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 October 2015 at 18:06:17 CET, Pisa-Milan (train), Italy
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
case 'data_sets':
	data_sets(get_table_parameters(), $db, $user, $account);
	break;
default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}

function data_sets($_data, $db, $user,$account) {
	
	$rtext_label='data sets';
	include_once 'prepare_table/init.php';
	include_once 'utils/natural_language.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

            switch ($data['Data Sets Code']) {
                case 'Timeseries':
                    $name=_('Timeseries');
                    break;
                    case 'Images':
                    $name=_('Images');
                    break;
                     case 'Attachments':
                    $name=_('Attachments');
                    break;
                     case 'OSF':
                    $name=_('Order transactions timeseries');
                    break;
                     case 'ISF':
                    $name=_('Inventory transactions timeseries');
                    break;
                     
                default:
                    $name=$data['Data Sets Code'];
                    break;
            }
            
			
			$adata[]=array(
				'id'=>(integer) $data['Data Sets Key'],
				'name'=>$name,
				'sets'=>number($data['Data Sets Number Sets']),
				'items'=>number($data['Data Sets Number Items']),
				'size'=>file_size($data['Data Sets Size']),
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
