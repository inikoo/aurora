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

$store_key=3;




$sql='select * from `Payment Account Site Bridge` where `Store Key`=?';

$stmt = $db->prepare($sql);
if ($stmt->execute(
    array(
        $store_key
    )
)) {
    while ($row = $stmt->fetch()) {
        $sql=sprintf('insert into `Payment Account Store Bridge` (`Payment Account Store Store Key`,`Payment Account Store Website Key`,`Payment Account Store Payment Account Key`,
                                            `Payment Account Store Valid From`,`Payment Account Store Status`,`Payment Account Store Show In Cart`,`Payment Account Store Show Cart Order`)  values (%d,%d,%d,%s,%s,%s,%d)',

                     $row['Store Key'],$row['Site Key'],$row['Payment Account Key'],prepare_mysql($row['Valid From']),prepare_mysql($row['Status']),prepare_mysql($row['Show In Cart']),$row['Show Cart Order']


        );


      //  print "$sql\n";
        $db->exec($sql);
        }
} else {
    print_r($error_info = $this->db->errorInfo());
    exit();
}


$sql = sprintf('SELECT `Payment Key` FROM `Payment Dimension` left join `Store Dimension` on (`Store Key`=`Payment Store Key`)  where   `Store Key`=%d',$store_key);

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
