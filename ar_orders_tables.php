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
	$rtext_label='orders';
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

function customers_server($_data,$db,$user) {

	$rtext_label='store';
	include_once 'prepare_table/init.php';



	$sql="select `Store Key`,`Store Name`,`Store Code`,`Store Contacts`,`Store Total Users`, (`Store Active Contacts`+`Store Losing Contacts`) as active,`Store New Contacts`,`Store Lost Contacts`,`Store Losing Contacts`,
         `Store Contacts With Orders`,(`Store Active Contacts With Orders`+`Store Losing Contacts With Orders`)as active_with_orders,`Store New Contacts With Orders`,`Store Lost Contacts With Orders`,`Store Losing Contacts With Orders` from  `Store Dimension`    $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";




	$total_contacts=0;
	$total_active_contacts=0;
	$total_new_contacts=0;
	$total_lost_contacts=0;
	$total_losing_contacts=0;
	$total_contacts_with_orders=0;
	$total_active_contacts_with_orders=0;
	$total_new_contacts_with_orders=0;
	$total_lost_contacts_with_orders=0;
	$total_losing_contacts_with_orders=0;


	foreach ($db->query($sql) as $data) {

		$total_contacts+=$row['Store Contacts'];

		$total_active_contacts+=$row['active'];
		$total_new_contacts+=$row['Store New Contacts'];
		$total_lost_contacts+=$row['Store Lost Contacts'];
		$total_losing_contacts+=$row['Store Losing Contacts'];
		$total_contacts_with_orders+=$row['Store Contacts With Orders'];
		$total_active_contacts_with_orders+=$row['active_with_orders'];
		$total_new_contacts_with_orders+=$row['Store New Contacts With Orders'];
		$total_lost_contacts_with_orders+=$row['Store Lost Contacts With Orders'];
		$total_losing_contacts_with_orders+=$row['Store Losing Contacts With Orders'];




		$contacts=number($row['Store Contacts']);
		$new_contacts=number($row['Store New Contacts']);
		$active_contacts=number($row['active']);
		$losing_contacts=number($row['Store Losing Contacts']);
		$lost_contacts=number($row['Store Lost Contacts']);
		$contacts_with_orders=number($row['Store Contacts With Orders']);
		$new_contacts_with_orders=number($row['Store New Contacts With Orders']);
		$active_contacts_with_orders=number($row['active_with_orders']);
		$losing_contacts_with_orders=number($row['Store Losing Contacts With Orders']);
		$lost_contacts_with_orders=number($row['Store Lost Contacts With Orders']);
		$total_users=$row['Store Total Users'];

		//  $contacts_with_orders=number($row['contacts_with_orders']);
		// $active_contacts=number($row['active_contacts']);
		// $new_contacts=number($row['new_contacts']);
		// $lost_contacts=number($row['lost_contacts']);
		// $new_contacts_with_orders=number($row['new_contacts']);


		/*
                if ($parameters['percentages']) {
                    $contacts_with_orders=percentage($row['contacts_with_orders'],$total_contacts_with_orders);
                    $active_contacts=percentage($row['active_contacts'],$total_active);
                    $new_contacts=percentage($row['new_contacts'],$total_new);
                    $lost_contacts=percentage($row['los_contactst'],$total_lost);
                    $contacts=percentage($row['contacts'],$total_contacts);
                    $new_contacts_with_orders=percentage($row['new_contacts'],$total_new_contacts);

                } else {
                    $contacts_with_orders=number($row['contacts_with_orders']);
                    $active_contacts=number($row['active_contacts']);
                    $new_contacts=number($row['new_contacts']);
                    $lost_contacts=number($row['lost_contacts']);
                    $contacts=number($row['contacts']);
                    $new_contacts_with_orders=number($row['new_contacts']);

                }
        */
		$adata[]=array(
			'store_key'=>$row['Store Key'],
			'code'=>$row['Store Code'],
			'name'=>$row['Store Name'],
			'contacts'=>(integer) $row['Store Contacts'],
			'active_contacts'=>(integer) $row['active'],
			'new_contacts'=>(integer) $row['Store New Contacts'],
			'lost_contacts'=>(integer) $row['Store Lost Contacts'],
			'losing_contacts'=>(integer) $row['Store Losing Contacts'],
			'contacts_with_orders'=>$contacts_with_orders,
			'active_contacts_with_orders'=>$active_contacts_with_orders,
			'new_contacts_with_orders'=>$new_contacts_with_orders,
			'lost_contacts_with_orders'=>$lost_contacts_with_orders,
			'losing_contacts_with_orders'=>$losing_contacts_with_orders,
			'users'=>$total_users


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
		// $total_contacts=number($total_contacts);
		// $total_active_contacts=number($total_active_contacts);
		// $total_new_contacts=number($total_new_contacts);
		// $total_lost_contacts=number($total_lost_contacts);
		// $total_losing_contacts=number($total_losing_contacts);
		// $total_contacts_with_orders=number($total_contacts_with_orders);
		// $total_active_contacts_with_orders=number($total_active_contacts_with_orders);
		// $total_new_contacts_with_orders=number($total_new_contacts_with_orders);
		// $total_lost_contacts_with_orders=number($total_lost_contacts_with_orders);
		// $total_losing_contacts_with_orders=number($total_losing_contacts_with_orders);

		// $sum_total=number($total_contacts_with_orders);
		// $sum_active=number($total_active_contacts);
		// $sum_new=number($total_new_contacts);
		// $sum_lost=number($total_lost_contacts);
		// $sum_contacts=number($total_contacts);
		// $sum_new_contacts=number($total_new_contacts);
	}


	$adata[]=array(
		'store_key'=>'',
		'name'=>'',
		'code'=>_('Total'),
		'contacts'=>(integer) $total_contacts,
		'active_contacts'=>(integer) $total_active_contacts,
		'new_contacts'=>(integer) $total_new_contacts,
		'lost_contacts'=>(integer) $total_lost_contacts,
		'losing_contacts'=>(integer) $total_losing_contacts,
		'contacts_with_orders'=>(integer) $total_contacts_with_orders,
		'active_contacts_with_orders'=>(integer) $total_active_contacts_with_orders,
		'new_contacts_with_orders'=>(integer) $total_new_contacts_with_orders,
		'lost_contacts_with_orders'=>(integer) $total_lost_contacts_with_orders,
		'losing_contacts_with_orders'=>(integer) $total_losing_contacts_with_orders,
		'users'=>(integer) $total_users


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
