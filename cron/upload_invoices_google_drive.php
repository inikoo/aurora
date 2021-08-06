<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1:31 pm Wednesday, 1 July 2020 (MYT) Kuala Lumpur Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
include_once 'class.google_drive.php';


/**
 * var $google_drive google_drive
 */
$google_drive = new google_drive($account);


set_stores($db, $google_drive);


$sql  = "select `Invoice Key` from `Invoice Dimension` where year(`Invoice Date`)=? order by `Invoice Key` desc";
$stmt = $db->prepare($sql);
$stmt->execute(
    ['2020']
);
while ($row = $stmt->fetch()) {
    $invoice = get_object('Invoice', $row['Invoice Key']);

    print $invoice->get('Public ID')."\n";

    $invoice->upload_pdf_to_google_drive($google_drive);



}


function set_stores($db, $google_drive) {

    $sql  = "select `Store Key` from `Store Dimension`";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $store = get_object('Store', $row['Store Key']);
        $google_drive->set_store_folder($store);
    }


}
