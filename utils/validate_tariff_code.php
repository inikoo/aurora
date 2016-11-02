<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2016, Inikoo
 Created: 24 February 2016 at 19:21:47 GMT+8, Kuala Lumpur, Malaysia

 Version 3.0
*/


function validate_tariff_code($tariff_code, $db) {

    if (strlen($tariff_code) == 10) {
        $tariff_code = substr($tariff_code, 0, -2);
    }

    $valid = 'No';
    $sql   = sprintf(
        "SELECT count(*) AS num  FROM kbase.`Commodity Code Dimension` WHERE `Commodity Code`=%s ", prepare_mysql($tariff_code)
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            if ($row['num'] > 0) {
                $valid = 'Yes';
            }
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    return $valid;


}


?>
