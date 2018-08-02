<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 August 2018 at 18:51:04 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'vendor/autoload.php';

require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';
require_once 'utils/object_functions.php';
include_once 'class.Billing_To.php';
include_once 'class.Store.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$account = new Account();


$sql = sprintf('SELECT `Payment Key` FROM `Payment Dimension` left join `Store Dimension` on (`Store Key`=`Payment Store Key`)  where   `Store Version`=1');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $payment = get_object('Payment', $row['Payment Key']);

        $data_to_update                               = array();
        $data_to_update['Payment Transaction Amount'] = $payment->get('Payment Amount');

       // print_r($data_to_update);
        $payment->fast_update($data_to_update);
    }


} else {
    print_r($error_info = $db->errorInfo());
    print $sql;
    exit;
}


?>
