<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 June 2017 at 14:43:54 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.Category.php';


$print_est = true;


$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Reference`="FO-P7" ORDER BY `Part SKU` DESC  '
);
$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension`  ORDER BY `Part SKU`  DESC '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = new Part($row['Part SKU']);

        $cartons = '';
        foreach ($part->get_supplier_parts('objects') as $sp) {
            if ($sp->get('Supplier Part Status') == 'Available' and $sp->get('Supplier Part Packages Per Carton') > 0) {
                $cartons = $sp->get('Supplier Part Packages Per Carton');
            }

        }

        if (!$cartons) {
            foreach ($part->get_supplier_parts('object') as $sp) {
                if ($sp->get('Supplier Part Packages Per Carton') > 0) {
                    $cartons = $sp->get('Supplier Part Packages Per Carton');
                }

            }

        }
        if ($cartons > 1) {

            print $part->get('Reference').' '.$cartons."\n";
        }
        $part->update(array('Part SKOs per Carton' => $cartons), 'no_history');

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
