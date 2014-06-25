<?php
/*

 About:
 Autor: Jonathan Hardi, supervied by is mastrer Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 27 May 2014 10:46:57 CEST, Malaga , Spain

 Version 2.0
*/

require_once 'common.php';
include_once 'class.Payment.php';
include_once 'class.Payment_Account.php';

include_once 'send_confirmation_email_function.php';
@mail("raul@inikoo.com", "softshit", var_export($_REQUEST, true));


if (!isset($_POST['pass1']) or !isset($_POST['payment_key']) or !isset($_POST['tranid']) or !isset($_POST['fullamount'])  ) {
	exit();
}

$rep_password1 = $_POST['pass1'];
$rep_amount = $_POST['fullamount']/100 ;
$payment_key = $_POST['payment_key'];


$payment=new Payment($payment_key);
$payment_account=new Payment_Account($payment->data['Payment Account Key']);

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
			'Payment Transaction ID'=>$rep_tranid,


		);

		$payment->update($data_to_update);
		$order=new Order($payment->data['Payment Order Key']);




		$order->update(
			array(
				'Order Payment Account Key'=>$payment_account->id,
				'Order Payment Account Code'=>$payment_account->data['Payment Account Code'],
				'Order Payment Method'=>$payment_account->data['Payment Type'],
				'Order Payment Key'=>$payment->id,
				'Order Checkout Completed Payment Date'=>gmdate('Y-m-d H:i:s'),


			));
			
	
	
				//======
				
				$account_payment_key=0;
				$sql=sprintf("select `Payment Key` from `Order Payment Bridge` where `Is Account Payment`='Yes' and `Order Key`=%d ",
					$order->id

				);
				$res=mysql_query($sql);
				if ($row=mysql_fetch_assoc($res)) {
					$account_payment_key=$row['Payment Key'];

				}



				if ($account_payment_key) {
					$account_payment=new Payment($account_payment_key);

					$data_to_update=array(
							'Payment Completed Date'=>gmdate('Y-m-d H:i:s'),
							'Payment Last Updated Date'=>gmdate('Y-m-d H:i:s'),
							'Payment Transaction Status'=>'Completed'

					);



					$account_payment->update($data_to_update);
				
				
				//====

		$order->checkout_submit_order();






		send_confirmation_email($order);

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

	if ($rep_password1 != md5($payment->data['Payment Random String']) ) {
		$valid=false;
		$error_type='wrong_signature';
		$error_info=$payment->data['Payment Random String'].'<<-->>'.$rep_password1;

		return array($valid,$error,$error_info);

	}


	if ($payment->data['Payment Balance'] != $rep_amount) {
		$valid=false;
		$error_type='payment_amount_not_match';
		$error_info=$payment->data['Payment Balance'].'<->'.$rep_amount;
		return array($valid,$error,$error_info);

	}


	return array($valid,$error,$error_info);

}



?>
