<?php
require_once 'common.php';
require_once 'ar_edit_common.php';
require_once 'class.Order.php';
require_once 'class.User.php';
include_once 'class.Customer.php';
include_once 'class.Payment.php';
include_once 'class.Payment_Account.php';
include_once 'class.Payment_Service_Provider.php';
include_once 'class.Billing_To.php';
include_once 'class.SendEmail.php';



require_once 'order_common_functions.php';



if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>407,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case('create_payment'):
	$data=prepare_values($_REQUEST,array(
			'payment_account_key'=>array('type'=>'key'),
			'order_key'=>array('type'=>'key'),

		));
	create_payment($data);
	break;
case('cancel_payment'):
	$data=prepare_values($_REQUEST,array(
			'payment_key'=>array('type'=>'key'),
			'order_key'=>array('type'=>'key')
		));
	cancel_payment($data);
	break;
case('submit_order'):
	$data=prepare_values($_REQUEST,array(
			'payment_account_key'=>array('type'=>'key'),
			'order_key'=>array('type'=>'key'),

		));
	submit_order($data);
	break;

default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);

}



function submit_order($data) {
	global $user,$site,$language,$customer;

	$order=new Order($data['order_key']);


	if (!$order->id) {
		$response=array('state'=>400,'msg'=>'error: order dont exists','type_error'=>'invalid_order_key');
		echo json_encode($response);
		return;
	}

	$payment_account=new Payment_Account($data['payment_account_key']);


	if (!$payment_account->id) {
		$response=array('state'=>400,'msg'=>'error: payment account dont exists','type_error'=>'invalid_payment_account_key');
		echo json_encode($response);
		return;
	}

	if (!$payment_account->in_site($site->id)) {
		$response=array('state'=>400,'msg'=>'error: payment account not in this site','type_error'=>'payment_account_not_in_site');
		echo json_encode($response);
		return;
	}

	if (!$payment_account->is_active_in_site($site->id)) {
		$response=array('state'=>400,'msg'=>'error: payment account not active','type_error'=>'payment_account_not_active');
		echo json_encode($response);
		return;
	}


	$order->checkout_submit_order();


	$order->update(
		array(
			'Order Payment Account Key'=>$payment_account->id,
			'Order Payment Account Code'=>$payment_account->data['Payment Account Code'],
			'Order Payment Method'=>$payment_account->data['Payment Type']
		));



send_confirmation_email($order);


	$response=array('state'=>200,
		'order_key'=>$order->id
	);
	echo json_encode($response);
	return;





}

function create_payment($data) {
	global $user,$site,$language,$customer;

	$order=new Order($data['order_key']);
	$payment_account=new Payment_Account($data['payment_account_key']);

	if (!$order->id) {
		$response=array('state'=>400,'msg'=>'error: order dont exists','type_error'=>'invalid_order_key');
		echo json_encode($response);
		return;
	}


	if (!$order->data['Order Current Dispatch State']=='In Process by Customer') {
		$response=array('state'=>400,'msg'=>'error: order dispatch state not valid '.$order->data['Order Current Dispatch State'],'type_error'=>'invalid_dispatch_state');
		echo json_encode($response);
		return;
	}

	if (!$payment_account->id) {
		$response=array('state'=>400,'msg'=>'error: payment account dont exists','type_error'=>'invalid_payment_account_keyy');
		echo json_encode($response);
		return;
	}

	if (!$payment_account->in_site($site->id)) {
		$response=array('state'=>400,'msg'=>'error: payment account not in this site','type_error'=>'payment_account_not_in_site');
		echo json_encode($response);
		return;
	}

	if (!$payment_account->is_active_in_site($site->id)) {
		$response=array('state'=>400,'msg'=>'error: payment account not active','type_error'=>'payment_account_not_active');
		echo json_encode($response);
		return;
	}

	$payment_service_provider=new Payment_Service_Provider($payment_account->data['Payment Service Provider Key']);


	//Todo
	// chack if order in site

	$billing_to=new Billing_To($order->data['Order Billing To Keys']);




	$payment_data=array(
		'Payment Account Key'=>$payment_account->id,
		'Payment Account Code'=>$payment_account->data['Payment Account Code'],

		'Payment Service Provider Key'=>$payment_account->data['Payment Service Provider Key'],
		'Payment Order Key'=>$order->id,
		'Payment Store Key'=>$order->data['Order Store Key'],
		'Payment Site Key'=>$site->id,
		'Payment Customer Key'=>$order->data['Order Customer Key'],

		'Payment Balance'=>$order->data['Order Balance Total Amount'],
		'Payment Amount'=>$order->data['Order Balance Total Amount'],
		'Payment Refund'=>0,
		'Payment Currency Code'=>$order->data['Order Currency'],
		'Payment Created Date'=>gmdate('Y-m-d H:i:s'),
		'Payment Random String'=>md5(mt_rand().date('U'))


	);

	$payment=new Payment('create',$payment_data);

	$order->checkout_submit_payment();

	$contact=new Contact($customer->data['Customer Main Contact Key']);

	switch ($payment_service_provider->data['Payment Service Provider Code']) {
	case 'Worldpay':
		$signature = $payment_account->data['Payment Account Password'].":".$payment_account->data['Payment Account ID'].":".$payment_account->data['Payment Account Cart ID'].":" . $payment->data['Payment Currency Code'] . ":" . $payment->data['Payment Balance'] . ":" . $payment->data['Payment Customer Key'];
		$signature = md5($signature);
		break;
	default:
		$signature='';
	}

	$payment_data=array(

		'Payment_Account_URL_Link'=>$payment_account->data['Payment Account URL Link'],
		'Payment_Account_ID'=>$payment_account->data['Payment Account ID'],
		'Payment_Account_Login'=>$payment_account->data['Payment Account Login'],
		'Payment_Account_Cart_ID'=>$payment_account->data['Payment Account Cart ID'],
		'Payment_Account_Return_Link_Good'=>'http://'.$site->data['Site URL']."/thanks.php?id=".$order->id,
		'Payment_Account_Return_Link_Bad'=>'http://'.$site->data['Site URL']."/return_cancelled_Paypal.php?payment_key=".$payment->id,
		'Payment_Random_String'=>$payment->data['Payment Random String'],
		'Payment_Currency_Code'=>$payment->data['Payment Currency Code'],
		'Customer_Name'=>$order->data['Order Customer Name'],
		'Customer_Contact_Name'=>$order->data['Order Customer Contact Name'],
		'Customer_Main_Plain_Email'=>$customer->data['Customer Main Plain Email'],
		'Payment_Account_Business_Name'=>$payment_account->data['Payment Account Business Name'],
		'Payment_Customer_Key'=>$payment->data['Payment Customer Key'],
		'Billing_To_Line1'=>$billing_to->data['Billing To Line 1'],
		'Billing_To_Line2'=>$billing_to->data['Billing To Line 2'],
		'Billing_To_Line3'=>$billing_to->data['Billing To Line 3'],
		'Billing_To_Town'=>$billing_to->data['Billing To Town'],
		'Billing_To_Postal_Code'=>$billing_to->data['Billing To Postal Code'],
		'Billing_To_2_Alpha_Code'=>$billing_to->data['Billing To Country 2 Alpha Code'],
		'Customer_Main_Plain_Telephone'=>$customer->data['Customer Main Plain Telephone'],
		'Payment_Balance'=>$payment->data['Payment Balance'],
		'Description'=>_('Order').':'.$order->data['Order Public ID'],
		'Description2'=>_('Customer').':'.$customer->data['Customer Name'],
		'signature'=>$signature,
		'Payment_Service_Provider_Key'=>$payment_service_provider->id,
		'Payment_Key'=>$payment->id,
		'Language'=>$language,
		'Order_Key'=>$order->id,
		'First_Name'=>$contact->data['Contact First Name'],
		'Last_Name'=>$contact->data['Contact Surname']

	);

	$response=array('state'=>200,
		'payment_data'=>$payment_data,
		'payment_service_provider_code'=>$payment_service_provider->data['Payment Service Provider Code']








	);
	echo json_encode($response);
	return;





}


function cancel_payment($data) {


	$order=new Order($data['order_key']);
	$payment=new Payment($data['payment_key']);



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
		'Payment Transaction Status Info'=>'Cancelled by customer',


	);
	$payment->update($data_to_update);




	$pending_payments=count($order->get_payment_keys('Pending'));

	if ($pending_payments==0) {

		if (  count($order->get_payment_keys('Completed'))) {

			$order->checkout_submit_payment();
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



function send_confirmation_email($order) {

	global $site,$user,$customer;

	$email_mailing_list_key=0;


	$credentials=$site->get_email_credentials();
	if (!$credentials) {
		return false;
	}
	$message_data['from_name']=$site->data['Site Name'];
	$message_data['method']='smtp';
	$message_data['type']='HTML';
	$message_data['to']=$user->data['User Handle'];
	$message_data['subject']=_('Order confirmation');
	$message_data['html']='';
	$message_data['email_credentials_key']=$credentials['Email Credentials Key'];
	$message_data['email_matter']='Order Confirmation';
	$message_data['email_matter_key']=$email_mailing_list_key;
	$message_data['email_matter_parent_key']=$email_mailing_list_key;
	$message_data['recipient_type']='User';
	$message_data['recipient_key']=0;
	$message_data['email_key']=0;
	$message_data['plain']=null;




	$order_items_info=$order->get_items_info();
	$order_info='<table  cellpadding="0">';
	$order_info.=sprintf('<tr style="border-bottom:1px solid #cccccc">
	<td style="padding:5px;border-bottom:1px solid #777777">%s</td>
	<td style="padding:5px;border-bottom:1px solid #777777">%s</td>
	<td style="padding:5px;border-bottom:1px solid #777777">%s</td>
	<td style="padding:5px;border-bottom:1px solid #777777">%s</td>
	</tr>',
		_('Code'),
		_('Description'),
		_('Quantity'),
		_('Amount')

	);

	foreach ($order_items_info as $data) {
		$order_info.=sprintf('<tr style="border-bottom:1px solid #cccccc">
		<td style="padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>',
			$data['code_plain'],
			$data['description'],
			$data['quantity'],
			$data['to_charge']

		);
	}
	

		$order_info.=sprintf('<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>',
			_('Items Net'),
			$order->get('Items Net Amount')

		);
	
	if($order->get('Order Net Credited Amount')!=0){
	$order_info.=sprintf('<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>',
			_('Credits'),
			$order->get('Net Credited Amount
			')

		);
		}
	
	if($order->get('Order Charges Net Amount')!=0){
	$order_info.=sprintf('<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>',
			_('Charges'),
			$order->get('Charges Net Amount')

		);
		}
		
		$order_info.=sprintf('<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>',
			_('Shipping'),
			$order->get('Shipping Net Amount')

		);
	
		$order_info.=sprintf('<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>',
			_('Net'),
			$order->get('Balance Net Amount')

		);
		
		$order_info.=sprintf('<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>',
			_('Tax'),
			$order->get('Balance Tax Amount')

		);
		
		$order_info.=sprintf('<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>',
			_('Total'),
			$order->get('Balance Total Amount')

		);
		
		
		
	
	
	
	$order_info.='</table>';

	$message_data['email_placeholders']=array(
		'CUSTOMERS_NAME' => $customer->get_name_for_grettings(),
		'ORDER_NUMBER'=> $order->data['Order Public ID'],
		'PAYMENT_EXTRA_INFO'=>'x',
		'ORDER_DATA'=>$order_info
	);

	$message_data['promotion_name']='thanks_cart_inikoo_AW';

	if ($site->data['Site Direct Subscribe Madmimi']) {
		$message_data['madmimi_auto_subscribe']=$site->data['Site Direct Subscribe Madmimi'];
	}

	$send_email=new SendEmail();

	$send_email->track=false;
	$send_email->secret_key=CKEY;

print_r($message_data);

	$send_email->set($message_data);
	$result=$send_email->send();
	print_r($result);

}

?>
