<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 November 2018 at 15:53:47 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';





$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);




$sql = sprintf("SELECT `Payment Service Provider Key` FROM `Payment Service Provider Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $payment_service_provider = get_object('Payment Service Provider',$row['Payment Service Provider Key']);

        $payment_service_provider->update_payments_data();
        $payment_service_provider->update_accounts_data();


    }

}

$sql = sprintf("SELECT `Payment Account Key` FROM `Payment Account Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $payment_account = get_object('Payment_Account',$row['Payment Account Key']);

        $payment_account->update_payments_data();
        $payment_account->update_stores_data();


    }

}