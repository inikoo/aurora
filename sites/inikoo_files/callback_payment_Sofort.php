<?php
/*

 About:
 Autor: Jonathan Hardi, supervied by is mastrer Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 27 May 2014 10:46:57 CEST, Malaga , Spain

 Version 2.0
*/


@mail("jonneyblueeyesuk@hotmail.com", "WorldPay DEBUGGING Good", 'xxx');

exit("xxx");

include_once 'class.Payment.php';

if (!isset($_POST['pass1']) or !isset($_POST['payment_key']) or !isset($_POST['tranid']) or !isset($_POST['fullamount'])  ) {
	return
}

$rep_password1 = $_POST['pass1'];
$rep_amount = $_POST['fullamount']/100 ;
$payment_key = $_POST['payment_key'];


$payment=new Payment($payment_key);

list ($valid,$error,$error_info)=check_if_valid($rep_password1,$rep_amount,$payment);


if ($valid) {

	if ($payment->data['Payment Transaction Status']=='Pending') {





		$rep_tranid = $_POST['tranid'];
		$rep_userid = $_POST['userid'];
		$rep_custid = $_POST['custid'];
		$rep_productid = $_POST['productid'];

		$rep_SENDER_HOLDER = $_POST['SENDERHOLDER'];
		$rep_SENDER_ACCOUNT_NUMBER = $_POST['SENDERACCOUNTNUMBER'];
		$rep_SENDER_BANK_CODE = $_POST['SENDERBANKCODE'];
		$rep_SENDER_BANK_NAME = $_POST['SENDERBANKNAME'];
		$rep_SENDER_BANK_BIC = $_POST['SENDERBANKBIC'];
		$rep_SENDER_IBAN = $_POST['SENDERIBAN'];
		$rep_SENDER_COUNTRY_ID = $_POST['SENDERCOUNTRYID'];

		$rep_TIMESTAMP = $_POST['TIMESTAMP'];
		$rep_CURRENCY_ID = $_POST['CURRENCYID'];
		$rep_FEES = $_POST['FEES'];
		$rep_STATUS = $_POST['STATUS'];
		$rep_STATUS_REASON = $_POST['STATUSREASON'];
		$rep_STATUS_MODIFIED = $_POST['STATUSMODIFIED'];
		$rep_AMOUNT_REFUNDED = $_POST['AMOUNTREFUNDED'] /100;

		$rep_RECIPIENT_HOLDER = $_POST['RECIPIENTHOLDER'];
		$rep_RECIPIENT_HOLDER_URLENCODE = $_POST['RECIPIENTHOLDERURLENCODE'];
		$rep_RECIPIENT_ACCOUNT_NUMBER = $_POST['RECIPIENTACCOUNTNUMBER'];
		$rep_RECIPIENT_BANK_CODE = $_POST['RECIPIENTBANKCODE'];
		$rep_RECIPIENT_BANK_NAME = $_POST['RECIPIENTBANKNAME'];
		$rep_RECIPIENT_BANK_NAME_URLENCODE = $_POST['RECIPIENTBANKNAMEURLENCODE'];
		$rep_RECIPIENT_BANK_BIC = $_POST['RECIPIENTBANKBIC'];
		$rep_RECIPIENT_IBAN = $_POST['RECIPIENTIBAN'];
		$rep_RECIPIENT_COUNTRY_ID = $_POST['RECIPIENTCOUNTRYID'];
		$rep_FEES = '';

		$data_to_update=array(
			'Payment Sender'=>$rep_SENDER_HOLDER,
			'Payment Sender Account Number'=>$rep_SENDER_ACCOUNT_NUMBER,
			'Payment Sender Sort Code'=>$rep_SENDER_BANK_CODE,
			'Payment Sender Bank Name'=>$rep_RECIPIENT_BANK_NAME,
			'Payment Sender BIC'=>$rep_RECIPIENT_BANK_BIC,
			'Payment Sender IBAN'=>$rep_RECIPIENT_IBAN,
			'Payment Sender Country 2 Alpha Code'=>$rep_SENDER_COUNTRY_ID,
			'Payment Completed Date'=>gmdate('Y-m-d H:i:s'),
			'Payment Last Updated Date'=>gmdate('Y-m-d H:i:s'),
			'Payment Transaction Status'=>'Completed',



		);
		$payment->update($data_to_update);
		$order=new Order($payment_key->data['Payment Order Key']);
		$order->checkout_submit_order()();
	);
}


}else {


}



function check_if_valid($rep_password1,$rep_amount,$payment) {

$valid=true;
$error='';
$error_info='';

if (!$payment->id) {
	$valid=false;
	$error_type='no_payment_found';
	$error_info=$payment->id;
	return array($valid,$error,$error_info);
}

if ($rep_password1 == $payment->data['Payment Random String'] ) {
	$valid=false;
	$error_type='wrong_signature';
	$error_info=$rep_password1;

	return array($valid,$error,$error_info);

}


if ($payment->data['Payment Balance'] == $rep_amount) {
	$valid=false;
	$error_type='payment_amount_not_match';
	$error_info=$rep_amount;
	return array($valid,$error,$error_info);

}


if ($payment->data['Payment Balance'] == $rep_amount) {
	$valid=false;
	$error_type='payment_amount_not_match';
	$error_info=$rep_amount;
	return array($valid,$error,$error_info);

}


return array($valid,$error,$error_info);

}



?>
