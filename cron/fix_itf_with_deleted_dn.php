<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 October 2017 at 17:28:35 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';



require_once 'utils/aes.php';


require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';

require_once 'class.Account.php';

require_once 'class.Customer.php';
require_once 'class.Store.php';
require_once 'class.Warehouse.php';
require_once 'class.Part.php';
require_once 'class.Material.php';
require_once 'class.Page.php';

require_once 'class.Product.php';
include_once 'utils/parse_materials.php';
include_once 'utils/object_functions.php';
include_once 'class.PartLocation.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$account = new Account();


print "A=======\n";
$sql = 'SELECT  ITF.`Delivery Note Key` as x , DN.`Delivery Note Key` as y ,`Date`,`Inventory Transaction Key`,`Location Key`,ITF.`Part SKU`,ITF.`Delivery Note Key`,`Note`,`Inventory Transaction Type`,`Inventory Transaction Section` FROM `Inventory Transaction Fact` ITF LEFT JOIN `Delivery Note Dimension` DN ON (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) 
        WHERE    ITF.`Delivery Note Key`>0 AND DN.`Delivery Note Key` IS NULL;';

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

       // print_r($row);

       // continue;


        $part     = get_object('Part', $row['Part SKU']);
        $location = get_object('Location', $row['Location Key']);


        $part_location = new PartLocation($row['Part SKU'].'_'.$row['Location Key']);


        $sql = sprintf('delete from `Inventory Transaction Fact` where `Inventory Transaction Key`=%d', $row['Inventory Transaction Key']);



        $db->exec($sql);
        if ($part_location->ok) {
            $part_location->update_stock();
        } else {

            $part->update_stock();
            $location->update_stock_value();


            foreach (
                $part->get_production_suppliers('objects') as $production
            ) {
                $production->update_locations_with_errors();
            }


        }


    }
}

print "B=======\n";

$sql =
    "select `Date`,`Inventory Transaction Key`,`Location Key`,ITF.`Part SKU`,ITF.`Delivery Note Key`,`Note`,`Amount In`,`Inventory Transaction Type`,`Inventory Transaction Section`,`Delivery Note State`,`Inventory Transaction Quantity` from `Inventory Transaction Fact` ITF left join `Delivery Note Dimension` DN on (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) 
      where  `Inventory Transaction Section`='OIP'  and `Delivery Note State` in ('Cancelled') ;";


//print $sql;
print "C=======\n";

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        print_r($row);
        //continue;
        $part     = get_object('Part', $row['Part SKU']);
        $location = get_object('Location', $row['Location Key']);


        $part_location = new PartLocation($row['Part SKU'].'_'.$row['Location Key']);


        // print_r($row);

        $sql = sprintf(
            'update  `Inventory Transaction Fact` set 
                        `Inventory Transaction Type` = "FailSale",`Inventory Transaction Section`="NoDispatched" 
                          where `Inventory Transaction Key`=%d',


            $row['Inventory Transaction Key']
        );


        //   print "$sql\n";


        $db->exec($sql);
        if ($part_location->ok) {
            // $part_location->redo_adjusts();
            $part_location->update_stock();

        } else {

            $part->update_stock();
            $location->update_stock_value();


            foreach (
                $part->get_production_suppliers('objects') as $production
            ) {
                $production->update_locations_with_errors();
            }


        }
    }
}


$sql =
"select `Delivery Note Assigned Picker Key`,`Delivery Note Assigned Packer Key`, `Required` ,`Delivery Note Date Finish Packing`,`Delivery Note Date Finish Picking`,`Date`,`Inventory Transaction Key`,`Location Key`,ITF.`Part SKU`,ITF.`Delivery Note Key`,`Note`,`Amount In`,`Inventory Transaction Type`,`Inventory Transaction Section`,`Delivery Note State`,`Inventory Transaction Quantity` from `Inventory Transaction Fact` ITF left join `Delivery Note Dimension` DN on (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) where  `Inventory Transaction Section`='OIP'  and `Delivery Note State` in ('Approved','Packed Done','Packed','Dispatched') ;";


//print $sql;

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        print_r($row);

        $part     = get_object('Part', $row['Part SKU']);
        $location = get_object('Location', $row['Location Key']);


        $part_location = new PartLocation($row['Part SKU'].'_'.$row['Location Key']);


        $sql = sprintf('delete from `Inventory Transaction Fact` where `Inventory Transaction Key`=%d', $row['Inventory Transaction Key']);
        $db->exec($sql);
        if ($part_location->ok) {
            $part_location->update_stock();
        } else {

            $part->update_stock();
            $location->update_stock_value();


            foreach (
                $part->get_production_suppliers('objects') as $production
            ) {
                $production->update_locations_with_errors();
            }


        }

    }
}



