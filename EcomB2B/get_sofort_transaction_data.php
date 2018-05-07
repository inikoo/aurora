<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 May 2018 at 17:34:20 CEST, Mijas Costa, Spain
 Copyright (c) 2018, Inikoo

 Version 3

*/


namespace Sofort\SofortLib;



if (!isset($_REQUEST['key'])) {
   // exit('');
}
if (!isset($_REQUEST['transaction_key'])) {
    exit('');
}


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



//$notification = new Notification();
//$notification->addTransaction($_REQUEST['transaction_key']);


$TransactionData = new TransactionData('84606:368503:d1ae7fa642559001aec0ba8bf75f7292');
$TransactionData->addTransaction($_REQUEST['transaction_key']);
$TransactionData->setApiVersion('2.0');
$TransactionData->sendRequest();


$output = array();
$methods = array(
    'getAmount' => '',
    'getAmountRefunded' => '',
    'getCount' => '',
    'getPaymentMethod' => '',
    'getConsumerProtection' => '',
    'getStatus' => '',
    'getStatusReason' => '',
    'getStatusModifiedTime' => '',
    'getLanguageCode' => '',
    'getCurrency' => '',
    'getTransaction' => '',
    'getReason' => array(0,0),
    'getUserVariable' => 0,
    'getTime' => '',
    'getProjectId' => '',
    'getRecipientHolder' => '',
    'getRecipientAccountNumber' => '',
    'getRecipientBankCode' => '',
    'getRecipientCountryCode' => '',
    'getRecipientBankName' => '',
    'getRecipientBic' => '',
    'getRecipientIban' => '',
    'getSenderHolder' => '',
    'getSenderAccountNumber' => '',
    'getSenderBankCode' => '',
    'getSenderCountryCode' => '',
    'getSenderBankName' => '',
    'getSenderBic' => '',
    'getSenderIban' => '',
);

foreach($methods as $method => $params) {
    if(count($params) == 2) {
        $output[] = $method . ': ' . $TransactionData->$method($params[0], $params[1]);
    } else if($params !== '') {
        $output[] = $method . ': ' . $TransactionData->$method($params);
    } else {
        $output[] = $method . ': ' . $TransactionData->$method();
    }
}

print_r($output);


?>