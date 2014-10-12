<?php

include_once 'class.SendEmail.php';
include_once 'class.Payment_Service_Provider.php';
include_once 'class.Payment.php';
include_once 'class.Payment_Account.php';



function send_confirmation_email($order) {


	$site=new Site($order->data['Order Site Key']);

	$store=new Store($order->data['Order Store Key']);


	if (!$site->id) {
		// to do get credentials from store and send email anyway maybe
		return;
	}

	$email_mailing_list_key=0;


	$credentials=$site->get_email_credentials();
	if (!$credentials) {
		return false;
	}
	$message_data['from_name']=$site->data['Site Name'];
	$message_data['method']='smtp';
	$message_data['type']='HTML';
	$message_data['to']=$order->data['Order Email'];
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



$payment_info_for_customer='<p></p>';
	$payment_info_for_customer_for_internal_notification='<p></p>';

	if ($order->get_number_payments()>0) {


		foreach ($order->get_payment_objects('',true,true)  as $payment) {


			$payment_info_for_customer=', '.$payment->get_formated_info();
			$payment_info_for_customer_for_internal_notification=', '.$payment->get_formated_info();

		}
		$payment_info_for_customer='<p>'.preg_replace('/^\, /','',$payment_info_for_customer).'</p>';
		$payment_info_for_customer_for_internal_notification='<p>'.preg_replace('/^\, /','',$payment_info_for_customer_for_internal_notification).'</p>';



	}
	
	if(!$order->data['Order Payment Key']) {

		if ($payment_service_provider->data['Payment Service Provider Type']=='Bank') {

			$payment_info_for_customer.='<p>'._('Here are our bank details').'</p><div>'.$payment_account->get_formated_bank_data().'</div><p>'._('Please always state the order number in the payment reference').'.</p>';
			$payment_info_for_customer_for_internal_notification.=_('To be paid by bank transfer');

		}elseif ($payment_service_provider->data['Payment Service Provider Type']=='ConD') {

			$payment_info_for_customer_for_internal_notification.=_('To be paid by cash on delivery');
		}else {

			$payment_info_for_customer_for_internal_notification.=_('To be paid by').': '.$payment_service_provider->data['Payment Service Provider Type'];

		}
	}






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

	if ($order->get('Order Net Credited Amount')!=0) {
		$order_info.=sprintf('<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>',
			_('Credits'),
			$order->get('Net Credited Amount
			')

		);
	}

	if ($order->get('Order Charges Net Amount')!=0) {
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

	if ($order->get('Order Insurance Net Amount')!=0) {
		$order_info.=sprintf('<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>',
			_('Insurance'),
			$order->get('Insurance Net Amount')

		);
	}



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

	if ($order->get('Order Payments Amount')!=0) {

		$order_info.=sprintf('<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>',
			_('Paid'),
			$order->get('Payments Amount')

		);
		$order_info.=sprintf('<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>',
			_('To Pay Amount'),
			$order->get('To Pay Amount')

		);
	}

	$order_info.='</table>';

	$message_data['email_placeholders']=array(
		'CUSTOMERS_NAME' => $order->get_name_for_grettings(),
		'ORDER_NUMBER'=>$order->get('Order Public ID'),
		'PAYMENT_EXTRA_INFO'=>$payment_info_for_customer,
		'ORDER_DATA'=>$order_info,
		'Order_XHTML_Ship_Tos'=>$order->get('Order XHTML Ship Tos'),
		'Order_XHTML_Billing_Tos'=>$order->get('Order XHTML Billing Tos'),

	);


	$message_data['promotion_name']=$site->data['Site Order Confirmation Email Code'];

	if ($site->data['Site Direct Subscribe Madmimi']) {
		$message_data['madmimi_auto_subscribe']=$site->data['Site Direct Subscribe Madmimi'];
	}

	$send_email=new SendEmail();

	$send_email->track=false;
	$send_email->secret_key=CKEY;

	//print_r($message_data);

	$send_email->set($message_data);

	$send_email->from=$store->data['Store Name'].' <'.$store->data['Store Email'].'>';

	$result=$send_email->send();


	//notification email

	$notification_recipients=preg_split('/,/',$site->data['Site Order Notification Email Recipients']);

	foreach ($notification_recipients as $notification_recipient) {

		$message_data['from_name']=$site->data['Site Name'];
		$message_data['method']='smtp';
		$message_data['type']='HTML';
		$message_data['to']=$notification_recipient;
		$message_data['subject']='Order notification'.' '.$store->data['Store Code'];
		$message_data['html']='';
		$message_data['email_credentials_key']=$credentials['Email Credentials Key'];
		$message_data['email_matter']='Order Confirmation';
		$message_data['email_matter_key']=$email_mailing_list_key;
		$message_data['email_matter_parent_key']=$email_mailing_list_key;
		$message_data['recipient_type']='User';
		$message_data['recipient_key']=0;
		$message_data['email_key']=0;
		$message_data['plain']=null;





		$message_data['email_placeholders']=array(
			'Order_Public_ID'=>$order->get('Order Public ID'),

			'Balance_Total_Amount' => $order->get('Balance Total Amount'),
			'Order_Number_Products'=>$order->get('Order Number Products'),
			'Created_Date'=>$order->get('Created Date'),
			'Submitted_by_Customer_Date'=>$order->get('Submitted by Customer Date'),
			'Order_Customer_Name'=>$order->get('Order Customer Name'),
			'Order_Customer_Contact_Name'=>$order->get('Order Customer Contact Name'),
			'Order_Customer_Key'=>$order->get('Order Customer Key'),
			'Order_Tax_Number'=>($order->get('Order Tax Number')==''?'ND':$order->get('Order Tax Number')),
			'Tax_Number_OK'=>($order->get('Order Tax Number Valid')==''?'ND':$order->get('Order Tax Number Valid')),
			'Order_XHTML_Ship_Tos'=>$order->get('Order XHTML Ship Tos'),
			'Order_XHTML_Billing_Tos'=>$order->get('Order XHTML Billing Tos'),
			'Order_Customer_Message'=>($order->get('Order Customer Message')==''?'ND':$order->get('Order Customer Message')),
			'Order_Voucher_Code'=>'ND',


			'PAYMENT_EXTRA_INFO'=>$payment_info_for_customer_for_internal_notification,
			'ORDER_DATA'=>$order_info
		);


		$message_data['promotion_name']=$site->data['Site Order Notification Email Code'];

		if ($site->data['Site Direct Subscribe Madmimi']) {
			$message_data['madmimi_auto_subscribe']=$site->data['Site Direct Subscribe Madmimi'];
		}

		//$send_email->bcc=$site->data['Site Order Notification Email Recipients'];

		$send_email=new SendEmail();

		$send_email->track=false;
		$send_email->secret_key=CKEY;

		//print_r($message_data);

		$send_email->set($message_data);

		$send_email->from=$store->data['Store Name'].' <'.$store->data['Store Email'].'>';

		$result=$send_email->send();
	}


}

?>
