<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 December 2015 at 22:37:16 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d' ) {

    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while( $current <= $last ) {
        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}


?>
