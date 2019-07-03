<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-07-2019 19:46:16 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';


if ($account->get('Account Add Stock Value Type') != 'Last Price') {
    exit("This script is only for Account Add Stock Value Type:  Last Price \n");
}

$manufacturer_key=0;

$sql = 'SELECT `Supplier Production Supplier Key` FROM `Supplier Production Dimension` left join `Supplier Dimension` on (`Supplier Key`=`Supplier Production Supplier Key`) WHERE `Supplier Type`!=?';

$stmt = $db->prepare($sql);
$stmt->execute(
    array('Archived')
);
if ($row = $stmt->fetch()) {
    $manufacturer_key = $row['Supplier Production Supplier Key'];

}




$print_est = true;


$sql = sprintf("SELECT count(*) AS num FROM `Part Dimension` ");
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
    'SELECT `Part SKU` FROM `Part Dimension`   '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = get_object('Part', $row['Part SKU']);





        $sql = sprintf(
            'select  *, POTF.`Supplier Key`,`Date`,(`Inventory Transaction Amount`/`Inventory Transaction Quantity`) as value_per_sko ,`ITF POTF Costing Done POTF Key` from    `ITF POTF Costing Done Bridge` B  left join     `Inventory Transaction Fact` ITF   on  (B.`ITF POTF Costing Done ITF Key`=`Inventory Transaction Key`)  
    left join `Purchase Order Transaction Fact` POTF on  (`Purchase Order Transaction Fact Key`=`ITF POTF Costing Done POTF Key`) where  `Inventory Transaction Amount`>0 and `Inventory Transaction Quantity`>0 and   B.`ITF POTF Costing Done ITF State`="InvoiceChecked"  and  `Inventory Transaction Section`="In"    and ITF.`Part SKU`=%d    and POTF.`Supplier Key`!=%d order by `Date` desc  limit 1 ',
            $part->id,$manufacturer_key
        );



        if ($result=$db->query($sql)) {
            if ($row = $result->fetch()) {


            //    print_r($row);

                $value_per_sko=$row['value_per_sko'];

            }else{
                $part->update_cost();
                $value_per_sko=$part->get('Part Cost');
            }
          //  print "$value_per_sko\n\n\n";


             $part->update_field_switcher('Part Cost in Warehouse',$value_per_sko , 'no_history');
        }else{
            print $sql;
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



