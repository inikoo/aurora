<?php
require_once 'common.php';
require_once 'ar_edit_common.php';
require_once 'class.Order.php';
require_once 'class.User.php';
include_once 'class.Customer.php';
include_once 'class.Payment.php';





if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>407,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case('get_payment_status'):
	$data=prepare_values($_REQUEST,array(
			'payment_key'=>array('type'=>'key'),

		));
	get_payment_status($data);
	break;


default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);

}



function get_payment_status($data) {
	global $user,$site,$language,$customer;


$payment=new Payment($data['payment_key']);
	$order=new Order($payment->data['Payment Order Key']);
	
	
		if (!$order->id) {
		$response=array('state'=>201,'msg'=>'error: order dont exists','type_error'=>'invalid_order_key');
		echo json_encode($response);
		return;
	}
	
	$pending_payments=count($order->get_payment_keys('Pending'));
	
	if (!$payment->id) {
		$response=array(
		'state'=>200,
		'payment_key'=>$data['payment_key'],
		'pending_payments'=>$pending_payments,
		'status'=>'Deleted',
		'created_time_interval'=>0,
		'msg'=>'error: payment dont exists',
		'type_error'=>'invalid_payment_key');
	}else{
	
	$response=array(
		'state'=>200,
		'payment_key'=>$payment->id,
		'pending_payments'=>$pending_payments,
		'status'=>$payment->data['Payment Transaction Status'],
		'created_time_interval'=>$payment->get_formated_time_lapse('Created Date')
	);
	}



	
	
	
	

	echo json_encode($response);
	return;





}





?>
