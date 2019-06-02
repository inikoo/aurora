<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:02-06-2019 13:07:46 BSTSheffield, UK
 Copyright (c) 2019, Inikoo

 Version 3

*/


$dropshipping_location_key = 15221;

require_once 'common.php';
include 'class.Customer.php';
include 'class.PartLocation.php';
include 'class.TaxCategory.php';


require_once 'class.Country.php';
require_once 'utils/get_addressing.php';
include_once 'utils/data_entry_picking_aid.class.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (Magento import)'
);
$store  = get_object('Store', 9);



$sql = sprintf('select  `Delivery Note ID` ,count(`Delivery Note ID`) ,group_concat(`Delivery Note Key`) as dn_keys  from `Delivery Note Dimension` where `Delivery Note Store Key`=9  group by `Delivery Note ID` HAVING COUNT(`Delivery Note ID`) > 1  ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $dn_keys = preg_split('/\,/', $row['dn_keys']);

        sort($dn_keys);

        array_pop($dn_keys);

        foreach ($dn_keys as $dn_key) {

            $dn=get_object('Delivery Note',$dn_key);



            print $dn->id.' '.$dn->get('`Delivery Note ID').' '.$dn->get('Delivery Note Date').' '.$dn->get('Delivery Note Number Ordered Parts')."\n";
            $dn->delete(true);

        }
        print "\n";

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}




$sql = sprintf('select  `Invoice Public ID` ,count(`Invoice Public ID`) ,group_concat(`Invoice Key`) as invoice_keys  from `Invoice Dimension` where `Invoice Store Key`=9  group by `Invoice Public ID` HAVING COUNT(`Invoice Public ID`) > 1  ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $invoice_keys = preg_split('/\,/', $row['invoice_keys']);

        sort($invoice_keys);

        array_pop($invoice_keys);

        foreach ($invoice_keys as $invoice_key) {

            $invoice=get_object('Invoice',$invoice_key);



            print $invoice->id.' '.$invoice->get('Public ID').' '.$invoice->get('Date').' '.$invoice->get('number_otfs').' '.$invoice->get('number_onptf')."\n";
            $invoice->delete('',true);

        }
        print "\n";

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


