<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20-06-2019 23:08:34 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/
// Note this is not the same as the other wi.php
include_once 'keyring/dns.php';
chdir('../');


if (empty($_REQUEST['id']) or !is_numeric($_REQUEST['id']) or $_REQUEST['id'] <= 0) {
    header("HTTP/1.0 404 Not Found");
    echo "Image not found (invalid id)";
    exit();
} else {
    $image_key = $_REQUEST['id'];
}


if (!empty($_REQUEST['s'])) {
    $size_r = $_REQUEST['s'];

    if (!preg_match('/^\d+x\d+$/', $size_r)) {
        $size_r = '';
    }


} else {
    $size_r = '';
}

$redis = new Redis();
if ($redis->connect('127.0.0.1', 6379)) {
    $redis_on = true;
} else {
    $redis_on = false;
}

//$cache_file = 'image_cache/'.$image_key.'_'.$size.($size_r!=''?'_'.$size_r:'');

$image_code = 'pi.'.DNS_ACCOUNT_CODE.'.'.$image_key.'_'.$size_r;

if ($redis->exists($image_code)) {


    list($image_filename, $mime_type) = json_decode($redis->get($image_code), true);


    if (file_exists($image_filename)) {
        header("Content-type: $mime_type");
        $seconds_to_cache = 43200000;
        $ts               = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache)." GMT";

        header("Expires: $ts");
        header("Pragma: cache");
        header("Cache-Control: max-age=$seconds_to_cache");
        header('Content-Length: '.filesize($image_filename));


        readfile($image_filename);


        exit();
    }

}
require_once 'vendor/autoload.php';

use \Gumlet\ImageResize;
use Spatie\ImageOptimizer\OptimizerChainFactory;


$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$sql  = sprintf('select `Image Path`,`Image MIME Type`,`Image File Checksum` from `Image Dimension` where `Image Key`=? ');
$stmt = $db->prepare($sql);


$stmt->execute([$image_key]);


if ($row = $stmt->fetch()) {


    $image_path = preg_replace('/img\/db/', 'img/public_db/', $row['Image Path']);


    if (!file_exists($image_path)) {
        header('HTTP/1.0 403 Forbidden');
        echo _('Forbidden');
        exit;
    }


    $image_mime = $row['Image MIME Type'];

    $cached_image_path = preg_replace('/^img\/public_db/', 'img/public_cache', $image_path);
    $cached_image_path = preg_replace('/\./', '_'.$size_r.'.', $cached_image_path);

    if ($size_r != '') {
        list($w, $h) = preg_split('/x/', $size_r);
        $image              = new ImageResize($image_path);
        $image->quality_jpg = 100;
        $image->quality_png = 9;

        $image->resizeToBestFit($w, $h);


        if (!is_dir('img/public_cache/'.$row['Image File Checksum'][0])) {
            mkdir('img/public_cache/'.$row['Image File Checksum'][0]);
        }


        if (!is_dir('img/public_cache/'.$row['Image File Checksum'][0].'/'.$row['Image File Checksum'][1])) {
            mkdir('img/public_cache/'.$row['Image File Checksum'][0].'/'.$row['Image File Checksum'][1]);
        }


        $image->save($cached_image_path);

        if (file_exists($cached_image_path)) {
            usleep(1000);
        }
        if (file_exists($cached_image_path)) {
            usleep(2000);
        }
        if (file_exists($cached_image_path)) {
            usleep(3000);
        }
        if (file_exists($cached_image_path)) {
            usleep(100000);
        }
    } else {
        copy($image_path, $cached_image_path);
    }


    $optimizerChain = OptimizerChainFactory::create();
    $optimizerChain->optimize($cached_image_path);

    if (file_exists($cached_image_path)) {
        usleep(1000);
    }
    if (file_exists($cached_image_path)) {
        usleep(2000);
    }
    if (file_exists($cached_image_path)) {
        usleep(3000);
    }
    if (file_exists($cached_image_path)) {
        usleep(100000);
    }




    $redis->set(
        $image_code, json_encode(
                       array(
                           $cached_image_path,
                           $image_mime
                       )
                   )
    );


    if (file_exists($cached_image_path)) {

        header("Content-type: ".$image_mime);
        $seconds_to_cache = 43200000;
        $ts               = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache)." GMT";
        header("Expires: $ts");
        header("Pragma: cache");
        header("Cache-Control: max-age=$seconds_to_cache");
        header('Content-Length: '.filesize($cached_image_path));

        readfile($cached_image_path);


    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Image not found";
        exit();
    }

    //print $cached_image_path;
    // exit;


} else {
    header("HTTP/1.0 404 Not Found");
    echo "Image not found";
    exit();
}


