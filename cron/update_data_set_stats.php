<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 16 August 2016 at 19:52:04 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Data_Sets.php';

$sql = sprintf("SELECT `Data Sets Key`  FROM `Data Sets Dimension` ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $data_set = new Data_Sets($row['Data Sets Key']);
        $data_set->update_stats();

    }
}


?>
