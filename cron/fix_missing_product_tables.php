<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 November 2018 at 23:07:28 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$sql = sprintf(
    'SELECT `Product ID`,`Product Code`,`Product Store Key` FROM `Product Dimension` ORDER BY `Product ID`  '
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $sql = sprintf(
            "SELECT `Product ID` FROM `Product Data` WHERE `Product ID`=%d", $row['Product ID']
        );


        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {

            }else{

                $sql = sprintf(
                    "INSERT INTO `Product Data` (`Product ID`) VALUES (%d)", $row['Product ID']

                );

                $db->exec($sql);
                $sql = sprintf(
                    "INSERT INTO `Product DC Data` (`Product ID`) VALUES (%d)", $row['Product ID']

                );
                $db->exec($sql);

                print_r($row);
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
