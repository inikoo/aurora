<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 September 2016 at 11:41:21 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.Category.php';


$print_est = true;


update_parts_data($db);
//update_part_categories_data($db, $print_est);

function update_parts_data($db) {

    $sql = sprintf(
        'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Reference`="AFP01" ORDER BY `Part SKU` DESC  '
    );
    $sql = sprintf('SELECT `Part SKU` FROM `Part Dimension`  where `Part Status`="In Use"  ORDER BY `Part Reference`   ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);

            print $part->get('Reference')."\n";

           // foreach($part->get_locations('part_location_object') as $pl) {
           //     $pl->update_stock();
           // }

            $part->update_stock();
            //$part->update_available_forecast();
            //$part->update_stock_status();



           // $part->update_products_data();
          //  $part->update_weight_status();

            //$part->update_number_locations();
            //$part->update_cost();
          //  $part->update_next_deliveries_data();


            /*
                        $part->activate();


                       // $part->update_cost();
                        $part->update_products_data();
                        $part->update_history_records_data();
                        $part->update_attachments_data();
                        $part->update_images_data();



            $sql = sprintf(
                "UPDATE `Barcode Asset Bridge` SET `Barcode Asset Status`='Historic',`Barcode Asset Withdrawn Date`=NOW() WHERE `Barcode Asset Status`='Assigned' AND `Barcode Asset Type`='Part' AND `Barcode Asset Key`=%d AND `Barcode Asset Barcode Key`!=%d ;",
                $part->id, $part->get('Part Barcode Key')
            );


            //  print "$sql\n";

            $db->exec($sql);



            $part->validate_barcode();

            if ($part->get('Part Cost') <= 0 and $part->get('Part Status') != 'Not In Use') {
                //   print $part->get('Reference')." ".$part->get('Part Cost')."\n";


            }

   */
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }
}


function update_part_categories_data($db, $print_est) {

    $where = " where `Category Key`=28380 ";
    $where = "where true";

    $sql = sprintf(
        "select count(distinct `Category Key`) as num from `Category Dimension` $where and  `Category Scope`='Part' "
    );

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
        "select `Category Key` from `Category Dimension` $where and  `Category Scope`='Part'  "
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $category = new Category($row['Category Key']);

            $category->update_part_category_status();

            $category->update_history_records_data();
            $category->update_attachments_data();
            $category->update_images_data();

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
}


?>
