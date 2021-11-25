<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-06-2019 14:10:43 BST  Sheffield, UK
 Copyright (c) 2019, Inikoo

 Version 3

*/


require_once __DIR__.'/cron_common.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script '
);


switch (DNS_ACCOUNT_CODE) {
    case 'ES':
        $store_key = 3;
        break;
    case 'AWEU':
        $store_key = 18;

        break;
    case 'AW':
        $store_key = 19;
        break;
    default:
        exit('cacaca');
}



$sql = "select `Order Date`,`Order Public ID`,`Order Key` from `Order Dimension` O left join `Customer Client Dimension` C on (`Customer Client Key`=`Order Customer Client Key`) where `Order Store Key`=? and `Customer Client Key` is null and `Order State`='InBasket' ";
$stmt = $db->prepare($sql);
$stmt->execute(
    [
        $store_key
    ]
);
while ($row = $stmt->fetch()) {

    $order=get_object('Order',$row['Order Key']);
    $order->editor;
    print $order->get('Order Public ID')."\n";
    $order->cancel(_('Cancelled because client was deleted'));
    $order->fast_update_json_field('Order Metadata', 'cancel_reason', 'client_deleted');
    $order->fast_update(['Order Customer Client Key' => null]);

}


$sql = "select `Order Date`,`Order Public ID`,`Order Key` from `Order Dimension` O left join `Customer Client Dimension` C on (`Customer Client Key`=`Order Customer Client Key`) where `Order Store Key`=? and `Customer Client Key` is null and `Order State`='Cancelled' and aiku_note is null ";
$stmt = $db->prepare($sql);
$stmt->execute(
    [
        $store_key
    ]
);
while ($row = $stmt->fetch()) {

    $order=get_object('Order',$row['Order Key']);;
    print $order->get('Order Public ID')." ** \n";

    $order->fast_update(['aiku_note' => 'ignore-zombie-client']);

}


$sql = "select `Order Date`,`Order Public ID`,`Order Key` from `Order Dimension` O left join `Customer Dimension` C on (`Customer Key`=`Order Customer Key`) where `Order Store Key`!=? and `Customer Key` is null and `Order State` not in ('Approved','Dispatched','Cancelled') and aiku_note is null ";
$stmt = $db->prepare($sql);
$stmt->execute(
    [
        $store_key
    ]
);
while ($row = $stmt->fetch()) {

    $order=get_object('Order',$row['Order Key']);;
    print $order->get('Order Public ID')." *** \n";

    $order->fast_update(['aiku_note' => 'ignore-basket']);

}
