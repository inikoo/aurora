<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:9 December 2015 at 12:32:58 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


require_once 'common.php';
/** @var User $user */
if ($user->get('User View') != 'Staff') {
    exit;
}
require_once 'utils/authorize_file_view.php';

if (!isset($_REQUEST['id'])) {
    $id = -1;
} else {
    $id = $_REQUEST['id'];
}


$attachment_not_found_image = 'art/error_404.png';
$no_preview_image           = 'art/attachment_no_preview.png';
$forbidden_image            = 'art/error_403.jpg';


$sql = sprintf(
    "SELECT `Attachment Public`,`Subject`,`Subject Key`,B.`Attachment Key`,`Attachment Thumbnail Image Key` FROM `Attachment Bridge` B LEFT JOIN  `Attachment Dimension` A ON (A.`Attachment Key`= B.`Attachment Key`) 
        WHERE `Attachment Bridge Key`=%d",
    $id
);


/** @var PDO $db */
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        if ($row['Attachment Thumbnail Image Key']) {
            if (authorize_file_view($db, $user, $row['Attachment Public'], $row['Subject'], $row['Subject Key'])) {
                echo file_get_contents('image.php?id='.$row['Attachment Thumbnail Image Key'].(!empty($_REQUEST['s']) ? '&s='.$_REQUEST['s'] : ''));
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
        readfile($attachment_not_found_image);
        exit;
    }
}

