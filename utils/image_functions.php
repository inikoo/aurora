<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 April 2018 at 21:00:37 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0

*/


function create_cached_image($image_key, $width, $height ,$mode='') {

    $cached_image = '';

    if (is_writable('EcomB2B/server_files/cached_images/') and is_writable('EcomB2B/server_files/tmp/')) {


        require_once 'external_libs/ImageCache.php';
        $imagecache                         = new ImageCache();
        $imagecache->cached_image_directory = 'EcomB2B/server_files/cached_images/';


        $image          = get_object('Image', $image_key);


        if($mode=='fit_highest'){
            $ratio=$image->get('Image Width')/$image->get('Image Height');

            if($ratio>1){
                $height=ceil($width/$ratio);
            }else{
                $width=ceil($height*$ratio);
            }
        }elseif($mode=='do_not_enlarge'){

            if($image->get('Image Width')<$width){
                $width=$image->get('Image Width');
                $height=$image->get('Image Height');

            }
        }



        $_size_image_product_webpage = $width.'_'.$height;


        $image_format = 'jpeg';
        if (file_exists('EcomB2B/server_files/cached_images/'.md5($image_key.'_'.$_size_image_product_webpage.'.'.$image_format).'.'.$image_format)) {
            $cached_image = 'server_files/cached_images/'.md5($image_key.'_'.$_size_image_product_webpage.'.'.$image_format).'.'.$image_format;
        } else {

            $image_filename = 'EcomB2B/server_files/tmp/'.$image_key.'_'.$_size_image_product_webpage.'.'.$image_format;

            if (!file_exists($image_filename)) {

                $_image = $image->fit_to_canvas($width, $height);

                if ($image->get('Image File Format') == 'png') {
                    $image->save_image_to_file_as_jpeg('EcomB2B/server_files/tmp', $image_key.'_'.$_size_image_product_webpage, $_image, $image_format);
                } else {
                    $image->save_image_to_file('EcomB2B/server_files/tmp', $image_key.'_'.$_size_image_product_webpage, $_image, $image_format);

                }
            }


            $cached_image = $imagecache->cache($image_filename);


            unlink($image_filename);
        }


    }
    $cached_image=preg_replace('/^.*EcomB2B\//','',$cached_image);
    return $cached_image;
}


?>