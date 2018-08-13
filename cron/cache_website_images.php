<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 September 2017 at 22:39:29 GMT+8,  Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/object_functions.php';
require_once 'external_libs/ImageCache.php';


$imagecache                         = new ImageCache();
$imagecache->cached_image_directory = 'EcomB2B/server_files/cached_images/';


$sql = sprintf("SELECT `Product ID` FROM `Product Dimension` ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $product = get_object('Product', $row['Product ID']);

        $image_key = $product->get('Product Main Image Key');

        if ($image_key) {


            $_size_image_product_webpage = '340_214';
            $image_format                = 'jpeg';
            if (!file_exists('EcomB2B/server_files/cached_images/'.md5($image_key.'_'.$_size_image_product_webpage.'.'.$image_format).'.'.$image_format)) {

                $image          = get_object('Image', $image_key);
                $image_filename = 'EcomB2B/server_files/tmp/'.$image_key.'_'.$_size_image_product_webpage.'.'.$image_format;

                if (!file_exists($image_filename)) {

                    $_image = $image->fit_to_canvas(340, 214);
                    if ($image->get('Image File Format') == 'png') {
                        $image->save_image_to_file_as_jpeg('EcomB2B/server_files/tmp', $image_key.'_'.$_size_image_product_webpage, $_image, $image_format);
                    } else {
                        $image->save_image_to_file('EcomB2B/server_files/tmp', $image_key.'_'.$_size_image_product_webpage, $_image, $image_format);
                    }


                }
                $image_product_webpage = $imagecache->cache($image_filename);
                unlink($image_filename);


            }


            $_size_image_product_webpage = '120_120';
            $image_format                = 'jpeg';
            if (!file_exists('EcomB2B/server_files/cached_images/'.md5($image_key.'_'.$_size_image_product_webpage.'.'.$image_format).'.'.$image_format)) {

                $image          = get_object('Image', $image_key);
                $image_filename = 'EcomB2B/server_files/tmp/'.$image_key.'_'.$_size_image_product_webpage.'.'.$image_format;

                if (!file_exists($image_filename)) {

                    $_image = $image->fit_to_canvas(120, 120);
                    if ($image->get('Image File Format') == 'png') {
                        $image->save_image_to_file_as_jpeg('EcomB2B/server_files/tmp', $image_key.'_'.$_size_image_product_webpage, $_image, $image_format);
                    } else {
                        $image->save_image_to_file('EcomB2B/server_files/tmp', $image_key.'_'.$_size_image_product_webpage, $_image, $image_format);
                    }


                }
                $image_product_webpage = $imagecache->cache($image_filename);
                unlink($image_filename);


            }

        }


        $sql = sprintf(
            "SELECT `Image Subject Is Principal`,`Image Key`,`Image Subject Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` FROM `Image Subject Bridge` B LEFT JOIN `Image Dimension` I ON (`Image Subject Image Key`=`Image Key`) WHERE `Image Subject Object`=%s AND   `Image Subject Object Key`=%d ORDER BY `Image Subject Is Principal`,`Image Subject Date`,`Image Subject Key`",
            prepare_mysql('Product'), $product->id
        );


        // print $sql;

        $subject_order = 0;

        //print $sql;
        $images_slideshow = array();
        if ($result2 = $db->query($sql)) {
            foreach ($result2 as $row2) {

                if ($row2['Image Key']) {

                    $image_format = 'jpeg';

                    $image_key = $row2['Image Key'];

                    $_size_image_product_webpage = '600_375';


                    if (!file_exists('EcomB2B/server_files/cached_images/'.md5($image_key.'_'.$_size_image_product_webpage.'.'.$image_format).'.'.$image_format)) {

                        $image = get_object('Image', $image_key);

                        $image_filename = 'EcomB2B/server_files/tmp/'.$image_key.'_'.$_size_image_product_webpage.'.'.$image_format;

                        if (!file_exists($image_filename)) {

                            $_image = $image->fit_to_canvas(600, 375);

                            if ($image->get('Image File Format') == 'png') {
                                $image->save_image_to_file_as_jpeg('EcomB2B/server_files/tmp', $image_key.'_'.$_size_image_product_webpage, $_image, $image_format);
                            } else {
                                $image->save_image_to_file('EcomB2B/server_files/tmp', $image_key.'_'.$_size_image_product_webpage, $_image, $image_format);

                            }


                        }
                        $image_product_webpage = $imagecache->cache($image_filename);
                        unlink($image_filename);
                    }


                }

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql";
            exit;
        }


    }
}


$sql = sprintf('SELECT  `Category Main Image Key` FROM   `Product Category Dimension` PC LEFT JOIN    `Category Dimension` C    ON (PC.`Product Category Key`=C.`Category Key`) ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $image_key = $row['Category Main Image Key'];

        if ($image_key) {


            $_size_image_product_webpage = '120_120';
            $image_format                = 'jpeg';
            if (!file_exists('EcomB2B/server_files/cached_images/'.md5($image_key.'_'.$_size_image_product_webpage.'.'.$image_format).'.'.$image_format)) {

                $image          = get_object('Image', $image_key);
                $image_filename = 'EcomB2B/server_files/tmp/'.$image_key.'_'.$_size_image_product_webpage.'.'.$image_format;

                if (!file_exists($image_filename)) {

                    $_image = $image->fit_to_canvas(120, 120);
                    if ($image->get('Image File Format') == 'png') {
                        $image->save_image_to_file_as_jpeg('EcomB2B/server_files/tmp', $image_key.'_'.$_size_image_product_webpage, $_image, $image_format);
                    } else {
                        $image->save_image_to_file('EcomB2B/server_files/tmp', $image_key.'_'.$_size_image_product_webpage, $_image, $image_format);
                    }


                }
                $image_product_webpage = $imagecache->cache($image_filename);
                unlink($image_filename);


            }

        }


    }
}


?>
