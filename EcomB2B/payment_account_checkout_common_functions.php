<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 03 Nov 2021 12:42:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once 'common_web_paying_functions.php';
require_once 'utils/currency_functions.php';
require_once 'utils/placed_order_functions.php';


function process_payment_response($response, $order, $website, $payment_account, $customer, $editor, $smarty, $account, $store, $db): array
{
    $state          = 400;
    $msg            = '';
    $redirect       = '';
    $payment_method = 'Credit Card';


    $card_type = '';
    if (isset($response->source['product_type'])) {
        $card_type = $response->source['product_type'];
    }
    if ($card_type == '' and isset($response->source['scheme'])) {
        $card_type = $response->source['scheme'];
    }

    $date = gmdate('Y-m-d H:i:s');
    if (!empty($response->processed_on)) {
        $date = gmdate('Y-m-d H:i:s', strtotime($response->processed_on));
    } elseif (!empty($response->requested_on)) {
        $date = gmdate('Y-m-d H:i:s', strtotime($response->requested_on));
    }

    $info = '';
    if (isset($response->response_summary)) {
        $info .= $response->response_summary;
    }
    if (isset($response->response_code)) {
        $info .= ' ('.$response->response_code.')';
    }




    $payment_data = array(
        'Payment Store Key'          => $order->get('Order Store Key'),
        'Payment Website Key'        => $website->id,
        'Payment Customer Key'       => $customer->id,
        'Payment Transaction Amount' => $response->amount / 100,
        'Payment Currency Code'      => $response->currency,

        'Payment Sender Card Type' => $card_type,
        'Payment Created Date'     => $date,

        'Payment Last Updated Date'       => $date,
        'Payment Transaction ID'          => $response->id,
        'Payment Method'                  => $payment_method,
        'Payment Location'                => 'Basket',
        'Payment Metadata'                => json_encode($response),
        'Payment Transaction Status Info' => $info

    );

    //print_r($response);

    if ($response->approved) {
        $payment_data['Payment Transaction Status'] = 'Completed';


        $payment = $payment_account->create_payment($payment_data);
        $order->add_payment($payment);
        $credits = floatval($customer->get('Customer Account Balance'));

        if ($credits > 0) {
            $to_pay_credits = min($order->get('Order To Pay Amount'), $credits);
            list($customer, $order, $credit_payment_account, $credit_payment) = pay_credit($order, $to_pay_credits, $editor, $db, $account);
        }


        place_order($store, $order, $payment_account->id, $customer, $website, $editor, $smarty, $account, $db);


        return array(
            'state'     => 200,
            'order_key' => $order->id,

        );
    } else {
        $payment_data['Payment Transaction Status'] = 'Declined';

        if(isset($response->actions)) {
            foreach ($response->actions as $action) {
                if($action['type']=='Authorization'){
                    if (isset($action['response_summary'])) {
                        $info .=$action['response_summary'];
                    }
                    if (isset($action['response_code'])) {
                        $info .= ' ('.$action['response_code'].') ';
                    }
                }
            }
        }


        $msg     = $info;
        $payment = $payment_account->create_payment($payment_data);


        $order->add_payment($payment);
    }


    return array(

        'state'    => $state,
        'msg'      => $msg,
        'redirect' => $redirect

    );
}