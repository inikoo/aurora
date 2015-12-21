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
case 'billingregion_taxcategory':
	billingregion_taxcategory(get_table_parameters(), $db, $user,$account);
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
	//include_once 'prepare_table/init.php';
	include_once 'utils/available_reports.php';



	//$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


	$adata=array();

	// print $sql;


	foreach ($available_reports as $key=>$data) {

		$adata[]=array(
			'name'=>$data['Label'],
			'report_request'=>'report/'.$key,
			'section'=>$data['GroupLabel'],
			'section_request'=>'reports/'.$data['Group']

		);

	}

	$_order=(isset($_data['o'])  ?$_data['o']:'id');
	$_dir=((isset($_data['od']) and  preg_match('/desc/i', $_data['od']) ) ?'desc':'');




	$rtext=get_rtext('report', count($available_reports));

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'total_records'=> count($available_reports)

		)
	);
	echo json_encode($response);
}

function billingregion_taxcategory($_data, $db, $user,$account) {
	
	$rtext_label='record';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

	$adata=array();


	if ($result=$db->query($sql)) {

		foreach ($result as $data) {




			switch ($data['Invoice Billing Region']) {
			case 'EU':
				$billing_region=_('European Union');
				break;
			case 'NOEU':
				$billing_region=_('Outside European Union');
				break;	
			case 'GBIM':
				$billing_region='GB+IM';
				break;	
			default:
				$billing_region=$data['Invoice Billing Region'];
				break;
			}

		



			$adata[]=array(

				'billing_region'=>$billing_region,
				'tax_code'=> sprintf('<span title="%s">%s</span>', ($data['Invoice Tax Code']=='UNK'?_('Unknown tax code'):$data['Tax Category Name']),$data['Invoice Tax Code']),
				
				
				'invoices'=>number($data['invoices'])  ,  
                'refunds'=>number($data['refunds']) ,
                 'customers'=>number($data['customers']) ,
                  'tax'=>money($data['tax'],$account->get('Account Currency')) ,
                  'net'=>money($data['net'],$account->get('Account Currency')) ,
                  'total'=>money($data['total'],$account->get('Account Currency')) ,
				

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
