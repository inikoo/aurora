<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 July 2017 at 16:44:58 CEST, Trnava, Slavakia
 Copyright (c) 2017, Inikoo

 Version 3

*/

use Checkout\CheckoutApiException;
use Checkout\CheckoutSdk;
use Checkout\Common\CustomerRequest;
use Checkout\Environment;
use Checkout\Payments\Request\PaymentRequest;
use Checkout\Payments\Request\Source\RequestTokenSource;
use Checkout\Payments\Sender\PaymentInstrumentSender;
use Checkout\Payments\ThreeDsRequest;


include_once 'ar_web_common_logged_in.php';
require_once 'utils/placed_order_functions.php';
require_once 'utils/aes.php';
require_once 'utils/currency_functions.php';
require_once 'utils/braintree_error_messages.php';
include_once 'payment_account_checkout_common_functions.php';
include_once 'common_web_paying_functions.php';


$smarty               = new Smarty();
$smarty->caching_type = 'redis';
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
    case 'get_checkout_html':
        $data = prepare_values(
            $_REQUEST, array(
                'device_prefix'    => array(
                    'type'     => 'string',
                    'optional' => true
                ),
                'client_order_key' => array(
                    'type'     => 'string',
                    'optional' => true
                )
            )
        );

        get_checkout_html($data, $website, $customer, $smarty);


        break;
    case 'place_order_pay_later':
        $data = prepare_values(
            $_REQUEST, array(
                'payment_account_key' => array('type' => 'key'),
                'order_key'           => array('type' => 'key')

            )
        );


        if ($website->get('Website Type') == 'EcomDS') {
            $order         = get_object('Order', $data['order_key']);
            $order->editor = $editor;

            if ($order->get('Order Customer Key') != $customer->id) {
                $response = array(
                    'state' => 400,
                    'resp'  => 'Error C_not_M'
                );
                echo json_encode($response);
                exit;
            }
        }


        if ($order->get('Order State') != 'InBasket') {
            $response = array(
                'state' => 400,
                'resp'  => 'Error order not in basket'
            );
            echo json_encode($response);
            exit;
        }

        $store = get_object('Store', $order->get('Order Store Key'));


        $exchange = currency_conversion(
            $db,
            $store->get('Store Currency Code'),
            $account->get('Account Currency Code'),
            '- 1440 minutes'
        );


        $payment_account_key = $data['payment_account_key'];

        $payment_account     = get_object('Payment_Account', $payment_account_key);


        //===== CoD charges


        if($payment_account->get('Payment Account Block')=='ConD') {
            $sql  = "select `Charge Key` from `Charge Dimension` where `Charge Store Key`=? and `Charge Scope`=? ";
            $stmt = $db->prepare($sql);
            $stmt->execute(
                [
                    $store->id,
                    'CoD'
                ]
            );
            if ($row = $stmt->fetch()) {
                $charge = get_object('Charge', $row['Charge Key']);
                $order->add_charge($charge);
            }
        }


        //================


        $to_pay = $order->get('Order To Pay Amount');


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

        place_order($store, $order, $payment_account_key, $customer, $website, $editor, $smarty, $account, $db);


        $analytics_items = array();
        foreach ($items = $order->get_items() as $item) {
            $analytics_items[] = $item['analytics_data'];
        }

        $response = array(
            'state'          => 200,
            'order_key'      => $order->id,
            'analytics_data' => array(
                'id'          => $order->get('Public ID'),
                'affiliation' => $store->get('Name'),
                'revenue'     => $order->get('Order Total Amount'),
                'gbp_revenue' => ceil($order->get('Order Total Amount') * $exchange),
                'tax'         => $order->get('Order Total Tax Amount'),
                'shipping'    => $order->get('Order Shipping Net Amount'),
                'items'       => $analytics_items
            )
        );


        echo json_encode($response);

        break;

    case 'place_order_pay_braintree_paypal':
        $data    = prepare_values(
            $_REQUEST, array(
                'payment_account_key' => array('type' => 'key'),
                'order_key'           => array('type' => 'key'),
                'amount'              => array('type' => 'string'),
                'nonce'               => array('type' => 'string'),


            )
        );
        $website = get_object('Website', $_SESSION['website_key']);

        if ($website->get('Website Type') == 'EcomDS') {
            $order         = get_object('Order', $data['order_key']);
            $order->editor = $editor;

            if ($order->get('Order Customer Key') != $customer->id) {
                $response = array(
                    'state' => 400,
                    'resp'  => 'Error C_not_M'
                );
                echo json_encode($response);
                exit;
            }
        }


        if ($order->get('Order State') != 'InBasket') {
            $response = array(
                'state' => 400,
                'resp'  => 'Error order not in basket'
            );
            echo json_encode($response);
            exit;
        }


        $store   = get_object('Store', $order->get('Order Store Key'));
        $account = get_object('Account', 1);

        place_order_pay_braintree_paypal($store, $data, $order, $customer, $website, $editor, $smarty, $db, $account);


        break;
    case 'place_order_pay_checkout':
        $data    = prepare_values(
            $_REQUEST, array(
                'payment_account_key' => array('type' => 'key'),
                'order_key'           => array('type' => 'key'),
                'token'               => array('type' => 'string'),

            )
        );
        $website = get_object('Website', $_SESSION['website_key']);


        if ($website->get('Website Type') == 'EcomDS') {
            $order         = get_object('Order', $data['order_key']);
            $order->editor = $editor;

            if ($order->get('Order Customer Key') != $customer->id) {
                $response = array(
                    'state' => 400,
                    'resp'  => 'Error C_not_M'
                );
                echo json_encode($response);
                exit;
            }
        }


        if ($order->get('Order State') != 'InBasket') {
            $response = array(
                'state' => 400,
                'resp'  => 'Error order not in basket'
            );
            echo json_encode($response);
            exit;
        }


        $store   = get_object('Store', $order->get('Order Store Key'));
        $account = get_object('Account', 1);
        place_order_pay_checkout($store, $data, $order, $customer, $website, $editor, $smarty, $db, $account);


        break;
    case 'place_order_pay_braintree':
        $data    = prepare_values(
            $_REQUEST, array(
                'payment_account_key' => array('type' => 'key'),
                'order_key'           => array('type' => 'key'),
                'data'                => array('type' => 'json array'),

            )
        );
        $website = get_object('Website', $_SESSION['website_key']);


        if ($website->get('Website Type') == 'EcomDS') {
            $order         = get_object('Order', $data['order_key']);
            $order->editor = $editor;

            if ($order->get('Order Customer Key') != $customer->id) {
                $response = array(
                    'state' => 400,
                    'resp'  => 'Error C_not_M'
                );
                echo json_encode($response);
                exit;
            }
        }


        if ($order->get('Order State') != 'InBasket') {
            $response = array(
                'state' => 400,
                'resp'  => 'Error order not in basket'
            );
            echo json_encode($response);
            exit;
        }


        $store   = get_object('Store', $order->get('Order Store Key'));
        $account = get_object('Account', 1);
        place_order_pay_braintree($store, $data, $order, $customer, $website, $editor, $smarty, $db, $account);


        break;

    case 'place_order_pay_braintree_using_saved_card':
        $data    = prepare_values(
            $_REQUEST, array(
                'payment_account_key' => array('type' => 'key'),
                'order_key'           => array('type' => 'key'),
                'data'                => array('type' => 'json array'),

            )
        );
        $website = get_object('Website', $_SESSION['website_key']);

        if ($website->get('Website Type') == 'EcomDS') {
            $order         = get_object('Order', $data['order_key']);
            $order->editor = $editor;

            if ($order->get('Order Customer Key') != $customer->id) {
                $response = array(
                    'state' => 400,
                    'resp'  => 'Error C_not_M'
                );
                echo json_encode($response);
                exit;
            }
        }


        if ($order->get('Order State') != 'InBasket') {
            $response = array(
                'state' => 400,
                'resp'  => 'Error order not in basket'
            );
            echo json_encode($response);
            exit;
        }

        $store   = get_object('Store', $order->get('Order Store Key'));
        $account = get_object('Account', 1);
        place_order_pay_braintree_using_saved_card($store, $data, $order, $customer, $website, $editor, $smarty, $db, $account);


        break;

    case 'delete_braintree_saved_card':

        $data = prepare_values(
            $_REQUEST, array(
                'payment_account_key' => array('type' => 'key'),

                'token' => array('type' => 'string'),


            )
        );

        delete_braintree_saved_card($data, $editor);

        break;
}


function place_order_pay_braintree($store, $_data, $order, $customer, $website, $editor, $smarty, $db, $account)
{
    $order->fast_update(
        array(
            'Order Available Credit Amount' => $customer->get('Customer Account Balance')
        )
    );

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


    $to_pay  = $order->get('Order To Pay Amount');
    $credits = floatval($customer->get('Customer Account Balance'));
    if ($credits > 0) {
        if ($to_pay < $credits) {
            list($customer, $order, $credit_payment_account, $credit_payment) = pay_credit($order, $to_pay, $editor, $db, $account);

            place_order($store, $order, $credit_payment_account->id, $customer, $website, $editor, $smarty, $account, $db);

            $exchange = currency_conversion(
                $db,
                $store->get('Store Currency Code'),
                $account->get('Account Currency Code'),
                '- 1440 minutes'
            );

            $analytics_items = array();
            foreach ($items = $order->get_items() as $item) {
                $analytics_items[] = $item['analytics_data'];
            }

            $response = array(
                'state'          => 200,
                'order_key'      => $order->id,
                'analytics_data' => array(
                    'id'          => $order->get('Public ID'),
                    'affiliation' => $store->get('Name'),
                    'revenue'     => $order->get('Order Total Amount'),
                    'gbp_revenue' => ceil($order->get('Order Total Amount') * $exchange),
                    'tax'         => $order->get('Order Total Tax Amount'),
                    'shipping'    => $order->get('Order Shipping Net Amount'),
                    'items'       => $analytics_items
                )
            );


            echo json_encode($response);
            exit;
        }
    }


    $braintree_data = get_sale_transaction_braintree_data($order, $gateway, $_data['data']['save_card']);


    $braintree_data['amount'] = $order->get('Order Basket To Pay Amount');


    $braintree_data['merchantAccountId']  = $payment_account->get('Payment Account Cart ID');
    $braintree_data['paymentMethodNonce'] = $_data['data']['nonce'];

    if (!empty($_data['data']['deviceData'])) {
        $braintree_data['deviceData'] = $_data['data']['deviceData'];
    }


    $response = process_braintree_order($braintree_data, $order, $gateway, $customer, $store, $website, $payment_account, $editor, $db, $account, $smarty);
    echo json_encode($response);
}


function place_order_pay_braintree_paypal($store, $_data, $order, $customer, $website, $editor, $smarty, $db, $account)
{
    $order->fast_update(
        array(
            'Order Available Credit Amount' => $customer->get('Customer Account Balance')
        )
    );

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


    $braintree_data = get_sale_transaction_braintree_data($order, $gateway);

    $braintree_data['amount'] = floatval($_data['amount']);

    $braintree_data['merchantAccountId']  = $payment_account->get('Payment Account Cart ID');
    $braintree_data['paymentMethodNonce'] = $_data['nonce'];

    $response = process_braintree_order($braintree_data, $order, $gateway, $customer, $store, $website, $payment_account, $editor, $db, $account, $smarty);
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
function get_checkout_html($data, $website, $customer, $smarty)
{
    require_once __DIR__.'/utils/aes.php';

    $theme = $website->get('Website Theme');


    if ($website->get('Website Type') == 'EcomDS') {
        if (empty($data['client_order_key']) or !is_numeric($data['client_order_key']) or $data['client_order_key'] <= 0) {
            $response = array(
                'state' => 400,
                'html'  => '<div style="margin:100px auto;text-align: center">Client order key not provided (b)</div>'


            );
            echo json_encode($response);
            exit;
        }


        $order = get_object('Order', $data['client_order_key']);

        if (!$order->id or $order->get('Order Customer Key') != $customer->id) {
            $response = array(
                'state' => 200,
                'html'  => '<div style="margin:100px auto;text-align: center">Incorrect order id</div>'

            );
            echo json_encode($response);
            exit;
        }

        if ($order->get('Order State') != 'InBasket') {
            $response = array(
                'state' => 200,
                'html'  => '<div style="margin:100px auto;text-align: center">Order not in basket</div>'

            );
            echo json_encode($response);
            exit;
        }
    } else {
        $order = get_object('Order', $customer->get_order_in_process_key());
    }


    $order->fast_update(
        array(
            'Order Available Credit Amount' => $customer->get('Customer Account Balance')
        )
    );


    if (!$order->id or ($order->get('Products') == 0)) {
        $response = array(
            'state' => 200,
            'html'  => $smarty->fetch('theme_1/checkout_no_order.'.$theme.'.EcomB2B.tpl'),
        );
        echo json_encode($response);
        exit;
    }
    //

    $order->update_totals();


    $store = get_object('Store', $website->get('Website Store Key'));

    $webpage = $website->get_webpage('checkout.sys');

    $content = $webpage->get('Content Data');

    $block       = array();
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


    $error_msg = '';
    if (!empty($_SESSION['checkout_payment_error'])) {
        $error_msg = $_SESSION['checkout_payment_error'];
    }


    $smarty->assign('error_msg', $error_msg);
    unset($_SESSION['checkout_payment_error']);


    if ($website->get('Website Type') == 'EcomDS') {
        $basket_url = '/client_basket.sys?client_id='.$order->get('Order Customer Client Key');
    } else {
        $basket_url = '/basket.sys';
    }


    $response = array(
        'state'      => 200,
        'html'       => $smarty->fetch('theme_1/blk.checkout.theme_1.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),
        'basket_url' => $basket_url
    );


    echo json_encode($response);
}


function place_order_pay_braintree_using_saved_card($store, $_data, $order, $customer, $website, $editor, $smarty, $db, $account)
{
    $order->fast_update(
        array(
            'Order Available Credit Amount' => $customer->get('Customer Account Balance')
        )
    );

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


        $nonce = $_data['data']['nonce'];

        if (!empty($_data['data']['nonce_for_card_verification'])) {
            $nonce = $_data['data']['nonce_for_card_verification'];
        }


        $verification_result = $gateway->paymentMethod()->update(
            $token, [
                'paymentMethodNonce' => $nonce,
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
        $to_pay  = $order->get('Order To Pay Amount');
        $credits = floatval($customer->get('Customer Account Balance'));
        if ($credits > 0) {
            if ($to_pay < $credits) {
                list($customer, $order, $credit_payment_account, $credit_payment) = pay_credit($order, $to_pay, $editor, $db, $account);

                place_order($store, $order, $credit_payment_account->id, $customer, $website, $editor, $smarty, $account, $db);


                $exchange = currency_conversion(
                    $db,
                    $store->get('Store Currency Code'),
                    $account->get('Account Currency Code'),
                    '- 1440 minutes'
                );


                $analytics_items = array();
                foreach ($items = $order->get_items() as $item) {
                    $analytics_items[] = $item['analytics_data'];
                }

                $response = array(
                    'state'          => 200,
                    'order_key'      => $order->id,
                    'analytics_data' => array(
                        'id'          => $order->get('Public ID'),
                        'affiliation' => $store->get('Name'),
                        'revenue'     => $order->get('Order Total Amount'),
                        'gbp_revenue' => ceil($order->get('Order Total Amount') * $exchange),
                        'tax'         => $order->get('Order Total Tax Amount'),
                        'shipping'    => $order->get('Order Shipping Net Amount'),
                        'items'       => $analytics_items
                    )
                );

                echo json_encode($response);
                exit;
            }
        }


        $braintree_data = get_sale_transaction_braintree_data($order, $gateway);

        $braintree_data['amount']             = $order->get('Order Basket To Pay Amount');
        $braintree_data['paymentMethodToken'] = $token;
        $braintree_data['merchantAccountId']  = $payment_account->get('Payment Account Cart ID');

        if (!empty($_data['data']['deviceData'])) {
            $braintree_data['deviceData'] = $_data['data']['deviceData'];
        }


        $response = process_braintree_order($braintree_data, $order, $gateway, $customer, $store, $website, $payment_account, $editor, $db, $account, $smarty);
        echo json_encode($response);
    } else {
        print_r($verification_result);

        $response = array(
            'state' => 400,
            'msg'   => _('Card verification value (CVV) failed')

        );

        echo json_encode($response);
    }
}


function get_sale_transaction_braintree_data($order, $gateway, $save_payment = false)
{
    include_once 'external_libs/contact_name_parser.php';


    $braintree_customer = false;
    if ($save_payment) {
        try {
            $braintree_customer = $gateway->customer()->find($order->get('Order Customer Key'));
            //print_r($braintree_customer);
        } catch (Exception $e) {
            //echo 'Message: ' .$e->getMessage();
        }
    }


    $parser = new FullNameParser();


    $billing_contact_name  = $parser->parse_name($order->get('Order Invoice Address Recipient'));
    $delivery_contact_name = $parser->parse_name($order->get('Order Delivery Address Recipient'));

    $braintree_data = [

        'amount'   => $order->get('Order To Pay Amount'),
        'orderId'  => $order->get('Order Public ID'),
        'customer' => [

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
            'region'            => $order->get('Order Invoice Address Administrative Area'),
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
            'region'            => $order->get('Order Delivery Address Administrative Area'),
            'postalCode'        => $order->get('Order Delivery Address Postal Code'),
            'countryCodeAlpha2' => $order->get('Order Delivery Address Country 2 Alpha Code'),
        ],


        'options' => [
            'submitForSettlement' => true,


        ]
    ];

    if ($save_payment) {
        $braintree_data['options']['storeInVaultOnSuccess']       = true;
        $braintree_data['options']['storeShippingAddressInVault'] = true;

        if ($braintree_customer) {
            $braintree_data['customerId'] = $order->get('Order Customer Key');
        } else {
            $braintree_data['customer']['id'] = $order->get('Order Customer Key');
        }
    }


    return $braintree_data;
}

function process_braintree_order($braintree_data, $order, $gateway, $customer, $store, $website, $payment_account, $editor, $db, $account, $smarty)
{
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


            //print_r($result);


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


            $order->add_payment($payment);


            $credits = floatval($customer->get('Customer Account Balance'));

            if ($credits > 0) {
                $to_pay_credits = min($order->get('Order To Pay Amount'), $credits);
                list($customer, $order, $credit_payment_account, $credit_payment) = pay_credit($order, $to_pay_credits, $editor, $db, $account);
            }


            place_order($store, $order, $payment_account->id, $customer, $website, $editor, $smarty, $account, $db);

            $exchange = currency_conversion(
                $db,
                $store->get('Store Currency Code'),
                $account->get('Account Currency Code'),
                '- 1440 minutes'
            );

            $analytics_items = array();
            foreach ($items = $order->get_items() as $item) {
                $analytics_items[] = $item['analytics_data'];
            }

            $response = array(
                'state'          => 200,
                'order_key'      => $order->id,
                'analytics_data' => array(
                    'id'          => $order->get('Public ID'),
                    'affiliation' => $store->get('Name'),
                    'revenue'     => $order->get('Order Total Amount'),
                    'gbp_revenue' => ceil($order->get('Order Total Amount') * $exchange),
                    'tax'         => $order->get('Order Total Tax Amount'),
                    'shipping'    => $order->get('Order Shipping Net Amount'),
                    'items'       => $analytics_items
                )
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


            if ($payment_account->get('Payment Account Block') == 'BTreePaypal') {
                $msg = _('There was a problem processing your payment').', '._('please double check your payment information and try again');
            } else {
                $msg = _('There was a problem processing your credit card').', '._('please double check your payment information and try again');
            }


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
                'state'           => 400,
                'msg'             => $msg,
                'private_message' => $private_message,
                // 'transaction_id'=>$transaction_id

            );

            return $response;
        }
    } catch (Exception $e) {
        $msg = _('There was a problem processing your credit card; please double check your payment information and try again');

        $response = array(

            'state' => 400,
            'msg'   => $msg

        );

        return $response;
        //echo 'Message: ' .$e->getMessage();
    }
}


function delete_braintree_saved_card($_data, $editor)
{
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

    try {
        $token_data = json_decode(AESDecryptCtr($_data['token'], md5('CCToken'.CKEY), 256), true);
        $token      = $token_data['t'];

        $result = $gateway->paymentMethod()->delete($token);

        if ($result->success) {
            $response = array(

                'state' => 200,

            );
            echo json_encode($response);
            exit;
        } else {
            $msg = _('There was a problem deleting your card please try again later');

            $response = array(

                'state' => 400,
                'msg'   => $msg

            );
            echo json_encode($response);
            exit;
        }
    } catch (Exception $e) {
        $msg = _('There was a problem deleting your card please try again later');

        $response = array(

            'state' => 400,
            'msg'   => $msg

        );
        echo json_encode($response);
        exit;
    }
}

function place_order_pay_checkout($store, $_data, $order, $customer, $website, $editor, $smarty, $db, $account)
{
    $order->fast_update(
        array(
            'Order Available Credit Amount' => $customer->get('Customer Account Balance')
        )
    );

    $payment_account         = get_object('Payment_Account', $_data['payment_account_key']);
    $payment_account->editor = $editor;


    $to_pay  = $order->get('Order To Pay Amount');
    $credits = floatval($customer->get('Customer Account Balance'));
    if ($credits > 0) {
        if ($to_pay < $credits) {
            list($customer, $order, $credit_payment_account, $credit_payment) = pay_credit($order, $to_pay, $editor, $db, $account);

            place_order($store, $order, $credit_payment_account->id, $customer, $website, $editor, $smarty, $account, $db);

            $exchange = currency_conversion(
                $db,
                $store->get('Store Currency Code'),
                $account->get('Account Currency Code'),
                '- 1440 minutes'
            );

            $analytics_items = array();
            foreach ($items = $order->get_items() as $item) {
                $analytics_items[] = $item['analytics_data'];
            }

            $response = array(
                'state'          => 200,
                'order_key'      => $order->id,
                'analytics_data' => array(
                    'id'          => $order->get('Public ID'),
                    'affiliation' => $store->get('Name'),
                    'revenue'     => $order->get('Order Total Amount'),
                    'gbp_revenue' => ceil($order->get('Order Total Amount') * $exchange),
                    'tax'         => $order->get('Order Total Tax Amount'),
                    'shipping'    => $order->get('Order Shipping Net Amount'),
                    'items'       => $analytics_items
                )
            );


            echo json_encode($response);
            exit;
        }
    }


    $sql =
        "SELECT `login` FROM `Payment Account Store Bridge`    
               WHERE 
                    `Payment Account Store Payment Account Key`=?  and 
                    `Payment Account Store Store Key`=?  AND 
                     `Payment Account Store Status`='Active' AND 
                   `Payment Account Store Show in Cart`='Yes'  ";
    /** @var TYPE_NAME $db */
    $stmt = $db->prepare($sql);
    $stmt->execute(
        [
            $payment_account->id,
            $store->id
        ]
    );
    $channel = '';
    while ($row = $stmt->fetch()) {
        $channel=$row['login'];
    }

$secretKey=$payment_account->get('Payment Account Password');

    //====== v3


    $CheckoutAPI = CheckoutSdk::builder()->staticKeys()

        ->secretKey($secretKey)
        ->environment(ENVIRONMENT == 'DEVEL' ?Environment::sandbox(): Environment::production())
        ->build();


    try {

        $requestTokenSource = new RequestTokenSource();
        $requestTokenSource->token = $_data['token'];

        $threeDsRequest = new ThreeDsRequest();
        $threeDsRequest->enabled = true;

        $paymentInstrumentSender = new PaymentInstrumentSender();

        $customerRequest = new CustomerRequest();
        $customerRequest->email = $customer->get('Customer Main Plain Email');
        $customerRequest->name = $customer->get('Customer Name');

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestTokenSource;
        $paymentRequest->amount = $order->get('Order Basket To Pay Amount') * 100;
        $paymentRequest->currency = $order->get('Order Currency');
        $paymentRequest->capture = true;
        $paymentRequest->reference = $order->get('Order Public ID');
        $paymentRequest->three_ds = $threeDsRequest;
        $paymentRequest->sender = $paymentInstrumentSender;
        $paymentRequest->customer = $customerRequest;
        $paymentRequest->processing_channel_id = $channel;
        //$paymentRequest->metadata = $metadata;


        if (ENVIRONMENT == 'DEVEL') {
            $success_url = "http://ecom.test:88/ar_web_process_checkout.php";
            $failure_url = "http://ecom.test:88/ar_web_process_checkout.php";
        } else {
            $success_url = 'https://'.$website->get('Website URL')."/ar_web_process_checkout.php";
            $failure_url = 'https://'.$website->get('Website URL')."/ar_web_process_checkout.php";
        }



        if ($website->get('Website Type') == 'EcomDS') {
            $success_url.='?client_order_key='.$order->id;
            $failure_url.='?client_order_key='.$order->id;

        }

        $paymentRequest->success_url = $success_url;
        $paymentRequest->failure_url = $failure_url;


        try {
            $response = $CheckoutAPI->getPaymentsClient()->requestPayment($paymentRequest);

        } catch (Exception $e) {
            $msg                                = _('There was a problem processing your credit card; please double check your payment information and try again');
            $_SESSION['checkout_payment_error'] = strip_tags($msg).' '.$e->getMessage();

            $response = array(

                'state' => 400,
                'msg'   => $msg

            );
            echo json_encode($response);
            exit;
        }

    }
    catch (CheckoutApiException $e) {


        $msg                                = _('There was a problem processing your credit card; please double check your payment information and try again');
        $_SESSION['checkout_payment_error'] = strip_tags($msg).' '.$e->getMessage();

        $response = array(

            'state' => 400,
            'msg'   => $msg

        );
        echo json_encode($response);
        exit;

    }

    switch ($response['http_metadata']->getStatusCode()) {
        case 202:

            $response = array(


                'state'    => 201,
                'redirect' => $response['_links']['redirect']['href']

            );
            echo json_encode($response);
            exit;

        case 201:

            $res = process_payment_response($response, $order, $website, $payment_account, $customer, $editor, $smarty, $account, $store, $db);

            if ($res['state'] == 400) {
                $_SESSION['checkout_payment_error'] = strip_tags($res['msg']);
            } elseif ($res['state'] == 200) {
                setcookie('au_pu_'.$order->id, $order->id, time() + 300, "/");
            }

            echo json_encode($res);
            exit;
    }
}

