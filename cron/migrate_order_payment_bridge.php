<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 July 2018 at 22:11:30 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'vendor/autoload.php';

require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';
require_once 'utils/object_functions.php';
include_once 'class.Billing_To.php';
include_once 'class.Store.php';

use CommerceGuys\Addressing\Address;
use CommerceGuys\Addressing\Formatter\PostalLabelFormatter;
use CommerceGuys\Addressing\AddressFormat\AddressFormatRepository;
use CommerceGuys\Addressing\Country\CountryRepository;
use CommerceGuys\Addressing\Subdivision\SubdivisionRepository;


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);


$sql = sprintf('SELECT `Payment Key`,`Payment Order Key`,`Payment Transaction Amount`,P.`Payment Account Key`,P.`Payment Service Provider Key`,`Payment Account Type` FROM `Payment Dimension` P left join `Store Dimension` on (`Store Key`=`Payment Store Key`) left join `Payment Account Dimension` PA on (PA.`Payment Account Key`=P.`Payment Account Key`) 
where `Store Version`=2  ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $order = get_object('Order', $row['Payment Order Key']);

        if ($order->id) {


            $sql = sprintf(
                'Insert into `Order Payment Bridge` (`Order Key`,`Payment Key`,`Payment Account Key`,`Payment Service Provider Key`,`Amount`,`Is Account Payment`)  values  (%d,%d,%d,%d,%.2f,%s) ON DUPLICATE KEY UPDATE 
            `Amount`=%.2f ,`Payment Account Key`=%d,`Payment Service Provider Key`=%d     ',
                $order->id, $row['Payment Key'], $row['Payment Account Key'],
                $row['Payment Service Provider Key'], $row['Payment Transaction Amount'], prepare_mysql(($row['Payment Account Type']=='Account'?'Yes':'No')), $row['Payment Transaction Amount'], $row['Payment Account Key'],
                $row['Payment Service Provider Key']


            );
            //print "$sql\n";
//            exit;

            $db->exec($sql);

        }
    }
}



$sql = sprintf('SELECT * FROM `Order Payment Bridge` ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $order   = get_object('Order', $row['Order Key']);


        if (!$order->id) {


            $sql = sprintf('delete from `Order Payment Bridge` where `Payment Key`=%d and `Order Key`=%d  ', $row['Payment Key'], $row['Order Key']);
            //print "$sql\n";
            //print_r($row);

            //print $row['Order Key'].','.$row['Payment Key'].','.$row['Amount']."\n";

            $db->exec($sql);
            continue;
        }


    }
}




$sql = sprintf('SELECT * FROM `Order Payment Bridge` ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $payment = get_object('Payment', $row['Payment Key']);




        if (!$payment->id) {

            $store = get_object('Store', $order->get('Store Key'));

            if ($store->get('Store Version') == 2) {

                $sql = sprintf('delete from `Order Payment Bridge` where `Payment Key`=%d and `Order Key`=%d  ', $row['Payment Key'], $row['Order Key']);
                //print "$sql\n";
               // print_r($row);
                print $row['Order Key'].','.$row['Payment Key'].','.$row['Amount']."\n";

                 $db->exec($sql);
            }


        }
    }
}



$sql = sprintf('SELECT `Payment Key`,`Payment Order Key`,`Payment Invoice Key`,`Payment Transaction Amount`,P.`Payment Account Key`,P.`Payment Service Provider Key`,`Payment Account Type` FROM `Payment Dimension` P left join `Store Dimension` on (`Store Key`=`Payment Store Key`) left join `Payment Account Dimension` PA on (PA.`Payment Account Key`=P.`Payment Account Key`) 
where `Store Version`=2  ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $order = get_object('Order', $row['Payment Order Key']);
        $invoice = get_object('Invoice', $row['Payment Invoice Key']);

        if ($order->id and $invoice->id) {


            $sql = sprintf(
                'Insert into `Order Payment Bridge` (`Order Key`,`Invoice Key`,`Payment Key`,`Payment Account Key`,`Payment Service Provider Key`,`Amount`,`Is Account Payment`)  values  (%d,%d,%d,%d,%d,%.2f,%s) ON DUPLICATE KEY UPDATE 
            `Invoice Key`=%d    ',
                $row['Payment Order Key'],  $invoice->id, $row['Payment Key'], $row['Payment Account Key'],
                $row['Payment Service Provider Key'], $row['Payment Transaction Amount'], prepare_mysql(($row['Payment Account Type']=='Account'?'Yes':'No')),$invoice->id

            );
            //print "$sql\n";
            //            exit;

            $db->exec($sql);

        }
    }
}

