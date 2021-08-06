<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 220/11/2018, 10:50, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

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
    'Author Name'  => 'System (Stock change)',
    'Author Alias' => 'System (Stock change)',
    'v'            => 3


);

$sql = sprintf("SELECT count(*) AS num FROM `Stack Dimension`  where `Stack Operation`='full_after_part_stock_update_legacy'");
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
$lap_time1 = date('U');

$contador = 0;


$sql = sprintf(
    "SELECT `Stack Key`,`Stack Object Key` FROM `Stack Dimension`  where `Stack Operation`='full_after_part_stock_update_legacy' ORDER BY RAND()"
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = get_object('Part', $row['Stack Object Key']);

        if ($part->id) {


            $sql=sprintf('select `Stack Key` from `Stack Dimension` where `Stack Key`=%d ',$row['Stack Key']);

            if ($result2=$db->query($sql)) {
                if ($row2 = $result2->fetch()) {

                    $sql = sprintf('delete from `Stack Dimension`  where `Stack Key`=%d ', $row['Stack Key']);
                    $db->exec($sql);

                    $editor['Date'] = gmdate('Y-m-d H:i:s');
                    $part->editor   = $editor;

                    $part->activate();
                    $part->discontinue_trigger();


                    $part->update_available_forecast();
                    $part->update_stock_status();

                    foreach ($part->get_products('objects') as $product) {

                        $editor['Date']  = gmdate('Y-m-d H:i:s');
                        $product->editor = $editor;

                        $product->fork = true;
                        $product->update_availability(false);
                    }




                }
            }



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
    printf("%s: %s/%s %.2f min Part update legacy\n",gmdate('Y-m-d H:i:s'),$contador,$total,($lap_time1 - $lap_time0)/60);
}

?>
