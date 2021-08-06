<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 November 2018 at 17:07:32 GMT+8, Kuala Lumpur, Malaysia
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
    'Author Name'  => 'System (Stack warehouse ISF)',
    'Author Alias' => 'System (Stack warehouse ISF)',



);


$sql = sprintf("SELECT count(*) AS num FROM `Stack Dimension`  where `Stack Operation`='warehouse_ISF'");
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
    "SELECT `Stack Key`,`Stack Object Key` FROM `Stack Dimension`  where `Stack Operation`='warehouse_ISF' ORDER BY RAND()"
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $warehouse =  get_object('Warehouse',$row['Stack Object Key']);

        if($warehouse->id){


            $sql=sprintf('select `Stack Key` from `Stack Dimension` where `Stack Key`=%d ',$row['Stack Key']);

            if ($result2=$db->query($sql)) {
                if ($row2 = $result2->fetch()) {

                    $sql=sprintf('delete from `Stack Dimension`  where `Stack Key`=%d ',$row['Stack Key']);
                    $db->exec($sql);

                    $editor['Date'] = gmdate('Y-m-d H:i:s');
                    $warehouse->editor = $editor;


                    $warehouse->update_inventory_snapshot(gmdate('Y-m-d'));





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
    printf("%s: WI  %s/%s %.2f min \n",gmdate('Y-m-d H:i:s'),$contador,$total,($lap_time1 - $lap_time0)/60);
}


?>
