<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12:52 pm Monday, 13 July 2020 (MYT) Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/
require_once __DIR__.'/cron_common.php';
require_once 'class.Part.php';
require_once 'class.Location.php';
require_once 'class.PartLocation.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (Fix duplicated picking locations)'
);


$note = 'fire 4th July';

$warehouse=get_object('Warehouse',1);

$counter = 0;




$sql = "select `Part SKU` from `Part Dimension` ";

//print $row[1]."\n";

$stmt2 = $db->prepare($sql);
$stmt2->execute();
while ($row2 = $stmt2->fetch()) {

    $part=get_object('Part',$row2['Part SKU']);
    $part_locations = $part->get_locations('part_location_object', 'stock');

  //  print_r($part_locations);

    $has_picking_location = false;
    $number_picking_locations=0;
    $number_part_locations=0;

    foreach ($part_locations as $part_location) {
        $number_part_locations++;
        if ($part_location->get('Can Pick') == 'Yes') {
            $has_picking_location = true;
            $number_picking_locations++;
            break;
        }
    }

    if($number_part_locations>0 and $number_picking_locations!=1){

        print $part->get('Part Reference')." $number_part_locations $number_picking_locations\n";

        if($number_picking_locations==0){
            foreach ($part_locations as $part_location) {
                if ($part_location->location->id != $warehouse->get('Warehouse Unknown Location Key')) {
                    $part_location->update(array('Can Pick' => 'Yes'));
                    break;
                }
            }
        }else{
            exit('todo remove multiple picking locations');

        }

    }



}
