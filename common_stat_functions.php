<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 28 March 2014 15:31:35 CET, Malaga Spain

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

function standard_deviation($aValues, $bSample = false)
{
    $fMean = array_sum($aValues) / count($aValues);
    $fVariance = 0.0;
    foreach ($aValues as $i)
    {
        $fVariance += pow($i - $fMean, 2);
    }
    $fVariance /= ( $bSample ? count($aValues) - 1 : count($aValues) );
    return (float) sqrt($fVariance);
}


?>