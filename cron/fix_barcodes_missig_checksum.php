<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created 31 October 2017 at 12:54:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.Category.php';
include_once 'class.Barcode.php';



$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension`  WHERE `Part Barcode Number Error`="Checksum_missing"  '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = new Part($row['Part SKU']);

        $value=$part->get('Part Barcode Number');


        $digits = substr($value, 0, 12);

        $digits         = (string)$digits;
        $even_sum       = $digits{1} + $digits{3} + $digits{5} + $digits{7} + $digits{9} + $digits{11};
        $even_sum_three = $even_sum * 3;
        $odd_sum        = $digits{0} + $digits{2} + $digits{4} + $digits{6} + $digits{8} + $digits{10};
        $total_sum      = $even_sum_three + $odd_sum;
        $next_ten       = (ceil($total_sum / 10)) * 10;
        $check_digit    = $next_ten - $total_sum;
        $new_value=$value.$check_digit;

        print $part->get('Reference')." $value $new_value\n";
        $part->update(array('Part Barcode'=>$new_value),'no_history ignore_reserved');
//       exit;

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}



?>
