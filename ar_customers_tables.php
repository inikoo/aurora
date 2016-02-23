<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2015 18:30:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Store.php';

require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


if (!$user->can_view('customers')) {
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
case 'customers':
	customers(get_table_parameters(), $db, $user);
	break;
case 'lists':
	lists(get_table_parameters(), $db, $user);
	break;
case 'customers_server':
	customers_server(get_table_parameters(), $db, $user);
	break;
case 'categories':
	categories(get_table_parameters(), $db, $user);
	break;
default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}

function customers($_data, $db, $user) {



	if ($_data['parameters']['parent']=='favourites')
		$rtext_label='customer with favourites';
	else
		$rtext_label='customer';

	include_once 'prepare_table/init.php';

	$sql="select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {


			if ($parameters['parent']=='category') {
				$category_other_value=$data['Other Note'];
			}else {
				$category_other_value='';
			}






			if ($data['Customer Orders']==0)
				$last_order_date='';
			else
				$last_order_date=strftime("%e %b %y", strtotime($data['Customer Last Order Date']." +00:00"));

			if ($data['Customer Orders Invoiced']==0 or $data['Customer Last Invoiced Order Date']=='')
				$last_invoice_date='';
			else
				$last_invoice_date=strftime("%e %b %y", strtotime($data['Customer Last Invoiced Order Date']." +00:00"));




			$contact_since=strftime("%e %b %y", strtotime($data['Customer First Contacted Date']." +00:00"));


			if ($data['Customer Billing Address Link']=='Contact')
				$billing_address='<i>'._('Same as Contact').'</i>';
			else
				$billing_address=$data['Customer XHTML Billing Address'];

			if ($data['Customer Delivery Address Link']=='Contact')
				$delivery_address='<i>'._('Same as Contact').'</i>';
			elseif ($data['Customer Delivery Address Link']=='Billing')
				$delivery_address='<i>'._('Same as Billing').'</i>';
			else
				$delivery_address=$data['Customer XHTML Main Delivery Address'];

			switch ($data['Customer Type by Activity']) {
			case 'Inactive':
				$activity=_('Lost');
				break;
			case 'Active':
				$activity=_('Active');
				break;
			case 'Prospect':
				$activity=_('Prospect');
				break;
			default:
				$activity=$data['Customer Type by Activity'];
				break;
			}

			$adata[]=array(
				'id'=>(integer) $data['Customer Key'],
				'store_key'=>$data['Customer Store Key'],
				'formatted_id'=>sprintf("%06d", $data['Customer Key']),
				'name'=>$data['Customer Name'],
				'company_name'=>$data['Customer Company Name'],
				'contact_name'=>$data['Customer Main Contact Name'],

				'location'=>$data['Customer Location'],

				'invoices'=>(integer) $data['Customer Orders Invoiced'],
				'email'=>$data['Customer Main Plain Email'],
				'telephone'=>$data['Customer Main XHTML Telephone'],
				'mobile'=>$data['Customer Main XHTML Mobile'],
				'orders'=>number($data['Customer Orders']),

				'last_order'=>$last_order_date,
				'last_invoice'=>$last_invoice_date,
				'contact_since'=>$contact_since,

				'other_value'=>$category_other_value,

				'total_payments'=>money($data['Customer Net Payments'], $currency),
				'net_balance'=>money($data['Customer Net Balance'], $currency),
				'total_refunds'=>money($data['Customer Net Refunds'], $currency),
				'total_profit'=>money($data['Customer Profit'], $currency),
				'balance'=>money($data['Customer Outstanding Net Balance'], $currency),
				'account_balance'=>money($data['Customer Account Balance'], $currency),


				'top_orders'=>percentage($data['Customer Orders Top Percentage'], 1, 2),
				'top_invoices'=>percentage($data['Customer Invoices Top Percentage'], 1, 2),
				'top_balance'=>percentage($data['Customer Balance Top Percentage'], 1, 2),
				'top_profits'=>percentage($data['Customer Profits Top Percentage'], 1, 2),
				'address'=>$data['Customer Main XHTML Address'],
				'billing_address'=>$billing_address,
				'delivery_address'=>$delivery_address,

				'activity'=>$activity,
				'logins'=>number($data['Customer Number Web Logins']),
				'failed_logins'=>number($data['Customer Number Web Failed Logins']),
				'requests'=>number($data['Customer Number Web Requests']),


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


function lists($_data, $db, $user) {

	$rtext_label='list';
	include_once 'prepare_table/init.php';

	$sql="select $fields from `List Dimension` CLD $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();
	if ($result=$db->query($sql)) {

		foreach ($result as $data) {
			switch ($data['List Type']) {
			case 'Static':
				$customer_list_type=_('Static');
				$items=number($data['List Number Items']);
				break;
			default:
				$customer_list_type=_('Dynamic');
				$items='~'.number($data['List Number Items']);
				break;

			}

			$adata[]=array(
				'id'=>(integer) $data['List key'],
				'type'=>$customer_list_type,
				'name'=>$data['List Name'],
				'creation_date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['List Creation Date']." +00:00")),
				//'add_to_email_campaign_action'=>'<div class="buttons small"><button class="positive" onClick="add_to_email_campaign('.$data['List key'].')">'._('Add Emails').'</button></div>',
				'items'=>$items,
				'delete'=>'<img src="/art/icons/cross.png"/>'
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


function customers_server($_data, $db, $user) {

	$rtext_label='store';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";




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


	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			$total_contacts+=$data['Store Contacts'];

			$total_active_contacts+=$data['active'];
			$total_new_contacts+=$data['Store New Contacts'];
			$total_lost_contacts+=$data['Store Lost Contacts'];
			$total_losing_contacts+=$data['Store Losing Contacts'];
			$total_contacts_with_orders+=$data['Store Contacts With Orders'];
			$total_active_contacts_with_orders+=$data['active_with_orders'];
			$total_new_contacts_with_orders+=$data['Store New Contacts With Orders'];
			$total_lost_contacts_with_orders+=$data['Store Lost Contacts With Orders'];
			$total_losing_contacts_with_orders+=$data['Store Losing Contacts With Orders'];




			$contacts=number($data['Store Contacts']);
			$new_contacts=number($data['Store New Contacts']);
			$active_contacts=number($data['active']);
			$losing_contacts=number($data['Store Losing Contacts']);
			$lost_contacts=number($data['Store Lost Contacts']);
			$contacts_with_orders=number($data['Store Contacts With Orders']);
			$new_contacts_with_orders=number($data['Store New Contacts With Orders']);
			$active_contacts_with_orders=number($data['active_with_orders']);
			$losing_contacts_with_orders=number($data['Store Losing Contacts With Orders']);
			$lost_contacts_with_orders=number($data['Store Lost Contacts With Orders']);
			$total_users=$data['Store Total Users'];

			//  $contacts_with_orders=number($data['contacts_with_orders']);
			// $active_contacts=number($data['active_contacts']);
			// $new_contacts=number($data['new_contacts']);
			// $lost_contacts=number($data['lost_contacts']);
			// $new_contacts_with_orders=number($data['new_contacts']);


			/*
                if ($parameters['percentages']) {
                    $contacts_with_orders=percentage($data['contacts_with_orders'],$total_contacts_with_orders);
                    $active_contacts=percentage($data['active_contacts'],$total_active);
                    $new_contacts=percentage($data['new_contacts'],$total_new);
                    $lost_contacts=percentage($data['los_contactst'],$total_lost);
                    $contacts=percentage($data['contacts'],$total_contacts);
                    $new_contacts_with_orders=percentage($data['new_contacts'],$total_new_contacts);

                } else {
                    $contacts_with_orders=number($data['contacts_with_orders']);
                    $active_contacts=number($data['active_contacts']);
                    $new_contacts=number($data['new_contacts']);
                    $lost_contacts=number($data['lost_contacts']);
                    $contacts=number($data['contacts']);
                    $new_contacts_with_orders=number($data['new_contacts']);

                }
        */
			$adata[]=array(
				'store_key'=>$data['Store Key'],
				'code'=>$data['Store Code'],
				'name'=>$data['Store Name'],
				'contacts'=>(integer) $data['Store Contacts'],
				'active_contacts'=>(integer) $data['active'],
				'new_contacts'=>(integer) $data['Store New Contacts'],
				'lost_contacts'=>(integer) $data['Store Lost Contacts'],
				'losing_contacts'=>(integer) $data['Store Losing Contacts'],
				'contacts_with_orders'=>$contacts_with_orders,
				'active_contacts_with_orders'=>$active_contacts_with_orders,
				'new_contacts_with_orders'=>$new_contacts_with_orders,
				'lost_contacts_with_orders'=>$lost_contacts_with_orders,
				'losing_contacts_with_orders'=>$losing_contacts_with_orders,
				'users'=>$total_users


			);

		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
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
		'code'=>_('Total').($filtered>0?' '.'<i class="fa fa-filter fa-fw"></i>':''),
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
