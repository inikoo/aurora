<?php
require_once 'common.php';
require_once 'ar_edit_common.php';
require_once 'class.Order.php';
require_once 'class.Staff.php';
include_once 'class.Payment.php';
include_once 'class.Payment_Account.php';
include_once 'class.Payment_Service_Provider.php';

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


case ('refund_payment'):
	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),

			'parent_key'=>array('type'=>'key'),
			'refund_reference'=>array('type'=>'string'),
			'refund_payment_method'=>array('type'=>'string'),
			'refund_amount'=>array('type'=>'numeric'),
			'payment_key'=>array('type'=>'key')




		));


	refund_payment($data);

	break;
case('add_payment'):
	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),

			'parent_key'=>array('type'=>'key'),
			'payment_reference'=>array('type'=>'string'),
			'payment_method'=>array('type'=>'string'),
			'payment_amount'=>array('type'=>'numeric'),
			'payment_account_key'=>array('type'=>'key')




		));

	if ($data['parent']=='order') {
		add_payment_to_order($data);
	}elseif ($data['parent']=='invoice') {

		add_payment_to_invoice($data);
	}
	break;


case('set_payment_as_completed'):
	$data=prepare_values($_REQUEST,array(
			'payment_key'=>array('type'=>'key'),
			'payment_transaction_id'=>array('type'=>'string')

		));
	set_payment_as_completed($data);
	break;

case('cancel_payment'):
	$data=prepare_values($_REQUEST,array(
			'payment_key'=>array('type'=>'key'),
			'order_key'=>array('type'=>'key')
		));
	cancel_payment($data);
	break;

	break;

default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);

}




function cancel_payment($data) {

	$payment_key=$data['payment_key'];
	$payment=new Payment($payment_key);
	$order=new Order($data['order_key']);



	if (!$payment->id) {

		$pending_payments=count($order->get_payment_keys('Pending'));


		$response=array(
			'state'=>201,
			'msg'=>'error: payment dont exists',
			'type_error'=>'invalid_payment_key',
			'payment_key'=>$data['payment_key'],
			'pending_payments'=>$pending_payments,
			'status'=>'Deleted',
			'created_time_interval'=>0,
			'order_dispatch_status'=>$order->data['Order Current Dispatch State']


		);
		echo json_encode($response);
		return;
	}

	if ($payment->data['Payment Transaction Status']!='Pending') {
		$pending_payments=count($order->get_payment_keys('Pending'));
		$response=array(
			'state'=>201,
			'msg'=>'error: payment not pending. '.$payment->data['Payment Transaction Status'],
			'type_error'=>'invalid_payment_status',
			'payment_key'=>$payment->id,
			'pending_payments'=>$pending_payments,
			'status'=>$payment->data['Payment Transaction Status'],
			'created_time_interval'=>0,
			'order_dispatch_status'=>$order->data['Order Current Dispatch State']

		);
		echo json_encode($response);
		return;
	}

	$data_to_update=array(

		'Payment Completed Date'=>'',
		'Payment Last Updated Date'=>gmdate('Y-m-d H:i:s'),
		'Payment Cancelled Date'=>gmdate('Y-m-d H:i:s'),
		'Payment Transaction Status'=>'Cancelled',
		'Payment Transaction Status Info'=>_('Cancelled by customer'),


	);
	$payment->update($data_to_update);




	$pending_payments=count($order->get_payment_keys('Pending'));

	if ($pending_payments==0) {

		if (  count($order->get_payment_keys('Completed'))) {

			$order->set_as_in_process();
		}else {

			$order->checkout_cancel_payment();
		}
	}

	if (!$payment->id) {
		$response=array(
			'state'=>200,
			'payment_key'=>$data['payment_key'],
			'pending_payments'=>$pending_payments,
			'status'=>'Deleted',
			'created_time_interval'=>0,
			'msg'=>'error: payment dont exists',
			'type_error'=>'invalid_payment_key',
			'order_dispatch_status'=>$order->data['Order Current Dispatch State']

		);
	}else {

		$response=array(
			'state'=>200,
			'payment_key'=>$payment->id,
			'pending_payments'=>$pending_payments,
			'status'=>$payment->data['Payment Transaction Status'],
			'created_time_interval'=>$payment->get_formated_time_lapse('Created Date'),
			'order_dispatch_status'=>$order->data['Order Current Dispatch State']
		);
	}








	echo json_encode($response);
	return;




}



function set_payment_as_completed($data) {

	$payment_transaction_id=$data['payment_transaction_id'];
	$payment_key=$data['payment_key'];

	$payment=new Payment($payment_key);
	$payment_account=new Payment_Account($payment->data['Payment Account Key']);

	$order_key=$payment->data['Payment Order Key'];
	$order=new Order($order_key);
	$data_to_update=array(

		'Payment Completed Date'=>gmdate('Y-m-d H:i:s'),
		'Payment Last Updated Date'=>gmdate('Y-m-d H:i:s'),
		'Payment Transaction Status'=>'Completed',
		'Payment Transaction ID'=>$payment_transaction_id,

	);


	$payment->update($data_to_update);
	$order=new Order($payment->data['Payment Order Key']);

	$order->update(
		array(
			'Order Payment Account Key'=>$payment_account->id,
			'Order Payment Account Code'=>$payment_account->data['Payment Account Code'],
			'Order Payment Method'=>$payment_account->data['Payment Type'],
			'Order Payment Key'=>$payment->id,
			'Order Checkout Completed Payment Date'=>gmdate('Y-m-d H:i:s')
		));

	$order->checkout_submit_order();






	send_confirmation_email($order);

}




function add_payment_to_order($data) {

	$order=new Order($data['parent_key']);
	$payment_account=new Payment_Account($data['payment_account_key']);

	if (!$order->id) {
		$response=array('state'=>400,'msg'=>'error: order dont exists','type_error'=>'invalid_order_key');
		echo json_encode($response);
		return;
	}



	if (!$payment_account->id) {
		$response=array('state'=>400,'msg'=>'error: payment account dont exists','type_error'=>'invalid_payment_account_keyy');
		echo json_encode($response);
		return;
	}

	if (!$payment_account->in_store($order->data['Order Store Key'])) {
		$response=array('state'=>400,'msg'=>'error: payment account not in this site','type_error'=>'payment_account_not_in_store');
		echo json_encode($response);
		return;
	}


	$payment_service_provider=new Payment_Service_Provider($payment_account->data['Payment Service Provider Key']);


	$billing_to=new Billing_To($order->data['Order Billing To Keys']);


	$payment_data=array(
		'Payment Account Key'=>$payment_account->id,
		'Payment Account Code'=>$payment_account->data['Payment Account Code'],

		'Payment Service Provider Key'=>$payment_account->data['Payment Service Provider Key'],
		'Payment Order Key'=>$order->id,
		'Payment Store Key'=>$order->data['Order Store Key'],
		'Payment Customer Key'=>$order->data['Order Customer Key'],

		'Payment Balance'=>$data['payment_amount'],
		'Payment Amount'=>$data['payment_amount'],
		'Payment Refund'=>0,
		'Payment Currency Code'=>$order->data['Order Currency'],
		'Payment Completed Date'=>gmdate('Y-m-d H:i:s'),
		'Payment Created Date'=>gmdate('Y-m-d H:i:s'),
		'Payment Last Updated Date'=>gmdate('Y-m-d H:i:s'),
		'Payment Transaction Status'=>'Completed',
		'Payment Transaction ID'=>$data['payment_reference'],
		'Payment Method'=>$data['payment_method']

	);

	$payment=new Payment('create',$payment_data);

	$sql=sprintf("insert into `Order Payment Bridge` values (%d,%d,%d,%d,%.2f,'No') ON DUPLICATE KEY UPDATE `Amount`=%.2f ",
		$order->id,
		$payment->id,
		$payment_account->id,
		$payment_account->data['Payment Service Provider Key'],
		$payment->data['Payment Amount'],
		$payment->data['Payment Amount']
	);
	mysql_query($sql);


	$order->update_payment_state();


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
		'order_total_paid'=>$order->get('Payments Amount'),
		'order_total_to_pay'=>$order->get('To Pay Amount')

	);

	$payments_data=array();
	foreach ($order->get_payment_objects('',true,true) as $payment) {
		$payments_data[$payment->id]=array(
			'date'=>$payment->get('Created Date'),
			'amount'=>$payment->get('Amount'),
			'status'=>$payment->get('Payment Transaction Status')
		);
	}



	$response=array('state'=>200,
		'result'=>'updated',
		'order_shipping_method'=>$order->data['Order Shipping Method'],
		'data'=>$updated_data,
		'shipping'=>money($order->new_value),
		'shipping_amount'=>$order->data['Order Shipping Net Amount'],
		'ship_to'=>$order->get('Order XHTML Ship Tos'),
		'tax_info'=>$order->get_formated_tax_info_with_operations(),
		'payments_data'=>$payments_data,
		'order_total_paid'=>$order->data['Order Payments Amount'],
		'order_total_to_pay'=>$order->data['Order To Pay Amount']
	);

	echo json_encode($response);

}


function refund_payment($data) {

	$refund_amount=round($data['refund_amount'],2);

	$payment=new Payment($data['payment_key']);
	$payment->load_payment_account();
	$payment->load_payment_service_provider();
	if ($data['refund_payment_method']=='online') {

		if ($payment->payment_account->data['Payment Account Online Refund']=='Yes') {

			switch ($payment->payment_service_provider->data['Payment Service Provider Code']) {
			case 'Paypal':
				$refunded_data=online_paypal_refund($refund_amount,$payment);
				break;
			case 'Worldpay':
				$refunded_data=online_worldpay_refund($refund_amount,$payment);
				break;
			default:
				$response=array('state'=>400,'msg'=>"Error 2. Payment account can't do online refunds");
				echo json_encode($response);
				return;
			}

		}else {
			$response=array('state'=>400,'msg'=>"Payment account can't do online refunds");
			echo json_encode($response);
			return;
		}

	}else {

		$refunded_data=array(
			'status'=>'Completed',
			'reference'=>$data['refund_reference'],
		);

	}




	if ($data['parent']=='order') {
		$order_key=$data['parent_key'];
	}else {
		$order_key=0;
	}



	$payment->update(array(
			'Payment Refund'=>round($payment->data['Payment Refund']+$refund_amount,2)
		));

	$payment_data=array(
		'Payment Account Key'=>$payment->data['Payment Account Key'],
		'Payment Account Code'=>$payment->data['Payment Account Code'],
		'Payment Type'=>'Refund',

		'Payment Service Provider Key'=>$payment->data['Payment Service Provider Key'],
		'Payment Order Key'=>$order_key,
		'Payment Store Key'=>$payment->data['Payment Store Key'],
		'Payment Customer Key'=>$payment->data['Payment Customer Key'],

		'Payment Balance'=>$refund_amount,
		'Payment Amount'=>$refund_amount,
		'Payment Refund'=>0,
		'Payment Currency Code'=>$payment->data['Payment Currency Code'],
		'Payment Completed Date'=>gmdate('Y-m-d H:i:s'),
		'Payment Created Date'=>gmdate('Y-m-d H:i:s'),
		'Payment Last Updated Date'=>gmdate('Y-m-d H:i:s'),
		'Payment Transaction Status'=>$refunded_data['status'],
		'Payment Transaction ID'=>$refunded_data['reference'],
		'Payment Method'=>$payment->data['Payment Method'],
		'Payment Related Payment Key'=>$payment->id,
		'Payment Related Payment Transaction ID'=>$payment->data['Payment Transaction ID'],

	);

	$refund_payment=new Payment('create',$payment_data);

	$refund_payment->load_payment_account();


	$order=new Order($order_key);
	if ($order->id) {

		$sql=sprintf("insert into `Order Payment Bridge` values (%d,%d,%d,%d,%.2f,'No') ON DUPLICATE KEY UPDATE `Amount`=%.2f ",
			$order->id,
			$refund_payment->id,
			$refund_payment->payment_account->id,
			$refund_payment->payment_account->data['Payment Service Provider Key'],
			$refund_payment->data['Payment Amount'],
			$refund_payment->data['Payment Amount']
		);
		mysql_query($sql);
		$order->update_payment_state();


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
			'order_total_paid'=>$order->get('Payments Amount'),
			'order_total_to_pay'=>$order->get('To Pay Amount')

		);

		$payments_data=array();
		foreach ($order->get_payment_objects('',true,true) as $payment) {
			$payments_data[$payment->id]=array(
				'date'=>$payment->get('Created Date'),
				'amount'=>$payment->get('Amount'),
				'status'=>$payment->get('Payment Transaction Status')
			);
		}



		$response=array('state'=>200,
			'result'=>'updated',
			'order_shipping_method'=>$order->data['Order Shipping Method'],
			'data'=>$updated_data,
			'shipping'=>money($order->new_value),
			'shipping_amount'=>$order->data['Order Shipping Net Amount'],
			'ship_to'=>$order->get('Order XHTML Ship Tos'),
			'tax_info'=>$order->get_formated_tax_info_with_operations(),
			'payments_data'=>$payments_data,
			'order_total_paid'=>$order->data['Order Payments Amount'],
			'order_total_to_pay'=>$order->data['Order To Pay Amount']
		);

		echo json_encode($response);
	}


}


function online_paypal_refund($refund_amount,$payment) {
	require_once 'class.PaypalRefund.php';

	$aryData['transactionID'] = $payment->data['Payment Transaction ID'];   //Payment Transaction ID   1JR99805457778808
	$aryData['refundType'] = "Partial"; //Partial or Full   can do full one as Partial if we want still works
	$aryData['currencyCode'] =$payment->data['Payment Currency Code'];    //Payment Currency Code
	$aryData['amount'] = round(-1.0*$refund_amount,2);
	$aryData['memo'] = _("Refund");  //what ever we want to say back to the customer about the refunds
	//  $aryData['invoiceID'] = "Order:00053";

	$ref = new PayPalRefund(
		$payment->payment_account->data['Payment Account Refund Login'],
		$payment->payment_account->data['Payment Account Refund Password'],
		$payment->payment_account->data['Payment Account Refund Signature'],
		$payment->payment_account->data['Payment Account Refund URL Link']
	);


	// print_r($aryData);

	//exit;

	$aryRes = $ref->refundAmount($aryData);

	//print_r($aryRes);

	if ($aryRes['ACK'] == "Success") {

		$refunded_data=array(
			'status'=>'Completed',
			'reference'=>$aryRes['REFUNDTRANSACTIONID'],
		);

	}else {
		$refunded_data=array(
			'status'=>'Error',
			'reference'=>$aryRes['L_LONGMESSAGE0']
		);
	}

	return $refunded_data;

}

function online_worldpay_refund($refund_amount,$payment) {


	$url =$payment->payment_account->data['Payment Account Refund URL Link'];

	$authPW = $payment->payment_account->data['Payment Account Refund Password'];

	$instId =$payment->payment_account->data['Payment Account Refund Login'];

	$cartId = "Refund";  // always the same
	$testMode = "0";   // 0 when live

	$amount = round(-1.0*$refund_amount,2); // amount of refund
	$normalAmount = $amount;
	$op= "refund-partial";  // for full refund change to 'refund-full' and  amount  ='';
	$transId = $payment->data['Payment Transaction ID']; //  Payment Transaction ID
	$Currency = $payment->data['Payment Currency Code'];  //Payment Currency Code
	$startDelayUnit = 4;  // always the same
	$startDelayMult = 1; // always the same
	$intervalMult = 1;   // always the same
	$intervalUnit = 4;  // always the same
	$option = 0;        // always the same


	$sigNotMd5 = $payment->payment_account->data['Payment Account Password'];



	$signature = $sigNotMd5 . ":".$instId.":" . $Currency . ":" . $amount;
	$signature = md5($signature);

	$request=sprintf("https://%s?authPW=%s&instId=%s&cartId=%s&testMode=%s&signature=%s&normalAmount=%s&op=%s&transId=%s&amount=%s&currency=%s&startDelayUnit=%s&startDelayMult=%s&intervalMult=%s&intervalUnit=%s&option=%s",
		$url,
		$authPW,
		$instId,
		$cartId,
		$testMode,
		$signature,
		$normalAmount,
		$op,
		$transId,
		$amount,
		$Currency,
		$startDelayUnit,
		$startDelayMult,
		$intervalMult,
		$intervalUnit,
		$option
	);

$request=urlencode($request);

	$response=file_get_contents($request);


	var_dump($request);
	print " HOLA ";
var_dump($response);
print " HOLA ";
exit;
	$respond_array=preg_split('/\,/',$response);
	if (count($respond_array)==3) {

		if ($respond_array[0]=="A") {
			$refunded_data=array(
				'status'=>'Completed',
				'reference'=>$respond_array[1]
			);
		}elseif ($respond_array[0]=="N") {
			$refunded_data=array(
				'status'=>'Error',
				'reference'=>$respond_array[2]
			);
		}else {
			$refunded_data=array(
				'status'=>'Error',
				'reference'=>'Unknown response:'.$response
			);

		}
	}else {
		$refunded_data=array(
			'status'=>'Error',
			'reference'=>'Wrong response:'.$response
		);

	}

return $refunded_data;


}




?>
