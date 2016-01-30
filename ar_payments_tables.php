<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 3 November 2015 at 15:07:32 CET, Tessera, Italy
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
case 'payment_service_providers':
	payment_service_providers(get_table_parameters(), $db, $user);
	break;
case 'accounts':
	payment_accounts(get_table_parameters(), $db, $user);
	break;
case 'payments':
	payments(get_table_parameters(), $db, $user);
	break;
default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function payment_service_providers($_data, $db, $user) {
	global $db, $account;
	$rtext_label='payment_service_provider';
	include_once 'prepare_table/init.php';

	$account_currency=$account->get('Account Currency');

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	foreach ($db->query($sql) as $data) {


		$other_currency=($account_currency!=$data['Payment Service Provider Currency']);

		$adata[]=array(
			'id'=>(integer) $data['Payment Service Provider Key'],
			'code'=>$data['Payment Service Provider Code'],
			'name'=>$data['Payment Service Provider Name'],
			'accounts'=>number($data['Payment Service Provider Accounts']),
			'transactions'=>number($data['Payment Service Provider Transactions']),
			'payments'=>money($data['Payment Service Provider Payments Amount'], $account_currency),
			'refunds'=>money($data['Payment Service Provider Refunds Amount'], $account_currency),
			'balance'=>money($data['Payment Service Provider Balance Amount'], $account_currency)
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


function payment_accounts($_data, $db, $user) {
	global $db, $account;
	$rtext_label='payment_account';
	include_once 'prepare_table/init.php';

	$account_currency=$account->get('Account Currency');

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();



	if ($result=$db->query($sql)) {

		foreach ($result as $data) {


			$other_currency=($account_currency!=$data['Payment Account Currency']);

			$adata[]=array(
				'id'=>(integer) $data['Payment Account Key'],
				'code'=>$data['Payment Account Code'],
				'name'=>$data['Payment Account Name'],
				'transactions'=>number($data['Payment Account Transactions']),
				'payments'=>money($data['Payment Account Payments Amount'], $account_currency),
				'refunds'=>money($data['Payment Account Refunds Amount'], $account_currency),
				'balance'=>money($data['Payment Account Balance Amount'], $account_currency)
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


function payments($_data, $db, $user) {
	global $db, $account;
	$rtext_label='transaction';
	include_once 'prepare_table/init.php';


	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {


			switch ($data['Payment Type']) {
			case 'Payment':
				$type=_('Payment');
				break;
			case 'Refund':
				$type=_('Refund');
				break;
			case 'Credit':
				$type=_('Credit');
				break;
			default:
				$type=$data['Payment Type'];
				break;
			}


			switch ($data['Payment Transaction Status']) {
			case 'Pending':
				$status=_('Pending');
				break;
			case 'Completed':
				$status=_('Completed');
				break;
			case 'Cancelled':
				$status=_('Cancelled');
				break;
			case 'Error':
				$status=_('Error');
				break;
			case 'Declined':
				$status=_('Declined');
				break;
			default:
				$status=$data['Payment Transaction Status'];
				break;
			}


			$notes='';



			$adata[]=array(
				'id'=>(integer) $data['Payment Key'],
				'reference'=>$data['Payment Transaction ID'],
				'currency'=>$data['Payment Currency Code'],
				'amount'=>money($data['Payment Amount'], $data['Payment Currency Code']),
				'date'=>money($data['Payment Amount'], $data['Payment Currency Code']),
				'formatted_id'=>sprintf("%05d", $data['Payment Key']),
				'type'=>$type,
				'status'=>$status,
				'notes'=>$notes,
				'date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Payment Last Updated Date'].' +0:00')),

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
