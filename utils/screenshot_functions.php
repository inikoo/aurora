<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 206-07-2019 00:39:33 MYT Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 2.0

*/


use Gumlet\ImageResize;
use Spatie\ImageOptimizer\OptimizerChainFactory;

function process_screenshot($webpage,$filename,$type){
    $size_data = getimagesize($filename);
    $width  = $size_data[0];
    $height = $size_data[1];

    $resized_image_filename=preg_replace('/original/','resize',$filename);

    switch ($type){
        case 'Full Webpage':
            $width_resize  = $width*.52;
            $height_resize =  $height*.52;
            break;
        case 'Full Webpage Thumbnail':

            $resized_image_filename=preg_replace('/original/','resize_thumbnail',$filename);
            $ratio=$height/$width;

            $width_resize  = 270;
            $height_resize =  270*$ratio;


            break;

        case 'Desktop':

            $ratio=$width/$height;

            $width_resize  = 270;
            $height_resize =  270*$ratio;
            break;
        case 'Tablet':

            $ratio=$width/$height;

            $width_resize  = 270;
            $height_resize =  270*$ratio;
            break;
        case 'Mobile':

            $ratio=$width/$height;
            $height_resize =  270;
            $width_resize  = 270/$ratio;

            break;
    }

    $image              = new ImageResize($filename);
    $image->quality_jpg = 100;
    $image->quality_png = 9;

    $image->resizeToBestFit($width_resize,$height_resize);


    $image->save($resized_image_filename);



    if (file_exists($resized_image_filename)) {
        usleep(  1000 );
    }
    if (file_exists($resized_image_filename)) {
        usleep(  2000 );
    }
    if (file_exists($resized_image_filename)) {
        usleep(  3000 );
    }
    if (file_exists($resized_image_filename)) {
        usleep(  100000 );
    }



    $optimizerChain = OptimizerChainFactory::create();
    $optimizerChain->optimize($resized_image_filename);

    if (file_exists($resized_image_filename)) {
        usleep(  1000 );
    }
    if (file_exists($resized_image_filename)) {
        usleep(  2000 );
    }
    if (file_exists($resized_image_filename)) {
        usleep(  3000 );
    }
    if (file_exists($resized_image_filename)) {
        usleep(  100000 );
    }


    $image = $webpage->add_image(
        array(
            'Image Filename'                   => $filename,
            'Upload Data'                      => array(
                'tmp_name' => $resized_image_filename,
                'type'     => 'jpeg'
            ),
            'Image Subject Object Image Scope' => $type.' Screenshot'

        )
    );

    if (file_exists($resized_image_filename)) {
        unlink($resized_image_filename);
    }
    return $image;


}


