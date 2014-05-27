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

default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);

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
		'Payment_Account_Return_Link_Good'=>$payment_account->data['Payment Account Return Link Good'],
		'Payment_Account_Return_Link_Bad'=>$payment_account->data['Payment Account Return Link Bad'],
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
		'Order_key'=>$order->id,
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



?>
