<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 September 2016 at 18:48:59 GMT+8, Kuala Lumpur, Malaysia
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

$print_est = true;

print date('l jS \of F Y h:i:s A')."\n";


$where = 'where `Product ID`=971';
$where = '';
//$where='where `Product Code` like "JBB-%"';
$sql = sprintf(
    "SELECT count(*) AS num FROM `Product Dimension` %s", $where
);
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
}

$lap_time0 = date('U');
$contador  = 0;


$sql = sprintf(
    "SELECT `Product ID` FROM `Product Dimension` %s ORDER BY `Product ID` DESC ", $where
);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $product = get_object('Product', $row['Product ID']);

        $product->load_acc_data();



        $product->update_previous_years_data();
        $product->update_previous_quarters_data();

        $product->update_sales_from_invoices('Total');
        $product->update_sales_from_invoices('Week To Day');

        $product->update_sales_from_invoices('Month To Day');
        $product->update_sales_from_invoices('Quarter To Day');

        $product->update_sales_from_invoices('Year To Day');
        $product->update_sales_from_invoices('1 Year');
        $product->update_sales_from_invoices('1 Quarter');



        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'P   '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
                )."h  ($contador/$total) \r";
        }

    }

}


