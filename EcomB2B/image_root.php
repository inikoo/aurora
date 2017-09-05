<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 January 2016 at 11:09:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/







require_once 'keyring/dns.php';

$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


if (!isset($_REQUEST['id'])) {
    $image_key = -1;
} else {
    $image_key = $_REQUEST['id'];
}


if (isset($_REQUEST['size']) and preg_match('/^large|small|thumbnail|tiny$/', $_REQUEST['size'])) {
    $size = $_REQUEST['size'];
} else {
    $size = 'original';
}

if(isset($_REQUEST['r'])){

    include_once 'class.Image.php';
    $image=new Image($image_key);


    list($w,$h)=preg_split('/x/',$_REQUEST['r']);

    $new_image=$image->fit_to_canvas($w,$h);
    header('Content-type: image/'.$image->get('Image File Format'));
    header('Content-Disposition: inline; filename='.$image->get('Image Filename'));
    ImagePNG($new_image);
    exit;
}


$sql = sprintf(
    "SELECT `Image Data`,`Image Thumbnail Data`,`Image Small Data`,`Image Large Data`,`Image File Format`,`Image Filename` FROM `Image Dimension` WHERE `Image Key`=%d", $image_key
);





if ($result = $db->query($sql)) {

    if ($row = $result->fetch()) {


        header('Content-type: image/'.$row['Image File Format']);
        header('Content-Disposition: inline; filename='.$row['Image Filename']);

        if ($size == 'original') {
            echo $row['Image Data'];
        } elseif ($size == 'large') {
            if (!$row['Image Large Data']) {
                echo $row['Image Data'];
            } else {
                echo $row['Image Large Data'];
            }
        } elseif ($size == 'small') {
            if (!$row['Image Small Data']) {
                echo $row['Image Data'];
            } else {
                echo $row['Image Small Data'];
            }
        } elseif ($size == 'thumbnail' or $size == 'tiny') {
            if ($row['Image Thumbnail Data']) {
                echo $row['Image Thumbnail Data'];
            } elseif ($row['Image Small Data']) {
                echo $row['Image Small Data'];
            } else {
                echo $row['Image Data'];
            }
        } else {
            echo $row['Image Data'];
        }


    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Image not found";

        exit;
    }


} else {
    print_r($error_info = $db->errorInfo());
    exit;

}


?>
