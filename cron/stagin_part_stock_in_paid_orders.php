<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 November 2018 at 11:14:34 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';

$print_est = true;


$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Part paid by customer)',
    'Author Alias' => 'System (Part paid by customer)',
    'v'            => 3


);

$sql = sprintf("SELECT count(*) AS num FROM `Staging Dimension`  where `Staging Operation`='part_stock_in_paid_orders'");
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
    "SELECT `Staging Key`,`Staging Object Key` FROM `Staging Dimension`  where `Staging Operation`='part_stock_in_paid_orders' "
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part =  get_object('Part',$row['Staging Object Key']);

        if($part->id){

            $editor['Date'] = gmdate('Y-m-d H:i:s');
            $part->editor = $editor;

            $part->update_stock_in_paid_orders();



            $sql=sprintf('delete from `Staging Dimension`  where `Staging Key`=%d ',$row['Staging Key']);
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


?>
