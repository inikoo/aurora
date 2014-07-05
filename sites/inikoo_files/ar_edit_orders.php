<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 6 June 2014 17:09:43 GMT+1, Sheffield , UK

 Version 2.0
*/



require_once 'common.php';
require_once 'ar_edit_common.php';
require_once 'class.Order.php';
require_once 'class.User.php';
include_once 'class.Payment.php';
include_once 'class.Payment_Account.php';
include_once 'class.Payment_Service_Provider.php';


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>407,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case('check_tax_number'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key')
		));
	check_order_tax_number($data);
	break;
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
case('update_ship_to_key_from_address'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'address_key'=>array('type'=>'key')
		));
	update_ship_to_key_from_address($data);
	break;
case('update_billing_to_key_from_address'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'address_key'=>array('type'=>'key')
		));
	update_billing_to_key_from_address($data);
	break;
case('cancel'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'note'=>array('type'=>'string')
		));
	cancel_order($data);
	break;

case('edit_new_order'):
	edit_new_order();
	break;
case('is_order_exist'):
	is_order_exist();
	break;

case('edit_new_order_shipping_type'):
	edit_new_order_shipping_type();
	break;


case('edit_order'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')
		));
	edit_order($data);
	break;

default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);

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
		'order_total_paid'=>$order->get('Payments Amount'),
		'order_total_to_pay'=>$order->get('To Pay Amount'),

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
		'order_insurance_amount'=>$order->data['Order Insurance Net Amount'],
		'order_total_paid'=>$order->data['Order Payments Amount'],
		'order_total_to_pay'=>$order->data['Order To Pay Amount']

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
		'store_currency_total_balance'=>money($order->data['Order Balance Total Amount'],$order->data['Order Currency']),
		'order_total_paid'=>$order->get('Payments Amount'),
		'order_total_to_pay'=>$order->get('To Pay Amount'),
	);

	$response= array(
		'state'=>200,

		'data'=>$updated_data,
		'order_key'=>$order->id,
		'ship_to'=>$order->get('Order XHTML Ship Tos'),
		'tax_info'=>$order->get_formated_tax_info_with_operations(),

		'order_insurance_amount'=>$order->data['Order Insurance Net Amount'],
		'order_total_paid'=>$order->data['Order Payments Amount'],
		'order_total_to_pay'=>$order->data['Order To Pay Amount']

	);


	echo json_encode($response);




}
function cancel_order($data) {
	include_once 'class.Deal.php';

	global $editor,$user;
	$order_key=$data['order_key'];

	$order=new Order($order_key);
	$order->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);

	$order->editor=$editor;
	if (isset($_REQUEST['note']))
		$note=stripslashes(urldecode($data['note']));
	else
		$note='';

	$order->cancel_by_customer($note);
	if ($order->cancelled) {
		$response=array(
			'state'=>200,
			'order_key'=>$order->id,
			//'dispatch_state'=>get_order_formated_dispatch_state($order->data['Order Current Dispatch State'],$order->id),// function in: order_common_functions.php
			//'payment_state'=>get_order_formated_payment_state($order->data),
			//'operations'=>get_orders_operations($order->data,$user)

		);
		echo json_encode($response);
	} else {
		$response=array('state'=>400,'msg'=>$order->msg);
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
		
			$order->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);
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
				'store_currency_total_balance'=>money($order->data['Order Balance Total Amount'],$order->data['Order Currency']),
				'order_total_paid'=>$order->get('Payments Amount'),
				'order_total_to_pay'=>$order->get('To Pay Amount')
			);


			$response=array('state'=>200,
				'result'=>'updated',
				'new_value'=>$order->new_value,
				'order_shipping_method'=>$order->data['Order Shipping Method'],
				'data'=>$updated_data,
				'shipping'=>money($_SESSION['set_currency_exchange']*$order->new_value,$_SESSION['set_currency']),
				'shipping_amount'=>$order->data['Order Shipping Net Amount'],
				'ship_to'=>$order->get('Order XHTML Ship Tos'),
				'tax_info'=>$order->get_formated_tax_info_with_operations(),
				'order_total_paid'=>$order->data['Order Payments Amount'],
				'order_total_to_pay'=>$order->data['Order To Pay Amount']
			);

		

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
			$order->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);

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

	$quantity=ceil($quantity);


	$order=new Order($order_key);
	$order->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);

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
		$sql=sprintf("select `Product Units Per Case`,`Product Name`,(select `Deal Info` from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`,P.`Product ID`,`Product XHTML Short Description`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` from `Order Transaction Fact` OTF   left join `Product Dimension` P on (OTF.`Product ID`=P.`Product ID`) where OTF.`Order Key`=%d and OTF.`Product Key` in (%s)",
			$order->id,
			$product_keys);


		//print $sql;
		$res = mysql_query($sql);
		$adata=array();

		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


			if ($row['Deal Info']) {



				$deal_info='<br/><span style="font-style:italics;color:#555555;font-size:90%">'.$row['Deal Info'].($row['Order Transaction Total Discount Amount']?', <span style="font-weight:800">-'.money($_SESSION['set_currency_exchange']*$row['Order Transaction Total Discount Amount'],$_SESSION['set_currency']).'</span>':'').'</span>';
			}else {
				$deal_info='';
			}


			$adata[$row['Product ID']]=array(
				'pid'=>$row['Product ID'],
				'description'=>$row['Product Units Per Case'].'x '.$row['Product Name'].$deal_info,
				'to_charge'=>money($_SESSION['set_currency_exchange']*($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount']),$_SESSION['set_currency'])
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
		'store_currency_total_balance'=>money($order->data['Order Balance Total Amount'],$order->data['Order Currency']),
		'order_total_paid'=>$order->get('Payments Amount'),
		'order_total_to_pay'=>$order->get('To Pay Amount'),
	);

	$charges_deal_info=$order->get_no_product_deal_info('Charges');
	if ($charges_deal_info!='') {
		$charges_deal_info='<span style="color:red" title="'.$charges_deal_info.'">*</span> ';
	}

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
		'charges'=>($order->data['Order Charges Net Amount']!=0?true:false),
		'charges_deal_info'=>$charges_deal_info,
		'order_total_paid'=>$order->data['Order Payments Amount'],
		'order_total_to_pay'=>$order->data['Order To Pay Amount']
	);

	echo json_encode($response);
}
function update_ship_to_key_from_address($data) {

	$order=new Order($data['order_key']);
	$order->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);
	$address=new Address($data['address_key']);
	
	

	$contact_name=$order->data['Order Customer Contact Name'];
	$company_name=$order->data['Order Customer Name'];

	if ($company_name==$contact_name) {
		$company_name='';
	}
	$ship_to_data=array(
		'Ship To Contact Name'=>$contact_name,
		'Ship To Company Name'=>$company_name,
		'Ship To Telephone'=>$order->data['Order Telephone'],
		'Ship To Email'=>$order->data['Order Email']
	);



	$address=new Address($data['address_key']);
	$ship_to_key=$address->get_ship_to($ship_to_data);

	$order->update_ship_to($ship_to_key);

	if ($order->error) {
		$response=array('state'=>400,'result'=>'no_change','msg'=>$order->msg);
		echo json_encode($response);
	}else {

		$order->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);

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
			'store_currency_total_balance'=>money($order->data['Order Balance Total Amount'],$order->data['Order Currency']),
			'order_total_paid'=>$order->get('Payments Amount'),
			'order_total_to_pay'=>$order->get('To Pay Amount'),
		);

		$response= array(
			'state'=>200,

			'data'=>$updated_data,
			'order_key'=>$order->id,
			'ship_to'=>$order->get('Order XHTML Ship Tos'),
			'tax_info'=>$order->get_formated_tax_info_with_operations(),
			'order_total_paid'=>$order->data['Order Payments Amount'],
			'order_total_to_pay'=>$order->data['Order To Pay Amount']

		);


		echo json_encode($response);


	}


}
function update_billing_to_key_from_address($data) {

	$order=new Order($data['order_key']);
	$order->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);
	
	
	
	$address=new Address($data['address_key']);
	
	
	$contact_name=$order->data['Order Customer Contact Name'];
	if ($order->data['Order Customer Fiscal Name']=='') {
		$company_name=$order->data['Order Customer Name'];

	}else {
		$company_name=$order->data['Order Customer Fiscal Name'];
	}


	if ($company_name==$contact_name) {
		$contact_name='';
	}

	$billing_to_data=array(
		'Billing To Contact Name'=>$contact_name,
		'Billing To Company Name'=>$company_name,
		'Billing To Telephone'=>$order->data['Order Telephone'],
		'Billing To Email'=>$order->data['Order Email']
	);

	
	
	$billing_to_key=$address->get_billing_to($billing_to_data);

	$order->update_billing_to($billing_to_key);

	if ($order->error) {
		$response=array('state'=>400,'result'=>'no_change','msg'=>$order->msg);
		echo json_encode($response);
	}else {
		$order->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);


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
			'store_currency_total_balance'=>money($order->data['Order Balance Total Amount'],$order->data['Order Currency']),
			'order_total_paid'=>$order->get('Payments Amount'),
			'order_total_to_pay'=>$order->get('To Pay Amount'),
		);

		$response= array(
			'state'=>200,

			'data'=>$updated_data,
			'order_key'=>$order->id,
			'billing_to'=>$order->get('Order XHTML Billing Tos'),
			'tax_info'=>$order->get_formated_tax_info_with_operations(),
			'order_total_paid'=>$order->data['Order Payments Amount'],
			'order_total_to_pay'=>$order->data['Order To Pay Amount']


		);


		echo json_encode($response);
	}


}
function update_order_special_intructions($data) {
	$order=new Order($data['order_key']);

	$order->update_field_switcher('Order Customer Message',strip_tags($data['value']),'no_history');



	$response= array(
		'state'=>200,
		'value'=>$order->data['Order Customer Message']

	);
	echo json_encode($response);
}


function edit_order($data) {
	$order_key=$data['order_key'];

	if ($order_key==0) {
		$response= array(
			'state'=>200
		);

		echo json_encode($response);
		exit;
	}
	$order=new Order($order_key);

	$translate=array('tax_number'=>'Order Tax Number');

	$responses=array();

	foreach ($data['values'] as $key=>$value) {
		if (array_key_exists($value['okey'],$translate))
			$_key=$translate[$value['okey']];
		else
			$_key=$value['okey'];


		$data_to_update=array($_key=>$value['value']);
		//print_r($data_to_update);
		$order->update($data_to_update);
		//print_r($order);
		if (!$order->error) {
			if ($order->updated) {
				$responses[]= array(
					'state'=>200,
					'key'=>$value['okey'],
					'newvalue'=>$order->new_value,
					'action'=>'updated'
				);
			}else {
				$responses[]= array(
					'state'=>200,
					'key'=>$value['okey'],

					'newvalue'=>$order->data[$_key],
					'action'=>'no_change'

				);

			}
		}else {
			$responses[]= array(
				'state'=>400,
				'key'=>$value['okey'],
				'msg'=>$order->msg

			);
		}


	}



	echo json_encode($responses);
}



function check_order_tax_number($data) {

	$order= new Order($data['order_key']);

	include_once 'common_tax_number_functions.php';
	$tax_number_data=check_tax_number($order->data['Order Tax Number'],$order->data['Order Billing To Country 2 Alpha Code']);

	$order->update(
		array(
			'Order Tax Number'=>$order->data['Order Tax Number'],
			'Order Tax Number Valid'=>$tax_number_data['Tax Number Valid'],
			'Order Tax Number Validation Date'=>$tax_number_data['Tax Number Validation Date'],
			'Order Tax Number Associated Name'=>$tax_number_data['Tax Number Associated Name'],
			'Order Tax Number Associated Address'=>$tax_number_data['Tax Number Associated Address'],
		)
	);


	$order->update_tax();

	$response= array(
		'state'=>200,
		'valid'=>$tax_number_data['Tax Number Valid'],
		'name'=>$tax_number_data['Tax Number Associated Name'],
		'addresss'=>$tax_number_data['Tax Number Associated Address'],
		'msg'=>$tax_number_data['msg']


	);


	echo json_encode($response);


}




?>
