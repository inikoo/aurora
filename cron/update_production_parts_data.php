<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  30 January 2019 at 15:02:57 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/

require_once 'common.php';


create_supplier_production_parts($db);


$sql = "select B.`Production Part Supplier Part Key`,`Supplier Part Reference` from `Production Part Dimension` B left join `Supplier Part Dimension` S on (B.`Production Part Supplier Part Key`=S.`Supplier Part Key`) 
        where `Supplier Part Reference` like '%'
";


$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {

    $production_part = get_object('Supplier Part', $row['Production Part Supplier Part Key']);

    if ($production_part->id) {
        // print $production_part->part->get('Reference')."\n";

        $production_part->part->update_next_deliveries_data();
    }
}


function create_supplier_production_parts($db) {
    $sql  = "SELECT `Supplier Key` FROM `Supplier Production Dimension` left join `Supplier Dimension` on (`Supplier Key`=`Supplier Production Supplier Key`)";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {


        $sql   = "SELECT `Supplier Part Key` FROM `Supplier Part Dimension` where `Supplier Part Supplier Key`=?";
        $stmt2 = $db->prepare($sql);
        $stmt2->execute(
            [$row['Supplier Key']]
        );
        while ($row2 = $stmt2->fetch()) {


            $sql = "insert into `Production Part Dimension` (`Production Part Supplier Part Key`) values (?)";
            $db->prepare($sql)->execute([$row2['Supplier Part Key']]);


        }
    }
}