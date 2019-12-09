<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2019 at 17:20:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System',
    'Author Alias' => 'System (Fix missing dimensions from part)',


);


$print_est = true;


$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension` ORDER BY `Part SKU`  DESC '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = get_object('Part',$row['Part SKU']);


        if($part->get('Part Unit Dimensions')!=''){
            foreach ($part->get_products('objects') as $product) {

                if (count($product->get_parts()) == 1) {
                    $product->editor = $editor;



                    $product->update(
                        array('Product Unit Dimensions' => $part->get('Part Unit Dimensions')), ' from_part'
                    );
                }

            }

        }



    }

}


