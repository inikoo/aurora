<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2019 at 17:20:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.Category.php';

$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System',
    'Author Alias' => 'System (Migrate SKO per carton)',


);


$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension`   ORDER BY `Part SKU`  DESC '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = new Part($row['Part SKU']);

        if($part->get('Part Carton Barcode')!=''){
            $supplier_parts = $part->get_supplier_parts('objects');
            foreach($supplier_parts as $supplier_part){
                $supplier_part->fast_update(array('Supplier Part Carton Barcode'=>$part->get('Part Carton Barcode')));
            }
        }


        if($part->get('Part Recommended Packages Per Selling Outer')==''){
            $part->fast_update(array('Part Recommended Packages Per Selling Outer'=>1));

        }


    }
}

$print_est = true;


$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension` where  `Part Main Supplier Part Key` is  null  ORDER BY `Part SKU`  DESC '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = new Part($row['Part SKU']);

        $part->fast_update_json_field('Part Properties', preg_replace('/\s/', '_', 'old_sko_carton'), $part->get('Part SKOs per Carton'));

        $part->editor   = $editor;
        $supplier_parts = $part->get_supplier_parts('objects');


        if (count($supplier_parts) == 0) {
            continue;
        }
        if (count($supplier_parts) == 1) {

            $supplier_part = array_pop($supplier_parts);
            $part->update(array('Part Main Supplier Part Key' => $supplier_part->id));
            continue;
        }

        $available = array();
        foreach ($supplier_parts as $supplier_part) {
            if ($supplier_part->get('Supplier Part Status') == 'Available') {
                $available[] = $supplier_part;
            }

        }

        if (count($available) == 1) {
            $supplier_part = array_pop($available);
            $part->update(array('Part Main Supplier Part Key' => $supplier_part->id));
            continue;
        }

        if (count($available) == 0) {
            $available = $supplier_parts;
        }

        $tmp = array();
        foreach ($available as $supplier_part) {
            $tmp[] = $supplier_part->id;
        }

        $sql = sprintf(
            'select `Purchase Order Last Updated Date`,`Supplier Part Key` from `Purchase Order Transaction Fact`  where `Supplier Part Key` in (%s)  order by `Purchase Order Last Updated Date`  desc limit 1 ',

            join(',', $tmp)
        );
        // print "$sql\n";
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                //print_r($row);

                $part->update(array('Part Main Supplier Part Key' => $row['Supplier Part Key']));
                continue 2;

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $part->update(array('Part Main Supplier Part Key' => array_pop($tmp)));


    }

}