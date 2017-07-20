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

case 'upload_transactions_input':
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
		));
	upload_transactions_input($data);
	break;
case('import_transactions_from_csv'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')

		));
	import_transactions_from_csv($data);
	break;

case('update_meta_bonus'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'customer_key'=>array('type'=>'key'),
			'pid'=>array('type'=>'key'),
			'product_key'=>array('type'=>'key'),
			'code'=>array('type'=>'string'),
			'family_key'=>array('type'=>'key'),
			'deal_component_key'=>array('type'=>'key'),
			'value'=>array('type'=>'numeric')
		));
	update_meta_bonus($data);

	break;
case('check_tax_number'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key')
		));
	check_order_tax_number($data);
	break;

case('update_recargo_de_equivalencia'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key'),
			'value'=>array('type'=>'string'),
		));
	update_recargo_de_equivalencia($data);
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
case('remove_all_products'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key')
		));
	remove_all_products($data);
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

function update_recargo_de_equivalencia($data) {


	$order= new Order($data['order_key']);
	$customer=new Customer($order->data['Order Customer Key']);
	$customer->update(array('Recargo Equivalencia'=>$data['value']));

	$order->update_tax();

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
		'order_insurance_amount'=>$order->data['Order Insurance Net Amount'],
		'order_total_paid'=>$order->data['Order Payments Amount'],
		'order_total_to_pay'=>$order->data['Order To Pay Amount']

	);


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

	global $smarty;

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

		$dispatching_state='In Process by Customer';
	}

	$payment_state='Waiting Payment';

	$product=new Product('pid',$product_pid);



	$data=array(
		'date'=>gmdate('Y-m-d H:i:s'),
		'Product Key'=>$product->data['Product Current Key'],
		'Product ID'=>$product->pid,

		'Metadata'=>'',
		'qty'=>$quantity,
		'Current Dispatching State'=>$dispatching_state,
		'Current Payment State'=>$payment_state
	);

	$disconted_products=$order->get_discounted_products();
	$order->skip_update_after_individual_transaction=false;

	$transaction_data=$order->add_order_transaction_to_delete($data);

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
		$sql=sprintf("select `Product History Units Per Case`,`Product History Name`,`Product History Price`,`Order Quantity`,`Order Bonus Quantity`, `Product History XHTML Short Description`,(select `Deal Info` from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`,P.`Product ID`,`Product History XHTML Short Description`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` from
		`Order Transaction Fact` OTF   left join
		`Product History Dimension` P on (OTF.`Product Key`=P.`Product Key`)
		 where OTF.`Order Key`=%d and OTF.`Product Key` in (%s)",
			$order->id,
			$product_keys);



		$res = mysql_query($sql);
		$adata=array();

		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


			if ($row['Deal Info']) {
				$deal_info='<br/><span style="font-style:italics;color:#555555;font-size:90%">'.$row['Deal Info'].($row['Order Transaction Total Discount Amount']>0?', <span style="font-weight:800">'._('You save').':  '.money($_SESSION['set_currency_exchange']*$row['Order Transaction Total Discount Amount'],$_SESSION['set_currency']).'</span>':'').'</span>';
			}else {
				$deal_info='';
			}

			$qty=number($row['Order Quantity']);
			if ($row['Order Bonus Quantity']!=0) {
				if ($row['Order Quantity']!=0) {
					$qty.='<br/> +'.number($row['Order Bonus Quantity']).' '._('free');
				}else {
					$qty=number($row['Order Bonus Quantity']).' '._('free');
				}
			}


			$adata[$row['Product ID']]=array(
				'pid'=>$row['Product ID'],
				'quantity'=>$qty,
				'ordered_quantity'=>$row['Order Quantity'],
				'description'=>$row['Product History Units Per Case'].'x '.$row['Product History Name'].$deal_info,
				'price_per_outer'=>money($_SESSION['set_currency_exchange']*$row['Product History Price'],$_SESSION['set_currency']),


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




	$order_has_deal_with_bonus=$order->has_deal_with_bonus();

	if ($order_has_deal_with_bonus) {
		$smarty->assign('order',$order);
		$order_deal_bonus=$smarty->fetch('order_deal_bonus_splinter.tpl');
	}else {
		$order_deal_bonus='';

	}


	$response= array(
		'state'=>200,
		'quantity'=>$transaction_data['qty'],
		'ordered_quantity'=>$transaction_data['qty'],
		'description'=>$product->data['Product Units Per Case'].x .$product->data['Product Name'],
		'price_per_outer'=>money($_SESSION['set_currency_exchange']*$product->data['Product Price'],$_SESSION['set_currency']),

		'discount_percentage'=>$transaction_data['discount_percentage'],
		'key'=>$_REQUEST['id'],
		'data'=>$updated_data,
		'to_charge'=>$transaction_data['to_charge'],
		'discount_data'=>$adata,
		'discounts'=>($order->data['Order Items Discount Amount']!=0?true:false),
		'charges'=>($order->data['Order Charges Net Amount']!=0?true:false),
		'charges_deal_info'=>$charges_deal_info,
		'order_total_paid'=>$order->data['Order Payments Amount'],
		'order_total_to_pay'=>$order->data['Order To Pay Amount'],
		'order_has_deal_with_bonus'=>$order_has_deal_with_bonus,
		'order_deal_bonus'=>$order_deal_bonus,

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


function update_meta_bonus($data) {


	$sql=sprintf("select `Order Meta Transaction Deal Key`,`Bonus Order Transaction Fact Key`,`Bonus Product ID`  from `Order Meta Transaction Deal Dimension` where  `Deal Component Key`=%d and `Order Key`=%d  ",
		$data['deal_component_key'],
		$data['order_key']
	);
	// print $sql;

	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {

		$product_pid=$row['Bonus Product ID'];


		if ($data['value']) {
			if ($data['pid']!=$product_pid) {
				$sql=sprintf("delete from `Order Transaction Fact` where `Order Transaction Fact Key`=%d",$row['Bonus Order Transaction Fact Key']);
				mysql_query($sql);
				//print $sql;

				$order=new Order($data['order_key']);




				$_data=array(
					'date'=>gmdate('Y-m-d H:i:s'),
					'Product Key'=>$data['product_key'],
					'Product ID'=>$data['pid'],
					'Metadata'=>'',
					'qty'=>0,
					'bonus qty'=>$data['value'],
					'Current Dispatching State'=>'In Process by Customer',
					'Current Payment State'=>'Waiting Payment'
				);

				$order->skip_update_after_individual_transaction=true;

				$transaction_data=$order->add_order_transaction_to_delete($_data);

				$sql=sprintf("update `Order Meta Transaction Deal Dimension` set
				`Bonus Quantity`=%f,`Bonus Product Key`=%d,`Bonus Product ID`=%d ,`Bonus Product Family Key`=%d ,
				`Bonus Order Transaction Fact Key`=%d where `Order Meta Transaction Deal Key`=%d ",
					$data['value'],
					$data['product_key'],
					$data['pid'],
					$data['family_key'],

					$transaction_data['otf_key'],
					$row['Order Meta Transaction Deal Key']
				);
				mysql_query($sql);


				$sql=sprintf("insert into `Deal Component Customer Preference Bridge`  (`Deal Component Key`,`Customer Key`,`Preference Metadata`) values (%d,%d,%s)  ON DUPLICATE KEY UPDATE `Preference Metadata`=%s",
					$data['deal_component_key'],
					$data['customer_key'],
					prepare_mysql($data['code']),
					prepare_mysql($data['code'])
				);
				mysql_query($sql);
			}

		}
		else {

			$sql=sprintf("delete from `Order Transaction Fact` where `Order Transaction Fact Key`=%d",$row['Bonus Order Transaction Fact Key']);
			mysql_query($sql);

			$sql=sprintf("update `Order Meta Transaction Deal Dimension` set `Bonus Quantity`=0,`Bonus Product Key`=NULL,`Bonus Product ID`=NULL ,`Bonus Product Family Key`=NULL ,`Bonus Order Transaction Fact Key`=0 where `Order Meta Transaction Deal Key`=%d ",

				$row['Order Meta Transaction Deal Key']
			);
			mysql_query($sql);


			$sql=sprintf("insert into `Deal Component Customer Preference Bridge`  (`Deal Component Key`,`Customer Key`,`Preference Metadata`) values (%d,%d,'')  ON DUPLICATE KEY UPDATE `Preference Metadata`=''",
				$data['deal_component_key'],
				$data['customer_key']

			);
			mysql_query($sql);

		}


	}

	$response= array(
		'state'=>200,




	);


	echo json_encode($response);

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

function remove_all_products($data) {

	global $smarty,$site;

	$order=new Order($data['order_key']);
	$order->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);
	$disconted_products=$order->get_discounted_products();
	$order->skip_update_after_individual_transaction=false;



	$baskey_page_key=0;


	$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Site Key`=%d and `Page Store Section`='Basket'",$site->id);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$basket_page_key=$row['Page Key'];
	}

	$sql=sprintf("select `Product Key`,`Product ID` from `Order Transaction Fact` where `Order Key`=%d",$order->id);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {



		$_data=array(
			'date'=>gmdate('Y-m-d H:i:s'),
			'Product Key'=>$row['Product Key'],
			'Product ID'=>$row['Product ID'],

			'Metadata'=>'',
			'qty'=>0,
			'Current Dispatching State'=>'In Process by Customer',
			'Current Payment State'=>'Waiting Payment'
		);


		$transaction_data=$order->add_order_transaction_to_delete($_data);


		$basket_history=array(
			'otf_key'=>$transaction_data['otf_key'],
			'Page Key'=>$basket_page_key,
			'Product ID'=>$row['Product ID'],
			'Quantity Delta'=>$transaction_data['delta_qty'],
			'Quantity'=>$transaction_data['qty'],
			'Net Amount Delta'=>$transaction_data['delta_net_amount'],
			'Net Amount'=>$transaction_data['net_amount'],
			'Page Store Section Type'=>'System',

		);

		if ($basket_history['Net Amount Delta']!=0 or $basket_history['Quantity']!=0  or $basket_history['Quantity Delta']!=0  ) {
			$order->add_basket_history($basket_history);
		}



	}


	$new_disconted_products=$order->get_discounted_products();
	foreach ($new_disconted_products as $key=>$value) {
		$disconted_products[$key]=$value;
	}

	$adata=array();

	if (count($disconted_products)>0) {

		$product_keys=join(',',$disconted_products);
		$sql=sprintf("select `Order Quantity`,`Order Bonus Quantity`, `Product Units Per Case`,`Product Name`,(select `Deal Info` from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`,P.`Product ID`,`Product XHTML Short Description`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` from `Order Transaction Fact` OTF   left join `Product Dimension` P on (OTF.`Product ID`=P.`Product ID`) where OTF.`Order Key`=%d and OTF.`Product Key` in (%s)",
			$order->id,
			$product_keys);


		//print $sql;
		$res = mysql_query($sql);
		$adata=array();

		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


			if ($row['Deal Info']) {
				$deal_info='<br/><span style="font-style:italics;color:#555555;font-size:90%">'.$row['Deal Info'].($row['Order Transaction Total Discount Amount']>0?', <span style="font-weight:800">'._('You save').':  '.money($_SESSION['set_currency_exchange']*$row['Order Transaction Total Discount Amount'],$_SESSION['set_currency']).'</span>':'').'</span>';
			}else {
				$deal_info='';
			}

			$qty=number($row['Order Quantity']);
			if ($row['Order Bonus Quantity']!=0) {
				if ($row['Order Quantity']!=0) {
					$qty.='<br/> +'.number($row['Order Bonus Quantity']).' '._('free');
				}else {
					$qty=number($row['Order Bonus Quantity']).' '._('free');
				}
			}


			$adata[$row['Product ID']]=array(
				'pid'=>$row['Product ID'],
				'quantity'=>$qty,
				'ordered_quantity'=>$row['Order Quantity'],
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




	$order_has_deal_with_bonus=$order->has_deal_with_bonus();

	if ($order_has_deal_with_bonus) {
		$smarty->assign('order',$order);
		$order_deal_bonus=$smarty->fetch('order_deal_bonus_splinter.tpl');
	}else {
		$order_deal_bonus='';

	}


	$response= array(
		'state'=>200,
		'data'=>$updated_data,
		'discount_data'=>$adata,
		'discounts'=>($order->data['Order Items Discount Amount']!=0?true:false),
		'charges'=>($order->data['Order Charges Net Amount']!=0?true:false),
		'charges_deal_info'=>$charges_deal_info,
		'order_total_paid'=>$order->data['Order Payments Amount'],
		'order_total_to_pay'=>$order->data['Order To Pay Amount'],
		'order_has_deal_with_bonus'=>$order_has_deal_with_bonus,
		'order_deal_bonus'=>$order_deal_bonus,

	);

	echo json_encode($response);
}

function upload_transactions_input($data) {
	include_once 'external_libs/PHPExcel/Classes/PHPExcel/IOFactory.php';


	if (isset($_FILES['testFile']['tmp_name']) and $_FILES['testFile']['tmp_name']) {
		$file_name=$_FILES['testFile']['tmp_name'];


		//$objPHPExcel = PHPExcel_IOFactory::load('server_files/tmp/tmp.csv');

		//$file_name='server_files/tmp/tmp.csv';

		$valid = false;
		$types = array('Excel2007', 'Excel5','CSV');
		foreach ($types as $type) {
			$reader = PHPExcel_IOFactory::createReader($type);
			$reader->setReadDataOnly(true);
			if ($reader->canRead($file_name)) {
				$valid = true;
				break;
			}
		}

		if ($valid) {

			class MyReadFilter implements PHPExcel_Reader_IReadFilter
			{
				public function readCell($column, $row, $worksheetName = '') {
					// Read title row and rows 20 - 30
					if ($column>2) {
						return false;
					}else {
						return true;

					}

				}
			}

			$reader->setReadFilter( new MyReadFilter() );


			$objPHPExcel = PHPExcel_IOFactory::load($file_name);
			$transactions_data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			// print_r($transactions_data);
			import_transaction($data['order_key'],$transactions_data);

		} else {
			$msg=_("Sorry, I can't read data");
			$response= array('state'=>400,'msg'=>$msg);
			echo json_encode($response);
			return;


		}



		//$inputFileType = 'EXCEL5';
		//$inputFileName = $file_name;
		//$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		//$objPHPExcel = $objReader->load($inputFileName);
		//$transactions_data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);





		// import_transaction($data['order_key'],$transactions_data);

	} else {

		$poidsMax = ini_get('upload_max_filesize');
		$msg=_("Your file is too big, maximum allowed size here is").": $poidsMax";
		$response= array('state'=>400,'msg'=>$msg);
		echo json_encode($response);
		return;
	}
}

function import_transactions_from_csv($data) {



	include_once 'external_libs/PHPExcel/Classes/PHPExcel/IOFactory.php';


	$tmpfname = tempnam('server_files/tmp/', "import_transactions");

	$handle = fopen($tmpfname, "w");
	fwrite($handle, $data['values']['data']);
	fclose($handle);




	$inputFileType = 'CSV';
	$inputFileName = $tmpfname;
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objPHPExcel = $objReader->load($inputFileName);
	$transactions_data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	unlink($tmpfname);
	import_transaction($data['order_key'],$transactions_data);


}

function import_transaction($order_key,$transactions_data) {
	global $smarty,$editor,$user,$account_code;

	$order=new Order($order_key);

	$transactions_updated=0;
	$transactions_added=0;

	foreach ($transactions_data as $transaction_data) {
		//print_r( $transaction_data);

		$transaction_found=false;

		if (count( $transaction_data)<2) {
			continue;
		}

		$code=array_shift($transaction_data);
		$qty=array_shift($transaction_data);

		if ($code=='' or !is_numeric($qty) or $qty<=0 ) {
			continue;
		}

		$qty=ceil($qty);
		//print "$code $qty\n";



		$order->editor=$editor;

		if (in_array($order->data['Order Current Dispatch State'],array('Ready to Pick','Picking & Packing','Packed','Packed Done','Packing')) ) {
			$dispatching_state='Ready to Pick';
		}else {

			$dispatching_state='In Process';
		}

		$payment_state='Waiting Payment';


		$sql=sprintf('select `Product Current Key`,`Product ID` from `Product Dimension` where `Product Store Key`=%d and `Product Code`=%s  and `Product Record Type`="Normal"     and `Product Web State`="For Sale"    ',
			$order->data['Order Store Key'],
			prepare_mysql($code)
		);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {


			$sql=sprintf('select `Order Quantity`  from `Order Transaction Fact` where `Order Key`=%d and `Product ID`=%d ',
				$order->id,
				$row['Product ID']
			);
			$res2=mysql_query($sql);
			if ($row2=mysql_fetch_assoc($res2)) {
				$transaction_found=true;;
				if ($row2['Order Quantity']>=$qty) {
					continue;
				}
			}



			$_data=array(
				'date'=>gmdate('Y-m-d H:i:s'),
				'Product Key'=>$row['Product Current Key'],
				'Product ID'=>$row['Product ID'],
				'Metadata'=>'',
				'qty'=>$qty,
				'Current Dispatching State'=>$dispatching_state,
				'Current Payment State'=>$payment_state
			);

			$order->skip_update_after_individual_transaction=false;

			$transaction_data=$order->add_order_transaction_to_delete($_data);

			if ($transaction_data['updated']) {
				if ($transaction_found) {
					$transactions_updated++;
				}else {
					$transactions_added++;

				}
			}


		}










	}

	$total_transactions_updated=$transactions_updated+$transactions_added;
	if ($total_transactions_updated==0) {

		$import_msg=_('No transactions were affected');
	}else {

		if ($transactions_updated) {

			$import_msg=sprintf(ngettext('%s transaction updated', '%s transactions updated', $transactions_updated),'<b>'.$transactions_updated.'</b>');
		}
		if ($transactions_added) {
			$import_msg=sprintf(ngettext('%s transaction created', '%s transactions created', $transactions_added),'<b>'.$transactions_added.'</b>');
		}

	}



	$updated_data=array(
		'import_msg'=>$import_msg,
		'total_transactions_updated'=>(float) $total_transactions_updated,
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




	$order_has_deal_with_bonus=$order->has_deal_with_bonus();

	if ($order_has_deal_with_bonus) {
		$smarty->assign('order',$order);
		$order_deal_bonus=$smarty->fetch('order_deal_bonus_splinter.tpl');
	}else {
		$order_deal_bonus='';

	}


	$response= array(
		'state'=>200,

		'data'=>$updated_data,
		'discounts'=>($order->data['Order Items Discount Amount']!=0?true:false),
		'charges'=>($order->data['Order Charges Net Amount']!=0?true:false),
		'charges_deal_info'=>$charges_deal_info,
		'order_total_paid'=>$order->data['Order Payments Amount'],
		'order_total_to_pay'=>$order->data['Order To Pay Amount'],
		'order_has_deal_with_bonus'=>$order_has_deal_with_bonus,
		'order_deal_bonus'=>$order_deal_bonus,

	);

	echo json_encode($response);

	include 'splinters/new_fork.php';
	list($fork_key,$msg)=new_fork('housekeeping',array('type'=>'update_otf','order_key'=>$order->id),$account_code);


}



?>
