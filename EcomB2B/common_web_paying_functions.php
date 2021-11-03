<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 03 Nov 2021 14:16:04 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


/**
 * @param $order   \Public_Order
 * @param $amount
 * @param $editor
 * @param $db      \PDO
 * @param $account \Public_Account
 *
 * @return array
 */
function pay_credit($order, $amount, $editor, $db, $account)
{
    include_once __DIR__.'/utils/currency_functions.php';

    $store    = get_object('store', $order->get('Order Store Key'));
    $customer = get_object('Customer', $order->get('Customer Key'));


    $order->editor = $editor;

    /**
     * @var $payment_account \Public_Payment_Account
     */
    $payment_account = get_object('Payment_Account', $store->get('Store Customer Payment Account Key'));


    $payment_account->editor = $editor;

    $date     = gmdate('Y-m-d H:i:s');
    $exchange = currency_conversion($db, $order->get('Currency Code'), $account->get('Currency Code'));

    $payment_data = array(
        'Payment Store Key'                   => $order->get('Store Key'),
        'Payment Customer Key'                => $order->get('Customer Key'),
        'Payment Transaction Amount'          => $amount,
        'Payment Currency Code'               => $order->get('Currency Code'),
        'Payment Sender'                      => $order->get('Order Invoice Address Recipient'),
        'Payment Sender Country 2 Alpha Code' => $order->get('Order Invoice Address Country 2 Alpha Code'),
        'Payment Sender Email'                => $order->get('Email'),
        'Payment Sender Card Type'            => '',
        'Payment Created Date'                => $date,

        'Payment Completed Date'         => $date,
        'Payment Last Updated Date'      => $date,
        'Payment Transaction Status'     => 'Completed',
        'Payment Transaction ID'         => '',
        'Payment Method'                 => 'Account',
        'Payment Location'               => 'Order',
        'Payment Metadata'               => '',
        'Payment Submit Type'            => 'AutoCredit',
        'Payment Currency Exchange Rate' => $exchange,
        'Payment Type'                   => 'Payment'


    );


    $payment = $payment_account->create_payment($payment_data);


    $sql = sprintf(
        'INSERT INTO `Credit Transaction Fact` 
                    (`Credit Transaction Date`,`Credit Transaction Amount`,`Credit Transaction Currency Code`,`Credit Transaction Currency Exchange Rate`,`Credit Transaction Customer Key`,`Credit Transaction Payment Key`) 
                    VALUES (%s,%.2f,%s,%f,%d,%d) ',
        prepare_mysql($date),
        -$amount,
        prepare_mysql($order->get('Currency Code')),
        $exchange,
        $order->get('Order Customer Key'),
        $payment->id


    );


    $db->exec($sql);
    $reference = $db->lastInsertId();
    if (!$reference) {
        throw new Exception('Error inserting CTF');
    }

    $payment->fast_update(array('Payment Transaction ID' => sprintf('%05d', $reference)));


    $customer->update_account_balance();
    $customer->update_credit_account_running_balances();

    $order->add_payment($payment);
    $order->update_totals();


    return array(
        $customer,
        $order,
        $payment_account,
        $payment
    );
}

function place_order($store, $order, $payment_account_key, $customer, $website, $editor, $smarty, $account, $db)
{
    $order->update(
        array(
            'Order State'                              => 'InProcess',
            'Order Checkout Block Payment Account Key' => $payment_account_key
        ),
        'no_history'
    );


    include_once 'utils/new_fork.php';
    new_housekeeping_fork(
        'au_housekeeping',
        array(
            'type'         => 'order_submitted_by_client',
            'order_key'    => $order->id,
            'customer_key' => $customer->id,

            'editor'      => $editor,
            'website_key' => $website->id,
            'order_info'  => get_pay_info($order, $website, $smarty),
            'pay_info'    => get_order_info($order),

        ),
        $account->get('Account Code')
    );
}