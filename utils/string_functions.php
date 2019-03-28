<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 March 2019 at 23:01:28 GMT+8, Kuela Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function permutation_letter_case($input,$max_permutations=4096) {
    $input = strtolower($input);
    $results = [];
    $length = strlen($input);
    $counter = pow(2, $length);

    $permutation_index=0;
    for($i=0; $i<$counter; $i++) {
        $binaryStr = str_pad(decbin($i), $length, '0', STR_PAD_LEFT);

        $variant = '';
        for($j=0; $j<$length; $j++) {
            $variant .= ($binaryStr[$j] == '1') ? strtoupper($input[$j]) : $input[$j];
        }
        $results[] = $variant;
        
        $permutation_index++;
        
        if($permutation_index>$max_permutations){
            break;
        }
        
    }

    return $results;
}