<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 August 2017 at 16:43:01 CEST, Trnava, Slavakia
 Copyright (c) 2017, Inikoo

 Version 3

*/



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


function get_pay_info($order, $website, $smarty) {

    $payment_info_for_customer ='';

    $payment_account          = get_object('Payment_Account', $order->get('Order Checkout Block Payment Account Key'));


    switch($payment_account->get('Payment Account Block')){

        //todo add other payment methods

        case 'Bank':

            //todo add here credits


$webpage_key = $website->get_system_webpage_key('checkout.sys');


    $webpage = get_object('webpage', $webpage_key);


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
