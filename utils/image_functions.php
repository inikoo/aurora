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


function create_cached_imagex($image_key, $width, $height, $mode = '') {

    $path     = 'EcomB2B/server_files/cached_images/';
    $tmp_path = 'EcomB2B/server_files/tmp/';

    $cached_image = '';


    if (is_writable($path) and is_writable($tmp_path)) {


        require_once 'external_libs/ImageCache.php';
        $imagecache                         = new ImageCache();
        $imagecache->cached_image_directory = $path;


        $image = get_object('Image', $image_key);


        if ($mode == 'fit_highest') {
            $ratio = $image->get('Image Width') / $image->get('Image Height');

            if ($ratio > 1) {
                $height = ceil($width / $ratio);
            } else {
                $width = ceil($height * $ratio);
            }
        } elseif ($mode == 'do_not_enlarge') {

            if ($image->get('Image Width') < $width) {
                $width  = $image->get('Image Width');
                $height = $image->get('Image Height');

            }
        } elseif ($mode == 'height') {


            $ratio = $image->get('Image Width') / $image->get('Image Height');

            //print "ration $ratio  $height \n";
            $width = $height * $ratio;

        }


        // print "old size ".$image->get('Image Width')." x ".$image->get('Image Height')."\n";

        // print "new size $width x $height\n";


        $_size_image_product_webpage = $width.'_'.$height;


        $image_format = 'jpeg';
        if (file_exists($path.md5($image_key.'_'.$_size_image_product_webpage.'.'.$image_format).'.'.$image_format)) {
            $cached_image = $path.md5($image_key.'_'.$_size_image_product_webpage.'.'.$image_format).'.'.$image_format;
        } else {

            $image_filename = $tmp_path.$image_key.'_'.$_size_image_product_webpage.'.'.$image_format;

            if (!file_exists($image_filename)) {


                $_image = $image->fit_to_canvas($width, $height);

                if ($image->get('Image File Format') == 'png') {
                    $image->save_image_to_file_as_jpeg($tmp_path, $image_key.'_'.$_size_image_product_webpage, $_image, $image_format);
                } else {
                    $image->save_image_to_file($tmp_path, $image_key.'_'.$_size_image_product_webpage, $_image, $image_format);

                }
            }


            $cached_image = $imagecache->cache($image_filename);


            unlink($image_filename);
        }


    }


    $cached_image = preg_replace('/^.*EcomB2B\//', '', $cached_image);

    $cached_image = str_replace('//', '/', $cached_image);

    return $cached_image;
}


