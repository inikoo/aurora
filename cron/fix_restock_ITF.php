<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 May 2021 23:17 KL Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/


require_once __DIR__.'/cron_common.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (Fix Restock ITF)'
);

$counter = 1;


$sql  = "select  `Metadata`  from `Purchase Order Transaction Fact` where  `Purchase Order Transaction Type`='Return'";
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {

    if($row['Metadata']!='') {

        $metadata = json_decode($row['Metadata'], true);


       // print_r($metadata);
        foreach ($metadata['placement_data'] as $_data) {
            print $_data['oif_key']."\n";

            $sql="update `Inventory Transaction Fact` set `Inventory Transaction Type`='Restock' where `Inventory Transaction Key`=? ";
            $db->prepare($sql)->execute(
                array(
                    $_data['oif_key']
                )
            );
            

        }
    }

}