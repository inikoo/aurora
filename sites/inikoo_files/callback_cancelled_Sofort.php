<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 27 May 2014 15:03:42 CEST, Malaga , Spain

 Version 2.0
*/
require_once 'common.php';
require_once 'ar_edit_common.php';
require_once 'class.Order.php';
require_once 'class.User.php';
include_once 'class.Payment.php';

if(!isset($_REQUEST['payment_key'])){
exit;
}

print_r($_REQUEST);

$payment_key=$_REQUEST['payment_key'];
$payment=new Payment($payment_key);

	$data_to_update=array(
			
			'Payment Completed Date'=>'',
			'Payment Last Updated Date'=>gmdate('Y-m-d H:i:s'),
			'Payment Cancelled Date'=>gmdate('Y-m-d H:i:s'),
			'Payment Transaction Status'=>'Cancelled',



		);
		$payment->update($data_to_update);
$order=new Order($payment->data['Payment Order Key']);
		$order->checkout_cancel_payment();
		
			header('Location: checkout.php?payment_cancelled_key='.$payment->id);


?>