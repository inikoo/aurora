<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 12 May 2016 at 18:36:49 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$default_DB_link = @mysql_connect($dns_host, $dns_user, $dns_pwd);
if (!$default_DB_link) {
    print "Error can not connect with database server\n";
}
$db_selected = mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
    print "Error can not access the database\n";
    exit;
}
mysql_set_charset('utf8');
mysql_query("SET time_zone='+0:00'");


require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';
include_once 'class.CurrencyExchange.php';
require_once 'class.PurchaseOrder.php';
require_once 'class.Account.php';

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$account = new Account();

$sql = sprintf(
    'UPDATE  `Purchase Order Dimension` SET `Part Package Description`=`Part Unit Description`;  '
);
$db->exec($sql);


$sql = sprintf('SELECT * FROM `Purchase Order Dimension`  ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $purchase_order    = new PurchaseOrder($row['Purchase Order Key']);
        $currency_exchange = new CurrencyExchange(
            $row['Purchase Order Currency Code'].$account->get(
                'Account Currency'
            )
        );
        $exchange          = $currency_exchange->get_exchange();
        $purchase_order->update(
            array(
                'Purchase Order Currency Exchange'   => $exchange,
                'Purchase Order Parent Key'          => $row['Purchase Order Supplier Key'],
                'Purchase Order Parent Code'         => $row['Purchase Order Supplier Code'],
                'Purchase Order Parent Name'         => $row['Purchase Order Supplier Name'],
                'Purchase Order Parent Contact Name' => $row['Purchase Order Supplier Contact Name'],
                'Purchase Order Parent Email'        => $row['Purchase Order Supplier Email'],
                'Purchase Order Parent Telephone'    => $row['Purchase Order Supplier Telephone'],
                'Purchase Order Parent Address'      => $row['Purchase Order Supplier Address'],

            ), 'no_history'
        );

        print "$exchange\n";
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
