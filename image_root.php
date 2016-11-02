<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 11 January 2016 at 11:09:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


require_once 'common.php';

//if (!$user->can_view('account')) {
//	header('HTTP/1.0 403 Forbidden');
//	echo _('Forbidden');
//	exit;
//}


if (!isset($_REQUEST['id'])) {
    $image_key = -1;
} else {
    $image_key = $_REQUEST['id'];
}


if (isset($_REQUEST['size']) and preg_match(
        '/^large|small|thumbnail|tiny$/', $_REQUEST['size']
    )
) {
    $size = $_REQUEST['size'];
} else {
    $size = 'original';
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
        echo "Attachment not found";

        exit;
    }


} else {
    print_r($error_info = $db->errorInfo());
    exit;

}


?>
