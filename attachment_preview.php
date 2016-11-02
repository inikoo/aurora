<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:9 December 2015 at 12:32:58 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


require_once 'common.php';
require_once 'utils/authorize_file_view.php';

if (!isset($_REQUEST['id'])) {
    $id = -1;
} else {
    $id = $_REQUEST['id'];
}


if (isset($_REQUEST['size']) and preg_match(
        '/^(large|small|thumbnail|tiny|original)$/', $_REQUEST['size']
    )
) {
    $size = $_REQUEST['size'];
} else {
    $size = 'large';
}


$attachement_not_found_image = 'art/error_404.png';
$no_preview_image            = 'art/attachment_no_preview.png';
$forbidden_image             = 'art/error_403.jpg';


$sql = sprintf(
    "SELECT `Attachment Public`,`Subject`,`Subject Key`,B.`Attachment Key`,`Attachment Thumbnail Image Key` FROM `Attachment Bridge` B LEFT JOIN  `Attachment Dimension` A ON (A.`Attachment Key`= B.`Attachment Key`) WHERE `Attachment Bridge Key`=%d",
    $id
);


if ($result = $db->query($sql)) {

    if ($row = $result->fetch()) {


        if ($row['Attachment Thumbnail Image Key']) {

            if (authorize_file_view(
                $db, $user, $row['Attachment Public'], $row['Subject'], $row['Subject Key']
            )) {

                display_database_image(
                    $db, $row['Attachment Thumbnail Image Key']
                );
            } else {

                header('Content-type:image/jpg');
                readfile($forbidden_image);
                exit;
            }

        } else {
            header('Content-type:image/png');
            readfile($no_preview_image);
            exit;
        }


    } else {
        header('Content-type:image/png');
        readfile($attachement_not_found_image);
        exit;
    }


} else {
    print_r($error_info = $db->errorInfo());
    exit;

}


function display_database_image($db, $image_key, $size = 'small') {

    $sql = sprintf(
        'SELECT `Image Original Filename`,`Image Data`,`Image Small Data`,`Image Large Data`,`Image Thumbnail Data` FROM `Image Dimension` WHERE `Image Key`=%d', $image_key
    );
    if ($result = $db->query($sql)) {

        if ($row = $result->fetch()) {

            header('Content-type: image/jpeg');
            header(
                'Content-Disposition: inline; filename='.$row['Image Original Filename']
            );
            //readfile($row['Attachment Filename']);
            // echo  $row['Image Data'];
            // var_dump(  $row) ;

            //exit;

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
                echo $row['Image Thumbnail Data'];

            } else {
                echo $row['Image Data'];

            }

        } else {
            header("HTTP/1.0 404 Not Found");
            exit();

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;

    }


}


?>
