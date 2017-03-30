<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 March 2017 at 13:47:53 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


require_once 'common.php';



if (!isset($_REQUEST['id'])) {
    $image_key = -1;
} else {
    $image_key = $_REQUEST['id'];
}


$sql = sprintf(
    "SELECT `Website Image Key`,`Website Image Data`,`Website Image Format` FROM `Website Image Dimension` WHERE `Website Image Key`=%d", $image_key
);


if ($result = $db->query($sql)) {

    if ($row = $result->fetch()) {


        header('Content-type: image/'.$row['Website Image Format']);
        header('Content-Disposition: inline; filename='.$row['Website Image Key'].'.'.$row['Website Image Format']);

        echo $row['Website Image Data'];

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
