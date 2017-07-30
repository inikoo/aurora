<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 July 2017 at 08:35:13 CEST, Tranava, Slovakia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';


require_once 'class.Store.php';
require_once 'class.Category.php';
require_once 'class.Payment_Service_Provider.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);



$sql='truncate `Payment Account Dimension`';
$db->exec($sql);


$braintree_service_provider = new Payment_Service_Provider('block', 'Btree');

if ($braintree_service_provider->id) {

    $account_data = array(
        'Payment Account Code'     => 'BTree',
        'Payment Account Name'     => 'Braintree',
        'Payment Account Block' => 'BTree',

    );

    $braintree_account = $braintree_service_provider->create_payment_account($account_data);


    $account_data = array(
        'Payment Account Code'     => 'Paypal_BT',
        'Payment Account Name'     => 'Paypal by Braintree',
        'Payment Account Block' => 'BTreePaypal',
    );

    $braintree_paypal_account = $braintree_service_provider->create_payment_account($account_data);


}


$paypal_service_provider = new Payment_Service_Provider('block', 'Paypal');

if ($paypal_service_provider->id) {

    $account_data = array(
        'Payment Account Code' => 'Paypal',
        'Payment Account Name' => 'Paypal',
    );

    $paypal_account = $paypal_service_provider->create_payment_account($account_data);

}



$bank_service_provider = new Payment_Service_Provider('block', 'Bank');

if ($braintree_service_provider->id) {

    $account_data = array(
        'Payment Account Code' => 'Bank',
        'Payment Account Name' => 'Bank',
    );

    $bank_account = $bank_service_provider->create_payment_account($account_data);

}


$sofort_service_provider = new Payment_Service_Provider('block', 'Sofort');

if ($sofort_service_provider->id) {

    $account_data = array(
        'Payment Account Code' => 'Sofort',
        'Payment Account Name' => 'Sofort',
    );

    $sofort_account = $sofort_service_provider->create_payment_account($account_data);

}


$cash_service_provider = new Payment_Service_Provider('block', 'Cash');

if ($cash_service_provider->id) {

    $account_data = array(
        'Payment Account Code'  => 'Cash',
        'Payment Account Name'  => 'Cash',
        'Payment Account Block' => 'Cash',
    );

    $sofort_account = $cash_service_provider->create_payment_account($account_data);

    $account_data = array(
        'Payment Account Code'  => 'Other',
        'Payment Account Name'  => 'Other',
        'Payment Account Block' => 'Other',
    );

    $sofort_account = $cash_service_provider->create_payment_account($account_data);

}

$cond_service_provider = new Payment_Service_Provider('block', 'ConD');

if ($cond_service_provider->id) {

    $account_data = array(
        'Payment Account Code' => 'ConD',
        'Payment Account Name' => 'Cash on delivery',
    );

    $sofort_account = $cond_service_provider->create_payment_account($account_data);

}


$sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $store = new Store('id', $row['Store Key']);


        $internal_accounts_service_provider = new Payment_Service_Provider('type', 'Account');

        $account_data = array(
            'Payment Account Code'          => 'IA_'.$store->get('Code'),
            'Payment Account Name'          => sprintf(_('%s accounts'), $store->get('Code')),
            'Payment Account Online Refund' => 'No',
        );

        $internal_payment_account = $internal_accounts_service_provider->create_payment_account($account_data);

        $data = array(
            'Store Key'       => $store->id,
            'Status'          => 'Active',
            'Show In Cart'    => 'No',
            'Show Cart Order' => 0,

        );

        $internal_payment_account->assign_to_store($data);



        $data['Show Cart Order'] = 3;
        $sofort_account->assign_to_store($data);


        $data['Show In Cart']    = 'Yes';
        $data['Show Cart Order'] = 1;
        $braintree_account->assign_to_store($data);

        $data['Show Cart Order'] = 4;
        $bank_account->assign_to_store($data);


        $data['Show Cart Order'] = 2;
        $braintree_paypal_account->assign_to_store($data);


        $data['Status']    = 'Inactive';
        $data['Show In Cart']    = 'No';

        $data['Show Cart Order'] = 2;
        $paypal_account->assign_to_store($data);



    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
