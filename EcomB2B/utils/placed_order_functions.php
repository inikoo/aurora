<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 August 2017 at 16:43:01 CEST, Trnava, Slavakia
 Copyright (c) 2017, Inikoo

 Version 3

*/

use Aws\Ses\SesClient;


function send_order_confirmation_email($store, $website, $customer, $order, $smarty,$account,$db) {
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
        '[Pay Info]'      => get_pay_info($order, $website, $smarty),
        '[Order]'         => get_order_info($order),


    );


    $from_name = base64_encode($store->get('Store Name'));
    $_source   = "=?utf-8?B?$from_name?= <$sender_email_address>";

    $request = array();
    // $request['Source']                          = sprintf('%s <%s>',$store->get('Store Name'), $sender_email_address) ;
    $request['Source']                          = $_source;
    $request['Destination']['ToAddresses']      = array($order->get('Order Email'));
    $request['Message']['Subject']['Data']      = $published_email_template->get('Published Email Template Subject');
    $request['Message']['Body']['Text']['Data'] = strtr($published_email_template->get('Published Email Template Text'), $placeholders);
    $request['ConfigurationSetName']            = $account->get('Account Code');

    if ($email_template->get('Email Template Type') == 'HTML') {

        $request['Message']['Body']['Html']['Data'] = strtr($published_email_template->get('Published Email Template HTML'), $placeholders);

    }


    $sql = sprintf(
        'insert into `Email Tracking Dimension` (
              `Email Tracking Scope`,`Email Tracking Scope Key`,
              `Email Tracking Email Template Key`,`Email Tracking Published Email Template Key`,
              `Email Tracking Recipient`,`Email Tracking Recipient Key`,`Email Tracking Created Date`) values (
                    %s,%d,
                    %d,%d,
                    %s,%s,%s)', prepare_mysql('Order Confirmation'), $website->id, $email_template->id, $published_email_template->id, prepare_mysql('Customer'), $customer->id, prepare_mysql(gmdate('Y-m-d H:i:s'))


    );
    $db->exec($sql);
    $email_tracking_key = $db->lastInsertId();




    try {
        $result    = $client->sendEmail($request);
        $messageId = $result->get('MessageId');


        $sql = sprintf(
            'update `Email Tracking Dimension` set `Email Tracking State`="Send to SES" , `Email Tracking SES Id`=%s   where `Email Tracking Key`=%d ', prepare_mysql($messageId), $email_tracking_key
        );
        $db->exec($sql);




    } catch (Exception $e) {

        $sql = sprintf(
            'update `Email Tracking Dimension` set `Email Tracking State`="Error"   where `Email Tracking Key`=%d ', $email_tracking_key
        );
        $db->exec($sql);


        $sql = sprintf(
            'insert into `Email Tracking Event Dimension` (
              `Email Tracking Event Tracking Key`,`Email Tracking Event Type`,
              `Email Tracking Event Date`,`Email Tracking Event Data`
     ) values (
                    %d,%s,%s,%s)', $email_tracking_key, prepare_mysql('Send to SES Error'), prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql(json_encode(array('error'=>$e->getMessage())))


        );
        $db->exec($sql);





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
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', strip_tags($data['code']), $data['description'], $data['qty'], $data['amount']

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
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Net'), $order->get('Total Net Amount')

    );

    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Tax'), $order->get('Total Tax Amount')

    );

    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Total'), $order->get('Total')

    );

    if ($order->get('Order Payments Amount') != 0) {

        $order_info .= sprintf(
            '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Amount paid'), $order->get('Payments Amount')

        );
    }

    if ($order->get('Order To Pay Amount') != 0) {

        $order_info .= sprintf(
            '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('To Pay Amount'), $order->get('To Pay Amount')

        );
    }

    $order_info .= '</table>';

    return $order_info;

}


function get_pay_info($order, $website, $smarty) {

    $payment_info_for_customer = '';

    $payment_account = get_object('Payment_Account', $order->get('Order Checkout Block Payment Account Key'));


    switch ($payment_account->get('Payment Account Block')) {

        //todo add other payment methods

        case 'Bank':

            //todo add here credits


            $webpage_key = $website->get_system_webpage_key('checkout.sys');


            $webpage = get_object('webpage', $webpage_key);


            $content = $webpage->get('Content Data');


            $block_found = false;
            $block_key   = false;
            foreach ($content['blocks'] as $_block_key => $_block) {
                if ($_block['type'] == 'checkout') {
                    $block       = $_block;
                    $block_key   = $_block_key;
                    $block_found = true;
                    break;
                }
            }

            if (!$block_found) {
                $response = array(
                    'state' => 200,
                    'html'  => '',
                    'msg'   => 'no checkout in webpage'
                );
                echo json_encode($response);
                exit;
            }


            $placeholders = array(

                '[Order Number]' => $order->get('Public ID'),
                '[Order Amount]' => $order->get('To Pay Amount'),

            );




            if (isset($block['labels']['_bank_header'])) {
                $content['_bank_header'] = strtr($block['labels']['_bank_header'], $placeholders);
            }
            if (isset($block['labels']['_bank_footer'])) {
                $content['_bank_footer'] = strtr($block['labels']['_bank_footer'], $placeholders);
            }


            $smarty->assign('content', $block['labels']);
            $smarty->assign('labels', $website->get('Localised Labels'));
            $smarty->assign('bank_payment_account', $payment_account);


            $payment_info_for_customer = $smarty->fetch('payment_bank_details.inc.tpl');


            $payment_info_for_customer=strtr($payment_info_for_customer, $placeholders);

            break;


    }






    return $payment_info_for_customer;


}


?>
