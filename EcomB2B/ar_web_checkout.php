<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 July 2017 at 16:44:58 CEST, Trnava, Slavakia
 Copyright (c) 2017, Inikoo

 Version 3

*/

//use Aws\Ses\SesClient;

include_once 'ar_web_common_logged_in.php';
require_once 'utils/placed_order_functions.php';
require_once 'utils/aes.php';

require_once 'external_libs/Smarty/Smarty.class.php';


$smarty               = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir  = 'server_files/smarty/templates_c';
$smarty->cache_dir    = 'server_files/smarty/cache';
$smarty->config_dir   = 'server_files/smarty/configs';


$account = get_object('Account', 1);


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
    case 'get_checkout_html':
        $data = prepare_values(
            $_REQUEST, array(
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        get_checkout_html($data, $customer, $smarty);


        break;
    case 'place_order_pay_later':
        $data = prepare_values(
            $_REQUEST, array(
                         'payment_account_key' => array('type' => 'key'),
                         'order_key'           => array('type' => 'key')

                     )
        );


        $to_pay = $order->get('Order To Pay Amount');

        $payment_account_key = $data['payment_account_key'];

        $credits = $customer->get('Customer Account Balance');
        if ($credits > 0) {

            if ($to_pay < $credits) {
                $to_pay_credits = $to_pay;

                list($customer, $order, $credit_payment_account, $credit_payment) = pay_credit($order, $to_pay_credits, $editor, $db, $account);


                $payment_account_key = $credit_payment_account->id;


            } else {
                $to_pay_credits = $credits;
                list($customer, $order, $credit_payment_account, $credit_payment) = pay_credit($order, $to_pay_credits, $editor, $db, $account);

            }


        }

        $website = get_object('Website', $_SESSION['website_key']);
        $store   = get_object('Store', $order->get('Order Store Key'));

        place_order($store, $order, $payment_account_key, $customer, $website, $editor, $smarty, $account, $db);


        break;

    case 'place_order_pay_braintree_paypal':
        $data    = prepare_values(
            $_REQUEST, array(
                         'payment_account_key' => array('type' => 'key'),
                         'order_key'           => array('type' => 'key'),
                         'amount'              => array('type' => 'string'),
                         'currency'            => array('type' => 'string'),
                         'nonce'               => array('type' => 'string'),


                     )
        );
        $website = get_object('Website', $_SESSION['website_key']);
        $store   = get_object('Store', $order->get('Order Store Key'));
        $account = get_object('Account', 1);

        place_order_pay_braintree_paypal($store, $data, $order, $customer, $website, $editor, $smarty, $db, $account);


        break;

    case 'place_order_pay_braintree':
        $data    = prepare_values(
            $_REQUEST, array(
                         'payment_account_key' => array('type' => 'key'),

                         'data' => array('type' => 'json array'),

                     )
        );
        $website = get_object('Website', $_SESSION['website_key']);
        $store   = get_object('Store', $order->get('Order Store Key'));
        $account = get_object('Account', 1);
        place_order_pay_braintree($store, $data, $order, $customer, $website, $editor, $smarty, $db, $account);


        break;
}

function place_order_pay_braintree($store, $_data, $order, $customer, $website, $editor, $smarty, $db, $account) {


    require_once 'external_libs/braintree-php-3.2.0/lib/Braintree.php';

    include_once 'external_libs/contact_name_parser.php';

    $payment_account         = get_object('Payment_Account', $_data['payment_account_key']);
    $payment_account->editor = $editor;


    if (empty($_data['data']['nonce']) and isset($_REQUEST['data']['card_id']) and isset($_REQUEST['data']['cvv'])) {

        $nonce        = false;
        $save_payment = false;
        $token        = $customer->get_credit_card_token($_data['data']['card_id'], $order->get('Order Delivery Address Checksum'), $order->get('Order Invoice Address Checksum'));


    } else {

        $nonce        = $_data['data']['nonce'];
        $save_payment = $_data['data']['save_card'];
        $token        = '';
    }


    $parser = new FullNameParser();


    $billing_contact_name  = $parser->parse_name($order->get('Order Invoice Address Recipient'));
    $delivery_contact_name = $parser->parse_name($order->get('Order Delivery Address Recipient'));


    $braintree_customer = false;
    if ($save_payment) {

        try {
            $braintree_customer = Braintree_Customer::find($customer->id);
            //print_r($braintree_customer);
        } catch (Exception $e) {
            //echo 'Message: ' .$e->getMessage();
        }


    }


    Braintree_Configuration::environment('production');
    Braintree_Configuration::merchantId($payment_account->get('Payment Account ID'));
    Braintree_Configuration::publicKey($payment_account->get('Payment Account Login'));
    Braintree_Configuration::privateKey($payment_account->get('Payment Account Password'));


    $to_pay  = $order->get('Order To Pay Amount');
    $credits = $customer->get('Customer Account Balance');
    if ($credits > 0) {

        if ($to_pay < $credits) {
            $to_pay_credits = $to_pay;

            list($customer, $order, $credit_payment_account, $credit_payment) = pay_credit($order, $to_pay_credits, $editor, $db, $account);

            place_order($store, $order, $credit_payment_account->id, $customer, $website, $editor, $smarty, $account, $db);
            exit;

        } else {
            $to_pay_credits = $credits;
            list($customer, $order, $credit_payment_account, $credit_payment) = pay_credit($order, $to_pay_credits, $editor, $db, $account);

        }


    }


    if ($order->get('Order To Pay Amount') > 0) {
        $braintree_data = [
            'merchantAccountId' => $payment_account->get('Payment Account Cart ID'),
            'amount'            => $order->get('Order To Pay Amount'),
            'orderId'           => $order->get('Order Public ID'),
            'customer'          => [

                'firstName' => $billing_contact_name['fname'],
                'lastName'  => $billing_contact_name['lname'],
                'company'   => $order->get('Order Invoice Address Organization'),
                'email'     => $order->get('Order Email')
            ],

            'billing'  => [
                'firstName'         => $billing_contact_name['fname'],
                'lastName'          => $billing_contact_name['lname'],
                'company'           => $order->get('Order Invoice Address Organization'),
                'streetAddress'     => $order->get('Order Invoice Address Line 1'),
                'extendedAddress'   => $order->get('Order Invoice Address Line 2'),
                'locality'          => $order->get('Order Invoice Address Locality'),
                'region'            => $order->get('Order Invoice Address  Administrative Area'),
                'postalCode'        => $order->get('Order Invoice Address Postal Code'),
                'countryCodeAlpha2' => $order->get('Order Invoice Address Country 2 Alpha Code'),
            ],
            'shipping' => [
                'firstName'         => $delivery_contact_name['fname'],
                'lastName'          => $delivery_contact_name['lname'],
                'company'           => $order->get('Order Delivery Address Organization'),
                'streetAddress'     => $order->get('Order Delivery Address Line 1'),
                'extendedAddress'   => $order->get('Order Delivery Address Line 2'),
                'locality'          => $order->get('Order Delivery Address Locality'),
                'region'            => $order->get('Order Delivery Address  Administrative Area'),
                'postalCode'        => $order->get('Order Delivery Address Postal Code'),
                'countryCodeAlpha2' => $order->get('Order Delivery Address Country 2 Alpha Code'),
            ],


            'options' => [
                'submitForSettlement'   => true,
                'storeInVaultOnSuccess' => $save_payment,
            ]
        ];


        if ($save_payment and !$braintree_customer) {
            $braintree_data['customer']['id'] = $customer->id;
        }


        if ($nonce) {
            $braintree_data['paymentMethodNonce'] = $nonce;

        } else {
            $braintree_data['paymentMethodToken'] = $token;


        }


        try {
            $result = Braintree_Transaction::sale($braintree_data);


            // print_r($result);


            if ($result->success) {


                switch ($result->transaction->paymentInstrumentType) {
                    case 'credit_card':

                        $payment_method   = 'Credit Card';
                        $payment_metadata = AESEncryptCtr(json_encode($result->transaction->creditCard), md5('Payment'.CKEY), 256);

                        break;
                    case 'paypal_account':

                        $payment_method = 'Paypal';
                        break;

                    default:
                        $payment_method = 'Other';
                        break;
                }


                //print_r($result);
                include_once 'utils/aes.php';


                $payment_metadata = '';


                $payment_data = array(
                    'Payment Store Key'                   => $order->get('Order Store Key'),
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
                    'Payment Location'           => 'Basket',
                    'Payment Metadata'           => $payment_metadata

                );


                $payment = $payment_account->create_payment($payment_data);


                if ($result->transaction->creditCard['token'] != '' and $save_payment) {
                    $customer->save_credit_card('Braintree', $result->transaction->creditCard, $order->get('Order Delivery Address Checksum'), $order->get('Order Invoice Address Checksum'));
                }

                //  print_r($payment);

                $order->add_payment($payment);


                place_order($store, $order, $payment_account->id, $customer, $website, $editor, $smarty, $account, $db);


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

                    $msg = '<ul>';
                    foreach ($error_messages as $error_message) {
                        $msg .= '<li>'.$error_message.'</li>';
                    }
                    $msg .= '</ul>';

                    $private_message = join(', ', $error_private_messages);
                } else {


                    $msg = _('There was a problem processing your credit card; please double check your payment information and try again').'. ('.$result->transaction->id.')';

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


                $payment_metadata = '';


                if (isset($result->transaction) and $result->transaction != '') {


                    $payment_data = array(
                        'Payment Store Key'                   => $order->get('Order Store Key'),
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


                    $order->add_payment($payment);
                }

                $response = array(
                    'state' => 400,
                    'msg'   => $msg
                    // 'private_message'=>$private_message,
                    // 'transaction_id'=>$transaction_id

                );
                echo json_encode($response);


            }


        } catch (Exception $e) {


            $msg = _('There was a problem processing your credit card; please double check your payment information and try again').'.';

            $response = array(

                'state' => 400,
                'msg'   => $msg

            );
            echo json_encode($response);
            exit;

            //echo 'Message: ' .$e->getMessage();
        }
    }


}


function place_order($store, $order, $payment_account_key, $customer, $website, $editor, $smarty, $account, $db) {


    $order->update(
        array(
            'Order State'                              => 'InProcess',
            'Order Checkout Block Payment Account Key' => $payment_account_key
        ), 'no_history'
    );


    include_once 'utils/new_fork.php';
    new_housekeeping_fork(
        'au_housekeeping', array(
        'type'         => 'order_submitted_by_client',
        'order_key'    => $order->id,
        'customer_key' => $customer->id,

        'editor'      => $editor,
        'website_key' => $website->id,
        'order_info'  => get_pay_info($order, $website, $smarty),
        'pay_info'    => get_order_info($order),

    ), $account->get('Account Code')
    );


    $response = array(
        'state'     => 200,
        'order_key' => $order->id,

    );


    echo json_encode($response);

}


function get_error_message($code, $original_message) {

    switch ($code) {
        case ('81528'):
            $msg = _('Sorry, but the amount is too large').'.';
            break;
        case ('81509'):
        case ('91517'):
        case ('91734'):
            $msg = _("Sorry, we don't accept this credit card type").'.';
            break;
        case ('91577'):
            $msg = _("Sorry, we don't support this payment instrument").'.';
            break;
        case ('91518'):
            $msg = _("There was a problem processing your credit card, please provide your payment information again").'.';
            break;
        case ('92202'):
            $msg = _("Phone number is invalid").'.';
            break;

        case ('81706'):
            $msg = _("CVV is required").'.';
            break;
        case ('81707'):
            $msg = _("CVV must be 3 or 4 digits").'.';
            break;
        case ('81709'):
            $msg = _("Expiration date is required").'.';
            break;
        case ('81710'):
        case ('81711'):
        case ('81712'):
        case ('81713'):
            $msg = _("Expiration date is invalid").'.';
            break;
        case ('81706'):
            $msg = _("CVV is required").'.';
            break;

        case ('81714'):
        case ('81715'):
        case ('81716'):
        case ('81737'):
        case ('81736'):
            $msg = _("There was a problem processing your credit card, please double check your payment information and try again").'.';
            break;

        default:
            $msg = $original_message;
    }

    return $msg;
}


function place_order_pay_braintree_paypal($store, $_data, $order, $customer, $website, $editor, $smarty, $db, $account) {


    require_once 'external_libs/braintree-php-3.2.0/lib/Braintree.php';

    include_once 'external_libs/contact_name_parser.php';

    $payment_account         = get_object('Payment_Account', $_data['payment_account_key']);
    $payment_account->editor = $editor;

    /*
        if (empty($_data['data']['nonce']) and isset($_REQUEST['data']['card_id']) and isset($_REQUEST['data']['cvv'])) {

            $nonce        = false;
            $save_payment = false;
            $token        = $customer->get_credit_card_token($_data['data']['card_id'], $order->get('Order Delivery Address Checksum'), $order->get('Order Invoice Address Checksum'));


        } else {

            $nonce        = $_data['data']['nonce'];
            $save_payment = $_data['data']['save_card'];
            $token        = '';
        }
    */

    $nonce        = $_data['nonce'];
    $save_payment = false;
    $token        = '';

    $parser = new FullNameParser();


    $billing_contact_name  = $parser->parse_name($order->get('Order Invoice Address Recipient'));
    $delivery_contact_name = $parser->parse_name($order->get('Order Delivery Address Recipient'));


    $braintree_customer = false;
    if ($save_payment) {

        try {
            $braintree_customer = Braintree_Customer::find($customer->id);
            //print_r($braintree_customer);
        } catch (Exception $e) {
            //echo 'Message: ' .$e->getMessage();
        }


    }


    Braintree_Configuration::environment('production');
    Braintree_Configuration::merchantId($payment_account->get('Payment Account ID'));
    Braintree_Configuration::publicKey($payment_account->get('Payment Account Login'));
    Braintree_Configuration::privateKey($payment_account->get('Payment Account Password'));


    $to_pay  = $order->get('Order To Pay Amount');
    $credits = $customer->get('Customer Account Balance');
    if ($credits > 0) {

        if ($to_pay < $credits) {
            $to_pay_credits = $to_pay;

            list($customer, $order, $credit_payment_account, $credit_payment) = pay_credit($order, $to_pay_credits, $editor, $db, $account);

            place_order($store, $order, $credit_payment_account->id, $customer, $website, $editor, $smarty, $account, $db);
            exit;

        } else {
            $to_pay_credits = $credits;
            list($customer, $order, $credit_payment_account, $credit_payment) = pay_credit($order, $to_pay_credits, $editor, $db, $account);

        }


    }

    if ($order->get('Order To Pay Amount') > 0) {


        $braintree_data = [
            'merchantAccountId' => $payment_account->get('Payment Account Cart ID'),
            'amount'            => $order->get('Order To Pay Amount'),
            'orderId'           => $order->get('Order Public ID'),
            'customer'          => [

                'firstName' => $billing_contact_name['fname'],
                'lastName'  => $billing_contact_name['lname'],
                'company'   => $order->get('Order Invoice Address Organization'),
                'email'     => $order->get('Order Email')
            ],

            'billing'  => [
                'firstName'         => $billing_contact_name['fname'],
                'lastName'          => $billing_contact_name['lname'],
                'company'           => $order->get('Order Invoice Address Organization'),
                'streetAddress'     => $order->get('Order Invoice Address Line 1'),
                'extendedAddress'   => $order->get('Order Invoice Address Line 2'),
                'locality'          => $order->get('Order Invoice Address Locality'),
                'region'            => $order->get('Order Invoice Address  Administrative Area'),
                'postalCode'        => $order->get('Order Invoice Address Postal Code'),
                'countryCodeAlpha2' => $order->get('Order Invoice Address Country 2 Alpha Code'),
            ],
            'shipping' => [
                'firstName'         => $delivery_contact_name['fname'],
                'lastName'          => $delivery_contact_name['lname'],
                'company'           => $order->get('Order Delivery Address Organization'),
                'streetAddress'     => $order->get('Order Delivery Address Line 1'),
                'extendedAddress'   => $order->get('Order Delivery Address Line 2'),
                'locality'          => $order->get('Order Delivery Address Locality'),
                'region'            => $order->get('Order Delivery Address  Administrative Area'),
                'postalCode'        => $order->get('Order Delivery Address Postal Code'),
                'countryCodeAlpha2' => $order->get('Order Delivery Address Country 2 Alpha Code'),
            ],


            'options' => [
                'submitForSettlement'   => true,
                'storeInVaultOnSuccess' => $save_payment,
            ]
        ];


        if ($save_payment and !$braintree_customer) {
            $braintree_data['customer']['id'] = $customer->id;
        }


        if ($nonce) {
            $braintree_data['paymentMethodNonce'] = $nonce;

        } else {
            $braintree_data['paymentMethodToken'] = $token;


        }


        //  print_r($braintree_data);


        try {
            $result = Braintree_Transaction::sale($braintree_data);


            if ($result->success) {


                switch ($result->transaction->paymentInstrumentType) {
                    case 'credit_card':

                        $payment_method   = 'Credit Card';
                        $payment_metadata = AESEncryptCtr(json_encode($result->transaction->creditCard), md5('Payment'.CKEY), 256);

                        break;
                    case 'paypal_account':

                        $payment_method = 'Paypal';
                        break;

                    default:
                        $payment_method = 'Other';
                        break;
                }


                //print_r($result);
                include_once 'utils/aes.php';


                $payment_metadata = '';


                $payment_data = array(
                    'Payment Store Key'                   => $order->get('Order Store Key'),
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
                    'Payment Location'           => 'Basket',
                    'Payment Metadata'           => $payment_metadata

                );


                $payment = $payment_account->create_payment($payment_data);


                if ($result->transaction->creditCard['token'] != '' and $save_payment) {
                    $customer->save_credit_card('Braintree', $result->transaction->creditCard, $order->get('Order Delivery Address Checksum'), $order->get('Order Invoice Address Checksum'));
                }

                //  print_r($payment);

                $order->add_payment($payment);


                place_order($store, $order, $payment_account->id, $customer, $website, $editor, $smarty, $account, $db);


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

                    $msg = '<ul>';
                    foreach ($error_messages as $error_message) {
                        $msg .= '<li>'.$error_message.'</li>';
                    }
                    $msg .= '</ul>';

                    $private_message = join(', ', $error_private_messages);
                } else {


                    $msg = _('There was a problem processing your credit card; please double check your payment information and try again').'. ('.$result->transaction->id.')';

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


                $payment_metadata = '';


                if (isset($result->transaction) and $result->transaction != '') {


                    $payment_data = array(
                        'Payment Store Key'                   => $order->get('Order Store Key'),
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


                    $order->add_payment($payment);
                }

                $response = array(
                    'state' => 400,
                    'msg'   => $msg
                    // 'private_message'=>$private_message,
                    // 'transaction_id'=>$transaction_id

                );
                echo json_encode($response);


            }


        } catch (Exception $e) {


            $msg = _('There was a problem processing your credit card; please double check your payment information and try again').'.';

            $response = array(

                'state' => 400,
                'msg'   => $msg

            );
            echo json_encode($response);
            exit;

            //echo 'Message: ' .$e->getMessage();
        }

    }


}


function pay_credit($order, $amount, $editor, $db, $account) {


    include_once 'utils/currency_functions.php';


    $store    = get_object('store', $order->get('Order Store Key'));
    $customer = get_object('Customer', $order->get('Customer Key'));


    $order->editor = $editor;

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

    //print_r($payment_data);


    $payment = $payment_account->create_payment($payment_data);


    $sql = sprintf(
        'INSERT INTO `Credit Transaction Fact` 
                    (`Credit Transaction Date`,`Credit Transaction Amount`,`Credit Transaction Currency Code`,`Credit Transaction Currency Exchange Rate`,`Credit Transaction Customer Key`,`Credit Transaction Payment Key`) 
                    VALUES (%s,%.2f,%s,%f,%d,%d) ', prepare_mysql($date), -$amount, prepare_mysql($order->get('Currency Code')), $exchange, $order->get('Order Customer Key'), $payment->id


    );

    //print $sql;

    $db->exec($sql);

    $reference = $db->lastInsertId();


    //print " ****> $reference <*****";


    $payment->fast_update(array('Payment Transaction ID' => sprintf('%05d', $reference)));


    $customer->update_account_balance();


    $order->add_payment($payment);
    $order->update_totals();


    return array(
        $customer,
        $order,
        $payment_account,
        $payment
    );


}


function get_checkout_html($data, $customer, $smarty) {


    require_once "utils/aes.php";


    $website = get_object('Website', $_SESSION['website_key']);
    $theme   = $website->get('Website Theme');

    $order = get_object('Order', $customer->get_order_in_process_key());

    if (!$order->id or ($order->get('Products') == 0)) {

        // print '>'.$data['device_prefix'].'<';


        $response = array(
            'state' => 200,
            'html'  => $smarty->fetch('theme_1/checkout_no_order.'.$theme.'.EcomB2B.tpl'),
        );


        echo json_encode($response);
        exit;
    }


    $order->update_totals();


    $store = get_object('Store', $website->get('Website Store Key'));

    $webpage = $website->get_webpage('checkout.sys');

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
        '[Order Amount]' => $order->get('Basket To Pay Amount'),

    );

    if (isset($block['labels']['_bank_header'])) {
        $block['labels']['_bank_header'] = strtr($block['labels']['_bank_header'], $placeholders);
    }
    if (isset($block['labels']['_bank_footer'])) {
        $block['labels']['_bank_footer'] = strtr($block['labels']['_bank_footer'], $placeholders);
    }


    $smarty->assign('order', $order);
    $smarty->assign('customer', $customer);
    $smarty->assign('website', $website);
    $smarty->assign('store', $store);

    $smarty->assign('key', $block_key);
    $smarty->assign('data', $block);
    $smarty->assign('labels', $website->get('Localised Labels'));


    $response = array(
        'state' => 200,
        'html'  => $smarty->fetch('theme_1/blk.checkout.theme_1.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),
    );


    echo json_encode($response);

}


?>
