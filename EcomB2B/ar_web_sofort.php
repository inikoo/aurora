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

        $website=get_object('Website',$_SESSION['website_key']);
        $store=get_object('Store',$order->get('Order Store Key'));
        $account=get_object('Account',1);

        place_order_pay_sofort($store, $order, $data,$customer, $website, $editor, $db, $account);


        break;
   

        break;
}

function place_order_pay_sofort($store, $order, $data,$customer, $website, $editor, $db, $account) {


    $payment_account=get_object('Payment_Account',$data['payment_account_key']);

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




    $sofort->setAmount(10.21);
    $sofort->setCurrencyCode('EUR');
    $sofort->setReason(_('Payment'));
    $sofort->setSuccessUrl('https://'.$website->get('Website URL').'/sofort.php', true); // i.e. http://my.shop/order/success
    $sofort->setAbortUrl('https://'.$website->get('Website URL').'/checkout.sys');
    $sofort->setNotificationUrl('https://'.$website->get('Website URL').'/sofort.php');

    $sofort->sendRequest();
    if ($sofort->isError()) {

        print_r($sofort);

        $response = array(

            'state' => 400,
            'msg'   => $sofort->getError()

        );
        echo json_encode($response);
        exit;
    } else {
        $transaction_key = $sofort->getTransactionId();
        $response        = array(

            'state'    => 200,
            'redirect' => $sofort->getPaymentUrl(),
        //    'transaction_key'=>$transaction_key

        );
        echo json_encode($response);
        exit;
    }


}




?>
