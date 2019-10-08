<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 November 2018 at 01:10:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';

$print_est = false;


$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Stack product sales)',
    'Author Alias' => 'System (Stack product sales)',


);

$sql = sprintf("SELECT count(*) AS num FROM `Stack Dimension`  where `Stack Operation`='product_sales'");
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
$lap_time1= date('U');
$contador  = 0;


$sql = sprintf(
    "SELECT `Stack Key`,`Stack Object Key` FROM `Stack Dimension`  where `Stack Operation`='product_sales' ORDER BY RAND() "
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $product =  get_object('Product',$row['Stack Object Key']);

        if($product->id){

            $sql=sprintf('select `Stack Key` from `Stack Dimension` where `Stack Key`=%d ',$row['Stack Key']);

            if ($result2=$db->query($sql)) {
                if ($row2 = $result2->fetch()) {

                    $sql=sprintf('delete from `Stack Dimension`  where `Stack Key`=%d ',$row['Stack Key']);
                    $db->exec($sql);

                    $editor['Date'] = gmdate('Y-m-d H:i:s');
                    $product->editor = $editor;

                    $product->update_sales_from_invoices('Total', true, false);
                    $product->update_sales_from_invoices('Week To Day', true, false);
                    $product->update_sales_from_invoices('Month To Day', true, false);
                    $product->update_sales_from_invoices('Quarter To Day', true, false);
                    $product->update_sales_from_invoices('Year To Day', true, false);

                    $product->update_sales_from_invoices('Today', true, false);
                }
            }





        }else{
            $sql=sprintf('delete from `Stack Dimension`  where `Stack Key`=%d ',$row['Stack Key']);
            $db->exec($sql);
        }

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

if($total>0){
    printf("%s: %s/%s %.2f min Product Sales\n",gmdate('Y-m-d H:i:s'),$contador,$total,($lap_time1 - $lap_time0)/60);
}

?>
