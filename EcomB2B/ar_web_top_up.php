<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  24 March 2020  15:53::50  +0800. Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/
include_once 'ar_web_common_logged_in.php';
require_once 'utils/placed_order_functions.php';
require_once 'utils/aes.php';
require_once 'utils/currency_functions.php';
require_once 'utils/braintree_error_messages.php';


$smarty = new Smarty();
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');

$account = get_object('Account', 1);

$website = get_object('Website', $_SESSION['website_key']);
//print_r($_REQUEST);
//exit;

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

    case 'get_top_up_html':
        $data = prepare_values(
            $_REQUEST, array(
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        get_top_up_html($data, $website, $customer, $smarty);


        break;

    case 'place_order_pay_braintree_paypal':
        $data    = prepare_values(
            $_REQUEST, array(
                         'payment_account_key' => array('type' => 'key'),
                         'amount'              => array('type' => 'string'),
                         'nonce'               => array('type' => 'string'),


                     )
        );
        $website = get_object('Website', $_SESSION['website_key']);


        $store   = get_object('Store', $website->get('Website Store Key'));
        $account = get_object('Account', 1);

        top_up_pay_braintree_paypal($store, $data, $customer, $website, $editor, $smarty, $db, $account);


        break;

    case 'top_up_pay_braintree':
        $data    = prepare_values(
            $_REQUEST, array(
                         'payment_account_key' => array('type' => 'key'),
                         'amount'              => array('type' => 'key'),
                         'data'                => array('type' => 'json array'),

                     )
        );
        $website = get_object('Website', $_SESSION['website_key']);
        $store   = get_object('Store', $website->get('Website Store Key'));
        $account = get_object('Account', 1);
        top_up_pay_braintree($store, $data, $customer, $website, $editor, $db, $account);


        break;

    case 'top_up_pay_braintree_using_saved_card':
        $data    = prepare_values(
            $_REQUEST, array(
                         'payment_account_key' => array('type' => 'key'),
                         'amount'              => array('type' => 'key'),
                         'data'                => array('type' => 'json array'),

                     )
        );
        $website = get_object('Website', $_SESSION['website_key']);
        $store   = get_object('Store', $customer->get('Customer Store Key'));
        $account = get_object('Account', 1);
        top_up_pay_braintree_using_saved_card($store, $data, $customer, $website, $editor, $smarty, $db, $account);


        break;

}

/**
 * @param $store
 * @param $_data
 * @param $customer \Public_Customer
 * @param $website
 * @param $editor
 * @param $db
 * @param $account
 */
function top_up_pay_braintree($store, $_data, $customer, $website, $editor, $db, $account) {


    $top_up = $customer->create_top_up($_data['amount']);

    if ($top_up == false) {
        $response = array(
            'state' => 400,
            'resp'  => $customer->msg
        );
        echo json_encode($response);
        exit;
    }


    $payment_account         = get_object('Payment_Account', $_data['payment_account_key']);
    $payment_account->editor = $editor;

    $gateway = new Braintree_Gateway(
        [
            'environment' => BRAINTREE_ENV,
            'merchantId'  => $payment_account->get('Payment Account ID'),
            'publicKey'   => $payment_account->get('Payment Account Login'),
            'privateKey'  => $payment_account->get('Payment Account Password')
        ]
    );


    $braintree_data = get_top_up_transaction_braintree_data($top_up, $customer, $gateway, $_data['data']['save_card']);


    $braintree_data['amount'] = $top_up->get('Top Up Amount');


    $braintree_data['merchantAccountId']  = $payment_account->get('Payment Account Cart ID');
    $braintree_data['paymentMethodNonce'] = $_data['data']['nonce'];


    $response = process_braintree_top_up($braintree_data, $top_up, $gateway, $customer, $store, $website, $payment_account, $db, $account);


    echo json_encode($response);

}

function top_up_pay_braintree_paypal($store, $_data, $customer, $website, $editor, $smarty, $db, $account) {


    $top_up = $customer->create_top_up($_data['amount']);

    if ($top_up == false) {
        $response = array(
            'state' => 400,
            'resp'  => $customer->msg
        );
        echo json_encode($response);
        exit;
    }

    $payment_account         = get_object('Payment_Account', $_data['payment_account_key']);
    $payment_account->editor = $editor;


    $gateway = new Braintree_Gateway(
        [
            'environment' => BRAINTREE_ENV,
            'merchantId'  => $payment_account->get('Payment Account ID'),
            'publicKey'   => $payment_account->get('Payment Account Login'),
            'privateKey'  => $payment_account->get('Payment Account Password')
        ]
    );


    $braintree_data = get_top_up_transaction_braintree_data($top_up, $customer, $gateway);

    $braintree_data['amount'] = floatval($_data['amount']);

    $braintree_data['merchantAccountId']  = $payment_account->get('Payment Account Cart ID');
    $braintree_data['paymentMethodNonce'] = $_data['nonce'];

    $response = process_braintree_top_up($braintree_data, $top_up, $gateway, $customer, $store, $website, $payment_account, $db, $account);

    echo json_encode($response);

}

/**
 * @param $data
 * @param $website  \Public_Website
 * @param $customer \Public_Customer
 * @param $smarty   \Smarty
 *
 * @throws \SmartyException
 */
function get_top_up_html($data, $website, $customer, $smarty) {


    require_once __DIR__.'/utils/aes.php';


    $store = get_object('Store', $website->get('Website Store Key'));

    $webpage = $website->get_webpage('top_up.sys');

    $content     = $webpage->get('Content Data');
    $block       = array();
    $block_found = false;
    $block_key   = false;
    foreach ($content['blocks'] as $_block_key => $_block) {
        if ($_block['type'] == 'top_up') {
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


    $smarty->assign('customer', $customer);
    $smarty->assign('website', $website);
    $smarty->assign('store', $store);

    $smarty->assign('key', $block_key);
    $smarty->assign('data', $block);
    $smarty->assign('labels', $website->get('Localised Labels'));

    $smarty->assign('checkout_labels', $block['labels']);


    $response = array(
        'state' => 200,
        'customer_balance'=>$customer->get('Account Balance'),
        'html'  => $smarty->fetch('theme_1/blk.top_up.theme_1.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),
    );


    echo json_encode($response);

}


function top_up_pay_braintree_using_saved_card($store, $_data, $customer, $website, $editor, $smarty, $db, $account) {

    $top_up = $customer->create_top_up($_data['amount']);

    if ($top_up == false) {
        $response = array(
            'state' => 400,
            'resp'  => $customer->msg
        );
        echo json_encode($response);
        exit;
    }


    $payment_account         = get_object('Payment_Account', $_data['payment_account_key']);
    $payment_account->editor = $editor;


    $gateway = new Braintree_Gateway(
        [
            'environment' => BRAINTREE_ENV,
            'merchantId'  => $payment_account->get('Payment Account ID'),
            'publicKey'   => $payment_account->get('Payment Account Login'),
            'privateKey'  => $payment_account->get('Payment Account Password')
        ]
    );


    include_once 'utils/aes.php';


    try {

        $token_data = json_decode(AESDecryptCtr($_data['data']['token'], md5('CCToken'.CKEY), 256), true);
        $token      = $token_data['t'];


        $verification_result = $gateway->paymentMethod()->update(
            $token, [
                      'paymentMethodNonce' => $_data['data']['nonce'],
                      'options'            => ['verifyCard' => true]
                  ]
        );
    } catch (Exception $e) {


        $msg = _('There was a problem with your saved card').'<br>'.sprintf(_('click in %s and provide the credit card details again'), '<b>'._('Pay with other card').'</b>');

        $response = array(
            'state' => 400,
            'msg'   => $msg

        );
        echo json_encode($response);
        exit;

    }


    if ($verification_result->success) {


        $braintree_data = get_top_up_transaction_braintree_data($top_up, $customer, $gateway);


        $braintree_data['amount']             = $top_up->get('Top Up Amount');
        $braintree_data['paymentMethodToken'] = $token;
        $braintree_data['merchantAccountId']  = $payment_account->get('Payment Account Cart ID');

        $response = process_braintree_top_up($braintree_data, $top_up, $gateway, $customer, $store, $website, $payment_account, $db, $account);

        echo json_encode($response);

    } else {


        $response = array(
            'state' => 400,
            'msg'   => _('Card verification value (CVV) failed')

        );

        echo json_encode($response);


    }


}


/**
 * @param      $top_up   \Public_Top_Up
 * @param      $customer \Public_Customer
 * @param      $gateway
 * @param bool $save_payment
 *
 * @return array
 */
function get_top_up_transaction_braintree_data($top_up, $customer, $gateway, $save_payment = false) {


    include_once 'external_libs/contact_name_parser.php';


    $braintree_customer = false;
    if ($save_payment) {

        try {
            $braintree_customer = $gateway->customer()->find($customer->id);

        } catch (Exception $e) {

        }


    }


    $parser = new FullNameParser();


    $billing_contact_name  = $parser->parse_name($customer->get('Customer Main Contact Name'));
    $delivery_contact_name = $parser->parse_name($customer->get('Customer Main Contact Name'));

    $braintree_data = [

        'amount'   => $top_up->get('Top Up Amount'),
        'orderId'  => 'TopUp_'.$top_up->id,
        'customer' => [

            'firstName' => $billing_contact_name['fname'],
            'lastName'  => $billing_contact_name['lname'],
            'company'   => $customer->get('Customer Company Name'),
            'email'     => $customer->get('Customer Main Plain Email')
        ],

        'billing'  => [
            'firstName'         => $billing_contact_name['fname'],
            'lastName'          => $billing_contact_name['lname'],
            'company'           => $customer->get('Customer Company Name'),
            'streetAddress'     => $customer->get('Customer Invoice Address Line 1'),
            'extendedAddress'   => $customer->get('Customer Invoice Address Line 2'),
            'locality'          => $customer->get('Customer Invoice Address Locality'),
            'region'            => $customer->get('Customer Invoice Address Administrative Area'),
            'postalCode'        => $customer->get('Customer Invoice Address Postal Code'),
            'countryCodeAlpha2' => $customer->get('Customer Invoice Address Country 2 Alpha Code'),
        ],
        'shipping' => [
            'firstName'         => $delivery_contact_name['fname'],
            'lastName'          => $delivery_contact_name['lname'],
            'company'           => $customer->get('Customer Company Name'),
            'streetAddress'     => $customer->get('Customer Invoice Address Line 1'),
            'extendedAddress'   => $customer->get('Customer Invoice Address Line 2'),
            'locality'          => $customer->get('Customer Invoice Address Locality'),
            'region'            => $customer->get('Customer Invoice Address Administrative Area'),
            'postalCode'        => $customer->get('Customer Invoice Address Postal Code'),
            'countryCodeAlpha2' => $customer->get('Customer Invoice Address Country 2 Alpha Code'),
        ],


        'options' => [
            'submitForSettlement' => true,


        ]
    ];

    if ($save_payment) {
        $braintree_data['options']['storeInVaultOnSuccess']       = true;
        $braintree_data['options']['storeShippingAddressInVault'] = true;

        if ($braintree_customer) {
            $braintree_data['customerId'] = $customer->id;
        } else {
            $braintree_data['customer']['id'] = $customer->id;
        }

    }


    return $braintree_data;
}

/**
 * @param $braintree_data
 * @param $top_up
 * @param $gateway
 * @param $customer
 * @param $store
 * @param $website
 * @param $payment_account
 * @param $db \PDO
 * @param $account
 *
 * @return array
 */
function process_braintree_top_up($braintree_data, $top_up, $gateway, $customer, $store, $website, $payment_account, $db, $account) {


    try {


        $result = $gateway->transaction()->sale($braintree_data);


        if ($result->success) {


            switch ($result->transaction->paymentInstrumentType) {
                case 'credit_card':

                    $payment_method = 'Credit Card';

                    break;
                case 'paypal_account':

                    $payment_method = 'Paypal';
                    break;

                default:
                    $payment_method = 'Other';
                    break;
            }


            $payment_metadata = '';


            $payment_data = array(
                'Payment Store Key'                   => $top_up->get('Top Up Store Key'),
                'Payment Website Key'                 => $website->id,
                'Payment Customer Key'                => $customer->id,
                'Payment Transaction Amount'          => $result->transaction->amount,
                'Payment Currency Code'               => $result->transaction->currencyIsoCode,
                'Payment Sender'                      => trim($result->transaction->customer['firstName'].' '.$result->transaction->customer['lastName']),
                'Payment Sender Country 2 Alpha Code' => $result->transaction->billing['countryCodeAlpha2'],
                'Payment Sender Email'                => $result->transaction->customer['email'],
                'Payment Sender Card Type'            => $result->transaction->creditCard['cardType'],
                'Payment Created Date'                => $result->transaction->createdAt->format('Y-m-d H:i:s'),

                'Payment Completed Date'     => $result->transaction->createdAt->format('Y-m-d H:i:s'),
                'Payment Last Updated Date'  => $result->transaction->updatedAt->format('Y-m-d H:i:s'),
                'Payment Transaction Status' => 'Completed',
                'Payment Transaction ID'     => $result->transaction->id,
                'Payment Method'             => $payment_method,
                'Payment Location'           => 'Top Up',
                'Payment Metadata'           => $payment_metadata

            );


            $payment = $payment_account->create_payment($payment_data);


            $exchange = currency_conversion(
                $db, $store->get('Store Currency Code'), $account->get('Account Currency Code'), '- 1440 minutes'
            );
            $top_up->fast_update(
                [
                    'Top Up Payment Key' => $payment->id,
                    'Top Up Status'      => 'Paid',
                    'Top Up Exchange'    => $exchange,
                    'Top Up Amount'      => $result->transaction->amount
                ]
            );


            include_once "utils/currency_functions.php";


            $date = gmdate('Y-m-d H:i:s');


            $sql = "INSERT INTO `Credit Transaction Fact` 
                    (`Credit Transaction Date`,`Credit Transaction Amount`,`Credit Transaction Currency Code`,`Credit Transaction Currency Exchange Rate`,`Credit Transaction Customer Key`,
                     `Credit Transaction Payment Key`,`Credit Transaction Top Up Key`,
                     `Credit Transaction Type`) 
                    VALUES (?,?,?,?,?,?,?,?) ";


            $db->prepare($sql)->execute(
                array(
                    $date,
                    $top_up->get('Top Up Amount'),
                    $top_up->get('Top Up Currency Code'),
                    $exchange,
                    $customer->id,
                    $payment->id,
                    $top_up->id,
                    'TopUp'

                )
            );


            $credit_key = $db->lastInsertId();


            $history_data = array(
                'History Abstract' => sprintf(
                    _('Customer top up %s'), money($top_up->get('Top Up Amount'), $top_up->get('Store Currency Code'))
                ),
                'History Details'  => '',
                'Action'           => 'edited'
            );

            $history_key = $customer->add_subject_history(
                $history_data, true, 'No', 'Changes', $customer->get_object_name(), $customer->id
            );

            $sql = "INSERT INTO `Credit Transaction History Bridge` 
                    (`Credit Transaction History Credit Transaction Key`,`Credit Transaction History History Key`) 
                    VALUES (?,?) ";
            $db->prepare($sql)->execute(
                array(
                    $credit_key,
                    $history_key
                )
            );


            $customer->update_account_balance();
            $customer->update_credit_account_running_balances();


            $response = array(
                'state'      => 200,
                'top_up_key' => $top_up->id
            );

            return $response;


        } else {


            $error_messages         = array();
            $error_private_messages = array();

            foreach ($result->errors->deepAll() as $error) {


                $_msg = get_error_message($error->code, $error->message);

                if (!(in_array(
                    $error->code, array(
                                    '81714',
                                    '81715',
                                    '81716',
                                    '81736',
                                    '81737',
                                )
                ))) {
                    $_msg .= ' ('.$error->code.')';
                }


                $error_messages[$_msg]    = $_msg;
                $error_private_messages[] = $error->message.' ('.$error->code.')';

            }

            if (count($error_messages) > 0) {


                $private_message = join(', ', $error_private_messages);
            } else {


                if ($result->transaction->status == 'processor_declined') {

                    $private_message = $result->transaction->processorResponseText.' ('.$result->transaction->processorResponseCode.')';
                } elseif ($result->transaction->status == 'settlement_declined') {
                    $private_message = $result->processorSettlementResponseText->processorResponseText.' ('.$result->transaction->processorSettlementResponseCode.')';
                } elseif ($result->transaction->status == 'gateway_rejected') {
                    $private_message = 'Rejected by gateway due to: '.$result->transaction->gatewayRejectionReason;
                } else {
                    $private_message = $result->message;
                }

            }

            $msg = _('There was a problem processing your credit card; please double check your payment information and try again').'. ('.$result->transaction->id.')';


            $payment_metadata = '';


            if (!empty($result->transaction)) {


                switch ($result->transaction->paymentInstrumentType) {
                    case 'credit_card':
                        $payment_method = 'Credit Card';
                        break;
                    case 'paypal_account':
                        $payment_method = 'Paypal';
                        break;
                    default:
                        $payment_method = 'Other';
                        break;
                }

                $payment_data = array(
                    'Payment Store Key'                   => $top_up->get('Top Up Store Key'),
                    'Payment Website Key'                 => $website->id,
                    'Payment Customer Key'                => $customer->id,
                    'Payment Transaction Amount'          => $result->transaction->amount,
                    'Payment Currency Code'               => $result->transaction->currencyIsoCode,
                    'Payment Sender'                      => trim($result->transaction->customer['firstName'].' '.$result->transaction->customer['lastName']),
                    'Payment Sender Country 2 Alpha Code' => $result->transaction->billing['countryCodeAlpha2'],
                    'Payment Sender Email'                => $result->transaction->customer['email'],
                    'Payment Sender Card Type'            => $result->transaction->creditCard['cardType'],
                    'Payment Created Date'                => $result->transaction->createdAt->format('Y-m-d H:i:s'),

                    'Payment Last Updated Date'       => $result->transaction->updatedAt->format('Y-m-d H:i:s'),
                    'Payment Transaction Status'      => 'Declined',
                    'Payment Transaction ID'          => $result->transaction->id,
                    'Payment Method'                  => $payment_method,
                    'Payment Location'                => 'Basket',
                    'Payment Metadata'                => $payment_metadata,
                    'Payment Transaction Status Info' => $private_message

                );


                $payment = $payment_account->create_payment($payment_data);
                $top_up->fast_update(
                    [
                        'Top Up Payment Key' => $payment->id,
                        'Top Up Status'      => 'Error',

                    ]
                );


            }

            $response = array(
                'state' => 400,
                'msg'   => $msg
                // 'private_message'=>$private_message,
                // 'transaction_id'=>$transaction_id

            );

            return $response;


        }


    } catch (Exception $e) {


        $top_up->fast_update(
            [
                'Top Up Status' => 'Error',

            ]
        );


        $msg = _('There was a problem processing your credit card; please double check your payment information and try again');

        $response = array(

            'state' => 400,
            'msg'   => $msg

        );


        return $response;

    }


}



