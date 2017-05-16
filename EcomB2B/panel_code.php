<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 December 2016 at 18:19:00 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

if (!isset($_REQUEST['id'])) {
    $panel_key = -1;
} else {
    $panel_key = $_REQUEST['id'];
}


$sql = sprintf(
    "SELECT `Webpage Panel Data`  FROM `Webpage Panel Dimension` WHERE `Webpage Panel Key`=%d", $panel_key
);


if ($result = $db->query($sql)) {

    if ($row = $result->fetch()) {

        echo $row['Webpage Panel Data'];
   
   }

} else {
    print_r($error_info = $db->errorInfo());
    exit;

}

?>