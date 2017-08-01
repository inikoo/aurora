<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 July 2017 at 16:44:58 CEST, Trnava, Slavakia
 Copyright (c) 2017, Inikoo

 Version 3

*/

use Aws\Ses\SesClient;

require_once 'common.php';
require_once 'utils/ar_web_common.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


if (!$customer->id) {
    $response = array(
        'state' => 400,
        'resp'  => 'not customer'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {

    case 'place_order_pay_later':
        $data = prepare_values(
            $_REQUEST, array(
                         'payment_account_key' => array('type' => 'key'),
                         'order_key'           => array('type' => 'key')

                     )
        );

        place_order_pay_later($data, $customer, $website, $editor,$smarty);


        break;
}

function place_order_pay_later($_data, $customer, $website, $editor,$smarty) {


    $customer->editor = $editor;


    $order_key = $_data['order_key'];

    //$order=get_object('Order',$order_key);
    include_once 'class.Order.php';
    $order = new Order($order_key);


    $order->update(
        array(
            'Order Current Dispatch State' => 'Submitted by Customer',
            'Order Checkout Block Payment Account Key' => $_data['payment_account_key']
        ), 'no_history'
    );


    send_order_confirmation_email($website, $customer, $order,$smarty);


    $response = array(
        'state' => 200,
        'key'   => $order->id,

    );


    echo json_encode($response);

}

function send_order_confirmation_email($website, $customer, $order,$smarty) {
    require 'external_libs/aws.phar';


    $webpage_key = $website->get_system_webpage_key('checkout.sys');


    $webpage = get_object('webpage', $webpage_key);

    $scope_metadata = $webpage->get('Scope Metadata');


    // print_r($scope_metadata['emails']);

    $email_template = get_object('email_template', $scope_metadata['emails']['order_confirmation']['key']);

    $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


    if ($email_template->get('Email Template Subject') == '') {
        $response = array(
            'state'      => 400,
            'msg'        => _('Empty email subject'),
            'error_code' => 'unknown'
        );
        echo json_encode($response);
        exit;
    }


    $sender_email_address = $webpage->get('Send Email Address');

    if ($sender_email_address == '') {
        $response = array(
            'state'      => 400,
            'msg'        => 'Sender email address not configured',
            'error_code' => 'unknown'
        );
        echo json_encode($response);
        exit;
    }


    $client = SesClient::factory(
        array(
            'version'     => 'latest',
            'region'      => 'eu-west-1',
            'credentials' => [
                'key'    => AWS_ACCESS_KEY_ID,
                'secret' => AWS_SECRET_ACCESS_KEY,
            ],
        )
    );


    $placeholders = array(
        '[Greetings]'     => $customer->get_greetings(),
        '[Customer Name]' => $customer->get('Name'),
        '[Name]'          => $customer->get('Customer Main Contact Name'),
        '[Name,Company]'  => preg_replace(
            '/^, /', '', $customer->get('Customer Main Contact Name').($customer->get('Customer Company Name') == '' ? '' : ', '.$customer->get('Customer Company Name'))
        ),
        '[Signature]'     => $webpage->get('Signature'),
        '[Order Number]'  => $order->get('Public ID'),
        '[Order Amount]'  => $order->get('Total'),
        '[Pay Info]'      => get_pay_info($order, $website, $webpage, $smarty),
        '[Order]'         => get_order_info($order),


    );


    $request                                    = array();
    $request['Source']                          = $sender_email_address;
    $request['Destination']['ToAddresses']      = array($order->get('Order Email'));
    $request['Message']['Subject']['Data']      = $published_email_template->get('Published Email Template Subject');
    $request['Message']['Body']['Text']['Data'] = strtr($published_email_template->get('Published Email Template Text'), $placeholders);


    if ($email_template->get('Email Template Type') == 'HTML') {

        $request['Message']['Body']['Html']['Data'] = strtr($published_email_template->get('Published Email Template HTML'), $placeholders);

    }


  

    try {
        $result    = $client->sendEmail($request);
        $messageId = $result->get('MessageId');
        $response  = array(
            'state' => 200


        );


    } catch (Exception $e) {
        // echo("The email was not sent. Error message: ");
        // echo($e->getMessage()."\n");
        $response = array(
            'state'      => 400,
            'msg'        => "Error, email not send",
            'code'       => $e->getMessage(),
            'error_code' => 'unknown'


        );
    }


}


function get_order_info($order) {


    $order_items_info = $order->get_items();


    $order_info = '<table  cellpadding="0">';
    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
	<td style="padding:5px;border-bottom:1px solid #777777">%s</td>
	<td style="padding:5px;border-bottom:1px solid #777777">%s</td>
	<td style="padding:5px;border-bottom:1px solid #777777">%s</td>
	<td style="padding:5px;border-bottom:1px solid #777777">%s</td>
	</tr>', _('Code'), _('Description'), _('Quantity'), _('Amount')

    );

    foreach ($order_items_info as $data) {
        $order_info .= sprintf(
            '<tr style="border-bottom:1px solid #cccccc">
		<td style="padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', $data['code'], $data['description'], $data['qty'], $data['amount']

        );
    }


    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Items Net'), $order->get('Items Net Amount')

    );

    if ($order->get('Order Net Credited Amount') != 0) {
        $order_info .= sprintf(
            '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Credits'), $order->get('Net Credited Amount')

        );
    }

    if ($order->get('Order Charges Net Amount') != 0) {
        $order_info .= sprintf(
            '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Charges'), $order->get('Charges Net Amount')

        );
    }

    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Shipping'), $order->get('Shipping Net Amount')

    );

    if ($order->get('Order Insurance Net Amount') != 0) {
        $order_info .= sprintf(
            '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Insurance'), $order->get('Insurance Net Amount')

        );
    }


    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Net'), $order->get('Balance Net Amount')

    );

    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Tax'), $order->get('Balance Tax Amount')

    );

    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Total'), $order->get('Balance Total Amount')

    );

    if ($order->get('Order Payments Amount') != 0) {

        $order_info .= sprintf(
            '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Paid'), $order->get('Payments Amount')

        );
        $order_info .= sprintf(
            '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('To Pay Amount'), $order->get('To Pay Amount')

        );
    }

    $order_info .= '</table>';

    return;

}


function get_pay_info($order, $website, $webpage, $smarty) {

    $payment_info_for_customer ='';

    $payment_account          = get_object('Payment_Account', $order->get('Order Checkout Block Payment Account Key'));


    switch($payment_account->get('Payment Account Block')){

        //todo add other payment methods

        case 'Bank':

            //todo add here credits

            $content = $webpage->get('Content Data');

            $placeholders = array(

                '[Order Number]' => $order->get('Public ID'),
                '[Order Amount]' => $order->get('Total'),

            );


            if (isset($content['_bank_header'])) {
                $content['_bank_header'] = strtr($content['_bank_header'], $placeholders);
            }
            if (isset($content['_bank_footer'])) {
                $content['_bank_footer'] = strtr($content['_bank_footer'], $placeholders);
            }


            $smarty->assign('content', $content);
            $smarty->assign('labels', $website->get('Localised Labels'));
            $smarty->assign('bank_payment_account', $payment_account);


            $payment_info_for_customer = $smarty->fetch('payment_bank_details.inc.tpl');
            break;




    }








    return $payment_info_for_customer;


}


?>
