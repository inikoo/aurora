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
require_once 'utils/placed_order_functions.php';


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

        place_order($store,$order, $data['payment_account_key'], $customer, $website, $editor, $smarty);


        break;

    case 'place_order_pay_braintree_paypal':
        $data = prepare_values(
            $_REQUEST, array(
                         'payment_account_key' => array('type' => 'key'),
                         'order_key' => array('type' => 'key'),
                         'amount' => array('type' => 'string'),
                         'currency' => array('type' => 'string'),
                         'nonce' => array('type' => 'string'),



                     )
        );

        place_order_pay_braintree_paypal($store,$data, $order, $customer, $website, $editor, $smarty);


        break;

    case 'place_order_pay_braintree':
        $data = prepare_values(
            $_REQUEST, array(
                         'payment_account_key' => array('type' => 'key'),

                         'data' => array('type' => 'json array'),

                     )
        );

        place_order_pay_braintree($store,$data, $order, $customer, $website, $editor, $smarty);


        break;
}

function place_order_pay_braintree($store,$_data, $order, $customer, $website, $editor, $smarty) {


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


    $braintree_data = [
        'merchantAccountId' => $payment_account->get('Payment Account Cart ID'),
        'amount'            => $order->get('Order To Pay Amount'), // todo make sure you subtraction the credits
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


            place_order($store,$order, $payment_account->id, $customer, $website, $editor, $smarty);


        } else {


            $error_messages         = array();
            $error_private_messages = array();

            foreach ($result->errors->deepAll() as $error) {


                $_msg = get_errror_message($error->code, $error->message);

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


            if (isset($result->transaction) and $result->transaction!='') {




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


function place_order($store,$order, $payment_account_key, $customer, $website, $editor, $smarty) {


    $customer->editor = $editor;


    $order->update(
        array(
            'Order State'             => 'InProcess',
            'Order Checkout Block Payment Account Key' => $payment_account_key
        ), 'no_history'
    );


    send_order_confirmation_email($store,$website, $customer, $order, $smarty);


    $response = array(
        'state'     => 200,
        'order_key' => $order->id,

    );


    echo json_encode($response);

}

function send_order_confirmation_email($store,$website, $customer, $order, $smarty) {
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


    $request                                    = array();
    $request['Source']                          = sprintf('%s <%s>',$store->get('Store Name'), $sender_email_address) ;
    $request['Destination']['ToAddresses']      = array($order->get('Order Email'));
    $request['Message']['Subject']['Data']      = $published_email_template->get('Published Email Template Subject');
    $request['Message']['Body']['Text']['Data'] = strtr($published_email_template->get('Published Email Template Text'), $placeholders);


    if ($email_template->get('Email Template Type') == 'HTML') {

        $request['Message']['Body']['Html']['Data'] = strtr($published_email_template->get('Published Email Template HTML'), $placeholders);

    }

    //   print_r($request);

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


function get_errror_message($code, $original_message) {

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


function place_order_pay_braintree_paypal($store,$_data, $order, $customer, $website, $editor, $smarty) {




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
    $save_payment=false;
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


    $braintree_data = [
        'merchantAccountId' => $payment_account->get('Payment Account Cart ID'),
        'amount'            => $order->get('Order To Pay Amount'), // todo make sure you subtraction the credits
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


            place_order($store,$order, $payment_account->id, $customer, $website, $editor, $smarty);


        } else {


            $error_messages         = array();
            $error_private_messages = array();

            foreach ($result->errors->deepAll() as $error) {


                $_msg = get_errror_message($error->code, $error->message);

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


            if (isset($result->transaction) and $result->transaction!='') {




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



?>
