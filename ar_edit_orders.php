<?php
require_once 'common.php';
require_once 'ar_edit_common.php';
require_once 'class.Order.php';
require_once 'class.Staff.php';

require_once 'class.User.php';
include_once 'class.PartLocation.php';
require_once 'order_common_functions.php';



if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>407,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case('add_insurance'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'insurance_key'=>array('type'=>'key')

		));
	add_insurance($data);

	break;
case('remove_insurance'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'onptf_key'=>array('type'=>'key')

		));
	remove_insurance($data);

	break;
case('update_order_special_intructions'):

$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'value'=>array('type'=>'string')

		));
	update_order_special_intructions($data);
	break;

case('edit_delivery_note'):
	$data=prepare_values($_REQUEST,array(
			'key'=>array('type'=>'string'),
			'newvalue'=>array('type'=>'string'),
			'dn_key'=>array('type'=>'key'),

		));
	edit_delivery_note($data);
	break;
case('edit_tax_category_order'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'tax_code'=>array('type'=>'string')
		));
	edit_tax_category_order($data);
	break;
case('remove_credit_from_order'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'transaction_key'=>array('type'=>'key')
		));
	remove_credit_from_order($data);
	break;
case('edit_credit_to_order'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'amount'=>array('type'=>'numeric'),
			'transaction_key'=>array('type'=>'key'),

			'description'=>array('type'=>'string'),
			'tax_code'=>array('type'=>'string')
		));
	edit_credit_to_order($data);
	break;
case('add_credit_to_order'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'amount'=>array('type'=>'numeric'),
			'description'=>array('type'=>'string'),
			'tax_code'=>array('type'=>'string')
		));
	add_credit_to_order($data);
	break;
case('mark_all_for_refund_order'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key')
		));
	mark_all_for_refund_order($data);
	break;

	break;
case('cancel_saved_credit'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key')
		));
	cancel_saved_credit($data);
	break;

	break;
case('save_credit'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key')
		));
	save_credit($data);
	break;

	break;

case('set_as_dispatched_dn'):
	$data=prepare_values($_REQUEST,array(
			'dn_key'=>array('type'=>'key')
		));
	set_as_dispatched_dn($data);
	break;

	break;
case('approve_dispatching_dn'):

	$data=prepare_values($_REQUEST,array(
			'dn_key'=>array('type'=>'key')


		));
	approve_dispatching_dn($data);
	break;

case('set_as_dispatched_order'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key')
		));
	set_as_dispatched_order($data);
	break;

	break;
case('approve_dispatching_order'):

	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key')
		));
	approve_dispatching_order($data);
	break;

case('approve_dispatching_invoice'):

	$data=prepare_values($_REQUEST,array(
			'invoice_key'=>array('type'=>'key')
		));
	approve_dispatching_invoice($data);
	break;


case('assign_picker_and_packer_to_order'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'picker_key'=>array('type'=>'string'),
			'packer_key'=>array('type'=>'string'),
			'weight'=>array('type'=>'string'),
			'parcels'=>array('type'=>'string'),
			'parcel_type'=>array('type'=>'string')

		));
	assign_picker_and_packer_to_order($data);
	break;
case('quick_invoice'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'picker_key'=>array('type'=>'string'),
			'packer_key'=>array('type'=>'string'),
			'weight'=>array('type'=>'string'),
			'parcels'=>array('type'=>'string'),
			'parcel_type'=>array('type'=>'string')

		));
	quick_invoice($data);
	break;


case('update_percentage_discount'):
	$data=prepare_values($_REQUEST,array(
			'order_transaction_key'=>array('type'=>'key'),
			'percentage'=>array('type'=>'numeric'),
			'order_key'=>array('type'=>'key'),

		));
	update_percentage_discount($data);
	break;

case('approve_packing'):
	$data=prepare_values($_REQUEST,array(
			'dn_key'=>array('type'=>'key'),

		));
	approve_packing($data);
	break;
case('import_transactions_mals_e'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')

		));
	import_transactions_mals_e($data);
	break;
case('set_picking_aid_sheet_pending_as_picked'):
	$data=prepare_values($_REQUEST,array(
			'dn_key'=>array('type'=>'key'),
		));
	set_picking_aid_sheet_pending_as_picked($data);
	break;
case('set_packing_aid_sheet_pending_as_packed'):
	require_once 'class.Warehouse.php';

	$data=prepare_values($_REQUEST,array(
			'dn_key'=>array('type'=>'key'),
			'warehouse_key'=>array('type'=>'key')
		));
	$warehouse=new Warehouse($data['warehouse_key']);
	if (!$warehouse->id) {
		$response=array('state'=>400,'msg'=>'Warehouse not found');
		echo json_encode($response);
		exit;
	}
	$data['approve_pp']=($warehouse->data['Warehouse Approve PP Locked']=='No'?true:false);
	set_packing_aid_sheet_pending_as_packed($data);
	break;

case('delete_order_list'):
	$data=prepare_values($_REQUEST,array(
			'key'=>array('type'=>'key'),


		));
	delete_order_list($data);
	break;

case('delete_invoice_list'):
	$data=prepare_values($_REQUEST,array(
			'key'=>array('type'=>'key'),


		));
	delete_invoice_list($data);
	break;

case('delete_dn_list'):
	$data=prepare_values($_REQUEST,array(
			'key'=>array('type'=>'key'),


		));
	delete_dn_list($data);
	break;
case('pay_invoice'):
	if (!$user->can_view('orders'))
		exit();

	$data=prepare_values($_REQUEST,array(

			'invoice_key'=>array('type'=>'key'),
			'reference'=>array('type'=>'string'),
			'pay_amount'=>array('type'=>'numeric'),

			'method'=>array('type'=>'enum','valid values regex'=>'/pay_by_creditcard|pay_by_bank_transfer|pay_by_paypal|pay_by_cash|pay_by_cheque|pay_by_other/i'


			)
		));


	pay_invoice($data);
	break;


case('new_list'):
	if (!$user->can_view('orders'))
		exit();



	$data=prepare_values($_REQUEST,array(
			'awhere'=>array('type'=>'json array'),
			'store_id'=>array('type'=>'key'),
			'list_name'=>array('type'=>'string'),
			'list_type'=>array('type'=>'enum',
				'valid values regex'=>'/static|Dynamic/i'
			)
		));


	new_orders_list($data);
	break;

case('cc_payment'):
	$data=prepare_values($_REQUEST,array(
			'json_values'=>array('type'=>'json array'),
		));
	cc_payment($data);
	break;

case('new__invoice_list'):
	if (!$user->can_view('orders'))
		exit();

	$data=prepare_values($_REQUEST,array(
			'awhere'=>array('type'=>'json array'),
			'store_id'=>array('type'=>'key'),
			'list_name'=>array('type'=>'string'),
			'list_type'=>array('type'=>'enum',
				'valid values regex'=>'/static|Dynamic/i'
			)
		));


	new_invoices_list($data);
	break;


case('new_dn_list'):
	if (!$user->can_view('orders'))
		exit();

	$data=prepare_values($_REQUEST,array(
			'awhere'=>array('type'=>'json array'),
			'store_id'=>array('type'=>'key'),
			'list_name'=>array('type'=>'string'),
			'list_type'=>array('type'=>'enum',
				'valid values regex'=>'/static|Dynamic/i'
			)
		));


	new_dn_list($data);
	break;
case('update_no_dispatched'):
	$data=prepare_values($_REQUEST,array(
			'dn_key'=>  array('type'=>'key'),
			'itf_key'=>  array('type'=>'key'),
			'out_of_stock'=>  array('type'=>'numeric'),
			'not_found'=>array('type'=>'numeric'),
			'no_picked_other'=>array('type'=>'numeric'),
		));
	update_no_dispatched($data);
	break;
case('pick_order'):
	$data=prepare_values($_REQUEST,array(
			'dn_key'=>  array('type'=>'key'),
			'picker_key'=>  array('type'=>'numeric'),
			'itf_key'=>array('type'=>'key'),
			'new_value'=>array('type'=>'numeric'),
			'key'=>array('type'=>'string'),
		));
	pick_order($data);
	break;
case('pack_order'):
	$data=prepare_values($_REQUEST,array(
			'dn_key'=>  array('type'=>'key'),
			'packer_key'=>  array('type'=>'numeric'),
			'itf_key'=>array('type'=>'key'),
			'new_value'=>array('type'=>'numeric'),
			'key'=>array('type'=>'string'),
		));
	pack_order($data);
	break;
case('update_ship_to_key'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'ship_to_key'=>array('type'=>'numeric')
		));
	update_ship_to_key($data);
	break;


case('update_ship_to_key_from_address'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'address_key'=>array('type'=>'key')
		));
	update_ship_to_key_from_address($data);
	break;

case('update_billing_to_key'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'billing_to_key'=>array('type'=>'numeric')
		));
	update_billing_to_key($data);
	break;


case('update_billing_to_key_from_address'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'address_key'=>array('type'=>'key')
		));
	update_billing_to_key_from_address($data);
	break;


case('create_refund'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key')
		));
	create_refund($data);
	break;
case('send_post_order_to_warehouse'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key')
		));
	send_post_order_to_warehouse($data);
	break;
case('cancel_post_transactions'):
	$data=prepare_values($_REQUEST,array(

			'order_key'=>array('type'=>'key')
		));

	cancel_post_transactions_in_process($data);
	break;
case('picking_aid_sheet'):
	picking_aid_sheet();
	break;
case('get_locations'):

	$data=prepare_values($_REQUEST,array(

			'part_sku'=>array('type'=>'key')
		));

	get_locations($data);
	break;
case('packing_aid_sheet'):
	packing_aid_sheet();
	break;
case('create_invoice'):
	$data=prepare_values($_REQUEST,array(
			'dn_key'=>array('type'=>'key'),
		));

	create_invoice($data);
	break;
case('create_invoice_order'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
		));

	create_invoice_order($data);
	break;

case('assign_picker'):


	if ($_REQUEST['staff_key']=='') {
		$response=array(
			'state'=>400,
			'msg'=>_('Please select a picker')
		);
		echo json_encode($response);
		exit;
	}
	$ok=false;

	if ($_REQUEST['pin']=='') {
		if ($user->can_edit('pick') and $_REQUEST['staff_key']==$user->data['User Parent Key'])
			$ok=true;
		if ($user->can_edit('assign_pp')) {
			$ok=true;
		}

	}






	if (!$ok) {
		$response=array(
			'state'=>400,
			'msg'=>_('Please provide the supervisor PIN')
		);
		echo json_encode($response);
		exit;
	}


	$data=prepare_values($_REQUEST,array(
			'dn_key'=>array('type'=>'key'),
			'pin'=>array('type'=>'string'),
			'staff_key'=>array('type'=>'key')
		));

	assign_picker($data);
	break;
case('assign_packer'):


	if ($_REQUEST['staff_key']=='') {
		$response=array(
			'state'=>400,
			'msg'=>_('Please select a packer')
		);
		echo json_encode($response);
		exit;
	}
	if ($_REQUEST['pin']=='' and !$user->can_edit('assign_pp')) {
		$response=array(
			'state'=>400,
			'msg'=>_('Please provide the supervisor PIN')
		);
		echo json_encode($response);
		exit;
	}


	$data=prepare_values($_REQUEST,array(
			'dn_key'=>array('type'=>'key'),
			'pin'=>array('type'=>'string'),
			'staff_key'=>array('type'=>'key')
		));

	assign_packer($data);
	break;

case('start_picking'):
case('pick_it'):

	// if ($_REQUEST['staff_key']=='') {
	//  $response=array(
	//   'state'=>400,
	//   'msg'=>'Required fields missing'
	//  );
	//  echo json_encode($response);
	//  exit;
	// }
	$data=prepare_values($_REQUEST,array(
			'dn_key'=>array('type'=>'key'),
			'pin'=>array('type'=>'string'),
			'staff_key'=>array('type'=>'number')
		));

	start_picking($data);
	break;
case('start_packing'):
case('pack_it'):
	//if ($_REQUEST['staff_key']=='' || $_REQUEST['pin']=='') {
	// $response=array(
	//  'state'=>400,
	//  'msg'=>'Required fields missing'
	// );
	// echo json_encode($response);
	// exit;
	//}
	$data=prepare_values($_REQUEST,array(
			'dn_key'=>array('type'=>'key'),
			'pin'=>array('type'=>'string'),
			'staff_key'=>array('type'=>'number')
		));

	start_packing($data);
	break;

case('store_pending_orders'):
	list_store_pending_orders();
	break;
case('warehouse_orders'):
	list_warehouse_orders();
	break;


case('cancel'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'note'=>array('type'=>'string')
		));
	cancel_order($data);
	break;
case('send_to_warehouse'):
	if (isset($_REQUEST['order_key']) and is_numeric($_REQUEST['order_key']) )
		$order_key=$_REQUEST['order_key'];
	else
		$order_key=$_SESSION['state']['order']['id'];
	send_to_warehouse($order_key);
	break;


case('edit_new_order'):
	edit_new_order();
	break;
case('is_order_exist'):
	is_order_exist();
	break;
case('edit_new_post_order'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'otf_key'=>array('type'=>'key'),
			'key'=>array('type'=>'string'),
			'new_value'=>array('type'=>'string')
		));
	edit_new_post_order($data);
	break;
case('transactions_to_process'):
	transactions_to_process();
	break;
case('post_transactions_to_process'):
	post_transactions_to_process();
	break;
case('edit_new_order_shipping_type'):
	edit_new_order_shipping_type();
	break;
case('set_order_shipping'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'value'=>array('type'=>'string')
		));
	set_order_shipping($data);
	break;
case('use_calculated_shipping'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
		));
	use_calculated_shipping($data);
	break;
case('set_order_items_charges'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'value'=>array('type'=>'string')
		));
	set_order_items_charges($data);
	break;
case('use_calculated_items_charges'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
		));
	use_calculated_items_charges($data);
	break;
case('update_order'):

	update_order();
	break;
default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);

}



function cancel_order($data) {
	include_once 'class.Deal.php';

	global $editor,$user;
	$order_key=$data['order_key'];

	$order=new Order($order_key);
	$order->editor=$editor;
	if (isset($_REQUEST['note']))
		$note=stripslashes(urldecode($data['note']));
	else
		$note='';

	$order->cancel($note);
	if ($order->cancelled) {
		$response=array(
			'state'=>200,
			'order_key'=>$order->id,
			'dispatch_state'=>get_order_formated_dispatch_state($order->data['Order Current Dispatch State'],$order->id),// function in: order_common_functions.php
			'payment_state'=>get_order_formated_payment_state($order->data),
			'operations'=>get_orders_operations($order->data,$user)

		);
		echo json_encode($response);
	} else {
		$response=array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);

	}

}



function send_to_warehouse($order_key) {
	include_once 'class.PartLocation.php';
	global $user;

	$order=new Order($order_key);


	$sql=sprintf("select count(*) as num  from `Order Transaction Fact`   where `Order Key`=%d    ",$order->id);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {

		if ($row['num']==0) {
			$response=array('state'=>400,'msg'=>_('Error, can not send an empty order to warehouse'),'number_items'=>0);
			echo json_encode($response);
			return;

		}

	}


	$order->authorize_all();

	$order->send_to_warehouse();
	if (!$order->error) {
		$response=array(
			'state'=>200,
			'order_key'=>$order->id,
			'dispatch_state'=>get_order_formated_dispatch_state($order->data['Order Current Dispatch State'],$order->id),
			'operations'=>get_orders_operations($order->data,$user)

		);
		echo json_encode($response);
	} else {
		$response=array('state'=>400,'msg'=>$order->msg,'number_items'=>$order->data['Order Number Items'],'order_key'=>$order->id);
		echo json_encode($response);

	}

}


function edit_new_order_shipping_type() {

	$order_key=$_REQUEST['id'];

	$value=$_REQUEST['newvalue'];

	$order=new Order($order_key);

	$store=new Store($order->data['Order Store Key']);
	if ($value=='Yes' and $store->data['Store Can Collect']=='No') {
		$response=array('state'=>400,'msg'=>'the store dont accept collections');
		echo json_encode($response);
		return;
	}
	if ($order->id) {
		$order->update_order_is_for_collection($value);
		if ($order->updated) {

			$updated_data=array(
				'order_items_gross'=>$order->get('Items Gross Amount'),
				'order_items_discount'=>$order->get('Items Discount Amount'),
				'order_items_net'=>$order->get('Items Net Amount'),
				'order_net'=>$order->get('Total Net Amount'),
				'order_tax'=>$order->get('Total Tax Amount'),
				'order_charges'=>$order->get('Charges Net Amount'),
				'order_credits'=>$order->get('Net Credited Amount'),
				'order_shipping'=>$order->get('Shipping Net Amount'),
				'order_total'=>$order->get('Total Amount')

			);


			$response=array('state'=>200,
				'result'=>'updated',
				'new_value'=>$order->new_value,
				'order_shipping_method'=>$order->data['Order Shipping Method'],
				'data'=>$updated_data,
				'shipping'=>money($order->new_value),
				'shipping_amount'=>$order->data['Order Shipping Net Amount'],
				'ship_to'=>$order->get('Order XHTML Ship Tos'),
				'tax_info'=>$order->get_formated_tax_info_with_operations()
			);

		} else {
			$response=array('state'=>200,'result'=>'no_change');

		}

	} else {
		$response=array('state'=>400,'msg'=>$order->msg);

	}
	echo json_encode($response);



}


function use_calculated_shipping($data) {
	$order_key=$data['order_key'];
	$order=new Order($order_key);
	if ($order->id) {

		$order->use_calculated_shipping();
		$updated_data=array(
			'order_items_gross'=>$order->get('Items Gross Amount'),
			'order_items_discount'=>$order->get('Items Discount Amount'),
			'order_items_net'=>$order->get('Items Net Amount'),
			'order_net'=>$order->get('Total Net Amount'),
			'order_tax'=>$order->get('Total Tax Amount'),
			'order_charges'=>$order->get('Charges Net Amount'),
			'order_credits'=>$order->get('Net Credited Amount'),
			'order_shipping'=>$order->get('Shipping Net Amount'),
			'order_total'=>$order->get('Total Amount')

		);
		$response=array('state'=>200,'result'=>'updated','new_value'=>$order->new_value,'order_shipping_method'=>$order->data['Order Shipping Method'],'data'=>$updated_data,'shipping'=>money($order->new_value),'shipping_amount'=>$order->data['Order Shipping Net Amount']);
	} else {
		$response=array('state'=>400,'msg'=>$order->msg);

	}
	echo json_encode($response);
}



function use_calculated_items_charges($data) {
	$order_key=$data['order_key'];
	$order=new Order($order_key);
	if ($order->id) {

		$order->use_calculated_items_charges();
		$updated_data=array(
			'order_items_gross'=>$order->get('Items Gross Amount'),
			'order_items_discount'=>$order->get('Items Discount Amount'),
			'order_items_net'=>$order->get('Items Net Amount'),
			'order_net'=>$order->get('Total Net Amount'),
			'order_tax'=>$order->get('Total Tax Amount'),
			'order_charges'=>$order->get('Charges Net Amount'),
			'order_credits'=>$order->get('Net Credited Amount'),
			'order_shipping'=>$order->get('Shipping Net Amount'),
			'order_total'=>$order->get('Total Amount')

		);
		$response=array('state'=>200,'result'=>'updated','new_value'=>$order->new_value,'data'=>$updated_data,'items_charges'=>money($order->new_value),'items_charges_amount'=>$order->data['Order Charges Net Amount']);
	} else {
		$response=array('state'=>400,'msg'=>$order->msg);

	}
	echo json_encode($response);
}





function set_order_shipping($data) {

	$order_key=$data['order_key'];
	$value=$data['value'];
	$order=new Order($order_key);
	if ($order->id) {
		$order->update_shipping_amount($value);
		if ($order->updated) {
			$updated_data=array(
				'order_items_gross'=>$order->get('Items Gross Amount'),
				'order_items_discount'=>$order->get('Items Discount Amount'),
				'order_items_net'=>$order->get('Items Net Amount'),
				'order_net'=>$order->get('Balance Net Amount'),
				'order_tax'=>$order->get('Balance Tax Amount'),
				'order_charges'=>$order->get('Charges Net Amount'),
				'order_credits'=>$order->get('Net Credited Amount'),
				'order_shipping'=>$order->get('Shipping Net Amount'),
				'order_total'=>$order->get('Balance Total Amount')

			);
			$response=array('state'=>200,'result'=>'updated','new_value'=>$order->new_value,'data'=>$updated_data,'shipping_amount'=>$order->data['Order Shipping Net Amount'],'shipping'=>money($order->new_value),'order_shipping_method'=>$order->data['Order Shipping Method']);
		} else {
			$response=array('state'=>200,'result'=>'no_change');
		}
	} else {
		$response=array('state'=>400,'msg'=>$order->msg);
	}
	echo json_encode($response);
}


function set_order_items_charges($data) {

	$order_key=$data['order_key'];
	$value=$data['value'];
	$order=new Order($order_key);
	if ($order->id) {

		$tax_category_object=new TaxCategory($order->data['Order Tax Code']);

		$charges_data=array(
			'Charge Net Amount'=>$value,
			'Charge Tax Amount'=>$value*$tax_category_object->data['Tax Category Rate'],
			'Charge Key'=>0,
			'Charge Description'=>'Charge'
		);



		$order->update_charges_amount($charges_data);
		if ($order->updated) {
			$updated_data=array(
				'order_items_gross'=>$order->get('Items Gross Amount'),
				'order_items_discount'=>$order->get('Items Discount Amount'),
				'order_items_net'=>$order->get('Items Net Amount'),
				'order_net'=>$order->get('Balance Net Amount'),
				'order_tax'=>$order->get('Balance Tax Amount'),
				'order_charges'=>$order->get('Charges Net Amount'),
				'order_credits'=>$order->get('Net Credited Amount'),
				'order_shipping'=>$order->get('Shipping Net Amount'),
				'order_total'=>$order->get('Balance Total Amount')

			);
			$response=array('state'=>200,'result'=>'updated','new_value'=>$order->new_value,'data'=>$updated_data,'items_charges_amount'=>$order->data['Order Charges Net Amount'],'items_charges'=>money($order->new_value));
		} else {
			$response=array('state'=>200,'result'=>'no_change');
		}
	} else {
		$response=array('state'=>400,'msg'=>$order->msg);
	}
	echo json_encode($response);
}

function is_order_exist() {
	$order_key=$_REQUEST['id'];

	$product_pid=$_REQUEST['pid'];
	$quantity=$_REQUEST['newvalue'];
	$user_key=$_REQUEST['user_key'];
	$user=new User($user_key);

	if ($order_key==0) {
		$sql=sprintf("select * from `Order Dimension` where `Order Customer Key`=%d and `Order Current Dispatch State`='In Process' order by `Order Public ID` DESC", $user->get('User Parent Key'));
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result)) {
			$order_exist=true;
			$order_key=$row['Order Key'];
		}
		else {
			//$order_exist=false;
			date_default_timezone_set('UTC');

			$customer_=new Customer($user->get('User Parent Key'));
			if (!$customer_->id)
				$customer_=new Customer('create anonymous');

			$editor=array(
				'Author Name'=>$user->data['User Alias'],
				'Author Alias'=>$user->data['User Alias'],
				'Author Type'=>$user->data['User Type'],
				'Author Key'=>$user->data['User Parent Key'],
				'User Key'=>$user->id
			);

			$order_data=array(

				'Customer Key'=>$customer_->id,
				'Order Original Data MIME Type'=>'application/inikoo',
				'Order Type'=>'Order',
				'editor'=>$editor

			);

			$order=new Order('new',$order_data);
			$order_key=$order->id;
			$order_exist=true;
			//exit;
			if ($order->error)
				exit('error');


		}
		$_REQUEST['id']=$order_key;

	}

	edit_new_order();
}

function edit_new_order() {

	$order_key=$_REQUEST['id'];

	$product_pid=$_REQUEST['pid'];
	$quantity=$_REQUEST['newvalue'];




	if (!(is_numeric($quantity) and $quantity>=0)) {

		$quantity=0;
	}




	$order=new Order($order_key);

	if (in_array($order->data['Order Current Dispatch State'],array('Ready to Pick','Picking & Packing','Packed')) ) {
		$dispatching_state='Ready to Pick';
	}else {

		$dispatching_state='In Process';
	}

	$payment_state='Waiting Payment';

	$product=new Product('pid',$product_pid);
	$data=array(
		'date'=>gmdate('Y-m-d H:i:s'),
		'Product Key'=>$product->data['Product Current Key'],
		'Metadata'=>'',
		'qty'=>$quantity,
		'Current Dispatching State'=>$dispatching_state,
		'Current Payment State'=>$payment_state
	);

	$disconted_products=$order->get_discounted_products();
	$order->skip_update_after_individual_transaction=false;

	$transaction_data=$order->add_order_transaction($data);
	if (!$transaction_data['updated']) {
		$response= array('state'=>200,'newvalue'=>$_REQUEST['oldvalue'],'key'=>$_REQUEST['id']);
		echo json_encode($response);
		return;
	}

	$new_disconted_products=$order->get_discounted_products();
	foreach ($new_disconted_products as $key=>$value) {
		$disconted_products[$key]=$value;
	}

	$adata=array();

	if (count($disconted_products)>0) {

		$product_keys=join(',',$disconted_products);
		$sql=sprintf("select (select `Deal Info` from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`,P.`Product ID`,`Product XHTML Short Description`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` from `Order Transaction Fact` OTF   left join `Product Dimension` P on (OTF.`Product ID`=P.`Product ID`) where OTF.`Order Key`=%d and OTF.`Product Key` in (%s)",
			$order->id,
			$product_keys);


		//print $sql;
		$res = mysql_query($sql);
		$adata=array();

		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


			if ($row['Deal Info']) {



				$deal_info='<br/><span style="font-style:italics;color:#555555;font-size:90%">'.$row['Deal Info'].($row['Order Transaction Total Discount Amount']?', <span style="font-weight:800">-'.money($row['Order Transaction Total Discount Amount'],$order->data['Order Currency']).'</span>':'').'</span>';
			}else {
				$deal_info='';
			}


			$adata[$row['Product ID']]=array(
				'pid'=>$row['Product ID'],
				'description'=>''.$row['Product XHTML Short Description'].$deal_info,
				'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'],$order->data['Order Currency'])
			);
		};
	}

	$updated_data=array(
		'order_items_gross'=>$order->get('Items Gross Amount'),
		'order_items_discount'=>$order->get('Items Discount Amount'),
		'order_items_net'=>$order->get('Items Net Amount'),
		'order_net'=>$order->get('Total Net Amount'),
		'order_tax'=>$order->get('Total Tax Amount'),
		'order_charges'=>$order->get('Charges Net Amount'),
		'order_credits'=>$order->get('Net Credited Amount'),
		'order_shipping'=>$order->get('Shipping Net Amount'),
		'order_total'=>$order->get('Total Amount'),
		'ordered_products_number'=>$order->get('Number Products'),
	);

	$response= array(
		'state'=>200,
		'quantity'=>$transaction_data['qty'],
		'description'=>$product->data['Product XHTML Short Description'],
		'discount_percentage'=>$transaction_data['discount_percentage'],
		'key'=>$_REQUEST['id'],
		'data'=>$updated_data,
		'to_charge'=>$transaction_data['to_charge'],
		'discount_data'=>$adata,
		'discounts'=>($order->data['Order Items Discount Amount']!=0?true:false),
		'charges'=>($order->data['Order Charges Net Amount']!=0?true:false)
	);

	echo json_encode($response);
}

function edit_new_post_order($data) {

	$order_key=$data['order_key'];
	$otf_key=$data['otf_key'];
	$value=$data['new_value'];
	$key=$data['key'];
	$quantity=0;
	$order=new Order($order_key);


	$transaction_data=array(
		'Quantity'=>0,
		'Operation'=>$_SESSION['state']['order']['post_transactions']['operation'],
		'Reason'=>$_SESSION['state']['order']['post_transactions']['reason'],
		'To Be Returned'=>$_SESSION['state']['order']['post_transactions']['to_be_returned'],
	);

	if ($key=='quantity' and is_numeric($value) and $value>=0) {
		$transaction_data['Quantity']=$value;
		$_key='Quantity';

	}
	elseif ($key=='operation') {
		$transaction_data['Operation']=$value;
		$_key='Operation';
		$_SESSION['state']['order']['post_transactions']['operation']=$value;
	}
	elseif ($key=='reason') {
		$transaction_data['Reason']=$value;
		$_key='Reason';
		$_SESSION['state']['order']['post_transactions']['reason']=$value;

	}
	elseif ($key=='to_be_returned') {
		$transaction_data['To Be Returned']=$value;
		$_key='To Be Returned';
		$_SESSION['state']['order']['post_transactions']['to_be_returned']=$value;
	}
	else {
		$response=array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);
		exit;
	}



	$transaction_data=$order->create_post_transaction_in_process($otf_key,$_key,$transaction_data);
	//print_r($transaction_data);
	if ($order->updated) {
		$response= array(
			'state'=>200,
			'result'=>'updated',
			'quantity'=>$transaction_data['Quantity'],
			'operation'=>$transaction_data['Operation'],
			'reason'=>$transaction_data['Reason'],
			'to_be_returned'=>$transaction_data['To Be Returned'],
			'data'=>$order->get_post_transactions_in_process_data(),
			'new_value'=>$transaction_data[$_key]
		);
	} else {
		$response= array(
			'state'=>200,
			'result'=>'nochange'
		);

	}
	echo json_encode($response);

}

function transactions_to_process() {


	if (isset( $_REQUEST['order_key']) and is_numeric( $_REQUEST['order_key'])) {
		$order_id=$_REQUEST['order_key'];
	} else
		return;

	if (isset( $_REQUEST['store_key']) and is_numeric( $_REQUEST['store_key'])) {
		$store_key=$_REQUEST['store_key'];
	} else
		return;



	if (isset( $_REQUEST['display'])) {
		$display=$_REQUEST['display'];
		$_SESSION['state']['order']['block_view']=$display;

	}else
		$display=$_SESSION['state']['order']['block_view'];


	if ($display=='products') {
		$conf=$_SESSION['state']['order']['products'];
		$conf_table='products';
	}elseif ($display=='items') {

		$conf=$_SESSION['state']['order']['items'];
		$conf_table='items';
	}else {
		exit;
	}



	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;




	if (isset( $_REQUEST['lookup_family'])) {
		$lookup_family=$_REQUEST['lookup_family'];

	}else
		$lookup_family=$conf['lookup_family'];




	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
	} else
		$number_results=$conf['nr'];



	$_SESSION['state']['order'][$conf_table]['order']=$order;
	$_SESSION['state']['order'][$conf_table]['order_dir']=$order_direction;
	$_SESSION['state']['order'][$conf_table]['sf']=$start_from;
	$_SESSION['state']['order'][$conf_table]['nr']=$number_results;

	$_SESSION['state']['order'][$conf_table]['f_field']=$f_field;
	$_SESSION['state']['order'][$conf_table]['f_value']=$f_value;

	$_SESSION['state']['order'][$conf_table]['lookup_family']=$lookup_family;



	$store=new Store($store_key);



	if ($display=='products') {
		$table=' `Product Dimension` P ';


		$order_object=new Order($order_id);


		$where=sprintf('where `Product Store Key`=%d  and `Product Record Type`="Normal"    and `Product Main Type` in ("Private","Sale") ',$store_key);

		if ($lookup_family!='') {
			$where.=sprintf('and `Product Family Code`=%s   ',prepare_mysql($lookup_family));

		}

		$sql_qty=sprintf(' ,"%s"  as `Order Currency Code`  ,"" as `Transaction Tax Code`,"" as `Transaction Tax Rate`,(select `Order Transaction Fact Key` from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d limit 1) as `Order Transaction Fact Key`,IFNULL((select sum(`Order Quantity`) from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d),0) as `Order Quantity`, IFNULL((select sum(`Order Transaction Total Discount Amount`) from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d),0) as `Order Transaction Total Discount Amount`, IFNULL((select sum(`Order Transaction Gross Amount`) from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d),0) as `Order Transaction Gross Amount` ,(  select GROUP_CONCAT(`Deal Info`) from  `Order Transaction Deal Bridge` OTDB  where OTDB.`Product Key`=`Product Current Key` and OTDB.`Order Key`=%d )  as `Deal Info`,(select `Current Dispatching State` from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d limit 1) as `Current Dispatching State`,(select `Picking Factor` from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d limit 1) as `Picking Factor`,(select `Packing Factor` from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d limit 1) as `Packing Factor` ',
			$order_object->data['Order Currency'],$order_id,$order_id,$order_id,$order_id,$order_id,$order_id,$order_id,$order_id);
	} else if ($display=='items') {
			$table='  `Order Transaction Fact` OTF  left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P on (PHD.`Product ID`=P.`Product ID`)  ';
			$where=sprintf(' where `Order Quantity`>0 and `Order Key`=%d',$order_id);
			$sql_qty='`Order Currency Code`,`Transaction Tax Code`,`Transaction Tax Rate`,`No Shipped Due No Authorized`,`No Shipped Due Not Found`,`No Shipped Due Other`,`No Shipped Due Out of Stock`,`Picking Factor`,`Packing Factor`,`Order Transaction Fact Key`, `Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,(select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`,`Current Dispatching State`';
		} else {
		exit();
	}




	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  P.`Product Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  P.`Product Name` like '%".addslashes($f_value)."%'";

	$sql="select count(*) as total from $table   $where $wheref   ";

	// print_r($conf);exit;
	//  print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from $table  $where   ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}


	$rtext=number($total_records)." ".ngettext('product','products',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;
	$order='`Product Code File As`';
	if ($order=='stock')
		$order='`Product Availability`';
	if ($order=='code')
		$order='`Product Code File As`';
	else if ($order=='name')
			$order='`Product Name`';
		else if ($order=='available_for')
				$order='`Product Available Days Forecast`';
			elseif ($order=='family') {
				$order='`Product Family`Code';
			}
		elseif ($order=='dept') {
			$order='`Product Main Department Code`';
		}
	elseif ($order=='expcode') {
		$order='`Product Tariff Code`';
	}
	elseif ($order=='parts') {
		$order='`Product XHTML Parts`';
	}
	elseif ($order=='supplied') {
		$order='`Product XHTML Supplied By`';
	}
	elseif ($order=='gmroi') {
		$order='`Product GMROI`';
	}
	elseif ($order=='state') {
		$order='`Product Sales State`';
	}
	elseif ($order=='web') {
		$order='`Product Web Configuration`';
	}



	$sql="select `Product Stage`, `Product Availability`,`Product Record Type`,P.`Product ID`,P.`Product Code`,`Product XHTML Short Description`,`Product Price`,`Product Units Per Case`,`Product Record Type`,`Product Web Configuration`,`Product Family Name`,`Product Main Department Name`,`Product Tariff Code`,`Product XHTML Parts`,`Product GMROI`,`Product XHTML Parts`,`Product XHTML Supplied By`,`Product Stock Value`  $sql_qty from $table   $where $wheref order by $order $order_direction limit $start_from,$number_results    ";

	//print $sql;
	$res = mysql_query($sql);

	$adata=array();

	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		if (is_numeric($row['Product Availability']))
			$stock=number($row['Product Availability']);
		else
			$stock='?';
		$type=$row['Product Record Type'];

		if ($row['Product Stage']=='In Process')
			$type.='<span style="color:red">*</span>';

		switch ($row['Product Web Configuration']) {
		case('Online Force Out of Stock'):
			$web_state=_('Out of Stock');
			break;
		case('Online Auto'):
			$web_state=_('Auto');
			break;
		case('Unknown'):
			$web_state=_('Unknown');
		case('Offline'):
			$web_state=_('Offline');
			break;
		case('Online Force Hide'):
			$web_state=_('Hide');
			break;
		case('Online Force For Sale'):
			$web_state=_('Sale');
			break;
		default:
			$web_state=$row['Product Web Configuration'];
		}


		if ($row['Deal Info']) {



			$deal_info='<br/><span style="font-style:italics;color:#555555;font-size:90%">'.$row['Deal Info'].($row['Order Transaction Total Discount Amount']?', <span style="font-weight:800">-'.money($row['Order Transaction Total Discount Amount'],$row['Order Currency Code']).'</span>':'').'</span>';
		}else {
			$deal_info='';
		}


		if ($display=='ordered_products') {
			switch ($row['Current Dispatching State']) {
			case 'In Process by Customer':
				$dispatching_status=_('In Process by Customer');
				break;
			case 'Submitted by Customer':
				$dispatching_status=_('Submitted by Customer');
				break;
			case 'In Process':
				$dispatching_status=_('In Process');
				break;
			case 'Ready to Pick':
				$dispatching_status=_('Ready to Pick').' ['.$row['Picking Factor']*$row['Order Quantity'].'/'.$row['Order Quantity'].']';
				break;
			case 'Picking':
				$dispatching_status=_('Picking').' ['.$row['Picking Factor']*$row['Order Quantity'].'/'.($row['Order Quantity']-$row['No Shipped Due Out of Stock']-$row['No Shipped Due No Authorized']-$row['No Shipped Due Not Found']-$row['No Shipped Due Other']).']';
				break;
			case 'Ready to Pack':
				$dispatching_status=_('Ready to Pack').' ['.$row['Picking Factor']*$row['Order Quantity'].'/'.$row['Order Quantity'].'] '.' ['.$row['Packing Factor']*$row['Order Quantity'].'/'.$row['Order Quantity'].']';
				break;
			case 'Ready to Ship':
				$dispatching_status=_('Ready to Ship');
				break;
			case 'Dispatched':
				$dispatching_status=_('Dispatched');
				break;
			case 'Unknown':
				$dispatching_status=_('Unknown');
				break;
			case 'Packing':
				$dispatching_status=_('Packing');
				break;

			case 'Cancelled':
				$dispatching_status=_('Cancelled');
				break;
			case 'No Picked Due Out of Stock':
				$dispatching_status=_('No Picked Due Out of Stock');
				break;
			case 'No Picked Due No Authorised':
				$dispatching_status=_('No Picked Due No Authorised');
				break;
			case 'No Picked Due Not Found':
				$dispatching_status=_('No Picked Due Not Found');
				break;
			case 'No Picked Due Other':
				$dispatching_status=_('No Picked Due Other');
				break;
			case 'Suspended':
				$dispatching_status=_('Suspended');
				break;
			default:
				$dispatching_status=$row['Current Dispatching State'];
				break;
			}

			$quantity=number($row['Order Quantity']);



		}else {
			$dispatching_status='';
			$quantity=number($row['Order Quantity']);
		}

		$quantity_notes='';



		$code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);
		$adata[]=array(
			'pid'=>$row['Product ID'],
			'otf_key'=>$row['Order Transaction Fact Key'],//($display=='ordered_products'?$row['Order Transaction Fact Key']:0),
			'code'=>$code,
			'description'=>$row['Product XHTML Short Description'].$deal_info,
			'shortname'=>number($row['Product Units Per Case']).'x @'.money($row['Product Price']/$row['Product Units Per Case'],$store->data['Store Currency Code']).' '._('ea'),
			'family'=>$row['Product Family Name'],
			'dept'=>$row['Product Main Department Name'],
			'expcode'=>$row['Product Tariff Code'],
			'parts'=>$row['Product XHTML Parts'],
			'supplied'=>$row['Product XHTML Supplied By'],
			'gmroi'=>$row['Product GMROI'],
			//    'stock_value'=>money($row['Product Stock Value']),
			'stock'=>$stock,
			'quantity'=>$row['Order Quantity'],
			//'quantity_formated'=>$quantity,
			'state'=>$type,
			'web'=>$web_state,
			//    'image'=>$row['Product Main Image'],
			'type'=>'item',
			'add'=>'+',
			'remove'=>'-',
			//'change'=>'<span onClick="quick_change("+",'.$row['Product ID'].')" class="quick_add">+</span> <span class="quick_add" onClick="quick_change("-",'.$row['Product ID'].')" >-</span>',
			'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'],$store->data['Store Currency Code']),
			'tax'=>percentage($row['Transaction Tax Rate'],1),
			'dispatching_status'=>$dispatching_status,
			'discount_percentage'=>($row['Order Transaction Total Discount Amount']>0?percentage($row['Order Transaction Total Discount Amount'],$row['Order Transaction Gross Amount'],$fixed=1,$error_txt='NA',$psign=''):'')




		);


	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records-$filtered,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);


}
function post_transactions_to_process() {

	if (isset( $_REQUEST['parent_key']) and is_numeric( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	} else {
		exit();
	}




	$conf=$_SESSION['state']['order']['post_transactions'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];


	}      else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	$_SESSION['state']['order']['post_transactions']['order']=$order;
	$_SESSION['state']['order']['post_transactions']['order_dir']=$order_direction;
	$_SESSION['state']['order']['post_transactions']['nr']=$number_results;
	$_SESSION['state']['order']['post_transactions']['sf']=$start_from;
	$_SESSION['state']['order']['post_transactions']['f_field']=$f_field;
	$_SESSION['state']['order']['post_transactions']['f_value']=$f_value;



	$table='  `Order Transaction Fact` OTF  left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P on (PHD.`Product ID`=P.`Product ID`) left join `Order Post Transaction Dimension` POT on (POT.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`)  ';
	$where=sprintf(' where `Order Quantity`>0 and OTF.`Order Key`=%d ',$parent_key);
	$sql_qty=', `Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,(select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`';





	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  P.`Product Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Product Name` like '%".addslashes($f_value)."%'";

	$sql="select count(*) as total from $table   $where $wheref   ";

	// print_r($conf);exit;
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from $table  $where   ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}


	$rtext=number($total_records)." ".ngettext('product','products',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;
	$order='`Product Code File As`';
	if ($order=='stock')
		$order='`Product Availability`';
	if ($order=='code')
		$order='`Product Code File As`';
	elseif ($order=='name')
		$order='`Product Name`';
	elseif ($order=='available_for')
		$order='`Product Available Days Forecast`';
	elseif ($order=='family') {
		$order='`Product Family`Code';
	}
	elseif ($order=='dept') {
		$order='`Product Main Department Code`';
	}
	elseif ($order=='expcode') {
		$order='`Product Tariff Code`';
	}
	elseif ($order=='parts') {
		$order='`Product XHTML Parts`';
	}
	elseif ($order=='supplied') {
		$order='`Product XHTML Supplied By`';
	}
	elseif ($order=='gmroi') {
		$order='`Product GMROI`';
	}
	elseif ($order=='state') {
		$order='`Product Sales State`';
	}
	elseif ($order=='web') {
		$order='`Product Web Configuration`';
	}



	$sql="select IFNULL(`State`,'') as `State`,`Reason`,`To Be Returned`,`Operation`,IFNULL(`Quantity`,'') as Quantity,OTF.`Order Key`,OTF.`Order Transaction Fact Key`,`Invoice Currency Code`,(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as charged, `Delivery Note Quantity`,`Product Availability`,`Product Record Type`,P.`Product ID`,P.`Product Code`,`Product XHTML Short Description`,`Product Price`,`Product Units Per Case`,`Product Record Type`,`Product Web Configuration`,`Product Family Name`,`Product Main Department Name`,`Product Tariff Code`,`Product XHTML Parts`,`Product GMROI`,`Product XHTML Parts`,`Product XHTML Supplied By`,`Product Stock Value`  $sql_qty from $table   $where $wheref order by $order $order_direction limit $start_from,$number_results    ";


	$res = mysql_query($sql);

	$adata=array();

	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		if (is_numeric($row['Product Availability']))
			$stock=number($row['Product Availability']);
		else
			$stock='?';


		switch ($row['Product Web Configuration']) {
		case('Online Force Out of Stock'):
			$web_state=_('Out of Stock');
			break;
		case('Online Auto'):
			$web_state=_('Auto');
			break;
		case('Unknown'):
			$web_state=_('Unknown');
		case('Offline'):
			$web_state=_('Offline');
			break;
		case('Online Force Hide'):
			$web_state=_('Hide');
			break;
		case('Online Force For Sale'):
			$web_state=_('Sale');
			break;
		default:
			$web_state=$row['Product Web Configuration'];
		}


		$deal_info='';
		if ($row['Deal Info']!='') {
			$deal_info=' <span class="deal_info">'.$row['Deal Info'].'</span>';
		}







		$submited_post_transactions_info='';


		$code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);
		$adata[]=array(
			'otf_key'=>$row['Order Transaction Fact Key'],
			'order_key'=>$row['Order Key'],
			'pid'=>$row['Product ID'],
			'code'=>$code,
			'description'=>$row['Product XHTML Short Description'].$deal_info.$submited_post_transactions_info,

			'stock'=>$stock,
			'ordered'=>$row['Delivery Note Quantity'].' ('.money($row['charged'],$row['Invoice Currency Code']).')',
			'state'=>$row['State'],
			'max_resend'=>$row['Delivery Note Quantity'],
			'max_refund'=>$row['charged'],
			'add'=>(!( $row['State']=='In Process' or  $row['State']=='')?'':'+'),
			'remove'=>(!( $row['State']=='In Process' or  $row['State']=='')?'':'-'),
			'to_charge'=>'<span onClick="change_discount(this)">'.money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount']).'</span>',
			'quantity'=>( !( $row['State']=='In Process' or  $row['State']=='')   ?'<img src="art/icons/lock_bw.png"> ':'').$row['Quantity'],
			'operation'=>$row['Operation'],
			'reason'=>$row['Reason'],
			'to_be_returned'=>$row['To Be Returned'],
		);


	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records-$filtered,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);


}



function list_store_pending_orders() {

	include_once 'order_common_functions.php';

	global $user;

	$conf=$_SESSION['state']['customers']['pending_orders'];

	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		return;

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))
		$where=$_REQUEST['where'];
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;



	$elements=$conf['elements'];
	if (isset( $_REQUEST['elements_PackedDone'])) {
		$elements['PackedDone']=$_REQUEST['elements_PackedDone'];
	}
	if (isset( $_REQUEST['elements_InWarehouse'])) {
		$elements['InWarehouse']=$_REQUEST['elements_InWarehouse'];
	}
	if (isset( $_REQUEST['elements_SubmittedbyCustomer'])) {
		$elements['SubmittedbyCustomer']=$_REQUEST['elements_SubmittedbyCustomer'];
	}
	if (isset( $_REQUEST['elements_InProcess'])) {
		$elements['InProcess']=$_REQUEST['elements_InProcess'];
	}
	if (isset( $_REQUEST['elements_InProcessbyCustomer'])) {
		$elements['InProcessbyCustomer']=$_REQUEST['elements_InProcessbyCustomer'];
	}

	if (isset( $_REQUEST['elements_ReadytoPick'])) {
		$elements['ReadytoPick']=$_REQUEST['elements_ReadytoPick'];
	}



	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	$_SESSION['state']['customers']['pending_orders']['order']=$order;
	$_SESSION['state']['customers']['pending_orders']['order_dir']=$order_direction;
	$_SESSION['state']['customers']['pending_orders']['nr']=$number_results;
	$_SESSION['state']['customers']['pending_orders']['sf']=$start_from;
	$_SESSION['state']['customers']['pending_orders']['where']=$where;
	$_SESSION['state']['customers']['pending_orders']['f_field']=$f_field;
	$_SESSION['state']['customers']['pending_orders']['f_value']=$f_value;
	$_SESSION['state']['customers']['pending_orders']['elements']=$elements;

	//'In Process by Customer','In Process','Submitted by Customer','Ready to Pick','Picking & Packing','Packed','Ready to Ship','Dispatched','Unknown','Packing','Cancelled','Suspended'

	$where.=sprintf(' and `Order Store Key`=%d  and `Order Current Dispatch State` not in ("Dispatched","Unknown","Packing","Cancelled","Suspended","") ',$parent_key);





	$_elements='';
	$elements_count=0;
	foreach ($elements as $_key=>$_value) {
		if ($_value) {
			$elements_count++;

			if ($_key=='InWarehouse') {
				$_key="'Picking & Packing','Ready to Ship','Packed','Packing'";
			}if ($_key=='SubmittedbyCustomer') {
				$_key="'Submitted by Customer'";
			}if ($_key=='InProcess') {
				$_key="'In Process'";
			}if ($_key=='InProcessbyCustomer') {
				$_key="'In Process by Customer','Waiting for Payment Confirmation'";
			}if ($_key=='PackedDone') {
				$_key="'Packed Done'";
			}if ($_key=='ReadytoPick') {
				$_key="'Ready to Pick'";
			}

			$_elements.=','.$_key;
		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($elements_count==0) {
		$where.=' and false' ;
	} elseif ($elements_count<5) {
		$where.=' and `Order Current Dispatch State` in ('.$_elements.')' ;
	}




	$wheref='';

	if ($f_field=='max' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Order Date Created`))<=".$f_value."    ";
	else if ($f_field=='min' and is_numeric($f_value) )
			$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Order Date Created`))>=".$f_value."    ";
		elseif ($f_field=='customer_name' and $f_value!='')
			$wheref.=" and  `Order Customer Name` like '".addslashes($f_value)."%'";
		elseif ($f_field=='public_id' and $f_value!='')
			$wheref.=" and  `Order Public ID` like '".addslashes($f_value)."%'";


		$sql="select count(*) as total from `Order Dimension`   $where $wheref ";
	// print $sql ;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($where=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		$sql="select count(*) as total from `Order Dimension`  $where";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}
	mysql_free_result($result);

	$rtext=number($total_records)." ".ngettext('pending order','pending orders',$total_records);

	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	$filter_msg='';

	switch ($f_field) {
	case('public_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>)";
		break;
	case('customer_name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with customer")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with customer')." <b>".$f_value."*</b>)";
		break;
	case('minvalue'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>)";
		break;

	case('max'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').")";
		break;
	}




	$_order=$order;
	$_dir=$order_direction;



	if ($order=='customer') {
		$order='`Order Customer Name`';
	}elseif ($order=='public_id') {
		$order='`Order File As`';
	}elseif ($order=='dispatch_state') {
		$order='O.`Order Current Dispatch State`';
	}elseif ($order=='payment_state') {
		$order='O.`Order Current Payment State`';

	}elseif ($order=='total_amount') {
		$order='O.`OOrder Total Amount`';

	}else {
		$order='`Order Date`';
	}


	$sql="select *  from `Order Dimension`   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
	// print $sql;
	global $myconf;

	$data=array();

	$res = mysql_query($sql);
	//print $sql;
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$operations=get_orders_operations($row,$user);
		
		$order=new Order($row['Order Key']);
		
		
	$dns_data=array();
	foreach ($order->get_delivery_notes_objects() as $dn) {
		$current_delivery_note_key=$dn->id;

		$missing_dn_data=false;
		$missing_dn_str='';
		$dn_data='';
		if ($dn->data['Delivery Note Weight']) {
			$dn_data=$dn->get('Weight');
		}else {
			$missing_dn_data=true;
			$missing_dn_str=_('weight');

		}

		if ($dn->data['Delivery Note Number Parcels']!='') {
			$dn_data.=', '.$dn->get_formated_parcels();
		}else {
			$missing_dn_data=true;
			$missing_dn_str.=', '._('parcels');
		}
		$missing_dn_str=preg_replace('/^,/','',$missing_dn_str);


		if ($dn->data['Delivery Note Shipper Consignment']!='') {
			$dn_data.=', '. $dn->get('Consignment');
		}else {
			$missing_dn_data=true;
			$missing_dn_str.=', '._('consignment');
		}
		$missing_dn_str=preg_replace('/^,/','',$missing_dn_str);
		$dn_data=preg_replace('/^,/','',$dn_data);


		if ($missing_dn_data) {
			//$dn_data='<span style="font-style:italic;color:#777">'._('Missing').': '.$missing_dn_str.'</span> <img src="art/icons/edit.gif"> ';
		}

		$dns_data[]=array(
			'key'=>$dn->id,
			'number'=>$dn->data['Delivery Note ID'],
			'state'=>$dn->data['Delivery Note XHTML State'],
			'data'=>$dn_data,
			'operations'=>$dn->get_operations($user,''),
		);
	}
	$number_dns=count($dns_data);
	if ($number_dns!=1) {
		$current_delivery_note_key='';
	}
	
	$dn_operations='<div style="border:1px solid #cccclear:both;margin-top:10px;padding-top:5px;padding-bottom:5px"><table style="margin-top:0px">';
	foreach($dns_data as $dn_data){
		$dn_operations.=sprintf('<tr style="font-size:90%%;margin:5px 0px;border:none"><td>%s</td><td>%s</td></tr>',_('Delivery Note'),$dn_data['number']);
		$dn_operations.=sprintf('<tr style="border:none;"><td colspan=2">%s</td></tr>',$dn_data['operations']);
	}
	$dn_operations.='</table></div>';
		$operations.=$dn_operations;


		$public_id=sprintf("<a href='order.php?id=%d&referral=spo'>%s</a>",$row['Order Key'],$row['Order Public ID']);


$date='<span title="'.strftime("%a %e %b %Y %H:%M %Z", strtotime($row['Order Date'])).'" >'.strftime("%e %b %Y", strtotime($row['Order Date'])).'</span>';

		$see_link=sprintf("<a href='order_pick_aid.php?id=%d'>%s</a>",$row['Order Key'],"See Picking Sheet");
		$data[]=array(
			'id'=>$row['Order Key'],
			'public_id'=>$public_id,
			'customer'=>$row['Order Customer Name'],
			'date'=>$date,
			'total_amount'=>money($row['Order Total Amount'],$row['Order Currency']),
			'operations'=>$operations,
			'dispatch_state'=>get_order_formated_dispatch_state($row['Order Current Dispatch State'],$row['Order Key']),// function in: order_common_functions.php
			'payment_state'=>get_order_formated_payment_state($row),
			'see_link'=>$see_link
		);
	}
	mysql_free_result($res);

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records-$filtered,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);



}




function list_warehouse_orders() {

	global $user;

	$conf=$_SESSION['state']['orders']['warehouse_orders'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;




	$elements=$conf['elements'];
	if (isset( $_REQUEST['elements_ready_to_ship'])) {
		$elements['ReadytoShip']=$_REQUEST['elements_ready_to_ship'];
	}
	if (isset( $_REQUEST['elements_done'])) {
		$elements['Done']=$_REQUEST['elements_done'];
	}
	if (isset( $_REQUEST['elements_picking_and_packing'])) {
		$elements['PickingAndPacking']=$_REQUEST['elements_picking_and_packing'];
	}
	if (isset( $_REQUEST['elements_ready_to_restock'])) {
		$elements['ReadytoRestock']=$_REQUEST['elements_ready_to_restock'];
	}
	if (isset( $_REQUEST['elements_ready_to_pack'])) {
		$elements['ReadytoPack']=$_REQUEST['elements_ready_to_pack'];
	}
	if (isset( $_REQUEST['elements_ready_to_pick'])) {
		$elements['ReadytoPick']=$_REQUEST['elements_ready_to_pick'];
	}


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	$_SESSION['state']['orders']['warehouse_orders']['order']=$order;
	$_SESSION['state']['orders']['warehouse_orders']['order_dir']=$order_direction;
	$_SESSION['state']['orders']['warehouse_orders']['nr']=$number_results;
	$_SESSION['state']['orders']['warehouse_orders']['sf']=$start_from;
	$_SESSION['state']['orders']['warehouse_orders']['f_field']=$f_field;
	$_SESSION['state']['orders']['warehouse_orders']['f_value']=$f_value;
	$_SESSION['state']['orders']['warehouse_orders']['elements']=$elements;

	$where="where  `Delivery Note Show in Warehouse Orders`='Yes'  ";

	$_elements='';
	$elements_count=0;
	foreach ($elements as $_key=>$_value) {
		if ($_value) {
			$elements_count++;

			if ($_key=='ReadytoShip') {
				$_key="'Approved'";
			}if ($_key=='Done') {
				$_key="'Packed Done'";
			}if ($_key=='PickingAndPacking') {
				$_key="'Picking & Packing','Packer Assigned','Picker Assigned','Picking','Packing','Packed','Picker & Packer Assigned'";
			}if ($_key=='ReadytoRestock') {
				$_key="'Cancelled to Restock'";
			}if ($_key=='ReadytoPack') {
				$_key="'Picked'";
			}if ($_key=='ReadytoPick') {
				$_key="'Ready to be Picked'";
			}

			$_elements.=','.$_key;
		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($elements_count==0) {
		$where.=' and false' ;
	} elseif ($elements_count<6) {
		$where.=' and `Delivery Note State` in ('.$_elements.')' ;
	}else {
		$where.=' and `Delivery Note State` not in  ("Dispatched","Cancelled")' ;
	}





	$wheref='';

	if ($f_field=='max' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Delivery Note Date Created`))<=".$f_value."    ";
	else if ($f_field=='min' and is_numeric($f_value) )
			$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Delivery Note Date Created`))>=".$f_value."    ";
		elseif ($f_field=='customer_name' and $f_value!='')
			$wheref.=" and  `Delivery Note Customer Name` like '".addslashes($f_value)."%'";
		elseif ($f_field=='public_id' and $f_value!='')
			$wheref.=" and  `Delivery Note ID` like '".addslashes($f_value)."%'";


		$sql="select count(*) as total from `Delivery Note Dimension`   $where $wheref ";
	// print $sql ;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($where=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		$sql="select count(*) as total from `Delivery Note Dimension`  $where";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}
	mysql_free_result($result);

	$rtext=number($total_records)." ".ngettext('delivery note','delivery notes',$total_records);

	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	$filter_msg='';

	switch ($f_field) {
	case('public_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>)";
		break;
	case('customer_name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with customer")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with customer')." <b>".$f_value."*</b>)";
		break;
	case('minvalue'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>)";
		break;

	case('max'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').")";
		break;
	}




	$_order=$order;
	$_dir=$order_direction;



	if ($order=='customer')
		$order='`Delivery Note Customer Name`';
	elseif ($order=='public_id')
		$order='`Delivery Note File As`';
	elseif ($order=='status')
		$order='`Delivery Note State`';
	else
		$order='`Delivery Note Date Created`';



	$sql="select  `Delivery Note State`,`Delivery Note Assigned Packer Key`,`Delivery Note XHTML State`,`Delivery Note Assigned Packer Alias`,`Delivery Note Fraction Packed`,`Delivery Note Fraction Picked`,`Delivery Note Assigned Picker Key`,`Delivery Note Assigned Picker Alias`, `Delivery Note Date Created`,`Delivery Note Key`,`Delivery Note Customer Name`,`Delivery Note Estimated Weight`,`Delivery Note Distinct Items`,`Delivery Note State`,`Delivery Note ID`,`Delivery Note Estimated Weight`,`Delivery Note Distinct Items`  from `Delivery Note Dimension`   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
	//print $sql;
	global $myconf;

	$data=array();

	$res = mysql_query($sql);
	//print $sql;
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$w=weight($row['Delivery Note Estimated Weight'],'Kg',0,true);
		$picks=number($row['Delivery Note Distinct Items']);



		$operations=get_dn_operations($row,$user,'warehouse_orders');

		if ($row['Delivery Note State']=='Picked' or $row['Delivery Note State']=='Packer Assigned' or $row['Delivery Note State']=='Packing' or  $row['Delivery Note State']=='Packed') {
			$see_link=sprintf("<a href='order_pack_aid.php?id=%d&refresh=1'>%s</a>",$row['Delivery Note Key'],"See Packing Sheet");
		}elseif ($row['Delivery Note State']=='Ready to be Picked' or $row['Delivery Note State']=='Picking' or  $row['Delivery Note State']=='Picker Assigned'  or  $row['Delivery Note State']=='Picker & Packer Assigned'   or  $row['Delivery Note State']=='Picking & Packing') {
			$see_link=sprintf("<a href='order_pick_aid.php?id=%d&refresh=1'>%s</a>",$row['Delivery Note Key'],"See Picking Sheet");
		}else {
			$see_link='';
		}


		$state='<div id="dn_state'.$row['Delivery Note Key'].'">'.$row['Delivery Note XHTML State'].'</div>';

		$data[]=array(
			'id'=>$row['Delivery Note Key'],
			'public_id'=>sprintf("<a href='dn.php?id=%d'>%s</a>",$row['Delivery Note Key'],$row['Delivery Note ID']),
			'customer'=>$row['Delivery Note Customer Name'],
			'weight'=>$w,
			'state'=>$state,
			'picks'=>$picks,
			'points'=>"$w, <span style='display: inline-block;width:27px;'>$picks</span>",
			'date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($row['Delivery Note Date Created'])),
			'operations'=>$operations,
			'see_link'=>$see_link//." ".$row['Delivery Note State']

		);
	}
	mysql_free_result($res);

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records-$filtered,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);



}

function assign_picker($data) {
	global $user;

	$dn=new DeliveryNote($data['dn_key']);
	if (!$dn->id) {
		$response=array(
			'state'=>400,
			'msg'=>'Unknown Delivery Note'
		);
		echo json_encode($response);
		exit;
	}

	$autorized=false;

	if ($user->data['User Type']=='Warehouse') {

	}elseif (!$user->can_edit('assign_pp') and !$user->can_edit('pick')) {
		$sql=sprintf("select count(*) as cnt from `Staff Dimension` where `Staff PIN`=%d and `Staff Is Supervisor`='Yes' and `Staff Currently Working`='Yes'", $data['pin']);
		//print $sql;
		$result=mysql_query($sql);
		$row=mysql_fetch_assoc($result);
		//print_r($row);exit;
		if ($row['cnt'] > 0) {
			$autorized=true;
		}

	}else {
		$autorized=true;
	}

	if ($autorized) {
		$dn->assign_picker($data['staff_key']);
	}else {
		$response=array(
			'state'=>400,
			'msg'=>_('Wrong Supervisor PIN')
		);
		echo json_encode($response);
		exit;
	}





	if ($dn->assigned) {
		$response=array(
			'state'=>200,
			'action'=>'updated',
			'operations'=>get_dn_operations($dn->data,$user),
			'dn_state'=>$dn->data['Delivery Note XHTML State'],
			'dn_key'=>$dn->dn_key,
			'staff_key'=>$data['staff_key']
		);



	} else if ($dn->error) {
			$response=array(
				'state'=>400,
				'msg'=>$dn->msg
			);



		} else {
		$response=array(
			'state'=>200,
			'action'=>'uncharged',

		);


	}
	echo json_encode($response);

}







function assign_packer($data) {
	global $user;

	$dn=new DeliveryNote($data['dn_key']);
	if (!$dn->id) {
		$response=array(
			'state'=>400,
			'msg'=>'Unknown Delivery Note'
		);
		echo json_encode($response);
		exit;
	}

	$autorized=false;
	if (!$user->can_edit('assign_pp')) {
		$sql=sprintf("select count(*) as cnt from `Staff Dimension` where `Staff PIN`=%d and `Staff Is Supervisor`='Yes' and `Staff Currently Working`='Yes'", $data['pin']);
		//print $sql;
		$result=mysql_query($sql);
		$row=mysql_fetch_assoc($result);
		//print_r($row);exit;
		if ($row['cnt'] > 0) {
			$autorized=true;
		}

	}else {
		$autorized=true;
	}

	if ($autorized) {
		$dn->assign_packer($data['staff_key']);
	}else {
		$response=array(
			'state'=>400,
			'msg'=>_('Wrong Supervisor PIN')
		);
		echo json_encode($response);
		exit;
	}





	if ($dn->assigned) {
		$response=array(
			'state'=>200,
			'action'=>'updated',
			'operations'=>get_dn_operations($dn->data,$user),
			'dn_state'=>$dn->data['Delivery Note XHTML State'],
			'dn_key'=>$dn->id,
			'staff_key'=>$data['staff_key']
		);



	} else if ($dn->error) {
			$response=array(
				'state'=>400,
				'msg'=>$dn->msg
			);



		} else {
		$response=array(
			'state'=>200,
			'action'=>'uncharged',

		);


	}
	echo json_encode($response);

}


function start_picking($data) {
	global $user;
	$dn=new DeliveryNote($data['dn_key']);
	if (!$dn->id) {
		$response=array(
			'state'=>400,
			'msg'=>'Unknown Delivery Note'
		);
		echo json_encode($response);
		exit;
	}



	$dn->start_picking($data['staff_key']);




	if ($dn->assigned) {
		$response=array(
			'state'=>200,
			'action'=>'updated',
			'operations'=>get_dn_operations($dn->data,$user),
			'dn_state'=>$dn->data['Delivery Note XHTML State'],
			'dn_key'=>$dn->id
		);



	} else if ($dn->error) {
			$response=array(
				'state'=>400,
				'msg'=>$dn->msg
			);



		} else {
		$response=array(
			'state'=>200,
			'action'=>'uncharged',

		);


	}
	echo json_encode($response);

}

function start_packing($data) {
	global $user;
	$dn=new DeliveryNote($data['dn_key']);
	if (!$dn->id) {
		$response=array(
			'state'=>400,
			'msg'=>'Unknown Delivery Note'
		);
		echo json_encode($response);
		exit;
	}

	/*
	$sql=sprintf("select * from `Staff Dimension` where `Staff Key`=%d and `Staff Currently Working`='Yes'", $data['staff_key']);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_assoc($result)) {
		if ($row['Staff PIN'] != $data['pin']) {
			$response=array(
				'state'=>400,
				'msg'=>'Wrong PIN'
			);
			echo json_encode($response);
			return;
		}
		else

	}
*/

	$dn->start_packing($data['staff_key']);

	if ($dn->assigned) {
		$response=array(
			'state'=>200,
			'action'=>'updated',
			'operations'=>get_dn_operations($dn->data,$user),
			'dn_state'=>$dn->data['Delivery Note XHTML State'],
			'dn_key'=>$dn->id,

		);



	} else if ($dn->error) {
			$response=array(
				'state'=>400,
				'msg'=>$dn->msg
			);



		} else {
		$response=array(
			'state'=>200,
			'action'=>'uncharged',

		);


	}
	echo json_encode($response);

}


function set_packing_aid_sheet_pending_as_packed($data) {

	global $user;

	$dn_key=$data['dn_key'];

	$delivery_note=new DeliveryNote($dn_key);


	$where=sprintf(' where `Delivery Note Key`=%d',$dn_key);
	$sql="select `Packer Key`,`Inventory Transaction Key`, `Picked`,`Inventory Transaction Key`,`Part XHTML Currently Used In`,Part.`Part SKU`,`Part Unit Description` from `Inventory Transaction Fact` ITF  left join  `Part Dimension` Part on  (Part.`Part SKU`=ITF.`Part SKU`)  $where  ";
	// print $sql;
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$todo=$row['Picked'];

		if ($todo) {
			$delivery_note->set_as_packed($row['Inventory Transaction Key'],round($todo,8),gmdate("Y-m-d H:i:s"),$row['Packer Key']);
		}
	}
	$delivery_note->update_packing_percentage();

	if ($data['approve_pp']) {
		$delivery_note->approve_packed();
	}

	$response=array(
		'state'=>200,
		'dn_key'=>$delivery_note->id,
		'operations'=>get_dn_operations($delivery_note->data,$user),
		'dn_state'=>$delivery_note->data['Delivery Note XHTML State']
	);
	echo json_encode($response);

}

function set_picking_aid_sheet_pending_as_picked($data) {

	global $user;

	$dn_key=$data['dn_key'];

	$delivery_note=new DeliveryNote($dn_key);

	if ($delivery_note->id) {
		$where=sprintf(' where `Delivery Note Key`=%d',$delivery_note->id);
		$sql="select `Given`,`Picker Key`,`Inventory Transaction Key`, `Picked`,IFNULL(`Out of Stock`,0) as `Out of Stock`,IFNULL(`Not Found`,0) as `Not Found`,IFNULL(`No Picked Other`,0) as `No Picked Other` ,`Inventory Transaction Key`,`Part XHTML Currently Used In`,Part.`Part SKU`,`Part Unit Description`,`Required`,`Part XHTML Picking Location` from `Inventory Transaction Fact` ITF  left join  `Part Dimension` Part on  (Part.`Part SKU`=ITF.`Part SKU`)  $where  ";
		// print $sql;
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$todo=$row['Given']+$row['Required']-$row['Out of Stock']-$row['Not Found']-$row['No Picked Other'];

			if ($todo) {
				$delivery_note->set_as_picked($row['Inventory Transaction Key'],round($todo,8),false,$row['Picker Key']);
			}


		}
		$delivery_note->update_picking_percentage();
		$response=array(
			'state'=>200,
			'dn_key'=>$delivery_note->id,
			'operations'=>get_dn_operations($delivery_note->data,$user),
			'dn_state'=>$delivery_note->data['Delivery Note XHTML State'],

			'dn_formated_state'=>$delivery_note->get_formated_state(),
		);
	}else {
		$response=array(
			'state'=>400,'msg'=>'DN not found'
		);
	}

	echo json_encode($response);

}



function picking_aid_sheet() {
	if (isset( $_REQUEST['dn_key']) and is_numeric( $_REQUEST['dn_key']))
		$order_id=$_REQUEST['dn_key'];
	else {

		return;
	}

	$data=array('dn_key'=>$order_id, 'staff_key'=>1);



	$where=sprintf(' where `Delivery Note Key`=%d',$order_id);

	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$order='`Location Code`';
	$order_dir='';

	$data=array();
	$sql="select `Part Reference`, Part.`Part Current On Hand Stock` as total_stock, PLD.`Quantity On Hand` as stock_in_picking,`Packed`,`Given`,`Location Code`,`Picking Note`,`Picked`,IFNULL(`Out of Stock`,0) as `Out of Stock`,IFNULL(`Not Found`,0) as `Not Found`,IFNULL(`No Picked Other`,0) as `No Picked Other` ,`Inventory Transaction Key`,`Part XHTML Currently Used In`,Part.`Part SKU`,`Part Unit Description`,`Required`,`Part XHTML Picking Location` from `Inventory Transaction Fact` ITF  left join  `Part Dimension` Part on  (Part.`Part SKU`=ITF.`Part SKU`) left join  `Location Dimension` L on  (L.`Location Key`=ITF.`Location Key`) left join `Part Location Dimension` PLD on (ITF.`Location Key`=PLD.`Location Key` and ITF.`Part SKU`=PLD.`Part SKU`) $where order by  $order $order_dir  ";
	//   print $sql;
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		//print_r($row);

		$todo=$row['Required']+$row['Given']-$row['Picked']-$row['Out of Stock']-$row['Not Found']-$row['No Picked Other'];

		if ($todo==0)
			$formated_todo='';
		else {
			if ($todo<0) {
				$formated_todo='<span style="font-weight:800;color:#FFFFFF;background:#EE0000;padding:0 4px">'.number($todo).'</span>';
			}else {
				$formated_todo=number($todo);
			}

		}


		$notes='';
		if ($row['Packed']!=0) {
			$notes.=_('Packed').' '.number($row['Packed']);
		}
		if ($row['Out of Stock']!=0) {
			$notes.=_('Out of Stock').' '.number($row['Out of Stock']);
		}
		if ($row['Not Found']!=0) {
			$notes.='<br/>'._('Not Found').' '.number($row['Not Found']);
		}
		if ($row['No Picked Other']!=0) {
			$notes.='<br/>'._('Not picked (other)').' '.number($row['No Picked Other']);
		}
		$notes=preg_replace('/^\<br\/\>/', '', $notes);

		$stock_in_picking=$row['stock_in_picking'];
		$total_stock=$row['total_stock'];

		//print_r($row);exit;
		$sku=sprintf('<a href="part.php?sku=%d">SKU%05d</a>',$row['Part SKU'],$row['Part SKU']);
		$reference=sprintf('<a href="part.php?sku=%d">%s</a>',$row['Part SKU'],$row['Part Reference']);

		$picking_notes=sprintf('<a href="part.php?sku=%d">%s</a>',$row['Part SKU'],$row['Picking Note']);
		$_id=$row['Part SKU'];
		$data[]=array(
			'itf_key'=>$row['Inventory Transaction Key'],
			'sku'=>$sku,
			'reference'=>$reference,
			'description'=>$row['Part Unit Description'].($row['Picking Note']?' <i>('.$row['Picking Note'].')</i>':''),
			'used_in'=>$row['Part XHTML Currently Used In'],
			'quantity'=>number($row['Required']+$row['Given']),
			//'location'=>sprintf($row['Location Code'].'<img src="art/icons/info_bw.png" onClick="get_locations(this,{$_id})">'),
			'location'=>sprintf(" <img style='width:12px;cursor:pointer' src='art/icons/info_bw.png' onClick='get_locations(this,%d)'> <b>%s</b> <span style='float:right;color:#777;margin-left:10px'>[<b>%d</b>,%d]</span>", $_id, $row['Location Code'],$stock_in_picking,$total_stock),

			'done'=>(!$todo?'&#x2713;':''),
			//'check_mark'=>(($row['Packed']-$row['Picked'])?'&#8704;':'<span style="color:#ccc">&#8704;</span>'),


			'check_mark'=>($todo?'&#8704;':'<span style="color:#ccc">&#8704;</span>'),
			'add'=>($todo?'+':'<span style="color:#ccc">+</span>'),
			'remove'=>(($row['Picked']-$row['Packed'])?'-':'<span style="color:#ccc">-</span>'),
			'picked'=>$row['Picked'],
			'packed'=>$row['Packed'],
			'todo'=>$todo,
			'formated_todo'=>$formated_todo,
			'notes'=>$notes,
			'picking_notes'=>$picking_notes,
			'required'=>($row['Required']+$row['Given']),

			'out_of_stock'=>$row['Out of Stock'],
			'not_found'=>$row['Not Found'],
			'no_picked_other'=>$row['No Picked Other'],
			'see_link'=>'xx<a href="xx">'._('pick aid sheet').'</a>'
		);
	}





	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}
function packing_aid_sheet() {
	if (isset( $_REQUEST['dn_key']) and is_numeric( $_REQUEST['dn_key']))
		$order_id=$_REQUEST['dn_key'];
	else {

		return;
	}

	$data=array('dn_key'=>$order_id, 'staff_key'=>1);


	$where=sprintf(' where `Delivery Note Key`=%d',$order_id);

	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$order='`Picking Note`';
	$order_dir='';


	$data=array();
	$sql="select `Part Reference`,`Given`,`Packed`,`Location Code`,`Picking Note`,`Picked`,IFNULL(`Out of Stock`,0) as `Out of Stock`,IFNULL(`Not Found`,0) as `Not Found`,IFNULL(`No Picked Other`,0) as `No Picked Other` ,`Inventory Transaction Key`,`Part XHTML Currently Used In`,Part.`Part SKU`,`Part Unit Description`,`Required`,`Part XHTML Picking Location` from `Inventory Transaction Fact` ITF  left join  `Part Dimension` Part on  (Part.`Part SKU`=ITF.`Part SKU`) left join  `Location Dimension` L on  (L.`Location Key`=ITF.`Location Key`) $where  order by  $order $order_dir ";
	//   print $sql;
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		//print_r($row);
		$formated_todo='';
		$todo=0;
		if ($row['Required']+$row['Given']-$row['Picked']>0) {

			$todo=$row['Required']+$row['Given']-$row['Picked']-$row['Out of Stock']-$row['Not Found']-$row['No Picked Other'];
			if ($todo==0)
				$formated_todo='';
			else
				$formated_todo=number($todo);
		}


		$notes='';
		if ($todo) {
			$notes.=_('To pick').' '.$formated_todo.'<br/>';
		}
		if ($row['Out of Stock']!=0) {
			$notes.=_('Out of Stock').' '.number($row['Out of Stock']).'<br/>';
		}
		if ($row['Not Found']!=0) {
			$notes.=_('Not Found').' '.number($row['Not Found']).'<br/>';
		}
		if ($row['No Picked Other']!=0) {
			$notes.=_('Not picked (other)').' '.number($row['No Picked Other']).'<br/>';
		}

		$notes=preg_replace('/\<br\/\>$/', '', $notes);


		$sku=sprintf('<a href="part.php?sku=%d">SKU%05d</a>',$row['Part SKU'],$row['Part SKU']);
		$reference=sprintf('<a href="part.php?sku=%d">%s</a>',$row['Part SKU'],$row['Part Reference']);

		$data[]=array(
			'itf_key'=>$row['Inventory Transaction Key'],
			'sku'=>$sku,
			'reference'=>$reference,
			'description'=>$row['Part Unit Description'].($row['Picking Note']?' <i>('.$row['Picking Note'].')</i>':''),
			'used_in'=>$row['Part XHTML Currently Used In'],
			'quantity'=>number($row['Required']+$row['Given']),
			'location'=>$row['Location Code'],
			'done'=>(( ($row['Packed']-$row['Picked'])==0 and $todo==0)?'&#x2713;':''),
			'check_mark'=>(($row['Packed']-$row['Picked'])?'&#8704;':'<span style="color:#ccc">&#8704;</span>'),
			'add'=>(($row['Packed']-$row['Picked'])?'+':'<span style="color:#ccc">+</span>'),
			'remove'=>(($row['Packed'])?'-':'<span style="color:#ccc">-</span>'),
			'picked'=>$row['Picked'],
			'packed'=>$row['Packed'],
			'todo'=>$todo,
			'formated_todo'=>$formated_todo,
			'notes'=>$notes,
			'picking_notes'=>$row['Picking Note'],
			'required'=>($row['Required']+$row['Given']),
			'picked'=>$row['Picked'],
			'out_of_stock'=>$row['Out of Stock'],
			'not_found'=>$row['Not Found'],
			'no_picked_other'=>$row['No Picked Other'],
			'see_link'=>'<a href="xx">'._('pick aid sheet').'</a>'
		);
	}





	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function create_invoice($data) {
	global $user;
	$dn_key=$data['dn_key'];
	$dn=new DeliveryNote($dn_key);
	$invoice=$dn->create_invoice();

	if (!$dn->error and $invoice->id) {
		$response=array(
			'state'=>200,
			'invoice_key'=>$invoice->id
		);

		if (array_key_exists('order_key',$data)) {
			$order=new Order($data['order_key']);
			$response['order_key']=$order->id;

			$response['order_operations']=get_orders_operations($order->data,$user);
			$response['order_dispatch_state']=get_order_formated_dispatch_state($order->data['Order Current Dispatch State'],$order->id);
			$response['order_payment_state']=get_order_formated_payment_state($order->data);

		}

		echo json_encode($response);
	} else {
		$response=array('state'=>400,'msg'=>$dn->msg);
		echo json_encode($response);

	}

}


function create_invoice_order($data) {

	$order_key=$data['order_key'];
	$order=new Order($order_key);

	$dn_keys=$order->get_delivery_notes_ids();

	$number_dns=count($dn_keys);

	if ($number_dns==0) {
		$response= array('state'=>400,'msg'=>'no delivery notes associated with order');
		echo json_encode($response);
		return;

	}elseif ($number_dns>1) {
		$response= array('state'=>400,'msg'=>'multiple delivery notes associated with order');
		echo json_encode($response);
		return;
	}
	$data['dn_key']=array_pop($dn_keys);
	create_invoice($data);

}


function cancel_post_transactions_in_process($data) {
	$order=new Order($data['order_key']);
	$order->cancel_post_transactions_in_process();
	if (!$order->error) {
		$response=array('state'=>200,'order_key'=>$order->id);
		echo json_encode($response);
	} else {
		$response=array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);

	}

}


function save_credit($data) {


	$order=new Order($data['order_key']);
	$order->submit_credits();
	if (!$order->error) {
		$response=array('state'=>200,'order_key'=>$order->id);
		echo json_encode($response);
	} else {
		$response=array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);

	}

}

function cancel_saved_credit($data) {


	$order=new Order($data['order_key']);
	$order->cancel_submited_credits();
	if (!$order->error) {
		$response=array('state'=>200,'order_key'=>$order->id);
		echo json_encode($response);
	} else {
		$response=array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);

	}

}



function send_post_order_to_warehouse($data) {

	$order=new Order($data['order_key']);
	$customer=new Customer ($order->data['Order Customer Key']);
	$ship_to=$customer->get_ship_to();
	$billing=$customer->get_billing_to();
	$transaction_data=array(
		'Metadata'=>'',
		'Current Payment State'=>'No Applicable',
		'Order Tax Rate'=>$order->data['Order Tax Rate'],
		'Order Tax Code'=>$order->data['Order Tax Code'],
		'Ship To Key'=>$ship_to->id,
		'Billing To Key'=>$billing->id,
		'Gross'=>0,
	);

	$order->add_post_order_transactions($transaction_data);



	$order->send_post_action_to_warehouse();
	if (!$order->error) {
		$response=array('state'=>200,'order_key'=>$order->id);
		echo json_encode($response);
	} else {
		$response=array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);

	}

}

function create_refund($data) {

	$date=date("Y-m-d H:i:s");
	$order=new Order($data['order_key']);

	$refund=$order->create_refund(array(
			'Invoice Metadata'=>'',
			'Invoice Date'=>$date
		)
	);



	if (!$order->error) {
		$response=array('state'=>200,'order_key'=>$order->id);
		echo json_encode($response);
	} else {
		$response=array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);

	}

}

function update_ship_to_key($data) {

	$order=new Order($data['order_key']);
	$order->update_ship_to($data['ship_to_key']);
	if ($order->updated) {
		$response=array('state'=>200,'result'=>'updated','order_key'=>$order->id,'new_value'=>$order->new_value);
		echo json_encode($response);
	} else {
		$response=array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);

	}


}

function update_ship_to_key_from_address($data) {

	$order=new Order($data['order_key']);
	$address=new Address($data['address_key']);
	$ship_to_key=$address->get_ship_to();

	$order->update_ship_to($ship_to_key);

	if ($order->error) {
		$response=array('state'=>400,'result'=>'no_change','msg'=>$order->msg);
		echo json_encode($response);
	}else {


		$updated_data=array(
			'order_items_gross'=>$order->get('Items Gross Amount'),
			'order_items_discount'=>$order->get('Items Discount Amount'),
			'order_items_net'=>$order->get('Items Net Amount'),
			'order_net'=>$order->get('Total Net Amount'),
			'order_tax'=>$order->get('Total Tax Amount'),
			'order_charges'=>$order->get('Charges Net Amount'),
			'order_credits'=>$order->get('Net Credited Amount'),
			'order_shipping'=>$order->get('Shipping Net Amount'),
			'order_total'=>$order->get('Total Amount'),
			'ordered_products_number'=>$order->get('Number Products'),
		);

		$response= array(
			'state'=>200,

			'data'=>$updated_data,
			'order_key'=>$order->id,
			'ship_to'=>$order->get('Order XHTML Ship Tos'),
			'tax_info'=>$order->get_formated_tax_info_with_operations()

		);


		echo json_encode($response);


	}


}


function update_billing_to_key($data) {

	$order=new Order($data['order_key']);
	$order->update_billing_to($data['billing_to_key']);
	if ($order->updated) {
		$response=array('state'=>200,'result'=>'updated','order_key'=>$order->id,'new_value'=>$order->new_value);
		echo json_encode($response);
	} else {
		$response=array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);

	}


}



function add_insurance($data) {

	$order= new Order($data['order_key']);
	$onptf_key=$order->add_insurance($data['insurance_key']);

	$order->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);

	$updated_data=array(
		'order_items_gross'=>$order->get('Items Gross Amount'),
		'order_items_discount'=>$order->get('Items Discount Amount'),
		'order_items_net'=>$order->get('Items Net Amount'),
		'order_net'=>$order->get('Total Net Amount'),
		'order_tax'=>$order->get('Total Tax Amount'),
		'order_charges'=>$order->get('Charges Net Amount'),
		'order_insurance'=>$order->get('Insurance Net Amount'),
		'order_credits'=>$order->get('Net Credited Amount'),
		'order_shipping'=>$order->get('Shipping Net Amount'),
		'order_total'=>$order->get('Total Amount'),
		'ordered_products_number'=>$order->get('Number Products'),
		'store_currency_total_balance'=>money($order->data['Order Balance Total Amount'],$order->data['Order Currency'])
	);

	$response= array(
		'state'=>200,

		'data'=>$updated_data,
		'order_key'=>$order->id,
		'ship_to'=>$order->get('Order XHTML Ship Tos'),
		'tax_info'=>$order->get_formated_tax_info_with_operations(),
		'onptf_key'=>$onptf_key,
		'order_insurance_amount'=>$order->data['Order Insurance Net Amount']

	);


	echo json_encode($response);

}
function remove_insurance($data) {

	$order= new Order($data['order_key']);
	$order->remove_insurance($data['onptf_key']);

	$order->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);

	$updated_data=array(
		'order_items_gross'=>$order->get('Items Gross Amount'),
		'order_items_discount'=>$order->get('Items Discount Amount'),
		'order_items_net'=>$order->get('Items Net Amount'),
		'order_net'=>$order->get('Total Net Amount'),
		'order_tax'=>$order->get('Total Tax Amount'),
		'order_charges'=>$order->get('Charges Net Amount'),
		'order_insurance'=>$order->get('Insurance Net Amount'),
		'order_credits'=>$order->get('Net Credited Amount'),
		'order_shipping'=>$order->get('Shipping Net Amount'),
		'order_total'=>$order->get('Total Amount'),
		'ordered_products_number'=>$order->get('Number Products'),
		'store_currency_total_balance'=>money($order->data['Order Balance Total Amount'],$order->data['Order Currency'])
	);

	$response= array(
		'state'=>200,

		'data'=>$updated_data,
		'order_key'=>$order->id,
		'ship_to'=>$order->get('Order XHTML Ship Tos'),
		'tax_info'=>$order->get_formated_tax_info_with_operations(),

		'order_insurance_amount'=>$order->data['Order Insurance Net Amount']

	);


	echo json_encode($response);




}

function update_billing_to_key_from_address($data) {

	$order=new Order($data['order_key']);
	$address=new Address($data['address_key']);
	$billing_to_key=$address->get_billing_to();

	$order->update_billing_to($billing_to_key);

	if ($order->error) {
		$response=array('state'=>400,'result'=>'no_change','msg'=>$order->msg);
		echo json_encode($response);
	}else {


		$updated_data=array(
			'order_items_gross'=>$order->get('Items Gross Amount'),
			'order_items_discount'=>$order->get('Items Discount Amount'),
			'order_items_net'=>$order->get('Items Net Amount'),
			'order_net'=>$order->get('Total Net Amount'),
			'order_tax'=>$order->get('Total Tax Amount'),
			'order_charges'=>$order->get('Charges Net Amount'),
			'order_credits'=>$order->get('Net Credited Amount'),
			'order_shipping'=>$order->get('Shipping Net Amount'),
			'order_total'=>$order->get('Total Amount'),
			'ordered_products_number'=>$order->get('Number Products'),
		);

		$response= array(
			'state'=>200,

			'data'=>$updated_data,
			'order_key'=>$order->id,
			'billing_to'=>$order->get('Order XHTML Billing Tos'),
			'tax_info'=>$order->get_formated_tax_info_with_operations()


		);


		echo json_encode($response);
	}


}




function approve_packing($data) {
	global $user;
	$dn=new DeliveryNote($data['dn_key']);


	if ($dn->data['Delivery Note State']!='Packed' ) {
		$response=array('state'=>400,'msg'=>'Delivery Note is '.$dn->data['Delivery Note State']);
		echo json_encode($response);
		return;
	}

	$dn->approve_packed();


	$response=array(
		'state'=>200,
		'msg'=>'',
		'dn_key'=>$dn->id,
		'operations'=>get_dn_operations($dn->data,$user),
		'dn_state'=>$dn->data['Delivery Note XHTML State'],
		'dn_formated_state'=>$dn->get_formated_state(),

	);
	echo json_encode($response);



}



function pick_order($data) {

	$dn=new DeliveryNote($data['dn_key']);
	if ($data['key']=='quantity') {


		$transaction_data=$dn->set_as_picked($data['itf_key'],round($data['new_value'],8),date("Y-m-d H:i:s"),$data['picker_key']);

		$dn->update_picking_percentage();
		if (!$dn->error) {

			$response=array('state'=>200,
				'result'=>'updated',
				'new_value'=>$transaction_data['Picked'],
				'todo'=>$transaction_data['Pending'],
				'formated_todo'=>number($transaction_data['Pending']),

				'picked'=>$transaction_data['Picked'],
				'packed'=>$transaction_data['Packed'],
				'percentage_picked'=>$dn->get('Fraction Picked'),
				'number_picked_transactions'=>$dn->get_number_picked_transactions(),
				'number_transactions'=>$dn->get_number_transactions(),
				'dn_formated_state'=>$dn->get_formated_state(),
				'dn_xhtml_state'=>$dn->data['Delivery Note XHTML State'],
				'finish_picking_date'=>$dn->get('Date Finish Picking'),

			);
			echo json_encode($response);
		} else {
			$response=array('state'=>400,'msg'=>$dn->msg);
			echo json_encode($response);
		}
		return;
	}

}

function pack_order($data) {

	$dn=new DeliveryNote($data['dn_key']);
	if ($data['key']=='quantity') {


		$transaction_data=$dn->set_as_packed($data['itf_key'],round($data['new_value'],8),date("Y-m-d H:i:s"),$data['packer_key']);
		//print_r($transaction_data);
		$dn->update_packing_percentage();


		if (!$dn->error) {

			$response=array('state'=>200,
				'result'=>'updated',
				'new_value'=>$transaction_data['Packed'],
				// 'todo'=>$transaction_data['Pending'],
				// 'formated_todo'=>number($transaction_data['Pending']),

				'packed'=>$transaction_data['Packed'],
				'picked'=>$transaction_data['Picked'],
				'percentage_packed'=>$dn->get('Fraction Packed'),
				'number_packed_transactions'=>$dn->get_number_packed_transactions(),
				'number_transactions'=>$dn->get_number_transactions(),
				'finish_packing_date'=>$dn->get('Date Finish Packing'),
				'dn_formated_state'=>$dn->get_formated_state(),
				'dn_xhtml_state'=>$dn->data['Delivery Note XHTML State']
			);






			echo json_encode($response);
		} else {
			$response=array('state'=>400,'msg'=>$dn->msg);
			echo json_encode($response);
		}
		return;
	}

}

function update_no_dispatched($data) {
	$dn=new DeliveryNote($data['dn_key']);
	if (!$dn->id) {
		$response=array('state'=>400,'msg'=>$dn->msg);
		echo json_encode($response);
	}
	$transaction_data=$dn->update_unpicked_transaction_data($data['itf_key'],array(
			'Out of Stock'=>$data['out_of_stock'],
			'Not Found'=>$data['not_found'],
			'No Picked Other'=>$data['no_picked_other']
			// 'No Authorized'=>$data['no_authorized']
		)
	);
	$dn->update_picking_percentage();


	if (!$dn->error) {

		if ($dn->updated) {

			$formated_todo='';

			if ($transaction_data['Pending']>0) {
				$formated_todo=number($transaction_data['Pending']);
			}




			$notes='';
			if ($transaction_data['Out of Stock']!=0) {
				$notes.=_('Out of Stock').': '.number($transaction_data['Out of Stock']);
			}
			//  if ($transaction_data['No Authorized']!=0) {
			//   $notes.='<br/>'._('No Authorized').': '.number($transaction_data['No Authorized']);
			//  }
			if ($transaction_data['Not Found']!=0) {
				$notes.='<br/>'._('Not Found').': '.number($transaction_data['Not Found']);
			}
			if ($transaction_data['No Picked Other']!=0) {
				$notes.='<br/>'._('Not picked (other)').': '.number($transaction_data['No Picked Other']);
			}
			$notes=preg_replace('/^\<br\/\>/','',$notes);

			$response=array('state'=>200,'result'=>'updated','new_value'=>$dn->new_value,
				'todo'=>$transaction_data['Pending'],
				'formated_todo'=>$formated_todo,
				'notes'=>$notes,
				'out_of_stock'=>$transaction_data['Out of Stock'],
				'not_found'=>$transaction_data['Not Found'],
				'no_picked_other'=>$transaction_data['No Picked Other'],
				//   'no_authorized'=>$transaction_data['No Authorized'],
				'picked'=>$transaction_data['Picked'],
				'percentage_picked'=>$dn->get('Fraction Picked'),
				'number_picked_transactions'=>$dn->get_number_picked_transactions(),
				'number_transactions'=>$dn->get_number_transactions()
			);

		} else {
			$response=array('state'=>200,'result'=>'no_change');

		}

	} else {
		$response=array('state'=>400,'msg'=>$dn->msg);

	}
	echo json_encode($response);

}
function new_dn_list($data) {

	$list_name=$data['list_name'];
	$store_id=$data['store_id'];

	$sql=sprintf("select * from `List Dimension`  where `List Name`=%s and `List Parent Key`=%d and `List Scope`='DN'",
		prepare_mysql($list_name),
		$store_id
	);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$response=array('resultset'=>
			array(
				'state'=>400,
				'msg'=>_('Another list has the same name')
			)
		);
		echo json_encode($response);
		return;
	}

	$list_type=$data['list_type'];

	$awhere=$data['awhere'];
	$table='`Delivery Note Dimension` D ';


	list($where,$table)=dn_awhere($awhere);

	$where.=sprintf(' and `Delivery Note Store Key`=%d ',$store_id);

	$sql="select count(Distinct D.`Delivery Note Key`) as total from $table  $where";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		if ($row['total']==0) {
			$response=array('resultset'=>
				array(
					'state'=>400,
					'msg'=>_('No order match this criteria')
				)
			);
			echo json_encode($response);
			return;

		}


	}
	mysql_free_result($res);

	$list_sql=sprintf("insert into `List Dimension` (`List Scope`,`List Parent Key`,`List Name`,`List Type`,`List Metadata`,`List Creation Date`) values ('Delivery Note',%d,%s,%s,%s,NOW())",
		$store_id,
		prepare_mysql($list_name),
		prepare_mysql($list_type),
		prepare_mysql(json_encode($data['awhere']))

	);
	mysql_query($list_sql);
	$order_list_key=mysql_insert_id();
	if ($list_type=='Static') {


		$sql="select D.`Delivery Note Key` from $table  $where group by D.`Delivery Note Key`";
		// print $sql;
		$result=mysql_query($sql);
		while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$order_key=$data['Delivery Note Key'];
			$sql=sprintf("insert into `List Delivery Note Bridge` (`List Key`,`Delivery Note Key`) values (%d,%d)",
				$order_list_key,
				$order_key
			);
			mysql_query($sql);

		}
		mysql_free_result($result);




	}




	$response=array(
		'state'=>200,
		'customer_list_key'=>$order_list_key

	);
	echo json_encode($response);
	exit;
}

function new_invoices_list($data) {

	$list_name=$data['list_name'];
	$store_id=$data['store_id'];

	$sql=sprintf("select * from `List Dimension`  where `List Name`=%s and `List Parent Key`=%d and `List Scope`='Invoice'",
		prepare_mysql($list_name),
		$store_id
	);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$response=array('resultset'=>
			array(
				'state'=>400,
				'msg'=>_('Another list has the same name')
			)
		);
		echo json_encode($response);
		return;
	}

	$list_type=$data['list_type'];

	$awhere=$data['awhere'];
	$table='`Invoice Dimension` I ';


	list($where,$table)=invoices_awhere($awhere);

	$where.=sprintf(' and `Invoice Store Key`=%d ',$store_id);

	$sql="select count(Distinct I.`Invoice Key`) as total from $table  $where";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		if ($row['total']==0) {
			$response=array('resultset'=>
				array(
					'state'=>400,
					'msg'=>_('No order match this criteria')
				)
			);
			echo json_encode($response);
			return;

		}


	}
	mysql_free_result($res);

	$list_sql=sprintf("insert into `List Dimension` (`List Scope`,`List Parent Key`,`List Name`,`List Type`,`List Metadata`,`List Creation Date`) values ('Invoice',%d,%s,%s,%s,NOW())",
		$store_id,
		prepare_mysql($list_name),
		prepare_mysql($list_type),
		prepare_mysql(json_encode($data['awhere']))

	);
	mysql_query($list_sql);
	$order_list_key=mysql_insert_id();
	if ($list_type=='Static') {


		$sql="select I.`Invoice Key` from $table  $where group by O.`Invoice Key`";
		//   print $sql;
		$result=mysql_query($sql);
		while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$order_key=$data['Invoice Key'];
			$sql=sprintf("insert into `List Invoice Bridge` (`List Key`,`Invoice Key`) values (%d,%d)",
				$order_list_key,
				$order_key
			);
			mysql_query($sql);

		}
		mysql_free_result($result);




	}




	$response=array(
		'state'=>200,
		'customer_list_key'=>$order_list_key

	);
	echo json_encode($response);
	exit;
}


function new_orders_list($data) {

	$list_name=$data['list_name'];
	$store_id=$data['store_id'];

	$sql=sprintf("select * from `List Dimension`  where `List Name`=%s and `List Parent Key`=%d and `List Scope`='Order'",
		prepare_mysql($list_name),
		$store_id
	);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$response=array('resultset'=>
			array(
				'state'=>400,
				'msg'=>_('Another list has the same name')
			)
		);
		echo json_encode($response);
		return;
	}

	$list_type=$data['list_type'];

	$awhere=$data['awhere'];
	$table='`Order Dimension` O ';


	list($where,$table)=orders_awhere($awhere);

	$where.=sprintf(' and `Order Store Key`=%d ',$store_id);

	$sql="select count(Distinct O.`Order Key`) as total from $table  $where";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		if ($row['total']==0) {
			$response=array('resultset'=>
				array(
					'state'=>400,
					'msg'=>_('No order match this criteria')
				)
			);
			echo json_encode($response);
			return;

		}


	}
	mysql_free_result($res);

	$list_sql=sprintf("insert into `List Dimension` (`List Scope`,`List Parent Key`,`List Name`,`List Type`,`List Metadata`,`List Creation Date`) values ('Order',%d,%s,%s,%s,NOW())",
		$store_id,
		prepare_mysql($list_name),
		prepare_mysql($list_type),
		prepare_mysql(json_encode($data['awhere']))

	);
	mysql_query($list_sql);
	$order_list_key=mysql_insert_id();
	if ($list_type=='Static') {


		$sql="select O.`Order Key` from $table  $where group by O.`Order Key`";
		//   print $sql;
		$result=mysql_query($sql);
		while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$order_key=$data['Order Key'];
			$sql=sprintf("insert into `List Order Bridge` (`List Key`,`Order Key`) values (%d,%d)",
				$order_list_key,
				$order_key
			);
			mysql_query($sql);

		}
		mysql_free_result($result);




	}




	$response=array(
		'state'=>200,
		'customer_list_key'=>$order_list_key

	);
	echo json_encode($response);
	exit;
}

function delete_order_list($data) {
	global $user;
	$sql=sprintf("select `List Parent Key`,`List Key` from `List Dimension` where `List Key`=%d",$data['key']);

	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {

		if (in_array($row['List Parent Key'],$user->stores)) {
			$sql=sprintf("delete from  `List Order Bridge` where `List Key`=%d",$data['key']);
			mysql_query($sql);
			$sql=sprintf("delete from  `List Dimension` where `List Key`=%d",$data['key']);
			mysql_query($sql);
			$response=array('state'=>200,'action'=>'deleted');
			echo json_encode($response);
			return;



		} else {
			$response=array('state'=>400,'msg'=>_('Forbidden Operation'));
			echo json_encode($response);
			return;
		}



	} else {
		$response=array('state'=>400,'msg'=>'Error no order list');
		echo json_encode($response);
		return;

	}



}

function delete_invoice_list($data) {
	global $user;
	$sql=sprintf("select `List Parent Key`,`List Key` from `List Dimension` where `List Key`=%d",$data['key']);

	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {

		if (in_array($row['List Parent Key'],$user->stores)) {
			$sql=sprintf("delete from  `List Invoice Bridge` where `List Key`=%d",$data['key']);
			mysql_query($sql);
			$sql=sprintf("delete from  `List Dimension` where `List Key`=%d",$data['key']);
			mysql_query($sql);
			$response=array('state'=>200,'action'=>'deleted');
			echo json_encode($response);
			return;



		} else {
			$response=array('state'=>400,'msg'=>_('Forbidden Operation'));
			echo json_encode($response);
			return;
		}



	} else {
		$response=array('state'=>400,'msg'=>'Error no invoice list');
		echo json_encode($response);
		return;

	}



}

function delete_dn_list($data) {
	global $user;
	$sql=sprintf("select `List Parent Key`,`List Key` from `List Dimension` where `List Key`=%d",$data['key']);

	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {

		if (in_array($row['List Parent Key'],$user->stores)) {
			$sql=sprintf("delete from  `List Delivery Note Bridge` where `List Key`=%d",$data['key']);
			mysql_query($sql);
			$sql=sprintf("delete from  `List Dimension` where `List Key`=%d",$data['key']);
			mysql_query($sql);
			$response=array('state'=>200,'action'=>'deleted');
			echo json_encode($response);
			return;



		} else {
			$response=array('state'=>400,'msg'=>_('Forbidden Operation'));
			echo json_encode($response);
			return;
		}



	} else {
		$response=array('state'=>400,'msg'=>'Error no delivery note list');
		echo json_encode($response);
		return;

	}



}

function import_transactions_mals_e($_data) {
	$transactions_raw_data=$_data['values']['data'];
	$lines = preg_split( '/\n/', $transactions_raw_data );

	$products_data = array ();


	foreach ( $lines as $line ) {

		$line = _trim ( $line );


		if (preg_match('/^.+ \: \d+ \: (\d|\.)+/',$line)) {
			$line_components=preg_split('/\:/',$line);
			if (count($line_components)==3) {
				if (preg_match('/^[a-z0-9\-\&\/]+\s/i',$line_components[0],$match)) {

					$product_code=_trim($match[0]);
					$quantity=(float)  $line_components[1];
					if (array_key_exists($product_code,$products_data))
						$products_data[$product_code]=$quantity+$products_data[$product_code];
					else
						$products_data[$product_code]=$quantity;
				}
			}
		}

	}





	$order_key=$_data['order_key'];
	$order=new Order($order_key);


	foreach ($products_data as $product_code=>$quantity) {



		if (is_numeric($quantity) and $quantity>=0) {

			$product=new Product('code_store',$product_code,$order->data['Order Store Key']);
			$product->data['Product Code'];

			if ($product->id and ($product->data['Product Record Type']=='Normal'  ) ) {

				$data=array(
					'date'=>date('Y-m-d H:i:s'),
					'Product Key'=>$product->data['Product Current Key'],
					'Metadata'=>'',
					'qty'=>$quantity,
					'Current Dispatching State'=>'In Process',
					'Current Payment State'=>'Waiting Payment'
				);


				$order->skip_update_after_individual_transaction=true;
				$order->add_order_transaction($data);

			}
		}
	}

	$order->update_discounts();
	$order->update_item_totals_from_order_transactions();

	$order->update_shipping();
	$order->update_charges();
	$order->update_item_totals_from_order_transactions();

	$order->update_no_normal_totals();
	$order->update_totals_from_order_transactions();
	$order->update_number_items();
	$order->update_number_products();


	$updated_data=array(
		'order_items_gross'=>$order->get('Items Gross Amount'),
		'order_items_discount'=>$order->get('Items Discount Amount'),
		'order_items_net'=>$order->get('Items Net Amount'),
		'order_net'=>$order->get('Total Net Amount'),
		'order_tax'=>$order->get('Total Tax Amount'),
		'order_charges'=>$order->get('Charges Net Amount'),
		'order_credits'=>$order->get('Net Credited Amount'),
		'order_shipping'=>$order->get('Shipping Net Amount'),
		'order_total'=>$order->get('Total Amount'),
		'ordered_products_number'=>$order->get('Number Products'),
	);

	$response= array(
		'state'=>200,
		'data'=>$updated_data,
	);

	echo json_encode($response);



}


function update_order_special_intructions($data){
$order=new Order($data['order_key']);

$order->update_field_switcher('Order Customer Message',strip_tags($data['value']),'no_history');



	$response= array(
		'state'=>200,
		'value'=>$order->data['Order Customer Message']

	);
	echo json_encode($response);
}

function update_order() {
	$order_key=$_REQUEST['order_key'];

	if ($order_key==0) {
		$response= array(
			'state'=>200
		);

		echo json_encode($response);
		exit;
	}
	$order=new Order($order_key);
	$updated_data=array(

		'order_total'=>$order->get('Total Amount'),
		'ordered_products_number'=>$order->get('Number Products'),
	);
	$_SESSION['basket']['total']=$updated_data['order_total'];
	$_SESSION['basket']['items']=$updated_data['ordered_products_number'];
	//print_r($updated_data);
	//print "total: ".$_SESSION['basket']['total'];
	//print " qty: ".$_SESSION['basket']['items'];

	$response= array(
		'state'=>200,
		'data'=>$updated_data

	);

	echo json_encode($response);
}

function pay_invoice($data) {

	$invoice=new Invoice($data['invoice_key']);



	switch ($data['method']) {
	case 'pay_by_creditcard':
		$method='Credit Card';
		break;
	case 'pay_by_bank_transfer':
		$method='Bank Transfer';
		break;
	case 'pay_by_paypal':
		$method='Paypal Card';
		break;
	case 'pay_by_cash':
		$method='Cash';
		break;
	case 'pay_by_cheque':
		$method='Check';
		break;
	default:
		$method='Other';
	}

	$payment_data=array('amount'=>$data['pay_amount'],'Payment Method'=>$method);
	$invoice->pay('',$payment_data);

	if (!$invoice->error) {
		$response= array('state'=>200,'msg'=>'paid','invoice_key'=>$invoice->id);
		echo json_encode($response);

	}else {
		$response= array('state'=>400,'msg'=>$invoice->msg);
		echo json_encode($response);
	}


}


function cc_payment($data) {
	$data=$data['json_values'];
	require_once 'paypal/DoDirectPayment.php';


	//print $data['firstName'];exit;
	//print_r($data); exit;
	// Set request-specific fields.
	$paymentType = urlencode('Authorization');    // or 'Sale'
	$firstName = urlencode($data['firstName']);
	$lastName = urlencode($data['lastName']);
	$creditCardType = urlencode($data['CCType']);
	$creditCardNumber = urlencode($data['CCNo']);
	$expDateMonth = $data['CCExpiresMonth'];
	// Month must be padded with leading zero
	$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));

	$expDateYear = urlencode($data['CCExpiresYear']);
	$cvv2Number = urlencode($data['CVV2']);
	$address1 = urlencode($data['address1']);
	$address2 = urlencode($data['address2']);
	$city = urlencode($data['city']);
	$state = urlencode($data['state']);
	$zip = urlencode($data['zip']);
	$country = urlencode($data['country']);    // US or other valid country code
	$amount = urlencode('10');
	$currencyID = urlencode('GBP');       // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

	// Add request-specific fields to the request string.
	$nvpStr = "&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
		"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
		"&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";

	// Execute the API operation; see the PPHttpPost function above.
	$httpParsedResponseAr = PPHttpPost('DoDirectPayment', $nvpStr);


	if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
		//  exit('Direct Payment Completed Successfully: '.print_r($httpParsedResponseAr, true));
		$httpParsedResponseAr['state']=200;
	} else {
		//exit('DoDirectPayment failed: ' . print_r($httpParsedResponseAr, true));
		$httpParsedResponseAr['state']=400;
	}

	$response=$httpParsedResponseAr;

	echo json_encode($response);
}


function update_percentage_discount($data) {

	$order=new Order($data['order_key']);
	if (!$order->id) {
		$response= array('state'=>400,'msg'=>'order not found');
		echo json_encode($response);
		return;
	}


	$transaction_data=$order->update_transaction_discount_percentage($data['order_transaction_key'],$data['percentage']);
	if ($order->error) {

		$response= array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);
		return;
	}




	$updated_data=array(
		'order_items_gross'=>$order->get('Items Gross Amount'),
		'order_items_discount'=>$order->get('Items Discount Amount'),
		'order_items_net'=>$order->get('Items Net Amount'),
		'order_net'=>$order->get('Total Net Amount'),
		'order_tax'=>$order->get('Total Tax Amount'),
		'order_charges'=>$order->get('Charges Net Amount'),
		'order_credits'=>$order->get('Net Credited Amount'),
		'order_shipping'=>$order->get('Shipping Net Amount'),
		'order_total'=>$order->get('Total Amount'),
		'ordered_products_number'=>$order->get('Number Products'),
	);




	$discounts= (float) $order->data['Order Items Discount Amount'];
	$response= array(
		'state'=>200,
		'quantity'=>$transaction_data['qty'],
		'description'=>$transaction_data['description'],
		'discount_percentage'=>$transaction_data['discount_percentage'],

		// 'key'=>$_REQUEST['id'],
		'data'=>$updated_data,
		'to_charge'=>$transaction_data['to_charge'],
		//'discount_data'=>$adata,
		'discounts'=>($discounts!=0?true:false),
		//'charges'=>($order->data['Order Charges Net Amount']!=0?true:false)
		'charges'=>$order->data['Order Charges Net Amount']
	);
	echo json_encode($response);

}

function get_locations($data) {
	//$part_sku=$_REQUEST['part_sku'];
	//print $part_sku;

	//$sql=sprintf("select * from `Part Location Dimension` where `Part SKU`=%d", $part_sku);
	//print $sql;

	$part= new part('sku',$data['part_sku']);
	$user=$data['user'];
	$modify_stock=$user->can_edit('product stock');

	$result='<table border="0" id="part_locations" class="show_info_product" style="width:260px;margin-top:10px">';
	foreach ($part->get_locations(true) as $location) {
		//print_r($location);
		$result.=sprintf('<tr id="part_location_tr_%s_%s">', $location['PartSKU'], $location['LocationKey']);
		$result.=sprintf('<td><a href="location.php?id=%s">%s </a>
						<img style="cursor:pointer;position:relative;bottom:2px" sku_formated="%s" location="%s" id="part_location_can_pick_%s_%s"  can_pick="%s" src="%s"  alt="can_pick" onclick="save_can_pick(%s,%s)" /> </td>

						<td id="picking_limit_quantities_'.$location['PartSKU'].'_'.$location['LocationKey'].'" min_value="'.(isset($location['MinimumQuantity'])?$location['MinimumQuantity']:'').'" max_value="'.(isset($location['MaximumQuantity'])?$location['MaximumQuantity']:'').'" location_key="'.$location['LocationKey'].'" part_sku="'.$location['PartSKU'].'" style="cursor:pointer; color:#808080;'.($location['CanPick']=='No'?'display:none':'').'" onclick="show_picking_limit_quantities(this)"> {<span id="picking_limit_min_'.$location['PartSKU'].'_'.$location['LocationKey'].'">'.(isset($location['MinimumQuantity'])?$location['MinimumQuantity']:'?').'</span>,<span id="picking_limit_max_'.$location['PartSKU'].'_'.$location['LocationKey'].'">'.(isset($location['MaximumQuantity'])?$location['MaximumQuantity']:'?').'</span>} </td>
						<td id="store_limit_quantities_'.$location['PartSKU'].'_'.$location['LocationKey'].'" move_qty="'.(isset($location['MovingQuantity'])?$location['MovingQuantity']:'').'" location_key="'.$location['LocationKey'].'" part_sku="'.$location['PartSKU'].'"  style="cursor:pointer; color:#808080;'.($location['CanPick']!='No'?'display:none':'').'" onclick="show_move_quantities(this)"> [<span id="store_limit_move_qty_'.$location['PartSKU'].'_'.$location['LocationKey'].'">'.(isset($location['MovingQuantity'])?$location['MovingQuantity']:'?').'</span>] </td>



					<td class="quantity" id="part_location_quantity_%s_%s" quantity="%s">%s</td>
						<td style="%s" class="button"><img style="cursor:pointer" id="part_location_audit_%s_%s" src="art/icons/note_edit.png" title="audit" alt="audit" onclick="audit(%s,%s)" /></td>
						<td style="%s" class="button"> <img style="cursor:pointer" sku_formated="%s" location="%s" id="part_location_add_stock_%s_%s" src="art/icons/lorry.png" title="add stock" alt="add stock" onclick="add_stock_part_location(%s,%s)" /></td>
						<td style="%s" class="button"> <img style="%s cursor:pointer" sku_formated="%s" location="%s" id="part_location_delete_%s_%s" src="art/icons/cross_bw.png" title="delete" alt="delete" onclick="delete_part_location(%s,%s)" /><img style="%s cursor:pointer" id="part_location_lost_items_%s_%s" src="art/icons/package_delete.png" title="lost" alt="lost" onclick="lost(%s,%s)" /></td>
						<td style="%s" class="button"><img style="cursor:pointer" sku_formated="%s" location="%s" id="part_location_move_items_%s_%s" src="art/icons/package_go.png" title="move" alt="move" onclick="move(%s,%s)" /></td>
						'
			,$location['LocationKey']
			,$location['LocationCode']
			,$part->get_sku()
			,$location['LocationCode']
			,$location['PartSKU']
			,$location['LocationKey']
			,($location['CanPick']=='Yes')?_('No'):_('Yes')
			,($location['CanPick']=='Yes')?'art/icons/basket.png':'art/icons/box.png'
			,$location['PartSKU']
			,$location['LocationKey']
			,$location['PartSKU']
			,$location['LocationKey']
			,$location['QuantityOnHand']
			,$location['FormatedQuantityOnHand']
			,(!$modify_stock)?'display:none':'',$location['PartSKU'],$location['LocationKey'],$location['PartSKU'],$location['LocationKey']
			,(!$modify_stock)?'display:none':'',$part->get_sku(),$location['LocationCode'],$location['PartSKU'],$location['LocationKey'],$location['PartSKU'],$location['LocationKey']
			,(!$modify_stock)?'display:none':'',($location['QuantityOnHand']!=0)?'display:none;':'',$part->get_sku(),$location['LocationCode'],$location['PartSKU'], $location['LocationKey'], $location['PartSKU'],$location['LocationKey'],($location['QuantityOnHand']==0)?'display:none;':'',$location['PartSKU'],$location['LocationKey'],$location['PartSKU'],$location['LocationKey']
			,(!$modify_stock)?'display:none':'',$part->get_sku(),$location['LocationCode'], $location['PartSKU'],$location['LocationKey'],$location['PartSKU'], $location['LocationKey']
		);


		$result.='</tr>';
	}
	$result.=sprintf('<tr style="%s"><td colspan="6"><div id="add_location_button" class="buttons small left"><button onclick="add_location(%s)">Add Location</button></div></td></tr></table>'
		,(!$modify_stock)?'display:none':'',$location['PartSKU']);

	$response= array(
		'state'=>200,
		'result'=>$result
	);
	echo json_encode($response);

}


function quick_invoice($data) {



	$order_key=$data['order_key'];

	$picker_key=($data['picker_key']==''?0:$data['picker_key']);
	$packer_key=($data['packer_key']==''?0:$data['packer_key']);

	$order=new Order($order_key);

	$dn_keys=$order->get_delivery_notes_ids();

	$number_dns=count($dn_keys);

	if ($number_dns==0) {
		$response= array('state'=>400,'msg'=>'no delivery notes associated with order');
		echo json_encode($response);
		return;

	}elseif ($number_dns>1) {
		$response= array('state'=>400,'msg'=>'multiple delivery notes associated with order');
		echo json_encode($response);
		return;
	}

	$dn=new DeliveryNote(array_pop($dn_keys));

	$dn->assign_picker($picker_key);
	$dn->assign_packer($packer_key,true);


	$dn->set_weight($data['weight']);
	$dn->set_parcels($data['parcels'],$data['parcel_type']);



	$where=sprintf(' where `Delivery Note Key`=%d',$dn->id);
	$sql="select `Picker Key`,`Inventory Transaction Key`, `Picked`,IFNULL(`Out of Stock`,0) as `Out of Stock`,IFNULL(`Not Found`,0) as `Not Found`,IFNULL(`No Picked Other`,0) as `No Picked Other` ,`Inventory Transaction Key`,`Part XHTML Currently Used In`,Part.`Part SKU`,`Part Unit Description`,`Required`,`Part XHTML Picking Location` from `Inventory Transaction Fact` ITF  left join  `Part Dimension` Part on  (Part.`Part SKU`=ITF.`Part SKU`)  $where  ";
	// print $sql;
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$todo=$row['Required']-$row['Out of Stock']-$row['Not Found']-$row['No Picked Other'];

		if ($todo) {
			$dn->set_as_picked($row['Inventory Transaction Key'],round($todo,8),false,$row['Picker Key']);
		}


	}
	$dn->update_picking_percentage();

	$sql="select `Packer Key`,`Inventory Transaction Key`, `Picked`,`Inventory Transaction Key`,`Part XHTML Currently Used In`,Part.`Part SKU`,`Part Unit Description` from `Inventory Transaction Fact` ITF  left join  `Part Dimension` Part on  (Part.`Part SKU`=ITF.`Part SKU`)  $where  ";
	// print $sql;
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$todo=$row['Picked'];

		if ($todo) {
			$dn->set_as_packed($row['Inventory Transaction Key'],round($todo,8),gmdate("Y-m-d H:i:s"),$row['Packer Key']);
		}
	}
	$dn->update_packing_percentage();

	$dn->approve_packed();



	$invoice=$dn->create_invoice();

	if ($invoice->id) {
		$response= array('state'=>200,'invoice_key'=>$invoice->id);
		echo json_encode($response);
		return;

	}else {
		$response= array('state'=>400,'msg'=>$invoice->msg);
		echo json_encode($response);
		return;

	}


}



function assign_picker_and_packer_to_order($data) {



	$order_key=$data['order_key'];

	$picker_key=($data['picker_key']==''?0:$data['picker_key']);
	$packer_key=($data['packer_key']==''?0:$data['packer_key']);

	$order=new Order($order_key);

	$dn_keys=$order->get_delivery_notes_ids();

	$number_dns=count($dn_keys);

	if ($number_dns==0) {
		$response= array('state'=>400,'msg'=>'no delivery notes associated with order');
		echo json_encode($response);
		return;

	}elseif ($number_dns>1) {
		$response= array('state'=>400,'msg'=>'multiple delivery notes associated with order');
		echo json_encode($response);
		return;
	}

	$dn=new DeliveryNote(array_pop($dn_keys));

	$dn->assign_picker($picker_key);
	$dn->assign_packer($packer_key,$force=true);
	$dn->set_weight($data['weight']);
	$dn->set_parcels($data['parcels'],$data['parcel_type']);


	if ($dn->id) {
		$response= array('state'=>200,'dn_key'=>$dn->id);
		echo json_encode($response);
		return;

	}else {
		$response= array('state'=>400,'msg'=>$dn->msg);
		echo json_encode($response);
		return;

	}


}


function set_as_dispatched_order($data) {

	$order_key=$data['order_key'];
	$order=new Order($order_key);

	$dn_keys=$order->get_delivery_notes_ids();

	$number_dns=count($dn_keys);

	if ($number_dns==0) {
		$response= array('state'=>400,'msg'=>'no delivery notes associated with order');
		echo json_encode($response);
		return;

	}elseif ($number_dns>1) {
		$response= array('state'=>400,'msg'=>'multiple delivery notes associated with order');
		echo json_encode($response);
		return;
	}
	$data['dn_key']=array_pop($dn_keys);
	set_as_dispatched_dn($data);

}


function set_as_dispatched_dn($data) {

	global $user;

	$dn=new DeliveryNote($data['dn_key']);
	if (!$dn->id) {
		$response= array('state'=>400,'msg'=>'dn not found');
		echo json_encode($response);
		return;
	}

	if ($dn->data['Delivery Note Dispatch Method']=='Dispatch')
		$dn->dispatch(array());
	elseif ($dn->data['Delivery Note Dispatch Method']=='Collection') {
		$dn->set_as_collected(array());

	}


	if (!$dn->error) {
		$response= array(
			'state'=>200,
			'dn_key'=>$dn->id,
			'operations'=>get_dn_operations($dn->data,$user),
			'dn_state'=>$dn->data['Delivery Note XHTML State']

		);
		echo json_encode($response);
		return;

	}else {
		$response= array('state'=>400,'msg'=>$dn->msg);
		echo json_encode($response);
		return;

	}
}



function approve_dispatching_order($data) {
	$order_key=$data['order_key'];
	$order=new Order($order_key);

	$dn_keys=$order->get_delivery_notes_ids();

	$number_dns=count($dn_keys);

	if ($number_dns==0) {
		$response= array('state'=>400,'msg'=>'no delivery notes associated with order');
		echo json_encode($response);
		return;

	}elseif ($number_dns>1) {
		$response= array('state'=>400,'msg'=>'multiple delivery notes associated with order');
		echo json_encode($response);
		return;
	}
	$data['dn_key']=array_pop($dn_keys);
	approve_dispatching_dn($data);
}

function approve_dispatching_invoice($data) {
	$invoice_key=$data['invoice_key'];


	$invoice=new Invoice($invoice_key);

	$dn_keys=$invoice->get_delivery_notes_ids();

	$number_dns=count($dn_keys);

	if ($number_dns==0) {
		$response= array('state'=>400,'msg'=>'no delivery notes associated with invoice');
		echo json_encode($response);
		return;

	}elseif ($number_dns>1) {
		$response= array('state'=>400,'msg'=>'multiple delivery notes associated with invoice');
		echo json_encode($response);
		return;
	}
	$data['dn_key']=array_pop($dn_keys);
	approve_dispatching_dn($data);
}

function approve_dispatching_dn($data) {

	global $user;
	$dn=new DeliveryNote($data['dn_key']);
	if (!$dn->id) {
		$response= array('state'=>400,'msg'=>'dn not found');
		echo json_encode($response);
		return;
	}

	$dn->approved_for_shipping();

	if (!$dn->error) {
		$response= array('state'=>200,'dn_key'=>$dn->id);


		if (array_key_exists('order_key',$data)) {
			$order=new Order($data['order_key']);
			$response['order_key']=$order->id;

			$response['order_operations']=get_orders_operations($order->data,$user);
			$response['order_dispatch_state']=get_order_formated_dispatch_state($order->data['Order Current Dispatch State'],$order->id);
			$response['order_payment_state']=get_order_formated_payment_state($order->data);

		}

		echo json_encode($response);
		return;

	}else {
		$response= array('state'=>400,'msg'=>$dn->msg);
		echo json_encode($response);
		return;

	}
}

function mark_all_for_refund_order($data) {
	$order_key=$data['order_key'];
	$order=new Order($order_key);

	$order->mark_all_transactions_for_refund();
	if (!$order->error) {
		$response= array('state'=>200,'order_key'=>$order->id);
		echo json_encode($response);
		return;

	}else {
		$response= array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);
		return;

	}
}

function add_credit_to_order($data) {
	$order_key=$data['order_key'];
	$order=new Order($order_key);

	$credit_transaction_data=array();
	$credit_transaction_data['Transaction Description']=$data['description'];
	$credit_transaction_data['Transaction Net Amount']=$data['amount'];
	$tax_category=new TaxCategory('code',$data['tax_code']);


	$credit_transaction_data['Transaction Tax Amount']=$tax_category->data['Tax Category Rate']*$credit_transaction_data['Transaction Net Amount'];
	$credit_transaction_data['Tax Category Code']=$tax_category->data['Tax Category Code'];

	$credit_transaction_data['Affected Order Key']='';
	$order->add_credit_no_product_transaction($credit_transaction_data);
	if (!$order->error) {
		$response= array('state'=>200,'order_key'=>$order->id);
		echo json_encode($response);
		return;

	}else {
		$response= array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);
		return;

	}
}

function edit_credit_to_order($data) {
	$order_key=$data['order_key'];
	$order=new Order($order_key);

	$credit_transaction_data=array();
	$credit_transaction_data['Transaction Description']=$data['description'];
	$credit_transaction_data['Transaction Net Amount']=$data['amount'];
	$tax_category=new TaxCategory('code',$data['tax_code']);
	$credit_transaction_data['Transaction Tax Amount']=$tax_category->data['Tax Category Rate']*$credit_transaction_data['Transaction Net Amount'];
	$credit_transaction_data['Tax Category Code']=$tax_category->data['Tax Category Code'];

	$credit_transaction_data['Affected Order Key']='';
	$credit_transaction_data['Order No Product Transaction Fact Key']=$data['transaction_key'];
	$order->update_credit_no_product_transaction($credit_transaction_data);
	if (!$order->error) {
		$response= array('state'=>200,'order_key'=>$order->id);
		echo json_encode($response);
		return;

	}else {
		$response= array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);
		return;

	}
}


function remove_credit_from_order($data) {
	$order_key=$data['order_key'];
	$order=new Order($order_key);


	$order->delete_credit_transaction($data['transaction_key']);
	if (!$order->error) {
		$response= array('state'=>200,'order_key'=>$order->id);
		echo json_encode($response);
		return;

	}else {
		$response= array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);
		return;

	}
}

function edit_tax_category_order($data) {
	$order_key=$data['order_key'];
	$order=new Order($order_key);


	$order->update_tax_category($data['tax_code']);
	if (!$order->error) {
		$response= array('state'=>200,'order_key'=>$order->id);
		echo json_encode($response);
		return;

	}else {
		$response= array('state'=>400,'msg'=>$order->msg);
		echo json_encode($response);
		return;

	}
}

function edit_delivery_note($data) {

	$dn_key=$data['dn_key'];
	$delivery_note=new DeliveryNote($dn_key);



	$translate_keys=array(
		'number_parcels'=>'Delivery Note Number Parcels',
		'parcel_type'=>'Delivery Note Parcel Type',
		'shipper_code'=>'Delivery Note Shipper Code',
		'consignment_number'=>'Delivery Note Shipper Consignment',

	);
	$okey=$data['key'];
	if (array_key_exists($data['key'],$translate_keys)) {
		$key=$translate_keys[$data['key']];
	}else {
		$key=$data['key'];
	}


	$delivery_note->update(array($key=>$data['newvalue']));


	if (!$delivery_note->error) {
		$response= array('state'=>200,'msg'=>$delivery_note->msg,'newvalue'=>$delivery_note->new_value,'key'=>$okey);

		echo json_encode($response);
		return;

	}else {
		$response= array('state'=>400,'dn_key'=>$delivery_note->id,'msg'=>$delivery_note->msg,'key'=>$okey);
		echo json_encode($response);
		return;

	}

}


?>
