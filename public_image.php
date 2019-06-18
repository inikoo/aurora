<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 January 2018 at 14:23:19 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/



require_once 'keyring/dns.php';

$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);



if (!isset($_REQUEST['id'])) {
    $image_subject_key = -1;
} else {
    $image_subject_key = $_REQUEST['id'];
}



$sql = sprintf(
    "SELECT `Image Data`,`Image Thumbnail Data`,`Image Small Data`,`Image Large Data`,`Image File Format`,`Image Filename` FROM `Image Subject Bridge`  left join  `Image Dimension`  I on  (I.`Image Key`=`Image Subject Image Key`) WHERE `Image Subject Key`=%d  and `Image Subject Is Public`='Yes' ", $image_subject_key
);


if ($result = $db->query($sql)) {

    if ($row = $result->fetch()) {

        header('Content-type: image/'.$row['Image File Format']);
        header('Content-Disposition: inline; filename='.$row['Image Filename']);

        echo $row['Image Data'];

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
