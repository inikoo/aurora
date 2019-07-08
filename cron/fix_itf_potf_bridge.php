<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-07-2019 22:31:51 MYTKuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';


$manufacturer_key=0;

$sql = 'SELECT `Supplier Production Supplier Key` FROM `Supplier Production Dimension` left join `Supplier Dimension` on (`Supplier Key`=`Supplier Production Supplier Key`) WHERE `Supplier Type`!=?';

$stmt = $db->prepare($sql);
$stmt->execute(
    array('Archived')
);
if ($row = $stmt->fetch()) {
    $manufacturer_key = $row['Supplier Production Supplier Key'];

}




$db->exec('truncate `ITF POTF Costing Done Bridge`');
$db->exec($sql);
///'InProcess','Consolidated','Dispatched','Received','Checked','Placed','Costing','Cancelled','InvoiceChecked'
$contador  = 0;

$sql = sprintf(
    'SELECT `Metadata`,`Supplier Delivery State`,`Purchase Order Transaction Fact Key` FROM `Purchase Order Transaction Fact` POTF left join `Supplier Delivery Dimension`  SDD on (POTF.`Supplier Delivery Key`=SDD.`Supplier Delivery Key`)   where
                                                                                                                                                                                                                                                   POTF.`Supplier Key`!=%d  and
                                                                                                                                                                                                                                                 SDD.`Supplier Delivery State` ="InvoiceChecked"  ',
    $manufacturer_key
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


      //  print_r($row);

        $metadata=json_decode($row['Metadata'],true);


        if(is_array($metadata)){
            foreach($metadata['placement_data'] as $key=>$value ){

                $sql=sprintf('insert into `ITF POTF Costing Done Bridge`  values (%d,%d)  ',$value['oif_key'],$row['Purchase Order Transaction Fact Key']);
                $db->exec($sql);
            }
        }



    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}



