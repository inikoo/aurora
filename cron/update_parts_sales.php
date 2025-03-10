<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:19 April 2016 at 11:32:28 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'class.Part.php';
require_once 'class.Product.php';
require_once 'class.Page.php';
require_once 'class.Supplier.php';
require_once 'class.Category.php';

$print_est = true;


$where = " where `Part SKU`=5291 ";
//	$where=" where `Part Reference` like 'jbb-%' ";

$where = "";

$sql = sprintf("SELECT count(*) AS num FROM `Part Dimension` %s", $where);
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

$lap_time0 = date('U');
$contador  = 0;

$sql = sprintf(
    "SELECT `Part SKU` FROM `Part Dimension`  %s  ORDER BY `Part SKU`", $where
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = new Part($row['Part SKU']);

        $part->load_acc_data();

        $part->update_sales_from_invoices('Total');
        $part->update_sales_from_invoices('Week To Day');
        $part->update_sales_from_invoices('Month To Day');
        $part->update_sales_from_invoices('Quarter To Day');
        $part->update_sales_from_invoices('Year To Day');

        $part->update_sales_from_invoices('1 Year');
        $part->update_sales_from_invoices('1 Quarter');
        $part->update_sales_from_invoices('Last Week');
        $part->update_sales_from_invoices('Last Month');


        $part->update_previous_quarters_data();

        $part->update_previous_years_data();


        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'Pa '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
                )."h  ($contador/$total) \r";
        }


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
