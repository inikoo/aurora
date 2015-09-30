<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 23 September 2015 15:34:56 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'ar_edit_common.php';
require_once 'utils/table_functions.php';
require_once 'common_order_functions.php';

if (!$user->can_view('orders')) {
	echo json_encode(array('state'=>405,'resp'=>'Forbidden'));
	exit;
}


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'orders':
	orders(get_table_parameters(),$db,$user);
	break;
case 'invoices':
	invoices(get_table_parameters(),$db,$user);
	break;
case 'delivery_notes':
	delivery_notes(get_table_parameters(),$db,$user);
	break;
case 'orders_server':
	orders_server(get_table_parameters(),$db,$user);
	break;
case 'invoices_server':
	invoices_server(get_table_parameters(),$db,$user);
	break;
case 'delivery_notes_server':
	delivery_notes_server(get_table_parameters(),$db,$user);
	break;
case 'invoice_categories':
	invoice_categories(get_table_parameters(),$db,$user);
	break;
default:
	$response=array('state'=>405,'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function orders($_data,$db,$user) {
	global $db;
	$rtext_label='order';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();



	foreach ($db->query($sql) as $data) {



		$adata[]=array(
			'id'=>(integer)$data['Order Key'],
			'store_key'=> (integer) $data['Order Store Key'],
			'customer_key'=> (integer) $data['Order Customer Key'],
			'public_id'=>$data['Order Public ID'],
			'date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Date'].' +0:00')),
			'last_date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Order Last Updated Date'].' +0:00')),
			'customer'=>$data['Order Customer Name'],
			'dispatch_state'=>get_order_formated_dispatch_state($data['Order Current Dispatch State'],$data['Order Key']),// function in: common_order_functions.php
			'payment_state'=>get_order_formated_payment_state($data),

			'total_amount'=>money($data['Order Total Amount'],$data['Order Currency'])


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

function delivery_notes($_data,$db,$user) {
	global $db;
	$rtext_label='delivery_note';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();



	foreach ($db->query($sql) as $data) {




		switch ($data['Delivery Note Type']) {
		case('Order'):
			$type=_('Order');
			break;
		case('Sample'):
			$type=_('Sample');
			break;
		case('Donation'):
			$type=_('Donation');
			break;
		case('Replacement'):
		case('Replacement & Shortages'):
			$type=_('Replacement');
		case('Shortages'):
			$type=_('Shortages');

		default:
			$type=$data['Delivery Note Type'];

		}

		switch ($data['Delivery Note Parcel Type']) {
		case('Pallet'):
			$parcel_type='P';
			break;
		case('Envelope'):
			$parcel_type='e';
			break;
		default:
			$parcel_type='b';

		}

		if ($data['Delivery Note Number Parcels']=='') {
			$parcels='?';
		}
		elseif ($data['Delivery Note Parcel Type']=='Pallet' and $data['Delivery Note Number Boxes']) {
			$parcels=number($data['Delivery Note Number Parcels']).' '.$parcel_type.' ('.$data['Delivery Note Number Boxes'].' b)';
		}
		else {
			$parcels=number($data['Delivery Note Number Parcels']).' '.$parcel_type;
		}




		$adata[]=array(
			'id'=>(integer)$data['Delivery Note Key'],
			'store_key'=> (integer) $data['Delivery Note Store Key'],
			'customer_key'=> (integer) $data['Delivery Note Customer Key'],

			'number'=>$data['Delivery Note ID'],
			'customer'=>$data['Delivery Note Customer Name'],

			'date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Delivery Note Date Created'].' +0:00')),
			'state'=>$data['Delivery Note XHTML State'],
			'weight'=>weight($data['Delivery Note Weight']),
			'parcels'=>$parcels,
			'type'=>$type,



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


function invoices($_data,$db,$user) {
	global $db;
	$rtext_label='invoice';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();



	foreach ($db->query($sql) as $data) {

		if ($data['Invoice Paid']=='Yes')
			$state=_('Paid');
		elseif ($data['Invoice Paid']=='Partially')
			$state=_('Partially Paid');

		else
			$state=_('No Paid');


		if ($data['Invoice Type']=='Invoice')
			$type=_('Invoice');
		elseif ($data['Invoice Type']=='CreditNote')
			$type=_('Credit Note');
		else
			$type=_('Refund');

		switch ($data['Invoice Main Payment Method']) {
		default:
			$method=$data['Invoice Main Payment Method'];
		}

		$adata[]=array(
			'id'=>(integer)$data['Invoice Key'],
			'store_key'=> (integer) $data['Invoice Store Key'],
			'customer_key'=> (integer) $data['Invoice Customer Key'],

			'number'=>$data['Invoice Public ID'],
			'customer'=>$data['Invoice Customer Name'],
			'date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Invoice Date'].' +0:00')),
			'total_amount'=>money($data['Invoice Total Amount'],$data['Invoice Currency']),
			'net'=>money($data['Invoice Total Net Amount'],$data['Invoice Currency']),
			'shipping'=>money($data['Invoice Shipping Net Amount'],$data['Invoice Currency']),
			'items'=>money($data['Invoice Items Net Amount'],$data['Invoice Currency']),
			'type'=>$type,
			'method'=>$method,
			'state'=>$state,


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

function orders_server($_data,$db,$user) {

	$rtext_label='store';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$total_orders=0;
	$total_invoices=0;
	$total_delivery_notes=0;
	$total_payments=0;


	foreach ($db->query($sql) as $data) {

		$total_orders+=$data['orders'];
		$total_invoices+=$data['invoices'];
		$total_delivery_notes+=$data['delivery_notes'];
		$total_payments+=$data['payments'];

		$adata[]=array(
			'store_key'=>$data['Store Key'],
			'code'=>$data['Store Code'],
			'name'=>$data['Store Name'],
			'orders'=>number($data['orders']),
			'delivery_notes'=>number($data['delivery_notes']),
			'invoices'=>number($data['invoices']),
			'payments'=>number($data['payments']),
		);

	}


	if ($parameters['percentages']) {
		$sum_total='100.00%';
		$sum_active='100.00%';
		$sum_new='100.00%';
		$sum_lost='100.00%';
		$sum_contacts='100.00%';
		$sum_new_contacts='100.00%';
	}
	else {

	}



	$adata[]=array(
		'store_key'=>'',
		'name'=>'',
		'code'=>_('Total').($filtered>0?' '.'<i class="fa fa-filter fa-fw"></i>':''),

		'orders'=>number($total_orders),
		'delivery_notes'=>number($total_delivery_notes),
		'invoices'=>number($total_invoices),
		'payments'=>number($total_payments),

	);



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
