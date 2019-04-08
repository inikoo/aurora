<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Mon 08 April  2019 12:02:47 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';


$sql = sprintf(
    'SELECT `Page Key` FROM `Page Store Dimension`   '
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $sql = sprintf(
            "INSERT INTO `Webpage Analytics Data` (`Webpage Analytics Webpage Key`) VALUES (%d)", $row['Page Key']

        );

        $db->exec($sql);

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
