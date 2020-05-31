<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 May 2018 at 14:34:26 CEST, Mijas, Costa, Spain
 Copyright (c) 2017, Inikoo

 Version 3

*/
namespace Sofort\SofortLib;

include_once 'ar_web_common_logged_in.php';


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
    case 'place_order_pay_sofort':
        $data = prepare_values(
            $_REQUEST, array(
                         'payment_account_key' => array('type' => 'key')


                     )
        );

        $website = get_object('Website', $_SESSION['website_key']);
        $store   = get_object('Store', $order->get('Order Store Key'));
        $account = get_object('Account', 1);

        place_order_pay_sofort($store, $order, $data, $customer, $website, $editor, $db, $account);


        break;


        break;
}

function place_order_pay_sofort($store, $order, $data, $customer, $website, $editor, $db, $account) {


    $payment_account         = get_object('Payment_Account', $data['payment_account_key']);
    $payment_account->editor = $editor;
    spl_autoload_register(
        function ($className) {
            //include_once 'external_libs/CommerceGuys/Addressing/AddressFormat/AddressFormatRepository.php';
            //include_once 'external_libs/CommerceGuys/Addressing/AddressFormat/AddressFormatRepositoryInterface.php';

            if (!preg_match('/Sofort/', $className)) {
                return;
            }

            $className = str_replace("_", "\\", $className);
            $className = ltrim($className, '\\');
            $fileName  = '';
            $namespace = '';
            if ($lastNsPos = strripos($className, '\\')) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
            }
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className).'.php';


            include_once 'external_libs/'.$fileName;


        }
    );



    $sofort = new Sofortueberweisung($payment_account->get('Payment Account Password'));


    ;
    $secret = hash('crc32', base64_url_encode(random_bytes(9)), false);


    $sofort->setAmount($order->get('Order To Pay Amount'));
    $sofort->setCurrencyCode($order->get('Order Currency'));



    $sofort->setLanguageCode( substr($website->get('Website Locale'), 0, 2));





    $sofort->setReason(sprintf('Payment order %s', $order->get('Order Public ID')));
    $sofort->setSuccessUrl('https://'.$website->get('Website URL').'/sofort.php?conf='.$secret.'&tx=-TRANSACTION-&order_key='.$order->id, true);
    $sofort->setAbortUrl('https://'.$website->get('Website URL').'/sofort.php?cancel=1&conf='.$secret.'&tx=-TRANSACTION-&order_key='.$order->id, true);


    $sofort->setNotificationUrl('https://'.$website->get('Website URL').'/sofort_notification.php');

    $sofort->sendRequest();
    if ($sofort->isError()) {

       // print_r($sofort);

        $response = array(

            'state' => 400,
            'msg'   => $sofort->getError()

        );
        echo json_encode($response);
        exit;
    } else {
        $transaction_key = $sofort->getTransactionId();


        $payment_data = array(
            'Payment Store Key'                   => $order->get('Order Store Key'),
            'Payment Website Key'                 => $website->id,
            'Payment Customer Key'                => $customer->id,
            'Payment Transaction Amount'          => $order->get('Order To Pay Amount'),
            'Payment Currency Code'               => $order->get('Order Currency'),
            'Payment Sender'                      => $order->get('Order Invoice Address Recipient'),
            'Payment Sender Country 2 Alpha Code' => $order->get('Order Invoice Address Country 2 Alpha Code'),
            'Payment Sender Email'                => $order->get('Order Email'),
            'Payment Created Date'                => gmdate('Y-m-d H:i:s'),


            'Payment Last Updated Date'  => gmdate('Y-m-d H:i:s'),
            'Payment Transaction Status' => 'Pending',
            'Payment Transaction ID'     => $transaction_key,
            'Payment Method'             => 'EPS',
            'Payment Location'           => 'Basket',
            'Payment Metadata'           => $secret

        );


        $payment = $payment_account->create_payment($payment_data);

        $order->add_payment($payment);

        $order->fast_update(
            array('Order Checkout Block Payment Account Key' => $payment_account->id)
        );

        $response = array(

            'state'    => 200,
            'redirect' => $sofort->getPaymentUrl(),
            //    'transaction_key'=>$transaction_key

        );
        echo json_encode($response);
        exit;
    }


}


?>
