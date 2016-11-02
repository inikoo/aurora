<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 June 2016 at 10:53:19 CEST, Mijas Costa, Spain

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include 'pcommon.php';
include_once 'class.Image.php';

if (preg_match('/(.*)\/(.+)\/(.+)-(\d+)-(\d+x\d+)\.(png|jpg)$/', $_SERVER['REQUEST_URI'], $matches)) {

    $scope       = $matches[2];
    $code        = $matches[3];
    $image_index = $matches[4];
    $size        = $matches[5];
    $type        = $matches[6];

    switch ($scope) {
        case 'products':
            include_once 'class.Product.php';
            $product   = new Product('store_code', $website->get('Store Key'), $code);
            $image_key = $product->get_image_key($image_index);

            $image = new Image($image_key);


            break;
        default:

            break;
    }


    if ($image->id) {

        $_size = preg_split('/x/', $size);

        $im = $image->get_resized($_size[0], $_size[1]);


        header('Content-Type: image/jpeg');

        imagejpeg($im);

        imagedestroy($im);
        exit;

    } else {
        if (in_array(
            $size, array(
            '600x450',
            '500x375'
        )
        )) {
            $size = '-'.$size;
        } else {
            $size = '';
        }


        $name = "art/ecom/nopic$size.".$type;
        $fp   = fopen($name, 'rb');


        // send the right headers
        header("Content-Type: image/".$type);
        header("Content-Length: ".filesize($name));

        // dump the picture and stop the script
        fpassthru($fp);
        exit;

    }

}

?>
