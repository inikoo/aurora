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
    'Author Alias' => 'System (Fix missing properties from part)',


);


$print_est = true;


$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension` ORDER BY `Part SKU`  DESC '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = get_object('Part',$row['Part SKU']);



        foreach ($part->get_products('objects') as $product) {

            $product->fast_update(
                array(
                    'Product Tariff Code'                  => $part->get('Part Tariff Code'),
                    'Product HTSUS Code'                   => $part->get('Part HTSUS Code'),
                    'Product Duty Rate'                    => $part->get('Part Duty Rate'),
                    'Product Origin Country Code'          => $part->get('Part Origin Country Code'),
                    'Product UN Number'                    => $part->get('Part UN Number'),
                    'Product UN Class'                     => $part->get('Part UN Class'),
                    'Product Packing Group'                => $part->get('Part Packing Group'),
                    'Product Proper Shipping Name'         => $part->get('Part Proper Shipping Name'),
                    'Product Hazard Identification Number' => $part->get('Part Hazard Identification Number'),
                    'Product Unit Weight'                  => $part->get('Part Unit Weight'),
                    'Product Unit Dimensions'              => $part->get('Part Unit Dimensions'),
                    'Product Materials'                    => $part->data['Part Materials'],
                    'Product Barcode Number'               => $part->data['Part Barcode Number'],
                    'Product Barcode Key'                  => $part->data['Part Barcode Key'],

                )
            );

            $product->update_updated_markers('Data');

            $sql = "SELECT `Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Object`='Part' AND `Image Subject Object Key`=? and `Image Subject Object Image Scope`='Marketing' ORDER BY `Image Subject Order` ";

            $stmt = $db->prepare($sql);
            $stmt->execute(
                array($part->id)
            );
            while ($row = $stmt->fetch()) {
                $product->link_image($row['Image Subject Image Key'], 'Marketing');
            }


        }



        /*
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
        */


    }

}


