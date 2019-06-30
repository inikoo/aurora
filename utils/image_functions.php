<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 April 2018 at 21:00:37 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0

*/


function get_image_size($image_key, $width, $height, $mode = '') {

    $image = get_object('Image', $image_key);

    if(!$image->id){
        return ceil($width).'x'.ceil($height);
    }


    switch ($mode) {
        case 'fit_highest':
            $ratio = $image->get('Image Width') / $image->get('Image Height');

            if ($ratio > 1) {
                $height = ceil($width / $ratio);
            } else {
                $width = ceil($height * $ratio);
            }

            break;
        case 'height':

            $ratio = $image->get('Image Width') / $image->get('Image Height');
            $width = $height * $ratio;
            break;
        case 'do_not_enlarge':
            if ($image->get('Image Width') < $width) {
                $width  = $image->get('Image Width');
                $height = $image->get('Image Height');

            }
            break;

    }

    return ceil($width).'x'.ceil($height);
}
