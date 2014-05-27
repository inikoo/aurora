<?php




require_once 'common.php';
include_once 'class.Payment.php';
include_once 'class.Payment_Account.php';



if (!isset($_POST['custom']) or !isset($_POST['mc_gross'])  ) {
	exit();
}



$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}

// post back to PayPal system to validate

$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";


$header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Host: www.paypal.com\r\n";  // www.paypal.com for a live site
$header .= "Content-Length: " . strlen($req) . "\r\n";
$header .= "Connection: close\r\n\r\n";

$fp = fsockopen('ssl://www.paypal.com', 443, $errno, $errstr, 30);
//$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
// assign posted variables to local variables
$item_name = (isset($_POST['item_name'])?$_POST['item_name']:'');
$item_number = (isset($_POST['item_number'])?$_POST['item_number']:'');
$payment_status = $_POST['payment_status'];
$payment_shipping = $_POST['mc_shipping'];
$payment_amountG1 = (isset($_POST['mc_gross1'])?$_POST['mc_gross1']:'');
$payment_amount = $_POST['mc_gross'];


$payment_currency = $_POST['mc_currency'];
$payment_transaction_key = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];
$This_invoice = $_POST['invoice'];
$payment_key = $_POST['custom'];
$This_txn_type = $_POST['txn_type'];

//$payment_gross = $_POST['payment_gross'];
//$amount = $_POST['amount'];
$txn_type = $_POST['txn_type'];
//$profile_status = $_POST['profile_status'];
//$payment_cycle = $_POST['payment_cycle'];


$residence_country = $_POST['residence_country'];
$payment_cycle = (isset($_POST['payment_cycle'])?$_POST['payment_cycle']:'');
$mc_fee = $_POST['mc_fee'];
$reason_code = (isset($_POST['reason_code'])?$_POST['reason_code']:'');
$payment_type = $_POST['payment_type'];
$payment_memo = (isset($_POST['mc_gross1'])?$_POST['mc_gross1']:'');


$payment_address_country_code = $_POST['residence_country'];
$payment_address_status = $_POST['payer_status'];
$payment_first_name = $_POST['first_name'];
$payment_last_name = $_POST['last_name'];
$payment_payer_business_name = (isset($_POST['payer_business_name'])?$_POST['payer_business_name']:'');
$payment_payer_payment_status = $_POST['payment_status'];





$payment_fullName = $payment_first_name.' '.$payment_last_name;


$emailBack = $payment_payer_business_name."\n Amount=".$payment_amount."\n Email=".$receiver_email." PayPal ID=".$payment_transaction_key." Add State=".$payment_address_status;




if (!$fp) {
	// HTTP ERROR
} else {
	fputs($fp, $header . $req);


	while (!feof($fp)) {
		$res = fgets($fp, 1024);


		$TOTEST = intval(strcmp($res, "VERIFIED") );


		if ($TOTEST > -1) {
			// check the payment_status is Completed

			if ($payment_status == 'Completed' ) {

				$payment=new Payment($payment_key);
				$payment_account=new Payment_Account($payment->data['Payment Account Key']);


				list ($valid,$error,$error_info)=check_if_valid($receiver_email,$payment_amount,$payment_currency,$payment,$payment_account);

 				@mail("raul@inikoo.com", "paypal test","pay_key:".$payment->id."Erros: $valid <- ,$error,$error_info");

				if ($valid) {

					if ($payment_transaction_key!= $payment->data['Payment Transaction ID']) {


						$data_to_update=array(
							'Payment Sender'=>$payment_fullName,
							'Payment Sender Email'=>$receiver_email,
							'Payment Sender Payment Paypal Type'=>$payment_type,
							'Payment Sender Message'=>$payment_memo,
							'Payment Transaction Address Status'=>$payment_payer_payment_status,
							'Payment Fees'=>$mc_fee,
							'Payment Transaction Status Info'=>$payment_status,
							'Payment Completed Date'=>gmdate('Y-m-d H:i:s'),
							'Payment Last Updated Date'=>gmdate('Y-m-d H:i:s'),
							'Payment Transaction Status'=>'Completed',
							'Payment Transaction ID'=>$payment_transaction_key,


						);


						$payment->update($data_to_update);
						$order=new Order($payment->data['Payment Order Key']);
						$order->checkout_submit_order();


					}


				}else {


				}


				if ($payment_transaction_key!= $payment->data['Payment Transaction ID']) {

					if ($receiver_email == $payment_account->data['Payment Account Login']) {





					}

				}






			}


		}
		else if (strcmp($res, "INVALID") == 0) {
				// log for manual investigation
			}
	}
	fclose($fp);
}

function check_if_valid($login,$amount,$currency,$payment,$payment_account) {

	$valid=true;
	$error='';
	$error_info='';

	if (!$payment->id) {
		$valid=false;
		$error_type='no_payment_found';
		$error_info=$payment->id;
		return array($valid,$error,$error_info);
	}


	if ($login != $payment_account->data['Payment Account Login'] ) {
		$valid=false;
		$error_type='wrong_login';
		$error_info=$payment_account->data['Payment Account Login'].'<<-->>'.$login;

		return array($valid,$error,$error_info);

	}

/*
	if ($payment->data['Payment Balance'] != $amount) {
		$valid=false;
		$error_type='payment_amount_not_match';
		$error_info=$payment->data['Payment Balance'].'<->'.$amount;
		return array($valid,$error,$error_info);

	}
	*/

	if ($payment->data['Payment Currency Code'] != $currency) {
		$valid=false;
		$error_type='payment_currency_not_match';
		$error_info='->'.$payment->data['Payment Currency Code'].'<->'.$currency.'<-';
		return array($valid,$error,$error_info);

	}


	return array($valid,$error,$error_info);

}


?>
