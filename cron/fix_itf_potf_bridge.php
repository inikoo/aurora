<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-07-2019 22:31:51 MYTKuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';


///'InProcess','Consolidated','Dispatched','Received','Checked','Placed','Costing','Cancelled','InvoiceChecked'
$contador  = 0;

$sql = sprintf(
    'SELECT `Metadata`,`Supplier Delivery State`,`Purchase Order Transaction Fact Key` FROM `Purchase Order Transaction Fact` POTF left join `Supplier Delivery Dimension`  SDD on (POTF.`Supplier Delivery Key`=SDD.`Supplier Delivery Key`)   where SDD.`Supplier Delivery State` in ("Costing","Placed","InvoiceChecked")  '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


      //  print_r($row);

        $metadata=json_decode($row['Metadata'],true);


        if(is_array($metadata)){
            foreach($metadata['placement_data'] as $key=>$value ){

                $sql=sprintf('insert into `ITF POTF Bridge`  values (%d,%d,%s)  ',$value['oif_key'],$row['Purchase Order Transaction Fact Key'],prepare_mysql($row['Supplier Delivery State']));
                // print"$sql\n";
                $db->exec($sql);
            }
        }



    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}



