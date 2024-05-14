<?php

/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 28 Apr 2022 11:29:23 Central European Summer Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */
include_once 'ar_web_common_logged_in.php';
require_once 'utils/sentry.php';
require_once 'utils/object_functions.php';
require_once 'utils/general_functions.php';
require_once 'common_web_paying_functions.php';
require_once 'utils/placed_order_functions.php';
require_once 'utils/natural_language.php';


/** @var PDO $db */
/** @var Order $order */

if (empty($_REQUEST['order_id'])) {
    echo json_encode(
        [
            'status' => 'error 1',
        ]
    );
    exit;
}


if ($order->id and $order->id == $_REQUEST['order_id']) {
    $customer = get_object('Customer', $order->get('Order Customer Key'));
    $store    = get_object('Store', $order->get('Order Store Key'));
    $website  = get_object('Website', $store->get('Store Website Key'));

    $payment_account_key = $website->get_payment_account__key('Pastpay');
    $payment_account     = get_object('Payment_Account', $payment_account_key);


    $amount = $order->get('Order To Pay Amount');
    $date   = gmdate('Y-m-d H:i:s');

    $api_key = $payment_account->get('Payment Account Password');


    $website_url = 'https://'.$website->get('Website URL');
    if (ENVIRONMENT == 'DEVEL') {
        $website_url = 'https://9678-62-30-84-183.ngrok-free.app';
    }


    if($order->get('Order Invoice Address Country 2 Alpha Code')=='GB'){

        $tax_number='GB'.$customer->get('Customer Registration Number');
    }else{

        $tax_number=$customer->get('Customer Tax Number');
        $tax_number=preg_replace('/^(PL|HU)/i','',$tax_number);
    }




    //===



    $settings=json_decode($payment_account->get('Payment Account Settings'),true);
    $currency=$order->get('Order Currency');

    $plans=$settings[$currency];




    $_charge=$plans[$_REQUEST['term']]['charge'];
    $_charge_amount=round($_charge*$amount,2);

    $amount=$amount+$_charge_amount;


    $pay_past_data=[
        'term'=>$_REQUEST['term']
    ];


    $order->fast_update(
        ['Order Pastpay Data'=>json_encode($pay_past_data)]);







    $data = [
        'debtorTaxNumber'    => $tax_number,
        'orderId'            => $order->get('Order Public ID'),
        'totalPrice'         => [
            'amount'   => $amount,
            'currency' => $order->get('Order Currency'),
        ],
        'paymentRedirectUrl' => [
            'success' => $website_url.'/ar_web_pastpay_success.php?id='.$order->id,
            'failure' => $website_url.'/ar_web_pastpay_failure.php?id='.$order->id,
        ],
    ];



    $res = pastpay_api_post_call('/store/order', $data, $api_key);



    if (!empty($res['data']['redirectUrl'])) {
        echo json_encode(
            [
                'status'   => 'ok',
                'redirect' => $res['data']['redirectUrl']
            ]
        );
    } else {
        echo json_encode(
            [
                'status' => 'error_3',
                'msg'    => $res
            ]
        );
    }
} else {
    echo json_encode(
        [
            'status' => 'error_2',
        ]
    );
}


function pastpay_api_post_call($url, $data, $api_key = false, $type = 'POST', $db = false)
{
    $ch = curl_init();

    $base_url = 'https://api.pastpay.com';
    if (ENVIRONMENT == 'DEVEL') {
        $base_url = 'https://api.demo.pastpay.com';
    }

    curl_setopt($ch, CURLOPT_URL, $base_url.$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($type == 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
    } else {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
    }
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $headers   = array();
    $headers[] = 'Content-Type: application/json';
    if ($api_key) {
        $headers[] = "X-Api-Key: $api_key";
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $res = curl_exec($ch);



    if ($res === false) {
        Sentry\captureMessage('Curl error: '.curl_error($ch));
        curl_close($ch);

        return false;
    } else {
        curl_close($ch);

        if ($db) {
            $sql = "insert into hokodo_debug (`request`,`data`,`response`,`date`,`status`) values (?,?,?,?,?) ";
            $db->prepare($sql)->execute(
                [
                    $url,
                    json_encode($data),
                    $res,
                    gmdate('Y-m-d H:i:s'),
                    'ok'

                ]
            );
        }

        return json_decode($res, true);
    }
}