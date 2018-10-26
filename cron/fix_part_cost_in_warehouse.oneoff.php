<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 July 2017 at 16:13:34 CEST, Tranava, Slovakia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.Category.php';


    $sql = sprintf(
        'SELECT `Part SKU` FROM `Part Dimension`  ORDER BY `Part SKU` desc  '
    );
 // $sql = sprintf('SELECT `Part SKU` FROM `Part Dimension`  where `Part Reference`="AWFO-67" ORDER BY `Part SKU`  desc ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);




            $part->update_products_data();
            $part->update_commercial_value();


            continue;

            $part->update_cost();
            if($part->get('Part Status')=='In Process' and count($part->get_locations())==0 ){
              //  $part->update(array('Part Cost in Warehouse'=>''),'no_history');
            }else{
             //   $part->update(array('Part Cost in Warehouse'=>$part->get('Part Cost')),'no_history');

            }

        //foreach ($part->get_products('objects') as $product){
        //    $product->update_cost();
        //}


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }




?>
