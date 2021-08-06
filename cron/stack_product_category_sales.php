<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 November 2018 at 01:18:11 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';

$print_est = false;


$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Stack product category sales)',
    'Author Alias' => 'System (Stack product category sales)',
    'v'            => 3


);

$sql = sprintf("SELECT count(*) AS num FROM `Stack Dimension`  where `Stack Operation` in ('product_family_sales','product_department_sales')");
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

require_once 'conf/timeseries.php';
require_once 'class.Timeserie.php';

$lap_time0 = date('U');
$lap_time1= date('U');
$contador  = 0;


$sql = sprintf(
    "SELECT `Stack Key`,`Stack Object Key` FROM `Stack Dimension`  where `Stack Operation` in ('product_family_sales','product_department_sales') ORDER BY RAND()"
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $category = get_object('Category', $row['Stack Object Key']);

        if ($category->id) {



            $sql=sprintf('select `Stack Key` from `Stack Dimension` where `Stack Key`=%d ',$row['Stack Key']);

            if ($result2=$db->query($sql)) {
                if ($row2 = $result2->fetch()) {
                    $sql = sprintf('delete from `Stack Dimension`  where `Stack Key`=%d ', $row['Stack Key']);
                    $db->exec($sql);

                    $editor['Date']   = gmdate('Y-m-d H:i:s');
                    $category->editor = $editor;

                    $category->update_sales_from_invoices('Total', true, false);
                    $category->update_sales_from_invoices('Week To Day', true, false);
                    $category->update_sales_from_invoices('Month To Day', true, false);
                    $category->update_sales_from_invoices('Quarter To Day', true, false);
                    $category->update_sales_from_invoices('Year To Day', true, false);

                    $category->update_sales_from_invoices('Today', true, false);


                    $timeseries      = get_time_series_config();
                    $timeseries_data = $timeseries['ProductCategory'];
                    foreach ($timeseries_data as $time_series_data) {


                        $time_series_data['Timeseries Parent']     = 'Category';
                        $time_series_data['Timeseries Parent Key'] = $category->id;
                        $time_series_data['editor']                = $editor;


                        $object_timeseries = new Timeseries('find', $time_series_data, 'create');
                        $category->update_product_timeseries_record($object_timeseries, gmdate('Y-m-d'), gmdate('Y-m-d'));


                    }
            	}
            }else {
            	print_r($error_info=$db->errorInfo());
            	print "$sql\n";
            	exit;
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
if($total>0) {
    printf("%s: %s/%s %.2f min Prod Cat sales\n", gmdate('Y-m-d H:i:s'), $contador, $total, ($lap_time1 - $lap_time0) / 60);
}

?>
