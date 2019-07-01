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

    if (!$image->id) {
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


function fit_to_canvas($image, $canvas_w, $canvas_h) {

    $image_data = get_image_from_file($image->get('Image File Format'),$image->get('Image Path'));

    $w = $image->data['Image Width'];
    $h = $image->data['Image Height'];

    $r = $w / $h;


    if ($canvas_h == 0) {
        sdsdsd();
        exit;
    }

    $r_canvas = $canvas_w / $canvas_h;

    if ($r < $r_canvas) {
        $fit_h    = $canvas_h;
        $fit_w    = $w * ($fit_h / $h);
        $canvas_y = 0;
        $canvas_x = ($canvas_w - $fit_w) / 2;
    } elseif ($r > $r_canvas) {
        $fit_w = $canvas_w;
        $fit_h = $h * ($fit_w / $w);

        $canvas_x = 0;
        $canvas_y = ($canvas_h - $fit_h) / 2;
    } else {
        $fit_h    = $canvas_h;
        $fit_w    = $canvas_w;
        $canvas_x = 0;
        $canvas_y = 0;

    }


    $canvas = imagecreatetruecolor($canvas_w, $canvas_h);
    $white  = imagecolorallocate($canvas, 255, 255, 255);
    imagefill($canvas, 0, 0, $white);

    imagecopyresampled($canvas, imagecreatefromstring($image_data), $canvas_x, $canvas_y, 0, 0, $fit_w, $fit_h, $w, $h);

    return $canvas;

}

function get_image_from_file($format, $srcImage) {


    if ($format == 'jpeg') {
        $im = imagecreatefromjpeg($srcImage);
    } elseif ($format == 'png') {
        $im = imagecreatefrompng($srcImage);
        imagealphablending($im, true);
        imagesavealpha($im, true);
    } elseif ($format == 'gif') {
        $im = imagecreatefromgif($srcImage);
    } elseif ($format == 'wbmp') {
        $im = imagecreatefromwbmp($srcImage);
    } elseif ($format == 'psd') {
        include_once 'class.PSD.php';
        $im = imagecreatefrompsd($srcImage);
    } else {

        return false;
    }



    return $im;

}



function is_animated_gif($filename) {
    if (!($fh = @fopen($filename, 'rb'))) {
        return false;
    }
    $count = 0;
    //an animated gif contains multiple "frames", with each frame having a
    //header made up of:
    // * a static 4-byte sequence (\x00\x21\xF9\x04)
    // * 4 variable bytes
    // * a static 2-byte sequence (\x00\x2C) (some variants may use \x00\x21 ?)

    // We read through the file til we reach the end of the file, or we've found
    // at least 2 frame headers
    while (!feof($fh) && $count < 2) {
        $chunk = fread($fh, 1024 * 100); //read 100kb at a time
        $count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches);
    }

    fclose($fh);

    return $count > 1;
}