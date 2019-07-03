<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 03-07-2019 14:49:15 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';


if ($account->get('Account Add Stock Value Type') != 'Last Price') {
    exit("This script is only for Account Add Stock Value Type:  Last Price \n");
}

$manufacturer_key = 0;

$sql = 'SELECT `Supplier Production Supplier Key` FROM `Supplier Production Dimension` left join `Supplier Dimension` on (`Supplier Key`=`Supplier Production Supplier Key`) WHERE `Supplier Type`!=?';

$stmt = $db->prepare($sql);
$stmt->execute(
    array('Archived')
);
if ($row = $stmt->fetch()) {
    $manufacturer_key = $row['Supplier Production Supplier Key'];

}


$sql = sprintf("SELECT `Supplier Delivery Key`  FROM `Supplier Delivery Dimension` where `Supplier Delivery State`  in ('Costing','Placed','InvoiceChecked')  and `Supplier Delivery Parent`='Supplier' and `Supplier Delivery Parent Key`=%d   ", $manufacturer_key);

if ($resultx = $db->query($sql)) {
    foreach ($resultx as $rowx) {


        $delivery         = get_object('SupplierDelivery', $rowx['Supplier Delivery Key']);


        $sql = sprintf(
            'select `Supplier Part Part SKU`,`Purchase Order Transaction Fact Key`,`Metadata` from `Purchase Order Transaction Fact`  POTF left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`) 
where `Supplier Delivery Key`=%d   and `Supplier Part Part SKU`=273 group by `Supplier Part Part SKU` ',
            $rowx['Supplier Delivery Key']
        );
        if ($result = $db->query($sql)) {

            foreach ($result as $row) {

               // print_r($row);

                if ($row['Metadata'] != '') {
                    $metadata = json_decode($row['Metadata'], true);


                    if (isset($metadata['placement_data'])) {


                        $min_date     = '';
                        $total_placed = 0;
                        foreach ($metadata['placement_data'] as $placement_data) {


                            $total_placed += $placement_data['qty'];


                        }

                        //   {"placement_data":[{"oif_key":"44589259","wk":"1","lk":"14158","l":"Unit 3","qty":"540"}]}

                        $amount_paid = 0;

                        if ($total_placed > 0) {
                            foreach ($metadata['placement_data'] as $placement_data) {
                                $sql = sprintf(
                                    'select  `Inventory Transaction Amount` from  `Inventory Transaction Fact`    where `Inventory Transaction Key`=%d', $placement_data['oif_key']
                                );

                                if ($result4 = $db->query($sql)) {
                                    foreach ($result4 as $row4) {
                                        $amount_paid += $row4['Inventory Transaction Amount'];
                                    }
                                }


                                $sql = sprintf(
                                    'update `Purchase Order Transaction Fact` set `Supplier Delivery Net Amount`=%.2f ,`Supplier Delivery Extra Cost Amount`=0, `Supplier Delivery Extra Cost Account Currency Amount`=0   where `Purchase Order Transaction Fact Key`=%d    ',
                                    $amount_paid,

                                    $row['Purchase Order Transaction Fact Key']

                                );

                               // print "$sql\n";
                                $db->exec($sql);
                                $sql = sprintf('insert into `ITF POTF Costing Done Bridge`  values (%d,%d)  ', $placement_data['oif_key'], $row['Purchase Order Transaction Fact Key']);

                             //   print "$sql\n";
                                $db->exec($sql);
                                // $db->exec($sql);


                            }

                        }
                    }

                }

            }


        }


        $date=$delivery->get('Supplier Delivery Placed Date');

        $delivery->fast_update(
            array(
                'Supplier Delivery State'                => 'InvoiceChecked',
                'Supplier Delivery Invoice Checked Date' => $date,
            )
        );

        $delivery->update_totals();



    }
} else {
    print "$sql\n";
    exit;
}

