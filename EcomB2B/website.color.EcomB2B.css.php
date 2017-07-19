<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 July 2017 at 16:31:39 CEST, Vienna, Austria
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';







$smarty->assign('primary_color',$website->get('Website Primary Color'));
$smarty->assign('primary_color_darker',adjustBrightness($website->get('Website Primary Color'),-30));
$smarty->assign('primary_color_lighter',adjustBrightness($website->get('Website Primary Color'),+30));




$smarty->assign('secondary_color',$website->get('Website Secondary Color'));
$smarty->assign('secondary_color_darker',adjustBrightness($website->get('Website Secondary Color'),-20));

header("Content-type: text/css");

$smarty->display($theme.'/color.'.$theme.'.css.tpl');




function adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        $color   = hexdec($color); // Convert to decimal
        $color   = max(0,min(255,$color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }

    return $return;
}






?>