<?php

include_once 'class.SendEmail.php';
include_once 'class.Payment_Service_Provider.php';
include_once 'class.Payment.php';
include_once 'class.Payment_Account.php';



function send_confirmation_email($order) {

	global $site,$user,$customer,$store;

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



	$payment_account=new Payment_Account($order->data['Order Payment Account Key']);
	$payment_service_provider=new Payment_Service_Provider($payment_account->data['Payment Service Provider Key']);
	//print_r($payment_account->data);
	//print_r($payment_service_provider->data);

	$payment_info='<p>kiss</p>';

	if ($order->data['Order Payment Key']) {

		$payment = new Payment($order->data['Order Payment Key']);
		$payment_info=$payment->get_formated_info();

	}else {

		if ($payment_service_provider->data['Payment Service Provider Type']=='Bank') {

			$payment_info='<p>'._('Here are our bank details').'</p><div>'.$payment_account->get_formated_bank_data().'</div><p>'._('Please always state the order number in the payment reference').'.</p>';
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
		'ORDER_NUMBER'=>$order->get('Order Public ID'),
		'PAYMENT_EXTRA_INFO'=>$payment_info,
		'ORDER_DATA'=>$order_info
	);

	$message_data['promotion_name']='thanks_cart_inikoo_AW';

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

}

?>
