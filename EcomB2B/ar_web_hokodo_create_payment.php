<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 28 Apr 2022 11:29:23 Central European Summer Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */
include_once 'ar_web_common_logged_in.php';
/** @var PDO $db */
/** @var Order $order */



$sql = "update `Order Dimension` set `hokodo_order_id`=?  where `Order Key`=? ";

$db->prepare($sql)->execute(
    [
        $_REQUEST['order_id'],
        $order->id
    ]
);

$customer=get_object('Customer',$order->get('Order Customer Key'));
$store=get_object('Store',$order->get('Order Store Key'));
$website = get_object('Website', $store->get('Store Website Key'));

$amount=$order->get('Order Total Amount');
$date=gmdate('Y-m-d H:i:s');

$editor = array(
    'Author Name'  => $customer->get('Name'),
    'Author Alias' => $customer->get('Name'),
    'Author Type'  => 'Customer',
    'Author Key'   => $customer->id,
    'Subject'      => 'Customer',
    'Subject Key'  => $customer->id,
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$payment_data = array(
    'Payment Store Key'                   => $order->get('Order Store Key'),
    'Payment Website Key'                 => $website->id,
    'Payment Customer Key'                => $customer->id,
    'Payment Transaction Amount'          => $amount,
    'Payment Currency Code'               => $order->get('Order Currency'),
    // 'Payment Sender'                      => trim($result->transaction->customer['firstName'].' '.$result->transaction->customer['lastName']),
    // 'Payment Sender Country 2 Alpha Code' => $result->transaction->billing['countryCodeAlpha2'],
    //  'Payment Sender Email'                => $result->transaction->customer['email'],
    //  'Payment Sender Card Type'            => $result->transaction->creditCard['cardType'],
    'Payment Created Date'                => $date,

    //  'Payment Completed Date'     => $result->transaction->createdAt->format('Y-m-d H:i:s'),
    'Payment Last Updated Date'  => $date,
    'Payment Transaction Status' => 'Pending',
    'Payment Transaction ID'     => $_REQUEST['payment_id'],
    'Payment Method'             => 'Ecredit',
    'Payment Location'           => 'Basket',
    //  'Payment Metadata'           => $payment_metadata

);


$payment_account_key=$website->get_payment_account__key('hokodo');

$payment_account=get_object('Payment_Account',$payment_account_key);
$payment_account->editor=$editor;

$payment = $payment_account->create_payment($payment_data);
$order->add_payment($payment);

$sql = "update `Order Dimension` set `pending_hokodo_payment_id`=?  where `Order Key`=? ";

$db->prepare($sql)->execute(
    [
        $payment->id,
        $order->id
    ]
);

echo json_encode(
    [
        'url'=>$_REQUEST['payment_url']
    ]
);


