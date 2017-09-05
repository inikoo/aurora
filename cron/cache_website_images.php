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


$sql = sprintf("SELECT `Product ID` FROM `Product Dimension` WHERE `Product ID`=29832");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $product = get_object('Product', $row['Product ID']);

        $image_key = $product->get('Product Main Image Key');

        if ($image_key) {

            //if(file_exists('server_files/cached_images/'.md5($image_key.'_600_375.jpeg').'.jpeg' )){
            //    return 'server_files/cached_images/'.md5($image_key.'_600_375.jpeg').'.jpeg';
            //}

            $image = get_object('Image', $image_key);

            $image_filename = 'EcomB2B/server_files/tmp/'.$image_key.'_600_375.jpeg';

            if (!file_exists($image_filename)) {

                $image->save_image_to_file('EcomB2B/server_files/tmp', $image_key.'_600_375', $image->fit_to_canvas(600, 375));
            }

            $cached_image = $imagecache->cache($image_filename);

            // print $cached_image;


        }
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
