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

if (empty($_REQUEST['payment_account_id'])) {
    echo json_encode(
        [
            'status' => 'error 1',
        ]
    );
    exit;
}

if (empty($_REQUEST['order_id'])) {
    echo json_encode(
        [
            'status' => 'error 2',
        ]
    );
    exit;
}

if ($website->get('Website Type') == 'EcomDS') {
    $order = get_object('Order', $_REQUEST['order_id']);

    if (!$order->id or $order->get('Order Customer Key') != $customer->id) {
        $response = array(
            'state' => 200,
            'html'  => '<div style="margin:100px auto;text-align: center">Incorrect order id</div>'

        );
        echo json_encode($response);
    }
}



if ($order->id and $order->id == $_REQUEST['order_id']) {
    $customer = get_object('Customer', $order->get('Order Customer Key'));
    $store    = get_object('Store', $order->get('Order Store Key'));
    $website  = get_object('Website', $store->get('Store Website Key'));

    $payment_account_key = $_REQUEST['payment_account_id'];
    $payment_account     = get_object('Payment_Account', $payment_account_key);
    $token               = get_access_token($payment_account);



    $res=submit_order_to_paypal($token,$order);


    $res=json_decode($res,true);




    echo json_encode(
        [
            'data' => $res
        ]
    );
} else {
    echo json_encode(
        [
            'status' => 'error_2',
        ]
    );
}


function submit_order_to_paypal($token,$order)
{
    $curl = curl_init();
    $uuid = guidv4();

    $base_url = "https://api-m.paypal.com/v2/";
    if (ENVIRONMENT == 'DEVEL') {
        $base_url = "https://api-m.sandbox.paypal.com/v2/";
    }

    $currency = $order->get('Order Currency');


    $to_pay = $order->get('Order To Pay Amount');

    curl_setopt_array($curl, array(
        CURLOPT_URL            => $base_url.'checkout/orders',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_POSTFIELDS     => '{
    "intent": "CAPTURE",
    "purchase_units": [
        {
            "amount": {
                "currency_code": "'.$currency.'",
                "value": "'.$to_pay.'"
            }
        }
    ]
}',
        CURLOPT_HTTPHEADER     => array(
            'Content-Type: application/json',
            'Prefer: return=representation',
            "PayPal-Request-Id: $uuid",
            "Authorization: Bearer $token"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
}

function get_access_token($payment_account)
{
    $x = $payment_account->get('Payment Account Login').':'.$payment_account->get('Payment Account Password');

    $auth = base64_encode($x);



    $res = paypal_api_post_call(
        'oauth2/token',
        [
            'grant_type'                => 'client_credentials',
            'ignoreCache'               => true,
            'return_authn_schemes'      => true,
            'return_client_metadata'    => true,
            'return_unconsented_scopes' => true


        ]
        , $auth
    );


    return $res['access_token'];
}


function paypal_api_post_call($url, $data, $api_key = false, $type = 'POST', $db = false)
{
    $query = http_build_query($data, '', '&');


    $base_url = "https://api-m.paypal.com/v1/";
    if (ENVIRONMENT == 'DEVEL') {
        $base_url = "https://api-m.sandbox.paypal.com/v1/";
    }


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL            => $base_url.$url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_POSTFIELDS     => $query,
        CURLOPT_HTTPHEADER     => array(
            'Content-Type: application/x-www-form-urlencoded',
            "Authorization: Basic $api_key"


        ),
    ));

    $res = curl_exec($curl);


    if ($res === false) {
        Sentry\captureMessage('Curl error: '.curl_error($curl));
        curl_close($curl);

        return false;
    } else {
        curl_close($curl);

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

function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}


