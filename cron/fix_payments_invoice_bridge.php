<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 November 2017 at 15:12:17 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


require_once 'utils/parse_natural_language.php';

include_once 'utils/object_functions.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$account = new Account();


$sql = sprintf('SELECT * FROM `Order Payment Bridge`  ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $order = get_object('order', $row['Order Key']);
        $payment = get_object('payment', $row['Payment Key']);
        if ($order->id and $payment->id) {
            $invoice=get_object('invoice', $order->data['Order Invoice Key']);
            if ($invoice->id) {
                $invoice->add_payment($payment);

            }
        }



    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


?>
